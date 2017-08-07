const deleteCategoryForm = $('#delete-category-form');
$('.btn-delete-category').click(function () {
    if (confirm('Do you really want to delete this category ?')) {
        var $this = $(this);
        deleteCategoryForm.find('#category_id').val($this.data('id'));
        deleteCategoryForm.submit();
    }
});