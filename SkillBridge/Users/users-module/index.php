<div id="third-submenu">
    <a href="index.php?page=settings&subpage=users">List Users</a> | <a href="index.php?page=settings&subpage=users&action=create">New User</a> | Search <input type="text"/>
</div>
<div id="subcontent">
    <?php
      switch($action){
                case 'post':
                    require_once 'users-module/create-post.php';
                break; 
                case 'edit':
                    require_once 'users-module/edit-post.php';
                break; 
                case 'create':
                    require_once 'users-module/create-user.php';
                break; 
                case 'modify':
                    require_once 'users-module/modify-user.php';
                break; 
                case 'profile':
                    require_once 'users-module/view-profile.php';
                break;
                case 'result':
                    require_once 'users-module/search-user.php';
                break;
                default:
                    require_once 'users-module/index.php';
                break; 
            }
    ?>
  </div>