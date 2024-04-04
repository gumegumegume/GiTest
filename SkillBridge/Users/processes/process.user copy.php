<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../classes/class.user.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action){
    case 'create_post':
        create_new_job_post();
    break;
    case 'new':
        create_new_user();
    break;
    case 'update':
        update_user();
    break;
    case 'deactivate':
        deactivate_user();
    break;
    case 'activate':
        activate_user();
    break;
    case 'delete':
        delete_user();
    break;
    case 'fetch_job_postings':
        fetch_job_postings();
    break;
    case 'delete_job_post':
        delete_job_post();
    break;
    case 'update_post':
        update_job_post();
    break;
    case 'apply':
        apply_for_job();
    break;
}
function view_applicants() {
    // Check if the post_id parameter is set in the URL
    if(isset($_GET['post_id'])) {
        $post_id = $_GET['post_id'];

        // Create a new instance of the User class
        $user = new User();

        // Retrieve applicants for the specified job post
        $applicants = $user->get_job_applicants($post_id);

        // Check if applicants were found
        if ($applicants) {
            echo '<div id="applicantList">';
            echo '<h4>Applicants for Job Post</h4>';
            foreach ($applicants as $applicant) {
                echo '<div class="applicant">';
                echo '<p>First Name: ' . $applicant['user_firstname'] . '</p>';
                echo '<p>Last Name: ' . $applicant['user_lastname'] . '</p>';
                echo '<p>Email: ' . $applicant['user_email'] . '</p>';
                echo '<p>Resume: <a href="../css/resume/' . $applicant['resume'] . '">Download</a></p>';
                echo '</div>'; // Close applicant container
            }
            echo '</div>'; // Close applicantList container
        } else {
            echo '<p>No applicants found for this job post.</p>';
        }
    } else {
        echo '<p>Post ID not provided.</p>';
    }
}

function apply_for_job() {
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve job post ID and resume file from the form
        $post_id = $_POST['post_id'];
        $resume = $_FILES['resume'];

        // Check if file is uploaded successfully
        if ($resume['error'] !== UPLOAD_ERR_OK) {
            echo '<script>alert("Please Upload a file first");</script>';
            echo '<script>window.location.href = "../index.php";</script>';
            return;
        }

        // Check file type
        $file_type = mime_content_type($resume['tmp_name']);
        if ($file_type !== 'application/pdf') {
            echo '<script>alert("Only PDF files are allowed.");</script>';
            echo '<script>window.location.href = "../index.php";</script>';
            return;
        }

        // Move uploaded file to desired location
        $upload_directory = '../css/resume/';
        $resume_filename = 'resume_' . uniqid() . '.pdf';
        $resume_path = $upload_directory . $resume_filename;        
        if (!move_uploaded_file($resume['tmp_name'], $resume_path)) {
            echo "Failed to move uploaded file.";
            return;
        }

        // Get user details from session
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
        $user_profilepic = isset($_SESSION['user_profilepic']) ? $_SESSION['user_profilepic'] : '';
        $user_firstname = isset($_SESSION['user_firstname']) ? $_SESSION['user_firstname'] : '';
        $user_lastname = isset($_SESSION['user_lastname']) ? $_SESSION['user_lastname'] : '';
        $user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';

        // Create a new instance of the User class
        $user = new User();

        // Call the method to apply for the job
        $result = $user->apply_for_job($post_id, $user_id, $user_profilepic, $user_firstname, $user_lastname, $user_email, $resume_filename);

        if($result) {
            // Display success message or redirect to a success page
            echo '<script>alert("Application submitted successfully.");</script>';
            echo '<script>window.location.href = "../index.php";</script>';
        } else {
            // Handle application failure
            echo '<script>alert("Failed to submit application.");</script>';
            echo '<script>window.location.href = "../index.php";</script>';
        }
    }
}


function update_job_post(){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Retrieve job post details from form
        $post_id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $company = $_POST['company'];
        $location = $_POST['location'];
        $salary = $_POST['salary'];

        // Create a new instance of the User class
        $user = new User();

        // Call the method to update the job post
        $result = $user->update_job_post($title, $description, $company, $location, $salary, $post_id);

        if($result){
            // Display a JavaScript popup
            echo '<script>alert("Job post updated successfully");</script>';
            // Redirect to the index page after the popup is closed
            echo '<script>window.location.href = "../index.php";</script>';
        } else {
            // Handle update failure
            echo "Failed to update job post";
        }
    }
}
function delete_job_post(){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $user = new User();
        $post_id = $_POST['id'];
        $result = $user->delete_job_post($post_id);
        if($result){
            // Display a JavaScript popup
            echo '<script>alert("Job post deleted successfully");</script>';
            // Redirect to the index page after the popup is closed
            echo '<script>window.location.href = "../index.php";</script>';
        } else {
            // Handle deletion failure
            // You can display an error message or redirect to an error page
            echo "Failed to delete job post";
        }
    }
}


