<?php include 'app/views/shares/header.php'; ?>

<style>
    /* CSS cho trang giỏ hàng */
    body {
        background: linear-gradient(135deg, #d8c1ff, #a0c4ff); /* tím nhẹ sang xanh dương nhẹ */
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        color: #222;
    }

    .cart-container {
        background: white;
        max-width: 900px;
        margin: 20px auto;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(74, 63, 158, 0.3);
    }

    h1 {
        text-align: center;
        color: #4a3f9e; /* tím đậm nhẹ */
        margin-bottom: 30px;
        font-size: 2.5em;
    }

    .empty-cart-message {
        text-align: center;
        font-size: 1.2em;
        color: #666;
        margin-bottom: 30px;
    }

    .cart-items-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .cart-item {
        display: flex;
        align-items: center;
        border-bottom: 1px solid #eee;
        padding: 15px 0;
        margin-bottom: 15px;
    }

    .cart-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .cart-item-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 20px;
        border: 1px solid #a0c4ff;
        flex-shrink: 0;
    }

    .cart-item-details {
        flex-grow: 1;
    }

    .cart-item-details h3 {
        margin-top: 0;
        margin-bottom: 5px;
        color: #4a3f9e;
        font-size: 1.5em;
    }

    .cart-item-details p {
        margin-bottom: 5px;
        color: #555;
    }

    .cart-item-price {
        font-weight: bold;
        color: #3b7ddd;
        font-size: 1.1em;
    }

    .cart-item-quantity {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    .cart-item-quantity label {
        margin-right: 10px;
        font-weight: bold;
    }

    .cart-item-quantity input[type="number"] {
        width: 60px;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        text-align: center;
    }

    .item-actions {
        display: flex;
        flex-direction: column; /* Xếp các nút dọc */
        gap: 8px; /* Khoảng cách giữa các nút */
        margin-left: 20px; /* Khoảng cách từ chi tiết sản phẩm */
    }

    .btn {
        padding: 8px 15px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s ease, transform 0.2s ease;
        text-align: center;
        white-space: nowrap;
        border: none;
        cursor: pointer;
    }

    .btn-update {
        background-color: #6a4eff; /* Tím */
        color: white;
    }

    .btn-update:hover {
        background-color: #5a3edb;
        transform: translateY(-2px);
    }

    .btn-remove {
        background-color: #e74c3c; /* Đỏ */
        color: white;
    }

    .btn-remove:hover {
        background-color: #c0392b;
        transform: translateY(-2px);
    }

    .cart-summary {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
        text-align: right;
        font-size: 1.3em;
        font-weight: bold;
        color: #4a3f9e;
    }

    .cart-actions {
        display: flex;
        justify-content: space-between; /* Đẩy các nút ra hai phía */
        margin-top: 30px;
        gap: 15px; /* Khoảng cách giữa các nhóm nút */
        flex-wrap: wrap; /* Cho phép xuống dòng nếu màn hình nhỏ */
    }

    .cart-actions .left-buttons,
    .cart-actions .right-buttons {
        display: flex;
        gap: 10px; /* Khoảng cách giữa các nút trong cùng một nhóm */
    }

    .btn-primary { /* Nút "Thanh toán" */
        background: linear-gradient(90deg, #6a4eff, #3b7ddd);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, #4a3f9e, #1d57c4);
        transform: translateY(-2px);
    }

    .btn-secondary { /* Nút "Tiếp tục mua sắm" */
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
    }

    /* Style cho thông báo flash message */
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
        text-align: center;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .alert-error {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }
</style>

<div class="cart-container">
    <h1>Giỏ hàng của bạn</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message']['type'], ENT_QUOTES, 'UTF-8'); ?>">
            <?php echo htmlspecialchars($_SESSION['message']['text'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <?php unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị ?>
    <?php endif; ?>

    <?php
    $cartItems = $_SESSION['cart'] ?? [];
    $totalPrice = 0;
    ?>

    <?php if (empty($cartItems)): ?>
        <p class="empty-cart-message">Giỏ hàng của bạn đang trống.</p>
        <div class="cart-actions" style="justify-content: center;">
            <a href="/17_5/Product/list" class="btn btn-secondary">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <ul class="cart-items-list">
            <?php foreach ($cartItems as $productId => $item): ?>
                <?php
                $subtotal = $item['price'] * $item['quantity'];
                $totalPrice += $subtotal;
                ?>
                <li class="cart-item">
                    <?php if (!empty($item['image'])): ?>
                        <img src="/17_5/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>"
                             alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>"
                             class="cart-item-image">
                    <?php else: ?>
                        <img src="/17_5/uploads/no-image.png"
                             alt="Không có hình ảnh"
                             class="cart-item-image">
                    <?php endif; ?>
                    <div class="cart-item-details">
                        <h3><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p class="cart-item-price">Giá: <?php echo number_format($item['price'], 2, ',', '.') . ' VND'; ?></p>
                        <form action="/17_5/Product/updateCart" method="post" class="cart-item-quantity">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($productId, ENT_QUOTES, 'UTF-8'); ?>">
                            <label for="quantity-<?php echo $productId; ?>">Số lượng:</label>
                            <input type="number" id="quantity-<?php echo $productId; ?>" name="quantity"
                                   value="<?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?>"
                                   min="0" required>
                            <button type="submit" name="update_cart" class="btn btn-update" style="margin-left: 10px;">Cập nhật</button>
                        </form>
                        <p>Thành tiền: <?php echo number_format($subtotal, 2, ',', '.') . ' VND'; ?></p>
                    </div>
                    <div class="item-actions">
                        <form action="/17_5/Product/removeFromCart" method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($productId, ENT_QUOTES, 'UTF-8'); ?>">
                            <button type="submit" class="btn btn-remove">Xóa</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="cart-summary">
            Tổng cộng: <?php echo number_format($totalPrice, 2, ',', '.') . ' VND'; ?>
        </div>

        <div class="cart-actions">
            <div class="left-buttons">
                <a href="/17_5/Product/list" class="btn btn-secondary">Tiếp tục mua sắm</a>
            </div>
            <div class="right-buttons">
                <a href="/17_5/Product/checkout" class="btn btn-primary">Thanh Toán</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>