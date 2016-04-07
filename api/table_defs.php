<?php

	$create_student_table = "CREATE TABLE students(
		studentID int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		lastName varchar(255) NOT NULL,
		firstName varchar(255)NOT NULL,
        email varchar(255) NOT NULL,
        facebookID varchar(20),
		date TIMESTAMP
		)";
		
	$create_achievements_table = "CREATE TABLE achievements(
		achievementID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(255) NOT NULL,
		short_desc VARCHAR(255) NOT NULL,
		long_desc VARCHAR(400) NOT NULL,
		points int NOT NULL,
		classID int NOT NULL,
		date TIMESTAMP
		)";
		
	$create_achievements_earned_table = "CREATE TABLE achievements_earned(
		earnedID int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		achievementID int UNSIGNED NOT NULL,
        studentID int UNSIGNED NOT NULL,
        has_viewed BOOL DEFAULT FALSE,
		date TIMESTAMP
        )";
    
    $classes_def = "CREATE TABLE classes(
        classID int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        short_desc VARCHAR(255) NOT NULL,
        long_desc VARCHAR(400) NOT NULL,
        date TIMESTAMP)";

    $class_members_def = "CREATE TABLE class_members(
        memberID int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        classID int UNSIGNED NOT NULL,
        studentID int UNSIGNED NOT NULL,
        isMember BOOL DEFAULT FALSE,
        admin BOOL DEFAULT FALSE,
        date TIMESTAMP)";
	
	$tables_list = [
		"students" => $create_student_table,
		"achievements" => $create_achievements_table,
        "achievements_earned" => $create_achievements_earned_table,
        "classes" => $classes_def,
        "class_members" => $class_members_def
	];
	
?>
