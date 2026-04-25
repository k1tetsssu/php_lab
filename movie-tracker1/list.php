<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$movies = [];
$errorMessage = '';

try {
    $movies = loadData(DATA_FILE);
} catch (RuntimeException $exception) {
    $errorMessage = $exception->getMessage();
}

$sort = $_GET['sort'] ?? '';

switch ($sort) {
    case 'title':
        usort($movies, fn($a, $b) => strcmp($a['title'], $b['title']));
        break;

    case 'release_date':
        usort($movies, fn($a, $b) => strcmp($a['release_date'], $b['release_date']));
        break;

    case 'genre':
        usort($movies, fn($a, $b) => strcmp($a['genre'], $b['genre']));
        break;

    case 'rating':
        usort($movies, fn($a, $b) => $a['rating'] <=> $b['rating']);
        break;

    case 'created_at':
        usort($movies, fn($a, $b) => strcmp($a['created_at'], $b['created_at']));
        break;
}

render('list', [
    'title' => 'Список фильмов',
    'movies' => $movies,
    'errorMessage' => $errorMessage
]);