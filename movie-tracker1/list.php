<?php
declare(strict_types=1);

// ✅ Подключаем конфиг и функции ПЕРЕД вызовом методов БД
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$movies = [];
$errorMessage = '';

try {
    $sort = $_GET['sort'] ?? 'created_at';
    // Сортировка теперь выполняется внутри функции на уровне SQL
    $movies = getAllMovies($sort);
} catch (Throwable $e) {
    $errorMessage = 'Ошибка загрузки данных: ' . $e->getMessage();
}

render('list', [
    'title' => 'Список фильмов и сериалов',
    'movies' => $movies,
    'errorMessage' => $errorMessage
]);