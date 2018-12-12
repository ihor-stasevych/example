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

    public function postUp(Schema $schema)
    {
        $data = [
            [
                'algorithm' => 'SHA-256',
                'coins'     => 'Bitcoin, Mastercoin, MazaCoin, Namecoin, NuBits, Peercoin, BitcoinDark, Scotcoin, Nubits, CarpeDiem, Paccoin, Tigercoin, Mazacoin, eMark, Titcoin, Neoscoin, Saffroncoin, Curecoin, Zetacoin, Acoin, TEKcoin, Unobtanium, Blakecoin, Reikicoin, Ixcoin, Bytecoin',
            ],
            [
                'algorithm' => 'Scrypt',
                'coins'     => 'Auroracoin, Coinye, Synereo, Syscoin,GameCredits, Dogecoin, Litecoin, Potcoin, Starcoin, Teslacoin, Nucoin,Topcoin, Pesetacoin, Smartcoin, Xivra, Zedcoin, Stockcoin, Foxcoin, Worldcoin, Reddcoin',
            ],
            [
                'algorithm' => 'Scrypt Adaptive-N',
                'coins'     => 'Vertcoin, Execoin, Parallaxcoin, SiliconValleycoin, GPUcoin',
            ],
            [
                'algorithm' => 'Scrypt-Jane (Scrypt-Chacha)',
                'coins'     => 'Yacoin, Ultracoin, Velocitycoin',
            ],
            [
                'algorithm' => 'X11',
                'coins'     => 'Dash, Crevacoin, Cryptcoin, Fuelcoin, Startcoin, Crevacoin, Adzcoin, Influxcoin, Cannabiscoin, Darkcoin, Hirocoin, X11coin, Smartcoin, Goldblocks',
            ],
            [
                'algorithm' => 'X13',
                'coins'     => 'Cloakcoin, Sherlockcoin, Boostcoin, Ambercoin, Navcoin, QiBuck, Networkcoin,Marucoin, X13coin',
            ],
            [
                'algorithm' => 'X15',
                'coins'     => 'X15Coin, BitBlock',
            ],
            [
                'algorithm' => 'Groestl',
                'coins'     => 'Groestlcoin, Securecoin, Myriad-Groestl',
            ],
            [
                'algorithm' => 'Quark',
                'coins'     => 'BitQuark, Diamondcoin, Animecoin',
            ],
            [
                'algorithm' => 'Qubit',
                'coins'     => 'Geocoin, DGB-Qubit, Myriad-Qubit',
            ],
            [
                'algorithm' => 'NeoScrypt',
                'coins'     => 'Feathercoin, Phoenixcoin, Orbitcoin, UFOcoin',
            ],
            [
                'algorithm' => 'SHA-3 (Keccak)',
                'coins'     => 'Maxcoin, Slothcoin, Cryptometh, NexusNiro',
            ],
            [
                'algorithm' => 'Blake-256',
                'coins'     => 'Dirac, Electron, BlakeBitcoin, Blakecoin, Photon',
            ],
            [
                'algorithm' => 'Ethash',
                'coins'     => 'Krypton, Shift, Expanse, Ethereum Classic, Ethereum',
            ],
            [
                'algorithm' => 'Lyra2REv2',
                'coins'     => 'Monacoin, Vertcoin',
            ],
            [
                'algorithm' => 'Chacha',
                'coins'     => 'Ultracoin',
            ],
        ];

        foreach ($data as $d) {
            $this->connection->insert('Miner_Panel_Miner_Algorithm', [
                'value' => $d['algorithm']
            ]);
            $minerAlgorithmId = $this->connection->lastInsertId();

            $coins = explode(',', $d['coins']);

            foreach ($coins as $coin) {
                $this->connection->insert('Miner_Panel_Miner_Coin', [
                    'value'       => trim($coin),
                    'algorithmId' => $minerAlgorithmId,
                ]);
            }
        }
    }
}
