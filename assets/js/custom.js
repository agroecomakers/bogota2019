$('#register').submit(function(e) {
    e.preventDefault();
    var $this = $(this);
    console.log($this.serialize());
    $('#responses').addClass('d-none');
    $('#responses-list').html('');
    $('.form-group').removeClass('has-danger');
    $.ajax({
        type: "POST", // GET & url for json slightly different
        url: 'insert.php',
        data: $this.serialize(),
        dataType: 'json',
        //contentType: 'application/json; charset=utf-8',
        error: function(res, text){
            console.log('Err', res);
        },
        success     : function(data) {
            console.log(data);
            if (data.status) {
                $('#responses').addClass('d-none');
                $('#responses-list').html('');
                $('.form-group').removeClass('has-danger');

                $('#message-confirmation').removeClass('d-none');
                $('#div-register').addClass('d-none');
                $('#btn-register').addClass('d-none');
            } else {
                if (data.hasOwnProperty('validation')){
                    $('#responses').removeClass('d-none');
                    $.each(data.validation, function ($index, $item) {
                        $('#' + $item['id']).parent('div').addClass('has-danger');
                        $('#responses-list').append('<li> ' + $item['message'] + '</li>');
                    });
                }
            }
        }
    });
    return false;
});