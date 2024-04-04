<!DOCTYPE html>
<html>
<head>
    <title>Jobs</title>
    <link rel="stylesheet" href="css/custom.css?<?php echo time(); ?>">
</head>
<body>
    <h3>Find Your Job</h3>
    <?php
// Check if the user's access level is not "Applicant"
if ($_SESSION['user_access'] != 'Applicant') {
?>
<div id="second-submenu">
    <a href="index.php?page=post">Create Post</a> | 
    Search <input type="text" id="searchInput" placeholder="Search..." oninput="searchUsers(this.value)">
    <a href="?sort=latest">| Latest Post |</a>
    <a href="?sort=oldest">| Oldest Post |</a>
    <a href="?sort=salary_desc">| Highest Salary |</a>
    <a href="?sort=salary_asc">| Lowest Salary |</a>
</div>
<?php
} else {
?>
<div id="second-submenu">
    Search <input type="text" id="searchInput" placeholder="Search..." oninput="searchUsers(this.value)">
    <a href="?sort=latest">| Latest Post |</a>
    <a href="?sort=oldest">| Oldest Post |</a>
    <a href="?sort=salary_desc">| Highest Salary |</a>
    <a href="?sort=salary_asc">| Lowest Salary |</a>
</div>
<?php
}
?>
    <div id="jobPostings">
    <?php
    
    function sortByDate($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    }
    
    // Sorting function for salary (high to low)
    function sortBySalaryDesc($a, $b) {
        return $b['salary'] - $a['salary'];
    }
    
    // Sorting function for salary (low to high)
    function sortBySalaryAsc($a, $b) {
        return $a['salary'] - $b['salary'];
    }

        $jobPostings = $user->fetch_job_postings();

        
        if ($jobPostings) {

            if (isset($_GET['sort'])) {
                $sort = $_GET['sort'];
                switch ($sort) {
                    case 'date':
                        usort($jobPostings, 'sortByDate');
                        break;
                    case 'oldest':
                        usort($jobPostings, function($a, $b) {
                            return strtotime($a['created_at']) - strtotime($b['created_at']);
                        });
                        break;
                    case 'latest':
                        usort($jobPostings, function($a, $b) {
                            return strtotime($b['created_at']) - strtotime($a['created_at']);
                        });
                        break;
                    case 'salary_desc':
                        usort($jobPostings, 'sortBySalaryDesc');
                        break;
                    case 'salary_asc':
                        usort($jobPostings, 'sortBySalaryAsc');
                        break;
                    default:
                        break;
                }
            }

            foreach ($jobPostings as $job) {
                echo '<div class="jobPosting">';
                $postedTime = strtotime($job['created_at']);
                $currentTime = time();
                $timeDifference = $currentTime - $postedTime;

                // Convert the time difference into seconds, minutes, hours, or days
                if ($timeDifference < 60) {
                    $postedAgo = $timeDifference . ' seconds ago';
                } elseif ($timeDifference < 3600) {
                    $postedAgo = floor($timeDifference / 60) . ' minutes ago';
                } elseif ($timeDifference < 86400) {
                    $postedAgo = floor($timeDifference / 3600) . ' hours ago';
                } else {
                    $postedAgo = floor($timeDifference / 86400) . ' days ago';
                }


                echo '<h4>' . $job['title'] . '</h4>';
                echo '<p class="timestamp">' . $postedAgo . '</p>';
                echo '<p>Description: ' . $job['description'] . '</p>';
                echo '<p>Company: ' . $job['company'] . '</p>';
                echo '<p>Location: ' . $job['location'] . '</p>';
                echo '<p>Salary: $' . $job['salary'] . '</p>';

                echo '<br>';
                $owner_id           = $job['user_id'];
                $owner_firstname    = $user->get_user_firstname($owner_id);
                $owner_lastname     = $user->get_user_lastname($owner_id);
                $owner_contactNumber = $user->get_user_number($owner_id);
                $owner_email        = $user->get_user_email($owner_id);
                $owner_pic          = $user->get_user_profilepic($owner_id);

                echo '<div class="contact-info">';
                echo '<img src="css/profilepic/' . $owner_pic . '" alt="Owner\'s Picture">';
                echo '<br>';
                echo '<p>' . $owner_lastname . ', ' . $owner_firstname . '</p>';
                echo '<br>';
                echo '<p><strong>Email:</strong> ' . $owner_email. '</p>';
                echo '<br>';
                echo '<p><strong>Contact Number:</strong> ' . $owner_contactNumber. '</p>';
                echo '<br>';
                echo '</div>';
                // Check if the logged-in user is the owner of the job posting
                if ($_SESSION['user_email'] == $job['user_email']) {
                    echo '<div class="button-group">'; 
                    echo '<a href="index.php?page=edit&id=' . $job['post_id'] . '" class="editbutton">Edit</a>';

                    // Apply button with file input (only visible for users with access level "applicant")


                    // View Applicants button (only visible for employers)
                    echo '<a href="index.php?page=view&post_id='  . $job['post_id'] . '" class="editbutton">View Applicants</a>';

                    // Delete job post button
                    echo '<form id="deleteForm" action="processes/process.user.php?action=delete_job_post" method="post" onsubmit="return confirm(\'Are you sure you want to delete this job post?\');">';
                    echo '<input type="hidden" name="id" value="' . $job['post_id'] . '">';
                    echo '<input id="deleteButton" type="submit" value="Delete" >';
                    echo '</form>';
                    
                    echo '</div>'; // Close button-group container
                }
                    else if ($_SESSION['user_access'] == 'Applicant') {
                    echo '<form action="processes/process.user.php?action=apply" method="post" enctype="multipart/form-data">';
                    echo '<input type="hidden" name="post_id" value="' . $job['post_id'] . '">';
                    echo '<label class="file-upload-btn">';
                    echo 'Upload Resume';
                    echo '<input type="file" name="resume" accept="application/pdf" class="file-input">';
                    echo '</label>';
                    echo '<input type="submit" value="Apply" class="editbutton">';
                    echo '</form>';
                    }
                
                echo '</div>';
            }
        } else {
            echo '<p>No job postings found.</p>';
        }
    ?>
    </div>
</body>
</html>
