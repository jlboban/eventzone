<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201023081939 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_musician (event_id INT NOT NULL, musician_id INT NOT NULL, INDEX IDX_779DA8F771F7E88B (event_id), INDEX IDX_779DA8F79523AA8A (musician_id), PRIMARY KEY(event_id, musician_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_venue (event_id INT NOT NULL, venue_id INT NOT NULL, INDEX IDX_D08AAA2171F7E88B (event_id), INDEX IDX_D08AAA2140A73EBA (venue_id), PRIMARY KEY(event_id, venue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_musician ADD CONSTRAINT FK_779DA8F771F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_musician ADD CONSTRAINT FK_779DA8F79523AA8A FOREIGN KEY (musician_id) REFERENCES musician (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_venue ADD CONSTRAINT FK_D08AAA2171F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_venue ADD CONSTRAINT FK_D08AAA2140A73EBA FOREIGN KEY (venue_id) REFERENCES venue (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE event_musician');
        $this->addSql('DROP TABLE event_venue');
    }
}
