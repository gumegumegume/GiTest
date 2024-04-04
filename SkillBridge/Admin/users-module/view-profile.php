<h3>Profile</h3>
<div id="form-block">
    <form method="POST" action="processes/process.user.php?action=update">
        <label for="fname">First Name</label>
        <input type="text" id="fname" class="input" name="firstname" value="<?php echo $user->get_user_firstname($id);?>" placeholder="Your name..">

        <label for="lname">Last Name</label>
        <input type="text" id="lname" class="input" name="lastname" value="<?php echo $user->get_user_lastname($id);?>" placeholder="Your last name..">

        <label for="access">Access Level</label>
        <select id="access" name="access">
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
            <input type="submit" value="Update">
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
<form method="POST" action="processes/process.user.php?action=<?php echo $action; ?>">
    <input type="hidden" id="userid" name="userid" value="<?php echo $id; ?>"/>
    <div id="button-block">
        <input type="submit" value="<?php echo $action_label; ?>">
    </div>
</form>
<form method="POST" action="processes/process.user.php?action=delete">
    <input type="hidden" id="userid" name="userid" value="<?php echo $id; ?>"/>
    <div id="button-block">
        <input type="submit" value="Delete Account" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
    </div>
</form>
</div>