<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250303104824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9A76ED395');

        // Modifier la colonne user_id
        $this->addSql('ALTER TABLE projet CHANGE user_id user_id INT NOT NULL');
    
        // Recréer la contrainte de clé étrangère
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    { // Supprimer la contrainte avant de revenir en arrière
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9A76ED395');
    
        // Revenir en arrière en mettant user_id en nullable
        $this->addSql('ALTER TABLE projet CHANGE user_id user_id INT DEFAULT NULL');
    
        // Recréer la contrainte de clé étrangère
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }
}
