<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link type="text/css" rel="stylesheet" href="style.css" />
</head>

<?php

    require("config.inc");
    
    // This variable will be used to re-display the user's username to them in the
    // login form if they fail to enter the correct password.
    $submitted_username = '';
    
    // This if statement checks to determine whether the login form has been submitted
    // If it has, then the login code is run, otherwise the form is displayed
    if(!empty($_POST))
    {
        // This query retreives the user's information from the database using
        // their username.
        $query = "
            SELECT
                id,
                firstname,
				lastname,
                password,
                salt,
                email
            FROM users
            WHERE
                email = :email
        ";
        
        // The parameter values
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
        
        // This variable tells us whether the user has successfully logged in or not.
        $login_ok = false;
        
        // Retrieve the user data from the database.  If $row is false, then the username
        // they entered is not registered.
        $row = $stmt->fetch();
        if($row)
        {
            // Using the password submitted by the user and the salt stored in the database,
            // we now check to see whether the passwords match by hashing the submitted password
            // and comparing it to the hashed version already stored in the database.
            $check_password = hash('sha256', $_POST['password'] . $row['salt']);
            for($round = 0; $round < 65536; $round++)
            {
                $check_password = hash('sha256', $check_password . $row['salt']);
            }
            
            if($check_password === $row['password'])
            {
                // If they do, then we flip this to true
                $login_ok = true;
            }
        }
        
        // If the user logged in successfully, then we send them to the private members-only page
        // Otherwise, we display a login failed message and show the login form again
        if($login_ok)
        {
            unset($row['salt']);
            unset($row['password']);

            $_SESSION['user'] = $row;
			$_SESSION['name']= $row['firstname'];
            $_SESSION['loginok']=true;
            // Redirect the user to the private members-only page.
            header("Location: index.php");
            //die("Redirecting to your private page.");
        }
        else
        {
            $_SESSION['loginok']=false;
			// Tell the user they failed
            //print("Login Failed.");
            //echo '<span class="error">Login Failed</span>';
			//header("Location: index.php");
            // Show them their username again so all they have to do is enter a new
            // password.  The use of htmlentities prevents XSS attacks.  You should
            // always use htmlentities on user submitted values before displaying them
            // to any users (including the user that submitted them).
            $_SESSION['su'] = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
			header("Location: index.php");
        }
    }
    
?>