<?php
session_start();
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_mensajes.php");
$in           = new tepuy_include();
$con          = $in->uf_conectar();
$ds           = new class_datastore();
$io_sql       = new class_sql($con);
$io_funcion   = new class_funciones();
$io_msg       = new class_mensajes();
$arr          = $_SESSION["la_empresa"];
$ls_codemp    = $arr["codemp"];
$li_estmodest = $arr["estmodest"];
if ($li_estmodest=='1')
   {
	 $li_maxlength_1 = '20';
	 $li_maxlength_2 = '6';
	 $li_maxlength_3 = '3';
	 $li_size        = '25';
	 $li_ancho       = '50';
   }
else
   {
	 $li_maxlength_1 = '2';
	 $li_maxlength_2 = '2';
	 $li_maxlength_3 = '2';
	 $li_size        = '5';
	 $li_ancho       = '70';
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Presupuestaria</title>
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
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <br>
  <div align="center">
    <table width="615" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="6" align="right"><div align="center">Cat&aacute;logo de Cuentas Presupuestaria </div></td>
      </tr>
      <tr>
        <td height="22" align="right"><input name="hidmaestro" type="hidden" id="hidmaestro" value="N">
        <?php
		if (array_key_exists("operacion",$_POST))
		   {
		     $ls_operacion  = $_POST["operacion"];
	  	     $ls_codigo     = $_POST["txtcodigo"];
	         $ls_denominacion=$_POST["txtdenominacion"];
		     $ls_cuenta     = $_POST["txtcuentascg"];		   
		     $ls_codestpro1 = $_POST["txtcodestpro1"];  
		     $ls_codestpro2 = $_POST["txtcodestpro2"];
		     $ls_codestpro3 = $_POST["txtcodestpro3"];
		     $ls_denestpro1 = $_POST["txtdenestpro1"];
		     $ls_denestpro2 = $_POST["txtdenestpro2"];
		     $ls_denestpro3 = $_POST["txtdenestpro3"];
		     if ($li_estmodest=='2')
			    {
			      $ls_codestpro4 = $_POST["txtcodestpro4"];
		          $ls_codestpro5 = $_POST["txtcodestpro5"];
				  $ls_denestpro4 = $_POST["txtdenestpro4"];
		          $ls_denestpro5 = $_POST["txtdenestpro5"];
				}
			 else
			    {
		          $ls_codestpro4 = "00";
		          $ls_codestpro5 = "00";
				  $ls_denestpro4 = "";
		          $ls_denestpro5 = "";
				}
		   }
		else
		   {
			 $ls_operacion="";
		     $ls_codigo     = "";
	         $ls_denominacion="";
		     $ls_cuenta     = "";
		     $ls_codestpro1 = "";
		     $ls_codestpro2 = "";
		     $ls_codestpro3 = "";
		     $ls_codestpro4 = "";
		     $ls_codestpro5 = "";
             $ls_denestpro1 = "";
		     $ls_denestpro2 = "";
		     $ls_denestpro3 = "";
		     $ls_denestpro4 = "";
		     $ls_denestpro5 = "";
		   }

		?>          <?php print $arr["nomestpro1"];?></td>
        <td height="22" colspan="5"><div align="left">
          <input name="txtcodestpro1" type="text" id="txtcodestpro1" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_1 ?>" style="text-align:center "  value="<?php print $ls_codestpro1;?>" readonly>
        <a href="javascript:catalogo_estpro1();"><img src="../../shared/imagebank/tools15/buscar.png" alt="Cat&aacute;logo de Estructuras de Nivel 1" width="15" height="15" border="0"></a>
        <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php print $ls_denestpro1 ?>" size="<?php print $li_ancho ?>" readonly style="cursor:default">
        </div></td>
      </tr>
      <tr>
        <td height="22" align="right"><?php print $arr["nomestpro2"];?></td>
        <td height="22" colspan="5"><input name="txtcodestpro2" type="text"   id="txtcodestpro2" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_2 ?>" style="text-align:center "  value="<?php print $ls_codestpro2;?>" readonly>
        <a href="javascript:catalogo_estpro2();"><img src="../../shared/imagebank/tools15/buscar.png" alt="Cat&aacute;logo de Estructuras de Nivel 2" width="15" height="15" border="0"></a>
        <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" value="<?php print $ls_denestpro2 ?>" size="<?php print $li_ancho ?>" readonly style="cursor:default">
        <input name="hidcodest2" type="hidden" id="hidcodest2">
        </td>
      </tr>
      <tr>
        <td height="22" align="right"><?php print $arr["nomestpro3"];?></td>
        <td height="22" colspan="5"><input name="txtcodestpro3" type="text" id="txtcodestpro3" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_3 ?>" style="text-align:center "  value="<?php print $ls_codestpro3;?>" readonly>
        <a href="javascript:catalogo_estpro3();"><img src="../../shared/imagebank/tools15/buscar.png" alt="Cat&aacute;logo de Estructuras de Nivel 3" width="15" height="15" border="0"></a>
        <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" value="<?php print $ls_denestpro3 ?>" size="<?php print $li_ancho ?>" readonly style="cursor:default">
        <input name="hidcodest3" type="hidden" id="hidcodest3">
        </td>
      </tr>
      <?
	   if ($li_estmodest=='2')
	      { ?>
	  <tr>
        <td height="22" align="right"><?php print $arr["nomestpro4"];?></td>
        <td height="22" colspan="5"><input name="txtcodestpro4" type="text" id="txtcodestpro4" value="<?php print $ls_codestpro4 ?>" size="<?php print $li_size ?>" style="text-align:center " maxlength="2" readonly>
          <a href="javascript:catalogo_estpro4();"><img src="../../shared/imagebank/tools15/buscar.png" alt="Cat&aacute;logo de Estructuras de Nivel 4" width="15" height="15" border="0"></a>
        <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" value="<?php print $ls_denestpro4 ?>" size="<?php print $li_ancho ?>" readonly style="cursor:default"></td>
      </tr>
      <tr>
        <td height="22" align="right"><?php print $arr["nomestpro5"];?></td>
        <td height="22" colspan="5"><label>
          
          <div align="left">
            <input name="txtcodestpro5" type="text" id="txtcodestpro5" style="text-align:center " value="<?php print $ls_codestpro5 ?>" size="<?php print $li_size ?>" maxlength="2" readonly>
            <a href="javascript:catalogo_estpro5();"><img src="../../shared/imagebank/tools15/buscar.png" alt="Cat&aacute;logo de Estructura de Nivel 5" width="15" height="15" border="0"></a>
            <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" value="<?php print $ls_denestpro5 ?>" size="<?php print $li_ancho ?>" readonly style="cursor:default">
          </div>
        </label>          <label></label></td>
      </tr>		  
	  <?
	  } 
	  ?>
	  
      <tr>
        <td width="135" height="22" align="right">Código</td>
        <td width="403" height="22"><div align="left">
          <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codigo ?>" size="22" maxlength="20" style="text-align:center">        
        </div></td>
        <td height="22" colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="5"><div style="text-align:left">
          <input name="txtdenominacion" type="text" id="txtdenominacion" value="<?php print $ls_denominacion ?>" size="72" maxlength="254">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Cuenta Contable </div></td>
        <td height="22" colspan="5"><div align="left">
          <input name="txtcuentascg" type="text" id="txtcuentascg" value="<?php print $ls_cuenta ?>" size="22" maxlength="20" style="text-align:center">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22" colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
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
if ($li_estmodest=='2') 
   {
     print "<td>".$arr["nomestpro4"]."</td>";
     print "<td>".$arr["nomestpro5"]."</td>";
   }
print "<td>Denominación</td>";
print "<td>Contable</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 if (!empty($ls_codestpro1)&&!empty($ls_codestpro2)&&!empty($ls_codestpro3))
	    {
		  $ls_codestpro1 = str_pad($ls_codestpro1,20,0,0);
		  $ls_codestpro2 = str_pad($ls_codestpro2,6,0,0);
		  $ls_codestpro3 = str_pad($ls_codestpro3,3,0,0);
	    }
	 $ls_sql = "SELECT spg_cuenta, denominacion, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, sc_cuenta, status  
	              FROM spg_cuentas                                                                        
		         WHERE codemp = '".$ls_codemp."'
				   AND spg_cuenta like '".$ls_codigo."%'
				   AND denominacion like '%".$ls_denominacion."%'
				   AND sc_cuenta  like '%".$ls_cuenta."%'
				   AND codestpro1 like '%".$ls_codestpro1."%'
				   AND codestpro2 like '%".$ls_codestpro2."%'
				   AND codestpro3 like '%".$ls_codestpro3."%'
				   AND codestpro4 like '%".$ls_codestpro4."%'
				   AND codestpro5 like '%".$ls_codestpro5."%'                                                    
				 ORDER BY spg_cuenta";
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
			   while($row=$io_sql->fetch_row($rs_data))
			        {
					  $ls_spgcta     = trim($row["spg_cuenta"]);
					  $ls_denctaspg  = $row["denominacion"];
					  $ls_codestpro1 = $row["codestpro1"];
					  $ls_codestpro2 = $row["codestpro2"];
					  $ls_codestpro3 = $row["codestpro3"];
					  $ls_codestpro4 = $row["codestpro4"];
					  $ls_codestpro5 = $row["codestpro5"];
					  if ($li_estmodest=='2') 
					     {
						   $ls_codestpro1 = substr($ls_codestpro1,-2);
						   $ls_codestpro2 = substr($ls_codestpro2,-2);
						   $ls_codestpro3 = substr($ls_codestpro3,-2);
					     }
					  $ls_scgcta = trim($row["sc_cuenta"]);
					  $ls_estcta = $row["status"];
					  if ($ls_estcta=='S')
					     {
						   echo "<tr class=celdas-blancas>";
						   echo "<td  align=center>".$ls_spgcta."</td>";
						 }
					  elseif($ls_estcta=='C')
					     {
						   echo "<tr class=celdas-azules>";
						   echo "<td  align=center><a href=\"javascript: aceptar('$ls_spgcta','$ls_denctaspg','$ls_scgcta','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcta');\">".$ls_spgcta."</a></td>";
						 }	 
					  echo "<td style=text-align:center>".$ls_codestpro1."</td>";
					  echo "<td style=text-align:center>".$ls_codestpro2."</td>";
					  echo "<td style=text-align:center>".$ls_codestpro3."</td>";
					  if ($li_estmodest=='2') 
					     {
						   echo "<td style=text-align:center>".$ls_codestpro4."</td>";
						   echo "<td style=text-align:center>".$ls_codestpro5."</td>";
						 }
					  echo "<td style=text-align:left>".$ls_denctaspg."</td>";
					  echo "<td style=text-align:center>".$ls_scgcta."</td>";
					  echo "</tr>";
					}
		  	   $io_sql->free_result($rs_data);
		       $io_sql->close();
			 }
          else
		     {
			   $io_msg->message("No se han creado Cuentas de Gasto para la Estructura Programática seleccionada !!!");
			 }
        }
   }
