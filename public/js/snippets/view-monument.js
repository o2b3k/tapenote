$('#upload_photo').click(function () {
    $('#file').val('');
    $('#description').val('');
    $('#upload-image-modal').modal('show');
});

$('#upload_button').click(function () {
    $('#upload_form').submit();
});

$('.btn-delete-image').click(function () {
    if (confirm("Do you want to delete this photo ?")) {
        var deleteImageForm = $('#delete-image-form');
        deleteImageForm.find('#image_id').val($(this).data('id'));
        deleteImageForm.submit();
    }
});