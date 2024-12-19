<?php
    function contadorvisitas()//  La funci�n que ejecutara las visitas
    {
        $archivo = "contadorvisitas.txt"; //el archivo donde se almacena las visitas
        $f = fopen($archivo, "r"); //abrimos el fichero
        if($f)
        {
            $contadorvisitas = fread($f, filesize($archivo)); //vemos el archivo a grabar
            $contadorvisitas = $contadorvisitas + 1; // Le agregamos una visita mas al contador
            fclose($f);
        }
        $f = fopen($archivo, "w+");
        if($f)
        {
            fwrite($f, $contadorvisitas);
            fclose($f);
        }
        return $contadorvisitas;
    }
	
	//Mostramos las visitas acumuladas
	echo "Registered ";
	echo contadorvisitas();//imprime las visitas
	echo " views";

?>