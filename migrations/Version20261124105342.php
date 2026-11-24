<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261124105342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_tag (produit_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY (produit_id, tag_id), CONSTRAINT FK_423DC0FAF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_423DC0FABAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_423DC0FAF347EFB ON produit_tag (produit_id)');
        $this->addSql('CREATE INDEX IDX_423DC0FABAD26311 ON produit_tag (tag_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__categorie AS SELECT id, nom FROM categorie');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('CREATE TABLE categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(100) NOT NULL)');
        $this->addSql('INSERT INTO categorie (id, nom) SELECT id, nom FROM __temp__categorie');
        $this->addSql('DROP TABLE __temp__categorie');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_497DD6346C6E55B5 ON categorie (nom)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__marque AS SELECT id, nom FROM marque');
        $this->addSql('DROP TABLE marque');
        $this->addSql('CREATE TABLE marque (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(100) NOT NULL)');
        $this->addSql('INSERT INTO marque (id, nom) SELECT id, nom FROM __temp__marque');
        $this->addSql('DROP TABLE __temp__marque');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A6F91CE6C6E55B5 ON marque (nom)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__produit AS SELECT id, dummy_json_id, titre, description, prix, remise, note, stock, sku, quantite_minimale, miniature_url FROM produit');
        $this->addSql('DROP TABLE produit');
        $this->addSql('CREATE TABLE produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, dummy_json_id INTEGER NOT NULL, titre VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, remise DOUBLE PRECISION DEFAULT NULL, note DOUBLE PRECISION DEFAULT NULL, stock INTEGER NOT NULL, sku VARCHAR(50) DEFAULT NULL, quantite_minimale INTEGER DEFAULT NULL, miniature_url VARCHAR(255) DEFAULT NULL, categorie_id INTEGER NOT NULL, marque_id INTEGER DEFAULT NULL, CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_29A5EC274827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO produit (id, dummy_json_id, titre, description, prix, remise, note, stock, sku, quantite_minimale, miniature_url) SELECT id, dummy_json_id, titre, description, prix, remise, note, stock, sku, quantite_minimale, miniature_url FROM __temp__produit');
        $this->addSql('DROP TABLE __temp__produit');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29A5EC27E963D0FF ON produit (dummy_json_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27BCF5E72D ON produit (categorie_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC274827B9B2 ON produit (marque_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__tag AS SELECT id, nom FROM tag');
        $this->addSql('DROP TABLE tag');
        $this->addSql('CREATE TABLE tag (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(50) NOT NULL)');
        $this->addSql('INSERT INTO tag (id, nom) SELECT id, nom FROM __temp__tag');
        $this->addSql('DROP TABLE __temp__tag');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B7836C6E55B5 ON tag (nom)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE produit_tag');
        $this->addSql('CREATE TEMPORARY TABLE __temp__categorie AS SELECT id, nom FROM categorie');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('CREATE TABLE categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(100) NOT NULL)');
        $this->addSql('INSERT INTO categorie (id, nom) SELECT id, nom FROM __temp__categorie');
        $this->addSql('DROP TABLE __temp__categorie');
        $this->addSql('CREATE TEMPORARY TABLE __temp__marque AS SELECT id, nom FROM marque');
        $this->addSql('DROP TABLE marque');
        $this->addSql('CREATE TABLE marque (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(100) NOT NULL)');
        $this->addSql('INSERT INTO marque (id, nom) SELECT id, nom FROM __temp__marque');
        $this->addSql('DROP TABLE __temp__marque');
        $this->addSql('CREATE TEMPORARY TABLE __temp__produit AS SELECT id, dummy_json_id, titre, description, prix, remise, note, stock, sku, quantite_minimale, miniature_url FROM produit');
        $this->addSql('DROP TABLE produit');
        $this->addSql('CREATE TABLE produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, dummy_json_id INTEGER NOT NULL, titre VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, remise DOUBLE PRECISION NOT NULL, note DOUBLE PRECISION DEFAULT NULL, stock INTEGER NOT NULL, sku VARCHAR(50) DEFAULT NULL, quantite_minimale INTEGER DEFAULT NULL, miniature_url VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO produit (id, dummy_json_id, titre, description, prix, remise, note, stock, sku, quantite_minimale, miniature_url) SELECT id, dummy_json_id, titre, description, prix, remise, note, stock, sku, quantite_minimale, miniature_url FROM __temp__produit');
        $this->addSql('DROP TABLE __temp__produit');
        $this->addSql('CREATE TEMPORARY TABLE __temp__tag AS SELECT id, nom FROM tag');
        $this->addSql('DROP TABLE tag');
        $this->addSql('CREATE TABLE tag (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(50) NOT NULL)');
        $this->addSql('INSERT INTO tag (id, nom) SELECT id, nom FROM __temp__tag');
        $this->addSql('DROP TABLE __temp__tag');
    }
}
