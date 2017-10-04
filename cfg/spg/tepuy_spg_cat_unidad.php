<?php
session_start();
$dat=$_SESSION["la_empresa"];
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("class_folder/tepuy_spg_c_unidad.php");
require_once("../../shared/class_folder/tepuy_c_seguridad.php");
$in           = new tepuy_include();
$con          = $in->uf_conectar();
$io_msg       = new class_mensajes();
$ds           = new class_datastore();
$SQL          = new class_sql($con);
$io_funcion   = new class_funciones(); 
$ls_codemp    = $dat["codemp"];
$li_estmodest = $dat["estmodest"];
$io_unidad    = new tepuy_spg_c_unidad($con);

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion  = $_POST["operacion"];
	$ls_codunieje  = $_POST["txtcodunieje"];
	$ls_denunieje  = $_POST["txtdenunieje"];
	$ls_codestpro1 = $_POST["txtcodestpro1"];
	$ls_codestpro2 = $_POST["txtcodestpro2"];
	$ls_codestpro3 = $_POST["txtcodestpro3"];
    if ($li_estmodest=='2')
	   {
	     $ls_codestpro4 = $_POST["txtcodestpro4"];
	     $ls_codestpro5 = $_POST["txtcodestpro5"];
	   }
     else
	   {
	     $ls_codestpro4 = '00';
	     $ls_codestpro5 = '00';
	   }
}
else
{
	$ls_operacion  = "";
	$ls_codestpro1 = "";
	$ls_codestpro2 = "";
	$ls_codestpro3 = "";
    $ls_codestpro4 = "";
	$ls_codestpro5 = "";
}

	if ($li_estmodest=='1')
	   {
	     $li_maxlenght_1 = '20';
	     $li_maxlenght_2 = '6';
	     $li_maxlenght_3 = '3';
	     $li_size        = '25';
	     $ls_ancho       = '45';
	     $ls_nomestpro4  = "";
	     $ls_nomestpro5  = "";
	     $ls_denestpro4  = "";
	     $ls_denestpro5  = "";
	   }
	else
	   {
	     $li_maxlenght_1 = '2';
	     $li_maxlenght_2 = '2';
	     $li_maxlenght_3 = '2';
	     $li_size        = '5';
	     $ls_ancho       = '65';
	   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Unidades Ejecutoras </title>
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
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"></p>
  	 <table width="564" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="22" colspan="2" class="titulo-celda"><input name="hidmaestro" type="hidden" id="hidmaestro" value="N">
        Cat&aacute;logo de Unidades Ejecutoras</td>
       </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td width="121" height="22"><div align="right">Codigo</div></td>
        <td width="441" height="22"><div align="left">
          <input name="txtcodunieje" type="text" id="txtcodunieje" maxlength="10" style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890');">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominacion</div></td>
        <td height="22"><div align="left">
          <input name="txtdenunieje" type="text" id="txtdenunieje" maxlength="100" style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $dat["nomestpro1"];?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro1" type="text" id="txtcodestpro1" size="<?php print $li_size ?>" style="text-align:center" readonly maxlength="<?php print $li_maxlength_1 ?>">
          <a href="javascript:catalogo_estpro1();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
          <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" size="<?php print $ls_ancho ?>" readonly style="text-align:left">          
        </div>       </td>
      </tr>
      <tr>
        <td height="22"><div align="right"> <?php print $dat["nomestpro2"];?></div>         </td>
        <td height="22"><div align="left">
          <input name="txtcodestpro2" type="text" id="txtcodestpro2" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_1 ?>" style="text-align:center" readonly>
          <a href="javascript:catalogo_estpro2();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
          <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" size="<?php print $ls_ancho ?>" readonly style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">    <?php print $dat["nomestpro3"];?></div>      </td>
        <td height="22"><div align="left">
          <input name="txtcodestpro3" type="text" id="txtcodestpro3" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_1 ?>" style="text-align:center" readonly>
          <a href="javascript:catalogo_estpro3();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
          <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" readonly size="<?php print $ls_ancho ?>" style="text-align:left">
        </div></td>
      </tr>
      <?
	   if ($li_estmodest=='2')
	      { ?>
       <tr>
        <td height="22"><div align="right"><?php print $dat["nomestpro4"]?></div></td>
        <td height="22"><div align="left">
          <label>
          <input name="txtcodestpro4" type="text" id="txtcodestpro4" maxlength="2" size="<?php print $li_size ?>" style="text-align:center" readonly>
          </label>
          <a href="javascript:catalogo_estpro4();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a> 
          <label>
          <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" size="<?php print $ls_ancho ?>" style="text-align:left" readonly>
          </label>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $dat["nomestpro5"]?></div></td>
        <td height="22"><div align="left">
          <label>
          <input name="txtcodestpro5" type="text" id="txtcodestpro5" maxlength="2" size="<?php print $li_size ?>" style="text-align:center" readonly>
          </label>
          <a href="javascript:catalogo_estpro5();"><img src="../../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a> 
          <label>
          <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" size="<?php print $ls_ancho ?>" style="text-align:left" readonly>
          </label>
        </div></td>
      </tr>	
	  <?
	  	  }
	  ?>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.png" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	 <div align="center"><br>
         <?php

print "<table width=564 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "<td>Emite Req.</td>";
print "<td>".$dat["nomestpro1"]."</td>";
print "<td>".$dat["nomestpro2"]."</td>";
print "<td>".$dat["nomestpro3"]."</td>";
if ($li_estmodest=='2')
   {
     print "<td>".$dat["nomestpro4"]."</td>";
     print "<td>".$dat["nomestpro5"]."</td>";
   }
print "</tr>";
if ($ls_operacion=="BUSCAR")
   { 
	 $ls_sql="SELECT coduniadm,denuniadm,estemireq,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,coduniadmsig             ".
	         "  FROM spg_unidadadministrativa                                                                                      ".
			 " WHERE codemp='".$ls_codemp."' AND coduniadm like '%".$ls_codunieje."%' AND denuniadm like '%".$ls_denunieje."%' AND ".
			 "       coduniadm<>'----------'";
	 if (!empty($ls_codestpro1)&&(!empty($ls_codestpro2))&&(!empty($ls_codestpro3)))
	    {
		  $ls_codestpro1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,20);
		  $ls_codestpro2 = $io_funcion->uf_cerosizquierda($ls_codestpro2,6);
		  $ls_codestpro3 = $io_funcion->uf_cerosizquierda($ls_codestpro3,3);
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
			$ls_codunieje  = $data["coduniadm"][$z];
			$ls_denunieje  = $data["denuniadm"][$z];
			$estreq        = $data["estemireq"][$z];
			$ls_codestpro1 = $data["codestpro1"][$z];
			$ls_codestpro2 = $data["codestpro2"][$z];
			$ls_codestpro3 = $data["codestpro3"][$z];
			$ls_codestpro4 = $data["codestpro4"][$z];
			$ls_codestpro5 = $data["codestpro5"][$z];
		    $ls_denestpro1 = $io_unidad->uf_load_nombre_estructura($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,1);
			$ls_denestpro2 = $io_unidad->uf_load_nombre_estructura($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,2);
			$ls_denestpro3 = $io_unidad->uf_load_nombre_estructura($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,3);
			$ls_denestpro4 = $io_unidad->uf_load_nombre_estructura($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,4);
			$ls_denestpro5 = $io_unidad->uf_load_nombre_estructura($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,5);
			if ($li_estmodest=='2')
			   {
			     $ls_codestpro1 = substr($ls_codestpro1,18,2);
			     $ls_codestpro2 = substr($ls_codestpro2,4,2);
			     $ls_codestpro3 = substr($ls_codestpro3,1,2);
			   }
			$ls_coduniadm = $data["coduniadmsig"][$z];
			print "<td align=center><a href=\"javascript: aceptar('$ls_codunieje','$ls_denunieje','$estreq','$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5','$ls_coduniadm');\">".$ls_codunieje."</a></td>";
			print "<td>".$ls_denunieje."</td>";
			print "<td align=center>".$estreq."</td>";
			print "<td align=center>".$ls_codestpro1."</td>";
			print "<td align=center>".$ls_codestpro2."</td>";
			print "<td align=center>".$ls_codestpro3."</td>";
			if ($li_estmodest=='2')
			   {
			     print "<td align=center>".$ls_codestpro4."</td>";
			     print "<td align=center>".$ls_codestpro5."</td>";
			   }
			print "</tr>";			
		}
	}
	else
	{
		$io_msg->message("No se han definido Unidades Ejecutoras !!!");		
	}
}
print "</table>";
?>
       </div>
     </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
