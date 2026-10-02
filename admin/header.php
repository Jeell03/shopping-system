<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$adminTitle = $adminTitle ?? 'ShopEasy Admin Portal';
$activeMenu = $activeMenu ?? 'dashboard';
$adminStats = getAdminStats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($adminTitle); ?> - ShopEasy Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
            background: #f1f5f9;
        }
        .admin-sidebar {
            width: 250px;
            background: #0f172a;
            color: #94a3b8;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
        }
        .admin-brand {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            font-weight: 700;
        }
        .admin-nav {
            padding: 16px 0;
            flex: 1;
        }
        .admin-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            color: #cbd5e1;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .admin-nav-item:hover, .admin-nav-item.active {
            color: #ffffff;
            background: rgba(37, 99, 235, 0.25);
            border-left: 3px solid #3b82f6;
        }
        .admin-nav-item i {
            font-size: 15px;
            width: 20px;
            text-align: center;
        }
        .admin-badge {
            margin-left: auto;
            background: #ef4444;
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 99px;
        }
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }
        .admin-topbar {
            background: #ffffff;
            height: 64px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
        }
        .admin-content {
            padding: 28px;
            flex: 1;
        }
        .kpi-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .kpi-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .admin-table-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 24px;
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .admin-table th {
            background: #f8fafc;
            padding: 12px 18px;
            text-align: left;
            font-weight: 700;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
        }
        .admin-table td {
            padding: 14px 18px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            vertical-align: middle;
        }
        .admin-table tr:hover td {
            background: #f8fafc;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <div class="logo-icon-box" style="width: 32px; height: 32px; font-size: 14px;">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <span>ShopEasy <strong>Admin</strong></span>
            </div>

            <nav class="admin-nav">
                <a href="index.php" class="admin-nav-item <?php echo $activeMenu === 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="products.php" class="admin-nav-item <?php echo $activeMenu === 'products' ? 'active' : ''; ?>">
                    <i class="fas fa-boxes"></i> Manage Products
                </a>
                <a href="product-form.php" class="admin-nav-item <?php echo $activeMenu === 'add_product' ? 'active' : ''; ?>">
                    <i class="fas fa-plus-circle"></i> Add New Product
                </a>
                <a href="orders.php" class="admin-nav-item <?php echo $activeMenu === 'orders' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart"></i> Orders
                    <?php if ($adminStats['pending_orders'] > 0): ?>
                        <span class="admin-badge"><?php echo $adminStats['pending_orders']; ?></span>
                    <?php endif; ?>
                </a>
                <a href="categories.php" class="admin-nav-item <?php echo $activeMenu === 'categories' ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i> Categories
                </a>
                <a href="coupons.php" class="admin-nav-item <?php echo $activeMenu === 'coupons' ? 'active' : ''; ?>">
                    <i class="fas fa-ticket-alt"></i> Coupons & Discounts
                </a>
                <a href="contact-messages.php" class="admin-nav-item <?php echo $activeMenu === 'messages' ? 'active' : ''; ?>">
                    <i class="fas fa-envelope"></i> Customer Inquiries
                </a>
                <div style="border-top: 1px solid rgba(255,255,255,0.08); margin: 12px 0;"></div>
                <a href="../index.php" class="admin-nav-item" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Visit Live Store
                </a>
                <a href="logout.php" class="admin-nav-item" style="color: #f87171;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Body -->
        <div class="admin-main">
            <!-- Topbar -->
            <header class="admin-topbar">
                <div style="font-size: 14px; font-weight: 600; color: #0f172a;">
                    <i class="fas fa-shield-alt" style="color: #2563eb; margin-right: 6px;"></i> Store Administration Portal
                </div>
                <div style="display: flex; align-items: center; gap: 16px;">
                    <span style="font-size: 13px; color: #64748b;">
                        Admin: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                    </span>
                    <a href="logout.php" class="btn btn-outline btn-sm" style="color: #ef4444; border-color: #fca5a5;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </header>

            <main class="admin-content">
