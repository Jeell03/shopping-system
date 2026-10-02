<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$pageTitle = 'Returns, Refunds & Replacement Policy - ShopEasy';
$activeNav = '';

require_once 'includes/header.php';
?>

<main class="policy-page-wrapper" style="padding: 24px 0 60px;">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="catalog-breadcrumb" aria-label="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <span>Returns &amp; Refunds</span>
        </nav>

        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 32px; align-items: start;">
            <!-- Left Sticky Policy Nav -->
            <aside style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: var(--shadow-xs); position: sticky; top: 90px;">
                <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                    Policy Index
                </h3>
                <ul style="display: flex; flex-direction: column; gap: 8px; font-size: 13px;">
                    <li><a href="shipping.php" style="color: #64748b; font-weight: 500;"><i class="fas fa-truck" style="width: 20px;"></i> Shipping Policy</a></li>
                    <li><a href="returns.php" style="color: #2563eb; font-weight: 700;"><i class="fas fa-sync-alt" style="width: 20px;"></i> Returns &amp; Refunds</a></li>
                    <li><a href="privacy.php" style="color: #64748b; font-weight: 500;"><i class="fas fa-shield-alt" style="width: 20px;"></i> Privacy Policy</a></li>
                    <li><a href="terms.php" style="color: #64748b; font-weight: 500;"><i class="fas fa-file-contract" style="width: 20px;"></i> Terms of Service</a></li>
                    <li><a href="faq.php" style="color: #64748b; font-weight: 500;"><i class="fas fa-question-circle" style="width: 20px;"></i> Help Center &amp; FAQ</a></li>
                </ul>

                <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #f1f5f9; text-align: center;">
                    <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 8px;">Have an item to return?</span>
                    <a href="contact.php" class="btn btn-primary btn-sm btn-block">Start Return Request</a>
                </div>
            </aside>

            <!-- Right Policy Content -->
            <article style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 36px; box-shadow: var(--shadow-xs);">
                <span style="background: #ecfdf5; color: #065f46; font-size: 11px; font-weight: 700; text-transform: uppercase; padding: 3px 10px; border-radius: 99px;">
                    Buyer Protection Guarantee
                </span>
                <h1 style="font-family: 'Poppins', sans-serif; font-size: 26px; font-weight: 800; color: #0f172a; margin: 8px 0 16px;">
                    7-Day Easy Returns &amp; Instant Refunds Policy
                </h1>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 28px;">Last updated: October 2026 &bull; Fair &amp; Transparent Customer Protection</p>

                <!-- Process Steps Strip -->
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 32px;">
                    <div style="background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; padding: 18px; text-align: center;">
                        <span style="display: inline-block; width: 28px; height: 28px; border-radius: 50%; background: #2563eb; color: #fff; font-size: 13px; font-weight: 800; line-height: 28px; margin-bottom: 8px;">1</span>
                        <h4 style="font-size: 13px; font-weight: 700; color: #0f172a; margin: 0 0 4px;">Request Return</h4>
                        <span style="font-size: 11px; color: #64748b;">Within 7 days of package delivery</span>
                    </div>
                    <div style="background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; padding: 18px; text-align: center;">
                        <span style="display: inline-block; width: 28px; height: 28px; border-radius: 50%; background: #10b981; color: #fff; font-size: 13px; font-weight: 800; line-height: 28px; margin-bottom: 8px;">2</span>
                        <h4 style="font-size: 13px; font-weight: 700; color: #0f172a; margin: 0 0 4px;">Free Doorstep Pickup</h4>
                        <span style="font-size: 11px; color: #64748b;">Our courier collects from your address</span>
                    </div>
                    <div style="background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; padding: 18px; text-align: center;">
                        <span style="display: inline-block; width: 28px; height: 28px; border-radius: 50%; background: #8b5cf6; color: #fff; font-size: 13px; font-weight: 800; line-height: 28px; margin-bottom: 8px;">3</span>
                        <h4 style="font-size: 13px; font-weight: 700; color: #0f172a; margin: 0 0 4px;">Instant Refund</h4>
                        <span style="font-size: 11px; color: #64748b;">Credited to source payment or UPI</span>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 24px; font-size: 14px; color: #334155; line-height: 1.7;">
                    <section>
                        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">1. 7-Day Hassle-Free Returns</h3>
                        <p>
                            At ShopEasy, we want you to be 100% delighted with every purchase. If your item arrives damaged, defective, differs from the website description, or if the size doesn't fit, you can request an exchange or full refund within <strong>7 days of delivery</strong>.
                        </p>
                    </section>

                    <section>
                        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">2. Return Eligibility Requirements</h3>
                        <p>To qualify for a seamless refund or replacement, please ensure:</p>
                        <ul style="padding-left: 20px; margin-top: 6px;">
                            <li>The product is in its original, unused condition with all brand tags and labels attached.</li>
                            <li>The original manufacturer box, accessories, charger, and warranty cards are included.</li>
                            <li>Electronic devices have been logged out and restored to factory defaults.</li>
                        </ul>
                    </section>

                    <section>
                        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">3. Refund Processing Timelines</h3>
                        <p>
                            Once our pickup executive collects the package and confirms item verification, your refund is initiated immediately:
                        </p>
                        <ul style="padding-left: 20px; margin-top: 6px;">
                            <li><strong>UPI &amp; Digital Wallets:</strong> Credited within 24 hours.</li>
                            <li><strong>Credit &amp; Debit Cards:</strong> 3 to 5 business days, subject to your card issuer's billing cycle.</li>
                            <li><strong>Cash on Delivery (COD):</strong> Transferred via instant bank NEFT or UPI to your provided account details.</li>
                        </ul>
                    </section>
                </div>
            </article>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>