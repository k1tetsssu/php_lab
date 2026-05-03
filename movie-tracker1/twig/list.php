<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/twig.php';

$movies = getAllMovies();
$errorMessage = '';

$sort = $_GET['sort'] ?? '';
// Сортировка уже выполняется в getAllMovies()

renderTwig('list.html.twig', [
    'title' => 'Список фильмов (Twig)',
    'movies' => $movies,
    'errorMessage' => $errorMessage,
]);