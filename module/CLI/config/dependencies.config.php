<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\CLI;

use Doctrine\DBAL\Connection;
use GeoIp2\Database\Reader;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Shlinkio\Shlink\Common\Doctrine\NoDbNameConnectionFactory;
use Shlinkio\Shlink\Core\Domain\DomainService;
use Shlinkio\Shlink\Core\Options\TrackingOptions;
use Shlinkio\Shlink\Core\Service;
use Shlinkio\Shlink\Core\ShortUrl\Helper\ShortUrlStringifier;
use Shlinkio\Shlink\Core\ShortUrl\Transformer\ShortUrlDataTransformer;
use Shlinkio\Shlink\Core\Tag\TagService;
use Shlinkio\Shlink\Core\Visit;
use Shlinkio\Shlink\Installer\Factory\ProcessHelperFactory;
use Shlinkio\Shlink\IpGeolocation\GeoLite2\DbUpdater;
use Shlinkio\Shlink\IpGeolocation\Resolver\IpLocationResolverInterface;
use Shlinkio\Shlink\Rest\Service\ApiKeyService;
use Symfony\Component\Console as SymfonyCli;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Process\PhpExecutableFinder;

use const Shlinkio\Shlink\Core\LOCAL_LOCK_FACTORY;

return [

    'dependencies' => [
        'factories' => [
            SymfonyCli\Application::class => Factory\ApplicationFactory::class,
            SymfonyCli\Helper\ProcessHelper::class => ProcessHelperFactory::class,
            PhpExecutableFinder::class => InvokableFactory::class,

            Util\GeolocationDbUpdater::class => ConfigAbstractFactory::class,
            Util\ProcessRunner::class => ConfigAbstractFactory::class,

            ApiKey\RoleResolver::class => ConfigAbstractFactory::class,

            Command\ShortUrl\GenerateShortUrlCommand::class => ConfigAbstractFactory::class,
            Command\ShortUrl\ResolveUrlCommand::class => ConfigAbstractFactory::class,
            Command\ShortUrl\ListShortUrlsCommand::class => ConfigAbstractFactory::class,
            Command\ShortUrl\GetVisitsCommand::class => ConfigAbstractFactory::class,
            Command\ShortUrl\DeleteShortUrlCommand::class => ConfigAbstractFactory::class,

            Command\Visit\DownloadGeoLiteDbCommand::class => ConfigAbstractFactory::class,
            Command\Visit\LocateVisitsCommand::class => ConfigAbstractFactory::class,

            Command\Api\GenerateKeyCommand::class => ConfigAbstractFactory::class,
            Command\Api\DisableKeyCommand::class => ConfigAbstractFactory::class,
            Command\Api\ListKeysCommand::class => ConfigAbstractFactory::class,

            Command\Tag\ListTagsCommand::class => ConfigAbstractFactory::class,
            Command\Tag\CreateTagCommand::class => ConfigAbstractFactory::class,
            Command\Tag\RenameTagCommand::class => ConfigAbstractFactory::class,
            Command\Tag\DeleteTagsCommand::class => ConfigAbstractFactory::class,

            Command\Db\CreateDatabaseCommand::class => ConfigAbstractFactory::class,
            Command\Db\MigrateDatabaseCommand::class => ConfigAbstractFactory::class,

            Command\Domain\ListDomainsCommand::class => ConfigAbstractFactory::class,
            Command\Domain\DomainRedirectsCommand::class => ConfigAbstractFactory::class,
        ],
    ],

    ConfigAbstractFactory::class => [
        Util\GeolocationDbUpdater::class => [
            DbUpdater::class,
            Reader::class,
            LOCAL_LOCK_FACTORY,
            TrackingOptions::class,
        ],
        Util\ProcessRunner::class => [SymfonyCli\Helper\ProcessHelper::class],
        ApiKey\RoleResolver::class => [DomainService::class],

        Command\ShortUrl\GenerateShortUrlCommand::class => [
            Service\UrlShortener::class,
            ShortUrlStringifier::class,
            'config.url_shortener.default_short_codes_length',
        ],
        Command\ShortUrl\ResolveUrlCommand::class => [Service\ShortUrl\ShortUrlResolver::class],
        Command\ShortUrl\ListShortUrlsCommand::class => [
            Service\ShortUrlService::class,
            ShortUrlDataTransformer::class,
        ],
        Command\ShortUrl\GetVisitsCommand::class => [Visit\VisitsStatsHelper::class],
        Command\ShortUrl\DeleteShortUrlCommand::class => [Service\ShortUrl\DeleteShortUrlService::class],

        Command\Visit\DownloadGeoLiteDbCommand::class => [Util\GeolocationDbUpdater::class],
        Command\Visit\LocateVisitsCommand::class => [
            Visit\VisitLocator::class,
            IpLocationResolverInterface::class,
            LockFactory::class,
        ],

        Command\Api\GenerateKeyCommand::class => [ApiKeyService::class, ApiKey\RoleResolver::class],
        Command\Api\DisableKeyCommand::class => [ApiKeyService::class],
        Command\Api\ListKeysCommand::class => [ApiKeyService::class],

        Command\Tag\ListTagsCommand::class => [TagService::class],
        Command\Tag\CreateTagCommand::class => [TagService::class],
        Command\Tag\RenameTagCommand::class => [TagService::class],
        Command\Tag\DeleteTagsCommand::class => [TagService::class],

        Command\Domain\ListDomainsCommand::class => [DomainService::class],
        Command\Domain\DomainRedirectsCommand::class => [DomainService::class],

        Command\Db\CreateDatabaseCommand::class => [
            LockFactory::class,
            Util\ProcessRunner::class,
            PhpExecutableFinder::class,
            Connection::class,
            NoDbNameConnectionFactory::SERVICE_NAME,
        ],
        Command\Db\MigrateDatabaseCommand::class => [
            LockFactory::class,
            Util\ProcessRunner::class,
            PhpExecutableFinder::class,
        ],
    ],

];
