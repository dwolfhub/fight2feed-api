<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181101005212 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE reset_token (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, token VARCHAR(255) NOT NULL, expiration_date DATETIME NOT NULL, INDEX IDX_D7C8DC1961220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_token ADD CONSTRAINT FK_D7C8DC1961220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(25) DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object CHANGE content_url content_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE donation CHANGE photo_id photo_id INT DEFAULT NULL, CHANGE address_id address_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE reset_token');
        $this->addSql('ALTER TABLE donation CHANGE photo_id photo_id INT DEFAULT NULL, CHANGE address_id address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object CHANGE content_url content_url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(25) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
