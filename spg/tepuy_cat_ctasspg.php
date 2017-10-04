<?php
session_start();
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_tepuy_int.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_tepuy_int_scg.php");
$in=new tepuy_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
$int_scg=new class_tepuy_int_scg();
$msg=new class_mensajes();
$fun    = new class_funciones();
$io_sql = new class_sql($con);
$arr=$_SESSION["la_empresa"];
$li_estmodest  = $arr["estmodest"];
$as_codemp=$arr["codemp"];
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
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="opera" type="hidden" id="opera" value="<? print $ls_opera; ?>">
  </p>
  <br>
  <div align="center">
    <table width="700" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="3" align="right" class="titulo-celda">Cat&aacute;logo de Cuentas Presupuestaria</td>
      </tr>
      <tr>
        <td width="120" height="13" align="right">&nbsp;</td>
        <td width="117" height="13">&nbsp;</td>
        <td width="461" height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" align="right"><?php print $arr["nomestpro1"];?></td>
        <td height="22" colspan="2"><?php
		$li_estmodest  = $arr["estmodest"];
		if(array_key_exists("operacion",$_POST))
		{
			$ls_operacion    = $_POST["operacion"];
			$ls_codigo       = "%".$_POST["codigo"]."%";
			$ls_denominacion = "%".$_POST["nombre"]."%";
			$ls_codscg	     = $_POST["txtcuentascg"]."%";
			$ls_estpro1      = $_POST["codestpro1"];
			$ls_estpro2      = $_POST["codestpro2"];
			$ls_estpro3		 = $_POST["codestpro3"];
			if((array_key_exists("codestpro4",$_POST)))
			{
				$ls_estpro4=$_POST["codestpro4"];
			}
			if(array_key_exists("codestpro5",$_POST))
			{
				$ls_estpro5=$_POST["codestpro5"];
			}
            $ls_opera   	 = $_GET["opera"];
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
			if(array_key_exists("hicodest2",$_GET))
			{
				$ls_estpro2=$_GET["hicodest2"];
			}
			if(array_key_exists("hicodest3",$_GET))
			{
				$ls_estpro3=$_GET["hicodest3"];
			}
			if(array_key_exists("hicodest4",$_GET))
			{
				$ls_estpro4=$_GET["hicodest4"];
			}
			if(array_key_exists("hicodest5",$_GET))
			{
				$ls_estpro5=$_GET["hicodest5"];
			}			
			if(array_key_exists("opera",$_GET))
			{
				$ls_opera=$_GET["opera"];
			}
			else
			{
				$ls_opera="";
			}
		}
		?>
        <input name="codestpro1" type="text" id="codestpro1" size="22" maxlength="20" style="text-align:center " readonly value="<?php print $ls_estpro1;?>"></td>
      </tr>
      <tr>
        <td height="22" align="right"><?php print $arr["nomestpro2"];?></td>
        <td height="22" colspan="2"><input name="codestpro2" type="text" id="codestpro2" size="8" maxlength="6" style="text-align:center " readonly value="<?php print $ls_estpro2;?>"></td>
      </tr>
      <tr>
        <td height="22" align="right"><?php print $arr["nomestpro3"];?></td>
        <td height="22" colspan="2"><input name="codestpro3" type="text" id="codestpro3" size="5" maxlength="3" style="text-align:center " readonly value="<?php print $ls_estpro3;?>"></td>
      </tr>
      <?php
	  if ($li_estmodest=='2')
	     {
	  ?>
	  <tr>
        <td height="22" align="right"><?php print $arr["nomestpro4"];?></td>
        <td height="22" colspan="2"><input name="codestpro4" type="text" id="codestpro4" size="5" maxlength="2" style="text-align:center " readonly value="<?php print $ls_estpro4;?>"></td>
      </tr>
      <tr>
        <td height="22" align="right"><?php print $arr["nomestpro5"];?></td>
        <td height="22" colspan="2"><input name="codestpro5" type="text" id="codestpro5" size="5" maxlength="2" style="text-align:center " readonly value="<?php print $ls_estpro5;?>"></td>
      </tr>
	  <?php
	    }
	  ?>
      <tr>
        <td height="22" align="right">C&oacute;digo</td>
        <td height="22" colspan="2" style="text-align:left"><input name="codigo" type="text" id="codigo" size="22" maxlength="20" style="text-align:center"></td>
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
        <td height="22"><div align="right">Cuenta Contable </div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtcuentascg" type="text" id="txtcuentascg" size="22" maxlength="20">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
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
	
	if($ls_opera=="DI")
	{
	  $ls_cadena="AND spg_cuenta like '498%' ";
	}
	else
	{
	  $ls_cadena=" ";
	}
	if($li_estmodest==2)
	{
	    $ls_estpro1=$fun->uf_cerosizquierda($ls_estpro1,20);
	    $ls_estpro2=$fun->uf_cerosizquierda($ls_estpro2,6);
	    $ls_estpro3=$fun->uf_cerosizquierda($ls_estpro3,3);
	    $ls_estpro4=$fun->uf_cerosizquierda($ls_estpro4,2);
	    $ls_estpro5=$fun->uf_cerosizquierda($ls_estpro5,2);
		$ls_sql =" SELECT *,(asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible ".
		            " FROM  spg_cuentas ".
					" WHERE codemp = '".$as_codemp."' AND  spg_cuenta like '".$ls_codigo."' AND ".
					"       denominacion like '".$ls_denominacion."' AND sc_cuenta like '".$ls_codscg."' AND ".
					"       codestpro1 = '".$ls_estpro1."' AND codestpro2 = '".$ls_estpro2."' AND  ".
					"       codestpro3 = '".$ls_estpro3."' AND codestpro4 = '".$ls_estpro4."' AND  ".
					"       codestpro5 = '".$ls_estpro5."' ".$ls_cadena." ".
	                " ORDER BY spg_cuenta";
	}
	else
	{
		$ls_sql =" SELECT *,(asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible ".
		            " FROM  spg_cuentas ".
					" WHERE codemp = '".$as_codemp."' AND spg_cuenta like '".$ls_codigo."' AND ".
					"       denominacion like '".$ls_denominacion."' AND sc_cuenta like '".$ls_codscg."' AND ".
					"       codestpro1 = '".$ls_estpro1."' AND codestpro2 = '".$ls_estpro2."' AND ".
					"       codestpro3 = '".$ls_estpro3."' ".$ls_cadena." ".
					" ORDER BY spg_cuenta";
	}
	$rs_data = $io_sql->select($ls_sql);//print $ls_sql;
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
				     $ls_spgcta     = $row["spg_cuenta"];
					 $ls_denctaspg  = $row["denominacion"];
					 $ls_codestpro1 = $row["codestpro1"];
					 $ls_codestpro2 = $row["codestpro2"];
				     $ls_codestpro3 = $row["codestpro3"];
					 if ($li_estmodest==2)
				        {
						  $ls_codestpro4 = $row["codestpro4"];
					      $ls_codestpro5 = $row["codestpro5"];
				        }
				     $ls_scgcta = $row["sc_cuenta"];
				     $ls_status = $row["status"];
				     $ld_mondis = $row["disponible"];
				     if ($li_estmodest==2)
				        {
					      $ls_codestpro1 = substr($ls_codestpro1,18,2);
						  $ls_codestpro2 = substr($ls_codestpro2,4,2);
					      $ls_codestpro3 = substr($ls_codestpro3,1,2);
					      $ls_codestpro4 = substr($ls_codestpro4,0,2);
					      $ls_codestpro5 = substr($ls_codestpro5,0,2);
					      if ($ls_status=="S")
					         {
							   print "<tr class=celdas-blancas>";
							   print "<td>".$ls_spgcta."</td>";
							   print "<td  align=left>".$ls_codestpro1."</td>";
							   print "<td  align=left>".$ls_codestpro2."</td>";
							   print "<td  align=left>".$ls_codestpro3."</td>";
							   print "<td  align=left>".$ls_codestpro4."</td>";
							   print "<td  align=left>".$ls_codestpro5."</td>";
							   print "<td  align=left>".$ls_denctaspg."</td>";
							   print "<td  align=center>".$ls_scgcta."</td>";
							   print "<td  align=center width=119>".number_format($ld_mondis,2,",",".")."</td>";
					         }
					      else
					         {
					 	       print "<tr class=celdas-azules>";
						       print "<td><a href=\"javascript:aceptar_programa('$ls_spgcta','$ls_denctaspg','$ls_scgcta',
							   '$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5',
							   '$ls_status','$ld_mondis');\">".$ls_spgcta."</a></td>";
							   print "<td  align=left>".$ls_codestpro1."</td>";
							   print "<td  align=left>".$ls_codestpro2."</td>";
							   print "<td  align=left>".$ls_codestpro3."</td>";
							   print "<td  align=left>".$ls_codestpro4."</td>";
							   print "<td  align=left>".$ls_codestpro5."</td>";
							   print "<td  align=left>".$ls_denctaspg."</td>";
							   print "<td  align=center>".$ls_scgcta."</td>";
							   print "<td  align=center>".number_format($ld_mondis,2,",",".")."</td>";				
					         }
					      print "</tr>";			
				        }
				      else
				        {
					      if ($ls_status=="S")
					         {
							   print "<tr class=celdas-blancas>";
							   print "<td>".$ls_spgcta."</td>";
							   print "<td  align=left>".$ls_codestpro1."</td>";
							   print "<td  align=left>".$ls_codestpro2."</td>";
							   print "<td  align=left>".$ls_codestpro3."</td>";
							   print "<td  align=left>".$ls_denctaspg."</td>";
							   print "<td  align=center>".$ls_scgcta."</td>";
							   print "<td  align=center width=119>".number_format($ld_mondis,2,",",".")."</td>";
					         }
					      else
					         {
							   print "<tr class=celdas-azules>";
							   print "<td><a href=\"javascript: aceptar('$ls_spgcta','$ls_denctaspg','$ls_scgcta','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_status','$ld_mondis');\">".$ls_spgcta."</a></td>";
							   print "<td  align=left>".$ls_codestpro1."</td>";
							   print "<td  align=left>".$ls_codestpro2."</td>";
							   print "<td  align=left>".$ls_codestpro3."</td>";
							   print "<td  align=left>".$ls_denctaspg."</td>";
							   print "<td  align=center>".$ls_scgcta."</td>";
							   print "<td  align=center>".number_format($ld_mondis,2,",",".")."</td>";				
					         }
					      print "</tr>";			
				       }
			
			       }
			  $io_sql->free_result($rs_data);
			  $io_sql->close();
		    }
		 else
		    { 
			  ?>
			  <script language="JavaScript">
		 	  alert("No se han creado Cuentas.....");
			  close();
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

  function aceptar(cuenta,deno,scgcuenta,codest1,codest2,codest3,status,disponible)
  {
	opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=deno;
    //	opener.document.form1.submit();
	close();
  }
  function aceptar_programa(cuenta,deno,scgcuenta,codest1,codest2,codest3,codest4,codest5,status,disponible)
  {
	opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=deno;
	//	opener.document.form1.submit();
	close();
  }
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cat_ctasspg.php?opera=<?php print $ls_opera ?>";
	  f.submit();
  }
  function uf_cambio_estprog1()
  {
	f=document.form1;
	f.action="tepuy_cat_ctasspg.php";
	f.operacion.value="est1";
	f.submit();
  }
  function uf_cambio_estprog2()
  {
	f=document.form1;
	f.action="tepuy_cat_ctasspg.php";
	f.operacion.value="est2";
	f.submit();
  }
</script>
</html>