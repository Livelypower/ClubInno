<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190328084150 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE semester (id INT AUTO_INCREMENT NOT NULL, start_year VARCHAR(255) NOT NULL, end_year VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity ADD semester_id INT DEFAULT NULL, DROP registration_deadline');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A4A798B6F FOREIGN KEY (semester_id) REFERENCES semester (id)');
        $this->addSql('CREATE INDEX IDX_AC74095A4A798B6F ON activity (semester_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A4A798B6F');
        $this->addSql('DROP TABLE semester');
        $this->addSql('DROP INDEX IDX_AC74095A4A798B6F ON activity');
        $this->addSql('ALTER TABLE activity ADD registration_deadline DATETIME DEFAULT NULL, DROP semester_id');
    }
}
