<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190404102259 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activity_moment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, location VARCHAR(255) NOT NULL, start_date_time DATETIME NOT NULL, end_date_time DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_moment_activity_group (activity_moment_id INT NOT NULL, activity_group_id INT NOT NULL, INDEX IDX_2F07B604CF1B6F4 (activity_moment_id), INDEX IDX_2F07B605E5E6949 (activity_group_id), PRIMARY KEY(activity_moment_id, activity_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_moment_activity_group ADD CONSTRAINT FK_2F07B604CF1B6F4 FOREIGN KEY (activity_moment_id) REFERENCES activity_moment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_moment_activity_group ADD CONSTRAINT FK_2F07B605E5E6949 FOREIGN KEY (activity_group_id) REFERENCES activity_group (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity_moment_activity_group DROP FOREIGN KEY FK_2F07B604CF1B6F4');
        $this->addSql('DROP TABLE activity_moment');
        $this->addSql('DROP TABLE activity_moment_activity_group');
    }
}
