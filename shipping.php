<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$pageTitle = 'Shipping & Delivery Policy - ShopEasy';
$activeNav = '';

require_once 'includes/header.php';
?>

<main class="policy-page-wrapper" style="padding: 24px 0 60px;">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="catalog-breadcrumb" aria-label="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <span>Shipping Policy</span>
        </nav>

        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 32px; align-items: start;">
            <!-- Left Sticky Policy Nav -->
            <aside style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: var(--shadow-xs); position: sticky; top: 90px;">
                <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                    Policy Index
                </h3>
                <ul style="display: flex; flex-direction: column; gap: 8px; font-size: 13px;">
                    <li><a href="shipping.php" style="color: #2563eb; font-weight: 700;"><i class="fas fa-truck" style="width: 20px;"></i> Shipping Policy</a></li>
                    <li><a href="returns.php" style="color: #64748b; font-weight: 500;"><i class="fas fa-sync-alt" style="width: 20px;"></i> Returns &amp; Refunds</a></li>
                    <li><a href="privacy.php" style="color: #64748b; font-weight: 500;"><i class="fas fa-shield-alt" style="width: 20px;"></i> Privacy Policy</a></li>
                    <li><a href="terms.php" style="color: #64748b; font-weight: 500;"><i class="fas fa-file-contract" style="width: 20px;"></i> Terms of Service</a></li>
                    <li><a href="faq.php" style="color: #64748b; font-weight: 500;"><i class="fas fa-question-circle" style="width: 20px;"></i> Help Center &amp; FAQ</a></li>
                </ul>

                <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #f1f5f9; text-align: center;">
                    <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 8px;">Need order assistance?</span>
                    <a href="contact.php" class="btn btn-outline btn-sm btn-block">Contact Support</a>
                </div>
            </aside>

            <!-- Right Policy Content -->
            <article style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 36px; box-shadow: var(--shadow-xs);">
                <span style="background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 700; text-transform: uppercase; padding: 3px 10px; border-radius: 99px;">
                    Logistics &amp; Fulfillment
                </span>
                <h1 style="font-family: 'Poppins', sans-serif; font-size: 26px; font-weight: 800; color: #0f172a; margin: 8px 0 16px;">
                    Shipping &amp; Delivery Policy
                </h1>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 28px;">Last updated: October 2026 &bull; Verified by Logistics Operations</p>

                <!-- Key Highlights Strip -->
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 32px;">
                    <div style="background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; padding: 16px; text-align: center;">
                        <i class="fas fa-box" style="color: #2563eb; font-size: 20px; margin-bottom: 6px;"></i>
                        <h4 style="font-size: 13px; font-weight: 700; color: #0f172a; margin: 0 0 2px;">Free Over $50</h4>
                        <span style="font-size: 11px; color: #64748b;">No code required at cart</span>
                    </div>
                    <div style="background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; padding: 16px; text-align: center;">
                        <i class="fas fa-bolt" style="color: #ea580c; font-size: 20px; margin-bottom: 6px;"></i>
                        <h4 style="font-size: 13px; font-weight: 700; color: #0f172a; margin: 0 0 2px;">1-2 Day Express</h4>
                        <span style="font-size: 11px; color: #64748b;">Priority dispatch for $9.99</span>
                    </div>
                    <div style="background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; padding: 16px; text-align: center;">
                        <i class="fas fa-shield-alt" style="color: #10b981; font-size: 20px; margin-bottom: 6px;"></i>
                        <h4 style="font-size: 13px; font-weight: 700; color: #0f172a; margin: 0 0 2px;">Insured Transit</h4>
                        <span style="font-size: 11px; color: #64748b;">Zero damage guarantee</span>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 24px; font-size: 14px; color: #334155; line-height: 1.7;">
                    <section>
                        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">1. Shipping Rates &amp; Delivery Speeds</h3>
                        <p>
                            ShopEasy partners with leading logistics networks including Blue Dart, Delhivery, FedEx, and UPS to guarantee fast, tracked shipments. Standard Delivery is completely <strong>FREE on all orders over $50</strong>. For carts below $50, a nominal $10.00 courier fee is added. Optional Express Priority Delivery is available at checkout for $9.99 with guaranteed 1-2 business day arrival.
                        </p>
                    </section>

                    <section>
                        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">2. Order Processing &amp; Dispatch Timeline</h3>
                        <p>
                            All orders placed before 3:00 PM IST on business days (Monday through Saturday) are verified, packed, and dispatched from regional fulfillment hubs the same day. Orders placed on Sundays or national holidays are dispatched the following morning.
                        </p>
                    </section>

                    <section>
                        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">3. Real-Time Tracking &amp; Notifications</h3>
                        <p>
                            As soon as your shipment departs our fulfillment warehouse, an SMS and email notification is sent with your carrier consignment number. You can monitor the package journey live inside your account under <a href="profile.php?tab=orders" style="color: #2563eb; font-weight: 600;">My Orders</a>.
                        </p>
                    </section>

                    <section>
                        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">4. Tamper-Proof Packaging</h3>
                        <p>
                            All electronics, fashion, and home appliances are sealed inside reinforced, moisture-resistant packaging with tamper-evident security tape. If the outer seal appears broken or compromised upon arrival, please refuse the delivery and contact our support desk immediately.
                        </p>
                    </section>
                </div>
            </article>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>