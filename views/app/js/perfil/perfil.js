$("#cambiar_pass").on("click", function() {
  var id = $("#id").val();
  var form =
    "<h4>" +
    '<form id="cambiarpass" name="cambiarpass">' +
    'Nueva Contraseña<input type="password" name="pass" id="pass" class="form-control" placeholder="Password" >' +
    "<br>" +
    'Repita la contraseña<input type="password" name="pass_repeat" id="pass_repeat" class="form-control" placeholder="re ingrese password" >' +
    "</form>" +
    "</h4>";
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
$("#actualizar").on("click", function() {
  $.ajax({
    type: "POST",
    url: "api/actualizar",
    data: $("#actualizar_form").serialize(),
    success: function(json) {
      if (json.success == 1) {
        $.alert({
          typeAnimated: true,
          icon: "glyphicon glyphicon-warning-sign",
          type: "green",
          title: "Actualizar Datos!",
          content: "Datos actualizados con éxito"
        });
      } else {
        $.alert({
          typeAnimated: true,
          icon: "glyphicon glyphicon-warning-sign",
          type: "red",
          title: "Actualizar Datos!",
          content: "Los datos no se pudieron actualizar"
        });
      }
    },
    error: function(/*xhr, status*/) {
      alert(json.message);
    }
  });
});

$("#crearempresa").on("click", function() {
  $.ajax({
    type: "POST",
    url: "api/crearempresa",
    data: $("#crearempresa_form").serialize(),
    success: function(json) {
      if (json.success == 1) {
        $.alert({
          typeAnimated: true,
          icon: "glyphicon glyphicon-warning-sign",
          type: "green",
          title: "Evento",
          content: "Empresa creada con exito"
        });
      } else {
        $.alert({
          typeAnimated: true,
          icon: "glyphicon glyphicon-warning-sign",
          type: "red",
          title: "Evento",
          content: json.message
        });
      }
    },
    error: function(xhr, status) {
      alert(xhr.responsetext);
    }
  });
});
