<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');

    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        if (registerUser($username, $email, $password)) {
            // Update additional fields
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $newId = $stmt->fetchColumn();
            if ($newId && (!empty($firstName) || !empty($phone))) {
                $up = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ? WHERE id = ?");
                $up->execute([$firstName, $lastName, $phone, $newId]);
            }
            $success = 'Registration successful! You can now log in to start shopping.';
        } else {
            $error = 'Username or email already exists in our system.';
        }
    }
}

$pageTitle = 'Create Account - ShopEasy';
$activeNav = 'profile';

require_once 'includes/header.php';
?>

<main style="padding: 50px 0 70px; background: #f8fafc;">
    <div class="container">
        <div style="max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: var(--shadow-md); padding: 32px;">
            <div style="text-align: center; margin-bottom: 24px;">
                <div class="logo-icon-box" style="margin: 0 auto 12px; width: 48px; height: 48px; font-size: 22px;">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h2 style="font-family: 'Poppins', sans-serif; font-size: 22px; font-weight: 700; color: #0f172a;">Create Your ShopEasy Account</h2>
                <p style="font-size: 13px; color: #64748b; margin-top: 4px;">Join millions of shoppers and get 10% off your first order</p>
            </div>

            <?php if ($success): ?>
                <div style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 16px; font-size: 13px; margin-bottom: 20px; text-align: center;">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?><br>
                    <a href="login.php" class="btn btn-primary btn-sm" style="margin-top: 10px; display: inline-block;">Proceed to Sign In</a>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div style="background: #fef2f2; color: #b91c1c; border: 1px solid #f87171; border-radius: 8px; padding: 10px 14px; font-size: 13px; margin-bottom: 16px;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">First Name</label>
                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Last Name</label>
                        <input type="text" name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                    </div>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Username *</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Email Address *</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Mobile Number</label>
                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" placeholder="+91 9876543210" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 18px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Password *</label>
                        <input type="password" name="password" required minlength="6" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Confirm *</label>
                        <input type="password" name="confirm_password" required minlength="6" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    Create Account <i class="fas fa-user-plus"></i>
                </button>
            </form>

            <div style="text-align: center; margin-top: 20px; font-size: 13px; color: #64748b;">
                Already have an account? <a href="login.php" style="color: #2563eb; font-weight: 700;">Sign in here</a>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
