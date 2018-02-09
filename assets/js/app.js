var waitingDialog = require('../js/bootstrap/plugin/bootstrap-waitingfor');
var bootbox = require('bootbox');

$(document).ready(function () {
    $('#update_currency').on('click', function () {
        $.ajax({
            url: Routing.generate('update_all_currency'),
            method: 'POST',
            dataType: 'json',
            //  data: {'data': data},
            beforeSend: function (jqXHR) {
                waitingDialog.show('Зачекайте, будь ласка');
            },
            complete: function (jqXHR, textStatus) {
                waitingDialog.hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                waitingDialog.hide();
                alert(jqXHR.responseText);

            },
            success: function (data) {
                waitingDialog.hide();
                bootbox.alert(data.message);
                location.reload();
            }
        });
    });

    $('#update_balances').on('click', function () {
        $.ajax({
            url: Routing.generate('update_balances'),
            method: 'POST',
            dataType: 'json',
            beforeSend: function (jqXHR) {
                waitingDialog.show('Зачекайте, будь ласка');
            },
            complete: function (jqXHR, textStatus) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                waitingDialog.hide();
                bootbox.alert(jqXHR.responseJSON);
            },
            success: function (data) {
                waitingDialog.hide();
                bootbox.alert(data.message);
                location.reload();
            }
        });
    })

    $('.sonata-readmore').each(function (obj) {
        if($(this).text() > 0){
            $(this).parent().addClass('label-success');
        }else if($(this).text() < 0){
            $(this).parent().addClass('label-danger');
        }
    })

    $('.sum_title h3').each(function (obj) {
        if($(this).text() > 0){
            $(this).parent().addClass('label-success');
        }else if($(this).text() < 0){
            $(this).parent().addClass('label-danger');
        }
    })

    $('#update_order_history').on('click', function () {
        $.ajax({
            url: Routing.generate('update_order_history'),
            method: 'POST',
            dataType: 'json',
            beforeSend: function (jqXHR) {
                waitingDialog.show('Зачекайте, будь ласка');
            },
            complete: function (jqXHR, textStatus) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                waitingDialog.hide();
                bootbox.alert(jqXHR.responseText);
            },
            success: function (data) {
                waitingDialog.hide();
                bootbox.alert(data.message);
                location.reload();
            }
        });
    })
});
