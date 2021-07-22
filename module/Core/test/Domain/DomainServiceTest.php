<?php

declare(strict_types=1);

namespace ShlinkioTest\Shlink\Core\Domain;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Shlinkio\Shlink\Core\Config\NotFoundRedirects;
use Shlinkio\Shlink\Core\Domain\DomainService;
use Shlinkio\Shlink\Core\Domain\Model\DomainItem;
use Shlinkio\Shlink\Core\Domain\Repository\DomainRepositoryInterface;
use Shlinkio\Shlink\Core\Entity\Domain;
use Shlinkio\Shlink\Core\Exception\DomainNotFoundException;
use Shlinkio\Shlink\Core\Options\NotFoundRedirectOptions;
use Shlinkio\Shlink\Rest\ApiKey\Model\ApiKeyMeta;
use Shlinkio\Shlink\Rest\ApiKey\Model\RoleDefinition;
use Shlinkio\Shlink\Rest\Entity\ApiKey;

class DomainServiceTest extends TestCase
{
    use ProphecyTrait;

    private DomainService $domainService;
    private ObjectProphecy $em;

    public function setUp(): void
    {
        $this->em = $this->prophesize(EntityManagerInterface::class);
        $this->domainService = new DomainService($this->em->reveal(), 'default.com', new NotFoundRedirectOptions());
    }

    /**
     * @test
     * @dataProvider provideExcludedDomains
     */
    public function listDomainsDelegatesIntoRepository(array $domains, array $expectedResult, ?ApiKey $apiKey): void
    {
        $repo = $this->prophesize(DomainRepositoryInterface::class);
        $getRepo = $this->em->getRepository(Domain::class)->willReturn($repo->reveal());
        $findDomains = $repo->findDomainsWithout('default.com', $apiKey)->willReturn($domains);

        $result = $this->domainService->listDomains($apiKey);

        self::assertEquals($expectedResult, $result);
        $getRepo->shouldHaveBeenCalledOnce();
        $findDomains->shouldHaveBeenCalledOnce();
    }

    public function provideExcludedDomains(): iterable
    {
        $default = DomainItem::forDefaultDomain('default.com', new NotFoundRedirectOptions());
        $adminApiKey = ApiKey::create();
        $domainSpecificApiKey = ApiKey::fromMeta(
            ApiKeyMeta::withRoles(RoleDefinition::forDomain((new Domain(''))->setId('123'))),
        );

        yield 'empty list without API key' => [[], [$default], null];
        yield 'one item without API key' => [
            [new Domain('bar.com')],
            [$default, DomainItem::forExistingDomain(new Domain('bar.com'))],
            null,
        ];
        yield 'multiple items without API key' => [
            [new Domain('foo.com'), new Domain('bar.com')],
            [
                $default,
                DomainItem::forExistingDomain(new Domain('foo.com')),
                DomainItem::forExistingDomain(new Domain('bar.com')),
            ],
            null,
        ];

        yield 'empty list with admin API key' => [[], [$default], $adminApiKey];
        yield 'one item with admin API key' => [
            [new Domain('bar.com')],
            [$default, DomainItem::forExistingDomain(new Domain('bar.com'))],
            $adminApiKey,
        ];
        yield 'multiple items with admin API key' => [
            [new Domain('foo.com'), new Domain('bar.com')],
            [
                $default,
                DomainItem::forExistingDomain(new Domain('foo.com')),
                DomainItem::forExistingDomain(new Domain('bar.com')),
            ],
            $adminApiKey,
        ];

        yield 'empty list with domain-specific API key' => [[], [], $domainSpecificApiKey];
        yield 'one item with domain-specific API key' => [
            [new Domain('bar.com')],
            [DomainItem::forExistingDomain(new Domain('bar.com'))],
            $domainSpecificApiKey,
        ];
        yield 'multiple items with domain-specific API key' => [
            [new Domain('foo.com'), new Domain('bar.com')],
            [
                DomainItem::forExistingDomain(new Domain('foo.com')),
                DomainItem::forExistingDomain(new Domain('bar.com')),
            ],
            $domainSpecificApiKey,
        ];
    }

    /** @test */
    public function getDomainThrowsExceptionWhenDomainIsNotFound(): void
    {
        $find = $this->em->find(Domain::class, '123')->willReturn(null);

        $this->expectException(DomainNotFoundException::class);
        $find->shouldBeCalledOnce();

        $this->domainService->getDomain('123');
    }

    /** @test */
    public function getDomainReturnsEntityWhenFound(): void
    {
        $domain = new Domain('');
        $find = $this->em->find(Domain::class, '123')->willReturn($domain);

        $result = $this->domainService->getDomain('123');

        self::assertSame($domain, $result);
        $find->shouldHaveBeenCalledOnce();
    }

    /**
     * @test
     * @dataProvider provideFoundDomains
     */
    public function getOrCreateAlwaysPersistsDomain(?Domain $foundDomain): void
    {
        $authority = 'example.com';
        $repo = $this->prophesize(DomainRepositoryInterface::class);
        $repo->findOneBy(['authority' => $authority])->willReturn($foundDomain);
        $getRepo = $this->em->getRepository(Domain::class)->willReturn($repo->reveal());
        $persist = $this->em->persist($foundDomain ?? Argument::type(Domain::class));
        $flush = $this->em->flush();

        $result = $this->domainService->getOrCreate($authority);

        if ($foundDomain !== null) {
            self::assertSame($result, $foundDomain);
        }
        $getRepo->shouldHaveBeenCalledOnce();
        $persist->shouldHaveBeenCalledOnce();
        $flush->shouldHaveBeenCalledOnce();
    }

    /**
     * @test
     * @dataProvider provideFoundDomains
     */
    public function configureNotFoundRedirectsConfiguresFetchedDomain(?Domain $foundDomain): void
    {
        $authority = 'example.com';
        $repo = $this->prophesize(DomainRepositoryInterface::class);
        $repo->findOneBy(['authority' => $authority])->willReturn($foundDomain);
        $getRepo = $this->em->getRepository(Domain::class)->willReturn($repo->reveal());
        $persist = $this->em->persist($foundDomain ?? Argument::type(Domain::class));
        $flush = $this->em->flush();

        $result = $this->domainService->configureNotFoundRedirects($authority, new NotFoundRedirects(
            'foo.com',
            'bar.com',
            'baz.com',
        ));

        if ($foundDomain !== null) {
            self::assertSame($result, $foundDomain);
        }
        self::assertEquals('foo.com', $result->baseUrlRedirect());
        self::assertEquals('bar.com', $result->regular404Redirect());
        self::assertEquals('baz.com', $result->invalidShortUrlRedirect());
        $getRepo->shouldHaveBeenCalledOnce();
        $persist->shouldHaveBeenCalledOnce();
        $flush->shouldHaveBeenCalledTimes(2);
    }

    public function provideFoundDomains(): iterable
    {
        yield 'domain not found' => [null];
        yield 'domain found' => [new Domain('')];
    }
}
