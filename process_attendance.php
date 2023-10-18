<?php
session_start();
include_once '../controllers/config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the course code entered by the student
    $courseCode = $_POST['course_code'];

    // Check if the course availability is set to 'on'
    $queryCourse = "SELECT availability FROM courses WHERE course_code = '$courseCode'";
    $resultCourse = mysqli_query($conn, $queryCourse);

    // Check if the course exists and availability is 'on'
    if (mysqli_num_rows($resultCourse) > 0) {
        $rowCourse = mysqli_fetch_assoc($resultCourse);
        $availability = $rowCourse['availability'];

        if ($availability === 'on') {
            // Retrieve the GPS coordinates of the selected location from the lecture_halls table
            $queryCoordinates = "SELECT gps_latitude, gps_longitude FROM lecture_halls WHERE availability = 'on'";
            $resultCoordinates = mysqli_query($conn, $queryCoordinates);
            // Check if a matching lecture hall is found
            if (mysqli_num_rows($resultCoordinates) > 0) {
                $rowCoordinates = mysqli_fetch_assoc($resultCoordinates);
                $locationLatitude = floatval($rowCoordinates['gps_latitude']);
                $locationLongitude = floatval($rowCoordinates['gps_longitude']);

                // Retrieve the user's device GPS coordinates
                $userLatitude = floatval($_POST['latitude']);
                $userLongitude = floatval($_POST['longitude']);

                // Debugging: Display the latitude and longitude values
                echo "User Latitude: " . $userLatitude . "<br>";
                echo "User Longitude: " . $userLongitude . "<br>";
                echo "Location Latitude: " . $locationLatitude . "<br>";
                echo "Location Longitude: " . $locationLongitude . "<br>";

                // Compare the user's coordinates with the selected location's coordinates
                if ($userLatitude == $locationLatitude && $userLongitude == $locationLongitude) {
                    // User is in the correct location

                    // Retrieve the user's index number from the session
                    // Assuming you have stored the user's index number in $_SESSION['user_id']
                    $indexNumber = $_SESSION['user_id'];

                    // Store the student's attendance in the registrations table
                    $insertQuery = "INSERT INTO registrations (Index_Number, course_code) VALUES ('$indexNumber', '$courseCode')";
                    mysqli_query($conn, $insertQuery);

                    // Print a success message or redirect to a success page
                    echo "Attendance registered successfully";
                    exit();
                } else {
                    // User is not in the correct location
                    echo "You are not in the correct location for this course";
                    exit();
                }
            }
        }
    } else {
        // Course is not available
        echo "Course is not available for attendance";
        exit();
    }
} else {
    // Course not found
    echo "Course not found";
    exit();
}
