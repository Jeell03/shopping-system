<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header('Location: ' . ($_SESSION['redirect_after_login'] ?? 'index.php'));
    unset($_SESSION['redirect_after_login']);
    exit();
}

$error = '';
$flash = getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        if (loginUser($username, $password)) {
            $redirectUrl = $_SESSION['redirect_after_login'] ?? 'index.php';
            unset($_SESSION['redirect_after_login']);
            header("Location: $redirectUrl");
            exit();
        } else {
            $error = 'Invalid username/email or password';
        }
    }
}

$pageTitle = 'Sign In - ShopEasy';
$activeNav = 'profile';

require_once 'includes/header.php';
?>

<main style="padding: 50px 0 70px; background: #f8fafc;">
    <div class="container">
        <div style="max-width: 440px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: var(--shadow-md); padding: 32px;">
            <div style="text-align: center; margin-bottom: 24px;">
                <div class="logo-icon-box" style="margin: 0 auto 12px; width: 48px; height: 48px; font-size: 22px;">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h2 style="font-family: 'Poppins', sans-serif; font-size: 22px; font-weight: 700; color: #0f172a;">Sign In to ShopEasy</h2>
                <p style="font-size: 13px; color: #64748b; margin-top: 4px;">Welcome back! Access your orders and wishlist</p>
            </div>

            <?php if ($flash): ?>
                <div style="background: #eff6ff; color: #1d4ed8; border: 1px solid #93c5fd; border-radius: 8px; padding: 10px 14px; font-size: 13px; margin-bottom: 16px;">
                    <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($flash['message']); ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div style="background: #fef2f2; color: #b91c1c; border: 1px solid #f87171; border-radius: 8px; padding: 10px 14px; font-size: 13px; margin-bottom: 16px;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Username or Email</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label style="font-size: 13px; font-weight: 600;">Password</label>
                        <a href="forget_password.php" style="font-size: 12px; color: #2563eb; font-weight: 500;">Forgot?</a>
                    </div>
                    <input type="password" name="password" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 8px;">
                    Sign In <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <!-- 1-Click Quick Demo Sign In Box -->
            <div style="margin-top: 24px; padding-top: 18px; border-top: 1px dashed #e2e8f0;">
                <span style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #94a3b8; margin-bottom: 8px; text-align: center;">1-Click Quick Test Accounts</span>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <button type="button" class="btn btn-outline btn-sm" onclick="document.querySelector('input[name=username]').value='admin'; document.querySelector('input[name=password]').value='password123';">
                        <i class="fas fa-user-shield"></i> Admin
                    </button>
                    <button type="button" class="btn btn-outline btn-sm" onclick="document.querySelector('input[name=username]').value='jeel'; document.querySelector('input[name=password]').value='password123';">
                        <i class="fas fa-user"></i> Customer
                    </button>
                </div>
            </div>

            <div style="text-align: center; margin-top: 20px; font-size: 13px; color: #64748b;">
                New to ShopEasy? <a href="register.php" style="color: #2563eb; font-weight: 700;">Create your account</a>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
