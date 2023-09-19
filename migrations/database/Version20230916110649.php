<?php

declare(strict_types=1);

namespace Context\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230916110649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE core.actor ADD character_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN core.actor.character_id IS \'(DC2Type:aggregate_id)\'');
        $this->addSql('ALTER TABLE core.actor ADD CONSTRAINT FK_D28AA97D1136BE75 FOREIGN KEY (character_id) REFERENCES core.character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D28AA97D1136BE75 ON core.actor (character_id)');
        $this->addSql('ALTER TABLE core.actor DROP CONSTRAINT FK_D28AA97D1136BE75');
        $this->addSql('ALTER TABLE core.actor ADD CONSTRAINT FK_D28AA97D1136BE75 FOREIGN KEY (character_id) REFERENCES core.character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE core.character ADD house_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN core.character.house_id IS \'(DC2Type:aggregate_id)\'');
        $this->addSql('ALTER TABLE core.character ADD CONSTRAINT FK_C5916EB66BB74515 FOREIGN KEY (house_id) REFERENCES core.house (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C5916EB66BB74515 ON core.character (house_id)');
        $this->addSql('CREATE TABLE core.character_relate (id UUID NOT NULL, character_id UUID DEFAULT NULL, related_to UUID DEFAULT NULL, relation VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CD09D67A1136BE75 ON core.character_relate (character_id)');
        $this->addSql('CREATE INDEX IDX_CD09D67A29DC7595 ON core.character_relate (related_to)');
        $this->addSql('COMMENT ON COLUMN core.character_relate.id IS \'(DC2Type:aggregate_id)\'');
        $this->addSql('COMMENT ON COLUMN core.character_relate.character_id IS \'(DC2Type:aggregate_id)\'');
        $this->addSql('COMMENT ON COLUMN core.character_relate.related_to IS \'(DC2Type:aggregate_id)\'');
        $this->addSql('COMMENT ON COLUMN core.character_relate.relation IS \'(DC2Type:character_relation)\'');
        $this->addSql('ALTER TABLE core.character_relate ADD CONSTRAINT FK_CD09D67A1136BE75 FOREIGN KEY (character_id) REFERENCES core.character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE core.character_relate ADD CONSTRAINT FK_CD09D67A29DC7595 FOREIGN KEY (related_to) REFERENCES core.character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE core.actor DROP CONSTRAINT FK_D28AA97D1136BE75');
        $this->addSql('ALTER TABLE core.actor ADD CONSTRAINT FK_D28AA97D1136BE75 FOREIGN KEY (character_id) REFERENCES core.character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE messenger_messages ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE messenger_messages ALTER available_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE messenger_messages ALTER delivered_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE core.actor DROP CONSTRAINT FK_D28AA97D1136BE75');
        $this->addSql('DROP INDEX IDX_D28AA97D1136BE75');
        $this->addSql('ALTER TABLE core.actor DROP character_id');
        $this->addSql('ALTER TABLE core.actor DROP CONSTRAINT fk_d28aa97d1136be75');
        $this->addSql('ALTER TABLE core.actor ADD CONSTRAINT fk_d28aa97d1136be75 FOREIGN KEY (character_id) REFERENCES core."character" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE core.character DROP CONSTRAINT FK_C5916EB66BB74515');
        $this->addSql('DROP INDEX IDX_C5916EB66BB74515');
        $this->addSql('ALTER TABLE core.character DROP house_id');
        $this->addSql('ALTER TABLE core.character_relate DROP CONSTRAINT FK_CD09D67A1136BE75');
        $this->addSql('ALTER TABLE core.character_relate DROP CONSTRAINT FK_CD09D67A29DC7595');
        $this->addSql('DROP TABLE core.character_relate');
        $this->addSql('ALTER TABLE messenger_messages ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE messenger_messages ALTER available_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE messenger_messages ALTER delivered_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS NULL');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS NULL');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS NULL');
        $this->addSql('ALTER TABLE core.actor DROP CONSTRAINT fk_d28aa97d1136be75');
        $this->addSql('ALTER TABLE core.actor ADD CONSTRAINT fk_d28aa97d1136be75 FOREIGN KEY (character_id) REFERENCES core."character" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

    }
}
