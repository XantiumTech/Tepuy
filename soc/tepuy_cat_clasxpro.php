<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Calificación por Proveedor</title>
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
</head>
<body>
<form name="form1" method="post" action="">
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="22" colspan="2" class="titulo-celda"><input name="txtprov" type="hidden" id="txtprov" value= "<?php print $ls_codprov?>">
        Cat&aacute;logo de Calificaci&oacute;n por Proveedor</td>
    </tr>
  </table>
  <p align="center">
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/tepuy_include.php");

$io_include = new tepuy_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($ls_conect);
$io_msg     = new class_mensajes();

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
     $ls_codpro    = $_POST["txtprov"];
   }
else
   {
     $ls_operacion = "";
 	 $ls_codpro    = $_GET["txtprov"];
   }

  $ls_sql = "SELECT rpc_clasifxprov.codclas, rpc_clasificacion.denclas, rpc_clasifxprov.status, rpc_clasifxprov.nivstatus
               FROM rpc_clasificacion, rpc_clasifxprov
		      WHERE rpc_clasificacion.codemp='".$_SESSION["la_empresa"]["codemp"]."' 
		        AND rpc_clasifxprov.cod_pro= '".$ls_codpro."'
		        AND rpc_clasificacion.codemp= rpc_clasifxprov.codemp
		        AND rpc_clasificacion.codclas=rpc_clasifxprov.codclas";
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
		    echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			echo "<tr class=titulo-celda>";
			echo "<td>C&oacute;digo</td>";
			echo "<td>Denominaci&oacute;n</td>";
			echo "<td>Estatus</td>"; 	 
			echo "<td>Nivel del Estatus</td>";
			echo "</tr>";  
			while($row=$io_sql->fetch_row($rs_data))
			     {
				   echo "<tr class=celdas-blancas>";
				   $ls_codcal = $row["codclas"];
				   $ls_dencal = $row["denclas"];
				   $li_estcal = $row["status"];
				   $li_nivcal = $row["nivstatus"];
				   if ($li_estcal==0)
					  {
					    $ls_estcal = "Activa";
					  }
				   elseif($li_estcal==1)  
					  {
					    $ls_estcal = "No Activa";
					  }
				   if ($li_nivcal==0)
					  {
					    $ls_nivcal="Ninguno";
					  }
				   elseif($li_nivcal==1)
					  {
					    $ls_nivcal="Bueno";
					  }
				   elseif($li_nivcal==2)
					  {
					    $ls_nivcal="Regular";
					  }
				   elseif($li_nivcal==3)
					  {
					    $ls_nivcal="Malo";
					  }  	        
				   echo "<td><a href=\"javascript: aceptar('$ls_codcal','$ls_dencal','$li_estcal','$li_nivcal');\">".$ls_codcal."</a></td>";
				   echo "<td>".$ls_dencal."</td>";
				   echo "<td>".$ls_estcal."</td>";
				   echo "<td>".$ls_nivcal."</td>";
				   echo "</tr>";  
				 }
		    echo "</table>";
			$io_sql->free_result($rs_data);
		  }
       else
	      {
		    $io_msg->message("No se han creado Calificaciones Por Proveedor !!!");
		  }
	 }
?>
</p>
</form>
<br>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,estatus,nivel)
  {
    opener.document.form1.txtcodigo.value=codigo;
    opener.document.form1.txtdenominacion.value=denominacion;
	if (estatus==0)
	   {
	     opener.document.form1.cmbestatus[0].selected=true;
	   }
	else
	   {
	     opener.document.form1.cmbestatus[1].selected=true;	   
	   }
	if (nivel==0)
	   {
	     opener.document.form1.cmbnivestatus[0].selected=true;
	   }
	else
	if (nivel==1)
	   {
	     opener.document.form1.cmbnivestatus[1].selected=true;	   
	   }       
	if (nivel==2)
	   {
	     opener.document.form1.cmbnivestatus[2].selected=true;
	   }
	else
	if (nivel==3)
	   {
	     opener.document.form1.cmbnivestatus[3].selected=true;	   
	   }
	close();
  }
</script>
</html>