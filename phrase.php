<?php
include_once('config/functions.php');
$GLOBALS = array();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1)
  $page = 1;

$paginatorItemsPerPage = 2000;

$search = isset($_GET['s']) ? $_GET['s'] : '';

if(!empty($search))
  $paginator = get_paginator($page, $paginatorItemsPerPage, $search);
else
  $paginator = get_paginator($page, $paginatorItemsPerPage);


 // render_paginator($paginator);

 $rowset = $paginator->get_rowset();

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
    
  </head>
 
  <body>    
      <?php include_once('nav.php');?>
      <div class="container">

    <br />
    <?php include_once('phrase-nav.php');?>

<form action=" " method="post"  id="frmAction" >

      <table class="table table-bordered table-striped">
          <thead>
              <tr>
                  <th style="width: 10px;" >#</th>
                  <th style="width: 20px;" >
                    Es<input type="radio" id="jqEs"  name="jqPhrase"  >
                  </th>
                  <th style="width: 20px;" >
                    En<input type="radio" id="jqEn"  name="jqPhrase" >
                  </th>
                  <th>Frases</th>
                  <th style="width: 60px;" ></th>
              </tr>
          </thead>
          <tbody>
             <?php foreach ($rowset as $key => $rowFrase):?>
              <tr>
                  <td><?php echo ($key + 1);?></td>
                  <td>
                    <input type="radio" name="phrase_<?php echo $rowFrase->translation_id;?>" class="jqEs"   value="<?php echo $rowFrase->id_spanish.'-es';?>" >
                  </td>
                  <td>
                    <input type="radio" name="phrase_<?php echo $rowFrase->translation_id;?>" class="jqEn" value="<?php echo $rowFrase->id_english.'-en';?>" >
                  </td>
                  <td>
                    <h5><b>Es:</b> <?php echo $rowFrase->spanish;?></h5>
                    <h5><b>En:</b> <?php echo $rowFrase->english;?></h5>
                  </td>
                  <td>

                    <a href="<?php echo SITE_URL.'phrase-save.php?id='.$rowFrase->translation_id;?>" >
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    </a>

                    <a href="javascript:;" data-toggle="confirmation"  data-href="<?php echo SITE_URL.'phrase-delete.php?id='.$rowFrase->translation_id;?>" style="margin-left:10px;" >
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </a>                    

                  </td>
              </tr>
             <?php endforeach;?>
          </tbody>
          <tfoot>
            <tr> 
              <td colspan="5">

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
        $("#jqEs").click(function(){

          if($(this).is(':checked'))
          {              
              $('.jqEs').prop('checked', true);
          }
          else
          {              
              $('.jqEs').prop('checked', false);
          }

        });

        $("#jqEn").click(function(){

          if($(this).is(':checked'))
          {              
              $('.jqEn').prop('checked', true);
          }
          else
          {              
              $('.jqEn').prop('checked', false);
          }

        });

        $("#btnSelect").click(function(){
          var url = "<?php echo SITE_URL.'phrase-selected.php';?>";

          $("#frmAction").attr('action', url);

          $("#frmAction").submit();

        });

        $('[data-toggle="confirmation"]').confirmation({
            singleton:true,
            title:'Please confirm that you want to deleted the selected record.'
          });

      });

    </script>
  </body>
</html>