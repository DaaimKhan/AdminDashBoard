<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<h1>Login</h1>

<?= $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'login'], 'id' => 'loginForm']) ?>
    <?= $this->Form->control('username', ['label' => 'Username']) ?>
    <?= $this->Form->control('password', ['label' => 'Password', 'type' => 'password']) ?>
    <?= $this->Form->button('Login', ['id' => 'loginButton']) ?>
<?= $this->Form->end() ?>

<script>
    $(document).ready(function(){
        $('#loginButton').on('click', function(e){
            e.preventDefault();

            var formData = $('#loginForm').serialize();
            var username = $('#username').val();
            var password = $('#password').val();

        // Check if either username or password is empty
        if (username === '' || password === '') {
            alert('Please fill in both username and password.');
            return;
        }

            $.ajax({
                type: 'POST',
                url: '/users/login',
                data: formData,
                success: function(response){
                    if (response == 'success'){
                        console.log('Login successful!');
                        window.location.href = '/users/dashboard';
                    }else {
                       
                        alert('Account is not active.');
                    }
                },
                error: function(error){
                    console.error('AJAX Error:', error);
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>