<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$pageTitle = 'Terms & Conditions of Service - ShopEasy';
$activeNav = '';

require_once 'includes/header.php';
?>

<main class="policy-page-wrapper" style="padding: 24px 0 60px;">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="catalog-breadcrumb" aria-label="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <span>Terms of Service</span>
        </nav>

        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 32px; align-items: start;">
            <!-- Left Sticky Policy Nav -->
            <aside style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: var(--shadow-xs); position: sticky; top: 90px;">
                <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                    Policy Index
                </h3>
                <ul style="display: flex; flex-direction: column; gap: 8px; font-size: 13px;">
                    <li><a href="shipping.php" style="color: #64748b; font-weight: 500;"><i class="fas fa-truck" style="width: 20px;"></i> Shipping Policy</a></li>
                    <li><a href="returns.php" style="color: #64748b; font-weight: 500;"><i class="fas fa-sync-alt" style="width: 20px;"></i> Returns &amp; Refunds</a></li>
                    <li><a href="privacy.php" style="color: #64748b; font-weight: 500;"><i class="fas fa-shield-alt" style="width: 20px;"></i> Privacy Policy</a></li>
                    <li><a href="terms.php" style="color: #2563eb; font-weight: 700;"><i class="fas fa-file-contract" style="width: 20px;"></i> Terms of Service</a></li>
                    <li><a href="faq.php" style="color: #64748b; font-weight: 500;"><i class="fas fa-question-circle" style="width: 20px;"></i> Help Center &amp; FAQ</a></li>
                </ul>
            </aside>

            <!-- Right Policy Content -->
            <article style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 36px; box-shadow: var(--shadow-xs);">
                <span style="background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 700; text-transform: uppercase; padding: 3px 10px; border-radius: 99px;">
                    Customer Agreement
                </span>
                <h1 style="font-family: 'Poppins', sans-serif; font-size: 26px; font-weight: 800; color: #0f172a; margin: 8px 0 16px;">
                    Terms &amp; Conditions of Service
                </h1>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 28px;">Last updated: October 2026 &bull; Fair Commercial Standards</p>

                <div style="display: flex; flex-direction: column; gap: 24px; font-size: 14px; color: #334155; line-height: 1.7;">
                    <section>
                        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">1. User Agreement &amp; Account Security</h3>
                        <p>
                            By registering, accessing, or placing orders through ShopEasy, you agree to these Terms. You are responsible for safeguarding your login credentials and maintaining the confidentiality of your account password.
                        </p>
                    </section>

                    <section>
                        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">2. Pricing, Stock &amp; Order Acceptance</h3>
                        <p>
                            All product prices are quoted in USD ($) and include applicable taxes. We strive for absolute pricing accuracy; in the rare event of a typographical pricing error, we reserve the right to cancel affected orders and issue an immediate 100% refund.
                        </p>
                    </section>

                    <section>
                        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">3. Coupons &amp; Promotional Codes</h3>
                        <p>
                            Coupons such as WELCOME10 and SAVE50 are subject to their specified minimum order requirements, valid date windows, and single-use guidelines. ShopEasy reserves the right to terminate promotional offers at its reasonable discretion.
                        </p>
                    </section>

                    <section>
                        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">4. Limitation of Liability</h3>
                        <p>
                            ShopEasy shall not be liable for incidental, indirect, or consequential damages resulting from the temporary unavailability of site services or delayed third-party courier delivery times beyond our direct operational control.
                        </p>
                    </section>
                </div>
            </article>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>