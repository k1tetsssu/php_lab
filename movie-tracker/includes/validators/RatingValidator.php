<?php
declare(strict_types=1);

require_once __DIR__ . '/ValidatorInterface.php';

class RatingValidator implements ValidatorInterface
{
    private string $field;
    private string $label;
    private int $min;
    private int $max;

    /**
     * Проверяет, входит ли значение поля в список допустимых.
     *
     * @param array $data Ассоциативный массив данных формы.
     * @return string|null Сообщение об ошибке или null.
     */
    public function __construct(string $field, string $label, int $min = 1, int $max = 10)
    {
        $this->field = $field;
        $this->label = $label;
        $this->min = $min;
        $this->max = $max;
    }
    
    /**
     * Проверяет, что значение поля является числом в допустимом диапазоне.
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

        if (!is_numeric($value)) {
            return "Поле \"{$this->label}\" должно быть числом.";
        }

        $number = (int)$value;

        if ($number < $this->min || $number > $this->max) {
            return "Поле \"{$this->label}\" должно быть в диапазоне от {$this->min} до {$this->max}.";
        }

        return null;
    }
}