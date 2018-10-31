<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181031131443 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE address ADD street_address VARCHAR(255) NOT NULL, ADD address_locality VARCHAR(255) NOT NULL, ADD address_region VARCHAR(255) NOT NULL, ADD postal_code VARCHAR(255) NOT NULL, ADD address_country VARCHAR(255) NOT NULL, DROP line1, DROP line2, DROP city, DROP state, DROP zip');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(25) DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object CHANGE content_url content_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE donation CHANGE photo_id photo_id INT DEFAULT NULL, CHANGE address_id address_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE address ADD line1 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD line2 VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, ADD city VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD state VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD zip VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP street_address, DROP address_locality, DROP address_region, DROP postal_code, DROP address_country');
        $this->addSql('ALTER TABLE donation CHANGE photo_id photo_id INT DEFAULT NULL, CHANGE address_id address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object CHANGE content_url content_url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(25) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
