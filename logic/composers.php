<?php
if ($_POST) {
	$composer_name = trim($_POST['composer_name']);
    if (mb_strlen($composer_name) < 2) {
        echo '<p>Невалидно име</p>';
    } else {
        $composer_esc = mysqli_real_escape_string($db, $composer_name);
        $q = mysqli_query($db, 'SELECT * FROM composers WHERE
        composer_name="' . $composer_esc . '"');
        if (mysqli_error($db)) {
            echo 'Грешка търсене в composers';
        }

        if (mysqli_num_rows($q) > 0) {
            echo 'Има такъв композитор';
        } else {
            mysqli_query($db, 'INSERT INTO composers (composer_name)
            VALUES("' . $composer_esc . '")');
            if (mysqli_error($db)) {
                echo 'Грешка запис в composers';
            } else {
            	$comp_id=mysqli_insert_id($db);
            	addAliases("c",$comp_id,$comp_id,$db);
            	if (isset($_POST['composers'])){
            		$comp_al=$_POST['composers'];
            		$n=count($_POST['composers']);
					/* проверка дали е избрано нещо в "Известен като" */             		
            		if ($n>0){
	            		for ($i=0;$i<=2*$n-1;$i++){
	            			if($i<=$n-1){
	            				$id1=$comp_id;
	            				$id2=$comp_al[$i];
	            			} else {
	            				$id2=$comp_id;
	            				$id1=$comp_al[$i-$n];
	               			}
	               			addAliases("c",$id1,$id2,$db);
	            		}
            		
            		}
	            }
            }
        }
    }
}
$composers = getComposers($db);
if ($composers===false) {
    echo 'Грешка в getComposers';
}
else {
	render('Композитори',$viewDir.'ls_composers',$composers);
}
include './inc/footer.php';

 
