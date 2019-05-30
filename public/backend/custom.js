$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //Delete record
    $('.btn-delete').on('click', function (e) {
        if (!confirm('Are you sure you want to delete?')) return false;
        e.preventDefault();
        console.log($(this).attr('href'));
        // return;

        $.ajax({
            type: 'DELETE',  // destroy Method
            url: $(this).data('href')
        }).done(function (data) {
            console.log(data);
            location.reload(true);
        });
    });

    //Change status of record
    $('.status').on('click', function (e) {
        e.preventDefault();
        var item = $(this);
        $.post({
            type: 'PUT',
            url: $(this).attr('href'),
            dataType: 'json'
        }).done(function (status) {
            if (status == 1) {
                item.removeClass('fa-times text-danger').addClass('fa-check text-success');
            } else {
                item.removeClass('fa-check text-success').addClass('fa-times text-danger');
            }
        });
    });

});