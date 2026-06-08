<?php

session_start();

if (
    !isset($_SESSION['user_id'])
) {

    header(
        "Location: auth/login.php"
    );

    exit;

}

require_once "config/koneksi.php";

$user_name =
$_SESSION['user_name']
?? 'Pengguna';

$user_email =
$_SESSION['user_email']
?? '-';

/* Total User */

$queryTotal =
mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM users"
);

$dataTotal =
mysqli_fetch_assoc(
    $queryTotal
);

/* 3 User Terbaru */

$queryUserBaru =
mysqli_query(
    $conn,
    "SELECT *
     FROM users
     ORDER BY id DESC
     LIMIT 3"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>
Dashboard
</title>

<link
rel="stylesheet"
href="assets/css/style.css">

</head>

<body>

<nav class="navbar">

<a
href="index.php"
class="brand">

PWeb Akademik

</a>

<div>

<span
style="
color:white;
margin-right:15px;
">

Halo,
<?= htmlspecialchars($user_name) ?>

</span>

<a href="users/index.php">
Data User
</a>

<a href="auth/logout.php">
Logout
</a>

</div>

</nav>

<div class="container">

<div class="table-wrapper">

<h2
style="
color:#1F4E79;
margin-bottom:15px;
">

Selamat Datang,
<?= htmlspecialchars($user_name) ?>!

</h2>

<p>

Anda login sebagai:

<strong>
<?= htmlspecialchars($user_email) ?>
</strong>

</p>

<hr style="margin:20px 0;">

<h3>
Total Pengguna Terdaftar
</h3>

<p
style="
font-size:22px;
font-weight:bold;
color:#2E75B6;
">

<?= $dataTotal['total']; ?>

User

</p>

<br>

<h3>
3 User Terbaru
</h3>

<ul
style="
margin-left:20px;
margin-top:10px;
">

<?php
while(
    $user =
    mysqli_fetch_assoc(
        $queryUserBaru
    )
):
?>

<li
style="
margin-bottom:10px;
">

<?= htmlspecialchars($user['name']) ?>

-
<?= htmlspecialchars($user['email']) ?>

</li>

<?php endwhile; ?>

</ul>

<br>

<a
href="users/index.php"
class="btn btn-primary"
style="
width:auto;
">

Lihat Data User

</a>

</div>

</div>

<script src="assets/js/script.js"></script>

</body>
</html>