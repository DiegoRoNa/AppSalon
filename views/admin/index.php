<h1 class="nombre-pagina">Panel de administración</h1>

<?php include_once __DIR__.'/../templates/barra.php'; ?>

<h2>Buscar citas</h2>

<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="<?=$fecha;?>">
        </div>
    </form>
</div>

<?php if (count($citas) === 0): ?>
    <h3>No hay citas para esta fecha</h3>
<?php endif; ?>


<div id="citas-admin">
    <ul class="citas">
        <?php $idCita = 0;
        foreach($citas as $key => $cita): ?>
            <!--ESTE if SOLO SE EJECUTA UNA VEZ POR CLIENTE-->
            <?php if($idCita !== $cita->id): //COMPARAR PARA SOLO MOSTRAR UNA VEZ ESE ID
                                             //si el id es diferente, creará el nuevo li
                //SE PONE ESTA VARIABLE AQUI, PARA QUE SOLO CREE UNA VEZ POR CLIENTE
                $total = 0; //VARIABLE PARA EL TOTAL A PAGAR
            ?> 
            <li>

                <p>ID: <span><?=$cita->id;?></span></p>
                <p>Hora: <span><?=$cita->hora;?></span></p>
                <p>Cliente: <span><?=$cita->cliente;?></span></p>
                <p>Correo: <span><?=$cita->email;?></span></p>
                <p>Teléfono: <span><?=$cita->telefono;?></span></p>

                <h3>Servicios solicitados</h3>

            <?php $idCita = $cita->id; //Igualamos idCita al id que está iterando, para que ya no valga 0
            endif; 
                $total += $cita->precio; //Realiza la suma en cada precio por iteracion, para que ya no valga 0
            ?>

                <p class="servicio"><?=$cita->servicio.' $'.$cita->precio;?></p>

            <?php 
            //MOSTRAR EL TOTAL A PAGAR

            $actual = $cita->id;//id del registro actual donde se encuentra la iteracion
            $proximo = $citas[$key + 1]->id ?? 0;//indice siguiente del arreglo citas, 

            //echo $actual;
            //echo '<hr>';
            //echo $proximo;
            //echo '<hr>';

            //ULTIMO SERVICIO DEJA DE SUMAR
            if (esUltimo($actual, $proximo)) { ?>
                <p class="total">Total: <span>$<?=$total;?></span></p>

                <form action="/api/eliminar" method="POST">
                    <input type="hidden" id="id" name="id" value="<?=$cita->id;?>">
                    <input type="submit" value="Eliminar cita" class="boton-eliminar">
                </form>
            <?php } ?>
                    
        <?php endforeach; ?>
    </ul>
</div>

<!--CARGAR JS-->
<?php $script = "
        
        <script src='build/js/buscador.js'></script>
    "; 
?>