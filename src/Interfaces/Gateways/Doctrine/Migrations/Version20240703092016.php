<?php

declare(strict_types=1);

namespace App\Interfaces\Gateways\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240703092016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE transactions ADD category_id CHAR(36) NOT NULL AFTER wallet_id, ADD note LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE transactions DROP category_id, DROP note');
    }
}
