<h3>Create a New User</h3>
<div id="form-block">
    <form method="POST" action="processes/process.user.php?action=new">
            <label for="fname">First Name</label>
            <input type="text" id="fname" class="input" name="firstname" placeholder="Please Enter Your First Name">

            <label for="lname">Last Name</label>
            <input type="text" id="lname" class="input" name="lastname" placeholder="Please Enter Your Last Name">
            <label for="access">Select Role</label>
            <select id="access" name="access">
              <option value="Applicant">Applicant</option>
              <option value="Employer">Employer</option>
            </select>

            <label for="email">Email</label>
            <input type="email" id="email" class="input" name="email" placeholder="Your email..">

            <label for="address">Address</label>
            <input type="address"  id="address" class="input" name="address" placeholder="Your Address">
        
            <label for="contactnumber">Contact Number</label>
            <input type="tel"  id="contactnumber" class="input" name="contactnumber"  minlength="11" maxlength="11"  placeholder="Your Contact Number">



            <label for="password">Password</label>
            <input type="password" id="password" class="input" name="password" placeholder="Enter password..">

            <label for="confirmpassword">Confirm Password</label>
            <input type="password" id="confirmpassword" class="input" name="confirmpassword" placeholder="Confirm password..">
            
        <div id="button-block">
        <input type="submit" value="Save">
        </div>
  </form>
</div>