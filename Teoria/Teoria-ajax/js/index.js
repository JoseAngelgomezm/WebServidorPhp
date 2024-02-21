const DIR_SERV =           "http://localhost/Proyectos/WebServidorPhp/Teoria/Teoria_servicios_web/Api"
const DIR_SERV_PRODUCTOS = "http://localhost/Proyectos/WebServidorPhp/Ejercicios_API_REST/Ejercicio1/servicios_rest"

const llamadaGetSaludo = () => {
    // llamada ajax con parametro por url
    $.ajax({

        url:DIR_SERV.concat("/saludo"),
        dataType: "json",
        type: "get",

    }).done(function (data) {

        $("#respuesta").html(data.mensaje)

    }).fail(function (estado,textoEstado) {
        $("#respuesta").html(error_ajax_jquery(estado,textoEstado))
    })
}

const llamadaGetSaludoNombre = () => {
    $.ajax({
        // url:encondeURL(DIR_SERV+"/saludo/Jose Angel Gomez Morillo")),
        url:DIR_SERV.concat("/saludo/Jose Angel Gomez Morillo"),
        dataType: "json",
        type: "get",

    }).done(function (data) {

        $("#respuesta").html(data.mensaje)

    }).fail(function (estado,textoEstado) {
        $("#respuesta").html(error_ajax_jquery(estado,textoEstado))
    })
}

const llamadaPostSaludo = () => {
    $.ajax({

        url:DIR_SERV.concat("/saludo"),
        dataType: "json",
        type: "post",
        data:{nombre:"Pepito Grillo"} // por aqui se pasan los parametros por abajo

    }).done(function (data) {

        $("#respuesta").html(data.mensaje)

    }).fail(function (estado,textoEstado) {
        $("#respuesta").html(error_ajax_jquery(estado,textoEstado))
    })
}

const llamadaDeleteSaludo = () => {
    $.ajax({

        url:DIR_SERV.concat("/saludo"),
        dataType: "json",
        type: "delete",
        data:{id_saludo: "20"}
    }).done(function (data) {

        $("#respuesta").html(data.mensaje)

    }).fail(function (estado,textoEstado) {
        $("#respuesta").html(error_ajax_jquery(estado,textoEstado))
    })
}

const llamadaPutActualizarSaludo = () => {
    $.ajax({

        url:DIR_SERV.concat("/actualizar_saludo/20"),
        dataType: "json",
        type: "put",
        data:{nombre:"Jose Angel"}
    }).done(function (data) {

        $("#respuesta").html(data.mensaje)

    }).fail(function (estado,textoEstado) {
        $("#respuesta").html(error_ajax_jquery(estado,textoEstado))
    })
}

const llamadaGetProductos = () => {
    $.ajax({

        url:DIR_SERV_PRODUCTOS.concat("/productos"),
        dataType: "json",
        type: "get",

    }).done(function (data) {

        // si he recibido errror
        if(data.mensaje_error){
            $("#respuesta").html(data.mensaje_error)
        }else{

            let tablaProductos = "";
            tablaProductos += "<table>"
            tablaProductos += "<tr>"
            tablaProductos += "<td>COD</td>"
            tablaProductos += "<td>Nombre Corto</td>"
            tablaProductos += "<td>PVP</td>"
            tablaProductos += "</tr>"
            $.each(data.productos, function(key, value){
                tablaProductos += "<tr>"
                tablaProductos += "<td>"+ value["cod"] +"</td>"
                tablaProductos += "<td>"+ value["nombre_corto"] +"</td>"
                tablaProductos += "<td>"+ value["PVP"] +"</td>"
                tablaProductos += "</tr>"
            })
            
            tablaProductos += "</table>"

            $("#respuesta").html(tablaProductos)
        }

    }).fail(function (estado,textoEstado) {
        $("#respuesta").html(error_ajax_jquery(estado,textoEstado))
    })
}

function error_ajax_jquery( jqXHR, textStatus) 
{
    var respuesta;
    if (jqXHR.status === 0) {
  
      respuesta='Not connect: Verify Network.';
  
    } else if (jqXHR.status == 404) {
  
      respuesta='Requested page not found [404]';
  
    } else if (jqXHR.status == 500) {
  
      respuesta='Internal Server Error [500].';
  
    } else if (textStatus === 'parsererror') {
  
      respuesta='Requested JSON parse failed.';
  
    }  else if (textStatus === 'timeout') {
  
        respuesta='Time out error.';
    
      } else if (textStatus === 'abort') {
    
        respuesta='Ajax request aborted.';
    
      } else {
    
        respuesta='Uncaught Error: ' + jqXHR.responseText;
    
      }
      return respuesta;
}

$(document).ready(function(){
    llamadaGetProductos()
})