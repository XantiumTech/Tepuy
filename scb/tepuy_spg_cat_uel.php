<?php
session_start();
$dat=$_SESSION["la_empresa"];
require_once("../shared/class_folder/tepuy_include.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ls_codemp=$dat["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
	$ls_estpro1=$_POST["codestpro1"];
	$ls_estpro2=$_POST["codestpro2"];
	$ls_estpro3=$_POST["codestpro3"];
	if($dat["estmodest"]==2)
	{
		$ls_estpro4=$_POST["codestpro4"];
		$ls_estpro5=$_POST["codestpro5"];
	}
	$ls_coduniadm=$_POST["coduniadm"];
	$ls_estuac=$_POST["estuac"];
	$ls_denestpro1=$_POST["denestpro1"];
}
else
{
	$ls_operacion="";
	$ls_estpro1=$_GET["codestpro1"];
	$ls_estpro2="";
	$ls_estpro3="";
	$ls_coduniadm=$_GET["coduniadm"];
	$ls_estuac=$_GET["estuac"];
	$ls_denestpro1="";
	$ls_denestpro1=$_GET["denestpro1"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Unidades Administrativas</title>
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
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="564" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="560" colspan="2" class="titulo-celda">Cat&aacute;logo de Unidades Administrativas </td>
    	</tr>
	 </table>
	 <br>
	 <table width="564" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="111"><div align="right">Codigo</div></td>
        <td width="451"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion">
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><?php print $dat["nomestpro1"];?></div></td>
        <td><div align="left">
          <input name="codestpro1" type="text" id="codestpro1" style="text-align:center" value="<?php print $ls_estpro1?>" size="22" maxlength="20" readonly>
          <a href="javascript:catalogo_estpro1();"></a>
          <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" value="<?php print $ls_denestpro1; ?>" size="53" readonly>          
        </div>
       </td>
      </tr>
      <tr>
        <td><div align="right"> <?php print $dat["nomestpro2"];?></div>         </td>
        <td><div align="left">
          <input name="codestpro2" type="text" id="codestpro22" size="22" maxlength="6" style="text-align:center" readonly>
          <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
          <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" size="53" readonly>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">    <?php print $dat["nomestpro3"];?></div>      </td>
        <td><div align="left">
          <input name="codestpro3" type="text" id="codestpro32" size="22" maxlength="3" style="text-align:center" readonly>
          <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
          <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" size="53" readonly>
        </div></td>
      </tr>
	  <?php
	  if($dat["estmodest"]==2)
	  {
	  ?>
	  <tr>
        <td><div align="right"> <?php print $dat["nomestpro4"];?></div>         </td>
        <td><div align="left">
          <input name="codestpro4" type="text" id="codestpro4" size="5" maxlength="2" style="text-align:center" readonly>
          <a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 4"></a>
          <input name="denestpro4" type="text" class="sin-borde" id="denestpro4" size="53" readonly>
        </div></td>
      </tr>
	  <tr>
        <td><div align="right"> <?php print $dat["nomestpro5"];?></div>         </td>
        <td><div align="left">
          <input name="codestpro5" type="text" id="codestpro5" size="5" maxlength="2" style="text-align:center" readonly>
          <a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 5"></a>
          <input name="denestpro5" type="text" class="sin-borde" id="denestpro5" size="53" readonly>
        </div></td>
      </tr>
	  <?php
	  }
	  ?>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();">
          <input name="coduniadm" type="hidden" id="coduniadm" value="<?php print $ls_coduniadm;?>">
          <input name="estuac" type="hidden" id="estuac" value="<?php print $ls_estuac;?>">
          <img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<br>
    <?php

print "<table width=564 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "<td>Emite Req.</td>";
print "<td>".$dat["nomestpro1"]."</td>";
print "<td>".$dat["nomestpro2"]."</td>";
print "<td>".$dat["nomestpro3"]."</td>";
if($dat["estmodest"]==2)
{
	print "<td>".$dat["nomestpro4"]."</td>";
	print "<td>".$dat["nomestpro5"]."</td>";
}
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	if($ls_estuac=='C')
	{
	$ls_sql="SELECT a.coduniadm,a.denuniadm,a.estemireq,a.codestpro1,a.codestpro2,a.codestpro3,a.coduniadmsig,b.denestpro1,c.denestpro2,d.denestpro3,a.codestpro4,a.codestpro5,e.denestpro4,f.denestpro5
			 FROM   spg_unidadadministrativa a,spg_ep1 b ,spg_ep2 c,spg_ep3 d,spg_ep4 e,spg_ep5 f
			 WHERE  a.codemp='".$ls_codemp."' AND a.denuniadm like '".$ls_denominacion."'
			 AND    b.codemp='".$ls_codemp."' AND c.codemp='".$ls_codemp."' AND d.codemp='".$ls_codemp."' AND e.codemp='".$ls_codemp."' AND f.codemp='".$ls_codemp."'
			 AND a.codestpro1=b.codestpro1 AND a.codestpro2=c.codestpro2 AND a.codestpro1=c.codestpro1 AND a.codestpro1=d.codestpro1 
			 AND a.codestpro2=d.codestpro2 AND a.codestpro3=d.codestpro3 AND a.codestpro1=e.codestpro1 
			 AND a.codestpro2=e.codestpro2 AND a.codestpro3=e.codestpro3 AND a.codestpro4=e.codestpro4 AND a.codestpro1=f.codestpro1 
			 AND a.codestpro2=f.codestpro2 AND a.codestpro3=f.codestpro3 AND a.codestpro4=f.codestpro4 
			 AND a.codestpro5=f.codestpro5 ";
	}
	else
	{
	$ls_sql="SELECT a.coduniadm,a.denuniadm,a.estemireq,a.codestpro1,a.codestpro2,a.codestpro3,a.coduniadmsig,b.denestpro1,c.denestpro2,d.denestpro3,a.codestpro4,a.codestpro5,e.denestpro4,f.denestpro5
			 FROM   spg_unidadadministrativa a,spg_ep1 b ,spg_ep2 c,spg_ep3 d,spg_ep4 e,spg_ep5 f
			 WHERE  a.codemp='".$ls_codemp."' AND a.coduniadm like '".$ls_codigo."' AND a.denuniadm like '".$ls_denominacion."'
			 AND    b.codemp='".$ls_codemp."' AND c.codemp='".$ls_codemp."' AND d.codemp='".$ls_codemp."' 
			 AND a.codestpro1=b.codestpro1 AND a.codestpro2=c.codestpro2 AND a.codestpro1=c.codestpro1 AND a.codestpro1=d.codestpro1 
			 AND a.codestpro2=d.codestpro2 AND a.codestpro3=d.codestpro3 AND a.codestpro1=e.codestpro1 
			 AND a.codestpro2=e.codestpro2 AND a.codestpro3=e.codestpro3 AND a.codestpro4=e.codestpro4 AND a.codestpro1=f.codestpro1 
			 AND a.codestpro2=f.codestpro2 AND a.codestpro3=f.codestpro3 AND a.codestpro4=f.codestpro4 
			 AND a.codestpro5=f.codestpro5 AND coduniadmsig='".$ls_coduniadm."'";
			 if($ls_estpro1!="")
			{
				 $ls_sql=$ls_sql." AND a.codestpro1='".$ls_estpro1."'";
				 if($ls_estpro2!="")
				 {
					$ls_sql=$ls_sql." AND a.codestpro2='".$ls_estpro2."'";
				 }	
				 if($ls_estpro3!="")
				 {
					$ls_sql=$ls_sql." AND a.codestpro3='".$ls_estpro3."'";
				 }
				 if($dat["estmodest"]==2)	
				 {	
				 	if($ls_estpro4!="")
					 {
						$ls_sql=$ls_sql." AND a.codestpro4='".$ls_estpro4."'";
					 }
					 if($ls_estpro5!="")
					 {
						$ls_sql=$ls_sql." AND a.codestpro5='".$ls_estpro5."'";
					 }
				 }
			}
	}
	
	$rs_unidad=$SQL->select($ls_sql);
	$data=$rs_unidad;
	if($row=$SQL->fetch_row($rs_unidad))
	{
		$data=$SQL->obtener_datos($rs_unidad);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("coduniadm");
		$SQL->free_result($rs_unidad);
		$SQL->close();
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$codigo=$data["coduniadm"][$z];
			$denominacion=$data["denuniadm"][$z];
			$estreq=$data["estemireq"][$z];
			$codestpro1=$data["codestpro1"][$z];
			$codestpro2=$data["codestpro2"][$z];
			$codestpro3=$data["codestpro3"][$z];
			if($dat["estmodest"]==2)
			{
				$codestpro1=substr($codestpro1,-2);
				$codestpro2=substr($codestpro2,-2);
				$codestpro3=substr($codestpro3,-2);
				$codestpro4=$data["codestpro4"][$z];
				$codestpro5=$data["codestpro5"][$z];
				$denestpro4=$data["denestpro4"][$z];				
				$denestpro5=$data["denestpro5"][$z];	
			}
			else
			{
				$codestpro4="";
				$codestpro5="";
				$denestpro4="";				
				$denestpro5="";	
			}	
			$coduniadmsig=$data["coduniadmsig"][$z];
			$denestpro1=$data["denestpro1"][$z];			
			$denestpro2=$data["denestpro2"][$z];			
			$denestpro3=$data["denestpro3"][$z];
			if($dat["estmodest"]==2)
			{
				print "<td align=center><a href=\"javascript: aceptar('$codigo','$denominacion','$estreq','$codestpro1','$codestpro2','$codestpro3','$codestpro4','$codestpro5','$coduniadmsig','$denestpro1','$denestpro2','$denestpro3','$denestpro4','$denestpro5');\">".$codigo."</a></td>";
			}
			else
			{
				print "<td align=center><a href=\"javascript: aceptar('$codigo','$denominacion','$estreq','$codestpro1','$codestpro2','$codestpro3','$codestpro4','$codestpro5','$coduniadmsig','$denestpro1','$denestpro2','$denestpro3','$denestpro4','$denestpro5');\">".$codigo."</a></td>";
			}
			print "<td>".$denominacion."</td>";
			print "<td align=center>".$estreq."</td>";
			print "<td align=center>".$codestpro1."</td>";
			print "<td align=center>".$codestpro2."</td>";
			print "<td align=center>".$codestpro3."</td>";
			if($dat["estmodest"]==2)
			{
				print "<td align=center>".$codestpro4."</td>";
				print "<td align=center>".$codestpro5."</td>";
			}
			print "</tr>";			
		}
	}
	else
	{
		$io_msg->message("No se han definido unidades administrativas");		
	}
}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(codigo,deno,estreq,codest1,codest2,codest3,codest4,codest5,coduniadmsig,denestpro1,denestpro2,denestpro3,denestpro4,denestpro5)
  {
    opener.document.form1.txtcoduniadm.value=codigo;
    opener.document.form1.txtdenuniadm.value=deno;
	opener.document.form1.codestpro2.value=codest2;
	opener.document.form1.codestpro3.value=codest3;
	opener.document.form1.denestpro2.value=denestpro2;
	opener.document.form1.denestpro3.value=denestpro3;
	if("<?php print $dat["estmodest"]?>"==2)
	{
		opener.document.form1.codestpro4.value=codest4;
		opener.document.form1.codestpro5.value=codest5;
		opener.document.form1.denestpro4.value=denestpro4;
		opener.document.form1.denestpro5.value=denestpro5;
	}	
	close();
  }
  
function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="tepuy_spg_cat_uel.php";
	f.submit();
}
  
function catalogo_estpro1()
{
	   pagina="tepuy_cat_public_estpro1.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="tepuy_cat_public_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	codestpro3=f.codestpro3.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3==""))
	{
    	pagina="tepuy_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		pagina="tepuy_cat_public_estpro.php";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
}
</script>
</html>
