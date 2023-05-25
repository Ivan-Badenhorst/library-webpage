<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230525114241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book_reviews (id INT AUTO_INCREMENT NOT NULL, book_id_id INT NOT NULL, user_id_id INT NOT NULL, score INT NOT NULL, comment VARCHAR(2000) DEFAULT NULL, date_added DATETIME NOT NULL, INDEX IDX_FA50C39971868B2E (book_id_id), INDEX IDX_FA50C3999D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book_reviews ADD CONSTRAINT FK_FA50C39971868B2E FOREIGN KEY (book_id_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE book_reviews ADD CONSTRAINT FK_FA50C3999D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('DROP INDEX isbn_UNIQUE ON book');
        $this->addSql('ALTER TABLE book DROP loginTries');
        $this->addSql('ALTER TABLE user DROP login_tries');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_reviews DROP FOREIGN KEY FK_FA50C39971868B2E');
        $this->addSql('ALTER TABLE book_reviews DROP FOREIGN KEY FK_FA50C3999D86650F');
        $this->addSql('DROP TABLE book_reviews');
        $this->addSql('ALTER TABLE book ADD loginTries INT DEFAULT 0 NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX isbn_UNIQUE ON book (isbn)');
        $this->addSql('ALTER TABLE user ADD login_tries INT NOT NULL');
    }
}
