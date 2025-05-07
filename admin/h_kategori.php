<?php
include 'koneksi.php';
$id = $_GET['id'];

$hapus = mysqli_query($koneksi, "DELETE FROM tb_kategori WHERE id_ktg='$id'");
if ($hapus) {
    echo "<script>alert('Data Berhasil Dihapus')</script>";
    header('location:kategori.php');
} else {
    echo "<script>alert('Data Gagal Dihapus')</script>";
    header('location:kategori.php');
}
?>