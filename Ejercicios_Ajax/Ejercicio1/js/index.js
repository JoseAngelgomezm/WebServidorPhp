const DIR_SERV = "http://localhost/Proyectos/WebServidorPhp/Ejercicios_API_REST/Ejercicio1/servicios_rest"

const llamadaGetProductos = () => {
  $.ajax({

    url: DIR_SERV.concat("/productos"),
    dataType: "json",
    type: "get",

  }).done(function (data) {

    // si he recibido errror
    if (data.mensaje_error) {
      $("#respuesta").html(data.mensaje_error)
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

      $("#tablaProductos").html(tablaProductos)
    }

  }).fail(function (estado, textoEstado) {
    $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
  })
}


function MostrarFormularioEditar() {

}

function llamadaDelete(codigo) {
  $.ajax({

    url: DIR_SERV.concat("/producto/borrar/"+codigo),
    dataType: "json",
    type: "delete",

  }).done(function (data) {

    // si he recibido errror
    if (data.mensaje_error) {
      $("#respuesta").html(data.mensaje_error)
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
    if (data.mensaje_error) {
      $("#respuesta").html(data.mensaje_error)
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
    if (data.mensaje_error) {
      $("#respuesta").html(data.mensaje_error)
    } else {
      
      let formularioInsertar = ""
      formularioInsertar += "<form onSubmit='event.preventDefault();llamadaInsertar()'>"
      formularioInsertar += "<p><label for='nombre'>Nombre: </label><input name='nombre' id='nombre' type='text'/></p>"
      formularioInsertar += "<p><label for='nombrecorto'>Nombre corto: </label><input name='nombrecorto' id='nombrecorto' type='text'/></p>"
      formularioInsertar += "<p><label for='descripcion'>Descripcion: </label><input name='descripcion' id='descripcion' type='text'/></p>"
      formularioInsertar += "<p><label for='pvp'>PVP: </label><input name='pvp' type='text'/></p>"
      formularioInsertar += "<select name='familia'>"
      $.each(data.familia, function (key, value) {
        formularioInsertar += "<option>" + value.nombre + "</value>"
      })
      formularioInsertar += "</select>"
      formularioInsertar += "<button type='submit'>Insertar</button>"
      formularioInsertar += "</form>"
      $("#respuesta").html(formularioInsertar)
    }
   

  }).fail(function (estado, textoEstado) {
    $("#respuesta").html(error_ajax_jquery(estado, textoEstado))
  })

}

function llamadaInsertar() {
  
}


function confirmarBorrado(codigoProducto){
  let campoBorrar = "div#respuesta"
  let preguntaBorrar = ""
  preguntaBorrar += "<h2>Borrado de producto</h2>"
  preguntaBorrar += "<p>¿Estas seguro que quieres borrar el poducto con codigo "+codigoProducto+"?</p>"
  preguntaBorrar += "<button onclick='llamadaBorrarCampo(\"" + campoBorrar + "\")'>Volver</button>"
  preguntaBorrar += "<button onclick='llamadaDelete(\"" + codigoProducto + "\")'>Continuar</button>"


  $("#respuesta").html(preguntaBorrar)  
}

function llamadaBorrarCampo(idcampo){
  console.log(idcampo)
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