<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210923195138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', starts_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ends_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programming_language (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_programming_language (user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', programming_language_id INT NOT NULL, INDEX IDX_79046A86A76ED395 (user_id), INDEX IDX_79046A86A2574C1E (programming_language_id), PRIMARY KEY(user_id, programming_language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_programming_language ADD CONSTRAINT FK_79046A86A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_programming_language ADD CONSTRAINT FK_79046A86A2574C1E FOREIGN KEY (programming_language_id) REFERENCES programming_language (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_programming_language DROP FOREIGN KEY FK_79046A86A2574C1E');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE programming_language');
        $this->addSql('DROP TABLE user_programming_language');
    }
}
