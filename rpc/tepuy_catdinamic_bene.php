<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Beneficiarios</title>
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
require_once("class_folder/tepuy_rpc_c_beneficiario.php");

$io_include  = new tepuy_include();
$ls_conect   = $io_include->uf_conectar();
$io_sql      = new class_sql($ls_conect);
$io_msg      = new class_mensajes();
$io_funcion  = new class_funciones();
$io_classben = new tepuy_rpc_c_beneficiario();

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
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="4" align="right"><div align="center">Cat&aacute;logo de Beneficiarios</div></td>
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
        <td height="22" align="right">Banco</td>
        <td height="22">
		<?php
		$rs_ben=$io_classben->uf_select_banco($_SESSION["la_empresa"]["codemp"]);
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
      <tr>
   <td height="22" colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0">Buscar</a></div></td>
   </tr>
<input name="operacion" type="hidden" id="operacion"> 
</table> 
</form> 
<br>     
<div align="center">
<?php
echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td width=100 style=text-align:center>Cédula</td>";
echo "<td width=400 style=text-align:center>Nombre</td>";
echo "</tr>";

if ($ls_operacion=="BUSCAR")
   {
     $ls_cedbene = $_POST["txtcedula"];
	 $ls_nombene = $_POST["txtnombre"];
  	 $ls_apebene = $_POST["txtapellido"];
	 $ls_codban  = $_POST["cmbbanco"];
	 if ($ls_codban=="s1")
	    {  
	      $ls_codban = "";	
		}   
     $ls_sql = "SELECT rpc_beneficiario.codemp, rpc_beneficiario.ced_bene, rpc_beneficiario.codpai, rpc_beneficiario.codest,
	  				   rpc_beneficiario.codmun, rpc_beneficiario.codpar, rpc_beneficiario.codtipcta, rpc_beneficiario.rifben,
					   rpc_beneficiario.nombene, rpc_beneficiario.apebene, rpc_beneficiario.dirbene, rpc_beneficiario.telbene, 
	                   rpc_beneficiario.celbene, rpc_beneficiario.email, rpc_beneficiario.sc_cuenta, rpc_beneficiario.codbansig,
					   rpc_beneficiario.codban, rpc_beneficiario.ctaban, rpc_beneficiario.fecregben, rpc_beneficiario.nacben, 
					   rpc_beneficiario.numpasben, rpc_beneficiario.tipconben, rpc_beneficiario.tipcuebanben, scg_cuentas.denominacion 
	              FROM rpc_beneficiario, scg_cuentas
				 WHERE rpc_beneficiario.codemp='".$_SESSION["la_empresa"]["codemp"]."'
				   AND rpc_beneficiario.ced_bene like '%".$ls_cedbene."%'
				   AND rpc_beneficiario.nombene like '%".$ls_nombene."%'
				   AND rpc_beneficiario.apebene like '%".$ls_apebene."%'
				   AND rpc_beneficiario.codban  like '%".$ls_codban."%'
				   AND rpc_beneficiario.ced_bene<>'----------'
				   AND rpc_beneficiario.codemp=scg_cuentas.codemp
				   AND rpc_beneficiario.sc_cuenta=scg_cuentas.sc_cuenta
				   ORDER BY rpc_beneficiario.ced_bene ASC";
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
					  $ls_cedbene = trim($row["ced_bene"]);
					  $ls_rifbene = $row["rifben"];
					  $ls_nombene = $row["nombene"];
					  $ls_apebene = $row["apebene"];
					  if (!empty($ls_apebene))
					     {
						   $ls_nombre = $ls_apebene.', '.$ls_nombene;
						 }
					  else
						 {
						   $ls_nombre = $ls_nombene;
						 }
					  $ls_dirbene      = $row["dirbene"];
					  $ls_telbene      = $row["telbene"];
					  $ls_celbene      = $row["celbene"];
					  $ls_email        = $row["email"];
					  $ls_contable     = trim($row["sc_cuenta"]);
					  $ls_denocontable = $row["denominacion"];
					  $ls_banco        = $row["codban"];
					  $ls_cuenta       = $row["ctaban"];
					  $ls_tipocuenta   = $row["codtipcta"];
					  $ls_pais         = $row["codpai"];
                      $ls_estado       = $row["codest"];
					  $ls_municipio    = $row["codmun"];
					  $ls_parroquia    = $row["codpar"];
					  $ls_codbansig    = $row["codbansig"];
					  $ls_nacben       = $row["nacben"];
					  $ls_numpasben    = $row["numpasben"];
					  $ls_fecregben    = $row["fecregben"];
					  $ls_fecregben    = $io_funcion->uf_convertirfecmostrar($ls_fecregben);
					  $ls_tipconben    = $row["tipconben"];
					  $ls_tipcuebanben = $row["tipcuebanben"];
					  $ls_denbansig    = "";
					  $ls_sql2         = "SELECT denbansig FROM tepuy_banco_sigecof WHERE codbansig = '".$ls_codbansig."'"; 
					  $rs_datos        = $io_sql->select($ls_sql2);
   	                  if ($row=$io_sql->fetch_row($rs_datos))
	                     {
 		                   $ls_denbansig = $row["denbansig"];   
						 }
					  echo "<td  style=text-align:center width=100><a href=\"javascript: aceptar('$ls_cedbene','$ls_nombene','$ls_apebene','$ls_dirbene','$ls_telbene','$ls_celbene','$ls_email','$ls_contable','$ls_denocontable','$ls_banco','$ls_cuenta','$ls_tipocuenta','$ls_pais','$ls_estado','$ls_municipio','$ls_parroquia','$ls_codbansig','$ls_denbansig','$ls_rifbene','$ls_fecregben','$ls_nacben','$ls_numpasben','$ls_tipconben','$ls_tipcuebanben');\">".$ls_cedbene."</a></td>";
					  echo "<td  style=text-align:left   width=400>".$ls_nombre."</td>";
					  echo "</tr>";
					}
             }
		  else
		     {
			   $io_msg->message("No se han definido Beneficiarios !!!");			 
			 }
		}
   }
