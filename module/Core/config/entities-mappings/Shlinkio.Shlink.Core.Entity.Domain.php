<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\Core;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

return static function (ClassMetadata $metadata, array $emConfig): void {
    $builder = new ClassMetadataBuilder($metadata);

    $builder->setTable(determineTableName('domains', $emConfig))
            ->setCustomRepositoryClass(Domain\Repository\DomainRepository::class);

    $builder->createField('id', Types::BIGINT)
            ->columnName('id')
            ->makePrimaryKey()
            ->generatedValue('IDENTITY')
            ->option('unsigned', true)
            ->build();

    $builder->createField('authority', Types::STRING)
            ->unique()
            ->build();

    $builder->createField('baseUrlRedirect', Types::STRING)
            ->columnName('base_url_redirect')
            ->nullable()
            ->build();

    $builder->createField('regular404Redirect', Types::STRING)
            ->columnName('regular_not_found_redirect')
            ->nullable()
            ->build();

    $builder->createField('invalidShortUrlRedirect', Types::STRING)
            ->columnName('invalid_short_url_redirect')
            ->nullable()
            ->build();
};
