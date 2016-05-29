<?php include_once('config.php');
include_once('Zebra_Pagination.php');
include_once('render-paginator.php');

function insertPhraseExercise(&$post, &$globals, $link)
{
	$exerciseId = 0;

	$gls = array();

	$req = array('translation_id', 'question_type', 'question_id', 'answer');

	requiredFields($req, $post, $gls);

	if(isset($gls['post_error']) && $gls['post_error'])
	{
		$globals['post_message'] = 'Por favor rellene todos los campos requeridos.';
		$globals['post_error'] = true;
		return false;
	}
	else
	{

		$post = $_POST;

  		$translationId = trim($_POST['translation_id']);
  		$questionType = trim($_POST['question_type']);
  		$questionId = trim($_POST['question_id']);
  		$answer = trim($_POST['answer']);
  		$score = 0;

    	$rowPhrase = getPhrase($translationId);
    	if(count($rowPhrase) <= 0)
    	{
    		$globals['post_message'] = 'La frase no  existe.';
			$globals['post_error'] = true;
    		return false;
    	}


        if($questionType == 'phrase_spanish')
        {
        	$answerType = 'phrase_english';
            $answerId = $rowPhrase['id_english'];
            $answerPhrase = $rowPhrase['english'];
        }
        elseif($questionType == 'phrase_english')
        {
        	$answerType = 'phrase_spanish';
            $answerId = $rowPhrase['id_spanish'];
            $answerPhrase = $rowPhrase['spanish'];
        }

        
        if(strtolower($answer) === strtolower($answerPhrase))
        	$score = 1;

        $createdOn = date('Y-m-d H:m:s');

        $insert = "INSERT INTO `phrase_exercise` (`translation_id`, `question_type`, `question_id`, `answer_type`, `answer_id`, `answer`, `score`, `created_on`)
        VALUES ($translationId, '$questionType', $questionId, '$answerType', $answerId, '$answer', $score, '$createdOn')";

        $result = mysql_query($insert, $link) or die(mysql_error());
        $exerciseId = mysql_insert_id();

        if($score)
		{
			$globals['post_message'] = 'Respuesta correcta.';
		}
		else
		{
			$globals['post_message'] = 'Respuesta incorrecta.';
			$globals['post_error'] = true;
		} 
	}

	return $exerciseId;
}


function getPhraseExercise($id)
{
	$row = array();

	$sql = "SELECT * FROM phrase_exercise WHERE id = $id";

	$results = mysql_query($sql);
	if($results)	
		$row = mysql_fetch_assoc($results);

	return $row;
}

function getPhraseSelectedToday($phraseId = null, $type = null)
{
	$now = date('Y-m-d');

	if(!is_null($phraseId) && !is_null($type))
	{
		$sql = "SELECT COUNT(*) AS cn FROM `phrase_selected` WHERE `phrase_id` = $phraseId AND `type` = '$type' AND `date` = '$now'";
		$results = mysql_query ($sql);
		return mysql_fetch_assoc($results);
	}
	else
	{
		$rowset = array();
		$sql = "SELECT * FROM `phrase_selected` WHERE `date` = '$now'";
		$results = mysql_query($sql);
		if($results)
		{
			while ( $row = mysql_fetch_object ( $results ) )
			{
				$rowset[] = $row;
			}
		}
		return $rowset;
	}
}


function insertPhraseSelected(&$post, &$globals, $link)
{
    if(count($post) <= 0)
    {
    	$globals['post_message'] = 'Por favor rellene todos los campos requeridos.';
		$globals['post_error'] = true;

		return false;
    }

    # date
    $now = date('Y-m-d');

    foreach($post as $value)
    {
    	$phraseId = $value['phrase_id'];
    	$type = $value['type'];

    	$rowPhraseSelected = getPhraseSelectedToday($phraseId, $type);
		if(!isset($rowPhraseSelected['cn']) || $rowPhraseSelected['cn'] <= 0)
		{
			$insertPhraseSelected = 'INSERT INTO `phrase_selected` (`phrase_id`, `type`, `date`, `active`) 
			VALUES ("'.$phraseId.'", "'.$type.'", "'.$now.'", 1)';
			$result = mysql_query($insertPhraseSelected, $link) or die(mysql_error());
			$sId = mysql_insert_id();
		}
    }

    $globals['post_message'] = 'La frase fue agregado exitosamente.';
	
	return true;
}

