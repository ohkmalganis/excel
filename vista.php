<?php
	require_once("db.php");
	$db = new DB();

	$sql = "SHOW TABLES";
	$tables = $db->execute($sql);

	$object = "Tables_in_".DB_NAME;

	while($row = $tables->fetch_object()){
		$rows[] = $row->$object;
	}

?>
<html>
<head>
	<title>Vista de lo Importado</title>
</head>

<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/jquery.datatables.js"></script>
<script src="js/bootstrap-datatables.js"></script>
<script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap-datatables.css" />

<script>
$(function(){
	$('#sort').dataTable(); 
});
function manda(id)
{
	var posicion = id;
	var valor = $('#tablas_'+id).val();
	$('#carga_'+id).load('label.php?t='+valor+'&id='+id);
}
</script>
<style type="text/css">
p{
	font-size:11px;
}
#subtitulo{
	font-size: 14px;
}
</style>
<body>
	<h3>Detalle de lo Importado</h3>
	<p id="subtitulo">A continuacion configure donde ingresaran los datos</p>

	<table class="table table-bordered table-condensed">
	<?php

		//SI EL ARCHIVO SE ENVIA Y ADEMÃS SE SUBIO CORRECTAMENTE
		if (isset($_FILES["archivo"]) && is_uploaded_file($_FILES['archivo']['tmp_name']))
		{
		 
			//SE ABRE EL ARCHIVO EN MODO LECTURA
			$fp = fopen($_FILES['archivo']['tmp_name'], "r");

			//SE RECORRE
			$c=0;
			$cuantos = 0;

			// Realizando Barrido
			while (!feof($fp)){

				// Extrayendo los datos y especificando el separador
				$data  = explode(",", fgets($fp));
				//$informacion = explode(",", fgets($fp));

				$columnas = count($data);
				$segundas = $columnas;

	?>
	<?php if($c==0){ ?>
			<thead>
				<tr>
					<?php for($i=0; $i<$columnas; $i++){ ?>
					<?php $dato[] = $data[$i]; ?>
					<th><p><?= utf8_encode($data[$i]); ?></p></th>
					<?php } ?>
				</tr>
			</thead>	
	<?php }else{ ?>
			<tbody>
				<tr>
					<?php for($i=0; $i<$columnas; $i++){ ?>
					<?php $informacion[] = $data[$i]; ?>
					<td><p><?= utf8_encode($data[$i]); ?></p></td>
					<?php } ?>
					<?php 
						$cuantos++;
					?>
				</tr>
			</tbody>
	<?php } ?>
		
	<?php

			$c++;
			}

		}
	?>
	</table>
	<?php $columnas2 = count($dato); ?>
	<p id="subtitulo">Detalle de los encabezados</p>
	<p id="subtitulo"><strong>Primero seleccionamos la tabla y luego seleccionamos la columna</strong> </p>
	<form name="form1" method="post" action="save.php">
	<table class="table table-bordered table-condensed">
		<tr>
			<?php for($i=0; $i<$columnas2; $i++){ ?>
			<td>
				<select name="tablas[<?= $i; ?>]" id="tablas_<?= $i; ?>" onchange="manda(<?= $i; ?>)">
					<option value="0">-- Seleccione --</option>
					<?php foreach ($rows as $id => $value){ ?>
					<option value="<?= $value; ?>"><?= $value; ?></option>
					<?php } ?>
				</select>
			</td>
			<?php } ?>
		</tr>
		<tr>
			<?php for($i=0; $i<$columnas2; $i++){ ?>
			<td><p><strong><?= utf8_encode($dato[$i]); ?></strong></p></td>
			<?php } ?>
		</tr>
		<tr>
			<?php for($i=0; $i<$columnas2; $i++){ ?>
			<td><div id="carga_<?= $i; ?>"></div></td>
			<?php } ?>
		</tr>
	</table>
	<input type="hidden" name="filas" id="filas" value="<?= $cuantos; ?>">
	<input type="hidden" name="info" id="info" value="<?= base64_encode(serialize($informacion)); ?>">
	<input type="submit" name="enviar" id="enviar" value="Guardar Datos">
	</form>
</body>
</html>