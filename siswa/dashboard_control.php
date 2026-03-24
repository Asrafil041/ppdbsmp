<?php

$id_user = $_SESSION['id_users'];
$data_pendaftar = array(); // Initialize empty array
$data_nilai = array(); // Initialize empty array
$status = null; // Initialize null

$sql_pendaftar = "SELECT * FROM pendaftar where users_id = ?";
$stmt_pendaftar = $koneksi->prepare($sql_pendaftar);
$stmt_pendaftar->bind_param("i", $id_user);
$stmt_pendaftar->execute();
$result_pendaftar = $stmt_pendaftar->get_result();

if($result_pendaftar && $result_pendaftar->num_rows > 0){

    $data_pendaftar = $result_pendaftar->fetch_array(MYSQLI_ASSOC);
    $id_pendaftar = $data_pendaftar['id'];

    $sql_nilai = "SELECT * FROM nilai where pendaftar_id = ?";
    $stmt_nilai = $koneksi->prepare($sql_nilai);
    $stmt_nilai->bind_param("i", $id_pendaftar);
    $stmt_nilai->execute();
    $result_nilai = $stmt_nilai->get_result();

    if($result_nilai && $result_nilai->num_rows > 0) {
        $data_nilai = $result_nilai->fetch_array(MYSQLI_ASSOC);
        $status = $data_nilai['status'];

    } else  {
        // jika belum ada data nilai/ status maka kosong
    }


    // simpan data nilai
    if(isset($_POST['btn_simpan']) && $_POST['btn_simpan'] == 'simpan_nilai') {

        $un = $_POST['un'];
        $us = $_POST['us'];
        $uts_1 = $_POST['uts_1'];
    
        $sql_insert_nilai = "INSERT INTO nilai (nilai_un, nilai_us, nilai_uts_1, status, pendaftar_id) values (?, ?, ?, 0, ?)";
        $stmt_insert = $koneksi->prepare($sql_insert_nilai);
        $stmt_insert->bind_param("dddi", $un, $us, $uts_1, $id_pendaftar);
        $query_insert_nilai = $stmt_insert->execute();

        if($query_insert_nilai){
            // berhasil
            header('location:dashboard.php');
            exit;
        } else {
            echo "error ". $stmt_insert->error;
            die;
        }
    
    }


    

}




?>