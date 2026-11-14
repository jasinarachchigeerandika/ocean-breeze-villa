
<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact_number'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid name and email address.";

    } elseif (strlen($password) < 8) {
        $error = "Password must contain at least 8 characters.";

    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";

    } else {

        $check = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetch()) {
            $error = "This email address is already registered.";

        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insert = $pdo->prepare("
                INSERT INTO users
                (name, email, contact_number, password, role)
                VALUES (?, ?, ?, ?, 'customer')
            ");

            $insert->execute([
                $name,
                $email,
                $contact,
                $hashedPassword
            ]);

            redirect('login.php');
        }
    }
}

require_once 'includes/header.php';
?>

<section class="page-banner">
    <h1>Create Account</h1>
    <p>Register with Ocean Breeze Villa</p>
</section>

<section class="section">
    <div class="container">

        <div class="booking-form">

            <h2>Customer Registration</h2>

            <?php if ($error): ?>
                <p class="error-message"><?php echo e($error); ?></p>
            <?php endif; ?>

            <form method="POST" action="register.php">

                <label>Full Name</label>
                <input type="text" name="name" required>

                <label>Email Address</label>
                <input type="email" name="email" required>

                <label>Contact Number</label>
                <input type="text" name="contact_number">

                <label>Password</label>
                <input type="password" name="password" minlength="8" required>

                <label>Confirm Password</label>
                <input type="password" name="confirm_password" minlength="8" required>

                <button type="submit" class="btn">
                    Register
                </button>

            </form>

            <p>Already have an account?
                <a href="login.php">Login here</a>
            </p>

        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
