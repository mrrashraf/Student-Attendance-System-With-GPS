<?php
include_once '../controllers/config.php';
include_once 'header.php'; // Include your header file here
?>

<!-- Your HTML content here -->
<div class="container">
    <h1>Registered Courses</h1>

    <?php
    // Get the session of the logged-in user from the username (replace with your own session handling code)
    $username = $_SESSION['username']; // Change 'username' to the actual session variable name

    // Query the database to get the unique registered courses for the student's session
    $sql = "SELECT DISTINCT c.course_name, c.course_code, c.lecturer_name
            FROM registrations AS r
            INNER JOIN courses AS c ON r.course_code = c.course_code
            WHERE r.index_number = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr>';
        echo '<th>Course Name</th>';
        echo '<th>Course Code</th>';
        echo '<th>Lecturer Name</th>';
        echo '</tr>';

        while ($row = $result->fetch_assoc()) {
            $courseName = $row['course_name'];
            $courseCode = $row['course_code'];
            $lecturerName = $row['lecturer_name'];

            echo '<tr>';
            echo '<td>' . $courseName . '</td>';
            echo '<td>' . $courseCode . '</td>';
            echo '<td>' . $lecturerName . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'No registered courses found for this user.';
    }

    // Close the database connection
    $conn->close();
    ?>

</div>

<?php
include_once 'footer.php'; // Include your footer file here
?>