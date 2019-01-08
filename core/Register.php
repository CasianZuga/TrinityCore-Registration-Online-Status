<?php 

if ( !isset($_POST['signup_button']) && $_POST['signup_button'] != "Signup" )
{
	exit;
}
require "Database.php";
$Database = new Database("localhost", "root", "yourdatabasepassword", "yourrealmdatabase");
try 
{
	$username = $Database->conn->escape_string($_POST['signup_username']);
	$email = $Database->conn->escape_string($_POST['signup_email']);
	$password = $Database->conn->escape_string($_POST['signup_password']);
	$password_repeat = $Database->conn->escape_string($_POST['signup_password_repeat']);

	if ( empty($username) || empty($email) || empty($password) || empty($password_repeat))
	{
		$Database->warning("Please Enter All Fields!", true);
	}


	# Username checks
	if ( ( filter_var($username, FILTER_SANITIZE_STRING) ) === false )
	{
		$Database->warning("Username given is not valid, please try again.", true);
	}

	if ( $Database->select("account", "username", $username, "username=?")->num_rows > 0 )
	{
		$Database->warning("Username given is already in use, please enter another one.", true);
	}

	# Email Checks
	if ( (filter_var($email, FILTER_VALIDATE_EMAIL) ) === false )
	{
		$Database->warning("Email given is not valid, please try again.", true);
	}

	if ( $Database->select("account", "email", $email, "email=?")->num_rows > 0 )
	{
		$Database->warning("Email given is already in use, please enter another one.", true);
	}

	# Password Checks
	if ( $password !== $password_repeat )
	{
		$Database->warning("Both password don't match!", true);	
	}

	##

	$hash_password = sha1( $username .":". $password );

	if ( $Database->insert("account", array($username, $email, $hash_password), array("username", "email", "sha_pass_hash")) === true )
	{
		$Database->success("Account Created Successfully!");
	}
} 
catch (Exception $e)
{
	$Database->error("There was an unexpected error", $e);
}