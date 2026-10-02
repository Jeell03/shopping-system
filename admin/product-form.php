<?php
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = ($productId > 0);

$adminTitle = $isEdit ? 'Edit Product' : 'Add New Product';
$activeMenu = $isEdit ? 'products' : 'add_product';

require_once 'header.php';

$product = null;
if ($isEdit) {
    $product = getProductForAdmin($productId);
    if (!$product) {
        header('Location: products.php');
        exit();
    }
}

$categories = getCategories();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name             = trim($_POST['name'] ?? '');
    $categoryId       = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $price            = (float)($_POST['price'] ?? 0);
    $salePrice        = !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null;
    $sku              = trim($_POST['sku'] ?? '');
    $stockQuantity    = (int)($_POST['stock_quantity'] ?? 0);
    $shortDescription = trim($_POST['short_description'] ?? '');
    $description      = trim($_POST['description'] ?? '');
    $image            = trim($_POST['image'] ?? '');
    $featured         = isset($_POST['featured']) ? 1 : 0;
    $status           = $_POST['status'] ?? 'active';

    if (empty($name) || $price <= 0) {
        $error = 'Product name and a valid regular price are required.';
    } else {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

        if ($isEdit) {
            $stmt = $pdo->prepare("
                UPDATE products 
                SET name = ?, slug = ?, category_id = ?, price = ?, sale_price = ?, sku = ?, 
                    stock_quantity = ?, short_description = ?, description = ?, image = ?, 
                    featured = ?, status = ?, updated_at = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([
                $name, $slug, $categoryId, $price, $salePrice, $sku, 
                $stockQuantity, $shortDescription, $description, $image, 
                $featured, $status, $productId
            ]);
            $success = 'Product updated successfully!';
            $product = getProductForAdmin($productId);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO products 
                (name, slug, category_id, price, sale_price, sku, stock_quantity, short_description, description, image, featured, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $name, $slug, $categoryId, $price, $salePrice, $sku, 
                $stockQuantity, $shortDescription, $description, $image, 
                $featured, $status
            ]);
            $newId = $pdo->lastInsertId();
            header("Location: products.php?msg=added");
            exit();
        }
    }
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h1 style="font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 700; color: #0f172a;">
            <?php echo $isEdit ? 'Edit Product: ' . htmlspecialchars($product['name']) : 'Add New Product to Store'; ?>
        </h1>
        <p style="font-size: 13px; color: #64748b;">Specify product details, pricing, inventory, and visibility</p>
    </div>
    <a href="products.php" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Products
    </a>
</div>

<?php if ($success): ?>
    <div style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div style="background: #fef2f2; color: #b91c1c; border: 1px solid #f87171; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<form action="product-form.php<?php echo $isEdit ? '?id='.$productId : ''; ?>" method="POST" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 28px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 28px;">
        <!-- Left Column: Core Fields -->
        <div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Product Name *</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Regular Price ($) *</label>
                    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Sale / Offer Price ($) (Optional)</label>
                    <input type="number" step="0.01" name="sale_price" value="<?php echo htmlspecialchars($product['sale_price'] ?? ''); ?>" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Short Highlight Description</label>
                <input type="text" name="short_description" value="<?php echo htmlspecialchars($product['short_description'] ?? ''); ?>" placeholder="Brief 1-sentence highlight for card previews" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Full Product Description & Specifications</label>
                <textarea name="description" rows="7" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; resize: vertical;"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
            </div>
        </div>

        <!-- Right Column: Organization & Meta -->
        <div>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                <h4 style="font-size: 13px; font-weight: 700; text-transform: uppercase; color: #0f172a; margin-bottom: 14px;">Organization</h4>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Category</label>
                    <select name="category_id" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: #fff;">
                        <?php foreach ($categories as $c): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo (isset($product['category_id']) && $product['category_id'] == $c['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Stock Units in Warehouse</label>
                    <input type="number" name="stock_quantity" value="<?php echo htmlspecialchars($product['stock_quantity'] ?? 50); ?>" required style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">SKU Identifier</label>
                    <input type="text" name="sku" value="<?php echo htmlspecialchars($product['sku'] ?? 'SKU-'.rand(1000, 9999)); ?>" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Image File Path or URL</label>
                    <input type="text" name="image" value="<?php echo htmlspecialchars($product['image'] ?? 'Image/iPhone 15 Pro.webp'); ?>" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                    <span style="font-size: 11px; color: #64748b;">e.g. Image/iPhone 15 Pro.webp</span>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" name="featured" value="1" <?php echo !empty($product['featured']) ? 'checked' : ''; ?>>
                        <span>Show on Homepage Featured Section</span>
                    </label>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Status</label>
                    <select name="status" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: #fff;">
                        <option value="active" <?php echo (!isset($product['status']) || $product['status'] === 'active') ? 'selected' : ''; ?>>Active (Visible in Store)</option>
                        <option value="inactive" <?php echo (isset($product['status']) && $product['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive (Hidden)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                <i class="fas fa-save"></i> <?php echo $isEdit ? 'Update Product' : 'Save & Publish Product'; ?>
            </button>
        </div>
    </div>
</form>

<?php require_once 'footer.php'; ?>
