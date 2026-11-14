
<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

$stmt = $pdo->query("SELECT * FROM rooms
                     WHERE room_status = 'available'
                     ORDER BY room_id DESC
                     LIMIT 3");

$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <p class="hero-subtitle">WELCOME TO OCEAN BREEZE VILLA</p>

        <h1>Relax, Refresh & Enjoy<br>the Ocean View</h1>

        <p>
            Experience a peaceful coastal holiday
            at our beautiful villa in Mirissa, Sri Lanka.
        </p>

        <a href="rooms.php" class="btn">Explore Our Rooms</a>
        <a href="availability.php" class="btn btn-outline">
            Book Your Stay
        </a>
    </div>
</section>

<!-- About Section -->
<section class="section">
    <div class="container">
        <div class="section-heading">
            <p class="small-title">DISCOVER OUR VILLA</p>
            <h2>Welcome to Ocean Breeze Villa</h2>
        </div>

        <p class="about-text">
            Ocean Breeze Villa is a relaxing coastal destination
            located in the beautiful area of Mirissa, Sri Lanka.
            Enjoy comfortable accommodation, friendly service
            and unforgettable ocean experiences.
        </p>

        <div class="features">
            <div class="feature-card">
                <h3>Ocean View</h3>
                <p>Enjoy beautiful views of the ocean.</p>
            </div>

            <div class="feature-card">
                <h3>Comfortable Rooms</h3>
                <p>Relax in our well-designed rooms.</p>
            </div>

            <div class="feature-card">
                <h3>Free Wi-Fi</h3>
                <p>Stay connected during your holiday.</p>
            </div>
        </div>
    </div>
</section>

<!-- Rooms Section -->
<section class="section rooms-section">
    <div class="container">

        <div class="section-heading">
            <p class="small-title">OUR ACCOMMODATION</p>
            <h2>Explore Our Rooms</h2>
            <p>Choose a comfortable room for your stay.</p>
        </div>

        <div class="room-grid">

            <?php foreach ($rooms as $room): ?>

                <div class="room-card">

                    <img
                        src="<?php echo e($room['image_url']); ?>"
                        alt="<?php echo e($room['room_name']); ?>"
                    >

                    <div class="room-card-content">

                        <h3>
                            <?php echo e($room['room_name']); ?>
                        </h3>

                        <p>
                            <?php echo e($room['room_type']); ?>
                        </p>

                        <h4>
                            Rs. <?php echo number_format($room['price_per_night'], 2); ?>
                            <small>/ night</small>
                        </h4>

                        <a
                            href="room-details.php?id=<?php echo (int)$room['room_id']; ?>"
                            class="btn"
                        >
                            View Details
                        </a>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>

        <div class="center-button">
            <a href="rooms.php" class="btn">View All Rooms</a>
        </div>

    </div>
</section>

<!-- Booking Section -->
<section class="booking-banner">
    <div class="container">
        <h2>Plan Your Perfect Holiday</h2>

        <p>
            Find your ideal room and book your stay with us.
        </p>

        <a href="availability.php" class="btn">
            Check Availability
        </a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
