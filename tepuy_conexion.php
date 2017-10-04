<?php 
session_start(); 
require_once("tepuy_config.php");
require_once("shared/class_folder/class_sql.php");
require_once("cfg/class_folder/tepuy_cfg_c_empresa.php");
require_once("shared/class_folder/tepuy_include.php");
require_once("shared/class_folder/class_sql.php");
require_once("shared/class_folder/class_funciones.php");
require_once("shared/class_folder/class_mensajes.php");
$io_conect = new tepuy_include();
$msg=new class_mensajes();
//$obj = new tepuy_include();
?>
<html>
<head>
<title>Servicios y Sistemas Integrados MP</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<style type="text/css">
<!--

input,select,textarea,text{font-family:Tahoma, Verdana, Arial;font-size:11px;}
body {font-family: Tahoma, Verdana, Arial;	font-size: 10px;color: #000000;}
.boton{border-right:1px outset #FFFFFF;border-top:1px outset #CCCCCC;border-left:1px outset #CCCCCC;border-bottom:1px outset #FFFFFF;font-weight:bold;cursor:pointer;color: #666666;background-color:#CCCCCC;font-family: Tahoma, Verdana, Arial;	font-size: 11px;}
.pie-pagina{
	color: #000;
	text-align: center;
	
}
.Estilo1 {color: #4D6DA4}
.Estilo2 {color: #FFFFFF}
.Estilo3 {color: #FF0000}
      body {
      	background-image:url("shared/imagenes/tepuy-fondo.jpg");
        background-repeat: no-repeat;
        background-position: 1px 1px;
        }
-->
select{
	background: #eee url(arrow.png);
	background-position: 280px center;
    background-repeat: no-repeat;
   	padding: 15px;
   	font-size: 16px;
   	border: 0;
   	border-radius: 3px;
   -webkit-appearance: none;
   -webkit-transition: all 0.4s;
   -moz-transition: all 0.4s;
   transition: all 0.4s;
   -webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.3);
	}
	select:hover{
	background-color: #ddd;
	}
	.login-form {
	background:#fff;
	border-radius: 10%;
	width:30%;
	height: 60%;
	margin:9% auto 4% auto;
 	position: relative;
    -webkit-box-shadow: 2px 1px 7px 5px rgba(0,0,0,1);
    -moz-box-shadow: 2px 1px 7px 5px rgba(0,0,0,1);
    box-shadow: 2px 1px 7px 5px rgba(0,0,0,1);
    }
.head {
	position: absolute;
	top:-15%;
	left: 35%;
}
.head img {
	border-radius:50%;
	-webkit-border-radius:50%;
	-o-border-radius:50%;
	-moz-border-radius:50%;
	border:6px solid rgba(221, 218, 215, 0.23);
}
.myButton {
	-moz-box-shadow:inset 0px 1px 0px 0px #bbdaf7;
	-webkit-box-shadow:inset 0px 1px 0px 0px #bbdaf7;
	box-shadow:inset 0px 1px 0px 0px #bbdaf7;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #79bbff), color-stop(1, #378de5));
	background:-moz-linear-gradient(top, #79bbff 5%, #378de5 100%);
	background:-webkit-linear-gradient(top, #79bbff 5%, #378de5 100%);
	background:-o-linear-gradient(top, #79bbff 5%, #378de5 100%);
	background:-ms-linear-gradient(top, #79bbff 5%, #378de5 100%);
	background:linear-gradient(to bottom, #79bbff 5%, #378de5 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#79bbff', endColorstr='#378de5',GradientType=0);
	background-color:#79bbff;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #84bbf3;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:15px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:0px 1px 0px #528ecc;
}
.myButton:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #378de5), color-stop(1, #79bbff));
	background:-moz-linear-gradient(top, #378de5 5%, #79bbff 100%);
	background:-webkit-linear-gradient(top, #378de5 5%, #79bbff 100%);
	background:-o-linear-gradient(top, #378de5 5%, #79bbff 100%);
	background:-ms-linear-gradient(top, #378de5 5%, #79bbff 100%);
	background:linear-gradient(to bottom, #378de5 5%, #79bbff 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#378de5', endColorstr='#79bbff',GradientType=0);
	background-color:#378de5;
}
.myButton:active {
	position:relative;
	top:1px;
}


</style>


</head>
<body bgcolor="#FFFFFF" leftmargin="0" marginwidth="0" marginheight="0">
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
<?php

	function uf_conectar () 
	{
		global $msg;
		if (strtoupper($_SESSION["ls_gestor"])==strtoupper("mysql"))
		{
		    $conec = @mysql_connect($_SESSION["ls_hostname"],$_SESSION["ls_login"],$_SESSION["ls_password"]);

			if($conec===false)
			{
				$msg->message("No pudo conectar con el servidor de datos,".$_SESSION["ls_hostname"]." , contacte al administrador del sistema");
				
				
			}
			else
			{
				$lb_ok=mysql_select_db(trim($_SESSION["ls_database"]),$conec);
				if (!$lb_ok)
				{
					$msg->message("No existe la base de datos ".$_SESSION["ls_database"]);					
					
				}
			}
		return $conec;
		}
		
		if(strtoupper($_SESSION["ls_gestor"])==strtoupper("postgre"))
		{
			$conec = @pg_connect("host=".$_SESSION["ls_hostname"]." port=".$_SESSION["ls_port"]."  dbname=".$_SESSION["ls_database"]." user=".$_SESSION["ls_login"]." password=".$_SESSION["ls_password"]); 
		
			if (!$conec)
			{
				$msg->message("No pudo conectar al servidor de base de datos, contacte al administrador del sistema");				
				
			}
      	 return $conec;
	    }
		
	}
	
	
	if(array_key_exists("OPERACION",$_POST))
	{
		$operacion=$_POST["OPERACION"];
		
		if ($operacion=="SELECT")
		   {
			$posicion=$_POST["cmbdb"];
											
			//Realizo la conexion a la base de datos
			if($posicion=="")
			  {
			
			  }
			else
			  {
				$_SESSION["ls_database"] = $empresa["database"][$posicion];
				$_SESSION["ls_hostname"] = $empresa["hostname"][$posicion];
				$_SESSION["ls_login"]    = $empresa["login"][$posicion];
				$_SESSION["ls_password"] = $empresa["password"][$posicion];
				$_SESSION["ls_gestor"]   = $empresa["gestor"][$posicion];	
				$_SESSION["ls_port"]     = $empresa["port"][$posicion];	
				$_SESSION["ls_width"]    = $empresa["width"][$posicion];
				$_SESSION["ls_height"]   = $empresa["height"][$posicion];	
				$_SESSION["ls_logo"]     = $empresa["logo"][$posicion];	
				$conn=uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase tepuy_include.
				if($conn)
				{
				$io_empresa = new tepuy_cfg_c_empresa($conn);
				$obj_sql=new class_sql($conn);
				$ls_sql="SELECT * FROM tepuy_empresa ";
				$result=$obj_sql->select($ls_sql);
				if($result===false)
				{
					$msg->message("No pudo conectar a la tabla empresa en la base de datos, contacte al administrador del sistema");				
				}
				else
				{
				   if (!$row=$obj_sql->fetch_row($result))
				   {
				     $io_empresa->uf_insert_empresa();
				   }
			    }
				$result=$obj_sql->select($ls_sql);
				$li_pos=0;
				if($result===false)
				{
					
				}
				else
				{
					while ($row=$obj_sql->fetch_row($result))
				      {
					    $li_pos=$li_pos+1;
					    $la_empresa["codemp"][$li_pos]=$row["codemp"];   
					    $la_empresa["nombre"][$li_pos]=$row["nombre"];   				
				      }
				}
			 }
			}
		}
		elseif($operacion="SELEMPRESA")
		{
			
			$ls_codemp=$_POST["cmbempresa"];
			$con=uf_conectar();
			$obj_sql=new class_sql($con);
			$ls_sql="SELECT * FROM tepuy_empresa where codemp='".$ls_codemp."' ";
			$result=$obj_sql->select($ls_sql);
			$li_row=$obj_sql->num_rows($result);
			$li_pos=0;
			if($row=$obj_sql->fetch_row($result))
			{
				$la_empresa=$row;   
				$_SESSION["la_empresa"]=$la_empresa;
				$a=$_SESSION["la_empresa"];
				print "<script language=JavaScript>";
				print "location.href='tepuy_inicio_sesion.php'" ;
				print "</script>";
			}
		}
		
	}
	else
	{ 
		$operacion="";
		$arr=array_keys($_SESSION);	
		$li_count=count($arr);
		for($i=0;$i<$li_count;$i++)
		{
			$col=$arr[$i];
			unset($_SESSION["$col"]);
		}
	}
	


?>
<br>
<br>


<form name="form1" method="post" action="">
<div class="login-form">
		<div class="head">
		<img src="shared/imagenes/mem2.jpg" alt=""/>
		</div>

<br>
<br>
<br>
<br>

<h1><span class="Estilo2">......</span><span class="Estilo1">Seleccione:<span></h1>
<center>
<h2>Base de Datos</h2>
<br>
<?php
   	$li_total = count($empresa["database"]);
    ?>
     <select name="cmbdb" style="width:260px" style="color:red" onChange="javascript:selec();">
          <option value="">Seleccione....</option>
	      <?php

		for($i=1; $i <= $li_total ; $i++)
		{
			if($posicion==$i)
			{
				$selected="selected";
			}
			else
			{
				$selected="";
			}
		?>
          <option value="<?php echo $i;?>" <?php print $selected; ?>>
          <?php
				echo $empresa["database"][$i] ;
			  ?>
          </option>
          <?php
		}
		?>
      </select>
 </center>  
 <center>
 <h2>Instituci&oacuten</h2>
<select name="cmbempresa" style="width:255px ">
          <option value="">Seleccione....</option>
          <?php
	if($operacion=="SELECT")
	{
		$li_total=count($la_empresa["codemp"]);
		for($i=1; $i <= $li_total ; $i++)
		{
		?>
          <option value="<?php echo $la_empresa["codemp"][$i];?>">
          <?php
				echo $la_empresa["nombre"][$i] ;
			  ?>
          </option>
          <?php
		}
	}	
	?>
        </select>
        </center>
        <br>
        <br>
    <center>
      <input name="Button" class="myButton" type="button" value="Aceptar" onClick="javascript:uf_selempresa();">
      <input name="OPERACION" type="hidden" id="OPERACION" value="<?php $_REQUEST["OPERACION"] ?>">
    </center>
       <br>



</form>
	<br>
<center>
  <p>Software Libre desarrollado por<span class="Estilo3"> Servicios y Sistemas Integrados MP</span>  <br>
 Direcci&oacute;n: Urb. Ciudad Varyna Sector II Calle 5. Quinta N&ordm; N-19. <br>
 Barinas - Edo. Barinas
 <br>
 Hecho en Venezuela.<br>
 Telefonos: (0424) 5322557 - (0416) 6436802 </p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</center>

</body>
<script language="javascript">
function selec()
{
	f=document.form1;
	f.OPERACION.value="SELECT";
	f.action="tepuy_conexion.php";
	f.submit();
}

function uf_selempresa()
{
	f=document.form1;
	empresa=f.cmbempresa.value;
	db=f.cmbdb.value;
	if(empresa=="")
	{
		if(db=="")
		{
			alert("Debe Seleccionar una base de datos");
		}
		else
		{
			alert("Debe Seleccionar la institucion");
		}
	}
	else
	{
		f.OPERACION.value="SELEMPRESA";
		f.action="tepuy_conexion.php";
		f.submit();
	}
}
</script>
</html>
