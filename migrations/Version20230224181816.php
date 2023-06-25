<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230224181816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE course_tag (course_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(course_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_760531B1591CC992 ON course_tag (course_id)');
        $this->addSql('CREATE INDEX IDX_760531B1BAD26311 ON course_tag (tag_id)');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, label VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE course_tag ADD CONSTRAINT FK_760531B1591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE course_tag ADD CONSTRAINT FK_760531B1BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE course ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_169E6FB912469DE2 ON course (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE course DROP CONSTRAINT FK_169E6FB912469DE2');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('ALTER TABLE course_tag DROP CONSTRAINT FK_760531B1591CC992');
        $this->addSql('ALTER TABLE course_tag DROP CONSTRAINT FK_760531B1BAD26311');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE course_tag');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP INDEX IDX_169E6FB912469DE2');
        $this->addSql('ALTER TABLE course DROP category_id');
    }
}
