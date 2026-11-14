
<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

$roomId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$roomId) {
    redirect('rooms.php');
}

$stmt = $pdo->prepare("SELECT * FROM rooms WHERE room_id = ?");
$stmt->execute([$roomId]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    redirect('rooms.php');
}

require_once 'includes/header.php';
?>

<section class="page-banner">
    <h1>Room Details</h1>
    <p>Discover your comfortable stay</p>
</section>

<section class="section">
    <div class="container">

        <div class="room-details">

            <div class="room-details-image">
                <img
                    src="<?php echo e($room['image_url']); ?>"
                    alt="<?php echo e($room['room_name']); ?>"
                >
            </div>

            <div class="room-details-info">

                <p class="small-title">
                    <?php echo e($room['room_type']); ?>
                </p>

                <h2><?php echo e($room['room_name']); ?></h2>

                <p><?php echo e($room['description']); ?></p>

                <h3>
                    Rs. <?php echo number_format($room['price_per_night'], 2); ?>
                    <small>/ night</small>
                </h3>

                <p>
                    <strong>Maximum Guests:</strong>
                    <?php echo (int)$room['max_guests']; ?>
                </p>

                <p>
                    <strong>Facilities:</strong>
                    <?php echo e($room['facilities']); ?>
                </p>

                <p>
                    <strong>Status:</strong>
                    <?php echo e(ucfirst($room['room_status'])); ?>
                </p>

                <?php if ($room['room_status'] === 'available'): ?>

                    <a
                        href="availability.php?room_id=<?php echo (int)$room['room_id']; ?>"
                        class="btn"
                    >
                        Check Availability
                    </a>

                <?php else: ?>

                    <p>This room is currently unavailable.</p>

                <?php endif; ?>

                <a href="rooms.php" class="btn btn-outline">
                    Back to Rooms
                </a>

            </div>

        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
