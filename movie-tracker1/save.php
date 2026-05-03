<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Ошибка: разрешён только метод POST.';
    exit;
}

if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo 'Ошибка: невалидный CSRF-токен';
    exit;
}

$title = trim($_POST['title'] ?? '');
$releaseDate = trim($_POST['release_date'] ?? '');
$type = trim($_POST['type'] ?? '');
$genreId = (int)($_POST['genre_id'] ?? 0);
$rating = trim($_POST['rating'] ?? '');
$description = trim($_POST['description'] ?? '');
$watchedAt = trim($_POST['watched_at'] ?? '');
$status = trim($_POST['status'] ?? '');

$formData = [
    'title' => $title,
    'release_date' => $releaseDate,
    'type' => $type,
    'genre_id' => $genreId,
    'rating' => $rating,
    'description' => $description,
    'watched_at' => $watchedAt,
    'status' => $status,
];

$errors = validateMovieData($formData);

if (!empty($errors)) {
    render('errors', [
        'title' => 'Ошибка',
        'errors' => $errors
    ]);
    exit;
}

$newMovie = [
    'title' => $title,
    'release_date' => $releaseDate,
    'type' => $type,
    'genre_id' => $genreId,
    'rating' => (int)$rating,
    'description' => $description,
    'watched_at' => $watchedAt,
    'status' => $status,
];

try {
    createMovie($newMovie);
    regenerateCsrfToken();
    render('success', [
        'title' => 'Успешно'
    ]);
    exit;
} catch (RuntimeException $exception) {
    http_response_code(500);
    render('errors', [
        'title' => 'Ошибка сервера',
        'errors' => [$exception->getMessage()]
    ]);
    exit;
}