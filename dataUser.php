<?php

session_start();

require_once '../config/koneksi.php';

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

    <title>Data User</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<nav class="navbar">

    <a href="../index.php" class="brand">
        PWeb Akademik
    </a>

    <div>

        <a href="../auth/register.php">
            + Tambah User
        </a>

        <a href="../auth/logout.php">
            Logout
        </a>

    </div>

</nav>

<div class="container">

    <h2>Daftar Pengguna</h2>

    <?php if (empty($users)): ?>

        <p>Belum ada data user.</p>

    <?php else: ?>

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

                            <a
                                href="detail.php?id=<?= $user['id'] ?>"
                                class="btn btn-primary"
                                style="padding:5px 10px;font-size:12px"
                            >
                                Detail
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

</div>

<script src="../assets/js/script.js"></script>

</body>
</html>