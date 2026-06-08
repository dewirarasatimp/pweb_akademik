<?php

session_start();

if (
    !isset($_SESSION['user_id'])
) {

    header(
        "Location: ../auth/login.php"
    );

    exit;

}

require_once "../config/koneksi.php";

$id =
$_GET['id']
?? 0;

$stmt =
mysqli_prepare(
    $conn,
    "SELECT *
     FROM users
     WHERE id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute(
    $stmt
);

$result =
mysqli_stmt_get_result(
    $stmt
);

$user =
mysqli_fetch_assoc(
    $result
);

if (!$user) {

    die(
        "Data user tidak ditemukan."
    );

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>
Detail User
</title>

<link
rel="stylesheet"
href="../assets/css/style.css">

</head>

<body>

<nav class="navbar">

<a
href="../index.php"
class="brand">

PWeb Akademik

</a>

<div>

<a href="index.php">
Data User
</a>

<a href="../auth/logout.php">
Logout
</a>

</div>

</nav>

<div class="container">

<div class="table-wrapper">

<h2
style="
color:#1F4E79;
margin-bottom:20px;
">

Detail User

</h2>

<p>

<strong>ID :</strong>

<?= $user['id']; ?>

</p>

<br>

<p>

<strong>Nama :</strong>

<?= htmlspecialchars(
    $user['name']
); ?>

</p>

<br>

<p>

<strong>Email :</strong>

<?= htmlspecialchars(
    $user['email']
); ?>

</p>

<br>

<p>

<strong>Alamat :</strong>

<?= nl2br(
    htmlspecialchars(
        $user['address']
    )
); ?>

</p>

<br>

<a
href="index.php"
class="btn btn-primary"
style="
width:auto;
">

Kembali

</a>

</div>

</div>

</body>
</html>