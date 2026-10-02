<?php
$category = isset($_GET['category']) ? trim($_GET['category']) : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : (isset($_GET['query']) ? trim($_GET['query']) : null);
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'featured';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$minPrice = isset($_GET['min_price']) && is_numeric($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$maxPrice = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (float)$_GET['max_price'] : 0;
$rating = isset($_GET['rating']) && is_numeric($_GET['rating']) ? (float)$_GET['rating'] : 0;
$inStock = !empty($_GET['in_stock']);
$onSale = !empty($_GET['on_sale']);

$limit = 12;
$offset = ($page - 1) * $limit;

$filterParams = [
    'category'   => $category,
    'search'     => $search,
    'min_price'  => $minPrice,
    'max_price'  => $maxPrice,
    'rating'     => $rating,
    'in_stock'   => $inStock,
    'on_sale'    => $onSale,
    'sort'       => $sort,
    'limit'      => $limit,
    'offset'     => $offset
];

require_once 'config/database.php';
require_once 'includes/functions.php';

$products = getFilteredProductsAdvanced($filterParams);
$totalProducts = getFilteredProductsAdvancedCount($filterParams);
$totalPages = ceil($totalProducts / $limit);
$categories = getCategories();

$pageTitle = $search 
    ? "Search results for \"$search\" - ShopEasy" 
    : ($category ? ucfirst($category) . " Products - ShopEasy" : "All Products - ShopEasy");

$activeNav = $category ?: 'products';

require_once 'includes/header.php';
?>

<main class="catalog-page-container">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <nav class="catalog-breadcrumb" aria-label="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <a href="products.php">Catalog</a>
            <?php if ($category): ?>
                <i class="fas fa-chevron-right"></i>
                <span><?php echo htmlspecialchars(ucfirst($category)); ?></span>
            <?php elseif ($search): ?>
                <i class="fas fa-chevron-right"></i>
                <span>Search: "<?php echo htmlspecialchars($search); ?>"</span>
            <?php endif; ?>
        </nav>

        <div class="catalog-split-layout">
            <!-- Multi-Attribute Filter Sidebar (Flipkart / Amazon standard) -->
            <aside class="catalog-filter-sidebar">
                <div class="filter-sidebar-header">
                    <h3><i class="fas fa-filter"></i> Filters</h3>
                    <a href="products.php" class="clear-filters-link">Reset All</a>
                </div>

                <form action="products.php" method="GET" id="catalogFilterForm">
                    <?php if ($search): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                    <?php endif; ?>
                    <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">

                    <!-- Filter: Categories -->
                    <div class="filter-block">
                        <h4 class="filter-block-title">Categories</h4>
                        <ul class="filter-categories-list">
                            <li>
                                <a href="products.php<?php echo $search ? '?search='.urlencode($search) : ''; ?>" 
                                   class="filter-cat-link <?php echo empty($category) ? 'active' : ''; ?>">
                                    <span>All Categories</span>
                                </a>
                            </li>
                            <?php foreach ($categories as $cat): ?>
                            <li>
                                <a href="products.php?category=<?php echo urlencode($cat['slug']); ?><?php echo $search ? '&search='.urlencode($search) : ''; ?>" 
                                   class="filter-cat-link <?php echo ($category === $cat['slug']) ? 'active' : ''; ?>">
                                    <span><?php echo htmlspecialchars($cat['name']); ?></span>
                                    <span class="pill"><?php echo $cat['product_count']; ?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Filter: Price Range -->
                    <div class="filter-block">
                        <h4 class="filter-block-title">Price Range</h4>
                        <div class="price-inputs-row">
                            <div class="price-input-box">
                                <span>$</span>
                                <input type="number" name="min_price" placeholder="Min" value="<?php echo $minPrice > 0 ? $minPrice : ''; ?>" min="0">
                            </div>
                            <span style="color: #94a3b8;">-</span>
                            <div class="price-input-box">
                                <span>$</span>
                                <input type="number" name="max_price" placeholder="Max" value="<?php echo $maxPrice > 0 ? $maxPrice : ''; ?>" min="0">
                            </div>
                            <button type="submit" class="price-apply-btn">Go</button>
                        </div>
                    </div>

                    <!-- Filter: Customer Rating -->
                    <div class="filter-block">
                        <h4 class="filter-block-title">Customer Rating</h4>
                        <div class="rating-filter-options">
                            <label>
                                <input type="radio" name="rating" value="4" <?php echo $rating == 4 ? 'checked' : ''; ?> onchange="this.form.submit()">
                                <span class="star-badge-green">4 <i class="fas fa-star"></i></span>
                                <span>& above</span>
                            </label>
                            <label>
                                <input type="radio" name="rating" value="3" <?php echo $rating == 3 ? 'checked' : ''; ?> onchange="this.form.submit()">
                                <span class="star-badge-green" style="background: #eab308;">3 <i class="fas fa-star"></i></span>
                                <span>& above</span>
                            </label>
                            <?php if ($rating > 0): ?>
                                <label>
                                    <input type="radio" name="rating" value="0" onchange="this.form.submit()">
                                    <span style="font-size: 12px; color: #2563eb;">Clear rating filter</span>
                                </label>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Filter: Availability & Deals -->
                    <div class="filter-block">
                        <h4 class="filter-block-title">Special Offers</h4>
                        <label class="toggle-filter-label">
                            <input type="checkbox" name="in_stock" value="1" <?php echo $inStock ? 'checked' : ''; ?> onchange="this.form.submit()">
                            <span>In Stock Only</span>
                        </label>
                        <label class="toggle-filter-label">
                            <input type="checkbox" name="on_sale" value="1" <?php echo $onSale ? 'checked' : ''; ?> onchange="this.form.submit()">
                            <span style="color: #ea580c; font-weight: 600;"><i class="fas fa-percent"></i> On Sale & Discounted</span>
                        </label>
                    </div>
                </form>
            </aside>

            <!-- Main Catalog Product Showcase -->
            <section class="catalog-products-col">
                <!-- Top Toolbar with Sort & Counters -->
                <div class="catalog-top-toolbar">
                    <div class="catalog-title-meta">
                        <h1>
                            <?php 
                                if ($search) {
                                    echo 'Results for "' . htmlspecialchars($search) . '"';
                                } elseif ($category) {
                                    echo htmlspecialchars(ucfirst($category)) . ' Collection';
                                } elseif ($onSale) {
                                    echo "Today's Lightning Deals & Offers";
                                } else {
                                    echo 'All Products';
                                }
                            ?>
                        </h1>
                        <p>Showing <strong><?php echo min($totalProducts, count($products)); ?></strong> of <strong><?php echo $totalProducts; ?></strong> products</p>
                    </div>

                    <div class="catalog-toolbar-actions">
                        <div class="sort-dropdown-box">
                            <label for="catalogSortSelect"><i class="fas fa-sort-amount-down"></i> Sort by:</label>
                            <select id="catalogSortSelect" class="sort-select" onchange="changeCatalogSort(this.value)">
                                <option value="featured" <?php echo $sort === 'featured' ? 'selected' : ''; ?>>Featured / Recommended</option>
                                <option value="price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                                <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                                <option value="rating" <?php echo $sort === 'rating' ? 'selected' : ''; ?>>Customer Rating</option>
                                <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest Arrivals</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products Grid / Empty State -->
                <?php if (empty($products)): ?>
                    <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 60px 20px; text-align: center;">
                        <i class="fas fa-search" style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px;"></i>
                        <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">No products match your selection</h3>
                        <p style="color: #64748b; font-size: 14px; max-width: 400px; margin: 0 auto 24px;">Try loosening your filters, adjusting the price range, or searching for a different keyword.</p>
                        <a href="products.php" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Reset All Filters</a>
                    </div>
                <?php else: ?>
                    <div class="products-grid">
                        <?php foreach ($products as $product): ?>
                        <?php 
                            $price = ($product['sale_price'] && $product['sale_price'] > 0) ? $product['sale_price'] : $product['price'];
                            $hasSale = ($product['sale_price'] && $product['sale_price'] > 0 && $product['sale_price'] < $product['price']);
                            $discountPct = $hasSale ? round((($product['price'] - $product['sale_price']) / $product['price']) * 100) : 0;
                            $imgUrl = getProductImageUrl($product['image']);
                            $inWishlist = isLoggedIn() ? isInWishlist($_SESSION['user_id'], $product['id']) : false;
                        ?>
                        <div class="product-card">
                            <!-- Wishlist Toggle -->
                            <button class="card-wishlist-btn <?php echo $inWishlist ? 'active' : ''; ?>" 
                                    onclick="toggleWishlist(<?php echo $product['id']; ?>, this)" 
                                    title="Save to Wishlist">
                                <i class="fas fa-heart"></i>
                            </button>

                            <!-- Image Box -->
                            <div class="card-image-box">
                                <a href="product.php?id=<?php echo $product['id']; ?>">
                                    <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
                                </a>
                                <?php if ($hasSale): ?>
                                    <div class="card-badge-container">
                                        <span class="discount-tag"><?php echo $discountPct; ?>% OFF</span>
                                    </div>
                                <?php endif; ?>
                                <button class="card-quick-view-btn" onclick="openQuickView(<?php echo $product['id']; ?>)">
                                    <i class="far fa-eye"></i> Quick View
                                </button>
                            </div>

                            <!-- Details -->
                            <div class="card-details">
                                <span class="card-category-name"><?php echo htmlspecialchars($product['category_name'] ?? 'General'); ?></span>
                                <h3 class="card-product-title">
                                    <a href="product.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                                </h3>
                                
                                <div class="card-rating-row">
                                    <span class="star-badge-green">
                                        <?php echo round($product['avg_rating'], 1) ?: '4.7'; ?> <i class="fas fa-star"></i>
                                    </span>
                                    <span class="rating-count-text">(<?php echo $product['review_count'] ?: '12'; ?>)</span>
                                </div>

                                <div class="card-pricing-row">
                                    <span class="current-price">$<?php echo number_format($price, 2); ?></span>
                                    <?php if ($hasSale): ?>
                                        <span class="original-price-mrp">$<?php echo number_format($product['price'], 2); ?></span>
                                        <span class="discount-percentage-text"><?php echo $discountPct; ?>% off</span>
                                    <?php endif; ?>
                                </div>

                                <div class="card-actions-row">
                                    <button class="add-to-cart-btn" 
                                            onclick="quickAddToCart(<?php echo $product['id']; ?>, 1, this)"
                                            <?php echo $product['stock_quantity'] <= 0 ? 'disabled' : ''; ?>>
                                        <i class="fas fa-shopping-cart"></i>
                                        <?php echo $product['stock_quantity'] <= 0 ? 'Out of Stock' : 'Add to Cart'; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Dynamic Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination-container">
                            <?php if ($page > 1): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" class="page-btn">
                                    <i class="fas fa-chevron-left"></i> Prev
                                </a>
                            <?php endif; ?>

                            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $p])); ?>" 
                                   class="page-num <?php echo $p === $page ? 'active' : ''; ?>">
                                    <?php echo $p; ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" class="page-btn">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
        </div>
    </div>
</main>

<script>
function changeCatalogSort(newSort) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', newSort);
    url.searchParams.set('page', '1');
    window.location.href = url.toString();
}
</script>

<?php require_once 'includes/footer.php'; ?>
