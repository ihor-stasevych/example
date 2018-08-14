<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180810152718 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('INSERT INTO User (id, user_name, email, backup_email, first_name, last_name, password, roles, phone, created_at, updated_at, token) VALUES (5, \'2qwerty@gmail.com\', \'2qwerty@gmail.com\', \'2qwerty@gmail.com\', \'\', \'\', \'$2y$12$B977EDheZVSSeTk0i/RQIOrTok0UPLQVVqn9N8w7XBGJNQVU3Vej2\', \'["ROLE_USER"]\', \'\', \'2018-08-10 09:42:33\', \'2018-08-10 09:42:33\', \'da59f4a0-da81-40ea-a0b3-90cc08f8b30e\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
