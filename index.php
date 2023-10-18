<?php
// Assuming the database configuration file is located at '../controllers/config.php'
include_once '../controllers/config.php';



// Step 1: Form submission handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseCode = $_POST['course_code'];

    // Step 2: Query the courses table
    $courseQuery = "SELECT * FROM courses WHERE course_code = '$courseCode' AND availability = 'on'";
    $courseResult = mysqli_query($conn, $courseQuery);

    if (mysqli_num_rows($courseResult) > 0) {
        // Step 4: Course available
        echo "Course is available.";
        // Step 9: Insert into registrations table
        $userLatitude = $_POST['latitude'];
        $userLongitude = $_POST['longitude'];

        // Query to fetch lecture halls with availability 'on'
        $sql = "SELECT hall_name, latitude1, longitude1, latitude2, longitude2, latitude3, longitude3, latitude4, longitude4 FROM lecture_halls WHERE availability = 'on'";
        $result = $conn->query($sql);

        // Check if any lecture halls found
        if ($result->num_rows > 0) {
            // Loop through each lecture hall
            while ($row = $result->fetch_assoc()) {
                $lectureHall = $row['hall_name'];
                $latitude1 = $row['latitude1'];
                $longitude1 = $row['longitude1'];
                $latitude2 = $row['latitude2'];
                $longitude2 = $row['longitude2'];
                $latitude3 = $row['latitude3'];
                $longitude3 = $row['longitude3'];
                $latitude4 = $row['latitude4'];
                $longitude4 = $row['longitude4'];

                // Check if the user's latitude and longitude fall within the range of the building's sides
                if (
                    $userLatitude >= min($latitude1, $latitude2, $latitude3, $latitude4) &&
                    $userLatitude <= max($latitude1, $latitude2, $latitude3, $latitude4) &&
                    $userLongitude >= min($longitude1, $longitude2, $longitude3, $longitude4) &&
                    $userLongitude <= max($longitude1, $longitude2, $longitude3, $longitude4)
                ) {
                    echo "You have signed for attendance in $lectureHall.";
                    $indexNumber = $_SESSION['username'];


                    $registrationQuery = "INSERT INTO registrations (Index_Number, course_code) VALUES ('$indexNumber', '$courseCode')";
                    mysqli_query($conn, $registrationQuery);
                } else {
                    echo "You are not in the location of the class at $lectureHall.";
                }

                echo "<br>";
            }
        }
    } else {
        echo "You are in the wrong location.";
    }
} else {
    // Step 4: Course not available
    echo "Course is not available.";
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php include 'header.php'; ?>

</head>

<body>

    <center>
        <h1>Attendance Registration</h1>
    </center><br><br>
    <center>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="course_code" placeholder="Enter Course Code" style="margin-bottom: 10px; padding: 5px; border: none; border-radius: 5px; color: black;">
            <button type="submit" style="padding: 10px 20px; border: none; border-radius: 5px; background-color: #006994; color: #fff; cursor: pointer;">Register</button>
            <span> <button onclick="getLocation()" style="padding: 10px 20px; border: none; border-radius: 5px; background-color: #006994; color: #fff; cursor: pointer;">Get
                    Location</button></span>
            <p id="demo"></p>
        </form>
    </center>
    <br>


    <div class="container mt-4">
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Attendance</h5>
                        <p class="card-text">View your student attendance records.</p>
                        <a href="attendance.php" class="btn btn-primary">View Attendance</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card" style="height: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Courses</h5>
                        <p class="card-text">View the courses you have registered attendance for.</p>
                        <a href="selection.php" class="btn btn-primary">View Courses</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                showError("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            document.getElementById("demo").innerHTML = `Latitude: ${latitude}<br>Longitude: ${longitude}`;

            // Add hidden input fields to the form and set the values
            const form = document.getElementsByTagName("form")[0];
            const latitudeInput = document.createElement("input");
            latitudeInput.type = "hidden";
            latitudeInput.name = "latitude";
            latitudeInput.value = latitude;
            form.appendChild(latitudeInput);

            const longitudeInput = document.createElement("input");
            longitudeInput.type = "hidden";
            longitudeInput.name = "longitude";
            longitudeInput.value = longitude;
            form.appendChild(longitudeInput);
        }

        function showError(error) {
            document.getElementById("demo").innerHTML = error;
        }
    </script>
    <?php include 'footer.php'; ?>
</body>

</html>