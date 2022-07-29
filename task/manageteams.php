<?php
  include("connection.php");

  $con = mysqli_connect('localhost','root','');
   
  mysqli_select_db($con, 'task');
  $supervisor = $_SESSION['login_user']; 
  
  $sql = "SELECT * FROM team where supervisor='$supervisor'";
  $sql2 = mysqli_query($con, "SELECT * FROM team as t1 INNER JOIN supervisor as t2 ON t1.supervisor = t2.username WHERE username = '$supervisor'");

  $info = mysqli_query($con, $sql);

  $irow = mysqli_fetch_array($sql2);

  $new= "SELECT * from intern as tb1 inner join supervision as tb2
	on tb1.intern_id = tb2.intern_id where tb2.super_id='".$_SESSION['login_id']."'";
  $newmem = mysqli_query($con, $new) or die( mysqli_error($con));

  $team= "SELECT * from intern as tb1 inner join supervision as tb2
	on tb1.intern_id = tb2.intern_id where tb2.super_id='".$_SESSION['login_id']."'";
  $newteam = mysqli_query($con, $team) or die( mysqli_error($con));
  
  $que= "SELECT distinct username from intern as tab1 inner join grouping as tab2
	on tab1.team_id = tab2.team_id where tab1.team_id='".$irow['team_id']."'";
  $mem = mysqli_query($con, $que) or die( mysqli_error($con));
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<link rel="stylesheet" href="css/dashboard.css" type="text/css">
	<title>Manage User</title>
	
</head>
 <body>
      
	<div id="container">
	  
		<nav id="menu">
			<ul>
				<li class="menuitem"><a href="index.php"><img src = "pictures/iwomi-smaller.png" ></a></li>
				<li class="menuitem"><a href="about.php">About Us</a></li>
				<li class="menuitem"><a href="contact.php">Contact Us</a></li>
				<li>
					<ul>
						<li class ="signup"><a href = "index.php"><button class = "button buttongreen">Sign out</button></a>
						</li>
					</ul>		
				</li>
				
			</ul>
	    </nav>
		  
		 <aside>
			<nav id="leftmenu">
				<a href = "dashboard.php"><h3>Dashboard</h3></a>
				<ul>
					<a href="manageUser.php"><li>Manage User</li></a>
					<a href="manageteams.php"><li>Teams</li></a>
					<a href="view.php"><li>Manage Task</li></a>
				</ul>
			</nav>
		</aside>

		<section>
			<table align=center>
				<tr align= 'center'>
					<thead>
						<th>Team</th>
						<th>Capacity</th>
						<th>Supervisor</th>
						<th>Option</th>
					</thead>
				</tr>
				<?php
					while($row= mysqli_fetch_array($info))
					{
					echo "<tr>";
					echo "<td>".$row['teamName']."</td>";
					echo "<td>".$row['capacity']."</td>";
					echo "<td>".$row['supervisor']."</td>";
					echo "<td><div class='button'>View</div>	<div class='delete'>Remove</div></td>";
					echo "</tr>";
					}
				?>
			</table>
		</section>

		<div class="content">
			<div class="cards">
				<div class="card" id="card">
					<div class="box">
						<h3>Team:</h3>
						<h2><?php echo $irow['teamName'] ?></h2>
						<br>
						<h3>Capacity:</h3><h2><?php echo $irow['capacity'] ?></h2>
						<br>
						<img src="pictures/user.png" alt="#" width="80px" height="80px">
						<h1><?php echo $supervisor ?></h1>
					</div>
					<div class="icon">
						<h1>Members:</h1>
						<ul>
						<?php
							while($row=mysqli_fetch_array($mem)){
								echo"<li class='member'>".$row['username']."</li>";
							}
						?>
						</ul>
						<br>
						<br>
						<div class="add" method="POST" name="member" onclick="addmem(this)">Add Member</div>
						<br><br><br>
						<select id="newmem" onchange='hideme(this.value)' hidden="hidden">
							<option selected disabled hidden></option>
						<?php
							while($mrow=mysqli_fetch_array($newmem)){
								echo"<option value='".$mrow['username']."' >".$mrow['username']."</option>";
							}
							/* $cache = null;
							if(isset($_POST['member'])){
								$cache = $_POST['member'];
							}
							switch($cache){
								case $_POST['member']: echo "<script>alert('".$_POST['member']."')</script>"; break;
								default: echo "<script>alert('Select value is null')</script>"; break;
							} */
						?>
						</select>
					</div>
				</div>				
			</div>
		</div>
		<script type="text/javascript">
			function addmem(butt){
				let mem = document.getElementById("newmem");
				if(butt){
					mem.removeAttribute("hidden");
					console.log(mem);
				}
			}
			function hideme(opt){
				let hmem = document.getElementById("newmem");
				if(opt){
					hmem.setAttribute("hidden", "hidden");
					console.log(opt);

					//location.reload();
					//alert ("Will reload");
				}
			}
		</script>
		<div id="newtm" onclick="appear(this)">
			<p >New Team</p>
		</div>
		<div id="fltab" > 
			<form method="post" action="pcreateteam.php">
				<h2>Create a Team</h2>
				<p>
					<label><h1>Team Name:</h1> <input type="text" name="nteam" maxlength="20" required/>
				</p>
				<p>
					<label><h1>Members:</h1> 
						<ul>
						<?php
							$t = 0;
							while($fetch=mysqli_fetch_array($newteam)){
								echo "<li class='member'><input type='checkbox' name='member".$t."' value='".$fetch['intern_id']."' maxlength='20' />&nbsp;".$fetch['username']."</li>";
								$t++;
								// echo "<script>alert('The loop is suppose to have loaded!')</script>";	//Use this to check if the array values have laoded
								// echo "<script>alert('".$fetch['username']."');</script>";	// Use this to check is the values loaded
							}
						?>
						</ul>
				</p>
				<br><br>
				<input type="submit" value="Create Team" name="newteam" onclick="disappear(this)"/>
				<input type= "reset"/>
			</form>
		</div>
		<script type="text/javascript">
			function appear(ev){
				let box = document.getElementById("fltab");
				if(ev){
					box.removeAttribute("hidden");
					box.style.transition = "all 5s"
					box.style.display= "initial";
				}
			}
			function disappear(ev){
				let box = document.getElementById("fltab");
				if(ev){
					box.style.display = "none"
				}
			}
		</script>
		
	</div><!--container end -->
	<div style="clear;both"></div>
</body>

<!--<h1>Welcome  <?php //echo $login_session; ?></h1>-->
</html>
?>