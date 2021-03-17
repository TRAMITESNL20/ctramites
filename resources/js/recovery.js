var validation;

document.addEventListener('DOMContentLoaded', function(e) {
    validation = FormValidation.formValidation(
        document.getElementById('kt_login_recovery_form'), {
            fields: {
                email: {
                    validators: {
                        emailAddress: {
                            message: 'El correo electronico no es valido'
                        }
                    }
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                submitButton: new FormValidation.plugins.SubmitButton(),
                //defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
                bootstrap: new FormValidation.plugins.Bootstrap(),
                declarative: new FormValidation.plugins.Declarative()
            }
        }
    );
});

$('#kt_recovery_submit').on('click', function(e) {
    e.preventDefault();
    var email = $(document).find('input[name="email"]').val();
    validation.validate().then(function(status) {
        if (status == 'Valid') {
            $.ajax({
                url: `${process.env.SESSION_HOSTNAME}/password/recovery`,
                type: "POST",
                data: {
                    "email": email,
                },
                success: function(res) {
                    swal.fire({
                        text: "Te hemos enviado un mensaje a tu correo electrónico (" + email + ") para continuar con el cambio de contraseña. Revisa tu bandeja de SPAM si es necesario ",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Entendido",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        redirect("/login");
                    });
                }
            });
        } else {
            swal.fire({
                text: "El correo electronico ingresado es incorrecto, intenta nuevamente.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Entendido",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-light-primary"
                }
            }).then(function() {
                KTUtil.scrollTop();
            });
        }
    });
});