<?php
function sortArrays($Data){
    $PrintString = "";

    $Data = str_replace("["," ",$Data);
    $Data = str_replace("]"," ",$Data);
        
    //split the row by commas
    $Data = explode(",",$Data);
    //arrays cant be echoed
        
    //so we loop through each item in the array, get rid of the '' around the word

    foreach ($Data as $value) {
        $value = str_replace("'"," ",$value);
        $PrintString .= rtrim($value) . ", ";
    }

    $PrintString = rtrim($PrintString,", ");

    if ($PrintString != "") {
        return $PrintString;
    } else {
        return " unknown";
    }
    }
?>