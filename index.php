<?php
$pageTitle = 'ShopEasy - India\'s Favorite Online Shopping Mall | Deals, Tech & Fashion';
$activeNav = 'home';
require_once 'includes/header.php';

// Fetch dynamic data from DB
$featuredProducts = getFeaturedProducts(8);
$dealsOfDay       = getDealsOfDay(4);
$topRated         = getTopRatedProducts(4);
$newArrivals      = getNewArrivals(6);
?>

<main class="main-content">

    <!-- ================================================================
         HERO BANNER CAROUSEL
    ================================================================ -->
    <section class="hero-slider-section">
        <div class="hero-slider-container">

            <!-- Slide 1 -->
            <div class="hero-slide hero-slide-1 active">
                <div class="container">
                    <div class="hero-slide-content">
                        <div class="hero-text-col">
                            <span class="hero-pill-badge"><i class="fas fa-bolt"></i> Mega Flagship Sale</span>
                            <h1>Experience the Next Generation of Tech</h1>
                            <p>iPhone 15 Pro, Galaxy S24 & MacBook Air M3 at exclusive prices. Free delivery + EMI options.</p>
                            <div class="hero-cta-group">
                                <a href="products.php?category=electronics" class="btn btn-accent btn-lg"><i class="fas fa-shopping-bag"></i> Shop Electronics</a>
                                <a href="products.php?on_sale=1" class="btn btn-outline btn-lg" style="color:#fff;border-color:rgba(255,255,255,0.4);">View Offers</a>
                            </div>
                            <div class="hero-trust-strip">
                                <span><i class="fas fa-shield-alt"></i> Secure Pay</span>
                                <span><i class="fas fa-undo"></i> Easy Returns</span>
                                <span><i class="fas fa-truck"></i> Free Delivery</span>
                            </div>
                        </div>
                        <div class="hero-image-col">
                            <img src="Image/iPhone 15 Pro.webp" alt="iPhone 15 Pro" class="hero-banner-img" onerror="this.src='Image/MacBook Air M3.webp'">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="hero-slide hero-slide-2">
                <div class="container">
                    <div class="hero-slide-content">
                        <div class="hero-text-col">
                            <span class="hero-pill-badge" style="background:rgba(16,185,129,0.25);"><i class="fas fa-tshirt"></i> Premium Wardrobe Edit</span>
                            <h1>Step Out in Style & Unrivaled Comfort</h1>
                            <p>Authentic Levi's denim, Nike Air Max & Adidas Ultraboost — crafted for street presence and daily comfort.</p>
                            <div class="hero-cta-group">
                                <a href="products.php?category=clothing" class="btn btn-primary btn-lg"><i class="fas fa-shopping-bag"></i> Shop Fashion</a>
                                <a href="products.php?category=sports" class="btn btn-outline btn-lg" style="color:#fff;border-color:rgba(255,255,255,0.4);">Explore Athleisure</a>
                            </div>
                        </div>
                        <div class="hero-image-col">
                            <img src="Image/Nike Air Max 270.webp" alt="Nike Air Max 270" class="hero-banner-img">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="hero-slide hero-slide-3">
                <div class="container">
                    <div class="hero-slide-content">
                        <div class="hero-text-col">
                            <span class="hero-pill-badge" style="background:rgba(249,115,22,0.25);"><i class="fas fa-home"></i> Modern Living Solutions</span>
                            <h1>Elevate Your Home & Culinary Adventures</h1>
                            <p>Dyson precision cleaning + KitchenAid luxury baking — experience premium home appliances with warranty.</p>
                            <div class="hero-cta-group">
                                <a href="products.php?category=home" class="btn btn-accent btn-lg"><i class="fas fa-shopping-bag"></i> Shop Home & Kitchen</a>
                                <a href="products.php" class="btn btn-outline btn-lg" style="color:#fff;border-color:rgba(255,255,255,0.4);">All Categories</a>
                            </div>
                        </div>
                        <div class="hero-image-col">
                            <img src="Image/KitchenAid Stand Mixer.webp" alt="KitchenAid Mixer" class="hero-banner-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="slider-arrow slider-arrow-prev" aria-label="Previous slide"><i class="fas fa-chevron-left"></i></button>
        <button class="slider-arrow slider-arrow-next" aria-label="Next slide"><i class="fas fa-chevron-right"></i></button>
        <div class="slider-dots-box">
            <span class="slider-dot active"></span>
            <span class="slider-dot"></span>
            <span class="slider-dot"></span>
        </div>
    </section>

    <div class="container">

        <!-- ================================================================
             CATEGORY STORY BUBBLES (Meesho / Instagram / Flipkart Style)
        ================================================================ -->
        <section class="category-bubbles-section">
            <div class="category-bubbles-grid">
                <a href="products.php?category=electronics" class="category-bubble-item">
                    <div class="bubble-avatar-wrapper">
                        <div class="bubble-avatar-inner">
                            <img src="Image/Electronics.jpg" alt="Electronics" onerror="this.src='assets/images/placeholder.svg'">
                        </div>
                        <span class="bubble-count-tag">3</span>
                    </div>
                    <span class="bubble-title">Electronics</span>
                </a>
                <a href="products.php?category=clothing" class="category-bubble-item">
                    <div class="bubble-avatar-wrapper">
                        <div class="bubble-avatar-inner">
                            <img src="Image/Clothing.png" alt="Fashion" onerror="this.src='assets/images/placeholder.svg'">
                        </div>
                        <span class="bubble-count-tag">3</span>
                    </div>
                    <span class="bubble-title">Fashion</span>
                </a>
                <a href="products.php?category=home" class="category-bubble-item">
                    <div class="bubble-avatar-wrapper">
                        <div class="bubble-avatar-inner">
                            <img src="Image/Home & Garden.jpg" alt="Home" onerror="this.src='assets/images/placeholder.svg'">
                        </div>
                        <span class="bubble-count-tag">2</span>
                    </div>
                    <span class="bubble-title">Home & Living</span>
                </a>
                <a href="products.php?category=sports" class="category-bubble-item">
                    <div class="bubble-avatar-wrapper">
                        <div class="bubble-avatar-inner">
                            <img src="Image/Sports.jpg" alt="Sports" onerror="this.src='assets/images/placeholder.svg'">
                        </div>
                        <span class="bubble-count-tag">2</span>
                    </div>
                    <span class="bubble-title">Sports</span>
                </a>
                <a href="products.php?on_sale=1" class="category-bubble-item">
                    <div class="bubble-avatar-wrapper" style="background: linear-gradient(135deg, #ef4444, #f97316);">
                        <div class="bubble-avatar-inner" style="background:#fff1f2;font-size:26px;color:#ef4444;">
                            <i class="fas fa-fire"></i>
                        </div>
                        <span class="bubble-count-tag" style="background:#ef4444;">Hot</span>
                    </div>
                    <span class="bubble-title" style="color:#ef4444;font-weight:700;">Deals &amp; Offers</span>
                </a>
                <a href="products.php" class="category-bubble-item">
                    <div class="bubble-avatar-wrapper" style="background: linear-gradient(135deg, #2563eb, #6366f1);">
                        <div class="bubble-avatar-inner" style="background:#eff6ff;font-size:24px;color:#2563eb;">
                            <i class="fas fa-th-large"></i>
                        </div>
                        <span class="bubble-count-tag" style="background:#2563eb;">All</span>
                    </div>
                    <span class="bubble-title">All Categories</span>
                </a>
            </div>
        </section>

        <!-- ================================================================
             BRAND LOGOS AUTO-SCROLL STRIP (Amazon / Flipkart Pattern)
        ================================================================ -->
        <section class="brands-strip-section">
            <div class="brands-strip-header"><i class="fas fa-certificate" style="color:#f59e0b;"></i> &nbsp;Trusted Brands Available on ShopEasy</div>
            <div class="brands-logos-scroll">
                <!-- Track 1 (real) -->
                <div class="brands-logos-track" id="brandsTrack">
                    <span class="brand-logo-item">Apple</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Samsung</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Nike</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Adidas</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Sony</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Dyson</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">LG</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Levi's</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">KitchenAid</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">OnePlus</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Xiaomi</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Boat</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Puma</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Bose</span><span class="brands-dot-sep">·</span>
                    <!-- Duplicate for seamless loop -->
                    <span class="brand-logo-item">Apple</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Samsung</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Nike</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Adidas</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Sony</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Dyson</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">LG</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Levi's</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">KitchenAid</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">OnePlus</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Xiaomi</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Boat</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Puma</span><span class="brands-dot-sep">·</span>
                    <span class="brand-logo-item">Bose</span>
                </div>
            </div>
        </section>

        <!-- ================================================================
             DEAL OF THE DAY
        ================================================================ -->
        <?php if (!empty($dealsOfDay)): ?>
        <section class="deal-of-day-section">
            <div class="section-header-deal">
                <div class="deal-header-left">
                    <div class="deal-title-box">
                        <i class="fas fa-bolt deal-flame-icon"></i>
                        <h2>Deal of the Day</h2>
                    </div>
                    <div class="countdown-timer-box">
                        <span class="countdown-label"><i class="far fa-clock"></i> Ends in:</span>
                        <div class="timer-units">
                            <span class="time-box" id="dealHours">08</span> :
                            <span class="time-box" id="dealMins">45</span> :
                            <span class="time-box" id="dealSecs">30</span>
                        </div>
                    </div>
                </div>
                <a href="products.php?on_sale=1" class="view-all-link">See All Deals <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="products-grid">
                <?php foreach ($dealsOfDay as $product):
                    $price = $product['sale_price'] ?? $product['price'];
                    $discountPct = ($product['sale_price'] && $product['price'] > 0)
                        ? round((($product['price'] - $product['sale_price']) / $product['price']) * 100)
                        : 0;
                    $imgUrl = getProductImageUrl($product['image']);
                    $inWishlist = isLoggedIn() ? isInWishlist($_SESSION['user_id'], $product['id']) : false;
                ?>
                <div class="product-card">
                    <button class="card-wishlist-btn <?php echo $inWishlist ? 'active' : ''; ?>"
                            onclick="toggleWishlist(<?php echo $product['id']; ?>, this)"
                            title="Save to Wishlist">
                        <i class="fas fa-heart"></i>
                    </button>
                    <div class="card-image-box">
                        <a href="product.php?id=<?php echo $product['id']; ?>">
                            <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy" onerror="this.src='assets/images/placeholder.svg'">
                        </a>
                        <div class="card-badge-container">
                            <?php if ($discountPct > 0): ?>
                            <span class="discount-tag"><?php echo $discountPct; ?>% OFF</span>
                            <?php endif; ?>
                            <span class="deal-badge-tag">Lightning Deal</span>
                        </div>
                        <button class="card-quick-view-btn" onclick="openQuickView(<?php echo $product['id']; ?>)">
                            <i class="far fa-eye"></i> Quick View
                        </button>
                    </div>
                    <div class="card-details">
                        <span class="card-category-name"><?php echo htmlspecialchars($product['category_name'] ?? 'General'); ?></span>
                        <h3 class="card-product-title">
                            <a href="product.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                        </h3>
                        <div class="card-rating-row">
                            <span class="star-badge-green">
                                <?php echo round($product['avg_rating'], 1) ?: '4.8'; ?> <i class="fas fa-star"></i>
                            </span>
                            <span class="rating-count-text">(<?php echo $product['review_count'] ?: '24'; ?>)</span>
                        </div>
                        <div class="card-pricing-row">
                            <span class="current-price">$<?php echo number_format($price, 2); ?></span>
                            <?php if ($discountPct > 0): ?>
                            <span class="original-price-mrp">$<?php echo number_format($product['price'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php 
                            $claimedPct = min(94, max(52, 60 + (($product['id'] * 19) % 33)));
                            $leftUnits = max(2, min(9, 11 - (($product['id'] * 2) % 9)));
                        ?>
                        <div class="deal-progress-box">
                            <div class="progress-bar-bg">
                                <div class="progress-bar-fill" style="width: <?php echo $claimedPct; ?>%;"></div>
                            </div>
                            <div class="deal-status-text">
                                <span><?php echo $claimedPct; ?>% Claimed</span>
                                <span style="color:#ea580c;font-weight:700;">Only <?php echo $leftUnits; ?> left!</span>
                            </div>
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
        </section>
        <?php endif; ?>

        <!-- ================================================================
             PROMOTIONAL DUO BANNERS
        ================================================================ -->
        <section class="promo-duo-section">
            <div class="promo-card promo-card-blue">
                <div class="promo-card-text">
                    <span class="promo-pill">Top Electronics</span>
                    <h3>Smartphones &amp; Laptops</h3>
                    <p>Starting from $799 · Brand warranty included</p>
                    <a href="products.php?category=electronics" class="btn btn-accent btn-sm">Explore <i class="fas fa-arrow-right"></i></a>
                </div>
                <img src="Image/Samsung Galaxy S24.webp" alt="Samsung Galaxy" onerror="this.style.display='none'">
            </div>
            <div class="promo-card promo-card-green">
                <div class="promo-card-text">
                    <span class="promo-pill">Fitness &amp; Footwear</span>
                    <h3>Nike &amp; Adidas Runners</h3>
                    <p>Up to 30% off · Premier running shoes</p>
                    <a href="products.php?category=clothing" class="btn btn-accent btn-sm">Shop Now <i class="fas fa-arrow-right"></i></a>
                </div>
                <img src="Image/Adidas Ultraboost 22.webp" alt="Adidas Ultraboost" onerror="this.style.display='none'">
            </div>
        </section>

        <!-- ================================================================
             FEATURED PRODUCTS
        ================================================================ -->
        <?php if (!empty($featuredProducts)): ?>
        <section class="home-section">
            <div class="home-section-header">
                <div>
                    <h2><i class="fas fa-star" style="color:#f59e0b;"></i> Featured Products</h2>
                    <p>Curated top-selling products across our highest-rated categories</p>
                </div>
                <a href="products.php" class="view-all-link">Browse All <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="products-grid">
                <?php foreach ($featuredProducts as $product):
                    $price = ($product['sale_price'] && $product['sale_price'] > 0) ? $product['sale_price'] : $product['price'];
                    $hasSale = ($product['sale_price'] && $product['sale_price'] > 0 && $product['sale_price'] < $product['price']);
                    $discountPct = $hasSale ? round((($product['price'] - $product['sale_price']) / $product['price']) * 100) : 0;
                    $imgUrl = getProductImageUrl($product['image']);
                    $inWishlist = isLoggedIn() ? isInWishlist($_SESSION['user_id'], $product['id']) : false;
                ?>
                <div class="product-card">
                    <button class="card-wishlist-btn <?php echo $inWishlist ? 'active' : ''; ?>"
                            onclick="toggleWishlist(<?php echo $product['id']; ?>, this)"
                            title="Save to Wishlist">
                        <i class="fas fa-heart"></i>
                    </button>
                    <div class="card-image-box">
                        <a href="product.php?id=<?php echo $product['id']; ?>">
                            <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy" onerror="this.src='assets/images/placeholder.svg'">
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
                    <div class="card-details">
                        <span class="card-category-name"><?php echo htmlspecialchars($product['category_name'] ?? 'General'); ?></span>
                        <h3 class="card-product-title">
                            <a href="product.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                        </h3>
                        <div class="card-rating-row">
                            <span class="star-badge-green">
                                <?php echo round($product['avg_rating'], 1) ?: '4.7'; ?> <i class="fas fa-star"></i>
                            </span>
                            <span class="rating-count-text">(<?php echo $product['review_count'] ?: '18'; ?>)</span>
                        </div>
                        <div class="card-pricing-row">
                            <span class="current-price">$<?php echo number_format($price, 2); ?></span>
                            <?php if ($hasSale): ?>
                            <span class="original-price-mrp">$<?php echo number_format($product['price'], 2); ?></span>
                            <span class="discount-percentage-text"><?php echo $discountPct; ?>% off</span>
                            <?php endif; ?>
                        </div>
                        <?php if ($product['stock_quantity'] > 0 && $product['stock_quantity'] <= 10): ?>
                        <p class="low-stock-warning"><i class="fas fa-exclamation-triangle"></i> Only <?php echo $product['stock_quantity']; ?> left!</p>
                        <?php endif; ?>
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
        </section>
        <?php endif; ?>

        <!-- ================================================================
             MID-PROMO RIBBON BANNER (Premium E-com Pattern)
        ================================================================ -->
        <section class="mid-promo-ribbon">
            <div class="mid-promo-ribbon-text">
                <h3><i class="fas fa-bolt"></i> Exclusive Member Benefits</h3>
                <p>Shop more, save more — extra 10% OFF for registered members on every purchase!</p>
                <?php if (!isLoggedIn()): ?>
                <a href="login.php" class="btn btn-accent btn-sm"><i class="fas fa-user-plus"></i> Join Free Now</a>
                <?php else: ?>
                <a href="products.php?on_sale=1" class="btn btn-accent btn-sm"><i class="fas fa-fire"></i> Claim Deals</a>
                <?php endif; ?>
            </div>
            <div class="mid-promo-ribbon-icons">
                <div class="mid-promo-icon-item"><i class="fas fa-truck-fast"></i><span>Free Delivery</span></div>
                <div class="mid-promo-icon-item"><i class="fas fa-undo-alt"></i><span>Easy Returns</span></div>
                <div class="mid-promo-icon-item"><i class="fas fa-shield-halved"></i><span>Secure Pay</span></div>
                <div class="mid-promo-icon-item"><i class="fas fa-headset"></i><span>24/7 Support</span></div>
            </div>
        </section>

        <!-- ================================================================
             NEW ARRIVALS
        ================================================================ -->
        <?php if (!empty($newArrivals)): ?>
        <section class="home-section">
            <div class="home-section-header">
                <div>
                    <h2><i class="fas fa-sparkles" style="color:#8b5cf6;"></i> New Arrivals</h2>
                    <p>Fresh drops — the latest products added to our store</p>
                </div>
                <a href="products.php?sort=newest" class="view-all-link">See All New <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="products-grid">
                <?php foreach ($newArrivals as $product):
                    $price = ($product['sale_price'] && $product['sale_price'] > 0) ? $product['sale_price'] : $product['price'];
                    $hasSale = ($product['sale_price'] && $product['sale_price'] > 0 && $product['sale_price'] < $product['price']);
                    $discountPct = $hasSale ? round((($product['price'] - $product['sale_price']) / $product['price']) * 100) : 0;
                    $imgUrl = getProductImageUrl($product['image']);
                    $inWishlist = isLoggedIn() ? isInWishlist($_SESSION['user_id'], $product['id']) : false;
                ?>
                <div class="product-card">
                    <button class="card-wishlist-btn <?php echo $inWishlist ? 'active' : ''; ?>"
                            onclick="toggleWishlist(<?php echo $product['id']; ?>, this)"
                            title="Save to Wishlist">
                        <i class="fas fa-heart"></i>
                    </button>
                    <div class="card-image-box">
                        <a href="product.php?id=<?php echo $product['id']; ?>">
                            <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy" onerror="this.src='assets/images/placeholder.svg'">
                        </a>
                        <div class="card-badge-container">
                            <span class="new-badge-tag">NEW</span>
                            <?php if ($hasSale): ?>
                            <span class="discount-tag"><?php echo $discountPct; ?>% OFF</span>
                            <?php endif; ?>
                        </div>
                        <button class="card-quick-view-btn" onclick="openQuickView(<?php echo $product['id']; ?>)">
                            <i class="far fa-eye"></i> Quick View
                        </button>
                    </div>
                    <div class="card-details">
                        <span class="card-category-name"><?php echo htmlspecialchars($product['category_name'] ?? 'General'); ?></span>
                        <h3 class="card-product-title">
                            <a href="product.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                        </h3>
                        <div class="card-rating-row">
                            <span class="star-badge-green">
                                <?php echo round($product['avg_rating'], 1) ?: '4.5'; ?> <i class="fas fa-star"></i>
                            </span>
                            <span class="rating-count-text">(<?php echo $product['review_count'] ?: '12'; ?>)</span>
                        </div>
                        <div class="card-pricing-row">
                            <span class="current-price">$<?php echo number_format($price, 2); ?></span>
                            <?php if ($hasSale): ?>
                            <span class="original-price-mrp">$<?php echo number_format($product['price'], 2); ?></span>
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
        </section>
        <?php endif; ?>

        <!-- ================================================================
             CUSTOMER FAVORITES / TOP RATED
        ================================================================ -->
        <?php if (!empty($topRated)): ?>
        <section class="home-section">
            <div class="home-section-header">
                <div>
                    <h2><i class="fas fa-award" style="color:#f59e0b;"></i> Customer Favorites</h2>
                    <p>Highest rated by verified buyers across India</p>
                </div>
                <a href="products.php?rating=4" class="view-all-link">See Top Rated <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="products-grid">
                <?php foreach ($topRated as $product):
                    $price = ($product['sale_price'] && $product['sale_price'] > 0) ? $product['sale_price'] : $product['price'];
                    $imgUrl = getProductImageUrl($product['image']);
                    $inWishlist = isLoggedIn() ? isInWishlist($_SESSION['user_id'], $product['id']) : false;
                ?>
                <div class="product-card">
                    <button class="card-wishlist-btn <?php echo $inWishlist ? 'active' : ''; ?>"
                            onclick="toggleWishlist(<?php echo $product['id']; ?>, this)"
                            title="Save to Wishlist">
                        <i class="fas fa-heart"></i>
                    </button>
                    <div class="card-image-box">
                        <a href="product.php?id=<?php echo $product['id']; ?>">
                            <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy" onerror="this.src='assets/images/placeholder.svg'">
                        </a>
                        <button class="card-quick-view-btn" onclick="openQuickView(<?php echo $product['id']; ?>)">
                            <i class="far fa-eye"></i> Quick View
                        </button>
                    </div>
                    <div class="card-details">
                        <span class="card-category-name"><?php echo htmlspecialchars($product['category_name'] ?? 'General'); ?></span>
                        <h3 class="card-product-title">
                            <a href="product.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                        </h3>
                        <div class="card-rating-row">
                            <span class="star-badge-green">
                                <?php echo round($product['avg_rating'], 1) ?: '4.9'; ?> <i class="fas fa-star"></i>
                            </span>
                            <span class="rating-count-text">(<?php echo $product['review_count'] ?: '35'; ?> verified)</span>
                        </div>
                        <div class="card-pricing-row">
                            <span class="current-price">$<?php echo number_format($price, 2); ?></span>
                            <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                            <span class="original-price-mrp">$<?php echo number_format($product['price'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="card-actions-row">
                            <button class="add-to-cart-btn"
                                    onclick="quickAddToCart(<?php echo $product['id']; ?>, 1, this)"
                                    <?php echo $product['stock_quantity'] <= 0 ? 'disabled' : ''; ?>>
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- ================================================================
             TRUST BADGES
        ================================================================ -->
        <section class="trust-badges-section">
            <div class="trust-badge-item">
                <i class="fas fa-truck-fast"></i>
                <div>
                    <strong>Free Delivery</strong>
                    <span>On orders above $50</span>
                </div>
            </div>
            <div class="trust-badge-item">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <strong>100% Secure</strong>
                    <span>SSL encrypted payments</span>
                </div>
            </div>
            <div class="trust-badge-item">
                <i class="fas fa-undo-alt"></i>
                <div>
                    <strong>Easy Returns</strong>
                    <span>30-day return policy</span>
                </div>
            </div>
            <div class="trust-badge-item">
                <i class="fas fa-headset"></i>
                <div>
                    <strong>24/7 Support</strong>
                    <span>Always here to help</span>
                </div>
            </div>
            <div class="trust-badge-item">
                <i class="fas fa-tag"></i>
                <div>
                    <strong>Best Prices</strong>
                    <span>Price match guarantee</span>
                </div>
            </div>
        </section>

    </div><!-- /.container -->
</main>

<?php require_once 'includes/footer.php'; ?>
