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
	
	function get_job_title($job_post_id) {
		$sql = "SELECT title FROM job_postings WHERE id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $job_post_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	
	function get_job_description($job_post_id) {
		$sql = "SELECT description FROM job_postings WHERE id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $job_post_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	
	function get_job_company($job_post_id) {
		$sql = "SELECT company FROM job_postings WHERE id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $job_post_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	
	function get_job_location($job_post_id) {
		$sql = "SELECT location FROM job_postings WHERE id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $job_post_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	
	function get_job_salary($job_post_id) {
		$sql = "SELECT salary FROM job_postings WHERE id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $job_post_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	function get_job_id($job_post_id) {
		$sql = "SELECT id FROM job_postings WHERE id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $job_post_id);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	
	
	public function create_new_job_post($title, $description, $company, $location, $salary) {
		$sql = "INSERT INTO job_postings (title, description, company, location, salary) VALUES (:title, :description, :company, :location, :salary)";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':description', $description);
		$stmt->bindParam(':company', $company);
		$stmt->bindParam(':location', $location);
		$stmt->bindParam(':salary', $salary);
		return $stmt->execute();
	}
	
	public function update_job_post($title, $description, $company, $location, $salary, $job_post_id) {
		$sql = "UPDATE job_postings SET title = :title, description = :description, company = :company, location = :location, salary = :salary WHERE id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':description', $description);
		$stmt->bindParam(':company', $company);
		$stmt->bindParam(':location', $location);
		$stmt->bindParam(':salary', $salary);
		$stmt->bindParam(':id', $job_post_id);
		return $stmt->execute();
	}
	
	
	public function delete_job_post($job_post_id) {
		$sql = "DELETE FROM job_postings WHERE id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $job_post_id);
		return $stmt->execute();
	}

	public function new_user($email,$password,$lastname,$firstname, $access){
		
		/* Setting Timezone for DB */
		$NOW = new DateTime('now', new DateTimeZone('Asia/Manila'));
		$NOW = $NOW->format('Y-m-d H:i:s');

		$data = [
			[$lastname,$firstname,$email,$password,$NOW,$NOW,'1', $access],
		];
		$stmt = $this->conn->prepare("INSERT INTO tbl_users (user_lastname, user_firstname, user_email, user_password, user_date_added, user_time_added, user_status, user_access) VALUES (?,?,?,?,?,?,?,?)");
		try {
			$this->conn->beginTransaction();
			foreach ($data as $row)
			{
				$stmt->execute($row);
			}
			$this->conn->commit();
		}catch (Exception $e){
			$this->conn->rollback();
			throw $e;
		}

		return true;

	}

	public function update_user($lastname,$firstname, $access, $email, $id){
		
		/* Setting Timezone for DB */
		$NOW = new DateTime('now', new DateTimeZone('Asia/Manila'));
		$NOW = $NOW->format('Y-m-d H:i:s');

		$sql = "UPDATE tbl_users SET user_firstname=:user_firstname,user_lastname=:user_lastname,user_date_updated=:user_date_updated,user_time_updated=:user_time_updated,user_access=:user_access,user_email=:user_email WHERE user_id=:user_id";

		$q = $this->conn->prepare($sql);
		$q->execute(array(':user_firstname'=>$firstname, ':user_lastname'=>$lastname,':user_date_updated'=>$NOW,':user_time_updated'=>$NOW,':user_access'=>$access,':user_email'=>$email,':user_id'=>$id));
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
	function get_user_email($id){
		$sql="SELECT user_email FROM tbl_users WHERE user_id = :id";	
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
    $sql = "SELECT user_id, user_status FROM tbl_users WHERE user_email = :email AND user_password = :password"; 
    $q = $this->conn->prepare($sql);
    $q->execute(['email' => $email, 'password' => $password]);
    $user = $q->fetch(PDO::FETCH_ASSOC);

    if($user && $user['user_status'] == 1){
        $_SESSION['login'] = true;
        $_SESSION['user_email'] = $email;
        return true;
    } else {
        return false;
    }
}
public function delete_user($user_id){
    $sql = "DELETE FROM tbl_users WHERE user_id = :user_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    return $stmt->execute();
}
}