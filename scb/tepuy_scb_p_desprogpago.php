<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../tepuy_inicio_sesion.php'";
		print "</script>";		
	}
	$dat=$_SESSION["la_empresa"];	

$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","tepuy_scb_p_desprogpago.php",$ls_permisos,$la_seguridad,$la_permisos);
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Desprogramacion de Pagos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
</head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" alt="Encabezado" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="12" bgcolor="#E7E7E7"><table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Caja y Banco</td>
            <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
      <tr>
        <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
        <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table></td>
  </tr>
<!-- DO NOT MOVE! The following AllWebMenus linking code section must always be placed right AFTER the BODY tag-->
<!-- ******** BEGIN ALLWEBMENUS CODE FOR menu ******** -->
<script type="text/javascript">var MenuLinkedBy="AllWebMenus [4]",awmMenuName="menu",awmBN="848";awmAltUrl="";</script><script charset="UTF-8" src="js/menu.js" type="text/javascript"></script><script type="text/javascript">awmBuildMenu();</script>
<!-- ******** END ALLWEBMENUS CODE FOR menu ******** -->
<!--  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr> -->
  <tr>
    <td height="42" class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.png" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.png" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.png" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.png" alt="Ayuda" title="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
    require_once("../shared/class_folder/tepuy_include.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("../shared/class_folder/class_datastore.php");
	$msg		= new class_mensajes();	
	$fun		= new class_funciones();	
	$lb_guardar = true;
    $sig_inc    = new tepuy_include();
    $con		= $sig_inc->uf_conectar();
	$io_grid	= new grid_param();
	$ds_sol		= new class_datastore();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];

	require_once("tepuy_scb_c_desprogpago.php");
	$io_sol=new tepuy_scb_c_desprogpago($la_seguridad);

	if( array_key_exists("operacion",$_POST))//Cuando aplicamos alguna operacion 
	{
		$ls_operacion= $_POST["operacion"];
		$ls_tipo=$_POST["rb_provbene"];
		
	}
	else//Caso de apertura de la pagina o carga inicial
	{
		$ls_operacion= "NUEVO" ;
		$ls_tipo='-';
		$ls_desproben="Ninguno";
		$i=1;
		$object[$i][1] = "<input type=checkbox name=chksel".$i."  id=chksel".$i." value=1 >";		
		$object[$i][2] = "<input type=text name=txtnumsol".$i."   value=''      class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";
		$object[$i][3] = "<input type=text name=txtmonsol".$i."   value='".number_format(0,2,",",".")."'    class=sin-borde readonly style=text-align:right size=20 maxlength=22 >";
		$object[$i][4] = "<input name=txtcodproben".$i." type=hidden id=txtcodproben".$i." value=''><input type=text name=txtnomprovbene".$i." value=''    class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
		//$object[$i][5] = "<input type=text name=txtfecsol".$i."   value=''      class=sin-borde readonly style=text-align:center size=10 maxlength=10>"; 
		$object[$i][5] = "<input type=text name=txtfecprog".$i."  value=''    class=sin-borde readonly style=text-align:center size=10 maxlength=10>";			
		$object[$i][6] = "<input name=txtcodban".$i." type=hidden id=txtcodban".$i." value=''><input type=text name=txtnomban".$i." value=''    class=sin-borde readonly style=text-align:center size=20 maxlength=22>";			
		$object[$i][7] = "<input name=txtctaban".$i." type=hidden id=txtctaban".$i." value=''><input type=text name=txtdencta".$i." value=''    class=sin-borde readonly style=text-align:center size=20 maxlength=22>";			
		$li_total=1;
	}
	
	if($ls_tipo=='P')
	{
		$rb_prov="checked";
		$rb_bene="";
	}
	elseif($ls_tipo=='B')
	{
		$rb_prov="";
		$rb_bene="checked";
	}
	else
	{
		$rb_prov="";
		$rb_bene="";
	}
	//Declaración de parametros del grid.
	$titleProg[1]="Check";   $titleProg[2]="Solicitud";     $titleProg[3]="Monto";     $titleProg[4]="Prov/Benef.";   	/*$titleProg[5]="Fecha Sol."; */  $titleProg[5]="Fecha Prog."; $titleProg[6]="Banco";   $titleProg[7]="Cuenta Banco";   
    $gridProg="grid_prog";
	
	if($ls_operacion == "GUARDAR")
	{
		$li_total=$_POST["totsol"];
		$lb_valido=true;
		$io_sol->SQL->begin_transaction();
		for($i=0;($i<=$li_total)&&($lb_valido);$i++)
		{
			if(array_key_exists("chksel".$i,$_POST))
			{
					$ls_numsol=$_POST["txtnumsol".$i];
					$ldec_monto=$_POST["txtmonsol".$i];
					$ls_codban=$_POST["txtcodban".$i];
					$ls_ctaban=$_POST["txtctaban".$i];
					$ls_provbene=$_POST["txtcodproben".$i];
					$ls_tipo=$_POST["rb_provbene"];
					$ld_fecpropag=$_POST["txtfecprog".$i];	
					$ls_estmov='D';				
					$lb_valido=$io_sol->uf_procesar_desprogramacion($ls_numsol,$ld_fecpropag,$ls_estmov,$ls_codban,$ls_ctaban,$ls_provbene,$ls_tipo);
			
			}			
		}		
		if($lb_valido)
		{
			$io_sol->SQL->commit();	
			$msg->message("El movimiento fue registrado");
		}
		else
		{
			$io_sol->SQL->rollback();
		}
		
	}
	if(($ls_operacion=="CAMBIO_TIPO")||($ls_operacion=="GUARDAR"))
	{
		//Cargo los datos de las programaciones.
		$data=$io_sol->uf_cargar_programaciones($ls_empresa,$ls_tipo);		
		$ldec_total_prog=0;
		if($data!="")
		{
			$ds_sol->data=$data;
			$li_total=$ds_sol->getRowCount("numsol");
			for($i=1;$i<=$li_total;$i++)
			{
				$ls_numsol  = $ds_sol->getValue("numsol",$i);
				$ldec_monsol= $ds_sol->getValue("monsol",$i);
				$ldec_moncan= 0;
				$ld_fecsol  = $fun->uf_convertirfecmostrar($ds_sol->getValue("fecemisol",$i));
				$ls_provbene= $ds_sol->getValue("codproben",$i);
				$ls_nomprovbene= $ds_sol->getValue("nomproben",$i);
				$ls_codban  = $ds_sol->getValue("codban",$i);
				$ls_nomban  = $ds_sol->getValue("nomban",$i);
				$ls_ctaban  = $ds_sol->getValue("ctaban",$i);
				$ls_dencta  = $ds_sol->getValue("dencta",$i);
				$ld_fecpropag=$fun->uf_convertirfecmostrar($ds_sol->getValue("fecpropag",$i));						
				$object[$i][1] = "<input type=checkbox name=chksel".$i."  id=chksel".$i." value=1 >";		
				$object[$i][2] = "<input type=text name=txtnumsol".$i."   value='".$ls_numsol."'      class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";
				$object[$i][3] = "<input type=text name=txtmonsol".$i."   value='".number_format($ldec_monsol,2,",",".")."'    class=sin-borde readonly style=text-align:right size=20 maxlength=22 >";
				$object[$i][4] = "<input name=txtcodproben".$i." type=hidden id=txtcodproben".$i." value='".$ls_provbene."'><input type=text name=txtnomprovbene".$i." value='".$ls_nomprovbene."'    class=sin-borde readonly style=text-align:left size=20 maxlength=22 >";
				//$object[$i][5] = "<input type=text name=txtfecsol".$i."   value='".$ld_fecsol."'      class=sin-borde readonly style=text-align:center size=10 maxlength=10>"; 
				$object[$i][5] = "<input type=text name=txtfecprog".$i."  value='".$ld_fecpropag."'    class=sin-borde readonly style=text-align:center size=10 maxlength=10>";			
				$object[$i][6] = "<input name=txtcodban".$i." type=hidden id=txtcodban".$i." value='".$ls_codban."'><input type=text name=txtnomban".$i." value='".$ls_nomban."'    class=sin-borde readonly style=text-align:left size=20 maxlength=22>";			
 				$object[$i][7] = "<input name=txtctaban".$i." type=hidden id=txtctaban".$i." value='".$ls_ctaban."'><input type=text name=txtdencta".$i." value='".$ls_dencta."'    class=sin-borde readonly style=text-align:left size=20 maxlength=22>";			
			}
		}	
		else
		{
				$i=1;
				$object[$i][1] = "<input type=checkbox name=chksel".$i."  id=chksel".$i." value=1 >";		
				$object[$i][2] = "<input type=text name=txtnumsol".$i."   value=''      class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";
				$object[$i][3] = "<input type=text name=txtmonsol".$i."   value='".number_format(0,2,",",".")."'    class=sin-borde readonly style=text-align:right size=20 maxlength=22 >";
				$object[$i][4] = "<input name=txtcodproben".$i." type=hidden id=txtcodproben".$i." value=''><input type=text name=txtnomprovbene".$i." value=''    class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
				//$object[$i][5] = "<input type=text name=txtfecsol".$i."   value=''      class=sin-borde readonly style=text-align:center size=10 maxlength=10>"; 
				$object[$i][5] = "<input type=text name=txtfecprog".$i."  value=''    class=sin-borde readonly style=text-align:center size=10 maxlength=10>";			
				$object[$i][6] = "<input name=txtcodban".$i." type=hidden id=txtcodban".$i." value=''><input type=text name=txtnomban".$i." value=''    class=sin-borde readonly style=text-align:center size=20 maxlength=22>";			
 				$object[$i][7] = "<input name=txtctaban".$i." type=hidden id=txtctaban".$i." value=''><input type=text name=txtdencta".$i." value=''    class=sin-borde readonly style=text-align:center size=20 maxlength=22>";			
				$li_total=1;
		}
	}
  ?>
  <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <br>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td colspan="4">Desprogramaci&oacute;n de Pagos </td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="75" height="22">&nbsp;</td>
      <td colspan="2"><p>
        <label>
        <input name="rb_provbene" id="rb_provbene" type="radio" value="P" onClick="javascript:uf_cambiar();" <?php print $rb_prov;?>>
  Proveedor</label>
        <label>
        <input type="radio" name="rb_provbene" id="rb_provbene" value="B" onClick="javascript:uf_cambiar();" <?php print $rb_bene;?>>
  Beneficiario</label>
        <br>
      </p></td>
      <td width="202">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center">
        <input name="operacion" type="hidden" id="operacion">
        <?php $io_grid->makegrid($li_total,$titleProg,$object,770,'Solicitudes Programadas',$gridProg);?>
          <input name="totsol"  type="hidden" id="totsol"  value="<?php print $li_total?>">
          <input name="fila"    type="hidden" id="fila">
      </div></td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td width="249" height="22">&nbsp;</td>
      <td width="252" height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  </form>
