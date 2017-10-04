<?php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../tepuy_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_funciones_inventario.php");
	$io_fun_activo=new class_funciones_inventario();
	$io_fun_activo->uf_load_seguridad("SIV","tepuy_siv_p_revrecepcion.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   	function uf_formatonumerico($as_valor)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:     uf_formatonumerico
		//	Arguments:    as_valor  // valor sin formato numérico
		//	Returns:	  $as_valor // valor numérico formateado
		//	Description:  Función que le da formato a los valores numéricos que vienen de la BD
		//////////////////////////////////////////////////////////////////////////////
		$as_valor=    str_replace(".",",",$as_valor);
		$li_poscoma = stripos($as_valor, ",");
		$li_contador = 1;
		if ($li_poscoma==0)
		{
			$li_poscoma = strlen($as_valor);
			$as_valor = $as_valor.",00";
		}
		$li_poscoma = $li_poscoma - 1;
		for($li_index=$li_poscoma;$li_index>=0;--$li_index)
		{
			if(($li_contador==3)&&(($li_index-1)>=0)) 
			{
				$as_valor = substr($as_valor,0,$li_index).".".substr($as_valor,$li_index);
				$li_contador=1;
			}
			else
			{
				$li_contador=$li_contador + 1;
			}
		}
		return $as_valor;
	}
   function uf_obtenervalor($as_valor, $as_valordefecto)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_obtenervalor
	//	Access:    public
	//	Arguments:
    // 				as_valor         //  nombre de la variable que desamos obtener
    // 				as_valordefecto  //  contenido de la variable
    // Description: Función que obtiene el valor de una variable si viene de un submit
	//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists($as_valor,$_POST))
		{
			$valor=$_POST[$as_valor];
		}
		else
		{
			$valor=$as_valordefecto;
		}
   		return $valor; 
   }
   //--------------------------------------------------------------
   
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_agregarlineablanca
	//	Access:    public
	//	Arguments:
	//  			  aa_object // arreglo de titulos 
	//  			  ai_totrows // ultima fila pintada en el grid
	//	Description:  Funcion que agrega una linea en blanco al final del grid
	//              
	//////////////////////////////////////////////////////////////////////////////		
		$aa_object[$ai_totrows][1]="<input name=txtestpres".$ai_totrows." type=text id=txtestpres".$ai_totrows." class=sin-borde size=15 maxlength=15 readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtcuenta".$ai_totrows." type=text id=txtcuenta".$ai_totrows." class=sin-borde size=15 maxlength=15 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtmonto".$ai_totrows." type=text id=txtmonto".$ai_totrows." class=sin-borde size=12 maxlength=12 readonly>";
		$aa_object[$ai_totrows][4]="<input type='checkbox' name=chkcasar".$ai_totrows." class= sin-borde value=1>";

   }
   //--------------------------------------------------------------
   function uf_pintardetalle($ai_totrows,$ls_estpro)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_pintardetalle
	//	Access:    public
	//	Arguments:
	//  		      ai_totrows    // cantidad de filas que tiene el grid
	//				  ls_estpro     // indica que valor tiene el radiobutton O--> Orden de compra F--> Factura
	//  		      ls_checkedord // variable imprime o no "checked" para el radiobutton en la orden de compra
	//				  ls_checkedfac // variable imprime o no "checked" para el radiobutton en la factura
	//	Description:  Funcion que vuelve a pintar el detalle del grid tal cual como estaba.
	//              
	//////////////////////////////////////////////////////////////////////////////		
		global $lo_object;

		if($ls_estpro=="O")
		{
			$ls_checkedord="checked";
			$ls_checkedfac="";
		}
		elseif($ls_estpro=="F")
		{
			$ls_checkedord="";
			$ls_checkedfac="checked";
		}
		else
		{
			$ls_checkedord="";
			$ls_checkedfac="";
		}
		for($li_i=1;$li_i<$ai_totrows;$li_i++)
		{	
			$la_unidad[0]="";
			$la_unidad[1]="";
			$ls_codart=    $_POST["txtcodart".$li_i];
			$ls_unidad=    $_POST["txtunidad".$li_i];
			$li_canart=    $_POST["txtcanart".$li_i];
			$li_penart=    $_POST["txtpenart".$li_i];
			$li_preuniart= $_POST["txtpreuniart".$li_i];
			$li_canoriart= $_POST["txtcanoriart".$li_i];
			$li_montotart= $_POST["txtmontotart".$li_i];
			//uf_seleccionarcombo("D-M",$ls_unidad,$la_unidad,2);
					
			$lo_object[$li_i][1]="<input name=txtcodart".$li_i." type=text id=txtcodart".$li_i." class=sin-borde size=15 maxlength=15 value='".$ls_codart."' readonly>";
			$lo_object[$li_i][2]="<input name=txtunidad".$li_i." type=text id=txtunidad".$li_i." class=sin-borde size=12 maxlength=12 value='".$ls_unidad."' readonly>";
			$lo_object[$li_i][3]="<input name=txtcanart".$li_i." type=text id=txtcanart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canart."' onKeyUp='javascript: ue_validarnumero(this);'>";
			$lo_object[$li_i][4]="<input name=txtpenart".$li_i." type=text id=txtpenart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_penart."' onKeyUp='javascript: ue_validarnumero(this);'>";
			$lo_object[$li_i][5]="<input name=txtpreuniart".$li_i." type=text id=txtpreuniart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_preuniart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
			$lo_object[$li_i][6]="<input name=txtcanoriart".$li_i." type=text id=txtcanoriart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canoriart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
			$lo_object[$li_i][7]="<input name=txtmontotart".$li_i." type=text id=txtmontotart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_montotart."' onKeyUp='javascript: ue_validarnumero(this);'>";
			$lo_object[$li_i][8]="";
			$lo_object[$li_i][9]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=imagebank/tools15/deshacer.png alt=Aceptar width=15 height=15 border=0></a>";			
	   } 
	   uf_agregarlineablanca($lo_object,$ai_totrows);
   }
  	//--------------------------------------------------------------

   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_numordcom,$ls_codpro,$ls_denpro,$ls_codalm,$ls_nomfisalm,$ld_fecrec,$ls_obsrec;
		global $ls_checkedord,$ls_checkedfac,$ls_codusu,$ls_readonly,$ls_codart,$ls_denart,$ls_numerorden;
		
		$ls_numordcom="";
		$ls_codpro="";
		$ls_denpro="";
		$ls_codalm="";
		$ls_nomfisalm="";
		$ld_fecrec="";
		$ls_obsrec="";
		$ls_checkedord="";
		$ls_checkedfac="";
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_readonly="true";
		$ls_codart="";
		$ls_denart="";
		$ls_numerorden="";
   }
   
   function uf_obtenervalorunidad($li_i)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_obtenervalorunidad
	//	Access:    public
	//	Arguments:
    // 				li_i         //  valor del 
    // 				ls_valor     //  nombre de la variable que desamos obtener
    // Description: Función que obtiene el contenido del combo cmbunidad o 
	//				del campo txtunidad deacuerdo sea el caso 
	//////////////////////////////////////////////////////////////////////////////
		if (array_key_exists("cmbunidad".$li_i,$_POST))
		{
			$ls_valor= $_POST["cmbunidad".$li_i];
		}
		else
		{
			$ls_valoraux= $_POST["txtunidad".$li_i];
			if($ls_valoraux=="Mayor")
			{
				$ls_valor="M";
			}
			else
			{
				$ls_valor="D";
			}
		}
   		return $ls_valor; 
   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Reverso del Casamiento Presupuestario </title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

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
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="css/siv.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<!--<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
--></head>

