<?php

declare(strict_types=1);

/**
 * Интерфейс для хранения транзакций.
 */
interface TransactionStorageInterface
{
    /**
     * Добавляет транзакцию.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function addTransaction(Transaction $transaction): void;

    /**
     * Удаляет транзакцию по ID.
     *
     * @param int $id
     * @return void
     */
    public function removeTransactionById(int $id): void;

    /**
     * Возвращает все транзакции.
     *
     * @return Transaction[]
     */
    public function getAllTransactions(): array;

    /**
     * Ищет транзакцию по ID.
     *
     * @param int $id
     * @return Transaction|null
     */
    public function findById(int $id): ?Transaction;
}

/**
 * Класс, описывающий одну банковскую транзакцию.
 */
class Transaction {
    private int $id;
    private DateTime $date;    
    private float $amount;
    private string $description;
    private string $merchant;

    /**
    * Конструктор класса Transaction.
    *
    * @param int $id Уникальный идентификатор транзакции.
    * @param string $date Дата транзакции в формате Y-m-d.
    * @param float $amount Сумма транзакции.
    * @param string $description Описание платежа.
    * @param string $merchant Получатель платежа.
    */
    public function __construct(int $id, string $date, float $amount, string $description, string $merchant)
    {
        $this->id = $id; 
        $this->date = new DateTime($date);
        $this->amount = $amount;
        $this->description = $description;
        $this->merchant = $merchant;
    }

    /* Геттеры для всех свойств класса Transaction. */

    /**
     * Получить уникальный идентификатор транзакции.
     *
     * @return int Идентификатор транзакции.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Получить дату транзакции.
     *
     * @return DateTime Дата транзакции.
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Получить сумму транзакции.
     *
     * @return float Сумма транзакции.
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Получить описание транзакции.
     *
     * @return string Описание транзакции.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Получить получателя платежа.
     *
     * @return string Получатель платежа.
     */
    public function getMerchant(): string
    {
        return $this->merchant;
    }
    
    /**
     * Получить количество дней, прошедших с момента транзакции.
     *
     * @return int Количество дней.
     */
    public function getDaysSinceTransaction(): int
    {
        $now = new DateTime();
        $interval = $now->diff($this->date);
        return $interval->days;
    }


}

class TransactionRepository implements TransactionStorageInterface {  

    /**
     *  @var Transaction[]       
     */
    private array $transactions = [];

    /**
     * Добавить транзакцию в репозиторий.
     *
     * @param Transaction $transaction Транзакция для добавления.
     */
    public function addTransaction(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    /**
     * Удалить транзакцию из репозитория по ее идентификатору.
     *
     * @param int $id Идентификатор транзакции для удаления.
     */

    public function removeTransactionById(int $id): void
    {
        foreach ($this->transactions as $index => $transaction) {
            if ($transaction->getId() === $id) {
                unset($this->transactions[$index]);
                // Переиндексация массива после удаления элемента
                $this->transactions = array_values($this->transactions);
                break;
            }
        }
    }

    /**
     * Получить все транзакции из репозитория.
     *
     * @return Transaction[] Массив всех транзакций.
     */
    public function getAllTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * Найти транзакцию по ее идентификатору.
     *
     * @param int $id Идентификатор транзакции для поиска.
     * @return Transaction|null Найденная транзакция или null, если не найдена.
     */
    public function findById(int $id): ?Transaction
    {
        foreach ($this->transactions as $transaction) {
            if ($transaction->getId() === $id) {
                return $transaction;
            }
        }
        return null; 
    }
}

/**
 * Класс для реализации бизнес-логики над транзакциями.
 */
class TransactionManager
{
    /**
     * Конструктор класса TransactionManager.
     *
     * @param TransactionStorageInterface $repository Репозиторий транзакций.
     */
    public function __construct(
        private TransactionStorageInterface $repository
    ) {
    }

    /**
     * Вычисляет общую сумму всех транзакций.
     *
     * @return float Общая сумма транзакций.
     */
    public function calculateTotalAmount(): float
    {
        $total = 0.0;

        foreach ($this->repository->getAllTransactions() as $transaction) {
            $total += $transaction->getAmount();
        }

        return $total;
    }

    /**
     * Вычисляет сумму транзакций за указанный период.
     *
     * @param string $startDate Начальная дата периода.
     * @param string $endDate Конечная дата периода.
     * @return float Сумма транзакций за период.
     */
    public function calculateTotalAmountByDateRange(string $startDate, string $endDate): float
    {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $total = 0.0;

        foreach ($this->repository->getAllTransactions() as $transaction) {
            $transactionDate = $transaction->getDate();

            if ($transactionDate >= $start && $transactionDate <= $end) {
                $total += $transaction->getAmount();
            }
        }

        return $total;
    }

