<?php

class Model {

    var $servername		= "localhost"; 
    var $username		= "root";
    var $password		= "";
    var $database_name 	= "";
    var $conn;
    var $connected = FALSE;

    public function init($server, $user, $p, $d){
	$this->servername    = $server;
	$this->username	     = $user;
	$this->password	     = $p;
	$this->database_name = $d;
	$result = $this->init_database($d);
	if($result){
	    $this->connected = $this->connect();
	}
	if($this->connected){
	    $this->init_tables();
	}
    }

    function test(){
	return "TEST STRING";
    }

    function init_database($dbname){
    	$conn;
    	try{
	    $conn = new PDO("mysql:host=$this->servername;port=3306;",$this->username,$this->password);
	    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	catch(Exception $e){
	    echo "Database init failed: " . $e->getMessage();
	    return FALSE; 
	}

    	$query = "CREATE DATABASE IF NOT EXISTS " . $dbname;
    	try { 
	    $conn->exec($query);
	    $conn = null;
	    return true; 
	} 
	catch(PDOException $e) {
	    echo $query . "<br>" . $e->getMessage();
	    $conn = null; 
	    return false;
	} 
    }

    public function toString(){
	echo $this->servername . "\n";
	echo $this->username . "\n";
	echo $this->password . "\n";
    }

    private function connect(){
	try{
	    $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->database_name",
		$this->username,
		$this->password);
	    $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	    return TRUE;
	}
	catch(Exception $e){
	    echo "Connection failed: " . $e->getMessage();
	    return FALSE; 
	}
    }
    
    public function entry_to_string($pdo, $entry){
    	$result = "";
    	$l = $pdo->fetchAll(PDO::FETCH_ASSOC);
    	while (list($key, $value) = each($l[$entry])) {
	   if($result != ""){
		$result = $result . " ,";
    	    }
    	    $result = $result .  "$key=$value";
	}
	return $result;
    }
    
    public function table_exists($table_name){
	if($this->connected != TRUE) return FALSE;
	if ($this->conn->query("SHOW TABLES LIKE '" . $table_name . "'")->rowCount() > 0){
	    return TRUE;
	}
	return FALSE;
    }
    
    private function init_tables(){
    	include('table_defs.php');
    	foreach($tables_list as $key => $value) {
	    if(!$this->table_exists($key)){
		$this->conn->exec($value);
    	    }
	}
    }
    
    //##########---STUDENT FUNCTIONS---################
    /*
    insert_student(first, last, email)
    
    Adds a new student record to the database
    @param $first (string) First name of student.
    @param $last (string) Last name of the student.
    @param $email (string) Email of student.
    @post If successfull a new student is added to the database.
    @return None.
    */
    public function insert_student($first, $last, $email){
	//if(!$this->connected) return;
	if($first == "" ||
	    $last == "" ||
	    $email == "" ||
	    !$this->connected){
	    return $this->result_message(FALSE, "Missing required parameters.");
	}
	$sql = " INSERT INTO students(firstName, lastName,email) VALUES ('$first','$last','$email')";
	try { 	
	    $this->conn->exec($sql);
	    return $this->result_message(TRUE, "Student added.");
	} 
	catch(PDOException $e) {
	    return $this->result_message(FALSE, "Failed to add student");
	} 
    }
    /*
    get_students()
    
    Returns all values from student table
    @return PDOStatement object containing all student records..
    */
    public function get_students(){
	$q = "SELECT * FROM students";
	if(!$this->connected) return;
	return json_encode($this->conn->query($q)->fetchAll(PDO::FETCH_ASSOC)); 
    }
	
    public function edit_student($id, $first, $last, $email){
	if(!$this->connected) return;
	if($id == "" ||
	    $first == "" ||
	    $last == "" ||
	    !$this->connected ||
	     email == ""){
	    return $this->result_message(FALSE, "Missing required parameters.");
	}
	$sql = "UPDATE students SET firstName=\"$first\", 
		lastName=\"$last\",
		email=\"$email\"
		WHERE studentID=$id";
	try { 	
	    $this->conn->exec($sql);
	    //return "{}";
	    return $this->result_message(TRUE, "Student edited.");
	} 
	catch(PDOException $e) {
	    return $this->result_message(FALSE, "Failed to edit student.");    
	}
    }
	
	/*
    get_student($stuID)
    
    Returns record of student
    @param $stuID (int) Unique id of student record.
    @return PDOStatement object of specified student.
    */
    public function get_student($stuID){
	//echo "GET STUDENT CALLED";
	$q = "SELECT * FROM students WHERE students.studentID=$stuID";
	if(!$this->connected) return;
	return json_encode($this->conn->query($q)->fetchAll(PDO::FETCH_ASSOC)); 
    }
	
