<?php
include("manageteams.php");

	$tname = $_POST['nteam'];
	$tmem = [];
	for($i=0;$i<$t;$i++){
		if(isset($_POST['member'.$i.''])){
		$tmem[$i] = $_POST['member'.$i.''];}
		else{$tmem[$i] = 0;}
	}
	
	$sql1 = "SELECT team_id from team where teamname='$tname'";
	$que = mysqli_query($con, $sql1) or die(mysqli_error($con));
	$row = mysqli_fetch_array($que);
	$count = count($row);

	if($count>0){
		echo "<script>alert('Team name Already existing');</script>";
	}
    $sql = $sql = "INSERT into team (teamName, capacity, supervisor)
		values ('$tname', '$t', '$supervisor') ";
	mysqli_query($con, $sql) or die(mysqli_error($con));	
	
	$que2 = mysqli_query($con, $sql1) or die(mysqli_error($con));
	$row2 = mysqli_fetch_array($que2);
	$teamId = $row[0];
	
	for($j=0;$j<$t;$j++){
		if($tmem[$j] != 0){
		$sql2 = "INSERT into grouping (team_id, intern_id)
			values ('$teamId', '$tmem[$j]')" ;
		mysqli_query($con, $sql2);}
		else{continue;}
	}
	echo "<script>alert('New team created');window.location.href = 'manageteams.php';</script>";
		

	
	mysqli_close($con);
?>
	