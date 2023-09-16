<?php

declare(strict_types=1);

namespace Context\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230916082329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core.actor (id UUID NOT NULL, internal_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, seasons_active VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN core.actor.id IS \'(DC2Type:aggregate_id)\'');
        $this->addSql('COMMENT ON COLUMN core.actor.internal_id IS \'(DC2Type:actor_id)\'');
        $this->addSql('COMMENT ON COLUMN core.actor.name IS \'(DC2Type:name)\'');
        $this->addSql('COMMENT ON COLUMN core.actor.seasons_active IS \'(DC2Type:seasonsActive)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE core.actor');
    }
}
