<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181021032354 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(25) DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object CHANGE content_url content_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE donation ADD creator_id INT DEFAULT NULL, CHANGE photo_id photo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A061220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_31E581A061220EA6 ON donation (creator_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A061220EA6');
        $this->addSql('DROP INDEX UNIQ_31E581A061220EA6 ON donation');
        $this->addSql('ALTER TABLE donation DROP creator_id, CHANGE photo_id photo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object CHANGE content_url content_url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(25) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
