<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181031130847 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE address CHANGE line2 line2 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(25) DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object CHANGE content_url content_url VARCHAR(255) DEFAULT NULL, CHANGE creator_id creator_id INT NOT NULL');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D4313261220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_14D4313261220EA6 ON media_object (creator_id)');
        $this->addSql('ALTER TABLE donation CHANGE photo_id photo_id INT DEFAULT NULL, CHANGE address_id address_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE address CHANGE line2 line2 VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE donation CHANGE photo_id photo_id INT DEFAULT NULL, CHANGE address_id address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object DROP FOREIGN KEY FK_14D4313261220EA6');
        $this->addSql('DROP INDEX IDX_14D4313261220EA6 ON media_object');
        $this->addSql('ALTER TABLE media_object CHANGE creator_id creator_id VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE content_url content_url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(25) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
