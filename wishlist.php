<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

requireLogin();

$userId = $_SESSION['user_id'];
$wishlistItems = getWishlistItems($userId);

$pageTitle = 'My Wishlist (' . count($wishlistItems) . ' items) - ShopEasy';
$activeNav = 'wishlist';

require_once 'includes/header.php';
?>

<main class="wishlist-page-container" style="padding: 24px 0 60px;">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="catalog-breadcrumb" aria-label="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <span>My Wishlist</span>
        </nav>

        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
            <h1 style="font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 700; color: #0f172a;">
                My Wishlist <span style="font-size: 16px; color: #64748b; font-weight: 500;">(<?php echo count($wishlistItems); ?> items)</span>
            </h1>
            <?php if (!empty($wishlistItems)): ?>
                <button type="button" class="btn btn-outline btn-sm" onclick="moveAllWishlistToCart()">
                    <i class="fas fa-shopping-cart"></i> Move All to Cart
                </button>
            <?php endif; ?>
        </div>

        <?php if (empty($wishlistItems)): ?>
            <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 60px 20px; text-align: center; max-width: 600px; margin: 30px auto;">
                <div style="width: 72px; height: 72px; border-radius: 50%; background: #fdf2f8; color: #ec4899; font-size: 32px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="far fa-heart"></i>
                </div>
                <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Your Wishlist is Empty</h3>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">Save products you love so you can easily purchase them later.</p>
                <a href="products.php" class="btn btn-primary"><i class="fas fa-th-large"></i> Explore Products</a>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($wishlistItems as $item): ?>
                <?php 
                    $product = isset($item['product']) && is_array($item['product']) ? $item['product'] : $item;
                    $price = ($product['sale_price'] && $product['sale_price'] > 0) ? $product['sale_price'] : $product['price'];
                    $hasSale = ($product['sale_price'] && $product['sale_price'] > 0 && $product['sale_price'] < $product['price']);
                    $discountPct = $hasSale ? round((($product['price'] - $product['sale_price']) / $product['price']) * 100) : 0;
                    $imgUrl = getProductImageUrl($product['image']);
                ?>
                <div class="product-card" id="wishlistCard_<?php echo $product['id']; ?>">
                    <button class="card-wishlist-btn active" onclick="removeFromWishlistPage(<?php echo $product['id']; ?>)" title="Remove from Wishlist">
                        <i class="fas fa-times"></i>
                    </button>

                    <div class="card-image-box">
                        <a href="product.php?id=<?php echo $product['id']; ?>">
                            <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
                        </a>
                        <?php if ($hasSale): ?>
                            <div class="card-badge-container">
                                <span class="discount-tag"><?php echo $discountPct; ?>% OFF</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-details">
                        <span class="card-category-name"><?php echo htmlspecialchars($product['category_name'] ?? 'General'); ?></span>
                        <h3 class="card-product-title">
                            <a href="product.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                        </h3>

                        <div class="card-pricing-row">
                            <span class="current-price">$<?php echo number_format($price, 2); ?></span>
                            <?php if ($hasSale): ?>
                                <span class="original-price-mrp">$<?php echo number_format($product['price'], 2); ?></span>
                            <?php endif; ?>
                        </div>

                        <div style="font-size: 11px; color: <?php echo $product['stock_quantity'] > 0 ? '#10b981' : '#ef4444'; ?>; font-weight: 700; margin-bottom: 12px;">
                            <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                        </div>

                        <div class="card-actions-row">
                            <button class="add-to-cart-btn" onclick="moveToCart(<?php echo $product['id']; ?>, this)" <?php echo $product['stock_quantity'] <= 0 ? 'disabled' : ''; ?>>
                                <i class="fas fa-shopping-cart"></i> Move to Cart
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
function removeFromWishlistPage(productId) {
    fetch('ajax/remove_from_wishlist.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            updateHeaderBadges(null, data.wishlist_count);
            const card = document.getElementById(`wishlistCard_${productId}`);
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.9)';
                setTimeout(() => window.location.reload(), 300);
            }
        }
    });
}

function moveToCart(productId, btn) {
    quickAddToCart(productId, 1, btn);
    setTimeout(() => removeFromWishlistPage(productId), 500);
}

function moveAllWishlistToCart() {
    const cards = document.querySelectorAll('.product-card');
    cards.forEach(c => {
        const id = c.id.replace('wishlistCard_', '');
        quickAddToCart(id, 1);
    });
    setTimeout(() => window.location.href = 'cart.php', 1000);
}
</script>

<?php require_once 'includes/footer.php'; ?>