fop = opener.document.form1;
f   = document.form1;

  function aceptar(ls_codunieje,ls_denunieje,estreq,ls_codestpro1,ls_denestpro1,ls_codestpro2,ls_denestpro2,ls_codestpro3,ls_denestpro3,ls_codestpro4,ls_denestpro4,ls_codestpro5,ls_denestpro5,ls_coduniadm)
  {
    fop.txtcodunieje.value = ls_codunieje;
    fop.txtdenunieje.value = ls_denunieje;
	if (estreq==1)
	   {
		 fop.estreq.checked=true;
	   }
	else
	   {
		 fop.estreq.checked=false;
	   }
	fop.txtcodestpro1.value = ls_codestpro1;
	fop.txtdenestpro1.value = ls_denestpro1;
	fop.txtcodestpro2.value = ls_codestpro2;
	fop.txtdenestpro2.value = ls_denestpro2;
	fop.txtcodestpro3.value = ls_codestpro3;
	fop.txtdenestpro3.value = ls_denestpro3;
	li_estmodest            = "<?php print $li_estmodest ?>";
	if (li_estmodest=='2')
	   {
	     fop.txtcodestpro4.value = ls_codestpro4;
	     fop.txtdenestpro4.value = ls_denestpro4;
		 fop.txtcodestpro5.value = ls_codestpro5;
	   	 fop.txtdenestpro5.value = ls_denestpro5;
	   }
	fop.status.value          ='C';
	fop.txtcoduniadm.value    = ls_coduniadm;
	fop.txtcodunieje.readOnly = true;
	close();
  }
  
