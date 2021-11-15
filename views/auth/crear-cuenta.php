<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<!--ALERTAS DE ERRORES-->
<?php include_once __DIR__.'/../templates/alertas.php'; ?>

<form action="/crear-cuenta" method="POST" class="formulario">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" value="<?=s($usuario->nombre);?>" placeholder="Tu nombre">
    </div>

    <div class="campo">
        <label for="apellidos">Apellidos</label>
        <input type="text" name="apellidos" id="apellidos" value="<?=s($usuario->apellidos);?>" placeholder="Tus apellidos">
    </div>

    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input type="tel" name="telefono" id="telefono" value="<?=s($usuario->telefono);?>" placeholder="Tu telefono">
    </div>

    <div class="campo">
        <label for="email">Correo</label>
        <input type="email" name="email" id="email" value="<?=s($usuario->email);?>" placeholder="Tu correo">
    </div>

    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Tu contraseña">
    </div>

    <input type="submit" value="Crear cuenta" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una uenta?, inicia sesión</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>