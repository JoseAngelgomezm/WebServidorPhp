const DIR_SERV = "servicios_rest"

const llamadaGetProductos = () => {
  $.ajax({

    url: DIR_SERV.concat("/productos"),
    dataType: "json",
    type: "get",

  }).done(function (data) {

    // si he recibido errror
    if (data.mensaje) {
      $("#respuesta").html(data.mensaje)
    } else {

      let tablaProductos = "";
      tablaProductos += "<table>"
      tablaProductos += "<tr>"
      tablaProductos += "<td>COD</td>"
      tablaProductos += "<td>Nombre Corto</td>"
      tablaProductos += "<td>PVP</td>"
      tablaProductos += "<td><button onclick='MostrarInsertar()'>Producto+</button></td>"
      tablaProductos += "</tr>"
      $.each(data.productos, function (key, value) {
        tablaProductos += "<tr>"
        tablaProductos += "<td><button onclick='llamadaGetDetalles(\"" + value["cod"] + "\")'>" + value["cod"] + "</button></td>";
        tablaProductos += "<td>" + value["nombre_corto"] + "</td>"
        tablaProductos += "<td>" + value["PVP"] + "</td>"
        tablaProductos += "<td><button onclick='confirmarBorrado(\"" + value["cod"] + "\")'>Borrar</button> - <button onclick='MostrarFormularioEditarObtenerFamilias(\"" + value["cod"] + "\")'>Editar</button></td>"
        tablaProductos += "</tr>"
      })

      tablaProductos += "</table>"

      $("#tablaProductos").html(tablaProductos)
    }

  }).fail(function (estado, textoEstado) {
    $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
  })
}


function MostrarFormularioEditarObtenerFamilias(codigoProducto) {

  // obtener las familias
  $.ajax({

    url: DIR_SERV.concat("/familias"),
    dataType: "json",
    type: "get",

  }).done(function (data) {

    // si he recibido errror
    if (data.mensaje) {
      $("#respuesta").html(data.mensaje)
    } else {
      MostrarFormularioEditarObtenerDatosProducto(codigoProducto, data.familia)
    }

  }).fail(function (estado, textoEstado) {
    $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
  })
}


const MostrarFormularioEditarObtenerDatosProducto = (codigoProducto, familias) => {
  // obtener los datos de los productos
  $.ajax({

    url: DIR_SERV.concat("/producto/" + codigoProducto),
    dataType: "json",
    type: "get",

  }).done(function (data) {

    // si he recibido errror
    if (data.mensaje) {
      $("#respuesta").html(data.mensaje)
    } else {
      let formularioEditar = "";
      formularioEditar += "<form onsubmit='event.preventDefault(), actualizarDatos(\"" + data.producto.cod + "\",\"" + data.producto.nombre_corto + "\") '>"
      formularioEditar += "<h2>Editando el producto " + data.producto.cod + "</h2>"
      formularioEditar += "<p><label for='codigo'><strong>Codigo: </strong></label><input name ='codigo' id='codigo' value='" + data.producto.cod + "'><span></span></p>"
      if (data.producto.nombre) {
        formularioEditar += "<p><label for='nombre'><strong>Nombre: </strong></label><input name ='nombre' id='nombre' value='" + data.producto.nombre + "'><span></span></p>"
      } else {
        formularioEditar += "<p><label for='nombre'><strong>Nombre: </strong></label><input name ='nombre' id='nombre' value=''><span></span></p>"
      }

      formularioEditar += "<p><label for='nombrecorto'><strong>Nombre corto: </strong></label><input name ='nombrecorto' id='nombrecorto' value='" + data.producto.nombre_corto + "'><span></span></p>"
      formularioEditar += "<p><label for='descripcion'><strong>Descripcion: </strong></label><input name ='descripcion' id='descripcion' value='" + data.producto.descripcion + "'><span></span></p>"
      formularioEditar += "<p><label for='pvp'><strong>PVP: </strong></label><input type='number' name='pvp' id='pvp' value='" + data.producto.PVP + "'><span></span></p>"
      formularioEditar += "<p>"
      formularioEditar += "<label for='familia'><strong>Familia: </strong></label>"
      formularioEditar += "<select name='familia' id='familia'>"
      $.each(familias, function (key, value) {
        if (value.cod == data.producto.familia) {
          formularioEditar += "<option selected value='" + value.cod + "'>" + value.nombre + "</option>"
        } else {
          formularioEditar += "<option value='" + value.cod + "'>" + value.nombre + "</option>"
        }
      })
      formularioEditar += "</select>"
      formularioEditar += "</p>"
      formularioEditar += "<button type='submit' name='editar'>Editar</button>"
      formularioEditar += "<p></p>"
      formularioEditar += "</form>"

      $("#respuesta").html(formularioEditar)
    }

  }).fail(function (estado, textoEstado) {
    $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
  })
}

