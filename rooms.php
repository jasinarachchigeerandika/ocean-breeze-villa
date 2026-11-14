
<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

$stmt = $pdo->query("SELECT * FROM rooms ORDER BY room_id DESC");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once 'includes/header.php';
?>

<section class="page-banner">
    <h1>Our Rooms</h1>
    <p>Find the perfect room for your holiday</p>
</section>

<section class="section">
    <div class="container">

        <div class="section-heading">
            <h2>Discover Our Accommodation</h2>
            <p>Choose a room that suits your needs.</p>
        </div>

        <div class="room-grid">

            <?php if (count($rooms) > 0): ?>

                <?php foreach ($rooms as $room): ?>

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

                            <?php if ($room['room_status'] === 'available'): ?>

                                <a
                                    href="room-details.php?id=<?php echo (int)$room['room_id']; ?>"
                                    class="btn"
                                >
                                    View Details
                                </a>

                            <?php else: ?>

                                <p>Currently Unavailable</p>

                            <?php endif; ?>

                        </div>
                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <p>No rooms are available at the moment.</p>

            <?php endif; ?>

        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
