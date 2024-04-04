<h3>Create a New User</h3>
<div id="form-block">
    <form method="POST" action="processes/process.user.php?action=new">
            <label for="fname">First Name</label>
            <input type="text" id="fname" class="input" name="firstname" placeholder="Your name..">

            <label for="lname">Last Name</label>
            <input type="text" id="lname" class="input" name="lastname" placeholder="Your last name..">

            <label for="access">Select Role</label>
            <select id="access" name="access">
              <option value="Applicant">Applicant</option>
              <option value="Employer">Employer</option>
            </select>

            <label for="email">Email</label>
            <input type="email" id="email" class="input" name="email" placeholder="Your email..">

            <label for="password">Password</label>
            <input type="password" id="password" class="input" name="password" placeholder="Enter password.." minlength="8">

            <label for="confirmpassword">Confirm Password</label>
            <input type="password" id="confirmpassword" class="input" name="confirmpassword" minlength="8" placeholder="Confirm password..">
            
        <div id="button-block">
        <input type="submit" value="Save">
        </div>
  </form>
</div>