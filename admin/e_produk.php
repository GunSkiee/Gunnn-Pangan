<?php
session_start();
include "koneksi.php";

// Cek apakah sudah login
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

// Cek apakah status tersedia dan pastikan user adalah admin
if (!isset($_SESSION["status"]) || $_SESSION["status"] !== "admin") {
    echo "<script>
    alert('Akses ditolak! Halaman ini hanya untuk Admin.');
    window.location.href='login.php';
  </script>";
    exit;
}

// Pastikan ada ID produk yang dikirimkan
if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];

    // Ambil data produk berdasarkan ID
    $query = mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id_produk = '$id_produk'");
    $data = mysqli_fetch_array($query);
}

// Jika tombol update ditekan
if (isset($_POST['update'])) {
    $nm_produk = $_POST['nm_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $desk = $_POST['desk'];
    $id_kategori = $_POST['id_ktg'];
    $gambar_lama = $_POST['gambar_lama'];

    // Cek apakah ada gambar baru yang diupload
    if ($_FILES['gambar']['name'] != "") {
        $imgfile = $_FILES['gambar']['name'];
        $tmp_file = $_FILES['gambar']['tmp_name'];
        $extension = strtolower(pathinfo($imgfile, PATHINFO_EXTENSION));
        $dir = "produk_img/";
        $allowed_extensions = array("jpg", "jpeg", "png", "webp");

        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Format tidak valid. Hanya jpg, jpeg, png, dan webp yang diperbolehkan.');</script>";
        } else {
            // Hapus gambar lama jika ada
            if (file_exists($dir . $gambar_lama) && $gambar_lama != "") {
                unlink($dir . $gambar_lama);
            }

            // Simpan gambar baru dengan nama unik
            $imgnewfile = md5(time() . $imgfile) . "." . $extension;
            move_uploaded_file($tmp_file, $dir . $imgnewfile);
        }
    } else {
        $imgnewfile = $gambar_lama; // Jika tidak ada gambar baru, gunakan gambar lama
    }

    // Update data ke database
    $query = mysqli_query($koneksi, "UPDATE tb_produk SET nm_produk='$nm_produk', harga='$harga', stok='$stok', desk='$desk', id_ktg='$id_kategori', gambar='$imgnewfile' WHERE id_produk='$id_produk'");

    if ($query) {
        echo "<script>alert('Produk berhasil diperbarui!');</script>";
        header("refresh:0, produk.php");
    } else {
        echo "<script>alert('Gagal memperbarui produk!');</script>";
        header("refresh:0, produk.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Produk - gunnnpangan Admin</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="index.php" class="logo d-flex align-items-center">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">gunnnpangan</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="assets/img/obito.jpg" alt="Profile" class="rounded-circle">
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></h6>
                            <span>Admin</span>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="logout.php">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
       <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link collapsed" href="index.php">
                <i class="bi bi-house-fill"></i>
                    <span>Beranda</span>
                </a>
            </li><!-- End Dashboard Nav -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="kategori.php">
                <i class="bi bi-clipboard-data"></i>
                    <span>Kategori</span>
                </a>
            </li><!-- End Kategori Page Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="produk.php">
                <i class="bi bi-cart-plus-fill"></i>
                    <span>Produk</span>
                </a>
            </li><!-- End Produk Page Nav -->

            <li class="nav-item">
                <a class="nav-link" href="keranjang.php">
                <i class="bi bi-bag-check"></i>
                    <span>Keranjang</span>
                </a>
            </li><!-- End Keranjang Page Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="transaksi.php">
                    <i class="bi bi-card-list"></i>
                    <span>Transaksi</span>
                </a>
            </li><!-- End Transaksi Page Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan.php">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Laporan</span>
                </a>
            </li><!-- End Laporan Page Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="pengguna.php">
                    <i class="bi bi-dash-circle"></i>
                    <span>Pengguna</span>
                </a>
            </li><!-- End Pengguna Page Nav -->
        </ul>

    </aside><!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Produk</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                    <li class="breadcrumb-item">Produk</li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form class="row g-3 mt-2" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="gambar_lama" value="<?php echo $data['gambar']; ?>">
                                <div class="col-12">
                                    <label for="nm_produk" class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control" id="nm_produk" name="nm_produk" value="<?php echo $data['nm_produk']; ?>" required>
                                </div>
                                <div class="col-12">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control" id="harga" name="harga" value="<?php echo $data['harga']; ?>" required>
                                </div>
                                <div class="col-12">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $data['stok']; ?>" required>
                                </div>
                                <div class="col-12">
                                    <label for="desk" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="desk" name="desk" required><?php echo $data['desk']; ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="id_ktg" class="form-label">Kategori</label>
                                    <select class="form-control" id="id_ktg" name="id_ktg" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php
                                        $query_kategori = mysqli_query($koneksi, "SELECT * FROM tb_kategori");
                                        while ($kategori = mysqli_fetch_array($query_kategori)) {
                                            $selected = ($kategori['id_ktg'] == $data['id_ktg']) ? 'selected' : '';
                                            echo "<option value='{$kategori['id_ktg']}' $selected>{$kategori['nm_ktg']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="gambar" class="form-label">Gambar Produk</label>
                                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                                    <br>
                                    <?php if ($data['gambar']) { ?>
                                        <img src="produk_img/<?php echo $data['gambar']; ?>" width="150">
                                    <?php } ?>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                                    <a href="produk.php" class="btn btn-secondary">Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>gunnnpangan</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
         Designed by <a href="https://www.instagram.com/4rlgnwn?igsh=MWx3bG84c2F6MGJrdw==" target="blank">4rlgnwn</a>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>