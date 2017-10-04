<?php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../tepuy_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_ano=substr($ls_cargorep = $_SESSION["la_empresa"]["periodo"],0,4);//date('Y');
	$ls_mes=date('m');
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_sfa.php");
	$io_fun_sfa=new class_funciones_sfa();
	require_once("../shared/class_folder/tepuy_include.php");
	$io_include=new tepuy_include();
	$io_conexion=$io_include->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($io_conexion);
	require_once("class_folder/tepuy_sfa_c_cmp_retencion.php");
	$io_retencion=new tepuy_sfa_c_cmp_retencion("../");	

	$io_fun_sfa->uf_load_seguridad("SFA","tepuy_sfa_d_editcmpreten.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Comprobantes de Retenciones</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<!--<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sfa.js"></script> -->

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<!--<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
--><script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script language="javascript" src="js/funcion_sfa.js"></script>
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
</style>
</head>
<body>
<form name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_sfa->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_sfa);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<br>
	<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-celda">
      <td height="22" colspan="4" align="center">Cat&aacute;logo de Comprobantes de Retenciones </td>
      </tr>
    <tr>
      <td height="13" align="center">&nbsp;</td>
      <td height="13" align="center">&nbsp;</td>
      <td height="13" align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" align="center"><div align="right">Mes</div></td>
      <td width="208" height="22" align="center"><div align="left">
        <label>
        <select name="cmbmes" id="cmbmes">
          <option value="01" <?php if($ls_mes=="01"){ print "selected";} ?>>ENERO</option>
          <option value="02" <?php if($ls_mes=="02"){ print "selected";} ?>>FEBRERO</option>
          <option value="03" <?php if($ls_mes=="03"){ print "selected";} ?>>MARZO</option>
          <option value="04" <?php if($ls_mes=="04"){ print "selected";} ?>>ABRIL</option>
          <option value="05" <?php if($ls_mes=="05"){ print "selected";} ?>>MAYO</option>
          <option value="06" <?php if($ls_mes=="06"){ print "selected";} ?>>JUNIO</option>
          <option value="07" <?php if($ls_mes=="07"){ print "selected";} ?>>JULIO</option>
          <option value="08" <?php if($ls_mes=="08"){ print "selected";} ?>>AGOSTO</option>
          <option value="09" <?php if($ls_mes=="09"){ print "selected";} ?>>SEPTIEMBRE</option>
          <option value="10" <?php if($ls_mes=="10"){ print "selected";} ?>>OCTUBRE</option>
          <option value="11" <?php if($ls_mes=="11"){ print "selected";} ?>>NOVIEMBRE</option>
          <option value="12" <?php if($ls_mes=="12"){ print "selected";} ?>>DICIEMBRE</option>
        </select>
        </label>
</div></td>
      <td width="66" height="22" align="center"><div align="right">A&ntilde;o</div></td>
      <td width="182" align="center"><div align="left">
        <label>
        <input name="txtano" type="text" id="txtano" value="<?php print $ls_ano;?>" size="6" maxlength="4" readonly>
        </label>
</div></td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center"><table width="511" border="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="199" height="22">&nbsp;</td>
          <td width="89" height="22"><div align="center">
                <input name="rdproben" type="radio" class="sin-borde" value="radiobutton" onClick="javascript: ue_limpiarproben();" checked>
            Proveedor</div></td>
          <td width="215" height="22"><div align="left">
            <input name="rdproben" type="radio" class="sin-borde" value="radiobutton" onClick="javascript: ue_limpiarproben();">
          Beneficiario</div></td>
        </tr>
        <tr>
	<?php $io_retencion->uf_cmb_retencion($ls_tiporeten); //Combo que contiene los meses del a�o y retorna la selecciona ?>
     <!--     <td height="22"><div align="right">Tipo</div></td>
          <td colspan="2"><label>
            <select name="tiporeten" size="1" id="tiporeten">
              <option value="01" selected="selected">IVA</option>
              <option value="03">I.S.A.E.</option>
              <option value="02">I.S.L.R.</option>
              <option value="04">Timbre Fiscal</option>
              <option value="05">Aporte Social</option> 
            </select> -->
          </label></td>
          </tr>
<!--            <select name="tiporeten1" size="1" id="tiporeten1">
<?php
	$ls_sql="SELECT codcmp, nom  FROM cxp_contador ORDER BY codcmp  ASC ";	
	$rs_data=$io_sql->select($ls_sql);
	if($rs_data===true)
	{
		while($row=$io_sql->fetch_row($rs_data))
			{
			$ls_codcmp=$row["codcmp"];
			$ls_nom=$row["nom"];
			print "<option value='".$ls_codcmp."-".$ls_nom.">".$ls_nom." - "."</option>";
			}
		$io_sql->free_result($rs_data);	
		print "</select>";
	}	
