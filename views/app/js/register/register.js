/**
 * Ajax action to api rest
 */
function register() {
  $.ajax({
    type: "POST",
    url: "api/register",
    data: $("#register_form").serialize(),
    success: function(json) {
      if (json.success == 1) {
        $.alert({
          typeAnimated: true,
          icon: "glyphicon glyphicon-warning-sign",
          type: "green",
          title: "Registro!",
          content: json.message
        });
        setTimeout(function() {
          location.reload();
        }, 1000);
      } else {
        $.alert({
          typeAnimated: true,
          icon: "glyphicon glyphicon-warning-sign",
          type: "red",
          title: "Registro!",
          content: json.message
        });
      }
    },
    error: function(/*xhr, status*/) {
      alert("Ha ocurrido un problema.");
    }
  });
}

/**
 * Events
 */
$("#register").click(function(e) {
  e.defaultPrevented;
  register();
});
$("#register_form").keypress(function(e) {
  e.defaultPrevented;
  if (e.which == 13) {
    register();
  }
});
$("#registrarUser").click(function() {
  var form =
    "<h4>" +
    '<form id="register_form">' +
    '<input type="rut" name="rut" class="form-control" placeholder="RUT" autofocus="autofocus">' +
    '<input type="text" name="name" class="form-control" placeholder="Nombre" >' +
    '<input type="email" name="mail" class="form-control" placeholder="Ejemplo@gmail.com" >' +
    '<input type="password" name="pass" class="form-control" placeholder="Password" >' +
    '<input type="password" name="pass_repeat" class="form-control" placeholder="re ingrese password" >' +
    "</form>" +
    "</h4>";
  $.confirm({
    icon: "glyphicon glyphicon-warning-sign",
    title: "Nuevo Usuario!",
    content: form,
    type: "blue",
    buttons: {
      confirmar: {
        text: "<h3>Registrar.</h3>",
        btnClass: "btn-blue",
        action: function() {
          register();
        }
      },
      cancel: {
        text: "<h3>Cancelar</h3>",
        btnClass: "btn-red",
        action: function() {}
      }
    }
  });
});
