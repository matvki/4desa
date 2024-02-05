<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240205130830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INT IDENTITY NOT NULL, email NVARCHAR(255) NOT NULL, roles VARCHAR(MAX) NOT NULL, password NVARCHAR(255) NOT NULL, pseudo NVARCHAR(255) NOT NULL, description VARBINARY(MAX), private BIT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A4E7927C74 ON account (email) WHERE email IS NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A486CC499D ON account (pseudo) WHERE pseudo IS NOT NULL');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'account\', N\'COLUMN\', roles');
        $this->addSql('CREATE TABLE comment (id INT IDENTITY NOT NULL, post_id INT NOT NULL, writer_id INT NOT NULL, content VARBINARY(MAX) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_9474526C4B89032C ON comment (post_id)');
        $this->addSql('CREATE INDEX IDX_9474526C1BC7E6B6 ON comment (writer_id)');
        $this->addSql('CREATE TABLE follow (id INT IDENTITY NOT NULL, followed_id INT NOT NULL, follower_id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_68344470D956F010 ON follow (followed_id)');
        $this->addSql('CREATE INDEX IDX_68344470AC24F853 ON follow (follower_id)');
        $this->addSql('CREATE TABLE media (id INT IDENTITY NOT NULL, post_id INT, picture VARBINARY(MAX) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A2CA10C4B89032C ON media (post_id) WHERE post_id IS NOT NULL');
        $this->addSql('CREATE TABLE post (id INT IDENTITY NOT NULL, belongs_to_id INT NOT NULL, description VARBINARY(MAX) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D33C857F5 ON post (belongs_to_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT IDENTITY NOT NULL, body VARCHAR(MAX) NOT NULL, headers VARCHAR(MAX) NOT NULL, queue_name NVARCHAR(190) NOT NULL, created_at DATETIME2(6) NOT NULL, available_at DATETIME2(6) NOT NULL, delivered_at DATETIME2(6), PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', created_at');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', available_at');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', delivered_at');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C1BC7E6B6 FOREIGN KEY (writer_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_68344470D956F010 FOREIGN KEY (followed_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_68344470AC24F853 FOREIGN KEY (follower_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D33C857F5 FOREIGN KEY (belongs_to_id) REFERENCES account (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA db_accessadmin');
        $this->addSql('CREATE SCHEMA db_backupoperator');
        $this->addSql('CREATE SCHEMA db_datareader');
        $this->addSql('CREATE SCHEMA db_datawriter');
        $this->addSql('CREATE SCHEMA db_ddladmin');
        $this->addSql('CREATE SCHEMA db_denydatareader');
        $this->addSql('CREATE SCHEMA db_denydatawriter');
        $this->addSql('CREATE SCHEMA db_owner');
        $this->addSql('CREATE SCHEMA db_securityadmin');
        $this->addSql('CREATE SCHEMA dbo');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C1BC7E6B6');
        $this->addSql('ALTER TABLE follow DROP CONSTRAINT FK_68344470D956F010');
        $this->addSql('ALTER TABLE follow DROP CONSTRAINT FK_68344470AC24F853');
        $this->addSql('ALTER TABLE media DROP CONSTRAINT FK_6A2CA10C4B89032C');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8D33C857F5');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE follow');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
