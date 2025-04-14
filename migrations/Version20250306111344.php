<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306111344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponse ADD proposition_id INT DEFAULT NULL, DROP id_reponse');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7DB96F9E FOREIGN KEY (proposition_id) REFERENCES propositions (id)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC7DB96F9E ON reponse (proposition_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7DB96F9E');
        $this->addSql('DROP INDEX IDX_5FB6DEC7DB96F9E ON reponse');
        $this->addSql('ALTER TABLE reponse ADD id_reponse INT NOT NULL, DROP proposition_id');
    }
}
