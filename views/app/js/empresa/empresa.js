$("#cambiar_pass").on("click", function() {
  var id = $("#id").val();
  $.confirm({
    theme: "supervan",
    icon: "glyphicon glyphicon-heart",
    title: "Perfil de usuario",
    content: form,
    type: "purple",
    typeAnimated: true,
    buttons: {
      formSubmit: {
        text: "<h3>Actualizar.</h3>",
        btnClass: "btn-green",
        action: function() {
          $.ajax({
            type: "POST",
            url: "api/resetpass",
            data: $("#cambiarpass").serialize(),
            success: function(json) {
              if (json.success == 1) {
                $.alert({
                  typeAnimated: true,
                  icon: "glyphicon glyphicon-warning-sign",
                  type: "green",
                  title: "Cambio de contraseña!",
                  content: "Contraseña Actualizada con exito"
                });
              } else {
                $.alert({
                  typeAnimated: true,
                  icon: "glyphicon glyphicon-warning-sign",
                  type: "red",
                  title: "Cambio de contraseña!",
                  content: "Las contraseñas no son iguales"
                });
              }
            },
            error: function(xhr, status) {
              alert(json.message);
            }
          });
        }
      },
      cancel: {
        text: "<h3>Cancelar</h3>",
        btnClass: "btn-red",
        action: function() {
          //close
        }
      }
    }
  });
});
