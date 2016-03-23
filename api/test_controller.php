<?php
include 'table_defs.php';
include 'model.php';

echo "Lising tables.....\n";

foreach($tables_list as $key => $value) {
	echo $key . "\n";
}

echo "\n";

$test_controller = new controller;

$test_controller->init("localhost", "root", "", "testDatabase");
$test_controller->toString();
if(!$test_controller->table_exists("test")){
	echo "Non existant table found not to exists. PASS.\n";
}
foreach($tables_list as $key => $value) {
	if(!$test_controller->table_exists($key)){
		echo "FAIL: $key does not exist.\n";
		break;
	}
}

echo "Getting all achievements earned by studentID 1.....\n";
$stu = $test_controller->get_earned_ach_student(14);
foreach($stu as $row){
	print $row['achievementID'] . "\t";
	print $row['name'] . "\t";
	print $row['short_desc'] . "\t";
    print $row['long_desc'] . "\t";
    print $row['points'] . "\t";
    print $row['creation_date'] . "\t";
    print $row['catagory'] . "\n";
}

echo "total points test...";
$stu = $test_controller->get_total_points_for_student(14);
echo $stu[0]['total']."\n";
?>
