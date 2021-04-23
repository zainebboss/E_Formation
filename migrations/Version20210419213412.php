<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419213412 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE categoriee');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE examen');
        $this->addSql('DROP TABLE examenquestion');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE inscription');
        $this->addSql('DROP TABLE listereponse');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE seance');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('ALTER TABLE aide MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE aide DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE aide ADD aide VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE aide ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, formateur_id INT NOT NULL, apprenant_id INT NOT NULL, note INT NOT NULL, INDEX apprenant_id (apprenant_id), INDEX formateur_id (formateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE categorie (id_catégorie INT AUTO_INCREMENT NOT NULL, Description VARCHAR(1027) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, PRIMARY KEY(id_catégorie)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE categoriee (ID INT AUTO_INCREMENT NOT NULL, Numero INT NOT NULL, lien VARCHAR(1024) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(1024) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, titre VARCHAR(1024) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'Table \'\'eformation.categoriee\'\' doesn\'\'t exist in engine\' ');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(1024) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, fichier VARCHAR(1024) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, formation_id INT NOT NULL, Description_cat VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, INDEX id_formation (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE examen (id INT AUTO_INCREMENT NOT NULL, formation_id INT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'Table \'\'eformation.examen\'\' doesn\'\'t exist in engine\' ');
        $this->addSql('CREATE TABLE examenquestion (id INT AUTO_INCREMENT NOT NULL, idQuestion INT NOT NULL, idExamen INT NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'Table \'\'eformation.examenquestion\'\' doesn\'\'t exist in engine\' ');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(11) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, prix VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, description VARCHAR(1024) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, UNIQUE INDEX titre (titre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, formation_id INT NOT NULL, date_inscrit DATE NOT NULL, INDEX id_formation (formation_id), INDEX id_apprenant (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE listereponse (id INT AUTO_INCREMENT NOT NULL, idUser INT NOT NULL, idQuestion INT NOT NULL, idReponse INT NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'Table \'\'eformation.listereponse\'\' doesn\'\'t exist in engine\' ');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, idUser INT NOT NULL, idExamen INT NOT NULL, score INT NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'Table \'\'eformation.note\'\' doesn\'\'t exist in engine\' ');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, formation_id INT NOT NULL, question VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'Table \'\'eformation.question\'\' doesn\'\'t exist in engine\' ');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, idQuestion INT NOT NULL, reponse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, vrai VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'Table \'\'eformation.reponse\'\' doesn\'\'t exist in engine\' ');
        $this->addSql('CREATE TABLE seance (ID_seance INT AUTO_INCREMENT NOT NULL, ID_formation INT NOT NULL, lien VARCHAR(1024) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, description VARCHAR(1024) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, Date_seance VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, INDEX id_formation (ID_formation), PRIMARY KEY(ID_seance)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, date VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, formation VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, duree INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(1024) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, password VARCHAR(1024) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, role VARCHAR(20) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, nom VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, prenom VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, telephone VARCHAR(110) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, adresse VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, date_naissance DATE DEFAULT NULL, enable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE aide MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE aide DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE aide DROP aide');
        $this->addSql('ALTER TABLE aide ADD PRIMARY KEY (id, sujet)');
    }
}
