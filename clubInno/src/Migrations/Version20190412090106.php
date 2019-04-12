<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190412090106 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, semester_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, max_amount_students INT NOT NULL, active TINYINT(1) DEFAULT NULL, main_image VARCHAR(255) DEFAULT NULL, files LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_AC74095A4A798B6F (semester_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_tag (activity_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_71B0290181C06096 (activity_id), INDEX IDX_71B02901BAD26311 (tag_id), PRIMARY KEY(activity_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_group (id INT AUTO_INCREMENT NOT NULL, actvity_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_73C27276B37D2B8B (actvity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_group_user (activity_group_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F12BB3315E5E6949 (activity_group_id), INDEX IDX_F12BB331A76ED395 (user_id), PRIMARY KEY(activity_group_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_moment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, location VARCHAR(255) NOT NULL, start_date DATE NOT NULL, start_time TIME NOT NULL, end_date DATE DEFAULT NULL, end_time TIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_moment_activity_group (activity_moment_id INT NOT NULL, activity_group_id INT NOT NULL, INDEX IDX_2F07B604CF1B6F4 (activity_moment_id), INDEX IDX_2F07B605E5E6949 (activity_group_id), PRIMARY KEY(activity_moment_id, activity_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE application (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date DATETIME NOT NULL, motivation_letter_path VARCHAR(255) NOT NULL, INDEX IDX_A45BDDC1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE application_activity (application_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_134FD6493E030ACD (application_id), INDEX IDX_134FD64981C06096 (activity_id), PRIMARY KEY(application_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_post (id INT AUTO_INCREMENT NOT NULL, activity_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, datetime DATETIME NOT NULL, files LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_BA5AE01D81C06096 (activity_id), INDEX IDX_BA5AE01DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, body LONGTEXT NOT NULL, datetime DATETIME NOT NULL, BlogPost_id INT DEFAULT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C6ED4F725 (BlogPost_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE semester (id INT AUTO_INCREMENT NOT NULL, start_year VARCHAR(255) NOT NULL, end_year VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_activity (tag_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_9A457281BAD26311 (tag_id), INDEX IDX_9A45728181C06096 (activity_id), PRIMARY KEY(tag_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, orientation VARCHAR(255) DEFAULT NULL, api_token VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6497BA2F5EB (api_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_activity (user_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_4CF9ED5AA76ED395 (user_id), INDEX IDX_4CF9ED5A81C06096 (activity_id), PRIMARY KEY(user_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A4A798B6F FOREIGN KEY (semester_id) REFERENCES semester (id)');
        $this->addSql('ALTER TABLE activity_tag ADD CONSTRAINT FK_71B0290181C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_tag ADD CONSTRAINT FK_71B02901BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_group ADD CONSTRAINT FK_73C27276B37D2B8B FOREIGN KEY (actvity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE activity_group_user ADD CONSTRAINT FK_F12BB3315E5E6949 FOREIGN KEY (activity_group_id) REFERENCES activity_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_group_user ADD CONSTRAINT FK_F12BB331A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_moment_activity_group ADD CONSTRAINT FK_2F07B604CF1B6F4 FOREIGN KEY (activity_moment_id) REFERENCES activity_moment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_moment_activity_group ADD CONSTRAINT FK_2F07B605E5E6949 FOREIGN KEY (activity_group_id) REFERENCES activity_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE application_activity ADD CONSTRAINT FK_134FD6493E030ACD FOREIGN KEY (application_id) REFERENCES application (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE application_activity ADD CONSTRAINT FK_134FD64981C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01D81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C6ED4F725 FOREIGN KEY (BlogPost_id) REFERENCES blog_post (id)');
        $this->addSql('ALTER TABLE tag_activity ADD CONSTRAINT FK_9A457281BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_activity ADD CONSTRAINT FK_9A45728181C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_activity ADD CONSTRAINT FK_4CF9ED5AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_activity ADD CONSTRAINT FK_4CF9ED5A81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity_tag DROP FOREIGN KEY FK_71B0290181C06096');
        $this->addSql('ALTER TABLE activity_group DROP FOREIGN KEY FK_73C27276B37D2B8B');
        $this->addSql('ALTER TABLE application_activity DROP FOREIGN KEY FK_134FD64981C06096');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01D81C06096');
        $this->addSql('ALTER TABLE tag_activity DROP FOREIGN KEY FK_9A45728181C06096');
        $this->addSql('ALTER TABLE user_activity DROP FOREIGN KEY FK_4CF9ED5A81C06096');
        $this->addSql('ALTER TABLE activity_group_user DROP FOREIGN KEY FK_F12BB3315E5E6949');
        $this->addSql('ALTER TABLE activity_moment_activity_group DROP FOREIGN KEY FK_2F07B605E5E6949');
        $this->addSql('ALTER TABLE activity_moment_activity_group DROP FOREIGN KEY FK_2F07B604CF1B6F4');
        $this->addSql('ALTER TABLE application_activity DROP FOREIGN KEY FK_134FD6493E030ACD');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C6ED4F725');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A4A798B6F');
        $this->addSql('ALTER TABLE activity_tag DROP FOREIGN KEY FK_71B02901BAD26311');
        $this->addSql('ALTER TABLE tag_activity DROP FOREIGN KEY FK_9A457281BAD26311');
        $this->addSql('ALTER TABLE activity_group_user DROP FOREIGN KEY FK_F12BB331A76ED395');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1A76ED395');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01DA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE user_activity DROP FOREIGN KEY FK_4CF9ED5AA76ED395');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE activity_tag');
        $this->addSql('DROP TABLE activity_group');
        $this->addSql('DROP TABLE activity_group_user');
        $this->addSql('DROP TABLE activity_moment');
        $this->addSql('DROP TABLE activity_moment_activity_group');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE application_activity');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE semester');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_activity');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_activity');
    }
}
