<?php
session_start();
	if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}
	else
	{ $ls_ejecucion="";}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Accidentes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/tepuy_srh_js_cat_accidentes.js"></script>



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

<body onload="doOnLoad()">
<form name="form1" method="post" action="">
  <p align="center">
    <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_ejecucion ?>">    
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Accidentes</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td width="114"><div align="right">N&uacute;mero</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtnroreg" type="text" id="txtnroreg" size="16" onkeyup="javascript: ue_validarnumero(this);" >
        </div></td>
      </tr>
      
      
        <tr class="formato-blanco"> 
    <tr>
        <td><div align="right">C&oacute;digo Personal</div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtcodper" type="text" id="txtcodper" size=16  onKeyUp="javascript:  ue_validarnumero(this);" >
        </div></td>
      </tr>

  </tr>
   <tr>
        <td><div align="right">Nombre</div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtnomper" type="text" id="txtnomper" size=40 >
        </div></td>
      </tr>
 
  </tr>
   <tr class="formato-blanco"> 
  <td><div align="right">Apellido</div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtapeper" type="text" id="txtapeper" size=40  >
        </div></td>
         <td width="8">&nbsp;</td>
  </tr>
    
      <tr>
        <td>&nbsp;</td>
            </tr>
		 <tr>
	  <td>&nbsp;</td>
	
       <td width="167"><div align="right"><a href="javascript: Limpiar_busqueda(); ">  <img src="../../../public/imagenes/nuevo.png" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td>
       <td width="211"><div align="right"><a href="javascript: Buscar()"><img src="../../../../shared/imagebank/tools15/buscar.png" alt="Buscar" width="15" height="15" border="0">Haga click aqu&iacute; para Buscar</a></div></td>
       
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
        <td align="center" bgcolor="#EBEBEB"><div id="gridbox" align="center" width="500" height="600" style="background-position:center"></div></td>
      </tr>
	  
    </table>
	
 
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>

