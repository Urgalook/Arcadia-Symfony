<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240710140631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nourriture_animaux DROP FOREIGN KEY FK_62BD210EA9DAECAA');
        $this->addSql('ALTER TABLE nourriture_animaux DROP FOREIGN KEY FK_62BD210E98BD5834');
        $this->addSql('DROP TABLE nourriture_animaux');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nourriture_animaux (nourriture_id INT NOT NULL, animaux_id INT NOT NULL, INDEX IDX_62BD210E98BD5834 (nourriture_id), INDEX IDX_62BD210EA9DAECAA (animaux_id), PRIMARY KEY(nourriture_id, animaux_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE nourriture_animaux ADD CONSTRAINT FK_62BD210EA9DAECAA FOREIGN KEY (animaux_id) REFERENCES animaux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nourriture_animaux ADD CONSTRAINT FK_62BD210E98BD5834 FOREIGN KEY (nourriture_id) REFERENCES nourriture (id) ON DELETE CASCADE');
    }
}
