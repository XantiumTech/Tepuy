<?php
class tepuy_ins_c_reprocesar_comprobantes
{
	var $io_sql;
	var $io_message;
	var $io_function;
	var $is_msg_error;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_ins_c_reprocesar_comprobantes()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("../shared/class_folder/class_funciones.php");	
		require_once("../shared/class_folder/class_tepuy_int.php");
		require_once("../shared/class_folder/class_tepuy_int_scg.php");	
		require_once("../shared/class_folder/class_tepuy_int_spg.php");			
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		require_once("../shared/class_folder/class_tepuy_int.php");
		require_once("../shared/class_folder/class_tepuy_int_int.php");
		require_once("../shared/class_folder/class_tepuy_int_spg.php");
		require_once("../shared/class_folder/class_tepuy_int_scg.php");
		require_once("../shared/class_folder/class_tepuy_int_spi.php");
		$io_siginc=new tepuy_include();
		$con=$io_siginc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->io_message=new class_mensajes();
		$this->io_function=new class_funciones();
		$this->io_int_spg=new class_tepuy_int_spg();
		$this->io_seguridad=new tepuy_c_seguridad();
        $this->io_tepuy_int=new class_tepuy_int_int();
		$this->io_tepuy_int_spg = new class_tepuy_int_spg();
		$this->io_tepuy_int_scg = new class_tepuy_int_scg();		
		$this->io_tepuy_int_spi = new class_tepuy_int_spi();		
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function tepuy_ins_c_reprocesar_comprobantes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reprocesar_comprobantes_scb($aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	     Function: uf_reprocesar_comprobantes_scb
		// 	       Access: public
		//      Arguments: $aa_seguridad
		//	      Returns: Boolean
		//    Description: Esta funcion verifica que los comprobantes de banco que est�n contabilizados tambien
		//				   se encuentren en contabilidad, presupuesto de gasto y presupuesto de ingreso en caso
		//				   de que no se encuentren los genera
		//	   Creado Por: Ing. Miguel Palencia
		// Modificado Por: 											Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codban,ctaban,estmov,numdoc,fecmov,conmov,codope,tipo_destino,ced_bene,cod_pro,conmov ".
                "  FROM scb_movbco ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND estmov='C' ".
				"   AND estmodordpag<>'CM'";
		$this->io_sql->begin_transaction();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("CLASE->Reprocesar Comprobantes M�TODO->uf_reprocesar_comprobantes_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i=$li_i+1;
				$ls_codban=$row["codban"];
				$ls_ctaban=$row["ctaban"];
				$ls_estmov=$row["estmov"];
				$ls_numdoc=$row["numdoc"];
				$ls_fecmov=$row["fecmov"];
				$ls_conmov=$row["conmov"];
				$ls_codope=$row["codope"];
				$ls_tipo=$row["tipo_destino"];  		
				$ls_ced_bene=$row["ced_bene"];
				$ls_cod_pro=$row["cod_pro"];
				$ls_descripcion=$row["conmov"];
			    $ls_procede="SCBB".$ls_codope;
				$ls_comprobante=$ls_numdoc;
			    $lb_existe=$this->io_tepuy_int->uf_obtener_comprobante($this->ls_codemp,$ls_procede,$ls_comprobante,&$ls_fecmov,
																		$ls_codban,$ls_ctaban,&$ls_tipo,&$ls_ced_bene,&$ls_cod_pro);
				if($lb_existe===false)
				{
					$lb_valido=$this->uf_insertar_movimiento_scb($this->ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,
																 $ls_estmov,'N',$ls_procede,$ls_comprobante,$ls_fecmov,'1900-01-01');
					if($lb_valido)
					{
						$lb_valido=$this->uf_delete_movimiento_scb($this->ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,
																   $ls_estmov);
					}
				}
				else
				{
					$lb_valido=$this->uf_verificar_gasto_scb($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov,$ls_fecmov,$ls_procede,$ls_descripcion);
					if($lb_valido)
					{
						$lb_valido=$this->uf_verificar_contabilidad_scb($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov,$ls_fecmov,$ls_procede,$ls_descripcion);					
					}
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso los comprobantes descuadrados del sistema de Caja y Banco";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_comprobantes_scb
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_insertar_movimiento_scb($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_estmov_new,
										$as_procede,$as_comprobante,$adt_fecmov,$adt_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insertar_movimiento_scb
		//		   Access: private
		//	    Arguments: as_codemp // C�digo de Empresa
		//	    		   as_codban // C�digo de Banco
		//	    		   as_ctaban // Cuenta Banco
		//	    		   as_numdoc // N�mero de Documento
		//	    		   as_codope // C�digo de Operaci�n
		//	    		   as_estmov // estatus del Movimiento
		//	    		   as_estmov_new // Nuevo estatus del Movimiento
		//	    		   as_procede // Procede del documento
		//	    		   as_comprobante // comprobante
		//	    		   adt_fecha // Fecha para contabilizar
		//	      Returns: lb_valido True si se encontro el movimiento � false si no se encontro
		//	  Description: Funcion que crea un nuevo registro de banco al cambiar el estatus del mismo
		//	   Creado Por: Ing. Wilmer Brice�o
		// Modificado Por: Ing. Miguel Palencia								Fecha �ltima Modificaci�n : 02/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "INSERT INTO scb_movbco (codemp,codban,ctaban,numdoc,codope,estmov,cod_pro,ced_bene,".
		          "                        tipo_destino, codconmov, fecmov, conmov, nomproben, monto, ".
				  "                        estbpd, estcon, estcobing, esttra, chevau, estimpche, ".
				  "                        monobjret, monret, procede, comprobante, fecha, id_mco,".
				  "                        emicheproc, emicheced, emichenom, emichefec, estmovint, ".
				  "                        codusu, codopeidb, aliidb, feccon, estreglib, numcarord,".
				  "                        numpolcon,coduniadmsig,codbansig,fecordpagsig,tipdocressig,".
				  "                        numdocressig,estmodordpag,codfuefin,forpagsig,medpagsig,codestprosig,fechaconta,fechaanula ) ".
				  " SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',cod_pro,ced_bene,".
		          "        tipo_destino, codconmov, '".$adt_fecmov."', conmov, nomproben, monto, ".
				  "        estbpd, estcon, estcobing, esttra, chevau, estimpche, ".
				  "        monobjret, monret,'".$as_procede."','".$as_comprobante."','".$adt_fecha."',id_mco,".
				  "        emicheproc, emicheced, emichenom, emichefec, estmovint, ".
				  "        codusu, codopeidb, aliidb, feccon, estreglib, numcarord, ".
				  "        numpolcon,coduniadmsig,codbansig,fecordpagsig,tipdocressig,".
				  "        numdocressig,estmodordpag,codfuefin,forpagsig,medpagsig,codestprosig,fechaconta,fechaanula	".			  
				  "  FROM scb_movbco ".
                  " WHERE codemp='".$as_codemp."' ".
				  "	  AND codban='".$as_codban."' ".
				  "   AND ctaban='".$as_ctaban."' ".
				  "   AND numdoc='".$as_numdoc."' ".
				  "   AND codope='".$as_codope."' ".
				  "   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_message->message("CLASE->Instala M�TODO->uf_insertar_movimiento_scb 1 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle contables
		$ls_sql = "INSERT INTO scb_movbco_scg (codemp, codban, ctaban, numdoc, codope, estmov, scg_cuenta,".
		          "                            debhab, codded, documento, desmov, procede_doc, monto, monobjret) ".
				  " SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',scg_cuenta,".
				  "        debhab, codded, documento, desmov, procede_doc, monto, monobjret".
				  "  FROM scb_movbco_scg ".
                  " WHERE codemp='".$as_codemp."' ".
				  "   AND codban='".$as_codban."' ".
				  "   AND ctaban='".$as_ctaban."' ".
				  "   AND numdoc='".$as_numdoc."' ".
				  "   AND codope='".$as_codope."' ".
				  "   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_message->message("CLASE->Instala M�TODO->uf_insertar_movimiento_scb 2 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle de gastos
		$ls_sql = " INSERT INTO scb_movbco_spg (codemp,codban,ctaban,numdoc,codope,estmov,codestpro,".
		          "                             spg_cuenta,operacion,documento,desmov,procede_doc,monto) ".
				  " SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',codestpro,spg_cuenta,".
				  "        operacion,documento,desmov,procede_doc,monto ".
				  " FROM scb_movbco_spg ".
                  " WHERE codemp='".$as_codemp."' ".
				  "   AND codban='".$as_codban."' ".
				  "   AND ctaban='".$as_ctaban."' ".
				  "   AND numdoc='".$as_numdoc."' ".
				  "   AND codope='".$as_codope."' ".
				  "   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_message->message("CLASE->Instala M�TODO->uf_insertar_movimiento_scb 3 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle de gastos
		$ls_sql = " INSERT INTO scb_movbco_spgop (codemp,codban,ctaban,numdoc,codope,estmov,codestpro,".
		          "                             spg_cuenta,operacion,documento,coduniadm,desmov,procede_doc,monto) ".
				  "SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',codestpro,spg_cuenta,".
				  "        operacion,documento,coduniadm,desmov,procede_doc,monto ".
				  "  FROM scb_movbco_spgop ".
                  " WHERE codemp='".$as_codemp."' ".
				  "   AND codban='".$as_codban."' ".
				  "   AND ctaban='".$as_ctaban."' ".
				  "   AND numdoc='".$as_numdoc."' ".
				  "   AND codope='".$as_codope."' ".
				  "   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_message->message("CLASE->Instala M�TODO->uf_insertar_movimiento_scb 4 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle de ingresos
		$ls_sql = " INSERT INTO scb_movbco_spi (codemp,codban,ctaban,numdoc,codope,estmov,spi_cuenta,".
		          "                             documento,operacion,desmov,procede_doc,monto) ".
				  " SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',spi_cuenta,".
				  "        documento,operacion,desmov,procede_doc,monto ".
				  "   FROM scb_movbco_spi ".
                  "  WHERE codemp='".$as_codemp."' ".
				  "    AND codban='".$as_codban."' ".
				  "    AND ctaban='".$as_ctaban."' ".
				  "    AND numdoc='".$as_numdoc."' ".
				  "    AND codope='".$as_codope."' ".
				  "    AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_message->message("CLASE->Instala M�TODO->uf_insertar_movimiento_scb 5 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle de fuentes de financiamiento
		$ls_sql = " INSERT INTO scb_movbco_fuefinanciamiento (codemp, codban, ctaban, numdoc, codope, estmov, codfuefin) ".
				  " SELECT codemp,codban,ctaban,numdoc,codope,'".$as_estmov_new."',codfuefin ".
				  "   FROM scb_movbco_fuefinanciamiento ".
                  "  WHERE codemp='".$as_codemp."' ".
				  "    AND codban='".$as_codban."' ".
				  "    AND ctaban='".$as_ctaban."' ".
				  "    AND numdoc='".$as_numdoc."' ".
				  "    AND codope='".$as_codope."' ".
				  "    AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_message->message("CLASE->Instala M�TODO->uf_insertar_movimiento_scb 6 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		// SI NO ES ANULADO ENTONCES NO SE CREA 
        if(($as_estmov_new!="A")||($as_estmov_new!="O")) 
		{
			// transferencia al nuevo registro de solicitud banco
			$ls_sql = " INSERT INTO cxp_sol_banco (codemp,numsol,codban,ctaban,numdoc,codope,estmov,monto,id) ".
					  " SELECT codemp,numsol,codban,ctaban,numdoc,codope,'".$as_estmov_new."',monto,id".
					  "   FROM cxp_sol_banco ".
					  "  WHERE codemp='".$as_codemp."' ".
					  "	   AND codban='".$as_codban."' ".
					  "    AND ctaban='".$as_ctaban."' ".
					  "    AND numdoc='".$as_numdoc."' ".
					  "    AND codope='".$as_codope."' ".
					  "    AND estmov='".$as_estmov."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{   
           		$this->io_message->message("CLASE->Instala M�TODO->uf_insertar_movimiento_scb 7 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				return false;
			}
		}
		return $lb_valido;
	}// end function uf_insertar_movimiento_scb
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_movimiento_scb($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_movimiento_scb
		//		   Access: private
		//	    Arguments: as_codemp // C�digo de Empresa
		//	    		   as_codban // C�digo de Banco
		//	    		   as_ctaban // Cuenta Banco
		//	    		   as_numdoc // N�mero de Documento
		//	    		   as_codope // C�digo de Operaci�n
		//	    		   as_estmov // estatus del Movimiento
		//	      Returns: lb_valido True si se encontro el movimiento � false si no se encontro
		//	  Description: M�todo que elimina el movimiento referente al banco en la solicitud de pago banco
		//                  se eliminar� el que contiene el antiguo estatus previo a la contabilizacion del movimiento 
		//	   Creado Por: Ing. Wilmer Brice�o
		// Modificado Por: Ing. Miguel Palencia								Fecha �ltima Modificaci�n : 03/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	    $ls_sql="DELETE FROM cxp_sol_banco ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_message->message("CLASE->Instala M�TODO->uf_delete_movimiento_scb 1 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		} 
		$ls_sql="DELETE FROM scb_movbco_spg ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_message->message("CLASE->Instala M�TODO->uf_delete_movimiento_scb 2 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		$ls_sql="DELETE FROM scb_movbco_spgop ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_message->message("CLASE->Instala M�TODO->uf_delete_movimiento_scb 3 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		$ls_sql="DELETE FROM scb_movbco_spi ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_message->message("CLASE->Instala M�TODO->uf_delete_movimiento_scb 4 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco detalle contables
		$ls_sql="DELETE FROM scb_movbco_scg ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_message->message("CLASE->Instala M�TODO->uf_delete_movimiento_scb 5 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
        // transferencia al nuevo registro de banco fuente de financimiento
		$ls_sql="DELETE FROM scb_movbco_fuefinanciamiento ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_msg->message("CLASE->Integraci�n SCB M�TODO->uf_delete_movimiento_banco 7 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		$ls_sql="DELETE FROM scb_movbco ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{   
           	$this->io_message->message("CLASE->Instala M�TODO->uf_delete_movimiento_scb 6 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		return $lb_valido;
	}// end function uf_delete_movimiento_scb
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_gasto_scb($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_fecmov,$as_procede,$as_descripcion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_gasto_scb
		//		   Access: private
		//	    Arguments: as_descripcion // Descripci�n del comprobante
		//	      Returns: lb_valido True si se encontro el movimiento � false si no se encontro
		//	  Description: Funcion que obtiene el los movimientos de presupuesto y los agrega al datastored
		//	   Creado Por: Ing. Wilmer Brice�o
		// Modificado Por: Ing. Miguel Palencia								Fecha �ltima Modificaci�n : 02/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * ".
                "  FROM scb_movbco_spg ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_message->message("CLASE->Instala M�TODO->uf_verificar_gasto_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{   
			$li_orden=-1;              
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
		    {
				$li_orden=$li_orden+1;
			    $ls_codestpro=$row["codestpro"];
				$ls_codestpro1=substr($ls_codestpro,0,20);
				$ls_codestpro2=substr($ls_codestpro,20,6);
				$ls_codestpro3=substr($ls_codestpro,26,3);
				$ls_codestpro4=substr($ls_codestpro,29,2);
				$ls_codestpro5=substr($ls_codestpro,31,2);
				$ls_spg_cuenta=$row["spg_cuenta"];
				$ldec_monto=$row["monto"];
			    $ls_procede_doc=$row["procede_doc"];
 			    $ls_documento=$row["documento"];
				$ls_mensaje=$row["operacion"];
				$ls_spg_cuenta=$this->io_tepuy_int_spg->uf_spg_pad_cuenta($ls_spg_cuenta);
				$ls_sql="SELECT spg_cuenta,monto,orden ".
						"  FROM spg_dt_cmp ".		
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND codestpro1 = '".$ls_codestpro1."' ".
						"   AND codestpro2 = '".$ls_codestpro2."' ". 
						"   AND codestpro3 = '".$ls_codestpro3."' ".
						"   AND codestpro4 = '".$ls_codestpro4."' ".
						"   AND codestpro5 = '".$ls_codestpro5."'  ".
						"   AND procede = '".$as_procede."' ".
						"   AND comprobante = '".$as_numdoc."' ".
						"   AND fecha = '".$as_fecmov."' ".
						"   AND documento = '".$ls_documento."' ".
						"   AND spg_cuenta = '".$ls_spg_cuenta."'  ".
						"   AND operacion = '".$ls_mensaje."' "; 
				$rs_data1=$this->io_sql->select($ls_sql);
				if($rs_data1===false)
				{   
					$this->io_message->message("CLASE->Reprocesar Comprobantes M�TODO->uf_verificar_gasto_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				}
				else
				{                 
					if(!($row=$this->io_sql->fetch_row($rs_data1)))
					{
						$ls_sql="INSERT INTO spg_dt_cmp (codemp,procede,comprobante,fecha,codestpro1,codestpro2,codestpro3,codestpro4,".
								"codestpro5,spg_cuenta,procede_doc,documento,operacion,descripcion,monto,orden)".
								" VALUES('".$this->ls_codemp."','".$as_procede."','".$as_numdoc."','".$as_fecmov."','".$ls_codestpro1."',".
								"'".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."',".
								"'".$ls_spg_cuenta."','".$ls_procede_doc."','".$ls_documento."','".$ls_mensaje."','".$as_descripcion."','".$ldec_monto."',".$li_orden.")"; 
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{   
							$this->io_message->message("CLASE->Reprocesar Comprobantes M�TODO->uf_verificar_gasto_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
							return false;
						}
					}
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
    }// end function uf_verificar_gasto_scb
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_contabilidad_scb($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_fecmov,$as_procede,$as_descripcion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_contabilidad_scb
		//		   Access: private
		//	    Arguments: as_descripcion // Descripci�n del comprobante
		//	      Returns: lb_valido True si se encontro el movimiento � false si no se encontro
		//	  Description: Funcion que obtiene el los movimientos de presupuesto y los agrega al datastored
		//	   Creado Por: Ing. Miguel Palencia	
		// Modificado Por: 												Fecha �ltima Modificaci�n : 11/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * ".
                "  FROM scb_movbco_scg ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
            $this->io_message->message("CLASE->Instala M�TODO->uf_verificar_contabilidad_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{   
			$li_orden=-1;              
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
		    {
				$li_orden=$li_orden+1;
				$ls_scg_cuenta=$row["scg_cuenta"];
                $ls_debhab=$row["debhab"];				
				$ldec_monto=$row["monto"];				
				$ls_documento=$row["documento"];
			    $ls_procede_doc=$row["procede_doc"];				
				$ls_scg_cuenta=$this->io_tepuy_int_scg->uf_pad_scg_cuenta($_SESSION["la_empresa"]["formcont"],$ls_scg_cuenta);
				$ls_sql="SELECT monto,orden".
						"  FROM scg_dt_cmp".
						" WHERE codemp='".$this->ls_codemp."' ".
						"   AND procede='".$as_procede."' ".
						"   AND comprobante='".$as_numdoc."' ".
						"   AND fecha='".$as_fecmov."' ".
						"   AND documento ='".$ls_documento."' ".
						"   AND sc_cuenta='".$ls_scg_cuenta."' ".
						"   AND debhab='".$ls_debhab."'";
				$rs_data1=$this->io_sql->select($ls_sql);
				if($rs_data1===false)
				{   
					$this->io_message->message("CLASE->Reprocesar Comprobantes M�TODO->uf_verificar_gasto_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				}
				else
				{                 
					if(!($row=$this->io_sql->fetch_row($rs_data1)))
					{
						$ls_sql="INSERT INTO scg_dt_cmp (codemp,procede,comprobante,fecha,sc_cuenta,procede_doc,documento,debhab, descripcion,monto,orden) ". 
								" VALUES ('".$this->ls_codemp."','".$as_procede."','".$as_numdoc."','" .$as_fecmov."',".
								"'".$ls_scg_cuenta."', '".$ls_procede_doc."','".$ls_documento."','".$ls_debhab."',".
								"'".$as_descripcion."',".$ldec_monto.",".$li_orden.")" ;
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{   
							$this->io_message->message("CLASE->Reprocesar Comprobantes M�TODO->uf_verificar_gasto_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
							return false;
						}
					}
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
    }// end function uf_verificar_contabilidad_scb
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_sep($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_sep
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualiz� sin ning�n problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Miguel Palencia	
		// Modificado Por: 												Fecha �ltima Modificaci�n : 19/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilizaci�n
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		$ls_sql="SELECT sep_solicitud.numsol, tepuy_cmp.fecha ".
				"  FROM tepuy_cmp, sep_solicitud ".
				" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND tepuy_cmp.procede ='SEPSPC' ".
				"   AND tepuy_cmp.codemp = sep_solicitud.codemp ".
				"   AND tepuy_cmp.comprobante = sep_solicitud.numsol".
				" ORDER BY sep_solicitud.numsol";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualizaci�n");	
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ld_fecha=$row["fecha"];
				$ls_numsol=$row["numsol"];
				$ls_sql="UPDATE sep_solicitud ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND numsol = '".$ls_numsol."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{ // para la fecha de anulaci�n
			$ls_sql="SELECT sep_solicitud.numsol, tepuy_cmp.fecha ".
					"  FROM tepuy_cmp, sep_solicitud ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (tepuy_cmp.procede ='SEPSPA' OR tepuy_cmp.procede='SEPRPC')".
					"   AND tepuy_cmp.codemp = sep_solicitud.codemp ".
					"   AND tepuy_cmp.comprobante = sep_solicitud.numsol".
					" ORDER BY sep_solicitud.numsol";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				$lb_valido=false;
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$ld_fecha=$row["fecha"];
					$ls_numsol=$row["numsol"];
					$ls_sql="UPDATE sep_solicitud ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numsol = '".$ls_numsol."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualizaci�n");		
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes del sistema de Solicitud de Ejecuci�n Presupuestaria";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_soc($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_soc
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualiz� sin ning�n problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Miguel Palencia	
		// Modificado Por: 												Fecha �ltima Modificaci�n : 19/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilizaci�n
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		$ls_sql="SELECT soc_ordencompra.numordcom, tepuy_cmp.fecha, tepuy_cmp.procede ".
				"  FROM tepuy_cmp, soc_ordencompra ".
				" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND (tepuy_cmp.procede ='SOCCOS' OR tepuy_cmp.procede ='SOCCOC')".
				"   AND tepuy_cmp.codemp = soc_ordencompra.codemp ".
				"   AND tepuy_cmp.comprobante = soc_ordencompra.numordcom ".
				" ORDER BY soc_ordencompra.numordcom, tepuy_cmp.procede ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualizaci�n");	
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ld_fecha=$row["fecha"];
				$ls_numordcom=$row["numordcom"];
				$ls_procede=$row["procede"];
				if($ls_procede=="SOCCOS")
				{
					$ls_estcondat="S";
				}
				if($ls_procede=="SOCCOC")
				{
					$ls_estcondat="B";
				}
				$ls_sql="UPDATE soc_ordencompra ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND numordcom = '".$ls_numordcom."' ".
						"   AND estcondat = '".$ls_estcondat."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{ // para la fecha de anulaci�n
			$ls_sql="SELECT soc_ordencompra.numordcom, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, soc_ordencompra ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (tepuy_cmp.procede ='SOCAOS' OR tepuy_cmp.procede='SOCAOC')".
					"   AND tepuy_cmp.codemp = soc_ordencompra.codemp ".
					"   AND tepuy_cmp.comprobante = soc_ordencompra.numordcom".
					" ORDER BY soc_ordencompra.numordcom, soc_ordencompra.estcondat";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				$lb_valido=false;
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$ld_fecha=$row["fecha"];
					$ls_numordcom=$row["numordcom"];
					$ls_procede=$row["procede"];
					if($ls_procede=="SOCAOS")
					{
						$ls_estcondat="S";
					}
					if($ls_procede=="SOCAOC")
					{
						$ls_estcondat="B";
					}
					$ls_sql="UPDATE soc_ordencompra ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numordcom = '".$ls_numordcom."' ".
							"   AND estcondat = '".$ls_estcondat."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualizaci�n");		
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes del sistema de Compras";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_soc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_cxp($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_cxp
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualiz� sin ning�n problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Miguel Palencia	
		// Modificado Por: 												Fecha �ltima Modificaci�n : 19/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilizaci�n
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		$ls_sql="SELECT cxp_solicitudes.numsol, tepuy_cmp.fecha, tepuy_cmp.procede ".
				"  FROM tepuy_cmp, cxp_solicitudes ".
				" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND tepuy_cmp.procede ='CXPSOP' ".
				"   AND tepuy_cmp.codemp = cxp_solicitudes.codemp ".
				"   AND tepuy_cmp.comprobante = cxp_solicitudes.numsol ".
				" ORDER BY cxp_solicitudes.numsol ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualizaci�n");	
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ld_fecha=$row["fecha"];
				$ls_numsol=$row["numsol"];
				$ls_sql="UPDATE cxp_solicitudes ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND numsol = '".$ls_numsol."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{ // para la fecha de anulaci�n
			$ls_sql="SELECT cxp_solicitudes.numsol, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, cxp_solicitudes ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='CXPAOP' ".
					"   AND tepuy_cmp.codemp = cxp_solicitudes.codemp ".
					"   AND tepuy_cmp.comprobante = cxp_solicitudes.numsol ".
					" ORDER BY cxp_solicitudes.numsol ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				$lb_valido=false;
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$ld_fecha=$row["fecha"];
					$ls_numsol=$row["numsol"];
					$ls_sql="UPDATE cxp_solicitudes ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numsol = '".$ls_numsol."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualizaci�n");		
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{ // para la fecha de contabilizaci�n de las notas de D�bito y cr�dito
			switch($_SESSION["ls_gestor"])
			{
				case "MYSQL":
					$ls_criterio="   AND tepuy_cmp.comprobante like CONCAT('%',cxp_sol_dc.numdc) ";
					break;
				
				case "POSTGRE":
					$ls_criterio="   AND tepuy_cmp.comprobante like '%'||cxp_sol_dc.numdc ";
					break;
			}
			$ls_sql="SELECT cxp_sol_dc.numdc, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, cxp_sol_dc ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (tepuy_cmp.procede ='CXPNOD' OR tepuy_cmp.procede ='CXPNOC')".
					"   AND tepuy_cmp.codemp = cxp_sol_dc.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_numdc=$row["numdc"];
					$ls_procede=$row["procede"];
					if($ls_procede=="CXPNOD")
					{
						$ls_codope="D";
					}
					if($ls_procede=="CXPNOC")
					{
						$ls_codope="C";
					}
					$ls_sql="UPDATE cxp_sol_dc ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numdc = '".$ls_numdc."' ".
							"   AND codope = '".$ls_codope."'";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualizaci�n");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes del sistema de Cuentas por Pagar";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_cxp
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_scb($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_scb
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualiz� sin ning�n problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Miguel Palencia	
		// Modificado Por: 												Fecha �ltima Modificaci�n : 20/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilizaci�n
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_criterio="   AND tepuy_cmp.comprobante like CONCAT('%',scb_movbco.numdoc) ";
				break;
			
			case "POSTGRE":
				$ls_criterio="   AND tepuy_cmp.comprobante like '%'||scb_movbco.numdoc ";
				break;
		}
		$ls_sql="SELECT scb_movbco.numdoc, tepuy_cmp.fecha, tepuy_cmp.procede, tepuy_cmp.codban, tepuy_cmp.ctaban ".
				"  FROM tepuy_cmp, scb_movbco ".
				" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND (tepuy_cmp.procede ='SCBBCH' OR tepuy_cmp.procede ='SCBBDP' OR ".
				"		 tepuy_cmp.procede ='SCBBNC' OR tepuy_cmp.procede ='SCBBND' OR ".
				"		 tepuy_cmp.procede ='SCBOPD') ".
				"   AND tepuy_cmp.codemp = scb_movbco.codemp ".
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualizaci�n");	
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ld_fecha=$row["fecha"];
				$ls_numdoc=$row["numdoc"];
				$ls_procede=$row["procede"];
				$ls_codban=$row["codban"];
				$ls_ctaban=$row["ctaban"];
				switch($ls_procede)
				{
					case "SCBBCH":
						$ls_codope="CH";
						break;
					case "SCBBDP":
						$ls_codope="DP";
						break;
					case "SCBBNC":
						$ls_codope="NC";
						break;
					case "SCBBND":
						$ls_codope="ND";
						break;
					case "SCBOPD":
						$ls_codope="OP";
						break;
				}
				$ls_sql="UPDATE scb_movbco ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND numdoc = '".$ls_numdoc."' ".
						"   AND codban = '".$ls_codban."' ".
						"   AND ctaban = '".$ls_ctaban."' ".
						"   AND codope = '".$ls_codope."'";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{ // para la fecha de anulaci�n
			$ls_sql="SELECT scb_movbco.numdoc, tepuy_cmp.fecha, tepuy_cmp.procede, tepuy_cmp.codban, tepuy_cmp.ctaban  ".
					"  FROM tepuy_cmp, scb_movbco ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (tepuy_cmp.procede ='SCBBAH' OR tepuy_cmp.procede ='SCBBAP' OR tepuy_cmp.procede ='SCBBAC' OR tepuy_cmp.procede ='SCBBAD') ".
					"   AND tepuy_cmp.codemp = scb_movbco.codemp ".
					"   AND tepuy_cmp.comprobante = scb_movbco.numdoc ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				$lb_valido=false;
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$ld_fecha=$row["fecha"];
					$ls_numdoc=$row["numdoc"];
					$ls_procede=$row["procede"];
					$ls_codban=$row["codban"];
					$ls_ctaban=$row["ctaban"];
					switch($ls_procede)
					{
						case "SCBBAH":
							$ls_codope="CH";
							break;
						case "SCBBAP":
							$ls_codope="DP";
							break;
						case "SCBBAC":
							$ls_codope="NC";
							break;
						case "SCBBAD":
							$ls_codope="ND";
							break;
					}
					$ls_sql="UPDATE scb_movbco ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numdoc = '".$ls_numdoc."' ".
							"   AND codban = '".$ls_codban."' ".
							"   AND ctaban = '".$ls_ctaban."' ".
							"   AND codope = '".$ls_codope."'";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualizaci�n");		
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_criterio="   AND tepuy_cmp.comprobante like CONCAT('%',scb_movcol.numcol) ";
				break;
			
			case "POSTGRE":
				$ls_criterio="   AND tepuy_cmp.comprobante like '%'||scb_movcol.numcol ";
				break;
		}
		if($lb_valido)
		{ // para la fecha de contabilizaci�n de las colocaciones
			$ls_sql="SELECT scb_movcol.numcol, tepuy_cmp.fecha, tepuy_cmp.procede, tepuy_cmp.codban, tepuy_cmp.ctaban ".
					"  FROM tepuy_cmp, scb_movcol ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (tepuy_cmp.procede ='SCBCNC' OR tepuy_cmp.procede ='SCBCND' OR ".
					"		 tepuy_cmp.procede ='SCBCDP') ".
					"   AND tepuy_cmp.codemp = scb_movcol.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_numcol=$row["numcol"];
					$ls_procede=$row["procede"];
					$ls_codban=$row["codban"];
					$ls_ctaban=$row["ctaban"];
					switch($ls_procede)
					{
						case "SCBCNC":
							$ls_codope="NC";
							break;
						case "SCBCND":
							$ls_codope="ND";
							break;
						case "SCBCDP":
							$ls_codope="DP";
							break;
					}
					$ls_sql="UPDATE scb_movcol ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND numcol = '".$ls_numcol."' ".
							"   AND codban = '".$ls_codban."' ".
							"   AND ctaban = '".$ls_ctaban."' ".
							"   AND codope = '".$ls_codope."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualizaci�n");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes del sistema de Caja y Banco ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_scb
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_sob($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_sob
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualiz� sin ning�n problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Miguel Palencia	
		// Modificado Por: 												Fecha �ltima Modificaci�n : 20/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilizaci�n
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_criterio="   AND tepuy_cmp.comprobante like CONCAT('%',sob_asignacion.codasi) ";
				break;
			
			case "POSTGRE":
				$ls_criterio="   AND tepuy_cmp.comprobante like '%'||sob_asignacion.codasi ";
				break;
		}
		$ls_sql="SELECT sob_asignacion.codasi, tepuy_cmp.fecha, tepuy_cmp.procede ".
				"  FROM tepuy_cmp, sob_asignacion ".
				" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND tepuy_cmp.procede ='SOBASI' ".
				"   AND tepuy_cmp.codemp = sob_asignacion.codemp ".
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualizaci�n");	
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ld_fecha=$row["fecha"];
				$ls_codasi=$row["codasi"];
				$ls_sql="UPDATE sob_asignacion ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND codasi = '".$ls_codasi."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{ // para la fecha de anulaci�n
			$ls_sql="SELECT sob_asignacion.codasi, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, sob_asignacion ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND (tepuy_cmp.procede ='SOBRAS' OR tepuy_cmp.procede='SOBRPC') ".
					"   AND tepuy_cmp.codemp = sob_asignacion.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				$lb_valido=false;
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$ld_fecha=$row["fecha"];
					$ls_codasi=$row["codasi"];
					$ls_sql="UPDATE sob_asignacion ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codasi = '".$ls_codasi."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualizaci�n");		
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_criterio="   AND tepuy_cmp.comprobante like CONCAT('%',sob_contrato.codcon) ";
				break;
			
			case "POSTGRE":
				$ls_criterio="   AND tepuy_cmp.comprobante like '%'||sob_contrato.codcon ";
				break;
		}
		if($lb_valido)
		{ // para la fecha de contabilizaci�n de los contratos
			$ls_sql="SELECT sob_contrato.codcon, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, sob_contrato ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='SOBCON' ".
					"   AND tepuy_cmp.codemp = sob_contrato.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_codcon=$row["codcon"];
					$ls_sql="UPDATE sob_contrato ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcon = '".$ls_codcon."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualizaci�n");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{ // para la fecha de anulaci�n de los contratos
			$ls_sql="SELECT sob_contrato.codcon, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, sob_contrato ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='SOBACO' ".
					"   AND tepuy_cmp.codemp = sob_contrato.codemp ".
					$ls_criterio;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ld_fecha=$row["fecha"];
					$ls_codcon=$row["codcon"];
					$ls_sql="UPDATE sob_contrato ".
							"   SET fechaanula = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcon = '".$ls_codcon."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualizaci�n");	
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes del sistema de Obras ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_sob
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_sno($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_sno
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualiz� sin ning�n problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Miguel Palencia	
		// Modificado Por: 												Fecha �ltima Modificaci�n : 20/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilizaci�n
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		$ls_sql="SELECT sno_dt_scg.codcom, tepuy_cmp.fecha, tepuy_cmp.procede ".
				"  FROM tepuy_cmp, sno_dt_scg ".
				" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND tepuy_cmp.procede ='SNOCNO' ".
				"	AND sno_dt_scg.tipnom = 'N' ".
				"   AND tepuy_cmp.codemp = sno_dt_scg.codemp ".
				"	AND tepuy_cmp.comprobante = sno_dt_scg.codcom ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualizaci�n");	
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ld_fecha=$row["fecha"];
				$ls_codcom=$row["codcom"];
				$ls_sql="UPDATE sno_dt_scg ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND codcom = '".$ls_codcom."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{ // para la fecha de contabilizaci�n de los aportes
			$ls_sql="SELECT sno_dt_scg.codcomapo, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, sno_dt_scg ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='SNOCNO' ".
					"	AND sno_dt_scg.tipnom = 'A' ".
					"   AND tepuy_cmp.codemp = sno_dt_scg.codemp ".
					"	AND tepuy_cmp.comprobante = sno_dt_scg.codcomapo ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				$lb_valido=false;
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$ld_fecha=$row["fecha"];
					$ls_codcom=$row["codcomapo"];
					$ls_sql="UPDATE sno_dt_scg ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcomapo = '".$ls_codcom."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualizaci�n");		
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{ // para la fecha de contabilizaci�n de las n�minas
			$ls_sql="SELECT sno_dt_spg.codcom, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, sno_dt_spg ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='SNOCNO' ".
					"	AND sno_dt_spg.tipnom = 'N' ".
					"   AND tepuy_cmp.codemp = sno_dt_spg.codemp ".
					"	AND tepuy_cmp.comprobante = sno_dt_spg.codcom ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				$lb_valido=false;
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$ld_fecha=$row["fecha"];
					$ls_codcom=$row["codcom"];
					$ls_sql="UPDATE sno_dt_spg ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcom = '".$ls_codcom."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualizaci�n");		
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}		
		if($lb_valido)
		{ // para la fecha de contabilizaci�n de los aportes
			$ls_sql="SELECT sno_dt_spg.codcomapo, tepuy_cmp.fecha, tepuy_cmp.procede ".
					"  FROM tepuy_cmp, sno_dt_spg ".
					" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
					"   AND tepuy_cmp.procede ='SNOCNO' ".
					"	AND sno_dt_spg.tipnom = 'A' ".
					"   AND tepuy_cmp.codemp = sno_dt_spg.codemp ".
					"	AND tepuy_cmp.comprobante = sno_dt_spg.codcomapo ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				$lb_valido=false;
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$ld_fecha=$row["fecha"];
					$ls_codcom=$row["codcomapo"];
					$ls_sql="UPDATE sno_dt_spg ".
							"   SET fechaconta = '".$ld_fecha."' ".
							" WHERE codemp = '".$this->ls_codemp."' ".
							"   AND codcomapo = '".$ls_codcom."' ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_message->message("Problemas al ejecutar actualizaci�n");		
					}
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes del sistema de N�mina ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_sno
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_saf($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_saf
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualiz� sin ning�n problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Miguel Palencia	
		// Modificado Por: 												Fecha �ltima Modificaci�n : 20/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilizaci�n
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_criterio="   AND tepuy_cmp.comprobante like CONCAT('%',SUBSTR(saf_depreciacion.fecdep,6,2),SUBSTR(saf_depreciacion.fecdep,1,4))";
				break;
			
			case "POSTGRE":
				$ls_criterio="   AND tepuy_cmp.comprobante like '%'||SUBSTR(saf_depreciacion.fecdep,6,2)||SUBSTR(saf_depreciacion.fecdep,1,4)";
				break;
		}
		$ls_sql="SELECT saf_depreciacion.fecdep, tepuy_cmp.fecha, tepuy_cmp.procede ".
				"  FROM tepuy_cmp, saf_depreciacion ".
				" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND tepuy_cmp.procede ='SAFDPR' ".
				"   AND tepuy_cmp.codemp = saf_depreciacion.codemp ".
				$ls_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualizaci�n");	
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ld_fecha=$row["fecha"];
				$ls_ano=substr($row["fecdep"],0,4);
				$ls_mes=substr($row["fecdep"],5,2);
				$ls_sql="UPDATE saf_depreciacion ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND SUBSTR(fecdep,1,4) = '".$ls_ano."' ".
						"   AND SUBSTR(fecdep,6,2) = '".$ls_mes."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes del sistema de Activos Fijos ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_saf
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reprocesar_fecha_comprobante_modpre($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprocesar_fecha_comprobante_modpre
		//		   Access: public
		//	    Arguments: 
		//	      Returns: lb_valido True si se actualiz� sin ning�n problema
		//	  Description: Funcion que obtiene el los comprobantes de presupuesto y le actualiza las fechas
		//	   Creado Por: Ing. Miguel Palencia	
		// Modificado Por: 												Fecha �ltima Modificaci�n : 20/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// para la fecha de contabilizaci�n
		$this->io_sql->begin_transaction();
		$lb_valido=true;
		$ls_sql="SELECT tepuy_cmp_md.comprobante, tepuy_cmp.fecha, tepuy_cmp.procede ".
				"  FROM tepuy_cmp, tepuy_cmp_md ".
				" WHERE tepuy_cmp.codemp = '".$this->ls_codemp."' ".
				"   AND tepuy_cmp.tipo_comp = 2 ".
				"   AND tepuy_cmp.codemp = tepuy_cmp_md.codemp ".
				"   AND tepuy_cmp.comprobante = tepuy_cmp_md.comprobante ".
				"   AND tepuy_cmp.procede = tepuy_cmp_md.procede ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("Problemas al ejecutar actualizaci�n");	
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ld_fecha=$row["fecha"];
				$ls_comprobante=$row["comprobante"];
				$ls_procede=$row["procede"];
				$ls_sql="UPDATE tepuy_cmp_md ".
						"   SET fechaconta = '".$ld_fecha."' ".
						" WHERE codemp = '".$this->ls_codemp."' ".
						"   AND comprobante = '".$ls_comprobante."' ".
						"   AND procede = '".$ls_procede."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_message->message("Problemas al ejecutar actualizaci�n");	
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso la fecha de los comprobantes de las Modificaciones Presupuestarias ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_fecha_comprobante_modpre
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reprocesar_comprobantes_contabilizados_scb($aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	     Function: uf_reprocesar_comprobantes_contabilizados_scb
		// 	       Access: public
		//      Arguments: $aa_seguridad
		//	      Returns: Boolean
		//    Description: Esta funcion verifica que los comprobantes de banco que est�n contabilizados tambien
		//				   se encuentren en contabilidad, presupuesto de gasto y presupuesto de ingreso en caso
		//				   de que no se encuentren los genera
		//	   Creado Por: Ing. Miguel Palencia
		// Modificado Por: 											Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codban,ctaban,estmov,numdoc,fecmov,conmov,codope,tipo_destino,ced_bene,cod_pro,conmov,fechaconta,fechaanula ".
                "  FROM scb_movbco ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND estmov='N' ".
				"   AND estmodordpag<>'CM'";
		$this->io_sql->begin_transaction();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_message->message("CLASE->Reprocesar Comprobantes M�TODO->uf_reprocesar_comprobantes_contabilizados_scb ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codban=$row["codban"];
				$ls_ctaban=$row["ctaban"];
				$ls_estmov=$row["estmov"];
				$ls_numdoc=$row["numdoc"];
				$ls_fecmov=$row["fecmov"];
				$ls_fechaconta=$row["fechaconta"];
				$ls_fechaanula=$row["fechaanula"];
				$ls_conmov=$row["conmov"];
				$ls_codope=$row["codope"];
				$ls_tipo=$row["tipo_destino"];  		
				$ls_ced_bene=$row["ced_bene"];
				$ls_cod_pro=$row["cod_pro"];
				$ls_descripcion=$row["conmov"];
			    $ls_procede="SCBBA".substr($ls_codope,1,1);
				$ls_comprobante=$ls_numdoc;
				$ls_estatus="C";
				$codban="---";
				$ctaban="-------------------------";
			    $lb_existe=$this->io_tepuy_int->uf_obtener_comprobante($this->ls_codemp,$ls_procede,$ls_comprobante,&$ls_fecmov,
																		$codban,$ctaban,&$ls_tipo,&$ls_ced_bene,&$ls_cod_pro);
				if($lb_existe)
				{
					$lb_valido=$this->uf_insertar_movimiento_scb($this->ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,
																 $ls_estmov,'O',$ls_procede,$ls_comprobante,$ls_fecmov,$ls_fechaconta);
					if($lb_valido)
					{
						$lb_valido=$this->uf_insertar_movimiento_scb($this->ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,
																	 $ls_estmov,'A',$ls_procede,$ls_comprobante,$ls_fechaanula,$ls_fechaanula);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_delete_movimiento_scb($this->ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,
																   $ls_estmov);
					}
				}
				else
				{
			    	$ls_procede="SCBB".$ls_codope;
					$lb_existe=$this->io_tepuy_int->uf_obtener_comprobante($this->ls_codemp,$ls_procede,$ls_comprobante,&$ls_fecmov,
																			$codban,$ctaban,&$ls_tipo,&$ls_ced_bene,&$ls_cod_pro);
					if($lb_existe)
					{
						$lb_valido=$this->uf_insertar_movimiento_scb($this->ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,
																	 $ls_estmov,'C',$ls_procede,$ls_comprobante,$ls_fecmov,$ls_fechaconta);
						if($lb_valido)
						{
							$lb_valido=$this->uf_delete_movimiento_scb($this->ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,
																	   $ls_estmov);
						}
					}
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso los comprobantes contabilizados del sistema de Caja y Banco";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_reprocesar_comprobantes_contabilizados_scb
	//-----------------------------------------------------------------------------------------------------------------------------------

}
?>