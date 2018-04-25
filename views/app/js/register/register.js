/**
 * Ajax action to api rest
 */
function registerUser() {
  $.ajax({
    type: "POST",
    url: "api/registerUser",
    data: $("#register_user_form").serialize(),
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
function registerEmpr() {
  $.ajax({
    type: "POST",
    url: "api/registerEmpr",
    data: $("#register_empr_form").serialize(),
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
$("#registrarUser").click(function() {
  var form =
    '<form id="register_user_form">' +
    'RUT:<input type="rut" name="rut" class="form-control" placeholder="RUT" autofocus="autofocus">' +
    'Nombre:<input type="text" name="name" class="form-control" placeholder="Nombre" >' +
    'Email:<input type="email" name="mail" class="form-control" placeholder="Ejemplo@gmail.com" >' +
    'Password:<input type="password" name="pass" class="form-control" placeholder="Password" >' +
    'Repita su password:<input type="password" name="pass_repeat" class="form-control" placeholder="re ingrese password" >' +
    '<input type="hidden" name="empr" value="0">' +
    "</form>";
  $.confirm({
    icon: "glyphicon glyphicon-warning-sign",
    title: "Nuevo Usuario!",
    content: form,
    type: "blue",
    buttons: {
      confirmar: {
        text: "<h5>Registrar.</h5>",
        btnClass: "btn-blue",
        action: function() {
          registerUser();
        }
      },
      cancel: {
        text: "<h5>Cancelar</h5>",
        btnClass: "btn-red",
        action: function() {}
      }
    }
  });
});
$("#registrarEmpresa").click(function() {
  var form =
    '<form id="register_empr_form">' +
    'RUT:<input type="rut" name="rut" class="form-control" placeholder="RUT" autofocus="autofocus">' +
    'Nombre:<input type="text" name="name" class="form-control" placeholder="Nombre" >' +
    'Email:<input type="email" name="mail" class="form-control" placeholder="Ejemplo@gmail.com" >' +
    'Password:<input type="password" name="pass" class="form-control" placeholder="Password" >' +
    'Repita su password:<input type="password" name="pass_repeat" class="form-control" placeholder="re ingrese password" >' +
    '<input type="hidden" name="empr" value="1">' +
    "</form>";
  $.confirm({
    icon: "glyphicon glyphicon-warning-sign",
    title: "Nueva Empresa!",
    content: form,
    type: "green",
    buttons: {
      confirmar: {
        text: "<h5>Registrar.</h5>",
        btnClass: "btn-green",
        action: function() {
          registerEmpr();
        }
      },
      cancel: {
        text: "<h5>Cancelar</h5>",
        btnClass: "btn-red",
        action: function() {}
      }
    }
  });
});
