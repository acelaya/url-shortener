<?php
declare(strict_types=1);

namespace Shlinkio\Shlink\CLI\Install\Plugin;

use Shlinkio\Shlink\CLI\Model\CustomizableAppConfig;
use Shlinkio\Shlink\Common\Util\StringUtilsTrait;
use Symfony\Component\Console\Style\SymfonyStyle;

class ApplicationConfigCustomizer implements ConfigCustomizerInterface
{
    use StringUtilsTrait;

    /**
     * @param SymfonyStyle $io
     * @param CustomizableAppConfig $appConfig
     * @return void
     */
    public function process(SymfonyStyle $io, CustomizableAppConfig $appConfig)
    {
        $io->title('APPLICATION');

        if ($appConfig->hasApp() && $io->confirm('Do you want to keep imported application config?')) {
            return;
        }

        $appConfig->setApp([
            'SECRET' => $io->ask(
                'Define a secret string that will be used to sign API tokens (leave empty to autogenerate one)',
                null,
                function ($value) {
                    return $value;
                }
            ) ?: $this->generateRandomString(32),
        ]);
    }
}