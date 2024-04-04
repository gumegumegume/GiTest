<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
class User{
	private $DB_SERVER='172.16.0.214';
	private $DB_USERNAME='group48';
	private $DB_PASSWORD='123456';
	private $DB_DATABASE='group48';
	private $conn;
	public function __construct(){
		$this->conn = new PDO("mysql:host=".$this->DB_SERVER.";dbname=".$this->DB_DATABASE,$this->DB_USERNAME,$this->DB_PASSWORD);
		
	}
	
	function get_job_title($post_id) {
		$sql = "SELECT title FROM job_postings WHERE post_id = :post_id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':post_id', $post_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	
	function get_job_description($post_id) {
		$sql = "SELECT description FROM job_postings WHERE post_id = :post_id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':post_id', $post_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	
	function get_job_company($post_id) {
		$sql = "SELECT company FROM job_postings WHERE post_id = :post_id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':post_id', $post_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	
	function get_job_location($post_id) {
		$sql = "SELECT location FROM job_postings WHERE post_id = :post_id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':post_id', $post_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	
	function get_job_salary($post_id) {
		$sql = "SELECT salary FROM job_postings WHERE post_id = :post_id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':post_id', $post_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	function get_job_id($post_id) {
		$sql = "SELECT post_id FROM job_postings WHERE post_id = :post_id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':post_id', $post_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	

public function create_new_job_post($title, $description, $company, $location, $salary, $email) {
    if(isset($_SESSION['login']) && $_SESSION['login'] == true && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
		$created_at = date('Y-m-d H:i:s');
        $sql = "INSERT INTO job_postings (title, description, company, location, salary, user_id, user_email, created_at) VALUES (:title, :description, :company, :location, :salary, :user_id, :user_email, :created_at)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':company', $company);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':salary', $salary);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':user_email', $email);
        $stmt->bindParam(':created_at', $created_at);
        return $stmt->execute();
    } else {
        return false;
    }
}


	public function get_job_applicants($post_id) {
        // Prepare SQL statement to retrieve job applicants for a given job post ID
		$sql = "SELECT user_profilepic, user_firstname, user_lastname, user_email, resume FROM job_applicants WHERE post_id = :post_id";

        // Prepare and execute the SQL statement
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();

        // Fetch all job applicants as an associative array
        $applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if job applicants were found
        if($applicants){
            return $applicants;
        } else {
            return false; // Return false if no applicants found
        }
    }
	
	public function apply_for_job($post_id, $user_id, $user_firstname, $user_lastname, $user_email, $user_profilepic, $resume) {
		session_start();
		$user_id = $_SESSION['user_id'];
		$user_profilepic = $this->get_user_profilepic($user_id);
		$user_firstname = $this->get_user_firstname($user_id);
		$user_lastname = $this->get_user_lastname($user_id);
		$user_email = $_SESSION['user_email'];  
	
		// Check if the user has already applied for this job
		$sql_check = "SELECT * FROM job_applicants WHERE post_id = :post_id AND user_id = :user_id";
		$stmt_check = $this->conn->prepare($sql_check);
		$stmt_check->bindParam(':post_id', $post_id);
		$stmt_check->bindParam(':user_id', $user_id);
		$stmt_check->execute();
		$existing_application = $stmt_check->fetch(PDO::FETCH_ASSOC);
	
		if ($existing_application) {
			// Update existing application
			$sql = "UPDATE job_applicants SET user_firstname = :user_firstname, user_lastname = :user_lastname, user_email = :user_email, user_profilepic = :user_profilepic, resume = :resume WHERE post_id = :post_id AND user_id = :user_id";
		} else {
			// Insert new application
			$sql = "INSERT INTO job_applicants (post_id, user_id, user_firstname, user_lastname, user_email, user_profilepic, resume) VALUES (:post_id, :user_id, :user_firstname, :user_lastname, :user_email, :user_profilepic, :resume)";
		}
	
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':post_id', $post_id);
		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':user_firstname', $user_firstname);
		$stmt->bindParam(':user_lastname', $user_lastname);
		$stmt->bindParam(':user_email', $user_email);
		$stmt->bindParam(':user_profilepic', $user_profilepic);
		$stmt->bindParam(':resume', $resume);
		return $stmt->execute();
	}
	
    public function get_resume($user_id) {
        // Prepare SQL statement to retrieve the resume filename
        $sql = "SELECT resume FROM job_applicants WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        // Execute the SQL statement
        $stmt->execute();
        // Fetch the resume filename
        $resume = $stmt->fetchColumn();
        return $resume;
    }

	
	public function update_job_post($title, $description, $company, $location, $salary, $post_id) {
		$sql = "UPDATE job_postings SET title = :title, description = :description, company = :company, location = :location, salary = :salary WHERE post_id = :post_id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':description', $description);
		$stmt->bindParam(':company', $company);
		$stmt->bindParam(':location', $location);
		$stmt->bindParam(':salary', $salary);
		$stmt->bindParam(':post_id', $post_id);
		return $stmt->execute();
	}
	
	
	public function delete_job_post($post_id) {
		$sql = "DELETE FROM job_postings WHERE post_id = :post_id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':post_id', $post_id);
		return $stmt->execute();
	}

	public function new_user($email, $password, $lastname, $firstname, $address, $contactnumber, $access) {
		/* Setting Timezone for DB */
		$NOW = new DateTime('now', new DateTimeZone('Asia/Manila'));
		$NOW = $NOW->format('Y-m-d H:i:s');
	
		// Set the default profile picture filename
		$defaultProfilePic = 'default.png';
	
		$data = [
			[NULL, $lastname, $firstname, $email, $password, $NOW, $NOW, NULL, NULL, '1', $access, $contactnumber, $address, $defaultProfilePic],
		];
		$stmt = $this->conn->prepare("INSERT INTO tbl_users (user_id, user_lastname, user_firstname, user_email, user_password, user_date_added, user_time_added, user_date_updated, user_time_updated, user_status, user_access, user_contactNumber, user_Address, user_profilepic) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		try {
			$this->conn->beginTransaction();
			foreach ($data as $row) {
				$stmt->execute($row);
			}
			$this->conn->commit();
		} catch (Exception $e) {
			$this->conn->rollback();
			throw $e;
		}
	
		return true;
	}
	
	

	public function updateProfilePicture($user_id, $newFileName) {
		$sql = "UPDATE tbl_users SET user_profilepic = :user_profilepic WHERE user_id = :user_id";
		$stmt = $this->conn->prepare($sql);
		$result = $stmt->execute(array(
			':user_profilepic' => $newFileName,
			':user_id' => $user_id
		));
		return $result;
	}
	
	
	public function getProfilePicture($user_id) {
		$sql = "SELECT user_profilepic FROM tbl_users WHERE user_id = :user_id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':user_id', $user_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	

	public function update_user( $user_address, $user_contactnumber, $user_profilepic,$id){
		/* Setting Timezone for DB */
		$NOW = new DateTime('now', new DateTimeZone('Asia/Manila'));
		$NOW = $NOW->format('Y-m-d H:i:s');
	
		// Modify the SQL query to include the profile picture, address, and contact number fields
		$sql = "UPDATE tbl_users SET user_date_updated=:user_date_updated, user_time_updated=:user_time_updated, user_Address=:user_Address, user_contactNumber=:user_contactNumber, user_profilepic=:user_profilepic WHERE user_id=:user_id";
	
		$q = $this->conn->prepare($sql);
		
		// Bind parameters including the profile picture, address, and contact number
		$q->execute(array(
			':user_date_updated' => $NOW,
			':user_time_updated' => $NOW,
			':user_Address' => $user_address,
			':user_contactNumber' => $user_contactnumber,
			':user_profilepic' => $user_profilepic,
			':user_id' => $id
		));
	
		return true;
	}
	
	
	
    public function deactivate_user($user_id){
        // Update the user status to deactivated in the database
        $sql = "UPDATE tbl_users SET user_status = '0' WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
    public function activate_user($user_id) {
        // Update the user status to activated in the database
        $sql = "UPDATE tbl_users SET user_status = '1' WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
	public function list_users(){
		$sql="SELECT * FROM tbl_users";
		$q = $this->conn->query($sql) or die("failed!");
		while($r = $q->fetch(PDO::FETCH_ASSOC)){
		$data[]=$r;
		}
		if(empty($data)){
		   return false;
		}else{
			return $data;	
		}
}
public function search_user($searchQuery){
    $sql = "SELECT * FROM tbl_users WHERE user_firstname LIKE :search OR user_lastname LIKE :search OR user_email LIKE :search";
    
    $q = $this->conn->prepare($sql);
    
    // Bind search query parameter
    $searchParam = '%' . $searchQuery . '%';
    $q->bindParam(':search', $searchParam, PDO::PARAM_STR);
    
    $q->execute();
    
    $data = array();
    while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $r;
    }
    
    if (empty($data)) {
       return false;
    } else {
        return $data;    
    }
}

  function fetch_job_postings(){
    // SQL query to fetch job postings sorted by creation date in descending order
    $sql = "SELECT * FROM job_postings ORDER BY created_at DESC";
    
    // Prepare the query
    $stmt = $this->conn->prepare($sql);
    
    // Execute the query
    $stmt->execute();
    
    // Fetch all job postings as an associative array
    $jobPostings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Check if job postings were found
    if($jobPostings){
        return $jobPostings;
    } else {
        return false; // Return false if no job postings found
    }
}

	function get_user_id($email){
		$sql="SELECT user_id FROM tbl_users WHERE user_email = :email";	
		$q = $this->conn->prepare($sql);
		$q->execute(['email' => $email]);
		$user_id = $q->fetchColumn();
		return $user_id;
	}
	function get_user_profilepic($id) {
		$sql = "SELECT user_profilepic FROM tbl_users WHERE user_id = :id";
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$user_profilepic = $q->fetchColumn();
		return $user_profilepic;
	}
	
	function get_user_email($id){
		$sql="SELECT user_email FROM tbl_users WHERE user_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$user_email = $q->fetchColumn();
		return $user_email;
	}
	function get_user_address($id){
		$sql="SELECT user_address FROM tbl_users WHERE user_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$user_email = $q->fetchColumn();
		return $user_email;
	}
	function get_user_number($id){
		$sql="SELECT user_contactNumber FROM tbl_users WHERE user_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$user_email = $q->fetchColumn();
		return $user_email;
	}
	function get_user_firstname($id){
		$sql="SELECT user_firstname FROM tbl_users WHERE user_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$user_firstname = $q->fetchColumn();
		return $user_firstname;
	}
	function get_user_lastname($id){
		$sql="SELECT user_lastname FROM tbl_users WHERE user_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$user_lastname = $q->fetchColumn();
		return $user_lastname;
	}
	function get_user_access($id){
		$sql="SELECT user_access FROM tbl_users WHERE user_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$user_access = $q->fetchColumn();
		return $user_access;
	}
	function get_user_status($id){
		$sql="SELECT user_status FROM tbl_users WHERE user_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$user_status = $q->fetchColumn();
		return $user_status;
	}
	function get_session(){
		if(isset($_SESSION['login']) && $_SESSION['login'] == true){
			return true;
		}else{
			return false;
		}
	}
	public function check_login($email, $password){
		// Retrieve user data based on the provided email
		$sql = "SELECT user_id, user_password, user_status, user_firstname, user_lastname, user_access FROM tbl_users WHERE user_email = :email"; 
		$q = $this->conn->prepare($sql);
		$q->execute(['email' => $email]);
		$user = $q->fetch(PDO::FETCH_ASSOC);
	
		// Check if user exists and is active
		if($user && $user['user_status'] == 1){
			// Verify if the provided password matches the hashed password stored in the database
			if(password_verify($password, $user['user_password'])){
				// Password matches, set session variables and return true
				session_start();
				$_SESSION['login'] = true;
				$_SESSION['user_id'] = $user['user_id'];
				$_SESSION['user_email'] = $email;
				$_SESSION['user_firstname'] = $user['user_firstname'];
				$_SESSION['user_lastname'] = $user['user_lastname'];
				$_SESSION['user_access'] = $user['user_access'];
				return true;
			}
		}
		
		// Either user does not exist or password is incorrect, return false
		return false;
	}
	
	

	
public function delete_user($user_id){
    $sql = "DELETE FROM tbl_users WHERE user_id = :user_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    return $stmt->execute();
}
}