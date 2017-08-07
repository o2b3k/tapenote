const deleteCountryForm = $('#delete-country-form');
$('.btn-delete').click(function () {
    if (confirm('Do you really want tu delete this country ?')) {
        var $this = $(this);
        deleteCountryForm.find('#country_id').val($this.data('id'));
        deleteCountryForm.submit();
    }
});