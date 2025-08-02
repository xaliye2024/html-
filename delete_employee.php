<?php
include_once 'connection.php';


if(isset($_GET['id'])){

$id = $_GET['id'];

$delete = $pdo->prepare("DELETE FROM tbl_hrm WHERE id =?");
$delete->execute([$id]);

header('location:employee_list.php');
$delete = $pdo->prepare("DELETE FROM tbl_salary WHERE id =?");
$delete->execute([$id]);
header('location:employee_salary.php');
}








?>