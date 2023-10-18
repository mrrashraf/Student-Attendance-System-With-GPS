<?php
include_once '../controllers/config.php';
include 'server.php';

// Check if the user is logged in
if (!isset($_SESSION['Index_Number'])) {
    // Redirect to login page or display an error message
    exit("Please log in to access this page");
}

// Retrieve the logged-in student's ID
$studentId = $_SESSION['Index_Number'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the course code entered by the student
    $courseCode = $_POST['course_code'];

    // Retrieve the selected location for the course
    $queryLocation = "SELECT selected_location FROM courses WHERE course_code = '$courseCode'";
    $resultLocation = mysqli_query($conn, $queryLocation);

    // Check if the course exists and has a selected location
    if (mysqli_num_rows($resultLocation) > 0) {
        $row = mysqli_fetch_assoc($resultLocation);
        $selectedLocation = $row['selected_location'];

        // Retrieve the user's device GPS coordinates (you can implement this functionality)
        $userLatitude = $_POST['latitude'];
        $userLongitude = $_POST['longitude'];

        // Retrieve the GPS coordinates of the selected location from the lecture_halls table
        $queryCoordinates = "SELECT gps_latitude, gps_longitude FROM lecture_halls WHERE hall_name = '$selectedLocation'";
        $resultCoordinates = mysqli_query($conn, $queryCoordinates);

        // Check if the user's coordinates match the selected location's coordinates
        if (mysqli_num_rows($resultCoordinates) > 0) {
            $rowCoordinates = mysqli_fetch_assoc($resultCoordinates);
            $locationLatitude = $rowCoordinates['gps_latitude'];
            $locationLongitude = $rowCoordinates['gps_longitude'];

            // Compare the user's coordinates with the selected location's coordinates
            if ($userLatitude == $locationLatitude && $userLongitude == $locationLongitude) {
                // User is in the correct location
                // Store the student's attendance in the registrations table
                $insertQuery = "INSERT INTO registrations (Index_Number, course_code) VALUES ('$studentId', '$courseCode')";
                mysqli_query($conn, $insertQuery);

                // Redirect to a success page or display a success message
                exit("Attendance registered successfully");
            } else {
                // User is not in the correct location
                exit("You are not in the correct location for this course");
            }
        }
    }

    // Course or location not found
    exit("Course or location not available");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register Attendance</title>
    <!-- Add any CSS or external dependencies if needed -->
</head>

<body>
    <h1>Register Attendance</h1>

    <form method="POST" action="">
        <label for="course_code">Course Code:</label>
        <input type="text" id="course_code" name="course_code" required>

        <!-- Add input fields for latitude and longitude (you can use JavaScript to obtain these values) -->
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">

        <input type="submit" value="Submit Attendance">
    </form>

    <!-- Include any JavaScript code to obtain the user's device coordinates and populate the hidden fields -->
    <!-- You can use the Geolocation API or any other suitable method -->
    <script>
        // Example using the Geolocation API
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }

        function successCallback(position) {
            document.getElementById("latitude").value = position.coords.latitude;
            document.getElementById("longitude").value = position.coords.longitude;
        }

        function errorCallback(error) {
            console.log(error.message);
        }
    </script>
</body>

</html>