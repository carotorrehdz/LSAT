
<nav data-topbar="" class="top-bar">

  <!-- Title -->
  <ul class="title-area">
    <li class="name"><h1>
      <?php
      $page = "#";
      if($user->data()->role == "admin"){
        $page = 'registerTeacher.php';
      }
      else if($user->data()->role == "teacher"){
        $page = 'groups.php';
      }
      else if($user->data()->role == "student"){
        $page = 'dashboard.php';
      }
      echo "<a href='$page'>LSAT</a>";
      ?>
    </h1></li>
  </ul>

<!-- Top Bar Section -->

<section class="top-bar-section">

  <!-- Top Bar Right Nav Elements -->
  <ul class="right">
    <!-- Divider -->
    <li class="divider"></li>

    <!-- Dropdown -->
    <li class="has-dropdown not-click"><a><?php echo $user->data()->username; ?></a>
      <ul class="dropdown">
        <li class="title back js-generated"><h5><a href="javascript:void(0)">Back</a></h5></li>
        <li><a href="settings.php">Settings</a></li>
      </ul>
    </li>

    <li class="divider"></li>

    <!-- Button -->
    <li class="has-form show-for-small-up">
      <a class="button" href="logout.php">Log Out</a>
    </li>
  </ul>
</section>
</div>

</nav>


<div class="ajaxWaiting"> <img src='img/loader.gif'></div>
