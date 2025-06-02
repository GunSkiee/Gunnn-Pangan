<?php
session_start();
include 'admin/koneksi.php';

// Pastikan ada parameter id_produk yang dikirim dari URL
$id_produk = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';

$query = "SELECT p.nm_produk, p.harga, p.stok, p.desk, p.gambar, k.nm_ktg
        FROM tb_produk p
        JOIN tb_kategori k ON p.id_ktg = k.id_ktg
        WHERE p.id_produk = '$id_produk'";

$result = $koneksi->query($query);
$produk = $result->fetch_assoc();

// Query untuk produk lain selain produk yang sedang dibuka
$query_lainnya = "SELECT id_produk, nm_produk, desk, harga, gambar, (SELECT nm_ktg FROM tb_kategori WHERE tb_kategori.id_ktg = p.id_ktg) as kategori 
                FROM tb_produk p
                WHERE id_produk != '$id_produk'
                ORDER BY RAND()
                  LIMIT 6"; // batasi sesuai kebutuhan

$result_lainnya = $koneksi->query($query_lainnya);

// Tambahkan pesanan ke database
if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['login'])) {
        echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    } else {
        $id_user = $_SESSION['id_user'];
        $qty = intval($_POST['qty']);
        $total = $produk['harga'] * $qty;

        // Cek stok langsung dari database (lebih aman)
        $cek_stok = $koneksi->query("SELECT stok FROM tb_produk WHERE id_produk = '$id_produk'");
        $data_stok = $cek_stok->fetch_assoc();

        if ($qty > $data_stok['stok']) {
            echo "<script>alert('Stok tidak mencukupi! Stok tersedia: {$data_stok['stok']}');</script>";
        } else {
            // Buat id_pesanan otomatis dengan format M001, M002, dst.
            $query_id = "SELECT id_pesanan FROM tb_pesanan ORDER BY id_pesanan DESC LIMIT 1";
            $result_id = $koneksi->query($query_id);
            if ($result_id->num_rows > 0) {
                $row = $result_id->fetch_assoc();
                $last_id = intval(substr($row['id_pesanan'], 1)); // Ambil angka dari id terakhir
                $new_id = "M" . str_pad($last_id + 1, 3, '0', STR_PAD_LEFT); // Format M001, M002
            } else {
                $new_id = "M001"; // Jika belum ada pesanan, mulai dari M001
            }

            // Simpan ke database
            $query_insert = "INSERT INTO tb_pesanan (id_pesanan, id_produk, qty, total, id_user) 
                            VALUES ('$new_id', '$id_produk', '$qty', '$total', '$id_user')";

            if ($koneksi->query($query_insert) === TRUE) {
                echo "<script>alert('Produk berhasil ditambahkan ke keranjang!'); window.location.href='belanja.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat menambahkan ke keranjang!');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Produk - gunnnpangan</title>
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
                                            <span class="qty"><?= htmlspecialchars($_SESSION['username']); ?></span>
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
        
    </header>

    <!--Hero Section-->
    <div class="hero-section hero-background">
        <h1 class="page-title">Detail Produk</h1>
    </div>

    <!--Navigation section-->
    <div class="container">
        <nav class="biolife-nav">
            <ul>
                <li class="nav-item"><a href="index.php" class="permal-link">Beranda</a></li>
                <li class="nav-item"><a href="belanja.php" class="permal-link">Belanja</a></li>
                <li class="nav-item"><span class="current-page">Detail Produk</span></li>
            </ul>
        </nav>
    </div>

    <div class="page-contain single-product">
        <div class="container">

            <!-- Main content -->
            <div id="main-content" class="main-content">

                <!-- summary info -->
                <div class="sumary-product single-layout">
                    <div class="media">
                        <ul class="biolife-carousel slider-for"
                            data-slick='{"arrows":false,"dots":false,"slidesMargin":30,"slidesToShow":1,"slidesToScroll":1,"fade":true,"asNavFor":".slider-nav"}'>
                            <li><img src="admin/produk_img/<?php echo $produk['gambar']; ?>" alt=""
                                    class="square-detail-img"></li>
                        </ul>
                    </div>
                    <div class="product-attribute">
                        <h3 class="title"><?php echo $produk['nm_produk']; ?></h3>
                        <span class="sku"><?php echo $produk['nm_ktg']; ?></span>
                        <p class="excerpt"><?php echo nl2br($produk['desk']); ?></p>
                        <div class="price">
                            <ins><span class="price-amount"><span
                                        class="currencySymbol">Rp.</span><?php echo number_format($produk['harga'], 0, ',', '.'); ?></span></ins>
                        </div>
                        <div class="shipping-info">
                        </div>
                    </div>
                    <form action="" method="post">
                        <div class="action-form">
                            <div class="quantity-box">
                                <span class="title">Quantity:</span>
                                <div class="qty-input">
                                    <input type="text" name="qty" value="1" data-max_value="20" data-min_value="1"
                                        data-step="1">
                                    <a href="#" class="qty-btn btn-up"><i class="fa fa-caret-up"
                                            aria-hidden="true"></i></a>
                                    <a href="#" class="qty-btn btn-down"><i class="fa fa-caret-down"
                                            aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div class="buttons">
                                <button type="submit" class="btn add-to-cart-btn" name="add_to_cart">Keranjang</button>
                                <p class="pull-row">
                                    <a href="#" class="btn wishlist-btn">wishlist</a>
                                    <a href="#" class="btn compare-btn">compare</a>
                                </p>
                            </div>
                            <div class="acepted-payment-methods">
                                <ul class="payment-methods">
                                    <li><img src="assets/images/card1.jpg" alt="" width="51" height="36"></li>
                                    <li><img src="assets/images/card2.jpg" alt="" width="51" height="36"></li>
                                    <li><img src="assets/images/card3.jpg" alt="" width="51" height="36"></li>
                                    <li><img src="assets/images/card4.jpg" alt="" width="51" height="36"></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tab info -->
                <div class="product-tabs single-layout biolife-tab-contain">
                    <div class="tab-head">
                        <ul class="tabs">
                            <li class="tab-element active"><a href="#tab_1st" class="tab-link">Deskripsi</a></li>
                            <li class="tab-element"><a href="#tab_2nd" class="tab-link">Stok</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="tab_1st" class="tab-contain desc-tab active">
                            <p class="desc"><?php echo nl2br($produk['desk']); ?></p>
                        </div>
                        <div id="tab_2nd" class="tab-contain addtional-info-tab">
                            <table class="tbl_attributes">
                                <tbody>
                                    <tr>
                                        <th><?php echo $produk['stok']; ?></th>
                                        <td>
                                            <p>Kg</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- related products -->
                <div class="product-related-box single-layout">
                    <div class="biolife-title-box lg-margin-bottom-26px-im">
                        <span class="biolife-icon icon-organic"></span>
                        <span class="subtitle">Semua Produk Terbaik Untuk Anda</span>
                        <h3 class="main-title">Produk Terkait</h3>
                    </div>
                    <ul class="products-list biolife-carousel nav-center-02 nav-none-on-mobile"
                        data-slick='{"rows":1,"arrows":true,"dots":false,"infinite":false,"speed":400,"slidesMargin":0,"slidesToShow":5, "responsive":[{"breakpoint":1200, "settings":{ "slidesToShow": 4}},{"breakpoint":992, "settings":{ "slidesToShow": 3, "slidesMargin":20 }},{"breakpoint":768, "settings":{ "slidesToShow": 2, "slidesMargin":10}}]}'>

                        <?php while ($produk_lain = $result_lainnya->fetch_assoc()): ?>
                            <li class="product-item">
                                <div class="contain-product layout-default">
                                    <div class="product-thumb">
                                        <a href="detail_produk.php?id=<?= $produk_lain['id_produk']; ?>"
                                            class="link-to-product">
                                            <img src="admin/produk_img/<?= $produk_lain['gambar']; ?>"
                                                alt="<?= $produk_lain['nm_produk']; ?>"
                                                class="product-thumbnail square-image">
                                        </a>
                                    </div>
                                    <div class="info">
                                        <b class="categories"><?= $produk_lain['kategori']; ?></b>
                                        <h4 class="product-title"><a
                                                href="detail_produk.php?id=<?= $produk_lain['id_produk']; ?>"
                                                class="pr-name"><?= $produk_lain['nm_produk']; ?></a></h4>
                                        <div class="price">
                                            <ins><span class="price-amount"><span
                                                        class="currencySymbol">Rp.</span><?= number_format($produk_lain['harga'], 0, ',', '.'); ?></span></ins>
                                        </div>
                                        <div class="slide-down-box">
                                            <p class="message"><?= $produk_lain['desk']; ?></p>
                                            <div class="buttons">
                                                <a href="detail_produk.php?id=<?= $produk_lain['id_produk']; ?>"
                                                    class="btn add-to-cart-btn">
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

    <!-- FOOTER -->
    <footer id="footer" class="footer layout-03">
        <div class="footer-content background-footer-03">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-9">
                        <section class="footer-item">
                            <a href="index.php" class="biolife-logo"><img src="assets/images/favicon.png"
                                    alt="biolife logo"><b style="font-size: 150% ; color: green;">PantryNusantara</b></a>
                            <div class="footer-phone-info">
                                <i class="biolife-icon icon-head-phone"></i>
                                <p class="r-info">
                                   <div class="footer-phone-info">
                                        <i class="biolife-icon icon-head-phone"></i>
                                        <p class="r-info">
                                            <span>Ada Pertanyaan ?</span>
                                            <span>0838-6520-9331</span>
                                        </p>
                                    </div>
                                </section>
                            </div>
                            <div
                                class="col-lg-4 col-md-4 col-sm-6 md-margin-top-5px sm-margin-top-50px xs-margin-top-40px">
                                <section class="footer-item">
                                </section>
                            </div>
                            <div
                                class="col-lg-4 col-md-4 col-sm-6 md-margin-top-5px sm-margin-top-50px xs-margin-top-40px">
                                <section class="footer-item">
                                    <h3 class="section-title">Layanan Transportasi</h3>
                                    <div class="contact-info-block footer-layout xs-padding-top-10px">
                                        <ul class="contact-lines">
                                            <li>
                                                <p class="info-item">
                                                    <i class="biolife-icon icon-location"></i>
                                                    <b class="desc">Tanjung, Kedungtuban, Blora, Jawa Tengah, Indonesia</b>
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
                                                    <b class="desc">Email: agunawan@gmail.com</b>
                                                </p>
                                            </li>
                                            <li>
                                                <p class="info-item">
                                                    <i class="biolife-icon icon-clock"></i>
                                                    <b class="desc">Jam Buka: Senin - Sabtu, Mulai Pukul 08.00 - 20.00</b>
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="biolife-social inline">
                                        <ul class="socials">
                                            <li><a href="#" title="twitter" class="socail-btn"><i class="fa fa-twitter"
                                                        aria-hidden="true"></i></a>
                                            </li>
                                            <li><a href="#" title="facebook" class="socail-btn"><i
                                                        class="fa fa-facebook" aria-hidden="true"></i></a>
                                            </li>
                                            <li><a href="#" title="pinterest" class="socail-btn"><i
                                                        class="fa fa-pinterest" aria-hidden="true"></i></a>
                                            </li>
                                            <li><a href="#" title="youtube" class="socail-btn"><i class="fa fa-youtube"
                                                        aria-hidden="true"></i></a>
                                            </li>
                                            <li><a href="https://www.instagram.com/4rlgnwn?igsh=MWx3bG84c2F6MGJrdw==" title="instagram"
                                                    class="socail-btn"><i class="fa fa-instagram"
                                                        aria-hidden="true"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </section>
                            </div>
                        </div>
                            <div class="biolife-social inline">
                                <ul class="socials">
                                    <li><a href="#" title="twitter" class="socail-btn"><i class="fa fa-twitter"
                                                aria-hidden="true"></i></a></li>
                                    <li><a href="#" title="facebook" class="socail-btn"><i class="fa fa-facebook"
                                                aria-hidden="true"></i></a></li>
                                    <li><a href="#" title="pinterest" class="socail-btn"><i class="fa fa-pinterest"
                                                aria-hidden="true"></i></a></li>
                                    <li><a href="#" title="youtube" class="socail-btn"><i class="fa fa-youtube"
                                                aria-hidden="true"></i></a></li>
                                    <li><a href="https://www.instagram.com/bang.fannreign?igsh=MWh5NWxrcXdoeTV5Zw==" title="instagram"
                                            class="socail-btn"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                                    </li>
                                </ul>
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
                                <ul>
                                    <li><a href="#" class="payment-link"><img src="assets/images/card1.jpg" width="51"
                                                height="36" alt=""></a></li>
                                    <li><a href="#" class="payment-link"><img src="assets/images/card2.jpg" width="51"
                                                height="36" alt=""></a></li>
                                    <li><a href="#" class="payment-link"><img src="assets/images/card3.jpg" width="51"
                                                height="36" alt=""></a></li>
                                    <li><a href="#" class="payment-link"><img src="assets/images/card4.jpg" width="51"
                                                height="36" alt=""></a></li>
                                    <li><a href="#" class="payment-link"><img src="assets/images/card5.jpg" width="51"
                                                height="36" alt=""></a></li>
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