echo "</table>";
?>
</div>
</body>
<script language="JavaScript">
fop = opener.document.form1;
function aceptar(cedula,nombre,apellido,direccion,telefono,celular,email,contable,denocontable,banco,cuenta,tipocuenta,pais,estado,municipio,parroquia,codbansig,denbansig,rifben,ls_fecregben,ls_nacben,ls_numpasben,ls_tipconben,ls_tipcuebanben)
{
	fop.txtcedula.value    = cedula;
	fop.txtcedula.readOnly = true;
	fop.txtrif.value       = rifben;
	fop.txtnombre.value    = nombre;
	fop.txtapellido.value  = apellido;
	fop.txtdireccion.value = direccion;
	fop.txttelefono.value  = telefono;
	fop.txtcelular.value   = celular;
	fop.txtemail.value     = email;
	fop.txtcontable.value  = contable;
	fop.txtdencuenta.value = denocontable;
	fop.cmbbanco.value     = banco;
	fop.txtcuenta.value    = cuenta;
	fop.cmbtipcue.value    = tipocuenta;
	fop.cmbpais.value      = pais;
	fop.hidestado.value    = estado;
	fop.hidmunicipio.value = municipio;
	fop.hidparroquia.value = parroquia;
	fop.txtcodbancof.value = codbansig;
	fop.txtnombancof.value = denbansig;
	fop.txtfecregben.value = ls_fecregben;
	fop.txtnumpasben.value = ls_numpasben;
	fop.cmbcontribuyente.value = ls_tipconben;
	fop.cmbtipcuebanben.value = ls_tipcuebanben;
	if (ls_nacben=='V')
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
	f.action="tepuy_catdinamic_bene.php";
	f.submit();
}
</script>
</html>