<?php
if ($_POST) {

    $opera_name = trim($_POST['opera_name']);
    if (!isset($_POST['composers'])) {
        $_POST['composers'] = '';
    }
    $composers = $_POST['composers'];
    $er = array();
    if (mb_strlen($opera_name) < 2) {
        $er[] = '<p>Невалидно име</p>';
    }
    if (!is_array($composers) || count($composers) == 0) {
        $er[] = '<p>Грешка</p>';
    }
    if (!isComposerIdExists($db, $composers)) {
        $er[] = 'невалиден автор';
    }
	/* изписват се последователно всички грешки */
    if (count($er) > 0) {
        foreach ($er as $v) {
            echo '<p>' . $v . '</p>';
        }
    } else {
    	/*запис на заглавието на произведението*/
        mysqli_query($db, 'INSERT INTO operas (opera_title) VALUES("' .
                mysqli_real_escape_string($db, $opera_name) . '")');
        if (mysqli_error($db)) {
            echo 'Error';
            exit;
        }
        /* запис на композиторите */
        $id = mysqli_insert_id($db); /* номер на произведението */
        foreach ($composers as $composerId) {
            mysqli_query($db, 'INSERT INTO operas_composers (opera_id,composer_id)
                VALUES (' . $id . ',' . $composerId . ')');
            if (mysqli_error($db)) {
                echo 'Error';
                echo mysqli_error($db);
                exit;
            }
        }
        /* запис на „известен като“ */
   		 $op_id=$id; /* номер на произведението */
         addAliases("o",$op_id,$op_id,$db);
         if (isset($_POST['operas'])){
            $op_al=$_POST['operas'];
            $n=count($_POST['operas']);
		/* проверка дали е избрано нещо в "Известен като" */             		
            if ($n>0){
	            for ($i=0;$i<=2*$n-1;$i++){
	            	if($i<=$n-1){
	            		$id1=$op_id;
	            		$id2=$op_al[$i];
	            	} else {
	            		$id2=$op_id;
	            		$id1=$op_al[$i-$n];
	               	}
	               	addAliases("o",$id1,$id2,$db);
	            }      
            }
		}
        echo 'Произведението е добавено';
        
    }
}
$composers = getComposers($db);
if ($composers === false) {
	echo 'грешка в композитори: add_opera:68';
}
else {
	$operas = getOperas($db);
	if ($operas === false) {
		echo 'грешка в опери: add_opera:73';
	} else {
		render('Ново произведение',$viewDir.'ls_add_opera',$composers,$operas);
	}
}
include './inc/footer.php';
?>