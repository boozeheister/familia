<?php
// Se crea la clase Sujeto con atributos como nombre y parentesco para realizar las relaciones
class Sujeto {
    var $nombre, $parentesco;

	// Constructor para el nombre y el parentesco de cada objeto sujeto
    function __construct($nombre) {
        $this->nombre      = $nombre;
        $this->parentesco = array();
    }

	// Metodo el cual asigna un parentesco a los arrays que se van creando de cada objeto  
    function Relaciones($type, $sujeto) {
        if (!isset($this->parentesco[$type])) {
            $this->parentesco[$type] = array();
        }
        $this->parentesco[$type][] = $sujeto;
    }

    // Metodo el cual busca los parentescos  de los objetos 
    function buscar_hijo($type) {
        if (!isset($this->parentesco[$type])) {
            return array();
        }
        return $this->parentesco[$type];
    }

    // Metodo el cual busca el parentesco (casado)  
    function buscar_casados($type) {
        $parentesco = $this->buscar_hijo($type);
        return empty($parentesco) ? null : $parentesco[0];
    }
	
	// Deveulve el objeto en forma String
    function __toString() {
        return $this->nombre;
    }	
	
	// Metodo que realiza el parentesco Madre -> Hijo
	function Mama($mama) {
        $mama->Hijo($this);     
    }
	
	// Metodo que realiza el parentesco Padres(mama y papa) -> Hijo
	function Padres($mama, $papa) {
        $mama->Hijo($this);
        $papa->Hijo($this);
    }

	// Metodo que realiza la relacion de todos los nodos en el array de los objetos hijo
    function Hijo($hijo) {
        $this ->Relaciones('hijo', $hijo);
        $hijo->Relaciones('parentesco',  $this);
    }

	// Metodo que realiza el parentesco casado de un objeto con otro objeto
    function casado($casado) {
        $this  ->Relaciones('casado', $casado);
        $casado->Relaciones('casado', $this);
    }
	

	// Metodo que devuelve los parentesco hijo/casado de los objetos en el array
    function obtener_hijo() { return $this->buscar_hijo('hijo'); }
    function obtener_casado  () { return $this->buscar_casados ('casado');   }
	
	
	
	}
	

// se crean los objetos instanciados de la clase Sujeto
$maria  = new Sujeto('Maria Herrera');
$yolanda  = new Sujeto('Yolanda Zambrano');
$jesus  = new Sujeto('Jesus Garcia');
$isidro  = new Sujeto('Isidro Garcia');
$florelia  = new Sujeto('Florelia Santander');
$norelis = new Sujeto('Norelis Santander');
$antonio = new Sujeto('Antonio Garcia');
$carmen  = new Sujeto('Carmen Perez');
$jesus2  = new Sujeto('Jesus Marques');



// Se crean las relaciones de los parentescos entre los objetos  
$jesus ->Mama($maria);
$jesus ->casado ($yolanda);
$isidro ->Padres ($yolanda,$jesus);
$isidro ->casado ($florelia);
$norelis->Padres($florelia, $isidro);
$antonio->Padres($florelia, $isidro);

$jesus2 ->Mama($carmen);
$florelia ->Mama ($carmen);
$florelia ->casado ($isidro);

// Función para mostrar las relaciones de parentesc entre los objetos
	function Mostrar_descendencia($principal, $prefijo = "") 
	{
		$resultado = array($principal);
		
		// Se buscan los objetos que tienen un parentesco de casados
		if ($principal->obtener_casado() != null) 
		{
			$resultado[] = $principal->obtener_casado();
		}
		
		// Muestra en pantalla los objetos
		echo $prefijo . implode("  <>  ", $resultado) . "<br>";

		// Se buscan los objetos que tienen un parentesco de hijos
		foreach ($principal->obtener_hijo() as $aux) 
		{
			Mostrar_descendencia($aux, "------$prefijo");
		}	
		
	}
	
	

?>
<html><body> 
	<?php

if (isset($_GET['envio'])) $link=$_GET['envio'];
else $link='';

switch($link){

case 'maria' :
    Mostrar_descendencia($maria);
    break;

case 'carmen' :
    Mostrar_descendencia($carmen);
    break;

}

?>
<hr>
<a href="?envio=maria">Descendencia de Maria</a>
<br>
<a href="?envio=carmen">Descendencia de Carmen</a>
<br>
<a href="?envio=0">Regresar</a>

</body></html>