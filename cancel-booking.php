
<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('my-bookings.php');
}

$user = current_user();

$bookingId = filter_input(INPUT_POST, 'booking_id', FILTER_VALIDATE_INT);

if (!$bookingId) {
    redirect('my-bookings.php');
}

$stmt = $pdo->prepare("
    UPDATE bookings
    SET booking_status = 'cancelled'
    WHERE booking_id = ?
    AND user_id = ?
    AND booking_status = 'pending'
");

$stmt->execute([$bookingId, $user['user_id']]);

redirect('my-bookings.php');
?>
