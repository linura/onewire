

jQuery(document).ready(function () {
    console.debug('hide');
    $('.config').hide();
    $('.form-actions').hide();
});


$('#conf_ontype').on('change', function () {
    console.debug('conf_ontype');

    $('.config').hide();
    val_type = $(this).val();
    val_mini = $('#conf_onmini').val();
    if (val_mini == '' || val_type == '')
        return;
    $('#conf_' + val_type).show();
    $('#' + val_type + '_' + val_mini).show();


});
$('#conf_onmini').on('change', function () {
    console.debug('conf_onmini');
    $('.config').hide();
    val_mini = $(this).val();
    val_type = $('#conf_ontype').val();
    if (val_mini == '' || val_type == '')
        return;
    $('#conf_' + val_type).show();
    $('#' + val_type + '_' + val_mini).show();
})


$('.bt_installDongle').on('click', function () {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "plugins/onewire/core/ajax/onewire.ajax.php", // url du fichier php
        data: {
            action: "installDongle",
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({ message: data.result, level: 'danger' });
                return;
            }
            $('#ul_plugin .li_plugin[data-plugin_id=onewire]').click();   // recharge la page config du plugin
            $('#div_alert').showAlert({ message: '{{Le système est en cours de mise à jour.}}', level: 'success' });
        }
    });
});
