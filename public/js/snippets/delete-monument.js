const deleteMonumentForm = $('#delete-monument-form');
$('.btn-delete-monument').click(function () {
    if (confirm('Do you really want tu delete this monument with it\'s photos ?')) {
        var $this = $(this);
        deleteMonumentForm.find('#monument_id').val($this.data('id'));
        deleteMonumentForm.submit();
    }
});