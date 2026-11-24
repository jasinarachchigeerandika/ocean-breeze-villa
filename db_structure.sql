
CREATE DATABASE IF NOT EXISTS ocean_breeze_villa;

USE ocean_breeze_villa;

-- 1. Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    contact_number VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Rooms Table
CREATE TABLE rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    room_name VARCHAR(100) NOT NULL,
    room_type VARCHAR(100) NOT NULL,
    description TEXT,
    price_per_night DECIMAL(10,2) NOT NULL,
    max_guests INT NOT NULL,
    room_status ENUM('available', 'unavailable') DEFAULT 'available',
    image_url VARCHAR(500),
    facilities TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Bookings Table
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    booking_status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (room_id) REFERENCES rooms(room_id)
);

-- Sample Rooms
INSERT INTO rooms
(room_name, room_type, description, price_per_night, max_guests, room_status, image_url, facilities)
VALUES
(
    'Ocean View Deluxe',
    'Deluxe Room',
    'Beautiful deluxe room with an amazing ocean view.',
    15000.00,
    2,
    'available',
    'https://images.unsplash.com/photo-1611892440504-42a792e24d32',
    'Air Conditioning, Wi-Fi, Private Bathroom, Ocean View'
),
(
    'Luxury Family Suite',
    'Family Room',
    'Spacious family suite suitable for a relaxing holiday.',
    25000.00,
    4,
    'available',
    'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6',
    'Air Conditioning, Wi-Fi, TV, Balcony'
),
(
    'Coastal Garden Room',
    'Standard Room',
    'Comfortable room surrounded by a beautiful tropical garden.',
    10000.00,
    2,
    'available',
    'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace',
    'Wi-Fi, Garden View, Private Bathroom'
);