    /**
     * Подсчитывает количество транзакций у указанного получателя.
     *
     * @param string $merchant Название получателя.
     * @return int Количество транзакций.
     */
    public function countTransactionsByMerchant(string $merchant): int
    {
        $count = 0;

        foreach ($this->repository->getAllTransactions() as $transaction) {
            if (strtolower($transaction->getMerchant()) === strtolower($merchant)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Сортирует транзакции по дате по возрастанию.
     *
     * @return Transaction[] Отсортированный массив транзакций.
     */
    public function sortTransactionsByDate(): array
    {
        $transactions = $this->repository->getAllTransactions();

        usort($transactions, function (Transaction $a, Transaction $b): int {
            return $a->getDate() <=> $b->getDate();
        });

        return $transactions;
    }

    /**
     * Сортирует транзакции по сумме по убыванию.
     *
     * @return Transaction[] Отсортированный массив транзакций.
     */
    public function sortTransactionsByAmountDesc(): array
    {
        $transactions = $this->repository->getAllTransactions();

        usort($transactions, function (Transaction $a, Transaction $b): int {
            return $b->getAmount() <=> $a->getAmount();
        });

        return $transactions;
    }
}

/**
 * Класс для вывода транзакций в виде HTML-таблицы.
 */
final class TransactionTableRenderer
{
    /**
     * Отрисовывает HTML-таблицу транзакций.
     *
     * @param Transaction[] $transactions Массив транзакций.
     * @return string HTML-код таблицы.
     */
    public function render(array $transactions): string
    {
        $html = '<table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>ID транзакции</th>';
        $html .= '<th>Дата</th>';
        $html .= '<th>Сумма</th>';
        $html .= '<th>Описание</th>';
        $html .= '<th>Название получателя</th>';
        $html .= '<th>Категория получателя</th>';
        $html .= '<th>Количество дней с момента транзакции</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        foreach ($transactions as $transaction) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars((string)$transaction->getId()) . '</td>';
            $html .= '<td>' . htmlspecialchars($transaction->getDate()->format('Y-m-d')) . '</td>';
            $html .= '<td>' . htmlspecialchars((string)$transaction->getAmount()) . '</td>';
            $html .= '<td>' . htmlspecialchars($transaction->getDescription()) . '</td>';
            $html .= '<td>' . htmlspecialchars($transaction->getMerchant()) . '</td>';
            $html .= '<td>' . htmlspecialchars($this->getMerchantCategory($transaction->getMerchant())) . '</td>';
            $html .= '<td>' . htmlspecialchars((string)$transaction->getDaysSinceTransaction()) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }

    /**
     * Определяет категорию получателя по его названию.
     *
     * @param string $merchant Название получателя.
     * @return string Категория получателя.
     */
    private function getMerchantCategory(string $merchant): string
    {
        return match (strtolower($merchant)) {
            'linella', 'kaufland', 'fourchette' => 'Супермаркет',
            'spotify', 'netflix', 'youtube' => 'Подписки',
            'rompetrol', 'lukoil' => 'Топливо',
            'andys pizza', 'mcdonalds' => 'Еда',
            'moldtelecom', 'orange' => 'Связь',
            default => 'Другое',
        };
    }
}

$repository = new TransactionRepository();
$transactions = [
    new Transaction(1, '2026-03-01', 1200.50, 'Оплата продуктов', 'Linella'),
    new Transaction(2, '2026-03-02', 350.00, 'Заправка автомобиля', 'Rompetrol'),
    new Transaction(3, '2026-03-03', 89.99, 'Подписка на музыку', 'Spotify'),
    new Transaction(4, '2026-03-04', 1500.00, 'Оплата аренды', 'Landlord SRL'),
    new Transaction(5, '2026-03-05', 240.75, 'Ужин в ресторане', 'Andys Pizza'),
    new Transaction(6, '2026-03-06', 670.40, 'Покупка одежды', 'Mango'),
    new Transaction(7, '2026-03-07', 120.00, 'Покупка лекарств', 'Felicia'),
    new Transaction(8, '2026-03-08', 999.99, 'Покупка техники', 'Enter'),
    new Transaction(9, '2026-03-09', 45.50, 'Покупка кофе', 'Coffee House'),
    new Transaction(10, '2026-03-10', 300.00, 'Оплата интернета', 'Moldtelecom'),
];

foreach ($transactions as $transaction) {
    $repository->addTransaction($transaction);
}

$manager = new TransactionManager($repository);
$renderer = new TransactionTableRenderer();

echo '<h1>Проверка работы приложения</h1>';

echo '<h2>1. Общая сумма всех транзакций</h2>';
echo '<p>' . $manager->calculateTotalAmount() . '</p>';

echo '<h2>2. Сумма транзакций за период с 2026-03-03 по 2026-03-08</h2>';
echo '<p>' . $manager->calculateTotalAmountByDateRange('2026-03-03', '2026-03-08') . '</p>';

echo '<h2>3. Количество транзакций у получателя Spotify</h2>';
echo '<p>' . $manager->countTransactionsByMerchant('Spotify') . '</p>';

echo '<h2>4. Поиск транзакции по ID = 3</h2>';
$foundTransaction = $repository->findById(3);
echo '<p>';
echo $foundTransaction !== null
    ? 'Найдена транзакция: ' . htmlspecialchars($foundTransaction->getDescription())
    : 'Транзакция не найдена';
echo '</p>';

echo '<h2>5. Таблица всех транзакций</h2>';
echo $renderer->render($repository->getAllTransactions());

echo '<h2>6. Транзакции, отсортированные по дате</h2>';
echo $renderer->render($manager->sortTransactionsByDate());

echo '<h2>7. Транзакции, отсортированные по сумме по убыванию</h2>';
echo $renderer->render($manager->sortTransactionsByAmountDesc());

$repository->removeTransactionById(9);

echo '<h2>8. Таблица после удаления транзакции с ID = 9</h2>';
echo $renderer->render($repository->getAllTransactions());


echo '<h2>9. Добавление новой транзакции</h2>';
$newTransaction = new Transaction(11, '2026-03-11', 500.00, 'Покупка топлива', 'Lukoil');
$repository->addTransaction($newTransaction);
echo $renderer->render($repository->getAllTransactions());
