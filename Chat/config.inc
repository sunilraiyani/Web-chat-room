 <?php

    // These variables define the connection information for MySQL database
    $username = "root";
    $password = "";
    $host = "localhost";
    $dbname = "user_database";

    
    //Tell the MySQL server that we want to communicate with it using UTF-8
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    
    try
    {
        $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
    }
    catch(PDOException $ex)
    {
        die("Failed to connect to the database: " . $ex->getMessage());
    }
    
    // Configures PDO to throw an exception when it encounters
    // an error.
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configures PDO to return database rows from your database using an associative
    // array.
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    //Undo magic quotes.
    if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
    {
        function undo_magic_quotes_gpc(&$array)
        {
            foreach($array as &$value)
            {
                if(is_array($value))
                {
                    undo_magic_quotes_gpc($value);
                }
                else
                {
                    $value = stripslashes($value);
                }
            }
        }
    
        undo_magic_quotes_gpc($_POST);
        undo_magic_quotes_gpc($_GET);
        undo_magic_quotes_gpc($_COOKIE);
    }
    
    // Tells the web browser that it should 
	// submit content back to you using UTF-8
    header('Content-Type: text/html; charset=utf-8');
    
    session_start();
