
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
</head>

<body>
<h3>Messages</h3>
    <div id="third-submenu">
        <a onclick="filterUsers('All')">All</a> | 
        <a onclick="filterUsers('Employer')">List Employers</a> | 
        <a onclick="filterUsers('Applicant')">List Applicants</a> | 
        Search<input type="text" id="searchInput" placeholder="Search..." oninput="searchUsers(this.value)">

        <table id="data-list">
            <tr>
                <th onclick="sortTable(0)">#</th>
                <th onclick="sortTable(1)">Name</th>
                <th onclick="sortTable(2)">Access Level</th>
                <th onclick="sortTable(3)">Email</th>
            </tr>
            <?php
            $count = 1;
            // Check if search query is set
            if (isset($_GET['search'])) {
                // Sanitize and set the search query
                $searchQuery = trim($_GET['search']);
                // Call search_user() method to get the list of users based on the search query
                $users = $user->search_user($searchQuery);
                // Iterate through the retrieved users
                if($users != false){
                    foreach($users as $value){
                        extract($value);
                        ?>
                        <tr>
                            <td><?php echo $count;?></td>
                            <td><a href="index.php?page=settings&subpage=users&action=profile&id=<?php echo $user_id;?>"><?php echo $user_lastname.', '.$user_firstname;?></a></td>
                            <td><?php echo $user_access;?></td>
                            <td><?php echo $user_email;?></td>
                        </tr>
                        <?php
                        $count++;
                    }
                } else {
                    echo "<tr><td colspan='4'>No Record Found.</td></tr>";
                }
            } else {
                // If search query is not set, display all users
                if($user->list_users() != false){
                    foreach($user->list_users() as $value){
                        extract($value);
                        ?>
                        <tr>
                            <td><?php echo $count;?></td>
                            <td><a href="index.php?page=settings&subpage=users&action=profile&id=<?php echo $user_id;?>"><?php echo $user_lastname.', '.$user_firstname;?></a></td>
                            <td><?php echo $user_access;?></td>
                            <td><?php echo $user_email;?></td>
                        </tr>
                        <?php
                        $count++;
                    }
                } else {
                    echo "<tr><td colspan='4'>No Record Found.</td></tr>";
                }
            }
            ?>
        </table>
    </div>
    <script src="jscript/script.js"> </script>
</body>
</html>
