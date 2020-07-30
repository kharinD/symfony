<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200730125431 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT fk_9474526c9d86650f');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE conference_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE conference (id INT NOT NULL, city VARCHAR(255) NOT NULL, year VARCHAR(4) NOT NULL, is_international BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP INDEX idx_9474526c9d86650f');
        $this->addSql('ALTER TABLE comment ADD author VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE comment ADD text TEXT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE comment ADD photo_filename VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE comment DROP content');
        $this->addSql('ALTER TABLE comment DROP is_active');
        $this->addSql('ALTER TABLE comment RENAME COLUMN user_id_id TO conference_id');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9474526C604B8382 ON comment (conference_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C604B8382');
        $this->addSql('DROP SEQUENCE conference_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE post (id INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_published BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON "user" (email)');
        $this->addSql('DROP TABLE conference');
        $this->addSql('DROP INDEX IDX_9474526C604B8382');
        $this->addSql('ALTER TABLE comment ADD content VARCHAR(1000) NOT NULL');
        $this->addSql('ALTER TABLE comment ADD is_active BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE comment DROP author');
        $this->addSql('ALTER TABLE comment DROP text');
        $this->addSql('ALTER TABLE comment DROP email');
        $this->addSql('ALTER TABLE comment DROP photo_filename');
        $this->addSql('ALTER TABLE comment RENAME COLUMN conference_id TO user_id_id');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_9474526c9d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9474526c9d86650f ON comment (user_id_id)');
    }
}
