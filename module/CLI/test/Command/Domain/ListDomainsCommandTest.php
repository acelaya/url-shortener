<?php

declare(strict_types=1);

namespace ShlinkioTest\Shlink\CLI\Command\Domain;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Shlinkio\Shlink\CLI\Command\Domain\ListDomainsCommand;
use Shlinkio\Shlink\CLI\Util\ExitCodes;
use Shlinkio\Shlink\Core\Config\NotFoundRedirects;
use Shlinkio\Shlink\Core\Domain\DomainServiceInterface;
use Shlinkio\Shlink\Core\Domain\Model\DomainItem;
use Shlinkio\Shlink\Core\Entity\Domain;
use Shlinkio\Shlink\Core\Options\NotFoundRedirectOptions;
use ShlinkioTest\Shlink\CLI\CliTestUtilsTrait;
use Symfony\Component\Console\Tester\CommandTester;

class ListDomainsCommandTest extends TestCase
{
    use CliTestUtilsTrait;

    private CommandTester $commandTester;
    private ObjectProphecy $domainService;

    public function setUp(): void
    {
        $this->domainService = $this->prophesize(DomainServiceInterface::class);
        $this->commandTester = $this->testerForCommand(new ListDomainsCommand($this->domainService->reveal()));
    }

    /**
     * @test
     * @dataProvider provideInputsAndOutputs
     */
    public function allDomainsAreProperlyPrinted(array $input, string $expectedOutput): void
    {
        $bazDomain = new Domain('baz.com');
        $bazDomain->configureNotFoundRedirects(new NotFoundRedirects(
            null,
            'https://foo.com/baz-domain/regular',
            'https://foo.com/baz-domain/invalid',
        ));

        $listDomains = $this->domainService->listDomains()->willReturn([
            DomainItem::forDefaultDomain('foo.com', new NotFoundRedirectOptions([
                'base_url' => 'https://foo.com/default/base',
                'invalid_short_url' => 'https://foo.com/default/invalid',
            ])),
            DomainItem::forExistingDomain(new Domain('bar.com')),
            DomainItem::forExistingDomain($bazDomain),
        ]);

        $this->commandTester->execute($input);

        self::assertEquals($expectedOutput, $this->commandTester->getDisplay());
        self::assertEquals(ExitCodes::EXIT_SUCCESS, $this->commandTester->getStatusCode());
        $listDomains->shouldHaveBeenCalledOnce();
    }

    public function provideInputsAndOutputs(): iterable
    {
        $withoutRedirectsOutput = <<<OUTPUT
        +---------+------------+
        | Domain  | Is default |
        +---------+------------+
        | foo.com | Yes        |
        | bar.com | No         |
        | baz.com | No         |
        +---------+------------+

        OUTPUT;
        $withRedirectsOutput = <<<OUTPUT
        +---------+------------+---------------------------------------------------------+
        | Domain  | Is default | "Not found" redirects                                   |
        +---------+------------+---------------------------------------------------------+
        | foo.com | Yes        | * Base URL: https://foo.com/default/base                |
        |         |            | * Regular 404: N/A                                      |
        |         |            | * Invalid short URL: https://foo.com/default/invalid    |
        | bar.com | No         | * Base URL: N/A                                         |
        |         |            | * Regular 404: N/A                                      |
        |         |            | * Invalid short URL: N/A                                |
        | baz.com | No         | * Base URL: N/A                                         |
        |         |            | * Regular 404: https://foo.com/baz-domain/regular       |
        |         |            | * Invalid short URL: https://foo.com/baz-domain/invalid |
        +---------+------------+---------------------------------------------------------+

        OUTPUT;

        yield 'no args' => [[], $withoutRedirectsOutput];
        yield 'no show redirects' => [['--show-redirects' => false], $withoutRedirectsOutput];
        yield 'show redirects' => [['--show-redirects' => true], $withRedirectsOutput];
    }
}
