<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190123153006 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Miner_Panel_Miner_Config (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Miner_Panel_Miner ADD configId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Miner_Panel_Miner ADD CONSTRAINT FK_A82393FB2E69CDB FOREIGN KEY (configId) REFERENCES Miner_Panel_Miner_Config (id)');
        $this->addSql('CREATE INDEX IDX_A82393FB2E69CDB ON Miner_Panel_Miner (configId)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Miner_Panel_Miner DROP FOREIGN KEY FK_A82393FB2E69CDB');
        $this->addSql('DROP TABLE Miner_Panel_Miner_Config');
        $this->addSql('DROP INDEX IDX_A82393FB2E69CDB ON Miner_Panel_Miner');
        $this->addSql('ALTER TABLE Miner_Panel_Miner DROP configId');
    }
}
