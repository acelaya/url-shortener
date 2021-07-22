<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\CLI\Command\Domain;

use Shlinkio\Shlink\CLI\Util\ExitCodes;
use Shlinkio\Shlink\CLI\Util\ShlinkTable;
use Shlinkio\Shlink\Core\Config\NotFoundRedirectConfigInterface;
use Shlinkio\Shlink\Core\Domain\DomainServiceInterface;
use Shlinkio\Shlink\Core\Domain\Model\DomainItem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Functional\map;

class ListDomainsCommand extends Command
{
    public const NAME = 'domain:list';

    public function __construct(private DomainServiceInterface $domainService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('List all domains that have been ever used for some short URL')
            ->addOption(
                'show-redirects',
                'r',
                InputOption::VALUE_NONE,
                'Will display an extra column with the information of the "not found" redirects for every domain.',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $domains = $this->domainService->listDomains();
        $showRedirects = $input->getOption('show-redirects');
        $commonFields = ['Domain', 'Is default'];

        ShlinkTable::fromOutput($output)->render(
            $showRedirects ? [...$commonFields, '"Not found" redirects'] : $commonFields,
            map($domains, function (DomainItem $domain) use ($showRedirects) {
                $commonValues = [$domain->toString(), $domain->isDefault() ? 'Yes' : 'No'];

                return $showRedirects
                    ? [
                        ...$commonValues,
                        $this->notFoundRedirectsToString($domain->notFoundRedirectConfig()),
                      ]
                    : $commonValues;
            }),
        );

        return ExitCodes::EXIT_SUCCESS;
    }

    private function notFoundRedirectsToString(NotFoundRedirectConfigInterface $config): string
    {
        $baseUrl = $config->baseUrlRedirect() ?? 'N/A';
        $regular404 = $config->regular404Redirect() ?? 'N/A';
        $invalidShortUrl = $config->invalidShortUrlRedirect() ?? 'N/A';

        return <<<EOL
        * Base URL: {$baseUrl}
        * Regular 404: {$regular404}
        * Invalid short URL: {$invalidShortUrl}
        EOL;
    }
}
