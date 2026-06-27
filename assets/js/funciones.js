var enviandoformulario = false;

function validarFormulario(formulario) {
    var error = false;

    $(".requerido", "#" + formulario).each(function () {
        var elemento = $(this).prop("tagName").toLowerCase();
        if (elemento == "input") {
            var tipo = $(this).attr("type");
            if (tipo == "text" && ($(this).val() == "" || $(this).val().trim().length == 0)) {
                swalFocus("Error", $(this).data("mensajeerror"), "error", $(this).attr("name"));
                error = true;
                return false;
            } else if (tipo == "password" && ($(this).val() == "" || $(this).val().trim().length == 0)) {
                swalFocus("Error", $(this).data("mensajeerror"), "error", $(this).attr("name"));
                error = true;
                return false;
            }
        }
    });

    if (!error) {
        enviarFormulario(formulario);
    }
}

function enviarFormulario(formulario) {
    if (!enviandoformulario) {
        $.ajax({
            type: "POST",
            url: "/assets/php/controladores/" + $("#controlador", "#" + formulario).val() + ".php",
            data: new FormData($("#" + formulario)[0]),
            processData: false,
            contentType: false,
            dataType: "json",
            beforeSend: function () {
                enviandoformulario = true;
            },
            success: function (data) {
                enviandoformulario = false;
                if (data.success) {
                    var tipo = data.tipo;
                    if (tipo == "href") {
                        location.href = $("#href", "#" + formulario).val();
                    } else if (tipo == "reload") {
                        location.href = location.href;
                    } else if (tipo == "mensaje") {
                        Swal.fire(data.titulo, data.message, "success");
                    }
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            },
            error: function () {
                enviandoformulario = false;
                Swal.fire("Error", "Ocurrió un error al procesar la solicitud.", "error");
            }
        });
    }
}

function swalFocus(titulo, mensaje, tipo, input) {
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: tipo,
        didClose: () => {
            $("#" + input).focus();
        }
    });
}
