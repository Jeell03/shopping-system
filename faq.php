<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$pageTitle = 'Frequently Asked Questions (FAQ) - Help Center - ShopEasy';
$activeNav = '';

require_once 'includes/header.php';
?>

<main class="faq-page-wrapper" style="padding: 24px 0 60px;">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="catalog-breadcrumb" aria-label="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <span>Help Center &amp; FAQs</span>
        </nav>

        <div style="text-align: center; max-width: 680px; margin: 0 auto 36px;">
            <span style="background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 700; text-transform: uppercase; padding: 4px 12px; border-radius: 99px; display: inline-block; margin-bottom: 12px;">
                <i class="fas fa-question-circle"></i> Instant Help Desk
            </span>
            <h1 style="font-family: 'Poppins', sans-serif; font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">
                Frequently Asked Questions
            </h1>
            <p style="font-size: 14px; color: #64748b; line-height: 1.6;">
                Quick answers regarding tracking shipments, coupon redemption, return requests, and payment options.
            </p>
        </div>

        <div style="max-width: 820px; margin: 0 auto;">
            <!-- Category Group 1: Orders & Tracking -->
            <div style="margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-box-open" style="color: #2563eb;"></i> Ordering &amp; Delivery Tracking
                </h2>

                <div class="faq-accordion-group" style="display: flex; flex-direction: column; gap: 12px;">
                    <details class="faq-item" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 22px; cursor: pointer;">
                        <summary style="font-size: 15px; font-weight: 700; color: #0f172a; outline: none; list-style: none; display: flex; justify-content: space-between; align-items: center;">
                            <span>How do I track my active order?</span>
                            <i class="fas fa-chevron-down" style="color: #64748b; font-size: 12px;"></i>
                        </summary>
                        <p style="font-size: 13px; color: #475569; line-height: 1.7; margin-top: 12px; border-top: 1px solid #f1f5f9; padding-top: 12px;">
                            You can easily track your parcel live anytime by visiting your <a href="profile.php?tab=orders" style="color: #2563eb; font-weight: 600;">My Orders</a> page. Click "View Invoice" to inspect real-time progression: Order Placed &rarr; Processing &rarr; Shipped &rarr; Delivered.
                        </p>
                    </details>

                    <details class="faq-item" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 22px; cursor: pointer;">
                        <summary style="font-size: 15px; font-weight: 700; color: #0f172a; outline: none; list-style: none; display: flex; justify-content: space-between; align-items: center;">
                            <span>Can I cancel or alter an order after placing it?</span>
                            <i class="fas fa-chevron-down" style="color: #64748b; font-size: 12px;"></i>
                        </summary>
                        <p style="font-size: 13px; color: #475569; line-height: 1.7; margin-top: 12px; border-top: 1px solid #f1f5f9; padding-top: 12px;">
                            Yes! If your order is in <em>Pending</em> or <em>Processing</em> status, you can cancel it with 1 click directly from the <a href="profile.php?tab=orders" style="color: #2563eb; font-weight: 600;">Orders page</a>. If the item has already shipped, simply refuse delivery or request an easy 7-day return.
                        </p>
                    </details>

                    <details class="faq-item" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 22px; cursor: pointer;">
                        <summary style="font-size: 15px; font-weight: 700; color: #0f172a; outline: none; list-style: none; display: flex; justify-content: space-between; align-items: center;">
                            <span>How does Free Shipping work?</span>
                            <i class="fas fa-chevron-down" style="color: #64748b; font-size: 12px;"></i>
                        </summary>
                        <p style="font-size: 13px; color: #475569; line-height: 1.7; margin-top: 12px; border-top: 1px solid #f1f5f9; padding-top: 12px;">
                            Orders with a cart value of <strong>$50.00 or higher</strong> qualify automatically for 100% Free Standard Delivery. For orders below $50, standard shipping is just $10.00, or you can select Express Priority Courier for $9.99 at checkout.
                        </p>
                    </details>
                </div>
            </div>

            <!-- Category Group 2: Payments & Promo Codes -->
            <div style="margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-credit-card" style="color: #10b981;"></i> Payments &amp; Coupons
                </h2>

                <div class="faq-accordion-group" style="display: flex; flex-direction: column; gap: 12px;">
                    <details class="faq-item" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 22px; cursor: pointer;">
                        <summary style="font-size: 15px; font-weight: 700; color: #0f172a; outline: none; list-style: none; display: flex; justify-content: space-between; align-items: center;">
                            <span>What payment methods are supported?</span>
                            <i class="fas fa-chevron-down" style="color: #64748b; font-size: 12px;"></i>
                        </summary>
                        <p style="font-size: 13px; color: #475569; line-height: 1.7; margin-top: 12px; border-top: 1px solid #f1f5f9; padding-top: 12px;">
                            We accept Credit/Debit Cards (Visa, MasterCard, RuPay, Amex), instant UPI (Google Pay, PhonePe, Paytm), and Cash on Delivery (COD). All card transactions are 256-bit SSL encrypted.
                        </p>
                    </details>

                    <details class="faq-item" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 22px; cursor: pointer;">
                        <summary style="font-size: 15px; font-weight: 700; color: #0f172a; outline: none; list-style: none; display: flex; justify-content: space-between; align-items: center;">
                            <span>How do I apply promotional discount codes?</span>
                            <i class="fas fa-chevron-down" style="color: #64748b; font-size: 12px;"></i>
                        </summary>
                        <p style="font-size: 13px; color: #475569; line-height: 1.7; margin-top: 12px; border-top: 1px solid #f1f5f9; padding-top: 12px;">
                            You can apply coupon codes directly in your <a href="cart.php" style="color: #2563eb; font-weight: 600;">Shopping Cart</a>. Enter codes like <strong>WELCOME10</strong> (10% off) or <strong>SAVE50</strong> ($50 off over $200) and click Apply to enjoy immediate bill deductions.
                        </p>
                    </details>
                </div>
            </div>

            <!-- Category Group 3: Returns & Refunds -->
            <div style="margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-sync-alt" style="color: #ea580c;"></i> Returns &amp; Warranty
                </h2>

                <div class="faq-accordion-group" style="display: flex; flex-direction: column; gap: 12px;">
                    <details class="faq-item" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 22px; cursor: pointer;">
                        <summary style="font-size: 15px; font-weight: 700; color: #0f172a; outline: none; list-style: none; display: flex; justify-content: space-between; align-items: center;">
                            <span>What is your return &amp; refund policy?</span>
                            <i class="fas fa-chevron-down" style="color: #64748b; font-size: 12px;"></i>
                        </summary>
                        <p style="font-size: 13px; color: #475569; line-height: 1.7; margin-top: 12px; border-top: 1px solid #f1f5f9; padding-top: 12px;">
                            We provide a <strong>7-Day Easy Return &amp; Replacement Policy</strong> from the delivery timestamp. If the product is defective, damaged, or doesn't meet expectations, our courier partner will pick it up at no cost to you. For full details, see our <a href="returns.php" style="color: #2563eb; font-weight: 600;">Returns Policy</a>.
                        </p>
                    </details>

                    <details class="faq-item" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 22px; cursor: pointer;">
                        <summary style="font-size: 15px; font-weight: 700; color: #0f172a; outline: none; list-style: none; display: flex; justify-content: space-between; align-items: center;">
                            <span>How long does it take to receive a refund?</span>
                            <i class="fas fa-chevron-down" style="color: #64748b; font-size: 12px;"></i>
                        </summary>
                        <p style="font-size: 13px; color: #475569; line-height: 1.7; margin-top: 12px; border-top: 1px solid #f1f5f9; padding-top: 12px;">
                            UPI refunds are credited instantly within 24 hours of package pickup. For credit and debit card transactions, banks typically reflect the refund within 3 to 5 business days.
                        </p>
                    </details>
                </div>
            </div>

            <!-- Still Need Help Box -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 24px; text-align: center; margin-top: 36px;">
                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Still have questions?</h3>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 16px;">Our team is available 24/7 to resolve any issue promptly.</p>
                <a href="contact.php" class="btn btn-primary btn-sm"><i class="fas fa-headset"></i> Contact Customer Support</a>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>