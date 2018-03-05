var waitingDialog = require('../js/bootstrap/plugin/bootstrap-waitingfor');
var bootbox = require('bootbox');
var Web3 = require('web3');

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
        waitingDialog.show('Зачекайте, будь ласка');
        var web3 = new Web3();
        var data = {};
        web3.setProvider(new web3.providers.HttpProvider('https://api.myetherapi.com/eth'));
        web3.eth.getBalance("0x5cA8A8F4E884Aa58ce6d0f07c450753a02e5a05b", function (error, result) {
            if(!error){
                data.farm1 =(result/1000000000000000000).toString(10);
            } else{
                console.error(error);
            }
            web3.eth.getBalance("0x31ade6dB0E914107386E61aC65c1975E546d4a8e", function (error, result) {
                if(!error){
                    data.farm2 =(result/1000000000000000000).toString(10);
                } else{
                    console.error(error);
                }
                if(!data){
                    bootbox.alert('Помилка отримання даних з myetherapi')
                }
                $.ajax({
                    url: Routing.generate('update_balances'),
                    method: 'POST',
                    dataType: 'json',
                    data: data,
                    beforeSend: function (jqXHR) {

                    },
                    complete: function (jqXHR, textStatus) {
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        waitingDialog.hide();
                        if(jqXHR.responseJSON){
                            bootbox.alert(jqXHR.responseJSON.error);
                        }else{
                            bootbox.alert(jqXHR.responseText);
                        }
                    },
                    success: function (data) {
                        waitingDialog.hide();
                        bootbox.alert(data.message);
                        location.reload();
                    }
                });
            });
        });
    });

    $('#update_deposit').on('click', function () {
        $.ajax({
            url: Routing.generate('update_deposit'),
            method: 'POST',
            dataType: 'json',
            beforeSend: function (jqXHR) {
                waitingDialog.show('Зачекайте, будь ласка');
            },
            complete: function (jqXHR, textStatus) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                waitingDialog.hide();
                if(jqXHR.responseJSON){
                    bootbox.alert(jqXHR.responseJSON.error);
                }else{
                    bootbox.alert(jqXHR.responseText);
                }
            },
            success: function (data) {
                waitingDialog.hide();
                bootbox.alert(data.message);
                location.reload();
            }
        });
    })


    $('#update_deposit_statistic').on('click', function () {
        $.ajax({
            url: Routing.generate('update_deposit_statistic'),
            method: 'POST',
            dataType: 'json',
            beforeSend: function (jqXHR) {
                waitingDialog.show('Зачекайте, будь ласка');
            },
            complete: function (jqXHR, textStatus) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                waitingDialog.hide();
                if(jqXHR.responseJSON){
                    bootbox.alert(jqXHR.responseJSON.error);
                }else{
                    bootbox.alert(jqXHR.responseText);
                }
            },
            success: function (data) {
                waitingDialog.hide();
                bootbox.alert(data.message);
                location.reload();
            }
        });
    })

    $('#update_deposit_month').on('click', function () {
        $.ajax({
            url: Routing.generate('update_deposit_month'),
            method: 'POST',
            dataType: 'json',
            beforeSend: function (jqXHR) {
                waitingDialog.show('Зачекайте, будь ласка');
            },
            complete: function (jqXHR, textStatus) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                waitingDialog.hide();
                if(jqXHR.responseJSON){
                    bootbox.alert(jqXHR.responseJSON.error);
                }else{
                    bootbox.alert(jqXHR.responseText);
                }
            },
            success: function (data) {
                waitingDialog.hide();
                bootbox.alert(data.message);
                //   location.reload();
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