const actualizarDatos = (codigoActualizar, nombreCortoActualizar) => {
  // comprobar los datos
  let errorCodigo = $("input#codigo").val() == ""
  let errorNombreCorto = $("input#nombrecorto").val() == ""
  let errorPVP = $("input#PVP").val() == ""


  if (errorCodigo) {
    $("input#codigo").next("span").text("Introduce un código")
  }

  if (errorNombreCorto) {
    $("input#nombrecorto").next("span").text("Introduce un nombre corto")
  }

  if (errorPVP) {
    $("input#pvp").next("span").text("Introduce un pvp valido")
  }

  let errorFormulario = errorCodigo || errorNombreCorto || errorPVP

  if (!errorFormulario) {
    // comprobar repetidos
    comoprobarRepetidoEditar(codigoActualizar, nombreCortoActualizar)
  }
}

function comoprobarRepetidoEditar(codigo, nombreCorto) {

  $('input#codigo').next("span").html("")
  $('input#nombrecorto').next("span").html("")


  let codigoInsertado = $('input#codigo').val();
  let nombreCortoInsertado = $('input#nombrecorto').val();


  $.ajax({
    url: encodeURI(DIR_SERV + "/repetido/producto/cod/" + codigoInsertado + "/cod/" + codigo + ""),
    type: "GET",
    dataType: "json"
  })
    .done(function (data) {
      if (data.mensaje) {
        $('#respuesta').html(data.mensaje)
        $('#tablaProductos').html("")
      }
      else if (data.repetido) {
        //Informo código repetido y compruebo también nombre corto  pero ya no inserto
        $('input#codigo').next("span").html("Código repetido")

        $.ajax({
          url: encodeURI(DIR_SERV + "/repetido/producto/nombre_corto/" + nombreCortoInsertado + "/nombre_corto/" + nombreCorto),
          type: "GET",
          dataType: "json"
        })
          .done(function (data) {
            if (data.mensaje) {
              $('#respuesta').html(data.mensaje)
              $('#tablaProductos').html("")
            }
            if (data.repetido) {
              $('input#nombrecorto').next("span").html("Nombre corto repetido")
            }
          })
          .fail(function (estado, textoEstado) {
            $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
            $('#tablaProductos').html("")
          });
      }
      else {
        //Compruebo nombre corto y si está repetido informo y no inserto, pero si no está repetido inserto
        $.ajax({
          url: encodeURI(DIR_SERV + "/repetido/producto/nombre_corto/" + nombreCortoInsertado + "/nombre_corto/"+nombreCorto),
          type: "GET",
          dataType: "json"
        })
          .done(function (data) {
            if (data.mensaje) {
              $('#respuesta').html(data.mensaje)
              $('#tablaProductos').html("")
            }
            if (data.repetido) {
              $('#error_nombre_corto').html("Nombre corto repetido");
            }
            else {
              let nombre = $('#nombre').val();
              let descripcion = $('#descripcion').val();
              let PVP = $('#pvp').val();
              let familia = $('#familia').val();

              $.ajax({
                url: DIR_SERV + "/producto/actualizar/" + codigo,
                type: "put",
                dataType: "json",
                data: { "cod": codigoInsertado, "nombre": nombre, "nombre_corto": nombreCortoInsertado, "descripcion": descripcion, "PVP": PVP, "familia": familia }
              })
                .done(function (data) {
                  if (data.error_actualizar) {
                    $("#respuesta").html(data.error_actualizar)
                    $('#tablaProductos').html("");
                    llamadaGetProductos();
                  }
                  else {
                    $('#respuesta').html("<p class='mensaje'>El producto con cod: <strong>" + codigo + "</strong> se ha actualizado con éxito<p>");
                    llamadaGetProductos();
                  }

                })
                .fail(function (estado, textoEstado) {
                  $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
                  $('#tablaProductos').html("");
                });
            }
          })
          .fail(function (estado, textoEstado) {
            $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
            $('#tablaProductos').html("");
          });

      }

    })
    .fail(function (estado, textoEstado) {
      $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
      $('#tablaProductos').html("");
    });
}

