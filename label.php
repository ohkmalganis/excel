<?php

	require_once("db.php");
	$db = new DB();

	$tabla = addslashes(trim($_GET['t']));
	$id = addslashes(trim($_GET['id']));

	$consulta = "SHOW COLUMNS FROM $tabla";
	$resultado = $db->execute($consulta);

?>
<select name="labels[<?= $id; ?>]" id="labels_<?= $id; ?>">
    <option value="0">-- -- --</option>
    <?php while($rowes = $resultado->fetch_object()){ ?>
    <option value="<?= $rowes->Field; ?>"><?= $rowes->Field; ?></option>
    <?php } ?>
</select>