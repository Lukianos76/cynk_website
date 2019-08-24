<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190823145759 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__painting AS SELECT id, creation_date, title, image, updated_at FROM painting');
        $this->addSql('DROP TABLE painting');
        $this->addSql('CREATE TABLE painting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, image VARCHAR(255) NOT NULL COLLATE BINARY, updated_at DATETIME NOT NULL, creation_date DATE NOT NULL)');
        $this->addSql('INSERT INTO painting (id, creation_date, title, image, updated_at) SELECT id, creation_date, title, image, updated_at FROM __temp__painting');
        $this->addSql('DROP TABLE __temp__painting');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__painting AS SELECT id, creation_date, title, image, updated_at FROM painting');
        $this->addSql('DROP TABLE painting');
        $this->addSql('CREATE TABLE painting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, creation_date DATETIME NOT NULL)');
        $this->addSql('INSERT INTO painting (id, creation_date, title, image, updated_at) SELECT id, creation_date, title, image, updated_at FROM __temp__painting');
        $this->addSql('DROP TABLE __temp__painting');
    }
}
