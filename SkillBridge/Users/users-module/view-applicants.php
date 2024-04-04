<!DOCTYPE html>
<html>
<head>
    <title>View Applicants</title>
    <link rel="stylesheet" href="css/custom.css">
</head>
<body>
    <h3>View Applicants</h3>
    <div id="jobPostings">
    <?php

        // Check if the post_id parameter is set in the URL
        if(isset($_GET['post_id'])) {
            $post_id = $_GET['post_id'];

            // Create a new instance of the User class
            $user = new User();

            // Retrieve applicants for the specified job post
            $applicants = $user->get_job_applicants($post_id);

            // Check if applicants were found
            if ($applicants) {
                foreach ($applicants as $applicant) {
                    echo '<div class="applicant">';
                    echo '<img src="css/profilepic/' . $applicant['user_profilepic'] . '" alt="Profile Picture">';
                    echo '<div class="applicant-details">';
                    echo '<h4>Applicant</h4>';
                    echo '<p><strong>First Name:</strong> ' . $applicant['user_firstname'] . '</p>';
                    echo '<p><strong>Last Name:</strong> ' . $applicant['user_lastname'] . '</p>';
                    echo '<p><strong>Email:</strong> ' . $applicant['user_email'] . '</p>';
                    echo '<p><strong>Resume:</strong> <a href="css/resume/' . $applicant['resume'] . '">Download</a></p>';
                    echo '</div>'; // Close applicant details
                    echo '</div>'; // Close applicant container
                }
            } else {
                echo '<p>No applicants found for this job post.</p>';
            }
        } else {
            echo '<p>Post ID not provided.</p>';
        }
    ?>
    </div>
</body>
</html>
