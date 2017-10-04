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
</head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if (document.all)
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
<body>
<?php
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_fecha.php");

$io_conect  = new tepuy_include();
$conn       = $io_conect->uf_conectar();
$io_msg     = new class_mensajes();
$io_dsprove = new class_datastore();
$io_sql     = new class_sql($conn);
$la_emp     = $_SESSION["la_empresa"];
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
?>
<form name="form1" method="post" action="">
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="4" class="titulo-celda">Cat&aacute;logo de Proveedores</td>
      </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td width="64" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="139" height="22"><input name="txtcodigo" type="text" id="txtcodigo" style="text-align:center"  maxlength="10">        </td>
        <td width="58" height="22"><div align="right">Direcci&oacute;n</div></td>
        <td width="200" height="22"><input name="txtdireccion" type="text" id="txtdireccion" size="25"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><input name="txtnombre" type="text" id="txtnombre"></td>
        <td height="22"><div align="right">Banco</div></td>
        <td height="22"><?php
		/*Llenar Combo Banco*/
		$ls_codemp=$la_emp["codemp"];
        $ls_sql=" SELECT * ".
		        " FROM   scb_banco ".
				" WHERE  codemp='".$ls_codemp."' ".
				" ORDER BY codban ASC";
				
        $rs_pro=$io_sql->select($ls_sql);  
        ?>  
        <select name="cmbBanco" id="cmbBanco" style="width:150px">
        <option value="000">Seleccione un Banco</option>
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
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.png" width="20" height="20" border="0"></a><a href="javascript: ue_search();">Buscar Proveedor </a></div>
  </table> 
<p align="center">
</p>
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
		$ls_codban="%".$_POST["cmbBanco"]."%";
        if ($ls_codban=="%000%")
		   {  
	         $ls_codban="%%";	
		   } 
		$ls_codemp=$la_emp["codemp"];
        $ls_sql=" SELECT * ".
		        " FROM   rpc_proveedor  ".
                " WHERE  codemp='".$ls_codemp."'      AND cod_pro like '".$ls_codigo."'   AND ". 
                "        nompro like '".$ls_nombre."' AND dirpro like '".$ls_direccion."' AND ". 
                "        codban like '".$ls_codban."' AND cod_pro<>'----------' AND estprov=0". 
                " ORDER BY cod_pro ASC"  ;
		$rs_pro=$io_sql->select($ls_sql);
		$data=$rs_pro;
			$lb_existe=false;
			while($row=$io_sql->fetch_row($data))
			{
					$lb_existe=true;
						$ls_codpro=$row["cod_pro"];
						$ls_nompro=$row["nompro"];
						$ls_sccuenta=$row["sc_cuenta"];
						$ls_rifpro=$row["rifpro"];
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
						print "<td width=100 style=text-align:center><a href=\"javascript:aceptar('$ls_codpro','$ls_nompro','$ls_sccuenta','$ls_rifpro');\">".$ls_codpro."</a></td>";
						print "<td width=300 style=text-align:left>".$row["nompro"]."</td>";
						print "<td width=100 style=text-align:center>".$lb_registronacional."</td>";
						print "</tr>";			
			}				
/*		   }
		else*/
if($lb_existe===false)
	 {
		     ?>
             <script language="javascript">
		 	 alert("No se han encontrado registros para esta Búsqueda");
		     </script>
	<?php
		   }
   }		 
 ?>
</body>

<script language="JavaScript">
function aceptar(codigo,nompro,sc_cuenta,rif_proveedor)
  {
    li_principal=(opener.document.form1.hidcatalogo.value); //Para decidir que campos vamos a rellenar para cada llamado del catalogo.
	if (li_principal==1)
	   {
	     opener.document.form1.txtcodproben.value=codigo;
	     opener.document.form1.txtnombre.value=nompro;
	     opener.document.form1.hidcodcuenta.value=sc_cuenta;
	     opener.document.form1.txtrif.value=rif_proveedor;
	   }
	else
	   {
	    buscar=opener.document.form1.hidrangocodigos.value;
        if (buscar=="1")
           {
	         opener.document.form1.txtcodigo1.value=codigo;
           }
        else
           {
	         opener.document.form1.txtcodigo2.value=codigo;   
           }
	   }
	close();
  }
  
  function ue_search()
  {
    f=document.form1;
    f.operacion.value="BUSCAR";
    f.action="tepuy_cxp_cat_proveedores.php";
    f.submit();
  }
</script>
</html>