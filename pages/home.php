<?php
// Luôn bắt đầu session ở đầu file
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Nạp file kết nối CSDL, sử dụng __DIR__ để có đường dẫn tuyệt đối an toàn
require_once __DIR__ . '/../data/db.php';

// CHỈ XỬ LÝ GIỎ HÀNG KHI NGƯỜI DÙNG ĐÃ ĐĂNG NHẬP
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $cart = [];
    $totalQty = 0;
    $totalPrice = 0;

    // Truy vấn để lấy thông tin giỏ hàng từ CSDL
    $stmt = $pdo->prepare(
        "SELECT ci.quantity, p.product_id, p.name, p.price, p.image_url 
         FROM cart_items ci 
         JOIN products p ON ci.product_id = p.product_id 
         WHERE ci.user_id = ?"
    );
    $stmt->execute([$userId]);
    $cart_items = $stmt->fetchAll();

    // Tính toán tổng số lượng và tổng giá
    foreach ($cart_items as $item) {
        $cart[] = $item;
        $totalQty += $item['quantity'];
        $totalPrice += $item['price'] * $item['quantity'];
    }
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
    <link type="text/css" rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <!-- Slick -->
    <link type="text/css" rel="stylesheet" href="assets/css/slick.css" />
    <link type="text/css" rel="stylesheet" href="assets/css/slick-theme.css" />
    <!-- nouislider -->
    <link type="text/css" rel="stylesheet" href="assets/css/nouislider.min.css" />
    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="assets/css/style.css" />
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
                    <li><a href="#"><i class="fa fa-dollar"></i> USD</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
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
                                <select class="input-select">
                                    <option value="0">All Categories</option>
                                </select>
                                <input class="input" placeholder="Search here">
                                <button class="search-btn">Search</button>
                            </form>
                        </div>
                    </div>
                    <!-- /SEARCH BAR -->

                    <!-- ACCOUNT -->
                    <div class="col-md-3 clearfix">
                        <div class="header-ctn">
                            <!-- Wishlist -->
                            <div>
                                <a href="#">
                                    <i class="fa fa-heart-o"></i>
                                    <span>Your Wishlist</span>
                                    <div class="qty">2</div>
                                </a>
                            </div>
                            <!-- /Wishlist -->

                            <!-- Cart: CHỈ HIỂN THỊ KHI ĐÃ ĐĂNG NHẬP -->
                            <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span>Giỏ hàng</span>
                                    <div class="qty"><?php echo $totalQty; ?></div>
                                </a>
                                <div class="cart-dropdown">
                                    <?php if (!empty($cart)): ?>
                                        <div class="cart-list">
                                            <?php foreach ($cart as $item): ?>
                                            <div class="product-widget">
                                                <div class="product-img">
                                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="">
                                                </div>
                                                <div class="product-body">
                                                    <h3 class="product-name"><a href="#"><?php echo htmlspecialchars($item['name']); ?></a></h3>
                                                    <h4 class="product-price"><span class="qty"><?php echo $item['quantity']; ?>x</span>$<?php echo number_format($item['price'], 2); ?></h4>
                                                </div>
                                                <button class="delete"><i class="fa fa-close"></i></button>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="cart-summary">
                                            <small><?php echo $totalQty; ?> sản phẩm đã chọn</small>
                                            <h5>TỔNG CỘNG: $<?php echo number_format($totalPrice, 2); ?></h5>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center" style="padding: 20px;">Giỏ hàng của bạn đang trống.</div>
                                    <?php endif; ?>
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
                    <li class="active"><a href="index.php">Home</a></li>
                    <li><a href="#">Hot Deals</a></li>
                    <li><a href="#">Categories</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- /NAVIGATION -->

