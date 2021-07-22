<?php

declare(strict_types=1);

namespace ShlinkMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20210720143824 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $domainsTable = $schema->getTable('domains');
        $this->skipIf($domainsTable->hasColumn('base_url_redirect'));

        $this->createRedirectColumn($domainsTable, 'base_url_redirect');
        $this->createRedirectColumn($domainsTable, 'regular_not_found_redirect');
        $this->createRedirectColumn($domainsTable, 'invalid_short_url_redirect');
    }

    private function createRedirectColumn(Table $table, string $columnName): void
    {
        $table->addColumn($columnName, Types::STRING, [
            'notnull' => false,
            'default' => null,
        ]);
    }

    public function down(Schema $schema): void
    {
        $domainsTable = $schema->getTable('domains');
        $this->skipIf(! $domainsTable->hasColumn('base_url_redirect'));

        $domainsTable->dropColumn('base_url_redirect');
        $domainsTable->dropColumn('regular_not_found_redirect');
        $domainsTable->dropColumn('invalid_short_url_redirect');
    }
}
