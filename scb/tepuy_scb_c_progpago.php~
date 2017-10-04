<?php 
class tepuy_scb_c_progpago
{
	var $SQL;
	var $fun;
	var $msg;
	var $is_msg_error;	
	var $ds_sol;
	var $dat;
	var $io_seguridad;
	var $is_empresa;
	var $is_sistema;
	var $is_logusr;
	var $is_ventanas;
	function tepuy_scb_c_progpago($aa_security)
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/tepuy_include.php");
		$sig_inc=new tepuy_include();
		$con=$sig_inc->uf_conectar();
		$this->SQL=new class_sql($con);
		$this->fun=new class_funciones();
		$this->msg=new class_mensajes();
		$this->dat=$_SESSION["la_empresa"];
		$this->is_empresa = $aa_security["empresa"];
		$this->is_sistema = $aa_security["sistema"];
		$this->is_logusr  = $aa_security["logusr"];	
		$this->is_ventana = $aa_security["ventanas"];
		$this->io_seguridad= new tepuy_c_seguridad();
	}//Fin del constructor

function uf_cmb_mes($as_mes)
	{
		///////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cmb_mes
		//		   Access: public
		//		 Argument: $as_mes // numero que representa el mes en curso 
		//	  Description: Función que construye el combo de meses
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 26/09/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		switch ($as_mes) {
		   case '01':
			   $lb_selEnero="selected";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '02':
			   $lb_selEnero="";
			   $lb_selFebrero="selected";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '03':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="selected";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '04':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="selected";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '05':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="selected";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '06':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="selected";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;		   
		   case '07':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="selected";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;		   		 
		   case '08':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="selected";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;		   		 		     
		   case '09':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="selected";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '10':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="selected";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;		   
		   case '11':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="selected";	
			   $lb_selDiciembre="";
			   break;		   
		   case '12':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";

			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="selected";
			   break;		   
		}
	
		print "<select name=mes id=mes onchange=validarmes();>";
		print "<option value=01 ".$lb_selEnero.">ENERO</option>";
		print "<option value=02 ".$lb_selFebrero.">FEBRERO</option>";
		print "<option value=03 ".$lb_selMarzo.">MARZO</option>";
		print "<option value=04 ".$lb_selAbril.">ABRIL</option>";
		print "<option value=05 ".$lb_selMayo.">MAYO</option>";
		print "<option value=06 ".$lb_selJunio.">JUNIO</option>";
		print "<option value=07 ".$lb_selJulio.">JULIO</option>";
		print "<option value=08 ".$lb_selAgosto.">AGOSTO</option>";
		print "<option value=09 ".$lb_selSeptiembre.">SEPTIEMBRE</option>";
		print "<option value=10 ".$lb_selOctubre.">OCTUBRE</option>";
		print "<option value=11 ".$lb_selNoviembre.">NOVIEMBRE</option>";
		print "<option value=12 ".$lb_selDiciembre.">DICIEMBRE</option>";
		print "</select>";
	}

	function uf_cargar_solicitudes($ls_codemp,$ls_tipproben,$ai_tipvia,$ai_mes)
	{	

		//print "mes".$ai_mes."    ";
		$ls_a=substr($_SESSION["la_empresa"]["periodo"],0,4);
		$ld_fecdesde = "01/".$ai_mes."/".$ls_a;  	//."/2013";
		$ld_fechasta = "31/".$ai_mes."/".$ls_a; 	//"/2013";
		$ld_fecdesde = $this->fun->uf_convertirdatetobd($ld_fecdesde);
		$ld_fechasta = $this->fun->uf_convertirdatetobd($ld_fechasta);
		

		$ls_cadaux = "";
		if ($ls_tipproben=='P')
		   {
			 $ls_tabla   = 'rpc_proveedor ';
			 $ls_columna = 'nompro as nomproben';
			 $ls_campo   = 'cod_pro ';
			 $ls_aux     = 'AND rpc_proveedor.codemp=cxp_dt_solicitudes.codemp AND rpc_proveedor.cod_pro=cxp_dt_solicitudes.cod_pro';
		   }
		elseif($ls_tipproben=='B')
		   {
			 $ls_tabla   = 'rpc_beneficiario ';
			 $ls_columna = 'nombene, apebene ';
			 $ls_campo   = 'ced_bene ';
			 $ls_aux     = 'AND rpc_beneficiario.codemp=cxp_dt_solicitudes.codemp AND rpc_beneficiario.ced_bene=cxp_dt_solicitudes.ced_bene';
			 if ($ai_tipvia=='1')
			   {
				 $ls_cadaux = " AND cxp_rd.procede='SCVSOV' ";
			   } 
			 else
			   {
				 $ls_cadaux = " AND cxp_rd.procede<>'SCVSOV' "; 
			   } 
		   }		
		
		$ls_sql = "SELECT DISTINCT cxp_solicitudes.numsol as numsol,
								   cxp_solicitudes.$ls_campo as codproben,
								   cxp_solicitudes.fecemisol as fecemisol,
								   cxp_solicitudes.tipproben as tipproben,
								   cxp_solicitudes.fecpagsol as fecpagsol,
								   cxp_solicitudes.consol as consol,
								   cxp_solicitudes.estprosol as estprosol,
								   cxp_solicitudes.monsol as monsol,
								   cxp_solicitudes.obssol as obssol,
								   $ls_tabla.$ls_columna,
								   cxp_rd.procede as procede
							  FROM cxp_solicitudes, $ls_tabla, cxp_rd, cxp_dt_solicitudes
							 WHERE cxp_solicitudes.codemp='".$ls_codemp."'
							   AND cxp_solicitudes.tipproben='".$ls_tipproben."'
							   AND cxp_solicitudes.estprosol='C' $ls_cadaux
							   AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp
							   AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol
							   AND cxp_solicitudes.fechaconta >= '".$ld_fecdesde."' AND cxp_solicitudes.fechaconta <= '".$ld_fechasta."'
							   AND cxp_rd.codemp=cxp_dt_solicitudes.codemp
							   AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc
							   AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc
							   AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro
							   AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene $ls_aux
							   AND cxp_solicitudes.numsol NOT IN (SELECT numsol
																	FROM scb_prog_pago
																   WHERE codemp='".$ls_codemp."'
																	 AND numsol=cxp_solicitudes.numsol) ORDER BY cxp_solicitudes.numsol";
		$rs_data = $this->SQL->select($ls_sql);//print $ls_sql;
		if ($rs_data===false)
		   {
			 $lb_valido=false;
			 $this->is_msg_error="Error en metodo uf_cargar_solicitudes".$this->fun->uf_convertirmsg($this->SQL->message);
			 $data="";
		   }
		else
		   {
			 $li_numrows = $this->SQL->num_rows($rs_data);
			 if ($li_numrows>0)
				{
				  $data=$this->SQL->obtener_datos($rs_data);
				}
			 else
				{
				  $data="";
				  $this->is_msg_error="No encontro registros";
				}
		   }
		return $data;
	}//Fin uf_cargar_solicitudes
	
	function uf_procesar_programacion($ls_numsol,$ld_fecpropag,$ls_estmov,$ls_codban,$ls_ctaban,$ls_provbene,$ls_tipo,$ai_tipvia)
	{
		//////////////////////////////////////////////////////////////////////////////
		//
		//	Function:	uf_procesar_programacion
		// 					
		// Access:		public
		//
		//	Returns:	Boolean Retorna si proceso correctamente
		//
		//	Description:	Funcion que se encarga de guardar el movimiento bien sea 
		//						insertando o actualizando
		//               
		//////////////////////////////////////////////////////////////////////////////
		
		$li_ds_total=0;$li_x=0;
	
			
		//if not isnull(ads_data) then
	
			$ls_codemp   = $this->dat["codemp"];
			$ls_codusu   = $_SESSION["la_logusr"];			 
		
			$this->is_msg_error="";
			$ls_sql="INSERT INTO scb_prog_pago(codemp,codban,ctaban,codusu,numsol,fecpropag,estmov,esttipvia)
					 VALUES('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_codusu."','".$ls_numsol."','".$this->fun->uf_convertirdatetobd($ld_fecpropag)."','P',".$ai_tipvia.")";
			
			
			$li_result=$this->SQL->execute($ls_sql);
			if(($li_result===false))
			{
				$this->is_msg_error = "Error al programar solicitud ".$ls_numsol.", ".$this->fun->uf_convertirmsg($this->SQL->message);
				$lb_valido = false;
			}
			else
			{
					$lb_valido=true;
					////////////////////////Seguridad///////////////////////////////////////////////////////////
					$ls_evento="INSERT";
					$ls_descripcion="Programo la solicitud  ".$ls_numsol." perteneciente al  Proveedor/Beneficiario ".$ls_provbene." para el banco ".$ls_codban." y la cuenta ".$ls_ctaban." (Inserta en la table scb_prog_pago)";
					$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
					////////////////////////////////////////////////////////////////////////////////////////////
			}	
			
			if($lb_valido)
			{
				
				$ls_sql="UPDATE cxp_solicitudes
						 SET 	estprosol  = 'S'
						 WHERE   codemp ='".$ls_codemp."' AND numsol = '".$ls_numsol."'";
				
				$li_result_upd=$this->SQL->execute($ls_sql);		
				if(($li_result_upd===false))
				{
					$this->is_msg_error   = "Error actualizando solicitud ".$ls_numsol.",".$this->fun->uf_convertirmsg($this->SQL->message);
					$lb_valido      = false;
					
				}
				else
				{
						$lb_valido=true;
						/////////////////////////Seguridad//////////////////////////////////////////////////////////////////////////////
						$ls_evento="UPDATE";
						$ls_descripcion="Programo la solicitud  ".$ls_numsol." perteneciente al  Proveedor/Beneficiario ".$ls_provbene." para el banco ".$ls_codban." y la cuenta ".$ls_ctaban." (Actualiza estatus en tabla cxp_solicitudes)";
						$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
						/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				}
				
				$ls_sql="INSERT INTO cxp_historico_solicitud(codemp, numsol, fecha, estprodoc)
						 VALUES('".$ls_codemp."','".$ls_numsol."','".$this->fun->uf_convertirdatetobd($ld_fecpropag)."','S')";
				
				$li_result_upd=$this->SQL->execute($ls_sql);		
				if(($li_result_upd===false))
				{
					$this->is_msg_error   = "Error actualizando solicitud ".$ls_numsol.",".$this->fun->uf_convertirmsg($this->SQL->message);
					$lb_valido      = false;					
				}
				else
				{
						$lb_valido=true;
						/////////////////////////Seguridad//////////////////////////////////////////////////////////////////////////////
						$ls_evento="INSERT";
						$ls_descripcion="Inserto la programacion en el historico para la solicitud ".$ls_numsol;
						$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
						/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				}
				
			}	
		return $lb_valido;

	}//Fin de uf_procesar_programacion
	
	
