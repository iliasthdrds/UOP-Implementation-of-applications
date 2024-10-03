<?php


$errors = array();
$cert_flag = "0";



$db = mysqli_connect("localhost", "test", "test", "db_dit");
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

if (!isset($_SESSION)) {
  session_start();
  error_reporting(E_ERROR | E_PARSE);

  $sql = "CREATE TEMPORARY TABLE selections (
        id int NOT NULL,
        category varchar(10), 
        fname varchar(255))";
  $result = mysqli_query($db, $sql);
}


if (isset($_POST['selection_user'])) {
  $department = mysqli_real_escape_string($db, $_POST['department']);
  $year=mysqli_real_escape_string($db, $_POST['year']);


  $_SESSION['year'] = $year;
  $_SESSION['department'] = $department;
 
  
  header('location: display.php');
}

if (isset($_GET['departments'])) {

  $dep = ($_GET['departments']);
  $year = ($_GET['year']);
  $_SESSION['year'] = $year;
  $_SESSION['departments'] = $dep;

  header('location: selection.php');
}

if (isset($_POST['edit_course'])) {
  
  $tmpid = mysqli_real_escape_string($db, $_POST['id']);
  $tmpname = mysqli_real_escape_string($db, $_POST['name']);
  $tmpects = mysqli_real_escape_string($db, $_POST['ects']);
  $tmpcategory = mysqli_real_escape_string($db, $_POST['category']);
  $tmpcorrespondence= mysqli_real_escape_string($db, $_POST['correspondence']);
  $tmptable=$_POST['table'];

  if(strlen($tmpid)==0){
  $sql="INSERT INTO {$tmptable} (id,name,category,ects,correspondence) VALUES ('$tmpid','$tmpname','$tmpcategory ','$tmpects','$tmpcorrespondence'); ";
  }else{
   $sql= "UPDATE {$tmptable} SET name='$tmpname', category='$tmpcategory' , ects='$tmpects' , correspondence='$tmpcorrespondence' WHERE id='$tmpid' ";
  }

  
$result = mysqli_query($db, $sql);
header('location: display.php');  




 
  
}
if (isset($_GET['courses'])) {


  if (empty($_GET['course'])) {
    array_push($errors, "Δεν έχετε επιλέξει κανένα μάθημα");
  } else {

    $myboxes = $_GET['course'];
    $i = count($myboxes);

    if ($_SESSION['departments'] == 'ETY') {


      if ($_SESSION['year'] == '2002' || $_SESSION['year'] == '2003') {

        //ΕΤΥ 2002,2003 Χρειαζόμαστε 24Κ,8ΒΚ,10 Μαθήματα όπου 1ΒΚ-ΣΛ,1ΒΚ-ΤΥ,1ΒΚ-ΘΠ,1ΕΠ-ΣΛ,1ΕΠ-ΤΥ,1ΕΠ-ΘΛ,1ΕΛ και άλλα 3 όποια θέλουμε 
        $count_k = "0";

        $count_bk_sl = "0";
        $count_ep_sl = "0";

        $count_bk_up = "0";
        $count_ep_up = "0";

        $count_bk_ty = "0";
        $count_ep_ty = "0";

        $count_el = "0";



        for ($j = 0; $j < $i; $j++) {
          $id = $myboxes[$j];


          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003`
                  WHERE id='$id' LIMIT 1 ";
          $result = mysqli_query($db, $sql);
          $course = mysqli_fetch_assoc($result);



          $tmp_category = $course["category"];
          $tmp_name = $course["name"];
          $sql2 = "INSERT INTO selections (id,category,fname)
                VALUES ('$id','$tmp_category','$tmp_name')";
          if (mysqli_query($db, $sql2)) {
          } else {
            echo "Error-- ";
          }




          if ($course["category"] == 'Κ') { //Κορμού
            $count_k = $count_k + 1;
          }
          if ($course["category"] == 'ΒΚ-ΣΛ') {
            $count_bk_sl = $count_bk_sl + 1;
          }
          if ($course["category"] == 'ΒΚ-ΘΠ') {
            $count_bk_up = $count_bk_up + 1;
          }
          if ($course["category"] == 'ΒΚ-ΤΥ') {
            $count_bk_ty = $count_bk_ty + 1;
          }
          if ($course["category"] == 'ΕΠ-ΘΠ') {
            $count_ep_up = $count_ep_up + 1;
          }
          if ($course["category"] == 'ΕΠ-ΣΛ') {
            $count_ep_sl = $count_ep_sl + 1;
          }
          if ($course["category"] == 'ΕΠ-ΤΥ') {
            $count_ep_ty = $count_ep_ty + 1;
          }
          if ($course["category"] == 'ΕΛ' || $course["category"] == 'ΕΛ-Π') {
            $count_el = $count_el + 1;
          }
        }
        if ($count_k == 24) {
          array_push($errors, "<u>Έχετε συμπληρώσει τα 24 μαθήματα κορμού</u>");
          $cert_flag++;
          $sql = "DELETE FROM `selections`
                    WHERE category='Κ' ";

          $result = mysqli_query($db, $sql);
        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα 24 μαθήματα κορμού χρειάζεστε ακόμα " . (24 - $count_k) . "</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003`
                    WHERE id NOT IN (SELECT id FROM `selections` ) AND category='Κ' ";

          $result = mysqli_query($db, $sql);
          if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");

            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . " ,το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }
        }
        $total_bk_ek_el = $count_bk_sl + $count_bk_up + $count_bk_ty + $count_ep_sl + $count_ep_up + $count_ep_ty + $count_el;
        if (($count_bk_sl - 1) >= 0 and ($count_bk_up - 1) >= 0 and ($count_bk_ty - 1) >= 0 and ($count_ep_sl - 1) >= 0 and ($count_ep_up - 1) >= 0 and ($count_ep_ty - 1) >= 0 and $total_bk_ek_el >= 10) {

          array_push($errors, "<u>Έχετε συμπληρώσει τα 10 μαθήματα κατεύθυνσης</u> ");
          $cert_flag++;
          $total_to_deleted = 10;

          $total_to_deleted = $total_to_deleted - 1;
          $count_bk_sl = $count_bk_sl - 1;
          $sql = "DELETE FROM `selections`
                    WHERE category='ΒΚ-ΣΛ' LIMIT 1";
          $result = mysqli_query($db, $sql);

          $total_to_deleted = $total_to_deleted - 1;
          $count_bk_ty = $count_bk_ty - 1;
          $sql = "DELETE FROM `selections`
                    WHERE category='ΒΚ-ΤΥ' LIMIT 1";
          $result = mysqli_query($db, $sql);

          $total_to_deleted = $total_to_deleted - 1;
          $count_bk_up = $count_bk_up - 1;
          $sql = "DELETE FROM `selections`
                    WHERE category='ΒΚ-ΘΠ' LIMIT 1";
          $result = mysqli_query($db, $sql);

          $total_to_deleted = $total_to_deleted - 1;
          $count_ep_up = $count_ep_up - 1;
          $sql = "DELETE FROM `selections`
                    WHERE category='ΕΠ-ΘΠ' LIMIT 1";
          $result = mysqli_query($db, $sql);

          $total_to_deleted = $total_to_deleted - 1;
          $count_ep_ty = $count_ep_ty - 1;
          $sql = "DELETE FROM `selections`
                    WHERE category='ΕΠ-ΤΥ' LIMIT 1";
          $result = mysqli_query($db, $sql);

          $total_to_deleted = $total_to_deleted - 1;
          $count_ep_sl = $count_ep_sl - 1;
          $sql = "DELETE FROM `selections`
                    WHERE category='ΕΠ-ΣΛ' LIMIT 1";
          $result = mysqli_query($db, $sql);

          $total_to_deleted = $total_to_deleted - $count_el;
          $sql = "DELETE FROM `selections`
                    WHERE category  LIKE 'ΕΛ%' ";
          $result = mysqli_query($db, $sql);

          $total_to_deleted = $total_to_deleted - $count_ep_up - $count_ep_ty - $count_ep_sl;
          $sql = "DELETE FROM `selections`
                    WHERE category  LIKE 'ΕΠ%' ";
          $result = mysqli_query($db, $sql);


          $sql = "DELETE FROM `selections`
                    WHERE category  LIKE '%ΒΚ%' LIMIT $total_to_deleted ";
          $result = mysqli_query($db, $sql);
        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα 10 μαθήματα κατεύθυνσης (βασικά ή επιλογής, ανεξαρτήτως κατεύθυνσης), ή ελεύθερης επιλογής, ή μαθήματα κύκλου παιδαγωγικής και διδακτικής, χρειάζεστε ακόμα " . (10 - $total_bk_ek_el) . "</u>");

          if ($count_bk_sl - 1 < 0) {
            array_push($errors, "<u>Τουλάχιστον 1  βασικό  κατεύθυνσης Συστημάτων Λογισμικού </u>");
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");
            $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003`
                    WHERE id NOT IN (SELECT id FROM `selections` ) AND category='ΒΚ-ΣΛ' ";
            $result = mysqli_query($db, $sql);
            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . ", το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }

          if ($count_bk_up - 1 < 0) {
            array_push($errors, "<u>Τουλάχιστον 1  βασικό  κατεύθυνσης Θεωρητικής Πληροφορικής </u>");
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");
            $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003`
                    WHERE id NOT IN (SELECT id FROM `selections` ) AND category='ΒΚ-ΘΠ' ";
            $result = mysqli_query($db, $sql);
            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . ", το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }

          if ($count_bk_ty - 1 < 0) {
            array_push($errors, "<u>Τουλάχιστον 1  βασικό  κατεύθυνσης Τεχνολογίας Υπολογιστών </u>");
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");
            $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003`
                    WHERE id NOT IN (SELECT id FROM `selections` ) AND category='ΒΚ-ΤΥ' ";
            $result = mysqli_query($db, $sql);
            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . ", το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }


          if ($count_ep_sl - 1 < 0) {
            array_push($errors, "<u>Τουλάχιστον 1  επιλογής  κατεύθυνσης Συστημάτων Λογισμικού </u>");
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");
            $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003`
                    WHERE id NOT IN (SELECT id FROM `selections` ) AND category='ΕΠ-ΣΛ' ";
            $result = mysqli_query($db, $sql);
            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . ", το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }


          if ($count_ep_up - 1 < 0) {
            array_push($errors, "<u>Τουλάχιστον 1  επιλογής  κατεύθυνσης Θεωρητικής Πληροφορικής </u>");
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");
            $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003`
                    WHERE id NOT IN (SELECT id FROM `selections` ) AND category='ΕΠ-ΘΠ' ";
            $result = mysqli_query($db, $sql);
            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . ", το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }


          if ($count_ep_ty - 1 < 0) {
            array_push($errors, "<u>Τουλάχιστον 1  επιλογής  κατεύθυνσης Τεχνολογίας Υπολογιστών </u>");
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:");
            $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003`
                    WHERE id NOT IN (SELECT id FROM `selections` ) AND category='ΕΠ-ΤΥ' ";
            $result = mysqli_query($db, $sql);
            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . ", το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }
        }
        if (($count_bk_sl + $count_bk_up + $count_bk_ty) >= 8) {
          $cert_flag++;
          array_push($errors, "<u>Έχετε συμπληρώσει τα 8 βασικά μαθήματα </u>");
        } else {
          array_push($errors, "Δεν έχετε συμπληρώσει τα 8 βασικά μαθήματα  χρειάζεστε ακόμα " . (8 - ($count_bk_sl + $count_bk_up + $count_bk_ty)));
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003`
                      WHERE id NOT IN (SELECT id FROM `selections` ) AND category LIKE'%ΒΚ%' ";

          $result = mysqli_query($db, $sql);
          if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");

            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . ", το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }
        }
        if ($cert_flag == 3) {
          array_push($errors, "<h1>Επικοινωνήστε με την γραμματεία μπορείτε να πραγματοποιήσετε περάτωση σπουδών </h1>");
        }
      }

      if ($_SESSION['year'] == '2004' || $_SESSION['year'] == '2005' || $_SESSION['year'] == '2006') {

        //ΕΤΥ 2004-2006 29Κ , 6ΒΚ, 4ΒΚ Ή ΕΚ ΑΛΛΑ ΤΑ ΔΥΟ ΑΠΟ ΔΙΑΦΟΡΕΤΙΚΕΣ ΚΑΤΕΥΘΥΝΣΕΙΣ , 2 ΕΛΕΥΘΕΡΑ  
        $count_k = "0";

        $count_bk_sl = "0";
        $count_ep_sl = "0";

        $count_bk_up = "0";
        $count_ep_up = "0";

        $count_bk_ty = "0";
        $count_ep_ty = "0";

        $count_el = "0";
        $count_el_pd = "0";

        for ($j = 0; $j < $i; $j++) {
          $id = $myboxes[$j];


          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2004__2005__2006`
                  WHERE id='$id' LIMIT 1 ";
          $result = mysqli_query($db, $sql);
          $course = mysqli_fetch_assoc($result);



          $tmp_category = $course["category"];
          $tmp_name = $course["name"];
          $sql2 = "INSERT INTO selections (id,category,fname)
                VALUES ('$id','$tmp_category','$tmp_name')";
          if (mysqli_query($db, $sql2)) {
          } else {
            echo "Error-- ";
          }




          if ($course["category"] == 'Κ') {
            $count_k = $count_k + 1;
          }
          if ($course["category"] == 'ΒΚ-ΣΛ') {
            $count_bk_sl = $count_bk_sl + 1;
          }
          if ($course["category"] == 'ΒΚ-ΘΠ') {
            $count_bk_up = $count_bk_up + 1;
          }
          if ($course["category"] == 'ΒΚ-ΤΥ') {
            $count_bk_ty = $count_bk_ty + 1;
          }
          if ($course["category"] == 'ΕΠ-ΘΠ') {
            $count_ep_up = $count_ep_up + 1;
          }
          if ($course["category"] == 'ΕΠ-ΣΛ') {
            $count_ep_sl = $count_ep_sl + 1;
          }
          if ($course["category"] == 'ΕΠ-ΤΥ') {
            $count_ep_ty = $count_ep_ty + 1;
          }
          if ($course["category"] == 'ΕΛ') {
            $count_el = $count_el + 1;
          }
          if ($course["category"] == 'ΕΛ-Π') {
            $count_el_pd = $count_el_pd + 1;
          }
        }

        if ($count_k > 28) {
          array_push($errors, "<u>Έχετε συμπληρώσει τα 28 μαθήματα κορμού</u>");
          $cert_flag++;
          $sql = "DELETE FROM `selections`
                    WHERE category='Κ' ";

          $result = mysqli_query($db, $sql);
        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα 28 μαθήματα κορμού χρειάζεστε ακόμα " . (28 - $count_k) . "</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2004__2005__2006`
                    WHERE id NOT IN (SELECT id FROM `selections` ) AND category='Κ' ";

          $result = mysqli_query($db, $sql);
          if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");

            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . " ,το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }
        }



        $match_bk = "0";
        $option1_bk = "";
        $option2_bk = "";
        $match_ek = "0";
        $option1_ek = "";
        $option2_ek = "";

        $sql = "SELECT id,COUNT(category),category FROM `selections` GROUP BY category";
        $result = mysqli_query($db, $sql);
        while ($courses = mysqli_fetch_assoc($result)) {
          if ($courses['category'] == "ΒΚ-ΣΛ" or  $courses['category'] == "ΒΚ-ΤΥ" || $courses['category'] == "ΒΚ-ΘΠ") {

            $match_bk++;
            if ($match_bk == '1') {
              $option1_bk = $courses['category'];
            }

            if ($match_bk == '2') {
              $option2_bk = $courses['category'];
            }
          }

          if ($courses['category'] == "ΕΠ-ΘΠ" or  $courses['category'] == "ΕΠ-ΣΛ" || $courses['category'] == "ΕΠ-ΤΥ") {
            $match_bk++;
            if ($match_bk == '1') {
              $option1_ek = $courses['category'];
            }

            if ($match_bk == '2') {
              $option2_ek = $courses['category'];
            }
          }
        }




        $total_bk_ek_el_pd = $count_bk_sl + $count_bk_up + $count_bk_ty + $count_ep_sl + $count_ep_up + $count_ep_ty + $count_el_pd;
        $total_deleted = 4;
        if ($total_bk_ek_el_pd >= 4 and ($match_bk > 2 || $match_ek > 2)) {

          $cert_flag++;
          array_push($errors, "<u>Έχετε συμπληρώσει τα 4 μαθήματα επιλογής κατεύθυνσης ή κύκλου παιδαγωγικής και διδακτικής </u>");
          if ($match_bk > 0) {
            $sql = "DELETE FROM `selections`
            WHERE category='$option1_bk' LIMIT 1 ";
            $result = mysqli_query($db, $sql);
            $total_deleted--;

            $sql = "DELETE FROM `selections`
            WHERE category='$option2_bk' LIMIT 1 ";
            $result = mysqli_query($db, $sql);
            $total_deleted--;

            if (($count_ep_sl + $count_ep_up + $count_ep_ty) > 0) {
              $total_deleted = $total_deleted - ($count_ep_sl + $count_ep_up + $count_ep_ty);
              $sql = "DELETE FROM `selections`
              WHERE category LIKE '%ΕΠ%'  LIMIT 2 ";
              mysqli_query($db, $sql);
            }
            if ($total_deleted > 0 and $count_el_pd > 0) {

              $sql = "DELETE FROM `selections`
              WHERE category LIKE'ΕΛ-Π'  LIMIT '$total_deleted' ";
              mysqli_query($db, $sql);
              $total_deleted = $total_deleted - $count_el_pd;
            }
            if ($total_deleted > 0) {

              $sql = "DELETE FROM `selections`
              WHERE category LIKE '%ΒΚ%'  LIMIT '$total_deleted' ";
              mysqli_query($db, $sql);
            }
          } else if ($match_ek > 0) {
            $sql = "DELETE FROM `selections`
            WHERE category='$option1_ek' LIMIT 1 ";
            $result = mysqli_query($db, $sql);
            $total_deleted--;

            $sql = "DELETE FROM `selections`
            WHERE category='$option2_ek' LIMIT 1 ";
            $result = mysqli_query($db, $sql);
            $total_deleted--;

            if (($count_bk_sl + $count_bk_up + $count_bk_ty) > 0) {
              $total_deleted = $total_deleted - ($count_bk_sl + $count_bk_up + $count_bk_ty);
              $sql = "DELETE FROM `selections`
              WHERE category LIKE '%ΒΚ%'  LIMIT 2 ";
              mysqli_query($db, $sql);
            }
            if ($total_deleted > 0 and $count_el_pd > 0) {
              $total_deleted = $total_deleted - $count_el_pd;
              $sql = "DELETE FROM `selections`
              WHERE category LIKE 'ΕΛ-Π'  LIMIT '$total_deleted' ";
              mysqli_query($db, $sql);
            }
            if ($total_deleted > 0) {
              $sql = "DELETE FROM `selections`
              WHERE category LIKE '%ΒΚ%'  LIMIT '$total_deleted' ";
              mysqli_query($db, $sql);
            }
          }
        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα 4 μαθήματα επιλογής κατεύθυνσης ή κύκλου παιδαγωγικής και διδακτικής(*ΠΔ*),<br> εκ των
          οποίων 2 πρέπει να είναι επιλογές μαθημάτων (υποχρεωτικών*BK* ή κατ΄ επιλογήν*EK*) από άλλες κατευθύνσεις</u>");

          $sql = "SELECT id,COUNT(category),category FROM `selections`WHERE category<>'ΕΛ' AND category<>'Κ' GROUP BY category";
          $result = mysqli_query($db, $sql);
          while ($courses = mysqli_fetch_assoc($result)) {
            if ($courses['category'] != 'Κ' and  $courses['category'] != 'ΕΛ') {
              array_push($errors, "Έχετε σημειώσει " . ($courses['COUNT(category)']) . " " . ($courses['category']));
            }
          }


          array_push($errors, "Μπορείς να επιλέξεις ανάμεσα:");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2004__2005__2006`
                    WHERE id NOT IN (SELECT id FROM `selections` ) AND category<>'Κ' AND category<>'ΕΛ' AND category<>'ΕΛ-Π'";
          $result2 = mysqli_query($db, $sql);
          while ($courses = mysqli_fetch_assoc($result2)) {
            array_push($errors, "" . ($courses['name']) . "(" . ($courses['category']) . ")" . ", το οποίο αντιστοιχεί με:<br> "  . $courses['correspondence']);
          }


          $sql = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών` WHERE category='ΠΔ';";
          $result = mysqli_query($db, $sql);
          while ($courses = mysqli_fetch_assoc($result)) {
            array_push($errors, "" . ($courses['name']) . "(" . ($courses['category']) . ")");
          }
        }


        $total_bk = 0;
        $sql = "SELECT id,COUNT(category),category FROM `selections` GROUP BY category";
        $result = mysqli_query($db, $sql);
        while ($courses = mysqli_fetch_assoc($result)) {
          if ($courses['category'] == "ΒΚ-ΣΛ" or  $courses['category'] == "ΒΚ-ΤΥ" || $courses['category'] == "ΒΚ-ΘΠ") {
            $total_bk = $total_bk + $courses['COUNT(category)'];
          }
        }

        if ($total_bk > 6) {
          $cert_flag++;
          array_push($errors, "<u>Έχετε συμπληρώσει τα 6 υποχρεωτικά μαθήματα της κατεύθυνσης </u>");
          $sql = "DELETE FROM `selections` WHERE category LIKE '%ΒΚ%' LIMIT 6 ";
          $result = mysqli_query($db, $sql);
        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα 6 υποχρεωτικά μαθήματα της κατεύθυνσης  ακόμα " . (6 - $total_bk) . "</u>");
          array_push($errors, "Μπορείς να επιλέξεις ανάμεσα:");

          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2004__2005__2006`
                              WHERE id NOT IN (SELECT id FROM `selections` ) AND category LIKE'%ΒΚ%' ";
          $result2 = mysqli_query($db, $sql);
          while ($courses = mysqli_fetch_assoc($result2)) {
            array_push($errors, "" . ($courses['name']) . "(" . ($courses['category']) . ")" . ", το οποίο αντιστοιχεί με:<br> "  . $courses['correspondence']);
          }
        }

        $total_el = 0;
        $total_el_pd = 0;

        $sql = "SELECT id,COUNT(category),category FROM `selections` GROUP BY category";
        $result = mysqli_query($db, $sql);
        while ($courses = mysqli_fetch_assoc($result)) {
          if ($courses['category'] == "ΕΛ") {
            $total_el = $total_el + $courses['COUNT(category)'];
          }
          if ($courses['category'] == "ΕΛ-Π") {
            $total_el_pd = $total_el_pd + $courses['COUNT(category)'];
          }
        }
        echo ($total_el + $total_el_pd);
        if (($total_el + $total_el_pd) > 2) {
          $cert_flag++;
          array_push($errors, "<u>Έχετε συμπληρώσει τα 2 μαθήματα ελεύθερης επιλογής ή κύκλου παιδαγωγικής και διδακτικής </u>");
        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα 2 μαθήματα ελεύθερης επιλογής ή κύκλου παιδαγωγικής και διδακτικής χρειάζεστε ακόμα "  . (2 - ($total_el + $total_el_pd)) . "</u>");
          $sql = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών`
          WHERE category='ΕΕ' OR category='ΠΔ' ";
          $result2 = mysqli_query($db, $sql);
          while ($courses = mysqli_fetch_assoc($result2)) {
            array_push($errors, "" . ($courses['name']) . "(" . ($courses['category']) . ")");
          }
        }



        if ($cert_flag == 4) {
          array_push($errors, "<h1>Επικοινωνήστε με την γραμματεία μπορείτε να πραγματοποιήσετε περάτωση σπουδών </h1>");
        }
      }

      if ($_SESSION['year'] == '2007' || $_SESSION['year'] == '2008' || $_SESSION['year'] == '2009' || $_SESSION['year'] == '2010' || $_SESSION['year'] == '2011') {

        $count_k1 = "0";
        $count_k2 = "0";

        $count_bk_sl = "0";
        $count_ep_sl = "0";

        $count_bk_up = "0";
        $count_ep_up = "0";

        $count_bk_ty = "0";
        $count_ep_ty = "0";

        $count_el = "0";

        for ($j = 0; $j < $i; $j++) {
          $id = $myboxes[$j];

          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2007_2011`
                  WHERE id='$id' LIMIT 1 ";
          $result = mysqli_query($db, $sql);
          $course = mysqli_fetch_assoc($result);



          $tmp_category = $course["category"];
          $tmp_name = $course["name"];
          $sql2 = "INSERT INTO selections (id,category,fname)
                VALUES ('$id','$tmp_category','$tmp_name')";
          if (mysqli_query($db, $sql2)) {
          } else {
            echo "Error-- ";
          }




          if ($course["category"] == 'Κ1') { //Κορμού
            $count_k1 = $count_k1 + 1;
          }
          if ($course["category"] == 'Κ2') { //Κορμού
            $count_k2 = $count_k2 + 1;
          }
          if ($course["category"] == 'ΒΚ-ΣΛ') {
            $count_bk_sl = $count_bk_sl + 1;
          }
          if ($course["category"] == 'ΒΚ-ΘΠ') {
            $count_bk_up = $count_bk_up + 1;
          }
          if ($course["category"] == 'ΒΚ-ΤΥ') {
            $count_bk_ty = $count_bk_ty + 1;
          }
          if ($course["category"] == 'ΕΠ-ΘΠ') {
            $count_ep_up = $count_ep_up + 1;
          }
          if ($course["category"] == 'ΕΠ-ΣΛ') {
            $count_ep_sl = $count_ep_sl + 1;
          }
          if ($course["category"] == 'ΕΠ-ΤΥ') {
            $count_ep_ty = $count_ep_ty + 1;
          }
          if ($course["category"] == 'ΕΛ' || $course["category"] == 'ΕΛ-Π') {
            $count_el = $count_el + 1;
          }
        }


        if ($count_k1 == 23) {
          array_push($errors, "<u>Έχετε συμπληρώσει τα 24 μαθήματα κορμού Κ1</u>");
          $cert_flag++;
          $sql = "DELETE FROM `selections`
                    WHERE category='Κ1' ";

          $result = mysqli_query($db, $sql);
        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα 23 μαθήματα κορμού Κ1 χρειάζεστε ακόμα " . (23 - $count_k1) . "</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2007_2011`
                  WHERE id NOT IN (SELECT id FROM `selections` ) AND category='Κ1' ";

          $result = mysqli_query($db, $sql);
          if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");

            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . " ,το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }
        }

        if ($count_k2 >= 6) {
          array_push($errors, "<u>Έχετε συμπληρώσει τα 6 μαθήματα κορμού Κ2</u>");
          $cert_flag++;
          $sql = "DELETE FROM `selections`
                    WHERE category='Κ2' LIMIT 6 ";

          $result = mysqli_query($db, $sql);
        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα 6 μαθήματα κορμού Κ2 χρειάζεστε ακόμα " . (6 - $count_k2) . "</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2007_2011`
                  WHERE id NOT IN (SELECT id FROM `selections` ) AND category='Κ2' ";

          $result = mysqli_query($db, $sql);
          if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");

            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . " ,το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }
        }

        if (($count_bk_sl + $count_bk_ty + $count_bk_up) >= 3) {
          array_push($errors, "<u>Έχετε συμπληρώσει τα 3 υποχρεωτικά μαθήματα</u>");
          $cert_flag++;
          $sql = "DELETE FROM `selections`
                  WHERE category LIKE '%ΒΚ%' LIMIT 3 ";

          $result = mysqli_query($db, $sql);
        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα 3 υποχρεωτικά μαθήματα χρειάζεστε ακόμα " . (3 - ($count_bk_sl + $count_bk_ty + $count_bk_up)) . "</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2007_2011`
                  WHERE id NOT IN (SELECT id FROM `selections` ) AND category LIKE '%ΒΚ%' ";

          $result = mysqli_query($db, $sql);
          if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");

            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . " ,το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }
        }

        $total_k2 = 0;
        $total_bk = 0;
        $total_ek = 0;
        $total_el = 0;
        $total_el_pd = 0;

        $sql = "SELECT COUNT(category),category FROM `selections` GROUP BY category";
        $result = mysqli_query($db, $sql);
        while ($courses = mysqli_fetch_assoc($result)) {
          if ($courses['category'] == 'Κ2') {
            $total_k2 = $total_k2 + $courses['COUNT(category)'];
          }
          if ($courses['category'] == 'ΒΚ-ΘΠ' || $courses['category'] == 'ΒΚ-ΣΛ' || $courses['category'] == 'ΒΚ-ΤΥ') {
            $total_bk = $total_bk + 1;
          }
          if ($courses['category'] == 'ΕΠ-ΘΠ' || $courses['category'] == 'ΕΠ-ΣΛ' || $courses['category'] == 'ΕΠ-ΤΥ') {
            $total_ek = $total_ek + 1;
          }
          if ($courses['category'] == 'ΕΛ') {
            $total_el = $total_el + 1;
          }
          if ($courses['category'] == 'ΕΛ-Π') {
            $total_el_pd = $total_el_pd + 1;
          }
        }

        $totalects = $total_k2 * 6 + $total_bk * 6 + $total_ek * 6 + $total_el * 3 + $total_el_pd * 5;

        if ($totalects >= 36) {
          array_push($errors, "<u>Έχετε συμπληρώσει τα  κατ΄ επιλογή μαθήματα συνολικού βάρους 36 μονάδων ECTS </u>");
        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα κατ΄ επιλογή μαθήματα συνολικού βάρους 36 μονάδων ECTS χρειάζεστε ακόμα " . (36 - $totalects) . " ECTS</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2007_2011`
        WHERE id NOT IN (SELECT id FROM `selections` ) AND category<>'Κ1' AND category<>'ΕΛ'   ";
          $result = mysqli_query($db, $sql);
          if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");
            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name'] . " (" . $courses[ects]  . " ECTS) ,το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }

          $sql = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών`
        WHERE id NOT IN (SELECT id FROM `selections` )  AND category LIKE '%ΕΕ%'   ";
          $result = mysqli_query($db, $sql);
          if (mysqli_num_rows($result) > 0) {
            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name'] . " (" . $courses[ects]  . " ECTS) ";
              array_push($errors, "" . $string);
            }
          }
        }
      }
      if ($_SESSION['year'] == '2012') {
        $cert_flag = "0";
        $count_k = "0";
        $count_ep_eo = "0";
        $count_ep_et = "0";
        $count_ep_ey = "0";
        $count_el_p = "0";
        $count_el = "0";

        for ($j = 0; $j < $i; $j++) {
          $id = $myboxes[$j];

          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2012`
                  WHERE id='$id' LIMIT 1 ";
          $result = mysqli_query($db, $sql);
          $course = mysqli_fetch_assoc($result);



          $tmp_category = $course["category"];
          $tmp_name = $course["name"];
          $sql2 = "INSERT INTO selections (id,category,fname)
                VALUES ('$id','$tmp_category','$tmp_name')";
          if (mysqli_query($db, $sql2)) {
          } else {
            echo "Error-- ";
          }




          if ($course["category"] == 'Κ') { //Κορμού
            $count_k1 = $count_k1 + 1;
          }
          if ($course["category"] == 'ΕΠ-ΕΟ') {
            $count_ep_eo = $count_ep_eo + 1;
          }
          if ($course["category"] == 'ΕΠ-ΕΤ') {
            $count_ep_et = $count_ep_et + 1;
          }
          if ($course["category"] == 'ΕΠ-ΕΥ') {
            $count_ep_ey = $count_ep_ey + 1;
          }
          if ($course["category"] == 'ΕΛ-Π') {
            $count_el_p = $count_el_p + 1;
          }
          if ($course["category"] == 'ΕΛ') {
            $count_el =  $count_el + 1;
          }
        }

        if ($count_k1 >= 25) {
          array_push($errors, "<u>Έχετε συμπληρώσει τα 25 μαθήματα κορμού</u>");
          $cert_flag++;
          $sql = "DELETE FROM `selections`
                    WHERE category='Κ' ";
          $result = mysqli_query($db, $sql);
        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα 25 μαθήματα κορμού Κ χρειάζεστε ακόμα " . (25 - $count_k) . "</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2012`
                  WHERE id NOT IN (SELECT id FROM `selections` ) AND category='Κ' ";
          $result = mysqli_query($db, $sql);
          if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");
            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . " ,το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }
        }

        $total_ects = "0";
        $total_ects = $count_ep_ey * 6;
        if ($count_ep_et > 4) {
          $total_ects = $total_ects + 4 * 6;
        } else {
          $total_ects = $total_ects + $count_ep_et * 6;
        }
        if (($count_el + $count_ep_eo) > 3) {
          $total_ects = $total_ects + 4 * 3;
        } else {
          $total_ects = $total_ects + ($count_el + $count_ep_eo) * 4;
        }

        if ($count_el_p > 2) {
          $total_ects = $total_ects + 2 * 5;
        } else {
          $total_ects = $total_ects + $count_el_p * 5;
        }

        if ($total_ects >= 66) {
          $cert_flag++;
          array_push($errors, "<u>Έχετε συμπληρώσει τα μαθήματα επιλογής συνολικού βάρους 66 μονάδων ECTS</u>");
        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα μαθήματα επιλογής συνολικού βάρους 66 μονάδων ECTS χρειάζεστε ακόμα " . (66 - $total_ects) . "</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2012`
          WHERE id NOT IN (SELECT id FROM `selections` ) AND category='ΕΠ-ΕΥ' ";
          $result = mysqli_query($db, $sql);
          if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");
            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . " ,το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }
          if ($count_ep_et > 4) {
            array_push($errors, "<u> Έχετε συμπληρώσει τον μέγιστο αριθμό μαθημάτων επιλογής της επιστήμης τηλεπικοινωνιών. Υπολοίπονται ακόμα:" . (66 - $total_ects) . "ΕCTS</u>");
          } else {
            array_push($errors, "<u> Δεν έχετε συμπληρώσει τον μέγιστο αριθμό μαθημάτων επιλογής της επιστήμης τηλεπικοινωνιών. Μπορείτε να επιλέξετε ακόμα " . (4 - $count_ep_et) . "μαθήματα</u>");
            $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2012`
            WHERE id NOT IN (SELECT id FROM `selections` ) AND category='ΕΠ-ΕΤ' ";
            $result = mysqli_query($db, $sql);
            if (mysqli_num_rows($result) > 0) {
              array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");
              while ($courses = mysqli_fetch_assoc($result)) {
                $string = $courses['name']  . " ,το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
                array_push($errors, "" . $string);
              }
            }
          }
          if (($count_el + $count_ep_eo) > 3) {
            array_push($errors, "<u> Έχετε συμπληρώσει τον μέγιστο αριθμό μαθημάτων επιλογής της επιστήμης οικονομικών ή μαθήματα ελεύθερης επιλογής. Υπολοίπονται ακόμα:" . (66 - $total_ects) . "ECTS</u>");
          } else {
            array_push($errors, "<u> Δεν έχετε συμπληρώσει τον μέγιστο αριθμό μαθημάτων επιλογής της επιστήμης οικονομικών ή μαθήματα ελεύθερης επιλογής. Μπορείτε να επιλέξετε ακόμα " . (3 - ($count_el + $count_ep_eo)) . " μαθήματα</u>");
            $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2012`
            WHERE id NOT IN (SELECT id FROM `selections` ) AND category='ΕΛ' OR category='ΕΠ-ΕΟ' ";
            $result = mysqli_query($db, $sql);
            if (mysqli_num_rows($result) > 0) {
              array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");
              while ($courses = mysqli_fetch_assoc($result)) {
                $string = $courses['name']  . " ,το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
                array_push($errors, "" . $string);
              }
            }
          }
          if ($count_el_p > 2) {
            array_push($errors, "<u> Έχετε συμπληρώσει τον μέγιστο αριθμό μαθημάτων κύκλου παιδαγωγικής και διδακτικής. Υπολοίπονται ακόμα:" . (66 - $total_ects) . "ECTS</u>");
          } else {
            array_push($errors, "<u> Δεν έχετε συμπληρώσει τον μέγιστο αριθμό μαθημάτων κύκλου παιδαγωγικής και διδακτικής. Μπορείτε να επιλέξετε ακόμα " . (2 - $count_el_p) . " μαθήματα</u>");
            $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _υπολογιστών_2012`
            WHERE id NOT IN (SELECT id FROM `selections` ) AND category='ΕΛ-Π' ";
            $result = mysqli_query($db, $sql);
            if (mysqli_num_rows($result) > 0) {
              array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");
              while ($courses = mysqli_fetch_assoc($result)) {
                $string = $courses['name']  . " ,το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
                array_push($errors, "" . $string);
              }
            }
          }
        }
        if ($cert_flag == 2) {
          array_push($errors, "<h1>Επικοινωνήστε με την γραμματεία μπορείτε να πραγματοποιήσετε περάτωση σπουδών </h1>");
        }
      }
    }

    if ($_SESSION['departments'] == 'ETT') {
      $flag="0";

      if ($_SESSION['year'] < '2009') {
        $total_ects = "0";
        $count_k = "0";
        $count_ep_p = "0";
        $count_ep_t = "0";
        $count_ep_pt = "0";
        $count_el = "0";
        $count_el_p = "0";
       


        for ($j = 0; $j < $i; $j++) {
          $id = $myboxes[$j];


          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009`
                  WHERE id='$id' LIMIT 1 ";
          $result = mysqli_query($db, $sql);
          $course = mysqli_fetch_assoc($result);



          $tmp_category = $course["category"];
          $tmp_name = $course["name"];
          $sql2 = "INSERT INTO selections (id,category,fname)
                VALUES ('$id','$tmp_category','$tmp_name')";
          if (mysqli_query($db, $sql2)) {
          } else {

            echo "Error-- ";
          }




          if ($course["category"] == 'Κ') {
            $count_k = $count_k + 1;
            $total_ects = $total_ects + $course["ects"];
          }
          if ($course["category"] == 'ΕΚ-Π') {
            $count_ep_p = $count_ep_p + 1;
            $total_ects = $total_ects +  $course["ects"];
          }

          if ($course["category"] == 'ΕΚ-Τ') {
            $count_ep_t = $count_ep_t + 1;
            $total_ects = $total_ects + $course["ects"];
          }

          if ($course["category"] == 'ΕΚ-ΠΤ') {
            $count_ep_pt = $count_ep_pt + 1;
            $total_ects = $total_ects + $course["ects"];
          }
          if ($course["category"] == 'ΕΕ') {
            $count_el = $count_el + 1;
            $total_ects = $total_ects +  $course["ects"];
          }
          if ($course["category"] == 'ΕΕ-Π') {
            $count_el_p = $count_el_p + 1;
            $total_ects = $total_ects +  $course["ects"];
          }
        }

       
        if ($count_k == 27) {
          array_push($errors, "<u>Έχετε συμπληρώσει τα 27 μαθήματα κορμού</u>");

          $flag=$flag+1;

        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα 27 μαθήματα κορμού χρειάζεστε ακόμα " . (27 - $count_k) . "</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009`
                    WHERE id NOT IN (SELECT id FROM `selections` ) AND category='Κ' ";

          $result = mysqli_query($db, $sql);
          if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");

            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . " ,το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }
        }
        $total_ep=$count_ep_t + $count_ep_pt;
        if($total_ep >=6){
          $total_ep=$total_ep-6;
          $flag=$flag+1;
          array_push($errors, "<u>Έχετε συμπληρώσει τα  6 μαθήματα κατεύθυνσης Τηλεπικοινωνιών </u>");
          $sql = "DELETE FROM `selections`
          WHERE category='ΕΚ-Τ' or category='ΕΚ-ΠΤ'  LIMIT 6";
          $result = mysqli_query($db, $sql);
        }else{
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα  6 μαθήματα κατεύθυνσης Τηλεπικοινωνιών χρειάζεστε ακόμα " . (6 - $total_ep) . "</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009`
          WHERE id NOT IN (SELECT id FROM `selections` ) AND  category='ΕΚ-Τ'  or category='ΕΚ-ΠΤ' ";
           $result = mysqli_query($db, $sql);
           if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");

            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name'] ;
              array_push($errors, "" . $string);
            }
          }

        }



        if($flag==2){
          $calcualte_total=$count_ep_p +$total_ep;
        }else{
          $calcualte_total=$count_ep_p ;
        }
        $ep_total=$calcualte_total;
        


          if($count_el+$count_el_p>6){
            $calcualte_total=$calcualte_total+6;
          }else{
            $calcualte_total= $calcualte_total+$count_el+$count_el_p;
          }
          if($calcualte_total>=9 && $flag==2 ){
            
            $flag=$flag+1;
          } 
          else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει με επιτυχια τα προαπαιτούμενα μαθήματα  σας υπολείπονται  " . (9 - $calcualte_total) . " μαθήματα</u>");
          array_push($errors, "Έχετε σημειώσει " . $ep_total . " επιλογής κατεύθυνσης  ");
          array_push($errors, "Έχετε σημειώσει " . $count_el . " ελεύθερης επιλογής ");
          array_push($errors, "Έχετε σημειώσει " . $count_el_p . " κύκλου παιδαγωγικής και διδακτικής ");
          array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα :");
     
          array_push($errors, "<u> Σε 10 μαθήματα Κορμού ή κατεύθυνσης Τηλεπικοινωνιών ή κατεύθυνσης Πληροφορικής ή ελεύθερης επιλογής ή κύκλου παιδαγωγικής και διδακτικής </u>");
          array_push($errors, "<u>Το πολύ 6 μαθήματα ελεύθερης επιλογής ή κύκλου παιδαγωγικής και διδακτικής</u>");
          array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα απο:");
          $sql = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών`
                  WHERE name NOT IN (SELECT fname FROM `selections` ) AND category != 'Κ' ";

          $result = mysqli_query($db, $sql);
          while ($courses = mysqli_fetch_assoc($result)) {
            $string = $courses['name'] . "(" . $courses['category'] . ")";
            array_push($errors, "" . $string);
          }
        }
        if($flag==3){
          array_push($errors, "<u>Έχετε συμπληρώσει με επιτυχία τα μαθήματα </u>");
          array_push($errors, "<h1>Επικοινωνήστε με την γραμματεία μπορείτε να πραγματοποιήσετε περάτωση σπουδών </h1>");
        }
      }



      if ($_SESSION['year'] == '2009' || $_SESSION['year'] == '2010') {
        $total_ects = "0";
        $count_k = "0";
        $count_ep_p = "0";
        $count_ep_t = "0";
        $count_ep_pt = "0";
        $count_el = "0";
        $count_el_p = "0";



        for ($j = 0; $j < $i; $j++) {
          $id = $myboxes[$j];


          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009_2010`
                  WHERE id='$id' LIMIT 1 ";
          $result = mysqli_query($db, $sql);
          $course = mysqli_fetch_assoc($result);



          $tmp_category = $course["category"];
          $tmp_name = $course["name"];
          $sql2 = "INSERT INTO selections (id,category,fname)
                VALUES ('$id','$tmp_category','$tmp_name')";
          if (mysqli_query($db, $sql2)) {
          } else {

            echo "Error-- ";
          }




          if ($course["category"] == 'Κ') {
            $count_k = $count_k + 1;
            $total_ects = $total_ects + $course["ects"];
          }
          if ($course["category"] == 'ΕΚ-Π') {
            $count_ep_p = $count_ep_p + 1;
            $total_ects = $total_ects +  $course["ects"];
          }

          if ($course["category"] == 'ΕΚ-Τ') {
            $count_ep_t = $count_ep_t + 1;
            $total_ects = $total_ects + $course["ects"];
          }

          if ($course["category"] == 'ΕΚ-ΠΤ') {
            $count_ep_pt = $count_ep_pt + 1;
            $total_ects = $total_ects + $course["ects"];
          }
          if ($course["category"] == 'ΕΕ') {
            $count_el = $count_el + 1;
            $total_ects = $total_ects +  $course["ects"];
          }
          if ($course["category"] == 'ΕΕ-Π') {
            $count_el_p = $count_el_p + 1;
            $total_ects = $total_ects +  $course["ects"];
          }
        }

        if ($count_k == 28) {
          array_push($errors, "<u>Έχετε συμπληρώσει τα 28 μαθήματα κορμού</u>");

          $flag=$flag+1;
        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα 28 μαθήματα κορμού χρειάζεστε ακόμα " . (28 - $count_k) . "</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009_2010`
                    WHERE id NOT IN (SELECT id FROM `selections` ) AND category='Κ' ";

          $result = mysqli_query($db, $sql);
          if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");

            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . " ,το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }
        }

        $total_ep=$count_ep_t + $count_ep_pt;
        if($total_ep >=6){
          $total_ep=$total_ep-6;;
          $flag=$flag+1;
          array_push($errors, "<u>Έχετε συμπληρώσει τα  6 μαθήματα κατεύθυνσης Τηλεπικοινωνιών </u>");
          $sql = "DELETE FROM `selections`
          WHERE category='ΕΚ-Τ' or category='ΕΚ-ΠΤ' LIMIT 6";
          $result = mysqli_query($db, $sql);
        }else{
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα  6 μαθήματα κατεύθυνσης Τηλεπικοινωνιών χρειάζεστε ακόμα " . (6 - $total_ep) . "</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009_2010`
          WHERE id NOT IN (SELECT id FROM `selections` ) AND category='ΕΚ-Τ'  or category='ΕΚ-ΠΤ' ";
           $result = mysqli_query($db, $sql);
           if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");

            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name'] ;
              array_push($errors, "" . $string);
            }
          }

        }


        
        if($flag==2){
          $calcualte_total=$count_ep_p +$total_ep;
        }else{
          $calcualte_total=$count_ep_p ;
        }
        $ep_total=$calcualte_total;
        

        
        if($count_el+$count_el_p>6){
          $calcualte_total=$calcualte_total+6;
        }else{
          $calcualte_total= $calcualte_total+$count_el+$count_el_p;
        }
        
        if($calcualte_total>=8 && $flag==2){
          $flag=$flag+1;
        } 
        else {
        array_push($errors, "<u>Δεν έχετε συμπληρώσει με επιτυχια τα προαπαιτούμενα μαθήματα  σας υπολείπονται  " . (8 - $calcualte_total) . " μαθήματα</u>");
        array_push($errors, "Έχετε σημειώσει " . $ep_total . " επιλογής κατεύθυνσης  ");
        array_push($errors, "Έχετε σημειώσει " . $count_el . " ελεύθερης επιλογής ");
        array_push($errors, "Έχετε σημειώσει " . $count_el_p . " κύκλου παιδαγωγικής και διδακτικής ");
        array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα :");
   
        array_push($errors, "<u> Σε 9 μαθήματα Κορμού ή κατεύθυνσης Τηλεπικοινωνιών ή κατεύθυνσης Πληροφορικής ή ελεύθερης επιλογής ή κύκλου παιδαγωγικής και διδακτικής </u>");
        array_push($errors, "<u>Το πολύ 6 μαθήματα ελεύθερης επιλογής ή κύκλου παιδαγωγικής και διδακτικής</u>");
        array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα απο:");
        $sql = "SELECT * FROM  `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009_2010`
                WHERE name NOT IN (SELECT fname FROM `selections` ) AND category != 'Κ' ";

        $result = mysqli_query($db, $sql);
        while ($courses = mysqli_fetch_assoc($result)) {
          $string = $courses['name'] . "(" . $courses['category'] . ")";
          array_push($errors, "" . $string);
        }
      }
      if($flag==3){
        array_push($errors, "<u>Έχετε συμπληρώσει με επιτυχία τα μαθήματα </u>");
        array_push($errors, "<h1>Επικοινωνήστε με την γραμματεία μπορείτε να πραγματοποιήσετε περάτωση σπουδών </h1>");
      }


      }



      if ($_SESSION['year'] == '2011') {
        $total_ects = "0";
        $count_k = "0";
        $count_ep_p = "0";
        $count_ep_t = "0";
        $count_ep_pt = "0";
        $count_el = "0";
        $count_el_p = "0";



        for ($j = 0; $j < $i; $j++) {
          $id = $myboxes[$j];


          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2011`
                  WHERE id='$id' LIMIT 1 ";
          $result = mysqli_query($db, $sql);
          $course = mysqli_fetch_assoc($result);



          $tmp_category = $course["category"];
          $tmp_name = $course["name"];
          $sql2 = "INSERT INTO selections (id,category,fname)
                VALUES ('$id','$tmp_category','$tmp_name')";
          if (mysqli_query($db, $sql2)) {
          } else {

            echo "Error-- ";
          }




          if ($course["category"] == 'Κ') {
            $count_k = $count_k + 1;
            $total_ects = $total_ects + $course["ects"];
          }
          if ($course["category"] == 'ΕΚ-Π') {
            $count_ep_p = $count_ep_p + 1;
            $total_ects = $total_ects +  $course["ects"];
          }

          if ($course["category"] == 'ΕΚ-Τ') {
            $count_ep_t = $count_ep_t + 1;
            $total_ects = $total_ects + $course["ects"];
          }

          if ($course["category"] == 'ΕΚ-ΠΤ') {
            $count_ep_pt = $count_ep_pt + 1;
            $total_ects = $total_ects + $course["ects"];
          }
          if ($course["category"] == 'ΕΕ') {
            $count_el = $count_el + 1;
            $total_ects = $total_ects +  $course["ects"];
          }
          if ($course["category"] == 'ΕΕ-Π') {
            $count_el_p = $count_el_p + 1;
            $total_ects = $total_ects +  $course["ects"];
          }
        }

        if ($count_k == 24) {
          array_push($errors, "<u>Έχετε συμπληρώσει τα 24 μαθήματα κορμού</u>");
          $flag=$flag+1;
        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα 24 μαθήματα κορμού χρειάζεστε ακόμα " . (24 - $count_k) . "</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2011`
                    WHERE id NOT IN (SELECT id FROM `selections` ) AND category='Κ' ";

          $result = mysqli_query($db, $sql);
          if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");

            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . " ,το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }
        }

        $total_ep=$count_ep_t + $count_ep_pt;
        if($total_ep>= 10){
          $total_ep=$total_ep-10;
          $flag=$flag+1;
          array_push($errors, "<u>Έχετε συμπληρώσει τα  10 μαθήματα κατεύθυνσης Τηλεπικοινωνιών </u>");
          $sql = "DELETE FROM `selections`
          WHERE category='ΕΚ-Τ' or category='ΕΚ-ΠΤ' LIMIT 10";
          $result = mysqli_query($db, $sql);
        }else{
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα  10 μαθήματα κατεύθυνσης Τηλεπικοινωνιών χρειάζεστε ακόμα " . (10 -  $total_ep) . "</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2011`
          WHERE id NOT IN (SELECT id FROM `selections` ) AND category='ΕΚ-Τ' or category='ΕΚ-ΠΤ' ";
           $result = mysqli_query($db, $sql);
           if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");

            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name'] ;
              array_push($errors, "" . $string);
            }
          }

        }

        if($flag==2){
          $calcualte_total=$count_ep_p +$total_ep;
        }else{
          $calcualte_total=$count_ep_p ;
        }
        $ep_total=$calcualte_total;
        
        
      

        if($count_el+$count_el_p>6){
          $calcualte_total=$calcualte_total+6;
        }else{
          $calcualte_total= $calcualte_total+$count_el+$count_el_p;
        }

        if($calcualte_total>=9 && $flag==2 ){
          
          $flag=$flag+1;
        } 
        else {
        array_push($errors, "<u>Δεν έχετε συμπληρώσει με επιτυχια τα προαπαιτούμενα μαθήματα  σας υπολείπονται  " . (9 - $calcualte_total) . " μαθήματα</u>");
        array_push($errors, "Έχετε σημειώσει " . $ep_total . " επιλογής κατεύθυνσης  ");
        array_push($errors, "Έχετε σημειώσει " . $count_el . " ελεύθερης επιλογής ");
        array_push($errors, "Έχετε σημειώσει " . $count_el_p . " κύκλου παιδαγωγικής και διδακτικής ");
        array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα :");
   
        array_push($errors, "<u> Σε 10 μαθήματα Κορμού ή κατεύθυνσης Τηλεπικοινωνιών ή κατεύθυνσης Πληροφορικής ή ελεύθερης επιλογής ή κύκλου παιδαγωγικής και διδακτικής </u>");
        array_push($errors, "<u>Το πολύ 6 μαθήματα ελεύθερης επιλογής ή κύκλου παιδαγωγικής και διδακτικής</u>");
        array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα απο:");
        $sql = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών`
                WHERE name NOT IN (SELECT fname FROM `selections` ) AND category != 'Κ' ";

        $result = mysqli_query($db, $sql);
        while ($courses = mysqli_fetch_assoc($result)) {
          $string = $courses['name'] . "(" . $courses['category'] . ")";
          array_push($errors, "" . $string);
        }
      }
      if($flag==3){
        array_push($errors, "<u>Έχετε συμπληρώσει με επιτυχία τα μαθήματα </u>");
        array_push($errors, "<h1>Επικοινωνήστε με την γραμματεία μπορείτε να πραγματοποιήσετε περάτωση σπουδών </h1>");
      }
      }

      
      if ($_SESSION['year'] == '2012') {
        $total_ects = "0";
        $count_k = "0";
        $count_ep_p = "0";
        $count_ep_t = "0";
        $count_ep_pt = "0";
        $count_el = "0";
        $count_el_p = "0";



        for ($j = 0; $j < $i; $j++) {
          $id = $myboxes[$j];


          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2012`
                  WHERE id='$id' LIMIT 1 ";
          $result = mysqli_query($db, $sql);
          $course = mysqli_fetch_assoc($result);



          $tmp_category = $course["category"];
          $tmp_name = $course["name"];
          $sql2 = "INSERT INTO selections (id,category,fname)
                VALUES ('$id','$tmp_category','$tmp_name')";
          if (mysqli_query($db, $sql2)) {
          } else {

            echo "Error-- ";
          }




          if ($course["category"] == 'Κ') {
            $count_k = $count_k + 1;
            $total_ects = $total_ects + $course["ects"];
          }
          if ($course["category"] == 'ΕΚ-Π') {
            $count_ep_p = $count_ep_p + 1;
            $total_ects = $total_ects +  $course["ects"];
          }

          if ($course["category"] == 'ΕΚ-Τ') {
            $count_ep_t = $count_ep_t + 1;
            $total_ects = $total_ects + $course["ects"];
          }

          if ($course["category"] == 'ΕΚ-ΠΤ') {
            $count_ep_pt = $count_ep_pt + 1;
            $total_ects = $total_ects + $course["ects"];
          }
          if ($course["category"] == 'ΕΕ') {
            $count_el = $count_el + 1;
            $total_ects = $total_ects +  $course["ects"];
          }
          if ($course["category"] == 'ΕΕ-Π') {
            $count_el_p = $count_el_p + 1;
            $total_ects = $total_ects +  $course["ects"];
          }
        }

        if ($count_k == 26) {
          array_push($errors, "<u>Έχετε συμπληρώσει τα 26 μαθήματα κορμού</u>");
          $flag=$flag+1;
        } else {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα 26 μαθήματα κορμού χρειάζεστε ακόμα " . (26 - $count_k) . "</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2012`
                    WHERE id NOT IN (SELECT id FROM `selections` ) AND category='Κ' ";

          $result = mysqli_query($db, $sql);
          if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");

            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name']  . " ,το οποίο αντιστοιχεί με:<br> " . $courses['correspondence'];
              array_push($errors, "" . $string);
            }
          }
        }

         
        $total_ep=$count_ep_t + $count_ep_pt; 
        if($total_ep >= 9){
          $total_ep=$total_ep-9;
          $flag=$flag+1;
          array_push($errors, "<u>Έχετε συμπληρώσει τα  9 μαθήματα κατεύθυνσης Τηλεπικοινωνιών </u>");
          $sql = "DELETE FROM `selections`
          WHERE category='ΕΚ-Τ' or category='ΕΚ-ΠΤ'  LIMIT 6";
          $result = mysqli_query($db, $sql);
        }else{
          array_push($errors, "<u>Δεν έχετε συμπληρώσει τα  9 μαθήματα κατεύθυνσης Τηλεπικοινωνιών χρειάζεστε ακόμα " . (9 - $total_ep) . "</u>");
          $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2012`
          WHERE id NOT IN (SELECT id FROM `selections` ) AND category='ΕΚ-Τ'  or category='ΕΚ-ΠΤ' ";
           $result = mysqli_query($db, $sql);
           if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");

            while ($courses = mysqli_fetch_assoc($result)) {
              $string = $courses['name'] ;
              array_push($errors, "" . $string);
            }
          }

        }


        if($flag==2){
          $calcualte_total=$count_ep_p +$total_ep;
        }else{
          $calcualte_total=$count_ep_p ;
        }
        $ep_total=$calcualte_total;


        if($count_el+$count_el_p>6){
          $calcualte_total=$calcualte_total+6;
        }else{
          $calcualte_total= $calcualte_total+$count_el+$count_el_p;
        }

        if($calcualte_total>=9  ){
          
          $flag=$flag+1;
        } 
          else {
        array_push($errors, "<u>Δεν έχετε συμπληρώσει με επιτυχια τα προαπαιτούμενα μαθήματα  σας υπολείπονται  " . (9 - $calcualte_total) . " μαθήματα</u>");
        array_push($errors, "Έχετε σημειώσει " . $ep_total . " επιλογής κατεύθυνσης  ");
        array_push($errors, "Έχετε σημειώσει " . $count_el . " ελεύθερης επιλογής ");
        array_push($errors, "Έχετε σημειώσει " . $count_el_p . " κύκλου παιδαγωγικής και διδακτικής ");
        array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα :");
   
        array_push($errors, "<u> Σε 10 μαθήματα Κορμού ή κατεύθυνσης Τηλεπικοινωνιών ή κατεύθυνσης Πληροφορικής ή ελεύθερης επιλογής ή κύκλου παιδαγωγικής και διδακτικής </u>");
        array_push($errors, "<u>Το πολύ 6 μαθήματα ελεύθερης επιλογής ή κύκλου παιδαγωγικής και διδακτικής</u>");
        array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα απο:");
        $sql = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών`
                WHERE name NOT IN (SELECT fname FROM `selections` ) AND category != 'Κ' ";

        $result = mysqli_query($db, $sql);
        while ($courses = mysqli_fetch_assoc($result)) {
          $string = $courses['name'] . "(" . $courses['category'] . ")";
          array_push($errors, "" . $string);
        }
      }
      if($flag==3){
        array_push($errors, "<u>Έχετε συμπληρώσει με επιτυχία τα μαθήματα </u>");
        array_push($errors, "<h1>Επικοινωνήστε με την γραμματεία μπορείτε να πραγματοποιήσετε περάτωση σπουδών </h1>");
      }





      }
    }

    if ($_SESSION['departments'] == 'ΠΤ') {
      $total_ects = "0";
      $count_k = "0";
      $count_ep = "0";
      $count_bk = "0";
      $count_el = "0";
      $count_el_p = "0";
      for ($j = 0; $j < $i; $j++) {
        $id = $myboxes[$j];


        $sql = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών`
                WHERE id='$id' LIMIT 1 ";
        $result = mysqli_query($db, $sql);
        $course = mysqli_fetch_assoc($result);



        $tmp_category = $course["category"];
        $tmp_name = $course["name"];
        $sql2 = "INSERT INTO selections (id,category,fname)
              VALUES ('$id','$tmp_category','$tmp_name')";
        if (mysqli_query($db, $sql2)) {
        } else {

          echo "Error-- ";
        }




        if ($course["category"] == 'Κ') {
          $count_k = $count_k + 1;
          $total_ects = $total_ects + $course["ects"];
        }
        if ($course["category"] == 'ΕΚ-Π' || $course["category"] == 'ΕΚ-Τ' || $course["category"] == 'ΕΚ-ΠΤ') {
          $count_ep = $count_ep + 1;
          $total_ects = $total_ects +  $course["ects"];
        }
        if ($course["category"] == 'ΒΚ-Π' || $course["category"] == 'ΒΚ-Τ') {
          $count_bk = $count_bk + 1;
          $total_ects = $total_ects +  $course["ects"];
        }
        if ($course["category"] == 'ΕΕ') {
          $count_el = $count_el + 1;
          $total_ects = $total_ects +  $course["ects"];
        }
        if ($course["category"] == 'ΠΔ') {
          $count_el_p = $count_el_p + 1;
          $total_ects = $total_ects +  $course["ects"];
        }
      }

      if ($count_k == 22) {
        array_push($errors, "<u>Έχετε συμπληρώσει τα 22 μαθήματα κορμού</u>");
        $sql = "DELETE FROM `selections`
        WHERE category='Κ' LIMIT 27";
        $result = mysqli_query($db, $sql);
      } else {
        array_push($errors, "<u>Δεν έχετε συμπληρώσει τα 22 μαθήματα κορμού χρειάζεστε ακόμα " . (22 - $count_k) . "</u>");
        $sql = "SELECT * FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2012`
                  WHERE id NOT IN (SELECT id FROM `selections` ) AND category='Κ' ";

        $result = mysqli_query($db, $sql);
        if (mysqli_num_rows($result) > 0) {
          array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα:  ");

          while ($courses = mysqli_fetch_assoc($result)) {
            $string = $courses['name'];
            array_push($errors, "" . $string);
          }
        }
      }

      $cert_flag = "0";
      if ($count_bk >= 4) {
        $count_bk = $count_bk - 4;
        if ($count_ep + $count_bk >= 13) {
          $cert_flag = 1;
        }
        $count_bk = $count_bk + 4;
      }



      if ($total_ects >= 240 && $cert_flag == 1) {
        array_push($errors, "<u>Έχετε συμπληρώσει με επιτυχία  μαθήματα συνολικού βάρους τουλάχιστον 240 μονάδων ECTS</u>");
        array_push($errors, "<h1>Επικοινωνήστε με την γραμματεία μπορείτε να πραγματοποιήσετε περάτωση σπουδών </h1>");
      } else {

        if ($cert_flag == 1) {
          array_push($errors, "<u> Έχετε συμπληρώσει με επιτυχια τα απαραίτητα μαθήματα , παρ' όλα αυτά δεν έχετε συμπληρώσει το ποσό των   240 μονάδων ECTS σας υπολείπονται  " . (240 - $total_ects) . " ECTS</u>");
        } elseif ($cert_flag == 0) {
          array_push($errors, "<u>Δεν έχετε συμπληρώσει με επιτυχια  μαθήματα συνολικού βάρους τουλάχιστον 240 μονάδων ECTS σας υπολείπονται  " . (240 - $total_ects) . " ECTS</u>");
          array_push($errors, "Έχετε σημειώσει " . $count_ep . " μαθήματα επιλογής κατευθύνσης(ΕΚ-Π,ΕΚ-Τ,ΕΚ-ΠΤ) ");
          array_push($errors, "Έχετε σημειώσει " . $count_bk . " μαθήματα βασικής κατευθύνσης(ΒΚ-Π,ΒΚ-Τ)");
          array_push($errors, "Έχετε σημειώσει " . $count_el . " μαθήματα ελεύθερης επιλογής(ΕΕ) ");
          array_push($errors, "Έχετε σημειώσει " . $count_el_p . " μαθήματα παιδαγωγικού κύκλου και διδακτικής(ΠΔ) ");
          array_push($errors, "Πρέπει να επιλέξετε :");
          array_push($errors, "<u> Τουλάχιστον 4 από τα βασικά μαθήματα κατευθύνσεων (ΒΚ) </u>");
          array_push($errors, "<u> Τουλάχιστον 13 ακόμη μαθήματα κατευθύνσεων, βασικά ή επιλογής (ΒΚ ή ΕΚ). </u>");
          array_push($errors, "<u> Το πολύ 2 από τα μαθήματα ελεύθερης επιλογής (ΕΕ) ή κύκλου παιδαγωγικής και διδακτικής (ΠΔ).</u>");
        }

        array_push($errors, "Μπορείτε να επιλέξετε ανάμεσα απο:");
        $sql = "SELECT * FROM `πληροφορική και  τηλεπικοινωνιών`
                WHERE name NOT IN (SELECT fname FROM `selections` ) ";

        $result = mysqli_query($db, $sql);
        while ($courses = mysqli_fetch_assoc($result)) {
          $string = $courses['name'] . "(" . $courses['category'] . ")";
          array_push($errors, "" . $string);
        }
      }
    }
  }
}