<body>
<?php
	require_once("../shared/class_folder/tepuy_include.php");
	$in=      new tepuy_include();
	$con=     $in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=  new class_sql($con);
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=  new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun=  new class_funciones_db($con);
	require_once("../shared/class_folder/class_funciones.php");
	$io_func= new class_funciones();
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	require_once("tepuy_siv_c_cerraroc.php");
	$io_siv=  new tepuy_siv_c_cerraroc();
	require_once("class_funciones_inventario.php");
	$io_fun_inv=new class_funciones_inventario();
	$ls_numordencom=$io_fun_inv->uf_obtenervalor_get("ls_numordcomgrid",""); 
	$ls_coduniadm=$io_fun_inv->uf_obtenervalor_get("ls_coduniadmgrid",""); 
	$ls_monto=$io_fun_inv->uf_obtenervalor_get("ls_montogrid","");
	$ls_accion=$io_fun_inv->uf_obtenervalor_get("accion",""); 
	$li_filacerraroc=$io_fun_inv->uf_obtenervalor_get("li_totrows","");
	$ls_fecha=$io_fun_inv->uf_obtenervalor_get("ls_fechagrid",""); 
	$ls_codpro=$io_fun_inv->uf_obtenervalor_get("ls_codprogrid","");

	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_codusu=$_SESSION["la_logusr"];
	$li_totrows = uf_obtenervalor("totalfilas",1);
	$ls_titletable="Entradas Actuales";
	$li_widthtable=620;
	$ls_nametable="grid";
	$lo_title[1]="Estructura Presupuestaria";
	$lo_title[2]="Cuenta";
	$lo_title[3]="Monto";
	$lo_title[4]="";

	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_status=$_POST["hidestatus"];
	}
	else
	{
		$ls_operacion="BUSCARORDEN";
		$ls_status="";
		uf_limpiarvariables();
	}
	switch ($ls_operacion) 
	{

		case "BUSCARORDEN":
			/*$lb_valido=$io_siv->uf_verificar_orden($ls_codemp,$ls_numordencom,$ls_fecha,&$li_vali);
			if($li_vali==1)
			 {
			     print "<script language=JavaScript>";
				 print "close();" ;
				 print "</script>";
			 }
			 else
			 {*/
			$lb_valido=$io_siv->uf_siv_buscar_dt_ordencompra($ls_codemp,$ls_numordencom,&$li_totrowsart);
			if($lb_valido)
			{
			  for($li_i=1;$li_i<=$li_totrowsart;$li_i++)
			   {
		    		   $lb_valido=$io_siv->uf_siv_obtener_dt_pendiente($ls_codemp,$ls_numordencom,$ls_coduniadm,$ls_monto,
						                                               $li_totrows,&$ls_codigoart,&$ls_denomiart,&$ls_escondat,
																	   &$lo_object);
						if ($lb_valido)
						{  
							$ls_codart=$ls_codigoart; 
							$ls_denart=$ls_denomiart; 
							$ls_numorden=$ls_numordencom;
							 $ls_sql=  "SELECT siv_dt_recepcion.*,siv_articulo.codunimed,siv_cargosarticulo.codcar,".
											  "      (SELECT unidad FROM siv_unidadmedida ".
											  "	       WHERE siv_unidadmedida.codunimed = siv_articulo.codunimed) AS unidades,".
											  "      (SELECT denart FROM siv_articulo".
											  "        WHERE siv_dt_recepcion.codart=siv_articulo.codart) AS denart".
											  "  FROM siv_dt_recepcion, siv_recepcion,siv_articulo,siv_cargosarticulo".
											  " WHERE  siv_dt_recepcion.codemp=siv_recepcion.codemp".
											  "   AND siv_dt_recepcion.codart=siv_articulo.codart".
											  "   AND siv_dt_recepcion.numordcom=siv_recepcion.numordcom".
											  "   AND siv_dt_recepcion.numconrec=siv_recepcion.numconrec ".
											  "   AND siv_dt_recepcion.codemp='".$ls_codemp."'".
											  "   AND siv_dt_recepcion.numordcom='".$ls_numordencom."'".
											  "   AND siv_recepcion.estrec=0".
											  "   AND siv_dt_recepcion.codart='".$ls_codart."'".
											  " ORDER BY siv_dt_recepcion.numconrec DESC LIMIT  1";
								$rs_data=$io_sql->select($ls_sql);
								if($rs_data===false)
								{
									$lb_valido=false;
									$io_msg->message("ERROR->".$io_funcion->uf_convertirmsg($io_sql->message));
								}
								else
								{ 
									if($row=$io_sql->fetch_row($rs_data))
									{
										$li_penart= $row["penart"];
										if($li_penart>0)
										{ 
											$ls_unidad=    $row["unidad"];
											$li_unidad=    $row["unidades"];
											$li_preuniart= $row["preuniart"];
											$li_penart=    $row["penart"];
											$li_canoriart= $row["canoriart"];
											if($ls_unidad=="D")
											{$li_unidad=1;}
											$as_spgcuenta="";
											$li_monart=($li_preuniart * $li_penart * $li_unidad); 
										}// fin del if
									}// fin del if
								}// fin del else
								
							$lb_valido=$io_siv->uf_siv_buscar_cuentagasto($ls_codemp,$ls_numordencom,$ls_coduniadm,$ls_escondat,
																		  &$lo_object,&$ls_codestpro1,&$ls_codestpro2,&$ls_codestpro3,
						              									  &$ls_codestpro4,&$ls_codestpro5,&$spg_cuenta,&$as_montonuevo);
							$li_monart=number_format($li_monart,2,",",".");
							$ls_montotart=$li_monart;
							$ls_numorden=$ls_numordencom;
							
						} //fin del if 
						else
						{
							uf_agregarlineablanca($lo_object,1);
						}
			  } // fon del for
		   }
		   else
		   {
		     $io_msg->message("No se encontró el detalle de la orden de compra");
		   }
		 // } 
		break;
		
		case "GUARDAR":
			$li_totrows= $_POST["totalfilas"];
			$li_temp=0;
			$li_s=0;
			$ls_procede="SOCCOC";
			$ls_numordencom=$_POST["numorden"];
			$ls_codart=$_POST["txtcodart"];
			$ls_denart=$_POST["txtdenart"];
			$ls_montotart=$_POST["txtmontotal"];
			$ls_escondat="B";
			if($ls_accion==0)
			{
				$ls_estpenalm=1;
			}
			else
			{
				$ls_estpenalm=0;
			}
			$lb_valido=$io_siv->uf_siv_buscar_cuentagasto($ls_codemp,$ls_numordencom,$ls_coduniadm,$ls_escondat,
														  &$lo_object,&$ls_codestpro1,&$ls_codestpro2,&$ls_codestpro3,
						              					  &$ls_codestpro4,&$ls_codestpro5,&$spg_cuenta,&$as_montonuevo);
			if ($lb_valido)
			  {
				   $lb_valido=$io_siv->uf_siv_buscar_dt_cmp($ls_codemp,&$ls_procede,&$ls_numordencom,&$ls_fecha,&$ls_codban,&$ls_ctaban,
												  &$ls_codestpro1,&$ls_codestpro2,&$ls_codestpro3,&$ls_codestpro4,&$ls_codestpro5,
												  &$spg_cuenta,&$ls_documento,&$ls_procededoc,&$ls_operacion,&$ls_descripcion,
												  &$li_orden,&$ls_monto);
					if ($lb_valido)
					{ 
						$lb_valido=$io_siv->uf_siv_load_cmp($ls_codemp,$ls_procede,$ls_numordencom,&$ld_feccmp,&$ls_tipocomp,
															&$ls_tipodestino,&$ls_codpro,&$ls_cedbene);
						$ls_newprocede="SPGCMP";
						$li_monart= str_replace(".","",$ls_montotart);
						$li_monart= str_replace(",",".",$ls_montotart);
						$as_montonuevo= str_replace(".","",$as_montonuevo);
						$as_montonuevo= str_replace(",",".",$as_montonuevo);
						if($lb_valido)
						{ 
							$lb_valido=$io_siv->uf_siv_insertar_tepuy_cmp($ls_codemp,$ls_newprocede,$ls_numordencom,$ld_feccmp,
																		   $ls_codban,$ls_ctaban,$ls_descripcion,$ls_tipocomp,
																		   $ls_tipodestino,$ls_codpro,$ls_cedbene,$li_monart,
																		   $la_seguridad);
							 if($lb_valido!=0)
							 {
									for($li_i=1;$li_i<=$li_totrows;$li_i++)
									{ 
										if (array_key_exists("chkcasar".$li_i,$_POST))
										{
											$li_s=$li_s + 1;
											$li_check=$_POST["chkcasar".$li_i]; 
											if ($li_check==1)
											{
											  $ls_estpres= $_POST["txtestpres".$li_i];
											  $ls_numordencom=$_POST["numorden"];
											  $ls_codestpro1=substr($ls_estpres,0,20);   
											  $ls_codestpro2=substr($ls_estpres,21,6); 
											  $ls_codestpro3=substr($ls_estpres,27,3);
											  $ls_codestpro4=substr($ls_estpres,30,2); 
											  $ls_codestpro5=substr($ls_estpres,31,2); 
											  
											  $spg_cuenta= $_POST["txtcuenta".$li_i];
											  $as_montonuevo=$_POST["txtmonto".$li_i];
											  $ls_procede="SOCCOC"; 
											  $io_siv->uf_siv_insertasiento_spg_dt_cmp($ls_codemp,$ls_newprocede,$ls_numordencom,$ls_fecha,$ls_codban,$ls_ctaban,
																						$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
																						$spg_cuenta,$ls_documento,$ls_procede,$ls_operacion,$ls_descripcion,
																						$li_orden,$as_montonuevo,&$lo_object,$la_seguridad);
											   $lb_valido=$io_siv->uf_siv_update_statusorden($ls_codemp,$ls_numordencom,$ls_estpenalm,$la_seguridad);
											   
											 }// fin del li_check
										 }
										 else
										 {
										   $lo_object[$li_i][1]="<input name=txtestpres".$li_i." type=text id=txtestpres".$li_i." class=sin-borde size=40 maxlength=15 value='' readonly>";
										   $lo_object[$li_i][2]="<input name=txtcuenta".$li_i." type=text id=txtcuenta".$li_i." class=sin-borde size=20 maxlength=15 value='' readonly>";
										   $lo_object[$li_i][3]="<input name=txtmonto".$li_i." type=text id=txtmonto".$li_i." class=sin-borde size=20 maxlength=12 value='' readonly>";
										   $lo_object[$li_i][4]="<input type='checkbox' name=chkcasar".$li_i." class= sin-borde value=1>";
	
										 }
									 } // fin del for
									 if($lb_valido)
									   {
										   $io_sql->commit();
										   $io_msg->message("La orden de compra fué cerrada con éxito.");
										   $lo_object[$li_i][1]="<input name=txtestpres".$li_i." type=text id=txtestpres".$li_i." class=sin-borde size=40 maxlength=15 value='' readonly>";
										   $lo_object[$li_i][2]="<input name=txtcuenta".$li_i." type=text id=txtcuenta".$li_i." class=sin-borde size=20 maxlength=15 value='' readonly>";
										   $lo_object[$li_i][3]="<input name=txtmonto".$li_i." type=text id=txtmonto".$li_i." class=sin-borde size=20 maxlength=12 value='' readonly>";
										   $lo_object[$li_i][4]="<input type='checkbox' name=chkcasar".$li_i." class= sin-borde value=1>";
									   }
									   else
										 {
										   $io_sql->rollback();
										   $io_msg->message("No se pudo realizar el proceso.");
										   $lo_object[$li_i][1]="<input name=txtestpres".$li_i." type=text id=txtestpres".$li_i." class=sin-borde size=40 maxlength=15 value='' readonly>";
										   $lo_object[$li_i][2]="<input name=txtcuenta".$li_i." type=text id=txtcuenta".$li_i." class=sin-borde size=20 maxlength=15 value='' readonly>";
										   $lo_object[$li_i][3]="<input name=txtmonto".$li_i." type=text id=txtmonto".$li_i." class=sin-borde size=20 maxlength=12 value='' readonly>";
										   $lo_object[$li_i][4]="<input type='checkbox' name=chkcasar".$li_i." class= sin-borde value=1>";
	
										 }
									 print "<script language=JavaScript>";
									 print "close();" ;
									 print "</script>";
								}
								else
								{
								     $io_sql->rollback();
							         $io_msg->message("No se pudo realizar el proceso.");
								     $ls_codart="";
									 $ls_denart="";
									 $ls_montotart="";
									 for($li_i=1;$li_i<=$li_totrows;$li_i++)
									 {
								       	   $lo_object[$li_i][1]="<input name=txtestpres".$li_i." type=text id=txtestpres".$li_i." class=sin-borde size=40 maxlength=15 value='' readonly>";
										   $lo_object[$li_i][2]="<input name=txtcuenta".$li_i." type=text id=txtcuenta".$li_i." class=sin-borde size=20 maxlength=15 value='' readonly>";
										   $lo_object[$li_i][3]="<input name=txtmonto".$li_i." type=text id=txtmonto".$li_i." class=sin-borde size=20 maxlength=12 value='' readonly>";
										   $lo_object[$li_i][4]="<input type='checkbox' name=chkcasar".$li_i." class= sin-borde value=1>";
									 }
									 print "<script language=JavaScript>";
									 print "close();" ;
									 print "</script>";
								}
							} // fin del valido 
						 $ls_codart="";
					     $ls_denart="";
					     $ls_montotart="";
					   }// fin del valido 2
					} // fin del valido 1
					
		break;
	}
