<?php
$adminTitle = 'Manage Products';
$activeMenu = 'products';

require_once 'header.php';

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

// Handle product deletion
$msg = '';
if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    try {
        $del = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $del->execute([$deleteId]);
        $msg = 'Product #' . $deleteId . ' deleted successfully.';
    } catch (Exception $e) {
        $msg = 'Could not delete product. It may be part of previous customer orders.';
    }
}

// Query products
$sql = "
    SELECT p.*, c.name AS category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE 1=1
";
$args = [];

if ($search) {
    $sql .= " AND (p.name LIKE ? OR p.sku LIKE ?)";
    $args[] = "%$search%";
    $args[] = "%$search%";
}

if ($category) {
    $sql .= " AND c.slug = ?";
    $args[] = $category;
}

$sql .= " ORDER BY p.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($args);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories = getCategories();
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h1 style="font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 700; color: #0f172a;">Manage Store Catalog</h1>
        <p style="font-size: 13px; color: #64748b;">Add, edit pricing, update stock, or remove products from catalog</p>
    </div>
    <a href="product-form.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Product
    </a>
</div>

<?php if ($msg): ?>
    <div style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($msg); ?>
    </div>
<?php endif; ?>

<!-- Search & Filter Controls -->
<div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 16px 20px; margin-bottom: 20px; display: flex; gap: 14px; flex-wrap: wrap;">
    <form action="products.php" method="GET" style="display: flex; gap: 12px; flex: 1;">
        <input type="text" name="search" placeholder="Search by name or SKU..." value="<?php echo htmlspecialchars($search); ?>" style="flex: 1; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
        
        <select name="category" style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: #fff;">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo htmlspecialchars($cat['slug']); ?>" <?php echo $category === $cat['slug'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <?php if ($search || $category): ?>
            <a href="products.php" class="btn btn-outline btn-sm">Clear</a>
        <?php endif; ?>
    </form>
</div>

<!-- Products Table -->
<div class="admin-table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 60px;">Image</th>
                <th>Product Details</th>
                <th>Category</th>
                <th>Price / Sale Price</th>
                <th>Stock Units</th>
                <th>Featured</th>
                <th>Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 30px; color: #94a3b8;">No products found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                <?php $pImg = getProductImageUrl($p['image']); ?>
                <tr>
                    <td>
                        <img src="../<?php echo $pImg; ?>" alt="" style="width: 44px; height: 44px; object-fit: contain; border-radius: 6px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 2px;">
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($p['name']); ?></strong>
                        <div style="font-size: 11px; color: #64748b;">SKU: <?php echo htmlspecialchars($p['sku'] ?? 'N/A'); ?></div>
                    </td>
                    <td><?php echo htmlspecialchars($p['category_name'] ?? 'Uncategorized'); ?></td>
                    <td>
                        <strong>$<?php echo number_format($p['price'], 2); ?></strong>
                        <?php if ($p['sale_price']): ?>
                            <div style="font-size: 11px; color: #10b981; font-weight: 700;">Sale: $<?php echo number_format($p['sale_price'], 2); ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span style="display: inline-block; font-weight: 700; color: <?php echo $p['stock_quantity'] > 10 ? '#10b981' : ($p['stock_quantity'] > 0 ? '#f59e0b' : '#ef4444'); ?>;">
                            <?php echo $p['stock_quantity']; ?> in stock
                        </span>
                    </td>
                    <td>
                        <?php if ($p['featured']): ?>
                            <span style="color: #f59e0b; font-weight: 700;"><i class="fas fa-star"></i> Featured</span>
                        <?php else: ?>
                            <span style="color: #94a3b8;">Standard</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span style="text-transform: capitalize; font-size: 12px; font-weight: 600; color: <?php echo $p['status'] === 'active' ? '#10b981' : '#94a3b8'; ?>;">
                            &bull; <?php echo htmlspecialchars($p['status']); ?>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 8px;">
                            <a href="../product.php?id=<?php echo $p['id']; ?>" target="_blank" class="btn btn-outline btn-sm" title="View in Store">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <a href="product-form.php?id=<?php echo $p['id']; ?>" class="btn btn-outline btn-sm" title="Edit Product">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="products.php?delete=<?php echo $p['id']; ?>" class="btn btn-outline btn-sm" style="color: #ef4444; border-color: #fca5a5;" onclick="return confirm('Are you sure you want to delete this product?');" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'footer.php'; ?>
