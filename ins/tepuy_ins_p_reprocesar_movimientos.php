<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../tepuy_inicio_sesion.php'";
	print "</script>";		
}
	$li_diasem = date('w');
	switch ($li_diasem){
	  case '0': $ls_diasem='Domingo';
	  break; 
	  case '1': $ls_diasem='Lunes';
	  break;
	  case '2': $ls_diasem='Martes';
	  break;
	  case '3': $ls_diasem='Mi&eacute;rcoles';
	  break;
	  case '4': $ls_diasem='Jueves';
	  break;
	  case '5': $ls_diasem='Viernes';
	  break;
	  case '6': $ls_diasem='S&aacute;bado';
	  break;	
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Reprocesar Movimientos</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Instala</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
</table>

<p>&nbsp;</p>
<table width="442" height="223" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="571" height="221" valign="top"><form name="form1" method="post" action="">
    <?php 
	require_once("../shared/class_folder/class_mensajes.php");
	$msg=new class_mensajes();
	
	$ds=null;
/////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../shared/class_folder/tepuy_c_seguridad.php");
	$io_seguridad= new tepuy_c_seguridad();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
		$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
		$ls_logusr="";
	}
	$ls_sistema="CFG";
	$ls_ventanas="tepuy_scb_d_banco.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisostepuy();
		}
		else
		{
			$ls_permisos           = $_POST["permisos"];
			$la_accesos["leer"]    = $_POST["leer"];
			$la_accesos["incluir"] = $_POST["incluir"];
			$la_accesos["cambiar"] = $_POST["cambiar"];
			$la_accesos["eliminar"]= $_POST["eliminar"];
			$la_accesos["imprimir"]= $_POST["imprimir"];
			$la_accesos["anular"]  = $_POST["anular"];
			$la_accesos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]="";
		$la_accesos["incluir"]="";
		$la_accesos["cambiar"]="";
		$la_accesos["eliminar"]="";
		$la_accesos["imprimir"]="";
		$la_accesos["anular"]="";
		$la_accesos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
	//Inclusión de la clase de seguridad.
	
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

	require_once("class_folder/tepuy_ins_c_reprocesar_mov.php");
	$io_class_reprocesar=new tepuy_ins_c_reprocesar_mov();
	if( array_key_exists("operacion",$_POST))
	{
		if(array_key_exists("chk_reprocesar_saldo",$_POST))
		{
			$lb_chk_saldo=true;
		}
		else
		{
			$lb_chk_saldo=false;
		}
		$ls_operacion= $_POST["operacion"];
	}
	else
	{
		$ls_operacion= "NUEVO";		
	}
	
	if($ls_operacion=="EJECUTAR")
	{
			$io_class_reprocesar->io_sql->begin_transaction();
			$lb_valido=$io_class_reprocesar->uf_reprocesar();
			if($lb_valido)
			{
				$io_class_reprocesar->io_sql->commit();
				$io_class_reprocesar->io_message->message("Proceso Ejecutado Satisfactoriamente");
			}
			else
			{
				$io_class_reprocesar->io_sql->rollback();
				$io_class_reprocesar->io_message->message($io_class_reprocesar->is_msg_error);
			}
		
	}
	
	if($ls_operacion=="CARGAR")
	{
			$io_class_reprocesar->io_sql->begin_transaction();
			$lb_valido=$io_class_reprocesar->uf_crear_tabla_temp();
			$lb_valido2=$io_class_reprocesar->uf_cargar_cuentas_tmp();
			if($lb_valido&&$lb_valido2)
			{
				$io_class_reprocesar->io_sql->commit();
				$io_class_reprocesar->io_message->message("Proceso Ejecutado Satisfactoriamente");
			}
			else
			{
				$io_class_reprocesar->io_sql->rollback();
				$io_class_reprocesar->io_message->message($io_class_reprocesar->is_msg_error);
			}
		
	}
	
	
	if($ls_operacion=="TRANSFERIR")
	{
			$io_class_reprocesar->io_sql->begin_transaction();
			$lb_valido=$io_class_reprocesar->uf_crear_tabla_temp();
			$lb_valido2=$io_class_reprocesar->uf_insertar_planctas_temp();
			if($lb_valido&&$lb_valido2)
			{
				$io_class_reprocesar->io_sql->commit();
				$io_class_reprocesar->io_message->message("Proceso Ejecutado Satisfactoriamente");
			}
			else
			{
				$io_class_reprocesar->io_sql->rollback();
				$io_class_reprocesar->io_message->message($io_class_reprocesar->is_msg_error);
			}
		
	}
	
	
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer     value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir  value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar  value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular   value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='tepuywindow_blank.php'");
	print("</script>");
}
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
        <p>&nbsp;</p>
        <table width="393" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-ventana">
            <td colspan="4">Reprocesar Movimientos </td>
          </tr>
          <tr class="formato-blanco">
            <td width="93" height="18">&nbsp;</td>
            <td colspan="3">&nbsp;</td>
          </tr>
          
          <tr class="formato-blanco">
            <td height="22"><input name="botcuentas" type="button" id="botcuentas" value="Cuentas" onClick="javascript:uf_cuentas();"></td>
            <td width="110"><input name="botejecutar" type="button" id="botejecutar" value="Ejecutar" onClick="javascript:uf_ejecutar();"></td>
            <td width="23">&nbsp;</td>
            <td width="165"><input name="botplancta" type="submit" id="botplancta" value="Copiar Plan cuentas temporal" onClick="javascript:uf_cargar();"></td>
          </tr>
          <tr class="formato-blanco">
            <td height="20">&nbsp;</td>
            <td colspan="3">&nbsp;</td>
          </tr>
        </table>
        <p>
          <input name="operacion" type="hidden" id="operacion">
          <input name="status" type="hidden" id="status" value="<?php print $ls_status;?>">
        </p>
        </form></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
<script language="javascript">
function  uf_ejecutar()
{
	f=document.form1;
	f.operacion.value="EJECUTAR";
	f.action="tepuy_ins_p_reprocesar_movimientos.php";
	f.submit();
}
function  uf_cuentas()
{
	f=document.form1;
	f.operacion.value="CARGAR";
	f.action="tepuy_ins_p_reprocesar_movimientos.php";
	f.submit();
}

function  uf_cargar()
{
	f=document.form1;
	f.operacion.value="TRANSFERIR";
	f.action="tepuy_ins_p_reprocesar_movimientos.php";
	f.submit();
}

</script>

</html>
