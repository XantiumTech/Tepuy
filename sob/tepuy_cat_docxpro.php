<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Documentos Asociados al Proveedor</title>
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
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_funciones.php");

$io_conect  = new tepuy_include();
$ls_conect  = $io_conect->uf_conectar();
$io_sql     = new class_sql($ls_conect);
$io_funcion = new class_funciones();
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

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
?>
<form name="form1" method="post" action="">
<br>
  <table width="780" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="779" height="22" colspan="2" class="titulo-celda"><input name="txtprov" type="hidden" id="txtprov" value= "<?php print $ls_codprov?>">
      Documentos Asociados al Proveedor</td>
    </tr>
  </table>
<p align="center">
<?php
 $ls_sql = " SELECT rpc_docxprov.coddoc,
                    rpc_documentos.dendoc,
                    rpc_docxprov.fecrecdoc, 
					rpc_docxprov.fecvendoc,
                    rpc_docxprov.estdoc,
					rpc_docxprov.estorig
		       FROM rpc_documentos, rpc_docxprov
		      WHERE rpc_documentos.codemp = '".$ls_codemp."'
			    AND rpc_docxprov.cod_pro  = '".$ls_codpro."'
				AND rpc_documentos.codemp=rpc_docxprov.codemp
		        AND rpc_documentos.coddoc=rpc_docxprov.coddoc";
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
		   echo "<table width=780 border=0 cellpadding=1  cellspacing=1 class=fondo-tabla align=center>";
		   echo "<tr class=titulo-celda>";
		   echo "<td width=45  style=text-align:center>Código</td>";
		   echo "<td width=345 style=text-align:center>Denominación</td>";
		   echo "<td width=70 style=text-align:center>Recepción</td>";
		   echo "<td width=70 style=text-align:center>Vencimiento</td>";
		   echo "<td width=125 style=text-align:center>Estatus</td>";
		   echo "<td width=125 style=text-align:center>Original/Copia</td>";
		   echo "</tr>";
		   while($row=$io_sql->fetch_row($rs_data))
				{
				  echo "<tr class=celdas-blancas>";
				  $ls_coddocpro = $row["coddoc"];
				  $ls_dendocpro = $row["dendoc"]; 
				  $ls_fecrecdoc = $io_funcion->uf_convertirfecmostrar($row["fecrecdoc"]);
				  $ls_fecvendoc = $io_funcion->uf_convertirfecmostrar($row["fecvendoc"]);
				  $li_estdocpro = $row["estdoc"];
				  if ($li_estdocpro==0)
					 {
					   $ls_estdocpro = "No Entregado";
					 }
				  elseif($li_estdocpro==1)
					 {
					   $ls_estdocpro = "Entregado";
					 }
				  elseif($li_estdocpro==2)
					 {
					   $ls_estdocpro = "En Trámite";
					 }
				  elseif($li_estdocpro==3)
					 {
					   $ls_estdocpro = "No Aplica al Proveedor";
					 }
				  $li_estoridoc = $row["estorig"];
				  if ($li_estoridoc==0)
					 {
					   $ls_estoridoc="Copia del Documento";
					 }
				  elseif($li_estoridoc==1)
					 {
					   $ls_estoridoc="Original";
					 }
				  echo "<td style=text-align:center><a href=\"javascript: aceptar('$ls_coddocpro','$ls_dendocpro','$ls_fecrecdoc','$ls_fecvendoc','$li_estdocpro','$li_estoridoc');\">".$ls_coddocpro."</a></td>";
				  echo "<td style=text-align:left>".$ls_dendocpro."</td>";
				  echo "<td style=text-align:center>".$ls_fecrecdoc."</td>";
				  echo "<td style=text-align:center>".$ls_fecvendoc."</td>";
				  echo "<td style=text-align:left>".$ls_estdocpro."</td>";
				  echo "<td style=text-align:left>".$ls_estoridoc."</td>";
				  echo "</tr>";			
				}
           print "</table>"; 
           $io_sql->free_result($rs_data);
		 }	 
	  else
	     {?>
		   <script language="javascript">
		   alert("No se han creado Documentos Para este Proveedor !!!");
		   close();
		   </script>
		   <?php
	     }
	}		 
?>
</p>
</form>
<br>
</table>
</body>
<script language="JavaScript">
  function aceptar(ls_coddocpro,ls_dendocpro,ls_fecrecdoc,ls_fecvendoc,li_estdocpro,li_estoridoc)
  {
    opener.document.form1.txtcodigo.value		= ls_coddocpro;
	opener.document.form1.txtcodigo.readOnly	= true;
    opener.document.form1.txtdenominacion.value = ls_dendocpro;
	opener.document.form1.txtfecrec.value		= ls_fecrecdoc;
    opener.document.form1.txtfecven.value		= ls_fecvendoc;
	if (li_estdocpro==0)
	   {
	     opener.document.form1.cmbestdoc[0].selected=true;
	   }
	else
	if (li_estdocpro==1)
	   {
	     opener.document.form1.cmbestdoc[1].selected=true;
	   }
	else
	if (li_estdocpro==2)
	   {
	     opener.document.form1.cmbestdoc[2].selected=true;
	   }
	else
	if (li_estdocpro==3)
	   {
	     opener.document.form1.cmbestdoc[3].selected=true;
	   }	   
	if (li_estoridoc==0)
	   {
	     opener.document.form1.cmbestori[0].selected=true;
	   }
	else
	if (li_estoridoc==1)
	   {
	     opener.document.form1.cmbestori[1].selected=true;
	   }
    close();
  }
</script>
</html>