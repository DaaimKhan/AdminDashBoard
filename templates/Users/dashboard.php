<?php 
 $session = $this->request->getSession();
 $role= $session->read('role');
 $adminId =$session->read('adminId');
 $guestId =$session->read('guest');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header img {
            height: 40px;
        }

        .nav-function{
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chngPswd{
            margin-right: 10px;
            padding: 10px;
            color: #fff; 
            text-decoration: none;
        }

        .logout{
            padding: 8px;
            border-radius: 10px;
            color: red; 
            text-decoration: none;
        }

        .logout:hover{
            color: #fff;
            text-decoration: none;
            background-color: #3498db;
        }

        .chngPswd{
            padding: 10px;
            border-radius: 10px;
        }

        .chngPswd:hover{
            color: white;
            background-color: green;
            text-decoration: none;
        }

        .links-container {
            display: flex;
            height: calc(100vh - 60px);
        }

        .sidebar {
            width: 20%;
            background-color: #2c3e50;
            padding: 20px;
            box-sizing: border-box;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .welcome {
            width: 80%;
            padding: 20px;
            box-sizing: border-box;
        }

        .welcome h1 {
            color: #333;
        }
    </style>

</head>
<body>
    <header>
        <img src="" alt="Logo">
        <div class="nav-function">
        <a href="update/<?php
            if ($role == 'Admin'){
                echo $adminId;
            } else {
                echo $guestId;
            }?>" 
            class="chngPswd">Change Password
        </a>
        <a href="logout" class="logout">Logout</a>
        </div>
        
        
    </header>
 
    <div class="links-container">
        <div class="sidebar">
            <a class="dashboard" href="#">Dashboard</a><br><br>
            <a class="users" href="userlist">Users</a>
        </div>
 
        <div class="welcome">
            <h1>Welcome to Your Dashboard</h1>
        </div>
    </div>
</body>
</html>