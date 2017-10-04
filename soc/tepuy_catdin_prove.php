<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Proveedores</title>
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
<style type="text/css">
<!--
.style1 {color: #000000}
-->
</style>
</head>
<body>
<?php
require_once("class_folder/tepuy_rpc_c_proveedor.php");
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_fecha.php");

$io_proveedor=new tepuy_rpc_c_proveedor();
$io_conect=new tepuy_include();
$conn=$io_conect->uf_conectar();
$io_dspro=new class_datastore();
$io_sql=new class_sql($conn);
$la_emp=$_SESSION["la_empresa"];
$io_fecha    = new class_fecha();

if  (array_key_exists("cmbbanco",$_POST))
	{
	  $ls_banco=$_POST["cmbbanco"];
	  $lr_datos["banco"]=$ls_banco;
    }
else
	{
	  $ls_banco="000";
	}	
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion=$_POST["operacion"];
	 $ls_codigo="%".$_POST["txtcodigo"]."%";
	 $ls_nombre="%".$_POST["txtnombre"]."%";
   }
else
   {
	$ls_operacion="";
   }
if (array_key_exists("txtcodigo",$_POST))
   {
     $ls_codigo="%".$_POST["txtcodigo"]."%";
   }
else
   {
	$ls_codigo="";
   }      
?>
<form name="form1" method="post" action="">
<table width="463" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="18" colspan="4" align="right"><div align="center">Cat&aacute;logo de Proveedores</div></td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="64" height="27" align="right"><span class="style1">C&oacute;digo</span></td>
        <td width="139"><input name="txtcodigo" type="text" id="txtcodigo" maxlength="10">        </td>
        <td width="58" align="right">Direcci&oacute;n</td>
        <td width="200"><input name="txtdireccion" type="text" id="txtdireccion" size="25" maxlength="100"></td>
      </tr>
      <tr>
        <td height="32" align="right">Nombre</td>
        <td><input name="txtnombre" type="text" id="txtnombre" maxlength="100"></td>
        <td align="right">Banco</td>
        <td>
<?php
/*Llenar Combo Banco*/
$ls_codemp=$la_emp["codemp"];
$rs_pro=$io_proveedor->uf_select_llenarcombo_banco($ls_codemp);
?>  
          <select name="cmbbanco" id="cmbbanco"  style="width:150px " >
            <option value="s1">Seleccione un Banco</option>
        <?php
		while ($row=$io_sql->fetch_row($rs_pro))
  			  {
			    $ls_codban=$row["codban"];
			    $ls_nomban=$row["nomban"];
			    if ($ls_codban==$ls_banco)
			 	   {
					 print "<option value='$ls_codban' selected>$ls_nomban</option>";
				   }
			    else
				   {
					 print "<option value='$ls_codban'>$ls_nomban</option>";
				   }
			  } 
	  ?>
          </select>
<input name="operacion" type="hidden" id="operacion"> 
      <tr>
        <td height="15" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td>      <div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0">Buscar Proveedor</a></div>
  </table> 
</form>      

<?php
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=100 style=text-align:center>Código</td>";
print "<td width=300 style=text-align:center>Nombre del Proveedor</td>";
print "<td width=100 style=text-align:center>Reg. Nac. Contratistas</td>";
print "</tr>";

if ($ls_operacion=="BUSCAR")
   {
		$ls_codpro="%".$_POST["txtcodigo"]."%";
		$ls_nombre="%".$_POST["txtnombre"]."%";
		$ls_direccion="%".$_POST["txtdireccion"]."%";
		$ls_codban="%".$_POST["cmbbanco"]."%";
		if ($ls_codban=="%s1%")
		   {  
	         $ls_codban="%%";	
		   } 
		$ls_sql=" SELECT a.*, (select denominacion  from scg_cuentas where a.sc_cuenta=scg_cuentas.sc_cuenta) as denominacion ".
                " FROM rpc_proveedor a ".
                " WHERE a.cod_pro like '".$ls_codigo."' AND ".
                " a.cod_pro <> '----------' AND  ".
                " a.nompro like '".$ls_nombre."' AND a.dirpro like '".$ls_direccion."' AND  ".
                " a.codban like '".$ls_codban."' AND a.estprov=0  ".
                " ORDER BY a.cod_pro ASC";
		$rs_pro=$io_sql->select($ls_sql);
		$data=$rs_pro;
			$lb_existe=false;
			while($row=$io_sql->fetch_row($data))
			{
					$lb_existe=true;
						$ls_codpro=$row["cod_pro"];
						$ls_nompro=$row["nompro"];
						$ls_fechavenRNC   = $row["fecvenrnc"];
						$ld_hoy=date('Y')."-".date('m')."-".date('d');
						if($io_fecha->uf_comparar_fecha($ld_hoy,$ls_fechavenRNC))
						{
							$lb_registronacional="VIGENTE";
						}
						else
						{
							$lb_registronacional="VENCIDO";
						}
						print "<tr class=celdas-blancas>";
					    print "<td width=100 style=text-align:center><a href=\"javascript: aceptar('$ls_codpro');\">".$ls_codpro."</a></td>";
						print "<td width=300 style=text-align:left>".$row["nompro"]."</td>";
						print "<td width=100 style=text-align:center>".$lb_registronacional."</td>";
						print "</tr>";			
			}
if($lb_existe===false)
	 {
	   ?>
	   <script language="javascript">
	   alert("No se han encontrado registros para esta Búsqueda");
	   close();
	   </script>
	   <?php
	 }
}
	
?>
</body>

<script language="JavaScript">
function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="tepuy_catdin_prove.php";
	f.submit();
}
  
function aceptar(codpro)
{
buscar=opener.document.form1.hidrango.value;
if (buscar=="1")
   {
	 opener.document.form1.txtcodprov1.value=codpro;
   }
else
   {
	 opener.document.form1.txtcodprov2.value=codpro;
   }
close();
}

</script>
</html>