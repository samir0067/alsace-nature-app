<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200603121922 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, report_id INT DEFAULT NULL, structure_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, organisator_phone_num INT NOT NULL, organisator_mail VARCHAR(255) NOT NULL, type_users VARCHAR(255) NOT NULL, target_audience VARCHAR(255) NOT NULL, accessibility VARCHAR(255) NOT NULL, max_participants INT NOT NULL, number_subscrib_adult INT NOT NULL, register_required TINYINT(1) NOT NULL, cost INT NOT NULL, initial_price_adult INT DEFAULT NULL, initial_price_child INT DEFAULT NULL, location VARCHAR(255) NOT NULL, date_start DATE NOT NULL, date_stop DATE NOT NULL, event_status VARCHAR(255) DEFAULT NULL, illustration VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B26681E4BD2A4C0 (report_id), INDEX IDX_B26681E2534008B (structure_id), INDEX IDX_B26681EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement_category (evenement_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_892A8229FD02F13 (evenement_id), INDEX IDX_892A822912469DE2 (category_id), PRIMARY KEY(evenement_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement_theme (evenement_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_4DF4BF0FD02F13 (evenement_id), INDEX IDX_4DF4BF059027487 (theme_id), PRIMARY KEY(evenement_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE children (id INT AUTO_INCREMENT NOT NULL, evenement_id INT DEFAULT NULL, age INT NOT NULL, INDEX IDX_A197B1BAFD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervention_area (id INT AUTO_INCREMENT NOT NULL, intervention_area INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, authorization_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, phone INT NOT NULL, city VARCHAR(255) NOT NULL, zip_code INT NOT NULL, address VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, INDEX IDX_1483A5E92F8B0EB2 (authorization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure (id INT AUTO_INCREMENT NOT NULL, complete_name VARCHAR(255) NOT NULL, usual_name VARCHAR(255) DEFAULT NULL, description LONGTEXT NOT NULL, logo VARCHAR(255) DEFAULT NULL, structure_type VARCHAR(255) NOT NULL, website VARCHAR(255) NOT NULL, external_payment_link VARCHAR(255) DEFAULT NULL, legal_rp_first_name VARCHAR(255) NOT NULL, legal_rp_last_name VARCHAR(255) NOT NULL, legal_represent_mail VARCHAR(255) NOT NULL, legal_represent_role VARCHAR(255) NOT NULL, office_mail VARCHAR(255) NOT NULL, office_phone INT NOT NULL, office_address VARCHAR(255) NOT NULL, territory_office VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure_intervention_area (structure_id INT NOT NULL, intervention_area_id INT NOT NULL, INDEX IDX_EE15B8372534008B (structure_id), INDEX IDX_EE15B837F6E134F9 (intervention_area_id), PRIMARY KEY(structure_id, intervention_area_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE authorization (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, report_descrip VARCHAR(255) DEFAULT NULL, report_member VARCHAR(255) DEFAULT NULL, report_pictures VARCHAR(255) DEFAULT NULL, report_time_spent INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E4BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E2534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE evenement_category ADD CONSTRAINT FK_892A8229FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement_category ADD CONSTRAINT FK_892A822912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement_theme ADD CONSTRAINT FK_4DF4BF0FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement_theme ADD CONSTRAINT FK_4DF4BF059027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE children ADD CONSTRAINT FK_A197B1BAFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E92F8B0EB2 FOREIGN KEY (authorization_id) REFERENCES authorization (id)');
        $this->addSql('ALTER TABLE structure_intervention_area ADD CONSTRAINT FK_EE15B8372534008B FOREIGN KEY (structure_id) REFERENCES structure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE structure_intervention_area ADD CONSTRAINT FK_EE15B837F6E134F9 FOREIGN KEY (intervention_area_id) REFERENCES intervention_area (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE evenement_category DROP FOREIGN KEY FK_892A8229FD02F13');
        $this->addSql('ALTER TABLE evenement_theme DROP FOREIGN KEY FK_4DF4BF0FD02F13');
        $this->addSql('ALTER TABLE children DROP FOREIGN KEY FK_A197B1BAFD02F13');
        $this->addSql('ALTER TABLE evenement_category DROP FOREIGN KEY FK_892A822912469DE2');
        $this->addSql('ALTER TABLE evenement_theme DROP FOREIGN KEY FK_4DF4BF059027487');
        $this->addSql('ALTER TABLE structure_intervention_area DROP FOREIGN KEY FK_EE15B837F6E134F9');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EA76ED395');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E2534008B');
        $this->addSql('ALTER TABLE structure_intervention_area DROP FOREIGN KEY FK_EE15B8372534008B');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E92F8B0EB2');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E4BD2A4C0');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE evenement_category');
        $this->addSql('DROP TABLE evenement_theme');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE children');
        $this->addSql('DROP TABLE intervention_area');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE structure');
        $this->addSql('DROP TABLE structure_intervention_area');
        $this->addSql('DROP TABLE authorization');
        $this->addSql('DROP TABLE report');
    }
}
