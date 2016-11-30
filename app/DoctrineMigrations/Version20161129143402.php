<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161129143402 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE validated_object (id INT AUTO_INCREMENT NOT NULL, parent_object_id INT DEFAULT NULL, property1 VARCHAR(255) DEFAULT NULL, property2 VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_7FCC03C7BF396750 (id), INDEX IDX_7FCC03C7D26679C5 (parent_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE validated_object ADD CONSTRAINT FK_7FCC03C7D26679C5 FOREIGN KEY (parent_object_id) REFERENCES validated_object (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE validated_object DROP FOREIGN KEY FK_7FCC03C7D26679C5');
        $this->addSql('DROP TABLE validated_object');
    }
}