function deletePhrase($id, &$globals, $link)
{
	$row = getPhrase($id);

	if($row)
	{
		$deleteSpanish = "DELETE FROM `phrase_spanish` WHERE id = {$row['id_spanish']}";
		$result = mysql_query($deleteSpanish, $link) or die(mysql_error());

		$deleteEnglish = "DELETE FROM `phrase_english` WHERE id = {$row['id_english']}";
		$result = mysql_query($deleteEnglish, $link) or die(mysql_error());

		$deleteTranslation = "DELETE FROM `phrase_translation` WHERE id = {$id}";
		$result = mysql_query($deleteTranslation, $link) or die(mysql_error());

		$globals['post_message'] = 'Datos eleiminados.';
		return true;
	}

	$globals['post_message'] = 'Surgio un error inesperado.';
	$globals['post_error'] = true;
	return false;
}

function deletePhraseSelected($id, &$globals, $link)
{

	$delete = "DELETE FROM `phrase_selected` WHERE id = {$id}";
	$result = mysql_query($delete, $link) or die(mysql_error());

	$globals['post_message'] = 'Datos eleiminados.';
	return true;	
}


function insertPhrase(&$post, &$globals, $link)
{
	$gls = array();

	$req = array('english', 'spanish');

	requiredFields($req, $post, $gls);

	if(isset($gls['post_error']) && $gls['post_error'])
	{
		$globals['post_message'] = 'Por favor rellene todos los campos requeridos.';
		$globals['post_error'] = true;

		return false;
	}
	else
	{
		# spanish
		$spanish = $post['spanish'];		
		$insert_spanish = 'INSERT INTO `phrase_spanish` (`text`) VALUES ("'.$spanish.'")';
		$result = mysql_query($insert_spanish, $link) or die(mysql_error());
		$sId = mysql_insert_id();

		# english
		$english = $post['english'];
		$insert_english = 'INSERT INTO `phrase_english` (`text`) VALUES ("'.$english.'")';
		$result = mysql_query($insert_english, $link) or die(mysql_error());
		$eId = mysql_insert_id();
		
		# translation
		$insert_translation = "INSERT INTO `phrase_translation` (`id_spanish`, `id_english`) VALUES ($sId, $eId)";
		$result = mysql_query($insert_translation, $link) or die(mysql_error());		
		
		$globals['post_message'] = 'La frase fue agregado exitosamente.';
	}

	return true;
}

function updatePhrase(&$post, &$globals, $link)
{
	$gls = array();

	$req = array('english', 'spanish');

	requiredFields($req, $post, $gls);

	if(isset($gls['post_error']) && $gls['post_error'])
	{
		$globals['post_message'] = 'Por favor rellene todos los campos requeridos.';
		$globals['post_error'] = true;
		return false;
	}
	else
	{
		$id = $post['id'];
		$row = getPhrase($id);
		if(count($row) > 1)	
		{
			$id = $row['translation_id'];
			$english = $row['english'];
			$spanish = $row['spanish'];

			$id_english = $row['id_english'];
			$id_spanish = $row['id_spanish'];

			$update_english = 'UPDATE `phrase_english` SET `text` = "'.$post['english'].'"  WHERE id = '.$id_english;
			$result = mysql_query($update_english, $link) or die(mysql_error());

			$update_spanish = 'UPDATE `phrase_spanish` SET `text` = "'.$post['spanish'].'"  WHERE id = '.$id_spanish;
			$result = mysql_query($update_spanish, $link) or die(mysql_error());

			$globals['post_message'] = 'La frase fue agregado exitosamente.';
		}
		else
		{
			$globals['post_message'] = 'Registro no encontrado.';
			$globals['post_error'] = true;
			return false;
		}
	}

	return true;
}

function getPhrase($id = null)
{
	if(!is_null($id))
	{
		$row = array();

		$sql = "SELECT t.id AS translation_id,
				   e.id AS id_english,
				   e.text AS english,
				   s.id AS id_spanish,
				   s.text AS spanish
			FROM `phrase_translation` as t
			INNER JOIN `phrase_english` AS e ON e.id = t.id_english
			INNER JOIN `phrase_spanish` AS s ON s.id = t.id_spanish
		 	WHERE t.id = $id";

		$results = mysql_query($sql);
		if($results)
			$row = mysql_fetch_assoc($results);

		return $row;
	}
	else
	{
		$rowset = array();

		$sql = "SELECT t.id AS translation_id,
				   e.id AS id_english,
				   e.text AS english,
				   s.id AS id_spanish,
				   s.text AS spanish
			FROM `phrase_translation` as t
			INNER JOIN `phrase_english` AS e ON e.id = t.id_english
			INNER JOIN `phrase_spanish` AS s ON s.id = t.id_spanish
		 	GROUP BY t.id";

		$results = mysql_query($sql);
		if($results)
		{
			while ( $row = mysql_fetch_object ( $results ) )
			{
				$rowset[] = $row;
			}
		}

		return $rowset;
	}
}

