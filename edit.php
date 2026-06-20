<?php

session_start();

require_once "../config/koneksi.php";

/* Proteksi halaman */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* Ambil ID dari URL */
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php?msg=id_invalid");
    exit;
}

/* Ambil data user lama berdasarkan ID */
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM users WHERE id = ? LIMIT 1"
);

if (!$stmt) {
    die("Query gagal: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

/* Jika ID tidak ditemukan */
if (!$user) {
    header("Location: index.php?msg=not_found");
    exit;
}

/* Isi awal form dari data lama */
$error = "";
$name = $user['name'];
$email = $user['email'];
$address = $user['address'];

/* Proses saat form disubmit */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $address = trim($_POST["address"] ?? "");

    /* Validasi input */
    if ($name === "" || $email === "" || $address === "") {

        $error = "Semua field wajib diisi!";

    } elseif (strlen($name) < 3 || strlen($name) > 100) {

        $error = "Nama harus 3 sampai 100 karakter!";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Format email tidak valid!";

    } elseif (strlen($address) < 5) {

        $error = "Alamat terlalu pendek!";

    } else {

        /* Cek apakah email sudah digunakan oleh user lain */
        $cek = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1"
        );

        if (!$cek) {
            die("Query cek email gagal: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($cek, "si", $email, $id);
        mysqli_stmt_execute($cek);

        $cekResult = mysqli_stmt_get_result($cek);

        if (mysqli_num_rows($cekResult) > 0) {

            $error = "Email sudah digunakan oleh pengguna lain!";

        } else {

            /* Jalankan UPDATE */
            $sql = "UPDATE users SET name = ?, email = ?, address = ? WHERE id = ?";

            $updt = mysqli_prepare($conn, $sql);

            if (!$updt) {
                die("Query update gagal: " . mysqli_error($conn));
            }

            mysqli_stmt_bind_param(
                $updt,
                "sssi",
                $name,
                $email,
                $address,
                $id
            );

            if (mysqli_stmt_execute($updt)) {

                header("Location: index.php?msg=updated");
                exit;

            } else {

                $error = "Gagal memperbarui data: " . mysqli_error($conn);

            }

            mysqli_stmt_close($updt);

        }

        mysqli_stmt_close($cek);

    }

}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User - <?= htmlspecialchars($user['name']) ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar">

    <a href="../index.php" class="brand">
        pweb_akademik
    </a>

    <div>
        <a href="index.php">← Kembali</a>
        <a href="../auth/logout.php">Logout</a>
    </div>

</nav>

<div class="form-card">

    <h2>Edit Data Pengguna</h2>

    <p style="text-align:center;color:#666;font-size:13px;margin-bottom:20px">
        ID Pengguna:
        <strong>#<?= htmlspecialchars($user['id']) ?></strong>
    </p>

    <?php if ($error): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form
        action="edit.php?id=<?= $user['id'] ?>"
        method="POST"
        onsubmit="return validasiEdit()"
    >

        <div class="form-group">

            <label for="name">Nama Lengkap</label>

            <input
                type="text"
                id="name"
                name="name"
                placeholder="Masukkan nama lengkap"
                value="<?= htmlspecialchars($name) ?>"
            >

        </div>

        <div class="form-group">

            <label for="email">Alamat Email</label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="contoh@email.com"
                value="<?= htmlspecialchars($email) ?>"
            >

        </div>

        <div class="form-group">

            <label for="address">Alamat Lengkap</label>

            <textarea
                id="address"
                name="address"
                rows="3"
                placeholder="Jl. Contoh No.1, Kota"
            ><?= htmlspecialchars($address) ?></textarea>

        </div>

        <div style="display:flex;gap:10px;margin-top:10px">

            <button
                type="submit"
                class="btn btn-primary"
                style="flex:1"
            >
                Simpan Perubahan
            </button>

            <a
                href="index.php"
                class="btn"
                style="flex:1;text-align:center;background:#6c757d;color:white"
            >
                Batal
            </a>

        </div>

    </form>

</div>

<script src="../assets/js/script.js"></script>

</body>
</html>