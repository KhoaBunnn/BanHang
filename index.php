<?php
// Bắt đầu session ngay từ đầu để sử dụng $_SESSION
session_start();

// Định nghĩa ROOT_PATH để dễ dàng quản lý các đường dẫn include/require
// Nó sẽ trỏ đến thư mục gốc của project (ví dụ: C:\laragon\www\17_5\)
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);

// Require các file cần thiết
require_once ROOT_PATH . 'app/config/database.php';
require_once ROOT_PATH . 'app/models/ProductModel.php';
require_once ROOT_PATH . 'app/models/CategoryModel.php';
// Thêm các model khác nếu bạn có (ví dụ: OrderModel, UserModel)
// require_once ROOT_PATH . 'app/models/OrderModel.php';
// require_once ROOT_PATH . 'app/models/UserModel.php';


// Lấy đường dẫn URL hiện tại
$requestUri = trim($_SERVER['REQUEST_URI'], '/');

// Xóa bỏ prefix của thư mục gốc nếu có (ví dụ: /17_5/)
// Đảm bảo BASE_URL là thư mục gốc của project trên server
$baseURL = '17_5'; // Tên thư mục project của bạn
if (strpos($requestUri, $baseURL) === 0) {
    $requestUri = substr($requestUri, strlen($baseURL));
    $requestUri = trim($requestUri, '/'); // Xóa dấu '/' thừa sau khi cắt
}

// Phân tích URL thành các phần
$url = explode('/', $requestUri);

// Xác định tên Controller (mặc định là 'ProductController' nếu không có gì trong URL)
// Chuyển đổi phần tử đầu tiên của URL thành tên Controller chuẩn (ví dụ: product -> ProductController)
$controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'ProductController'; // Mặc định là ProductController
$controllerPath = ROOT_PATH . 'app/controllers/' . $controllerName . '.php';

// Xác định tên Action/Method (mặc định là 'index' nếu không có action nào trong URL)
$action = !empty($url[1]) ? $url[1] : 'index';

// Lấy các tham số còn lại trong URL (từ phần tử thứ 3 trở đi)
$params = array_slice($url, 2);

// Kiểm tra và khởi tạo Controller
if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controller = new $controllerName();

    // Kiểm tra xem phương thức (action) có tồn tại trong Controller không
    if (method_exists($controller, $action)) {
        // Gọi phương thức action với các tham số đã lấy được từ URL
        // call_user_func_array sẽ truyền các phần tử của $params làm đối số riêng lẻ
        call_user_func_array([$controller, $action], $params);
    } else {
        // Xử lý khi action không tồn tại
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found: Action '" . htmlspecialchars($action) . "' không tồn tại trong Controller '" . htmlspecialchars($controllerName) . "'.";
    }
} else {
    // Xử lý khi Controller không tồn tại
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found: Controller '" . htmlspecialchars($controllerName) . "' không tìm thấy.";
}
?>