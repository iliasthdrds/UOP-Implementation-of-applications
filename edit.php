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
   <style>
       button{
           float: right;
           
       }
       a{
           float: left;
       }
    </style>
   <link rel="stylesheet" href="css_files/index.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
   <meta charset="utf-8">
   
</head>
<body>


<?php


$tmp_id= $_SESSION['editid'];

$sql = "";
$sql1 = "";
$table="";
if ($_SESSION['department'] == 'ΠΤ') {
   
  $sql = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών` WHERE id='$tmp_id'";
  $sql1 = "SELECT DISTINCT category FROM `πληροφορική και  τηλεπικοινωνιών`";
  $table="`πληροφορική και  τηλεπικοινωνιών`";
  
} elseif ($_SESSION['department'] == 'ETY') {
    

  if ($_SESSION['year'] == '2002' || $_SESSION['year'] == '2003') {
    
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003`  WHERE id='$tmp_id'";
    $sql1 = "SELECT DISTINCT category FROM  `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003`";
    $table="`επιστήμης και τεχνολογίας _υπολογιστών_2002_2003`";


  } elseif ($_SESSION['year'] == '2004' || $_SESSION['year'] == '2005' || $_SESSION['year'] == '2006') {
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2004__2005__2006`  WHERE id='$tmp_id'";
    $sql1 = "SELECT DISTINCT category FROM  `επιστήμης και τεχνολογίας _υπολογιστών_2004__2005__2006` ";
    $table="`επιστήμης και τεχνολογίας _υπολογιστών_2004__2005__2006`";


  } elseif ($_SESSION['year'] == '2007' || $_SESSION['year'] == '2008' || $_SESSION['year'] == '2009' || $_SESSION['year'] == '2010' || $_SESSION['year'] == '2011') {
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2007_2011`  WHERE id='$tmp_id'";
    $sql1 = "SELECT DISTINCT category FROM  `επιστήμης και τεχνολογίας _υπολογιστών_2007_2011`";
    $table="`επιστήμης και τεχνολογίας _υπολογιστών_2007_2011`";
  } else { //Μετά το 2012
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2012`  WHERE id='$tmp_id'";
    $sql1 = "SELECT DISTINCT category FROM  `επιστήμης και τεχνολογίας _υπολογιστών_2012`";
    $table="`επιστήμης και τεχνολογίας _υπολογιστών_2012`";
  }
} else {
  if ($_SESSION['year'] < 2009) {
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009`  WHERE id='$tmp_id'";
    $sql1 = "SELECT DISTINCT category FROM  `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009`";
    $table="`επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009`";
    
  }
  if ($_SESSION['year'] == '2009' || $_SESSION['year'] == '2010') {
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009_2010`  WHERE id='$tmp_id'";
    $sql1 = "SELECT DISTINCT category FROM  `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009_2010`";
    $table="`επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009_2010`";

  }
  if ($_SESSION['year'] == '2011') {
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2011`  WHERE id='$tmp_id'";
    $sql1 = "SELECT DISTINCT category FROM  `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2011`";
    $table="`επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2011`";
  }
  if ($_SESSION['year'] == '2012') {
    $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2012`  WHERE id='$tmp_id'";
    $sql1 = "SELECT DISTINCT category FROM  `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2012`";
    $table="`επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2012`";
  }
}



$result = mysqli_query($db, $sql);
$categorys= mysqli_query($db, $sql1);

$courses = mysqli_fetch_assoc($result);
?>

<form action="edit.php" method="POST">
<div class="form-group">
    <label for="exampleFormControlInput1" ></label>
    <input type="hidden"  class="form-control" id="table" name="table" value='<?php echo $table; ?>'>
    </div>
    





    <?php if ($tmp_id>-1) : ?>
  <div class="form-group">
    <label for="exampleFormControlInput1" >ID</label>
    <input type="id" class="form-control" id="id" name="id" value=<?php echo $courses["id"]; ?> readonly>
  </div>
  <?php endif ?>

  <div class="form-group">
    <label for="exampleFormControlInput1" >Name</label>
    <input type="id" class="form-control" id="name"  name="name" value=<?php echo $courses["name"]; ?> >
  </div>



  <div class="form-group">
    <label for="exampleFormControlSelect1">Category</label>
    <select  class="form-control" id="category" name="category">
    <?php while ($selections = mysqli_fetch_assoc($categorys)) : ?>
    <option value="<?php echo $selections["category"]; ?>"  >   <?php echo $selections["category"]; ?>    </option>
    <?php endwhile ?>
    </select>
  </div>



  <div class="form-group">
    <label for="exampleFormControlInput1" >Ects</label>
    <input type="id" class="form-control" name="ects" id="ects" value=<?php echo $courses["ects"]; ?>>
  </div>

  <div class="form-group">
    <label for="exampleFormControlInput1" >Correspondence</label>
    <input type="id" class="form-control" name="correspondence" id="correspondence" value='<?php echo $courses["correspondence"]; ?>'>
  </div>

  <button class="btn btn-primary "  name="edit_course">Submit</button>
  
    <a href="index_user.php"> <button type="button" class="btn btn-danger">Cancel </button></a>
  
</form>
</body>