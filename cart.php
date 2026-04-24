<?php
ob_start();
session_start();
include 'config/database.php';
include 'includes/functions.php';

$cartItems = getCartItems();
$cartTotal = getCartTotal();

// Handle cart updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantity'] as $productId => $quantity) {
            updateCartQuantity($productId, (int)$quantity);
        }
        redirect('cart.php');
    } elseif (isset($_POST['remove_item'])) {
        $productId = (int)$_POST['product_id'];
        removeFromCart($productId);
        redirect('cart.php');
    } elseif (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = [];
        redirect('cart.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - ShopEasy</title>
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
                        <a href="cart.php" class="cart-link active">
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
            <span>Shopping Cart</span>
        </div>
    </div>

    <!-- Cart Section -->
    <section class="cart-section">
        <div class="container">
            <h1>Shopping Cart</h1>
            
            <?php if (empty($cartItems)): ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h2>Your cart is empty</h2>
                    <p>Looks like you haven't added any items to your cart yet.</p>
                    <a href="products.php" class="btn btn-primary">Continue Shopping</a>
                </div>
            <?php else: ?>
                <form method="POST" class="cart-form">
                    <div class="cart-items">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="cart-item" data-product-id="<?php echo $item['product']['id']; ?>">
                                <div class="cart-item-image">
                                    <img src="<?php echo $item['product']['image']; ?>" alt="<?php echo htmlspecialchars($item['product']['name']); ?>">
                                </div>
                                
                                <div class="cart-item-info">
                                    <h3><?php echo htmlspecialchars($item['product']['name']); ?></h3>
                                    <p class="cart-item-price">$<?php echo number_format($item['product']['price'], 2); ?></p>
                                    
                                    <div class="quantity-controls">
                                        <label for="quantity_<?php echo $item['product']['id']; ?>">Quantity:</label>
                                        <button type="button" onclick="decreaseQuantity(<?php echo $item['product']['id']; ?>)">-</button>
                                        <input type="number" 
                                               id="quantity_<?php echo $item['product']['id']; ?>" 
                                               name="quantity[<?php echo $item['product']['id']; ?>]" 
                                               value="<?php echo $item['quantity']; ?>" 
                                               min="1" 
                                               max="<?php echo $item['product']['stock_quantity']; ?>"
                                               onchange="updateCartItem(<?php echo $item['product']['id']; ?>)">
                                        <button type="button" onclick="increaseQuantity(<?php echo $item['product']['id']; ?>)">+</button>
                                    </div>
                                    
                                    <div class="item-total">
                                        Total: $<?php echo number_format($item['product']['price'] * $item['quantity'], 2); ?>
                                    </div>
                                </div>
                                
                                <div class="cart-item-actions">
                                    <button type="button" 
                                            class="btn btn-outline btn-small remove-item-btn" 
                                            data-product-id="<?php echo $item['product']['id']; ?>"
                                            onclick="removeCartItem(<?php echo $item['product']['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                        Remove
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="cart-actions">
                        <button type="submit" name="update_cart" class="btn btn-outline">
                            <i class="fas fa-sync"></i>
                            Update Cart
                        </button>
                        
                        <button type="submit" name="clear_cart" class="btn btn-outline" 
                                onclick="return confirm('Clear all items from cart?')">
                            <i class="fas fa-trash-alt"></i>
                            Clear Cart
                        </button>
                    </div>
                </form>
                
                <div class="cart-summary">
                    <div class="summary-card">
                        <h3>Order Summary</h3>
                        
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>$<?php echo number_format($cartTotal, 2); ?></span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span><?php echo $cartTotal >= 50 ? 'FREE' : '$9.99'; ?></span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Tax:</span>
                            <span>$<?php echo number_format($cartTotal * 0.08, 2); ?></span>
                        </div>
                        
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span>$<?php echo number_format($cartTotal + ($cartTotal >= 50 ? 0 : 9.99) + ($cartTotal * 0.08), 2); ?></span>
                        </div>
                        
                        <div class="checkout-actions">
                            <a href="checkout.php" class="btn btn-primary btn-large">
                                <i class="fas fa-credit-card"></i>
                                Proceed to Checkout
                            </a>
                            
                            <a href="products.php" class="btn btn-outline">
                                <i class="fas fa-arrow-left"></i>
                                Continue Shopping
                            </a>
                        </div>
                        
                        <?php if ($cartTotal < 50): ?>
                            <div class="shipping-notice">
                                <i class="fas fa-info-circle"></i>
                                Add $<?php echo number_format(50 - $cartTotal, 2); ?> more for free shipping!
                            </div>
                        <?php endif; ?>
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
        function increaseQuantity(productId) {
            const input = document.getElementById('quantity_' + productId);
            const max = parseInt(input.getAttribute('max'));
            const current = parseInt(input.value);
            if (current < max) {
                input.value = current + 1;
                updateCartItem(productId);
            }
        }
        
        function decreaseQuantity(productId) {
            const input = document.getElementById('quantity_' + productId);
            const current = parseInt(input.value);
            if (current > 1) {
                input.value = current - 1;
                updateCartItem(productId);
            }
        }
        
        function updateCartItem(productId) {
            const input = document.getElementById('quantity_' + productId);
            const quantity = parseInt(input.value);
            
            if (quantity < 1) {
                if (confirm('Remove this item from cart?')) {
                    removeCartItem(productId);
                } else {
                    input.value = 1;
                }
            } else {
                // Update quantity via AJAX
                fetch('ajax/update_cart_quantity.php', {
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
                        updateCartTotal(data.cart_total);
                        // Update the item total display
                        const itemTotal = document.querySelector(`[data-product-id="${productId}"]`).closest('.cart-item').querySelector('.item-total');
                        if (itemTotal) {
                            const price = parseFloat(itemTotal.textContent.replace('Total: $', ''));
                            const newTotal = price * quantity;
                            itemTotal.textContent = `Total: $${newTotal.toFixed(2)}`;
                        }
                    } else {
                        showNotification(data.message || 'Error updating cart', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error updating cart', 'error');
                });
            }
        }
        
        function removeCartItem(productId) {
            if (confirm('Remove this item from cart?')) {
                fetch('ajax/remove_from_cart.php', {
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
                        updateCartCount(data.cart_count);
                        // Remove the cart item from the DOM
                        const cartItem = document.querySelector(`[data-product-id="${productId}"]`).closest('.cart-item');
                        if (cartItem) {
                            cartItem.style.transform = 'scale(0.8)';
                            cartItem.style.opacity = '0';
                            setTimeout(() => {
                                cartItem.remove();
                                // Check if cart is empty
                                const remainingItems = document.querySelectorAll('.cart-item');
                                if (remainingItems.length === 0) {
                                    location.reload(); // Reload to show empty state
                                }
                            }, 300);
                        }
                        showNotification('Item removed from cart', 'success');
                    } else {
                        showNotification(data.message || 'Error removing item from cart', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error removing item from cart', 'error');
                });
            }
        }
        
        function updateCartCount(count) {
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = count;
            }
        }
        
        function updateCartTotal(total) {
            const cartTotal = document.querySelector('.cart-total h3');
            if (cartTotal) {
                cartTotal.textContent = `Total: $${total.toFixed(2)}`;
            }
        }
        
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            
            // Style the notification
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 5px;
                color: white;
                font-weight: 600;
                z-index: 10000;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
            `;
            
            // Set background color based on type
            switch(type) {
                case 'success':
                    notification.style.backgroundColor = '#27ae60';
                    break;
                case 'error':
                    notification.style.backgroundColor = '#e74c3c';
                    break;
                case 'warning':
                    notification.style.backgroundColor = '#f39c12';
                    break;
                default:
                    notification.style.backgroundColor = '#3498db';
            }
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>




