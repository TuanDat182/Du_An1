<?php
// data/db.php hoặc index.php đã bắt đầu session
// và đã tạo biến kết nối $conn

// Biến giỏ hàng mặc định
$cart = [];
$totalQty = 0;
$totalPrice = 0;

// Chỉ lấy dữ liệu giỏ hàng nếu người dùng đã đăng nhập
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // SỬA LỖI: Sử dụng biến $conn, không phải $pdo.
    // Và sửa lại câu SQL để dùng prepared statements đúng cách với MySQLi
    $stmt = $conn->prepare("
        SELECT 
            ci.quantity, 
            p.product_id, 
            p.name, 
            p.price, 
            p.image_url 
        FROM cart_items ci 
        JOIN products p ON ci.product_id = p.product_id 
        WHERE ci.user_id = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result) {
        $cart = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($cart as $item) {
            $totalQty += $item['quantity'];
            $totalPrice += $item['price'] * $item['quantity'];
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Electro - Cửa hàng Laptop</title>

    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
    <!-- Bootstrap -->
    <link type="text/css" rel="stylesheet" href="assets/css/bootstrap.min.css"/>
    <!-- Slick -->
    <link type="text/css" rel="stylesheet" href="assets/css/slick.css"/>
    <link type="text/css" rel="stylesheet" href="assets/css/slick-theme.css"/>
    <!-- nouislider -->
    <link type="text/css" rel="stylesheet" href="assets/css/nouislider.min.css"/>
    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>
    <!-- HEADER -->
    <header>
        <!-- TOP HEADER -->
        <div id="top-header">
            <div class="container">
                <ul class="header-links pull-left">
                    <li><a href="#"><i class="fa fa-phone"></i> +021-95-51-84</a></li>
                    <li><a href="#"><i class="fa fa-envelope-o"></i> email@email.com</a></li>
                    <li><a href="#"><i class="fa fa-map-marker"></i> 1734 Stonecoal Road</a></li>
                </ul>
                <ul class="header-links pull-right">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="#"><i class="fa fa-user-o"></i> Tài khoản của tôi</a></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
                    <?php else: ?>
                        <li><a href="login.php"><i class="fa fa-user-o"></i> Đăng nhập</a></li>
                        <li><a href="register.php"><i class="fa fa-user-plus"></i> Đăng ký</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <!-- /TOP HEADER -->

        <!-- MAIN HEADER -->
        <div id="header">
            <div class="container">
                <div class="row">
                    <!-- LOGO -->
                    <div class="col-md-3">
                        <div class="header-logo">
                            <a href="index.php" class="logo">
                                <img src="./assets/img/logo.png" alt="">
                            </a>
                        </div>
                    </div>
                    <!-- /LOGO -->

                    <!-- SEARCH BAR -->
                    <div class="col-md-6">
                        <div class="header-search">
                            <form>
                                <input class="input" placeholder="Tìm kiếm ở đây">
                                <button class="search-btn">Tìm kiếm</button>
                            </form>
                        </div>
                    </div>
                    <!-- /SEARCH BAR -->

                    <!-- ACCOUNT -->
                    <div class="col-md-3 clearfix">
                        <div class="header-ctn">
                            <!-- Cart -->
                            <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span>Giỏ hàng</span>
                                    <div class="qty"><?php echo $totalQty; ?></div>
                                </a>
                                <div class="cart-dropdown">
                                    <div class="cart-list">
                                        <?php if (!empty($cart)): ?>
                                            <?php foreach ($cart as $item): ?>
                                            <div class="product-widget">
                                                <div class="product-img">
                                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="">
                                                </div>
                                                <div class="product-body">
                                                    <h3 class="product-name"><a href="#"><?php echo htmlspecialchars($item['name']); ?></a></h3>
                                                    <h4 class="product-price"><span class="qty"><?php echo $item['quantity']; ?>x</span><?php echo number_format($item['price']); ?> VND</h4>
                                                </div>
                                                <!-- Nút xóa có thể thêm sau -->
                                                <!-- <button class="delete"><i class="fa fa-close"></i></button> -->
                                            </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p style="padding: 15px; text-align: center;">Giỏ hàng của bạn đang trống.</p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="cart-summary">
                                        <small><?php echo $totalQty; ?> sản phẩm được chọn</small>
                                        <h5>TỔNG CỘNG: <?php echo number_format($totalPrice); ?> VND</h5>
                                    </div>
                                    <div class="cart-btns">
                                        <a href="#">Xem giỏ hàng</a>
                                        <a href="#">Thanh toán <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <!-- /Cart -->

                            <!-- Menu Toogle -->
                            <div class="menu-toggle">
                                <a href="#">
                                    <i class="fa fa-bars"></i>
                                    <span>Menu</span>
                                </a>
                            </div>
                            <!-- /Menu Toogle -->
                        </div>
                    </div>
                    <!-- /ACCOUNT -->
                </div>
            </div>
        </div>
        <!-- /MAIN HEADER -->
    </header>
    <!-- /HEADER -->

    <!-- NAVIGATION -->
    <nav id="navigation">
        <div class="container">
            <div id="responsive-nav">
                <ul class="main-nav nav navbar-nav">
                    <li class="active"><a href="index.php">Trang chủ</a></li>
                    <li><a href="#">Ưu đãi nóng</a></li>
                    <li><a href="#">Danh mục</a></li>
                    <li><a href="#">Laptop</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- /NAVIGATION -->
</body>
</html>

