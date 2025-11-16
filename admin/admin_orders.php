<?php
require_once '../config.php';
require_once '../functions.php';
require_once 'admin_functions.php';

requireAdmin();

// Handle accept/decline
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['action'])) {
    $orderId = (int)$_POST['order_id'];
    $action  = $_POST['action'];

    if (in_array($action, ['accepted','declined'], true)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ?, notified = 0 WHERE order_id = ?");
        $stmt->execute([$action, $orderId]);
        $message = "Order #{$orderId} has been {$action}.";
    }
}

// Fetch orders (non-admin users)
$stmt = $pdo->query("
    SELECT o.*, u.username, u.first_name, u.last_name, u.address, u.phone
    FROM orders o
    JOIN users u ON u.user_id = o.user_id
    WHERE u.user_type != 'admin'
    ORDER BY o.order_id DESC
");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Helper to get items per order
$itemStmt = $pdo->prepare("
    SELECT oi.*, p.product_name
    FROM order_items oi
    JOIN products p ON p.product_id = oi.product_id
    WHERE oi.order_id = ?
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Orders</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left;}
        th { background: #f7f7f7; }
        .btn { padding: 5px 12px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-accept { background: #4CAF50; color: white; }
        .btn-decline { background: #f44336; color: white; }
        .status { font-weight: bold; }
        .accepted { color: green; }
        .declined { color: red; }
    </style>
</head>
<body>
<header>
    <a href="dashboard.php" class="logo">ADMIN</a>
    <nav class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="add_product.php">Add Product</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="../logout_process.php" style="color:#ff4444;">Logout</a>
    </nav>
</header>

<div class="admin-container">
    <h1>Customer Orders</h1>

    <?php if (!empty($message)): ?>
        <p style="color:green;"><?= clean($message); ?></p>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <p>No orders yet.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <?php
                $itemStmt->execute([$order['order_id']]);
                $items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <tr>
                    <td><?= (int)$order['order_id']; ?></td>
                    <td><?= clean($order['first_name'] . ' ' . $order['last_name']); ?></td>
                    <td><?= clean($order['address']); ?></td>
                    <td><?= clean($order['phone']); ?></td>
                    <td>
                        <?php if (!empty($items)): ?>
                            <?php foreach ($items as $it): ?>
                                <?= clean($it['product_name']); ?> x <?= (int)$it['quantity']; ?>
                                (PHP<?= number_format($it['price'], 2); ?>)<br>
                            <?php endforeach; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>PHP<?= number_format($order['total_amount'], 2); ?></td>
                    <td class="status <?= clean($order['status']); ?>"><?= ucfirst(clean($order['status'])); ?></td>
                    <td>
                        <?php if ($order['status'] === 'pending'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?= (int)$order['order_id']; ?>">
                                <button type="submit" name="action" value="accepted" class="btn btn-accept">Accept</button>
                                <button type="submit" name="action" value="declined" class="btn btn-decline">Decline</button>
                            </form>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