function getPhraseSpanish($id)
{
	$row = array();
	$sql = "SELECT t.id AS translation_id,
				   e.id AS id_english,
				   e.text AS english,
				   s.id AS id_spanish,
				   s.text AS spanish
			FROM `phrase_translation` as t
			INNER JOIN `phrase_english` AS e ON e.id = t.id_english
			INNER JOIN `phrase_spanish` AS s ON s.id = t.id_spanish
		 	WHERE s.id = $id LIMIT 1";

	$results = mysql_query($sql);
	if($results)	
		$row = mysql_fetch_object($results);

	return $row;
}

function getPhraseEnglish($id)
{
	$row = array();
	$sql = "SELECT t.id AS translation_id,
				   e.id AS id_english,
				   e.text AS english,
				   s.id AS id_spanish,
				   s.text AS spanish
			FROM `phrase_translation` as t
			INNER JOIN `phrase_english` AS e ON e.id = t.id_english
			INNER JOIN `phrase_spanish` AS s ON s.id = t.id_spanish
		 	WHERE e.id = $id LIMIT 1";

	$results = mysql_query($sql);
	if($results)	
		$row = mysql_fetch_object($results);

	return $row;
}

function getPhraseToday()
{
	$rowset = array();

	$rowset = getPhraseSelectedToday();

	/*
	echo '<pre />';
	print_r($rowset);
	exit;
	*/
	
	if(count($rowset) <= 0)
	{
		$rowset = getPhrase();
	}

	return $rowset;
}


/**
 *
 * @param string $section
 * @param string $order_by
 * @param int $current_page
 * @param int $records_per_page
 * @return Zebra_Pagination
 */
function get_paginator($current_page, $records_per_page = 10, $text = null, $table = '`patient`', $status = null)
{
	$current_page = (int)$current_page;
	if($current_page < 1)
		$current_page = 1;

	// instantiate the pagination object
	$pagination = new Zebra_Pagination();

	// set position of the next/previous page links
	// $pagination->navigation_position(isset($_GET['navigation_position']) && in_array($_GET['navigation_position'], array('left', 'right')) ? $_GET['navigation_position'] : 'outside');

	// the MySQL statement to fetch the rows
	// note how we build the LIMIT
	// also, note the "SQL_CALC_FOUND_ROWS"
	// this is to get the number of rows that would've been returned if there was no LIMIT
	// see http://dev.mysql.com/doc/refman/5.0/en/information-functions.html#function_found-rows
	
	$where = '';
	if(!is_null($text) && !empty($text))
	{
		$where = ' WHERE e.text LIKE "%'.$text.'%" OR s.text LIKE "%'.$text.'%" ';
	}
	
	$sqlCount = "SELECT count(t.*) as rows
   FROM `phrase_translation` AS t
   INNER JOIN `phrase_english` AS e ON e.id = t.id_english
   INNER JOIN `phrase_spanish` AS s ON s.id = t.id_spanish
   $where
   GROUP BY translation_id
   ORDER BY english ASC,  spanish ASC
   ";
	
	$sql = "SELECT t.id AS translation_id,
	   e.id AS id_english,
	   e.text AS english,
	   s.id AS id_spanish,
	   s.text AS spanish
   FROM `phrase_translation` AS t
   INNER JOIN `phrase_english` AS e ON e.id = t.id_english
   INNER JOIN `phrase_spanish` AS s ON s.id = t.id_spanish
   $where
   GROUP BY translation_id
   ORDER BY english ASC,  spanish ASC
   ";

	$sql .= " LIMIT " . (($pagination->get_page() - 1) * $records_per_page) . ', ' . $records_per_page . '';
	

	$results = mysql_query ( $sql );
	$rowset = array ();
	if($results)
	{
		while ( $row = mysql_fetch_object ( $results ) )
		{
			$rowset[] = $row;
		}
		
		mysql_free_result($results);
	}

	// fetch the total number of records in the table
	$resultCount = mysql_query($sqlCount);
	
	if($resultCount)
		$row = mysql_fetch_assoc($resultCount);

	// pass the total number of records to the pagination class
	$pagination->records($row['rows']);

	// records per page
	$pagination->records_per_page($records_per_page);

	$pagination->set_rowset($rowset);
	$pagination->selectable_pages(6);

	return $pagination;
}


function requiredFields($req, &$post, &$gls = array())
{
	foreach($req as $field)
	{
		if(!isset($post[$field]))
		{
			$gls['post_error'] = true;
		}
		else
		{
			$post[$field] = strip_tags(trim($post[$field]));
			if($post[$field] == '')
			{
				$gls['post_error'] = true;
			}
		}
	}
}


