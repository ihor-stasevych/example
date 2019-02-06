<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190130125221 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Miner_Panel_Miner ADD CONSTRAINT FK_A82393FB2E69CDB FOREIGN KEY (configId) REFERENCES Miner_Panel_Miner_Config (id)');
        $this->addSql('CREATE INDEX IDX_A82393FB2E69CDB ON Miner_Panel_Miner (configId)');
        $this->addSql('ALTER TABLE Miner_Panel_Miner_Hash_Rate DROP FOREIGN KEY FK_176915208D684D9D');
        $this->addSql('DROP INDEX IDX_176915208D684D9D ON Miner_Panel_Miner_Hash_Rate');
        $this->addSql('ALTER TABLE Miner_Panel_Miner_Hash_Rate CHANGE algorithmid minerId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Miner_Panel_Miner_Hash_Rate ADD CONSTRAINT FK_176915204E1D8FE7 FOREIGN KEY (minerId) REFERENCES Miner_Panel_Miner (id)');
        $this->addSql('CREATE INDEX IDX_176915204E1D8FE7 ON Miner_Panel_Miner_Hash_Rate (minerId)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Miner_Panel_Miner DROP FOREIGN KEY FK_A82393FB2E69CDB');
        $this->addSql('DROP INDEX IDX_A82393FB2E69CDB ON Miner_Panel_Miner');
        $this->addSql('ALTER TABLE Miner_Panel_Miner_Hash_Rate DROP FOREIGN KEY FK_176915204E1D8FE7');
        $this->addSql('DROP INDEX IDX_176915204E1D8FE7 ON Miner_Panel_Miner_Hash_Rate');
        $this->addSql('ALTER TABLE Miner_Panel_Miner_Hash_Rate CHANGE minerid algorithmId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Miner_Panel_Miner_Hash_Rate ADD CONSTRAINT FK_176915208D684D9D FOREIGN KEY (algorithmId) REFERENCES Miner_Panel_Miner_Algorithm (id)');
        $this->addSql('CREATE INDEX IDX_176915208D684D9D ON Miner_Panel_Miner_Hash_Rate (algorithmId)');
    }
}
