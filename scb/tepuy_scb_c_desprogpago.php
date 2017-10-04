<?php 
class tepuy_scb_c_desprogpago
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
	function tepuy_scb_c_desprogpago($aa_security)
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_seguridad= new tepuy_c_seguridad();
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
	}//Fin del constructor

	function uf_cargar_programaciones($ls_codemp,$as_proben)
	{
		//////////////////////////////////////////////////////////////////////////////
		//
		//	Function:		uf_cargar_programaciones
		// 					
		// Access:			public
		//
		//	Returns:			Boolean Retorna si encontro o no errores en la consulta
		//
		//	Description:	Funcion que se encarga de llenar el datastore con los datos de
		//						las programaciones para el proceso de cancelaciones
		//                desprogramacion de pagos
		//               
		//////////////////////////////////////////////////////////////////////////////
	
		$li_row_total=0;$li_dw_row=0;$li_x=0;$li_row=0;
		

	if($as_proben== 'P')
	{
			$ls_sql="SELECT a.numsol as numsol,a.cod_pro as codproben ,a.fecemisol as fecemisol,a.consol as consol,a.estprosol as estprosol,a.monsol as monsol,a.obssol as obssol,b.nompro as nomproben,c.fecpropag as fecpropag,c.codban as codban,c.ctaban as ctaban,d.nomban as nomban,e.dencta as dencta
					FROM   cxp_solicitudes a,rpc_proveedor b,scb_prog_pago c,scb_banco d,scb_ctabanco e
					WHERE  a.cod_pro=b.cod_pro AND a.codemp='".$ls_codemp."' AND a.tipproben='".$as_proben."' AND a.estprosol ='S' AND a.numsol=c.numsol AND c.estmov='P' AND c.codban=d.codban AND c.codban=e.codban AND c.ctaban=e.ctaban
					AND    a.codemp=b.codemp AND a.codemp=c.codemp AND a.codemp = d.codemp AND a.codemp=e.codemp AND a.numsol NOT IN (SELECT numsol FROM cxp_sol_banco WHERE estmov<>'A' AND estmov<>'O') ORDER BY a.numsol asc";
	}
	elseif($as_proben=='B')
	{
		  $ls_sql="SELECT   a.numsol as numsol,a.ced_bene as codproben,b.nombene as nomproben,a.fecemisol as fecemisol,c.fecpropag as fecpropag,a.consol as consol,a.estprosol as estprosol,a.monsol as monsol,a.obssol as obssol,c.codban as codban,d.nomban as nomban,c.ctaban as ctaban,e.dencta as dencta
				   FROM     cxp_solicitudes a, rpc_beneficiario b , scb_prog_pago c,scb_banco d,scb_ctabanco e
		  		   WHERE    a.codemp=b.codemp AND a.codemp=c.codemp AND a.codemp= d.codemp AND a.codemp=e.codemp AND a.codemp='".$ls_codemp."' AND a.tipproben='".$as_proben."' AND a.numsol=c.numsol  AND a.ced_bene=b.ced_bene AND a.estprosol='S' 
				   AND      c.codban=d.codban AND c.ctaban=e.ctaban AND d.codban=e.codban AND c.estmov='P' AND a.numsol NOT IN (SELECT numsol FROM cxp_sol_banco WHERE estmov<>'A' AND estmov<>'O')
		   		   ORDER BY a.numsol asc";
	}
		
		$rs_programaciones	=$this->SQL->select($ls_sql);//print $ls_sql;
		
		if($rs_programaciones===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_cargar_solicitudes".$this->fun->uf_convertirmsg($this->SQL->message);
			$data="";
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_programaciones))
			{
				$data=$this->SQL->obtener_datos($rs_programaciones);		
			}
			else
			{
				$data="";
				$this->is_msg_error="No encontro registros";
			}
			$this->SQL->free_result($rs_programaciones);
		}
		return $data;
	}//Fin uf_cargar_solicitudes
	
	function uf_procesar_desprogramacion($ls_numsol,$ld_fecpropag,$ls_estmov,$ls_codban,$ls_ctaban,$ls_provbene,$ls_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////
		//
		//	Function:	uf_procesar_desprogramacion
		// 					
		// Access:			public
		//
		//	Returns:			Boolean Retorna si proceso correctamente
		//
		//	Description:	Funcion que se encarga de realizar la desprogramacion de 
		//						los pagos a proveedores o beneficiarios
		//               
		//////////////////////////////////////////////////////////////////////////////
		
		
		$li_ds_total=0;$li_x=0;
		
		$lb_valido = true;
		//if not isnull(ads_data) then
			
			$ls_codemp   = $this->dat["codemp"];
			$ls_codusu   = $_SESSION["la_logusr"];
		
//		$lb_existe= uf_existe_movimiento( ls_numdoc,ls_codope,ls_codban,ls_ctaban,ls_codemp)
		
		//if lb_existe=false then
			$this->is_msg_error="";
			$ls_sql="DELETE 
					FROM  scb_prog_pago
					WHERE codemp='".$ls_codemp."' AND numsol='".$ls_numsol."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND fecpropag='".$this->fun->uf_convertirdatetobd($ld_fecpropag)."'";
			
			$li_result=$this->SQL->execute($ls_sql);
			if($li_result===false)
			{
				$lb_valido=false;
				$this->is_msg_error="Error al desprogramar solicitud ".$ls_numsol.", ".$this->fun->uf_convertirdatetobd($this->SQL->message);
				print $this->is_msg_error;
			}
			else
			{
				$lb_valido=true;
				////////////////////////Seguridad///////////////////////////////////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion="Se Desprogramo la soliciutd ".$ls_numsol." perteneciente al Proveedor/Beneficiario ".$ls_provbene." para el banco ".$ls_codban." y la cuenta ".$ls_ctaban." (Elimina en tabla scb_prog_pago)";
				$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
				////////////////////////////////////////////////////////////////////////////////////////////
			}
			
		//	UPDATE SCB_prog_pago
		//	SET    estmov=:ls_estmov
		//	WHERE  codemp=:ls_codemp AND numsol=:ls_numsol AND codban=:ls_codban AND ctaban=:ls_ctaban AND fecpropag=:ldt_fecpropag
		//	USING Sqlca;
			
			if($lb_valido)
			{
				
				$ls_sql="UPDATE cxp_solicitudes
						SET 	estprosol = 'C'
						WHERE   codemp	  = '".$ls_codemp."' AND numsol ='".$ls_numsol."'";
				$li_result=$this->SQL->execute($ls_sql);
				if(($li_result===false))
				{
					$lb_valido=false;
					$this->is_message_error="Error al actualizar estatus de solicitud ".$ls_numsol.", ".$this->fun->uf_convertirdatetobd($this->SQL->message);
					print $this->is_msg_error;
				}
				else
				{
					$lb_valido=true;
					////////////////////////Seguridad///////////////////////////////////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion="Se Desprogramo la soliciutd ".$ls_numsol." perteneciente al Proveedor/Beneficiario ".$ls_provbene." para el banco ".$ls_codban." y la cuenta ".$ls_ctaban." (Actualiza estatus en tabla cxp_solicitudes)";
					$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
					////////////////////////////////////////////////////////////////////////////////////////////
				}
				
				$ls_sql="DELETE FROM cxp_historico_solicitud
						 WHERE   codemp	  = '".$ls_codemp."' AND numsol ='".$ls_numsol."' AND fecha='".$this->fun->uf_convertirdatetobd($ld_fecpropag)."' AND  estprodoc='S'";
				$li_result=$this->SQL->execute($ls_sql);
				if(($li_result===false))
				{
					$lb_valido=false;
					$this->is_message_error="Error al actualizar estatus de solicitud ".$ls_numsol.", ".$this->fun->uf_convertirdatetobd($this->SQL->message);
					print $this->is_msg_error;
				}
				else
				{
					$lb_valido=true;
					////////////////////////Seguridad///////////////////////////////////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion="Se elimino la programacion del historico para la soliciutd ".$ls_numsol;
					$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
					////////////////////////////////////////////////////////////////////////////////////////////						
				}
				
			}
		
		return $lb_valido;

	}//Fin de uf_procesar_programacion
	
	
	
	
}
?>
