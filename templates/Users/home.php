<!-- src/Template/Users/home.ctp -->

<style>
    *{
        background-color: #8ec3eb;
    }
    
    .main-content{
        margin: 200px auto;
        width: 100%;
        text-align: center;
        padding: 100px;
        border-radius: 10px;
        background-color: white;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    h1{
        background-color: white;
        text-shadow: 10px 5px 5px #8ec3eb;
    }
    
    .links{
        background-color: white;
    }

    
</style>




<!-- <div class="nav">
    
</div> -->
<div class="main-content">
    <h1>Welcome to the Home Page</h1>

    <div class="links">
        <?= $this->Html->link('Login', ['controller' => 'Users', 'action' => 'login'], ['class' => 'button']) ?>
        <?= $this->Html->link('Sign Up', ['controller' => 'Users', 'action' => 'signup'], ['class' => 'button']) ?>
    </div>
</div>