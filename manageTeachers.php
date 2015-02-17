<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('admin');
$teachers = $user->getUsersByRole('teacher');
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
  <title>LSAT | Template</title>
  <?php include 'includes/templates/headTags.php' ?>
</head>

<body>

  <?php include 'includes/templates/header.php' ?>

  <section class="scroll-container" role="main">

    <div class="row">
      <?php include 'includes/templates/adminSidebar.php' ?>
      <div class="large-9 medium-8 columns">
        <h3>List of registered teachers</h3>
        <h4 class="subheader"> Here you can view and edit all the registered teachers</h4>
        <hr>

        <table> 
         <thead> 
           <tr> 
             <th width="300">Usename</th> 
             <th width="200">Mail</th> 
             <th width="200">Id number</th> 
             <th width="200">Registered Date</th> 
             <th width="300">Edit</th> 
           </tr> 
         </thead>

         <tbody> 
           <?php
           foreach ($teachers as $teacher) {

             echo "<tr id='$teacher->id'> 
             <td> $teacher->username </td>
             <td> $teacher->mail </td>
             <td> $teacher->idNumber </td>
             <td> $teacher->registeredDate </td> 
             <td> <a onclick=\"editTeacher($teacher->id);\" class='tiny button secondary'>Edit</a> </td> 
           </tr>";
         }

         ?>

       </tbody>
     </table>

   </div>
 </div>
</section>

<?php include 'includes/templates/footer.php' ?>


<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script>
  $(document).foundation();

  function X(){
    var xx  = $("#xx").val();

    $.post( "controls/doAction.php", { action:"xx", xx: xx })
    .done(function( data ) {
      console.log(data);
      data = JSON.parse(data);
      if(data.message == 'success'){
        alert("Success");
        window.location.reload();
      }else{
        alert("There was an error: " + data.message);
      }

    });
  }
</script>
</body>
</html>
