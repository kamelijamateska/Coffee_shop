<?php
session_start(); // Почеток на сесијата за да се користат податоци за корисникот.
require_once 'config/database.php'; // Вклучување на конфигурацијата за базата на податоци.
require_once 'classes/Menu.php'; // Вклучување на класата која се користи за менито.
require_once 'classes/Order.php'; // Вклучување на класата за управување со нарачки.

if (!isset($_SESSION['user_id'])) { // Проверка дали корисникот е најавен. Ако не е, ќе се пренасочи на страната за најава.
    header('Location: login.php');
    exit();
}

$db = connectDB(); // Поврзување со базата на податоци.
$menu = new Menu($db); // Креирање на објект од класата Menu за добивање на мени.
$order = new Order($db); // Креирање на објект од класата Order за поставување на нарачки.

$message = ''; // Променлива за чување на пораки за статусот на нарачката.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order'])) { // Проверка дали е испратено POST барање и дали е нарачка.
    $itemId = $_POST['item_id']; // Земање на идентификаторот на избраниот предмет.
    $quantity = $_POST['quantity']; // Земање на количеството за нарачка.

    if ($order->placeOrder($_SESSION['user_id'], $itemId, $quantity)) { // Проба за поставување на нарачка.
        $message = "Order placed successfully!"; // Ако нарачката е успешна.
    } else {
        $message = "Failed to place order. Please try again."; // Ако нарачката не е успешна.
    }
}

$menuItems = $menu->getAllItems(); // Добивање на сите предмети од менито.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .menu-item {
            transition: transform 0.2s; /* Додавање на анимација за предметите во менито. */
        }
        .menu-item:hover {
            transform: translateY(-5px); /* Поставување на ефект при ховер над предметот. */
        }
        .price {
            font-size: 1.25rem;
            color: #2c3e50;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark"> <!-- Навигациска лента. -->
    <div class="container">
        <a class="navbar-brand" href="index.php">Coffee Shop</a> <!-- Линк за враќање на почетната страница. -->
        <div class="navbar-nav">
            <?php if(isset($_SESSION['user_id'])): ?> <!-- Ако корисникот е најавен, ќе се прикажат опции за мени, нарачки и одјава. -->
                <a class="nav-link" href="menu.php">Menu</a>
                <a class="nav-link" href="book-table.php">Book Table</a>
                <a class="nav-link" href="my-orders.php">My Orders</a>
                <a class="nav-link" href="logout.php">Logout</a>
            <?php else: ?> <!-- Ако корисникот не е најавен, ќе се прикажат опции за логирање и регистрација. -->
                <a class="nav-link" href="login.php">Login</a>
                <a class="nav-link" href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container my-5">
    <?php if ($message): ?> <!-- Ако има порака за статусот на нарачката, ќе се прикаже. -->
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <h2 class="text-center mb-4">Our Menu</h2> <!-- Наслов за менито. -->
    
    <div class="row row-cols-1 row-cols-md-3 g-4"> <!-- Прикажување на предметите во менито во форма на карти. -->
        <?php foreach ($menuItems as $item): ?> <!-- За секој предмет од менито ќе се креира посебна картичка. -->
            <div class="col">
                <div class="card h-100 menu-item"> <!-- Картичка за предметот. -->
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5> <!-- Име на предметот. -->
                        <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p> <!-- Опис на предметот. -->
                        <p class="price">$<?php echo number_format($item['price'], 2); ?></p> <!-- Цена на предметот. -->
                        
                        <form method="POST" action=""> <!-- Форма за поставување на нарачка. -->
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>"> <!-- Скриеен внес за идентификаторот на предметот. -->
                            <div class="d-flex align-items-center mb-3">
                                <label for="quantity-<?php echo $item['id']; ?>" class="me-2">Quantity:</label> <!-- Лабел за избор на количина. -->
                                <select class="form-select w-auto" id="quantity-<?php echo $item['id']; ?>" name="quantity">
                                    <?php for ($i = 1; $i <= 10; $i++): ?> <!-- Операција за избор на количина на предметот. -->
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <button type="submit" name="order" class="btn btn-primary">Order Now</button> <!-- Копче за потврда на нарачката. -->
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> <!-- Вклучување на Bootstrap JS. -->
</body>
</html>
