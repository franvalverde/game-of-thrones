<?php

declare(strict_types=1);

namespace Context\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220619162136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stored_event (message_id UUID NOT NULL, message_name VARCHAR(255) NOT NULL, message_body JSON NOT NULL, aggregate_id UUID NOT NULL, occurred_at TIMESTAMP(6) WITH TIME ZONE NOT NULL, PRIMARY KEY(message_id))');
        $this->addSql('COMMENT ON COLUMN stored_event.message_id IS \'(DC2Type:message_id)\'');
        $this->addSql('COMMENT ON COLUMN stored_event.occurred_at IS \'(DC2Type:carbontz_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE SCHEMA core');
        $this->addSql('CREATE TABLE core.house (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN core.house.id IS \'(DC2Type:aggregate_id)\'');
        $this->addSql('COMMENT ON COLUMN core.house.name IS \'(DC2Type:name)\'');
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
        $this->addSql('DROP TABLE stored_event');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE core.house');
        $this->addSql('DROP TABLE core.actor');
    }
}
