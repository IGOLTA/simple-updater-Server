<?php
    $base_dir = "files/";

    function str_replace_first($from, $to, $content){
        $from = '/'.preg_quote($from, '/').'/';

        return preg_replace($from, $to, $content, 1);
    }

    function scan_dir_r($dir, &$results = array()){
        $files = scandir($dir);
    
        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
            if(!is_dir($path)) {
                $results[] = $path;
            } else if($value != "." && $value != "..") {
                scan_dir_r($path, $results);
                $results[] = $path;
            }
        }
        
        $files = $results;
        $results = array();

        foreach($files as $element){
            if(!is_dir($element)){
                $element = str_replace_first(realpath(".")."\\", "", $element);
                $element = str_replace("/", "\\", $element);

                array_push($results, $element);
            }
        }

        return $results;
    }
    
    if(!file_exists($base_dir)){
        mkdir($base_dir);
    }
    

    $files = scan_dir_r($base_dir);
    $json = array();

    $json["filesNumber"] = sizeof($files);

    $checksums = array();
    foreach($files as $element){
        $checksums[$element] = md5_file($element);
    }

    $json["checksums"] = $checksums;

    echo(json_encode($json));
?>