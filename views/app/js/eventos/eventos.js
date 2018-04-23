$("#editarEvento").on("click", function() {
  $.ajax({
    type: "POST",
    url: "api/editar_evento",
    data: $("#editarevento_form").serialize(),
    success: function(json) {
      if (json.success == 1) {
        $.alert({
          typeAnimated: true,
          icon: "glyphicon glyphicon-warning-sign",
          type: "green",
          title: "Evento",
          content: "Evento editado con exito",
          autoClose: "ok|3000",
          buttons: {
            ok: function() {
              location.href = "eventos/misEventos";
            }
          }
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
$("#crearevento").on("click", function() {
  $.ajax({
    type: "POST",
    url: "api/crearevento",
    data: $("#crearevento_form").serialize(),
    success: function(json) {
      if (json.success == 1) {
        $.alert({
          typeAnimated: true,
          icon: "glyphicon glyphicon-warning-sign",
          type: "green",
          title: "Evento",
          content: "Evento creado con exito",
          autoClose: "ok|3000",
          buttons: {
            ok: function() {
              location.href = "eventos/visualizar";
            }
          }
        });
      } else {
        $.alert({
          typeAnimated: true,
          icon: "glyphicon glyphicon-warning-sign",
          type: "red",
          title: "Evento",
          content: "Hubo un error al crear el evento",
          autoClose: "ok|3000"
        });
      }
    },
    error: function(/*xhr, status*/) {
      alert(json.message);
    }
  });
});
