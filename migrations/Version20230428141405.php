<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230428141405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book_genre (id INT AUTO_INCREMENT NOT NULL, book_id_id INT NOT NULL, genre_id_id INT NOT NULL, INDEX IDX_8D92268171868B2E (book_id_id), INDEX IDX_8D922681C2428192 (genre_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_book (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, book_id_id INT NOT NULL, INDEX IDX_B164EFF89D86650F (user_id_id), INDEX IDX_B164EFF871868B2E (book_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book_genre ADD CONSTRAINT FK_8D92268171868B2E FOREIGN KEY (book_id_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE book_genre ADD CONSTRAINT FK_8D922681C2428192 FOREIGN KEY (genre_id_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF89D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF871868B2E FOREIGN KEY (book_id_id) REFERENCES book (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_genre DROP FOREIGN KEY FK_8D92268171868B2E');
        $this->addSql('ALTER TABLE book_genre DROP FOREIGN KEY FK_8D922681C2428192');
        $this->addSql('ALTER TABLE user_book DROP FOREIGN KEY FK_B164EFF89D86650F');
        $this->addSql('ALTER TABLE user_book DROP FOREIGN KEY FK_B164EFF871868B2E');
        $this->addSql('DROP TABLE book_genre');
        $this->addSql('DROP TABLE user_book');
    }
}