function uf_load_recepciones_asociadas($as_codemp,$as_numsol,&$lb_valido)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_load_recepciones_asociadas
//	          Access:  public
//	        Arguments   
//        $as_codemp:  Código de la Empresa.
//        $as_numsol:  Número de Identificación de la Solicitud de Pago.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de seleccionar los números de las recepciones de documentos
//                     involucradas en una solicitud de pago. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  17/04/2007       Fecha Última Actualización:
////////////////////////////////////////////////////////////////////////////// 
  $lb_valido = true;
  $ls_sql    = "SELECT ced_bene,monto FROM cxp_dt_solicitudes WHERE codemp='".$as_codemp."' AND numsol='".$as_numsol."'";
  $rs_data   = $this->SQL->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
 	   $this->msg->message("CLASE->tepuy_SCB_C_PROGPAGO; METODO->uf_load_recepciones_asociadas; ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
	 } 
  return $rs_data;
}	
	
	function uf_load_notas_asociadas($as_codemp,$as_numsol,&$ai_montonotas)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_load_notas_asociadas
		//	          Access:  public
		//	        Arguments  as_codemp //  Código de la Empresa.
		//                     as_numsol //  Número de Identificación de la Solicitud de Pago.
		//                     ai_montonotas //  monto de las Notas de Débito y Crédito.
		//	         Returns:  lb_valido.
		//	     Description:  Función que se encarga de buscar las notas de debito y crédito asociadas a la solicitud de pago. 
		//     Elaborado Por:  Ing. Miguel Palencia
		// Fecha de Creación:  26/09/2007       Fecha Última Actualización:
		////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=true;
		$ai_montonotas=0;
		$ls_sql= "SELECT SUM(CASE cxp_sol_dc.codope WHEN 'NC' THEN (-1*cxp_sol_dc.monto) ".
			   "                                 			ELSE (cxp_sol_dc.monto) END) as total ".
			   "  FROM cxp_dt_solicitudes, cxp_sol_dc ".
			   " WHERE cxp_dt_solicitudes.codemp='".$as_codemp."' ".
			   "   AND cxp_dt_solicitudes.numsol='".$as_numsol."' ".
			   "   AND cxp_sol_dc.estnotadc= 'C' ".
			   "   AND cxp_dt_solicitudes.codemp = cxp_sol_dc.codemp ".
			   "   AND cxp_dt_solicitudes.numsol = cxp_sol_dc.numsol ".
			   "   AND cxp_dt_solicitudes.numrecdoc = cxp_sol_dc.numrecdoc ".
			   "   AND cxp_dt_solicitudes.codtipdoc = cxp_sol_dc.codtipdoc ".
			   "   AND cxp_dt_solicitudes.ced_bene = cxp_sol_dc.ced_bene ".
			   "   AND cxp_dt_solicitudes.cod_pro = cxp_sol_dc.cod_pro";
		$rs_data=$this->SQL->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_load_notas_asociadas".$this->fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_data))
			{
				$ai_montonotas=$row["total"];
			}
		}
		return $lb_valido;
	}	
}
?>
