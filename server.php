<?php

// Starting the session, necessary
// for using session variables
session_start();

// Declaring and hoisting the variables
$username = "";
$email = "";
$errors = array();
$_SESSION['success'] = "";

// DBMS connection code -> hostname,
// username, password, database name
$db = mysqli_connect('localhost', 'root', '', 'sas');

// Registration code
if (isset($_POST['reg_user'])) {

	// Receiving the values entered and storing
	// in the variables
	// Data sanitization is done to prevent
	// SQL injections
	$username = mysqli_real_escape_string($db, $_POST['username']);
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$fullname = mysqli_real_escape_string($db, $_POST['Full_Name']);
	$level = mysqli_real_escape_string($db, $_POST['level']);
	$program = mysqli_real_escape_string($db, $_POST['program']);
	$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
	$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

	// Ensuring that the user has not left any input field blank
	// error messages will be displayed for every blank input
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($email)) {
		array_push($errors, "Email is required");
	}
	if (empty($fullname)) {
		array_push($errors, "Name is required");
	}
	if (empty($level)) {
		array_push($errors, "Level is required");
	}
	if (empty($program)) {
		array_push($errors, "Program is required");
	}
	if (empty($password_1)) {
		array_push($errors, "Password is required");
	}

	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
		// Checking if the passwords match
	}

	// If the form is error free, then register the user
	if (count($errors) == 0) {

		// Password encryption to increase data security
		$password = md5($password_1);

		// Inserting data into table
		$query = "INSERT INTO studentinfo (index_number, Password, Full_Name, Email, Level, Program)
				VALUES('$username', '$password', '$fullname', '$email', '$level', '$program')";

		mysqli_query($db, $query);

		// Storing username of the logged in user,
		// in the session variable
		$_SESSION['Index_Number'] = $username;

		// Welcome message
		$_SESSION['success'] = "You have logged in";

		// Page on which the user will be
		// redirected after logging in
		header('location: index.php');
	}
}

// User login
if (isset($_POST['login_user'])) {

	// Data sanitization to prevent SQL injection
	$username = mysqli_real_escape_string($db, $_POST['username']);
	$password = mysqli_real_escape_string($db, $_POST['password']);

	// Error message if the input field is left blank
	if (empty($username)) {
		array_push($errors, "Index Number is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	// Checking for the errors
	if (count($errors) == 0) {

		// Password matching
		$password = md5($password);

		$query = "SELECT * FROM studentinfo WHERE index_number=
				'$username' AND password='$password'";
		$results = mysqli_query($db, $query);

		// $results = 1 means that one user with the
		// entered username exists
		if (mysqli_num_rows($results) == 1) {

			// Storing username in session variable
			$_SESSION['index_number'] = $username;

			// Welcome message
			$_SESSION['success'] = "You have logged in!";

			// Page on which the user is sent
			// to after logging in
			header('location: index.php');
		} else {

			// If the username and password doesn't match
			array_push($errors, "Index Number or password incorrect");
		}
	}
}
