<?php
ob_start(); // Start output buffering for safe redirects
session_start();
include 'config/database.php';
include 'includes/functions.php';

// Prepare user data for header if logged in
$isLoggedIn = isLoggedIn();
$username = $isLoggedIn ? htmlspecialchars($_SESSION['username']) : '';
$cartCount = getCartCount();
$wishlistCount = $isLoggedIn ? getWishlistCount($_SESSION['user_id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returns & Refunds Policy - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom styles for the Returns page, reusing general layouts */
        .returns-section {
            padding: 4rem 0;
            background: white;
            min-height: 80vh;
        }

        .returns-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .returns-header h1 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 3rem;
        }

        .returns-header p {
            color: #666;
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .policy-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .policy-section {
            margin-bottom: 3rem;
            padding: 2rem;
            background: #f8f9fa;
            border-left: 5px solid #e74c3c; /* Return policy accent color */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .policy-section h2 {
            color: #e74c3c; /* Red for emphasis on returns */
            margin-top: 0;
            margin-bottom: 1.5rem;
            padding-bottom: 10px;
            border-bottom: 1px dashed #e1e8ed;
            font-size: 2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .policy-section h3 {
            color: #34495e;
            margin-top: 2rem;
            margin-bottom: 0.8rem;
            font-size: 1.5rem;
        }

        .policy-section p, .policy-section ul {
            line-height: 1.7;
            color: #555;
        }

        .policy-section ul {
            list-style: disc;
            margin-left: 20px;
            margin-bottom: 1rem;
        }
        
        .contact-box {
            text-align: center;
            padding: 2rem;
            background: #d1ecf1;
            border-radius: 8px;
            border: 1px solid #bee5eb;
            margin-top: 3rem;
        }
        
        .contact-box h3 {
            color: #0c5460;
            margin-bottom: 1rem;
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
                
                <div class="search-bar">
                    <form action="search.php" method="GET">
                        <input type="text" name="query" placeholder="Search products..." required>
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                
                <div class="header-actions">
                    <div class="user-menu">
                        <?php if ($isLoggedIn): ?>
                            <a href="profile.php" class="user-link">
                                <i class="fas fa-user"></i>
                                <?php echo $username; ?>
                            </a>
                            <a href="logout.php" class="logout-link">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="login-link">Login</a>
                            <a href="register.php" class="register-link">Register</a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="header-icons">
                        <?php if ($isLoggedIn): ?>
                            <a href="wishlist.php" class="wishlist-link">
                                <i class="fas fa-heart"></i>
                                <span class="wishlist-count"><?php echo $wishlistCount; ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <a href="cart.php" class="cart-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count"><?php echo $cartCount; ?></span>
                        </a>
                    </div>
                </div>
            </div>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">All Products</a></li>
                    <li><a href="products.php?category=electronics">Electronics</a></li>
                    <li><a href="products.php?category=clothing">Clothing</a></li>
                    <li><a href="products.php?category=home">Home & Garden</a></li>
                    <li><a href="products.php?category=sports">Sports</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="breadcrumb">
        <div class="container">
            <a href="index.php">Home</a> > 
            <span>Returns Policy</span>
        </div>
    </div>

    <section class="returns-section">
        <div class="container policy-container">
            <div class="returns-header">
                <h1>Hassle-Free Returns & Refunds</h1>
                <p>Your satisfaction is our priority. If you're not completely happy with your purchase, we're here to help.</p>
            </div>

            <div class="policy-section">
                <h2><i class="fas fa-undo"></i> 30-Day Return Policy</h2>
                <p>You have **30 days** from the date you received your order to request a return or exchange.</p>

                <h3>Eligibility Requirements:</h3>
                <ul>
                    <li>The item must be unused, unwashed, and in the same condition that you received it.</li>
                    <li>The item must be in the original packaging with all tags attached.</li>
                    <li>Proof of purchase (order number) is required for all returns.</li>
                </ul>
            </div>
            
            <div class="policy-section">
                <h2><i class="fas fa-exchange-alt"></i> How to Initiate a Return</h2>
                <p>Follow these simple steps to start your return process:</p>
                <ol>
                    <li>**Contact Us:** Send an email to support@shopeasy.com with your Order Number and the reason for the return.</li>
                    <li>**Receive Authorization:** Our team will review your request and send you a Return Merchandise Authorization (RMA) number and the return shipping address.</li>
                    <li>**Ship Item:** Securely package the item and clearly label it with the provided RMA number. Customers are responsible for return shipping costs unless the return is due to our error (e.g., defective or wrong item).</li>
                </ol>
            </div>

            <div class="policy-section">
                <h2><i class="fas fa-money-check-alt"></i> Refunds & Processing</h2>

                <h3>Refund Method</h3>
                <p>Refunds will be processed to your original payment method. We cannot issue refunds to a different card or bank account.</p>

                <h3>Processing Time</h3>
                <ul>
                    <li>Once your return is received and inspected, we will send you an email notification.</li>
                    <li>Refunds are processed within **5-7 business days** after approval.</li>
                    <li>It may take an additional 3-10 business days for the credit to appear on your bank or credit card statement.</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2><i class="fas fa-exclamation-triangle"></i> Non-Returnable Items</h2>
                <p>The following items are exempt from being returned:</p>
                <ul>
                    <li>Gift cards</li>
                    <li>Downloadable software products</li>
                    <li>Items marked as "Final Sale" or "Clearance"</li>
                    <li>Personalized or customized items</li>
                    <li>Items damaged due to misuse or neglect</li>
                </ul>
            </div>
            
            <div class="contact-box">
                <h3>Need Personal Assistance?</h3>
                <p>If you have any questions regarding your return, please don't hesitate to reach out.</p>
                <a href="contact.php" class="btn btn-outline"><i class="fas fa-headset"></i> Contact Support</a>
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