function ue_search()
{
	f.operacion.value="BUSCAR";
	f.action="tepuy_spg_cat_unidad.php";
	f.submit();
}
  
function catalogo_estpro1()
{
	pagina="tepuy_spg_cat_estpro1.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function catalogo_estpro2()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	if((ls_codestpro1!="")&&(ls_denestpro1!=""))
	{
		pagina="tepuy_spg_cat_estpro2.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		     alert('Debe seleccionar una Estructura de Nivel 1 !!!');
	}
}

function catalogo_estpro3()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	li_estmodest  = "<?php print $li_estmodest ?>";
	if((ls_codestpro1!="")&&(ls_denestpro1!="")&&(ls_codestpro2!="")&&(ls_denestpro2!=""))
	{
		pagina="tepuy_spg_cat_estpro3.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,resizable=yes,location=no");
	}
	else
	{
		if (li_estmodest=='2')
		   {
		     alert('Debe seleccionar una Estructura de Nivel 2 !!!');
		   }
		else
		   {
		     pagina="tepuy_cat_public_estpro.php";
		     window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		   }
	}
}

function catalogo_estpro4()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	ls_denestpro3 = f.txtdenestpro3.value;
	if ((ls_codestpro1!="")&&(ls_denestpro1!="")&&(ls_codestpro2!="")&&(ls_denestpro2!="")&&(ls_codestpro3!="")&&(ls_denestpro3!=""))
	   {
    	 pagina="tepuy_spg_cat_estpro4.php?submit=si&txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtcodestpro3="+ls_codestpro3+"&txtdenestpro3="+ls_denestpro3;
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	   }
    else
	   {
	     alert("Debe seleccionar una Estructura de Nivel 3 !!!");
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
    	 pagina="tepuy_spg_cat_estpro5.php?submit=si&txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtcodestpro3="+ls_codestpro3+"&txtdenestpro3="+ls_denestpro3+"&txtcodestpro4="+ls_codestpro4+"&txtdenestpro4="+ls_denestpro4;
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