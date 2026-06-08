<?php

session_start();

require_once "../config/koneksi.php";

$error = "";

$email = "";

$flash = "";

if (
    isset($_SESSION['flash_message'])
) {

    $flash =
    $_SESSION['flash_message'];

    unset(
        $_SESSION['flash_message']
    );

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email =
    trim($_POST["email"]);

    $password =
    $_POST["password"];

    if (
        empty($email) ||
        empty($password)
    ) {

        $error =
        "Email dan password wajib diisi!";

    }

    else {

        $stmt =
        mysqli_prepare(
            $conn,
            "SELECT *
             FROM users
             WHERE email = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $email
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

        mysqli_stmt_close(
            $stmt
        );

        if (
            $user &&
            password_verify(
                $password,
                $user['password']
            )
        ) {

            session_regenerate_id(
                true
            );

            $_SESSION['user_id'] =
            $user['id'];

            $_SESSION['user_name'] =
            $user['name'];

            $_SESSION['user_email'] =
            $user['email'];

            header(
                "Location: ../index.php"
            );

            exit;

        }

        else {

            $error =
            "Email atau password salah!";

        }

    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>
Login - PWeb Akademik
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

<a href="login.php">
Login
</a>

<a href="register.php">
Daftar
</a>

</div>

</nav>

<div class="form-card">

<h2>
Masuk ke Akun
</h2>

<?php if($flash): ?>

<div class="alert alert-success">

<?= htmlspecialchars($flash) ?>

</div>

<?php endif; ?>

<?php if($error): ?>

<div class="alert alert-error">

<?= htmlspecialchars($error) ?>

</div>

<?php endif; ?>

<form
action="login.php"
method="POST">

<div class="form-group">

<label>
Email
</label>

<input
type="email"
name="email"
placeholder="email@contoh.com"
value="<?= htmlspecialchars($email) ?>">

</div>

<div class="form-group">

<label>
Password
</label>

<div style="position:relative;">

<input
type="password"
id="password"
name="password"
placeholder="Masukkan password">

<span
id="togglePwd"
onclick="togglePassword('password','togglePwd')"
style="
position:absolute;
right:12px;
top:12px;
cursor:pointer;">

👁️

</span>

</div>

</div>

<button
type="submit"
class="btn btn-primary">

Masuk

</button>

</form>

<p
class="text-center"
style="margin-top:20px;">

Belum punya akun?

<a
href="register.php"
class="link-secondary">

Daftar di sini

</a>

</p>

</div>

<script src="../assets/js/script.js"></script>

</body>
</html>