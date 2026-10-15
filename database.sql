CREATE DATABASE IF NOT EXISTS db_keuangan;
USE db_keuangan;

CREATE TABLE IF NOT EXISTS transaksi (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    tanggal    DATE         NOT NULL,
    keterangan VARCHAR(100) NOT NULL,
    jenis      VARCHAR(20)  NOT NULL,   --'Pemasukan' atau 'Pengeluaran'
    jumlah     INT          NOT NULL
);

--test
INSERT INTO transaksi (tanggal, keterangan, jenis, jumlah) VALUES
('2026-10-01', 'Uang saku bulanan', 'Pemasukan', 1500000),
('2026-10-03', 'Beli buku kuliah', 'Pengeluaran', 120000),
('2026-10-05', 'Makan siang', 'Pengeluaran', 25000);
