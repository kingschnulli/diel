<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210621155147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_event_group (user_id INTEGER NOT NULL, event_group_id INTEGER NOT NULL, PRIMARY KEY(user_id, event_group_id))');
        $this->addSql('CREATE INDEX IDX_85C03C7CA76ED395 ON user_event_group (user_id)');
        $this->addSql('CREATE INDEX IDX_85C03C7CB8B83097 ON user_event_group (event_group_id)');
        $this->addSql('DROP INDEX IDX_3BAE0AA7F4B9195');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, eventgroup_id, name, startdate, enddate, quota, description, longdescription FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, eventgroup_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, startdate DATETIME NOT NULL, enddate DATETIME DEFAULT NULL, quota INTEGER DEFAULT NULL, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, longdescription CLOB DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_3BAE0AA7F4B9195 FOREIGN KEY (eventgroup_id) REFERENCES event_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event (id, eventgroup_id, name, startdate, enddate, quota, description, longdescription) SELECT id, eventgroup_id, name, startdate, enddate, quota, description, longdescription FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7F4B9195 ON event (eventgroup_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('DROP INDEX IDX_8D93D649C35E566A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, family_id, email, roles, password, quota, name FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, family_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL COLLATE BINARY, roles CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        , password VARCHAR(255) NOT NULL COLLATE BINARY, quota INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_8D93D649C35E566A FOREIGN KEY (family_id) REFERENCES family (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, family_id, email, roles, password, quota, name) SELECT id, family_id, email, roles, password, quota, name FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649C35E566A ON user (family_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_event_group');
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
    }
}
