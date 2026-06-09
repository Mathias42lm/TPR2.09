<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260609123100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, sigle VARCHAR(255) NOT NULL, intitule VARCHAR(255) NOT NULL, logo_path VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE iut (id INT AUTO_INCREMENT NOT NULL, ville VARCHAR(255) NOT NULL, logo_path VARCHAR(255) NOT NULL, universite_id INT NOT NULL, INDEX IDX_BD67112B2A52F05F (universite_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE iut_formation (iut_id INT NOT NULL, formation_id INT NOT NULL, INDEX IDX_9A56A975D256BB67 (iut_id), INDEX IDX_9A56A9755200282E (formation_id), PRIMARY KEY (iut_id, formation_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE universite (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE iut ADD CONSTRAINT FK_BD67112B2A52F05F FOREIGN KEY (universite_id) REFERENCES universite (id)');
        $this->addSql('ALTER TABLE iut_formation ADD CONSTRAINT FK_9A56A975D256BB67 FOREIGN KEY (iut_id) REFERENCES iut (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iut_formation ADD CONSTRAINT FK_9A56A9755200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE informations_connexions');
        $this->addSql('DROP TABLE message_blog');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE informations_connexions (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, motde_passe VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE message_blog (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, message TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE iut DROP FOREIGN KEY FK_BD67112B2A52F05F');
        $this->addSql('ALTER TABLE iut_formation DROP FOREIGN KEY FK_9A56A975D256BB67');
        $this->addSql('ALTER TABLE iut_formation DROP FOREIGN KEY FK_9A56A9755200282E');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE iut');
        $this->addSql('DROP TABLE iut_formation');
        $this->addSql('DROP TABLE universite');
    }
}
