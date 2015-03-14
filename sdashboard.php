<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('student');
$studentId = $user->data()->id;

//$groups = new Groups();
//$teacherGroups = $groups->getGroupsForTeacher($teacherId);
//var_dump($teacherGroups);

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
    <?php include 'includes/templates/studentSidebar.php' ?>  
      <div class="large-9 medium-8 columns">
        <h3>Grupos</h3>
        <h4 class="subheader">Administracion de grupos</h4>
        <hr>  

        <table> 
         <thead> 
           <tr> 
             <th width="300">Grupo</th> 
             <th width="200">Periodo</th> 
             <th width="300">Editar</th> 
           </tr> 
         </thead>

         <tbody> 
           <?php
           /*
           foreach ($teacherGroups as $group) {

              echo "<tr id='$group->id'> 
                    <td> <a href='group.php?id=$group->id'> $group->name </a> </td>
                    <td> $group->term </td>
                    <td> <a onclick=\"editGroup($group->id);\" class='tiny button secondary'>Editar</a> </td> 
                    </tr>";
              }
*/
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

</script>
</body>
</html>
