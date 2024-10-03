<?php include('server.php') ?>
<?php 
  if (!isset($_SESSION['username']) || $_SESSION['username'] == ''){
    header('location:index.php'); 
  }
  else{
   echo "Welcome ";
   echo $_SESSION['username'];

  } 
?>


<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title></title>
   <link rel="stylesheet" href="css_files/index.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
   <meta charset="utf-8">
   
</head>

<body>

<?php



$sql = "";


if ($_SESSION['department'] == 'ΠΤ') {
   
  $sql = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών`;";
  $sql2 = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών` WHERE category  LIKE '%ΒΚ%'";
  $sql3 = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών` WHERE category  LIKE '%ΕΚ%'";
  $sql4 = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών` WHERE category  LIKE '%ΕΕ%' OR category LIKE '%ΠΔ%'";
  
} elseif ($_SESSION['department'] == 'ETY') {
    

  if ($_SESSION['year'] == '2002' || $_SESSION['year'] == '2003') {
    
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003`";


  } elseif ($_SESSION['year'] == '2004' || $_SESSION['year'] == '2005' || $_SESSION['year'] == '2006') {
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2004__2005__2006`;";
   


  } elseif ($_SESSION['year'] == '2007' || $_SESSION['year'] == '2008' || $_SESSION['year'] == '2009' || $_SESSION['year'] == '2010' || $_SESSION['year'] == '2011') {
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2007_2011`;";
    
  } else { //Μετά το 2012
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2012`;";
   
  }
} else {
  if ($_SESSION['year'] < 2009) {
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009`;";
   
  }
  if ($_SESSION['year'] == '2009' || $_SESSION['year'] == '2010') {
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009_2010`;";
   
  }
  if ($_SESSION['year'] == '2011') {
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2011`;";
   
  }
  if ($_SESSION['year'] == '2012') {
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2012`;";
   
  }
}



$result = mysqli_query($db, $sql);
?>
<br>

<a href="index_user.php"> <button type="button" class="btn btn-primary btn-sm">Back to selection </button></a>
<br>
<br>
<br>
<h1 style="text-align: center;"> Τμήμα: <?php echo $_SESSION['department'] ?> </h1>
<h1 style="text-align: center;"> Έτος: <?php echo $_SESSION['year']?> </h1>
<a href="display.php?new"> <button type="button" class="btn btn-warning btn-sm">Add Course </button></a>
<table id="editableTable" class="table table-bordered">
	<thead>
		<tr>
			<th>Id</th>
			<th>Name</th>
			<th>Category</th>
            <th>Ects</th>
			<th>Correspondence</th>
            <th>Edit</th>
            <th>Delete</th>
            											
		</tr>
	</thead>
	<tbody>
		<?php while( $courses = mysqli_fetch_assoc($result) ) { ?>
		   <td><?php echo $courses ['id']; ?></td>
		   <td><?php echo $courses ['name']; ?></td>
		   <td><?php echo $courses ['category']; ?></td>
		   <td><?php echo $courses ['ects']; ?></td>
		   <td><?php echo $courses ['correspondence']; ?></td>
           <td><a  href="display.php?edit=<?php echo $courses ['id']; ?>"><button type="button" class="btn btn-outline-primary" >Edit</button></a></td>          
           <td><a  href="display.php?delete=<?php echo $courses ['id']; ?>"><button type="button" class="btn btn-outline-danger">Delete</button></a></td>

            											  				   				   				  
		   </tr>
		<?php } ?>
	</tbody>
</table>



</body>
</html>
