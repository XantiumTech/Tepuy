<?php
session_start();
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_tepuy_int.php");
require_once("../shared/class_folder/class_tepuy_int_scg.php");
require_once("../shared/class_folder/class_tepuy_int_spg.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
$int_scg=new class_tepuy_int_scg();
$msg=new class_mensajes();
$fun=new class_funciones();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
$li_estmodest = $_SESSION["la_empresa"]["estmodest"];
$ls_gestor = $_SESSION["ls_gestor"];
function uf_buscar($io_sql,$ls_sql,$ls_campo)
{
	$ls_valor="";
	$rs_result=$io_sql->select($ls_sql);
	if($rs_result===false)
	{

	}
	else
	{
		if($row=$io_sql->fetch_row($rs_result))
		{
			$ls_valor=trim($row[$ls_campo]);
		}
	}	
	return $ls_valor;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas Presupuestarias</title>
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
.Estilo1 {font-weight: bold}
-->
</style>
</head>

<body>
<form name="form1" method="post" action="">
  <div align="center">
    <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="3" style="text-align:center"><?php
		if(array_key_exists("operacion",$_POST))
		{
			$ls_operacion=$_POST["operacion"];
			$ls_codigo=$_POST["codigo"]."%";
			$ls_denominacion="%".$_POST["nombre"]."%";
		}
		else
		{
			$ls_operacion="";
            $ls_codigo="";
			$ls_denominacion="";
		}
		if(array_key_exists("codestpro1",$_GET))
		{
		    $ls_codestpro1  = $_GET["codestpro1"];
		}
		else
		{
		   $ls_codestpro1  = "";
		}
		if(array_key_exists("codestpro2",$_GET))
		{
		    $ls_codestpro2  = $_GET["codestpro2"];
		}
		else
		{
		   $ls_codestpro2  = "";
		}
		if(array_key_exists("codestpro3",$_GET))
		{
		    $ls_codestpro3  = $_GET["codestpro3"];
		}
		else
		{
		   $ls_codestpro3  = "";
		}
		if(array_key_exists("codestpro1h",$_GET))
		{
		    $ls_codestpro1h  = $_GET["codestpro1h"];
		}
		else
		{
		   $ls_codestpro1h  = "";
		}
		if(array_key_exists("codestpro2h",$_GET))
		{
		    $ls_codestpro2h  = $_GET["codestpro2h"];
		}
		else
		{
		   $ls_codestpro2h  = "";
		}
		if(array_key_exists("codestpro3h",$_GET))
		{
		    $ls_codestpro3h  = $_GET["codestpro3h"];
		}
		else
		{
		   $ls_codestpro3h  = "";
		}
		$ls_codestpro4="00";
		$ls_codestpro5="00";
		$ls_codestpro4h="00";
		$ls_codestpro5h="00";
		if($li_estmodest==2)
		{
			if(array_key_exists("codestpro4",$_GET))
			{
				$ls_codestpro4  = $_GET["codestpro4"];
			}
			else
			{
			   $ls_codestpro4  = "";
			}
			if(array_key_exists("codestpro5",$_GET))
			{
				$ls_codestpro5  = $_GET["codestpro5"];
			}
			else
			{
			   $ls_codestpro5  = "";
			}
			if(array_key_exists("codestpro4h",$_GET))
			{
				$ls_codestpro4h  = $_GET["codestpro4h"];
			}
			else
			{
			   $ls_codestpro4h  = "";
			}
			if(array_key_exists("codestpro5h",$_GET))
			{
				$ls_codestpro5h  = $_GET["codestpro5h"];
			}
			else
			{
			   $ls_codestpro5h  = "";
			}
		}
		?>
        Cat&aacute;logo de Cuentas Presupuestaria
        <input name="operacion" type="hidden" id="operacion"></td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td width="122" height="13">&nbsp;</td>
        <td width="341" height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="135" height="22" align="right">C&oacute;digo</td>
        <td height="22" colspan="2"><div align="left">
          <input name="codigo" type="text" id="codigo" size="22" maxlength="20">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre" size="72">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?php
print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
  {
	if($li_estmodest==1)
	{ 
		if (strtoupper($ls_gestor)=="MYSQL")
		{
			if(($ls_codestpro1!="")&&($ls_codestpro2!="")&&($ls_codestpro3!="")&&($ls_codestpro1h!="")&&($ls_codestpro2h!="")&&($ls_codestpro3h!=""))
			{
			   $ls_codestprodes=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			   $ls_codestprohas=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
			   $ls_cad=" AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5) BETWEEN '".$ls_codestprodes."' AND  '".$ls_codestprohas."' ";
			}
			else
			{
			   $ls_cad="";
			}
		}
		else
		{
			if(($ls_codestpro1!="")&&($ls_codestpro2!="")&&($ls_codestpro3!="")&&($ls_codestpro1h!="")&&($ls_codestpro2h!="")&&($ls_codestpro3h!=""))
			{
			   $ls_codestprodes=trim($ls_codestpro1).trim($ls_codestpro2).trim($ls_codestpro3).trim($ls_codestpro4).trim($ls_codestpro5);
			   $ls_codestprohas=trim($ls_codestpro1h).trim($ls_codestpro2h).trim($ls_codestpro3h).trim($ls_codestpro4h).trim($ls_codestpro5h);
			   $ls_cad=" AND (codestpro1||codestpro2||codestpro3||codestpro4||codestpro5) BETWEEN '".$ls_codestprodes."' AND  '".$ls_codestprohas."' ";
			}
			else
			{
			   $ls_cad="";
			}
		}
	}
	else
	{
			$ls_cad="";
			if($ls_codestpro1!=""&&$ls_codestpro1!="**")
			{
				$ls_codestpro1  = $fun->uf_cerosizquierda(trim($ls_codestpro1),20);
			}
			else
			{
				$ls_codestpro1  = uf_buscar($io_sql,"SELECT MIN(codestpro1) as codestpro1 FROM spg_ep1 " ,"codestpro1");
			}
			if($ls_codestpro2!=""&&$ls_codestpro2!="**")
			{
				$ls_codestpro2  = $fun->uf_cerosizquierda(trim($ls_codestpro2),6);
			}
			else
			{
				$ls_codestpro2  = uf_buscar($io_sql,"SELECT MIN(codestpro2) as codestpro2 FROM spg_ep2 WHERE codestpro1='$ls_codestpro1'" ,"codestpro2");
			}			
			if($ls_codestpro3!=""&&$ls_codestpro3!="**")
			{
				$ls_codestpro3  = $fun->uf_cerosizquierda(trim($ls_codestpro3),3);
			}
			else
			{
				$ls_codestpro3  = uf_buscar($io_sql,"SELECT MIN(codestpro3) as codestpro3 FROM spg_ep3 WHERE codestpro1='$ls_codestpro1' AND codestpro2='$ls_codestpro2'" ,"codestpro3");
			}	
			if($ls_codestpro4!=""&&$ls_codestpro4!="**")
			{
				//$ls_cad=$ls_cad.",codestpro4";				
			}
			else
			{
				$ls_codestpro4  = uf_buscar($io_sql,"SELECT MIN(codestpro4) as codestpro4 FROM spg_ep4 WHERE codestpro1='$ls_codestpro1' AND codestpro2='$ls_codestpro2' AND codestpro3='$ls_codestpro3'" ,"codestpro4");
			}
			if($ls_codestpro5!=""&&$ls_codestpro5!="**")
			{
				//$ls_cad=$ls_cad.",codestpro5";				
			}
			else
			{
				$ls_codestpro5  = uf_buscar($io_sql,"SELECT MIN(codestpro5) as codestpro5 FROM spg_ep5 WHERE codestpro1='$ls_codestpro1' AND codestpro2='$ls_codestpro2' AND codestpro3='$ls_codestpro3' AND codestpro4='$ls_codestpro4'" ,"codestpro5");
			}
			
			if($ls_codestpro1h!=""&&$ls_codestpro1h!="**")
			{
				$ls_codestpro1h  = $fun->uf_cerosizquierda(trim($ls_codestpro1h),20);
			}
			else
			{
				$ls_codestpro1h  = uf_buscar($io_sql,"SELECT MAX(codestpro1) as codestpro1 FROM spg_ep1 " ,"codestpro1");
			}
			if($ls_codestpro2h!=""&&$ls_codestpro2h!="**")
			{
				$ls_codestpro2h  = $fun->uf_cerosizquierda(trim($ls_codestpro2h),6);
			}
			else
			{
				$ls_codestpro2h  = uf_buscar($io_sql,"SELECT MAX(codestpro2) as codestpro2 FROM spg_ep2 WHERE codestpro1='$ls_codestpro1h'" ,"codestpro2");
			}			
			if($ls_codestpro3h!=""&&$ls_codestpro3h!="**")
			{
				$ls_codestpro3h  = $fun->uf_cerosizquierda(trim($ls_codestpro3h),3);
			}
			else
			{
				$ls_codestpro3h  = uf_buscar($io_sql,"SELECT MAX(codestpro3) as codestpro3 FROM spg_ep3 WHERE codestpro1='$ls_codestpro1h' AND codestpro2='$ls_codestpro2h'" ,"codestpro3");
			}	
			if($ls_codestpro4h!=""&&$ls_codestpro4h!="**")
			{
								
			}
			else
			{
				$ls_codestpro4h  = uf_buscar($io_sql,"SELECT MAX(codestpro4) as codestpro4 FROM spg_ep4 WHERE codestpro1='$ls_codestpro1h' AND codestpro2='$ls_codestpro2h' AND codestpro3='$ls_codestpro3h'" ,"codestpro4");
			}
			if($ls_codestpro5h!=""&&$ls_codestpro5h!="**")
			{
								
			}
			else
			{
				$ls_codestpro5h  = uf_buscar($io_sql,"SELECT MAX(codestpro5) as codestpro5 FROM spg_ep5 WHERE codestpro1='$ls_codestpro1h' AND codestpro2='$ls_codestpro2h' AND codestpro3='$ls_codestpro3h' AND codestpro4='$ls_codestpro4h'" ,"codestpro5");
			}				
			$ls_codestprodes=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			$ls_codestprohas=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
			
			if (strtoupper($ls_gestor)=="MYSQL")
		 	{
				$ls_cad=" AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5) BETWEEN '".$ls_codestprodes."' AND  '".$ls_codestprohas."' ";
			}
			else
			{
			   $ls_cad=" AND (codestpro1||codestpro2||codestpro3||codestpro4||codestpro5) BETWEEN '".$ls_codestprodes."' AND  '".$ls_codestprohas."' ";
			}
		
	}
	$ls_cadena =" SELECT DISTINCT spg_cuenta, max(denominacion) as denominacion FROM spg_cuentas ".
		   		" WHERE codemp = '".$as_codemp."' 
				    AND spg_cuenta like '".$ls_codigo."' 
					AND denominacion like '".$ls_denominacion."' ".
				"       ".$ls_cad." ".
				" GROUP BY spg_cuenta".
				" ORDER BY spg_cuenta";
	$rs_data = $io_sql->select($ls_cadena);
	if ($rs_data==false)
	   {
	     $msg->message($fun->uf_convertirmsg($io_sql->message));
	   }
	else
	   {
		 $li_numrows = $io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
		    {
			  while($row=$io_sql->fetch_row($rs_data))
			       {
					 $ls_spgcta    = $row["spg_cuenta"];
					 $ls_denctaspg = $row["denominacion"];
					 print "<tr class=celdas-blancas>";
					 print "<td><a href=\"javascript: aceptar('$ls_spgcta','$ls_denctaspg');\">".$ls_spgcta."</a></td>";
					 print "<td  align=left>".$ls_denctaspg."</td>";
					 print "</tr>";			
				   }
			}
		 else
		    {
			  ?>
			  <script language="JavaScript">
			    alert("No se han creado Cuentas de gasto para la programatica seleccionada.....");
			    close();
			  </script>
			  <?php
			}
	   }
  }
print "</table>";
?>
        <span class="Estilo1">
        <input name="estmodest" type="hidden" id="estmodest" value="<?php print  $li_estmodest; ?>">
  </span></div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(cuenta,deno)
  {
    opener.document.form1.txtcuentahas.value=cuenta;
    opener.document.form1.txtcuentahas.readOnly=true;
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  estmodest=f.estmodest.value;
	  if(estmodest==1)
	  {
		  f.operacion.value="BUSCAR";
		  f.action="tepuy_cat_ctasrephas.php?codestpro1=<?php print $ls_codestpro1;?>&codestpro2=<?php print $ls_codestpro2;?>&codestpro3=<?php print $ls_codestpro3;?>&codestpro1h=<?php print $ls_codestpro1h;?>&codestpro2h=<?php print $ls_codestpro2h;?>&codestpro3h=<?php print $ls_codestpro3h;?>";
		  f.submit();
	  }
	  else
	  {
		  f.operacion.value="BUSCAR";
		  f.action="tepuy_cat_ctasrephas.php?codestpro1=<?php print $ls_codestpro1;?>&codestpro2=<?php print $ls_codestpro2;?>&codestpro3=<?php print $ls_codestpro3;?>&codestpro4=<?php print $ls_codestpro4;?>&codestpro5=<?php print $ls_codestpro5;?>&codestpro1h=<?php print $ls_codestpro1h;?>&codestpro2h=<?php print $ls_codestpro2h;?>&codestpro3h=<?php print $ls_codestpro3h;?>&codestpro4h=<?php print $ls_codestpro4h;?>&codestpro5h=<?php print $ls_codestpro5h;?>";
		  f.submit();
	  }	  
  }
</script>
</html>