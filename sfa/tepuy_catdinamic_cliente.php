<?php
session_start();
	require_once("class_folder/class_funciones_sfa.php");
	$io_fun_sfa=new class_funciones_sfa();
	$ls_tipo=$io_fun_sfa->uf_obtenertipo();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat�logo de Clientes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/tepuy_sfa_c_cliente.php");

$io_include  = new tepuy_include();
$ls_conect   = $io_include->uf_conectar();
$io_sql      = new class_sql($ls_conect);
$io_msg      = new class_mensajes();
$io_funcion  = new class_funciones();
$in_classcli = new tepuy_sfa_c_cliente();

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
   }
else
   {
	 $ls_operacion="";
   }
?>
<form name="form1" method="post" action="">
   <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
<table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="4" align="right"><div align="center">Cat&aacute;logo de Clientes</div></td>
    </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="64" height="22" align="right">C&eacute;dula</td>
        <td width="139" height="22"><input name="txtcedula" type="text" id="txtcedula" maxlength="10" style="text-align:center">        </td>
        <td width="58" height="22" align="right">Apellido</td>
        <td width="200" height="22"><input name="txtapellido" type="text" id="txtapellido" size="26" maxlength="100"></td>
      </tr>
      <tr>
        <td height="22" align="right">Nombre</td>
        <td height="22"><input name="txtnombre" type="text" id="nombre" maxlength="100"></td>
    <!--    <td height="22" align="right">Banco</td>
        <td height="22">
		<?php
		$rs_ben=$in_classcli->uf_select_banco($_SESSION["la_empresa"]["codemp"]);
		?>  
        <select name="cmbbanco" id="cmbbanco" style="width:150px ">
          <option value="s1">---seleccione---</option>
		   <?php
		   while ($row=$io_sql->fetch_row($rs_ben))
				 {
				   $ls_codban=$row["codban"];
				   $ls_nomban=$row["nomban"];
				   if ($ls_codban==$ls_banco)
					  {
						echo "<option value='$ls_codban' selected>$ls_nomban</option>";
					  }
				   else
					  {
						echo "<option value='$ls_codban'>$ls_nomban</option>";
					  }
				 } 
			  ?>
		 </select>
      <tr>-->
   <td height="22" colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0">Buscar</a></div></td>
   </tr>
<input name="operacion" type="hidden" id="operacion"> 
</table> 
</form> 
<br>     
<div align="center">
<?php
echo "<table width=610 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td width=110 style=text-align:center>C�dula</td>";
echo "<td width=400 style=text-align:center>Nombre</td>";
echo "<td width=100 style=text-align:center>Cuenta Contable</td>";
echo "</tr>";

