<?php
session_start(); // Starts the session for login tracking

$host = 'localhost';
$port = '8889'; 
$db   = 'COSC213';
$user = 'Admin';
$pass = '';
$message = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (strlen($username) < 3 || strlen($username) > 20) {
            $message = "Username must be between 3 and 20 characters.";
        } elseif (strlen($password) < 6 || strlen($password) > 20) {
            $message = "Password must be between 6 and 20 characters.";
        } else {
            $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);

            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $username;
                    header("Location: board.php");
                    exit;
                } else {
                    $message = "Incorrect password.";
                }
            } else {
                $message = "Username not found.";
            }
        }
    }
} catch (PDOException $e) {
    $message = "Database error: " . $e->getMessage();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Project Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-primary bg-gradient text-white">    
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <div class="container-fluid">
            <a href="#" class="navbar-brand px-5">APP NAME</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Account</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="myAccount.php">View My Account</a></li>
                            <li><a class="dropdown-item" href="login.php">Login</a></li>
                            <li><a class="dropdown-item" href="register.php">Sign Up</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow" style="width: 100%; max-width: 500px;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Login to Your Account</h3>
                <form method="post" action="login.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required
                            value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required
                            value="<?php echo htmlspecialchars($_POST['password'] ?? ''); ?>">
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Login</button>
                    <div class="mt-3 text-center">
                        <p>Need an account? <a href="register.php">Register here</a></p>
                    </div>
                </form>
                <?php if ($message): ?>
                    <div class="alert alert-danger mt-3 text-center" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>