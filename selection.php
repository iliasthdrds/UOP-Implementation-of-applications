<?php include('server.php') ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Επιστήμης και Τεχνολογίας Τηλεπικοινωνιών</title>
  <link rel="stylesheet" href="css_files/selection.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <meta charset="utf-8">
</head>


<body>

  <img src="images/logouop.png" class="image">
  <?php include('errors.php') ?>
  <h1>Επέλεξε τα μαθήματα που έχετε προβιβάσιμο βαθμό:</h1>
  <br>

  <?php



  $sql = "";
  

  if ($_SESSION['departments'] == 'ΠΤ') {
    $sql = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών`;";
    $sql2 = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών` WHERE category  LIKE '%ΒΚ%'";
    $sql3 = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών` WHERE category  LIKE '%ΕΚ%'";
    $sql4 = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών` WHERE category  LIKE '%ΕΕ%' OR category LIKE '%ΠΔ%'";
    
  } elseif ($_SESSION['departments'] == 'ETY') {

    if ($_SESSION['year'] == '2002' || $_SESSION['year'] == '2003') {
      
      $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003`";
      $sql2 = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003` WHERE category  LIKE '%ΒΚ%'";
      $sql3 = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003` WHERE category  LIKE '%ΕΠ%'";
      $sql4 = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003` WHERE category  LIKE '%ΕΛ-Π%' OR category LIKE '%ΕΛ%'";

    } elseif ($_SESSION['year'] == '2004' || $_SESSION['year'] == '2005' || $_SESSION['year'] == '2006') {
      $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2004__2005__2006`;";
      $sql2 = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2004__2005__2006` WHERE category  LIKE '%ΒΚ%'";
      $sql3 = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2004__2005__2006` WHERE category  LIKE '%ΕΠ%'";
      $sql4 = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2004__2005__2006` WHERE category  LIKE '%ΕΛ-Π%' OR category LIKE '%ΕΛ%'";


    } elseif ($_SESSION['year'] == '2007' || $_SESSION['year'] == '2008' || $_SESSION['year'] == '2009' || $_SESSION['year'] == '2010' || $_SESSION['year'] == '2011') {
      $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2007_2011`;";
      $sql2 = "SELECT * FROM`επιστήμης και τεχνολογίας _υπολογιστών_2007_2011` WHERE category  LIKE '%ΒΚ%'";
      $sql3 = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2007_2011` WHERE category  LIKE '%ΕΠ%'";
      $sql4 = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2007_2011` WHERE category  LIKE '%ΕΛ-Π%' OR category LIKE '%ΕΛ%'";
    } else { //Μετά το 2012
      $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2012`;";
      $sql2 = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2012` WHERE category  LIKE '%ΒΚ%'";
      $sql3 = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2012`WHERE category  LIKE '%ΕΠ%'";
      $sql4 = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2012` WHERE category  LIKE '%ΕΛ-Π%' OR category LIKE '%ΕΛ%'";
    }
  } else {
    if ($_SESSION['year'] < 2009) {
      $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009`;";
      $sql2 = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009` WHERE category  LIKE '%ΒΚ%'";
      $sql3 = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009` WHERE category  LIKE '%ΕΚ%'";
      $sql4 = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009` WHERE category  LIKE '%ΕΕ%' OR category LIKE '%ΕΕ-Π%'";
    }
    if ($_SESSION['year'] == '2009' || $_SESSION['year'] == '2010') {
      $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009_2010`;";
      $sql2 = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009_2010` WHERE category  LIKE '%ΒΚ%'";
      $sql3 = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009_2010` WHERE category  LIKE '%ΕΚ%'";
      $sql4 = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009_2010` WHERE category  LIKE '%ΕΕ%' OR category LIKE '%ΕΕ-Π%'";
    }
    if ($_SESSION['year'] == '2011') {
      $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2011`;";
      $sql2 = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2011` WHERE category  LIKE '%ΒΚ%'";
      $sql3 = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2011` WHERE category  LIKE '%ΕΚ%'";
      $sql4 = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2011` WHERE category  LIKE '%ΕΕ%' OR category LIKE '%ΕΕ-Π%'";
    }
    if ($_SESSION['year'] == '2012') {
      $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2012`;";
      $sql2 = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2012` WHERE category  LIKE '%ΒΚ%'";
      $sql3 = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2012` WHERE category  LIKE '%ΕΚ%'";
      $sql4 = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2012` WHERE category  LIKE '%ΕΕ%' OR category LIKE '%ΕΕ-Π%'";
    }
  }

  $result = mysqli_query($db, $sql);
  $result2 = mysqli_query($db, $sql2);
  $result3 = mysqli_query($db, $sql3);
  $result4 = mysqli_query($db, $sql4);



  ?>

  <form action="/selection.php">
    <?php if (mysqli_num_rows($result) > 0) : ?>
      <div class="d-flex p-2">
        <button class="btn btn-secondary dropdown-toggle btn btn-primary btn-lg btn-block" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Κορμού </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenu2" style="overflow-y: scroll; height:400px; width:100%;">
          <?php while ($courses = mysqli_fetch_assoc($result)) : ?>
            <?php if ( $courses["category"]=='Κ' ||  $courses["category"]=='Κ1' || $courses["category"]=='Κ2') : ?>

            <div class="courses  ">
              <input type="checkbox" id="course" name="course[]" value=<?php echo $courses["id"]; ?>>
              <label for=""> <?php echo  $courses["name"]; ?> </label><br>
            </div>
            <hr class="mt-5">

            <br>
            <br>
            <?php endif ?>
          <?php endwhile ?>
    

        </div>
      </div>

      <?php if ( $_SESSION['departments'] == 'ΠΤ' || $_SESSION['departments'] == 'ETY') : ?>
      <div class="d-flex p-2">
        <button class="btn btn-secondary dropdown-toggle btn btn-primary btn-lg btn-block" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Βασικά Κατευθύνσης </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenu2" style="overflow-y: scroll; height:400px; width:100%;">
          <?php while ($courses = mysqli_fetch_assoc($result2)) : ?>
            <?php if ( $courses["category"]=='ΒΚ-Π' || $courses["category"]=='ΒΚ-ΘΠ' || $courses["category"]=='ΒΚ-ΣΛ' || $courses["category"]=='ΒΚ-ΤΥ' || $courses["category"]=='ΒΚ-Τ' ) : ?>
            <div class="courses  ">
              <input type="checkbox" id="course" name="course[]" value=<?php echo $courses["id"];?>>
              <label for=""> <?php echo  $courses["name"]; ?> (<?php echo  $courses["category"]; ?>) </label><br>
            </div>
            <hr class="mt-5">

            <br>
            <br>

            <?php endif ?>
          <?php endwhile ?>
          
        </div>
      </div>
      <?php endif ?>


      <div class="d-flex p-2">
        <button class="btn btn-secondary dropdown-toggle btn btn-primary btn-lg btn-block" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Επιλογής </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenu2" style="overflow-y: scroll; height:400px; width:100%;">
          <?php while ($courses2 = mysqli_fetch_assoc($result3)) : ?>
            <div class="courses  ">
              <input type="checkbox" id="course" name="course[]" value=<?php echo $courses2["id"]; ?>>
              <label for=""> <?php echo  $courses2["name"]; ?> (<?php echo  $courses2["category"]; ?>) </label><br>
            </div>
            <hr class="mt-5">

            <br>
            <br>

       
          <?php endwhile ?>
          



        </div>
      </div>

      <div class="d-flex p-2">
        <button class="btn btn-secondary dropdown-toggle btn btn-primary btn-lg btn-block" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ελεύθερα</button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenu2" style="overflow-y: scroll; height:400px; width:100%;">
          <?php while ($courses2 = mysqli_fetch_assoc($result4)) : ?>
  
            <div class="courses  ">
              <input type="checkbox" id="course" name="course[]" value=<?php echo $courses2["id"]; ?>>
              <label for=""> <?php echo  $courses2["name"]; ?> (<?php echo  $courses2["category"]; ?>)  </label><br>
            </div>
            <hr class="mt-5">

            <br>
            <br>

          
          <?php endwhile ?>
          



        </div>
      </div>

    <?php endif ?>

    <br>
    <br>
    <br>
    <br>
    <br>

    <button class="btn btn-primary btn-lg btn-block" type="submit" name="courses" value="submit">Submit </button>



  </form>



</body>

</html>