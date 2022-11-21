<?php ob_start() ?>

<?php if (isset($_SESSION["nombreUser"])): ?>
    <table>
        <tr>
            <th>alimento (por 100g)</th>
            <th>energía (Kcal)</th>
            <th>grasa (g)</th>
            <th>Editar</th>
            <th>Borrar</th>
        </tr>
        <?php foreach ($params['alimentos'] as $alimento) : ?>
            <tr>
                <td><a href="index.php?ctl=ver&id=<?php echo $alimento['id'] ?>">
                        <?php echo $alimento['nombre'] ?></a></td>
                <td><?php echo $alimento['energia'] ?></td>
                <td><?php echo $alimento['grasatotal'] ?></td>
                <td><a href="index.php?ctl=editar&id= <?php echo $alimento['id'] ?>">Editar</a></td>
                <td><a href="index.php?ctl=borrar&id= <?php echo $alimento['id'] ?>">Borrar</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <table>
        <tr>
            <th>alimento (por 100g)</th>
            <th>energía (Kcal)</th>
            <th>grasa (g)</th>
        </tr>
        <?php foreach ($params['alimentos'] as $alimento) : ?>
            <tr>
                <td><a href="index.php?ctl=ver&id=<?php echo $alimento['id'] ?>">
                        <?php echo $alimento['nombre'] ?></a></td>
                <td><?php echo $alimento['energia'] ?></td>
                <td><?php echo $alimento['grasatotal'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>


<?php $contenido = ob_get_clean() ?>

<?php if (isset($_SESSION["nombreUser"])): ?>
    <?php include 'layoutsession.php' ?>
<?php else: ?>
    <?php include 'layout.php' ?>
<?php endif; ?>
