
<?php
/* include the class file (global - within application) */
include_once 'classes/class.user.php';
include 'config/config.php';

$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';
$subpage = (isset($_GET['subpage']) && $_GET['subpage'] != '') ? $_GET['subpage'] : '';
$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';
$id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : '';

$user = new User();
if(!$user->get_session()){
	header("location: login.php");
}
$user_id = $user->get_user_id($_SESSION['user_email']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Skill Bridge</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Assistant&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css?<?php echo time();?>">
</head>
<body>

<div id="navbar">
  <div class="logo">
  <a href="index.php?page=home"><img src="css/images/icon.png" class="logo"></a>
  </div>
  <div class="nav-buttons">
  <a href="index.php?page=home" <?php if($page == 'home') echo 'class="active"'; ?>>Home</a>
  <a href="index.php?page=jobs" <?php if($page == 'jobs') echo 'class="active"'; ?>>Jobs</a>
    <!-- <a href="index.php?page=list">Messages</a> -->
    <!-- <a href="index.php?page=profile&id=<?php echo $user_id;?>">Profile</a> -->
    <!-- <a href="index.php?page=settings">Settings</a> -->
    <a href="index.php?page=profile&id=<?php echo $user_id;?>">
      <span><?php echo $user->get_user_lastname($user_id).', '.$user->get_user_firstname($user_id);?></span>
    </a>
    <a href="logout.php">Log Out</a>
  </div>
</div>

  <div id="content">
    <?php
      switch($page){
                case 'home':
                    require_once 'users-module/landing.php';
                break;
                case 'jobs':
                    require_once 'users-module/main.php';
                break;
                case 'settings':
                    require_once 'settings-module/index.php';
                break;
                case 'post':
                    require_once 'users-module/create-post.php';
                break;
                case 'list':
                    require_once 'users-module/list.php';
                break;
                case 'create':
                    require_once 'users-module/create-user.php';
                break;
                case 'edit':
                    require_once 'users-module/edit-post.php';
                break;
                case 'module_xxx':
                    require_once 'module-folder';
                break;
                case 'profile':
                    require_once 'users-module/view-profile.php';
                break;
                case 'view':
                    require_once 'users-module/view-applicants.php';
                break;
                default:
                    require_once 'users-module/main.php';
                break;
            }
    ?>
  </div>
</div>

</body>

<script src="jscript/script.js"></script>
</html>