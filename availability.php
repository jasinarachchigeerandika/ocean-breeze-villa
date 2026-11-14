
<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

$checkIn = '';
$checkOut = '';
$guests = 1;
$roomId = filter_input(INPUT_GET, 'room_id', FILTER_VALIDATE_INT) ?: 0;
$availableRooms = [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $checkIn = $_POST['check_in'] ?? '';
    $checkOut = $_POST['check_out'] ?? '';
    $guests = filter_input(INPUT_POST, 'guests', FILTER_VALIDATE_INT) ?: 0;
    $roomId = filter_input(INPUT_POST, 'room_id', FILTER_VALIDATE_INT) ?: 0;

    if (!$checkIn || !$checkOut || $guests < 1) {
        $error = "Please enter valid booking details.";

    } elseif (
        !strtotime($checkIn) ||
        !strtotime($checkOut) ||
        $checkIn < date('Y-m-d') ||
        $checkOut <= $checkIn
    ) {
        $error = "Please select valid check-in and check-out dates.";

    } else {

        $sql = "
            SELECT r.*
            FROM rooms r
            WHERE r.room_status = 'available'
            AND r.max_guests >= ?
            AND NOT EXISTS (
                SELECT 1
                FROM bookings b
                WHERE b.room_id = r.room_id
                AND b.booking_status IN ('pending', 'confirmed')
                AND b.check_in < ?
                AND b.check_out > ?
            )
        ";

        $params = [$guests, $checkOut, $checkIn];

        if ($roomId > 0) {
            $sql .= " AND r.room_id = ?";
            $params[] = $roomId;
        }

        $sql .= " ORDER BY r.price_per_night ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $availableRooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

require_once 'includes/header.php';
?>

<section class="page-banner">
    <h1>Check Availability</h1>
    <p>Find an available room for your holiday</p>
</section>

<section class="section">
    <div class="container">

        <div class="booking-form">

            <h2>Search Available Rooms</h2>

            <?php if ($error): ?>
                <p class="error-message"><?php echo e($error); ?></p>
            <?php endif; ?>

            <form method="POST" action="availability.php">

                <input
                    type="hidden"
                    name="room_id"
                    value="<?php echo (int)$roomId; ?>"
                >

                <label>Check-in Date</label>
                <input
                    type="date"
                    name="check_in"
                    min="<?php echo date('Y-m-d'); ?>"
                    value="<?php echo e($checkIn); ?>"
                    required
                >

                <label>Check-out Date</label>
                <input
                    type="date"
                    name="check_out"
                    min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                    value="<?php echo e($checkOut); ?>"
                    required
                >

                <label>Number of Guests</label>
                <input
                    type="number"
                    name="guests"
                    min="1"
                    max="20"
                    value="<?php echo (int)$guests; ?>"
                    required
                >

                <button type="submit" class="btn">
                    Search Rooms
                </button>

            </form>

        </div>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error): ?>

            <div class="section-heading">
                <h2>Available Rooms</h2>
            </div>

            <?php if (count($availableRooms) > 0): ?>

                <div class="room-grid">

                    <?php foreach ($availableRooms as $room): ?>

                        <div class="room-card">

                            <img
                                src="<?php echo e($room['image_url']); ?>"
                                alt="<?php echo e($room['room_name']); ?>"
                            >

                            <div class="room-card-content">

                                <h3><?php echo e($room['room_name']); ?></h3>

                                <p><?php echo e($room['room_type']); ?></p>

                                <p>
                                    Maximum Guests:
                                    <?php echo (int)$room['max_guests']; ?>
                                </p>

                                <h4>
                                    Rs. <?php echo number_format($room['price_per_night'], 2); ?>
                                    <small>/ night</small>
                                </h4>

                                <form method="POST" action="booking.php">

                                    <input type="hidden" name="room_id"
                                        value="<?php echo (int)$room['room_id']; ?>">

                                    <input type="hidden" name="check_in"
                                        value="<?php echo e($checkIn); ?>">

                                    <input type="hidden" name="check_out"
                                        value="<?php echo e($checkOut); ?>">

                                    <input type="hidden" name="guests"
                                        value="<?php echo (int)$guests; ?>">

                                    <button type="submit" class="btn">
                                        Book This Room
                                    </button>

                                </form>

                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <p>No rooms are available for the selected dates.</p>

            <?php endif; ?>

        <?php endif; ?>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