echo "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
f   = document.form1;
fop = opener.document.form1
function aceptar(cuenta,deno,sccuenta,ls_codestpro1,ls_codestpro2,ls_codestpro3,ls_codestpro4,ls_codestpro5,status)
{
	fop.txtpresupuestaria.value=cuenta;
	li_estmodest = <?php print $li_estmodest ?>;
	if (li_estmodest=='1')
	   {
	     fop.txtcodestpro.value=ls_codestpro1+'-'+ls_codestpro2+'-'+ls_codestpro3;  
	   }
	else
       {
	     fop.txtcodestpro.value=ls_codestpro1+'-'+ls_codestpro2+'-'+ls_codestpro3+'-'+ls_codestpro4+'-'+ls_codestpro5;     
	   }
	close();
}

  function ue_search()
  {
	  f.operacion.value="BUSCAR";
	  f.action="tepuy_cat_ctasspg.php";
	  f.submit();
  }

function catalogo_estpro1()
{
	pagina="tepuy_cxp_cat_estpro1.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function catalogo_estpro2()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	if((ls_codestpro1!="")&&(ls_denestpro1!=""))
	{
		pagina="tepuy_cxp_cat_estpro2.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
    else
	{
	  alert("Debe seleccionar una estructura del Nivel 1 !!!");
	}
}
function catalogo_estpro3()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;	
	if((ls_codestpro1!="")&&(ls_denestpro1!="")&&(ls_codestpro2!="")&&(ls_denestpro2!="")&&(ls_codestpro3==""))
	{
		pagina="tepuy_cxp_cat_estpro3.php?submit=si&txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	}
	else
	{
		li_estmodest = "<?php print $li_estmodest ?>";
		if (li_estmodest=='1')
		   {
		     pagina="tepuy_cat_public_estpro.php?submit=si";
		     window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	       }
		else
		   {
		     alert("Debe seleccionar una estructura del Nivel 2 !!!");
		   }
	}
}

function catalogo_estpro4()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;
	ls_denestpro3 = f.txtdenestpro3.value;
	ls_codestpro4 = f.txtcodestpro4.value;
	if ((ls_codestpro1!="")&&(ls_denestpro1!="")&&(ls_codestpro2!="")&&(ls_denestpro2!="")&&(ls_codestpro3!="")&&(ls_denestpro3!="")&&(ls_codestpro4==""))
	   {
		 pagina="tepuy_cxp_cat_estpro4.php?submit=si&txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtcodestpro3="+ls_codestpro3+"&txtdenestpro3="+ls_denestpro3;
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
 	   }
    else
	   {
	     alert("Debe seleccionar una estructuta del Nivel 3 !!!");
	   }
}

function catalogo_estpro5()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;
	ls_codestpro4 = f.txtcodestpro4.value;
    ls_codestpro5 = f.txtcodestpro5.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	ls_denestpro3 = f.txtdenestpro3.value;
	ls_denestpro4 = f.txtdenestpro4.value;
	ls_denestpro5 = f.txtdenestpro5.value;
	if ((ls_codestpro1!="")&&(ls_denestpro1!="")&&(ls_codestpro2!="")&&(ls_denestpro2!="")&&(ls_codestpro3!="")&&(ls_denestpro3!="")&&(ls_codestpro4!="")&&(ls_denestpro4!="")&&(ls_codestpro5==""))
	   {
    	 pagina="tepuy_cxp_cat_estpro5.php?submit=si&txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtcodestpro3="+ls_codestpro3+"&txtdenestpro3="+ls_denestpro3+"&txtcodestpro4="+ls_codestpro4+"&txtdenestpro4="+ls_denestpro4;
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	   }
	else
	   {
		 pagina="tepuy_cat_public_estpro.php?submit=no";
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	   }
}
</script>
</html>
