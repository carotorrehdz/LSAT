<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('teacher');

?>

<!doctype html>
<html class="no-js" lang="en">
<head>
  <title>LSAT | New Question</title>
  <?php include 'includes/templates/headTags.php' ?>
</head>

<body>

  <?php include 'includes/templates/header.php' ?>

  <section class="scroll-container" role="main">

    <div class="row">
    <?php include 'includes/templates/teacherSidebar.php' ?>  
      <div class="large-9 medium-8 columns">
        <h3>Question</h3>
        <h4 class="subheader">Create new question</h4>
        <hr>  
        
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
