<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Administración de servicios</p>

<?php include_once __DIR__.'/../templates/barra.php'; ?>
<?php include_once __DIR__.'/../templates/alertas.php'; ?>

<ul class="servicios">
    <?php foreach($servicios as $servicio): ?>
        <li>
            <p>Nombre: <span><?=$servicio->nombre;?></span></p>
            <p>Precio: <span>$<?=$servicio->precio;?></span></p>

            <div class="acciones">
                <a class="boton" href="/servicios/actualizar?id=<?=$servicio->id;?>">Actualizar</a>

                <form action="/servicios/eliminar" method="post">
                    <input type="hidden" name="id" id="id" value="<?=$servicio->id;?>">

                    <input type="submit" class="boton-eliminar" value="Eliminar">
                </form>
            </div>
        </li>


    <?php endforeach; ?>
    
    
</ul>