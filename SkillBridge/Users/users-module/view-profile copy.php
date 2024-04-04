<?php 
$user = new User();
$profile_pic = $user->getProfilePicture($user_id);

// Check if a new profile picture has been uploaded
if(isset($_FILES['profile-pic']['name']) && !empty($_FILES['profile-pic']['name'])) {
    // If a new picture has been uploaded, use the uploaded picture
    $profile_pic = $_FILES['profile-pic']['name'];
    // Also, move the uploaded file to your desired directory
    move_uploaded_file($_FILES['profile-pic']['tmp_name'], 'css/profilepic/' . $profile_pic);
} else {
    // If no new picture has been uploaded, keep the current profile picture
    $profile_pic = $user->getProfilePicture($user_id);
}

?>

<h3>Profile</h3>
<div id="form-block">
    <form method="POST" action="processes/process.user.php?action=update" id="updateForm" enctype="multipart/form-data">

    <img id="profile-pic-preview" src="<?php 
    if ($profile_pic) {
        echo 'css/profilepic/' . $profile_pic . '?t=' . time(); // Add '?t=' . time() to the URL
    } else { 
        echo 'css/profilepic/default.jpg'; // Default profile picture path
    }
    ?>" alt="Profile Picture">
        <br>
        <label for="profile-pic">Upload New Profile Picture</label>
        <input type="file" id="profile-pic" name="profile-pic" accept="image/*">

    <br>

        <label for="fname">First Name</label>
        <input type="text" id="fname" class="input" name="firstname" disabled value="<?php echo $user->get_user_firstname($id);?>" placeholder="Your name..">

        <label for="lname">Last Name</label>
        <input type="text" id="lname" class="input" name="lastname" disabled value="<?php echo $user->get_user_lastname($id);?>" placeholder="Your last name..">

        <label for="access">Access Level</label>
        <select id="access" name="access" disabled>
            <option value="Applicant" <?php if($user->get_user_access($id) == "Applicant"){ echo "selected";};?>>Applicant</option>
            <option value="Employer" <?php if($user->get_user_access($id) == "Employer"){ echo "selected";};?>>Employer</option>
        </select>

        <label for="status">Account Status</label>
        <select id="status" name="status" disabled>
            <option <?php if($user->get_user_status($id) == "0"){ echo "selected";};?>>Deactivated</option>
            <option <?php if($user->get_user_status($id) == "1"){ echo "selected";};?>>Active</option>
        </select>
        <label for="email">Email</label>
        <input type="email" disabled id="email" class="input" name="email" value="<?php echo $user->get_user_email($id);?>" placeholder="Your email..">

        <input type="hidden" id="userid" name="userid" value="<?php echo $id;?>"/>
        <div id="button-block">


            <input type="submit" value="Update" onclick="confirmUpdate()">
        </div>
    </form>
    <?php
$account_status = $user->get_user_status($id);
$action_label = ($account_status == "1") ? "Deactivate Account" : "Activate Account";
$action = ($account_status == "1") ? "deactivate" : "activate";

// Debugging <--
// echo "Account Status: $account_status<br>";
// echo "Action Label: $action_label<br>";
// echo "Action: $action<br>";

?>
</div>
<script>
function confirmUpdate() {
    if (confirm("Are you sure you want to update your profile?")) {
        document.getElementById("updateForm").submit(); // Submit the form if confirmed
    } else {
        // Do nothing or provide feedback to the user
    }
}
</script>
