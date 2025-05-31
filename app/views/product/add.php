<?php
// Đảm bảo ROOT_PATH đã được định nghĩa trong index.php của bạn.
// Ví dụ: define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
include ROOT_PATH . 'app/views/shares/header.php';
?>

<style>
/* CSS của bạn ở đây */
body {
    background: linear-gradient(135deg, #d8c1ff, #a0c4ff); /* tím nhẹ sang xanh dương nhẹ */
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    color: #222;
}

h1 {
    text-align: center;
    color: #4a3f9e; /* tím đậm nhẹ */
    margin-bottom: 30px;
}

form {
    background: white;
    max-width: 600px;
    margin: 0 auto;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(74, 63, 158, 0.3);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #4a3f9e;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #a0c4ff;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #4a3f9e;
    outline: none;
    box-shadow: 0 0 6px #4a3f9e;
}

.btn-primary {
    background: linear-gradient(90deg, #6a4eff, #3b7ddd);
    border: none;
    padding: 12px 25px;
    font-size: 18px;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    transition: background 0.3s ease;
    display: block;
    margin: 0 auto;
}

.btn-primary:hover {
    background: linear-gradient(90deg, #4a3f9e, #1d57c4);
}

.btn-secondary {
    display: block;
    max-width: 600px;
    margin: 20px auto 0;
    padding: 10px 15px;
    border-radius: 8px;
    background-color: #8889a8;
    color: white;
    text-align: center;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.3s ease;
}

.btn-secondary:hover {
    background-color: #6a4eff;
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

.alert-error, .alert-danger { /* Đã thêm .alert-danger */
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.alert-info {
    color: #0c5460;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}

.error-message { /* CSS cho thông báo lỗi JS */
    color: red;
    font-size: 0.9em;
    margin-top: 5px;
    display: none; /* Ban đầu ẩn đi */
}

</style>


<h1>Thêm sản phẩm mới</h1>

<?php if (isset($_SESSION['message'])): // Hiển thị thông báo flash message ?>
    <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message']['type'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php echo htmlspecialchars($_SESSION['message']['text'], ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <?php unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị ?>
<?php endif; ?>

<form method="POST" action="/17_5/Product/save" enctype="multipart/form-data" onsubmit="return validateForm();">
    <div class="form-group">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" id="name" name="name" class="form-control"
               value="<?php echo htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <span id="name-error" class="error-message"></span>
    </div>

    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control"><?php echo htmlspecialchars($_POST['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
        <span id="description-error" class="error-message"></span>
    </div>

    <div class="form-group">
        <label for="price">Giá:</label>
        <input type="number" id="price" name="price" class="form-control" step="0.01"
               value="<?php echo htmlspecialchars($_POST['price'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <span id="price-error" class="error-message"></span>
    </div>

    <div class="form-group">
        <label for="category_id">Danh mục:</label>
        <select id="category_id" name="category_id" class="form-control">
            <option value="">-- Chọn danh mục --</option> <?php
            // Biến $categories này phải được truyền từ ProductController::add() hoặc ProductController::save()
            // Đảm bảo $categories là một mảng hoặc object có thể lặp
            if (isset($categories) && is_array($categories) || is_object($categories)) {
                foreach ($categories as $category):
                    $selected = (isset($_POST['category_id']) && $_POST['category_id'] == $category->id) ? 'selected' : '';
                    ?>
                    <option value="<?php echo htmlspecialchars($category->id, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $selected; ?>>
                        <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach;
            } else {
                // Xử lý trường hợp $categories không được truyền hoặc không phải kiểu dữ liệu mong muốn
                echo '<option value="">Không có danh mục nào được tìm thấy</option>';
            }
            ?>
        </select>
        <span id="category_id-error" class="error-message"></span>
    </div>

    <div class="form-group">
        <label for="image">Hình ảnh:</label>
        <input type="file" id="image" name="image" class="form-control">
        <span id="image-error" class="error-message"></span>
    </div>

    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
</form>

<a href="/17_5/Product/list" class="btn btn-secondary mt-2">Quay lại danh sách sản phẩm</a>

<?php
// JavaScript cho validateForm()
?>
<script>
function validateForm() {
    let isValid = true;

    // Hàm hiển thị lỗi
    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId + '-error');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
        isValid = false;
    }

    // Hàm ẩn lỗi
    function hideError(elementId) {
        const errorElement = document.getElementById(elementId + '-error');
        if (errorElement) {
            errorElement.textContent = '';
            errorElement.style.display = 'none';
        }
    }

    // Validate Tên sản phẩm
    let name = document.getElementById('name').value;
    if (name.trim() === '') {
        showError('name', 'Tên sản phẩm không được để trống.');
    } else {
        hideError('name');
    }

    // Validate Mô tả
    let description = document.getElementById('description').value;
    if (description.trim() === '') {
        showError('description', 'Mô tả sản phẩm không được để trống.');
    } else {
        hideError('description');
    }

    // Validate Giá
    let price = document.getElementById('price').value;
    if (price.trim() === '' || isNaN(price) || parseFloat(price) <= 0) {
        showError('price', 'Giá sản phẩm phải là một số dương.');
    } else {
        hideError('price');
    }

    // Validate Danh mục
    let category_id = document.getElementById('category_id').value;
    if (category_id === '' || category_id === '0') { // Kiểm tra cả giá trị 0 nếu có
        showError('category_id', 'Vui lòng chọn danh mục.');
    } else {
        hideError('category_id');
    }

    // Validate Hình ảnh (chỉ khi thêm mới, không cần bắt buộc khi chỉnh sửa)
    let image = document.getElementById('image').value;
    // Tùy thuộc vào logic của bạn, nếu sản phẩm mới luôn cần ảnh, hãy bỏ comment dòng dưới
    // if (image === '') {
    //     showError('image', 'Vui lòng chọn hình ảnh sản phẩm.');
    // } else {
    //     hideError('image');
    // }

    return isValid;
}
</script>

<?php
// Đảm bảo ROOT_PATH đã được định nghĩa trong index.php của bạn.
include ROOT_PATH . 'app/views/shares/footer.php';
?>