
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Escape output
function e($value) {
    return htmlspecialchars(
        $value ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}

// Redirect
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Get current user information
function current_user() {

    if (isset($_SESSION['user']) &&
        is_array($_SESSION['user'])) {

        return $_SESSION['user'];
    }

    if (isset($_SESSION['user_id'])) {

        return [
            'user_id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'] ?? '',
            'role' => $_SESSION['user_role'] ?? 'customer'
        ];
    }

    return null;
}

// Check login status
function is_logged_in() {
    return current_user() !== null;
}

// Check admin status
function is_admin() {

    $user = current_user();

    return $user !== null
        && ($user['role'] ?? '') === 'admin';
}

// Require login
function require_login() {

    if (!is_logged_in()) {
        redirect('login.php');
    }
}

// Require admin
function require_admin() {

    if (!is_admin()) {
        redirect('../login.php');
    }
}

// Flash message
function set_flash($message, $type = 'success') {

    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type
    ];
}

function get_flash() {

    $flash = $_SESSION['flash'] ?? null;

    unset($_SESSION['flash']);

    return $flash;
}

// CSRF Token
function csrf_token() {

    if (empty($_SESSION['csrf_token'])) {

        $_SESSION['csrf_token'] =
            bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf() {

    $sent = $_POST['csrf_token'] ?? '';

    if (!is_string($sent) ||
        !hash_equals(csrf_token(), $sent)) {

        exit("Invalid form token.");
    }
}

// Money format
function money($amount) {

    return "LKR " .
        number_format((float)$amount, 2);
}

// Validate booking dates
function valid_date_range($checkIn, $checkOut) {

    return $checkIn >= date('Y-m-d')
        && $checkOut > $checkIn;
}

// Calculate booking nights
function booking_nights($checkIn, $checkOut) {

    return (strtotime($checkOut) -
        strtotime($checkIn)) / 86400;
}

// Check room availability
function room_is_available(
    $pdo,
    $roomId,
    $checkIn,
    $checkOut,
    $ignoreBookingId = null
) {

    $sql = "SELECT COUNT(*) FROM bookings
            WHERE room_id = ?
            AND booking_status IN ('pending', 'confirmed')
            AND check_in < ?
            AND check_out > ?";

    $params = [
        $roomId,
        $checkOut,
        $checkIn
    ];

    if ($ignoreBookingId !== null) {

        $sql .= " AND booking_id != ?";

        $params[] = $ignoreBookingId;
    }

    $stmt = $pdo->prepare($sql);

    $stmt->execute($params);

    return $stmt->fetchColumn() == 0;
}

?>
