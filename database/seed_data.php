<?php
require_once __DIR__ . '/../config/database.php';

echo "Standardizing Database and Seeding Rich Data...\n";

// 1. Fix Levi's jeans image path
$stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = 6");
$stmt->execute(["Image/Levi's 501 Jeans.webp"]);
echo "Fixed Levi's jeans image path.\n";

// 2. Fetch existing user IDs
$userIds = $pdo->query("SELECT id FROM users")->fetchAll(PDO::FETCH_COLUMN);
$u1 = $userIds[0] ?? 1;
$u2 = $userIds[1] ?? $u1;
$u3 = $userIds[2] ?? $u1;

// 3. Seed realistic reviews
$reviewsCount = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
if ($reviewsCount < 5) {
    $sampleReviews = [
        [1, $u1, 5, 'Phenomenal device and build quality!', 'The titanium design feels so light in hand compared to previous generations. The camera in low light is mind-blowing.', 'approved'],
        [1, $u2, 5, 'Best phone ever', 'Switching from an older phone was seamless. The screen is buttery smooth and battery lasts easily all day.', 'approved'],
        [1, $u3, 4, 'Great performance, slightly warm when gaming', 'Super fast phone, love the Action button. Runs slightly warm during heavy 3D gaming but cools down fast.', 'approved'],
        [2, $u2, 5, 'AI features are super helpful!', 'Circle to Search and real-time live translation are game changers for my work. Display is vibrant and bright outside.', 'approved'],
        [2, $u1, 4, 'Solid flagship smartphone', 'Clean One UI interface, stellar battery life, and high quality cameras. Highly recommended.', 'approved'],
        [3, $u3, 5, 'Unbelievable battery life and performance', 'I get nearly 16 hours of coding and browsing on a single charge. Zero fan noise because it is fanless, yet stays cool.', 'approved'],
        [3, $u1, 5, 'Worth every penny', 'Lightweight, gorgeous display, snappy response for design and video editing.', 'approved'],
        [4, $u2, 5, 'Super comfortable for daily running', 'The heel bubble provides great cushion. Looks fantastic with athletic wear or jeans.', 'approved'],
        [4, $u3, 4, 'Looks stylish and fits true to size', 'Very comfortable sneaker. Delivery was swift, arrived within 2 days.', 'approved'],
        [6, $u1, 5, 'Classic timeless denim fit', 'The authentic 501 cut never goes out of style. Durable material with just the right amount of structure.', 'approved'],
        [7, $u2, 5, 'Laser reveals hidden dust, powerful suction!', 'You would be shocked how much dust this picks up that standard vacuums miss. The laser head is incredible.', 'approved'],
        [8, $u3, 5, 'Baking essential', 'Heavy duty motor kneads bread dough effortlessly. A gorgeous centerpiece on my kitchen counter.', 'approved']
    ];

    $revStmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, rating, title, comment, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    foreach ($sampleReviews as $rev) {
        $revStmt->execute($rev);
    }
    echo "Seeded " . count($sampleReviews) . " sample reviews.\n";
}

// 4. Verify Coupons
$couponCount = $pdo->query("SELECT COUNT(*) FROM coupons")->fetchColumn();
if ($couponCount < 3) {
    $coupons = [
        ['WELCOME10', 'Welcome voucher: 10% off your order', 'percentage', 10.00, 50.00, 100.00, 100, 0, 'active'],
        ['SAVE50', 'Save $50 flat on orders over $200', 'fixed', 50.00, 200.00, 50.00, 50, 0, 'active'],
        ['SUMMER20', 'Summer Special: 20% off', 'percentage', 20.00, 100.00, 80.00, 200, 0, 'active'],
        ['FESTIVE25', 'Festive Offer: 25% off above $150', 'percentage', 25.00, 150.00, 100.00, 100, 0, 'active']
    ];
    $cStmt = $pdo->prepare("INSERT IGNORE INTO coupons (code, description, discount_type, discount_value, minimum_amount, maximum_discount, usage_limit, used_count, valid_from, valid_until, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), ?)");
    foreach ($coupons as $c) {
        $cStmt->execute($c);
    }
    echo "Seeded coupons.\n";
}

// 5. Ensure users table has role column
try {
    $pdo->query("SELECT role FROM users LIMIT 1");
} catch (Exception $e) {
    $pdo->exec("ALTER TABLE users ADD COLUMN role ENUM('customer', 'admin') DEFAULT 'customer' AFTER country");
    $pdo->exec("UPDATE users SET role = 'admin' WHERE username = 'admin' OR email LIKE '%admin%'");
    echo "Added role column to users table and assigned admin.\n";
}

// 6. Seed sample addresses if user has empty addresses
$pdo->exec("UPDATE users SET address = 'Flat 402, Lotus Residency, MG Road', city = 'Mumbai', state = 'Maharashtra', zip_code = '400001', country = 'India', phone = '+91 9876543210' WHERE (address IS NULL OR address = '') AND id > 0");

echo "Database standardization complete!\n";
?>
