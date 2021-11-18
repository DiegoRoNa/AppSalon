let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp(){

    mostrarSeccion(); //MOSTRAR LA SECCION INICIAL QUE ES SERVICIOS
    tabs(); //CAMBIA LA SECCION CUANDO SE PRESIONEN LOS TABS
    botonesPaginador(); //AGREGA O QUITA LOS BOTONES DEL PAGINADOR
    paginaSiguiente();
    paginaAnterior();

    consultarAPI(); //CONSULTA LA API EN EL BACKEND DE PHP

    idCliente(); //TRAE EL ID DEL USUARIO AUTENTICADO A CITA
    nombreCliente(); //TRAE EL NOMBRE DEL USUARIO AUTENTICADO A CITA
    seleccionarFecha(); //TRAE LA FECHA DE LA CITA
    seleccionarHora(); //TRAE LA HORA DE LA CITA
    mostrarResumen(); //MOSTRAR EL ARREGLO cita LLENO EN LA SECCION resumen

}

function mostrarSeccion(){
    //Ocultar la seccion que tenga la clase mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }

    //Seleccionar la seccion con el paso
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    //Quitar la clase actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if (tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    //Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`); //seleccionando por data-paso
    tab.classList.add('actual');
    
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button'); //ARREGLO de botones del tabs

    //recorremos el arreglo para usar cada boton
    botones.forEach(boton => {
        boton.addEventListener('click', function(e){
            paso = parseInt(e.target.dataset.paso); //saca el atributo HTML data-paso de cada boton
            
            mostrarSeccion();
            botonesPaginador();
        });
    });
    
}

//Botones de la paginacion
function botonesPaginador(){
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if (paso === 1) { //Pagina 1 (SERVICIOS)
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }else if(paso === 3){ //Pagina 3 (RESUMEN)
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');

        mostrarResumen();
    }else{
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }

    mostrarSeccion();

}

function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function(){

        if (paso <= pasoInicial) return;
        paso--;
        
        botonesPaginador();
        
    });
}

function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function(){
        
        if (paso >= pasoFinal) return;
        paso++;
        
        botonesPaginador();
        
    });
}


//CONSULTA A LA API
async function consultarAPI(){ //async, para aumentar el performance de la app

    //Seusa TRY-CATCH porque puede haber un error en la conexión a la API
    //Consume memoria, pero se debe usar en casos críticos
    //Si hay un error, la app no detiene su funcionamiento

    try {
        //URL de la API que se va a consumir
        const url = 'http://localhost:3000/api/servicios';

        // await detiene la ejecucion hasta que termine de hacer la consulta
        // fetch permite consumir la URL
        const resultado = await fetch(url);//Devuelve un Responce

        //Tomamos el resultado que devuelve la URL
        const servicios = await resultado.json();

        //Monstramos los resultados
        mostrarServicios(servicios);
        
    } catch (error) {
        console.log(error);
        
    }
}


//MOSTRAR SERVICIOS
function mostrarServicios(servicios){

    //Recorremos el arreglo
    servicios.forEach(servicio => {
        //DESTRUCTUR... separa y crea la variable
        const {id, nombre, precio} = servicio;

        //SCRIPTING = Más lento para desarrollar, pero más eficaz y seguro
        
        //Parrafo nombre
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        //Parrafo precio
        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        //contenedor del servicio
        const serviciosDiv = document.createElement('DIV');
        serviciosDiv.classList.add('servicio');
        serviciosDiv.dataset.idServicio = id;//data-id-servicio"id"
        serviciosDiv.onclick = function(){
            seleccionaServicio(servicio); //Evento

        }
        serviciosDiv.appendChild(nombreServicio);//agregamos el parrafo nombre
        serviciosDiv.appendChild(precioServicio);//agregamos el parrafo precio
        
        //Seleccionamos el div del index de Cita
        document.querySelector('#servicios').appendChild(serviciosDiv);
        
    });
    
}

//SELECCIONAR EL SERVICIO Y LLENAR EL ARREGLO CITA
function seleccionaServicio(servicio){
    const {id} = servicio; //extraemos el ID del servicio
    const { servicios } = cita; //extraemos el elemeno servicios del arreglo cita

    //SERVICIO SELECCIONADO POR EL id
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //COMPROBAR SI UN SERVICIO YA FUE AGREGADO A servicios
    //some: Busca un elemento en el objeto y devuelve true o false, si existe o no
    //crea una variable temporal en memoria "agregado" y en id, lo compara con el id al que le da click
    if (servicios.some( agregado => agregado.id === id )) {
        //SI YA ESTA AGREGADO, ELIMINARLO
        //filter: Busca un elemento en el objeto, si es true lo elimina
        //busca el servicio clikceado, si su id es dirente al otro seleccionado, lo elimina
        cita.servicios = servicios.filter( agregado => agregado.id !== id );
        divServicio.classList.remove('seleccionado');
    }else{
        //SI NO ESTÁ AGREGADO, AGREGARLO
        //CREAMOS UNA COPIA EN EL ELEMENTO servicios, LLENANDOLO CON LO QUE HAY EN servicio
        cita.servicios = [...servicios, servicio];//LO PASAMOS A SERVICIOS DEL ARREGLO cita
        divServicio.classList.add('seleccionado');
    }

}

