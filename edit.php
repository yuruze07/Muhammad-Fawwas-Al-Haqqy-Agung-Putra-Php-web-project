<?php
// edit.php
include 'koneksi.php';

// Ambil id dari alamat URL (edit.php?id=...)
$id = $_GET['id'];

// Ambil data lama untuk ditampilkan di form
//$hasil = mysqli_query($conn, "SELECT * FROM transaksi WHERE id=$id");
//$data  = mysqli_fetch_assoc($hasil);
$stmt = mysqli_prepare($conn, "SELECT * FROM transaksi WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$data  = mysqli_fetch_assoc($hasil);

// Kalau data tidak ditemukan, kembali ke halaman utama
if (!$data) {
    header("Location: index.php");
    exit;
}

// Kalau tombol Update ditekan, ubah data di database
if (isset($_POST['update'])) {
    $tanggal    = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $jenis      = $_POST['jenis'];
    $jumlah     = $_POST['jumlah'];

    //mysqli_query($conn, "UPDATE transaksi
    //                     SET tanggal='$tanggal', keterangan='$keterangan', jenis='$jenis', jumlah='$jumlah'
    //                     WHERE id=$id");
    $stmt = mysqli_prepare($conn, "UPDATE transaksi SET tanggal = ?, keterangan = ?, jenis = ?, jumlah = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "sssii", $tanggal, $keterangan, $jenis, $jumlah, $id);
    mysqli_stmt_execute($stmt);

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container mt-4" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-warning d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Edit Transaksi</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control"
                           value="<?= htmlspecialchars($data['tanggal']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control"
                           value="<?= htmlspecialchars($data['keterangan']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <select name="jenis" class="form-select">
                        <option value="Pemasukan" <?= $data['jenis'] == 'Pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                        <option value="Pengeluaran" <?= $data['jenis'] == 'Pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" class="form-control"
                           value="<?= htmlspecialchars($data['jumlah']) ?>" required>
                </div>

                <button type="submit" name="update" class="btn btn-warning">Update</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
