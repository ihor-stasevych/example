<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180720092801 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Store_Order CHANGE updatedAt updatedAt DATETIME DEFAULT NULL, CHANGE userId userId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Roles CHANGE permissions permissions JSON NOT NULL');
        $this->addSql('ALTER TABLE User CHANGE user_name user_name VARCHAR(255) DEFAULT \'\' NOT NULL, CHANGE email email VARCHAR(255) DEFAULT \'\' NOT NULL, CHANGE backup_email backup_email VARCHAR(255) DEFAULT \'\' NOT NULL, CHANGE first_name first_name VARCHAR(255) DEFAULT \'\' NOT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT \'\' NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE roles roles JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE Store_Order_Item CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE Store_Product CHANGE description description JSON NOT NULL, CHANGE tech_details tech_details JSON NOT NULL');
        $this->addSql('ALTER TABLE Miner CHANGE description description JSON NOT NULL, CHANGE productId productId INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Miner CHANGE description description LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE productId productId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Roles CHANGE permissions permissions LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE Store_Order CHANGE updatedAt updatedAt DATETIME DEFAULT \'NULL\', CHANGE userId userId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Store_Order_Item CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE Store_Product CHANGE description description LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE tech_details tech_details LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE User CHANGE user_name user_name VARCHAR(255) DEFAULT \'\'\'\' NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE email email VARCHAR(255) DEFAULT \'\'\'\' NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE backup_email backup_email VARCHAR(255) DEFAULT \'\'\'\' NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE first_name first_name VARCHAR(255) DEFAULT \'\'\'\' NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE last_name last_name VARCHAR(255) DEFAULT \'\'\'\' NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE roles roles LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, CHANGE created_at created_at DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
    }
}