    // ############# Achievements functions ###################
    /*
		
    */
    public function insert_achievement($name, $short, $long, $points, $cat){
	if($name == ""	    ||
	    $short == ""    ||
	    $long == ""	    ||
	    $points == ""   ||
	    $cat    == ""   ||
	    !$this->connected){
	    return $this->result_message(FALSE, "Could not complete operation");
	}

	$sql = " INSERT INTO achievements(name, short_desc,long_desc, points, catagory) 
	    VALUES ('$name', '$short', '$long', '$points', '$cat')";
	try { 	
	    $this->conn->exec($sql);
	    return $this->result_message(TRUE, "Achievement Added.");
	} 
	catch(PDOException $e) {
	    return $this->result_message(FALSE, "Failed to add achievement.");
	} 
    }
	
    public function edit_achievement($id, $name, $short, $long, $points, $cat){
	if($name == ""	    ||
	    $short == ""    ||
	    $long == ""	    ||
	    $points == ""   ||
	    $cat    == ""   ||
	    !$this->connected){
	    return $this->result_message(FALSE, "Could not complete operation");
	}
	$q = "UPDATE achievements SET name=\"$name\", 
			short_desc=\"$short\",
			long_desc=\"$long\", 
			points=$points,
			catagory=\"$cat\"
			WHERE achievementID=$id";
	try { 	
	    $this->conn->exec($q);
	    return $this->result_message(TRUE, "Achievement Edited.");
	} 
	catch(PDOException $e) {
	    return $this->result_message(FALSE, "Failed to edit acheivement.");
	} 
    }
	
    public function get_achievements(){
	$q = "SELECT * FROM achievements";
	if(!$this->connected) return;
	return json_encode($this->conn->query($q)->fetchAll(PDO::FETCH_ASSOC));
    }
	
    public function earn_achievement($achID, $stuID){
	if(!$this->connected) return;
	$q = "INSERT INTO achievements_earned(achievementID, studentID) VALUES($achID, $stuID)";
	try { 	
	    $this->conn->exec($q);
	    return $this->result_message(TRUE, "Successfully updated earned achievements.");
	} 
	catch(PDOException $e) {
	    return $this->result_message(FALSE, "Failed to update earned achievements.");
	} 
    }
	
    function get_earned_ach_student($stuID){
	$q = "SELECT * FROM achievements_earned LEFT JOIN achievements AS a1 
			ON achievements_earned.achievementID=a1.achievementID 
			LEFT JOIN students AS s 
			ON achievements_earned.studentID = s.studentID
			WHERE s.studentID=$stuID";
	if(!$this->connected) return;
	//echo(json_encode($this->conn->query($q)->fetchAll(PDO::FETCH_ASSOC)));
	return json_encode($this->conn->query($q)->fetchAll(PDO::FETCH_ASSOC));
    }
	
    function get_total_points_for_student($stuID){
	$q = "SELECT SUM(points) AS total FROM achievements_earned LEFT JOIN achievements AS a1 
		ON achievements_earned.achievementID=a1.achievementID 
		LEFT JOIN students AS s 
		ON achievements_earned.studentID = s.studentID
		WHERE s.studentID=$stuID";
	if(!$this->connected) return;
	//echo(json_encode($this->conn->query($q)->fetchAll(PDO::FETCH_ASSOC)));
	return json_encode($this->conn->query($q)->fetchAll(PDO::FETCH_ASSOC));
    }
	
    //#########---- DELETE ID FROM TABLE --- ############	
    function delete_ID_from_table($condition, $tablename){
	if(!$this->connected) return;
	$q = "DELETE FROM $tablename WHERE $condition";
	try { 	
	    $this->conn->exec($q);
	    return $this->result_message(TRUE, "Value successfully deleted from table");
	} 
	catch(PDOException $e) {
	    return $this->result_message(FALSE, "Delete operation failed.");
	} 
    }	
	
    //#########---- UPDATE ID FROM TABLE --- ############	
	
    function update_ID_from_table($ID, $tablename){
	if(!$this->connected) return;
	$q = "DELETE FROM $tablename WHERE $condition";
	try { 	
	    $this->conn->exec($q);
	    return $this->result_message(TRUE, "Update successfull."); 
	} 
	catch(PDOException $e) {
	    return $this->result_message(FALSE, "Update failed.");
	} 
    }
    //Return JSON formatted response  message
    function result_message($status, $message){
	$result = [ "result" => $status,
		    "message" => $message
		];
	return json_encode($result);
    }
}

?>