function llamadaDelete(codigo) {
  $.ajax({

    url: DIR_SERV.concat("/producto/borrar/" + codigo),
    dataType: "json",
    type: "delete",

  }).done(function (data) {

    // si he recibido errror
    if (data.mensaje) {
      $("#respuesta").html(data.mensaje)
    } else {
      llamadaBorrarCampo("div#respuesta")
      llamadaGetProductos()
    }

  }).fail(function (estado, textoEstado) {
    $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
  })
}


function llamadaGetDetalles(cod) {

  $.ajax({

    url: DIR_SERV.concat("/producto/" + cod),
    dataType: "json",
    type: "get",

  }).done(function (data) {

    // si he recibido errror
    if (data.mensaje) {
      $("#respuesta").html(data.mensaje)
    } else {

      let respuestaDetalles = "";

      respuestaDetalles += "<h2>Datos del producto " + data.producto.cod + "</h2>"
      if (data.producto.nombre) {
        respuestaDetalles += "<p><strong>Nombre: </strong> " + data.producto.nombre + "</p>"
      } else {
        respuestaDetalles += "<p><strong>Nombre: </strong></p>"
      }

      respuestaDetalles += "<p><strong>Nombre corto: </strong> " + data.producto.nombre_corto + "</p>"
      respuestaDetalles += "<p><strong>Descripcion: </strong> " + data.producto.descripcion + "</p>"
      respuestaDetalles += "<p><strong>PVP: </strong> " + data.producto.PVP + "</p>"
      respuestaDetalles += "<p><strong>Familia pertenece: </strong> " + data.producto.familia + "</p>"

      $("#respuesta").html(respuestaDetalles)
    }

  }).fail(function (estado, textoEstado) {
    $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
  })
}

function MostrarInsertar() {
  // traernos las familias

  $.ajax({

    url: DIR_SERV.concat("/familias"),
    dataType: "json",
    type: "get",

  }).done(function (data) {

    // si he recibido errror
    if (data.mensaje) {
      $("#respuesta").html(data.mensaje)
    } else {

      let formularioInsertar = ""
      formularioInsertar += "<form onSubmit='event.preventDefault();llamadaInsertar()'>"
      formularioInsertar += "<p><label for='codigo'>Código: </label><input name='codigo' id='codigo' type='text'/><span class='error'></span></p>"
      formularioInsertar += "<p><label for='nombre'>Nombre: </label><input name='nombre' id='nombre' type='text'/></p>"
      formularioInsertar += "<p><label for='nombrecorto'>Nombre corto: </label><input name='nombrecorto' id='nombrecorto' type='text'/><span class='error'></span></p>"
      formularioInsertar += "<p><label for='descripcion'>Descripcion: </label><input name='descripcion' id='descripcion' type='text'/></p>"
      formularioInsertar += "<p><label for='pvp'>PVP: </label><input name='pvp' id='pvp' type='number'/><span class='error'></span></p>"
      formularioInsertar += "<p><label for='familia'>Familia: </label>"
      formularioInsertar += "<select name='familia' id='familia'>"
      $.each(data.familia, function (key, value) {
        formularioInsertar += "<option value='" + value.cod + "'>" + value.nombre + "</option>"
      })
      formularioInsertar += "</select>"
      formularioInsertar += "</p>"
      formularioInsertar += "<button type='submit'>Insertar</button>"
      formularioInsertar += "<br>"
      formularioInsertar += "<br>"
      formularioInsertar += "</form>"
      $("#respuesta").html(formularioInsertar)
    }


  }).fail(function (estado, textoEstado) {
    $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
  })

}



