<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<title>Register</title>
<link type="text/css" rel="stylesheet" href="style.css" />
</head>

<body>
<div id="loginform">
<form action="register.php" method="post">
	First Name:<br />
    <input type="text" name="firstname" value="" />
    <br /><br />
    Last Name:<br />
    <input type="text" name="lastname" value="" />
    <br /><br />
    E-Mail:<br />
    <input type="text" name="email" value="" />
    <br /><br />
    Password:<br />
    <input type="password" name="password" value="" />
    <br /><br />
    Verify Password:<br />
    <input type="password" name="vpassword" value="" />
    <br /><br />
    <input type="submit" value="Register" />
</form>
</div>
</body>
</html>