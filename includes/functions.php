<?php
// User authentication functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Product functions
function getFeaturedProducts($limit = 8) {
    global $pdo;
    $limit = (int)$limit;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE featured = 1 AND status = 'active' LIMIT $limit");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProducts($category = null, $limit = 12, $offset = 0, $sort = 'name') {
    global $pdo;
    
    // Cast to integers to avoid SQL syntax errors
    $limit = (int)$limit;
    $offset = (int)$offset;
    
    // Define sort options
    $sortOptions = [
        'name' => 'name ASC',
        'price_low' => 'price ASC',
        'price_high' => 'price DESC',
        'newest' => 'created_at DESC',
        'featured' => 'featured DESC, name ASC'
    ];
    
    $orderBy = isset($sortOptions[$sort]) ? $sortOptions[$sort] : $sortOptions['name'];
    
    if ($category) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = (SELECT id FROM categories WHERE slug = ?) AND status = 'active' ORDER BY $orderBy LIMIT $limit OFFSET $offset");
        $stmt->execute([$category]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE status = 'active' ORDER BY $orderBy LIMIT $limit OFFSET $offset");
        $stmt->execute();
    }
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProduct($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND status = 'active'");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function searchProducts($query, $limit = 12) {
    global $pdo;
    $limit = (int)$limit;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE (name LIKE ? OR description LIKE ?) AND status = 'active' LIMIT $limit");
    $searchTerm = "%$query%";
    $stmt->execute([$searchTerm, $searchTerm]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Cart functions
function getCartCount() {
    if (!isset($_SESSION['cart'])) {
        return 0;
    }
    return array_sum($_SESSION['cart']);
}

function addToCart($productId, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

function updateCartQuantity($productId, $quantity) {
    if ($quantity <= 0) {
        removeFromCart($productId);
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

function getCartItems() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return [];
    }
    
    global $pdo;
    $productIds = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($productIds) - 1) . '?';
    
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($productIds);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $cartItems = [];
    foreach ($products as $product) {
        $cartItems[] = [
            'product' => $product,
            'quantity' => $_SESSION['cart'][$product['id']]
        ];
    }
    
    return $cartItems;
}

function getCartTotal() {
    $cartItems = getCartItems();
    $total = 0;
    
    foreach ($cartItems as $item) {
        $total += $item['product']['price'] * $item['quantity'];
    }
    
    return $total;
}

// User functions
function registerUser($username, $email, $password) {
    global $pdo;
    
    // Check if user already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        return false;
    }
    
    // Hash password and insert user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
    return $stmt->execute([$username, $email, $hashedPassword]);
}

function loginUser($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
    }
    
    return false;
}

function getUser($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createOrder(
    $userId, 
    $cartItems, 
    $finalTotal, 
    $shippingAddress, 
    $billingAddress, 
    $paymentMethod, 
    $subtotal,
    $tax,
    $shippingCost,
    $discount
) {
    global $pdo;
    
    
    try {
        $pdo->beginTransaction();

        $orderNumber = 'ORD' . time() . mt_rand(1000, 9999);
        $orderStatus = 'pending'; 
        $paymentStatus = 'pending';
        
        $sql = "INSERT INTO orders (
                    user_id, order_number, total, subtotal, tax_amount, 
                    shipping_amount, discount_amount, shipping_address, 
                    billing_address, payment_method, payment_status, status
                ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $userId, 
            $orderNumber, 
            $finalTotal, 
            $subtotal, 
            $tax, 
            $shippingCost, 
            $discount,
            $shippingAddress, 
            $billingAddress, 
            $paymentMethod, 
            $paymentStatus, 
            $orderStatus
        ]);
        
        $orderId = $pdo->lastInsertId();

        
        $itemSql = "INSERT INTO order_items (order_id, product_id, quantity, price, total) VALUES (?, ?, ?, ?, ?)";
        $itemStmt = $pdo->prepare($itemSql);
        
        foreach ($cartItems as $item) {
            $productId = $item['product']['id'];
            $quantity = $item['quantity'];
            $price = $item['product']['price']; 
            $itemTotal = $price * $quantity;
            
            $itemStmt->execute([
                $orderId,
                $productId,
                $quantity,
                $price,
                $itemTotal
            ]);
           
        }

      
        $pdo->commit();
        return $orderId;

    } catch (PDOException $e) {
       
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
       
        return false;
    }
}

function getUserOrders($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Additional product functions
function getTotalProducts($category = null) {
    global $pdo;
    
    if ($category) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = (SELECT id FROM categories WHERE slug = ?) AND status = 'active'");
        $stmt->execute([$category]);
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE status = 'active'");
        $stmt->execute();
    }
    
    return $stmt->fetchColumn();
}

function getCategories() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY name");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRelatedProducts($categoryId, $productId, $limit = 4) {
    global $pdo;
    $limit = (int)$limit;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? AND status = 'active' LIMIT $limit");
    $stmt->execute([$categoryId, $productId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductReviews($productId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT r.*, u.username 
        FROM reviews r 
        LEFT JOIN users u ON r.user_id = u.id 
        WHERE r.product_id = ? AND r.status = 'approved' 
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$productId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAverageRating($productId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT AVG(rating) FROM reviews WHERE product_id = ? AND status = 'approved'");
    $stmt->execute([$productId]);
    $avg = $stmt->fetchColumn();
    return $avg ? round($avg, 1) : 0;
}

function getCoupon($code) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM coupons WHERE code = ? AND status = 'active' AND (valid_from IS NULL OR valid_from <= NOW()) AND (valid_until IS NULL OR valid_until >= NOW())");
    $stmt->execute([$code]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Wishlist functions
function addToWishlist($userId, $productId) {
    global $pdo;
    
    // Check if already in wishlist
    $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$userId, $productId]);
    if ($stmt->fetch()) {
        return false; // Already in wishlist
    }
    
    // Add to wishlist
    $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id, created_at) VALUES (?, ?, NOW())");
    return $stmt->execute([$userId, $productId]);
}

function removeFromWishlist($userId, $productId) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    return $stmt->execute([$userId, $productId]);
}

function getWishlistItems($userId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT p.*, w.created_at as added_date
        FROM wishlist w
        JOIN products p ON w.product_id = p.id
        WHERE w.user_id = ? AND p.status = 'active'
        ORDER BY w.created_at DESC
    ");
    $stmt->execute([$userId]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $wishlistItems = [];
    foreach ($products as $product) {
        $wishlistItems[] = [
            'product' => $product,
            'added_date' => $product['added_date']
        ];
    }
    
    return $wishlistItems;
}

function isInWishlist($userId, $productId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$userId, $productId]);
    return $stmt->fetch() ? true : false;
}

function getWishlistCount($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn();
}

// Utility functions
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function flash($message, $type = 'info') {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
?>
