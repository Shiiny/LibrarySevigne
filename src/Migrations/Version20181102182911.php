<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181102182911 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, date_register DATETIME DEFAULT NULL, token VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('DROP INDEX IDX_CBE5A33112469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__book AS SELECT id, category_id, title, content, year_book, updated_at, author_firstname, author_lastname FROM book');
        $this->addSql('DROP TABLE book');
        $this->addSql('CREATE TABLE book (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, content CLOB DEFAULT NULL COLLATE BINARY, year_book INTEGER NOT NULL, updated_at DATETIME DEFAULT NULL, author_firstname VARCHAR(255) NOT NULL COLLATE BINARY, author_lastname VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_CBE5A33112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO book (id, category_id, title, content, year_book, updated_at, author_firstname, author_lastname) SELECT id, category_id, title, content, year_book, updated_at, author_firstname, author_lastname FROM __temp__book');
        $this->addSql('DROP TABLE __temp__book');
        $this->addSql('CREATE INDEX IDX_CBE5A33112469DE2 ON book (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_CBE5A33112469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__book AS SELECT id, category_id, title, content, year_book, updated_at, author_firstname, author_lastname FROM book');
        $this->addSql('DROP TABLE book');
        $this->addSql('CREATE TABLE book (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, content CLOB DEFAULT NULL, year_book INTEGER NOT NULL, updated_at DATETIME DEFAULT NULL, author_firstname VARCHAR(255) NOT NULL, author_lastname VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO book (id, category_id, title, content, year_book, updated_at, author_firstname, author_lastname) SELECT id, category_id, title, content, year_book, updated_at, author_firstname, author_lastname FROM __temp__book');
        $this->addSql('DROP TABLE __temp__book');
        $this->addSql('CREATE INDEX IDX_CBE5A33112469DE2 ON book (category_id)');
    }
}
