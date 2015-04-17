<!-- Randall Meyer 4/16/2015 -->

<html>
<body>
    <h1><center>Player Spin</center></h1>
<table align="center">
<tr><td align="center"><input type="submit" id="SubmitSpin" name="SubmitSpin" value="Submit Spin" onclick="SubmitSpin()"/></td></tr>
</table>
</body>
</html>

<!-- javascript button to fake a Spin from client -->
<script type="text/javascript">
function SubmitSpin(clicked)
{
    var x="<?php SubmitSpin(); ?>";
    alert(x);
    var r = confirm("Would you like to navigate to the JSON Results?");
    if (r == true) {
        window.location.assign("http://localhost/~randallmeyer/Players.html");
    }
    return false;
}
</script>

<?php
    
    //Class to hold Database info
    class PlayersDatabase
    {
        public $servername = 'localhost';
        public $username = 'root';
        public $password = 'pizza4321';
        public $dbname = 'PlayerSpins';
        
        function __construct() {}
        
        //Creates Players database if none exists
        function CreateDatabaseIfNoneExists()
        {
            $link = mysql_connect($this->servername, $this->username, $this->password);
            
            if (!$link) {
                die('Could not connect: ' . mysql_error());
            }
            
            //Make Players the current database
            $db_selected = mysql_select_db($this->dbname, $link);
            
            //Checking if the database exists, if it doesn't make it
            if (!$db_selected) {
                $file_content = file('PlayerSpins.sql');
                $query = "";
                foreach($file_content as $sql_line){
                    if(trim($sql_line) != "" && strpos($sql_line, "--") === false){
                        $query .= $sql_line;
                        if (substr(rtrim($query), -1) == ';'){
                            //echo $query;
                            $result = mysql_query($query)or die(mysql_error());
                            $query = "";
                        }
                    }
                }
            }
            mysql_close($link);
        }
        
        //Creates fake client data and passes it
        //Can eventually be made to detect from elsewhere
        function SubmitSpin()
        {
            $randNum = 1;//rand(1, 5);
            $playerID = 0;
            $playerName = "";
            $saltValue = "";
            $coinsWon = rand(1, 5000);
            $coinsBet = rand(1, 5000);
            
            if($randNum == 1) {
                $playerID = 12345;
                $playerName = "Freddy Johnson";
                $saltValue = "568turjfigkturjfhgod93";
            }
            else if($randNum == 2) {
                $playerID = 54321;
                $playerName = "Bobby Smith";
                $saltValue = "65w934jg924jfi85jgkf93";
            }
            else if($randNum == 3) {
                $playerID = 76543;
                $playerName = "Ray Williams";
                $saltValue = "58gjtir9406kigjdiej32e";
            }
            else if($randNum == 4) {
                $playerID = 90876;
                $playerName = "Sara Farley";
                $saltValue = "596jtigorkfie32049igkd";
            }
            else if($randNum == 5) {
                $playerID = 11111;
                $playerName = "Amy Adams";
                $saltValue = "59tjgkri4320dekrofvgch";
            }
            
            //Create the player if doesn't exist
            $this->CreatePlayerIfFirstTimePlaying($playerID, $playerName, $saltValue);
            //$this->ClearDatabase();
            
            $options = [
            'cost' => 11,
            'salt' => $saltValue,
            ];
            $passwordHash = password_hash("thiscanbeanything", PASSWORD_BCRYPT, $options);

            //Checking the player data and updating if it's valid
            //$this->UpdateDataIfIsValid($playerID, $coinsWon, $coinsBet, $passwordHash);
        }
        
        //function for clearing everything for testing
        function ClearDatabase() {
            //Connecting to the mysql server
            $link = mysql_connect($this->servername, $this->username, $this->password);
            if (!$link) {
                die('Could not connect: ' . mysql_error());
            }
            
            //Make Players the current database
            $db_selected = mysql_select_db($this->dbname, $link);
            
            $delete = "DELETE FROM Player";
            mysql_query($delete, $link);
            
            mysql_close($link);
            
            $this->GenerateJSONResponseData();
        }
        
        function CreatePlayerIfFirstTimePlaying($PlayerID, $PlayerName, $SaltValue) {
            
            //Connecting to the mysql server
            $link = mysql_connect($this->servername, $this->username, $this->password);
            if (!$link) {
                die('Could not connect: ' . mysql_error());
            }
            
            //Make Players the current database
            $db_selected = mysql_select_db($this->dbname, $link);
            
            $select = "SELECT PlayerID FROM Player where PlayerID = $PlayerID";
            $result = mysql_query($select, $link);
            
            if(mysql_num_rows($result) > 0){
            }else{
                $create = "INSERT INTO Player (PlayerID, Name, Credits, LifetimeSpins, SaltValue) VALUES ($PlayerID, '$PlayerName',  5000, 1, '$SaltValue')";

                if (mysql_query($create)) {
                    echo "New record created successfully";
                } else {
                    echo "There was an error when creating new record";
                }
            }
            
            mysql_close($link);
            
            $this->GenerateJSONResponseData();
        }
        
        function UpdateOrCreateDataIfIsValid($PlayerID, $CoinsWon, $CoinsBet, $Hash) {
            
            /*echo "What!";
            
            //Connecting to the mysql server
            $link = mysql_connect($this->servername, $this->username, $this->password);
            if (!$link) {
                die('Could not connect: ' . mysql_error());
            }*/
            
            /*//Make Players the current database
             $db_selected = mysql_select_db($this->dbname, $link);
             
             //$sql = "INSERT INTO Player (PlayerID, Name, Credits, LifetimeSpins, SaltValue) VALUES (12345, 'Billy Bob',  5000, 1, 54634)";
             
             if (mysql_query($sql, $link)) {
             echo "New record created successfully";
             } else {
             echo "Error: " . $sql . "<br>" . mysql_error() . "\n";
             }*/
            
            //mysql_close($link);
        }
        
        function GenerateJSONResponseData()
        {
            //Connecting to the mysql server
            $link = mysql_connect($this->servername, $this->username, $this->password);
            if (!$link) {
                die('Could not connect: ' . mysql_error());
            }
            
            //Make Players the current database
             $db_selected = mysql_select_db($this->dbname, $link);
            
            #Code for JSON Response
            $result = mysql_query("SELECT * FROM Player");
            
            $json = array();
            $total_records = mysql_num_rows($result);
            
            if($total_records >= 1){
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                    $json[] = $row;
                }
            }
            
            #Creating file for JSON to read from for response html page http://localhost/~randallmeyer/Players.html
            $fp=fopen('players_mysql.php','w');
            fwrite($fp, json_encode($json));
            fclose($fp);
            
            mysql_close($link);
        }
    }
    
    
    $playersDatabase = new PlayersDatabase();
    $playersDatabase->CreateDatabaseIfNoneExists();
    echo "HERE NOW!";
    
    //Called from javascript
    function SubmitSpin()
    {
        $playersDatabase = new PlayersDatabase();
        $playersDatabase->SubmitSpin();
    }
    
    /*#Server database connection info
    $servername = "localhost";
    $username = "root";
    $password = "pizza4321";
    $dbname = "Players";
    
    echo $servername."whats up";
    
    //Connecting to the mysql server
    $link = mysql_connect($servername, $username, $password);
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }
    
    //Make Players the current database
    $db_selected = mysql_select_db($dbname, $link);
    
    //Checking if the database exists, if it doesn't make it
    if (!$db_selected) {
        $file_content = file('Players.sql');
        $query = "";
        foreach($file_content as $sql_line){
            if(trim($sql_line) != "" && strpos($sql_line, "--") === false){
                $query .= $sql_line;
                if (substr(rtrim($query), -1) == ';'){
                    echo $query;
                    $result = mysql_query($query)or die(mysql_error());
                    $query = "";
                }
            }
        }
    }
    else {
        $sql = "INSERT INTO Player (PlayerID, Name, Credits, LifetimeSpins, SaltValue) VALUES (12345, 'Billy Bob',  5000, 1, 54634)";
        
        if (mysql_query($sql, $link)) {
            echo "New record created successfully";
        } else {
            //echo "Error: " . $sql . "<br>" . mysql_error() . "\n";
        }
        
        $sql = "INSERT INTO Player (PlayerID, Name, Credits, LifetimeSpins, SaltValue) VALUES (56789, 'Freddy Smith',  7000, 5, 657890)";
        
        if (mysql_query($sql, $link)) {
            echo "New record created successfully";
        } else {
            //echo "Error: " . $sql . "<br>" . mysql_error() . "\n";
        }
    }*/
    
    /*#Code for JSON Response
    $result = mysql_query("SELECT * FROM Player");
    
    $json = array();
    $total_records = mysql_num_rows($result);
    
    if($total_records >= 1){
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
            $json[] = $row;
        }
    }
    
    #Creating file for JSON to read from for response html page http://localhost/~randallmeyer/Players.html
    $fp=fopen('players_mysql.php','w');
    fwrite($fp, json_encode($json));
    fclose($fp);
    
    mysql_close($link);*/
    
    //Button Click Recieved from Javascript to fake an incoming client spin
    /*function SubmitSpin()
    {
        #Creating fake users with fake data and selecting 1 randomly from 5
        #Assuming we got this client data from somewhere
        $randNum = rand(1, 5);
        $playerID = "";
        $saltValue = "";
        $coinsWon = rand(1, 50);
        $coinsBet = rand(1, 50);
        
        if($randomNum == 1) {
            $playerID = "12345";
            $saltValue = "568turjfigkturjfhgod93";
        }
        else if($randNum == 2) {
            $playerID = "54321";
            $saltValue = "65w934jg924jfi85jgkf93";
        }
        else if($randNum == 3) {
            $playerID = "76543";
            $saltValue = "58gjtir9406kigjdiej32e";
        }
        else if($randNum == 4) {
            $playerID = "90876";
            $saltValue = "596jtigorkfie32049igkd";
        }
        else if($randNum == 5) {
            $playerID = "11111";
            $saltValue = "59tjgkri4320dekrofvgch";
        }

        $options = [
            'cost' => 11,
            'salt' => $saltValue,
        ];
        $passwordHash = password_hash("thiscanbeanything", PASSWORD_BCRYPT, $options);
        
        //Checking the player data and updating if it's valid
        UpdateOrCreateDataIfIsValid($PlayerID, $CoinsWon, $CoinsBet, $passwordHash);
    }*/
?>
