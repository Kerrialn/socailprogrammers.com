<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210927151417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD host_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA71FB8D185 FOREIGN KEY (host_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA71FB8D185 ON event (host_id)');
        $this->addSql('ALTER TABLE participant DROP is_host');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA71FB8D185');
        $this->addSql('DROP INDEX IDX_3BAE0AA71FB8D185 ON event');
        $this->addSql('ALTER TABLE event DROP host_id');
        $this->addSql('ALTER TABLE participant ADD is_host TINYINT(1) NOT NULL');
    }
}
