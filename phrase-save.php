<?php
include_once ('config/functions.php');

$id = isset($_GET['id']) ? $_GET['id'] : '';

$spanish = "";
$english = "";

$post = array();
$GLOBALS = array();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$post = $_POST;

	if('form_phrase' == $post['option'])
	{
		$spanish = $post['spanish'];
		$english = $post['english'];


		if(isset($post['id']) && !empty($post['id']))
			updatePhrase($post, $GLOBALS, $link);
		else
			insertPhrase($post, $GLOBALS, $link);


		if(!isset($GLOBALS['post_error']))
		{
			session_start();
			$_SESSION['message'] = $GLOBALS['post_message'];

			if(!empty($id))
				header('Location:'.SITE_URL.'phrase-save.php?id='.$id);
			else
				header('Location:'.SITE_URL.'phrase-save.php');

			exit;
		}
	}
}
else 
{
	session_start();
	if(isset($_SESSION['message']))
	{
		$GLOBALS['post_message'] = $_SESSION['message'];
		unset($_SESSION['message']);
	}
}

if(!empty($id))
{
	$row = getPhrase($id);
	if(count($row) > 1)	
	{
		$id = $row['translation_id'];
		$english = $row['english'];
		$spanish = $row['spanish'];
	}
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

  </head>
 
<body>
    <?php include_once('nav.php');?>    
    <div class="container">

    <br />
    <?php include_once('phrase-nav.php');?>

    	<div class="jumbotron">
	    	<h1>Frases u Oración</h1>
	        <p>En el siguiente formulario una frase u oracion completa</p>
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
	    
	    <form class="form-horizontal" role="form" action="" method="post" >

	      <?php if(!empty($id)):?>
	      	<input type="hidden" name="id" value="<?php echo $id;?>" />
	  	  <?php endif;?>
	    
		  <div class="form-group">
		    <label for="spanish" class="col-lg-2 control-label">Spanish * </label>
		    <div class="col-lg-10">
		      <input type="text" class="form-control" id="spanish" placeholder="Spanish" name="spanish" value="<?php echo $spanish; ?>" />
		    </div>
		  </div>

		  <div class="form-group">
		    <label for="english" class="col-lg-2 control-label">English * </label>
		    <div class="col-lg-10">
		      <input type="text" class="form-control" id="english" placeholder="English" name="english" value="<?php echo $english; ?>" >
		    </div>
		  </div>

		  <div class="form-group">
		    <div class="col-lg-offset-2 col-lg-10">
		      <input type="hidden" name="option" value="form_phrase" />
		      <button type="submit" class="btn btn-lg btn-primary">Registrar</button>
		    </div>
		  </div>
		  
		</form>    

    </div>
    
    <footer>
        Fabiola Espinoza Gomez
    </footer>
</body>
</html>
