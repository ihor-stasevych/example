<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181107152326 extends AbstractMigration
{
    public function up(Schema $schema): void
    {

        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Miner_Algorithm (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Miner_Stock_Pool (id INT AUTO_INCREMENT NOT NULL, userName VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, minerStockId INT DEFAULT NULL, INDEX IDX_32001DCD4E8714B1 (minerStockId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Miner_Stock_Config (id INT AUTO_INCREMENT NOT NULL, dir VARCHAR(255) NOT NULL, fileName VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Miner_Stock_Pool ADD CONSTRAINT FK_32001DCD4E8714B1 FOREIGN KEY (minerStockId) REFERENCES Miner_Stock (id)');

        $configData = $this->connection->fetchAll('SELECT id, configName FROM Miner_Stock');

        foreach ($configData as $config) {

        }

        $this->addSql('ALTER TABLE Miner_Stock ADD configId INT DEFAULT NULL, DROP port, DROP user, DROP configName, CHANGE minerId minerId INT DEFAULT NULL, CHANGE productId productId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Miner_Stock ADD CONSTRAINT FK_9DF9CB0E2E69CDB FOREIGN KEY (configId) REFERENCES Miner_Stock_Config (id)');
        $this->addSql('CREATE INDEX IDX_9DF9CB0E2E69CDB ON Miner_Stock (configId)');
        $this->addSql('ALTER TABLE Miner_Allowed_Url ADD algorithmId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Miner_Allowed_Url ADD CONSTRAINT FK_8388827D8D684D9D FOREIGN KEY (algorithmId) REFERENCES Miner_Algorithm (id)');
        $this->addSql('CREATE INDEX IDX_8388827D8D684D9D ON Miner_Allowed_Url (algorithmId)');
        $this->addSql('ALTER TABLE Miner ADD hashRate VARCHAR(255) NOT NULL, ADD powerRate VARCHAR(255) NOT NULL, ADD powerEfficiency VARCHAR(255) NOT NULL, ADD ratedVoltage VARCHAR(255) NOT NULL, ADD operatingTemperature VARCHAR(255) NOT NULL, ADD algorithmId INT DEFAULT NULL, DROP hash_rate, DROP power_rate, DROP power_efficiency, DROP rated_voltage, DROP operating_temperature, DROP algorithm, CHANGE description description JSON NOT NULL');
        $this->addSql('ALTER TABLE Miner ADD CONSTRAINT FK_C4A7BAB48D684D9D FOREIGN KEY (algorithmId) REFERENCES Miner_Algorithm (id)');
        $this->addSql('CREATE INDEX IDX_C4A7BAB48D684D9D ON Miner (algorithmId)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Miner_Allowed_Url DROP FOREIGN KEY FK_8388827D8D684D9D');
        $this->addSql('ALTER TABLE Miner DROP FOREIGN KEY FK_C4A7BAB48D684D9D');
        $this->addSql('ALTER TABLE Miner_Stock DROP FOREIGN KEY FK_9DF9CB0E2E69CDB');
        $this->addSql('DROP TABLE Miner_Algorithm');
        $this->addSql('DROP TABLE Miner_Stock_Pool');
        $this->addSql('DROP TABLE Miner_Stock_Config');
        $this->addSql('DROP INDEX IDX_C4A7BAB48D684D9D ON Miner');
        $this->addSql('ALTER TABLE Miner ADD hash_rate VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD power_rate VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD power_efficiency VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD rated_voltage VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD operating_temperature VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD algorithm VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP hashRate, DROP powerRate, DROP powerEfficiency, DROP ratedVoltage, DROP operatingTemperature, DROP algorithmId, CHANGE description description LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('DROP INDEX IDX_8388827D8D684D9D ON Miner_Allowed_Url');
        $this->addSql('ALTER TABLE Miner_Allowed_Url DROP algorithmId');
        $this->addSql('DROP INDEX IDX_9DF9CB0E2E69CDB ON Miner_Stock');
        $this->addSql('ALTER TABLE Miner_Stock ADD port INT NOT NULL, ADD user VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD configName VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP configId, CHANGE minerId minerId INT DEFAULT NULL, CHANGE productId productId INT DEFAULT NULL');
    }
}
