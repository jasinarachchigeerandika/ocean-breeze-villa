
<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        if ($user['role'] === 'admin') {
            redirect('admin/dashboard.php');
        } else {
            redirect('index.php');
        }

    } else {
        $error = "Invalid email address or password.";
    }
}

require_once 'includes/header.php';
?>

<section class="page-banner">
    <h1>Login</h1>
    <p>Welcome back to Ocean Breeze Villa</p>
</section>

<section class="section">
    <div class="container">

        <div class="booking-form">

            <h2>Login to Your Account</h2>

            <?php if ($error): ?>
                <p class="error-message"><?php echo e($error); ?></p>
            <?php endif; ?>

            <form method="POST" action="login.php">

                <label>Email Address</label>
                <input type="email" name="email" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <button type="submit" class="btn">
                    Login
                </button>

            </form>

            <p>Don't have an account?
                <a href="register.php">Register here</a>
            </p>

        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
