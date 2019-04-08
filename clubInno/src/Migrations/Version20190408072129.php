<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190408072129 extends AbstractMigration
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
        $this->addSql('CREATE TABLE activity_moment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, location VARCHAR(255) NOT NULL, start_date DATE NOT NULL, start_time TIME NOT NULL, end_date DATE DEFAULT NULL, end_time TIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_moment_activity_group (activity_moment_id INT NOT NULL, activity_group_id INT NOT NULL, INDEX IDX_2F07B604CF1B6F4 (activity_moment_id), INDEX IDX_2F07B605E5E6949 (activity_group_id), PRIMARY KEY(activity_moment_id, activity_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_activity (tag_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_9A457281BAD26311 (tag_id), INDEX IDX_9A45728181C06096 (activity_id), PRIMARY KEY(tag_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_group_user ADD CONSTRAINT FK_F12BB3315E5E6949 FOREIGN KEY (activity_group_id) REFERENCES activity_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_group_user ADD CONSTRAINT FK_F12BB331A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_moment_activity_group ADD CONSTRAINT FK_2F07B604CF1B6F4 FOREIGN KEY (activity_moment_id) REFERENCES activity_moment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_moment_activity_group ADD CONSTRAINT FK_2F07B605E5E6949 FOREIGN KEY (activity_group_id) REFERENCES activity_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_activity ADD CONSTRAINT FK_9A457281BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_activity ADD CONSTRAINT FK_9A45728181C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE tags_activities');
        $this->addSql('DROP TABLE users_groups');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity_moment_activity_group DROP FOREIGN KEY FK_2F07B604CF1B6F4');
        $this->addSql('CREATE TABLE tags_activities (tag_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_D748B304BAD26311 (tag_id), INDEX IDX_D748B30481C06096 (activity_id), PRIMARY KEY(tag_id, activity_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users_groups (user_id INT NOT NULL, activity_group_id INT NOT NULL, INDEX IDX_FF8AB7E0A76ED395 (user_id), INDEX IDX_FF8AB7E05E5E6949 (activity_group_id), PRIMARY KEY(user_id, activity_group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tags_activities ADD CONSTRAINT FK_D748B30481C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_activities ADD CONSTRAINT FK_D748B304BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E05E5E6949 FOREIGN KEY (activity_group_id) REFERENCES activity_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE activity_group_user');
        $this->addSql('DROP TABLE activity_moment');
        $this->addSql('DROP TABLE activity_moment_activity_group');
        $this->addSql('DROP TABLE tag_activity');
    }
}
