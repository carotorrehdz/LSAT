<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('admin');

?>

<!doctype html>
<html class="no-js" lang="en">
<head>
  <title>LSAT | Dashboard</title>
  <?php include 'includes/templates/headTags.php' ?>
</head>

<body>

  <?php include 'includes/templates/header.php' ?>

  <section class="scroll-container" role="main">

    <div class="row">
    <?php include 'includes/templates/adminSidebar.php' ?>  
      <div class="large-9 medium-8 columns">
        <h3>Register new teacher</h3>
        <h4 class="subheader"> Here you can register a new teacher. Watch out for duplicates.</h4>
        <hr>  
        <div id="">
         Name:
         <input id="username" type="text">
         Mail:
         <input id="mail" type="text">
         Id number:
         <input id="idnumber" type="text">
         <a href="#" onclick="registerTeacher()" class="button">Register</a>
       </div>

     </div>
   </div>
 </section>


<?php include 'includes/templates/footer.php' ?>


<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script>
  $(document).foundation();

  function registerTeacher(){
    var username  = $("#username").val();
    var mail      = $("#mail").val();
    var idnumber  = $("#idnumber").val();

    $.post( "controls/doAction.php", { action:"registerTeacher", username: username, mail: mail, idnumber: idnumber })
    .done(function( data ) {
      console.log(data);
      data = JSON.parse(data);
      if(data.message == 'success'){
        alert("The teacher was registered");
        window.location.reload();
      }else{
        alert("There was an error: " + data.message);
      }

    });
  }


</script>
</body>
</html>
