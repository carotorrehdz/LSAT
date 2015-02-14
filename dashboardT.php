<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('teacher');

$teachers = $user->getUsersByRole('teacher');

?>

<!doctype html>
<html class="no-js" lang="en">
<head>
  <title>LSAT | Dashboard</title>
  <?php include 'includes/templates/headTags.php' ?>
</head>

<body>

<?php include 'includes/templates/header.php' ?>

<br/><br/>

<div class="row">
  <div class="large-12 columns">
   <div class="panel">
     <h3>Dashboard for the teacher</h3>

     <h3>Register a new group</h3>

     <div id="">
     Group:
     <input id="groupname" type="text">
     Students:
     <input id="students" type="text">
     <p class="grey">Comma separated list of the id number of the students.</p>
     <a href="#" onclick="registerGroup()" class="button">Register group</a>
     </div>

 </div>
</div>
</div>



<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script>
  $(document).foundation();

  function registerGroup(){
    alert("Pending");
    return;
    var groupname  = $("#groupname").val();
    var students   = $("#students").val();

    $.post( "controls/doAction.php", { action:"registerGroup", groupname: groupname, students: students })
    .done(function( data ) {
      console.log(data);
      data = JSON.parse(data);
      if(data.message == 'success'){
        alert("The group was registered");
        window.location.reload();
      }else{
        alert("There was an error: " + data.message);
      }

  });
  }


</script>
</body>
</html>
