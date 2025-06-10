<?php
session_start();
?>
<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beranda - GunnnPangan</title>
    <link href="https://fonts.googleapis.com/css?family=Cairo:400,600,700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400i,700i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu&amp;display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.png" />
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/nice-select.css">
    <link rel="stylesheet" type="text/css" href="assets/css/slick.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/main-color.css">
    <style>
        .login-button a {
            font-weight: 600;
            color: #347928;
            border: 1px solid #347928;
            transition: all 0.3s ease;
        }

        .login-button a:hover {
            background-color: #347928;
            color: #fff;
            text-decoration: none;
        }

        .logout-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .logout-list li {
            border-bottom: 1px solid #eee;
        }

        .logout-list li:last-child {
            border-bottom: none;
        }

        .logout-list li a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            transition: background 0.2s ease;
        }

        .logout-list li a:hover {
            background-color: #f2f2f2;
        }
        
        .square-image {
            width: 270px;
            height: 270px;
            object-fit: cover;
            /* Crop gambar agar isi tetap rapi */
            object-position: center;
            border-radius: 8px;
            /* opsional, biar lebih halus */
        }

        .square-detail-img {
            width: 500px;
            height: 500px;
            object-fit: cover;
            object-position: center;
            border-radius: 10px;
            /* opsional untuk sudut melengkung */
            display: block;
            margin: 0 auto;
        }
    </style>

</head>

