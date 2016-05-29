<?php
include_once('config/functions.php');
$GLOBALS = array();

/*
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1)
  $page = 1;

$paginatorItemsPerPage = 5;

$search = isset($_GET['s']) ? $_GET['s'] : '';

if(!empty($search))
  $paginator = get_paginator($page, $paginatorItemsPerPage, $search);
else
  $paginator = get_paginator($page);

$rowset = $paginator->get_rowset();
*/

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

  $post = $_POST;

  if(isset($post['jqPhrase']))
    unset($post['jqPhrase']);

  $data = array();

  foreach ($post as $key => $value)
  {
    if(substr($key, 0, 7) == 'phrase_')
    {
        $type = '';
        $explodeVualue = explode('-',$value);
        $phraseId = $explodeVualue[0];
        $lg = $explodeVualue[1];

        if($lg == 'es')
          $type = 'phrase_spanish';
        elseif($lg == 'en')
          $type = 'phrase_english';

        $data[] = array('phrase_id' => $phraseId, 'type' => $type);
    }
  }


  session_start();
  $_SESSION['phrases'] = null;  
  unset($_SESSION['phrases']);  
  

  if(insertPhraseSelected($data, $GLOBALS, $link))
    header('Location:'.SITE_URL.'phrase-selected.php');
  else
    header('Location:'.SITE_URL.'phrase.php');
}

$rowset = getPhraseSelectedToday();

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


    <script src="<?php echo SITE_URL?>js/jquery.js"></script>

    <script src="<?php echo SITE_URL?>js/bootstrap.js"></script>


    <script src="<?php echo SITE_URL?>js/bootstrap-tooltip.js"></script>

    <script src="<?php echo SITE_URL?>js/bootstrap-confirmation.js"></script>

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <script src="<?php echo SITE_URL?>js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  </head>
 
  <body>    
      <?php include_once('nav.php');?>
      <div class="container">
      <br />
      <?php include_once('phrase-nav.php');?>

      <form action=" " method="post" >

      <table class="table table-bordered table-striped">
          <thead>
              <tr>
                  <th style="width: 10px;" >#</th>                  
                  <th>Frases</th>
                  <th></th>
              </tr>
          </thead>
          <tbody>
             <?php foreach ($rowset as $key => $row):?>

              <?php if($row->type == 'phrase_spanish'):?>
                <?php $rowFrase = getPhraseSpanish($row->phrase_id);?>
              <?php elseif($row->type == 'phrase_english'): ?>
                  <?php $rowFrase = getPhraseEnglish($row->phrase_id);?>
              <?php endif; ?>

              <tr>
                  <td><?php echo ($key + 1);?></td>                 
                  <td>
                    <h5><b>Es:</b> <?php echo $rowFrase->spanish;?></h5>
                    <h5><b>En:</b> <?php echo $rowFrase->english;?></h5>
                  </td>
                  <td>
                    <a href=""  data-toggle="confirmation"  data-href="<?php echo SITE_URL.'phrase-delete.php?id='.$row->id.'&s=1';?>" >
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </a>                    
                  </td>
              </tr>
             <?php endforeach;?>
          </tbody>
          <tfoot>
            <tr> 
              <td colspan="3"> 
              
              </td>              
            </tr>
          </tfoot>
      </table>
           
           </form>

        

      </div>
      
      <footer>
          Fabiola Espinoza Gomez
      </footer>
      <script>
      jQuery(function(){
        $('[data-toggle="confirmation"]').confirmation({
            singleton:true,
            title:'Please confirm that you want to deleted the selected record.'
        });
      });
      </script>
  </body>
</html>