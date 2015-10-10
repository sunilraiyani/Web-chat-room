<?php
    require("config.inc");
    
    if(!empty($_POST))
    {
        // Ensure that the user has entered a non-empty first name
        if(empty($_POST['firstname']))
        {
            die("Please enter your first name.");
        }
		// Ensure that the user has entered a non-empty last name
        if(empty($_POST['lastname']))
        {
            die("Please enter your last name.");
        }
		 // Make sure the user entered a valid E-Mail address
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        {
            die("Invalid E-Mail Address");
        }
		
		// Ensure that the user has entered a non-empty e-mail
        if(empty($_POST['email']))
        {
            die("Please enter an email.");
        }
        // Ensure that the user has entered a non-empty password
        if(empty($_POST['password']))
        {
            die("Please enter a password.");
        }
		
		// Ensure that the user has entered a non-empty password verification
		if(empty($_POST['vpassword']))
        {
            die("Please enter a password.");
        }
		// Ensure that the two passwords match
		if($_POST['password'] !== $_POST['vpassword'])
		{
			header("location: index.php");
			die("Passwords do not match.");
			
		}
 
        // SQL query to see whether the email entered by the
        // user is already in use. 
        $query = "
            SELECT
                1
            FROM users
            WHERE
                email = :email
        ";
        
        $query_params = array(
            ':email' => $_POST['email']
        );
        
        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }
        
        $row = $stmt->fetch();
        
		// If a row is returned. 
		if($row)
        {
            die("This email is already registered");
        }
        
        $query = "
            INSERT INTO users (
                firstname,
				lastname,
                password,
                salt,
                email
            ) VALUES (
                :firstname,
				:lastname,
                :password,
                :salt,
                :email
            )
        ";
        
        // The following statement generates a hex
        // representation of an 8 byte salt.
		
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
        

        $password = hash('sha256', $_POST['password'] . $salt);
        for($round = 0; $round < 65536; $round++)
        {
            $password = hash('sha256', $password . $salt);
        }
        
        $query_params = array(
            ':firstname' => $_POST['firstname'],
			':lastname' => $_POST['lastname'],
            ':password' => $password,
            ':salt' => $salt,
            ':email' => $_POST['email']
        );
        
        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }
        
        // This redirects the admin back to the registration page
        header("Location: index.php");
        die("Redirecting to Registration Page");
    }
    
?>

