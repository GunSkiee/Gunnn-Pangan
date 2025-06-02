<?php
include "admin/koneksi.php";
session_start();

if (lisset($_SESSION['id_user'])) {
    echo "<script>alert('Silahkan login terlebih dahulu'); window.location='login.php';</script>";
    exit;
}

if (isset($_GET['id'])) {
  $id_pesanan = $_GET['id'];
  $query = "DELETE FROM tb_pesanan WHERE id_pesanan = '$id_pesanan'";
  $result = mysqli_query($koneksi, $query);
    if ($result) {
        echo "<script>alert('Item berhasil dihapus dari keranjang'); window.location='keranjang.php';</script>";
    } else {
        echo "Gagal menghapus: " . mysqli_error($koneksi);
    }
} else {
    echo "<script>alert('ID pesanan tidak ditemukan'); window.location='keranjang.php';</script>";
}
?>