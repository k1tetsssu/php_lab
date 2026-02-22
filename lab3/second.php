<?php

echo "<h2>Цикл for</h2>";

$a = 0;
$b = 0;

for ($i = 0; $i <= 5; $i++) {
    $a += 10;
    $b += 5;

    echo "Шаг $i: a = $a, b = $b <br>";
}

echo "<br>End of the loop: a = $a, b = $b";

echo "<h2>Цикл while</h2>";
$a = 0;
$b = 0;
$i = 0;

while ($i <= 5) {
    $a += 10;
    $b += 5;

    echo "Шаг $i: a = $a, b = $b <br>";

    $i++;
}

echo "<br>End of the loop: a = $a, b = $b";

echo "<h2>Цикл do-while</h2>";

$a = 0;
$b = 0;
$i = 0;

do {
    $a += 10;
    $b += 5;

    echo "Шаг $i: a = $a, b = $b <br>";

    $i++;

} while ($i <= 5);

echo "<br>End of the loop: a = $a, b = $b";