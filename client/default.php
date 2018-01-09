<!DOCTYPE html>
<html>
<head>
	<title>Generator Proyect</title>
</head>
<body>


<div style="width:30%; background:#efefef; float:left; padding:5px;">	
	<h3>Lista de ficheros de proyecto actual</h3>
<button role="view-loader" view="NewProject">New</button>
<button>Config</button>
<button role="view-loader" view="NewControllerTemplate">Crear controller</button>
<button role="view-loader" view="NewViewTemplate">Crear view</button>
	<ul id="project-dir" role-layout="async">		
	</ul>
</div>

<div style="width:67%;padding:5px;float:left; ">

<button role="view-loader" view="NewTemplate">Crear Template</button>

<div id="view-container" style="padding:8px;">
	loading..
</div>
</div>




<script type="text/javascript" src="./client/assets/jquery.min.js"></script> 
<script type="text/javascript">
	$(function(){

		$.get("action?name=loadProjectDir").done(function(rs){
		if(rs.length > 0){
			for (var i = 0; i < rs.length; i++) {
				$("#project-dir").append("<li>" +rs[i]+ "</li>")
			};
		}
		
	});

	$.get("action?name=loadViewTemplates").done(function(rs){
		console.log(rs)
		
	});

	$("#view-container").load("action?name=loadView&viewName=Home");

	$("[role=view-loader]").click(function(){
		console.log( )
		var viewTarget = $(this).attr("view") ;
		$("#view-container").load("action?name=loadView&viewName=" + viewTarget);

	})

	})
</script>


<style type="text/css">
	.form-row{
		width: 100%;
		float: left;
		margin: 0 2px 5px 2px; 
	}

	.form-row > input[type=text], .form-row > select{
width: 100%
	}
</style>


</body>
</html>