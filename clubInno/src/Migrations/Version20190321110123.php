<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190321110123 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, max_amount_students INT NOT NULL, registration_deadline DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT NULL, main_image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_application (activity_id INT NOT NULL, application_id INT NOT NULL, INDEX IDX_AAD5B7CD81C06096 (activity_id), INDEX IDX_AAD5B7CD3E030ACD (application_id), PRIMARY KEY(activity_id, application_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_tag (activity_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_71B0290181C06096 (activity_id), INDEX IDX_71B02901BAD26311 (tag_id), PRIMARY KEY(activity_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE application (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date DATETIME NOT NULL, motivation_letter_path VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE applications_activities (application_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_624AE4863E030ACD (application_id), INDEX IDX_624AE48681C06096 (activity_id), PRIMARY KEY(application_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_post (id INT AUTO_INCREMENT NOT NULL, activity_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, INDEX IDX_BA5AE01D81C06096 (activity_id), INDEX IDX_BA5AE01DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, body LONGTEXT NOT NULL, datetime DATETIME NOT NULL, BlogPost_id INT DEFAULT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C6ED4F725 (BlogPost_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags_activities (tag_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_D748B304BAD26311 (tag_id), INDEX IDX_D748B30481C06096 (activity_id), PRIMARY KEY(tag_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_application ADD CONSTRAINT FK_AAD5B7CD81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_application ADD CONSTRAINT FK_AAD5B7CD3E030ACD FOREIGN KEY (application_id) REFERENCES application (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_tag ADD CONSTRAINT FK_71B0290181C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_tag ADD CONSTRAINT FK_71B02901BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE applications_activities ADD CONSTRAINT FK_624AE4863E030ACD FOREIGN KEY (application_id) REFERENCES application (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE applications_activities ADD CONSTRAINT FK_624AE48681C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01D81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C6ED4F725 FOREIGN KEY (BlogPost_id) REFERENCES blog_post (id)');
        $this->addSql('ALTER TABLE tags_activities ADD CONSTRAINT FK_D748B304BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_activities ADD CONSTRAINT FK_D748B30481C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity_application DROP FOREIGN KEY FK_AAD5B7CD81C06096');
        $this->addSql('ALTER TABLE activity_tag DROP FOREIGN KEY FK_71B0290181C06096');
        $this->addSql('ALTER TABLE applications_activities DROP FOREIGN KEY FK_624AE48681C06096');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01D81C06096');
        $this->addSql('ALTER TABLE tags_activities DROP FOREIGN KEY FK_D748B30481C06096');
        $this->addSql('ALTER TABLE activity_application DROP FOREIGN KEY FK_AAD5B7CD3E030ACD');
        $this->addSql('ALTER TABLE applications_activities DROP FOREIGN KEY FK_624AE4863E030ACD');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C6ED4F725');
        $this->addSql('ALTER TABLE activity_tag DROP FOREIGN KEY FK_71B02901BAD26311');
        $this->addSql('ALTER TABLE tags_activities DROP FOREIGN KEY FK_D748B304BAD26311');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01DA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE activity_application');
        $this->addSql('DROP TABLE activity_tag');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE applications_activities');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tags_activities');
        $this->addSql('DROP TABLE user');
    }
}