if ($ls_operacion=="BUSCAR")
   {
	$ls_cedcli = $_POST["txtcedula"];
	$ls_nomcli = $_POST["txtnombre"];
  	$ls_apecli = $_POST["txtapellido"];
	$ls_tipo=$_POST["tipo"];
	// $ls_codban  = $_POST["cmbbanco"];
	 if ($ls_codban=="s1")
	    {  
	      $ls_codban = "";	
		}   
     $ls_sql = "SELECT a.*, b.denominacion FROM sfa_cliente a, scg_cuentas b
				 WHERE a.codemp='".$_SESSION["la_empresa"]["codemp"]."'
				   AND a.cedcli like '%".$ls_cedcli."%'
				   AND a.nomcli like '%".$ls_nomcli."%'
				   AND a.apecli like '%".$ls_apecli."%'
				   AND a.cedcli<>'----------'
				   AND a.codemp=b.codemp
				   AND a.sc_cuenta=b.sc_cuenta
				   ORDER BY a.cedcli ASC";
		//print $ls_sql;
 	 $rs_data = $io_sql->select($ls_sql);
     if ($rs_data===false)
	    {
		  $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
		}
     else
	    {
		  $li_numrows = $io_sql->num_rows($rs_data);
		  if ($li_numrows>0)
		     {
			   while($row=$io_sql->fetch_row($rs_data))
			        {
					  echo "<tr class=celdas-blancas>";
					  $ls_cedcli = trim($row["cedcli"]);
					  $ls_rifcli = $row["rifcli"];
					  $ls_nomcli = $row["nomcli"];
					  $ls_apecli = $row["apecli"];
					  if (!empty($ls_apecli))
					     {
						   $ls_nombre = $ls_apecli.', '.$ls_nomcli;
						 }
					  else
						 {
						   $ls_nombre = $ls_nomcli;
						 }
					  $ls_dircli      = $row["dircli"];
					  $ls_telcli      = $row["telcli"];
					  $ls_celcli      = $row["celcli"];
					  $ls_email        = $row["email"];
					  $ls_contable     = trim($row["sc_cuenta"]);
					  //$ls_contablecomp = trim($row["sc_cuenta_comp"]);
					  $ls_dencontable = $row["denominacion"];
					  //$ls_banco        = $row["codban"];
					  //$ls_cuenta       = $row["ctaban"];
					  //$ls_tipocuenta   = $row["codtipcta"];
					  $ls_pais         = $row["codpai"];
                      			  $ls_estado       = $row["codest"];
					  $ls_municipio    = $row["codmun"];
					  $ls_parroquia    = $row["codpar"];
					  $ls_codbansig    = $row["codbansig"];
					  $ls_naccli       = $row["naccli"];
					  //$ls_numpascli    = $row["numpasben"];
					  $ls_fecregcli    = $row["fecregcli"];
					  $ls_fecregcli    = $io_funcion->uf_convertirfecmostrar($ls_fecregcli);
					  $ls_tipconcli    = $row["tipconcli"];
					  $ls_trabajador    = $row["trabajador"];
					  //$ls_tipcuebanben = $row["tipcuebanben"];
					  //$ls_denbansig    = "";
					  //$ls_sql2         = "SELECT denbansig FROM tepuy_banco_sigecof WHERE codbansig = '".$ls_codbansig."'"; 
					  //$rs_datos        = $io_sql->select($ls_sql2);
   	                  		/*if ($row=$io_sql->fetch_row($rs_datos))
	                     		{
 		                   $ls_denbansig = $row["denbansig"];   
						 }
					$ls_sql3          = "SELECT denominacion FROM scg_cuentas WHERE sc_cuenta = '".$ls_contablecomp."'"; 
			 		$rs_datos         = $io_sql->select($ls_sql3);
			 		if ($row3=$io_sql->fetch_row($rs_datos))
			    		{
				  		$ls_dencontablecomp = $row3["denominacion"];   
			   		 }*/
					//print "Tipo: ".$ls_tipo;
					switch ($ls_tipo)
					{
					case "":
						echo "<td  style=text-align:center width=100><a href=\"javascript: aceptar('$ls_cedcli','$ls_rifcli','$ls_nomcli','$ls_apecli','$ls_dircli','$ls_telcli','$ls_celcli','$ls_email','$ls_contable','$ls_dencontable','$ls_pais','$ls_estado','$ls_municipio','$ls_parroquia','$ls_fecregcli','$ls_naccli','$ls_tipconcli','$ls_trabajador');\">".$ls_cedcli."</a></td>";
						echo "<td  style=text-align:left   width=400>".$ls_nombre."</td>";
						echo "<td  style=text-align:left   width=400>".$ls_dencontable."</td>";
						echo "</tr>";
					break;
				  	case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_reportedesde('$ls_cedcli','$ls_nombre');\">".$ls_cedcli."</a></td>";
						print "<td>".$ls_nombre."</td>";
						echo "<td  style=text-align:left   width=400>".$ls_dencontable."</td>";
						print "</tr>";
					break;
					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript:aceptar_reportehasta('$ls_cedcli','$ls_nombre');\">".$ls_cedcli."</a></td>";
						print "<td>".$ls_nombre."</td>";
						echo "<td  style=text-align:left   width=400>".$ls_dencontable."</td>";
						print "</tr>";
					break;
					}
				}
             		}
		  else
		     {
			   $io_msg->message("No se han definido Clientes !!!");			 
			 }
		}
   }
echo "</table>";
?>
</div>
</body>
<script language="JavaScript">
fop = opener.document.form1;
function aceptar(cedula,rif,nombre,apellido,direccion,telefono,celular,email,contable,dencontable,pais,estado,municipio,parroquia,ls_fecregcli,ls_naccli,ls_tipconcli,ls_trabajador)
{
	fop.txtcedula.value    = cedula;
	fop.txtcedula.readOnly = true;
	fop.txtrif.value       = rif;
	fop.txtnombre.value    = nombre;
	fop.txtapellido.value  = apellido;
	fop.txtdireccion.value = direccion;
	fop.txttelefono.value  = telefono;
	fop.txtcelular.value   = celular;
	fop.txtemail.value     = email;
	fop.txtcontable.value  = contable;
	fop.txtdencuenta.value = dencontable;
	fop.cmbpais.value      = pais;
	fop.hidestado.value    = estado;
	fop.cmbestado.value    = estado;

	fop.hidmunicipio.value = municipio;
	fop.cmbmunicipio.value = municipio;
	fop.hidparroquia.value = parroquia;
	fop.cmbparroquia.value = parroquia;

	//fop.txtcodbancof.value = codbansig;
	//fop.txtnombancof.value = denbansig;
	fop.txtfecregcli.value = ls_fecregcli;
	//alert("aqui");
	fop.cmbcontribuyente.value = ls_tipconcli;
	//fop.cmbtipcuebanben.value = ls_tipcuebanben;
	if (ls_naccli=='V')
	   {
		 fop.radionacionalidad[0].checked = true;
	   }
	else
	   {
		 fop.radionacionalidad[1].checked = true;
	   }
	fop.operacion.value    = "buscar";
	fop.hidestatus.value   = "GRABADO";
	fop.submit();
	close();
}
  
function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="tepuy_catdinamic_cliente.php";
	f.submit();
}

function aceptar_reportedesde(ls_cedcli,ls_nomcli)
{
	opener.document.formulario.txtcedclides.value = ls_cedcli;
	opener.document.formulario.txtnomclides.value = ls_nomcli;
	close();
}

function aceptar_reportehasta(ls_cedcli,ls_nomcli) 
{
	opener.document.formulario.txtcedclihas.value = ls_cedcli;
	opener.document.formulario.txtnomclihas.value = ls_nomcli;
	close();
}
</script>
</html>
