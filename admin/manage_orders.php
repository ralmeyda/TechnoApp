<?php
require_once '../config.php';
require_once 'admin_functions.php';
requireAdmin();

// Fetch all orders
$orders = $conn->query("
    SELECT o.order_id, o.user_id, o.total_amount, o.status, o.created_at, u.username
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    ORDER BY o.created_at DESC
");

if (isset($_POST['action'], $_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);
    $action = $_POST['action'];
    $status = $action === 'accept' ? 'accepted' : 'declined';

    // Update order
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE order_id=?");
    $stmt->bind_param("si", $status, $orderId);
    $stmt->execute();

    // Fetch user_id to send notification
    $userResult = $conn->query("SELECT user_id FROM orders WHERE order_id=$orderId");
    $user = $userResult->fetch_assoc();
    $userId = $user['user_id'];

    // Send notification to user
    $message = $status === 'accepted'
        ? "Your order #$orderId has been accepted! Thank you for shopping with us."
        : "Unfortunately, your order #$orderId has been declined. Please contact support for details.";

    $stmt2 = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt2->bind_param("is", $userId, $message);
    $stmt2->execute();

    header("Location: manage_orders.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h1>Manage Orders</h1>
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Total Amount</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $orders->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['order_id']; ?></td>
        <td><?php echo clean($row['username']); ?></td>
        <td>₱<?php echo number_format($row['total_amount'], 2); ?></td>
        <td><?php echo ucfirst($row['status']); ?></td>
        <td>
            <?php if ($row['status'] === 'pending'): ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                    <button type="submit" name="action" value="accept" style="background:green;color:white;">Accept</button>
                    <button type="submit" name="action" value="decline" style="background:red;color:white;">Decline</button>
                </form>
            <?php else: ?>
                <em><?php echo ucfirst($row['status']); ?></em>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