</body>
<script language="javascript">

function ue_nuevo()
{
	f=document.form1;
	f.operacion.value ="NUEVO";
	f.action="tepuy_scb_p_desprogpago.php";
	f.submit();
}

function ue_guardar()
{
	f=document.form1;
	f.operacion.value ="GUARDAR";
	f.action="tepuy_scb_p_desprogpago.php";
	f.submit();
}

function ue_cerrar()
{
	f=document.form1;
	f.action="tepuywindow_blank.php";
	f.submit();
}

    //Catalogo de cuentas contables
	function catalogo_cuentabanco()
	 {
	   f=document.form1;
	   ls_codban=f.txtcodban.value;
	   ls_denban=f.txtdenban.value;
	  	   if((ls_codban!=""))
		   {
			   pagina="tepuy_cat_ctabanco.php?codigo="+ls_codban+"&denban="+ls_denban;
			   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
		   }
		   else
		   {
				alert("Seleccione el Banco");   
		   }
	  
	 }
	 
	 function cat_bancos()
	 {
	   f=document.form1;
	   pagina="tepuy_cat_bancos.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }

	
  function currencyDate(date)
  { 
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}
			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string,10);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
   }   
   
   function uf_desaparecer(objeto)
   {
      eval("document.form1."+objeto+".style.visibility='hidden'");
   }
   function uf_aparecer(objeto)
   {
      eval("document.form1."+objeto+".style.visibility='visible'");
   }
   
	function catprovbene()
	{
		f=document.form1;
		if(f.rb_provbene[0].checked==true)
		{
			f.txtprovbene.disabled=false;	
			window.open("tepuy_catdinamic_prov.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else if(f.rb_provbene[1].checked==true)
		{
			f.txtprovbene.disabled=false;	
			window.open("tepuy_catdinamic_bene.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		}
	}   

	function uf_verificar_provbene(lb_checked,obj)
	{
		f=document.form1;
	
		if((f.rb_provbene[0].checked)&&(obj!='P'))
		{
			f.tipo.value='P';
			f.txtprovbene.value="";
			f.txtdesproben.value="";
			f.txttitprovbene.value="Proveedor";
		}
		if((f.rb_provbene[1].checked)&&(obj!='B'))
		{
			f.txtprovbene.value="";
			f.txtdesproben.value="";
			f.tipo.value='B';
			f.txttitprovbene.value="Beneficiario";
		}
		if((f.rb_provbene[2].checked)&&(obj!='N'))
		{
			f.txtprovbene.value="----------";
			f.txtdesproben.value="Ninguno";
			f.tipo.value='N';
			f.txttitprovbene.value="";
		}
	}

 
   function currencyFormat(fld, milSep, decSep, e)
   { 
		var sep = 0; 
		var key = ''; 
		var i = j = 0; 
		var len = len2 = 0; 
		var strCheck = '0123456789'; 
		var aux = aux2 = ''; 
		var whichCode = (window.Event) ? e.which : e.keyCode; 
		if (whichCode == 13) return true; // Enter 
		key = String.fromCharCode(whichCode); // Get key value from key code 
		if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
		len = fld.value.length; 
		for(i = 0; i < len; i++) 
		 if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
		aux = ''; 
		for(; i < len; i++) 
		 if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
		aux += key; 
		len = aux.length; 
		if (len == 0) fld.value = ''; 
		if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
		if (len == 2) fld.value = '0'+ decSep + aux; 
		if (len > 2) { 
		 aux2 = ''; 
		 for (j = 0, i = len - 3; i >= 0; i--) { 
		  if (j == 3) { 
		   aux2 += milSep; 
		   j = 0; 
		  } 
		  aux2 += aux.charAt(i); 
		  j++; 
		 } 
		 fld.value = ''; 
		 len2 = aux2.length; 
		 for (i = len2 - 1; i >= 0; i--) 
		  fld.value += aux2.charAt(i); 
		 fld.value += decSep + aux.substr(len - 2, len); 
		} 
		return false; 
	}
	
	function uf_cambiar()
	{
		f=document.form1;
		f.operacion.value="CAMBIO_TIPO";
		f.action="tepuy_scb_p_desprogpago.php";
		f.submit();
	}
</script>
</html>
