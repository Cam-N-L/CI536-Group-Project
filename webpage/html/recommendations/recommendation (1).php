<?php
//to call this function you need to make an instance of the class, passing the username and the database connection in.
//then use $recs->toBeRecommended to access the list of recommended items.
    session_start();
    include '../../php-folder/databaseConnection.php';
    include 'getFeatured.php';



    class getRecs{
        public $username = null;
        public $ogsql = "SELECT `Index` FROM `GamesInfo` INNER JOIN `PlayedGames` ON `Index`=`GameIndex` WHERE `Username`=\"(?)\"";
        public $conn = null;
        public $result = null;
        public $toBeRecommended = [];
        public $toBeRecommendedTitle = [];
        
        
        public $enoughdata = null;
        
        
        private $playsvals = [];
        private $ratingvals = [];
        private $totalGenres = [];
        private $genreScores = [];
        private $arrayToSearch = [];
        


        function __construct($usernamein, $connin){
            $this->username = $usernamein;
            $this->conn = $connin;
            $this->getAllFromDB();
            $this->info();
            $this->getRecommendation();
            //return $this->toBeRecommended;
        }
        function getRandom8(){
           

            if ($this->enoughdata==true){
                $finalList= [];
                if (count($this->toBeRecommended)==8){
                    $finalList = $this->toBeRecommended;
                }
                else {
                    for ($i = 0; $i<8;$i++){
                        $indexToFind = rand(0,count($this->toBeRecommended)-1);
               
                        //if the title is not already in the array, add it
                        if (!in_array($this->toBeRecommended[$indexToFind],$finalList)){
                            $finalList[] = $this->toBeRecommended[$indexToFind];
                        } else {
                            //if it is, loop again
                            $i--;
                        }
                    }
                }
                return $finalList;
            }
            else {return null;}
           
        }
        function runSQL($sql, $variable){
            //add the parameters, and run the sql, in function so i dont have to type it out
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s",$variable);
            $stmt->execute();
            $result=$stmt->get_result();
            return $result;
        }
        function info(){
            foreach ($this->data as $row){
                echo "plays:, ".$row[8]."rating: ".$row[7];
            }
        }
        function setPlaysVals($upper,$lower){
            $playsvals[0] = $lower;
            $playsvals[1] = $upper;
        }
        function setRatingVals($upper,$lower){
            $ratingvals[0] = $lower;
            $ratingvals[1] = $upper;
        }
        function getPlayVals(){
            return $playsvals;
        }
        function getRatingVals(){
            return $ratingvals;
        }
        function saveGenres($total,$scores,$array){
            $this->totalGenres = $total;
            $this->genreScoring = $scores;
            $this->arrayToSearch = $array;
            
        }
        function getAllFromDB(){
            //$sql = "SELECT `Index`,`Title`,`Release_date`,`Developers`,`Summary`,`Platforms`,`Genres`,`Rating`,`Plays`,`Playing`,`Backlog`,`Wishlist`,`Lists`,`Reviews`,`keywords` FROM `GamesInfo` INNER JOIN `PlayedGames` ON `Index`=`GameIndex` WHERE `Username`=(\"JasmineSlays\");";
            //$result = $this->conn->query($sql);
            //$title1 = $result->fetch_assoc();
            //$title = $title1["Title"];
            
            
            $sql = "SELECT `Index`,`Title`,`Release_date`,`Developers`,`Summary`,`Platforms`,`Genres`,`Rating`,`Plays`,`Playing`,`Backlog`,`Wishlist`,`Lists`,`Reviews`,`keywords` FROM `GamesInfo` INNER JOIN `PlayedGames` ON `Index`=`GameIndex` WHERE `Username`=(?);";
            //$result = $this->runSQL($sql,$this->username);
            $this->allstmt = $this->conn->prepare($sql);
            $this->allstmt->bind_param("s",$this->username);
            $this->allstmt->execute();
            $results = $this->allstmt->get_result();
            $this->data = $results->fetch_all();
            
            
            if (count($this->data)<5){
                $this->enoughdata = false;
            } else {
                $this->enoughdata = true;
            }
            
            
            echo "slay1<br>";
            if (count($this->data)>15){
                
                $sql = "SELECT`Index`,`Title`,`Release_date`,`Developers`,`Summary`,`Platforms`,`Genres`,GamesInfo.`Rating`,`Plays`,`Playing`,`Backlog`,`Wishlist`,`Lists`,`Reviews`,`keywords` FROM `GamesInfo` INNER JOIN `ReviewTable` ON `Index`=`GameIndex`WHERE `Username`= ? ORDER BY ReviewTable.`Rating` DESC;";

                //$sql = "SELECT`Index`,`Title`,`Release_date`,`Developers`,`Summary`,`Platforms`,`Genres`,GamesInfo.`Rating`,`Plays`,`Playing`,`Backlog`,`Wishlist`,`Lists`,`Reviews`,`keywords` FROM `GamesInfo` INNER JOIN `ReviewTable` ON `Index`=`GameIndex`WHERE `Username`= (?) ORDER BY ReviewTable.`Rating` DESC;";
                $this->ogsql = "SELECT`Index` FROM `GamesInfo` INNER JOIN `ReviewTable` ON `Index`=`GameIndex`WHERE `Username`= \"(?)\"";
                $this->allstmt = $this->conn->prepare($sql);
                $this->allstmt->bind_param("s",$this->username);
                $this->allstmt->execute();
                $results = $this->allstmt->get_result();
                $this->data = $results->fetch_all();
                echo "slay2<br>";
                if (count($this->data)<5) {
                    echo "slay3<br>";
                    $sql = "SELECT `Index`,`Title`,`Release_date`,`Developers`,`Summary`,`Platforms`,`Genres`,`Rating`,`Plays`,`Playing`,`Backlog`,`Wishlist`,`Lists`,`Reviews`,`keywords` FROM `GamesInfo` INNER JOIN `PlayedGames` ON `Index`=`GameIndex` WHERE `Username`=(?);";
                    //$result = $this->runSQL($sql,$this->username);
                    $this->allstmt = $this->conn->prepare($sql);
                    $this->allstmt->bind_param("s",$this->username);
                    $this->allstmt->execute();
                    $results = $this->allstmt->get_result();
                    $this->data = $results->fetch_all();
                    $this->ogsql = "SELECT `Index` FROM `GamesInfo` INNER JOIN `PlayedGames` ON `Index`=`GameIndex` WHERE `Username`=\"(?)\"";
                }
            } 
            
            
            // if this is more thna 15, get from the review table?? then if that is less than 5 use both unsorted??? 
            //$sql = "SELECT`Index`,`Title`,`Release_date`,`Developers`,`Summary`,`Platforms`,`Genres`,GamesInfo.`Rating`,`Plays`,`Playing`,`Backlog`,`Wishlist`,`Lists`,`Reviews`,`keywords` FROM `GamesInfo` INNER JOIN `ReviewTable` ON `Index`=`GameIndex`WHERE `Username`= (?) ORDER BY ReviewTable.`Rating` DESC;";
            //$this->allstmt->bind_result($this->Index,$this->Title,$this->Release_date,$this->Developers,$this->Summary,$this->Platforms,$this->Genres,$this->Rating,$this->Plays,$this->Playing,$this->Backlog,$this->Wishlist,$this->Lists,$this->Reviews);
                //`Index``Title``Release_date``Developers``Summary``Platforms``Genres``Rating``Plays``Playing``Backlog``Wishlist``Lists``Reviews`
        }

        function getGenres(){

            //set the lists for the genres and how important the genres are
            $totalGenres = [];
            $genreScoring = [];

            //set counter to ensure enough movies have been found IDK MIGHT NEED TO MOVE
            $counter = 0 ;
            //$row = $this->data[0];
            //loop through the results to add or remove genres
            foreach ($this->data as $row) {
                //get the genre
                $genres = $row[6];
                $counter++;
                //replace the [] with nothing
                $genres = str_replace("["," ",$genres);
                $genres = str_replace("]"," ",$genres);
                
                //split the row by commas
                $genres = explode(",",$genres);
                //arrays cant be echoed
                
                //so we loop through each genre in the array, get rid of the '' around the word
                foreach ($genres as $value) {
                    $value = str_replace("'"," ",$value);
                    //if it isnt in the array, we add it and add the score to be 1
                    if (!in_Array($value,$totalGenres)){
                        $totalGenres[]=$value;
                        $genreScoring[] = 1;
                    } else {
                        //if it is in the total genres array already, the score is increased for the value.
                        $where = array_search($value,$totalGenres);
                        $genreScoring[$where]++;
                    }
                }
                
            }
            
            //this formats the genre names to be input into a sql statement
            $arrayToSearch = [];
            foreach ($totalGenres as $genre){
                if ($genreScoring[array_search($genre,$totalGenres)]>0){
                    $genre = substr($genre, 1,-1);
                    $genre = trim($genre);
                $arrayToSearch[] = "%".$genre."%";
                }
            }
            $this->saveGenres($totalGenres, $genreScoring,$arrayToSearch);
            //this sets up the sql statement and adds all the most popular genres
            $sql = "SELECT `Index` FROM `GamesInfo` WHERE";
            for ($i=0;$i<count($arrayToSearch);$i++){
                $sql = $sql." `Genres` LIKE "."\"".$arrayToSearch[$i]."\" AND";
            }   
            $genresql = substr($sql,0,-4);
            $sql = $sql."`Index` NOT IN (".$this->ogsql." );";
            //this is the sql used later in other select statements
            
            
            $count = 0;
            //this counts the items with the same genre, and checks that the length of the sql is long enough to have been added
            if (strlen($sql)>25){
                $result = $this->conn->query($sql);
                while ($row = $result->fetch_assoc()){
                    $count++;
                }
            }
            return $genresql;
        }

        function getUpdatedGenres($iteration){
            //this makes a new sql statment to add genres to be less specific
            $length = count($this->arrayToSearch);
            //the number of genres together in the sql
            $compairing = $length-$iteration;
            
            $sql = "SELECT `Index` FROM `GamesInfo` WHERE (";
            for ($i=0;$i<$length;$i++){
                $sql = $sql." `Genres` LIKE "."\"".$this->arrayToSearch[$i]."\" AND ";
                for ($j=1;$j<$compairing;$j++){
                    $index = $i+$j;
                    if ($index>=$length){
                        $index = $index-$length;
                    }
                    $sql = $sql." `Genres` LIKE "."\"".$this->arrayToSearch[$index]."\" AND ";
                }
                $sql = substr($sql,0,-4);
                $sql=$sql.") OR (";
            }   
            $sql = substr($sql,0,-5);
            
            return $sql;
        }
        function getPlays(){
            
            //variables for calculating mean and standard deviation
            $counter = 0;
            $total = 0;
            $sdtotal = 0;
            $highest = -1;
            $lowest = 9999999;
            //this is a list to be used when calculating SD
            $placeholder = [];
            //loop through results and count the values and how many there are
            foreach($this->data as $row){
                $list = explode(",",$row[8]);
                foreach ($list as $play){
                    $counter++;
                    $total += $row[8];
                    $placeholder[] = $row[8];
                    if ($row[8]>$highest){
                        $highest=$row[8];
                        //echo $highest;
                    } if ($row[8]<$lowest){
                        $lowest = $row[8];
                        //echo $lowest;
                    }
                }
            } 
            $playsql = "";
            if ($counter>0){
                //calculate mean
                $mean = $total/$counter;
                //calculate standard deviation
                foreach($placeholder as $x){
                $sdtotal += pow($x - $mean,2);
                }
                $sd = pow($sdtotal/$counter,0.5);
                $rsd = (($sd*100)/$mean)/100;
                //find the upper and lower bounds
                
                echo "<br> highest".$highest."<br> lowest: ".$lowest;
                echo "<br> sd: ".$sd;
                echo "<br> mean: ".$mean;
                echo "<br> rsd: ".$rsd;
                $upper = $highest*($rsd+1);
                $lower = $lowest*$rsd;
                echo "<br> upper: ".$upper;
                echo "<br> lower: ".$lower;
                
                echo "<br> old upper: ".$highest;
                echo "<br> old lower: ".$mean-$sd;
                //create upper and lower plays avaliable
                $this->setPlaysVals(($upper),($lower));
                //$upper = $highest;
                //$lower = $mean - $sd;
    
                $sql = "SELECT `Index` FROM `GamesInfo` WHERE (`Plays` BETWEEN (?) AND (?)) AND `Index` NOT IN (".$this->ogsql." ) ;";
                $playsql = "(`Plays` BETWEEN (".$lower.") AND (".$upper."))";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("ii",$lower,$upper);
                $stmt->execute();
                $result=$stmt->get_result();
                
                
            }
            return $playsql;
        }
        

        function getDevs(){
            //like genres, the list of developers and how important they are
            $totalDevs = [];
            $DevsScore = [];
            $counter = 0;

            foreach($this->data as $row) {
                //get the developer
                $devs = $row[3];
                $counter++;
            
                //replace the [] with nothing
                $devs = str_replace("["," ",$devs);
                $devs = str_replace("]"," ",$devs);

                //split the row by commas
                $devs = explode(",",$devs);
                //arrays cant be echoed
            
                //so we loop through each item in the array, get rid of the '' around the word and print it
                foreach ($devs as $value) {
                    $value = str_replace("'"," ",$value);
                    //if not in the array, add to list
                    if (!in_Array($value,$totalDevs)){
                        $totalDevs[]=$value;
                        $devsScoring[] = 1;
                    } else {
                        //if already in list, increase counter
                        $where = array_search($value,$totalDevs);
                        $devsScoring[$where]++;
                    }
                }
            }

            $arrayToSearch2 = [];
            //loop through each dev
            foreach ($totalDevs as $devs){
                //if there is more than 1 of the same, add it to the sql to find similar values
                if ($devsScoring[array_search($devs,$totalDevs)]>1){
                    $devs = substr($devs, 1,-1);
                    $devs = trim($devs);
                    $arrayToSearch2[] = "%".$devs."%";
                }
            }

            //write the sql in full
            $sql = "SELECT `Index` FROM `GamesInfo` WHERE";
            for ($i=0;$i<count($arrayToSearch2);$i++){
                $sql = $sql." `Developers` LIKE "."\"".$arrayToSearch2[$i]."\" AND";
            }
            $devsql = substr($sql,0,-4);
            $sql = $sql."`Index` NOT IN (".$this->ogsql." );";
            $count = 0;
            //if the sql has been added it will be longer than 25 characters 
            if (strlen($sql)>25){
                $result = $this->conn->query($sql);
                while ($row = $result->fetch_assoc()){
                    $count++;
                }
            }
            return $devsql;

        }
        
        function getRatings(){
            //initalising counter and lists for mean and sd    
            $counter = 0;
            $total = 0;
            $sdtotal = 0;
            $placeholder = [];
            $highest = -1;
            $lowest = 9999999;
            
            //loop through every item in the list, add them for mean and count them.
            foreach($this->data as $row){
                $counter++;
                $total += $row[7];
                $placeholder[] = $row[7];
                if ($row[7]>$highest){
                    $highest=$row[7];
                    //echo $highest;
                } if ($row[7]<$lowest){
                    $lowest = $row[7];
                    //echo $lowest;
                }
            } 
            $ratingsql = "";
            if ($counter>0){
                //calculate mean
                $mean = $total/$counter;
                
                //calculate sd
                foreach($placeholder as $x){
                    $sdtotal += pow($x - $mean,2);
                }
                $sd = pow($sdtotal/$counter,0.5);
                $rsd = (($sd*100)/$mean)/100;
                //find the upper and lower bounds
                $upper = $highest*($rsd+1);
                $lower = $lowest*$rsd;
                echo "<br> highest".$highest."<br> lowest: ".$lowest;
                echo "<br> sd: ".$sd;
                echo "<br> mean: ".$mean;
                echo "<br> rsd: ".$rsd;
    
    
    
    
                //prepare sql statement
                $sql = "SELECT `Index` FROM `GamesInfo` WHERE (`Rating` BETWEEN (?) AND (?)) ;";
                $ratingsql ="`Rating` BETWEEN ";
                $ratingsql = $ratingsql.($lower)." AND ".($upper);
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("ii",$lower,$upper);
                //run sql statement
                $stmt->execute();
                $result=$stmt->get_result();
    
                //print results and count how many there are.
                $numOfResults = 0;
                while ($row = $result->fetch_assoc()){
                    $numOfResults++;
                }
            }
            return $ratingsql;
        }


        function getRecommendation(){
            

            $genresql = $this->getGenres();
            $ratingsql = $this->getRatings();
            $playsql = $this->getPlays();
            $devsql = $this->getDevs();
            $keywordsql = $this->getKeywords();

            //echo "<br>ratingsql ".$ratingsql."<br>genresql ".$genresql."<br>plays ".$playsql."<br>devs ".$devsql;
            
            //then i need to add them together with multiple sub queries, and if there arent enough, do more. add rating one as well.
            //  and do summary analysis first, then these, just trying to make it cleanest.
            //checks with all the info avaliable
            $finalsql = "SELECT `Index`,`Title` FROM `GamesInfo` WHERE ".$playsql." AND ".$ratingsql." AND `Index` IN (".$genresql.") AND `Index` IN (".$devsql.") AND `Index` NOT IN (".$this->ogsql.");";
            // AND `Index` IN (".$keywordsql.")
            
            //echo "<br><br><br>".$finalsql;
            $result = $this->conn->query($finalsql);
            while ($row = $result->fetch_assoc()){
                if (!in_Array($row["Title"],$this->toBeRecommendedTitle)){
                    $this->toBeRecommended[] = $row["Index"];
                    $this->toBeRecommendedTitle[] = $row["Title"];
                }
            }
            //removes the devs, adds found games to the list
            if ($this->toBeRecommended == null || count($this->toBeRecommended)<6){
                $finalsql = $finalsql = "SELECT `Index`,`Title` FROM `GamesInfo` WHERE ".$playsql." AND ".$ratingsql." AND `Index` IN (".$genresql.") AND `Index` NOT IN (".$this->ogsql.");";
                //echo "<br><br><br>".$finalsql;
                $result = $this->conn->query($finalsql);
                while ($row = $result->fetch_assoc()){
                    if (!in_Array($row["Title"],$this->toBeRecommendedTitle)){
                        $this->toBeRecommended[] = $row["Index"];
                    $this->toBeRecommendedTitle[] = $row["Title"];

                    }
                }
                $iteration = 0;
                //this then compares the genres less specifically until at least 5 games have been found
                while (count($this->toBeRecommended)<6 & $iteration < count($this->arrayToSearch)){
                    $iteration++;
                    //change genres
                    $genresql =  $this->getUpdatedGenres($iteration);
                    $finalsql = $finalsql = "SELECT `Index`,`Title` FROM `GamesInfo` WHERE ".$playsql." AND ".$ratingsql." AND `Index` IN (".$genresql.") AND `Index` NOT IN (".$this->ogsql.");";
                    //echo "<br><br><br>".$finalsql;
                    $result = $this->conn->query($finalsql);
                    while ($row = $result->fetch_assoc()){
                        if (!in_Array($row["Title"],$this->toBeRecommendedTitle)){
                            $this->toBeRecommended[] = $row["Index"];
                            $this->toBeRecommendedTitle[] = $row["Title"];
                            echo "<br>added ".$row["Title"];
                        }
                    }
                }
                $iteration = 1;
                while (count($this->toBeRecommended)<6 & $iteration < count($this->arrayToSearch)){
                    $iteration++;
                    //change genres
                    $genresql =  $this->getUpdatedGenres($iteration);
                    $finalsql = $finalsql = "SELECT `Index`,`Title` FROM `GamesInfo` WHERE ".$playsql." AND ".$ratingsql." AND `Index` IN (".$genresql.") AND `Index` IN (".$keywordsql.") AND `Index` NOT IN (".$this->ogsql.");";
                    //echo "<br><br><br>".$finalsql;
                    $result = $this->conn->query($finalsql);
                    while ($row = $result->fetch_assoc()){
                        if (!in_Array($row["Title"],$this->toBeRecommendedTitle)){
                            $this->toBeRecommended[] = $row["Index"];
                            $this->toBeRecommendedTitle[] = $row["Title"];
                            echo "<br>added ".$row["Title"];
                        }
                    }
                }
                
                echo "sdkjcbdsovbsdovljblv";
            }
        }
        function getKeywords(){

            //set the lists for the genres and how important the genres are
            $totalKeywords = [];
            $keywordScoring = [];

            //set counter to ensure enough movies have been found IDK MIGHT NEED TO MOVE
            $counter = 0 ;
            //$row = $this->data[0];
            //loop through the results to add or remove genres
            foreach ($this->data as $row) {
                //get the genre
                $keywords = $row[14];
                $counter++;
                //replace the [] with nothing
                $keywords = str_replace("["," ",$keywords);
                $keywords = str_replace("]"," ",$keywords);
                
                //split the row by commas
                $keywords = explode(",",$keywords);
                //arrays cant be echoed
                
                //so we loop through each genre in the array, get rid of the '' around the word
                foreach ($keywords as $word) {
                    $value = str_replace("'"," ",$word);
                    //if it isnt in the array, we add it and add the score to be 1
                    if (!in_Array($word,$totalKeywords)){
                        $totalKeywords[]=$word;
                        $keywordScoring[] = 1;
                    } else {
                        //if it is in the total genres array already, the score is increased for the value.
                        $where = array_search($word,$totalKeywords);
                        $keywordScoring[$where]++;
                    }
                }
                
            }
            foreach ($totalKeywords as $idk){
                //echo $idk.": ";
                $where = array_search($idk,$totalKeywords);
                //echo $keywordScoring[$where];
                
            }
            //this formats the genre names to be input into a sql statement
            $arrayToSearch = [];
            $number = count($totalKeywords)-5;
            //might need to change 2 MAYBE IDK AHHHHHH
            
            
            foreach ($totalKeywords as $word){
                if ($keywordScoring[array_search($word,$totalKeywords)]>1){
                    $word = substr($word, 2,-1);
                    //echo $word."<br>";
                    
                    $word = trim($word);
                    $arrayToSearch[] = "%".$word."%";
                   
                }
            }
            
            
            
            
            $length = count($arrayToSearch);
            //the number of genres together in the sql
            $compairing = $length/2;
            
            $sql = "SELECT `Index` FROM `GamesInfo` WHERE (";
            for ($i=0;$i<$length;$i++){
                $sql = $sql." `keywords` LIKE "."\"".$arrayToSearch[$i]."\" AND ";
                for ($j=1;$j<$compairing;$j++){
                    $index = $i+$j;
                    if ($index>=$length){
                        $index = $index-$length;
                    }
                    $sql = $sql." `keywords` LIKE "."\"".$arrayToSearch[$index]."\" AND ";
                }
                $sql = substr($sql,0,-4);
                $sql=$sql.") OR (";
            }   
            $sql = substr($sql,0,-5);
            
            

            //$count = 0;
            
            //this counts the items with the same keywords, and checks that the length of the sql is long enough to have been added
            //if (strlen($sql)>25){
                //$result = $this->conn->query($sql);
                //while ($row = $result->fetch_assoc()){
                    //echo $row["Index"];
                    //$count++;
                //}
            //}
            //echo "<br>".$sql;
            return $sql;
            
        } 
            
            
        
        
    
        

    }
?>