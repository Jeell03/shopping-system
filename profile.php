<?php
ob_start();
session_start();
include 'config/database.php';
include 'includes/functions.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$userId = $_SESSION['user_id'];
$user = getUser($userId);
$orders = getUserOrders($userId);

// Function to determine CSS class for status pill
function getStatusClass($status) {
    switch ($status) {
        case 'delivered':
            return 'status-delivered';
        case 'shipped':
            return 'status-shipped';
        case 'processing':
            return 'status-processing';
        case 'cancelled':
            return 'status-cancelled';
        case 'pending':
        default:
            return 'status-pending';
    }
}

function countOrderItems($orderId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM order_items WHERE order_id = ?");
    $stmt->execute([$orderId]);
    return $stmt->fetchColumn();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom styles for the profile page */
        .profile-section {
            padding: 4rem 0;
            background: #f8f9fa;
            min-height: 80vh;
        }

        .profile-content {
            display: grid;
            grid-template-columns: 280px 1fr; /* Sidebar and main content */
            gap: 3rem;
        }

        .profile-sidebar {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 2rem 0;
            height: fit-content;
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li a {
            display: block;
            padding: 1rem 2rem;
            color: #2c3e50;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.2s;
            font-weight: 500;
        }

        .sidebar-menu li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: #eaf4ff;
            border-left-color: #3498db;
            color: #3498db;
        }
        
        /* Main Content */
        .profile-main {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 2rem;
        }

        .profile-main h2 {
            margin-bottom: 2rem;
            color: #2c3e50;
            font-size: 2rem;
            border-bottom: 1px solid #e1e8ed;
            padding-bottom: 1rem;
        }

        /* Order List Styles */
        .order-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .order-card {
            border: 1px solid #e1e8ed;
            border-radius: 8px;
            padding: 1.5rem;
            transition: box-shadow 0.3s;
        }
        
        .order-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed #e1e8ed;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .order-header h3 {
            font-size: 1.2rem;
            color: #2c3e50;
            margin: 0;
        }

        .order-header a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }

        .order-body {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .order-info-item label {
            display: block;
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.25rem;
        }

        .order-info-item span {
            font-weight: 600;
            color: #333;
        }

        .status {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #d1ecf1; color: #0c5460; }
        .status-shipped { background: #cce5ff; color: #004085; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        
        /* Empty State */
        .empty-orders {
            text-align: center;
            padding: 4rem 2rem;
        }
        
        .empty-orders i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
        
        .empty-orders h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        /* Responsive Design for Profile */
        @media (max-width: 768px) {
            .profile-content {
                grid-template-columns: 1fr;
            }
            
            .profile-sidebar {
                padding: 1rem;
            }
            
            .sidebar-menu li a {
                padding: 0.75rem 1rem;
                text-align: center;
                border-left: none;
                border-bottom: 4px solid transparent;
            }
            
            .sidebar-menu ul {
                display: flex;
                justify-content: space-around;
            }
            
            .order-body {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .order-body {
                grid-template-columns: 1fr;
            }
            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1><a href="index.php">ShopEasy</a></h1>
                </div>
                
                <div class="header-actions">
                    <div class="user-menu">
                        <a href="profile.php" class="user-link active">
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <a href="logout.php" class="logout-link">Logout</a>
                    </div>
                    
                    <div class="cart">
                        <a href="cart.php" class="cart-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count"><?php echo getCartCount(); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="breadcrumb">
        <div class="container">
            <a href="index.php">Home</a> > 
            <span>My Account</span>
        </div>
    </div>

    <section class="profile-section">
        <div class="container">
            <h1>Welcome, <?php echo htmlspecialchars($user['first_name'] ?? $user['username']); ?></h1>
            <p>Manage your orders and account settings.</p>

            <div class="profile-content">
                <div class="profile-sidebar">
                    <div class="sidebar-menu">
                        <ul>
                            <li><a href="profile.php" class="active"><i class="fas fa-history"></i> My Orders</a></li>
                            <li><a href="wishlist.php"><i class="fas fa-heart"></i> My Wishlist</a></li>
                            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>

                <div class="profile-main">
                    <h2><i class="fas fa-box-open"></i> My Orders</h2>

                    <?php if (empty($orders)): ?>
                        <div class="empty-orders">
                            <i class="fas fa-history"></i>
                            <h3>No Orders Found</h3>
                            <p>You haven't placed any orders yet. Start shopping now!</p>
                            <a href="index.php" class="btn btn-primary">
                                <i class="fas fa-shopping-bag"></i> Continue Shopping
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="order-list">
                            <?php foreach ($orders as $order): ?>
                                <div class="order-card">
                                    <div class="order-header">
                                        <h3>Order #<?php echo htmlspecialchars($order['order_number']); ?></h3>
                                        <a href="order-confirmation.php?id=<?php echo $order['id']; ?>">View Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                    <div class="order-body">
                                        <div class="order-info-item">
                                            <label>Order Date</label>
                                            <span><?php echo date('M d, Y', strtotime($order['created_at'])); ?></span>
                                        </div>
                                        <div class="order-info-item">
                                            <label>Status</label>
                                            <span class="status <?php echo getStatusClass($order['status']); ?>"><?php echo ucfirst($order['status']); ?></span>
                                        </div>
                                        <div class="order-info-item">
                                            <label>Total</label>
                                            <span>$<?php echo number_format($order['total'], 2); ?></span>
                                        </div>
                                        <div class="order-info-item">
                                            <label>Items</label>
                                            <span><?php echo countOrderItems($order['id']); ?></span> 
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

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