<?php
// Giả định rằng file data/db.php đã được include trong index.php
// và đã gọi session_start().

// 1. KHỞI TẠO CÁC BIẾN CHO GIỎ HÀNG
$cart = [];
$totalQty = 0;
$totalPrice = 0;

// 2. KIỂM TRA TRẠNG THÁI ĐĂNG NHẬP
// Chúng ta giả sử rằng khi người dùng đăng nhập thành công, bạn sẽ lưu 'user_id' và 'username' vào session.
$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? 'My Account'; // Lấy username nếu có

// 3. LẤY DỮ LIỆU GIỎ HÀNG DỰA TRÊN TRẠNG THÁI ĐĂNG NHẬP
if ($isLoggedIn) {
    // --- LẤY GIỎ HÀNG TỪ DATABASE KHI ĐÃ ĐĂNG NHẬP ---
    global $pdo; // Lấy biến $pdo đã được tạo trong db.php
    $userId = $_SESSION['user_id'];
    
    // Câu lệnh SQL để lấy các sản phẩm trong giỏ hàng của người dùng cụ thể
    $sql = "SELECT p.product_id, p.name, p.price, p.image_url, ci.quantity 
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.product_id
            JOIN carts c ON ci.cart_id = c.cart_id
            WHERE c.user_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $cartItemsFromDb = $stmt->fetchAll();

    // Sắp xếp lại dữ liệu để phù hợp với cấu trúc hiển thị
    foreach ($cartItemsFromDb as $item) {
        $cart[$item['product_id']] = $item;
    }

} else {
    // --- LẤY GIỎ HÀNG TỪ SESSION KHI CHƯA ĐĂNG NHẬP ---
    $cart = $_SESSION['cart'] ?? [];
}

// 4. TÍNH TOÁN TỔNG SỐ LƯỢNG VÀ TỔNG GIÁ TRỊ GIỎ HÀNG
// Vòng lặp này hoạt động cho cả hai trường hợp (đã đăng nhập hoặc chưa)
foreach ($cart as $item) {
    $totalQty += $item['quantity'];
    $totalPrice += $item['price'] * $item['quantity'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Electro - HTML Ecommerce Template</title>

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
                    <li>
                        <?php if ($isLoggedIn): ?>
                            <a href="logout.php"><i class="fa fa-sign-out"></i> Logout (<?php echo htmlspecialchars($username); ?>)</a>
                        <?php else: ?>
                            <a href="login.php"><i class="fa fa-user-o"></i> My Account</a>
                        <?php endif; ?>
                    </li>
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
                                <img src="assets/img/logo.png" alt="">
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
                                    <option value="1">Category 01</option>
                                    <option value="1">Category 02</option>
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

                            <!-- Cart -->
                            <div class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span>Your Cart</span>
                                    <div class="qty"><?php echo $totalQty; ?></div>
                                </a>
                                <div class="cart-dropdown">
                                    <div class="cart-list">
                                        <?php if (empty($cart)): ?>
                                            <p style="padding: 15px; text-align: center;">Your cart is empty.</p>
                                        <?php else: ?>
                                            <?php foreach ($cart as $item): ?>
                                            <div class="product-widget">
                                                <div class="product-img">
                                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="">
                                                </div>
                                                <div class="product-body">
                                                    <h3 class="product-name"><a href="#"><?php echo htmlspecialchars($item['name']); ?></a></h3>
                                                    <h4 class="product-price">
                                                        <span class="qty"><?php echo $item['quantity']; ?>x</span> $<?php echo number_format($item['price'], 2); ?>
                                                    </h4>
                                                </div>
                                                <button class="delete"><i class="fa fa-close"></i></button>
                                            </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="cart-summary">
                                        <small><?php echo $totalQty; ?> Item(s) selected</small>
                                        <h5>SUBTOTAL: $<?php echo number_format($totalPrice, 2); ?></h5>
                                    </div>
                                    <div class="cart-btns">
                                        <a href="cart.php">View Cart</a>
                                        <a href="checkout.php">Checkout <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
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
                <!-- NAV -->
                <ul class="main-nav nav navbar-nav">
                    <li class="active"><a href="index.php">Home</a></li>
                    <li><a href="#">Hot Deals</a></li>
                    <li><a href="#">Categories</a></li>
                    <li><a href="#">Laptops</a></li>
                    <li><a href="#">Smartphones</a></li>
                    <li><a href="#">Cameras</a></li>
                    <li><a href="#">Accessories</a></li>
                </ul>
                <!-- /NAV -->
            </div>
        </div>
    </nav>
    <!-- /NAVIGATION -->
</body>
</html>

