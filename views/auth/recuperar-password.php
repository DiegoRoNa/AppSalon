<h1 class="nombre-pagina">Recuperar contraseña</h1>
<p class="descripcion-pagina">Coloca tu nueva contraseña a continuación</p>

<!--ERRORES Y ALERTAS-->
<?php include_once __DIR__.'/../templates/alertas.php'; ?>

<!--SI EL TOKEN ES INCORRECTO, DESAPARECE EL FORMULARIO-->
<?php if($error) return; ?>

<form method="POST" class="formulario">
    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Nueva contraseña">
    </div>

    <input type="submit" value="Enviar" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes cuenta?, inicia sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta?, crea una</a>
</div>