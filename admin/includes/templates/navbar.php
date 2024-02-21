<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="dashboard.php"><?php echo "Home" ?></a>
    </div>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav">
        <li><a href="categories.php"><?php echo "categories" ?></a></li>
        <li><a href="items.php"><?php echo "items" ?></a></li>
        <li><a href="members.php"><?php echo "members" ?></a></li>
        <li><a href="comments.php"><?php echo "comments" ?></a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">root <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="../index.php">visit shop</a></li>
            <li><a href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">edit profile</a></li>
            <li><a href="#">settings</a></li>
            <li><a href="logout.php">logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>