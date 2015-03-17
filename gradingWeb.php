<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('teacher');

$web = new Web();
$webId =  Input::get("web");
$webName = $web->getWeb($webId);

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
  <title>LSAT | Redes</title>
  <?php include 'includes/templates/headTags.php' ?>
</head>

<body>

  <?php include 'includes/templates/header.php' ?>

  <section class="scroll-container" role="main">

    <div class="row">

    <?php include 'includes/templates/teacherSidebar.php' ?>
      <div class="large-9 medium-8 columns">

        <h3>
          <?php echo $webName->name; ?>
        </h3>

<!-- WAAA-->
        <div class="">
          <?php
            foreach($levels as $level) {
              echo "<div class='webExplorerLevel'><h6 class='panel-title'>Nivel $level</h6> <div class='body'><ol>";
              foreach($questions as $question) {
                if ($level == $questionsByLevel[$question->id]){
                  echo "<li> <span>$question->text</span> <ul id='answersForQuestion'>";
                  $answersForQuestion = $answers[$question->id];
                  foreach($answersForQuestion as $a){
                    $text = $a[0]->text;
                    $answerId = $a[0]->id;
                    if ($a[0]->correct == 1){
                      echo "<li> <label class='label'>Correcta</label> <span> $text </span> </li>";
                    } else {
                      echo "<li> <input class='answer' name='$question->id-$answerId' type='text'/> <span>$text </span> </li>";
                    }
                  }
                  echo "</ul> </li>";
                }
              }
              echo "</ol></div></div>";
            }

          ?>
        </div>

        <a href="#" onclick="gradeWeb()" class="button round small right">Guardar</a>
     </div>
   </div>
 </section>

<?php include 'includes/templates/footer.php' ?>


<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script>
  $(document).foundation();

  function gradeWeb() {
    var answers = $(".answer");
    var len = answers.length;
    var data = {};

    for(var i=0; i<len; i++){
      var item = $(answers[i]);
      var name = item.attr('name').split('-');
      var q = name[0];
      var a = name[1];
      var p =  item.val();
      data[q] = [a,p];

    }
    console.log(data);
  }

</script>
</body>
</html>
