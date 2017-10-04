<?php
//session_id('8675309');
session_start();
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_tepuy_int.php");
require_once("../shared/class_folder/class_tepuy_int_scg.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
$int_scg=new class_tepuy_int_scg();
$msg=new class_mensajes();
$fun=new class_funciones();
$ds=new class_datastore();
$SQL=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$li_estmodest  = $arr["estmodest"];
$as_codemp=$arr["codemp"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas Contables</title>
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
  <table width="700" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda"><div align="center">Cat&aacute;logo de Cuentas Presupuestaria </div></td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="700" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
    <?php 
		if($li_estmodest==1)
		{
	?>
	  <tr>
        <td align="right"><?php print $arr["nomestpro1"];?></td>
        <td><?php
		$li_estmodest  = $arr["estmodest"];
		if(array_key_exists("operacion",$_POST))
		{
			$ls_operacion=$_POST["operacion"];
			$ls_codigo=$_POST["codigo"]."%";
			$ls_denominacion="%".$_POST["nombre"]."%";
			$ls_codscg	= $_POST["txtcuentascg"]."%";
			$ls_estpro1=$_POST["codestpro1"];
			$ls_estpro2=$_POST["codestpro2"];
			$ls_estpro3=$_POST["codestpro3"];
		}
		else
		{
			$ls_operacion="";
			$ls_estpro1="";
			$ls_estpro2="";
			$ls_estpro3="";
			$ls_codscg="";
			if((array_key_exists("codestpro1",$_GET)))
			{
				$ls_estpro1=$_GET["codestpro1"];
			}
			if(array_key_exists("codestpro2",$_GET))
			{
				$ls_estpro2=$_GET["codestpro2"];
			}
			if(array_key_exists("codestpro3",$_GET))
			{
				$ls_estpro3=$_GET["codestpro3"];
			}
		}
		?>
          <input name="codestpro1" type="text" id="codestpro1" size="22" maxlength="20" style="text-align:center " readonly value="<?php print $ls_estpro1;?>">          </td>
        <td width="48" align="right"><?php print $arr["nomestpro2"];?>        </td>
        <td width="48"><div align="left">
          <input name="codestpro2" type="text" id="codestpro23" size="8" maxlength="6" style="text-align:center " readonly value="<?php print $ls_estpro2;?>">
        </div></td>
        <td width="53" align="right"><?php print $arr["nomestpro3"];?></td>
        <td width="35">
          
          <div align="left">
            <input name="codestpro3" type="text" id="codestpro33" size="5" maxlength="3" style="text-align:center " readonly value="<?php print $ls_estpro3;?>">
          </div></td>
        <td width="54">&nbsp;</td>
        <td width="32">&nbsp;</td>
        <td width="49">&nbsp;</td>
        <td width="142">&nbsp;</td>
	  </tr>
	  <?php 
	  }
	  else
	  {
	  ?>
      <tr>
        <td align="right"><?php print $arr["nomestpro1"];?></td>
        <td><?php
		$li_estmodest  = $arr["estmodest"];
		if(array_key_exists("operacion",$_POST))
		{
			$ls_operacion=$_POST["operacion"];
			$ls_codigo=$_POST["codigo"]."%";
			$ls_denominacion="%".$_POST["nombre"]."%";
			$ls_codscg	= $_POST["txtcuentascg"]."%";
			$ls_estpro1=$_POST["codestpro1"];
			$ls_estpro2=$_POST["codestpro2"];
			$ls_estpro3=$_POST["codestpro3"];
			$ls_estpro4=$_POST["codestpro4"];
			$ls_estpro5=$_POST["codestpro5"];
		}
		else
		{
			$ls_operacion="";
			$ls_estpro1="";
			$ls_estpro2="";
			$ls_estpro2="";
			$ls_estpro4="";
			$ls_estpro5="";
			$ls_codscg="";
			if((array_key_exists("codestpro1",$_GET)))
			{
				$ls_estpro1=$_GET["codestpro1"];
			}
			if(array_key_exists("codestpro2",$_GET))
			{
				$ls_estpro2=$_GET["codestpro2"];
			}
			if(array_key_exists("codestpro3",$_GET))
			{
				$ls_estpro3=$_GET["codestpro3"];
			}
			if(array_key_exists("codestpro4",$_GET))
			{
				$ls_estpro4=$_GET["codestpro4"];
			}
			if(array_key_exists("codestpro5",$_GET))
			{
				$ls_estpro5=$_GET["codestpro5"];
			}
		}
		?>
        <input name="codestpro1" type="text" id="codestpro1" size="5" maxlength="2" style="text-align:center " readonly value="<?php print $ls_estpro1;?>"></td>
        <td align="right"><?php print $arr["nomestpro2"];?> </td>
        <td><div align="left">
            <input name="codestpro2" type="text" id="codestpro23" size="5" maxlength="2" style="text-align:center " readonly value="<?php print $ls_estpro2;?>">
        </div></td>
        <td align="right"><?php print $arr["nomestpro3"];?></td>
        <td><div align="left">
            <input name="codestpro3" type="text" id="codestpro33" size="5" maxlength="2" style="text-align:center " readonly value="<?php print $ls_estpro3;?>">
        </div></td>
        <td><div align="right"><?php print $arr["nomestpro4"];?></div></td>
        <td><div align="left">
          <input name="codestpro4" type="text" id="codestpro3" size="5" maxlength="2" style="text-align:center " readonly value="<?php print $ls_estpro4;?>">
        </div></td>
        <td><div align="right"><?php print $arr["nomestpro5"];?></div></td>
        <td><div align="left">
          <input name="codestpro5" type="text" id="codestpro4" size="5" maxlength="2" style="text-align:center " readonly value="<?php print $ls_estpro5;?>">
        </div></td>
      </tr>
	  <?php 
	   }
	  ?>
      <tr>
        <td align="right" width="94">Codigo</td>
        <td width="143"><div align="left">
          <input name="codigo" type="text" id="codigo" size="22" maxlength="20">        
        </div></td>
        <td colspan="8">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td colspan="9"><div align="left">
          <input name="nombre" type="text" id="nombre" size="72">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td><div align="right">Cuenta Contable </div></td>
        <td colspan="9"><div align="left">
          <input name="txtcuentascg" type="text" id="txtcuentascg" size="22" maxlength="20">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="8"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?php

print "<table width=700 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Presupuestaria</td>";
print "<td>".$arr["nomestpro1"]."</td>";
print "<td>".$arr["nomestpro2"]."</td>";
print "<td>".$arr["nomestpro3"]."</td>";
if($li_estmodest==2)
{
	print "<td>".$arr["nomestpro4"]."</td>";
	print "<td>".$arr["nomestpro5"]."</td>";
}
print "<td>Denominación</td>";
print "<td>Contable</td>";
print "<td>Disponible</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	
	if($li_estmodest==2)
	{
	    $ls_estpro1=$fun->uf_cerosizquierda($ls_estpro1,20);
	    $ls_estpro2=$fun->uf_cerosizquierda($ls_estpro2,6);
	    $ls_estpro3=$fun->uf_cerosizquierda($ls_estpro3,3);
	    $ls_estpro4=$fun->uf_cerosizquierda($ls_estpro4,2);
	    $ls_estpro5=$fun->uf_cerosizquierda($ls_estpro5,2);
		$ls_cadena ="SELECT *,(asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible".
					"  FROM spg_cuentas ".
					" WHERE codemp = '".$as_codemp."'".
					"   AND spg_cuenta like '408".$ls_codigo."'".
					"   AND denominacion like '".$ls_denominacion."'".
					"   AND sc_cuenta like '".$ls_codscg."'".
					"   AND codestpro1 like '%".$ls_estpro1."%'".
					"   AND codestpro2 like '%".$ls_estpro2."%'".
					"   AND codestpro3 like '%".$ls_estpro3."%'".
					"   AND codestpro4 like '%".$ls_estpro4."%'".
					"   AND codestpro5 like '%".$ls_estpro5."%' ".
					" ORDER BY codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta";
	}
	else
	{
		$ls_cadena ="SELECT *,(asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible".
					"  FROM spg_cuentas ".
					" WHERE codemp = '".$as_codemp."'".
					"   AND spg_cuenta like '408".$ls_codigo."'".
					"   AND denominacion like '".$ls_denominacion."'".
					"   AND sc_cuenta like '".$ls_codscg."'".
					"   AND codestpro1 like '%".$ls_estpro1."%'".
					"   AND codestpro2 like '%".$ls_estpro2."%'".
					"   AND codestpro3 like '%".$ls_estpro3."%'".
					" ORDER BY codestpro1, codestpro2, codestpro3, spg_cuenta";
	}
	//$ls_sql="SELECT SC_cuenta,denominacion FROM tepuy_Plan_unico ";
	$rs_cta=$SQL->select($ls_cadena);
	if($rs_cta==false)
	{
		$msg->message($fun->uf_convertirmsg($SQL->message));
	}
	else
	{
		$data=$rs_cta;
		if($row=$SQL->fetch_row($rs_cta))
		{
			$data=$SQL->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
			$totrow=$ds->getRowCount("spg_cuenta");
			for($z=1;$z<=$totrow;$z++)
			{
				$cuenta=trim($data["spg_cuenta"][$z]);
				$denominacion=$data["denominacion"][$z];
				$codest1=$data["codestpro1"][$z];
				$codest2=$data["codestpro2"][$z];
				$codest3=$data["codestpro3"][$z];
				if($li_estmodest==2)
				{
					$codest4=$data["codestpro4"][$z];
					$codest5=$data["codestpro5"][$z];
				}
				$scgcuenta=$data["sc_cuenta"][$z];
				$status=$data["status"][$z];
				$disponible=$data["disponible"][$z];
				if($li_estmodest==2)
				{
					$codest1=substr($codest1,18,2);
					$codest2=substr($codest2,4,2);
					$codest3=substr($codest3,1,2);
					$codest4=substr($codest4,0,2);
					$codest5=substr($codest5,0,2);
					if($status=="S")
					{
						print "<tr class=celdas-blancas>";
						print "<td>".$cuenta."</td>";
						print "<td  align=left>".$codest1."</td>";
						print "<td  align=left>".$codest2."</td>";
						print "<td  align=left>".$codest3."</td>";
						print "<td  align=left>".$codest4."</td>";
						print "<td  align=left>".$codest5."</td>";
						print "<td  align=left>".$denominacion."</td>";
						print "<td  align=center>".$scgcuenta."</td>";
						print "<td  align=center width=119>".number_format($disponible,2,",",".")."</td>";
					}
					else
					{
						print "<tr class=celdas-azules>";
						print "<td><a href=\"javascript:
					    aceptar_programa('$cuenta','$denominacion','$scgcuenta','$codest1','$codest2','$codest3','$codest4',
					    '$codest5','$status');\">".$cuenta."</a></td>";
						print "<td  align=left>".$codest1."</td>";
						print "<td  align=left>".$codest2."</td>";
						print "<td  align=left>".$codest3."</td>";
						print "<td  align=left>".$codest4."</td>";
						print "<td  align=left>".$codest5."</td>";
						print "<td  align=left>".$denominacion."</td>";
						print "<td  align=center>".$scgcuenta."</td>";
						print "<td  align=center>".number_format($disponible,2,",",".")."</td>";				
					}
					print "</tr>";			
				}
				else
				{
					if($status=="S")
					{
						print "<tr class=celdas-blancas>";
						print "<td>".$cuenta."</td>";
						print "<td  align=left>".$codest1."</td>";
						print "<td  align=left>".$codest2."</td>";
						print "<td  align=left>".$codest3."</td>";
						print "<td  align=left>".$denominacion."</td>";
						print "<td  align=center>".$scgcuenta."</td>";
						print "<td  align=center width=119>".number_format($disponible,2,",",".")."</td>";
					}
					else
					{
						print "<tr class=celdas-azules>";
						print "<td><a href=\"javascript: aceptar('$cuenta','$denominacion','$scgcuenta','$codest1','$codest2','$codest3','$status');\">".$cuenta."</a></td>";
						print "<td  align=left>".$codest1."</td>";
						print "<td  align=left>".$codest2."</td>";
						print "<td  align=left>".$codest3."</td>";
						print "<td  align=left>".$denominacion."</td>";
						print "<td  align=center>".$scgcuenta."</td>";
						print "<td  align=center>".number_format($disponible,2,",",".")."</td>";				
					}
					print "</tr>";			
				}
			}
			$SQL->free_result($rs_cta);
			$SQL->close();
		}
		else
		{
		?>
		<script language="JavaScript">
		alert("No se han creado Cuentas.....");
		//close();
        </script>
		<?php
		}
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

  function aceptar(cuenta,deno,scgcuenta,codest1,codest2,codest3,status)
  {
    opener.document.form1.txtctaspg.value=cuenta;
	opener.document.form1.txtdenctaspg.value=deno;
	close();
  }
  function aceptar_programa(cuenta,deno,scgcuenta,codest1,codest2,codest3,codest4,codest5,status)
  {
	opener.document.form1.txtctaspg.value=cuenta;
	opener.document.form1.txtdenctaspg.value=deno;
	close();
  }
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cat_public_ctasspg.php";
	  f.submit();
  }
	function uf_cambio_estprog1()
	{
		f=document.form1;
		f.action="tepuy_cat_public_ctasspg.php";
		f.operacion.value="est1";
		f.submit();
	}
	function uf_cambio_estprog2()
	{
		f=document.form1;
		f.action="tepuy_cat_public_ctasspg.php";
		f.operacion.value="est2";
		f.submit();
	}
</script>
</html>
