<?php
require_once __DIR__ . '/vendor/autoload.php';
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

// Ambil data dari tb_jual + tb_user (join)
$data = query("SELECT tb_jual.id_jual, tb_jual.tgl_jual, tb_jual.total, tb_jual.diskon,
                      tb_user.username
               FROM tb_jual
               JOIN tb_user ON tb_jual.id_user = tb_user.id_user
               ORDER BY tb_jual.id_jual ASC");

// Inisialisasi mPDF
$mpdf = new \Mpdf\Mpdf();

$html = '<html>
<head>
  <title>Laporan Transaksi</title>
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
    font-size: 14px;
  }
  </style>
</head>
<body>

<h1 align="center">GunnnPangan</h1>
<hr>
<h1 align="center">LAPORAN TRANSAKSI PENJUALAN</h1>

<table align="center" cellspacing="0">
<thead>
<tr>
  <th>ID Jual</th>
  <th>Tanggal</th>
  <th>Username</th>
  <th>Total</th>
  <th>Diskon</th>
</tr>
</thead>';

foreach ($data as $row) {
  $html .= '<tbody>
  <tr align="center">
    <td>'.$row["id_jual"].'</td>
    <td>'.$row["tgl_jual"].'</td>
    <td>'.$row["username"].'</td>
    <td>Rp ' . number_format($row["total"], 0, ',', '.') . '</td>
    <td>Rp ' . number_format($row["diskon"], 0, ',', '.') . '</td>
  </tr>
  </tbody>';
}

$html .= '</table>
</body>
</html>';

// Tampilkan file PDF ke browser
$mpdf->WriteHTML($html);
$mpdf->Output();