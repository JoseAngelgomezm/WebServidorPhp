
const TIEMPO_MINIMO_MINUTOS = 2
$(document).ready(function () {
  if (localStorage.ultima_accion && localStorage.api_session) {

    // hay login, controlar el tiempo, si pasa, hacer el logueado
    if ((new Date() / 1000) - localStorage.ultima_accion < TIEMPO_MINIMO_MINUTOS * 60) {

      // pasar seguridad
      $.ajax({

        url: DIR_SERV.concat("/logueado"),
        dataType: "json",
        type: "get",

      }).done(function (data) {

        // si he recibido error
        if (data.mensaje) {
          localStorage.clear()
          mostrarFormularioLogin("No se encuentra registrado")
        } else if (data.error) {
          $("#respuesta").html(data.error)
        } else if (data.no_auth) {
          $("#respuesta").html(data.no_auth)
        } else {
          // todo ha ido bien, ha pasado control tiempo y seguridad
          // actualizar tiempo y obtener api session
          localStorage.setItem("ultima_accion", new Date() / 1000)
          localStorage.setItem("api_session", data.api_session)

          // y mnostrar vista segun tipo usuario
          mostrarVistaUsuario(data.usuario)
        }

      }).fail(function (estado, textoEstado) {
        $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
      })

      // ha expirado la session
    } else {
      localStorage.clear()
      mostrarFormularioLogin("Su tiempo de session ha terminado")
    }
  } else {
    // mostrar formulario login
    mostrarFormularioLogin("");
  }
})

const mostrarVistaUsuario = (datosUsuario) => {
  $("#respuesta").html("")

  vistaUsuario = ""

  vistaUsuario += "<div>"
  vistaUsuario += "<h2> Bienvenido " + datosUsuario.nombre + "</h2>"
  vistaUsuario += "<button onclick='cerrarSession()'>Salir</button>"
  vistaUsuario += "</div>"

  $("#principal").html(vistaUsuario)

}

const mostrarFormularioLogin = (error) => {
  formulario_login = ""

  formulario_login += "<div>"
  formulario_login += "<form onsubmit='event.preventDefault(), llamadaLogin()'>"

  formulario_login += "<p>"
  formulario_login += "<label for='usuario'>Usuario:</label>"
  formulario_login += "<input type='text' name='usuario' id='usuario'></input>"
  formulario_login += "</p>"

  formulario_login += "<p>"
  formulario_login += "<label for='contraseña'>Contraseña:</label>"
  formulario_login += "<input type='password' name='contraseña' id='contraseña'></input>"
  formulario_login += "<input onkeyup='cifrarClave()' name='contraseñaCifrada' id='contraseñaCidfrada' hidden></input>"
  formulario_login += "</p>"

  formulario_login += "<span id='errorLogin'>" + error + "</span>"

  formulario_login += "<button type='submit' name='entrar'>Entrar</button>"
  formulario_login += "</form>"
  formulario_login += "</div>"

  $("div#principal").html(formulario_login)
}

const cifrarClave = () => {

}

const llamadaLogin = () => {
  $.ajax({

    url: "servicios_rest/login",
    dataType: "json",
    type: "post",
    data: {usuario : $("input#usuario").val() , clave: $("input#contraseña").val()}

  }).done(function (data) {

    // si he recibido error
    if (data.mensaje) {
      $("span#errorLogin").html("usuario/contraseña incorrecto")
      $("input#contraseña").html("")
      $("input#contraseña").focus()
      $()
    } else if (data.error) {
      $("#respuesta").html(data.error)
    } else {
      localStorage.setItem("ultima_accion", new Date() / 1000)
      localStorage.setItem("api_session", data.api_session)
    }

  }).fail(function (estado, textoEstado) {
    $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
  })
}

const llamadaGetProductos = () => {
  $.ajax({

    url: DIR_SERV.concat("/productos"),
    dataType: "json",
    type: "get",

  }).done(function (data) {

    // si he recibido error
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
        tablaProductos += "<td><button onclick='confirmarBorrado(\"" + value["cod"] + "\")'>Borrar</button> - <button onclick='MostrarFormularioEditar()'>Editar</button></td>"
        tablaProductos += "</tr>"
      })

      tablaProductos += "</table>"

      $("#respuesta").html(tablaProductos)
    }

  }).fail(function (estado, textoEstado) {
    $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
  })
}


function MostrarFormularioEditar() {

}

function llamadaDelete(codigo) {
  $.ajax({

    url: DIR_SERV.concat("/producto/borrar/" + codigo),
    dataType: "json",
    type: "delete",

  }).done(function (data) {

    // si he recibido error
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

    // si he recibido error
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

    // si he recibido error
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

      // si he recibido error
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

      // si he recibido error
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
