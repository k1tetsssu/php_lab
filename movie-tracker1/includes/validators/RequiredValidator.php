<?php
declare(strict_types=1);

require_once __DIR__ . '/ValidatorInterface.php';

class RequiredValidator implements ValidatorInterface
{
    private string $field;
    private string $label;

    /**
     * @param string $field Ключ поля в массиве данных.
     * @param string $label Отображаемое название поля.
     */
    public function __construct(string $field, string $label)
    {
        $this->field = $field;
        $this->label = $label;
    }

    /**
     * Проверяет, что значение поля не является пустым.
     *
     * @param array $data Ассоциативный массив данных формы.
     * @return string|null Сообщение об ошибке или null.
     */
    public function validate(array $data): ?string
    {
        $value = trim((string)($data[$this->field] ?? ''));

        if ($value === '') {
            return "Поле \"{$this->label}\" обязательно для заполнения.";
        }

        return null;
    }
}