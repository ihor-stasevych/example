<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190128134231 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }

    public function preUp(Schema $schema): void
    {
        $this->connection->insert('Scheduler_Task', [
            'name'            => 'Miner pool status update',
            'command'         => 'app:miner-pool-status-update',
            'arguments'       => '',
            'cronExpression'  => '*/1 * * * *',
            'status'          => 2,
            'type'            => 1,
            'disabled'        => 0,
            'repeatAfterFail' => 0,
        ]);
    }
}
