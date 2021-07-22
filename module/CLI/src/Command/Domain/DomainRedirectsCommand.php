<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\CLI\Command\Domain;

use Shlinkio\Shlink\CLI\Util\ExitCodes;
use Shlinkio\Shlink\Core\Config\NotFoundRedirects;
use Shlinkio\Shlink\Core\Domain\DomainServiceInterface;
use Shlinkio\Shlink\Core\Domain\Model\DomainItem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function Functional\filter;
use function Functional\invoke;
use function sprintf;
use function str_contains;

class DomainRedirectsCommand extends Command
{
    public const NAME = 'domain:redirects';

    public function __construct(private DomainServiceInterface $domainService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Set specific "not found" redirects for individual domains.')
            ->addArgument(
                'domain',
                InputArgument::REQUIRED,
                'The domain authority to which you want to set the specific redirects',
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        /** @var string|null $domain */
        $domain = $input->getArgument('domain');
        if ($domain !== null) {
            return;
        }

        $io = new SymfonyStyle($input, $output);
        $askNewDomain = static fn () => $io->ask('Domain authority for which you want to set specific redirects');

        /** @var string[] $availableDomains */
        $availableDomains = invoke(
            filter($this->domainService->listDomains(), static fn (DomainItem $item) => ! $item->isDefault()),
            'toString',
        );
        if (empty($availableDomains)) {
            $input->setArgument('domain', $askNewDomain());
            return;
        }

        $selectedOption = $io->choice(
            'Select the domain to configure',
            [...$availableDomains, '<options=bold>New domain</>'],
        );
        $input->setArgument('domain', str_contains($selectedOption, 'New domain') ? $askNewDomain() : $selectedOption);
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $io = new SymfonyStyle($input, $output);
        $domainAuthority = $input->getArgument('domain');
        $domain = $this->domainService->findByAuthority($domainAuthority);

        $ask = static function (string $message, ?string $current) use ($io): ?string {
            if ($current === null) {
                return $io->ask(sprintf('%s (Leave empty for no redirect)', $message));
            }

            $choice = $io->choice($message, [
                sprintf('Keep current one: [%s]', $current),
                'Set new redirect URL',
                'Remove redirect',
            ]);

            return match ($choice) {
                'Set new redirect URL' => $io->ask('New redirect URL'),
                'Remove redirect' => null,
                default => $current,
            };
        };

        $this->domainService->configureNotFoundRedirects($domainAuthority, new NotFoundRedirects(
            $ask(
                'URL to redirect to when a user hits this domain\'s base URL',
                $domain?->baseUrlRedirect(),
            ),
            $ask(
                'URL to redirect to when a user hits a not found URL other than an invalid short URL',
                $domain?->regular404Redirect(),
            ),
            $ask(
                'URL to redirect to when a user hits an invalid short URL',
                $domain?->invalidShortUrlRedirect(),
            ),
        ));

        $io->success(sprintf('"Not found" redirects properly set for "%s"', $domainAuthority));

        return ExitCodes::EXIT_SUCCESS;
    }
}
