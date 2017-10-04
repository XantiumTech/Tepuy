<?php
class tepuy_cfg_c_empresa
{
  var $ls_sql;
  var $io_seguridad;
  var $ls_codemp;

  function tepuy_cfg_c_empresa($conn)//Constructor de la Clase.
  {
    require_once("../class_folder/class_cfg_c_fill_datos.php");  
	$this->io_sql        = new class_sql($conn);		
	$this->io_msg        = new class_mensajes();
	$this->io_funcion    = new class_funciones();
	$this->io_fill_datos = new class_cfg_c_fill_datos();

  }
	
function uf_insert_empresa() 
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	//
	//	Function: uf_insert_empresa()
	//	Access:  public
	//	Description: Este método inserta un registro por defecto dentro de la Tabla tepuy_empresa 
	//               si una vez realizada la busqueda la empresa 0001 "No existe" 
	//               en la Base de Datos seleccionada.
	/////////////////////////////////////////////////////////////////////////////////////////////////////////

    $arr_date = getdate();
	$ls_ano = $arr_date["year"];
	$ls_sql = " INSERT INTO tepuy_empresa                                                                    ". 
	          " (codemp,nombre,nomres,titulo,sigemp,direccion,telemp,faxemp,email,website,m01,m02,m03,m04,    ".
			  " m05,m06,m07,m08,m09,m10,m11,m12,periodo,vali_nivel,esttipcont,formpre,formcont,formplan,      ".
			  " formspi,activo,pasivo,ingreso,gasto,resultado,capital,c_resultad,c_resultan,orden_d,          ". 
			  " orden_h,soc_gastos,soc_servic,activo_h,pasivo_h,resultado_h,                                  ".
			  " ingreso_f,gasto_f,ingreso_p,gasto_p,logo,numniv,nomestpro1,nomestpro2,nomestpro3,             ".
			  " nomestpro4,nomestpro5,estvaltra,estmodape,estdesiva,estprecom,codorgsig,salinipro,salinieje,  ".
			  " numordcom,numordser,numsolpag,estmodest,numlicemp,modageret,socbieser,concomiva,estmodiva,    ".
			  " cedben,nomben,scctaben,diacadche,nroivss,nomrep,cedrep,telfrep,cargorep,estretiva,confinstr)  ".
			  " VALUES                                                                                        ".
			  " ('0001','tepuy CA','','tepuy CA','tepuy','','',            ".
			  " '','tepuy@gmail.com','tepuyweb@tepuy.com',1,1,1,1,1,1,1,1,1,1,1,1,              ".
			  " '".$ls_ano.'01'.'01'."',1,1,'999-99-99-99','999-99-99-99','999-99-99-99','999-99-99-99','1',  ".
			  " '2','3','4','5','7','5010201000000','5010201000000','1','2','10101010101','10101010101',      ".
			  " '11','22','12','1','2','2','1','',3,                                                          ".
			  " 'Proyecto y/o Acciones Centralizadas','Acciones Específicas','Otros.','',                     ".
			  " '',1,0,0,0,'',0,0,0,0,0,1,'0000000000000000000000000','B',1,'',0,'','','','','','','',        ".
			  " '','','B','N')  ";
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
    if ($rs_data===false)
	   {
		 $this->io_sql->rollback();
         $this->io_msg->message("CLASE->tepuy_CFG_C_EMPRESA; METODO->uf_insert_empresa;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
         $lb_valido=$this->io_fill_datos->uf_main_fill();
		 if($lb_valido)
		   {
			 if($lb_valido)
			 {
			     $this->io_sql->commit();
			 }  
			 else
			 {
				 $this->io_sql->rollback();
			 }  
		   }
		 else
		   {
			 $this->io_sql->rollback();
		   }
       }
}

function uf_update_empresa($ar_datos,$aa_seguridad) 
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function: uf_update_empresa($ar_datos,$aa_seguridad)
	//	Access:  public
	//	Arguments:
	//  ar_datos      Arreglo cargado con la nueva data proveniente de la Interfaz Empresa dentro del 
	//                Módulo de Configuración del sistema. 
	//	Description:  Este método realiza la actualización en la Tabla tepuy_empresa con los valores 
	//                almacenados en el arreglo $ar_datos.
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
    require_once("../../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
	require_once("../../shared/class_folder/tepuy_c_seguridad.php");
	$this->io_seguridad= new tepuy_c_seguridad();
    $this->io_rcbsf= new tepuy_c_reconvertir_monedabsf();
	$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
	$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
	$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	$ls_codemp           = $ar_datos["codemp"];
  $ls_jefe_presupuesto          = $ar_datos["jefe_presupuesto"];
  $ls_cargo_presupuesto         = $ar_datos["cargo_presupuesto"];
  $ls_firma1			= $ar_datos["firma1_ordenanza"];
  $ls_cargo1			= $ar_datos["cargo1_ordenanza"];
  $ls_secreordenanza		= $ar_datos["secre_ordenanza"];
  $ls_cargosecreordenanza	= $ar_datos["cargosecre_ordenanza"];
  $ls_firma2			= $ar_datos["firma2_ordenanza"];
  $ls_cargo2			= $ar_datos["cargo2_ordenanza"];
  $ls_firma3			= $ar_datos["firma3_ordenanza"];
  $ls_cargo3			= $ar_datos["cargo3_ordenanza"];
  $ls_firma4			= $ar_datos["firma4_ordenanza"];
  $ls_cargo4			= $ar_datos["cargo4_ordenanza"];
  $ls_firma5			= $ar_datos["firma5_ordenanza"];
  $ls_cargo5			= $ar_datos["cargo5_ordenanza"];
  $ls_firma6			= $ar_datos["firma6_ordenanza"];
  $ls_cargo6			= $ar_datos["cargo6_ordenanza"];
  $ls_firma7			= $ar_datos["firma7_ordenanza"];
  $ls_cargo7			= $ar_datos["cargo7_ordenanza"];
  $ls_firma8			= $ar_datos["firma8_ordenanza"];
  $ls_cargo8			= $ar_datos["cargo8_ordenanza"];
  $ls_firma9			= $ar_datos["firma9_ordenanza"];
  $ls_cargo9			= $ar_datos["cargo9_ordenanza"];
  $ls_firma10			= $ar_datos["firma10_ordenanza"];
  $ls_cargo10			= $ar_datos["cargo10_ordenanza"];
  $ls_firma11			= $ar_datos["firma11_ordenanza"];
  $ls_cargo11			= $ar_datos["cargo11_ordenanza"];


/*	$ls_nombre           = $ar_datos["nombre"];
	$ls_nomres           = $ar_datos["nomres"];
	$ls_titulo           = $ar_datos["titulo"];
	$ls_direccion        = $ar_datos["direccion"];
	$ls_ciuemp           = $ar_datos["ciuemp"];
	$ls_estemp           = $ar_datos["estemp"];
	$ls_zonpos           = $ar_datos["zonpos"];
	$ls_telefono         = $ar_datos["telefono"];
	$ls_fax              = $ar_datos["fax"];
	$ls_email            = $ar_datos["email"];
	$ls_website          = $ar_datos["website"];
	$ls_nomorgads        = $ar_datos["nomorgads"];
	$li_enero            = $ar_datos["enero"];
	$li_febrero          = $ar_datos["febrero"];
	$li_marzo            = $ar_datos["marzo"];
	$li_abril            = $ar_datos["abril"];
	$li_mayo             = $ar_datos["mayo"];
	$li_junio            = $ar_datos["junio"];
	$li_julio            = $ar_datos["julio"];
	$li_agosto           = $ar_datos["agosto"];
	$li_septiembre		 = $ar_datos["septiembre"];
	$li_octubre			 = $ar_datos["octubre"];
	$li_noviembre		 = $ar_datos["noviembre"];
	$li_diciembre        = $ar_datos["diciembre"];
    $ls_fechaperiodo     = $ar_datos["periodo"];
	$ls_periodo      	 = $this->io_funcion->uf_convertirdatetobd($ls_fechaperiodo);
	$ls_nivel            = $ar_datos["nivelval"];
	$ls_tipocontabilidad = trim($ar_datos["tipocontabilidad"]);
	$ls_formpre          = $ar_datos["pgasto"];
	$ls_formcont         = $ar_datos["contabilidad"];
	$ls_formplan         = $ar_datos["planunico"];
	$ls_formspi          = $ar_datos["pingreso"];
	$ls_activo			 = $ar_datos["activo"];
	$ls_pasivo			 = $ar_datos["pasivo"];
	$ls_ingreso          = $ar_datos["ingreso"];
	$ls_gasto            = $ar_datos["gasto"];
	$ls_resultado        = $ar_datos["resultado"];
	$ls_capital          = $ar_datos["capital"];
	$ls_cresultad        = $ar_datos["resultadoactual"];
	$ls_cresultan        = $ar_datos["resultadoanterior"];
	$ls_ordend           = $ar_datos["ordendeudor"];
	$ls_ordenh           = $ar_datos["ordenacreedor"];
	$ls_socgastos        = $ar_datos["cuentabienes"];
	$ls_socservic        = $ar_datos["cuentaservicios"];
	$ls_activoh          = $ar_datos["haciendaactivo"];
	$ls_pasivoh          = $ar_datos["haciendapasivo"];
	$ls_resultadoh       = $ar_datos["haciendaresul"];
	$ls_ingresof         = $ar_datos["fiscalingreso"];
	$ls_gastof           = $ar_datos["fiscalgasto"];
	$ls_ingresop         = $ar_datos["presupuestoingreso"];
	$ls_gastop           = $ar_datos["presupuestogasto"];
	$ls_logo             = "N/A";
	$li_numnivest        = $ar_datos["numnivest"];
	$ls_nomestpro1       = $ar_datos["desestpro1"];
	$ls_nomestpro2       = $ar_datos["desestpro2"];
	$ls_nomestpro3       = $ar_datos["desestpro3"];
	$ls_nomestpro4       = $ar_datos["desestpro4"];
	$ls_nomestpro5       = $ar_datos["desestpro5"];
	$ls_estvaltra        = $ar_datos["estvaltra"];
	$ls_estmodape        = $ar_datos["estmodape"];
	$li_estdesiva        = $ar_datos["estdesiva"];
	$ls_codorgsig        = $ar_datos["codorgsig"];
	$ls_rifemp           = $ar_datos["rifemp"];
	$ls_nitemp           = $ar_datos["nitemp"];
	$ld_salinipro        = $ar_datos["salinipro"];
	$ld_salinipro        = str_replace('.','',$ld_salinipro);
	$ld_salinipro        = str_replace(',','.',$ld_salinipro);
	$ld_salinieje        = $ar_datos["salinieje"];
	$ld_salinieje        = str_replace('.','',$ld_salinieje);
	$ld_salinieje        = str_replace(',','.',$ld_salinieje);
	$li_estmodest        = $ar_datos["estmodest"];
	$ls_numordcom        = $ar_datos["numordcom"];
	$ls_numordser        = $ar_datos["numordser"];
	$ls_numsolpag        = $ar_datos["numsolpag"];
	$ls_numlicemp        = $ar_datos["numlicemp"];
	$ls_modgenret        = trim($ar_datos["modgenret"]);
	$ls_concomiva		 = trim($ar_datos["concomiva"]);
	$ls_estmodiva		 = trim($ar_datos["estmodiva"]);
	$ls_cedben		     = trim($ar_datos["cedben"]);	
	$ls_nomben		     = trim($ar_datos["nomben"]);
	$ls_scctaben		 = trim($ar_datos["scctaben"]);	
    $ls_tesoroactivo    = $ar_datos["tesoroactivo"];
    $ls_tesoropasivo    = $ar_datos["tesoropasivo"];
    $ls_tesororesul     = $ar_datos["tesororesul"];
    $ls_ctafinanciera   = trim($ar_datos["c_financiera"]);
    $ls_ctafiscal       = trim($ar_datos["c_fiscal"]);
	$ls_codasiona       = trim($ar_datos["codasiona"]);
	$ls_confich         = $ar_datos["confich"];
	$ls_confiva         = $ar_datos["confivaprecon"];
	$li_diacadche       = $ar_datos["diacadche"];
	$ls_ivss			= $ar_datos["nroivss"];
	$ls_nomrep       = $ar_datos["nomrep"];
	$ls_cedrep       = $ar_datos["cedrep"];
	$ls_telfrep       = $ar_datos["telfrep"];
	$ls_cargo       = $ar_datos["cargorep"];
	$ls_estretiva       = $ar_datos["estretiva"];
	$ls_confinstr   = $ar_datos["confinstr"];
*/	
	$ls_sql=" UPDATE tepuy_empresa                                                                           ".
			" SET jefe_presupuesto='".$ls_jefe_presupuesto."',cargo_presupuesto='".$ls_cargo_presupuesto."',                  ".
			" firma1_ordenanza='".$ls_firma1."',cargo1_ordenanza='".$ls_cargo1."',                  ".
			" secre_ordenanza='".$ls_secreordenanza."',cargosecre_ordenanza='".$ls_cargosecreordenanza."',                  ".
			" firma2_ordenanza='".$ls_firma2."',cargo2_ordenanza='".$ls_cargo2."',                  ".
			" firma3_ordenanza='".$ls_firma3."',cargo3_ordenanza='".$ls_cargo3."',                  ".
			" firma4_ordenanza='".$ls_firma4."',cargo4_ordenanza='".$ls_cargo4."',                  ".
			" firma5_ordenanza='".$ls_firma5."',cargo5_ordenanza='".$ls_cargo5."',                  ".
			" firma6_ordenanza='".$ls_firma6."',cargo6_ordenanza='".$ls_cargo6."',                  ".
			" firma7_ordenanza='".$ls_firma7."',cargo7_ordenanza='".$ls_cargo7."',                  ".
			" firma8_ordenanza='".$ls_firma8."',cargo8_ordenanza='".$ls_cargo8."',                  ".
			" firma9_ordenanza='".$ls_firma9."',cargo9_ordenanza='".$ls_cargo9."',                  ".
			" firma10_ordenanza='".$ls_firma10."',cargo10_ordenanza='".$ls_cargo10."',                  ".
			" firma11_ordenanza='".$ls_firma11."',cargo11_ordenanza='".$ls_cargo11."'                  ".
		/*	" direccion='".$ls_direccion."', ciuemp='".$ls_ciuemp."', estemp='".$ls_estemp."',                ".
			" zonpos='".$ls_zonpos."', telemp='".$ls_telefono."', faxemp='".$ls_fax."',                       ".
			" email='".$ls_email."', website='".$ls_website."', m01=".$li_enero.", m02=".$li_febrero.",       ".
			" m03=".$li_marzo.", m04=".$li_abril.",m05=".$li_mayo.",m06=".$li_junio.",m07=".$li_julio.",      ".
			" m08=".$li_agosto.",m09=".$li_septiembre.",m10=".$li_octubre.",m11=".$li_noviembre.",            ".
			" m12=".$li_diciembre.",periodo='".$ls_periodo."',vali_nivel=".$ls_nivel.",                       ".
			" esttipcont=".$ls_tipocontabilidad.",formpre='".$ls_formpre."',                                  ".
			" formcont='".$ls_formcont."',formplan='".$ls_formplan."',formspi='".$ls_formspi."',              ".
			" activo='".$ls_activo."',pasivo='".$ls_pasivo."',ingreso='".$ls_ingreso."',                      ".
			" gasto='".$ls_gasto."',resultado='".$ls_resultado."',capital='".$ls_capital."',                  ".
			" c_resultad='".$ls_cresultad."',c_resultan='".$ls_cresultan."',                                  ".
			" orden_d='".$ls_ordend."',orden_h='".$ls_ordenh."',soc_gastos='".$ls_socgastos."',               ".
			" soc_servic='".$ls_socservic."',activo_h='".$ls_activoh."',pasivo_h='".$ls_pasivoh."',           ".
			" resultado_h='".$ls_resultadoh."',ingreso_f='".$ls_ingresof."',                                  ".
			" gasto_f='".$ls_gastof."',ingreso_p='".$ls_ingresop."',gasto_p='".$ls_gastop."',                 ".
			" logo='".$ls_logo."',numniv=".$li_numnivest.",nomestpro1='".$ls_nomestpro1."',                   ".
			" nomestpro2='".$ls_nomestpro2."',nomestpro3='".$ls_nomestpro3."',                                ".
			" nomestpro4='".$ls_nomestpro4."',nomestpro5='".$ls_nomestpro5."',                                ".
			" estvaltra=".$ls_estvaltra.",estmodape='".$ls_estmodape."',estdesiva='".$li_estdesiva."',        ".
			" codorgsig='".$ls_codorgsig."',rifemp='".$ls_rifemp."',nitemp='".$ls_nitemp."',                  ".
			" salinipro=".$ld_salinipro.",salinieje=".$ld_salinieje.",estmodest=".$li_estmodest.",            ".
			" numordcom='".$ls_numordcom."',numordser='".$ls_numordser."',numsolpag='".$ls_numsolpag."',      ".
			" nomorgads='".$ls_nomorgads."',numlicemp='".$ls_numlicemp."',modageret='".$ls_modgenret."',      ".
			" concomiva='".$ls_concomiva."',estmodiva=".$ls_estmodiva.",cedben='".$ls_cedben."',              ".
			" nomben='".$ls_nomben."',scctaben='".$ls_scctaben."',activo_t='".$ls_tesoroactivo."',            ".
			" pasivo_t='".$ls_tesoropasivo."',resultado_t='".$ls_tesororesul."',                              ".
			" c_financiera='".$ls_ctafinanciera."',c_fiscal='".$ls_ctafiscal."',diacadche='".$li_diacadche."',".
			" codasiona='".$ls_codasiona."',confi_ch='".$ls_confich."',confiva='".$ls_confiva."',             ".
			" nroivss='".$ls_ivss."', nomrep='".$ls_nomrep."', cedrep='".$ls_cedrep."',                       ".
			" telfrep='".$ls_telfrep."', cargorep='".$ls_cargo."',  estretiva='".$ls_estretiva."',            ".
			" confinstr='".$ls_confinstr."'																	  ".*/
			" WHERE codemp='".$ls_codemp."'                                                                   ";
//print $ls_sql;
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
		 $this->io_sql->rollback();
         $this->io_msg->message("CLASE->tepuy_CFG_C_EMPRESA; METODO->uf_update_empresa;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
         print $this->io_sql->message;   
	   }
	else
	   {
	   	 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			     $ls_evento="UPDATE";
			     $ls_descripcion ="Actualizó en SPG el nivel de validación ".$ls_nivel." Asociado a la empresa ".$this->ls_codemp;
			     $ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			     $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			     $aa_seguridad["ventanas"],$ls_descripcion);
	     /////////////////////////////////         SEGURIDAD               ///////////////////////////// 
		 $this->io_sql->commit();
		 $this->io_msg->message('Registro Actualizado !!!'); 

		 $this->io_rcbsf->io_ds_datos->insertRow("campo","saliniproaux");
		 $this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_salinipro);

