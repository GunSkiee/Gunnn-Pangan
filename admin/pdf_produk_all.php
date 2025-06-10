<?php
// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';

// Load file koneksi.php
require_once('koneksi.php');

function query($query)
{
   global $koneksi;
   $result = mysqli_query($koneksi, $query);
   $rows = [];
   while ($row = mysqli_fetch_assoc($result)) {
      $rows[] = $row;
   }
   return $rows;
}

// Query dengan JOIN antara tb_produk dan tb_kategori
$data = query("SELECT tb_produk.id_produk, tb_produk.nm_produk, tb_produk.harga, tb_produk.stok, 
                      tb_produk.desk, tb_produk.gambar, tb_kategori.nm_ktg
               FROM tb_produk 
               JOIN tb_kategori ON tb_produk.id_ktg = tb_kategori.id_ktg");

// Create an instance of the class:
$mpdf = new \Mpdf\Mpdf();
$html = '<html>
<head>
  <title>Laporan Data Produk</title>
  <link rel="shortcut icon" href="../../assets/images/logos/logo-makmur.ico" type="image/x-icon">
    
   <style>
   h1 {
      color: #262626;
   }
   table {
      max-width: 960px;
      margin: 10px auto;
      border-collapse: collapse;
   }
   thead th {
      font-weight: 400;
      background: #8a97a0;
      color: #FFF;
   }
   tr {
      background: #f4f7f8;
      border-bottom: 1px solid #FFF;
      margin-bottom: 5px;
   }
   tr:nth-child(even) {
      background: #e8eeef;
   }
   th, td {
      text-align: center;
      padding: 15px 13px;
      font-weight: 300;
      border: 1px solid #ccc;
   }
   img {
      width: 100px;
      height: 50px;
   }
   </style>

</head>
<body>

<h1 align="center">GunnnPangan</h1>
<hr>
<h1 align="center">LAPORAN DATA PRODUK</h1>

<table align="center" cellspacing="0">
<thead>
<tr>
  <th>ID Produk</th>
  <th>Gambar</th>
  <th>Nama Produk</th>
  <th>Kategori</th>
  <th>Deskripsi</th>
  <th>Harga</th>
  <th>Stok</th>
</tr>
</thead>';

foreach ($data as $row) {
   $formatted_harga = "Rp " . number_format($row["harga"], 0, ',', '.'); // Format harga Rupiah
   $html .= '<tbody>
  <tr align="center">
    <td>' . $row["id_produk"] . '</td>
    <td><img src="produk_img/' . $row["gambar"] . '" alt="Gambar"></td>
    <td>' . $row["nm_produk"] . '</td>
    <td>' . $row["nm_ktg"] . '</td>  
    <td>' . $row["desk"] . '</td>
    <td>' . $formatted_harga . '</td> <!-- Harga dengan format Rp 6.400.000 -->
    <td>' . $row["stok"] . '</td>
  </tr>
  </tbody>';
}

$html .= '</table>
</body>
</html>';

// Write some HTML code:
$mpdf->WriteHTML($html);

// Output a PDF file directly to the browser
$mpdf->Output();