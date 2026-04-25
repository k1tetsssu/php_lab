<h1>Ошибка</h1>

<ul>
<?php foreach ($errors as $error): ?>
    <li><?= e($error) ?></li>
<?php endforeach; ?>
</ul>

<a href="index.php">Назад</a>