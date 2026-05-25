<?php
session_start();
include '../koneksi.php';

if(isset($_POST['login'])){

    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = md5($_POST['password']);

    $query = mysqli_query($koneksi, "
        SELECT * FROM users
        WHERE email='$email'
        AND password='$password'
    ");

    if(mysqli_num_rows($query) > 0){

        $data = mysqli_fetch_assoc($query);

        $_SESSION['id']    = $data['id'];
        $_SESSION['email'] = $data['email'];
        $_SESSION['role']  = $data['role'];

        // redirect berdasarkan role
        if($data['role'] == 'admin'){

            header("Location: admin/dashboard.php");

        } else {

            header("Location: user/index.php");

        }

    } else {

        echo "
        <script>
            alert('Email atau password salah!');
            window.location='index.php';
        </script>
        ";

    }
}
?>