<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Tracker</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h1>Movie Tracker</h1>
    <h2>Добавление фильма или сериала</h2>

    <form action="save.php" method="POST">
        <label for="title">Название</label>
        <input
            type="text"
            id="title"
            name="title"
            required
            minlength="2"
            maxlength="100"
        >

        <label for="release_date">Дата выхода</label>
        <input
            type="date"
            id="release_date"
            name="release_date"
            required
        >

        <label for="type">Тип</label>
        <select id="type" name="type" required>
            <option value="">Выберите тип</option>
            <?php foreach (ALLOWED_TYPES as $type): ?>
                <option value="<?= e($type) ?>"><?= e($type) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="genre">Жанр</label>
        <select id="genre" name="genre" required>
            <option value="">Выберите жанр</option>
            <?php foreach (ALLOWED_GENRES as $genre): ?>
                <option value="<?= e($genre) ?>"><?= e($genre) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="rating">Оценка</label>
        <input
            type="number"
            id="rating"
            name="rating"
            min="1"
            max="10"
            required
        >

        <label for="description">Описание</label>
        <textarea
            id="description"
            name="description"
            required
            minlength="10"
            maxlength="1000"
        ></textarea>

        <label for="watched_at">Дата просмотра</label>
        <input
            type="date"
            id="watched_at"
            name="watched_at"
        >

        <label for="status">Статус</label>
        <select id="status" name="status" required>
            <option value="">Выберите статус</option>
            <?php foreach (ALLOWED_STATUSES as $status): ?>
                <option value="<?= e($status) ?>"><?= e($status) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Сохранить запись</button>
    </form>

    <div class="actions">
        <a href="list.php">Посмотреть список фильмов и сериалов</a>
    </div>
</div>
</body>
</html>