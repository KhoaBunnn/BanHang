<?php
// Bắt đầu session ở đầu file để đảm bảo nó luôn có sẵn cho mọi request
// Kiểm tra xem session đã được khởi động chưa để tránh lỗi
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// =========================================================
// THAY ĐỔI QUAN TRỌNG: 1. Định nghĩa đường dẫn gốc (ROOT_PATH)
// =========================================================
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR); 
// __DIR__ là thư mục chứa file index.php này (ví dụ: C:\laragon\www\17_5\)
// DIRECTORY_SEPARATOR đảm bảo dấu phân cách đường dẫn đúng trên mọi HĐH (vd: / hoặc \)

// =========================================================
// 2. Tải các file cấu hình và Models cần thiết
// Sử dụng require_once để đảm bảo mỗi file chỉ được tải một lần
// =========================================================

// Tải file cấu hình cơ sở dữ liệu
require_once ROOT_PATH . 'app/config/database.php';

// Tải tất cả các Model mà ứng dụng có thể sử dụng.
// Điều này đảm bảo các lớp Model có sẵn trước khi bất kỳ Controller nào được khởi tạo.
require_once ROOT_PATH . 'app/models/ProductModel.php';
require_once ROOT_PATH . 'app/models/CategoryModel.php';
// Thêm các Model khác nếu bạn có (ví dụ: OrderModel, UserModel, v.v.)
// require_once ROOT_PATH . 'app/models/OrderModel.php';
// require_once ROOT_PATH . 'app/models/OrderItemModel.php';

// Tải tất cả các Controller.
// Điều này đảm bảo các lớp Controller có sẵn để router có thể khởi tạo chúng.
// Nếu bạn có nhiều controllers, hãy thêm chúng vào đây.
require_once ROOT_PATH . 'app/controllers/ProductController.php';
require_once ROOT_PATH . 'app/controllers/DefaultController.php';


// =========================================================
// 3. Phân tích URL để xác định Controller, Action và tham số
// =========================================================

// Lấy tham số 'url' từ query string, làm sạch và tách thành mảng
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/'); // Xóa dấu '/' cuối cùng
$url = filter_var($url, FILTER_SANITIZE_URL); // Lọc các ký tự không an toàn trong URL
$url = explode('/', $url); // Tách URL thành các phần tử mảng

// Xác định tên Controller (mặc định là 'DefaultController' nếu không có gì trong URL)
// Chuyển đổi phần tử đầu tiên của URL thành tên Controller chuẩn (ví dụ: product -> ProductController)
$controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'DefaultController';

// Xác định tên Action/Method (mặc định là 'index' nếu không có action nào trong URL)
$action = !empty($url[1]) ? $url[1] : 'index';

// Lấy các tham số còn lại trong URL (từ phần tử thứ 3 trở đi)
$params = array_slice($url, 2);


// =========================================================
// 4. Khởi tạo Controller và gọi Action tương ứng
// =========================================================

// Kiểm tra xem lớp Controller có tồn tại không
if (!class_exists($controllerName)) {
    // Nếu Controller không tồn tại, có thể chuyển hướng về trang lỗi 404
    // hoặc chuyển hướng về DefaultController/index
    // Để đơn giản, hiện tại sẽ hiển thị lỗi
    die('Error: Controller class "' . $controllerName . '" not found.');
}

// Khởi tạo đối tượng Controller
$controller = new $controllerName();

// Kiểm tra xem phương thức (action) có tồn tại trong Controller không
if (!method_exists($controller, $action)) {
    // Nếu action không tồn tại, có thể chuyển hướng về trang lỗi 404
    // hoặc gọi một phương thức mặc định khác trong Controller (ví dụ: _404NotFound)
    die('Error: Action "' . $action . '" not found in "' . $controllerName . '".');
}

// Gọi phương thức action với các tham số đã lấy được từ URL
// call_user_func_array sẽ truyền mảng $params như các đối số riêng lẻ vào phương thức
call_user_func_array([$controller, $action], $params);