$('#order_quantity').on('input', function() {
    $('#v-cal').addClass('hidden');
    $('#order').addClass('hidden');
    $('#check').show();
    if ($(this).val() > 0) {
        $('#check').prop('disabled', false);
    } else {
        $('#check').prop('disabled', true);
    }
});

$('#donate_quantity').on('input', function() {
    if ($(this).val() > 0) {
        $('#send-donation').prop('disabled', false);
    } else {
        $('#send-donation').prop('disabled', true);
    }
});

$('#order_type, #donate_type').on('change', function() {
    $('.order-type').toggleClass('fa-leaf fa-recycle');
});

$('#check').on('click', function(e) {
    var url = $('#check-availability-url').attr('data-url');
    var quantity = $('#order_quantity').val();
    var type = $('#order_type').val();
    url = url.replace('_type', type).replace('_quantity', quantity);
    console.log(url);

    $.get(url, function( data ) {
        if (data['date'] !== undefined) {
            $('#check').hide();
            vanillaCalendar.resetStartDate(new Date(data['date']));
            $('#v-cal').toggleClass('hidden');
            $('#availability-error').hide();
            $('#order').removeClass('hidden');
        } else {
            toastr.warning(data['error']);
        }
        //
    });

    // $.get(url, function( data ) {
    //     console.log(data);
    //
    // });

    e.preventDefault();
});
