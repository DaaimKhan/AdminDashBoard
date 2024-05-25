<?php 
 $session = $this->request->getSession();
 $role= $session->read('role');
 $adminId = $session->read('adminId');
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
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

        .adduser{
            padding: 10px;
            border-radius: 10px;
        }

        .adduser:hover{
            background-color: green;
        }

        .logout{
            padding: 10px;
            border-radius: 10px;
            color: red;
        }

        .logout:hover{
            background-color: #3498db;
            color: white;
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
            height: 100%;
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

        .user-list {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .user-list tr {
            border-bottom: 1px solid #ddd;
        }

        .user-list th, .user-list td {
            padding: 12px;
            text-align: left;
        }

        .user-list th {
            background-color: #f9f9f9;
            color: #333;
        }

        .user-list td a {
            display: inline-block;
            padding: 8px 12px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .user-list td a:hover {
            background-color: #2980b9;
        }

        span.disabled-link {
            color: #888;
            text-decoration: none;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <header>
        <img src="" alt="Logo">
        <div>
            <?php
            if($role==='Admin'){
                echo "<a class='adduser' href='adduser' style='color: #fff; text-decoration: none;'>Add User</a>";
            }
            ?>
            
            <a href="logout" class="logout" style=" text-decoration: none;">Logout</a>
        </div>
    </header>

    <div class="links-container">
        <div class="sidebar">
            <a class="dashboard" href="dashboard">Dashboard</a><br><br>
            <a class="users load-users" href="userlist">Users</a>
        </div>

        <table class="user-list">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Username</th>
                    <?php if ($role === 'Admin'):?>
                        <th>Status</th> 
                        <th>Actions</th>
                    <?php endif;?>                            
                    <?php if ($role === 'Guest'):?>
                        <th class="disabled-link">Status</th> 
                        <!-- <th class="disabled-link">Actions</th> -->
                    <?php endif;?>
                </tr>
            </thead>
            <tbody>
                <?php
                $serialNo = 1;
                foreach ($results as $row):
                ?>
               <?php echo "<tr id='userRow".$row->id."'>
                    <td>".$serialNo++."</td>";?>
                    <td><?= h($row->name) ?></td>
                    <td><?= h($row->username) ?></td>
                    
                    <?php 
                        if ($role === 'Admin') {
                            if($row->id != $adminId){
                                echo "<td><span><a href='javascript:' class='changeStatus' user_id='$row->id' user_status='$row->status'>$row->status</a></span></td>";
                                echo "<td class='action-column'>
                                        <span><a href='".$this->Url->build(["controller" => "Users","action" => "edit",$row->id])."'>Edit</a></span>
                                        <span><a href='".$this->Url->build(["controller" => "Users","action" => "update",$row->id])."'>Update &nbsp</a></span>
                                        <span><a href='javascript:' class='delete' user-id='$row->id'>Delete &nbsp</a></span>
                                    </td>";
                            } elseif ($row->id == $adminId) {
                            echo "<td><span class='disabledLink><a href='javascript:' class='changeStatus' user_id='$row->id' user_status='$row->status'>$row->status</a></span></td>";  
                            }  
                        }
                        else if ($role === 'Guest') {
                            echo "<td>".$row->status."</td>";
                            // echo "<td>
                            //         <span>Update/ &nbsp</span>
                            //         <span>Delete/ &nbsp</span>";
                            // if ($row->id == $session->read('session_id')) {
                            //     echo "<span><a href='".$this->Url->build(["controller" => "Users","action" => "edit",$row->id])."'>Edit</a></span>";
                            // } else {
                            //     echo "<span>Edit</span>";
                            // }
                        }
                        ?>            
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
    //       function changeStatus(userId) {
    //     if (confirm('Are you sure you want to change the status')) {
    //      window.location.href = 'status/' + userId;
    //     } else {
    //         // User clicked 'Cancel', do nothing or provide feedback
    //     }
    // }

    

        $(".changeStatus").click(function(event){
            var user_status = $(this).attr('user_status');
            var userId = $(this).attr('user_id');
            var csrfToken = '<?= $this->request->getAttribute('csrfToken');?>';
            var $this = $(this);
            if (confirm('Are you sure you want to change user status with ID ' + userId + '?')) {
                $.ajax({
                url: 'status/'+userId,
                type: 'post',
                data :'status='+user_status+'&user_id='+userId+'&_csrfToken='+csrfToken,
                success: function(response) 
                {
                    if(response == 'success')
                    {
                        var check;
                        if (user_status == 'active') {
                            check = 'inactive';
                        } else {
                            check = 'active';
                        }
            
                        $this.attr('user_status', check);
                        var textToShow;
                        if (user_status == 'active') {
                            textToShow = 'inactive';
                        } else {
                            textToShow = 'active';
                        }
                        $this.text(textToShow);
                    }
                    else
                    {
                    alert(response);
                    }
                },
                    error: function(error) {
                    alert("Something went wrong!");
                }
                });
            }
        });

    </script>
    

    <script>
 $(".delete").click(function(event){
            var userId = $(this).attr('user-id');
            var csrfToken = '<?= $this->request->getAttribute('csrfToken');?>';
            var $this = $(this);
            if (confirm('Are you sure you want to delete with ID ' + userId + '?')) {
                $.ajax({
                url: 'delete/'+userId,
                type: 'post',
                data :'_csrfToken='+csrfToken,
                success: function(response) 
                {
                    if(response == 'success')
                    {
                        $('#userRow' + userId).remove();
                    }
                    else
                    {
                    alert(response);
                    }
                },
                    error: function(error) {
                    alert("Something went wrong!");
                }
                });
            }
        });
      

    </script>
</body>
</html>