<?php
// Session check
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// User authentication functions
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function isAdmin() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ../login.php?admin=1');
        exit();
    }
}

// Image handling helper
function getProductImageUrl($path) {
    if (empty($path)) {
        return 'assets/images/placeholder.svg';
    }
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return htmlspecialchars($path);
    }
    $trimmed = trim($path);
    return htmlspecialchars($trimmed);
}

// Product functions
function getFeaturedProducts($limit = 8) {
    global $pdo;
    $limit = (int)$limit;
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug,
               COALESCE(AVG(r.rating), 0) AS avg_rating,
               COUNT(r.id) AS review_count
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN reviews r ON p.id = r.product_id AND r.status = 'approved'
        WHERE p.featured = 1 AND p.status = 'active'
        GROUP BY p.id
        ORDER BY p.id ASC
        LIMIT $limit
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDealsOfDay($limit = 4) {
    global $pdo;
    $limit = (int)$limit;
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug,
               COALESCE(AVG(r.rating), 0) AS avg_rating,
               COUNT(r.id) AS review_count,
               ROUND(((p.price - p.sale_price) / p.price) * 100) AS discount_pct
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN reviews r ON p.id = r.product_id AND r.status = 'approved'
        WHERE p.sale_price IS NOT NULL AND p.sale_price > 0 AND p.sale_price < p.price AND p.status = 'active'
        GROUP BY p.id
        ORDER BY discount_pct DESC, p.id ASC
        LIMIT $limit
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getNewArrivals($limit = 8) {
    global $pdo;
    $limit = (int)$limit;
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug,
               COALESCE(AVG(r.rating), 0) AS avg_rating,
               COUNT(r.id) AS review_count
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN reviews r ON p.id = r.product_id AND r.status = 'approved'
        WHERE p.status = 'active'
        GROUP BY p.id
        ORDER BY p.created_at DESC
        LIMIT $limit
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTopRatedProducts($limit = 4) {
    global $pdo;
    $limit = (int)$limit;
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug,
               COALESCE(AVG(r.rating), 0) AS avg_rating,
               COUNT(r.id) AS review_count
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN reviews r ON p.id = r.product_id AND r.status = 'approved'
        WHERE p.status = 'active'
        GROUP BY p.id
        HAVING avg_rating >= 4.0
        ORDER BY avg_rating DESC, review_count DESC
        LIMIT $limit
    ");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($results)) {
        return getFeaturedProducts($limit);
    }
    return $results;
}

function getProduct($id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug,
               COALESCE(AVG(r.rating), 0) AS avg_rating,
               COUNT(r.id) AS review_count
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN reviews r ON p.id = r.product_id AND r.status = 'approved'
        WHERE p.id = ? AND p.status = 'active'
        GROUP BY p.id
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getProductForAdmin($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getCategories() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT c.*, COUNT(p.id) AS product_count FROM categories c LEFT JOIN products p ON c.id = p.category_id AND p.status = 'active' GROUP BY c.id ORDER BY c.name ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Multi-attribute Filter Function
function getFilteredProductsAdvanced($params = []) {
    global $pdo;

    $category    = $params['category'] ?? null;
    $minPrice    = isset($params['min_price']) && is_numeric($params['min_price']) ? (float)$params['min_price'] : 0;
    $maxPrice    = isset($params['max_price']) && is_numeric($params['max_price']) ? (float)$params['max_price'] : 0;
    $minRating   = isset($params['rating']) && is_numeric($params['rating']) ? (float)$params['rating'] : 0;
    $inStockOnly = !empty($params['in_stock']);
    $onSaleOnly  = !empty($params['on_sale']);
    $sort        = $params['sort'] ?? 'featured';
    $limit       = isset($params['limit']) ? (int)$params['limit'] : 12;
    $offset      = isset($params['offset']) ? (int)$params['offset'] : 0;
    $search      = $params['search'] ?? null;

    $where = ["p.status = 'active'"];
    $args = [];

    if (!empty($category)) {
        $where[] = "c.slug = ?";
        $args[] = $category;
    }

    if ($minPrice > 0) {
        $where[] = "COALESCE(p.sale_price, p.price) >= ?";
        $args[] = $minPrice;
    }

    if ($maxPrice > 0) {
        $where[] = "COALESCE(p.sale_price, p.price) <= ?";
        $args[] = $maxPrice;
    }

    if ($inStockOnly) {
        $where[] = "p.stock_quantity > 0";
    }

    if ($onSaleOnly) {
        $where[] = "p.sale_price IS NOT NULL AND p.sale_price > 0 AND p.sale_price < p.price";
    }

    if (!empty($search)) {
        $where[] = "(p.name LIKE ? OR p.description LIKE ? OR p.short_description LIKE ?)";
        $searchTerm = "%$search%";
        $args[] = $searchTerm;
        $args[] = $searchTerm;
        $args[] = $searchTerm;
    }

    $having = [];
    if ($minRating > 0) {
        $having[] = "avg_rating >= " . (float)$minRating;
    }

    $sortOptions = [
        'price_low'  => 'effective_price ASC',
        'price_high' => 'effective_price DESC',
        'newest'     => 'p.created_at DESC',
        'rating'     => 'avg_rating DESC, review_count DESC',
        'name_asc'   => 'p.name ASC',
        'name_desc'  => 'p.name DESC',
        'featured'   => 'p.featured DESC, p.id ASC'
    ];

    $orderBy = $sortOptions[$sort] ?? $sortOptions['featured'];

    $whereSql = implode(" AND ", $where);
    $havingSql = !empty($having) ? "HAVING " . implode(" AND ", $having) : "";

    $sql = "
        SELECT p.*, c.name AS category_name, c.slug AS category_slug,
               COALESCE(p.sale_price, p.price) AS effective_price,
               COALESCE(AVG(r.rating), 0) AS avg_rating,
               COUNT(r.id) AS review_count
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN reviews r ON p.id = r.product_id AND r.status = 'approved'
        WHERE $whereSql
        GROUP BY p.id
        $havingSql
        ORDER BY $orderBy
        LIMIT $limit OFFSET $offset
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getFilteredProductsAdvancedCount($params = []) {
    global $pdo;

    $category    = $params['category'] ?? null;
    $minPrice    = isset($params['min_price']) && is_numeric($params['min_price']) ? (float)$params['min_price'] : 0;
    $maxPrice    = isset($params['max_price']) && is_numeric($params['max_price']) ? (float)$params['max_price'] : 0;
    $minRating   = isset($params['rating']) && is_numeric($params['rating']) ? (float)$params['rating'] : 0;
    $inStockOnly = !empty($params['in_stock']);
    $onSaleOnly  = !empty($params['on_sale']);
    $search      = $params['search'] ?? null;

    $where = ["p.status = 'active'"];
    $args = [];

    if (!empty($category)) {
        $where[] = "c.slug = ?";
        $args[] = $category;
    }
    if ($minPrice > 0) {
        $where[] = "COALESCE(p.sale_price, p.price) >= ?";
        $args[] = $minPrice;
    }
    if ($maxPrice > 0) {
        $where[] = "COALESCE(p.sale_price, p.price) <= ?";
        $args[] = $maxPrice;
    }
    if ($inStockOnly) {
        $where[] = "p.stock_quantity > 0";
    }
    if ($onSaleOnly) {
        $where[] = "p.sale_price IS NOT NULL AND p.sale_price > 0 AND p.sale_price < p.price";
    }
    if (!empty($search)) {
        $where[] = "(p.name LIKE ? OR p.description LIKE ?)";
        $searchTerm = "%$search%";
        $args[] = $searchTerm;
        $args[] = $searchTerm;
    }

    $whereSql = implode(" AND ", $where);
    $havingSql = ($minRating > 0) ? "HAVING COALESCE(AVG(r.rating), 0) >= " . (float)$minRating : "";

    $sql = "
        SELECT p.id, COALESCE(AVG(r.rating), 0) AS avg_rating
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN reviews r ON p.id = r.product_id AND r.status = 'approved'
        WHERE $whereSql
        GROUP BY p.id
        $havingSql
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    return count($stmt->fetchAll());
}

function searchProducts($query, $limit = 12) {
    return getFilteredProductsAdvanced([
        'search' => $query,
        'limit'  => $limit,
        'sort'   => 'featured'
    ]);
}

function getRelatedProducts($categoryId, $productId, $limit = 4) {
    global $pdo;
    $limit = (int)$limit;
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name,
               COALESCE(AVG(r.rating), 0) AS avg_rating,
               COUNT(r.id) AS review_count
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN reviews r ON p.id = r.product_id AND r.status = 'approved'
        WHERE p.category_id = ? AND p.id != ? AND p.status = 'active'
        GROUP BY p.id
        LIMIT $limit
    ");
    $stmt->execute([$categoryId, $productId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Cart functions
function getCartCount() {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        return 0;
    }
    return array_sum($_SESSION['cart']);
}

function addToCart($productId, $quantity = 1) {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $productId = (int)$productId;
    $quantity = max(1, (int)$quantity);

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

function removeFromCart($productId) {
    $productId = (int)$productId;
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

function updateCartQuantity($productId, $quantity) {
    $productId = (int)$productId;
    $quantity = (int)$quantity;
    if ($quantity <= 0) {
        removeFromCart($productId);
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

function clearCart() {
    $_SESSION['cart'] = [];
    unset($_SESSION['applied_coupon']);
}

function getCartItems() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return [];
    }
    global $pdo;
    $productIds = array_keys($_SESSION['cart']);
    if (empty($productIds)) {
        return [];
    }

    $placeholders = str_repeat('?,', count($productIds) - 1) . '?';
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.id IN ($placeholders)
    ");
    $stmt->execute($productIds);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $cartItems = [];
    foreach ($products as $product) {
        $pid = $product['id'];
        $qty = $_SESSION['cart'][$pid] ?? 0;
        if ($qty > 0) {
            $effectivePrice = ($product['sale_price'] && $product['sale_price'] > 0) ? (float)$product['sale_price'] : (float)$product['price'];
            $cartItems[] = [
                'product' => $product,
                'quantity' => $qty,
                'unit_price' => $effectivePrice,
                'subtotal' => $effectivePrice * $qty,
                'original_price' => (float)$product['price']
            ];
        }
    }
    return $cartItems;
}

function getCartBreakdown($couponCode = null) {
    $cartItems = getCartItems();
    $subtotal = 0;
    $mrpTotal = 0;

    foreach ($cartItems as $item) {
        $subtotal += $item['subtotal'];
        $mrpTotal += $item['original_price'] * $item['quantity'];
    }

    $couponCode = $couponCode ?? ($_SESSION['applied_coupon'] ?? null);
    $discount = 0;
    $appliedCoupon = null;

    if ($couponCode && $subtotal > 0) {
        $couponData = getCoupon($couponCode);
        if ($couponData) {
            if ($subtotal >= $couponData['minimum_amount']) {
                if ($couponData['discount_type'] === 'percentage') {
                    $discount = ($subtotal * $couponData['discount_value']) / 100;
                    if (!empty($couponData['maximum_discount']) && $couponData['maximum_discount'] > 0) {
                        $discount = min($discount, (float)$couponData['maximum_discount']);
                    }
                } else {
                    $discount = min((float)$couponData['discount_value'], $subtotal);
                }
                $appliedCoupon = $couponData;
                $_SESSION['applied_coupon'] = $couponCode;
            } else {
                unset($_SESSION['applied_coupon']);
            }
        } else {
            unset($_SESSION['applied_coupon']);
        }
    }

    // Free shipping threshold: $50
    $freeShippingThreshold = 50.00;
    $shipping = ($subtotal >= $freeShippingThreshold || $subtotal == 0) ? 0.00 : 10.00;
    $amountNeededForFreeShipping = max(0, $freeShippingThreshold - $subtotal);

    // Standard Tax estimated at 5%
    $taxable = max(0, $subtotal - $discount);
    $tax = round($taxable * 0.05, 2);

    $total = round($taxable + $shipping + $tax, 2);
    $totalSavings = round(($mrpTotal - $subtotal) + $discount, 2);

    return [
        'items' => $cartItems,
        'item_count' => getCartCount(),
        'mrp_total' => round($mrpTotal, 2),
        'subtotal' => round($subtotal, 2),
        'shipping' => $shipping,
        'free_shipping_threshold' => $freeShippingThreshold,
        'amount_needed_for_free_shipping' => round($amountNeededForFreeShipping, 2),
        'is_free_shipping' => ($shipping == 0 && $subtotal > 0),
        'tax' => $tax,
        'discount' => round($discount, 2),
        'total' => $total,
        'total_savings' => $totalSavings,
        'applied_coupon' => $appliedCoupon
    ];
}

function getCoupon($code) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT * FROM coupons 
        WHERE UPPER(code) = UPPER(?) 
          AND status = 'active' 
          AND (valid_from IS NULL OR valid_from <= NOW()) 
          AND (valid_until IS NULL OR valid_until >= NOW())
    ");
    $stmt->execute([trim($code)]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// User functions
function registerUser($username, $email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        return false;
    }
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, 'customer', NOW())");
    return $stmt->execute([$username, $email, $hashedPassword]);
}

function loginUser($username, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, username, email, password, role, first_name, last_name FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'] ?? 'customer';
        $_SESSION['full_name'] = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: $user['username'];
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

function updateUserProfile($id, $firstName, $lastName, $phone, $address, $city, $state, $zipCode, $country) {
    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE users 
        SET first_name = ?, last_name = ?, phone = ?, address = ?, city = ?, state = ?, zip_code = ?, country = ?, updated_at = NOW()
        WHERE id = ?
    ");
    return $stmt->execute([$firstName, $lastName, $phone, $address, $city, $state, $zipCode, $country, $id]);
}

// Orders
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

        $orderNumber = 'ORD-' . strtoupper(substr(uniqid(), 7)) . '-' . mt_rand(100, 999);
        $orderStatus = 'processing'; 
        $paymentStatus = ($paymentMethod === 'cod') ? 'pending' : 'paid';
        
        $sql = "INSERT INTO orders (
                    user_id, order_number, total, subtotal, tax_amount, 
                    shipping_amount, discount_amount, shipping_address, 
                    billing_address, payment_method, payment_status, status, created_at
                ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
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
        
        // Stock decrement statement
        $stockStmt = $pdo->prepare("UPDATE products SET stock_quantity = GREATEST(0, stock_quantity - ?) WHERE id = ?");

        foreach ($cartItems as $item) {
            $productId = $item['product']['id'];
            $quantity  = $item['quantity'];
            $price     = $item['unit_price']; 
            $itemTotal = $price * $quantity;
            
            $itemStmt->execute([$orderId, $productId, $quantity, $price, $itemTotal]);
            $stockStmt->execute([$quantity, $productId]);
        }

        $pdo->commit();
        return ['order_id' => $orderId, 'order_number' => $orderNumber];

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        return false;
    }
}

function getUserOrders($userId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT o.*, COUNT(oi.id) AS total_items 
        FROM orders o 
        LEFT JOIN order_items oi ON o.id = oi.order_id 
        WHERE o.user_id = ? 
        GROUP BY o.id 
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderDetails($orderId, $userId = null) {
    global $pdo;
    $query = "SELECT o.*, u.username, u.email, u.phone AS user_phone FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?";
    $args = [$orderId];
    if ($userId !== null && !isAdmin()) {
        $query .= " AND o.user_id = ?";
        $args[] = $userId;
    }
    $stmt = $pdo->prepare($query);
    $stmt->execute($args);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        return null;
    }

    $itemsStmt = $pdo->prepare("
        SELECT oi.*, p.name, p.image, p.sku, c.name AS category_name
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE oi.order_id = ?
    ");
    $itemsStmt->execute([$orderId]);
    $order['items'] = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    return $order;
}

function cancelOrder($orderId, $userId) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status IN ('pending', 'processing')");
    return $stmt->execute([$orderId, $userId]);
}

function updateOrderStatus($orderId, $status, $paymentStatus) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE orders SET status = ?, payment_status = ?, updated_at = NOW() WHERE id = ?");
    return $stmt->execute([$status, $paymentStatus, $orderId]);
}

// Reviews
function getProductReviews($productId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT r.*, u.username, u.first_name, u.last_name 
        FROM reviews r 
        LEFT JOIN users u ON r.user_id = u.id 
        WHERE r.product_id = ? AND r.status = 'approved' 
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$productId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRatingDistribution($productId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT rating, COUNT(*) AS cnt 
        FROM reviews 
        WHERE product_id = ? AND status = 'approved' 
        GROUP BY rating
    ");
    $stmt->execute([$productId]);
    $rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $dist = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
    $total = 0;
    foreach ($rows as $star => $count) {
        $dist[(int)$star] = (int)$count;
        $total += (int)$count;
    }

    $percentages = [];
    foreach ($dist as $star => $count) {
        $percentages[$star] = $total > 0 ? round(($count / $total) * 100) : 0;
    }

    return [
        'counts' => $dist,
        'percentages' => $percentages,
        'total' => $total
    ];
}

function submitReview($productId, $userId, $rating, $title, $comment) {
    global $pdo;
    $rating = max(1, min(5, (int)$rating));
    $stmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, rating, title, comment, status, created_at) VALUES (?, ?, ?, ?, ?, 'approved', NOW())");
    return $stmt->execute([$productId, $userId, $rating, sanitize($title), sanitize($comment)]);
}

// Wishlist functions
function addToWishlist($userId, $productId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$userId, $productId]);
    if ($stmt->fetch()) {
        return false;
    }
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
        SELECT p.*, w.created_at as added_date, c.name AS category_name,
               COALESCE(AVG(r.rating), 0) AS avg_rating,
               COUNT(r.id) AS review_count
        FROM wishlist w
        JOIN products p ON w.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN reviews r ON p.id = r.product_id AND r.status = 'approved'
        WHERE w.user_id = ? AND p.status = 'active'
        GROUP BY p.id
        ORDER BY w.created_at DESC
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function isInWishlist($userId, $productId) {
    global $pdo;
    if (!$userId) return false;
    $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$userId, $productId]);
    return (bool)$stmt->fetch();
}

function getWishlistCount($userId) {
    global $pdo;
    if (!$userId) return 0;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
    $stmt->execute([$userId]);
    return (int)$stmt->fetchColumn();
}

// Admin Functions
function getAdminStats() {
    global $pdo;

    $revenue = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'cancelled'")->fetchColumn();
    $ordersCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $productsCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $usersCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
    $pendingOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status IN ('pending', 'processing')")->fetchColumn();
    $lowStockCount = $pdo->query("SELECT COUNT(*) FROM products WHERE stock_quantity <= 10 AND status = 'active'")->fetchColumn();

    return [
        'total_revenue' => (float)$revenue,
        'total_orders'  => (int)$ordersCount,
        'total_products'=> (int)$productsCount,
        'total_users'   => (int)$usersCount,
        'pending_orders'=> (int)$pendingOrders,
        'low_stock'     => (int)$lowStockCount
    ];
}

function getRecentOrders($limit = 8) {
    global $pdo;
    $limit = (int)$limit;
    $stmt = $pdo->prepare("
        SELECT o.*, u.username, u.email 
        FROM orders o 
        LEFT JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC 
        LIMIT $limit
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLowStockProducts($threshold = 10) {
    global $pdo;
    $threshold = (int)$threshold;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE stock_quantity <= ? AND status = 'active' ORDER BY stock_quantity ASC LIMIT 10");
    $stmt->execute([$threshold]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Utility functions
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data ?? '')));
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
