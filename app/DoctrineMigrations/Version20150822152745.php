<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150822152745 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE topics (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB');
        $this->addSql('CREATE TABLE topics_article (topic_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_E48613AA1F55203D (topic_id), INDEX IDX_E48613AA7294869C (article_id), PRIMARY KEY(topic_id, article_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(100) NOT NULL, text LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB');
        $this->addSql('ALTER TABLE topics_article ADD CONSTRAINT FK_E48613AA1F55203D FOREIGN KEY (topic_id) REFERENCES topics (id)');
        $this->addSql('ALTER TABLE topics_article ADD CONSTRAINT FK_E48613AA7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE topics_article DROP FOREIGN KEY FK_E48613AA1F55203D');
        $this->addSql('ALTER TABLE topics_article DROP FOREIGN KEY FK_E48613AA7294869C');
        $this->addSql('DROP TABLE topics');
        $this->addSql('DROP TABLE topics_article');
        $this->addSql('DROP TABLE articles');
    }
}
