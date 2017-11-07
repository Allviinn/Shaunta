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
			$edit_user_id = $_GET['edit'];
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

?>

<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add a new'); ?> User</h2><hr>
<div class="container">
	<form action="users.php?<?=((isset($_GET['edit']))?'edit='.$edit_user_id:'add=1'); ?>" method="post">
		<div class="form-group col-md-6">
			<label for=name>Full Name :</label>
			<input type="text" name="name" id="name" class="form-control" value="<?=$name;?>">
		</div>
		<div class="form-group col-md-6">
			<label for=email>Email :</label>
			<input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
		</div>
		<div class="form-group col-md-6">
			<label for=password><?=((isset($_GET['edit']))?'New ':''); ?>Password :</label>
			<input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
		</div>
		<div class="form-group col-md-6">
			<label for=confirm>Confirm Password :</label>
			<input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
		</div>
		<div class="form-group col-md-6">
			<label for=permissions>Permissions :</label>
			<select class="form-control" name="permissions">
				<option value=""<?=(($permissions == "")?" selected":"");?>></option>
				<option value="editor"<?= (($permissions == "editor")?" selected":"");?>>Editor</option>
				<option value="admin,editor"<?= (($permissions == "admin,editor")?" selected":"");?>>Admin</option>
			</select>
		</div>
		<div class="form-group col-md-6 text-right" style="margin-top:25px;">
			<a href="users.php" class="btn btn-default">Cancel</a>
			<input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add'); ?> User" class="btn btn-primary">
		</div>
	</form>
</div>
<?php
} else 
{



	$userQuery = $db->query("SELECT * FROM users ORDER BY full_name");
	
	
	?>
	<h2 class="text-center">Users</h2>
	<a href="users.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add New User</a>
	<hr>
	<div class="container">
		<table class="table table-bordered table-stripped table-condensed">
		
			<thead>
				<th></th>
				<th>Name</th>
				<th>Email</th>
				<th>Join Date</th>
				<th>Last Login</th>
				<th>Permissions</th>
			</thead>
		
			<tbody>
			<?php while($user = mysqli_fetch_assoc($userQuery)): ?>
		
				<tr>
					<td align="center">
						<?php if($user['id'] != $user_data['id']):?>
							<a href="users.php?delete=<?=$user['id'];?>" class="btn btn-xs btn-default">
								<span class="glyphicon glyphicon-remove"></span>
							</a>
						<?php endif;?>
						<a href="users.php?edit=<?=$user['id'];?>" class="btn btn-xs btn-default">
								<span class="glyphicon glyphicon-edit"></span>
							</a>
					</td>
					<td><?=$user['full_name'];?></td>
					<td><?=$user['email'];?></td>
					<td><?=pretty_date($user['join_date']);?></td>
					<td><?=(($user['last_login'] == $user['join_date'])?'Never':pretty_date($user['last_login']));?></td>
					<td><?=$user['permissions'];?></td>
				</tr>
		
			<?php endwhile; ?>
			</tbody>
		
		</table>
	</div>
	
	
	<?php
}
include "includes/footer.php";
	

