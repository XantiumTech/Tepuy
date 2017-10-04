<?php
//session_id('8675309');
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "opener.document.form1.submit();";
	print "close();";
	print "</script>";		
}
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");
$in           = new tepuy_include();
$con          = $in->uf_conectar();
$msg          = new class_mensajes();
$fun          = new class_funciones();
$io_sql       = new class_sql($con);
$arr          = $_SESSION["la_empresa"];
$as_codemp    = $arr["codemp"];
$as_estmodest = $arr["estmodest"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas Presupuestarias de Gasto</title>
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
  <br>
  <div align="center">
    <table width="623" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="6" align="right" class="titulo-celda">Cat&aacute;logo de Cuentas Presupuestaria de Gasto</td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td width="106" align="right"><?php print $arr["nomestpro1"];?></td>
        <td width="122"><?php
		if(array_key_exists("operacion",$_POST))
		{
			$ls_operacion    = $_POST["operacion"];
			$ls_codigo       = $_POST["codigo"];
			$ls_denominacion = $_POST["nombre"];
			$ls_codscg	     = $_POST["txtcuentascg"];
			$ls_estpro1      = $_POST["codestpro1"];
			$ls_estpro2      = $_POST["codestpro2"];
			$ls_estpro3      = $_POST["codestpro3"];
			if($as_estmodest==1)
			{
				$ls_estpro4="00";
				$ls_estpro5="00";
			}
			else
			{
				$ls_estpro4=$_POST["codestpro4"];
				$ls_estpro5=$_POST["codestpro5"];
			}
		}
		else
		{
			$ls_operacion="";
			$ls_estpro1="";
			$ls_estpro2="";
			$ls_estpro2="";
			$ls_codscg="";
			if((array_key_exists("codestpro1",$_GET)))
			{
				$ls_estpro1=$_GET["codestpro1"];
			}
			if(array_key_exists("hicodest2",$_GET))
			{
				$ls_estpro2=$_GET["hicodest2"];
			}
			if(array_key_exists("hicodest3",$_GET))
			{
				$ls_estpro3=$_GET["hicodest3"];
			}
			if($as_estmodest==1)
			{
				$ls_estpro4="00";
				$ls_estpro5="00";
			}
			else
			{
				if(array_key_exists("hicodest4",$_GET))
				{
					$ls_estpro4=$_GET["hicodest4"];
				}
				if(array_key_exists("hicodest5",$_GET))
				{
					$ls_estpro5=$_GET["hicodest5"];
				}
			}
		}
		?>
		  <div align="left"></div>
		  <div align="left">
		   <?php if($as_estmodest==2){ $li_size=5;$li_max=2;$ls_estpro1=substr($ls_estpro1,-2);}else{$li_size=22;$li_max=20;}?>
            <input name="codestpro1" type="text" id="codestpro1" size="<?php print $li_size;?>" maxlength="<?php print $li_max;?>" style="text-align:center " readonly value="<?php print $ls_estpro1;?>">
        </div></td>
        <td width="63" align="right"><?php print $arr["nomestpro2"];?>        </td>
		<?php if($as_estmodest==2){ $li_size=5;$li_max=2;$ls_estpro2=substr($ls_estpro2,-2);}else{$li_size=8;$li_max=6;}?>
        <td width="127"><div align="left">
          <input name="codestpro2" type="text" id="codestpro2" size="<?php print $li_size;?>" maxlength="<?php print $li_max;?>" style="text-align:center " readonly value="<?php print $ls_estpro2;?>">
        </div></td>
        <td width="52" align="right"><?php print $arr["nomestpro3"];?></td>
        <td width="151" ><div align="left">
		<?php if($as_estmodest==2){ $li_size=5;$li_max=2;$ls_estpro3=substr($ls_estpro3,-2);}else{$li_size=5;$li_max=3;}?>
          <input name="codestpro3" type="text" id="codestpro3" size="<?php print $li_size;?>" maxlength="<?php print $li_max;?>" style="text-align:center " readonly value="<?php print $ls_estpro3;?>">
        </div></td>        
      </tr>
	  <tr>
	  <td ><div align="right"><?php if($as_estmodest==2){  print $arr["nomestpro4"];}?></div></td>
        <td ><label>
          <div align="left">
		  <?php if($as_estmodest==2){ ?>
            <input name="codestpro4" id="codestpro4" type="text" size="3" maxlength="2" readonly style="text-align:center" value="<?php print $ls_estpro4;?>">
          <?php }?>  
		  </div>
        </label></td>
        <td ><label></label>
        <div align="right"><?php if($as_estmodest==2){ print $arr["nomestpro5"];}?></div></td>
        <td ><div align="left">
		<?php if($as_estmodest==2){ ?>
          <input name="codestpro5" id="codestpro5" type="text" size="3" maxlength="2" readonly style="text-align:center" value="<?php print $ls_estpro5;?>">
        <?php }?>
		</div></td>
	  </tr>
      <tr>
        <td height="22" align="right">Codigo</td>
        <td height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="22" maxlength="20">        
        </div></td>
        <td height="22" colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="9"><div align="left">
          <input name="nombre" type="text" id="nombre" size="72">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Cuenta Contable </div></td>
        <td height="22" colspan="9"><div align="left">
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



print "<table width=750 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Presupuestaria</td>";
print "<td style=text-align:center>".$arr["nomestpro1"]."</td>";
print "<td style=text-align:center>".$arr["nomestpro2"]."</td>";
print "<td style=text-align:center>".$arr["nomestpro3"]."</td>";
if($as_estmodest==2)
{ 
	print "<td style=text-align:center>".$arr["nomestpro4"]."</td>";
	print "<td style=text-align:center>".$arr["nomestpro5"]."</td>";	
}
print "<td style=text-align:center>Denominación</td>";
print "<td style=text-align:center>Contable</td>";
print "<td style=text-align:center>Disponible</td>";
print "</tr>";
$ls_sql_aux="";

if ($ls_operacion=="BUSCAR")
   {
	 if ($as_estmodest==2)
	    {  
		  $ls_sql_aux = " AND codestpro4='".$ls_estpro4."' ";
		  $ls_sql_aux = $ls_sql_aux." AND codestpro5='".$ls_estpro5."' ";
	    }
	
	 $ls_sql = "SELECT codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,trim(spg_cuenta) as spg_cuenta,
	                   denominacion,trim(sc_cuenta) as sc_cuenta,status,".
	           "       (asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible ".
	           "  FROM spg_cuentas ".
			   " WHERE codemp = '".$as_codemp."' AND spg_cuenta like '".$ls_codigo."%' ".
			   "   AND denominacion like '%".$ls_denominacion."%' ".
			   "   AND sc_cuenta like '".$ls_codscg."%' ".
			   "   AND codestpro1='".$fun->uf_cerosizquierda($ls_estpro1,20)."' ".
			   "   AND codestpro2='".$fun->uf_cerosizquierda($ls_estpro2,6)."'  ".
			   "   AND codestpro3='".$fun->uf_cerosizquierda($ls_estpro3,3)."' ".$ls_sql_aux." 
				 ORDER BY spg_cuenta";
	  $rs_data = $io_sql->select($ls_sql);
	  if ($rs_data==false)
	     {
		   $msg->message($fun->uf_convertirmsg($io_sql->message));
	     }
	  else
	     {
		   $li_numrows = $io_sql->num_rows($rs_data);
		   if ($li_numrows>0)
		      {
				while ($row=$io_sql->fetch_row($rs_data))
				      {
	                    $cuenta       = $row["spg_cuenta"];
						$denominacion = $row["denominacion"];
						$codest1	  = $row["codestpro1"];
						$codest2	  =	$row["codestpro2"];
						$codest3      = $row["codestpro3"];
						$codest4      = $row["codestpro4"];
						$codest5      = $row["codestpro5"];
						$scgcuenta    = $row["sc_cuenta"];
						$status       = $row["status"];
						$disponible   = $row["disponible"];
						if ($status=="S")
						   {
							 print "<tr class=celdas-blancas>";
							 print "<td style=text-align:center>".$cuenta."</td>";
							 if ($as_estmodest==2)
								{
								  print "<td style=text-align:center>".substr($codest1,-2)."</td>";
								  print "<td style=text-align:center>".substr($codest2,-2)."</td>";
								  print "<td style=text-align:center>".substr($codest3,-2)."</td>";
								  print "<td style=text-align:center>".$codest4."</td>";
								  print "<td style=text-align:center>".$codest5."</td>";
								}
							 else
								{
								  print "<td style=text-align:center>".$codest1."</td>";
								  print "<td style=text-align:center>".$codest2."</td>";
								  print "<td style=text-align:center>".$codest3."</td>";
								}
							 print "<td style=text-align:left>".$denominacion."</td>";
							 print "<td style=text-align:center>".$scgcuenta."</td>";
							 print "<td style=text-align:right width=119>".number_format($disponible,2,",",".")."</td>";
						   }
						else
						   {
							 print "<tr class=celdas-azules>";
							 print "<td style=text-align:center><a href=\"javascript: aceptar('$cuenta','$denominacion','$scgcuenta');\">".$cuenta."</a></td>";
							 if ($as_estmodest==2)
								{
								  print "<td style=text-align:center>".substr($codest1,-2)."</td>";
								  print "<td style=text-align:center>".substr($codest2,-2)."</td>";
								  print "<td style=text-align:center>".substr($codest3,-2)."</td>";
								  print "<td style=text-align:center>".$codest4."</td>";
								  print "<td style=text-align:center>".$codest5."</td>";
								}
							 else
								{
								  print "<td style=text-align:center>".$codest1."</td>";
								  print "<td style=text-align:center>".$codest2."</td>";
								  print "<td style=text-align:center>".$codest3."</td>";
								}
							 print "<td style=text-align:left>".$denominacion."</td>";
							 print "<td style=text-align:center>".$scgcuenta."</td>";
							 print "<td style=text-align:right>".number_format($disponible,2,",",".")."</td>";				
						   }
						print "</tr>";			
					  }
				   $io_sql->free_result($rs_data);
				   $io_sql->close();
		      }
		   else
		      {
		        $msg->message("No se han creado Cuentas de gasto para la programatica seleccionada !!!");
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

  function aceptar(cuenta,deno,scgcuenta)
  {
    opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=deno;
    opener.document.form1.txtcuentascg.value=scgcuenta;
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cat_ctaspg.php";
	  f.submit();
  }	
</script>
</html>