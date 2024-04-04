<?php
// Retrieve job post ID from URL parameters
$job_post_id = isset($_GET['id']) ? $_GET['id'] : null;
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job Post</title>
    <link rel="stylesheet" href="css/custom.css?<?php echo time();?>">
</head>
<body>
    <h3>Edit Job Post</h3>
    <div id="form-block">
        <form method="POST" action="processes/process.user.php?action=update_post">
            <label for="title">Title</label>
            <input type="text" id="title" class="input" name="title" value="<?php echo $user->get_job_title($job_post_id); ?>" placeholder="Title" required>

            <label for="description">Description</label>
            <textarea id="description" class="input" name="description" placeholder="Description.." required><?php echo $user->get_job_description($job_post_id); ?></textarea>

            <label for="company">Company</label>
            <input type="text" id="company" class="input" name="company" value="<?php echo $user->get_job_company($job_post_id); ?>" placeholder="Company.." required>

            <label for="location">Location</label>
            <input type="text" id="location" class="input" name="location" value="<?php echo $user->get_job_location($job_post_id); ?>" placeholder="Location.." required>

            <label for="salary">Salary</label>
            <input type="number" id="salary" class="input" name="salary" value="<?php echo $user->get_job_salary($job_post_id); ?>" placeholder="Salary.." required>

            <input type="hidden" name="id" value="<?php echo $job_post_id; ?>">
            <div id="button-block">
                <input type="submit" value="Update">
            </div>
        </form>
    </div>
</body>
</html>
