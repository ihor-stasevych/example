<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181210092944 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Miner_Panel_Miner_Coin (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(100) NOT NULL, algorithmId INT DEFAULT NULL, INDEX IDX_64BAA9268D684D9D (algorithmId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Miner_Panel_Miner_Coin ADD CONSTRAINT FK_64BAA9268D684D9D FOREIGN KEY (algorithmId) REFERENCES Miner_Panel_Miner_Algorithm (id)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE Miner_Panel_Miner_Coin');
    }
}
