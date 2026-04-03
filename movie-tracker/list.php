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
        usort($movies, fn(array $a, array $b): int => strcmp($a['title'], $b['title']));
        break;

    case 'release_date':
        usort($movies, fn(array $a, array $b): int => strcmp($a['release_date'], $b['release_date']));
        break;

    case 'genre':
        usort($movies, fn(array $a, array $b): int => strcmp($a['genre'], $b['genre']));
        break;

    case 'rating':
        usort($movies, fn(array $a, array $b): int => $a['rating'] <=> $b['rating']);
        break;

    case 'created_at':
        usort($movies, fn(array $a, array $b): int => strcmp($a['created_at'], $b['created_at']));
        break;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список фильмов и сериалов</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h1>Список фильмов и сериалов</h1>

    <div class="actions">
        <a href="index.php">Добавить новую запись</a>
    </div>

    <p>
        Сортировать по:
        <a href="list.php?sort=title">названию</a>
        <a href="list.php?sort=release_date">дате выхода</a>
        <a href="list.php?sort=genre">жанру</a>
        <a href="list.php?sort=rating">оценке</a>
        <a href="list.php?sort=created_at">дате добавления</a>
    </p>

    <?php if ($errorMessage !== ''): ?>
        <div class="error-list">
            <p><?= e($errorMessage) ?></p>
        </div>
    <?php elseif (empty($movies)): ?>
        <p>Записей пока нет.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Название</th>
                <th>Дата выхода</th>
                <th>Тип</th>
                <th>Жанр</th>
                <th>Оценка</th>
                <th>Описание</th>
                <th>Дата просмотра</th>
                <th>Статус</th>
                <th>Дата добавления</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($movies as $movie): ?>
                <tr>
                    <td><?= e((string)($movie['title'] ?? '')) ?></td>
                    <td><?= e((string)($movie['release_date'] ?? '')) ?></td>
                    <td><?= e((string)($movie['type'] ?? '')) ?></td>
                    <td><?= e((string)($movie['genre'] ?? '')) ?></td>
                    <td><?= e((string)($movie['rating'] ?? '')) ?></td>
                    <td><?= e((string)($movie['description'] ?? '')) ?></td>
                    <td><?= e((string)($movie['watched_at'] ?? '')) ?></td>
                    <td><?= e((string)($movie['status'] ?? '')) ?></td>
                    <td><?= e((string)($movie['created_at'] ?? '')) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>