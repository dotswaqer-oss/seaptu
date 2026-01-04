<?php
include("koneksi.php");
$nama = $_POST['nama'];
$email = $_POST['email'];
$pesan = $_POST['pesan'];

mysqli_query($koneksi, "insert into bukutamu values('','$nama','$email','$pesan')");

header("location:index.php");