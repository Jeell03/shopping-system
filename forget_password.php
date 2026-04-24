<?php
ob_start();
session_start();
include 'config/database.php'; // Assuming this includes your database connection logic
include 'includes/functions.php'; // Assuming this includes utility functions like 'redirect', 'sanitize'

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

$message = '';
$message_type = ''; // 'success' or 'error'

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    
    if (empty($email)) {
        $message = 'Please enter your email address.';
        $message_type = 'error';
    } else {
        // --- START: Placeholder for Password Reset Logic ---
        
        // 1. Check if the email exists in the 'users' table (using the provided schema.sql)
        // In a real application, you would query the database:
        // $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        // $stmt->execute([$email]);
        // $user = $stmt->fetch();
        
        $user_exists = true; // Placeholder: Assume the user exists for demonstration
        
        if ($user_exists) {
            // 2. Generate a unique reset token and expiration time
            // $token = bin2hex(random_bytes(32));
            // $expires = time() + (60 * 30); // 30 minutes
            
            // 3. Save the token and expiration to the user's record (e.g., in a 'password_resets' table or the 'users' table)
            // In a real app, you would execute an UPDATE or INSERT:
            // $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
            // $stmt->execute([$token, $expires, $email]);

            // 4. Send an email to the user with the reset link
            // The link would look like: 'http://yoursite.com/reset-password.php?token=' . $token . '&email=' . urlencode($email)
            // mail($email, 'Password Reset Link', 'Click the link to reset your password...');
            
            // NOTE: Even if the email doesn't exist, we usually show a generic success message 
            // to prevent revealing which emails are registered.
            $message = 'If an account with that email exists, a password reset link has been sent to your inbox.';
            $message_type = 'info';
            
        } else {
            // Generic message for security reasons
            $message = 'If an account with that email exists, a password reset link has been sent to your inbox.';
            $message_type = 'info';
        }
        
        // --- END: Placeholder for Password Reset Logic ---
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                        <a href="login.php" class="login-link">Login</a>
                        <a href="register.php" class="register-link">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="auth-section">
        <div class="container">
            <div class="auth-container" style="grid-template-columns: 1fr;"> <div class="auth-form form-container" style="max-width: 500px; margin: 0 auto;">
                    <h2>Forgot Your Password?</h2>
                    <p>Enter your email address and we'll send you a link to reset your password.</p>
                    
                    <?php if ($message): ?>
                        <div class="flash-message flash-<?php echo $message_type; ?>">
                            <i class="fas fa-<?php echo ($message_type === 'error' ? 'exclamation-circle' : 'info-circle'); ?>"></i>
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" data-validate>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-large">
                            <i class="fas fa-envelope"></i>
                            Send Reset Link
                        </button>
                    </form>
                    
                    <div class="auth-links">
                        <p>Remember your password? <a href="login.php">Log In</a></p>
                    </div>
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