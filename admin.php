<?php
session_start();

if(!isset($_SESSION['id']) || $_SESSION['Roles'] !== "Admin") {
    header('Location: logout.php');
}