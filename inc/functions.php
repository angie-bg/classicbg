<?php

$logicDir='./logic/';
$viewDir='./views/';
mb_internal_encoding('UTF-8');
$db = mysqli_connect('localhost', 'test', 'qwerty', 'classicsbg');
if (!$db) {
    echo 'No database';
}
mysqli_set_charset($db, 'utf8');

function addAliases($type,$id1,$id2,$db){
	$sql='SELECT * from aliases WHERE type="'.$type.'" AND id1='.$id1.' AND id2='.$id2;
    $q=mysqli_query($db, $sql);
         		if (mysqli_error($db)) {
         			echo 'Грешка четене от aliases '.mysqli_error($db);
         		} else {
// няма такъв запис
            		if (mysqli_num_rows($q) == 0) {
           				$sql='INSERT INTO aliases (type,id1,id2) VALUES("'.$type.'",'.$id1.','.$id2.')';
        				mysqli_query($db,$sql);
        				if (mysqli_error($db)) {
         			   		echo 'Грешка запис в aliases '.mysqli_error($db);;
        				}
        			}
         		}
}

function render($title,$name,$data=array(),$data1=array()){  
	require $name.'.php'; 
}
	
function getComposers($db) {
    $q = mysqli_query($db, 'SELECT * FROM composers ORDER BY composer_name ASC');
    if (mysqli_error($db)) {
        return false;
    }
    $ret = array();
    while ($row = mysqli_fetch_assoc($q)) {
        $ret[] = $row;
    }
    return $ret;
}

function isComposerIdExists($db, $ids) {
    if (!is_array($ids)) {
        return false;
    }
    $q = mysqli_query($db, 'SELECT * FROM composers WHERE 
        composer_id IN(' . implode(',', $ids) . ')');
    if (mysqli_error($db)) {
        return false;
    }
    
    if (mysqli_num_rows($q) == count($ids)) {
        return true;
    }
    return false;
}

function getOperas($db) {
    $q = mysqli_query($db, 'SELECT * FROM operas ORDER BY opera_title ASC');
    if (mysqli_error($db)) {
        return false;
    }
    $ret = array();
    while ($row = mysqli_fetch_assoc($q)) {
        $ret[] = $row;
    }
    return $ret;
}

/* Function : dump()
 * Arguments : $data - the variable that must be displayed
 * Prints a array, an object or a scalar variable in an easy to view format.
 */
function dump($data) {
    if(is_array($data)) { //If the given variable is an array, print using the print_r function.
        print "<pre>-----------------------\n";
        print_r($data);
        print "-----------------------</pre>";
    } elseif (is_object($data)) {
        print "<pre>==========================\n";
        var_dump($data);
        print "===========================</pre>";
    } else {
        print "=========&gt; ";
        var_dump($data);
        print " &lt;=========";
    }
} 