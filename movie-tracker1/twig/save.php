<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/twig.php';

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

$input = [
    'title' => trim($_POST['title'] ?? ''),
    'release_date' => trim($_POST['release_date'] ?? ''),
    'type' => trim($_POST['type'] ?? ''),
    'genre_id' => (int)($_POST['genre_id'] ?? 0),
    'rating' => trim($_POST['rating'] ?? ''),
    'description' => trim($_POST['description'] ?? ''),
    'watched_at' => trim($_POST['watched_at'] ?? ''),
    'status' => trim($_POST['status'] ?? ''),
];

$errors = validateMovieData($input);

if (!empty($errors)) {
    renderTwig('errors.html.twig', [
        'title' => 'Ошибка',
        'errors' => $errors,
    ]);
    exit;
}

$newMovie = [
    'title' => $input['title'],
    'release_date' => $input['release_date'],
    'type' => $input['type'],
    'genre_id' => $input['genre_id'],
    'rating' => (int)$input['rating'],
    'description' => $input['description'],
    'watched_at' => $input['watched_at'],
    'status' => $input['status'],
];

try {
    createMovie($newMovie);
    regenerateCsrfToken();
    renderTwig('success.html.twig', [
        'title' => 'Успешно',
    ]);
    exit;
} catch (RuntimeException $exception) {
    http_response_code(500);
    renderTwig('errors.html.twig', [
        'title' => 'Ошибка сервера',
        'errors' => [$exception->getMessage()],
    ]);
    exit;
}