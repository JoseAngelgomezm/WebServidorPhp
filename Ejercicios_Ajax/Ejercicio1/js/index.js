const DIR_SERV = "http://localhost/Proyectos/WebServidorPhp/Ejercicios_API_REST/Ejercicio1/servicios_rest"

const llamadaGetProductos = () => {
    $.ajax({

        url:DIR_SERV.concat("/productos"),
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
                tablaProductos += "<td><button onclick='llamadaGetDetalles(\""+value["cod"]+"\")'>"+ value["cod"] +"</button></td>";
                                tablaProductos += "<td>"+ value["nombre_corto"] +"</td>"
                tablaProductos += "<td>"+ value["PVP"] +"</td>"
                tablaProductos += "</tr>"
            })
            
            tablaProductos += "</table>"

            $("#tablaProductos").html(tablaProductos)
        }

    }).fail(function (estado,textoEstado) {
        $("#respuesta").html(error_ajax_jquery(estado,textoEstado))
    })
}

function llamadaGetDetalles(cod){
  
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