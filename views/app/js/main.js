function verperfil(id) {
  var formData = new FormData();
  formData.append("id", id);
  $.ajax({
    type: "POST",
    url: "api/ver_usuario",
    contentType: false,
    processData: false,
    data: formData,
    success: function(data) {
      if (data.success == 1) {
        var datos = data.datos;
        $.confirm({
          theme: "supervan",
          icon: "glyphicon glyphicon-heart",
          title: "Perfil de usuario",
          content:
            '<form id="Formcierre" name="Formcierre" class="form-signin" >' +
            '<h4 class="text-left">' +
            "Nombre: " +
            data.datos[0]["name"] +
            "<br>" +
            "RUT: " +
            data.datos[0]["rut"] +
            "<br>" +
            "Email: " +
            data.datos[0]["email"] +
            "<br>" +
            "Numero de telefono: " +
            data.datos[0]["n_telefono"] +
            "<br>" +
            "Fecha de nacimiento: " +
            data.datos[0]["fecha_nacimiento"] +
            "<br>" +
            "Comuna: " +
            data.datos[0]["comuna"] +
            "<br>" +
            "Sexo: " +
            data.datos[0]["sexo"] +
            "<br>" +
            "Tarjeta Bip: " +
            data.datos[0]["bip"] +
            "</h4> " +
            "</form>",
          type: "purple",
          typeAnimated: true,
          buttons: {
            formSubmit: {
              text: "<h3>Actualizar.</h3>",
              btnClass: "btn-green",
              action: function() {
                location.href = "perfil/usuario";
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
        if (data.datos[0]["n_telefono"] == false) {
          $.alert({
            icon: "glyphicon glyphicon-warning-sign",
            title: "Atencion!",
            content: "Recuerde completar su perfil",
            typeAnimated: true,
            type: "orange"
          });
        }
      } else {
        $.alert("No existen datos del usuario");
      }
    },
    error: function(xhr, status) {
      msg_box_alert(99, "Filtrar Ordenes", xhr.responseText);
    }
  });
}
