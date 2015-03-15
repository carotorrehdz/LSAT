<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('teacher');

$web = new Web();
$webId =  Input::get("web");
$webName = $web->getWebName($webId);

$levels = $web->getLevelsInWeb($webId);
$questionsByLevel = $web->getQuestionsInWeb($webId);

$questionsIds = $web->getQuestionsIds($webId);
$question = new Question();
$questions = $question->getQuestions($questionsIds);

$answer = new Answer();
$answers = $answer->getAnswersForQuestionList($questions);

?>

<!doctype html>
<html class="no-js" lang="en">
<head>
  <title>LSAT | Webs</title>
  <?php include 'includes/templates/headTags.php' ?>
</head>

<body>

  <?php include 'includes/templates/header.php' ?>

  <section class="scroll-container" role="main">

    <div class="row">
    <?php include 'includes/templates/teacherSidebar.php' ?>
      <div class="large-9 medium-8 columns">
        <h3>
          <?php
            echo $webName[0]->name;
          ?>
        </h3>

          <?php
            foreach($levels as $level) {
              echo "<div><h4>Nivel $level</h4>";
              foreach($questions as $question) {
                if ($level == $questionsByLevel[$question->id]){
                  echo "<h5>$question->text</h5>";
                  $answersForQuestion = $answers[$question->id];
                  foreach($answersForQuestion as $a){
                    if ($a[0]->correct == 1){
                      $text = $a[0]->text;
                      echo "<label>Correcta $text </label>";
                    } else {
                      $text = $a[0]->text;
                      echo "<div class='large-4 columns'> $text <input type='text' /></div>";
                    }
                  }
                }


              }
              echo "</div>";
            }

          ?>

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
