<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$success = '';
$error = '';
$name = '';
$email = '';
$subject = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$name, $email, $subject, $message]);
            $success = 'Thank you for your message! Our customer support team will get back to you within 24 hours.';
            $name = $email = $subject = $message = '';
        } catch (Exception $e) {
            $error = 'Sorry, there was an issue sending your message. Please try again.';
        }
    }
}

$pageTitle = 'Contact Us - 24/7 Customer Support - ShopEasy';
$activeNav = 'contact';

require_once 'includes/header.php';
?>

<main class="contact-page-wrapper" style="padding: 24px 0 60px;">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="catalog-breadcrumb" aria-label="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <span>Contact Us</span>
        </nav>

        <div style="text-align: center; max-width: 650px; margin: 0 auto 36px;">
            <h1 style="font-family: 'Poppins', sans-serif; font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">
                We're Here to Help You
            </h1>
            <p style="font-size: 14px; color: #64748b; line-height: 1.6;">
                Have questions regarding your order, tracking shipments, warranty, or returns? Reach out to our dedicated 24/7 support specialists.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 32px; align-items: start;">
            <!-- Left Column: Support Channels & Details -->
            <div>
                <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 28px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
                    <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 20px;">
                        Customer Care Channels
                    </h2>

                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        <div style="display: flex; gap: 16px; align-items: flex-start;">
                            <div style="width: 44px; height: 44px; border-radius: 10px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 2px;">Phone Support</h3>
                                <p style="font-size: 13px; color: #475569; margin-bottom: 2px;">+91 81417 16722 / 1800-200-EASY</p>
                                <span style="font-size: 11px; color: #10b981; font-weight: 600;">Mon - Sat, 9:00 AM - 8:00 PM IST</span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 16px; align-items: flex-start;">
                            <div style="width: 44px; height: 44px; border-radius: 10px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 2px;">Email Support</h3>
                                <p style="font-size: 13px; color: #475569; margin-bottom: 2px;">support@shopeasy.com</p>
                                <span style="font-size: 11px; color: #64748b;">Average response time: Within 2 hours</span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 16px; align-items: flex-start;">
                            <div style="width: 44px; height: 44px; border-radius: 10px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 2px;">Corporate Headquarters</h3>
                                <p style="font-size: 13px; color: #475569; line-height: 1.5;">
                                    ShopEasy Commerce Pvt. Ltd.<br>
                                    Level 4, Express Towers, Bandra Kurla Complex<br>
                                    Mumbai, Maharashtra 400051, India
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Self-Serve Help Box -->
                <div style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); border-radius: 16px; padding: 24px; color: #ffffff;">
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 6px;">Need Instant Answers?</h3>
                    <p style="font-size: 12px; opacity: 0.9; line-height: 1.5; margin-bottom: 16px;">
                        Check our comprehensive FAQ knowledge base for quick steps on order tracking, return requests, and promo codes.
                    </p>
                    <a href="faq.php" class="btn btn-accent btn-sm" style="display: inline-block;">
                        Browse FAQs <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Right Column: Interactive Send Message Form -->
            <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 32px; box-shadow: var(--shadow-sm);">
                <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Send us a Direct Message</h2>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 24px;">Fill out the form below and we'll reply to your email address promptly.</p>

                <?php if ($success): ?>
                    <div style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check-circle" style="font-size: 18px;"></i>
                        <span><?php echo htmlspecialchars($success); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div style="background: #fef2f2; color: #b91c1c; border: 1px solid #f87171; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-exclamation-circle" style="font-size: 18px;"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <form action="contact.php" method="POST">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Your Name *</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($name ?: ($_SESSION['username'] ?? '')); ?>" required placeholder="John Doe" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Email Address *</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($email ?: ($_SESSION['email'] ?? '')); ?>" required placeholder="john@example.com" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Subject *</label>
                        <input type="text" name="subject" value="<?php echo htmlspecialchars($subject); ?>" required placeholder="e.g. Order Tracking Inquiry #ORD-..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                    </div>

                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Your Message *</label>
                        <textarea name="message" rows="5" required placeholder="Describe your question or issue in detail..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; resize: vertical;"><?php echo htmlspecialchars($message); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
