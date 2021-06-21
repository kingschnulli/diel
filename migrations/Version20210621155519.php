<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210621155519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_interest (event_id INTEGER NOT NULL, interest_id INTEGER NOT NULL, PRIMARY KEY(event_id, interest_id))');
        $this->addSql('CREATE INDEX IDX_2AD2F3B771F7E88B ON event_interest (event_id)');
        $this->addSql('CREATE INDEX IDX_2AD2F3B75A95FF89 ON event_interest (interest_id)');
        $this->addSql('CREATE TABLE user_interest (user_id INTEGER NOT NULL, interest_id INTEGER NOT NULL, PRIMARY KEY(user_id, interest_id))');
        $this->addSql('CREATE INDEX IDX_8CB3FE67A76ED395 ON user_interest (user_id)');
        $this->addSql('CREATE INDEX IDX_8CB3FE675A95FF89 ON user_interest (interest_id)');
        $this->addSql('DROP INDEX IDX_3BAE0AA7F4B9195');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, eventgroup_id, name, startdate, enddate, quota, description, longdescription FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, eventgroup_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, startdate DATETIME NOT NULL, enddate DATETIME DEFAULT NULL, quota INTEGER DEFAULT NULL, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, longdescription CLOB DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_3BAE0AA7F4B9195 FOREIGN KEY (eventgroup_id) REFERENCES event_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event (id, eventgroup_id, name, startdate, enddate, quota, description, longdescription) SELECT id, eventgroup_id, name, startdate, enddate, quota, description, longdescription FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7F4B9195 ON event (eventgroup_id)');
        $this->addSql('DROP INDEX IDX_8D93D649C35E566A');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, family_id, email, roles, password, quota, name FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, family_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL COLLATE BINARY, roles CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        , password VARCHAR(255) NOT NULL COLLATE BINARY, quota INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_8D93D649C35E566A FOREIGN KEY (family_id) REFERENCES family (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, family_id, email, roles, password, quota, name) SELECT id, family_id, email, roles, password, quota, name FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE INDEX IDX_8D93D649C35E566A ON user (family_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('DROP INDEX IDX_85C03C7CB8B83097');
        $this->addSql('DROP INDEX IDX_85C03C7CA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_event_group AS SELECT user_id, event_group_id FROM user_event_group');
        $this->addSql('DROP TABLE user_event_group');
        $this->addSql('CREATE TABLE user_event_group (user_id INTEGER NOT NULL, event_group_id INTEGER NOT NULL, PRIMARY KEY(user_id, event_group_id), CONSTRAINT FK_85C03C7CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_85C03C7CB8B83097 FOREIGN KEY (event_group_id) REFERENCES event_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user_event_group (user_id, event_group_id) SELECT user_id, event_group_id FROM __temp__user_event_group');
        $this->addSql('DROP TABLE __temp__user_event_group');
        $this->addSql('CREATE INDEX IDX_85C03C7CB8B83097 ON user_event_group (event_group_id)');
        $this->addSql('CREATE INDEX IDX_85C03C7CA76ED395 ON user_event_group (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE event_interest');
        $this->addSql('DROP TABLE user_interest');
        $this->addSql('DROP INDEX IDX_3BAE0AA7F4B9195');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, eventgroup_id, name, startdate, enddate, quota, description, longdescription FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, eventgroup_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, startdate DATETIME NOT NULL, enddate DATETIME DEFAULT NULL, quota INTEGER DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, longdescription CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO event (id, eventgroup_id, name, startdate, enddate, quota, description, longdescription) SELECT id, eventgroup_id, name, startdate, enddate, quota, description, longdescription FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7F4B9195 ON event (eventgroup_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('DROP INDEX IDX_8D93D649C35E566A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, family_id, email, roles, password, quota, name FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, family_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, quota INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, family_id, email, roles, password, quota, name) SELECT id, family_id, email, roles, password, quota, name FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649C35E566A ON user (family_id)');
        $this->addSql('DROP INDEX IDX_85C03C7CA76ED395');
        $this->addSql('DROP INDEX IDX_85C03C7CB8B83097');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_event_group AS SELECT user_id, event_group_id FROM user_event_group');
        $this->addSql('DROP TABLE user_event_group');
        $this->addSql('CREATE TABLE user_event_group (user_id INTEGER NOT NULL, event_group_id INTEGER NOT NULL, PRIMARY KEY(user_id, event_group_id))');
        $this->addSql('INSERT INTO user_event_group (user_id, event_group_id) SELECT user_id, event_group_id FROM __temp__user_event_group');
        $this->addSql('DROP TABLE __temp__user_event_group');
        $this->addSql('CREATE INDEX IDX_85C03C7CA76ED395 ON user_event_group (user_id)');
        $this->addSql('CREATE INDEX IDX_85C03C7CB8B83097 ON user_event_group (event_group_id)');
    }
}
