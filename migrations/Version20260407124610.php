<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260407124610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create all tables for the app, compatible PostgreSQL';
    }

    public function up(Schema $schema): void
    {
        // PostgreSQL sequences are handled automatically by SERIAL or IDENTITY

        $this->addSql('CREATE TABLE role (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description VARCHAR(255) NOT NULL
        )');

        $this->addSql('CREATE TABLE users (
            id SERIAL PRIMARY KEY,
            role_id INT NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            is_active BOOLEAN NOT NULL DEFAULT FALSE,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
            created_by VARCHAR(255) NOT NULL,
            updated_by VARCHAR(255) NOT NULL,
            CONSTRAINT FK_role FOREIGN KEY (role_id) REFERENCES role(id)
        )');

        $this->addSql('CREATE TABLE themes (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description VARCHAR(255) NOT NULL,
            image VARCHAR(255),
            created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
            created_by VARCHAR(255) NOT NULL,
            updated_by VARCHAR(255) NOT NULL
        )');

        $this->addSql('CREATE TABLE courses (
            id SERIAL PRIMARY KEY,
            theme_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            price NUMERIC(5,2) NOT NULL,
            description VARCHAR(255) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
            created_by VARCHAR(255) NOT NULL,
            updated_by VARCHAR(255) NOT NULL,
            CONSTRAINT FK_theme FOREIGN KEY (theme_id) REFERENCES themes(id)
        )');

        $this->addSql('CREATE TABLE lessons (
            id SERIAL PRIMARY KEY,
            course_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            video_url VARCHAR(255),
            price NUMERIC(5,2) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
            created_by VARCHAR(255) NOT NULL,
            updated_by VARCHAR(255) NOT NULL,
            CONSTRAINT FK_course FOREIGN KEY (course_id) REFERENCES courses(id)
        )');

        $this->addSql('CREATE TABLE purchases (
            id SERIAL PRIMARY KEY,
            user_id INT NOT NULL,
            purchase_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
            CONSTRAINT FK_user FOREIGN KEY (user_id) REFERENCES users(id)
        )');

        $this->addSql('CREATE TABLE comprise (
            id SERIAL PRIMARY KEY,
            course_id INT,
            purchase_id INT,
            lesson_id INT,
            price NUMERIC(5,2) NOT NULL,
            access_granted BOOLEAN NOT NULL,
            CONSTRAINT FK_course_comp FOREIGN KEY (course_id) REFERENCES courses(id),
            CONSTRAINT FK_purchase FOREIGN KEY (purchase_id) REFERENCES purchases(id),
            CONSTRAINT FK_lesson FOREIGN KEY (lesson_id) REFERENCES lessons(id)
        )');

        $this->addSql('CREATE TABLE certifications (
            id SERIAL PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            date_awarded TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
            CONSTRAINT FK_user_cert FOREIGN KEY (user_id) REFERENCES users(id)
        )');

        $this->addSql('CREATE TABLE gets (
            user_id INT NOT NULL,
            certification_id INT NOT NULL,
            PRIMARY KEY(user_id, certification_id),
            CONSTRAINT FK_user_get FOREIGN KEY (user_id) REFERENCES users(id),
            CONSTRAINT FK_cert_get FOREIGN KEY (certification_id) REFERENCES certifications(id)
        )');

        $this->addSql('CREATE TABLE valid (
            user_id INT NOT NULL,
            lesson_id INT NOT NULL,
            date_validated TIMESTAMP(0) WITHOUT TIME ZONE,
            PRIMARY KEY(user_id, lesson_id),
            CONSTRAINT FK_user_valid FOREIGN KEY (user_id) REFERENCES users(id),
            CONSTRAINT FK_lesson_valid FOREIGN KEY (lesson_id) REFERENCES lessons(id)
        )');

        $this->addSql('CREATE TABLE messenger_messages (
            id BIGSERIAL PRIMARY KEY,
            body TEXT NOT NULL,
            headers TEXT NOT NULL,
            queue_name VARCHAR(190) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            delivered_at TIMESTAMP(0) WITHOUT TIME ZONE
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS messenger_messages CASCADE');
        $this->addSql('DROP TABLE IF EXISTS valid CASCADE');
        $this->addSql('DROP TABLE IF EXISTS gets CASCADE');
        $this->addSql('DROP TABLE IF EXISTS certifications CASCADE');
        $this->addSql('DROP TABLE IF EXISTS comprise CASCADE');
        $this->addSql('DROP TABLE IF EXISTS purchases CASCADE');
        $this->addSql('DROP TABLE IF EXISTS lessons CASCADE');
        $this->addSql('DROP TABLE IF EXISTS courses CASCADE');
        $this->addSql('DROP TABLE IF EXISTS themes CASCADE');
        $this->addSql('DROP TABLE IF EXISTS users CASCADE');
        $this->addSql('DROP TABLE IF EXISTS role CASCADE');
    }
}