<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240205091949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE test');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CA76ED395');
        $this->addSql('DROP INDEX IDX_9474526CA76ED395 ON comment');
        $this->addSql('ALTER TABLE comment ADD writer_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD content VARBINARY(MAX) NOT NULL');
        $this->addSql('ALTER TABLE comment DROP COLUMN user_id');
        $this->addSql('ALTER TABLE comment DROP COLUMN text');
        $this->addSql('ALTER TABLE comment ALTER COLUMN post_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C1BC7E6B6 FOREIGN KEY (writer_id) REFERENCES [user] (id)');
        $this->addSql('CREATE INDEX IDX_9474526C1BC7E6B6 ON comment (writer_id)');
        $this->addSql('ALTER TABLE follow DROP CONSTRAINT FK_68344470A76ED395');
        $this->addSql('DROP INDEX IDX_68344470A76ED395 ON follow');
        $this->addSql('ALTER TABLE follow ADD followed_id INT NOT NULL');
        $this->addSql('ALTER TABLE follow DROP COLUMN user_id');
        $this->addSql('ALTER TABLE follow ALTER COLUMN follower_id INT NOT NULL');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_68344470D956F010 FOREIGN KEY (followed_id) REFERENCES [user] (id)');
        $this->addSql('CREATE INDEX IDX_68344470D956F010 ON follow (followed_id)');
        $this->addSql('DROP INDEX IDX_6A2CA10C4B89032C ON media');
        $this->addSql('ALTER TABLE media ADD picture VARBINARY(MAX) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A2CA10C4B89032C ON media (post_id) WHERE post_id IS NOT NULL');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DA76ED395');
        $this->addSql('DROP INDEX IDX_5A8A6C8DA76ED395 ON post');
        $this->addSql('ALTER TABLE post ADD belongs_to_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD description VARBINARY(MAX) NOT NULL');
        $this->addSql('ALTER TABLE post DROP COLUMN user_id');
        $this->addSql('ALTER TABLE post DROP COLUMN content');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D33C857F5 FOREIGN KEY (belongs_to_id) REFERENCES [user] (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D33C857F5 ON post (belongs_to_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON [user]');
        $this->addSql('ALTER TABLE [user] ADD roles VARCHAR(MAX) NOT NULL');
        $this->addSql('ALTER TABLE [user] ADD password NVARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE [user] ADD pseudo NVARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE [user] ADD private BIT NOT NULL');
        $this->addSql('ALTER TABLE [user] DROP COLUMN first_name');
        $this->addSql('ALTER TABLE [user] DROP COLUMN last_name');
        $this->addSql('ALTER TABLE [user] DROP COLUMN username');
        $this->addSql('ALTER TABLE [user] ALTER COLUMN description VARBINARY(MAX)');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'user\', N\'COLUMN\', roles');
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
        $this->addSql('CREATE TABLE test (id INT IDENTITY NOT NULL, name NVARCHAR(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL, PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C1BC7E6B6');
        $this->addSql('DROP INDEX IDX_9474526C1BC7E6B6 ON comment');
        $this->addSql('ALTER TABLE comment ADD user_id INT');
        $this->addSql('ALTER TABLE comment ADD text NVARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE comment DROP COLUMN writer_id');
        $this->addSql('ALTER TABLE comment DROP COLUMN content');
        $this->addSql('ALTER TABLE comment ALTER COLUMN post_id INT');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE NONCLUSTERED INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('ALTER TABLE follow DROP CONSTRAINT FK_68344470D956F010');
        $this->addSql('DROP INDEX IDX_68344470D956F010 ON follow');
        $this->addSql('ALTER TABLE follow ADD user_id INT');
        $this->addSql('ALTER TABLE follow DROP COLUMN followed_id');
        $this->addSql('ALTER TABLE follow ALTER COLUMN follower_id INT');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_68344470A76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE NONCLUSTERED INDEX IDX_68344470A76ED395 ON follow (user_id)');
        $this->addSql('DROP INDEX UNIQ_6A2CA10C4B89032C ON media');
        $this->addSql('ALTER TABLE media DROP COLUMN picture');
        $this->addSql('CREATE NONCLUSTERED INDEX IDX_6A2CA10C4B89032C ON media (post_id)');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8D33C857F5');
        $this->addSql('DROP INDEX IDX_5A8A6C8D33C857F5 ON post');
        $this->addSql('ALTER TABLE post ADD user_id INT');
        $this->addSql('ALTER TABLE post ADD content NVARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE post DROP COLUMN belongs_to_id');
        $this->addSql('ALTER TABLE post DROP COLUMN description');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES [user] (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE NONCLUSTERED INDEX IDX_5A8A6C8DA76ED395 ON post (user_id)');
        $this->addSql('ALTER TABLE [user] ADD first_name NVARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE [user] ADD last_name NVARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE [user] ADD username NVARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE [user] DROP COLUMN roles');
        $this->addSql('ALTER TABLE [user] DROP COLUMN password');
        $this->addSql('ALTER TABLE [user] DROP COLUMN pseudo');
        $this->addSql('ALTER TABLE [user] DROP COLUMN private');
        $this->addSql('ALTER TABLE [user] ALTER COLUMN description VARCHAR(MAX)');
        $this->addSql('CREATE UNIQUE NONCLUSTERED INDEX UNIQ_8D93D649F85E0677 ON [user] (username) WHERE username IS NOT NULL');
    }
}
