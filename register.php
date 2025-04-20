<?php
session_start(); // Почеток на сесијата за да се користат податоците за најавениот корисници.

require_once 'config/database.php'; // Вклучување на конфигурацијата за врска со базата на податоци.
require_once 'classes/User.php'; // Вклучување на класата User која се користи за регистрација на корисниците.

$error = ''; // Празна променлива за да се чуваат грешките.
$success = ''; // Празна променлива за успешни пораки.

if (isset($_SESSION['user_id'])) { // Проверка дали корисникот е најавен. Ако е, го пренасочуваме на почетната страница.
    header('Location: index.php');
    exit();
}

// Проверка дали е направено POST барање.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = connectDB(); // Поврзување со базата на податоци.
    $user = new User($db); // Креирање на објект од класата User за регистрација на корисниците.

    // Ги земаме податоците од формата
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    
    // Валидација на податоците од формата
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = "All fields are required"; // Ако некој од податоците е празен, прикажуваме грешка.
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format"; // Ако форматот на email-то е погрешен, прикажуваме грешка.
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match"; // Ако лозинките не се совпаѓаат, прикажуваме грешка.
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long"; // Ако лозинката е пократка од 8 знаци, прикажуваме грешка.
    } else {
        // Ако сите проверки се поминати, се обидуваме да регистрираме нов корисник.
        if ($user->register($username, $email, $password, $firstName, $lastName)) {
            $success = "Registration successful! Please login."; // Успешна регистрација.
        } else {
            $error = "Registration failed. Email might already be in use."; // Ако има проблем со регистрацијата, прикажуваме грешка.
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Coffee Shop</title> <!-- Наслов на страницата. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Вклучување на Bootstrap за стилови. -->
</head>
<body class="bg-light">
    <div class="container"> <!-- Контејнер за содржината на страната. -->
        <div class="row justify-content-center mt-5"> <!-- Центрирање на формата на страната. -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Register</h3> <!-- Наслов на картичката (форма за регистрација). -->
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?> <!-- Ако има грешка, прикажуваме грешка. -->
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?> <!-- Ако има успешна регистрација, прикажуваме успешна порака. -->
                            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <!-- Форма за регистрација -->
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required> <!-- Поле за корисничко име -->
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required> <!-- Поле за email -->
                            </div>
                            
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"> <!-- Поле за име -->
                            </div>
                            
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"> <!-- Поле за презиме -->
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required> <!-- Поле за лозинка -->
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required> <!-- Поле за потврда на лозинка -->
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Register</button> <!-- Кнопка за потврда на регистрација -->
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            Already have an account? <a href="login.php">Login here</a> <!-- Линк за логирање ако корисникот веќе има акаунт -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> <!-- Вклучување на Bootstrap JS за функционалност на компонентите. -->
</body>
</html>
