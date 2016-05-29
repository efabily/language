<?php
session_start();
include_once('config/functions.php');

$GLOBALS = array();
$score = 0;

if($_SERVER['REQUEST_METHOD'] === 'POST')
{

  $post = $_POST;

  $phrase = $post['phrase'];
  $type = $post['question_type'];
  $id = $post['question_id'];
  $translationID = $post['translation_id'];

  $exerciseId = insertPhraseExercise($post, $GLOBALS, $link);
  if($exerciseId)
  {
    if(isset($GLOBALS['post_error']))
      $_SESSION['post_error'] = $GLOBALS['post_error'];
    
    if(isset($GLOBALS['post_message']))
      $_SESSION['message'] = $GLOBALS['post_message'];

    header('Location:'.SITE_URL.'answer.php?id='.$exerciseId); exit;
  }
}
else
{    
    if(isset($_SESSION['phrases']))
    {      
      $rowset = $_SESSION['phrases'];
    }
    else
    {      
      $rowset = getPhraseToday();
    }    

    if(count($rowset) > 0)
    {
        $index = array_rand($rowset);
        $row = $rowset[$index];
        unset($rowset[$index]);
      
        if(count($rowset) > 0)
        {
          $_SESSION['phrases'] = null;
          $_SESSION['phrases'] = $rowset;
        }
        else
        {
          $_SESSION['phrases'] = null;
          unset($_SESSION['phrases']);
        }
    }

    if(isset($row->type) && !empty($row->type))
    {  
      $type = $row->type;
      $id = $row->phrase_id;

      if($type == 'phrase_spanish'):
          $row = getPhraseSpanish($id);
          $phrase = isset($row->spanish) ? $row->spanish : '';
      elseif($type == 'phrase_english'):
          $row = getPhraseEnglish($id);
          $phrase = isset($row->english) ? $row->english : '';
      endif;
    }
    else
    {
      $type = 'phrase_spanish';
      $id = $row->id_spanish;

      $phrase = isset($row->spanish) ? $row->spanish : '';
    }

    $translationID = $row->translation_id;
}
?>
<!DOCTYPE html>
 
<html lang="es"> 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    
    <link rel="icon" href="../../favicon.ico">

    <title>Theme Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo SITE_URL?>css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap theme -->
    <link href="<?php echo SITE_URL?>css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?php echo SITE_URL?>css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo SITE_URL?>css/theme.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <script src="<?php echo SITE_URL?>js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  </head>
 
  <body>    
      <?php include_once('nav.php');?>
      <div class="container">

      <br />
      <?php include_once('phrase-nav.php');?>

        <form class="form-horizontal" role="form" action=" " method="post" autocomplete="off" >

          <div class="jumbotron">
            <h2><?php echo '" '.$phrase.' "';?></h2>

            <input type="hidden" name="phrase" value="<?php echo $phrase;?>" />
            <input type="hidden" name="translation_id" value="<?php echo $translationID;?>" />
            <input type="hidden" name="question_type" value="<?php echo $type;?>" />
            <input type="hidden" name="question_id" value="<?php echo $id;?>" />
            
          </div>
          
          <?php if(isset($GLOBALS['post_error']) && $GLOBALS['post_error']):?>
              <?php if(isset($GLOBALS['post_message']) && $GLOBALS['post_message'] != ''):?>
            <div class="alert alert-danger" role="alert">
              <strong>Oh Noo!</strong> <?php echo $GLOBALS['post_message'];?>
            </div>
            <?php endif;?>
          <?php else:?>
              <?php if(isset($GLOBALS['post_message']) && $GLOBALS['post_message'] != ''):?>
            <div class="alert alert-success" role="alert">
               <strong>Excelente!</strong> <?php echo $GLOBALS['post_message'];?>
              </div>
            <?php endif;?>
          <?php endif;?>
        
        <div class="form-group">          
          <div class="col-lg-12">
            <input type="text" class="form-control" id="answer" name="answer" placeholder="Escribe la oración citada en la parte superior en ingles" value="<?php echo isset($answer) ? $answer : '';?>" >
          </div>        
        </div>

        <div class="form-group">
          <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-lg btn-success">Probar</button>            
          </div>
        </div>
        
      </form>

      </div>
      
      <footer>
          Fabiola Espinoza Gomez
      </footer>
  </body>
</html>