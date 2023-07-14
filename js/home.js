$(document).ready(function() {
    $('#hugButton').click(function() {
        $.ajax({
            type: 'POST',
            url: 'update_hugs.php',
            data: {
                dogId: dogId 
            },
            success: function(response) {
                $('#hugCounter').text(response);
            }
        });
    });
});
