<h1 class="nombre-pagina">Olvidé contraseña</h1>
<p class="descripcion-pagina">Reestablece tu contraseña escribiendo tu correo a continuación</p>

<form action="/olvide" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Correo</label>
        <input type="email" name="email" id="email" placeholder="Tu correo">
    </div>

    <input type="submit" value="Enviar instrucciones" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una uenta?, inicia sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta?, crea una</a>
</div>