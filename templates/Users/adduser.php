<h1>Add User</h1>
 
<div class="errorrs" role="alert">
</div>
 
<?= $this->Form->create(NULL, ['id' => 'userForm', 'url' => ['controller' => 'Users', 'action' => 'adduser']], array('url' => '/users/adduser')) ?>
<?= $this->Form->control('name', ['id' => 'name']) ?>
<?= $this->Form->control('username', ['id' => 'username']) ?>
<?= $this->Form->control('status', ['type' => 'select','options' => ['inactive' => 'Inactive', 'active' => 'Active']])?>
<?= $this->Form->control('password', ['type' => 'password', 'id' => 'password']) ?>
<?= $this->Form->control('confirmpassword', ['type' => 'password', 'id' => 'confirmpassword']) ?>
<?= $this->Form->button(__('Add'), ['id' => 'signupButton']) ?>
<?= $this->Form->end() ?>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Include the validation script -->
<script src="https://yourcdn.com/path/to/your/validation/script.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('userForm');
            var nameInput = document.getElementById('name');
            var usernameInput = document.getElementById('username');
            var passwordInput = document.getElementById('password');
            var confirmPasswordInput = document.getElementById('confirmpassword');

            // Add input event listeners for real-time validation
            nameInput.addEventListener('input', validateName);
            usernameInput.addEventListener('input', validateUsername);
            passwordInput.addEventListener('input', validatePassword);
            confirmPasswordInput.addEventListener('input', validateConfirmPassword);

            form.addEventListener('submit', function (event) {
                // Trigger initial validation before form submission
                validateName();
                validateUsername();
                validatePassword();
                validateConfirmPassword();

                // Prevent form submission if there are errors
                if (document.querySelectorAll('.error-message').length > 0) {
                    event.preventDefault();
                }
            });

            function validateName() {
                clearError(nameInput);

                var nameValue = nameInput.value.trim();
                if (nameValue === '') {
                    displayError(nameInput, 'Name cannot be blank.');
                } else if (containsNumbersOrSpecialChars(nameValue)) {
                    displayError(nameInput, 'Invalid name format. No numbers or special characters allowed.');
                } else if (containsMultipleSpaces(nameValue)) {
                    displayError(nameInput, 'Invalid name format. Only one space allowed between words.');
                }
            }

            function validateUsername() {
                clearError(usernameInput);

                var usernameValue = usernameInput.value.trim();
                if (usernameValue === '') {
                    displayError(usernameInput, 'Username cannot be blank.');
                } else if (containsSpecialChars(usernameValue)) {
                    displayError(usernameInput, 'Invalid username format. Special characters are not allowed.');
                } else if (containsSpace(usernameValue)) {
                    displayError(usernameInput, 'Space not allowed in username.');
                } else if (usernameValue.length < 8) {
                    displayError(usernameInput, 'Username must contain at least 8 characters.');
                }
            }

            function validatePassword() {
                clearError(passwordInput);

                var passwordValue = passwordInput.value;
                if (passwordValue === '') {
                    displayError(passwordInput, 'Password cannot be blank.');
                } else if (containsSpecialChars(passwordValue)) {
                    displayError(passwordInput, 'Invalid password format. Special characters are not allowed.');
                } else if (passwordValue.length < 8) {
                    displayError(passwordInput, 'Password must be at least 8 characters long.');
                } else if (containsSpace(passwordValue)) {
                    displayError(passwordInput, 'Password should not contain spaces.');
                }
            }

            function validateConfirmPassword() {
                clearError(confirmPasswordInput);

                var confirmPasswordValue = confirmPasswordInput.value;
                if (confirmPasswordValue === '') {
                    displayError(confirmPasswordInput, 'Please confirm your password.');
                } else if (confirmPasswordValue !== passwordInput.value) {
                    displayError(confirmPasswordInput, 'Passwords do not match.');
                }
            }

            function containsNumbersOrSpecialChars(value) {
                return /[^a-zA-Z\s]/.test(value);
            }

            function containsSpecialChars(value) {
                return /[^a-zA-Z0-9\s]/.test(value);
            }

            function containsSpace(value) {
                return /\s/.test(value);
            }

            function containsMultipleSpaces(value) {
                return /\s{2,}/.test(value);
            }

            function displayError(inputElement, errorMessage) {
                var errorElement = document.createElement('div');
                errorElement.className = 'error-message';
                errorElement.textContent = errorMessage;
                inputElement.parentNode.appendChild(errorElement);
            }

            function clearError(inputElement) {
                var existingError = inputElement.parentNode.querySelector('.error-message');
                if (existingError) {
                    existingError.parentNode.removeChild(existingError);
                }
            }

            function clearErrors() {
                var errorElements = document.querySelectorAll('.error-message');
                errorElements.forEach(function (element) {
                    element.parentNode.removeChild(element);
                });
            }
        });
</script>
 

<script>
    $(function() {
        $('#signupButton').click(function(e) {
            e.preventDefault();
            var formData = $('#userForm').serialize();
            $.ajax({
                url: '/users/adduser',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Add successful!');
                        window.location.href = '<?= $this->Url->build(['controller' => 'Users', 'action' => 'dashboard']) ?>';
                    } else {
                        let error = '';
                        response.error.forEach(function(err){
                            error += `<div class="alert alert-danger" role="alert">${err}</div>`;
                        })
                        $('.errorrs').html(error);                       
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
 
            });
            return false;
        });
    });

    
</script>