		 $this->io_rcbsf->io_ds_datos->insertRow("campo","saliniejeaux");
		 $this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_salinieje);
		
		 $this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
		 $this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
		 $this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
	//	 $lb_valido=$this->io_rcbsf->uf_reconvertir_datos("tepuy_empresa",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
		 if($lb_valido)
		 {
			 $ls_sql  = " SELECT * FROM tepuy_empresa WHERE codemp='".$ls_codemp."'";
			 $rs_data = $this->io_sql->select($ls_sql);
			 if ($rs_data===false)
			 {
				  $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
			 }
			 else
			 {
				  if ($row=$this->io_sql->fetch_row($rs_data))
				  {
					   $_SESSION["la_empresa"]=$row;
				  } 
			 }
		 }
     }
} 
		
function uf_select_empresa() 
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Function:  uf_select_empresa()
//	Access:  public
//	Returns: $lb_existe   // Variable que indica si el Registro fue encontrado o no, 
//                           en la Tabla tepuy_empresa de la Base de Datos seleccionada.
//                           True=Encontrado y False=No Encontrado.             
//	Description:  Este método realiza una búsqueda del Código de Empresa 0001 dentro de la 
//                Tabla tepuy_empresa en la Base de Datos seleccionada.
/////////////////////////////////////////////////////////////////////////////////////////////////////////

	$lb_existe = false;
	$ls_sql    = " SELECT codemp FROM tepuy_empresa WHERE codemp='0001'";
	$rs_data   = $this->io_sql->select($ls_sql);
    if ($row=$this->io_sql->fetch_row($rs_data))
	   {
		 $lb_existe=true;
	     $this->io_sql->free_result($rs_data);
	   } 
    return $lb_existe;
}

