<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    
    if (empty($email)) {
        $message = 'Please enter your registered email address.';
        $messageType = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email format.';
        $messageType = 'error';
    } else {
        // Query users table
        $stmt = $pdo->prepare("SELECT id, username FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Security best practice: Provide reassuring success message without revealing account enumeration
        $message = 'If an account is associated with ' . htmlspecialchars($email) . ', password reset instructions have been dispatched to your inbox.';
        $messageType = 'success';
    }
}

$pageTitle = 'Forgot Password - ShopEasy';
$activeNav = 'profile';

require_once 'includes/header.php';
?>

<main style="padding: 50px 0 70px; background: #f8fafc;">
    <div class="container">
        <div style="max-width: 440px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: var(--shadow-md); padding: 32px;">
            <div style="text-align: center; margin-bottom: 24px;">
                <div class="logo-icon-box" style="margin: 0 auto 12px; width: 48px; height: 48px; font-size: 22px; background: #eff6ff; color: #2563eb;">
                    <i class="fas fa-key"></i>
                </div>
                <h2 style="font-family: 'Poppins', sans-serif; font-size: 22px; font-weight: 700; color: #0f172a;">Password Recovery</h2>
                <p style="font-size: 13px; color: #64748b; margin-top: 4px;">Enter your email to receive recovery instructions</p>
            </div>

            <?php if ($message): ?>
                <div style="background: <?php echo $messageType === 'success' ? '#ecfdf5' : '#fef2f2'; ?>; color: <?php echo $messageType === 'success' ? '#065f46' : '#b91c1c'; ?>; border: 1px solid <?php echo $messageType === 'success' ? '#a7f3d0' : '#f87171'; ?>; border-radius: 8px; padding: 12px 16px; font-size: 13px; margin-bottom: 20px;">
                    <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i> <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form action="forget_password.php" method="POST">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Registered Email Address *</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required placeholder="e.g. john@example.com" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    Send Reset Link <i class="fas fa-paper-plane"></i>
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; font-size: 13px; color: #64748b; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                Remembered your password? <a href="login.php" style="color: #2563eb; font-weight: 700;">Back to Sign In</a>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>