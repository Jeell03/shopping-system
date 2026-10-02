<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$pageTitle = 'Privacy Policy - ShopEasy';
$activeNav = '';

require_once 'includes/header.php';
?>

<main class="policy-page-wrapper" style="padding: 24px 0 60px;">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="catalog-breadcrumb" aria-label="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <span>Privacy Policy</span>
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
                    <li><a href="privacy.php" style="color: #2563eb; font-weight: 700;"><i class="fas fa-shield-alt" style="width: 20px;"></i> Privacy Policy</a></li>
                    <li><a href="terms.php" style="color: #64748b; font-weight: 500;"><i class="fas fa-file-contract" style="width: 20px;"></i> Terms of Service</a></li>
                    <li><a href="faq.php" style="color: #64748b; font-weight: 500;"><i class="fas fa-question-circle" style="width: 20px;"></i> Help Center &amp; FAQ</a></li>
                </ul>
            </aside>

            <!-- Right Policy Content -->
            <article style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 36px; box-shadow: var(--shadow-xs);">
                <span style="background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 700; text-transform: uppercase; padding: 3px 10px; border-radius: 99px;">
                    Data Protection &amp; Security
                </span>
                <h1 style="font-family: 'Poppins', sans-serif; font-size: 26px; font-weight: 800; color: #0f172a; margin: 8px 0 16px;">
                    ShopEasy Privacy Notice
                </h1>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 28px;">Last updated: October 2026 &bull; Strict GDPR &amp; IT Act Compliance</p>

                <div style="display: flex; flex-direction: column; gap: 24px; font-size: 14px; color: #334155; line-height: 1.7;">
                    <section>
                        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">1. Information We Collect</h3>
                        <p>
                            When you register an account, browse products, or place an order on ShopEasy, we collect the necessary personal data to deliver your packages, process payments, and personalize deals:
                        </p>
                        <ul style="padding-left: 20px; margin-top: 6px;">
                            <li><strong>Contact Details:</strong> Your name, delivery address, email, and contact telephone number.</li>
                            <li><strong>Order History:</strong> Products purchased, payment method selected, and invoice totals.</li>
                            <li><strong>Device Information:</strong> Browser type, IP address, and cookie identifiers to maintain cart state.</li>
                        </ul>
                    </section>

                    <section>
                        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">2. How We Protect Your Financial Information</h3>
                        <p>
                            ShopEasy does <strong>NOT store raw credit or debit card numbers</strong> on its servers. All payments are securely tokenized and handled through RBI-licensed payment gateways featuring end-to-end 256-bit SSL encryption.
                        </p>
                    </section>

                    <section>
                        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">3. No Third-Party Data Selling</h3>
                        <p>
                            We never sell, rent, or trade your personal data to external marketing companies. Information is shared strictly with necessary fulfillment partners (e.g. courier drivers) exclusively for completing deliveries.
                        </p>
                    </section>

                    <section>
                        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">4. Your Privacy Rights</h3>
                        <p>
                            You have the full right to review, update, or request deletion of your account records at any time. Simply manage your data under your <a href="profile.php" style="color: #2563eb; font-weight: 600;">Account Profile</a> or reach out to our privacy officer via <a href="contact.php" style="color: #2563eb; font-weight: 600;">Contact Support</a>.
                        </p>
                    </section>
                </div>
            </article>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>