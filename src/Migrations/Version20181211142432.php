<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181211142432 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Miner_Panel_Package (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, description VARCHAR(1024) NOT NULL, price DOUBLE PRECISION NOT NULL,  isDefaultPackage SMALLINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Miner_Panel_Package_Options (id INT AUTO_INCREMENT NOT NULL, maxAllowedMiners INT NOT NULL, packageId INT DEFAULT NULL, INDEX IDX_C2925D6DF55D173E (packageId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Miner_Panel_Package_Options ADD CONSTRAINT FK_C2925D6DF55D173E FOREIGN KEY (packageId) REFERENCES Miner_Panel_Package (id)');
        $this->addSql('ALTER TABLE Miner_Panel_User ADD packageId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Miner_Panel_User ADD CONSTRAINT FK_AF1DDB23F55D173E FOREIGN KEY (packageId) REFERENCES Miner_Panel_Package (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AF1DDB23F55D173E ON Miner_Panel_User (packageId)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Miner_Panel_User DROP FOREIGN KEY FK_AF1DDB23F55D173E');
        $this->addSql('ALTER TABLE Miner_Panel_Package_Options DROP FOREIGN KEY FK_C2925D6DF55D173E');
        $this->addSql('ALTER TABLE User_PasswordRecovery ADD CONSTRAINT FK_AB105D9564B64DCC FOREIGN KEY (userId) REFERENCES User (id)');
        $this->addSql('DROP TABLE Miner_Panel_Package');
        $this->addSql('DROP TABLE Miner_Panel_Package_Options');
        $this->addSql('DROP INDEX UNIQ_AF1DDB23F55D173E ON Miner_Panel_User');
        $this->addSql('ALTER TABLE Miner_Panel_User DROP packageId');
    }

    public function postUp(Schema $schema): void
    {
	    $this->connection->insert('Miner_Panel_Package', [
		    'title' => 'Free package',
		    'description' => 'Free package default',
		    'price' => 0,
		    'isDefaultPackage' => true
	    ]);

	    $packId = $this->connection->lastInsertId();

	    $this->connection->insert('Miner_Panel_Package_Options', [
		    'maxAllowedMiners'       => 200,
		    'packageId' => $packId
	    ]);
    }
}
