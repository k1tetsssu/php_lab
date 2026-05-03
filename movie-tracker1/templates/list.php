<?php 
$movies       = $movies ?? getAllMovies();
$errorMessage = $errorMessage ?? '';
$csrfToken    = generateCsrfToken();
?>

<h1>Список фильмов и сериалов</h1>

<div class="actions">
    <a href="index.php">Добавить новую запись</a>
</div>

<p>
    Сортировать по:
    <a href="list.php?sort=title">названию</a> |
    <a href="list.php?sort=release_date">дате выхода</a> |
    <a href="list.php?sort=genre">жанру</a> |
    <a href="list.php?sort=rating">оценке</a> |
    <a href="list.php?sort=created_at">дате добавления</a>
</p>

<?php if ($errorMessage !== ''): ?>
    <div class="error-list"><p><?= e($errorMessage) ?></p></div>
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
        <th>Действия</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($movies as $movie): ?>
        <tr>
            <td><?= e($movie['title']) ?></td>
            <td><?= e($movie['release_date']) ?></td>
            <td><?= e($movie['type']) ?></td>
            <!-- ✅ Безопасный вывод жанра -->
            <td><?= e($movie['genre_name'] ?? 'Не указан') ?></td>
            <td><?= e($movie['rating']) ?></td>
            <td><?= e($movie['description']) ?></td>
            <td><?= e($movie['watched_at'] ?? '') ?></td>
            <td><?= e($movie['status']) ?></td>
            <td><?= e($movie['created_at']) ?></td>
            <td>
                <a href="edit.php?id=<?= (int)$movie['id'] ?>" title="Редактировать">✏️</a>
                <form method="POST" action="delete.php" style="display:inline;" 
                      onsubmit="return confirm('Удалить «<?= e($movie['title']) ?>»?');">
                    <input type="hidden" name="id" value="<?= (int)$movie['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <button type="submit" style="background:none;border:none;cursor:pointer;" title="Удалить">🗑️</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>