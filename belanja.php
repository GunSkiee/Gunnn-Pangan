<?php
session_start()
?>
<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Belanja - gunnnpangan</title>
    <link href="https://fonts.googleapis.com/css?family=Cairo:400,600,700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400i,700i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu&amp;display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.png" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/nice-select.css">
    <link rel="stylesheet" href="assets/css/slick.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/main-color.css">
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
            transition: background 0.25s ease;
        }

        .logout-list li a:hover {
            background-color: #f2f2f2;
        }

        .img-wrapper {
            width: 370px;
            height: 370px;
            overflow: hidden;
            border-radius: 10px;
            /* optional, blur lebih halus */
        }

        .square-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .minicart-block {
            margin-right: 30px;
        }
        .user .qty {
            margin-left: 5px;
            font-weight: bold;
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
                        <a href="index.php" class="biolife-logo"><img src="assets/images/favicon.png"
                                alt="biolife logo">
                            <b style="font-size: 150% ; color: green;">gunnnpangan</b></a>
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
                            <div class="mobile-search">
                                <a href="javascript:void(0)" class="open-searchbox"><i
                                        class="biolife-icon icon-search"></i></a>
                                <div class="mobile-search-content">
                                    <form action="#" class="form-search" name="mobile-seacrh" method="get">
                                        <a href="#" class="btn-close"><span
                                                class="biolife-icon icon-close-menu"></span></a>
                                        <input type="text" name="s" class="input-text" value=""
                                            placeholder="Search here...">
                                        <select name="category">
                                            <option value="-1" selected>All Categories</option>
                                            <option value="vegetables">Vegetables</option>
                                            <option value="fresh_berries">Fresh Berries</option>
                                            <option value="ocean_foods">Ocean Foods</option>
                                            <option value="butter_eggs">Butter & Eggs</option>
                                            <option value="fastfood">Fastfood</option>
                                            <option value="fresh_meat">Fresh Meat</option>
                                            <option value="fresh_onion">Fresh Onion</option>
                                            <option value="papaya_crisps">Papaya & Crisps</option>
                                            <option value="oatmeal">Oatmeal</option>
                                        </select>
                                        <button type="submit" class="btn-submit">go</button>
                                    </form>
                                </div>
                            </div>

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
                                    <a class="dropdown-toggle d-flex align-items-center link-to" href="#" id="userDropdown"
                                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="icon-qty-combine">
                                            <i class="fas fa-user biolife-icon"></i>
                                            <span class="qty"><?= htmlspecialchars($_SESSION['username']);  ?></span>
                                            <!-- Ganti qty jadi username -->
                                        </span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                        <ul class="logout-list">
                                            <li><a href="logout.php">Logout</a></li>
                                        </ul>
                                    </div>
                                </div>
                            <?php else: ?>

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

        <div class="header-bottom hidden-sm hidden-xs">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <div class="vertical-menu vertical-category-block">
                            <div class="block-title">
                                <span class="menu-icon">
                                    <span class="line-1"></span>
                                    <span class="line-2"></span>
                                    <span class="line-3"></span>
                                </span>
                                <span class="menu-title">Semua Kategori</span>
                                <span class="angle" data-tgleclass="fa fa-caret-down"><i class="fa fa-caret-up"
                                        aria-hidden="true"></i></span>
                            </div>
                            <div class="wrap-menu">
                                <ul class="menu clone-main-menu">
                                    <?php
                                    include "admin/koneksi.php";
                                    $kategori_result = mysqli_query($koneksi, "SELECT * FROM tb_ktg ORDER BY nm_ktg ASC");
                                    while ($kategori = mysqli_fetch_assoc($kategori_result)) {
                                        $selected = (isset($_GET['kategori']) && $_GET['kategori'] == $kategori['id_ktg']) ? 'style="font-weight:bold;"' : '';
                                        echo '<li class="menu-item"><a href="?kategori=' . $kategori['id_ktg'] . '" class="menu-title" ' . $selected . '>' . $kategori['nm_ktg'] . '</a></li>';
                                    }
                                    ?>
                                </ul>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 padding-top-2px">
                        <div class="header-search-bar layout-01">
                            <form action="" class="form-search" name="desktop-search" method="get">
                                <input type="text" name="s" class="input-text"
                                    value="<?php echo isset($_GET['s']) ? htmlspecialchars($_GET['s']) : ''; ?>"
                                    placeholder="Search here...">
                                <button type="submit" class="btn-submit"><i
                                        class="biolife-icon icon-search"></i></button>
                            </form>
                        </div>
                        <div class="live-info">
                            <p class="telephone"><i class="fa fa-phone" aria-hidden="true"></i><b
                                    class="phone-number">(+62) 838-6520-9331</b></p>
                            <p class="working-time">Senin - Kamis: 08.00 - 20.00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!--Hero Section-->
    <div class="hero-section hero-background">
        <h1 class="page-title">Belanja</h1>
    </div>

    <!--Navigation section-->
    <div class="container">
        <nav class="biolife-nav">
            <ul>
                <li class="nav-item"><a href="index-2.html" class="permal-link">Beranda</a></li>
                <li class="nav-item"><span class="current-page">Belanja</span></li>
            </ul>
        </nav>
    </div>

    <div class="page-contain category-page no-sidebar">
        <div class="container">
            <div class="row">

                <!-- Main content -->
                <div id="main-content" class="main-content col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="product-category list-style">
                        <div id="top-functions-area" class="top-functions-area">
                        </div>

                        <?php
                        include 'admin/koneksi.php';

                        // Tentukan jumlah produk per halaman
                        $limit = 6;

                        // Ambil halaman aktif dari URL, default ke halaman 1
                        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                        $start = ($page - 1) * $limit;

                        // Ambil keyword pencarian
                        $search = isset($_GET['s']) ? mysqli_real_escape_string($koneksi, $_GET['s']) : '';

                        // Ambil filter kategori jika ada
                        $kategori = isset($_GET['kategori']) ? mysqli_real_escape_string($koneksi, $_GET['kategori']) : '';

                        // Buat kondisi WHERE dinamis
                        $where = [];
                        if (!empty($search)) {
                            $where[] = "(p.nm_produk LIKE '%$search%' OR p.desk LIKE '%$search%')";
                        }
                        if (!empty($kategori)) {
                            $where[] = "p.id_ktg = '$kategori'";
                        }
                        $where_sql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

                        // Hitung total produk
                        $total_query = "SELECT COUNT(*) AS total FROM tb_produk p $where_sql";
                        $total_result = mysqli_query($koneksi, $total_query);
                        $total_row = mysqli_fetch_assoc($total_result);
                        $total_produk = $total_row['total'];
                        $total_pages = ceil($total_produk / $limit);

                        // Query ambil data produk
                        $query = "SELECT p.*, k.nm_ktg FROM tb_produk p 
            JOIN tb_kategori k ON p.id_ktg = k.id_ktg 
            $where_sql 
            ORDER BY p.id_produk ASC 
            LIMIT $start, $limit";
                        $result = mysqli_query($koneksi, $query);
                        ?>
                        <div class="row">
                            <ul class="products-list">
                                <?php while ($data = mysqli_fetch_assoc($result)) { ?>
                                    <li class="product-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="contain-product pr-detail-layout">
                                            <div class="product-thumb">
                                                <a href="detail_produk.php?id=<?php echo $data['id_produk']; ?>"
                                                    class="link-to-product">
                                                    <div class="img-wrapper">
                                                        <img src="admin/produk_img/<?php echo $data['gambar']; ?>"
                                                            alt="<?php echo $data['nm_produk']; ?>" class="square-img">
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="info">
                                                <b class="categories"><?php echo $data['nm_ktg']; ?></b>
                                                <h4 class="product-title"><a
                                                        href="detail_produk.php?id=<?php echo $data['id_produk']; ?>"
                                                        class="pr-name"><?php echo $data['nm_produk']; ?></a></h4>
                                                <p class="excerpt"><?php echo $data['desk']; ?></p>
                                                <div class="price">
                                                    <ins><span class="price-amount"><span
                                                                class="currencySymbol">Rp.</span><?php echo number_format($data['harga'], 0, ',', '.'); ?></span></ins>
                                                </div>
                                                <div class="buttons">
                                                    <a href="detail_produk.php?id=<?php echo $data['id_produk']; ?>"
                                                        class="btn add-to-cart-btn">Keranjang</a>
                                                </div>
                                            </div>
                                            <div class="advance-info">
                                                <ul class="list">
                                                    <?php
                                                    switch (strtolower($data['nm_ktg'])) {
                                                        case 'sayuran':
                                                            echo '<li>Segar Dipanen Setiap Hari</li><li>Tanpa Obat-Obatan Berbahaya</li>';
                                                            break;
                                                        case 'daging':
                                                            echo '<li>Daging Segar</li><li>Potongan Segar dan Berkualitas</li>';
                                                            break;
                                                        case 'buah':
                                                            echo '<li>Buah Diambil Langsung Dari Kebun </li><li>Tanpa Obat-Obatan Berbahaya</li>';
                                                            break;
                                                        case 'ikan':
                                                            echo '<li>Ikan Segar dari Nelayan Jago</li><li>Tanpa Formalin Dan Bahan Pengawet lainnya</li>';
                                                            break;
                                                        case 'bumbu masakan':
                                                            echo '<li>Bumbu Dapur Alami Langsung Dari Alam</li><li>Rempah-rempah Pilihan Terbaik</li>';
                                                            break;
                                                        case 'makanan beku':
                                                            echo '<li>Kualitas Terjaga dengan Pembekuan</li><li>Siap Masak, Praktis & Higienis</li>';
                                                            break;
                                                        case 'bahan pokok':
                                                            echo '<li>Kualitas Terjaga dengan Pembekuan</li><li>Siap Masak, Praktis & Higienis</li>';
                                                            break;
                                                        case 'makanan instan':
                                                            echo '<li>Kualitas Terjaga dengan Pembekuan</li><li>Siap Masak, Praktis & Higienis</li>';
                                                            break;
                                                        case 'produk impor':
                                                            echo '<li>Kualitas Terjaga dengan Pembekuan</li><li>Siap Masak, Praktis & Higienis</li>';
                                                            break;
                                                        default:
                                                            echo '<li> </li><li> </li>';
                                                            break;
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                <?php 
                                 }
                               ?>
                            </ul>
                        </div>

                        <div class="biolife-panigations-block">
                            <ul class="panigation-contain">
                                <?php
                                // Query string untuk mempertahankan search dan kategori
                                $search_query = !empty($search) ? '&s=' . urlencode($search) : '';
                                $kategori_query = !empty($kategori) ? '&kategori=' . urlencode($kategori) : '';
                                $query_string = $search_query . $kategori_query;

                                if ($page > 1): ?>
                                    <li>
                                        <a href="?page=<?php echo $page - 1 . $query_string; ?>" class="link-page prev">
                                            <i class="fa fa-angle-left" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li>
                                        <?php if ($i == $page): ?>
                                            <span class="current-page"><?php echo $i; ?></span>
                                        <?php else: ?>
                                            <a href="?page=<?php echo $i . $query_string; ?>"
                                                class="link-page"><?php echo $i; ?></a>
                                        <?php endif; ?>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($page < $total_pages): ?>
                                    <li>
                                        <a href="?page=<?php echo $page + 1 . $query_string; ?>" class="link-page next">
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
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
                            <a href="index.php" class="biolife-logo"><img src="assets/images/favicon.png"
                                    alt="biolife logo"><b style="font-size: 150% ; color: green;">gunnnpangan</b></a>
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
                              
                            </div>
                        </section>
                    </div>
                </div>
                <div class="row">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="separator sm-margin-top-70px xs-margin-top-40px"></div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="copy-right-text">
                                <p><a href="templateshub.net">&copy; Copyright <strong><span>2025</span></strong>. All
                                        Rights Reserved</a></p>
                            </div>

                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="payment-methods">
                              
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
                <a class="menu-bar myaccount-toggle btn-toggle" data-object="global-panel-opened"
                    href="javascript:void(0)">
                    <span class="fa fa-globe"></span>
                    <span class="text">Global</span>
                </a>
            </div>
        </div>
    </div>

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
                    <li class="list-item"><a href="#"><img src="assets/images/languages/us.jpg" alt="flag" width="24"
                                height="18"></a></li>
                    <li class="list-item"><a href="#"><img src="assets/images/languages/fr.jpg" alt="flag" width="24"
                                height="18"></a></li>
                    <li class="list-item"><a href="#"><img src="assets/images/languages/ger.jpg" alt="flag" width="24"
                                height="18"></a></li>
                    <li class="list-item"><a href="#"><img src="assets/images/languages/jap.jpg" alt="flag" width="24"
                                height="18"></a></li>
                </ul>
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