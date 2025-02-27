<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190205132219 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Miner_Panel_Miner_Hash_Rate DROP FOREIGN KEY FK_1769152064B64DCC');
        $this->addSql('DROP INDEX IDX_1769152064B64DCC ON Miner_Panel_Miner_Hash_Rate');
        $this->addSql('ALTER TABLE Miner_Panel_Miner_Hash_Rate DROP userId');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Miner_Panel_Miner_Hash_Rate ADD userId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Miner_Panel_Miner_Hash_Rate ADD CONSTRAINT FK_1769152064B64DCC FOREIGN KEY (userId) REFERENCES Miner_Panel_User (id)');
        $this->addSql('CREATE INDEX IDX_1769152064B64DCC ON Miner_Panel_Miner_Hash_Rate (userId)');
    }
}
