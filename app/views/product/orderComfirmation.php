<?php 
// Đảm bảo ROOT_PATH đã được định nghĩa trong index.php của bạn
// Ví dụ: define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR); trong index.php
include ROOT_PATH . 'app/views/shares/header.php'; 
?>

<style>
    /* CSS cơ bản cho trang xác nhận đơn hàng */
    body {
        background: linear-gradient(135deg, #d8c1ff, #a0c4ff); /* tím nhẹ sang xanh dương nhẹ */
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        color: #222;
        display: flex; /* Dùng flexbox để căn giữa nội dung */
        justify-content: center;
        align-items: center;
        min-height: 100vh; /* Đảm bảo chiều cao tối thiểu là toàn bộ viewport */
    }

    .confirmation-container {
        background: white;
        max-width: 700px; /* Tăng chiều rộng để dễ đọc hơn */
        margin: 0 auto;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(74, 63, 158, 0.3);
        text-align: center;
    }

    h1 {
        color: #4a3f9e; /* Tím đậm nhẹ */
        font-size: 2.5em;
        margin-bottom: 20px;
    }

    p {
        font-size: 1.1em;
        line-height: 1.6;
        color: #555;
        margin-bottom: 15px; /* Giảm margin bottom cho p */
    }

    .order-summary {
        background-color: #f0f8ff; /* Nền xanh nhạt cho tóm tắt */
        border: 1px solid #a0c4ff;
        border-radius: 8px;
        padding: 20px;
        margin-top: 30px;
        margin-bottom: 30px;
        text-align: left; /* Căn trái nội dung tóm tắt */
    }

    .order-summary h2 {
        color: #3b7ddd;
        font-size: 1.8em;
        margin-top: 0;
        margin-bottom: 15px;
        text-align: center;
    }

    .order-summary p strong {
        color: #4a3f9e; /* Màu đậm cho các label */
    }

    .order-summary ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .order-summary li {
        margin-bottom: 8px;
        color: #333;
    }

    .order-summary .total-amount {
        font-size: 1.3em;
        font-weight: bold;
        color: #e74c3c; /* Màu đỏ nổi bật cho tổng tiền */
        text-align: right;
        margin-top: 15px;
        border-top: 1px dashed #ccc;
        padding-top: 10px;
    }


    .btn-primary {
        background: linear-gradient(90deg, #6a4eff, #3b7ddd);
        border: none;
        padding: 12px 25px;
        font-size: 1.1em;
        border-radius: 8px;
        color: white;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
        text-decoration: none; /* Bỏ gạch chân cho link */
        display: inline-block; /* Để áp dụng padding và margin */
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, #4a3f9e, #1d57c4);
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

<div class="confirmation-container">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message']['type'], ENT_QUOTES, 'UTF-8'); ?>">
            <?php echo htmlspecialchars($_SESSION['message']['text'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <?php unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị ?>
    <?php endif; ?>

    <h1>Xác nhận đơn hàng thành công!</h1>
    <p>Cảm ơn bạn đã đặt hàng từ cửa hàng của chúng tôi.</p>
    <p>Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.</p>
    <p>Chúng tôi sẽ gửi một email xác nhận chi tiết đơn hàng đến địa chỉ email của bạn trong ít phút.</p>

    <?php
    // Để hiển thị chi tiết đơn hàng (tên khách hàng, tổng tiền, v.v.)
    // Bạn cần truyền biến $order_details từ ProductController::orderConfirmation().
    // Ví dụ: $order_details = $_SESSION['last_order_details'] ?? [];
    // Bạn sẽ cần lưu thông tin này vào Session trong phương thức processCheckout() của ProductController.
    
    // Ví dụ về cách hiển thị nếu có dữ liệu đơn hàng (bỏ comment để sử dụng khi bạn đã truyền dữ liệu)
    // if (!empty($order_details)):
    ?>
        <?php // endif; ?>

    <a href="/17_5/Product/list" class="btn btn-primary">Tiếp tục mua sắm</a>
</div>

<?php 
// Đảm bảo ROOT_PATH đã được định nghĩa trong index.php của bạn
include ROOT_PATH . 'app/views/shares/footer.php'; 
?>