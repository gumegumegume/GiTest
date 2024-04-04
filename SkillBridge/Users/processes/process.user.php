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
    case 'login':
        login_user();
    break;
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


function login_user() {
    $user = new User();
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user_data = $user->get_user_id($email);


    if ($user_data) {
        // Verify if the provided password matches the hashed password stored in the database
        if (password_verify($password, $user_data['user_password'])) {
            // Password matches, proceed with login
            // You may set user session variables here and redirect to the appropriate page
            session_start();
            $_SESSION['user_id'] = $user_data['user_id'];
            $_SESSION['user_email'] = $user_data['user_email'];
            $_SESSION['user_firstname'] = $user_data['user_firstname'];
            $_SESSION['user_lastname'] = $user_data['user_lastname'];
            $_SESSION['user_access'] = $user_data['user_access'];
            // Redirect the user to the dashboard or any other page
            echo '<script>window.location.href = "../index.php";</script>';
        } else {
            // Password does not match, display an error message
            echo "Invalid email or password.";
        }
    } else {
        // User not found, display an error message
        echo "Invalid email or password.";
    }
}


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

function fetch_job_postings(){
    // Create a new instance of the User class
    $user = new User();
    
    // Call the method to fetch job postings
    $jobPostings = $user->fetch_job_postings();

    // Check if job postings were fetched successfully
    if($jobPostings){
        // Check if sorting parameter is set
        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
            var_dump($sort); 
            switch ($sort) {
                case 'date':
                    usort($jobPostings, 'sortByDate');
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
    $address = ucwords($_POST['address']);
    $contactnumber = $_POST['contactnumber'];
    $access = ucwords($_POST['access']);
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

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
    // Setting Timezone for DB
    $NOW = new DateTime('now', new DateTimeZone('Asia/Manila'));
    $NOW = $NOW->format('Y-m-d H:i:s');

    $result = $user->new_user($email, $hashedPassword, $lastname, $firstname, $address, $contactnumber, $access, $NOW, $NOW);
    
    if($result){
       
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
        $user_address = $_POST['address']; // Get the address from the form
        $user_contactnumber = $_POST['contactnumber']; // Get the contact number from the form
        $newFileName = ''; // Define $user_profilepic initially as an empty string
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
        // Update user details including the profile picture, address, and contact number
        $user = new User();
        $result = $user->update_user($user_address,$user_contactnumber, $newFileName,$user_id);

        // Handle update result
        if($result){
            // Display a JavaScript popup
            echo '<script>alert("User profile updated successfully");</script>';
            // Redirect to the profile page after the popup is closed
            echo '<script>window.location.href = "../index.php?page=profile&id=' . $user_id . '";</script>';
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