function uf_existe_apertura($as_codemp,$as_anocur,&$rs_empresa) 
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	   Function: uf_existe_apertura($as_codemp,$as_anocur,&$rs_empresa)
	//    Arguments: $as_codemp  // codigo de la empresa
	//               $as_anocur  //  fecha ddel periodo
	//               $rs_empresa // ResultSet de referencia 
	//	    Returns: $lb_existe  // Variable que indica si el Registro fue encontrado o no, 
	//                           en la Tabla tepuy_empresa de la Base de Datos seleccionada.
	//                           True=Encontrado y False=No Encontrado.             
	//	Description:  Este método realiza una búsqueda del primer movimiento de apertura .
	/////////////////////////////////////////////////////////////////////////////////////////////////////////

	$ls_sql= " SELECT COUNT(*) as totalaper                ".
			  " FROM   tepuy_cmp                          ".
			  " WHERE  codemp='".$as_codemp."' AND         ".
			  "        procede='SPGAPR'  AND               ".
			  "        comprobante='0000000APERTURA'  AND  ".
			  "        fecha='".$as_anocur."'              ";
   $rs_data=$this->io_sql->select($ls_sql);
   if($rs_data===false)
   {
       $lb_existe=false;
	   $this->io_msg->message("CLASE->tepuy_cfg_c_empresa MÉTODO->uf_existe_apertura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
   }
   else
   {
     $lb_existe=false;
   }
  return  $lb_existe;
}

