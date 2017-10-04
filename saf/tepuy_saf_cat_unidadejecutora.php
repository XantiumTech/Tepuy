<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "opener.document.form1.submit();";
	print "close();";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Unidad Ejecutora</title>
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
.Estilo1 {font-size: 36px}
-->
</style>
</head>

<body>
<br>
<?php
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_funciones_activos.php");
$fun_activos=new class_funciones_activos("../");

$io_in=new tepuy_include();
$con=$io_in->uf_conectar();
$io_msg=new class_mensajes();
$io_ds=new class_datastore();
$io_sql=new class_sql($con);
$io_fun=new class_funciones(); 
$ls_destino=$fun_activos->uf_obtenervalor_get("destino",""); 
$ls_filtro=$fun_activos->uf_obtenervalor_get("filtro",""); 
$ls_coduniadmcede=$fun_activos->uf_obtenervalor_get("ls_coduniadmcede","");  
$la_emp=$_SESSION["la_empresa"]; 

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="BUSCAR";
}

if(array_key_exists("txtcoddep",$_POST))
{
  $ls_coddep=$_POST["txtcoddep"];	  
}
else
{
  $ls_coddep="";
}

if(array_key_exists("txtdendep",$_POST))
{
  $ls_dendep=$_POST["txtdendep"];	  
}
else
{
  $ls_dendep="";
}
?>
<form name="form1" method="post" action="">
  <table width="450" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td colspan="6" class="titulo-celda"><div align="center">Cat&aacute;logo de Unidad Ejecutora </div></td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td width="77"><div align="right">
        <p>N&uacute;mero</p>
      </div></td>
      <td width="157"><p align="left">
        <input name="txtcoddep" type="text" id="txtcoddep" value="<?php print $ls_coddep ?>" size="17" maxlength="15">
      </p>        </td>
      <td width="82"><div align="right"></div></td>
      <td width="103">
      <div align="right"></div></td>
      <td width="23"><p align="center">&nbsp; </p>          </td>
    </tr>
    <tr>
      <td  align="right"><div align="right">Denominaci&oacute;n</div></td>
      <td height="20"  align="right"><div align="left">
        <input name="txtdendep" type="text" id="txtdendep" value="<?php print $ls_dendep ?>" size="30" maxlength="60">
      </div></td>
      <td height="20"  align="right">&nbsp;</td>
      <td height="20"  align="right">&nbsp;</td>
      <td height="20"  align="right">&nbsp;</td>
      <td width="6" height="20"  align="right">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6"  align="right"><a href="javascript: ue_search();">
        <input name="operacion"    type="hidden"  id="operacion"    value="<?php print $ls_operacion;?>">
      <img src="../../tepuy_test/shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0" onClick="ue_search()"></a></div></td>
    </tr>
    <tr>
      <td colspan="6" align="center">
      <div align="center">      </div></td>
    </tr>
  </table>
  <p align="center">
    <?php
	print "<table width=450 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
    print "<tr class=titulo-celda>";
    print "<td align=center width=100>Código</td>";
    print "<td align=left   width=370>Denominación</td>";
    print "</tr>";
if($ls_operacion=="BUSCAR")
  {     
	$ls_codemp=$la_emp["codemp"];   
    if ($ls_filtro=="filtro")
	{
	$ls_sql=" SELECT * ".
		" FROM   spg_unidadadministrativa ".
		" WHERE  spg_unidadadministrativa.codemp='".$ls_codemp."' ".
		"     AND coduniadm like '%".$ls_coddep."%' AND denuniadm like '%".$ls_dendep."%' ".
		"     AND spg_unidadadministrativa.coduniadm <>'".$ls_coduniadmcede."' ".
		" ORDER BY coduniadm ASC ";
	}
	else
	{
	$ls_sql=" SELECT * ".
	        " FROM   spg_unidadadministrativa ".
			" WHERE  codemp='".$ls_codemp."' AND  coduniadm like '%".$ls_coddep."%' AND".
			"        denuniadm like '%".$ls_dendep."%' ".
			" ORDER BY coduniadm ASC ";
	}
	$rs_data=$io_sql->select($ls_sql);
	//$data=$rs;
	$li_row=$io_sql->num_rows($rs_data);
    if($li_row>0)
	{
		/*$data=$io_sql->obtener_datos($rs);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$io_ds->data=$data;
        $totrow=$io_ds->getRowCount("coduniadm");
        for ($z=1;$z<=$totrow;$z++)*/
		while($row=$io_sql->fetch_row($rs_data))
		{
		  print "<tr class=celdas-blancas>";         
		  $ls_codigo       = trim($row["coduniadm"]);
          $ls_denominacion = trim($row["denuniadm"]);  
		  $ls_codestpro1   = trim($row["codestpro1"]);
          $ls_codestpro2   = trim($row["codestpro2"]); 
		  $ls_codestpro3   = trim($row["codestpro3"]);
          $ls_codestpro4   = trim($row["codestpro4"]);  
          $ls_codestpro5   = trim($row["codestpro5"]);  
		
          switch ($ls_destino)
		  {  
		    case "":			   
			    print "<td align=center width=100><a href=\"javascript:aceptar('$ls_codigo','$ls_denominacion','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5');\">".$ls_codigo."</a></td>";
		        print "<td align=left  width=370>".$ls_denominacion."</td>";		  
		        print "</tr>";			
			break;
		    case "cedente":			   
			    print "<td align=center width=100><a href=\"javascript:aceptar('$ls_codigo','$ls_denominacion','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5');\">".$ls_codigo."</a></td>";
		        print "<td align=left  width=370>".$ls_denominacion."</td>";		  
		        print "</tr>";			
			break;
			case "receptora":			    
		        print "<td align=center width=100><a href=\"javascript:aceptar_uni_recep('$ls_codigo','$ls_denominacion','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5');\">".$ls_codigo."</a></td>";
		        print "<td align=left  width=370>".$ls_denominacion."</td>";		  
		        print "</tr>";			
			break;
			case "solicitante":			    
		        print "<td align=center width=100><a href=\"javascript:aceptar_uni_soli('$ls_codigo','$ls_denominacion','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5');\">".$ls_codigo."</a></td>";
		        print "<td align=left  width=370>".$ls_denominacion."</td>";		  
		        print "</tr>";			
			break;
		  } 
	    }//End For
     print "</table>";
    }//End If
}
?>
</p></form>      
</body>
<script language="JavaScript">
   function ue_search()
   {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_saf_cat_unidadejecutora.php";
	  f.submit();
   }

   function aceptar(codigo,denominacion,estpro1,estpro2,estpro3,estpro4,estpro5)
   {
	 opener.document.form1.txtcoduniadm.value=codigo; 
     opener.document.form1.txtdenuniadm.value=denominacion;	 	
     close(); 
   }
   function aceptar_uni_recep(codigo,denominacion,estpro1,estpro2,estpro3,estpro4,estpro5)
	{
		opener.document.form1.txtcoduni2.value=codigo;
	    opener.document.form1.txtdenuni2.value=denominacion;
	    close();
	}
	
   function aceptar_uni_soli(codigo,denominacion,estpro1,estpro2,estpro3,estpro4,estpro5)
	{
		opener.document.form1.txtcodunisol.value=codigo;
	    opener.document.form1.txtdenunisol.value=denominacion;
	    close();
	}
</script>
</html>