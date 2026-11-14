
<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

require_login();

$user = current_user();

$roomId = filter_input(INPUT_POST, 'room_id', FILTER_VALIDATE_INT);
$checkIn = $_POST['check_in'] ?? '';
$checkOut = $_POST['check_out'] ?? '';
$guests = filter_input(INPUT_POST, 'guests', FILTER_VALIDATE_INT) ?: 1;

if (!$roomId || !$checkIn || !$checkOut) {
    redirect('availability.php');
}

$stmt = $pdo->prepare("
    SELECT * FROM rooms
    WHERE room_id = ? AND room_status = 'available'
");
$stmt->execute([$roomId]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room || $guests < 1 || $guests > $room['max_guests']) {
    redirect('availability.php');
}

$start = strtotime($checkIn);
$end = strtotime($checkOut);

if (
    !$start || !$end ||
    $checkIn < date('Y-m-d') ||
    $checkOut <= $checkIn
) {
    redirect('availability.php');
}

$check = $pdo->prepare("
    SELECT COUNT(*) FROM bookings
    WHERE room_id = ?
    AND booking_status IN ('pending', 'confirmed')
    AND check_in < ?
    AND check_out > ?
");
$check->execute([$roomId, $checkOut, $checkIn]);

if ($check->fetchColumn() > 0) {
    $error = "Sorry, this room is no longer available for those dates.";
} else {
    $nights = (int)(($end - $start) / 86400);
    $total = $nights * (float)$room['price_per_night'];
    $error = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_booking']) && !$error) {

    $check->execute([$roomId, $checkOut, $checkIn]);

    if ($check->fetchColumn() > 0) {
        $error = "This room has just been booked for those dates. Please search again.";
    } else {
        $insert = $pdo->prepare("
            INSERT INTO bookings
            (user_id, room_id, check_in, check_out, total_amount, booking_status)
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");

        $insert->execute([
            $user['user_id'],
            $roomId,
            $checkIn,
            $checkOut,
            $total
        ]);

        redirect('my-bookings.php');
    }
}

require_once 'includes/header.php';
?>

<section class="page-banner">
    <h1>Confirm Your Booking</h1>
    <p>Review your booking details</p>
</section>

<section class="section">
    <div class="container">

        <div class="booking-form">

            <h2>Booking Summary</h2>

            <?php if ($error): ?>
                <p class="error-message"><?php echo e($error); ?></p>
                <a href="availability.php" class="btn">Search Again</a>
            <?php else: ?>

                <h3><?php echo e($room['room_name']); ?></h3>

                <p>Guest: <?php echo e($user['name']); ?></p>
                <p>Check-in: <?php echo e($checkIn); ?></p>
                <p>Check-out: <?php echo e($checkOut); ?></p>
                <p>Number of guests: <?php echo (int)$guests; ?></p>
                <p>Number of nights: <?php echo $nights; ?></p>

                <h3>
                    Total Amount: Rs.
                    <?php echo number_format($total, 2); ?>
                </h3>

                <p>Your booking will be sent to the administrator for confirmation.</p>

                <form method="POST" action="booking.php">

                    <input type="hidden" name="room_id"
                        value="<?php echo (int)$roomId; ?>">

                    <input type="hidden" name="check_in"
                        value="<?php echo e($checkIn); ?>">

                    <input type="hidden" name="check_out"
                        value="<?php echo e($checkOut); ?>">

                    <input type="hidden" name="guests"
                        value="<?php echo (int)$guests; ?>">

                    <button type="submit" name="confirm_booking" class="btn">
                        Confirm Booking Request
                    </button>

                </form>

            <?php endif; ?>

        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
