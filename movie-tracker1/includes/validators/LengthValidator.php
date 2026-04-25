<?php
declare(strict_types=1);

require_once __DIR__ . '/ValidatorInterface.php';

class LengthValidator implements ValidatorInterface
{
    private string $field;
    private string $label;
    private int $min;
    private int $max;

    /**
     * @param string $field Ключ поля.
     * @param string $label Название поля.
     * @param int $min Минимально допустимая длина.
     * @param int $max Максимально допустимая длина.
     */
    public function __construct(string $field, string $label, int $min, int $max)
    {
        $this->field = $field;
        $this->label = $label;
        $this->min = $min;
        $this->max = $max;
    }
    
    /**
     * Проверяет длину значения поля.
     *
     * @param array $data Ассоциативный массив данных формы.
     * @return string|null Сообщение об ошибке или null.
     */
    public function validate(array $data): ?string
    {
        $value = trim((string)($data[$this->field] ?? ''));

        if ($value === '') {
            return null;
        }

        $length = function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);

        if ($length < $this->min || $length > $this->max) {
            return "Поле \"{$this->label}\" должно содержать от {$this->min} до {$this->max} символов.";
        }

        return null;
    }
}