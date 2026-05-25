<?php
include '../koneksi.php';

if(isset($_POST['register'])){

    $nama_depan    = mysqli_real_escape_string($koneksi, $_POST['nama_depan']);
    $nama_belakang = mysqli_real_escape_string($koneksi, $_POST['nama_belakang']);
    $email         = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password      = md5($_POST['password']);
    $phone         = mysqli_real_escape_string($koneksi, $_POST['phone']);
    $kota          = mysqli_real_escape_string($koneksi, $_POST['kota']);

    // cek email
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($cek) > 0){

        echo "
        <script>
            alert('Email sudah digunakan!');
            window.location='index.php';
        </script>
        ";

    } else {

        $query = mysqli_query($koneksi, "
            INSERT INTO users
            (nama_depan, nama_belakang, email, password, phone, kota, role)
            VALUES
            ('$nama_depan','$nama_belakang','$email','$password','$phone','$kota','user')
        ");

        if($query){

            echo "
            <script>
                alert('Register berhasil!');
                window.location='index.php';
            </script>
            ";

        } else {

            echo "
            <script>
                alert('Register gagal!');
                window.location='index.php';
            </script>
            ";

        }
    }
}
?>