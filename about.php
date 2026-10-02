<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$pageTitle = 'About Us - Our Story, Mission & Vision - ShopEasy';
$activeNav = 'about';

require_once 'includes/header.php';
?>

<main class="about-page-wrapper" style="padding: 24px 0 60px;">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="catalog-breadcrumb" aria-label="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <span>About Us</span>
        </nav>

        <!-- Hero Mission Banner -->
        <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #2563eb 100%); border-radius: 20px; padding: 60px 40px; color: #ffffff; text-align: center; margin-bottom: 40px; position: relative; overflow: hidden;">
            <span style="background: rgba(255, 255, 255, 0.15); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 4px 14px; border-radius: 99px; display: inline-block; margin-bottom: 16px;">
                <i class="fas fa-gem"></i> Built with Passion & Integrity
            </span>
            <h1 style="font-family: 'Poppins', sans-serif; font-size: 36px; font-weight: 800; max-width: 800px; margin: 0 auto 16px; line-height: 1.25;">
                Reinventing the Online Shopping Experience for Everyday People
            </h1>
            <p style="font-size: 16px; opacity: 0.9; max-width: 680px; margin: 0 auto 28px; line-height: 1.6;">
                ShopEasy was founded with a singular ambition: eliminate unnecessary middlemen markups, ensure 100% genuine products, and deliver delightful shopping moments right to your doorstep.
            </p>
            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="products.php" class="btn btn-accent btn-lg"><i class="fas fa-shopping-bag"></i> Explore Our Catalog</a>
                <a href="contact.php" class="btn btn-outline btn-lg" style="color: #fff; border-color: rgba(255,255,255,0.4);"><i class="fas fa-envelope"></i> Contact Us</a>
            </div>
        </div>

        <!-- Key Stats Counter Strip -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 48px;">
            <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 24px; text-align: center; box-shadow: var(--shadow-xs);">
                <div style="font-size: 32px; font-weight: 800; color: #2563eb; margin-bottom: 4px;">50,000+</div>
                <div style="font-size: 13px; font-weight: 600; color: #475569;">Curated Products</div>
            </div>
            <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 24px; text-align: center; box-shadow: var(--shadow-xs);">
                <div style="font-size: 32px; font-weight: 800; color: #10b981; margin-bottom: 4px;">2 Million+</div>
                <div style="font-size: 13px; font-weight: 600; color: #475569;">Satisfied Shoppers</div>
            </div>
            <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 24px; text-align: center; box-shadow: var(--shadow-xs);">
                <div style="font-size: 32px; font-weight: 800; color: #ea580c; margin-bottom: 4px;">99.8%</div>
                <div style="font-size: 13px; font-weight: 600; color: #475569;">On-Time Delivery</div>
            </div>
            <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 24px; text-align: center; box-shadow: var(--shadow-xs);">
                <div style="font-size: 32px; font-weight: 800; color: #8b5cf6; margin-bottom: 4px;">24 / 7</div>
                <div style="font-size: 13px; font-weight: 600; color: #475569;">Expert Support</div>
            </div>
        </div>

        <!-- The Genesis Story -->
        <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 40px; margin-bottom: 40px; box-shadow: var(--shadow-sm);">
            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 40px; align-items: center;">
                <div>
                    <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: #2563eb; letter-spacing: 0.5px;">Our Journey</span>
                    <h2 style="font-family: 'Poppins', sans-serif; font-size: 26px; font-weight: 800; color: #0f172a; margin: 6px 0 16px;">
                        From Humble Beginnings to National Retail Benchmark
                    </h2>
                    <p style="font-size: 14px; color: #475569; line-height: 1.7; margin-bottom: 14px;">
                        It all started in 2020 with a simple observation: shoppers spent hours comparing prices across confusing websites with uncertain authenticity guarantees. We created ShopEasy as a curated platform where each product undergoes stringent quality verification before reaching catalog shelves.
                    </p>
                    <p style="font-size: 14px; color: #475569; line-height: 1.7; margin-bottom: 20px;">
                        Whether you are shopping for top-tier smartphones, durable denim, kitchen culinary appliances, or high-performance running shoes, ShopEasy guarantees direct brand sourcing, transparent pricing, and instant returns.
                    </p>
                    <div style="display: flex; gap: 14px;">
                        <a href="products.php?on_sale=1" class="btn btn-primary btn-sm"><i class="fas fa-fire"></i> Today's Deals</a>
                        <a href="shipping.php" class="btn btn-outline btn-sm"><i class="fas fa-truck"></i> Shipping Information</a>
                    </div>
                </div>

                <div style="background: #f8fafc; border-radius: 16px; padding: 28px; border: 1px solid #e2e8f0;">
                    <div style="display: flex; flex-direction: column; gap: 18px;">
                        <div style="display: flex; gap: 14px; align-items: center;">
                            <div style="width: 44px; height: 44px; border-radius: 10px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <h4 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0 0 2px;">100% Verified Sourcing</h4>
                                <span style="font-size: 12px; color: #64748b;">Direct manufacturer partnerships with brand warranties</span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 14px; align-items: center;">
                            <div style="width: 44px; height: 44px; border-radius: 10px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                            <div>
                                <h4 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0 0 2px;">No-Questions-Asked Returns</h4>
                                <span style="font-size: 12px; color: #64748b;">7-day doorstep pickup and immediate refunds</span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 14px; align-items: center;">
                            <div style="width: 44px; height: 44px; border-radius: 10px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div>
                                <h4 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0 0 2px;">256-Bit SSL Payment Protection</h4>
                                <span style="font-size: 12px; color: #64748b;">Encrypted checkout supporting Cards, UPI, and Cash on Delivery</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Core Values Grid -->
        <div style="margin-bottom: 20px;">
            <div style="text-align: center; margin-bottom: 28px;">
                <h2 style="font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">
                    Our Guiding Core Principles
                </h2>
                <p style="font-size: 13px; color: #64748b;">The pillars that drive every decision at ShopEasy</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
                <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 28px; text-align: center; box-shadow: var(--shadow-xs);">
                    <div style="width: 52px; height: 52px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 22px; margin: 0 auto 16px;">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Customer Centricity</h3>
                    <p style="font-size: 13px; color: #64748b; line-height: 1.6;">
                        Your satisfaction is our primary metric. From easy website navigation to express delivery, every detail is refined for you.
                    </p>
                </div>

                <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 28px; text-align: center; box-shadow: var(--shadow-xs);">
                    <div style="width: 52px; height: 52px; border-radius: 12px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 22px; margin: 0 auto 16px;">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Uncompromised Quality</h3>
                    <p style="font-size: 13px; color: #64748b; line-height: 1.6;">
                        We partner only with recognized brands and verified suppliers to guarantee authentic hardware and certified standards.
                    </p>
                </div>

                <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 28px; text-align: center; box-shadow: var(--shadow-xs);">
                    <div style="width: 52px; height: 52px; border-radius: 12px; background: #fdf2f8; color: #ec4899; display: flex; align-items: center; justify-content: center; font-size: 22px; margin: 0 auto 16px;">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Sustainable Logistics</h3>
                    <p style="font-size: 13px; color: #64748b; line-height: 1.6;">
                        We continuously optimize packaging waste using recyclable cardboard cartons and eco-friendly protective wrapping.
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>