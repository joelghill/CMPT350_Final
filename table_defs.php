<?php

	$create_student_table = "CREATE TABLE students(
		studentID int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		lastName varchar(255) NOT NULL,
		firstName varchar(255)NOT NULL,
		email varchar(255) NOT NULL,
		reg_date TIMESTAMP
		)";
		
	$create_achievements_table = "CREATE TABLE achievements(
		achievementID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(255) NOT NULL,
		short_desc VARCHAR(255) NOT NULL,
		long_desc VARCHAR(400) NOT NULL,
		points int NOT NULL,
		catagory varchar(255) NOT NULL,
		creation_date TIMESTAMP
		)";
		
	$create_achievements_earned_table = "CREATE TABLE achievements_earned(
		earnedID int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		achievementID int UNSIGNED NOT NULL,
		studentID int UNSIGNED NOT NULL,
		acheived_date TIMESTAMP
		)";
	
	$tables_list = [
		"students" => $create_student_table,
		"achievements" => $create_achievements_table,
		"achievements_earned" => $create_achievements_earned_table
	];
	
?>
