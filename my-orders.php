<?php
session_start(); // Почеток на сесијата за да може да се користат податоците на корисникот.

require_once 'config/database.php'; // Вклучување на конфигурацијата за поврзување со базата на податоци.
require_once 'classes/Order.php'; // Вклучување на класата за управување со нарачки.

if (!isset($_SESSION['user_id'])) { // Проверка дали корисникот е најавен. Ако не е, пренасочување на логирањето.
    header('Location: login.php');
    exit();
}

$db = connectDB(); // Поврзување со базата на податоци.
$order = new Order($db); // Креирање на објект од класата Order за управување со нарачките.

$query = "SELECT 
    orders.id AS order_id, 
    users.username, 
    menu.name AS item_name,
    orders.price AS order_price
FROM 
    orders
JOIN 
    users ON orders.user_id = users.id
JOIN 
    menu ON orders.item_id = menu.id
WHERE 
    users.id = 1;"; // SQL барање за да се добијат нарачките на одреден корисник. Потребно е да се постави вистинско ID на корисникот, во моментот е поставено 1.

try {
    $stmt = $db->prepare($query); // Подготовка на SQL барање.
    $stmt->execute(); // Извршување на барањето.
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC); // Преземање на сите резултати од барањето во асоцијативна низа.
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage()); // Прикажување на грешка ако нешто не е во ред со базата на податоци.
    $orders = []; // Поставување на празна низа ако има грешка.
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Вклучување на Bootstrap за стилови. -->
</head>
<body class="bg-light">
<!-- Навигациска лента -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Coffee Shop</a> <!-- Линк за враќање на почетната страница. -->
            <div class="navbar-nav">
                <?php if(isset($_SESSION['user_id'])): ?> <!-- Ако корисникот е најавен, ќе се прикажат линковите за мени, нарачки, одјава и други опции. -->
                    <a class="nav-link" href="menu.php">Menu</a> <!-- Линк за мени. -->
                    <a class="nav-link" href="book-table.php">Book Table</a> <!-- Линк за резервирање на маса. -->
                    <a class="nav-link" href="my-orders.php">My Orders</a> <!-- Линк за нарачки. -->
                    <a class="nav-link" href="logout.php">Logout</a> <!-- Линк за одјава. -->
                <?php else: ?> <!-- Ако корисникот не е најавен, ќе се прикажат линковите за логирање и регистрација. -->
                    <a class="nav-link" href="login.php">Login</a>
                    <a class="nav-link" href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container my-5">
    
