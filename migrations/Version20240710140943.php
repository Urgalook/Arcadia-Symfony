<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240710140943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nourriture ADD animal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE nourriture ADD CONSTRAINT FK_7447E6138E962C16 FOREIGN KEY (animal_id) REFERENCES animaux (id)');
        $this->addSql('CREATE INDEX IDX_7447E6138E962C16 ON nourriture (animal_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nourriture DROP FOREIGN KEY FK_7447E6138E962C16');
        $this->addSql('DROP INDEX IDX_7447E6138E962C16 ON nourriture');
        $this->addSql('ALTER TABLE nourriture DROP animal_id');
    }
}
