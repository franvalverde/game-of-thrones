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
    }
}
