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
          <?php echo $webName[0]->name; ?>
        </h3>

        <div class="panel panel-info">
          <?php
            foreach($levels as $level) {
              echo "<div class='webExplorerLevel'><div class='panel-heading'><h4 class='panel-title'>Nivel $level</h4></div> <div class='panel-body'><ol>";
              foreach($questions as $question) {
                if ($level == $questionsByLevel[$question->id]){
                  echo "<li>$question->text</li><ul id='answersForQuestion'>";
                  $answersForQuestion = $answers[$question->id];
                  foreach($answersForQuestion as $a){
                    $text = $a[0]->text;
                    if ($a[0]->correct == 1){
                      echo "<label class='label'>Correcta</label><li>$text</li>";
                    } else {
                      echo "<input type='text' /><li>$text</li>";
                    }
                  }
                  echo "</ul>";
                }
              }
              echo "</ol></div></div>";
            }

          ?>
        </div>

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
