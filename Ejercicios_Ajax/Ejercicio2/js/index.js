
const TIEMPO_MINIMO_MINUTOS = 2
$(document).ready(function () {
  if (localStorage.ultima_accion && localStorage.api_session) {

    // hay login, controlar el tiempo, si pasa, hacer el logueado
    if ((new Date() / 1000) - localStorage.ultima_accion < TIEMPO_MINIMO_MINUTOS * 60) {

      // pasar seguridad
      $.ajax({

        url: "servicios_rest/logueado",
        dataType: "json",
        type: "get",
        data: { api_session: localStorage.api_session }

      }).done(function (data) {

        // si he recibido error
        if (data.mensaje) {
          localStorage.clear()
          mostrarFormularioLogin("No se encuentra registrado")
        } else if (data.error) {
          $("#respuesta").html(data.error)
        } else if (data.no_auth) {
          localStorage.clear()
          $("#respuesta").html(data.no_auth)
        } else {
          // todo ha ido bien, ha pasado control tiempo y seguridad
          // actualizar tiempo y obtener api session
          localStorage.setItem("ultima_accion", new Date() / 1000)
          localStorage.setItem("api_session", data.api_session)
          // y mostrar vista segun tipo usuario
          mostrarVistaUsuario(data.usuario)
        }

      }).fail(function (estado, textoEstado) {
        localStorage.clear()
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

const seguridad = () => {
  if (localStorage.ultima_accion && localStorage.api_session) {

    // hay login, controlar el tiempo, si pasa, hacer el logueado
    if ((new Date() / 1000) - localStorage.ultima_accion < TIEMPO_MINIMO_MINUTOS * 60) {

      // pasar seguridad
      $.ajax({

        url: "servicios_rest/logueado",
        dataType: "json",
        type: "get",
        data: { api_session: localStorage.api_session }

      }).done(function (data) {

        // si he recibido error
        if (data.mensaje) {
          localStorage.clear()
          mostrarFormularioLogin("No se encuentra registrado")
        } else if (data.error) {
          $("#respuesta").html(data.error)
        } else if (data.no_auth) {
          localStorage.clear()
          $("#respuesta").html(data.no_auth)
        } else {
          // todo ha ido bien, ha pasado control tiempo y seguridad
          // actualizar tiempo y obtener api session
          localStorage.setItem("ultima_accion", new Date() / 1000)
          // y mostrar vista segun tipo usuario
          mostrarVistaUsuario(data.usuario)
        }

      }).fail(function (estado, textoEstado) {
        localStorage.clear()
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
}

const mostrarVistaUsuario = (datosUsuario) => {
  $("#principal").html("")

  vistaUsuario = ""

  vistaUsuario += "<div>"
  vistaUsuario += "<h2> Bienvenido " + datosUsuario.usuario + " tipo: " + datosUsuario.tipo + "</h2>"
  vistaUsuario += "<button onclick='cerrarSession()'>Salir</button>"
  vistaUsuario += "</div>"

  $("#principal").html(vistaUsuario)


}

const cerrarSession = () => {
  localStorage.clear()
  mostrarFormularioLogin("")
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

  formulario_login += "<p><span id='errorLogin'>" + error + "</span></p>"

  formulario_login += "<button type='submit' name='entrar'>Entrar</button>"
  formulario_login += "</form>"
  formulario_login += "</div>"

  $("div#principal").html(formulario_login)
}

const llamadaLogin = () => {
  let usuariohtml = $("input#usuario").val()
  let clavehtml = md5($("input#contraseña").val())

  $.ajax({

    url: "servicios_rest/login",
    dataType: "json",
    type: "post",
    data: {
      usuario: usuariohtml,
      clave: clavehtml,
    }

  }).done(function (data) {

    // si he recibido error
    if (data.mensaje) {
      $("span#errorLogin").html("usuario/contraseña incorrecto")
      $("input#contraseña").html("")
      $("input#contraseña").focus()
      $()
    } else if (data.error) {
      $("#respuesta").html(data.error)
    } else if (data.usuario) {
      localStorage.setItem("ultima_accion", new Date() / 1000)
      localStorage.setItem("api_session", data.api_session)
      window.location.href = "index.html"
    }

  }).fail(function (estado, textoEstado) {
    $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
  })
}

const llamadaGetProductos = () => {
  $.ajax({

    url: "servicios_rest/productos",
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

    url: "servicios_rest/producto/borrar/" + codigo,
    dataType: "json",
    type: "delete",
    data: { api_session: localStorage.api_session }

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

    url: "servicios_rest/producto/" + cod,
    dataType: "json",
    type: "get",
    data: { api_session: localStorage.api_session }

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

    url: "servicios_rest/familias",
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

      url: "servicios_rest/repetido/productos/cod/" + $("input#codigo").val(),
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

      url: "servicios_rest/repetido/producto/nombre_corto/" + $("input#nombrecorto").val(),
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

        url: "servicios_rest/producto/insertar",
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

// md5

/// Funciones para el MD5
function md5cycle(x, k) {
  var a = x[0], b = x[1], c = x[2], d = x[3];

  a = ff(a, b, c, d, k[0], 7, -680876936);
  d = ff(d, a, b, c, k[1], 12, -389564586);
  c = ff(c, d, a, b, k[2], 17, 606105819);
  b = ff(b, c, d, a, k[3], 22, -1044525330);
  a = ff(a, b, c, d, k[4], 7, -176418897);
  d = ff(d, a, b, c, k[5], 12, 1200080426);
  c = ff(c, d, a, b, k[6], 17, -1473231341);
  b = ff(b, c, d, a, k[7], 22, -45705983);
  a = ff(a, b, c, d, k[8], 7, 1770035416);
  d = ff(d, a, b, c, k[9], 12, -1958414417);
  c = ff(c, d, a, b, k[10], 17, -42063);
  b = ff(b, c, d, a, k[11], 22, -1990404162);
  a = ff(a, b, c, d, k[12], 7, 1804603682);
  d = ff(d, a, b, c, k[13], 12, -40341101);
  c = ff(c, d, a, b, k[14], 17, -1502002290);
  b = ff(b, c, d, a, k[15], 22, 1236535329);

  a = gg(a, b, c, d, k[1], 5, -165796510);
  d = gg(d, a, b, c, k[6], 9, -1069501632);
  c = gg(c, d, a, b, k[11], 14, 643717713);
  b = gg(b, c, d, a, k[0], 20, -373897302);
  a = gg(a, b, c, d, k[5], 5, -701558691);
  d = gg(d, a, b, c, k[10], 9, 38016083);
  c = gg(c, d, a, b, k[15], 14, -660478335);
  b = gg(b, c, d, a, k[4], 20, -405537848);
  a = gg(a, b, c, d, k[9], 5, 568446438);
  d = gg(d, a, b, c, k[14], 9, -1019803690);
  c = gg(c, d, a, b, k[3], 14, -187363961);
  b = gg(b, c, d, a, k[8], 20, 1163531501);
  a = gg(a, b, c, d, k[13], 5, -1444681467);
  d = gg(d, a, b, c, k[2], 9, -51403784);
  c = gg(c, d, a, b, k[7], 14, 1735328473);
  b = gg(b, c, d, a, k[12], 20, -1926607734);

  a = hh(a, b, c, d, k[5], 4, -378558);
  d = hh(d, a, b, c, k[8], 11, -2022574463);
  c = hh(c, d, a, b, k[11], 16, 1839030562);
  b = hh(b, c, d, a, k[14], 23, -35309556);
  a = hh(a, b, c, d, k[1], 4, -1530992060);
  d = hh(d, a, b, c, k[4], 11, 1272893353);
  c = hh(c, d, a, b, k[7], 16, -155497632);
  b = hh(b, c, d, a, k[10], 23, -1094730640);
  a = hh(a, b, c, d, k[13], 4, 681279174);
  d = hh(d, a, b, c, k[0], 11, -358537222);
  c = hh(c, d, a, b, k[3], 16, -722521979);
  b = hh(b, c, d, a, k[6], 23, 76029189);
  a = hh(a, b, c, d, k[9], 4, -640364487);
  d = hh(d, a, b, c, k[12], 11, -421815835);
  c = hh(c, d, a, b, k[15], 16, 530742520);
  b = hh(b, c, d, a, k[2], 23, -995338651);

  a = ii(a, b, c, d, k[0], 6, -198630844);
  d = ii(d, a, b, c, k[7], 10, 1126891415);
  c = ii(c, d, a, b, k[14], 15, -1416354905);
  b = ii(b, c, d, a, k[5], 21, -57434055);
  a = ii(a, b, c, d, k[12], 6, 1700485571);
  d = ii(d, a, b, c, k[3], 10, -1894986606);
  c = ii(c, d, a, b, k[10], 15, -1051523);
  b = ii(b, c, d, a, k[1], 21, -2054922799);
  a = ii(a, b, c, d, k[8], 6, 1873313359);
  d = ii(d, a, b, c, k[15], 10, -30611744);
  c = ii(c, d, a, b, k[6], 15, -1560198380);
  b = ii(b, c, d, a, k[13], 21, 1309151649);
  a = ii(a, b, c, d, k[4], 6, -145523070);
  d = ii(d, a, b, c, k[11], 10, -1120210379);
  c = ii(c, d, a, b, k[2], 15, 718787259);
  b = ii(b, c, d, a, k[9], 21, -343485551);

  x[0] = add32(a, x[0]);
  x[1] = add32(b, x[1]);
  x[2] = add32(c, x[2]);
  x[3] = add32(d, x[3]);

}

function cmn(q, a, b, x, s, t) {
  a = add32(add32(a, q), add32(x, t));
  return add32((a << s) | (a >>> (32 - s)), b);
}

function ff(a, b, c, d, x, s, t) {
  return cmn((b & c) | ((~b) & d), a, b, x, s, t);
}

function gg(a, b, c, d, x, s, t) {
  return cmn((b & d) | (c & (~d)), a, b, x, s, t);
}

function hh(a, b, c, d, x, s, t) {
  return cmn(b ^ c ^ d, a, b, x, s, t);
}

function ii(a, b, c, d, x, s, t) {
  return cmn(c ^ (b | (~d)), a, b, x, s, t);
}

function md51(s) {
  txt = '';
  var n = s.length,
    state = [1732584193, -271733879, -1732584194, 271733878], i;
  for (i = 64; i <= s.length; i += 64) {
    md5cycle(state, md5blk(s.substring(i - 64, i)));
  }
  s = s.substring(i - 64);
  var tail = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
  for (i = 0; i < s.length; i++)
    tail[i >> 2] |= s.charCodeAt(i) << ((i % 4) << 3);
  tail[i >> 2] |= 0x80 << ((i % 4) << 3);
  if (i > 55) {
    md5cycle(state, tail);
    for (i = 0; i < 16; i++) tail[i] = 0;
  }
  tail[14] = n * 8;
  md5cycle(state, tail);
  return state;
}

/* there needs to be support for Unicode here,
 * unless we pretend that we can redefine the MD-5
 * algorithm for multi-byte characters (perhaps
 * by adding every four 16-bit characters and
 * shortening the sum to 32 bits). Otherwise
 * I suggest performing MD-5 as if every character
 * was two bytes--e.g., 0040 0025 = @%--but then
 * how will an ordinary MD-5 sum be matched?
 * There is no way to standardize text to something
 * like UTF-8 before transformation; speed cost is
 * utterly prohibitive. The JavaScript standard
 * itself needs to look at this: it should start
 * providing access to strings as preformed UTF-8
 * 8-bit unsigned value arrays.
 */
function md5blk(s) { /* I figured global was faster.   */
  var md5blks = [], i; /* Andy King said do it this way. */
  for (i = 0; i < 64; i += 4) {
    md5blks[i >> 2] = s.charCodeAt(i)
      + (s.charCodeAt(i + 1) << 8)
      + (s.charCodeAt(i + 2) << 16)
      + (s.charCodeAt(i + 3) << 24);
  }
  return md5blks;
}

var hex_chr = '0123456789abcdef'.split('');

function rhex(n) {
  var s = '', j = 0;
  for (; j < 4; j++)
    s += hex_chr[(n >> (j * 8 + 4)) & 0x0F]
      + hex_chr[(n >> (j * 8)) & 0x0F];
  return s;
}

function hex(x) {
  for (var i = 0; i < x.length; i++)
    x[i] = rhex(x[i]);
  return x.join('');
}

function md5(s) {
  return hex(md51(s));
}
function add32(a, b) {
  return (a + b) & 0xFFFFFFFF;
}


// error ajax
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
