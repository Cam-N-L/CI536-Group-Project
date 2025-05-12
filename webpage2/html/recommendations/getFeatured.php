<?php
//to get the list of 5 recommended movies, call this function to a variable, passing the connection in
//this variable will contain 5 indexs of popular movies
    session_start();
    
    function findFeatured($conn){
        $sql = "SELECT `Index` FROM `GamesInfo` WHERE `Rating` >3 AND `Plays` > 80000 ;";
        $result = $conn->query($sql);
        $titles = [];
        while($row = $result->fetch_assoc()){
            //echo $row["Title"];
            $titles[]=$row["Index"];
        }
        $top5List = [];
        for ($i = 0; $i<8;$i++){
            //find the length
            $length = count($titles);
        
            //find a random number to select
            $indexToFind = rand(0,$length-1);
        
            //if the title is not already in the array, add it
            if (!in_array($titles[$indexToFind],$top5List)){
                $top5List[] = $titles[$indexToFind];
            } else {
                //if it is, loop again
                $i--;
            }
            
        }
        //print each of the titles
        //foreach($top5List as $Randomtitles){
            //echo "<br>";
            //echo $Randomtitles;
        //}
        return $top5List;
    }



?>
