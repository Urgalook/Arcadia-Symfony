<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240711121427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE veterinaire ADD animal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE veterinaire ADD CONSTRAINT FK_E9D962B88E962C16 FOREIGN KEY (animal_id) REFERENCES animaux (id)');
        $this->addSql('CREATE INDEX IDX_E9D962B88E962C16 ON veterinaire (animal_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE veterinaire DROP FOREIGN KEY FK_E9D962B88E962C16');
        $this->addSql('DROP INDEX IDX_E9D962B88E962C16 ON veterinaire');
        $this->addSql('ALTER TABLE veterinaire DROP animal_id');
    }
}
