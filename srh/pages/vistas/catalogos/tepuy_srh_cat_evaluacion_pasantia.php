<?php
session_start();
	if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}
	else 
	{ $ls_ejecucion="";	}
	
$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
$li_ano=substr($ldt_periodo,0,4);
$ldt_fecdes="01/01/".$li_ano;
$ldt_fechas=date("d/m/Y");
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Evaluaci&oacute;n de Pasant&iacute;as</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/tepuy_srh_js_cat_evaluacion_pasantia.js"></script>



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

</head>

<body onLoad="doOnLoad()">
<form name="form1" method="post" action="">
  <p align="center">
   
    <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_ejecucion?>">
   </p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Evaluaci&oacute;n de Pasant&iacute;as </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td width="157"><div align="right">N&uacute;mero Pasant&iacute;a</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtnropas" type="text" id="txtnropas" size="16" onKeyUp="javascript:ue_validarnumero(this);" >
        </div></td>
      </tr>
   <tr>
        <td><div align="right">Cedula Pasante</div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtcedpas" type="text" id="txtcedpas" size=16  onKeyUp="javascript:ue_validarnumero(this);" >
        </div></td>
      </tr>

  </tr>
   <tr>
        <td><div align="right">Nombre Pasante</div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtnompas" type="text" id="txtnompas" size=40  >
        </div></td>
      </tr>
 
  </tr>
   <tr class="formato-blanco"> 
  <td><div align="right">Apellido Pasante</div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtapepas" type="text" id="txtapepas" size=40 >
        </div></td>
     
  </tr>
     <tr class="formato-blanco">
    <td height="28"><div align="right">Fecha Evaluaci&oacute;n Desde</div></td>
    <td colspan="2" height="28" valign="middle"><input name="txtfecevaldes" type="text" id="txtfecevaldes"  size="16" value="<?php print ($ldt_fecdes)?>"  style="text-align:center"  readonly >
        <input name="reset" type="reset" onClick="return showCalendar('txtfecevaldes', '%d/%m/%Y');" value=" ... " />
    </td> </tr>
	<tr>
	  <tr class="formato-blanco">
    <td height="28"><div align="right">Fecha Evaluaci&oacute;n Hasta</div></td>
    <td colspan="2" height="28" valign="middle"><input name="txtfecevalhas" type="text" id="txtfecevalhas"  size="16" value= "<?php print ($ldt_fechas)?>" style="text-align:center"  readonly >
        <input name="reset" type="reset" onClick="return showCalendar('txtfecevalhas', '%d/%m/%Y');" value=" ... " />
    </td> </tr>
	<tr>
        <td><input name="txtfechahas2" type="hidden" id="txtfechahas2"  value="<?php print ($ldt_fechas)?>" readonly >
		    <input name="txtfechades2" type="hidden" id="txtfechades2"  value="<?php print ($ldt_fecdes)?>" readonly >
		</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
	  <td>&nbsp;</td>
	  
       <td width="144"><div align="right"><a href="javascript: Limpiar_busqueda(); ">  <img src="../../../public/imagenes/nuevo.png" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td>
       <td width="199"><div align="right"><a href="javascript: Buscar()"><img src="../../../../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0">Haga click aqu&iacute; para Buscar</a></div></td>
       
      </tr>
    </table>

 <div align="center" id="mostrar" class="oculto1"></div>  
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="fondo-tabla" align="center">
      
      <tr>
        <td bgcolor="#EBEBEB">&nbsp;</td>
      </tr>
       <tr>
        <td bgcolor="#EBEBEB">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="center" bgcolor="#EBEBEB"><div id="gridbox" align="center" width="500" height="800" style="background-position:center"></div></td>
      </tr>
	  
    </table>
	
 
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>

</html>