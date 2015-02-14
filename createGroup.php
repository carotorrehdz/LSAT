<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('teacher');

?>

<!doctype html>
<html class="no-js" lang="en">
<head>
  <title>LSAT | New Group</title>
  <?php include 'includes/templates/headTags.php' ?>
</head>

<body>

  <?php include 'includes/templates/header.php' ?>

  <section class="scroll-container" role="main">

    <div class="row">
      <?php include 'includes/templates/teacherSidebar.php' ?>  
      <div class="large-9 medium-8 columns">
        <h3>Group</h3>
        <h4 class="subheader">Create new group</h4>
        <hr>  

        <form> 
          <div class="row"> 
            <div class="large-4 columns"> 
            <label>Group name <input id="groupname" type="text" placeholder="TC-0001" /> </label> 
            </div>
          </div>
          <div class="row"> 
            <div class="large-12 columns"> 
              <label>Students<input id="students" type="text" placeholder="Comma sepparated list of the student id numbers" /> </label> 
            </div> 
          </div>  
          <a href="#" onclick="createGroup()" class="button round small right">Create</a>
        </form>

      </div>
    </div>
  </section>


  <?php include 'includes/templates/footer.php' ?>


  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation.min.js"></script>
  <script>
    $(document).foundation();

    function createGroup(){
      var groupname  = $("#groupname").val();
      var students   = $("#students").val();

      $.post( "controls/doAction.php", { action:"createGroup", groupname: groupname, students: students})
      .done(function( data ) {

        data = JSON.parse(data);
        if(data.message == 'success'){
          alert("El grupo fue creado exitosamente");
          window.location.reload();

        }else{
          alert("There was an error: " + data.message);
        }

      });
    }

  </script>
</body>
</html>
