<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/koneksi.php";

/* Tangkap pesan notifikasi dari redirect */
$msg = $_GET['msg'] ?? '';
$notif = '';
$notifType = '';

switch ($msg) {
    case 'deleted':
        $notif = 'Data berhasil dihapus.';
        $notifType = 'success';
        break;

    case 'updated':
        $notif = 'Data berhasil diperbarui.';
        $notifType = 'success';
        break;

    case 'not_found':
        $notif = 'Data tidak ditemukan.';
        $notifType = 'error';
        break;

    case 'id_invalid':
        $notif = 'ID pengguna tidak valid.';
        $notifType = 'error';
        break;

    case 'error':
        $notif = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        $notifType = 'error';
        break;
}

/* Ambil semua data user */
$query = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $query);

$users = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
} else {
    die("Query gagal: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data User - pweb_akademik</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar">

    <a href="../index.php" class="brand">
        pweb_akademik
    </a>

    <div>
        <a href="../auth/register.php">+ Tambah User</a>
        <a href="../auth/logout.php">Logout</a>
    </div>

</nav>

<div class="container">

    <div class="table-wrapper">

        <h2 style="margin-bottom:20px;color:#1F4E79">
            Daftar Pengguna
        </h2>

        <?php if ($notif): ?>
            <div class="alert alert-<?= $notifType ?>">
                <?= htmlspecialchars($notif) ?>
            </div>
        <?php endif; ?>

        <?php if (empty($users)): ?>

            <p style="text-align:center;color:#888">
                Belum ada data pengguna.
            </p>

        <?php else: ?>

            <div class="table-responsive">

                <table>

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php $no = 1; ?>

                        <?php foreach ($users as $user): ?>

                            <tr>

                                <td><?= $no++ ?></td>

                                <td><?= htmlspecialchars($user['name']) ?></td>

                                <td><?= htmlspecialchars($user['email']) ?></td>

                                <td><?= htmlspecialchars($user['address']) ?></td>

                                <td>
                                    <div class="aksi">

                                        <a
                                            href="detail.php?id=<?= $user['id'] ?>"
                                            class="btn btn-primary btn-sm"
                                        >
                                            Detail
                                        </a>

                                        <a
                                            href="edit.php?id=<?= $user['id'] ?>"
                                            class="btn btn-success btn-sm"
                                        >
                                            Edit
                                        </a>

                                        <a
                                            href="javascript:void(0)"
                                            onclick="konfirmasiHapus(<?= $user['id'] ?>)"
                                            class="btn btn-danger btn-sm"
                                        >
                                            Hapus
                                        </a>

                                    </div>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </div>

</div>

<script src="../assets/js/script.js"></script>

</body>
</html>