<?php



$projectConfig = parse_ini_file("project.ini");
include_once './config.php';
include_once './app/generator.php';
include_once './app/helper.php';

if(!file_exists("conf.json")){
  die("Config.json no existe");
}

$GeneratorConfig = json_decode(file_get_contents("conf.json"), true);


// verificating if have a current proyect
if(isset($GeneratorConfig["CURRENT_PROJECT"]) && file_exists("." .  DS . "repo" . DS . $GeneratorConfig["CURRENT_PROJECT"])){
    // load current proyect setting and send to the view
}


// getting view targets

$url = isset($_GET['mx']) ? $_GET['mx']:"";

function loadProjectDir()
{
	$projectConfig = parse_ini_file("project.ini");
	$_rootPro = Helper_RemoveDotsAndDuoubleDots(scandir($projectConfig["root"] ));

	return prepareSucessResponse($_rootPro, 200);
}

function createViewTemplate($GET, $POST){
  $projectConfig = parse_ini_file("project.ini");
  $request = $POST;

  $generator = new \Generator\App\NakGerator(
    $request["originBundleModel"], 
    $projectConfig["root"] . DS ."src" . DS . $request["targetBundle"] . DS . $request["targetBundle"]."Bundle" . DS . "Resources" . DS . "views", 
    $request["templateFilename"], 
    $request["targetViewTemplate"],
    true
    );
  $generator->GenerateView();
}

// Load Availables Template for create view
function loadViewTemplates()
{
	
	return prepareSucessResponse($rootPro, 200);
}

function Helper_RemoveDotsAndDuoubleDots($fileArray = array()){

    $fileListWithOutDots = array();

    foreach ($fileArray as $value) {
        if( $value !== "." && $value !== "..")
          $fileListWithOutDots[] = $value;
  }

  return $fileListWithOutDots;

}

function loadProjectModels(){
    if(isset($_GET["bundle"])){

        $bundleName = $_GET["bundle"];
        $projectConfig = parse_ini_file("project.ini");
        $bundleDir = $projectConfig["root"] . DS . "src" . DS . $bundleName;
        if(file_exists($bundleDir)){
            // root path of bundle examle src/Proec
           $bundeBundles = scandir($bundleDir );
           $targetBundle = "";

           foreach ($bundeBundles as $value) {
               if($value != "." || $value != ".."){

                if(is_dir($bundleDir = $projectConfig["root"] . DS . "src" . DS . $bundleName .  DS . $value))
                    $targetBundle = $value;
            }
        }

        $bundleRootFiles = $bundleDir = $projectConfig["root"] . DS . "src" . DS . $bundleName .  DS . $targetBundle;

        if(file_exists($bundleRootFiles . DS . "Entity")){
            $_models = Helper_RemoveDotsAndDuoubleDots(scandir($bundleRootFiles . DS . "Entity"));
            return prepareSucessResponse($_models);
        }else{
            return prepareErrorResponse(array("error"=>array("message"=>"View container file doest found for this bundle")));
        }

    }else{
     return prepareErrorResponse(array("error"=>array("message"=>"Bundle parameter is not correct")));
 }

}else {
    return prepareErrorResponse(array("error"=>array("message"=>"Bundle parameter is not correct")));
}
}

function loadView(){

    $_rootPro = Helper_RemoveDotsAndDuoubleDots(scandir(VIEWS_TEMPLATES));

    $projectConfig = parse_ini_file("project.ini");


    $bundleDir = $projectConfig["root"] . "src" . DS;
    $_bundles = array();
    $bundle = scandir($bundleDir);
    foreach ($bundle as $value) {
    	if( $value !== "." && $value !== ".."){
    		is_dir($bundleDir . $value) ? $_bundles[] = $value : 0;
    	}
    }


    extract(array("templates" => $_rootPro,  "bundles" => $_bundles));

    extract(array("page"=>$_GET["viewName"] . ".html"));

    if(file_exists(ROOT. DS . "client" . DS . "app_views" . DS .$_GET["viewName"] . ".html"))
     include_once ROOT. DS . "client" . DS . "app_views" . DS .$_GET["viewName"] . ".html";
 else 	  include_once ROOT. DS . "client" . DS . "app_views" . DS . "404" . ".html";

}

function prepareSucessResponse($response, $status = 200){
	header('Content-Type: application/json');
    echo json_encode($response);
}

function prepareErrorResponse($response, $status = 400){
	header('HTTP/1.1 ' . $status);
	header('Content-Type: application/json');
    echo json_encode($response);
}

function prepareViewResponse($response, $status = 400){
	header('HTTP/1.1 ' . $status);
	header('Content-Type: application/json');
    echo json_encode($response);
}

if($url === "")
    include './client/default.php';
else{

   if (isset($_GET["name"]) && isset($_GET["mx"])) {

    $actionName = $_GET["name"];
    if(function_exists($actionName)){
     call_user_func_array($actionName, array("GET"=>$_GET, "POST"=>$_POST));
 }else{     		
     prepareErrorResponse(array("error" => array("message"=>"Unknow endpoint", "endpoint"=>$actionName)),404);
 }
}



}



?>



