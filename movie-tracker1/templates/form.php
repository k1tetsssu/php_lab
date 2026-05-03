<?php $isEdit = $isEdit ?? false; ?>
<?php $movie = $movie ?? []; ?>
<?php $genres = $genres ?? getAllGenres(); ?>

<h1><?= e($title) ?></h1>
<h2><?= $isEdit ? 'Редактирование' : 'Добавление' ?> фильма или сериала</h2>

<form action="<?= $isEdit ? 'edit.php' : 'save.php' ?>" method="POST">
    <?php if ($isEdit && isset($movie['id'])): ?>
        <input type="hidden" name="id" value="<?= (int)$movie['id'] ?>">
    <?php endif; ?>
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
    
    <label for="title">Название</label>
    <input type="text" id="title" name="title" required minlength="2" maxlength="100"
           value="<?= e($movie['title'] ?? '') ?>">
    
    <label for="release_date">Дата выхода</label>
    <input type="date" id="release_date" name="release_date" required
           value="<?= e($movie['release_date'] ?? '') ?>">
    
    <label for="type">Тип</label>
    <select id="type" name="type" required>
        <option value="">Выберите тип</option>
        <?php foreach (ALLOWED_TYPES as $t): ?>
            <option value="<?= e($t) ?>" <?= ($movie['type'] ?? '') === $t ? 'selected' : '' ?>>
                <?= e($t) ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <label for="genre_id">Жанр</label>
    <select id="genre_id" name="genre_id" required>
        <option value="">Выберите жанр</option>
        <?php foreach ($genres as $g): ?>
            <option value="<?= (int)$g['id'] ?>" 
                    <?= (int)($movie['genre_id'] ?? 0) === (int)$g['id'] ? 'selected' : '' ?>>
                <?= e($g['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <label for="rating">Оценка</label>
    <input type="number" id="rating" name="rating" min="1" max="10" required
           value="<?= e($movie['rating'] ?? '') ?>">
    
    <label for="description">Описание</label>
    <textarea id="description" name="description" required minlength="10" maxlength="1000"><?= e($movie['description'] ?? '') ?></textarea>
    
    <label for="watched_at">Дата просмотра</label>
    <input type="date" id="watched_at" name="watched_at"
           value="<?= e($movie['watched_at'] ?? '') ?>">
    
    <label for="status">Статус</label>
    <select id="status" name="status" required>
        <option value="">Выберите статус</option>
        <?php foreach (ALLOWED_STATUSES as $s): ?>
            <option value="<?= e($s) ?>" <?= ($movie['status'] ?? '') === $s ? 'selected' : '' ?>>
                <?= e($s) ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <button type="submit"><?= $isEdit ? 'Обновить' : 'Сохранить' ?> запись</button>
</form>

<div class="actions">
    <a href="list.php">Посмотреть список фильмов и сериалов</a>
</div>