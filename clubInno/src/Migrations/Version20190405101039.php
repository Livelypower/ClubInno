<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190405101039 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activity_group_user (activity_group_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F12BB3315E5E6949 (activity_group_id), INDEX IDX_F12BB331A76ED395 (user_id), PRIMARY KEY(activity_group_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_activity_group (user_id INT NOT NULL, activity_group_id INT NOT NULL, INDEX IDX_1847AD2FA76ED395 (user_id), INDEX IDX_1847AD2F5E5E6949 (activity_group_id), PRIMARY KEY(user_id, activity_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_group_user ADD CONSTRAINT FK_F12BB3315E5E6949 FOREIGN KEY (activity_group_id) REFERENCES activity_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_group_user ADD CONSTRAINT FK_F12BB331A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_activity_group ADD CONSTRAINT FK_1847AD2FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_activity_group ADD CONSTRAINT FK_1847AD2F5E5E6949 FOREIGN KEY (activity_group_id) REFERENCES activity_group (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE users_groups');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users_groups (user_id INT NOT NULL, activity_group_id INT NOT NULL, INDEX IDX_FF8AB7E0A76ED395 (user_id), INDEX IDX_FF8AB7E05E5E6949 (activity_group_id), PRIMARY KEY(user_id, activity_group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E05E5E6949 FOREIGN KEY (activity_group_id) REFERENCES activity_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE activity_group_user');
        $this->addSql('DROP TABLE user_activity_group');
    }
}
