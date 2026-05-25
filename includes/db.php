<?php
// db.php — Подключение к базе данных

$host = 'localhost';
$db   = 'wille_store';
$user = 'root'; // Измените под свои настройки
$pass = '';     // Измените под свои настройки
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Выбрасывает исключения при ошибках
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Данные возвращаются в виде ассоциативного массива
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Использование реальных подготовленных выражений
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>