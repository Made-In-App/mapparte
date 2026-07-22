jQuery(document).ready(function ($) {

    // Temporaneo: non disabilitare «invia richiesta» in attesa del preventivo
    // $(".booking-form button#send").prop("disabled", true);
    // Set a minimum for the end date
    end_date_min($('#start_date').attr('min'));

    // Disable the booking form if the usere is not logged in
    if (!booking.logged) {
        $(".booking-form input").prop("disabled", true);
        $(".booking-form select").prop("disabled", true);
        $(".booking-form textarea").prop("disabled", true);

	}

    $('#more-days').click(function () {
        $(".time-slot-wrapper").toggleClass('active');
        $('#end_date').val('');
        if (!$(this).prop("checked")) {
            set_time_slots();
        } else {
            $('#end_time').find('option').remove().end();
        }
    });

    $('#start_date').change(function () {
        if ($('#end_date').val() && $('#start_date').val() >= $('#end_date').val()) {
            $('#end_date').val('');
            $('#end_time').find('option').remove().end();
        }
        set_time_slots();
        end_date_min($(this).val());
    });

    $('#end_date').change(function () {
        set_end_time_slots();
    });

    $('#start_date, #end_time, #start_time, #end_date, #more-days, #coupon').change(function () {
        get_quote();
    });

    $('.booking-form #send').click(function () {
        // Temporaneo: evita disabilitazione al click (doppi invii possibili)
        // $(this).prop("disabled", true);
        request_booking();
    });

	$('#send-booking-request').click(function () {
		var $bookingForm = $('#booking-form');
		$bookingForm.removeClass('d-none').addClass('mobile-booking-open');
		$('.mbl-action-btn').addClass('d-none');

		$('html, body').animate({
			scrollTop: $bookingForm.offset().top - 80
		}, 200);

    });

    function end_date_min(startDatemin) {

        if (startDatemin) {
            var startDate = new Date(startDatemin);
            var endDate = new Date(startDate.setDate(startDate.getDate() + 1));
            var month = endDate.getMonth() + 1;
            var day = endDate.getDate();
            var year = endDate.getFullYear();
            if (month < 10) month = '0' + month.toString();
            if (day < 10) day = '0' + day.toString();
            var minDate = year + '-' + month + '-' + day;
            $('#end_date').attr('min', minDate);

        }

    }

    function set_time_slots() {

        if ($('#start_date').val()) {
            $.ajax({
                url: booking.restURL + 'mapparte/v1/availability/' + booking.spaceId,
                data: {
                    date: $('#start_date').val(),
                },
                method: 'GET',
                beforeSend: function (xhr) {
                    $('.spinner').show();
                    xhr.setRequestHeader('X-WP-Nonce', booking.restNonce)
                },
                success: function (availability) {
                    $('#start_time').find('option').remove().end();

                    if (!$('#end_date').val() && !$('#more-days').prop("checked")) $('#end_time').find('option').remove().end();

                    if (availability.data.length !== 0) {

                        $('#start_time').append($("<option></option>").attr("value", 0).text(booking.selectTimeLabel));
                        $('#end_time').append($("<option></option>").attr("value", 0).text(booking.selectTimeLabel));

                        $.each(availability.data, function (index, value) {
                            $('#start_time').append($("<option></option>").attr("value", value).text(value));
                            if (!$('#end_date').val() && !$('#more-days').prop("checked")) $('#end_time').append($("<option></option>").attr("value", value).text(shift_end_time(value)));
                        });
                    } else {
                        $('#start_time').append($("<option></option>").attr("value", 0).text(booking.notAvailableLabel));
                        if (!$('#end_date').val() && !$('#more-days').prop("checked")) $('#end_time').append($("<option></option>").attr("value", 0).text(booking.notAvailableLabel));
                    }
                    get_quote();
                },
                error: function (xhr, status, error) {
                    $('#open-modal-login').click();
                },
            }).complete(function () {
                $('.spinner').hide();
            });
        }
    }

    function set_end_time_slots() {

        if ($('#end_date').val()) {
            $.ajax({
                url: booking.restURL + 'mapparte/v1/availability/' + booking.spaceId,
                data: {
                    date: $('#end_date').val(),
                },
                method: 'GET',
                beforeSend: function (xhr) {
                    $('.spinner').show();
                    xhr.setRequestHeader('X-WP-Nonce', booking.restNonce)
                },
                success: function (availability) {
                    $('#end_time').find('option').remove().end();
                    if (availability.data.length !== 0) {
                        $('#end_time').append($("<option></option>").attr("value", 0).text(booking.selectTimeLabel));
                        $.each(availability.data, function (index, value) {
                            $('#end_time').append($("<option></option>").attr("value", value).text(shift_end_time(value)));
                        });
                    } else {
                        $('#end_time').append($("<option></option>").attr("value", 0).text(booking.notAvailableLabel));
                    }
                    get_quote();
                },
                error: function (xhr, status, error) {
                    $('#open-modal-login').click();

                },
            }).complete(function () {
                $('.spinner').hide();
            });
        }
    }

    /** Parsing locale-safe (Safari non interpreta bene "YYYY-MM-DD HH:mm:ss"). */
    function parseBookingDateTime(dateStr, timeStr) {
        if (!dateStr || timeStr === undefined || timeStr === null || timeStr === '' || timeStr === '0' || timeStr === 0) {
            return null;
        }
        var dp = String(dateStr).split('-');
        var tp = String(timeStr).split(':');
        if (dp.length !== 3 || tp.length < 2) return null;
        var y = parseInt(dp[0], 10), mo = parseInt(dp[1], 10), d = parseInt(dp[2], 10);
        var h = parseInt(tp[0], 10), mi = parseInt(tp[1], 10);
        if (isNaN(y) || isNaN(mo) || isNaN(d) || isNaN(h) || isNaN(mi)) return null;
        return new Date(y, mo - 1, d, h, mi, 0, 0);
    }

    function shift_end_time(value) {
        var parts = String(value).split(':');
        if (parts.length < 2) return value;
        var h = parseInt(parts[0], 10);
        var m = parseInt(parts[1], 10);
        if (isNaN(h) || isNaN(m)) return value;
        var totalMin = h * 60 + m + 30;
        var nh = Math.floor(totalMin / 60);
        var nm = totalMin % 60;
        if (nh > 23) { nh = 23; nm = 59; }
        return (nh < 10 ? '0' : '') + nh + ':' + (nm < 10 ? '0' : '') + nm;
    }

    function get_quote() {

        // Reset all the labels
        $('.estimeted-price-wrapper .estimeted-price-ttl').addClass('hide');
        $('.estimeted-price-wrapper .estimeted-price p.hours').text('');
        $('.estimeted-price-wrapper .estimeted-price p.discount').text('');
        $('.estimeted-price-wrapper .estimeted-price h4').text('');
        // Temporaneo: non legare abilitazione tasto al preventivo
        // $(".booking-form button#send").prop("disabled", true);
        $('.form-check p.notice').text('');

        var end_date = $('#end_date').val();

        if (!$('#end_date').val() && !$('#more-days').prop("checked")) {
            end_date = $('#start_date').val();
        }

        var from = $('#start_date').val() + ' ' + $('#start_time').val() + ':00';
        var to = end_date + ' ' + $('#end_time').val() + ':00';
        var fromD = parseBookingDateTime($('#start_date').val(), $('#start_time').val());
        var toD = parseBookingDateTime(end_date, $('#end_time').val());

        if (fromD && toD && fromD.getTime() < toD.getTime()) {
            $.ajax({
                url: booking.restURL + 'mapparte/v1/quote/' + booking.spaceId,
                data: {
                    fromDateTime: from,
                    toDateTime: to,
                    voucherCode: $('#coupon').val()
                },
                method: 'GET',
                beforeSend: function (xhr) {
                    $('.spinner').show();
                    xhr.setRequestHeader('X-WP-Nonce', booking.restNonce)
                },
                success: function (quote) {
                    if (quote.success) {
                        var slotDetails = JSON.parse(quote.data.slotsDetails);
                        $('.estimeted-price-wrapper .estimeted-price-ttl').removeClass('hide');
                        $('.estimeted-price-wrapper .estimeted-price p.hours').text(slotDetails.hours_booked + ' ore');
                        if (quote.data.voucherValue != '0.00' && $('#coupon').val()) $('.estimeted-price-wrapper .estimeted-price p.discount').text('Sconto: ' + quote.data.voucherValue + ' €');
                        else if (quote.data.voucherUsed == 1) $('.estimeted-price-wrapper .estimeted-price p.discount').text('Voucher già utilizzato.');
                        else if ( $('#coupon').val() ) $('.estimeted-price-wrapper .estimeted-price p.discount').text('Voucher non valido.');
                        $('.estimeted-price-wrapper .estimeted-price h4').text(quote.data.finalPrice + ' €');
                        // $(".booking-form button#send").prop("disabled", false);
                    } else {
                        $('.form-check p.notice').text(booking.alertTimeLabel);
                    }

                },
                error: function (xhr, status, error) {
                    $('#open-modal-login').click();

                },
            }).complete(function () {
                $('.spinner').hide();
            });
        } else {
            if (fromD && toD && fromD.getTime() >= toD.getTime()) {
                $('.form-check p.notice').text(booking.alertTimeLabel);
            }
        }
    }

    function request_booking() {

        var end_date = $('#end_date').val();

        if (!$('#end_date').val() && !$('#more-days').prop("checked")) {
            end_date = $('#start_date').val();
        }

        var from = $('#start_date').val() + ' ' + $('#start_time').val() + ':00';
        var to = end_date + ' ' + $('#end_time').val() + ':00';
        var fromD = parseBookingDateTime($('#start_date').val(), $('#start_time').val());
        var toD = parseBookingDateTime(end_date, $('#end_time').val());

        if (fromD && toD && fromD.getTime() < toD.getTime()) {
            $.ajax({
                url: booking.restURL + 'mapparte/v1/book/' + booking.spaceId,
                data: {
                    planningTo: $('#s_activity').val(),
                    fromDateTime: from,
                    toDateTime: to,
                    voucherCode: $('#coupon').val(),
                    guests: $('#floatingInput').val(),
                    message: $('#message').val(),
                },
                method: 'POST',
                beforeSend: function (xhr) {
                    $('.spinner').show();
                    xhr.setRequestHeader('X-WP-Nonce', booking.restNonce)
                },
                success: function (success) {
                    alert('Richiesta inviata con successo!');
                    setTimeout(function () {
                        window.location.href = booking.getHome + '/my-bookings/'
                    }, 500);
                },
                error: function (xhr, status, error) {
                    // $(this).prop("disabled", false);
                    $('#open-modal-login').click();
                },
            }).complete(function () {
                $('.spinner').hide();
            });
        }
    }
});
