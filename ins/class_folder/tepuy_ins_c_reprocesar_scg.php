<?php
class tepuy_ins_c_reprocesar_scg
{
	var $io_sql;
	var $io_message;
	var $io_function;
	var $is_msg_error;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_ins_c_reprocesar_scg()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_fecha.php");//Necesaria para la clase integradora
		require_once("../shared/class_folder/class_funciones.php");	
		require_once("../shared/class_folder/class_tepuy_int.php");	//Necesaria para la clase integradora
		require_once("../shared/class_folder/class_tepuy_int_scg.php");	
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		$io_siginc=new tepuy_include();
		$con=$io_siginc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->io_message=new class_mensajes();
		$this->io_function=new class_funciones();
		$this->io_seguridad=new tepuy_c_seguridad();		
	}//end tepuy_ins_c_reprocesar_scg
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reprocesar_saldos($ls_codemp,$aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	 Function:  uf_reprocesar_saldos
		// 	   Access:  public
		//  Arguments:  
		//	  Returns:  Boolean
		//Description:  Este método realiza la actualización de los saldos de las cuentas contables 
		//				segun los movimientos realizado en base a las mismas.
		////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$int_scg=new class_tepuy_int_scg();
		$int_scg->is_codemp=$ls_codemp;
		$this->io_sql->begin_transaction();
		//Actualizo los saldos de las cuentas a 0 
		$ls_sql="UPDATE scg_saldos ".
				"   SET debe_mes=0,haber_mes=0 ".
				" WHERE codemp ='".$ls_codemp."'";
		$li_numrow=$this->io_sql->execute($ls_sql);
		if($li_numrow===false)
		{
            $this->io_message->message("CLASE->Reprocesar SCG MÉTODO->uf_reprocesar_saldos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}		
		if($lb_valido)
		{		
			//Obtengo los movimientos contables realizados
			$ls_sql="SELECT debhab,monto,fecha,sc_cuenta ".
					"  FROM scg_dt_cmp ".
					" WHERE codemp ='".$ls_codemp."'".
					" ORDER BY sc_cuenta ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("CLASE->Reprocesar SCG MÉTODO->uf_reprocesar_saldos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$ls_debhab=$row["debhab"];
					$ldec_monto=$row["monto"];
					$ld_fecha=$row["fecha"];
					$int_scg->id_fecha=$ld_fecha;
					$ls_cuenta=$row["sc_cuenta"];
					$lb_valido=$int_scg->uf_scg_procesar_saldos_contables($ls_cuenta,$ls_debhab,0, $ldec_monto );
					if(!$lb_valido)
					{					
						break;					
						$lb_valido=false;
					}
				}
			}
		}	
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso los Saldos de Contabilidad";
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
	}//end uf_reprocesar_saldos
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reprocesar_niveles($ls_codemp,$aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	 Function:  uf_reprocesar_niveles
		// 	   Access:  public
		//  Arguments:  
		//	  Returns:  Boolean
		//Description:  Este método realiza la actualización de los niveles de las cuentas contables 
		////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$int_scg=new class_tepuy_int_scg();
		$int_scg->is_codemp=$ls_codemp;
		$this->io_sql->begin_transaction();
		//Actualizo los saldos de las cuentas a 0 
		$ls_sql="UPDATE scg_cuentas ".
				"   SET nivel=0 ".
				" WHERE codemp ='".$ls_codemp."'";
		$li_numrow=$this->io_sql->execute($ls_sql);
		if($li_numrow===false)
		{
            $this->io_message->message("CLASE->Reprocesar SCG MÉTODO->uf_reprocesar_niveles ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}		
		if($lb_valido)
		{		
			//Obtengo los movimientos contables realizados
			$ls_sql="SELECT sc_cuenta ".
					"  FROM scg_cuentas ".
					" WHERE codemp ='".$ls_codemp."'".
					" ORDER BY sc_cuenta ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("CLASE->Reprocesar SCG MÉTODO->uf_reprocesar_saldos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$ls_cuenta=$row["sc_cuenta"];
					$li_nivel=$int_scg->uf_scg_obtener_nivel($ls_cuenta);
					$ls_referencia="";
					if($li_nivel>1)
					{
						$ls_referencia=$int_scg->uf_scg_next_cuenta_nivel($ls_cuenta);
					}
					$ls_sql="UPDATE scg_cuentas ".
							"   SET nivel = ".$li_nivel.", ".
							"       referencia = '".$ls_referencia."' ".
							" WHERE codemp ='".$ls_codemp."'".
							"   AND sc_cuenta = '".$ls_cuenta."' ";
					$li_numrow=$this->io_sql->execute($ls_sql);
					if($li_numrow===false)
					{
						$this->io_message->message("CLASE->Reprocesar SCG MÉTODO->uf_reprocesar_niveles ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
						$lb_valido=false;
					}		
					if(!$lb_valido)
					{					
						break;					
						$lb_valido=false;
					}
				}
			}
		}	
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso los Niveles de Contabilidad";
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
	}//end uf_reprocesar_niveles
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>