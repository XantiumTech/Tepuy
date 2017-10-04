<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Registro de Detalle Cargos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<body >
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="numsol">
<input name="orden" type="hidden" id="orden" value="ASC">
<table width="780" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td><div align="center"><img src="../shared/imagebank/tools15/aprobado.png" width="15" height="15"><a href="javascript: uf_procesar();">Procesar Operaci&oacute;n</a> </div></td>
  </tr>
  <tr>
    <td><div id="resultados" align="center"></div></td>
  </tr>
</table>
	
	</p>
    
    <p><br>    
  </p>
</form>  
</body>
<script language="JavaScript">
function uf_procesar(ls_codemp,ls_numrecdoc,ls_codtipdoc,ls_dentipdoc,ls_tipproben,ls_codpro,ls_cedbene)
{
	f=opener.document.formulario;
	li_total=document.formulario.totalrows.value;
	li_opener=f.numrowsprenota.value;
	ls_campos="";
	li_selected=0;
	ls_ctaprov=f.txtcuentaprov.value;
	ls_denctascg=f.txtdenctascg.value;
	ls_numrecdoc=f.txtnumrecdoc.value;
	ls_codproben   =f.txtcodproben.value;
	if(f.tipproben[0].checked)
	{
	    ls_tipproben='P';
	}
	else
	{
		ls_tipproben='B';
	}	
	ls_codtipdoc=f.txttipdoc.value;	
	if(f.tiponota[0].checked)
	{
	    ls_tiponota='NC';
	}
	else
	{
		ls_tiponota='ND';
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Obtengo los registros ya existentes en el grid de detalle presupuestario
	li_registros=0;
	for(li=1;li<=li_opener;li++)
	{
		ls_cuentaspg=eval("f.txtcuentaspgncnd"+li+".value");
		ls_codestpro=eval("f.txtcodestproncnd"+li+".value");
		ls_cuentascg=eval("f.txtscgcuentadt"+li+".value");
		ls_dencuentascg=eval("f.txtdenscgcuentadt"+li+".value");
		ls_dencuentaspg=eval("f.txtdencuentancnd"+li+".value");
		ls_estcargo=eval("f.txtestcargo"+li+".value");
		ldec_monto=eval("f.txtmontoncnd"+li+".value");
		if(ls_cuentaspg!="" && ls_codestpro!="" && ldec_monto!="0,00" && ls_estcargo!='C')
		{
			li_registros++;					
			ls_campos=ls_campos+"&txtspgcuenta"+li_registros+"="+ls_cuentaspg+"&txtcodestpro"+li_registros+"="+ls_codestpro+"&txtscgcuenta"+li_registros+"="+ls_cuentascg+
						  "&txtdenscgcuentadt"+li_registros+"="+ls_dencuentascg+"&txtdencuenta"+li_registros+"="+ls_dencuentaspg+"&txtestcargo"+li_registros+"="+ls_estcargo+"&txtmonto"+li_registros+"="+ldec_monto;		
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Obtengo los valores seleccionados del catalogo para enviarlo por POST
	for(j=1;j<=li_total;j++)
	{
		if(eval("document.formulario.chk"+j+".checked"))
		{
			
			lb_existe=false;
			cuenta=eval("document.formulario.txtspgcuenta"+j+".value");
			codestpro=eval("document.formulario.txtcodestpro"+j+".value");
			dencuenta=eval("document.formulario.txtdencuenta"+j+".value");
			monto=eval("document.formulario.txtmonto"+j+".value");
			scg_cuenta=eval("document.formulario.txtscgcuenta"+j+".value");
			den_scgcuenta=eval("document.formulario.txtdenscgcuenta"+j+".value");
			for(k=1;k<=li_opener;k++)
			{
				ls_cuenta_op=eval("f.txtcuentaspgncnd"+k+".value");
				ls_codestpro_op=eval("f.txtcodestproncnd"+k+".value");
				if(ls_cuenta_op==cuenta && ls_codestpro_op==codestpro)
				{
					lb_existe=true;
					break;					
				}
			}
			if(lb_existe==false)
			{
				li_registros++;	
				li_selected=li_registros;
				ls_campos=ls_campos+"&txtspgcuenta"+li_selected+"="+cuenta+"&txtcodestpro"+li_selected+"="+codestpro+
						   "&txtdencuenta"+li_selected+"="+dencuenta+"&txtmonto"+li_selected+"="+monto+"&txtscgcuenta"+li_selected+"="+scg_cuenta+"&txtdenscgcuentadt"+li_selected+"="+den_scgcuenta+"&txtestcargo"+li_selected+"=C";					   
			}
			else
			{
				alert("La cuenta "+cuenta+" asociada a la estructura "+codestpro+" ya existe en el detalle de la nota");
			}		   
		}
	}
	ls_campos=ls_campos+"&txtctaprov="+ls_ctaprov+"&denctascg="+ls_denctascg+"&tiponota="+ls_tiponota+"&selected="+li_selected+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben+"&numrecdoc="+ls_numrecdoc+"&codtipdoc="+ls_codtipdoc;	
	if(li_selected>0)
		{
		// Cargamos las variables para pasarlas al AJAX
		divgrid = opener.document.getElementById('detallesnota');
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde est�n los m�todos para buscar y pintar los resultados
		ajax.open("POST","class_folder/tepuy_cxp_c_ncnd_ajax.php",true);
		
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1)
			{
				divgrid.innerHTML = "<img src='../shared/imagebank/cargando.gif' width='32' height='32'>";//<-- aqui iria la precarga en AJAX 
			}
			else
			{
				if(ajax.readyState==4)
				{
					if(ajax.status==200)
					{//mostramos los datos dentro del contenedor
						divgrid.innerHTML = ajax.responseText;
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
		ajax.send("funcion=AGREGARDTNOTAPRE"+ls_campos);
	}	

}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	ls_numord="<?php print $_GET["numord"]?>";
	ls_tipo="<?php print $_GET["tipproben"]?>";
	ls_codproben="<?php print $_GET["codproben"]?>";
	ls_numrecdoc="<?php print $_GET["numrecdoc"]?>";
	ls_numncnd="<?php print $_GET["numncnd"]?>";
	ls_codtipdoc="<?php print $_GET["codtipdoc"]?>";
	ls_tiponota="<?php print $_GET["tiponota"]?>";
	ldec_montodoc="<?php print $_GET["montodoc"]?>";
	//ls_nomproben="";
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde est�n los m�todos para buscar y pintar los resultados
	ajax.open("POST","class_folder/tepuy_cxp_c_catalogo_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='../shared/imagebank/cargando.gif' width='32' height='32'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText;
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
	ajax.send("catalogo=DTCARGOS&numord="+ls_numord+"&tipproben="+ls_tipo+"&codproben="+ls_codproben+"&numrecdoc="+ls_numrecdoc+"&codtipdoc="+ls_codtipdoc+"&tiponota="+ls_tiponota+"&montodoc="+ldec_montodoc+
			  "&orden="+orden+"&campoorden="+campoorden);
}

function ue_calcular(a)
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	// Cargamos las variables para pasarlas al AJAX
	ls_numord="<?php print $_GET["numord"]?>";
	ls_tipo="<?php print $_GET["tipproben"]?>";
	ls_codproben="<?php print $_GET["codproben"]?>";
	ls_numrecdoc="<?php print $_GET["numrecdoc"]?>";
	ls_numncnd="<?php print $_GET["numncnd"]?>";
	ls_codtipdoc="<?php print $_GET["codtipdoc"]?>";
	ls_tiponota="<?php print $_GET["tiponota"]?>";
	ldec_montodoc=parseFloat(uf_convertir_monto("<?php print $_GET["montodoc"]?>"));
	ldec_baseimp=parseFloat(uf_convertir_monto(eval("f.txtbaseimp"+a+".value")));
	
	if(ldec_montodoc>=ldec_baseimp)
	{	
		//ls_nomproben="";
		orden=f.orden.value;
		campoorden=f.campoorden.value;
		// Div donde se van a cargar los resultados
		divgrid = document.getElementById('resultados');
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		li_totalrows=f.totalrows.value;
		ls_campos="";
		for(li=1;li<=li_totalrows;li++)
		{
			if(eval("f.chk"+li+".checked"))
			{
				ls_campos=ls_campos+"&chk"+li+"=1";
			}
			else
			{
				ls_campos=ls_campos+"&chk"+li+"=0";
			}
			ls_codestpro=eval("f.txtcodestpro"+li+".value");
			ls_formula=eval("f.txtformula"+li+".value");
			ls_spgcuenta=eval("f.txtspgcuenta"+li+".value");
			ls_scgcuenta=eval("f.txtscgcuenta"+li+".value");
			ls_denscg=eval("f.txtdenscgcuenta"+li+".value");
			ldec_baseimp=eval("f.txtbaseimp"+li+".value");
			ldec_monto=eval("f.txtmonto"+li+".value");
			ls_dencuenta=eval("f.txtdencuenta"+li+".value");			
			ls_campos=ls_campos+"&txtcodestpro"+li+"="+ls_codestpro+"&txtformula"+li+"="+ls_formula+"&txtspgcuenta"+li+"="+ls_spgcuenta+
					 "&txtscgcuenta"+li+"="+ls_scgcuenta+"&txtdenscgcuenta"+li+"="+ls_denscg+"&txtbaseimp"+li+"="+ldec_baseimp+
					 "&txtmonto"+li+"="+ldec_monto+"&txtdencuenta"+li+"="+ls_dencuenta
	
		}
		// Pagina donde est�n los m�todos para buscar y pintar los resultados
		ajax.open("POST","class_folder/tepuy_cxp_c_catalogo_ajax.php",true);
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1)
			{
				divgrid.innerHTML = "<img src='../shared/imagebank/cargando.gif' width='32' height='32'>";//<-- aqui iria la precarga en AJAX 
			}
			else
			{
				if(ajax.readyState==4)
				{
					if(ajax.status==200)
					{//mostramos los datos dentro del contenedor
						divgrid.innerHTML = ajax.responseText;
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
		ajax.send("catalogo=CALCULARCARGO&numord="+ls_numord+"&tipproben="+ls_tipo+"&codproben="+ls_codproben+"&numrecdoc="+ls_numrecdoc+"&codtipdoc="+ls_codtipdoc+"&tiponota="+ls_tiponota+
				  "&orden="+orden+"&campoorden="+campoorden+ls_campos+"&total="+li_totalrows);
	}	
	else
	{
		eval("document.formulario.txtbaseimp"+a+".value='0,00'");
		alert("La Base Imponible no debe ser mayor al Monto de la Nota");
	}
}

function uf_format(obj,lb_baseimp,a)
{
	ldec_monto=uf_convertir(obj.value);
	obj.value=ldec_monto;
	if(lb_baseimp)
	{
		if(eval("document.formulario.chk"+a+".checked"))
		{
			ue_calcular(a);
		}
		ldec_montodoc=parseFloat(uf_convertir_monto("<?php print $_GET["montodoc"]?>"));
		ldec_baseimp=parseFloat(uf_convertir_monto(eval("document.formulario.txtbaseimp"+a+".value")));		
		if(ldec_montodoc>=ldec_baseimp)
		{
			
		}
		else
		{
			eval("document.formulario.txtbaseimp"+a+".value='"+"<?php print $_GET["montodoc"]?>"+"'");
			alert("La Base Imponible no debe ser mayor al Monto de la Nota");
		}
	}
}

function ue_close()
{
	close();
}
ue_search();
</script>
</html>