<h1>Update</h1>
<?= $this->Html->link('Back', ['action' => 'userlist']) ?>
<?= $this->Form->create(null, ['id' => 'form']) ?>
    <?= $this->Form->control('id', ['value' => $id, 'readonly' => true, 'type' => 'hidden']) ?>
    <?= $this->Form->control('current_password') ?>
    <?= $this->Form->control('new_password', ['id' => 'password']) ?>
    <?= $this->Form->control('confirm_password') ?>
    <?= $this->Form->button('Update', ['id' => 'updateButton', 'type' => 'submit']) ?>
<?= $this->Form->end() ?>
 
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<?= $this->Html->script('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js') ?>
<!-- Your AJAX script -->
<script>
    $(document).ready(function() {
        $('#form').validate({
            rules: {
                current_password: {
                    required: true,
                },
                new_password: {
                    required: true,
                    minlength: 8,
                    maxlength: 16,
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password", // Make sure this matches the ID of the password input
                }
            },
            messages: {
                current_password: {
                    required: 'Please enter Current Password.',
                },
                new_password: {
                    required: 'Please enter Password.',
                    minlength: 'Password must be at least 8 characters long.',
                },
                confirm_password: {
                    required: 'Please enter Confirm Password.',
                    equalTo: 'Passwords do not match.',
                }
            },
            submitHandler: function (form, event) {
            e.preventDefault();
 
            $.ajax({
                type: 'POST',
                url: '<?= $this->Url->build(['controller' => 'Users', 'action' => 'update']) ?>',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.status == 'success' && response.message) {
                        alert('Password has been updated.');
                        window.location.href = '<?= $this->Url->build(['action' => 'userlist']) ?>';
                    } else {
                        alert('Unable to update the password. Please, try again.');
                    }
                },
                error: function () {
                    alert('An error occurred during the Ajax request.');
                }
            });
        }
    });
});
</script>