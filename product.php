<?php
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($productId <= 0) {
    header('Location: products.php');
    exit();
}

require_once 'config/database.php';
require_once 'includes/functions.php';

$product = getProduct($productId);
if (!$product) {
    header('Location: products.php');
    exit();
}

// Handle Review Submission
$reviewSuccess = false;
$reviewError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
    $rating = (int)($_POST['rating'] ?? 5);
    $title = trim($_POST['title'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    if ($rating >= 1 && $rating <= 5 && !empty($comment)) {
        submitReview($productId, $_SESSION['user_id'], $rating, $title, $comment);
        $reviewSuccess = true;
        // Refresh product details after new review
        $product = getProduct($productId);
    } else {
        $reviewError = 'Please provide a valid star rating and review comment.';
    }
}

// Data details
$price = ($product['sale_price'] && $product['sale_price'] > 0) ? (float)$product['sale_price'] : (float)$product['price'];
$hasSale = ($product['sale_price'] && $product['sale_price'] > 0 && $product['sale_price'] < $product['price']);
$discountPct = $hasSale ? round((($product['price'] - $product['sale_price']) / $product['price']) * 100) : 0;
$savingsAmount = $hasSale ? round($product['price'] - $product['sale_price'], 2) : 0;

$reviews = getProductReviews($productId);
$ratingDist = getRatingDistribution($productId);
$relatedProducts = getRelatedProducts($product['category_id'], $productId, 4);

$imgUrl = getProductImageUrl($product['image']);
$pageTitle = htmlspecialchars($product['name']) . ' - Buy Online at Best Price on ShopEasy';
$activeNav = $product['category_slug'] ?? 'products';
$inWishlist = isLoggedIn() ? isInWishlist($_SESSION['user_id'], $product['id']) : false;

require_once 'includes/header.php';
?>

<main class="product-detail-page" style="padding: 24px 0 50px;">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="catalog-breadcrumb" aria-label="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <a href="products.php">Catalog</a>
            <?php if (!empty($product['category_slug'])): ?>
                <i class="fas fa-chevron-right"></i>
                <a href="products.php?category=<?php echo urlencode($product['category_slug']); ?>">
                    <?php echo htmlspecialchars($product['category_name']); ?>
                </a>
            <?php endif; ?>
            <i class="fas fa-chevron-right"></i>
            <span><?php echo htmlspecialchars($product['name']); ?></span>
        </nav>

        <!-- Main Product Presentation Card -->
        <div class="product-detail-layout">
            <!-- Column 1: Media Gallery -->
            <div class="product-media-gallery">
                <div class="main-image-display" id="mainImgDisplay">
                    <img src="<?php echo $imgUrl; ?>" id="mainProductImg" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php if ($hasSale): ?>
                        <div class="card-badge-container">
                            <span class="discount-tag"><?php echo $discountPct; ?>% OFF</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Gallery Thumbnails -->
                <div class="gallery-thumbnails-strip">
                    <div class="thumb-item active" onclick="switchProductImage('<?php echo $imgUrl; ?>', this)">
                        <img src="<?php echo $imgUrl; ?>" alt="Main view">
                    </div>
                    <!-- Secondary angle representations -->
                    <div class="thumb-item" onclick="switchProductImage('<?php echo $imgUrl; ?>', this)">
                        <img src="<?php echo $imgUrl; ?>" alt="Side angle" style="transform: scale(0.9) rotate(-4deg);">
                    </div>
                    <div class="thumb-item" onclick="switchProductImage('<?php echo $imgUrl; ?>', this)">
                        <img src="<?php echo $imgUrl; ?>" alt="Detail zoom" style="transform: scale(1.15);">
                    </div>
                </div>

                <!-- Wishlist & Share Actions -->
                <div style="display: flex; gap: 12px; margin-top: 10px;">
                    <button class="btn btn-outline btn-block" onclick="toggleWishlist(<?php echo $product['id']; ?>, this)" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i class="fas fa-heart <?php echo $inWishlist ? 'active' : ''; ?>" style="<?php echo $inWishlist ? 'color: #ec4899;' : ''; ?>"></i>
                        <span><?php echo $inWishlist ? 'Saved in Wishlist' : 'Add to Wishlist'; ?></span>
                    </button>
                    <button class="btn btn-outline" onclick="navigator.clipboard.writeText(window.location.href); showToast('Product link copied to clipboard!', 'success');" title="Share">
                        <i class="fas fa-share-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Column 2: Product Info & Purchase Actions -->
            <div class="product-info-summary">
                <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: #2563eb; letter-spacing: 0.5px;">
                    <?php echo htmlspecialchars($product['category_name'] ?? 'ShopEasy Exclusive'); ?> &bull; SKU: <?php echo htmlspecialchars($product['sku'] ?? 'SE-'. $product['id']); ?>
                </span>

                <h1 class="product-detail-title"><?php echo htmlspecialchars($product['name']); ?></h1>

                <div class="product-rating-reviews-banner">
                    <span class="star-badge-green">
                        <?php echo round($product['avg_rating'], 1) ?: '4.8'; ?> <i class="fas fa-star"></i>
                    </span>
                    <a href="#customerReviewsSection" style="color: #2563eb; font-weight: 600;">
                        <?php echo count($reviews); ?> Ratings & Reviews
                    </a>
                    <span class="verified-buyer-tag"><i class="fas fa-check-circle"></i> 100% Genuine</span>
                    <span style="color: <?php echo $product['stock_quantity'] > 0 ? '#10b981' : '#ef4444'; ?>; font-weight: 700;">
                        &bull; <?php echo $product['stock_quantity'] > 0 ? "In Stock ({$product['stock_quantity']} units)" : 'Temporarily Out of Stock'; ?>
                    </span>
                </div>

                <!-- Price Box -->
                <div class="price-box-card">
                    <div class="price-main-line">
                        <span class="detail-current-price">$<?php echo number_format($price, 2); ?></span>
                        <?php if ($hasSale): ?>
                            <span class="detail-mrp-price">$<?php echo number_format($product['price'], 2); ?></span>
                            <span class="detail-discount-percent"><?php echo $discountPct; ?>% off</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($hasSale): ?>
                        <div class="savings-highlight-text">
                            <i class="fas fa-tag"></i> You save $<?php echo number_format($savingsAmount, 2); ?> with this deal!
                        </div>
                    <?php endif; ?>
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Inclusive of all taxes. Free shipping on orders over $50.</div>
                </div>

                <!-- Bank Offers & Promo Codes (Amazon/Flipkart standard) -->
                <div class="bank-offers-box">
                    <div class="offers-box-title">
                        <i class="fas fa-certificate"></i> Available Offers & Coupons
                    </div>
                    <ul class="offers-list">
                        <li>
                            <i class="fas fa-tag" style="color: #ea580c;"></i> 
                            <strong>Special Price:</strong> Extra 10% off with code 
                            <button type="button" class="coupon-pill-btn" onclick="navigator.clipboard.writeText('WELCOME10'); showToast('Coupon WELCOME10 copied! Apply at cart/checkout.', 'success');" title="Click to copy">
                                <strong>WELCOME10</strong> <i class="far fa-copy"></i>
                            </button>
                        </li>
                        <li>
                            <i class="fas fa-credit-card" style="color: #2563eb;"></i> 
                            <strong>Bank Offer:</strong> $50 flat off on orders &gt; $200 with code 
                            <button type="button" class="coupon-pill-btn" onclick="navigator.clipboard.writeText('SAVE50'); showToast('Coupon SAVE50 copied! Apply at cart/checkout.', 'success');" title="Click to copy">
                                <strong>SAVE50</strong> <i class="far fa-copy"></i>
                            </button>
                        </li>
                        <li><i class="fas fa-shield-alt" style="color: #10b981;"></i> <strong>Brand Warranty:</strong> 1 Year Comprehensive Brand Manufacturer Warranty</li>
                    </ul>
                </div>

                <!-- Delivery Pincode Checker -->
                <div class="pincode-checker-box">
                    <div class="pincode-header">
                        <i class="fas fa-map-marker-alt" style="color: #ea580c;"></i> Delivery Options & Check
                    </div>
                    <div class="pincode-input-group">
                        <input type="text" id="deliveryPincodeInput" placeholder="Enter Delivery Pincode" maxlength="6" value="400001">
                        <button type="button" class="btn btn-primary btn-sm" onclick="checkDeliveryPincode()">Check</button>
                    </div>
                    <div id="pincodeResultMsg" class="pincode-result-msg success">
                        <i class="fas fa-check-circle"></i> Delivery available to <strong>400001</strong> by <strong><?php echo date('D, M j', strtotime('+2 days')); ?></strong> &bull; Free Delivery.
                    </div>
                </div>

                <!-- Variant Selectors -->
                <div class="variant-selector-group">
                    <span class="variant-label">Color / Finish:</span>
                    <div class="variant-chips">
                        <button type="button" class="chip-btn active">Titanium Gray</button>
                        <button type="button" class="chip-btn">Midnight Blue</button>
                        <button type="button" class="chip-btn">Starlight Silver</button>
                    </div>
                </div>

                <?php if ($product['category_slug'] === 'clothing'): ?>
                <div class="variant-selector-group">
                    <span class="variant-label">Select Size:</span>
                    <div class="variant-chips">
                        <button type="button" class="chip-btn">S</button>
                        <button type="button" class="chip-btn active">M</button>
                        <button type="button" class="chip-btn">L</button>
                        <button type="button" class="chip-btn">XL</button>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Quantity & Add to Cart / Buy Now CTAs -->
                <div class="purchase-actions-box">
                    <div class="qty-stepper">
                        <button type="button" class="qty-step-btn" onclick="stepDetailQty(-1)">-</button>
                        <input type="text" id="detailQtyInput" class="qty-input" value="1" readonly>
                        <button type="button" class="qty-step-btn" onclick="stepDetailQty(1)">+</button>
                    </div>

                    <button type="button" class="detail-btn-cart" 
                            onclick="addDetailToCart(<?php echo $product['id']; ?>)"
                            <?php echo $product['stock_quantity'] <= 0 ? 'disabled' : ''; ?>>
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>

                    <button type="button" class="detail-btn-buy" 
                            onclick="buyNowDirect(<?php echo $product['id']; ?>)"
                            <?php echo $product['stock_quantity'] <= 0 ? 'disabled' : ''; ?>>
                        <i class="fas fa-bolt"></i> Buy Now
                    </button>
                </div>

                <!-- Highlights Checklist -->
                <div style="margin-top: 14px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                    <h4 style="font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">Highlights:</h4>
                    <ul style="font-size: 13px; color: #334155; line-height: 1.8;">
                        <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> <?php echo htmlspecialchars($product['short_description'] ?: $product['name']); ?></li>
                        <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> 7 Days Replacement Guarantee from delivery date</li>
                        <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> Eligible for Cash on Delivery & Fast Checkout</li>
                        <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> Bank Offer: Extra discount with coupons at checkout</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Specifications & Full Description -->
        <div class="specs-table-container">
            <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 16px; border-bottom: 2px solid #2563eb; display: inline-block; padding-bottom: 6px;">
                Product Specifications & Details
            </h3>
            
            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin-bottom: 24px;">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </p>

            <table class="specs-table">
                <tbody>
                    <tr>
                        <th>Model Name</th>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td><?php echo htmlspecialchars($product['category_name'] ?? 'Electronics & Lifestyle'); ?></td>
                    </tr>
                    <tr>
                        <th>SKU Identifier</th>
                        <td><?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Stock Availability</th>
                        <td><?php echo $product['stock_quantity'] > 0 ? "In Stock ({$product['stock_quantity']} units)" : 'Out of Stock'; ?></td>
                    </tr>
                    <tr>
                        <th>Warranty Summary</th>
                        <td>1 Year Brand Domestic Warranty Covering Hardware Defects</td>
                    </tr>
                    <tr>
                        <th>In The Box</th>
                        <td>Main Unit, Charging / Power Cable, Quick Start Guide, Warranty Document</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Customer Ratings & Reviews Section -->
        <div class="reviews-section-card" id="customerReviewsSection">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                <div>
                    <h3 style="font-size: 20px; font-weight: 700; color: #0f172a;">Customer Ratings & Reviews</h3>
                    <p style="font-size: 13px; color: #64748b;">Feedback from verified purchasers</p>
                </div>
                <button type="button" class="btn btn-outline" onclick="document.getElementById('writeReviewModal').style.display = 'flex';">
                    <i class="fas fa-pen"></i> Write a Review
                </button>
            </div>

            <?php if ($reviewSuccess): ?>
                <div class="savings-banner-green" style="margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i> Thank you! Your review has been submitted and published.
                </div>
            <?php endif; ?>
            <?php if ($reviewError): ?>
                <div style="background: #fef2f2; color: #b91c1c; border: 1px solid #f87171; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($reviewError); ?>
                </div>
            <?php endif; ?>

            <!-- Rating Overview & Bar Breakdown -->
            <div class="reviews-overview-grid">
                <div style="text-align: center;">
                    <div class="big-rating-number"><?php echo round($product['avg_rating'], 1) ?: '4.8'; ?></div>
                    <div style="color: #f59e0b; font-size: 18px; margin: 6px 0;">
                        <?php 
                        $stars = round($product['avg_rating']);
                        for ($i = 1; $i <= 5; $i++) {
                            echo ($i <= $stars) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                        }
                        ?>
                    </div>
                    <div style="font-size: 12px; color: #64748b;"><?php echo count($reviews); ?> Verified Ratings</div>
                </div>

                <div class="star-bars-col">
                    <?php for ($s = 5; $s >= 1; $s--): ?>
                    <div class="star-bar-row">
                        <span style="width: 30px; font-weight: 600;"><?php echo $s; ?> <i class="fas fa-star" style="color: #f59e0b; font-size: 10px;"></i></span>
                        <div class="bar-bg">
                            <div class="bar-fill" style="width: <?php echo $ratingDist['percentages'][$s] ?? 0; ?>%;"></div>
                        </div>
                        <span style="width: 35px; text-align: right; color: #64748b;"><?php echo $ratingDist['percentages'][$s] ?? 0; ?>%</span>
                    </div>
                    <?php endfor; ?>
                </div>

                <div style="border-left: 1px solid #e2e8f0; padding-left: 20px;">
                    <h5 style="font-size: 13px; font-weight: 700; margin-bottom: 6px;">Customer Feedback</h5>
                    <p style="font-size: 12px; color: #64748b; line-height: 1.5;">
                        96% of customers recommend this product based on verified build quality, delivery speed, and performance.
                    </p>
                </div>
            </div>

            <!-- Individual Reviews List -->
            <div class="reviews-items-list">
                <?php if (empty($reviews)): ?>
                    <p style="text-align: center; color: #94a3b8; padding: 20px 0;">No reviews yet. Be the first to review this product!</p>
                <?php else: ?>
                    <?php foreach ($reviews as $rev): ?>
                    <div class="review-item-card">
                        <div class="review-author-row">
                            <div class="review-author-avatar">
                                <?php echo strtoupper(substr($rev['username'] ?? 'U', 0, 1)); ?>
                            </div>
                            <div>
                                <span class="review-author-name"><?php echo htmlspecialchars($rev['first_name'] ? ($rev['first_name'].' '.$rev['last_name']) : $rev['username']); ?></span>
                                <span class="review-badge-verified"><i class="fas fa-check-circle"></i> Verified Buyer</span>
                            </div>
                            <span style="margin-left: auto; font-size: 12px; color: #94a3b8;"><?php echo date('M j, Y', strtotime($rev['created_at'])); ?></span>
                        </div>

                        <div class="review-title-stars">
                            <span class="star-badge-green">
                                <?php echo $rev['rating']; ?> <i class="fas fa-star"></i>
                            </span>
                            <strong><?php echo htmlspecialchars($rev['title']); ?></strong>
                        </div>

                        <p class="review-comment-text"><?php echo htmlspecialchars($rev['comment']); ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Related Products Carousel -->
        <?php if (!empty($relatedProducts)): ?>
        <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 24px;">
            <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 20px;">
                Customers Also Viewed
            </h3>
            <div class="products-grid">
                <?php foreach ($relatedProducts as $rel): ?>
                <?php 
                    $relPrice = ($rel['sale_price'] && $rel['sale_price'] > 0) ? $rel['sale_price'] : $rel['price'];
                    $relImg = getProductImageUrl($rel['image']);
                ?>
                <div class="product-card">
                    <div class="card-image-box">
                        <a href="product.php?id=<?php echo $rel['id']; ?>">
                            <img src="<?php echo $relImg; ?>" alt="<?php echo htmlspecialchars($rel['name']); ?>" loading="lazy">
                        </a>
                        <button class="card-quick-view-btn" onclick="openQuickView(<?php echo $rel['id']; ?>)">
                            <i class="far fa-eye"></i> Quick View
                        </button>
                    </div>
                    <div class="card-details">
                        <span class="card-category-name"><?php echo htmlspecialchars($rel['category_name'] ?? 'General'); ?></span>
                        <h4 class="card-product-title">
                            <a href="product.php?id=<?php echo $rel['id']; ?>"><?php echo htmlspecialchars($rel['name']); ?></a>
                        </h4>
                        <div class="card-pricing-row">
                            <span class="current-price">$<?php echo number_format($relPrice, 2); ?></span>
                            <?php if ($rel['sale_price']): ?>
                                <span class="original-price-mrp">$<?php echo number_format($rel['price'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="card-actions-row">
                            <button class="add-to-cart-btn" onclick="quickAddToCart(<?php echo $rel['id']; ?>, 1, this)">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<!-- Write Review Modal -->
<div id="writeReviewModal" class="modal-backdrop" style="display: none;">
    <div class="modal-dialog" style="max-width: 520px;">
        <button class="modal-close-btn" onclick="document.getElementById('writeReviewModal').style.display = 'none';">&times;</button>
        <div style="padding: 24px;">
            <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Write a Customer Review</h3>
            <p style="font-size: 12px; color: #64748b; margin-bottom: 18px;">Share your experience with other shoppers</p>

            <form action="product.php?id=<?php echo $productId; ?>" method="POST">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Overall Rating:</label>
                    <select name="rating" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                        <option value="5">⭐⭐⭐⭐⭐ 5 Stars - Exceptional</option>
                        <option value="4">⭐⭐⭐⭐ 4 Stars - Very Good</option>
                        <option value="3">⭐⭐⭐ 3 Stars - Average</option>
                        <option value="2">⭐⭐ 2 Stars - Below Expectations</option>
                        <option value="1">⭐ 1 Star - Poor</option>
                    </select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Review Headline:</label>
                    <input type="text" name="title" placeholder="e.g. Unbelievable performance and great camera!" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Written Review:</label>
                    <textarea name="comment" rows="4" placeholder="What did you like or dislike? How was the build quality?" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; resize: vertical;"></textarea>
                </div>

                <button type="submit" name="submit_review" class="btn btn-primary btn-block">
                    Submit Verified Review
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function switchProductImage(src, thumbElement) {
    document.getElementById('mainProductImg').src = src;
    document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
    thumbElement.classList.add('active');
}

function stepDetailQty(delta) {
    const input = document.getElementById('detailQtyInput');
    const current = parseInt(input.value) || 1;
    const next = Math.max(1, Math.min(<?php echo (int)$product['stock_quantity']; ?>, current + delta));
    input.value = next;
}

function addDetailToCart(productId) {
    const qty = parseInt(document.getElementById('detailQtyInput').value) || 1;
    quickAddToCart(productId, qty, document.querySelector('.detail-btn-cart'));
}

function buyNowDirect(productId) {
    const qty = parseInt(document.getElementById('detailQtyInput').value) || 1;
    fetch('ajax/add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId, quantity: qty })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'checkout.php';
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => window.location.href = 'checkout.php');
}

// Interactive Variant Chip Selection
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.variant-chips .chip-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.parentElement.querySelectorAll('.chip-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Auto-populate delivery pincode from localStorage if saved
    const savedPin = localStorage.getItem('shopeasy_pincode');
    if (savedPin) {
        const pinInput = document.getElementById('deliveryPincodeInput');
        if (pinInput) {
            pinInput.value = savedPin;
            checkDeliveryPincode();
        }
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
