<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181221160247 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Miner_Panel_Miner_Coin CHANGE difficulty difficulty DOUBLE PRECISION DEFAULT \'0\' NOT NULL, CHANGE reward reward DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Miner_Panel_Miner_Coin CHANGE difficulty difficulty VARCHAR(255) DEFAULT \'0\' NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE reward reward VARCHAR(255) DEFAULT \'0\' NOT NULL COLLATE utf8mb4_unicode_ci');
    }

    public function preUp(Schema $schema): void
    {
        $coins = $this->connection->fetchAll('SELECT id, difficulty, reward FROM Miner_Panel_Miner_Coin');

        foreach ($coins as $coin) {
            $difficulty = $coin['difficulty'];
            $reward = $coin['reward'];
            $flagUpdate = false;

            if (true === empty($coin['difficulty']) || false === is_numeric($coin['difficulty'])) {
                $difficulty = (float) $coin['difficulty'];
                $flagUpdate = true;
            }

            if (true === empty($coin['reward']) || false === is_numeric($coin['reward'])) {
                $reward = (float) $coin['reward'];
                $flagUpdate = true;
            }

            if (true === $flagUpdate) {
                $this->connection->update('Miner_Panel_Miner_Coin', ['difficulty' => $difficulty, 'reward' => $reward], ['id' => $coin['id']]);
            }
        }
    }
}