<body class="biolife-body">

    <!-- Preloader -->
    <div id="biof-loading">
        <div class="biof-loading-center">
            <div class="biof-loading-center-absolute">
                <div class="dot dot-one"></div>
                <div class="dot dot-two"></div>
                <div class="dot dot-three"></div>
            </div>
        </div>
    </div>

    <!-- HEADER -->
    <header id="header" class="header-area style-01 layout-03">
        <div class="header-middle biolife-sticky-object ">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-6 d-flex align-items-center">
                        <a href="index.php" class="biolife-logo"><img src="assets/images/favicon.png" alt="biolife logo"><b style="font-size: 190% ; color: black;">GunnnPangan</b></a>
                    </div>
                    <div class="col-lg-6 col-md-6 d-none d-md-block text-center">
                        <div class="primary-menu">
                            <ul>
                                <li class="menu-item"><a href="index.php">Beranda</a></li>
                                <li>
                                    <a href="belanja.php">Belanja</a>
                                </li>
                                <li class="menu-item"><a href="contact.php">Hubungi Kami</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-6 d-flex justify-content-end align-items-center">
                        <div class="biolife-cart-info">
                            <?php if (isset($_SESSION['username'])): ?>
                                <?php
                                include 'admin/koneksi.php';
                                $user_id = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;

                                if ($user_id) {
                                    $query = "SELECT COUNT(*) as total FROM tb_pesanan WHERE id_user = '$user_id'";
                                    $result = mysqli_query($koneksi, $query);
                                    $data = mysqli_fetch_assoc($result);
                                    $jumlah_item = isset($data['total']) ? $data['total'] : 0;
                                } else {
                                    $jumlah_item = 0;
                                }
                                ?>
                                <div class="minicart-block">
                                    <div class="minicart-contain">
                                        <a href="javascript:void(0)" class="link-to">
                                            <span class="icon-qty-combine">
                                                <i class="icon-cart-mini biolife-icon"></i>
                                                <span class="qty"><?= $jumlah_item ?></span>
                                            </span>
                                            <span class="title">Keranjang</span>
                                        </a>
                                        <div class="cart-content">
                                            <div class="cart-inner">
                                                <ul class="products">
                                                   <?php
                                                    include 'admin/koneksi.php';
                                                    $user_id = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;

                                                    if ($user_id) {
                                                        $query = "SELECT p.*, pr.nm_produk, pr.harga, pr.gambar 
                    FROM tb_pesanan p 
                    JOIN tb_produk pr ON p.id_produk = pr.id_produk 
                    WHERE p.id_user = '$user_id'";
                                                        $result = mysqli_query($koneksi, $query);
                                                        $subtotal = 0;

                                                        while ($row = mysqli_fetch_assoc($result)):
                                                            $total_harga = $row['harga'] * $row['qty'];
                                                            $subtotal += $total_harga;
                                                            ?>
                                                            <li>
                                                                <div class="minicart-item">
                                                                    <div class="thumb">
                                                                        <a href="#"><img
                                                                                src="admin/produk_img/<?= $row['gambar'] ?>"
                                                                                width="90" height="90"
                                                                                alt="<?= $row['nm_produk'] ?>"></a>
                                                                    </div>
                                                                    <div class="left-info">
                                                                        <div class="product-title"><a href="#"
                                                                                class="product-name"><?= $row['nm_produk'] ?></a>
                                                                        </div>
                                                                        <div class="price">
                                                                            <ins><span class="price-amount"><span
                                                                                        class="currencySymbol">Rp.</span><?= number_format($row['harga'], 0, ',', '.') ?></span></ins>
                                                                        </div>
                                                                        <div class="qty">
                                                                            <label>Qty:</label>
                                                                            <input type="number" class="input-qty"
                                                                                value="<?= $row['qty'] ?>" disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="action">
                                                                        <a href="hapus_item.php?id=<?= $row['id_pesanan'] ?>"><i
                                                                                class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <?php
                                                        endwhile;
                                                    } else {
                                                        echo '<li><p style="padding: 10px;">Keranjang kosong.</p></li>';
                                                        $subtotal = 0;
                                                    }
                                                    ?>
                                                </ul>

                                                <p class="btn-control">
                                                    <a href="cart.php" class="btn view-cart">Lihat Keranjang</a>
                                                    <a href="#" class="btn" onclick="checkout()">checkout</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown user wishlist-block hidden-sm hidden-xs">
                                    <a class="dropdown-toggle d-flex align-items-center link-to" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="icon-qty-combine">
                                            <i class="fas fa-user biolife-icon"></i>
                                            <span class="qty"><?= htmlspecialchars($_SESSION['username']); ?></span> <!-- Ganti qty jadi username -->
                                        </span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                        <ul class="logout-list">
                                            <li><a href="logout.php">Logout</a></li>
                                        </ul>
                                    </div>
                                </div>
                            <?php else : ?>
                                <!-- Login Button (shown if not logged in) -->
                                <div class="login-button">
                                    <a href="login.php" class="btn btn-sm btn-outline-primary">Login</a>
                                </div>
                            <?php endif; ?>

                            <div class="mobile-menu-toggle">
                                <a class="btn-toggle" data-object="open-mobile-menu" href="javascript:void(0)">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Contain -->
    <div class="page-contain">

        <!-- Main content -->
        <div id="main-content" class="main-content">

            <!--Block 01: Main slide-->
            <div class="main-slide block-slider">
                <ul class="biolife-carousel nav-none-on-mobile" data-slick='{"arrows": true, "dots": false, "slidesMargin": 0, "slidesToShow": 1, "infinite": true, "speed": 800}'>
                    <li>
                        <div class="slide-contain slider-opt03__layout01">
                            <div class="media">
                                <div class="child-elememt">
                                    <a href="#" class="link-to">
                                        <img src="assets/images/home-03/slide-01-child-01.png" width="604" height="580" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="text-content">
                                <i class="first-line">Delima</i>
                                <h3 class="second-line">Sayuran 100% Organik</h3>
                                <p class="third-line">Campuran buah apel hijau segar dan buah perasan</p>
                                <p class="buttons">
                                    <a href="belanja.php" class="btn btn-bold">Belanja Yuk!</a>
                                </p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="slide-contain slider-opt03__layout01">
                            <div class="media">
                                <div class="child-elememt"><a href="#" class="link-to"><img src="assets/images/home-03/slide-01-child-01.png" width="604" height="580" alt=""></a></div>
                            </div>
                            <div class="text-content">
                                <i class="first-line">Delima</i>
                                <h3 class="second-line">Sayuran 100% Organik</h3>
                                <p class="third-line">Campuran buah apel hijau segar dan buah perasan</p>
                                <p class="buttons">
                                    <a href="belanja.php" class="btn btn-bold">Belanja Yuk!</a>
                                </p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <!--Block 02: Banner-->
            <div class="special-slide">
                <div class="container">
                    <ul class="biolife-carousel dots_ring_style" data-slick='{"arrows": false, "dots": true, "slidesMargin": 30, "slidesToShow": 1, "infinite": true, "speed": 800, "responsive":[{"breakpoint":1200, "settings":{ "slidesToShow": 1}},{"breakpoint":768, "settings":{ "slidesToShow": 2, "slidesMargin":20, "dots": false}},{"breakpoint":480, "settings":{ "slidesToShow": 1}}]}'>
                        <li>
                            <div class="slide-contain biolife-banner__special">
                                <div class="banner-contain">
                                    <div class="media">
                                        <a href="#" class="bn-link">
                                            <figure><img src="assets/images/home-03/bn_special_org.jpg" width="616" height="483" alt=""></figure>
                                        </a>
                                    </div>
                                    <div class="text-content">
                                        <b class="first-line">Delima</b>
                                        <span class="second-line">Buatan Surga Organik</span>
                                        <span class="third-line">Mudah <i>Hidup Sehat, Bahagia</i></span>
                                        <div class="product-detail">
                                            <h4 class="product-name">Produksi Buah Segar Nasional</h4>
                                            <div class="price price-contain">
                                                <ins><span class="price-amount"><span class="currencySymbol">Hanya Mulai&nbsp;</span>Rp. 20.000</span></ins>
                                            </div>
                                            <div class="buttons">
                                                <a href="belanja" class="btn add-to-cart-btn">Lihat Produk</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="slide-contain biolife-banner__special">
                                <div class="banner-contain">
                                    <div class="media">
                                        <a href="#" class="bn-link">
                                            <figure><img src="assets/images/home-03/bn_special_org.jpg" width="616" height="483" alt=""></figure>
                                        </a>
                                    </div>
                                    <div class="text-content">
                                        <b class="first-line">Delima</b>
                                        <span class="second-line">Buatan Surga Organik</span>
                                        <span class="third-line">Mudah <i>Hidup Sehat, Bahagia</i></span>
                                        <div class="product-detail">
                                            <h4 class="product-name">Produksi Buah Segar Nasional</h4>
                                            <div class="price price-contain">
                                                <ins><span class="price-amount"><span class="currencySymbol">Hanya Mulai&nbsp;</span>Rp. 20.000</span></ins>
                                            </div>
                                            <div class="buttons">
                                                <a href="belanja" class="btn add-to-cart-btn">Lihat Produk</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="slide-contain biolife-banner__special">
                                <div class="banner-contain">
                                    <div class="media">
                                        <a href="#" class="bn-link">
                                            <figure><img src="assets/images/home-03/bn_special_org.jpg" width="616" height="483" alt=""></figure>
                                        </a>
                                    </div>
                                    <div class="text-content">
                                        <b class="first-line">Delima</b>
                                        <span class="second-line">Buatan Surga Organik</span>
                                        <span class="third-line">Mudah <i>Hidup Sehat, Bahagia</i></span>
                                        <div class="product-detail">
                                            <h4 class="product-name">Produksi Buah Segar Nasional</h4>
                                            <div class="price price-contain">
                                                <ins><span class="price-amount"><span class="currencySymbol">Hanya Mulai&nbsp;</span>Rp. 20.000</span></ins>
                                            </div>
                                            <div class="buttons">
                                                <a href="belanja" class="btn add-to-cart-btn">Lihat Produk</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="slide-contain biolife-banner__special">
                                <div class="banner-contain">
                                    <div class="media">
                                        <a href="#" class="bn-link">
                                            <figure><img src="assets/images/home-03/bn_special_org.jpg" width="616" height="483" alt=""></figure>
                                        </a>
                                    </div>
                                    <div class="text-content">
                                        <b class="first-line">Delima</b>
                                        <span class="second-line">Buatan Surga Organik</span>
                                        <span class="third-line">Mudah <i>Hidup Sehat, Bahagia</i></span>
                                        <div class="product-detail">
                                            <h4 class="product-name">Produksi Buah Segar Nasional</h4>
                                            <div class="price price-contain">
                                                <ins><span class="price-amount"><span class="currencySymbol">Hanya Mulai&nbsp;</span>Rp. 20.000</span></ins>
                                            </div>
                                            <div class="buttons">
                                                <a href="belanja" class="btn add-to-cart-btn">Lihat Produk</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="biolife-service type01 biolife-service__type01 sm-margin-top-0 xs-margin-top-45px">
                        <b class="txt-show-01">100%Alam</b>
                        <i class="txt-show-02">Buah Segar</i>
                        <ul class="services-list">
                            <li>
                                <div class="service-inner">
                                    <span class="number">1</span>
                                    <span class="biolife-icon icon-beer"></span>
                                    <a class="srv-name" href="#">produk berstempel penuh</a>
                                </div>
                            </li>
                            <li>
                                <div class="service-inner">
                                    <span class="number">2</span>
                                    <span class="biolife-icon icon-schedule"></span>
                                    <a class="srv-name" href="#">tempat dan pengiriman tepat waktu</a>
                                </div>
                            </li>
                            <li>
                                <div class="service-inner">
                                    <span class="number">3</span>
                                    <span class="biolife-icon icon-car"></span>
                                    <a class="srv-name" href="#">Gratis ongkos kirim dalam kota</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!--Block 03: Product Tab-->
            <div class="product-tab z-index-20 sm-margin-top-193px xs-margin-top-30px">
                <div class="container">
                    <div class="biolife-title-box">
                        <span class="subtitle">Semua barang terbaik untuk Anda</span>
                        <h3 class="main-title">Produk Terkait</h3>
                    </div>
                    <div class="biolife-tab biolife-tab-contain sm-margin-top-34px">
                        <div class="tab-content">
                            <div id="tab01_1st" class="tab-contain active">
                                <?php
                                include "admin/koneksi.php"; // koneksi ke database

                                // Query untuk join tb_produk dan tb_ktg, mengambil 8 data acak
                                $query = "
    SELECT p.*, k.nm_ktg FROM tb_produk p 
    JOIN tb_kategori k ON p.id_ktg = k.id_ktg 
    ORDER BY RAND() 
    LIMIT 8
