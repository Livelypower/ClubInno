<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190329104009 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activity_group (id INT AUTO_INCREMENT NOT NULL, actvity_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGBLOB DEFAULT NULL, INDEX IDX_73C27276B37D2B8B (actvity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_groups (user_id INT NOT NULL, activity_group_id INT NOT NULL, INDEX IDX_FF8AB7E0A76ED395 (user_id), INDEX IDX_FF8AB7E05E5E6949 (activity_group_id), PRIMARY KEY(user_id, activity_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_group ADD CONSTRAINT FK_73C27276B37D2B8B FOREIGN KEY (actvity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E05E5E6949 FOREIGN KEY (activity_group_id) REFERENCES activity_group (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users_groups DROP FOREIGN KEY FK_FF8AB7E05E5E6949');
        $this->addSql('DROP TABLE activity_group');
        $this->addSql('DROP TABLE users_groups');
    }
}
