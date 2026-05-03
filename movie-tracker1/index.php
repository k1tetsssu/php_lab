<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

render('form', [
    'title' => 'Movie Tracker',
    'genres' => getAllGenres(),
    'isEdit' => false,
]);