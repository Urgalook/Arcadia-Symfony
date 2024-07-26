<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240708140647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE animaux DROP FOREIGN KEY animaux_ibfk_2');
        $this->addSql('ALTER TABLE animaux DROP FOREIGN KEY animaux_ibfk_1');
        $this->addSql('ALTER TABLE habitat_veterinaire DROP FOREIGN KEY habitat_veterinaire_ibfk_1');
        $this->addSql('DROP INDEX habitat ON habitat_veterinaire');
        $this->addSql('ALTER TABLE nourriture DROP FOREIGN KEY nourriture_ibfk_1');
        $this->addSql('DROP INDEX id_animal ON nourriture');
        $this->addSql('ALTER TABLE veterinaire DROP FOREIGN KEY veterinaire_ibfk_1');
        $this->addSql('DROP INDEX id_animal ON veterinaire');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE roles');
        $this->addSql('ALTER TABLE animaux ADD CONSTRAINT animaux_ibfk_2 FOREIGN KEY (habitat_id) REFERENCES habitats (id)');
        $this->addSql('ALTER TABLE habitat_veterinaire ADD CONSTRAINT habitat_veterinaire_ibfk_1 FOREIGN KEY (habitat) REFERENCES habitats (id)');
        $this->addSql('CREATE INDEX habitat ON habitat_veterinaire (habitat)');
        $this->addSql('ALTER TABLE nourriture ADD CONSTRAINT nourriture_ibfk_1 FOREIGN KEY (id_animal) REFERENCES animaux (id)');
        $this->addSql('CREATE INDEX id_animal ON nourriture (id_animal)');
        $this->addSql('ALTER TABLE veterinaire ADD CONSTRAINT veterinaire_ibfk_1 FOREIGN KEY (id_animal) REFERENCES animaux (id)');
        $this->addSql('CREATE INDEX id_animal ON veterinaire (id_animal)');
    }
}