?>
<p>&nbsp;</p>
<div align="center">
  <table width="649" height="209" border="0" class="formato-blanco">
    <tr>
      <td width="755" height="203"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='tepuywindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
            <table width="626" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="620">&nbsp;</td>
              </tr>
              <tr>
                <td><table width="615" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                    <tr>
                      <td colspan="3" class="titulo-ventana">Casamiento Presupuestario  </td>
                    </tr>
                    <tr class="formato-blanco">
                      <td height="13">&nbsp;</td>
                      <td width="157">&nbsp;</td>
                      <td width="369">&nbsp;</td>
                    </tr>
                    <tr class="formato-blanco">
                      <td height="13"><div align="right">C&oacute;digo Art&iacute;culo</div></td>
                      <td><input name="txtcodart" type="text" id="txtcodart"  value="<?php print $ls_codart?>" size="30"  style="text-align:center " readonly></td>
                      <td><input name="txtdenart" type="text" id="txtdenart"  value="<?php print $ls_denart?>" size="60" class="sin-borde" style="text-align:center " readonly></td>
                    </tr>
                    <tr class="formato-blanco">
                      <td height="13">&nbsp;</td>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr class="formato-blanco">
                      <td height="13"><div align="right">Monto  </div></td>
                      <td colspan="2"><input name="txtmontotal" type="text" id="txtmontotal"  value="<?php print $ls_montotart?>" size="20"  style="text-align:center " readonly>
                      <input name="numorden" type="hidden" id="numorden" value="<?php print $ls_numorden?>" ></td>
                    </tr>
                    <tr class="formato-blanco">
                      <td height="13">&nbsp;</td>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr class="formato-blanco">
                    <td width="87" height="13"><input name="hidestatus" type="hidden" id="hidestatus2" value="<?php print $ls_status?>">                    </tr>
                    <tr class="formato-blanco">
                      <td height="22" colspan="3"><p align="center">
                          <?php
					$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					?>
                      </p></td>
                    </tr>
                    
                    <tr class="formato-blanco">
                      <td height="28" colspan="3"><div align="center">
                          <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion;?>">
                          <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
                          <input name="filadelete" type="hidden" id="filadelete">
                          <input name="catafilas" type="hidden" id="catafilas" value="<?php print $li_catafilas;?>">
                          <input name="btnguardar" type="button" class="boton" id="btnguardar" onClick="javascript: uf_guardar();" value="Guardar">
                          <input name="numordcompra" type="hidden" id="numordcompra" value="<?php print $ls_numordencom;?>">
                          <input name="coduniadm" type="hidden" id="coduniadm"  value="<?php print $ls_coduniadmgrid;?>">
                          <input name="monto" type="hidden" id="monto"  value="<?php print $ls_monto;?>">
                          <input name="fila" type="hidden" id="fila"  value="<?php print $li_filacerraroc;?>">
                          <input name="fecha" type="hidden" id="fecha"  value="<?php print $ls_fecha;?>">
                          <input name="codpro" type="hidden" id="codpro"  value="<?php print $ls_codpro;?>">
                      </div></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td><div align="center"> </div></td>
              </tr>
            </table>
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones 
function uf_guardar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	li_totrows=f.totalfilas.value;
	ls_montorden=f.txtmontotal.value;
	if(li_ejecutar==1)
	{	
    	for(li_i=1;li_i<=li_totrows;li_i++)
		{
		    ls_montogrid=eval("f.txtmonto"+li_i+".value");
			if(ls_montogrid>ls_montorden)
			{
			   alert ("El monto del grid debe ser menor o igual a el restante del compromiso");
			}
			/*else
			{
				if(eval("f.chkcasar"+li_i+".checked")==1)
				{
					f.operacion.value="GUARDAR"
					f.action="tepuy_siv_p_casapresu.php";
					f.submit();	
				}
			}*/
		}
		f.operacion.value="GUARDAR";
		f.action="tepuy_siv_p_casapresu.php";
		f.submit();	

	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_cerrar()
{
	window.location.href="tepuywindow_blank.php";
}

function currency_Format(fld, milSep, decSep, e) 
{ 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Enter 
	if (whichCode == 127) return true; // Enter 	
	if (whichCode == 9) return true; // Enter 	
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

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>