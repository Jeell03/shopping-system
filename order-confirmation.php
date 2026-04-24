<?php
ob_start();
session_start();
include 'config/database.php';
include 'includes/functions.php';

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$orderId) {
    redirect('index.php');
}

// Get order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    redirect('index.php');
}

// Get order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.name, p.image 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$orderId]);
$orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1><a href="index.php">ShopEasy</a></h1>
                </div>
                
                <div class="header-actions">
                    <div class="user-menu">
                        <a href="profile.php" class="user-link">
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <a href="logout.php" class="logout-link">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Order Confirmation -->
    <section class="order-confirmation">
        <div class="container">
            <div class="confirmation-content">
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <h1>Order Confirmed!</h1>
                    <p>Thank you for your purchase. Your order has been received and is being processed.</p>
                </div>
                
                <div class="order-details">
                    <div class="order-info">
                        <h2>Order Information</h2>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Order Number:</label>
                                <span><?php echo htmlspecialchars($order['order_number']); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Order Date:</label>
                                <span><?php echo date('F j, Y', strtotime($order['created_at'])); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Status:</label>
                                <span class="status status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Payment Method:</label>
                                <span><?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Total Amount:</label>
                                <span class="total-amount">$<?php echo number_format($order['total'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="shipping-info">
                        <h2>Shipping Address</h2>
                        <div class="address">
                            <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?>
                        </div>
                    </div>
                </div>
                
                <div class="order-items">
                    <h2>Order Items</h2>
                    <div class="items-list">
                        <?php foreach ($orderItems as $item): ?>
                        <div class="order-item">
                            <img src="<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p>Quantity: <?php echo $item['quantity']; ?></p>
                                <p class="item-price">$<?php echo number_format($item['price'], 2); ?> each</p>
                            </div>
                            <div class="item-total">
                                $<?php echo number_format($item['total'], 2); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>$<?php echo number_format($order['total'] - ($order['tax_amount'] ?? 0) - ($order['shipping_amount'] ?? 0), 2); ?></span>
                        </div>
                        <?php if ($order['shipping_amount'] > 0): ?>
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span>$<?php echo number_format($order['shipping_amount'], 2); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($order['tax_amount'] > 0): ?>
                        <div class="summary-row">
                            <span>Tax:</span>
                            <span>$<?php echo number_format($order['tax_amount'], 2); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span>$<?php echo number_format($order['total'], 2); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="next-steps">
                    <h2>What's Next?</h2>
                    <div class="steps-grid">
                        <div class="step">
                            <i class="fas fa-box"></i>
                            <h3>Order Processing</h3>
                            <p>We're preparing your order for shipment</p>
                        </div>
                        <div class="step">
                            <i class="fas fa-shipping-fast"></i>
                            <h3>Shipping</h3>
                            <p>Your order will be shipped within 1-2 business days</p>
                        </div>
                        <div class="step">
                            <i class="fas fa-truck"></i>
                            <h3>Delivery</h3>
                            <p>Expected delivery in 3-5 business days</p>
                        </div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="index.php" class="btn btn-primary">
                        <i class="fas fa-home"></i>
                        Continue Shopping
                    </a>
                    <a href="profile.php" class="btn btn-outline">
                        <i class="fas fa-user"></i>
                        View My Orders
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>ShopEasy</h3>
                    <p>Your trusted online shopping destination for quality products at great prices.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="products.php">All Products</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="faq.php">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Customer Service</h4>
                    <ul>
                        <li><a href="shipping.php">Shipping Info</a></li>
                        <li><a href="returns.php">Returns</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                        <li><a href="terms.php">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Connect With Us</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 ShopEasy. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>




