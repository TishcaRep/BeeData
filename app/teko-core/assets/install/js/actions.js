$(document).ready(function() {
    var $stepper = $('#wizard-install');
    var $form = $('#form-install');
    var ajaxurl = 'app/teko-core/assets/install/ajax';
    $stepper.activateStepper();

    window.validarConexionDB = function() {
        var data = $form.serialize();
        $.post(ajaxurl + '/validar-conexion-db.php', data).then(function(res) {
            if (res.error) {
                $stepper.destroyFeedback();
                $stepper.find('input[name="db[user]"]').showError(res.mensaje);
            } else {
                $stepper.nextStep();
            }
        }).fail(function() {
            $stepper.destroyFeedback();
            $stepper.find('input[name="db[user]"]').showError('Ocurrió un error al validar la conexión a la base de datos');
        });
    };

    $form.submit(function(e) {
        e.preventDefault();
        var data = $form.serialize();
        $stepper.activateFeedback();
        $.post(ajaxurl + '/instalar.php', data).then(function(res) {
            if (res.error) {
                $stepper.destroyFeedback();
                $stepper.find('input[name="admin[correo]"]').showError(res.mensaje);
            } else {
                location.href = res.url;
            }
        }).fail(function() {
            $stepper.destroyFeedback();
            $stepper.find('input[name="admin[correo]"]').showError('Ocurrió un error al instalar');
        });
    })
});