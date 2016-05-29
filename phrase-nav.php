<nav class="navbar navbar-default" >

  <div class="container-fluid">    

    <?php if($self == 'phrase.php'):?>
      <form class="navbar-form navbar-left" >
        <button type="button" class="btn btn-default" id="btnSelect" >Select to practice</button>  
      </form>
    <?php endif;?>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
  
      <?php if($self == 'phrase.php' || $self == 'phrase-selected.php'):?>
        <form class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search">
          </div>
          <button type="submit" class="btn btn-default">Submit</button>
        </form>
      <?php endif;?>

      <ul class="nav navbar-nav navbar-right">

        <?php if($self != 'phrase.php'):?>
        <li><a href="<?php echo SITE_URL.'phrase.php';?>" >Phrase</a></li>
        <?php endif;?>

        <?php if($self != 'phrase-selected.php'):?>
        <li><a href="<?php echo SITE_URL.'phrase-selected.php';?>" >Selected for today</a></li>
        <?php endif;?>

        
        <li>
          <a href="<?php echo SITE_URL.'index.php';?>" > To practice</a>
        </li>

        <?php if($self != 'phrase-save.php'):?>
          <li><a href="<?php echo SITE_URL.'phrase-save.php';?>" >Add Phrase</a></li>
        <?php endif;?>

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>