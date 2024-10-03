<?php include('server.php') ?>
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

<form action="index.php" method="POST"  style="display: flex; justify-content: flex-end">
<?php include('errors.php'); ?>
    <div class="inn">
      <input type="text" class="form-control" name="username" placeholder="Username" required>
    </div>
    <div class="inn">
      <input type="password" class="form-control" name="password" placeholder="Password" required>
    </div>

    <div class="pull-right">
    <button type="submit"   name="login_user" class="btn btn-primary">Sign in</button>
    </div>
  
  
 
</form>


   <img src="images/logouop.png" class="image">



   <form action="index.php">
      <label for="departments" class="info">
         <h3 class="info"> Επέλεξε το τμήμα που εισάχθηκες </h3>
      </label>
      <select class="custom-select" name="departments">
         <option value="ΠΤ">Πληροφορικής και Τηλεπικοινωνιών </option>
         <option value="ETT">Επιστήμης και Τεχνολογίας Τηλεπικοινωνιών </option>
         <option value="ETY">Επιστήμης και Τεχνολογίας Υπολογιστών </option>
      </select>
      <br><br>
      <label for="year" class="info">
         <h3 class="info"> Επέλεξε το έτος εισαγωγής σου</h3>
      </label>
      <select class="custom-select" name="year">
         <option value="2002">2002 </option>
         <option value="2003">2003 </option>
         <option value="2004">2004 </option>
         <option value="2005">2005 </option>
         <option value="2006">2006 </option>
         <option value="2007">2007 </option>
         <option value="2008">2008 </option>
         <option value="2009">2009 </option>
         <option value="2010">2010 </option>
         <option value="2011">2011 </option>
         <option value="2012">2012 </option>
         <option value="2013">Μετά το 2012 </option>

      </select>
      </select>
      <br><br>
      <input type="submit" class="btn btn-primary btn-lg btn-block" value="Submit">


   </form>
</body>

</html>