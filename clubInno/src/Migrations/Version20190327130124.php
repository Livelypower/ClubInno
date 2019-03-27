<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190327130124 extends AbstractMigration
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
        $this->addSql('CREATE TABLE user_activities (user_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_12966909A76ED395 (user_id), INDEX IDX_1296690981C06096 (activity_id), PRIMARY KEY(user_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_activities ADD CONSTRAINT FK_12966909A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_activities ADD CONSTRAINT FK_1296690981C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity ADD activity_id INT DEFAULT NULL, DROP registration_deadline');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A81C06096 FOREIGN KEY (activity_id) REFERENCES semester (id)');
        $this->addSql('CREATE INDEX IDX_AC74095A81C06096 ON activity (activity_id)');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC1A76ED395 ON application (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A81C06096');
        $this->addSql('DROP TABLE semester');
        $this->addSql('DROP TABLE user_activities');
        $this->addSql('DROP INDEX IDX_AC74095A81C06096 ON activity');
        $this->addSql('ALTER TABLE activity ADD registration_deadline DATETIME DEFAULT NULL, DROP activity_id');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1A76ED395');
        $this->addSql('DROP INDEX IDX_A45BDDC1A76ED395 ON application');
    }
}
