function deleteDog(event, dogId) {
    event.preventDefault();
    if (confirm('Are you sure you want to delete this dog?')) {
        $.ajax({
            type: 'POST',
            url: 'dog_delete.php',
            data: {
                dogId: dogId
            },
            success: function(response) {
                if (response === 'success') {
                    location.reload();
                } else {
                    alert('Failed to delete the dog.');
                }
            },
            error: function() {
                alert('An error occurred while deleting the dog.');
            }
        });
    }
}