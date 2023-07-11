$(function () {
    $("#sortable-list").sortable();
    $("#sortable-list").disableSelection();
});

function deleteGarden(event, gardenId) {
    event.preventDefault();
    if (confirm('Are you sure you want to delete this garden?')) {
        $.ajax({
            type: 'POST',
            url: 'garden_delete.php',
            data: {
                gardenId: gardenId
            },
            success: function (response) {
                if (response === 'success') {
                    location.reload();
                } else {
                    alert('Failed to delete the garden.');
                }
            },
            error: function () {
                alert('An error occurred while deleting the garden.');
            }
        });
    }
}
