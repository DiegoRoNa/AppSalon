document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp(){
    buscarPorFecha();
}

function buscarPorFecha(){
    const fechaInput = document.querySelector('#fecha');//input fecha
    fechaInput.addEventListener('input', function(e){//capurar el evento
        const fechaSeleccionada = e.target.value;//valor del input
        
        //redireccionamos a la fecha seleccionada
        window.location = `?fecha=${fechaSeleccionada}`;
        
    })
    
}