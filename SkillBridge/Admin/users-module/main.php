<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
    <link rel="stylesheet" href="css/custom.css?<?php echo time(); ?>">
</head>
<body>
    <h3>WIP Homepage</h3>
    <div id="second-submenu">
        <a href="index.php?page=post">Create Post</a> | 
        Search <input type="text" id="searchInput" placeholder="Search..." oninput="searchUsers(this.value)">
    </div>

    <div id="jobPostings">
        <?php
        // Assuming $user and its methods are already defined

        $jobPostings = $user->fetch_job_postings();

        if ($jobPostings) {
            foreach ($jobPostings as $job) {
                echo '<div class="jobPosting">';
                echo '<h4>' . $job['title'] . '</h4>';
                echo '<p>Description: ' . $job['description'] . '</p>';
                echo '<p>Company Name: ' . $job['company'] . '</p>';
                echo '<p>Location: ' . $job['location'] . '</p>';
                echo '<p>Salary: ' . $job['salary'] . '</p>';
                
                echo '<div class="button-group">'; 
                echo '<a href="index.php?page=edit&id=' . $job['id'] . '" class="editbutton">Edit</a>';
                
                echo '<form id="deleteForm" action="processes/process.user.php?action=delete_job_post" method="post" onsubmit="return confirm(\'Are you sure you want to delete this job post?\');">';
                echo '<input type="hidden" name="id" value="' . $job['id'] . '">';
                echo '<input id="deleteButton" type="submit" value="Delete">';
                echo '</form>';
                echo '</div>'; // Close button-group container
                echo '</div>'; // Close jobPosting container
            }
        } else {
            echo '<p>No job postings found.</p>';
        }
        ?>
    </div>
</body>
</html>
