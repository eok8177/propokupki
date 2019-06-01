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
    $('.checkbox').on('click', function (e) {
        e.preventDefault();
        var item = $(this).find('input');
        console.log(item);
        $.post({
            type: 'PUT',
            url: item.data('href'),
            dataType: 'json'
        }).done(function (status) {
            if (status == 1) {
                item.attr( 'checked', true );
            } else {
                item.removeAttr('checked');
            }
        });
    });

    //Change status of record
    $('.custom-select').on('change', function (e) {
        e.preventDefault();
        var item = $(this).val();
        console.log(item);
        window.location.href = window.location.href + "?status="+item;
    });

});