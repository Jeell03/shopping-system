<?php
$adminTitle = 'Manage Categories';
$activeMenu = 'categories';

require_once 'header.php';

$success = '';
$error = '';

// Handle category creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_category'])) {
    $catId = (int)($_POST['category_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    
    if (empty($name)) {
        $error = 'Category name is required.';
    } else {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        
        if ($catId > 0) {
            // Update
            $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ?, description = ?, image = ? WHERE id = ?");
            $stmt->execute([$name, $slug, $description, $image, $catId]);
            $success = 'Category updated successfully!';
        } else {
            // Create
            // Ensure unique slug
            $check = $pdo->prepare("SELECT id FROM categories WHERE slug = ?");
            $check->execute([$slug]);
            if ($check->fetch()) {
                $slug .= '-' . rand(10, 99);
            }
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description, image, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$name, $slug, $description, $image]);
            $success = 'Category added successfully!';
        }
    }
}

// Handle category deletion
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    // Count products in this category
    $pCheck = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
    $pCheck->execute([$delId]);
    $pCount = (int)$pCheck->fetchColumn();
    
    if ($pCount > 0) {
        $error = "Cannot delete this category because $pCount product(s) are assigned to it. Reassign products first.";
    } else {
        $del = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $del->execute([$delId]);
        $success = 'Category deleted successfully!';
    }
}

$editCat = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$editId]);
    $editCat = $stmt->fetch(PDO::FETCH_ASSOC);
}

$categories = getCategories();
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h1 style="font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 700; color: #0f172a;">Manage Store Categories</h1>
        <p style="font-size: 13px; color: #64748b;">Organize your product catalog into searchable customer departments</p>
    </div>
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

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; align-items: start;">
    <!-- Category Form (Add / Edit) -->
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">
            <?php echo $editCat ? 'Edit Category' : 'Add New Category'; ?>
        </h3>

        <form action="categories.php" method="POST">
            <input type="hidden" name="save_category" value="1">
            <input type="hidden" name="category_id" value="<?php echo $editCat ? $editCat['id'] : 0; ?>">

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Category Name *</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($editCat['name'] ?? ''); ?>" required placeholder="e.g. Smart Watches" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Description</label>
                <textarea name="description" rows="3" placeholder="Brief department description..." style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; resize: vertical;"><?php echo htmlspecialchars($editCat['description'] ?? ''); ?></textarea>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Banner / Icon Image Path</label>
                <input type="text" name="image" value="<?php echo htmlspecialchars($editCat['image'] ?? ''); ?>" placeholder="e.g. Image/Electronics.jpg" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> <?php echo $editCat ? 'Update Category' : 'Create Category'; ?>
                </button>
                <?php if ($editCat): ?>
                    <a href="categories.php" class="btn btn-outline btn-sm">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Categories List Table -->
    <div class="admin-table-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Slug URL</th>
                    <th>Products</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 24px; color: #94a3b8;">No categories found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($cat['name']); ?></strong>
                            <?php if (!empty($cat['description'])): ?>
                                <div style="font-size: 12px; color: #64748b;"><?php echo htmlspecialchars(substr($cat['description'], 0, 60)); ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 12px;"><?php echo htmlspecialchars($cat['slug']); ?></code>
                        </td>
                        <td>
                            <span style="font-weight: 700; color: #2563eb;"><?php echo (int)($cat['product_count'] ?? 0); ?> items</span>
                        </td>
                        <td style="text-align: right;">
                            <a href="categories.php?edit=<?php echo $cat['id']; ?>" class="btn btn-outline btn-sm" style="margin-right: 4px;">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="categories.php?delete=<?php echo $cat['id']; ?>" class="btn btn-outline btn-sm" style="color: #ef4444; border-color: #fca5a5;" onclick="return confirm('Delete this category?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'footer.php'; ?>
