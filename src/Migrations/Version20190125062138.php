<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190125062138 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Miner_Panel_Miner_Hash_Rate (id INT AUTO_INCREMENT NOT NULL, value DOUBLE PRECISION NOT NULL, createdAt DATETIME NOT NULL, userId INT DEFAULT NULL, algorithmId INT DEFAULT NULL, INDEX IDX_1769152064B64DCC (userId), INDEX IDX_176915208D684D9D (algorithmId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Miner_Panel_Miner_Hash_Rate ADD CONSTRAINT FK_1769152064B64DCC FOREIGN KEY (userId) REFERENCES Miner_Panel_User (id)');
        $this->addSql('ALTER TABLE Miner_Panel_Miner_Hash_Rate ADD CONSTRAINT FK_176915208D684D9D FOREIGN KEY (algorithmId) REFERENCES Miner_Panel_Miner_Algorithm (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Miner_Panel_Miner_Config (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE Miner_Panel_Miner_Hash_Rate');
    }
}
