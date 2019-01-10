<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181207114107 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Miner_Panel_Miner_Type (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Miner_Panel_Miner_Algorithm (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Miner_Panel_Miner (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, ip VARCHAR(100) NOT NULL, port INT NOT NULL, userId INT DEFAULT NULL, typeId INT DEFAULT NULL, algorithmId INT DEFAULT NULL, INDEX IDX_A82393FB64B64DCC (userId), INDEX IDX_A82393FB9BF49490 (typeId), INDEX IDX_A82393FB8D684D9D (algorithmId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Miner_Panel_User (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Miner_Panel_Miner ADD CONSTRAINT FK_A82393FB64B64DCC FOREIGN KEY (userId) REFERENCES Miner_Panel_User (id)');
        $this->addSql('ALTER TABLE Miner_Panel_Miner ADD CONSTRAINT FK_A82393FB9BF49490 FOREIGN KEY (typeId) REFERENCES Miner_Panel_Miner_Type (id)');
        $this->addSql('ALTER TABLE Miner_Panel_Miner ADD CONSTRAINT FK_A82393FB8D684D9D FOREIGN KEY (algorithmId) REFERENCES Miner_Panel_Miner_Algorithm (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Miner_Panel_Miner DROP FOREIGN KEY FK_A82393FB9BF49490');
        $this->addSql('ALTER TABLE Miner_Panel_Miner DROP FOREIGN KEY FK_A82393FB8D684D9D');
        $this->addSql('ALTER TABLE Miner_Panel_Miner DROP FOREIGN KEY FK_A82393FB64B64DCC');
        $this->addSql('DROP TABLE Miner_Panel_Miner_Type');
        $this->addSql('DROP TABLE Miner_Panel_Miner_Algorithm');
        $this->addSql('DROP TABLE Miner_Panel_Miner');
        $this->addSql('DROP TABLE Miner_Panel_User');
    }
}
