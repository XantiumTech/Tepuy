<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Fuentes de Financiamiento</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");


$io_conect=new tepuy_include();
$conn=$io_conect->uf_conectar();
$io_dsfuente=new class_datastore();
$io_sql=new class_sql($conn);

$arr=$_SESSION["la_empresa"];
$ls_sql=" SELECT * ".
        " FROM tepuy_fuentefinanciamiento WHERE codfuefin <> '--' ".
		" ORDER BY codfuefin ASC ";
$rs_fuente=$io_sql->select($ls_sql);
$data=$rs_fuente;
$ls_pantalla   = $_GET["txtpantalla"];
?>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Fuentes de Financiamiento </td>
  </tr>
</table>
  <br>
<form name="form1" method="post" action="">
 <input name="txtpantalla" id="txtpantalla" type="hidden" value="<?=$ls_pantalla?>">
  <div align="center">
    <?php
if ($row=$io_sql->fetch_row($rs_fuente))
   {
     $data=$io_sql->obtener_datos($rs_fuente);
     $io_dsfuente->data=$data;
     $totrow=$io_dsfuente->getRowCount("codfuefin");
     print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 print "<tr class=titulo-celda>";
	 print "<td>Código</td>";
	 print "<td  style=text-align:left>Denominación</td>";
 	 print "</tr>";
	 for ($z=1;$z<=$totrow;$z++)
         {
			print "<tr class=celdas-blancas>";
			$ls_codfuefin   =$data["codfuefin"][$z];
			$ls_denominacion=$data["denfuefin"][$z];
			$ls_explicacion =$data["expfuefin"][$z];
			print "<td><a href=\"javascript: aceptar('$ls_codfuefin','$ls_denominacion','$ls_explicacion');\">".$ls_codfuefin."</a></td>";
			print "<td  style=text-align:left>".$ls_denominacion."</td>";
			print "</tr>";			
         }
	print "</table>";
	}
else
    {
      ?>
	  <script language="javascript" >
	  alert("No se han creado Fuentes de Financiamiento !!!");
	  close();
	  </script>
     <?php  
	}		 
$io_sql->free_result($rs_fuente);
$io_sql->close();
?>
  </div>
</form>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,explicacion)
  {
    
	ls_pantalla                = document.form1.txtpantalla.value;
	if(ls_pantalla=="d_estprog5" )
	{
		opener.document.form1.txtcodigo.value=codigo;
		opener.document.form1.txtcodigo.readOnly=true;
    	opener.document.form1.txtdenominacion.value=denominacion;
	
	}else
	{
	opener.document.form1.txtcodigo.value=codigo;
	opener.document.form1.txtcodigo.readOnly=true;
    opener.document.form1.txtdenominacion.value=denominacion;
	opener.document.form1.txtexplicacion.value=explicacion;
	opener.document.form1.status.value='C';
	opener.document.form1.txtdenominacion.focus(true);
	
	}
	
	
	close();
  }
</script>
</html>