<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230428135957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, genre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_genre (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, genre_id_id INT NOT NULL, INDEX IDX_6192C8A09D86650F (user_id_id), INDEX IDX_6192C8A0C2428192 (genre_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_genre ADD CONSTRAINT FK_6192C8A09D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_genre ADD CONSTRAINT FK_6192C8A0C2428192 FOREIGN KEY (genre_id_id) REFERENCES genre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_genre DROP FOREIGN KEY FK_6192C8A09D86650F');
        $this->addSql('ALTER TABLE user_genre DROP FOREIGN KEY FK_6192C8A0C2428192');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE user_genre');
    }
}
