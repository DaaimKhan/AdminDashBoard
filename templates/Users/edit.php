<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<?= $this->Html->script('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js') ?>

<h1>Edit User</h1>
<?= $this->Form->create($user, ['id' => 'edit-form']) ?>
<?= $this->Form->control('name', ['label' => 'New Name', 'id' => 'name-input']) ?>
<?= $this->Form->button(__('Update Name')) ?>
<?= $this->Form->end() ?>
<?= $this->Html->link(__('Cancel'), ['action' => 'userlist']) ?>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('edit-form');
        var nameInput = document.getElementById('name-input');

        form.addEventListener('submit', function (event) {
            clearErrors();

            // Validate name
            var nameValue = nameInput.value.trim();
            if (nameValue === '') {
                displayError(nameInput, 'Name cannot be blank.');
            } else if (containsNumbersOrSpecialChars(nameValue)) {
                displayError(nameInput, 'Invalid name format. No numbers or special characters allowed.');
            } else if (containsMultipleSpaces(nameValue)) {
                displayError(nameInput, 'Invalid name format. Only one space allowed between words.');
            }

            // Prevent form submission if there are errors
            if (document.querySelectorAll('.error-message').length > 0) {
                event.preventDefault();
            }
        });

        function containsNumbersOrSpecialChars(name) {
            // Custom validation logic for numbers or special characters
            return /[^a-zA-Z\s]/.test(name);
        }

        function containsMultipleSpaces(name) {
            // Custom validation logic for multiple spaces
            return /\s{2,}/.test(name);
        }

        function displayError(inputElement, errorMessage) {
            var errorElement = document.createElement('div');
            errorElement.className = 'error-message';
            errorElement.textContent = errorMessage;
            inputElement.parentNode.appendChild(errorElement);
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
    $(document).ready(function () {
        $('#edit-form').submit(function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '<?= $this->Url->build(['controller' => 'Users', 'action' => 'edit', $user->id]) ?>',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        alert('User has been updated.');
                        window.location.href = '/users/userlist';
                        console.log(alert);
                    } else {
                        alert('Unable to update the user. Please, try again.' + response.message);
                    }
                },
                error: function () {
                    alert('You are not authorized to update name.');
                }
            });
        });
    });
</script>