";
                                $result = mysqli_query($koneksi, $query);
                                ?>

                                <ul class="products-list biolife-carousel nav-center-02 nav-none-on-mobile eq-height-contain" data-slick='{"rows":2 ,"arrows":true,"dots":false,"infinite":true,"speed":400,"slidesMargin":10,"slidesToShow":4, "responsive":[{"breakpoint":1200, "settings":{ "slidesToShow": 4}},{"breakpoint":992, "settings":{ "slidesToShow": 3, "slidesMargin":25 }},{"breakpoint":768, "settings":{ "slidesToShow": 2, "slidesMargin":15}}]}'>
                                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                        <li class="product-item">
                                            <div class="contain-product layout-default">
                                                <div class="product-thumb">
                                                    <a href="detail_produk.php?id=<?= $row['id_produk'] ?>" class="link-to-product">
                                                        <img src="admin/produk_img/<?= $row['gambar'] ?>" alt="<?= $row['nm_produk'] ?>" class="product-thumbnail square-image">
                                                    </a>
                                                </div>
                                                <div class="info">
                                                    <b class="categories"><?= $row['nm_ktg'] ?></b>
                                                    <h4 class="product-title">
                                                        <a href="detail_produk.php?id=<?= $row['id_produk'] ?>" class="pr-name"><?= $row['nm_produk'] ?></a>
                                                    </h4>
                                                    <div class="price">
                                                        <ins><span class="price-amount"><span class="currencySymbol">Rp.</span><?= number_format($row['harga'], 0, ',', '.') ?></span></ins>
                                                    </div>
                                                    <div class="slide-down-box">
                                                        <p class="message"><?= $row['desk'] ?></p>
                                                        <div class="buttons">
                                                            <a href="detail_produk.php?id=<?= $row['id_produk'] ?>" class="btn add-to-cart-btn">
                                                                <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>Keranjang
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Block 04: Banner Promotion 01 -->
            <div class="banner-promotion-01 xs-margin-top-50px sm-margin-top-11px">
                <div class="biolife-banner promotion biolife-banner__promotion">
                    <div class="banner-contain">
                        <div class="media background-biolife-banner__promotion">
                            <div class="img-moving position-1">
                                <img src="assets/images/home-03/img-moving-pst-1.png" width="149" height="139" alt="img msv">
                            </div>
                            <div class="img-moving position-2">
                                <img src="assets/images/home-03/img-moving-pst-2.png" width="185" height="265" alt="img msv">
                            </div>
                            <div class="img-moving position-3">
                                <img src="assets/images/home-03/img-moving-pst-3-cut.png" width="384" height="151" alt="img msv">
                            </div>
                            <div class="img-moving position-4">
                                <img src="assets/images/home-03/img-moving-pst-4.png" width="198" height="269" alt="img msv">
                            </div>
                        </div>
                        <div class="text-content">
                            <div class="container text-wrap">
                                <i class="first-line">Jus Buah Sehat</i>
                                <span class="second-line">Sayuran Selalu segar</span>
                                <p class="third-line">Food Heaven Made Easy kedengarannya seperti nama layanan pengiriman makanan yang luar biasa lezat, tetapi jangan terkecoh...</p>
                                <div class="product-detail">
                                    <p class="txt-price"><span>Hanya:&nbsp;</span>Rp. 20.000</p>
                                    <a href="#" class="btn add-to-cart-btn">Keranjang</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Block 05: Banner Promotion 02-->
            <div class="banner-promotion-02 z-index-20">
                <div class="biolife-banner promotion2 biolife-banner__promotion2">
                    <div class="banner-contain">
                        <div class="container">
                            <div class="media"></div>
                            <div class="text-content">
                                <b class="first-line">Makanan Buatan Surga</b>
                                <span class="second-line">Mudah <i>Hidup Sehat, Bahagia</i></span>
                                <p class="third-line">Food Heaven Made Easy kedengarannya seperti nama layanan pengiriman makanan yang luar biasa lezat, tetapi jangan tertipu. Blog ini sebenarnya merupakan kompilasi resep, video memasak, dan kiat nutrisi.</p>
                                <p class="buttons">
                                    <a href="#" class="btn btn-bold">Baca selengkapnya</a>
                                    <a href="#" class="btn btn-thin">Lihat Menu Sekarang</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Block 06: Products-->
            <div class="Product-box sm-margin-top-96px xs-margin-top-0">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-5 col-sm-6">
                            <div class="advance-product-box">
                                <div class="biolife-title-box bold-style biolife-title-box__bold-style">
                                    <h3 class="title">Penawaran hari ini</h3>
                                </div>
                                <ul class="products biolife-carousel nav-top-right nav-none-on-mobile" data-slick='{"arrows":true, "dots":false, "infinite":false, "speed":400, "slidesMargin":30, "slidesToShow":1}'>
                                    <li class="product-item">
                                        <div class="contain-product deal-layout contain-product__deal-layout">
                                            <div class="product-thumb">
                                                <a href="#" class="link-to-product">
                                                    <img src="assets/images/home-03/product_deal_330x330.jpg" alt="dd" width="330" height="330" class="product-thumnail">
                                                </a>
                                                <div class="labels">
                                                    <span class="sale-label">-50%</span>
                                                </div>
                                            </div>
                                            <div class="info">
                                                <div class="biolife-countdown" data-datetime="2020/02/18 00:00:00">
                                                </div>
                                                <b class="categories">Buah Segar</b>
                                                <h4 class="product-title"><a href="#" class="pr-name">Segar Nasional
                                                        Buah</a></h4>
                                                <div class="price ">
                                                    <ins><span class="price-amount"><span class="currencySymbol">Rp.</span>20.000</span></ins>
                                                </div>
                                                <div class="slide-down-box">
                                                    <p class="message">Semua produk dipilih dengan cermat untuk memastikan keamanan pangan.</p>
                                                    <div class="buttons">
                                                        <a href="belanja.php" class="btn add-to-cart-btn">Lihat Produk</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="product-item">
                                        <div class="contain-product deal-layout contain-product__deal-layout">
                                            <div class="product-thumb">
                                                <a href="#" class="link-to-product">
                                                    <img src="assets/images/home-03/product_deal-02_330x330.jpg" alt="dd" width="330" height="330" class="product-thumnail">
                                                </a>
                                                <div class="labels">
                                                    <span class="sale-label">-50%</span>
                                                </div>
                                            </div>
                                            <div class="info">
                                                <div class="biolife-countdown" data-datetime="2020/02/18 00:00:00">
                                                </div>
                                                <b class="categories">Buah Segar</b>
                                                <h4 class="product-title"><a href="#" class="pr-name">Segar Nasional
                                                        Buah</a></h4>
                                                <div class="price ">
                                                    <ins><span class="price-amount"><span class="currencySymbol">Rp.</span>20.000</span></ins>
                                                </div>
                                                <div class="slide-down-box">
                                                    <p class="message">Semua produk dipilih dengan cermat untuk memastikan keamanan pangan.</p>
                                                    <div class="buttons">
                                                        <a href="belanja.php" class="btn add-to-cart-btn">Lihat Produk</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="product-item">
                                        <div class="contain-product deal-layout contain-product__deal-layout">
                                            <div class="product-thumb">
                                                <a href="#" class="link-to-product">
                                                    <img src="assets/images/home-03/product_deal-03_330x330.jpg" alt="dd" width="330" height="330" class="product-thumnail">
                                                </a>
                                                <div class="labels">
                                                    <span class="sale-label">-50%</span>
                                                </div>
                                            </div>
                                            <div class="info">
                                                <div class="biolife-countdown" data-datetime="2020/02/18 00:00:00">
                                                </div>
                                                <b class="categories">Buah Segar</b>
                                                <h4 class="product-title"><a href="#" class="pr-name">Segar Nasional
                                                        Buah</a></h4>
                                                <div class="price ">
                                                    <ins><span class="price-amount"><span class="currencySymbol">Rp.</span>20.000</span></ins>
                                                </div>
                                                <div class="slide-down-box">
                                                    <p class="message">Semua produk dipilih dengan cermat untuk memastikan keamanan pangan.</p>
                                                    <div class="buttons">
                                                        <a href="belanja.php" class="btn add-to-cart-btn">Lihat Produk</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-7 col-sm-6">
                            <div class="advance-product-box">
                                <div class="biolife-title-box bold-style biolife-title-box__bold-style">
                                    <h3 class="title">Investasi Terbaik untuk Tubuh Sehatmu!</h3>
                                </div>
                                <div class="biolife-banner style-01 biolife-banner__style-01 xs-margin-top-80px-im sm-margin-top-30px-im">
                                    <div class="banner-contain">
                                        <a href="#" class="bn-link"></a>
                                        <div class="text-content">
                                            <span class="first-line">Segar Setiap Hari</span>
                                            <b class="second-line">Alami</b>
                                            <i class="third-line">Makanan Segar</i>
                                            <span class="fourth-line">Kualitas Premium</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- FOOTER -->
    <footer id="footer" class="footer layout-03">
        <div class="footer-content background-footer-03">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-9">
                        <section class="footer-item">
                            <a href="index.php" class="biolife-logo"><img src="assets/images/favicon.png" alt="biolife logo"><b style="font-size: 190% ; color: black;">GunnnPangan</b></a>
                            <div class="footer-phone-info">
                                <i class="biolife-icon icon-head-phone"></i>
                                <p class="r-info">
                                    <span>Ada Pertanyaan ?</span>
                                    <span>0838-6520-9331</span>
                                </p>
                            </div>
                        </section>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 md-margin-top-5px sm-margin-top-50px xs-margin-top-40px">
                        <section class="footer-item">

                        </section>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 md-margin-top-5px sm-margin-top-50px xs-margin-top-40px">
                        <section class="footer-item">
                            <h3 class="section-title">Layanan Transportasi</h3>
                            <div class="contact-info-block footer-layout xs-padding-top-10px">
                                <ul class="contact-lines">
                                    <li>
                                        <p class="info-item">
                                            <i class="biolife-icon icon-location"></i>
                                            <b class="desc">Tanjung-Kedungtuban, Blora, Jawa Tengah. Indonesia</b>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="info-item">
                                            <i class="biolife-icon icon-phone"></i>
                                            <b class="desc">Telepon: 0838-6520-9331</b>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="info-item">
                                            <i class="biolife-icon icon-letter"></i>
                                            <b class="desc">Email: gunnnpangan@gmail.com</b>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="info-item">
                                            <i class="biolife-icon icon-clock"></i>
                                            <b class="desc">Jam Buka: Senin - Kamis, Mulai Pukul 08.00 - 20.00</b>
                                        </p>
                                    </li>
                                </ul>
                            </div>
                            <div class="biolife-social inline">
                                <ul class="socials">
                                   <li><a href="https://www.instagram.com/4rlgnwn?igsh=MWx3bG84c2F6MGJrdw==" title="instagram" class="socail-btn"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                </ul>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="separator sm-margin-top-70px xs-margin-top-40px"></div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-xs-12">
                        <div class="copy-right-text">
                            <p><a href="templateshub.net">&copy; Copyright <strong><span>2025</span></strong>. All Rights Reserved</a></p>
                        </div>

                    </div>
                    <div class="col-lg-6 col-sm-6 col-xs-12">
                        <div class="payment-methods">
                            <ul>
                                <li><a href="#" class="payment-link"><img src="assets/images/card1.jpg" width="51" height="36" alt=""></a></li>
                                <li><a href="#" class="payment-link"><img src="assets/images/card2.jpg" width="51" height="36" alt=""></a></li>
                                <li><a href="#" class="payment-link"><img src="assets/images/card3.jpg" width="51" height="36" alt=""></a></li>
                                <li><a href="#" class="payment-link"><img src="assets/images/card4.jpg" width="51" height="36" alt=""></a></li>
                                <li><a href="#" class="payment-link"><img src="assets/images/card5.jpg" width="51" height="36" alt=""></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!--Footer For Mobile-->
    <div class="mobile-footer">
        <div class="mobile-footer-inner">
            <div class="mobile-block block-menu-main">
                <a class="menu-bar menu-toggle btn-toggle" data-object="open-mobile-menu" href="javascript:void(0)">
                    <span class="fa fa-bars"></span>
                    <span class="text">Menu</span>
                </a>
            </div>
            <div class="mobile-block block-sidebar">
                <a class="menu-bar filter-toggle btn-toggle" data-object="open-mobile-filter" href="javascript:void(0)">
                    <i class="fa fa-sliders" aria-hidden="true"></i>
                    <span class="text">Sidebar</span>
                </a>
            </div>
            <div class="mobile-block block-minicart">
                <a class="link-to-cart" href="#">
                    <span class="fa fa-shopping-bag" aria-hidden="true"></span>
                    <span class="text">Cart</span>
                </a>
            </div>
            <div class="mobile-block block-global">
                <a class="menu-bar myaccount-toggle btn-toggle" data-object="global-panel-opened" href="javascript:void(0)">
                    <span class="fa fa-globe"></span>
                    <span class="text">Global</span>
                </a>
            </div>
        </div>
    </div>

    <!--Mobile Global Menu-->
    <div class="mobile-block-global">
        <div class="biolife-mobile-panels">
            <span class="biolife-current-panel-title">Global</span>
            <a class="biolife-close-btn" data-object="global-panel-opened" href="#">&times;</a>
        </div>
        <div class="block-global-contain">
            <div class="glb-item my-account">
                <b class="title">My Account</b>
                <ul class="list">
                    <li class="list-item"><a href="#">Login/register</a></li>
                    <li class="list-item"><a href="#">Wishlist <span class="index">(8)</span></a></li>
                    <li class="list-item"><a href="#">Checkout</a></li>
                </ul>
            </div>
            <div class="glb-item currency">
                <b class="title">Currency</b>
                <ul class="list">
                    <li class="list-item"><a href="#">€ EUR (Euro)</a></li>
                    <li class="list-item"><a href="#">$ USD (Dollar)</a></li>
                    <li class="list-item"><a href="#">£ GBP (Pound)</a></li>
                    <li class="list-item"><a href="#">¥ JPY (Yen)</a></li>
                </ul>
            </div>
            <div class="glb-item languages">
                <b class="title">Language</b>
                <ul class="list inline">
                    <li class="list-item"><a href="#"><img src="assets/images/languages/us.jpg" alt="flag" width="24" height="18"></a></li>
                    <li class="list-item"><a href="#"><img src="assets/images/languages/fr.jpg" alt="flag" width="24" height="18"></a></li>
                    <li class="list-item"><a href="#"><img src="assets/images/languages/ger.jpg" alt="flag" width="24" height="18"></a></li>
                    <li class="list-item"><a href="#"><img src="assets/images/languages/jap.jpg" alt="flag" width="24" height="18"></a></li>
                </ul>
            </div>
        </div>
    </div>

    <!--Quickview Popup-->
    <div id="biolife-quickview-block" class="biolife-quickview-block">
        <div class="quickview-container">
            <a href="#" class="btn-close-quickview" data-object="open-quickview-block"><span class="biolife-icon icon-close-menu"></span></a>
            <div class="biolife-quickview-inner">
                <div class="media">
                    <ul class="biolife-carousel quickview-for" data-slick='{"arrows":false,"dots":false,"slidesMargin":30,"slidesToShow":1,"slidesToScroll":1,"fade":true,"asNavFor":".quickview-nav"}'>
                        <li><img src="assets/images/details-product/detail_01.jpg" alt="" width="500" height="500"></li>
                        <li><img src="assets/images/details-product/detail_02.jpg" alt="" width="500" height="500"></li>
                        <li><img src="assets/images/details-product/detail_03.jpg" alt="" width="500" height="500"></li>
                        <li><img src="assets/images/details-product/detail_04.jpg" alt="" width="500" height="500"></li>
                        <li><img src="assets/images/details-product/detail_05.jpg" alt="" width="500" height="500"></li>
                        <li><img src="assets/images/details-product/detail_06.jpg" alt="" width="500" height="500"></li>
                        <li><img src="assets/images/details-product/detail_07.jpg" alt="" width="500" height="500"></li>
                    </ul>
                    <ul class="biolife-carousel quickview-nav" data-slick='{"arrows":true,"dots":false,"centerMode":false,"focusOnSelect":true,"slidesMargin":10,"slidesToShow":3,"slidesToScroll":1,"asNavFor":".quickview-for"}'>
                        <li><img src="assets/images/details-product/thumb_01.jpg" alt="" width="88" height="88"></li>
                        <li><img src="assets/images/details-product/thumb_02.jpg" alt="" width="88" height="88"></li>
                        <li><img src="assets/images/details-product/thumb_03.jpg" alt="" width="88" height="88"></li>
                        <li><img src="assets/images/details-product/thumb_04.jpg" alt="" width="88" height="88"></li>
                        <li><img src="assets/images/details-product/thumb_05.jpg" alt="" width="88" height="88"></li>
                        <li><img src="assets/images/details-product/thumb_06.jpg" alt="" width="88" height="88"></li>
                        <li><img src="assets/images/details-product/thumb_07.jpg" alt="" width="88" height="88"></li>
                    </ul>
                </div>
                <div class="product-attribute">
                    <h4 class="title"><a href="#" class="pr-name">National Fresh Fruit</a></h4>
                    <div class="rating">
                        <p class="star-rating"><span class="width-80percent"></span></p>
                    </div>

                    <div class="price price-contain">
                        <ins><span class="price-amount"><span class="currencySymbol">£</span>85.00</span></ins>
                        <del><span class="price-amount"><span class="currencySymbol">£</span>95.00</span></del>
                    </div>
                    <p class="excerpt">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris vel maximus
                        lacus. Duis ut mauris eget justo dictum tempus sed vel tellus.</p>
                    <div class="from-cart">
                        <div class="qty-input">
                            <input type="text" name="qty12554" value="1" data-max_value="20" data-min_value="1" data-step="1">
                            <a href="#" class="qty-btn btn-up"><i class="fa fa-caret-up" aria-hidden="true"></i></a>
                            <a href="#" class="qty-btn btn-down"><i class="fa fa-caret-down" aria-hidden="true"></i></a>
                        </div>
                        <div class="buttons">
                            <a href="#" class="btn add-to-cart-btn btn-bold">add to cart</a>
                        </div>
                    </div>

                    <div class="product-meta">
                        <div class="product-atts">
                            <div class="product-atts-item">
                                <b class="meta-title">Categories:</b>
                                <ul class="meta-list">
                                    <li><a href="#" class="meta-link">Milk & Cream</a></li>
                                    <li><a href="#" class="meta-link">Fresh Meat</a></li>
                                    <li><a href="#" class="meta-link">Fresh Fruit</a></li>
                                </ul>
                            </div>
                            <div class="product-atts-item">
                                <b class="meta-title">Tags:</b>
                                <ul class="meta-list">
                                    <li><a href="#" class="meta-link">food theme</a></li>
                                    <li><a href="#" class="meta-link">organic food</a></li>
                                    <li><a href="#" class="meta-link">organic theme</a></li>
                                </ul>
                            </div>
                            <div class="product-atts-item">
                                <b class="meta-title">Brand:</b>
                                <ul class="meta-list">
                                    <li><a href="#" class="meta-link">Fresh Fruit</a></li>
                                </ul>
                            </div>
                        </div>
                        <span class="sku">SKU: N/A</span>
                        <div class="biolife-social inline add-title">
                            <span class="fr-title">Share:</span>
                            <ul class="socials">
                                <li><a href="#" title="twitter" class="socail-btn"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                <li><a href="#" title="facebook" class="socail-btn"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                <li><a href="#" title="pinterest" class="socail-btn"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
                                <li><a href="#" title="youtube" class="socail-btn"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
                                <li><a href="#" title="instagram" class="socail-btn"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function checkout() {
            if (confirm("Yakin ingin checkout sekarang?")) {
                fetch('checkout.php', {
                        method: 'POST'
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) {
                            window.location.href = "belanja.php";
                        }
                    })
                    .catch(error => {
                        alert("Terjadi kesalahan saat proses checkout.");
                        console.error(error);
                    });
            }
        }
    </script>

    <!-- Scroll Top Button -->
    <a class="btn-scroll-top"><i class="biolife-icon icon-left-arrow"></i></a>

    <script src="assets/js/jquery-3.4.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.countdown.min.js"></script>
    <script src="assets/js/jquery.nice-select.min.js"></script>
    <script src="assets/js/jquery.nicescroll.min.js"></script>
    <script src="assets/js/slick.min.js"></script>
    <script src="assets/js/biolife.framework.js"></script>
    <script src="assets/js/functions.js"></script>
</body>

</html>