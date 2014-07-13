<?php

require_once 'database.php';



$filename = '../resources/oevk/Bacs-Kiskun_korzet.csv';

$data = array();
 if ($handle = fopen($filename))
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
        var_dump($data);
    } else 
    {
    	echo 'shit';
    }





?>