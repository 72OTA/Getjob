/**
 * Ajax action to api rest
 */
function login() {
  $.ajax({
    type: "POST",
    url: "api/login",
    data: $("#login_form_user").serialize(),
    success: function(json) {
      if (json.success == 1) {
        $.dialog({
          title: "Acceso a sistema",
          type: "green",
          typeAnimated: true,
          content: json.message
        });
        setTimeout(function() {
          location.reload();
        }, 1000);
      } else {
        $.dialog({
          title: "Acceso a sistema",
          type: "orange",
          typeAnimated: true,
          content: json.message
        });
      }
    },
    error: function(xhr, status) {
      $.dialog({
        title: "Acceso a sistema",
        type: "red",
        typeAnimated: true,
        content: xhr.responseText
      });
    }
  });
}
function loginEmpresa() {
  $.ajax({
    type: "POST",
    url: "api/loginEmpresa",
    data: $("#login_form_empresa").serialize(),
    success: function(json) {
      if (json.success == 1) {
        $.dialog({
          title: "Acceso a sistema",
          type: "green",
          typeAnimated: true,
          content: json.message
        });
        setTimeout(function() {
          location.reload();
        }, 1000);
      } else {
        $.dialog({
          title: "Acceso a sistema",
          type: "orange",
          typeAnimated: true,
          content: json.message
        });
      }
    },
    error: function(xhr, status) {
      $.dialog({
        title: "Acceso a sistema",
        type: "red",
        typeAnimated: true,
        content: xhr.responseText
      });
    }
  });
}
/**
 * Events
 */
$("#loginUser").click(function(e) {
  e.defaultPrevented;
  login();
});
$("#login_form_user").keypress(function(e) {
  e.defaultPrevented;
  if (e.which == 13) {
    login();
  }
});
$("#loginEmpresa").click(function(e) {
  e.defaultPrevented;
  loginEmpresa();
});
$("#login_form_empresa").keypress(function(e) {
  e.defaultPrevented;
  if (e.which == 13) {
    loginEmpresa();
  }
});

var typed2 = new Typed("#inUser", {
  strings: [
    "Bienvenido",
    "¿Buscas Empleo?",
    "Aqui te buscan!",
    "Registrate",
    "Comienza hoy",
    "Getjob Te espera"
  ],
  typeSpeed: 80,
  backSpeed: 80,
  smartBackspace: true,
  loop: true
});
var typed = new Typed("#inEmpr", {
  strings: [
    "Bienvenido",
    "¿Buscas Personas?",
    "Aqui te buscan!",
    "Registrate",
    "Comienza hoy",
    "Getjob Te espera"
  ],
  typeSpeed: 80,
  backSpeed: 80,
  smartBackspace: true,
  loop: true
});
