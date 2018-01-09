<?php

namespace Generator;





include_once './config.php';

include_once './app/generator.php';

include_once './app/helper.php';



if(isset($argv)){

if($argv[1] != "generate")
    die("Error command '".$argv[1]. "' unknow command");

if(count($argv) < 6){
        echo PHP_EOL."ERROR: Some parameters are missing".PHP_EOL;
    echo "Right sintaxis".PHP_EOL;
    echo "php run.php [ModelName] [directory/to/view/container/] [Filename.ext] [Template.ext.ext]";
    die;
}

}








if(isset($argv)){
    $generator = new \Generator\App\NakGerator($argv[2], $argv[3], $argv[4], $argv[5]);
    $generator->GenerateView();
}

