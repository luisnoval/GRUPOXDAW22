<?php ob_start() ?>
<?php if(isset($params['mensaje'])): ?>
    <b><span style="color: red;"><?php echo $params['mensaje'] ?></span></b>
<?php endif; ?>

<div class="login">
    <form action="index.php?ctl=login" method="post">
        <table>
            <tr>
                <td><label>Nombre:</label></td>
                <td><input type="text" name="nombreUser" required value="<?php echo $params['nombreUser'] ?>"></td>
            </tr>

            <tr>
                <td><label>Contraseña:</label></td>
                <td><input type="password" name="password" required value="<?php echo $params['password'] ?>"></td>
            </tr>

            <tr class="centrado">
                <td colspan="2"><a href="index.php?ctl=registro">Página de registro</a></td>
            </tr>

            <tr class="centrado">
                <td colspan="2"><input type="submit" value="Entrar"></td>
            </tr>
        </table>
    </form>
</div>

<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>
