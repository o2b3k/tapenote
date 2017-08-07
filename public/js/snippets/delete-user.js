const deleteUserForm = $('#delete-user-form');
$('.btn-delete').click(function () {
    if (confirm('Do you really want tu delete this user ?')) {
        var $this = $(this);
        deleteUserForm.find('#user_id').val($this.data('id'));
        deleteUserForm.submit();
    }
});