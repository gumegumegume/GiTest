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
}

function update_job_post(){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Retrieve job post details from form
        $job_post_id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $company = $_POST['company'];
        $location = $_POST['location'];
        $salary = $_POST['salary'];

        // Create a new instance of the User class
        $user = new User();

        // Call the method to update the job post
        $result = $user->update_job_post($title, $description, $company, $location, $salary, $job_post_id);

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
        $job_post_id = $_POST['id'];
        $result = $user->delete_job_post($job_post_id);
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

        // Create a new instance of the User class
        $job_post = new User();

        // Call the method to create a new job post
        $result = $job_post->create_new_job_post($title, $description, $company, $location, $salary);

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
    $user = new User();
    $user_id = $_POST['userid'];
    $lastname = ucwords($_POST['lastname']);
    $firstname = ucwords($_POST['firstname']);
    $access = ucwords($_POST['access']);
    $email = ucwords($_POST['email']);
    
    // Check if the new email is different from the current email
    $current_email = $user->get_user_email($user_id);
    if($email !== $current_email){
        // Check if the new email already exists in the database
        $existing_user_id = $user->get_user_id($email);
        if($existing_user_id){
            // Email already exists, show a popup indicating that the email is already taken
            echo '<script>alert("Email already exists. Please choose a different email.");';
            // Redirect after the user closes the alert
            $id = $user->get_user_id($email);
            echo 'window.location.href = "../index.php?page=settings&subpage=users&action=profile&id='.$id.'";</script>';
            return false;
        }
    }
    
    // If the email is not taken or unchanged, proceed with updating the user profile
    $result = $user->update_user($lastname,$firstname,$access,$email,$user_id);
    if($result){
        // User profile updated successfully, redirect to another page or display a success message
        header('location: ../index.php');
    } else {
        // Handle any other errors during profile update
        echo "Failed to update user profile";
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
