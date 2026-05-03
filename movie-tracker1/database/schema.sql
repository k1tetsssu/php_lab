-- Файл: database/schema.sql

CREATE DATABASE IF NOT EXISTS movie_tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE movie_tracker;

-- Таблица жанров (справочник)
CREATE TABLE genres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица фильмов
CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    release_date DATE NOT NULL,
    type ENUM('Фильм', 'Сериал') NOT NULL,
    genre_id INT NOT NULL,
    rating TINYINT UNSIGNED CHECK (rating BETWEEN 1 AND 10),
    description TEXT NOT NULL,
    watched_at DATE DEFAULT NULL,
    status ENUM('Смотрю', 'Хочу посмотреть', 'Просмотрено') NOT NULL,
    created_at DATE NOT NULL,
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE RESTRICT,
    INDEX idx_title (title),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Заполнение справочника жанров
INSERT INTO genres (name) VALUES 
    ('Боевик'), ('Комедия'), ('Драма'), ('Ужасы'), ('Фантастика'), ('Приключения');