function llamadaInsertar() {
  // dejar los span de errores vacios
  $("input").next("span.error").text("")

  // comprobar errores
  let errorCodigo = $("input#codigo").val() == ""
  let errorNombreCorto = $("input#nombrecorto").val() == ""
  let errorPvp = isNaN($("input#pvp").val()) || $("input#pvp").val() == ""

  if (errorCodigo) {
    $("input#codigo").next("span").text("Introduce un código")
  }

  if (errorNombreCorto) {
    $("input#nombrecorto").next("span").text("Introduce un nombre corto")
  }

  if (errorPvp) {
    $("input#pvp").next("span").text("Introduce un pvp valido")
  }

  let errorFormulario = errorCodigo || errorNombreCorto || errorPvp

  if (!errorFormulario) {

    // comprobar que el codigo no este repetido
    $.ajax({

      url: DIR_SERV.concat("/repetido/producto/cod/" + $("input#codigo").val()),
      dataType: "json",
      type: "get",

    }).done(function (data) {

      // si he recibido errror
      if (data.mensaje) {
        $("#respuesta").html(data.mensaje)
      } else if (data.repetido) {
        $("input#codigo").next("span.error").text("El codigo ya existe")
        errorCodigo = true
      }

    }).fail(function (estado, textoEstado) {
      $("#respuesta").html(error_ajax_jquery(estado, textoEstado))

    })

    // comprobar que el nombre corto no este repetido
    $.ajax({

      url: DIR_SERV.concat("/repetido/producto/nombre_corto/" + $("input#nombrecorto").val()),
      dataType: "json",
      type: "get",

    }).done(function (data) {

      // si he recibido errror
      if (data.mensaje) {
        $("#respuesta").html(data.mensaje)
      } else if (data.repetido) {
        $("input#nombrecorto").next("span.error").text("El nombre corto ya existe")
        errorNombreCorto = true
      }

    }).fail(function (estado, textoEstado) {
      $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
    })


    if (!errorCodigo && !errorNombreCorto) {

      // llamada al insertar con los datos
      $.ajax({

        url: DIR_SERV.concat("/producto/insertar"),
        dataType: "json",
        type: "post",
        data: {
          cod: $("input#codigo").val(),
          nombre: $("input#nombre").val(),
          nombre_corto: $("input#nombrecorto").val(),
          descripcion: $("input#descripcion").val(),
          PVP: $("input#pvp").val(),
          familia: $("select#familia").val(),
        },

      }).done(function (data) {

        // si he recibido error
        if (data.mensaje) {
          $("#respuesta").html(data.mensaje)
        } else {
          $("#respuesta").html("Se ha insertado con éxito")
        }


      }).fail(function (estado, textoEstado) {
        $("#respuesta").html(error_ajax_jquery(estado, textoEstado))

      })

      llamadaGetProductos()
    }
  }
}


function confirmarBorrado(codigoProducto) {
  let campoBorrar = "div#respuesta"
  let preguntaBorrar = ""
  preguntaBorrar += "<h2>Borrado de producto</h2>"
  preguntaBorrar += "<p>¿Estas seguro que quieres borrar el poducto con codigo " + codigoProducto + "?</p>"
  preguntaBorrar += "<button onclick='llamadaBorrarCampo(\"" + campoBorrar + "\")'>Volver</button>"
  preguntaBorrar += "<button onclick='llamadaDelete(\"" + codigoProducto + "\")'>Continuar</button>"


  $("#respuesta").html(preguntaBorrar)
}

function llamadaBorrarCampo(idcampo) {
  $(idcampo).html("")
}

function error_ajax_jquery(jqXHR, textStatus) {
  var respuesta;
  if (jqXHR.status === 0) {

    respuesta = 'Not connect: Verify Network.';

  } else if (jqXHR.status == 404) {

    respuesta = 'Requested page not found [404]';

  } else if (jqXHR.status == 500) {

    respuesta = 'Internal Server Error [500].';

  } else if (textStatus === 'parsererror') {

    respuesta = 'Requested JSON parse failed.';

  } else if (textStatus === 'timeout') {

    respuesta = 'Time out error.';

  } else if (textStatus === 'abort') {

    respuesta = 'Ajax request aborted.';

  } else {

    respuesta = 'Uncaught Error: ' + jqXHR.responseText;

  }
  return respuesta;
}

$(document).ready(function () {
  llamadaGetProductos()
})