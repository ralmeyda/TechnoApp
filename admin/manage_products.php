<?php
require_once '../config.php';
require_once 'admin_functions.php';
require_once '../functions.php';

requireAdmin();

$products = getAllProductsAdmin();

// Success messages
$successMessage = '';
if (isset($_GET['added'])) {
    $successMessage = 'Product added successfully!';
} elseif (isset($_GET['updated'])) {
    $successMessage = 'Product updated successfully!';
} elseif (isset($_GET['deleted'])) {
    $successMessage = 'Product deactivated successfully!';
} elseif (isset($_GET['restored'])) {
    $successMessage = 'Product restored successfully!';
} elseif (isset($_GET['error'])) {
    $successMessage = 'Operation failed. Please try again.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products - CYCRIDE Admin</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { background: #f5f5f5; }
        .admin-container {
            max-width: 1400px;
            margin: 100px auto 50px;
            padding: 20px;
        }
        .admin-container h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .products-table {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f5f5f5;
            font-weight: 600;
            color: #333;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-active {
            background: #d4edda;
            color: #155724;
        }
        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .badge-low-stock {
            background: #fff3cd;
            color: #856404;
        }
        .action-btns {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .btn-edit {
            background: #3498db;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }
        .btn-edit:hover {
            background: #2980b9;
        }
        .btn-restore {
            background: #2ecc71;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }
        .btn-restore:hover {
            background: #27ae60;
        }
        .btn-delete {
            background: #e74c3c;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }
        .btn-delete:hover {
            background: #c0392b;
        }
        .btn-add {
            background: #e35f26;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        .btn-add:hover {
            background: #c54d1f;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .inactive-row {
            background: #fff3cd !important;
        }
        .inactive-row:hover {
            background: #ffe69c !important;
        }
    </style>
</head>
<body>
    <header>
        <a href="dashboard.php" class="logo">CYCRIDE ADMIN</a>
        <nav class="navbar">
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php">View Store</a>
            <a href="../logout_process.php" style="color: #ff4444;">Logout</a>
        </nav>
    </header>

    <div class="admin-container">
        <h1><i class="ri-list-check"></i> Manage Products</h1>
        
        <?php if ($successMessage): ?>
            <div class="alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <a href="add_product.php" class="btn-add">
            <i class="ri-add-line"></i> Add New Product
        </a>

        <div class="products-table">
            <?php if (empty($products)): ?>
                <p style="text-align: center; padding: 40px; color: #666;">
                    No products found. <a href="add_product.php">Add your first product</a>
                </p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr <?php echo !$product['is_active'] ? 'class="inactive-row"' : ''; ?>>
                                <td><?php echo $product['product_id']; ?></td>
                                <td>
                                    <?php if ($product['image_url'] && file_exists('../' . $product['image_url'])): ?>
                                        <img src="../<?php echo clean($product['image_url']); ?>" 
                                             alt="Product" class="product-img">
                                    <?php else: ?>
                                        <div style="width:60px; height:60px; background:#ddd; border-radius:4px; display:flex; align-items:center; justify-content:center;">
                                            <i class="ri-image-line" style="color:#999;"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo clean($product['product_name']); ?></td>
                                <td><?php echo clean($product['category_name']); ?></td>
                                <td>₱<?php echo number_format($product['price'], 2); ?></td>
                                <td>
                                    <?php echo $product['stock_quantity']; ?>
                                    <?php if ($product['stock_quantity'] < 5 && $product['stock_quantity'] > 0): ?>
                                        <span class="badge badge-low-stock">Low</span>
                                    <?php elseif ($product['stock_quantity'] == 0): ?>
                                        <span class="badge badge-inactive">Out</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?php echo $product['is_active'] ? 'badge-active' : 'badge-inactive'; ?>">
                                        <?php echo $product['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <?php if ($product['is_active']): ?>
                                            <!-- Active Product: Show Edit & Deactivate -->
                                            <a href="edit_product.php?id=<?php echo $product['product_id']; ?>" class="btn-edit">
                                                <i class="ri-edit-line"></i> Edit
                                            </a>
                                            <form method="POST" action="delete_product.php" style="display: inline; margin: 0;"
                                                  onsubmit="return confirm('Deactivate this product? It will be hidden from customers but can be restored later.');">
                                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                                <button type="submit" class="btn-delete">
                                                    <i class="ri-eye-off-line"></i> Deactivate
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <!-- Inactive Product: Show Restore -->
                                            <form method="POST" action="restore_product.php" style="display: inline; margin: 0;">
                                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                                <button type="submit" class="btn-restore">
                                                    <i class="ri-arrow-go-back-line"></i> Restore
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
