<?php
require_once '../config.php';
require_once '../functions.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$userId = getCurrentUserId();
$totalAmount = 0;

// ✅ Get all items in the user's cart
$cartData = getCartItems($userId);
$cartItems = $cartData['items'];

if (empty($cartItems)) {
    echo json_encode(['success' => false, 'message' => 'Your cart is empty.']);
    exit;
}

// ✅ Check stock before confirming order
foreach ($cartItems as $item) {
    $productId = $item['product_id'];
    $quantity = $item['quantity'];

    // Get the current stock from database
    $stmt = $conn->prepare("SELECT stock_quantity, product_name FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found.']);
        exit;
    }

    // 🚫 If requested quantity exceeds available stock
    if ($product['stock_quantity'] < $quantity) {
        echo json_encode([
            'success' => false,
            'message' => 'Cannot order more than available stock for ' . $product['product_name'] .
                         '. Available: ' . $product['stock_quantity']
        ]);
        exit;
    }
}

// ✅ If all items are valid, proceed to order
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, order_date) VALUES (?, ?, 'pending', NOW())");
$stmt->bind_param("id", $userId, $totalAmount);
$stmt->execute();
$orderId = $stmt->insert_id;

// ✅ Move cart items into order_items + decrement stock
foreach ($cartItems as $item) {
    $productId = $item['product_id'];
    $quantity = $item['quantity'];
    $price = $item['price'];

    // Insert into order_items
    $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiid", $orderId, $productId, $quantity, $price);
    $stmt2->execute();

    // Deduct stock safely
    $stmt3 = $conn->prepare("UPDATE products 
                             SET stock_quantity = stock_quantity - ? 
                             WHERE product_id = ?");
    $stmt3->bind_param("ii", $quantity, $productId);
    $stmt3->execute();

    // ✅ Optional: deactivate product if stock is 0
    $conn->query("UPDATE products 
                  SET is_active = 0 
                  WHERE product_id = $productId AND stock_quantity <= 0");

    // Add to total
    $totalAmount += ($price * $quantity);
}

// ✅ Update total amount in the order record
$stmt4 = $conn->prepare("UPDATE orders SET total_amount = ? WHERE order_id = ?");
$stmt4->bind_param("di", $totalAmount, $orderId);
$stmt4->execute();

// ✅ Clear the cart after successful purchase
$conn->query("DELETE FROM cart WHERE user_id = $userId");

echo json_encode(['success' => true, 'message' => 'Order placed successfully!']);
?>
