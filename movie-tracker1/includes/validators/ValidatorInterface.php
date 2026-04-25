<?php
declare(strict_types=1);

interface ValidatorInterface
{
    /**
     * Выполняет проверку данных.
     *
     * @param array $data Ассоциативный массив входных данных.
     * @return string|null Текст ошибки или null, если проверка успешна.
     */
    public function validate(array $data): ?string;
}