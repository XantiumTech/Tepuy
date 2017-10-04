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

$io_conect=new tepuy_include();
$con=$io_conect->uf_conectar();
$io_msg=new class_mensajes();
$io_dsbene=new class_datastore();
$io_sql=new class_sql($con);
$la_emp=$_SESSION["la_empresa"];

	if (array_key_exists("operacion",$_POST))
	   {
		 $ls_operacion=$_POST["operacion"];
		 $ls_cedula="%".$_POST["txtcedula"]."%";
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
        <td height="22" colspan="4">Cat&aacute;logo de Beneficiarios</td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td width="64" height="22"><div align="right">C&eacute;dula</div></td>
        <td width="139" height="22"><input name="txtcedula" type="text" id="txtcedula">        </td>
        <td width="58" height="22"><div align="right">Apellido</div></td>
        <td width="237" height="22"><input name="txtapellido" type="text" id="txtapellido" size="25"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><input name="txtnombre" type="text" id="nombre2"></td>
        <td height="22"><div align="right">Banco</div></td>
        <td height="22"><?php
		/*Llenar Combo Banco*/
		$ls_codemp=$la_emp["codemp"];
		$ls_sql=" SELECT  * ".
		        " FROM    scb_banco ".
				" WHERE   codemp='".$ls_codemp."' ".
				" ORDER BY codban ASC";
		$rs_banco=$io_sql->select($ls_sql);
		?>  
        <select name="cmbbanco" id="cmbbanco" style="width:150px">
        <option value="000">Selecciones un Banco</option>
        <?php
		while ($row=$io_sql->fetch_row($rs_banco))
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
	  $io_sql->free_result($rs_ben);
	  ?>
          </select>
      <tr>
   <td height="22" colspan="4"><div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.png" width="20" height="20" border="0">Buscar Beneficiario</a></div></td>
   </tr>
<input name="operacion" type="hidden" id="operacion"> 
</table> 
</form>      

<?php
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda  height=22>";
print "<td>Cédula </td>";
print "<td>Nombre del Beneficiario</td>";
print "</tr>";

if ($ls_operacion=="BUSCAR")
   {
	$ls_cedbene = "%".$_POST["txtcedula"]."%";
	$ls_nombene = $_POST["txtnombre"];
	$ls_apebene = "%".$_POST["txtapellido"]."%";
	$ls_codban  = "%".$_POST["cmbbanco"]."%";
	if ($ls_codban=="%000%")
	{  
	  $ls_codban="%%";	
	} 
	$ls_codemp=$la_emp["codemp"];
    $ls_sql=" SELECT * ".
	        " FROM   rpc_beneficiario ".
            " WHERE  codemp='".$ls_codemp."'          AND ced_bene like '".$ls_cedbene."' AND ".
            "        nombene like '%".$ls_nombene."%' AND apebene like '".$ls_apebene."' AND ".
            "        codban like '".$ls_codban."'     AND ced_bene<>'----------'".
            " ORDER BY ced_bene ASC";
	$rs_bene=$io_sql->select($ls_sql);
	$data=$rs_bene;
    if ($row=$io_sql->fetch_row($rs_bene))
	{
	    $data=$io_sql->obtener_datos($rs_bene);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$io_dsbene->data=$data;
		$totrow=$io_dsbene->getRowCount("ced_bene");
		for ($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_cedbene  = $data["ced_bene"][$z];
			$ls_nomben   = $data["nombene"][$z];
			$ls_apebene  = $data["apebene"][$z];
			if (!empty($ls_apebene) && $ls_apebene!='.')
			   {
			     $ls_nomben = $ls_nomben.", ".$ls_apebene.".";
			   }
			$ls_sccuenta = $data["sc_cuenta"][$z];
			print "<td  style=text-align:center><a href=\"javascript: aceptar('$ls_cedbene','$ls_nomben','$ls_apebene','$ls_sccuenta');\">".$ls_cedbene."</a></td>";
			print "<td  style=text-align:left>".$ls_nomben."</td>";
			print "</tr>";			
		}
	print "</table>";
	}
	else
	{
	 ?>
	 <script language="javascript">
		alert("No se han encontrado resgistros para esta Búsqueda !!!");
	 </script>
	 <?php	
	}  
}
print "</table>";
?>
</body>

<script language="JavaScript">
  function aceptar(cedula,nombre,apellido,sc_cuenta)
  {
    li_principal=(opener.document.form1.hidcatalogo.value); //Para decidir que campos vamos a rellenar para cada llamado del catalogo.
	if (li_principal==1)
	   {
	     opener.document.form1.txtcodproben.value = cedula;
	     opener.document.form1.txtnombre.value    = nombre;
	     opener.document.form1.hidcodcuenta.value = sc_cuenta;
	   }
	else
	   {
	    buscar=opener.document.form1.hidrangocodigos.value;
        if (buscar=="1")
           {
	         opener.document.form1.txtcodigo1.value=cedula;
           }
        else
           {
	         opener.document.form1.txtcodigo2.value=cedula;   
           }
	   }   
	close();
  }
  
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cxp_cat_beneficiarios.php";
	  f.submit();
  }
</script>
</html>