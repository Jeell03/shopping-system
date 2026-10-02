<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = $pageTitle ?? 'ShopEasy - Your Ultimate Online Shopping Destination';
$activeNav = $activeNav ?? '';
$categoriesNav = getCategories();
$cartCount = getCartCount();
$wishlistCount = isLoggedIn() ? getWishlistCount($_SESSION['user_id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="ShopEasy - India's favorite shopping platform for Electronics, Fashion, Home essentials and more with free delivery, easy returns, and unbeatable deals.">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/style.css'); ?>">
    <link rel="stylesheet" href="assets/css/theme.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/theme.css'); ?>">
    <!-- Instant Theme Init Script (Zero flicker) -->
    <script>
        (function() {
            var saved = localStorage.getItem('shopeasy_theme');
            var isDark = saved ? (saved === 'dark') : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
        })();
    </script>
</head>
<body>

    <!-- Top Announcement Bar (Amazon/Flipkart Style) -->
    <div class="top-announcement-bar">
        <div class="container top-bar-inner">
            <div class="top-bar-left">
                <div class="location-pill" id="headerLocationBtn" onclick="openPincodeModal()">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Deliver to <strong id="headerPincodeText">Mumbai 400001</strong></span>
                    <i class="fas fa-chevron-down pill-arrow"></i>
                </div>
                <div class="promo-ticker">
                    <span class="ticker-badge"><i class="fas fa-bolt"></i> Deal</span>
                    <span class="ticker-text">Mega Savings: Use code <strong>WELCOME10</strong> for 10% OFF | Free Shipping over $50</span>
                </div>
            </div>
            <div class="top-bar-right">
                <a href="faq.php" class="top-link"><i class="far fa-question-circle"></i> Help & 24/7 Support</a>
                <a href="shipping.php" class="top-link"><i class="fas fa-truck"></i> Track Order</a>
                <?php if (isAdmin()): ?>
                    <a href="admin/index.php" class="top-link admin-pill"><i class="fas fa-shield-alt"></i> Admin Panel</a>
                <?php endif; ?>
                <!-- Theme Mode (Light / Dark) Buttons -->
                <div class="theme-switch-group" role="group" aria-label="Theme Mode Selection">
                    <button type="button" class="theme-btn theme-btn-light" id="topThemeBtnLight" onclick="setThemeMode('light')" title="Switch to Light Mode">
                        <i class="fas fa-sun"></i>
                        <span>Light</span>
                    </button>
                    <button type="button" class="theme-btn theme-btn-dark" id="topThemeBtnDark" onclick="setThemeMode('dark')" title="Switch to Dark Mode">
                        <i class="fas fa-moon"></i>
                        <span>Dark</span>
                    </button>
                </div>
                <div class="currency-badge">
                    <span>USD ($)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="main-header" id="siteHeader">
        <div class="container header-container">
            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle navigation menu">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Brand Logo -->
            <div class="brand-logo">
                <a href="index.php" class="logo-link">
                    <div class="logo-icon-box">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="logo-text">
                        <span class="logo-name">Shop<span>Easy</span></span>
                        <span class="logo-tagline">Shop. Save. Smile.</span>
                    </div>
                </a>
            </div>

            <!-- Smart Live Search Bar -->
            <div class="header-search-wrapper">
                <form action="products.php" method="GET" class="search-form" id="globalSearchForm">
                    <div class="category-select-wrapper">
                        <select name="category" id="searchCategorySelect" class="search-cat-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categoriesNav as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['slug']); ?>">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fas fa-chevron-down cat-dropdown-icon"></i>
                    </div>
                    <div class="search-input-field">
                        <input type="text" name="search" id="globalSearchInput" placeholder="Search for products, brands, smartphones, fashion..." autocomplete="off" required>
                        <button type="button" class="clear-search-btn" id="clearSearchBtn" style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <button type="submit" class="search-submit-btn" aria-label="Submit search">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <!-- Live Auto-Suggest Dropdown Results Panel -->
                <div class="search-suggestions-dropdown" id="searchSuggestionsBox" style="display: none;">
                    <div class="suggestions-loading" id="suggestionsLoading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Searching products...
                    </div>
                    <div class="suggestions-list" id="suggestionsList"></div>
                    <div class="suggestions-footer" id="suggestionsFooter" style="display: none;">
                        <a href="#" id="viewAllResultsLink">View all matching products <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Header Action Controls -->
            <div class="header-actions">
                <!-- User Account Menu -->
                <div class="action-item user-account-dropdown">
                    <?php if (isLoggedIn()): ?>
                        <div class="user-trigger">
                            <div class="avatar-circle">
                                <?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>
                            </div>
                            <div class="user-meta">
                                <span class="greeting">Hello, <?php echo htmlspecialchars(substr($_SESSION['username'], 0, 10)); ?></span>
                                <span class="account-label">Account & Orders <i class="fas fa-chevron-down"></i></span>
                            </div>
                        </div>
                        <div class="account-menu-flyout">
                            <div class="flyout-header">
                                <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                                <span class="flyout-email"><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></span>
                            </div>
                            <ul class="flyout-links">
                                <li><a href="profile.php"><i class="fas fa-user-circle"></i> My Profile</a></li>
                                <li><a href="profile.php?tab=orders"><i class="fas fa-box-open"></i> My Orders</a></li>
                                <li><a href="wishlist.php"><i class="fas fa-heart"></i> My Wishlist (<?php echo $wishlistCount; ?>)</a></li>
                                <li><a href="profile.php?tab=addresses"><i class="fas fa-map-marker-alt"></i> Saved Addresses</a></li>
                                <?php if (isAdmin()): ?>
                                    <li class="admin-link-item"><a href="admin/index.php"><i class="fas fa-cog"></i> Admin Dashboard</a></li>
                                <?php endif; ?>
                                <li class="divider"></li>
                                <li><a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Sign Out</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="login-trigger-btn">
                            <i class="far fa-user"></i>
                            <div class="user-meta">
                                <span class="greeting">Sign In</span>
                                <span class="account-label">Account <i class="fas fa-chevron-down"></i></span>
                            </div>
                        </a>
                        <div class="account-menu-flyout guest-flyout">
                            <div class="flyout-cta">
                                <a href="login.php" class="btn btn-primary btn-sm btn-block">Sign In</a>
                                <div class="flyout-subtext">New customer? <a href="register.php">Start here.</a></div>
                            </div>
                            <ul class="flyout-links">
                                <li><a href="login.php"><i class="fas fa-box-open"></i> Your Orders</a></li>
                                <li><a href="login.php"><i class="fas fa-heart"></i> Your Wishlist</a></li>
                                <li><a href="contact.php"><i class="fas fa-headset"></i> Customer Service</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Wishlist Icon with Dynamic Badge -->
                <a href="<?php echo isLoggedIn() ? 'wishlist.php' : 'login.php'; ?>" class="action-item icon-action wishlist-action" title="View Wishlist">
                    <div class="icon-badge-box">
                        <i class="far fa-heart"></i>
                        <span class="count-badge wishlist-count-badge" id="headerWishlistBadge"><?php echo $wishlistCount; ?></span>
                    </div>
                    <span class="action-label">Wishlist</span>
                </a>

                <!-- Shopping Cart Icon with Flyout Preview -->
                <div class="action-item cart-action-wrapper" id="cartActionWrapper">
                    <a href="cart.php" class="icon-action cart-action" title="Shopping Cart">
                        <div class="icon-badge-box">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="count-badge cart-count-badge" id="headerCartBadge"><?php echo $cartCount; ?></span>
                        </div>
                        <div class="cart-label-box">
                            <span class="action-label">Cart</span>
                            <span class="cart-subtotal-header" id="headerCartTotal"></span>
                        </div>
                    </a>
                </div>
                <!-- Theme Mode Toggle Action -->
                <button type="button" class="action-item icon-action theme-action-btn" id="headerThemeBtn" onclick="toggleThemeMode()" title="Switch Light/Dark Mode" aria-label="Toggle display mode">
                    <div class="icon-badge-box">
                        <i class="fas fa-moon theme-toggle-icon" id="mainThemeIcon"></i>
                    </div>
                    <span class="action-label" id="mainThemeLabel">Theme</span>
                </button>
            </div>
        </div>

        <!-- Secondary Categories Navigation Bar -->
        <nav class="secondary-navbar">
            <div class="container nav-inner">
                <!-- All Categories Dropdown Mega Menu Trigger -->
                <div class="all-cats-menu">
                    <button class="all-cats-btn" id="allCatsBtn">
                        <i class="fas fa-bars"></i>
                        <span>All Categories</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="all-cats-flyout" id="allCatsFlyout">
                        <ul>
                            <?php foreach ($categoriesNav as $cat): ?>
                                <li>
                                    <a href="products.php?category=<?php echo htmlspecialchars($cat['slug']); ?>">
                                        <i class="fas fa-chevron-right cat-bullet"></i>
                                        <span><?php echo htmlspecialchars($cat['name']); ?></span>
                                        <?php if (!empty($cat['product_count'])): ?>
                                            <span class="cat-pill"><?php echo $cat['product_count']; ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <li>
                                <a href="products.php" class="view-all-cats">
                                    <i class="fas fa-th-large"></i>
                                    <span>Browse All Products</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Horizontal Navigation Links -->
                <ul class="nav-links-list">
                    <li><a href="index.php" class="<?php echo $activeNav === 'home' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="products.php" class="<?php echo $activeNav === 'products' ? 'active' : ''; ?>">All Products</a></li>
                    <li><a href="products.php?category=electronics" class="<?php echo $activeNav === 'electronics' ? 'active' : ''; ?>">Electronics</a></li>
                    <li><a href="products.php?category=clothing" class="<?php echo $activeNav === 'clothing' ? 'active' : ''; ?>">Fashion</a></li>
                    <li><a href="products.php?category=home" class="<?php echo ($activeNav === 'home' || $activeNav === 'home_garden') ? 'active' : ''; ?>">Home & Kitchen</a></li>
                    <li><a href="products.php?category=sports" class="<?php echo $activeNav === 'sports' ? 'active' : ''; ?>">Sports & Fitness</a></li>
                    <li><a href="products.php?on_sale=1" class="highlight-link deal-link"><i class="fas fa-fire"></i> Today's Deals</a></li>
                    <li><a href="about.php" class="<?php echo $activeNav === 'about' ? 'active' : ''; ?>">About Us</a></li>
                    <li><a href="contact.php" class="<?php echo $activeNav === 'contact' ? 'active' : ''; ?>">Contact</a></li>
                </ul>

                <!-- Right Trust Strip -->
                <div class="nav-extra-badge">
                    <span class="prime-pill"><i class="fas fa-shield-alt"></i> 100% Secure Checkout</span>
                </div>
            </div>
        </nav>
    </header>

    <!-- Mobile Navigation Drawer (Off-canvas) -->
    <div class="mobile-drawer-overlay" id="mobileDrawerOverlay"></div>
    <aside class="mobile-drawer" id="mobileDrawer">
        <div class="drawer-header">
            <div class="drawer-user">
                <i class="fas fa-user-circle"></i>
                <?php if (isLoggedIn()): ?>
                    <span>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <?php else: ?>
                    <span>Hello, <a href="login.php">Sign In</a></span>
                <?php endif; ?>
            </div>
            <button class="drawer-close-btn" id="drawerCloseBtn" aria-label="Close menu">&times;</button>
        </div>
        <div class="drawer-body">
            <div class="drawer-section-title">Shop by Category</div>
            <ul class="drawer-nav">
                <li><a href="products.php"><i class="fas fa-th-large"></i> All Products</a></li>
                <?php foreach ($categoriesNav as $cat): ?>
                    <li>
                        <a href="products.php?category=<?php echo htmlspecialchars($cat['slug']); ?>">
                            <i class="fas fa-tag"></i> <?php echo htmlspecialchars($cat['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li><a href="products.php?on_sale=1" class="drawer-deal"><i class="fas fa-fire"></i> Today's Deals</a></li>
            </ul>

            <div class="drawer-section-title">Account & Settings</div>
            <ul class="drawer-nav">
                <li class="drawer-theme-item">
                    <div class="drawer-theme-row">
                        <span><i class="fas fa-adjust" style="color: #2563eb; margin-right: 6px;"></i> Theme</span>
                        <div class="theme-switch-group">
                            <button type="button" class="theme-btn theme-btn-light" onclick="setThemeMode('light')">
                                <i class="fas fa-sun"></i> Light
                            </button>
                            <button type="button" class="theme-btn theme-btn-dark" onclick="setThemeMode('dark')">
                                <i class="fas fa-moon"></i> Dark
                            </button>
                        </div>
                    </div>
                </li>
                <?php if (isLoggedIn()): ?>
                    <li><a href="profile.php"><i class="fas fa-user"></i> My Profile</a></li>
                    <li><a href="profile.php?tab=orders"><i class="fas fa-box"></i> My Orders</a></li>
                    <li><a href="wishlist.php"><i class="fas fa-heart"></i> Wishlist</a></li>
                    <?php if (isAdmin()): ?>
                        <li><a href="admin/index.php"><i class="fas fa-shield-alt"></i> Admin Panel</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                <?php endif; ?>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact Support</a></li>
                <li><a href="faq.php"><i class="fas fa-question-circle"></i> FAQs</a></li>
            </ul>
        </div>
    </aside>

    <!-- Global Toast Notification Container -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Pincode Modal Container -->
    <div id="pincodeModal" class="modal-backdrop" style="display: none;">
        <div class="modal-dialog" style="max-width: 420px;">
            <button class="modal-close-btn" id="pincodeCloseBtn" onclick="closePincodeModal()">&times;</button>
            <div class="modal-body pincode-modal-card">
                <i class="fas fa-map-marker-alt" style="font-size: 32px; color: #ea580c; margin-bottom: 12px; display: inline-block;"></i>
                <h3>Choose Delivery Location</h3>
                <p>Select your delivery pincode to check accurate stock, express delivery, and payment options.</p>
                <div class="pincode-modal-form">
                    <input type="text" id="modalPincodeInput" placeholder="Enter 6-digit Pincode" maxlength="6" value="400001">
                    <button type="button" class="btn btn-primary" onclick="savePincodeModal()">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick View Modal Container -->
    <div id="quickViewModal" class="modal-backdrop" style="display: none;">
        <div class="modal-dialog modal-quick-view">
            <button class="modal-close-btn" id="quickViewCloseBtn" aria-label="Close modal">&times;</button>
            <div class="modal-body" id="quickViewContent">
                <!-- Injected via AJAX -->
            </div>
        </div>
    </div>


