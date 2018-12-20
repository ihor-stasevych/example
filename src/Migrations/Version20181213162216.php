<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181213162216 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Miner_Panel_Miner_Rig (minerId INT NOT NULL, rigId INT NOT NULL, INDEX IDX_726DD5974E1D8FE7 (minerId), INDEX IDX_726DD59725E27093 (rigId), PRIMARY KEY(minerId, rigId)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Miner_Panel_Rig (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, worker VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, userId INT DEFAULT NULL, INDEX IDX_43C6928F64B64DCC (userId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Miner_Panel_Miner_Rig ADD CONSTRAINT FK_726DD5974E1D8FE7 FOREIGN KEY (minerId) REFERENCES Miner_Panel_Miner (id)');
        $this->addSql('ALTER TABLE Miner_Panel_Miner_Rig ADD CONSTRAINT FK_726DD59725E27093 FOREIGN KEY (rigId) REFERENCES Miner_Panel_Rig (id)');
        $this->addSql('ALTER TABLE Miner_Panel_Rig ADD CONSTRAINT FK_43C6928F64B64DCC FOREIGN KEY (userId) REFERENCES Miner_Panel_User (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Miner_Panel_Miner_Rig DROP FOREIGN KEY FK_726DD59725E27093');
        $this->addSql('DROP TABLE Miner_Panel_Miner_Rig');
        $this->addSql('DROP TABLE Miner_Panel_Rig');
    }
}
