<?php

session_start();

require_once "../config/koneksi.php";

$error = "";

$name = "";
$email = "";
$address = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $address = trim($_POST["address"]);

    if (
        empty($name) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password) ||
        empty($address)
    ) {

        $error = "Semua field wajib diisi!";

    }

    elseif (
        strlen($name) < 3 ||
        strlen($name) > 50
    ) {

        $error = "Nama harus 3 - 50 karakter!";

    }

    elseif (
        !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $error = "Format email tidak valid!";

    }

    elseif (
        !preg_match(
            '/^(?=.*[A-Za-z])(?=.*\d).{8,}$/',
            $password
        )
    ) {

        $error = "Password minimal 8 karakter dan harus mengandung huruf serta angka!";

    }

    elseif (
        $password != $confirm_password
    ) {

        $error = "Konfirmasi password tidak cocok!";

    }

    else {

        $cekEmail = mysqli_prepare(
            $conn,
            "SELECT id
             FROM users
             WHERE email = ?"
        );

        mysqli_stmt_bind_param(
            $cekEmail,
            "s",
            $email
        );

        mysqli_stmt_execute($cekEmail);

        $hasilEmail =
        mysqli_stmt_get_result(
            $cekEmail
        );

        if (
            mysqli_num_rows(
                $hasilEmail
            ) > 0
        ) {

            $error = "Email sudah terdaftar!";

        }

        else {

            $hashPassword =
            password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt =
            mysqli_prepare(
                $conn,
                "INSERT INTO users
                (
                    name,
                    email,
                    password,
                    address
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?
                )"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "ssss",
                $name,
                $email,
                $hashPassword,
                $address
            );

            if (
                mysqli_stmt_execute(
                    $stmt
                )
            ) {

                $_SESSION['flash_message'] =
                "Registrasi berhasil! Silakan login.";

                header(
                    "Location: login.php"
                );

                exit;

            }

            else {

                $error =
                "Registrasi gagal!";

            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>
Register - PWeb Akademik
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
Registrasi Akun
</h2>

<?php if($error): ?>

<div class="alert alert-error">

<?= htmlspecialchars($error) ?>

</div>

<?php endif; ?>

<form
action="register.php"
method="POST">

<div class="form-group">

<label>
Nama
</label>

<input
type="text"
name="name"
value="<?= htmlspecialchars($name) ?>"
placeholder="Masukkan nama">

</div>

<div class="form-group">

<label>
Email
</label>

<input
type="email"
name="email"
value="<?= htmlspecialchars($email) ?>"
placeholder="email@contoh.com">

</div>

<div class="form-group">

<label>
Password
</label>

<input
type="password"
id="password"
name="password"
placeholder="Masukkan password">

</div>

<div class="form-group">

<label>
Konfirmasi Password
</label>

<input
type="password"
name="confirm_password"
placeholder="Ulangi password">

</div>

<div class="form-group">

<label>
Alamat
</label>

<textarea
name="address"
rows="4"
placeholder="Masukkan alamat"><?= htmlspecialchars($address) ?></textarea>

</div>

<button
type="submit"
class="btn btn-primary">

Daftar

</button>

</form>

<p
class="text-center"
style="margin-top:20px;">

Sudah punya akun?

<a
href="login.php"
class="link-secondary">

Login di sini

</a>

</p>

</div>

<script src="../assets/js/script.js"></script>

</body>
</html>