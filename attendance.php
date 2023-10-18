<?php
include_once '../controllers/config.php';
include_once 'header.php'; // Include your header file here
?>

<!-- Your HTML content here -->
<div class="container">
    <h1>Registration Details</h1>

    <?php
    // Get the session of the logged-in user from the username (replace with your own session handling code)
    $username = $_SESSION['username']; // Change 'username' to the actual session variable name

    // Query the database to get the course code and registration count for the student's session
    $sql = "SELECT course_code, COUNT(*) AS registration_count
            FROM registrations
            WHERE index_number = '$username'
            GROUP BY course_code";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr>';
        echo '<th>Course Code</th>';
        echo '<th>Registration Count</th>';
        echo '</tr>';

        while ($row = $result->fetch_assoc()) {
            $courseCode = $row['course_code'];
            $registrationCount = $row['registration_count'];

            echo '<tr>';
            echo '<td>' . $courseCode . '</td>';
            echo '<td>' . $registrationCount . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'No registration details found for this user.';
    }

    // Close the database connection
    $conn->close();
    ?>

</div>

<?php
include_once 'footer.php'; // Include your footer file here
?>