<?php

require_once("db.php");

$db = new DB();

function _multiple_busqueda($needle, $haystack)
{
	if(is_array($haystack))
	{
		$cuanto = count($haystack);
		for($i=0;$i<$cuanto;$i++)
		{
			if(in_array($needle, $haystack))
			{
				$find = array_search($needle, $haystack);
				$valor[] = $find;
				unset($haystack[$find]);
			}
		}
		return $valor;
	}
	else
	{
		return FALSE;
	}
}

// echo "<pre>";
// print_r($_POST);
// echo "</pre>";

$tablas = $_POST['tablas'];
$labels = $_POST['labels'];

$columnas = count($tablas);

$info = unserialize(base64_decode($_POST['info']));

$cuantos = $_POST['filas'];
$cuantos = $cuantos-1;

// Las veces que se repiten las tablas
$repeticiones = array_count_values($tablas);
// print_r($repeticiones);

// echo "<br>";

// Aca estan las tablas reales
$keys = array_keys($repeticiones);
//print_r($keys);

// Esta es la que obtiene los keys de las repeticiones
// echo "<br>";
// $clave = _multiple_busqueda("productos", $tablas);
// print_r($clave);

// echo "<br>";
// print_r($info);

// Cuantas filas hay
// echo "<pre>";
// print_r($info);
// echo "</pre>";

// Creando consulta
// Barriendo los nombres de las tablas
foreach ($keys as $key => $value) {
	// Buscando los duplicados
	$clave = _multiple_busqueda($value, $tablas);

	$i = 0;
    $contador = count($clave);
    
    // Iniciando la consulta {

	$concatena = "";
	$concatena .= "(";

	// Moviendo dentro de las claves
	foreach ($clave as $keyc => $valuec) {

		$concatena .= "`".$labels[$valuec]."`";

		$i++;
		if($i<$contador){
			$concatena .= ", ";
	    }
	}

	$concatena .= ")";

	// Buscando los duplicados
	$clave2 = _multiple_busqueda($value, $tablas);

	$j = 0;
	$salto = 0;

	$cc = "";

	for ($k=0; $k < $cuantos; $k++) { 

		$cc .= "(";

		foreach ($clave2 as $key2 => $value2) {
	
			$cc .= "'".$info[$value2+$salto]."'";
			$j++;
			if($j<$contador){
				$cc .= ", ";
		    }
		    else
		    {
		    	if($k+1<$cuantos)
		    	{
		    		$cc .= "), ";
		    	}
		    	else
		    	{
		    		$cc .= ") ";
		    	}
		    	
		    	$j = 0;
		    }
			
		}

		$salto = $salto + $columnas;

	}

	//$cc .= ")";

	// Moviendo los datos

	echo "<pre>";
	echo "<br>";
	$consulta = "INSERT INTO ".$value." ".$concatena." VALUES ".$cc;
	echo $consulta;
	echo "</pre>";

	// Ejecutando la consulta
	$db->execute($consulta);

}

// Algoritmo finalizado, redireccionar a donde gusten!
exit("Fin del algoritmo.");
//header("Location: direccion_a_redireccionar.extension");

?>