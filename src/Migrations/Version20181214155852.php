<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181214155852 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Miner_Panel_Miner_Credential (id INT AUTO_INCREMENT NOT NULL, ip VARCHAR(100) NOT NULL, port INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Miner_Panel_Miner ADD credentialId INT DEFAULT NULL, DROP ip, DROP port');
        $this->addSql('ALTER TABLE Miner_Panel_Miner ADD CONSTRAINT FK_A82393FB5A36BC9A FOREIGN KEY (credentialId) REFERENCES Miner_Panel_Miner_Credential (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A82393FB5A36BC9A ON Miner_Panel_Miner (credentialId)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Miner_Panel_Miner DROP FOREIGN KEY FK_A82393FB5A36BC9A');
        $this->addSql('DROP TABLE Miner_Panel_Miner_Credential');
        $this->addSql('DROP INDEX UNIQ_A82393FB5A36BC9A ON Miner_Panel_Miner');
        $this->addSql('ALTER TABLE Miner_Panel_Miner ADD ip VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, ADD port INT NOT NULL, DROP credentialId');
    }
}
