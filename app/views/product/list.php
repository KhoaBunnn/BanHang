<?php include 'app/views/shares/header.php'; ?>

<style>
/* Giữ nguyên hoặc điều chỉnh CSS của bạn */
body {
    background: linear-gradient(135deg, #d8c1ff, #a0c4ff); /* tím nhẹ sang xanh dương nhẹ */
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    color: #222;
}

h1 {
    text-align: center;
    background: linear-gradient(90deg, #0066ff, #00ccff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 30px;
}

.btn-success { /* Nút "Thêm sản phẩm mới" */
    background: linear-gradient(90deg, #0066ff, #00ccff);
    border: none;
    color: white;
    padding: 10px 20px; /* Thêm padding cho nút thêm sản phẩm mới */
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.3s ease, transform 0.2s ease;
}

.btn-success:hover {
    background: linear-gradient(90deg, #004aad, #0099cc);
    transform: translateY(-2px);
}

.list-group {
    background-color: #e6f0ff; /* nền xanh dương nhạt nhẹ nhàng */
    padding: 20px;
    border-radius: 10px;
    max-width: 800px; /* Đặt max-width cho list-group */
    margin: 20px auto; /* Canh giữa */
    box-shadow: 0 4px 15px rgba(0, 51, 102, 0.1); /* Thêm shadow nhẹ */
}

.list-group-item {
    border: 2px solid #3399ff;  /* viền xanh dương tươi sáng */
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    background-color: #f0f8ff; /* nền trắng pha xanh rất nhạt */
    color: #003366; /* chữ xanh đậm để dễ đọc */

    display: flex; /* Sử dụng flexbox để sắp xếp nội dung */
    align-items: flex-start; /* Căn trên cùng */
    gap: 20px; /* Khoảng cách giữa ảnh và nội dung */
}

.list-group-item:last-child {
    margin-bottom: 0; /* Không có margin dưới cho item cuối cùng */
}

.product-item-image {
    width: 120px; /* Kích thước ảnh */
    height: 120px;
    object-fit: cover; /* Đảm bảo ảnh không bị méo */
    border-radius: 8px;
    border: 1px solid #a0c4ff;
    flex-shrink: 0; /* Ngăn ảnh co lại */
}

.product-item-details {
    flex-grow: 1; /* Cho phép phần chi tiết mở rộng */
}

.list-group-item h2 {
    margin-top: 0;
    margin-bottom: 8px;
    font-size: 24px;
    color: #004aad; /* Màu đậm hơn cho tiêu đề */
}

.list-group-item p {
    margin-bottom: 5px;
    line-height: 1.5;
}

.list-group-item strong {
    color: #0066ff; /* Màu nhấn cho giá và danh mục */
}

.product-actions {
    margin-top: 15px;
    display: flex;
    gap: 10px; /* Khoảng cách giữa các nút */
    flex-wrap: wrap; /* Cho phép các nút xuống dòng nếu không đủ chỗ */
}

/* Các nút hành động */
.btn-action {
    padding: 8px 15px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.3s ease, transform 0.2s ease;
    text-align: center;
    white-space: nowrap;
    border: none; /* Đảm bảo không có viền thừa */
}

.btn-warning { /* Nút Sửa */
    background-color: #ffc107;
    color: #343a40;
}

.btn-warning:hover {
    background-color: #e0a800;
    transform: translateY(-2px);
}

.btn-danger { /* Nút Xóa */
    background-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
    transform: translateY(-2px);
}

.btn-add-to-cart { /* Nút "Thêm vào giỏ hàng" mới */
    background-color: #28a745; /* Xanh lá cây */
    color: white;
}

.btn-add-to-cart:hover {
    background-color: #218838;
    transform: translateY(-2px);
}

/* Style cho thông báo flash message */
.alert {
    padding: 15px;
    margin: 20px auto; /* Canh giữa */
    max-width: 800px;
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

<div class="product-container">
    <h1 style="text-align: center;">Danh sách sản phẩm</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message']['type'], ENT_QUOTES, 'UTF-8'); ?>">
            <?php echo htmlspecialchars($_SESSION['message']['text'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <?php unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị ?>
    <?php endif; ?>

    <a href="/17_5/Product/add" class="btn btn-success mb-2" style="display: block; width: fit-content; margin: 0 auto 20px auto;">Thêm sản phẩm mới</a>

    <ul class="list-group">
        <?php if (empty($products)): ?>
            <li class="list-group-item">
                <p>Không có sản phẩm nào để hiển thị.</p>
            </li>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <li class="list-group-item">
                    <?php if (!empty($product->image)): ?>
                        <img src="/17_5/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>"
                             alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>"
                             class="product-item-image">
                    <?php else: ?>
                        <img src="/17_5/uploads/no-image.png"
                             alt="Không có hình ảnh"
                             class="product-item-image">
                    <?php endif; ?>

                    <div class="product-item-details">
                        <h2>
                            <a href="/17_5/Product/show/<?php echo htmlspecialchars($product->id, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h2>

                        <p>Mô tả: <?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Giá: <?php echo number_format($product->price, 2, ',', '.') . ' VND'; ?></strong></p>
                        <p>Danh mục: <strong><?php echo htmlspecialchars($product->category_name ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></strong></p>

                        <div class="product-actions">
                            <a href="/17_5/Product/edit/<?php echo htmlspecialchars($product->id, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-action btn-warning">Sửa</a>
                            <a href="/17_5/Product/delete/<?php echo htmlspecialchars($product->id, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-action btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>

                            <form action="/17_5/Product/addToCart" method="post" style="display:inline-block; margin:0; padding:0;">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product->id, ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit" class="btn btn-action btn-add-to-cart">Thêm vào giỏ hàng</button>
                            </form>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<?php include 'app/views/shares/footer.php'; ?>