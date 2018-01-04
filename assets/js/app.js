var waitingDialog = require('../js/bootstrap/plugin/bootstrap-waitingfor');
var bootbox = require('bootbox');

$(document).ready(function () {
    $('#update_currency').on('click', function () {
        var ticker_url = 'https://api.coinmarketcap.com/v1/ticker/?limit=0'
        $.ajax({
            url: ticker_url,
            method: 'GET',
            dataType: 'json',
            beforeSend: function (jqXHR) {
                waitingDialog.show('Зачекайте, будь ласка');
            },
            complete: function (jqXHR, textStatus) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText);
            },
            success: function (data) {
                if (data.error) {
                    alert(data.error);
                } else {
                    $.ajax({
                        url: Routing.generate('update_all_currency'),
                        method: 'POST',
                        dataType: 'json',
                        data: {'data': data},
                        beforeSend: function (jqXHR) {

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
                        }
                    });
                }
            }
        });
    });

   /* var linechart = new Highcharts.Chart({
        chart: {"renderTo": "linechart"},
        series: [{"name": "Data Serie Name", "data": [1, 2, 4, 5, 6, 3, 15]}],
        title: {"text": "Chart Title"},
        xAxis: {"title": {"text": "Horizontal axis title"}},
        yAxis: {"title": {"text": "Vertical axis title"}}
    });*/

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
                bootbox.alert(jqXHR.responseText);
            },
            success: function (data) {
                waitingDialog.hide();
                // console.log(bootbox);
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
                //location.reload();

            }
        });
    })
});
