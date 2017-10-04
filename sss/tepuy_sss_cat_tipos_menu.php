<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Tipos de Menu</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>

<body>
<?php
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");

$io_conect   = new tepuy_include();
$conn        = $io_conect->uf_conectar();
$io_dsuniadm = new class_datastore();
$io_sql      = new class_sql($conn);
$arr         = $_SESSION["la_empresa"];
$ls_codemp   = $arr["codemp"];
$ls_sql      = " SELECT * FROM sss_tipousuario ORDER BY codusu ASC";
$rs_data     = $io_sql->select($ls_sql);
$data        = $rs_data;
?>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Tipos de Menu </td>
  </tr>
</table>
  <div align="center"><br>
    <?php
if ($row=$io_sql->fetch_row($rs_data))
   {
     $data              = $io_sql->obtener_datos($rs_data);
	 $arrcols           = array_keys($data);
     $totcol            = count($arrcols);
     $io_dsuniadm->data = $data;
     $totrow            = $io_dsuniadm->getRowCount("codusu");
     print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 print "<tr class=titulo-celda>";
	 print "<td>C�digo</td>";
	 print "<td>Denominaci�n</td>";
	 print "<td>Menu</td>";
 	 print "</tr>";
   
	 for ($z=1;$z<=$totrow;$z++)
         {
			print "<tr class=celdas-blancas>";
			$ls_codusu = $data["codusu"][$z];
			$ls_denominacion = $data["nomtipusu"][$z];
			$ls_menu = $data["menu"][$z];
			//$ls_tipuac = $data["tipuac"][$z];
		    print "<td  style=text-align:center><a href=\"javascript: aceptar('$ls_codusu','$ls_denominacion','$ls_menu');\">".$ls_codusu."</a></td>";
			print "<td  style=text-align:left>".$ls_denominacion."</td>";
			print "<td  style=text-align:left>".$ls_menu."</td>";
			print "</tr>";			
         }
	 print "</table>";
     $io_sql->free_result($rs_data);
   }
else
   {
     ?>
     <script language="javascript">
	 alert("No se han creado Tipos de Menu !!!");
	 close();
	 </script>
     <?php
   }	 
?>
    </table>
  </div>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,menu)
  {
    fop                         = opener.document.form1;
	li_maestro                  = fop.hidmaestro.value;
	if (li_maestro=='1')
	   {
	     fop.txtcodigo.value         = codigo;
         fop.txtcodigo.readOnly      = true;
	     fop.txtdenominacion.value   = denominacion;
	     fop.txtmenu.value    = menu;
	     
  	     // opener.document.form1.hidestatus.value="GRABADO";
		fop.hidestatus.value="GRABADO";
		fop.operacion.value = "GRABADO";
	   }
    else
	   {
	     fop.txtcodigo.value = codigo;
	     fop.txtdenonimnacion.value = denominacion;
	   }
	
	close();
  }
</script>
</html>