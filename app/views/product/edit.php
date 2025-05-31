<?php include 'app/views/shares/header.php'; ?>

<style>
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

</style>

<h1>Sửa sản phẩm</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="/17_5/Product/update" enctype="multipart/form-data" onsubmit="return validateForm();">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($product->id, ENT_QUOTES, 'UTF-8'); ?>">

    <div class="form-group">
        <label for="name">Tên sản phẩm:</label>
        <input 
            type="text" 
            id="name" 
            name="name" 
            class="form-control" 
            value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" 
            required
        >
    </div>

    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea 
            id="description" 
            name="description" 
            class="form-control" 
            required
        ><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
    </div>

    <div class="form-group">
        <label for="price">Giá:</label>
        <input 
            type="number" 
            id="price" 
            name="price" 
            class="form-control" 
            step="0.01" 
            value="<?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>" 
            required
        >
    </div>

    <div class="form-group">
        <label for="category_id">Danh mục:</label>
        <select id="category_id" name="category_id" class="form-control" required>
            <?php foreach ($categories as $category): ?>
                <option 
                    value="<?php echo htmlspecialchars($category->id, ENT_QUOTES, 'UTF-8'); ?>" 
                    <?php echo ($category->id == $product->category_id) ? 'selected' : ''; ?>
                >
                    <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="image">Hình ảnh:</label>
        <input type="file" id="image" name="image" class="form-control">
        <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>">
        <?php if ($product->image): ?>
            <img src="/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" alt="Product Image" style="max-width: 100px;">
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
</form>

<a href="/17_5/Product/list" class="btn btn-secondary mt-2">Quay lại danh sách sản phẩm</a>

<?php include 'app/views/shares/footer.php'; ?>
