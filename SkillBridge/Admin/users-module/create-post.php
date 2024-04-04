
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Post</title>
    <link rel="stylesheet" href="css/custom.css?<?php echo time();?>">
</head>
<body>
    <h3>Create Job Post</h3>
    <div id="form-block">
    <form action="processes/process.user.php?action=create_post" method="POST">
        <label for="title">Title:</label><br>
        <input type="text" class="input" id="title" name="title" required><br>
        
        <label for="description">Description:</label><br>
        <textarea id="description" class="input" name="description" required></textarea><br>
        
        <label for="company">Company:</label><br>
        <input type="text" id="company" class="input" name="company" required><br>
        
        <label for="location">Location:</label><br>
        <input type="text" id="location" class="input" name="location" required><br>
        
        <label for="salary">Salary:</label><br>
        <input type="number" id="salary" class="input" name="salary" required><br>
        
        <div id="button-block">
        <input type="submit" value="Post">
        </div>
    </form>
    </div>
</body>
</html>
