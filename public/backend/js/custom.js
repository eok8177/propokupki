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
        if($(this).attr('href')){
            $.ajax({
                type: 'DELETE',  // destroy Method
                url: $(this).data('href')
            }).done(function (data) {
                console.log(data);
                location.reload(true);
            });
        } else {
            $(this).parent('.delete-block').remove();
        }
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

    //Select limit
    $('#limit').on('change', function (e) {
        e.preventDefault();
        var item = $(this).val();
        console.log(item);
        window.location.href = window.location.href.split('?')[0] + "?limit="+item;
    });

    //Select status
    $('#status').on('change', function (e) {
        e.preventDefault();
        var item = $(this).val();
        console.log(item);
        window.location.href = window.location.href.split('?')[0] + "?status="+item;
    });

    //Change search
    $('.btn-search').on('click', function (e) {
        e.preventDefault();
        var item = $('#search').val();
        console.log(item);
        window.location.href = window.location.href.split('?')[0] + "?search="+item;
    });


    //Live search shops
    $('#shop_search').keyup( function(e) {
        e.preventDefault();
       var string = $(this).val();
       if (string.length >= 3){
           var data = {
               str:  string
           }
           $.post({
               type: 'POST',
               url: $(this).data('href'),
               data: data,
               dataType: 'json'
           }).done(function (data) {
               console.log(data);
               var html_code = '<ul class="actions">';
                html_code += data;
                html_code += '</ul>';

               $('.search-result').html(html_code);
               console.log(html_code);
               // if (status == 1) {
               //     item.attr( 'checked', true );
               // } else {
               //     item.removeAttr('checked');
               // }
           });
       }
    });


});