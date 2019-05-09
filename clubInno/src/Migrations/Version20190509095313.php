<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190509095313 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity ADD creator_id INT NOT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AC74095A61220EA6 ON activity (creator_id)');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC14A798B6F FOREIGN KEY (semester_id) REFERENCES semester (id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC14A798B6F ON application (semester_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497BA2F5EB ON user (api_token)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A61220EA6');
        $this->addSql('DROP INDEX IDX_AC74095A61220EA6 ON activity');
        $this->addSql('ALTER TABLE activity DROP creator_id');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC14A798B6F');
        $this->addSql('DROP INDEX IDX_A45BDDC14A798B6F ON application');
        $this->addSql('DROP INDEX UNIQ_8D93D6497BA2F5EB ON user');
    }
}