//agregar el id del usuario a cita
function idCliente(){
    //atributo value="" del input nombre
    cita.id = document.querySelector('#id').value;
}

//agregar el nombre del usuario a cita
function nombreCliente(){
    //atributo value="" del input nombre
    cita.nombre = document.querySelector('#nombre').value;
}


function seleccionarFecha(){
    //seleccionamos el input DATE y le agregamos evento
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {

        //Tomamos el dia de la semana seleccionado
        //sabado = 6 ... domingo = 0
        const dia = new Date(e.target.value).getUTCDay();

        //includes: busca el elemento y devuelve true o false
        if ( [6, 0].includes(dia) ) {
            e.target.value = '';//vaciamos el input DATE

            //mostramos una alerta para sabados y domingos
            mostrarAlerta('Fines de semana no trabajamos', 'error', '#paso-2 p');
        }else{
            cita.fecha = e.target.value;
        }
        
    });
}

function seleccionarHora(){
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e){
        
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        
        if (hora < 8 || hora >= 21) {
            mostrarAlerta('En esa hora no está abierto', 'error', '#paso-2 p');
        }else{
            cita.hora = e.target.value;

        }
        
    });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true){

    //VERIFICAR SI YA ESTA LA ALERTA
    const alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia){
        alertaPrevia.remove();
    };

    //SI NO ESTÁ, LA CREAMOS CON SCRIPTING
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if (desaparece) {
        //QUITAR LA ALERTA 3 SEGUNDOS DESPUES
        setTimeout( () => {
            alerta.remove();
        }, 3000);   
    }
}


//RESUMEN DE LA CITA
function mostrarResumen(){
    const resumen = document.querySelector('.contenido-resumen');

    //LIMPIAR EL CONTENIDO DEL RESUMEN
    while(resumen.firstChild){ //Mientras exista algo en el div resumen, elimarlo
        resumen.removeChild(resumen.firstChild);
    }

    //EVALUAR SI LOS ELEMENTOS DE cita YA ESTÁN LLENOS
    if ( Object.values(cita).includes('') || cita.servicios.length === 0 ) {
        mostrarAlerta('Selecciona un servicio, la fecha y hora', 'error', '.contenido-resumen', false);

        return;
    }

    //SCRIPTING PARA MOSTRAR EL RESUMEN

    //Formatear elarreglo de cita
    const { nombre, fecha, hora, servicios } = cita;

    //HEADING DE SERVICIOS
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de tus servicios solicitados';
    resumen.appendChild(headingServicios);

    //SERVICIOS
    servicios.forEach(servicio => {//recorrer el array de servicios
        const { id, precio, nombre } = servicio; //elementos de cada servicio
        
        //contenedor
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        //nombre del servicio
        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        //precio del servicio
        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio: </span>${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);

    });

    //HEADING DE INFORMACION DE CITA
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de la cita';
    resumen.appendChild(headingCita);

    //NOMBRE DEL USUARIO
    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre: </span>${nombre}`;

    //FORMATEAR LA FECHA PARA MOSTRARLA DE MEJOR MANERA
    const fechaObj = new Date(fecha);
    const year = fechaObj.getFullYear();
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;//Al instanciar un nuevo objeto DATE, le resta 1 dia al actual

    const fechaUTC = new Date( Date.UTC(year, mes, dia) );//formato UTC UNIX

    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);

    //FECHA DE LA CITA
    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha: </span>${fechaFormateada}`;

    //HORA DE LA CITA
    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora: </span>${hora} horas`;

    //BOTON PARA CREAR UNA CITA
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar cita';
    botonReservar.onclick = reservarCita;

    //AGREGAR AL DIV DE RESUMEN
    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReservar);
}


//RESERVAR CITA
async function reservarCita(){
    //CONSTRUIR LA PETICION A LA API
    const { id, fecha, hora, servicios } = cita;

    //HITERAR Y HACER COINCIDENCIAS, ESO HACE MAP
    const idServicios = servicios.map( servicio => servicio.id );

    //LE MANDAMOS A LA API LO QUE NECESITA PARA LLENAR LA TABLA citas, citasServicios
    //FormData: es como el submit de un formulario
    const datos = new FormData();//Arreglo para mandar los datos a la API
    datos.append('fecha', fecha); //'fecha' = $_POST['fecha'] ... fecha es la variable
    datos.append('hora', hora);
    datos.append('idUsuario', id); 
    datos.append('servicios', idServicios);
    //console.log([...datos]);
    //return;

    //PETICION A LA API
    try {
        const url = 'http://localhost:3000/api/citas';
        const respuesta = await fetch(url, {//parametro opcional, pero con POST es obligatorio
            method: 'POST', //metodo
            body: datos //datos
        });

        //MANDAMOS RESULTADO
        const resultado = await respuesta.json(); 

        //MANDAR MENSAJE DE EXITO
        if (resultado.resultado) {
            Swal.fire({
                icon: 'success',
                title: 'Cita creada',
                text: 'Has pedido una cita correctamente',
                button: 'OK'
            }).then( () => {
                setTimeout(() => {
                    window.location.reload();    
                }, 1500);
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al crear la cita',
            button: 'OK'
        }).then( () => {
            setTimeout(() => {
                window.location.reload();    
            }, 1500);
        });
    }

    
    

    
    
}
