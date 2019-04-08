<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190325095039 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE main_board (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, add_time DATETIME DEFAULT NULL, fix_time DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE re_board (id INT AUTO_INCREMENT NOT NULL, mainboard_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, add_time DATETIME DEFAULT NULL, fix_time DATETIME DEFAULT NULL, INDEX IDX_31754CBE67EF1F11 (mainboard_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE re_board ADD CONSTRAINT FK_31754CBE67EF1F11 FOREIGN KEY (mainboard_id) REFERENCES main_board (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE re_board DROP FOREIGN KEY FK_31754CBE67EF1F11');
        $this->addSql('DROP TABLE main_board');
        $this->addSql('DROP TABLE re_board');
    }
}