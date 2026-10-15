<?php
// hapus.php
include 'koneksi.php';

$id = $_GET['id'];

//mysqli_query($conn, "DELETE FROM transaksi WHERE id=$id");
$stmt = mysqli_prepare($conn, "DELETE FROM transaksi WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: index.php");
exit;
?>
