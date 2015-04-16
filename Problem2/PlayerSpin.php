<!-- Randall Meyer 4/16/2015 -->

<?php
    // Connect to MySQL
    $link = mysql_connect('localhost', 'root', 'pizza4321');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }
    
    //Make spinCollections the current database
    $db_selected = mysql_select_db('spinCollections', $link);
    
    //Checking if the database exists, if it doesn't make it
    if (!$db_selected) {
        $file_content = file('spinCollections.sql');
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
    }
    
    
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
?>
