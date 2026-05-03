<?php
declare(strict_types=1);

// Константы для валидаторов (оставляем для обратной совместимости)
define('DATA_FILE', __DIR__ . '/../data/data.json');
define('ALLOWED_TYPES', ['Сериал', 'Фильм']);
define('ALLOWED_STATUSES', ['Смотрю', 'Хочу посмотреть', 'Просмотрено']);
define('ALLOWED_GENRES', ['Боевик', 'Комедия', 'Драма', 'Ужасы', 'Фантастика', 'Приключения']);

// Функция конфигурации БД
if (!function_exists('getDbConfig')) {
    function getDbConfig(): array {
        return [
            'host'     => getenv('DB_HOST') ?: 'localhost',
            'port'     => getenv('DB_PORT') ?: '3306',
            'database' => getenv('DB_NAME') ?: 'movie_tracker',
            'username' => getenv('DB_USER') ?: 'root',
            'password' => getenv('DB_PASS') ?: '123456',
            'charset'  => 'utf8mb4',
        ];
    }
}