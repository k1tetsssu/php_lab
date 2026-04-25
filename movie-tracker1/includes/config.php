<?php
declare(strict_types=1);

/**
 * Конфигурационный файл для приложения Movie Tracker
 */

define('DATA_FILE', __DIR__ . '/../data/data.json');

define('ALLOWED_TYPES', [
    'Сериал',
    'Фильм',
]);

define('ALLOWED_STATUSES', [
    'Смотрю',
    'Хочу посмотреть',
    'Просмотрено',
]);

define('ALLOWED_GENRES', [
    'Боевик',
    'Комедия',
    'Драма',
    'Ужасы',
    'Фантастика',
    'Приключения',
]);

