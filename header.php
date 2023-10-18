<?php
// Include the config.php file to establish the database connection
include_once '../controllers/config.php';


// Starting the session, to use and
// store data in session variable
session_start();

// If the session variable is empty, this
// means the user is yet to login
// User will be sent to 'login.php' page
// to allow the user to login
//if (!isset($_SESSION['username'])) {
//  $_SESSION['msg'] = "You have to log in first";
//header('location: login.php');
//}

// Logout button will destroy the session, and
// will unset the session variables
// User will be headed to 'login.php'
// after logging out
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    //header("location: login.php");
}
?>
<!DOCTYPE html>
<html>
<style>

</style>

<head>
    <title>Student Attendance System</title>
    <!-- Include Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Include header CSS file -->
    <link rel="stylesheet" href="../css/home.css">

</head>

<body>

    <head>
        <link rel="stylesheet" href="css/home.css">
    </head>
    <header id="header">
        <div id="img-container">
            <img id="header-img" src="../images/logo.jpg" alt="PEIT">
        </div>
        <nav id="nav-bar">
            <ul>
                <li><a class="nav-link" href="#landing">Top</a></li>
                <li><a class="nav-link" href="#programs">Programs</a></li>
                <li><a class="nav-link" href="#projects">Contact Us</a></li>
                <li><a class="nav-link" href="#contact">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section id="landing">
            <div id="hero-section">
                <div id="dark">
                    <div id="container">
                        <h1>UNIVERSITY OF ENERGY AND NATURAL RESOURCES</span></h1>
                    </div>
                </div>
            </div>
        </section>
</body>


</html>