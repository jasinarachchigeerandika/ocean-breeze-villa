
<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

require_login();

$user = current_user();

$stmt = $pdo->prepare("
    SELECT b.*, r.room_name, r.room_type
    FROM bookings b
    INNER JOIN rooms r ON b.room_id = r.room_id
    WHERE b.user_id = ?
    ORDER BY b.booking_date DESC
");

$stmt->execute([$user['user_id']]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once 'includes/header.php';
?>

<section class="page-banner">
    <h1>My Bookings</h1>
    <p>View your booking history and status</p>
</section>

<section class="section">
    <div class="container">

        <h2>My Booking History</h2>

        <?php if (count($bookings) > 0): ?>

            <div class="table-container">

                <table>
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Room</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($bookings as $booking): ?>

                            <tr>
                                <td><?php echo (int)$booking['booking_id']; ?></td>

                                <td><?php echo e($booking['room_name']); ?></td>

                                <td><?php echo e($booking['check_in']); ?></td>

                                <td><?php echo e($booking['check_out']); ?></td>

                                <td>
                                    Rs. <?php echo number_format($booking['total_amount'], 2); ?>
                                </td>

                                <td>
                                    <?php echo e(ucfirst($booking['booking_status'])); ?>
                                </td>

                                <td>
                                    <?php if ($booking['booking_status'] === 'pending'): ?>

                                        <form method="POST" action="cancel-booking.php"
                                            onsubmit="return confirm('Are you sure you want to cancel this booking?');">

                                            <input type="hidden" name="booking_id"
                                                value="<?php echo (int)$booking['booking_id']; ?>">

                                            <button type="submit" class="btn btn-danger">
                                                Cancel
                                            </button>

                                        </form>

                                    <?php else: ?>

                                        No Action

                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>

            </div>

        <?php else: ?>

            <p>You have not made any bookings yet.</p>

            <a href="rooms.php" class="btn">Explore Rooms</a>

        <?php endif; ?>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
