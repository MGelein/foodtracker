<?php
/**
 * This file keeps track of all scoring and handles the backend.
 * We can do the following things:
 * - Change a score (of someone)
 * - Read a score (of someone)
 * 
 * So we give this file two GET parameters:
 * 'player' can be set to a player name, must be valid and existing data file name as well
 * 'method' can be set to either 'change|c' or 'read|r'
 * 'object' can be set to any operant of the method, so for change it is the amount, for read what we are reading
 * If either is not supplied the program will exit without doing anything
 */
if(isset($_GET['method']) && isset($_GET['player'])){
    //Retrieve the two variables from the GET array
    $method = $_GET['method'];
    $player = $_GET['player'];

    //Check if the player file exists (and thus if the player is legal)
    if(!file_exists("./data/$player.json")){
        //Unrecognized player name, do nothing
        exit();
    }
    //Load the file into memory, if we make it to here
    $file = json_decode(file_get_contents("./data/$player.json"));
    //If the last weekday has a larger number than the current (we started a new week), please start a new file
    if(date('w', $file->mostRecent->timestamp) > date('w')){
        
    }
    
    //Check if the method is legit
    if($method == 'change' || $method == 'c'){
        //Retrieve the change amount from the GET array
        $amt = $_GET['object'];
        //Only push transaction if the transaction is numeric
        if(is_numeric($amt)){
            //Add to the score, and update it
            $file->score += $amt;
            //Push a new transaction in there
            $transaction = array("timestamp" => time(), "amount" => $amt);
            $file->transactions[] = $transaction;
            //And set the shortcut to the most recent one
            $file->mostRecent = $transaction;
        }
        //And echo the resulting file
        echo json_encode($file);
    }else if($method == 'read' || $method == 'r'){
        //echo a json encode of the file, since we just want to read it anyway
        echo json_encode($file);
    }else{
        //Unrecognized argument, do nothing
        exit();
    }

    //After the transaction, please save the file back
    file_put_contents("./data/$player.json", json_encode($file));
}
?>