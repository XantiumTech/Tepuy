<?php
session_start();
	if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}
	else
	{
	  $ls_ejecucion="";
	}
	
$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
$li_ano=substr($ldt_periodo,0,4);
$ldt_fecdes="01/01/".$li_ano;
$ldt_fechas=date("d/m/Y");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Asignaci&oacute;n de Personas a Concurso</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/tepuy_srh_js_cat_asignar_concurso.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/tepuy_srh_js_asignar_concurso.js"></script>


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
     <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print trim($ls_ejecucion)?>">
   <input name="hidcontrol" type="hidden" id="hidcontrol" value="1">
    
</p>
  <table width="533" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="529" colspan="2" class="titulo-celda">Cat&aacute;logo de Asignaci&oacute;n de Personas a Concurso </td>
    </tr>
  </table>
<br>
    <table width="533" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td width="158"><div align="right">Nro. Registro</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtnroreg" type="text" size="16" id="txtnroreg" onKeyUp="javascript:ue_validarnumero(this);" >
        </div></td>
      </tr>
      
        <tr class="formato-blanco"> 
		 <td height="28"><div align="right">Fecha Asignaci&oacute;n Desde</div></td>
		  <td height="28" colspan="2" valign="middle"><input name="txtfechades" type="text" id="txtfechades"  onChange="javascript: Buscar()" value="<?php print ($ldt_fecdes)?>" size="16"   style="text-align:center" readonly >
		  <input name="reset" type="reset" onclick="return showCalendar('txtfechades', '%d/%m/%Y');" value=" ... " /> </td>
		  </tr>
		   <tr>
		   <tr class="formato-blanco"> 
		 <td height="28"><div align="right">Fecha Asignaci&oacute;n Hasta</div></td>
		  <td height="28" colspan="2" valign="middle"><input name="txtfechahas" type="text" id="txtfechahas"  onChange="javascript: Buscar()"   value="<?php print ($ldt_fechas)?>" size="16"   style="text-align:center" readonly >
		  <input name="reset" type="reset" onclick="return showCalendar('txtfechahas', '%d/%m/%Y');" value=" ... " /> </td>
		  </tr>
		   <tr>
        <td><div align="right">C&oacute;digo Concurso</div></td>
        <td width="135" height="22"><div align="left">          <input name="txtcodcon" size="16" type="text" id="txtcodcon" readonly>
        <a href="javascript:catalogo_concurso();"><img src="../../../../shared/imagebank/tools15/buscar.png" alt="Cat&aacute;logo Concurso" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
      </div></td>
        <td colspan="3"><input name="txtdescon" type="text" class="sin-borde" id="txtdescon"  size="40" maxlength="40" readonly>  </td>
   
      </tr>
   
    <tr>
         <td><input name="txtfechas2" type="hidden" id="txtfechas2"  value="<?php print ($ldt_fechas)?>" readonly >
		    <input name="txtfecdes2" type="hidden" id="txtfecdes2"  value="<?php print ($ldt_fecdes)?>" readonly >
			<input name="txttipo" type="hidden" id="txttipo" size="2" maxlength="2" value="M" readonly>
			<input name="hidcontrol2" type="hidden" id="hidcontrol2" value="2">
			<input name="hidcontrolcar" type="hidden" id="hidcontrolcar" value="1">
            <input name="hidcontrol" type="hidden" id="hidcontrol" value="">
		</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
	  <td>&nbsp;</td>
	
       <td width="135"><div align="right"><a href="javascript: Limpiar_busqueda(); ">  <img src="../../../public/imagenes/nuevo.png" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td>
       <td width="240"><div align="center"><a href="javascript: Buscar()"><img src="../../../../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0">Haga click aqu&iacute; para Buscar</a></div></td>
       
      </tr>
  </table>

  <div align="center" id="mostrar" class="oculto1"></div>
    <table width="533" border="0" cellpadding="0" cellspacing="0" class="fondo-tabla" align="center">
      <tr>
        <td width="533" bgcolor="#EBEBEB">&nbsp;</td>
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
