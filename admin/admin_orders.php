<?php
require_once '../config.php';
require_once '../functions.php';
require_once 'admin_functions.php'; // Make sure you have admin check

requireAdmin(); // Ensures only admin can access

// Handle Accept/Decline POST action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);
    $action = $_POST['action'];

    if (in_array($action, ['accepted', 'declined'])) {
        $stmt = $pdo->prepare("UPDATE orders SET status=?, notified=0 WHERE order_id=?");
        $stmt->execute([$action, $orderId]);
        $message = "Order #$orderId has been " . $action;
    }
}

// Fetch only orders from the shop (index.php)
$stmt = $pdo->query("
    SELECT o.*, u.username, u.first_name, u.last_name, u.address, u.phone
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    WHERE o.source = 'shop' AND u.user_type != 'admin'
    ORDER BY o.order_id DESC
");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <?php if(isset($message)) echo "<p style='color:green;'>$message</p>"; ?>

    <?php if(empty($orders)): ?>
        <p>No orders yet.</p>
    <?php else: ?>
        <table style="margin-top:43px;">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Products / Qty</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $order): ?>
                    <tr>
                        <td><?= $order['order_id'] ?></td>
                        <td><?= clean($order['first_name'] . ' ' . $order['last_name']) ?></td>
                        <td><?= clean($order['address']) ?></td>
                        <td><?= clean($order['phone']) ?></td>
                        <td>
                            <?php
                            $products = $order['products'] ?? null; // check if 'products' exists
                            $products = $products ? json_decode($products, true) : [];
                            if(!empty($products)):
                                foreach($products as $p):
                                    echo clean($p['name']).' x '.$p['quantity'].'<br>';
                                endforeach;
                            else:
                                echo "-";
                            endif;
                            ?>
                        </td>
                        <td>
                            <?= isset($order['total_price']) ? 'PHP ' . number_format($order['total_price'], 2) : 'PHP 0.00' ?>
                        </td>
                        <td class="status <?= $order['status'] ?>"><?= ucfirst($order['status']) ?></td>
                        <td>
                            <?php if($order['status'] === 'pending'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                    <button type="submit" name="action" value="accepted" class="btn btn-accept">Accept</button>
                                    <button type="submit" name="action" value="declined" class="btn btn-decline">Decline</button>
                                </form>
                            <?php else: ?>
                                <span>—</span>
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
