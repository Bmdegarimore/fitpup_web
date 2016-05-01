<?php
session_start();
    include("model/eventModel.php");
    //set $uidstr to equal the session id
    $uidstr = $_SESSION['user_uniqueId'];
    
    
   //Get action
   if(isset($_GET['action'])){
    $action=$_GET['action'];    
   }
   
   //Post used to confirm change in contact
   if($_POST['update']== 'update'){
    $action = 'update';
   } else if($_POST['update']== 'delete'){
    $action = 'delete';
   } else if($_POST['update']== 'add'){
    $action = 'insert';
   }
   
   echo($_POST['update']);
   
   // Initialize Database
   $db = new EventModel();
   $db->connect();
 
   //Help pass contacts to different switches
   $events = $db->selectEvents($uidstr);
   //print_r($events);
   
   switch($action){
       
    //Save event after edit
    case "update":
        $updatedTitle = $_POST['eventTitle'];
        $updatedDate = $_POST['eventDate'];
        $updatedNotes = $_POST['notes'];
        $existingEventID = $_POST['eventID'];
        $userID = $uidstr;
        echo('Title:'.$updatedTitle);
        echo('date:'.$updatedDate);
        echo('note:'.$updatedNotes);
        echo('eventID'.$eventID);
        echo('user'.$userID);
        //save to db
        $db->updateEvent($updatedTitle,$updatedDate,$updatedNotes,$existingEventID,$userID);
        
        //reload display
        $events = $db->selectEvents($userID);
        
        // Load show events
        include_once 'view/showEvents.php';
        break;
       
    //Delete an event
    case "delete":
        $eventIDInfo = $_POST['eventID'];
        $loginIDInfo = $uidstr;
        
        echo('Event:'.$eventIDInfo);
        echo('Login:'.$loginIDInfo);
        
        $db->deleteEvent($eventIDInfo,$loginIDInfo);
        
        //reload display
        $events = $db->selectEvents($uidstr);
        // Load show events
        include_once 'view/showEvents.php';
        break;
       
    //Insert an event
    case "insert":
        //Grab Posts to insert into database
        $newTitle = $_POST['eventTitle'];
        $newDateTime= $_POST['eventDate'];
        $newNotes = $_POST['notes'];
        $existingUser = $uidstr;
        
        //Attempt to insert into database
        $db->insertEvent($newTitle,$newDateTime,$newNotes,$existingUser);
        //reload display
        $events = $db->selectEvents($uidstr);
        // Load show events
        include_once 'view/showEvents.php';
        break;
       
    //Display list
    default:
        include 'view/showEvents.php';
        
    
   }
?>