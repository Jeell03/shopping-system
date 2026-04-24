<?php
ob_start();
session_start();
include 'config/database.php';
include 'includes/functions.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$userId = $_SESSION['user_id'];

// Handle wishlist actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_item'])) {
        $productId = (int)$_POST['product_id'];
        removeFromWishlist($userId, $productId);
        flash('Item removed from wishlist', 'success');
    } elseif (isset($_POST['add_to_cart'])) {
        $productId = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        addToCart($productId, $quantity);
        flash('Item added to cart successfully!', 'success');
    } elseif (isset($_POST['move_all_to_cart'])) {
        $wishlistItems = getWishlistItems($userId);
        $addedCount = 0;
        foreach ($wishlistItems as $item) {
            if ($item['product']['stock_quantity'] > 0) {
                addToCart($item['product']['id'], 1);
                $addedCount++;
            }
        }
        if ($addedCount > 0) {
            flash("$addedCount items added to cart!", 'success');
        } else {
            flash('No items available to add to cart', 'error');
        }
    }
}

// Get wishlist items
$wishlistItems = getWishlistItems($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1><a href="index.php">ShopEasy</a></h1>
                </div>
                
                <div class="search-bar">
                    <form action="search.php" method="GET">
                        <input type="text" name="query" placeholder="Search products..." required>
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                
                <div class="header-actions">
                    <div class="user-menu">
                        <a href="profile.php" class="user-link">
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <a href="logout.php" class="logout-link">Logout</a>
                    </div>
                    
                    <div class="cart">
                        <a href="cart.php" class="cart-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count"><?php echo getCartCount(); ?></span>
                        </a>
                    </div>
                </div>
            </div>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">All Products</a></li>
                    <li><a href="products.php?category=electronics">Electronics</a></li>
                    <li><a href="products.php?category=clothing">Clothing</a></li>
                    <li><a href="products.php?category=home">Home & Garden</a></li>
                    <li><a href="products.php?category=sports">Sports</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container">
            <a href="index.php">Home</a> > 
            <a href="profile.php">My Account</a> > 
            <span>My Wishlist</span>
        </div>
    </div>

    <!-- Wishlist Section -->
    <section class="wishlist-section">
        <div class="container">
            <div class="wishlist-header">
                <h1><i class="fas fa-heart"></i> My Wishlist</h1>
                <p>Save items you love for later</p>
            </div>

            <?php if (empty($wishlistItems)): ?>
                <div class="empty-wishlist">
                    <i class="fas fa-heart-broken"></i>
                    <h2>Your wishlist is empty</h2>
                    <p>Start adding items you love to your wishlist!</p>
                    <a href="products.php" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i>
                        Start Shopping
                    </a>
                </div>
            <?php else: ?>
                <div class="wishlist-actions">
                    <div class="wishlist-stats">
                        <span class="item-count"><?php echo count($wishlistItems); ?> items in wishlist</span>
                    </div>
                    <div class="wishlist-bulk-actions">
                        <form method="POST" style="display: inline;">
                            <button type="submit" name="move_all_to_cart" class="btn btn-primary" 
                                    onclick="return confirm('Add all available items to cart?')">
                                <i class="fas fa-shopping-cart"></i>
                                Add All to Cart
                            </button>
                        </form>
                        <a href="products.php" class="btn btn-outline">
                            <i class="fas fa-plus"></i>
                            Add More Items
                        </a>
                    </div>
                </div>

                <div class="wishlist-grid">
                    <?php foreach ($wishlistItems as $item): ?>
                        <div class="wishlist-item" data-product-id="<?php echo $item['product']['id']; ?>">
                            <div class="item-image">
                                <img src="<?php echo $item['product']['image']; ?>" 
                                     alt="<?php echo htmlspecialchars($item['product']['name']); ?>">
                                <div class="item-overlay">
                                    <a href="product.php?id=<?php echo $item['product']['id']; ?>" 
                                       class="btn btn-outline btn-small">
                                        <i class="fas fa-eye"></i>
                                        View Details
                                    </a>
                                </div>
                            </div>
                            
                            <div class="item-info">
                                <h3><?php echo htmlspecialchars($item['product']['name']); ?></h3>
                                <div class="item-price">
                                    <?php if ($item['product']['sale_price']): ?>
                                        <span class="sale-price">$<?php echo number_format($item['product']['sale_price'], 2); ?></span>
                                        <span class="original-price">$<?php echo number_format($item['product']['price'], 2); ?></span>
                                    <?php else: ?>
                                        <span class="price">$<?php echo number_format($item['product']['price'], 2); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="item-stock">
                                    <?php if ($item['product']['stock_quantity'] > 0): ?>
                                        <span class="in-stock">
                                            <i class="fas fa-check-circle"></i>
                                            In Stock (<?php echo $item['product']['stock_quantity']; ?>)
                                        </span>
                                    <?php else: ?>
                                        <span class="out-of-stock">
                                            <i class="fas fa-times-circle"></i>
                                            Out of Stock
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="item-actions">
                                    <?php if ($item['product']['stock_quantity'] > 0): ?>
                                        <form method="POST" class="add-to-cart-form">
                                            <input type="hidden" name="product_id" value="<?php echo $item['product']['id']; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" name="add_to_cart" class="btn btn-primary">
                                                <i class="fas fa-shopping-cart"></i>
                                                Add to Cart
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-outline" disabled>
                                            <i class="fas fa-times"></i>
                                            Out of Stock
                                        </button>
                                    <?php endif; ?>
                                    
                                    <form method="POST" class="remove-form">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product']['id']; ?>">
                                        <button type="submit" name="remove_item" class="btn btn-outline btn-remove" 
                                                onclick="return confirm('Remove from wishlist?')">
                                            <i class="fas fa-heart-broken"></i>
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="wishlist-footer">
                    <div class="wishlist-tips">
                        <h3><i class="fas fa-lightbulb"></i> Wishlist Tips</h3>
                        <ul>
                            <li>Items in your wishlist are saved for 30 days</li>
                            <li>You'll be notified if prices drop on wishlist items</li>
                            <li>Share your wishlist with friends and family</li>
                            <li>Move items to cart when you're ready to buy</li>
                        </ul>
                    </div>
                    
                    <div class="wishlist-share">
                        <h3><i class="fas fa-share-alt"></i> Share Your Wishlist</h3>
                        <p>Let others know what you're interested in</p>
                        <div class="share-buttons">
                            <button class="btn btn-outline" onclick="shareWishlist('facebook')">
                                <i class="fab fa-facebook"></i> Facebook
                            </button>
                            <button class="btn btn-outline" onclick="shareWishlist('twitter')">
                                <i class="fab fa-twitter"></i> Twitter
                            </button>
                            <button class="btn btn-outline" onclick="shareWishlist('email')">
                                <i class="fas fa-envelope"></i> Email
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>ShopEasy</h3>
                    <p>Your trusted online shopping destination for quality products at great prices.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="products.php">All Products</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="faq.php">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Customer Service</h4>
                    <ul>
                        <li><a href="shipping.php">Shipping Info</a></li>
                        <li><a href="returns.php">Returns</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                        <li><a href="terms.php">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Connect With Us</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 ShopEasy. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
    <script>
        function shareWishlist(platform) {
            const url = window.location.href;
            const title = 'Check out my wishlist on ShopEasy!';
            
            let shareUrl = '';
            
            switch(platform) {
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`;
                    break;
                case 'email':
                    shareUrl = `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent('Check out my wishlist: ' + url)}`;
                    break;
            }
            
            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        }
        
        // Add to cart with AJAX
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const productId = formData.get('product_id');
                const quantity = formData.get('quantity');
                
                fetch('ajax/add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartCount(data.cart_count);
                        showNotification('Item added to cart!', 'success');
                    } else {
                        showNotification(data.message || 'Error adding to cart', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error adding to cart', 'error');
                });
            });
        });
    </script>
</body>
</html>




