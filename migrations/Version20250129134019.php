<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250129134019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointments (id SERIAL NOT NULL, client_id INT DEFAULT NULL, employee_id INT DEFAULT NULL, service_id INT DEFAULT NULL, appointment_date DATE NOT NULL, appointment_time TIME(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A41727A19EB6921 ON appointments (client_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A41727A8C03F15C ON appointments (employee_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A41727AED5CA9E6 ON appointments (service_id)');
        $this->addSql('CREATE TABLE employees (id SERIAL NOT NULL, user_id INT DEFAULT NULL, bio VARCHAR(255) NOT NULL, rating NUMERIC(3, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA82C300A76ED395 ON employees (user_id)');
        $this->addSql('CREATE TABLE schedule (id SERIAL NOT NULL, employee_id INT DEFAULT NULL, date DATE NOT NULL, start_time TIME(0) WITHOUT TIME ZONE NOT NULL, end_time TIME(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A3811FB8C03F15C ON schedule (employee_id)');
        $this->addSql('CREATE TABLE services (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(200) DEFAULT NULL, duration_minutes INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id SERIAL NOT NULL, first_name VARCHAR(20) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(30) NOT NULL, role JSON NOT NULL, last_name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE appointments ADD CONSTRAINT FK_6A41727A19EB6921 FOREIGN KEY (client_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointments ADD CONSTRAINT FK_6A41727A8C03F15C FOREIGN KEY (employee_id) REFERENCES employees (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointments ADD CONSTRAINT FK_6A41727AED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C300A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB8C03F15C FOREIGN KEY (employee_id) REFERENCES employees (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE appointments DROP CONSTRAINT FK_6A41727A19EB6921');
        $this->addSql('ALTER TABLE appointments DROP CONSTRAINT FK_6A41727A8C03F15C');
        $this->addSql('ALTER TABLE appointments DROP CONSTRAINT FK_6A41727AED5CA9E6');
        $this->addSql('ALTER TABLE employees DROP CONSTRAINT FK_BA82C300A76ED395');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FB8C03F15C');
        $this->addSql('DROP TABLE appointments');
        $this->addSql('DROP TABLE employees');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE services');
        $this->addSql('DROP TABLE users');
    }
}
