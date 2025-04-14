<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211145951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet DROP num_projet');
        $this->addSql('ALTER TABLE propositions DROP FOREIGN KEY FK_E9AB02861B9D68F4');
        $this->addSql('DROP INDEX IDX_E9AB02861B9D68F4 ON propositions');
        $this->addSql('ALTER TABLE propositions DROP question_condition_id');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E1B9D68F4 FOREIGN KEY (question_condition_id) REFERENCES propositions (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE propositions ADD question_condition_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE propositions ADD CONSTRAINT FK_E9AB02861B9D68F4 FOREIGN KEY (question_condition_id) REFERENCES question (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E9AB02861B9D68F4 ON propositions (question_condition_id)');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E1B9D68F4');
        $this->addSql('ALTER TABLE projet ADD num_projet INT NOT NULL');
    }
}
