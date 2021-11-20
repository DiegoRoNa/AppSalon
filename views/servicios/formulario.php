
<?php include_once __DIR__.'/../templates/barra.php'; ?>

<div class="campo">
    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" name="nombre" placeholder="Nombre servicio" value="<?=$servicio->nombre;?>">
</div>

<div class="campo">
    <label for="precio">Precio</label>
    <input type="number" id="precio" name="precio" placeholder="Precio servicio" value="<?=$servicio->precio;?>">
</div>