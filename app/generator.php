<?php

namespace Generator\App;



/**
* 
*/
class NakGerator {




    public $ViewContent;

    public $targetBundleViewsContainer;

    public $newFileName;

    public $fileTemplate;

    
    function __construct($ModelName, $targetBundleViewsContainer, $newFileName, $fileTemplate,$loadedFormView = false)
    {
        $this->ModelName = $ModelName;
        $this->targetBundleViewsContainer = $targetBundleViewsContainer;
        $this->newFileName = $newFileName;
        $this->fileTemplate = $fileTemplate;
        $this->loadedFormView = $loadedFormView;
        $this->Pool = ["ModelName"=>$ModelName];
    }

    
    private  function processWriteInstruccion($instruction){

        $_instructionToWrite = "";

        $_instruction = preg_replace('/\s+/', ' ',trim($instruction));

        $instructionList = explode(" ", $_instruction);

        foreach ($instructionList as $value) {
        // echo $value;
        // echo PHP_EOL;
            if(strpos($value, '#') === 0){        

                $instructionName =  str_replace("#", "", $value);

                if(isset($this->Pool[$instructionName]))
                    $_instructionToWrite.=$this->Pool[$instructionName]." ";
                else 
                    die("Undefined variable $$instructionName " );


            }else{
                $_instructionToWrite.=$value." ";
            }
        }

        return $_instructionToWrite;
    }

    private  function processExecuteInstruccion($instruction){

        $_instructionToWrite = "";

        $_instruction = preg_replace('/\s+/', ' ',trim($instruction));

        $functionName = str_replace(")", "", str_replace("(", "", Helper::replace_between($_instruction, "(", ")", "")));


        preg_match_all('/\( (.*?) \)/', $_instruction, $functionParameters);
        var_dump($functionParameters);


        call_user_func_array( array($this, $functionName ), array(1, 2));

    }

    public function create_file($file, $origin){
        echo "string";
    }



    public function GenerateController(){

    }

    public function GenerateView(){

     if(!file_exists(VIEWS_TEMPLATES . $this->fileTemplate )){
       header("HTTP/1.1 400");
       die(PHP_EOL."Error: " . " File template '". $this->fileTemplate . "' doesnt exist on list templates availables.");

   }


   $fileContent = file_get_contents(VIEWS_TEMPLATES . $this->fileTemplate);

   $fileContentToWrite = \Generator\App\Helper::get_instruction_toWrite($fileContent);

        // $fileContentToExecute = \Generator\App\Helper::get_instruction_toExecute($fileContent);


   for($a = 0 ; $a < count($fileContentToWrite[0]); $a++){
    $fileContent = str_replace($fileContentToWrite[0][$a], $this->processWriteInstruccion($fileContentToWrite[1][$a], $this), $fileContent );
}

        // for($a = 0 ; $a < count($fileContentToExecute[0]); $a++){
        //     $fileContent = str_replace($fileContentToExecute[0][$a], $this->processExecuteInstruccion($fileContentToExecute[1][$a], $this), $fileContent );
        // }


$viewTargets = $this->targetBundleViewsContainer .  DS . $this->ModelName ;

try{

        // header('Content-Type: application/json');
        // echo json_encode(array("s" => $viewTargets));
        // die;



    if(!file_exists($viewTargets )){
        mkdir($viewTargets, 0777, true);
        if(!$this->loadedFormView) { echo PHP_EOL."DIRECTORY FOR VIEW CREATED" . PHP_EOL; } 
    }else if(!$this->loadedFormView) { echo PHP_EOL."DIRECTORY FOR VIEW ALREADY EXIST "; } 

    $fh = fopen($viewTargets . DS . $this->newFileName, "w");
    fwrite($fh, $fileContent);
    fclose($fh);

    if(!$this->loadedFormView){
        echo "VIEW FILE SUCCESSFULLY CREATED AT:" . PHP_EOL  ;
        echo $viewTargets . DS . $this->newFileName;
    }else{
        header("HTTP/1.1 200");
        header('Content-Type: application/json');
        echo json_encode(array("message"=>"View created", "path" => $viewTargets . DS . $this->newFileName));
    }



}catch(\Exception $e){
    header("HTTP/1.1 400");
}


}
}




?>