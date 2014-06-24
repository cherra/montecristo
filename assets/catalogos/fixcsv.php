<?php

if($argc >= 3){
    if(($file = fopen($argv[1],"rb")) !== FALSE){
        $output = fopen($argv[2],"wb");
        while(($datos = fgetcsv($file) ) !== FALSE){
            $numero = count($datos);
            //$datos = str_replace($filtros, '', $datos);
            for($c = 0; $c < $numero; $c++){
                $datos[$c] = ucwords(mb_strtolower(trim($datos[$c]),'UTF-8'));
            }
            fputcsv($output, $datos, ',', '"');
        }
        fclose($output);
        fclose($file);
    }else{
        die("Ocurrió un error al abrir el archivo\n");
    }
}else{
    printf("Forma de uso: php fixcsv.php origen.csv destino.csv\n");
    die("Faltan argumentos\n");
}
?>
