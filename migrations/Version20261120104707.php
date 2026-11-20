<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261120104707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(100) NOT NULL)');
        $this->addSql('CREATE TABLE marque (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(100) NOT NULL)');
        $this->addSql('CREATE TABLE produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, dummy_json_id INTEGER NOT NULL, titre VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, remise DOUBLE PRECISION NOT NULL, note DOUBLE PRECISION DEFAULT NULL, stock INTEGER NOT NULL, sku VARCHAR(50) DEFAULT NULL, quantite_minimale INTEGER DEFAULT NULL, miniature_url VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE tag (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(50) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE marque');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE tag');
    }
}
