<?php
require 'core/init.php';

$user = new User();
$user->checkIsValidUser('teacher');
$teacherId = $user->data()->id;

//Id del grupo
$groupId = Input::get("g");
$g = new Groups();
$group = $g->getGroupById($groupId);
if ($group == false){
  Redirect::to("./groups.php");
}

?>

<!doctype html>
<html class="no-js" lang="en">
<head>
  <title>LSAT | Editar grupo</title>
  <?php include 'includes/templates/headTags.php' ?>
</head>

<body>

  <?php include 'includes/templates/header.php' ?>

  <section class="scroll-container" role="main">

    <div class="row">
      <?php include 'includes/templates/teacherSidebar.php' ?>  
      <div class="large-9 medium-8 columns">
        <h3>Editar grupo</h3>
        <hr>  
        <h5>Nombre del grupo</h5>
        <input id="name" type="text" value="<?php echo $group->name; ?>"> <br/>
        <a onclick="updateGroup()" class="button">Guardar cambios</a>

      </div>
    </div>
  </section>

  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation.min.js"></script>
  <script>
    $(document).foundation();
  </script>

  <script>

   function updateGroup(){
    var name  = $("#name").val();

    $.post( "controls/doAction.php", { action:"updateGroup", g:<?php echo $groupId; ?>, name: name })
    .done(function( data ) {

      data = JSON.parse(data);
      if(data.message == 'success'){
        window.location.replace('./groups.php');
      }else{
        alert("There was an error: " + data.message);
      }

    });
  }

</script>
</body>
</html>