function fetch_job_postings(){
    // Create a new instance of the User class
    $user = new User();
    
    // Call the method to fetch job postings
    $jobPostings = $user->fetch_job_postings();
    
    // Check if job postings were fetched successfully
    if($jobPostings){
        // Output the job postings or perform any other action
        foreach($jobPostings as $posting){
            // Output or process each job posting
            echo "Title: " . $posting['title'] . "<br>";
            echo "Description: " . $posting['description'] . "<br>";
            echo "Company: " . $posting['company'] . "<br>";
            echo "Location: " . $posting['location'] . "<br>";
            echo "Salary: " . $posting['salary'] . "<br><br>";
        }
    } else {
        // Handle case when no job postings are found
        echo "No job postings found.";
    }
}

function create_new_job_post(){
    // Start the session
    session_start();

    // Check if form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Validate form data here (e.g., check for empty fields)
        if(empty($_POST['title']) || empty($_POST['description']) || empty($_POST['company']) || empty($_POST['location']) || empty($_POST['salary'])) {
            echo "All fields are required.";
            return;
        }
        
        // Sanitize inputs to prevent SQL injection
        $title = htmlspecialchars($_POST['title']);
        $description = htmlspecialchars($_POST['description']);
        $company = htmlspecialchars($_POST['company']);
        $location = htmlspecialchars($_POST['location']);
        $salary = htmlspecialchars($_POST['salary']);

        // Get the email from the session or form, wherever it is available
        $email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';

        // Check if email is empty
        if(empty($email)) {
            echo "Email is required.";
            return;
        }

        // Create a new instance of the User class
        $user = new User();
        
        // Call the method to create a new job post
        $result = $user->create_new_job_post($title, $description, $company, $location, $salary, $email);

        // Check if job post creation was successful
        if($result){
            // Redirect user back to the form or a success page
            echo  ('Post added');
            header('Location: ../index.php');
            exit;
        } else {
            // Handle job post creation failure
            // You can display an error message or redirect to an error page
            echo "Failed to create job post";
        }
    }
}





function delete_user(){
    $user = new User();
    $user_id = $_POST['userid'];
    $result = $user->delete_user($user_id);
    if($result){
        // Redirect or display a message after successful deletion
        header('location: ../index.php?message=Account%20deleted%20successfully');
    } else {
        // Handle deletion failure
        // You can display an error message or redirect to an error page
        echo "Failed to delete account";
    }
}

function create_new_user(){
    $user = new User();
    $email = $_POST['email'];
    $lastname = ucwords($_POST['lastname']);
    $firstname = ucwords($_POST['firstname']);
    $access = ucwords($_POST['access']);
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];
    
    // Check if the email already exists in the database
    $existing_user_id = $user->get_user_id($email);
    if($existing_user_id){
        // Email already exists, show a popup indicating that the email is already taken
        echo '<script>alert("Email already exists. Please choose a different email.");';
        // Redirect after the user closes the alert
        $id = $user->get_user_id($email);
        echo 'window.location.href = "../index.php?page=settings&subpage=users&action=create&id='.$id.'";</script>';
        return false;
    }
    
    // If the email doesn't exist, proceed with creating the new user
    $result = $user->new_user($email,$password,$lastname,$firstname,$access);
    if($result){
        // User created successfully, redirect to a profile page or another destination
        $id = $user->get_user_id($email);
        header('location: ../index.php?page=settings&subpage=users&action=profile&id='.$id);
    } else {
        // Handle any other errors during user creation
        echo "Failed to create user";
    }
}



function update_user(){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Retrieve user details from form
        $user_id = $_POST['userid'];
        $lastname = ucwords($_POST['lastname']);
        $firstname = ucwords($_POST['firstname']);
        $access = ucwords($_POST['access']);
        $newFileName = ''; // Define $newFileName initially as an empty string

        // Handle profile picture upload
        if(isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] === UPLOAD_ERR_OK) {
            $uploadDirectory = '../css/profilepic/';
            $fileTmpPath = $_FILES['profile-pic']['tmp_name'];
            $fileName = $_FILES['profile-pic']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = 'profile_pic_' . $user_id . '.' . $fileExtension;

            if (move_uploaded_file($fileTmpPath, $uploadDirectory . $newFileName)) {
                // Profile picture uploaded successfully, proceed with updating the user record in the database
                $user = new User();
                $result = $user->updateProfilePicture($user_id, $newFileName);
            } else {
                // Handle profile picture upload failure
                echo "Failed to upload profile picture";
            }
        } else {
            // No new profile picture uploaded, use the current profile picture if it exists
            $user = new User();
            $currentProfilePicture = $user->getProfilePicture($user_id);
            if ($currentProfilePicture) {
                $newFileName = $currentProfilePicture;
            }
        }

        // Update other user details
        $user = new User();
        $result = $user->update_user($lastname, $firstname, $access, $newFileName, $user_id);

        if($result){
            // Display a JavaScript popup
            echo '<script>alert("User profile updated successfully");</script>';
            // Redirect to the profile page after the popup is closed
            
        } else {
            // Handle update failure
            echo "Failed to update user profile";
        }
    }
}





function deactivate_user(){
    $user = new User();
    $user_id = $_POST['userid']; 
    $result = $user->deactivate_user($user_id);
    if($result){
        header('location: ../index.php?page=settings&subpage=users&action=profile&id='.$user_id);
    }
}

function activate_user(){
    $user = new User();
    $user_id = $_POST['userid']; 
    $result = $user->activate_user($user_id);
    if($result){
        header('location: ../index.php?page=settings&subpage=users&action=profile&id='.$user_id);
    }
}
?>
