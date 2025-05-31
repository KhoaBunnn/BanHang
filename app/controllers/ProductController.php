<?php
// Đảm bảo ROOT_PATH đã được định nghĩa trong index.php của bạn.
// Ví dụ: define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);

class ProductController
{
    private $productModel;
    private $categoryModel;
    private $orderModel;    // Thêm thuộc tính orderModel nếu bạn có OrderModel
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();

        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
        // if (class_exists('OrderModel')) {
        //    $this->orderModel = new OrderModel($this->db);
        // }
    }

    public function index()
    {
        $products = $this->productModel->getProducts();
        include ROOT_PATH . 'app/views/product/list.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include ROOT_PATH . 'app/views/product/show.php';
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Không tìm thấy sản phẩm!'];
            header('Location: /17_5/Product/index'); // Sửa đường dẫn ở đây
            exit;
        }
    }

    public function add()
    {
        $categories = $this->categoryModel->getCategories();
        include ROOT_PATH . 'app/views/product/add.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = floatval($_POST['price'] ?? 0);
            $category_id = intval($_POST['category_id'] ?? 0);

            $image = "";
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                try {
                    $image = $this->uploadImage($_FILES['image']);
                } catch (Exception $e) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Lỗi tải ảnh lên: ' . $e->getMessage()];
                    $categories = $this->categoryModel->getCategories();
                    include ROOT_PATH . 'app/views/product/add.php';
                    return;
                }
            }

            if (empty($name) || empty($description) || $price <= 0 || $category_id <= 0) {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Vui lòng điền đầy đủ và đúng thông tin sản phẩm.'];
                $categories = $this->categoryModel->getCategories();
                include ROOT_PATH . 'app/views/product/add.php';
                return;
            }

            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

            if ($result) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Sản phẩm đã được thêm thành công!'];
                header('Location: /17_5/Product'); // Sửa đường dẫn ở đây
                exit;
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Đã xảy ra lỗi khi thêm sản phẩm vào cơ sở dữ liệu.'];
                $categories = $this->categoryModel->getCategories();
                include ROOT_PATH . 'app/views/product/add.php';
            }
        } else {
            header('Location: /17_5/Product/add'); // Sửa đường dẫn ở đây
            exit;
        }
    }

    public function edit($id)
    {
        $product = $this->productModel->getProductById($id);
        $categories = $this->categoryModel->getCategories();
        if ($product) {
            include ROOT_PATH . 'app/views/product/edit.php';
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Không tìm thấy sản phẩm để chỉnh sửa!'];
            header('Location: /17_5/Product/index'); // Sửa đường dẫn ở đây
            exit;
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = floatval($_POST['price'] ?? 0);
            $category_id = intval($_POST['category_id'] ?? 0);
            $existing_image = $_POST['existing_image'] ?? '';

            $image = $existing_image;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0 && $_FILES['image']['size'] > 0) {
                try {
                    $image = $this->uploadImage($_FILES['image']);
                } catch (Exception $e) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Lỗi tải ảnh lên: ' . $e->getMessage()];
                    $product = $this->productModel->getProductById($id);
                    $categories = $this->categoryModel->getCategories();
                    include ROOT_PATH . 'app/views/product/edit.php';
                    return;
                }
            }

            if ($id <= 0 || empty($name) || empty($description) || $price <= 0 || $category_id <= 0) {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Vui lòng điền đầy đủ và đúng thông tin sản phẩm để cập nhật.'];
                $product = $this->productModel->getProductById($id);
                $categories = $this->categoryModel->getCategories();
                include ROOT_PATH . 'app/views/product/edit.php';
                return;
            }

            $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
            if ($edit) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Sản phẩm đã được cập nhật thành công!'];
                header('Location: /17_5/Product'); // Sửa đường dẫn ở đây
                exit;
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Đã xảy ra lỗi khi cập nhật sản phẩm.'];
                $product = $this->productModel->getProductById($id);
                $categories = $this->categoryModel->getCategories();
                include ROOT_PATH . 'app/views/product/edit.php';
            }
        } else {
            header('Location: /17_5/Product/index'); // Sửa đường dẫn ở đây
            exit;
        }
    }

    public function delete($id)
    {
        if ($this->productModel->deleteProduct($id)) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Sản phẩm đã được xóa thành công!'];
            header('Location: /17_5/Product'); // Sửa đường dẫn ở đây
            exit;
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Đã xảy ra lỗi khi xóa sản phẩm.'];
            header('Location: /17_5/Product'); // Sửa đường dẫn ở đây
            exit;
        }
    }

    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $newFileName = uniqid('img_') . '.' . $fileExtension;
        $target_file = $target_dir . $newFileName;

        $imageFileType = $fileExtension;

        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }

        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn (tối đa 10MB).");
        }

        if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }

        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        return $target_file;
    }

    public function addToCart($id = null)
    {
        $productId = $_POST['product_id'] ?? $id;

        if (!$productId) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Không có ID sản phẩm được cung cấp để thêm vào giỏ hàng.'];
            header('Location: /17_5/Product/list'); // Sửa đường dẫn ở đây
            exit;
        }

        $product = $this->productModel->getProductById($productId);

        if (!$product) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Không tìm thấy sản phẩm này.'];
            header('Location: /17_5/Product/list'); // Sửa đường dẫn ở đây
            exit;
        }

        $quantity = $_POST['quantity'] ?? 1;
        $quantity = max(1, (int)$quantity);

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
            $_SESSION['message'] = ['type' => 'info', 'text' => 'Số lượng sản phẩm "' . $product->name . '" trong giỏ hàng đã được cập nhật.'];
        } else {
            $_SESSION['cart'][$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity
            ];
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Sản phẩm "' . $product->name . '" đã được thêm vào giỏ hàng.'];
        }

        header('Location: /17_5/Product/cart'); // Sửa đường dẫn ở đây
        exit;
    }

    public function cart()
    {
        include ROOT_PATH . 'app/views/product/cart.php';
    }

    public function updateCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;
            $quantity = intval($_POST['quantity'] ?? 0);

            if ($productId && isset($_SESSION['cart'][$productId])) {
                if ($quantity > 0) {
                    $_SESSION['cart'][$productId]['quantity'] = $quantity;
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Số lượng sản phẩm đã được cập nhật.'];
                } else {
                    unset($_SESSION['cart'][$productId]);
                    $_SESSION['message'] = ['type' => 'info', 'text' => 'Sản phẩm đã được xóa khỏi giỏ hàng.'];
                }
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Sản phẩm không tồn tại trong giỏ hàng.'];
            }
        }
        header('Location: /17_5/Product/cart'); // Sửa đường dẫn ở đây
        exit;
    }

    public function removeFromCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;

            if ($productId && isset($_SESSION['cart'][$productId])) {
                $productName = $_SESSION['cart'][$productId]['name'];
                unset($_SESSION['cart'][$productId]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Sản phẩm "' . $productName . '" đã được xóa khỏi giỏ hàng.'];
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Sản phẩm không tìm thấy trong giỏ hàng để xóa.'];
            }
        }
        header('Location: /17_5/Product/cart'); // Sửa đường dẫn ở đây
        exit;
    }

    public function checkout()
    {
        if (empty($_SESSION['cart'])) {
            $_SESSION['message'] = ['type' => 'info', 'text' => 'Giỏ hàng của bạn đang trống, không thể thanh toán.'];
            header('Location: /17_5/Product/list'); // Sửa đường dẫn ở đây
            exit;
        }
        include ROOT_PATH . 'app/views/product/checkout.php';
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customer_name = trim($_POST['customer_name'] ?? '');
            $customer_email = trim($_POST['customer_email'] ?? '');
            $customer_phone = trim($_POST['customer_phone'] ?? '');
            $customer_address = trim($_POST['customer_address'] ?? '');
            $payment_method = trim($_POST['payment_method'] ?? '');

            $cartItems = $_SESSION['cart'] ?? [];

            if (empty($cartItems)) {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Giỏ hàng của bạn đang trống!'];
                header('Location: /17_5/Product/cart'); // Sửa đường dẫn ở đây
                exit;
            }

            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            try {
                $this->db->beginTransaction();

                $orderId = uniqid('ORDER_');

                $this->db->commit();

                unset($_SESSION['cart']);

                $_SESSION['message'] = ['type' => 'success', 'text' => 'Đơn hàng của bạn đã được đặt thành công!'];
                $_SESSION['last_order_details'] = [
                    'id' => $orderId,
                    'customer_name' => $customer_name,
                    'customer_email' => $customer_email,
                    'customer_phone' => $customer_phone,
                    'customer_address' => $customer_address,
                    'total_amount' => $totalAmount,
                    'payment_method' => $payment_method,
                ];

                header('Location: /17_5/Product/orderConfirmation'); // Sửa đường dẫn ở đây
                exit;

            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Đã xảy ra lỗi khi xử lý đơn hàng: ' . $e->getMessage()];
                header('Location: /17_5/Product/checkout'); // Sửa đường dẫn ở đây
                exit;
            }

        } else {
            header('Location: /17_5/Product/cart'); // Sửa đường dẫn ở đây
            exit;
        }
    }

    public function orderConfirmation()
    {
        $order_details = $_SESSION['last_order_details'] ?? null;

        if ($order_details) {
            unset($_SESSION['last_order_details']);
        } else {
            $_SESSION['message'] = ['type' => 'info', 'text' => 'Không có thông tin đơn hàng để hiển thị.'];
            header('Location: /17_5/Product/list'); 
            exit;
        }
        
        include ROOT_PATH . 'app/views/product/orderConfirmation.php';
    }

    public function list()
    {
        $this->index();
    }
}