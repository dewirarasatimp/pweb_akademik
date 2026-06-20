<?php
session_start();
require_once "../config/koneksi.php";

// Proteksi halaman
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Memastikan ID tersedia di URL
if (!isset($_GET['id'])) {
    header("Location: index.php?msg=id_tidak_ada");
    exit;
}

// Mengambil ID pengguna
$id = intval($_GET['id']);

// Memastikan ID valid
if ($id <= 0) {
    header("Location: index.php?msg=id_tidak_valid");
    exit;
}

// Query untuk menghapus data pengguna
$query = "DELETE FROM users WHERE id = $id";
$hapus = mysqli_query($conn, $query);

// Mengecek hasil penghapusan
if ($hapus) {
    if (mysqli_affected_rows($conn) > 0) {
        header("Location: index.php?msg=deleted");
        exit;
    } else {
        echo "Data tidak ditemukan atau sudah pernah dihapus.";
    }
} else {
    echo "Data gagal dihapus.<br>";
    echo "Pesan kesalahan: " . mysqli_error($conn);
}
?>