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

$input = [
    'title' => trim($_POST['title'] ?? ''),
    'release_date' => trim($_POST['release_date'] ?? ''),
    'type' => trim($_POST['type'] ?? ''),
    'genre' => trim($_POST['genre'] ?? ''),
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
    'genre' => $input['genre'],
    'rating' => (int)$input['rating'],
    'description' => $input['description'],
    'watched_at' => $input['watched_at'],
    'status' => $input['status'],
    'created_at' => date('Y-m-d'),
];

try {
    $movies = loadData(DATA_FILE);
    $movies[] = $newMovie;
    saveData(DATA_FILE, $movies);

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