function uf_existe_ivaconfigurado($as_codemp,&$ai_totalcargos) 
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	   Function: uf_existe_ivaconfigurado($as_codemp,&$li_totalcargos)
	//    Arguments: $as_codemp  // codigo de la empresa
	//               $ai_totalcargos  //  total cragos (referencia)
	//	    Returns: $lb_existe  // Variable que indica si el Registro fue encontrado o no, 
	//                           en la Tabla tepuy_empresa de la Base de Datos seleccionada.
	//                           True=Encontrado y False=No Encontrado.             
	//	Description:  Este método realiza una búsqueda del primer movimiento de apertura .
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_existe=true;
	$ls_sql= " SELECT count(*) as totalcargos  ".
             " FROM   tepuy_cargos            ".
             " WHERE  codemp='".$as_codemp."'  ";
   $rs_data=$this->io_sql->select($ls_sql);
   if($rs_data===false)
   {
       $lb_existe=false;
	   $this->io_msg->message("CLASE->tepuy_cfg_c_empresa MÉTODO->uf_existe_apertura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
   }
   else
   {
	  if ($row=$this->io_sql->fetch_row($rs_data))
	  {
		 $ai_totalcargos=$row["totalcargos"];
	  }
   }
   return  $lb_existe;
}

}//Fin de la Clase...
?>
