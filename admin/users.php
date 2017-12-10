<?php
require_once "../core/init.php";

if(!is_logged_in())
{
	login_error_redirect();
}

if(!has_permission('admin'))
{
	permission_error_redirect('index.php');
}

//rednu du template
$loader = new Twig_Loader_Filesystem('templates_admin');
$twig = new Twig_Environment($loader, [
		'cache' => false
	]);

$twig->addExtension(new MonExtension());

include "includes/head.php";
include "includes/navigation.php";

//delete user
if(isset($_GET['delete']))
{
	$delete_id = sanitize($_GET['delete']);
	$db->query("DELETE FROM users WHERE id = '$delete_id'");
	$_SESSION['success_flash'] = 'User has been deleted.';
	header('Location: users.php');
}

$get_add = false;
$get_edit = false;
$edit_user_id = '';
$name = '';
$email = '';
$password = '';
$confirm = '';
$permissions = '';
$user_list_array = [];
if(isset($_GET['add']))
{
	$get_add = true;
}

if(isset($_GET['edit']))
{
	$get_edit = true;
}

if(isset($_GET['add']) || isset($_GET['edit']))
{ 

$name = ((isset($_POST['name']))?sanitize($_POST['name']):'');
$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$permissions = ((isset($_POST['permissions']))?sanitize($_POST['permissions']):'');

$errors = array();

	if(isset($_GET['edit']))
	{
		$edit_user_id = sanitize($_GET['edit']);
		$edit_user_query = $db->query("SELECT * FROM users WHERE id = '$edit_user_id'");
		$edit_user = mysqli_fetch_assoc($edit_user_query);
		$name = ((isset($_POST['name']) && $_POST['name'] != "")?sanitize($_POST['name']):$edit_user['full_name']);
		$email = ((isset($_POST['email']) && $_POST['email'] != "")?sanitize($_POST['email']):$edit_user['email']);
		$permissions = ((isset($_POST['permissions']) && $_POST['permissions'] != "")?sanitize($_POST['permissions']):$edit_user['permissions']);
	}

	if($_POST)
	{
		if(!isset($_GET['edit']))
		{
			$emailQuery = $db->query("SELECT * FROM users WHERE email = '$email'");
			$emailCount = mysqli_num_rows($emailQuery);
		
			if($emailCount != 0)
			{
				$errors[] = 'This email already exists in our database.';
			}
		}
	
		$required = array('name', 'email', 'password', 'confirm', 'permissions');
		foreach($required as $f)
		{
			if(empty($_POST[$f]))
			{
				$errors[] = 'You must fill out all fields.';
				break;
			}
		}
	
		if(strlen($password) < 6)
		{
			$errors[] = 'The password must be at least 6 characters.';
		}
	
		if($password != $confirm) 
		{
			$errors[] = 'The password does not match confirmed password.';
		}
	
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$errors[] = 'You must enter a valid email.';
		}
	
	
	
		if(!empty($errors))
		{
			echo display_errors($errors);
		} else 
		{
			//add user
			$current_date = time();
			$current_date = date("Y-m-d H:i:s",$current_date);
			$hashed = password_hash($password, PASSWORD_DEFAULT);
	
			
	
			if(isset($_GET['edit']))
			{
				$edit_user_id = sanitize($_GET['edit']);
				$insert_user = "UPDATE users SET full_name = '$name', email = '$email', password = '$hashed', permissions = '$permissions' WHERE id ='$edit_user_id'";
				$db->query($insert_user);
				$_SESSION["success_flash"] = 'User has been updated.';
			} else
			{
				$insert_user = "INSERT INTO users (full_name, email, password, join_date, last_login, permissions) VALUES ('$name', '$email', '$hashed', '$current_date', '$current_date', '$permissions')";
				$db->query($insert_user);
				$_SESSION["success_flash"] = 'User has been added.';
			}
			header('Location: users.php');
		}
	}

} else 
{
	$userQuery = $db->query("SELECT * FROM users ORDER BY full_name");
	while($user = mysqli_fetch_assoc($userQuery))
	{
		$user_list_array[] = $user;
	}
}

echo $twig->render('users.twig', [
		'get_edit' => $get_edit,
		'get_add' => $get_add,
		'edit_user_id' => $edit_user_id,
		'name' => $name,
		'email' => $email,
		'password' => $password,
		'confirm' => $confirm,
		'permissions' => $permissions,
		'user_data_id' => $user_data['id'],
		'user_list_array' => $user_list_array

	]);	
include "includes/footer.php";
	

