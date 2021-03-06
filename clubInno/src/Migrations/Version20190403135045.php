<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190403135045 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01D81C06096');
        $this->addSql('DROP INDEX IDX_BA5AE01D81C06096 ON blog_post');
        $this->addSql('ALTER TABLE blog_post CHANGE activity_id activity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01DAC74095A FOREIGN KEY (activity) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_BA5AE01DAC74095A ON blog_post (activity)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01DAC74095A');
        $this->addSql('DROP INDEX IDX_BA5AE01DAC74095A ON blog_post');
        $this->addSql('ALTER TABLE blog_post CHANGE activity activity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01D81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_BA5AE01D81C06096 ON blog_post (activity_id)');
    }
}
