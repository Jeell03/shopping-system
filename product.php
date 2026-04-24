<?php
ob_start();
session_start();
include 'config/database.php';
include 'includes/functions.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = getProduct($productId);

if (!$product) {
    header('Location: products.php');
    exit();
}

// Get related products
$relatedProducts = getRelatedProducts($product['category_id'], $product['id'], 4);

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = (int)$_POST['quantity'];
    if ($quantity > 0 && $product['stock_quantity'] >= $quantity) {
        addToCart($productId, $quantity);
        flash('Product added to cart successfully!', 'success');
    } else {
        flash('Invalid quantity or insufficient stock', 'error');
    }
}

// Get product reviews
$reviews = getProductReviews($productId);
$averageRating = getAverageRating($productId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - ShopEasy</title>
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
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="profile.php" class="user-link">
                                <i class="fas fa-user"></i>
                                <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <a href="logout.php" class="logout-link">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="login-link">Login</a>
                            <a href="register.php" class="register-link">Register</a>
                        <?php endif; ?>
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
            <a href="products.php">Products</a> > 
            <span><?php echo htmlspecialchars($product['name']); ?></span>
        </div>
    </div>

    <!-- Product Details -->
    <section class="product-details">
        <div class="container">
            <div class="product-detail-content">
                <div class="product-images">
                    <div class="main-image">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" id="main-image">
                    </div>
                    <?php if ($product['gallery']): ?>
                        <?php $gallery = json_decode($product['gallery'], true); ?>
                        <div class="image-thumbnails">
                            <?php foreach ($gallery as $image): ?>
                                <img src="<?php echo $image; ?>" alt="Product image" onclick="changeMainImage('<?php echo $image; ?>')">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="product-info">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    
                    <!-- Rating -->
                    <div class="product-rating">
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $averageRating ? 'active' : ''; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-text">(<?php echo count($reviews); ?> reviews)</span>
                    </div>
                    
                    <!-- Price -->
                    <div class="product-price">
                        <?php if ($product['sale_price']): ?>
                            <span class="sale-price">$<?php echo number_format($product['sale_price'], 2); ?></span>
                            <span class="original-price">$<?php echo number_format($product['price'], 2); ?></span>
                            <span class="discount">Save $<?php echo number_format($product['price'] - $product['sale_price'], 2); ?></span>
                        <?php else: ?>
                            <span class="price">$<?php echo number_format($product['price'], 2); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Stock Status -->
                    <div class="stock-status">
                        <?php if ($product['stock_quantity'] > 0): ?>
                            <span class="in-stock">
                                <i class="fas fa-check-circle"></i>
                                In Stock (<?php echo $product['stock_quantity']; ?> available)
                            </span>
                        <?php else: ?>
                            <span class="out-of-stock">
                                <i class="fas fa-times-circle"></i>
                                Out of Stock
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Description -->
                    <div class="product-description">
                        <h3>Description</h3>
                        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    </div>
                    
                    <!-- Add to Cart Form -->
                    <form method="POST" class="add-to-cart-form">
                        <div class="quantity-selector">
                            <label for="quantity">Quantity:</label>
                            <div class="quantity-controls">
                                <button type="button" onclick="decreaseQuantity()">-</button>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                                <button type="button" onclick="increaseQuantity()">+</button>
                            </div>
                        </div>
                        
                        <div class="product-actions">
                            <button type="submit" name="add_to_cart" class="btn btn-primary btn-large" 
                                    <?php echo $product['stock_quantity'] <= 0 ? 'disabled' : ''; ?>>
                                <i class="fas fa-shopping-cart"></i>
                                <?php echo $product['stock_quantity'] <= 0 ? 'Out of Stock' : 'Add to Cart'; ?>
                            </button>
                            
                            <button type="button" class="btn btn-outline btn-large" onclick="toggleWishlist(<?php echo $product['id']; ?>)">
                                <i class="fas fa-heart" id="wishlist-icon-<?php echo $product['id']; ?>"></i>
                                <span id="wishlist-text-<?php echo $product['id']; ?>">
                                    <?php echo isLoggedIn() && isInWishlist($_SESSION['user_id'], $product['id']) ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>
                                </span>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Product Features -->
                    <div class="product-features">
                        <h3>Features</h3>
                        <ul>
                            <li><i class="fas fa-shipping-fast"></i> Free shipping on orders over $50</li>
                            <li><i class="fas fa-undo"></i> 30-day return policy</li>
                            <li><i class="fas fa-shield-alt"></i> 1-year warranty</li>
                            <li><i class="fas fa-headset"></i> 24/7 customer support</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Tabs -->
    <section class="product-tabs">
        <div class="container">
            <div class="tabs">
                <button class="tab-button active" onclick="showTab('description')">Description</button>
                <button class="tab-button" onclick="showTab('specifications')">Specifications</button>
                <button class="tab-button" onclick="showTab('reviews')">Reviews (<?php echo count($reviews); ?>)</button>
            </div>
            
            <div class="tab-content">
                <div id="description" class="tab-panel active">
                    <h3>Product Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
                
                <div id="specifications" class="tab-panel">
                    <h3>Specifications</h3>
                    <table class="specifications-table">
                        <tr>
                            <td>SKU</td>
                            <td><?php echo htmlspecialchars($product['sku']); ?></td>
                        </tr>
                        <tr>
                            <td>Category</td>
                            <td><?php echo ucfirst($product['category_id']); ?></td>
                        </tr>
                        <?php if ($product['weight']): ?>
                        <tr>
                            <td>Weight</td>
                            <td><?php echo $product['weight']; ?> lbs</td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($product['dimensions']): ?>
                        <tr>
                            <td>Dimensions</td>
                            <td><?php echo htmlspecialchars($product['dimensions']); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
                
                <div id="reviews" class="tab-panel">
                    <h3>Customer Reviews</h3>
                    <?php if (empty($reviews)): ?>
                        <p>No reviews yet. Be the first to review this product!</p>
                    <?php else: ?>
                        <div class="reviews-summary">
                            <div class="average-rating">
                                <span class="rating-number"><?php echo number_format($averageRating, 1); ?></span>
                                <div class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo $i <= $averageRating ? 'active' : ''; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <span>Based on <?php echo count($reviews); ?> reviews</span>
                            </div>
                        </div>
                        
                        <div class="reviews-list">
                            <?php foreach ($reviews as $review): ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <strong><?php echo htmlspecialchars($review['username']); ?></strong>
                                        <div class="review-rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'active' : ''; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <span class="review-date"><?php echo date('M j, Y', strtotime($review['created_at'])); ?></span>
                                </div>
                                <?php if ($review['title']): ?>
                                    <h4><?php echo htmlspecialchars($review['title']); ?></h4>
                                <?php endif; ?>
                                <p><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isLoggedIn()): ?>
                        <div class="add-review">
                            <h4>Write a Review</h4>
                            <form method="POST" action="ajax/add_review.php">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <div class="form-group">
                                    <label>Rating</label>
                                    <div class="rating-input">
                                        <input type="radio" name="rating" value="5" id="star5">
                                        <label for="star5"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" value="4" id="star4">
                                        <label for="star4"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" value="3" id="star3">
                                        <label for="star3"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" value="2" id="star2">
                                        <label for="star2"><i class="fas fa-star"></i></label>
                                        <input type="radio" name="rating" value="1" id="star1">
                                        <label for="star1"><i class="fas fa-star"></i></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="review_title">Title</label>
                                    <input type="text" id="review_title" name="title" required>
                                </div>
                                <div class="form-group">
                                    <label for="review_comment">Comment</label>
                                    <textarea id="review_comment" name="comment" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <p><a href="login.php">Login</a> to write a review</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
    <section class="related-products">
        <div class="container">
            <h2>Related Products</h2>
            <div class="products-grid">
                <?php foreach ($relatedProducts as $relatedProduct): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo $relatedProduct['image']; ?>" alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>">
                        <div class="product-overlay">
                            <a href="product.php?id=<?php echo $relatedProduct['id']; ?>" class="btn btn-outline">View Details</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($relatedProduct['name']); ?></h3>
                        <div class="product-price">
                            <?php if ($relatedProduct['sale_price']): ?>
                                <span class="sale-price">$<?php echo number_format($relatedProduct['sale_price'], 2); ?></span>
                                <span class="original-price">$<?php echo number_format($relatedProduct['price'], 2); ?></span>
                            <?php else: ?>
                                <span class="price">$<?php echo number_format($relatedProduct['price'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                        <button class="btn btn-primary add-to-cart" data-product-id="<?php echo $relatedProduct['id']; ?>">
                            Add to Cart
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

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
        function changeMainImage(imageSrc) {
            document.getElementById('main-image').src = imageSrc;
        }
        
        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const max = parseInt(quantityInput.getAttribute('max'));
            const current = parseInt(quantityInput.value);
            if (current < max) {
                quantityInput.value = current + 1;
            }
        }
        
        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const current = parseInt(quantityInput.value);
            if (current > 1) {
                quantityInput.value = current - 1;
            }
        }
        
        function showTab(tabName) {
            // Hide all tab panels
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });
            
            // Show selected tab panel
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }
        
        function toggleWishlist(productId) {
            if (!<?php echo isLoggedIn() ? 'true' : 'false'; ?>) {
                window.location.href = 'login.php';
                return;
            }
            
            const icon = document.getElementById('wishlist-icon-' + productId);
            const text = document.getElementById('wishlist-text-' + productId);
            const isInWishlist = icon.classList.contains('active');
            
            const url = isInWishlist ? 'ajax/remove_from_wishlist.php' : 'ajax/add_to_wishlist.php';
            const action = isInWishlist ? 'remove' : 'add';
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (action === 'add') {
                        icon.classList.add('active');
                        text.textContent = 'Remove from Wishlist';
                        showNotification('Added to wishlist!', 'success');
                    } else {
                        icon.classList.remove('active');
                        text.textContent = 'Add to Wishlist';
                        showNotification('Removed from wishlist', 'success');
                    }
                } else {
                    showNotification(data.message || 'Error updating wishlist', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error updating wishlist', 'error');
            });
        }
    </script>
</body>
</html>