if (isset($_POST['login_user'])) {

  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password=mysqli_real_escape_string($db, $_POST['password']);

  if (empty($password)) {
      array_push($errors, "Password is required");
    }
  if (empty($username)) {
    array_push($errors, "Username is required");
  }
 
  
  if (count($errors) == 0) {
    $password = md5($password);
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $results = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($results);
 
    if (mysqli_num_rows($results) == 1) {
      $_SESSION['username'] = $username;
      header('location:index_user.php'); 

    }else {
      array_push($errors, "Wrong username/password combination");
    }
  }
}

if (isset($_GET['delete'])) {
  if (!isset($_SESSION['username']) || $_SESSION['username'] == ''){
    header('location:index.php'); 
  }else{

    $id = $_GET['delete'];

    

    if ($_SESSION['department'] == 'ΠΤ') {
    
   
      $sql = "DELETE FROM `πληροφορική και  τηλεπικοινωνιών` WHERE id='$id' ;";
    
      
    } elseif ($_SESSION['department'] == 'ETY') {
        
    
      if ($_SESSION['year'] == '2002' || $_SESSION['year'] == '2003') {
        
        $sql = "DELETE   FROM `επιστήμης και τεχνολογίας _υπολογιστών_2002_2003` WHERE id='$id'";
    
    
      } elseif ($_SESSION['year'] == '2004' || $_SESSION['year'] == '2005' || $_SESSION['year'] == '2006') {
        $sql = "DELETE   FROM `επιστήμης και τεχνολογίας _υπολογιστών_2004__2005__2006` WHERE id='$id'";
       
    
    
      } elseif ($_SESSION['year'] == '2007' || $_SESSION['year'] == '2008' || $_SESSION['year'] == '2009' || $_SESSION['year'] == '2010' || $_SESSION['year'] == '2011') {
        $sql = "DELETE   FROM `επιστήμης και τεχνολογίας _υπολογιστών_2007_2011` WHERE id='$id'";
        
      } else { //Μετά το 2012
        $sql = "DELETE   FROM `επιστήμης και τεχνολογίας _υπολογιστών_2012` WHERE id='$id'";
       
      }
    } else {
      if ($_SESSION['year'] < 2009) {
        $sql = "DELETE   FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009` WHERE id='$id'";
       
      }
      if ($_SESSION['year'] == '2009' || $_SESSION['year'] == '2010') {
        $sql = "DELETE   FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2009_2010` WHERE id='$id'";
       
      }
      if ($_SESSION['year'] == '2011') {
        $sql = "DELETE  FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2011` WHERE id='$id'";
       
      }
      if ($_SESSION['year'] == '2012') {
        $sql = "DELETE  FROM `επιστήμης και τεχνολογίας _τηλεπικοινωνιών_2012` WHERE id='$id'";
       
      }
    }

    $result = mysqli_query($db, $sql);
    
  }
}


if (isset($_GET['edit'])) {
  if (!isset($_SESSION['username']) || $_SESSION['username'] == ''){
    header('location:index.php'); 
  }else{
    $id = $_GET['edit'];
    $_SESSION['editid']=$id;
    header('location:edit.php'); 
  }

}

if(isset($_GET['new'])) {
  if (!isset($_SESSION['username']) || $_SESSION['username'] == ''){
    header('location:index.php'); 
  }else{
    
    $_SESSION['editid']=-1;
    header('location:edit.php'); 
  }
  
}