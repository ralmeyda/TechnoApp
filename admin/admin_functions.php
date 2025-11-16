<?php
// ...

function requireAdmin() {
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
        header('Location: ../login.php');
        exit;
    }
}

function getDashboardStats() {
    global $pdo;

    $totalProducts = $pdo->query("SELECT COUNT(*) FROM products WHERE is_active = 1")->fetchColumn();
    $totalUsers    = $pdo->query("SELECT COUNT(*) FROM users WHERE user_type != 'admin'")->fetchColumn();
    $totalOrders   = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $totalRevenue  = $pdo->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE status = 'completed'")->fetchColumn();
    $lowStock      = $pdo->query("SELECT COUNT(*) FROM products WHERE stock_quantity <= 5 AND is_active = 1")->fetchColumn();

    return [
        'total_products' => (int)$totalProducts,
        'total_users'    => (int)$totalUsers,
        'total_orders'   => (int)$totalOrders,
        'total_revenue'  => (float)$totalRevenue,
        'low_stock'      => (int)$lowStock,
    ];
}
