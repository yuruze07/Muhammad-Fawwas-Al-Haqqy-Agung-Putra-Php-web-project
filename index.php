<?php
// index.php
include 'koneksi.php';

// Kalau tombol Simpan ditekan, masukkan data ke database
if (isset($_POST['simpan'])) {
    $tanggal    = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $jenis      = $_POST['jenis'];
    $jumlah     = $_POST['jumlah'];

    //mysqli_query($conn, "INSERT INTO transaksi (tanggal, keterangan, jenis, jumlah)
    //                     VALUES ('$tanggal', '$keterangan', '$jenis', '$jumlah')");
    $stmt = mysqli_prepare($conn, "INSERT INTO transaksi (tanggal, keterangan, jenis, jumlah) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssi", $tanggal, $keterangan, $jenis, $jumlah);
    mysqli_stmt_execute($stmt);

    header("Location: index.php");  // muat ulang halaman
    exit;
}

// Ambil semua data transaksi, yang terbaru di atas
$hasil = mysqli_query($conn, "SELECT * FROM transaksi ORDER BY tanggal DESC, id DESC");

// Simpan ke array dan hitung total
$rows         = [];
$total_masuk  = 0;
$total_keluar = 0;

while ($row = mysqli_fetch_assoc($hasil)) {
    if ($row['jenis'] == 'Pemasukan') {
        $total_masuk += $row['jumlah'];
    } else {
        $total_keluar += $row['jumlah'];
    }
    $rows[] = $row;
}

$saldo = $total_masuk - $total_keluar;

function rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catatan Keuangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container mt-4">

    <!-- RINGKASAN -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <div class="text-muted small">Total Pemasukan</div>
                    <h4 class="text-success mb-0"><?= rupiah($total_masuk) ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-danger">
                <div class="card-body">
                    <div class="text-muted small">Total Pengeluaran</div>
                    <h4 class="text-danger mb-0"><?= rupiah($total_keluar) ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-primary">
                <div class="card-body">
                    <div class="text-muted small">Saldo</div>
                    <h4 class="text-primary mb-0"><?= rupiah($saldo) ?></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- DAFTAR TRANSAKSI -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Daftar Transaksi</h4>
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                + Tambah Transaksi
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Jenis</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (count($rows) == 0): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada transaksi.</td>
                        </tr>
                    <?php endif; ?>

                    <?php $no = 1; foreach ($rows as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['tanggal']) ?></td>
                            <td><?= htmlspecialchars($row['keterangan']) ?></td>
                            <td>
                                <?php if ($row['jenis'] == 'Pemasukan'): ?>
                                    <span class="badge bg-success">Pemasukan</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Pengeluaran</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end"><?= rupiah($row['jumlah']) ?></td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH TRANSAKSI -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Transaksi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <select name="jenis" class="form-select">
                        <option value="Pemasukan">Pemasukan</option>
                        <option value="Pengeluaran">Pengeluaran</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
