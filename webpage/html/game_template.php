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
            return "unknown";
        }
        }

    function sortDate($Data){
        if ($Data == "TBD") {
            return "Release date to be announced";
    } else {
        return "Released on " . $Data;
    }}

   ?> 

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="../css/home.css" rel="stylesheet" type="text/css">
    <title>Videogame review webpage</title>
</head>

<body>
    <nav class="navbar">
        <a href="#">User</a>
        <a href="/webpage/html/home.html">Home</a>
        <a href="/webpage/html/log.html">Log</a>
    </nav>
    <div class="game-container">
        <h1> <?php echo $game['Title']; ?> </h1>
        <p> created by <?php echo sortArrays($game['Developers']); ?> • <?php echo sortArrays($game['Genres']); ?> • <?php echo sortDate($game['Release_date']); ?></p>
        <p> <?php echo $game['Summary']; ?> </p>
        <p> Released on </strong> <?php echo sortArrays($game['Platforms'])?> with an average rating of <?php echo $game['Rating']; ?></p>
    </div>
</body>
</html>
