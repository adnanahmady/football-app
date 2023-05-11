<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230511115857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE counter_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE team_player_contract_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE team_player_contract (id INT NOT NULL, team_id INT NOT NULL, player_id INT NOT NULL, amount INT NOT NULL, start_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CC0673C7296CD8AE ON team_player_contract (team_id)');
        $this->addSql('CREATE INDEX IDX_CC0673C799E6F5DF ON team_player_contract (player_id)');
        $this->addSql('COMMENT ON COLUMN team_player_contract.start_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN team_player_contract.end_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE team_player_contract ADD CONSTRAINT FK_CC0673C7296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team_player_contract ADD CONSTRAINT FK_CC0673C799E6F5DF FOREIGN KEY (player_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE counter');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE team_player_contract_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('CREATE SEQUENCE counter_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE counter (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE team_player_contract DROP CONSTRAINT FK_CC0673C7296CD8AE');
        $this->addSql('ALTER TABLE team_player_contract DROP CONSTRAINT FK_CC0673C799E6F5DF');
        $this->addSql('DROP TABLE team_player_contract');
        $this->addSql('DROP TABLE "user"');
    }
}