?>
           </select> -->
        <tr>
          <td height="22"><div align="right">Desde
            <input name="txtcoddes" type="text" id="txtcoddes" size="20" readonly>
              <a href="javascript: ue_catalogo_proben('REPDES');"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a></div></td>
          <td><div align="right">Hasta</div></td>
          <td><input name="txtcodhas" type="text" id="txtcodhas" size="20" readonly>
            <a href="javascript: ue_catalogo_proben('REPHAS');"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Solicitud
              <input name="txtnumsoldes" type="text" id="txtnumsoldes" size="20">
              <a href="javascript: ue_catalogo_solicitud();"><img src="../shared/imagebank/tools15/buscar.png" width="15" height="15" border="0"></a></div></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>

      </table></td>
    </tr>
    <tr>
      <td height="33" colspan="4" align="center">      <div align="left">
        <table width="511" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="22" colspan="5"><strong>Rango de Fecha </strong></td>
            </tr>
          <tr>
            <td width="136"><div align="right">Desde</div></td>
            <td width="101"><input name="txtfecdes" type="text" id="txtfecdes"  onKeyPress="ue_formatofecha(this,'/',patron,true);" size="15" maxlength="10"  datepicker="true"></td>
            <td width="42"><div align="right">Hasta</div></td>
            <td width="129"><div align="left">
                <input name="txtfechas" type="text" id="txtfechas"  onKeyPress="ue_formatofecha(this,'/',patron,true);" size="15" maxlength="10"  datepicker="true">
            </div></td>
            <td width="101">&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center"><div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.png" width="20" height="20" border="0">Buscar Documentos</a></div></td>
    </tr>
    <br>
	<tr>
	 <td colspan="4" align="center">
  		<div id="resultados" align="center"></div>	</td>
    </tr>
  </table>
	<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
</form>      
</body>
<script language="JavaScript">
var patron = new Array(2,2,4)
function ue_limpiarproben()
{
	f=document.formulario;
	f.txtcoddes.value="";
	f.txtcodhas.value="";
}

function ue_catalogo_proben(ls_tipo)
{
	f=document.formulario;
	valido=true;		
	
	if(f.rdproben[0].checked)
	{
		ls_catalogo="tepuy_sfa_cat_proveedor.php?tipo="+ls_tipo+"";
	}
	if(f.rdproben[1].checked)
	{
		ls_catalogo="tepuy_sfa_cat_beneficiario.php?tipo="+ls_tipo+"";
	}
	if(valido)
	{		
		window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("Debe indicar si es Proveedor � Beneficiario");
	}
}

function ue_catalogo_solicitud()
{
	tipo="REPDES";
	window.open("tepuy_sfa_cat_solicitudpago.php?tipo="+tipo+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	if(f.rdproben[0].checked)
	{
		tipproben="P";
	}
	else
	{
		if(f.rdproben[1].checked)
		{
			tipproben="B";
		}
		
	}
	
	codprobendes=f.txtcoddes.value;
	codprobenhas=f.txtcodhas.value;
	fecdes=f.txtfecdes.value;
	fechas=f.txtfechas.value;
	numsol=f.txtnumsoldes.value;
	mes=f.cmbmes.value;
	anio=f.txtano.value;
	tipo=f.tiporeten.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde est�n los m�todos para buscar y pintar los resultados
	ajax.open("POST","class_folder/tepuy_sfa_c_catalogo_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText
				}
				else
				{
					if(ajax.status==404)
					{
						divgrid.innerHTML = "La p�gina no existe";
					}
					else
					{//mostramos el posible error     
						divgrid.innerHTML = "Error:".ajax.status;
					}
				}
				
			}
		}
	}	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("catalogo=RETENCIONIVA&tipproben="+tipproben+"&codprobendes="+codprobendes+"&codprobenhas="+codprobenhas+
			  "&fecdes="+fecdes+"&fechas="+fechas+"&mes="+mes+"&anio="+anio+"&tipo="+tipo+"&numsol="+numsol);
}

function ue_cerrar()
{
	window.location.href="tepuywindow_blank.php";
}

function ue_aceptar(ls_numcom,ls_anofiscal,ls_mesfiscal,ls_codsujret,ls_nomsujret,ls_dirsujret,ls_rifsujret,ls_codret,ls_estcmpret)
{
	if(f.rdproben[0].checked)
	{
		ls_probene="P";
	}
	else
	{
		if(f.rdproben[1].checked)
		{
			ls_probene="B";
		}
	}
//	opener.document.formulario.txtestatus.value = ls_estcmpret;
	opener.ue_cargardatos(ls_numcom,ls_anofiscal,ls_mesfiscal,ls_codsujret,ls_nomsujret,  ls_dirsujret,ls_rifsujret,ls_codret,ls_probene,ls_estcmpret);
	parametros="";
	parametros=parametros+"&numcom="+ls_numcom+"&codret="+ls_codret;
	proceso="LOADDETALLECMP";
	if(parametros!="")
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("detalles");
		divlocal = document.getElementById("resultados");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde est�n los m�todos para buscar y pintar los resultados
		ajax.open("POST","class_folder/tepuy_sfa_c_modcmpret_ajax.php",true);
 		ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText
					close();
				}
				else
				{
					if(ajax.status==404)
					{
						divgrid.innerHTML = "La p�gina no existe";
					}
					else
					{//mostramos el posible error     
						divgrid.innerHTML = "Error:".ajax.status;
					}
				}
			}
		}
	}	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("proceso="+proceso+""+parametros);
	}
}
</script>
</html>
