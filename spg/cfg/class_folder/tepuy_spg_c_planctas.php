<?php
class tepuy_spg_c_planctas
{
	 var $int_scg;
	 var $io_sql;
	 var $dat;
	 var $msg;
	 var $fun;
	 var $int_spg;
	 var $is_msg_error;
	 var $io_seguridad;
	 var $obj="";
	 var $dts_reporte;
	 
	function tepuy_spg_c_planctas()
	{
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_tepuy_int_scg.php");
		require_once("../../shared/class_folder/class_tepuy_int_spg.php");
	    require_once("../../shared/class_folder/class_funciones.php");
	    require_once("../../shared/class_folder/tepuy_c_seguridad.php");
 	    require_once("../../shared/class_folder/tepuy_c_check_relaciones.php");
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_conect          = new tepuy_include();
        $conn               = $io_conect->uf_conectar ();
		$this->msg          = new class_mensajes();
		$this->fun          = new class_funciones();
		$this->int_scg      = new class_tepuy_int_scg();	
		$this->int_spg      = new class_tepuy_int_spg();
		$this->io_sql       = new class_sql($conn );
		$this->io_chkrel    = new tepuy_c_check_relaciones($conn);
		$this->dat          = $_SESSION["la_empresa"];
		$this->io_seguridad = new tepuy_c_seguridad();
        $this->obj=new class_datastore();
		$this->dts_reporte=new class_datastore();	
	
	}

	function uf_valida_cuenta($as_cuenta ,$aa_estpro,$as_cuenta_scg)
	{
			
		$ls_programa    = "";$ls_Status="";
		$ls_spg_cuenta  = $as_cuenta;
		$ls_formato     = $this->dat["formplan"];
		$ls_formato_spg = $this->dat["formpre"];
		$ls_cuenta_pad  = $this->int_scg->uf_pad_cuenta_plan(&$ls_formato,$ls_spg_cuenta);
		$li_len_cta     = strlen($ls_cuenta_pad);
		$li_len_formato = strlen($ls_formato);
		
		if($li_len_cta!=$li_len_formato)
		{
			$this->msg->message("Cuentas no poseen el formato del plan unico");
			return false;
		}
		
		$li_len_ctaspg      = strlen(trim($ls_spg_cuenta));
		$li_len_formato_spg = strlen(str_replace('-','',trim($ls_formato_spg)));
		
		if($li_len_ctaspg!=$li_len_formato_spg)
		{
			$this->msg->message("Formato de presupuesto ".$ls_formato_spg." no corresponde al de la cuenta introducida ".$ls_spg_cuenta);
			return false;
		}
		if($li_len_cta<$li_len_ctaspg)
		{
			$ls_NextCuenta = $ls_cuenta_pad;
		}
		else
		{
			$ls_NextCuenta = $this->int_spg->uf_spg_next_cuenta_nivel($ls_cuenta_pad);			
		}
		
		$lb_valido=$this->int_scg->uf_select_plan_unico_cuenta_recurso($ls_NextCuenta,&$as_denominacion);
		if(!$lb_valido)
		{
			/*$this->msg->message("La Cuenta no Existe en el Plan Unico de Cuentas de Recursos y Egresos");
			return false;*/
			$lb_valido=$this->int_scg->uf_select_plan_unico_cuenta_recurso($ls_spg_cuenta,&$as_denominacion);
		}
		
		if(substr($ls_spg_cuenta,0,1)!=trim($this->dat["gasto_p"]))
		{
			$this->msg->message("Las Cuentas de Gastos deben comenzar con ".$this->dat["gasto_p"]);
			return false;
		}

		// Verifico si es de nivel apropiado
	
		$li_nivel=$this->int_spg->uf_spg_obtener_nivel($ls_spg_cuenta);
		
		if($li_nivel <= 1)
		{
			$this->msg->message("Las Cuentas de Nivel 'Partida' no son Validas");
			return false;
		}
		
		if($li_nivel<= 2)
		{
			$this->msg->message("Las Cuentas de Nivel 'Genericas' no son Validas");
			return false;
		}	
			
	//	*-- Verifico que si no hay cuentas con movimientos de nivel superior
		$li_nivel =$this->int_spg->uf_spg_obtener_nivel($ls_spg_cuenta);
		
		if($li_nivel > 1)
		{
			$ls_NextCuenta = $this->int_spg->uf_spg_next_cuenta_nivel($ls_spg_cuenta);
			
			do 
			{
				if($this->int_spg->uf_spg_select_cuenta($this->dat["codemp"], $aa_estpro, $ls_NextCuenta,&$as_status,&$as_denominacion, $as_cuenta_scg))
				{
					if($as_status=="C")
					{
						$this->msg->message("Existen cuentas de nivel superior con Movimiento");
						return false;
					}
				}
				$ls_NextCuenta = $this->int_spg->uf_spg_next_cuenta_nivel($ls_NextCuenta);
	
			$li_nivel=$this->int_spg->uf_spg_obtener_nivel($ls_NextCuenta);
			}while( $li_nivel > 1);
		}
	
		return true;

	
	}//uf_valida_cuenta


function uf_procesar_cuentas($as_cuenta_spg,$as_denominacion_cta,$aa_estpro,$as_cuenta_scg,$aa_security)
{
		$ls_spg_cuenta=$as_cuenta_spg;
		$ls_cuenta_tempo=$ls_spg_cuenta;
		$ls_denominacion_cta=$as_denominacion_cta;
		$ls_scg_cuenta=$as_cuenta_scg;
		$ls_codest1=$aa_estpro[0];
		$ls_codest2=$aa_estpro[1];
		$ls_codest3=$aa_estpro[2];
		$ls_codest4=$aa_estpro[3];
		$ls_codest5=$aa_estpro[4];
		$lb_valido=true;							
		//Tomo los valores anteriores de la cuenta y denominacion.
		if($this->int_spg->uf_spg_select_cuenta($this->dat["codemp"], $aa_estpro, $ls_spg_cuenta, &$ls_status, &$ls_denominacion, $ls_scg_cuenta ))
		{

			//if($ls_status=="C")
			//{		
				$lb_valido=$this->int_spg->uf_spg_update_cuenta($ls_codest1,$ls_codest2,$ls_codest3,$ls_codest4,$ls_codest5,$ls_spg_cuenta,$ls_denominacion_cta,$as_cuenta_scg);
				//$lb_valido=$this->int_spg->uf_sql_transaction($lb_valido);
				//print $lb_valido;
				if($lb_valido)
				{
					$ls_estpro1="";
					$ls_estpro2="";
					$ls_ctapu="";
					$ls_denctapu="";
					$ls_ctaplan="";
					$this->is_msg_error="Denominación Actualizada";
					$disabled="";
					$this->int_spg->io_sql->commit();
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_desc_event="Actualizo la cuenta ".$ls_spg_cuenta.", asociada a la programatica ".$ls_codest1."-".$ls_codest2."-".$ls_codest3."-00-00 y a la cuenta contable ".$ls_scg_cuenta; 
					$ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_security[1],$aa_security[2],$ls_evento,$aa_security[3],$aa_security[4],$ls_desc_event);
					////////////////////////////////         SEGURIDAD               //////////////////////////////
					
				}
				else
				{
					$this->is_msg_error="Error ".$this->int_spg->io_sql->message;
				}
		}
		else
		{
			$ls_cuenta_tempo = $ls_spg_cuenta;
			$ls_denominacion = "";
			$ls_NextCuenta   = $this->int_spg->uf_spg_next_cuenta_nivel($ls_spg_cuenta);
			$li_Nivel        = $this->int_spg->uf_spg_obtener_nivel($ls_NextCuenta);
			$li_fila = 1	; 	 
			$lds_cuenta_temp=new class_datastore();		
			do 
			{
			  if(!$this->int_spg->uf_spg_select_cuenta($this->dat["codemp"], $aa_estpro, $ls_NextCuenta, &$ls_status, &$ls_denominacion, $ls_scg_cuenta ))
			  {
				  $ls_PadNextCuenta =$this->int_scg->uf_pad_cuenta_plan($this->dat["formplan"],$ls_NextCuenta);
 				  $this->int_spg->uf_select_plan_unico_cuenta($ls_PadNextCuenta,&$as_denominacion_plan);
										
					  if($li_Nivel > 1)
					  {
						  $ls_cuenta_ref = $this->int_spg->uf_spg_next_cuenta_nivel( $ls_NextCuenta );
					  }
					  else	
					  {
						  $ls_cuenta_ref = "             ";
					  }
					  if($as_denominacion_plan=="")
					  {
					  		$as_denominacion_plan=$as_denominacion_cta;
					  }
					  $lds_cuenta_temp->insertRow("sc_cuenta",trim($ls_scg_cuenta));
					  $lds_cuenta_temp->insertRow("spg_cuenta",$ls_NextCuenta);
					  $lds_cuenta_temp->insertRow("codestpro1",$ls_codest1);
					  $lds_cuenta_temp->insertRow("codestpro2",$ls_codest2);
					  $lds_cuenta_temp->insertRow("codestpro3",$ls_codest3);
					  $lds_cuenta_temp->insertRow("codestpro4",$ls_codest4); 				  			  
					  $lds_cuenta_temp->insertRow("codestpro5",$ls_codest5);
					  $lds_cuenta_temp->insertRow("denominacion",$as_denominacion_plan);				  			  
					  $lds_cuenta_temp->insertRow("sc_cuenta_ref",$ls_cuenta_ref);				  			  
					  $lds_cuenta_temp->insertRow("nivel",$li_Nivel);				  			  
					  $li_fila =  $li_fila + 1;								  
				  } 
	
				if ($li_Nivel > 1)
				{
						$ls_NextCuenta = $this->int_spg->uf_spg_next_cuenta_nivel( $ls_NextCuenta );
						$li_Nivel      = $this->int_spg->uf_spg_obtener_nivel( $ls_NextCuenta );
						
				}
				else
				{
					$li_Nivel = 0 ;
				}
			}while( $li_Nivel >= 1);
			
			$li_total = $lds_cuenta_temp->getRowCount("sc_cuenta");
			
			 if($li_total>0)
			 {
				 $this->int_spg->io_sql->begin_transaction();
				for($li_fila=1;$li_fila<=$li_total;$li_fila++)
				{
					 $ls_sc_cuenta  = $lds_cuenta_temp->getValue("sc_cuenta",$li_fila); 	    
					 $ls_spg_cuenta  = $lds_cuenta_temp->getValue("spg_cuenta",$li_fila);
					 $ls_codest1	 = $lds_cuenta_temp->getValue("codestpro1",$li_fila);
					 $ls_codest2	 = $lds_cuenta_temp->getValue("codestpro2",$li_fila);
					 $ls_codest3	 = $lds_cuenta_temp->getValue("codestpro3",$li_fila);	    
					 $ls_codest4	 = $lds_cuenta_temp->getValue("codestpro4",$li_fila);	    
					 $ls_codest5	 = $lds_cuenta_temp->getValue("codestpro5",$li_fila);	    
					 $ls_denominacion= $lds_cuenta_temp->getValue("denominacion",$li_fila) ;	    		 
					 $ls_cuenta_ref  = $lds_cuenta_temp->getValue("sc_cuenta_ref",$li_fila) ;	    		 
					 $li_Nivel       = $lds_cuenta_temp->getValue("nivel",$li_fila); 	    		 
					 $ls_mensaje_error="Error en Guardar";
					 $ls_status = "S";

					 $lb_valido = $this->int_spg->uf_spg_insert_cuenta($ls_codest1,$ls_codest2,$ls_codest3,$ls_codest4,$ls_codest5,$ls_spg_cuenta,$ls_denominacion,trim($ls_sc_cuenta),$ls_status,$li_Nivel,$ls_cuenta_ref);
					 if (!$lb_valido)
					 {
						break; 
					 }
					 else
					 {
						 /////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="INSERT";
						$ls_desc_event="Inserto la cuenta ".$ls_spg_cuenta.", asociada a la programatica ".$ls_codest1."-".$ls_codest2."-".$ls_codest3."-00-00 y a la cuenta contable ".$ls_sc_cuenta; 
						//////////////////////////////         SEGURIDAD               /////////////////////////////
					 }	
				}
			 }
				
			 if($lb_valido)
			 {
				
					$ls_cuenta = $ls_cuenta_tempo;

					$ls_Cuenta_temp = $this->int_spg->uf_spg_padcuenta_plan( $this->dat["formplan"] , $ls_cuenta);
					$ls_NextCuenta = $this->int_spg->uf_spg_next_cuenta_nivel($ls_cuenta);
					$li_Nivel      = $this->int_spg->uf_spg_obtener_nivel($ls_cuenta);
					$ls_status	  = "C";
					$lb_valido = 	$this->int_spg->uf_spg_insert_cuenta($ls_codest1,$ls_codest2,$ls_codest3,$ls_codest4,$ls_codest5,$ls_cuenta,$ls_denominacion_cta,$as_cuenta_scg,$ls_status,$li_Nivel,$ls_NextCuenta);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="INSERT";
					$ls_desc_event="Inserto la cuenta ".$ls_cuenta.", asociada a la programatica ".$ls_codest1."-".$ls_codest2."-".$ls_codest3."-00-00 y a la cuenta contable ".$as_cuenta_scg; 
					//////////////////////////////         SEGURIDAD               /////////////////////////////
			 }
			
				 if ($lb_valido)
				 {
						$this->int_spg->io_sql->commit();
						$this->is_msg_error="Registro guardado";
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_security[1],$aa_security[2],$ls_evento,$aa_security[3],$aa_security[4],$ls_desc_event);
						////////////////////////////////         SEGURIDAD               //////////////////////////////
				 }
				 else
				 {
					   $this->int_spg->io_sql->rollback();
  					   $this->is_msg_error="Error al guardar cuenta ";
				 }
		}
		return $lb_valido;
	}

	function uf_procesar_delete_cuenta($as_cuenta_spg,$as_dencuentaspg,$aa_estpro,$as_cuenta_scg,$lb_existe,$aa_security)
	{
		$ls_codemp    = $this->dat["codemp"];
		$lb_valido    = false;
        /*$ls_condicion = " AND column_name='spg_cuenta'";//Nombre del o los campos que deseamos buscar.
	    $ls_mensaje   = "";                             //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
		$lb_tiene     = $this->io_chkrel->uf_check_relaciones($ls_codemp,$ls_condicion,'spg_cuentas',$as_cuenta_spg,$ls_mensaje);*/
		$lb_tiene     = $this->uf_check_relacion($ls_codemp,$as_cuenta_spg,$aa_estpro);
		$lb_existe    = $this->int_spg->uf_spg_select_cuenta($ls_codemp, $aa_estpro, $as_cuenta_spg, &$ls_status, &$ls_denominacion, $as_cuenta_scg );
		if ($lb_existe)
		   {
			 if ($lb_tiene)
			    {
				  $this->msg->message("Existen movimientos asociados a la cuenta ".$as_cuenta_spg." cuya cuenta Contable es ".$as_cuenta_scg);
				  return false;
			    }
			 else
			    {
				  $ls_cuenta_cero = $this->int_spg->uf_spg_cuenta_sin_cero($as_cuenta_spg);
				  $li_total_rows  = $this->int_spg->uf_spg_select_cuenta_sin_cero($ls_codemp,$ls_cuenta_cero,$aa_estpro);
				  if ($li_total_rows > 1)
					 {
					   $lb_valido=false;
					 }
			 	  else 
					 {
					   $lb_valido     = $this->int_spg->uf_spg_delete_cuenta($ls_codemp, $aa_estpro, $as_cuenta_spg);   
					   $ls_NextCuenta = $this->int_spg->uf_spg_next_cuenta_nivel($ls_cuenta_cero);
					   $li_Nivel      = $this->int_spg->uf_spg_obtener_nivel($ls_NextCuenta);
					   do 
						 {
						   if ($this->int_spg->uf_spg_select_cuenta($ls_codemp, $aa_estpro, $ls_NextCuenta, &$ls_status, &$ls_denominacion, $as_cuenta_scg ))
							  {
							    $ls_PadNextCuenta =$this->int_spg->uf_spg_padcuenta_plan($this->dat["formplan"] , $ls_NextCuenta);				
							    $this->int_scg->uf_select_plan_unico_cuenta($ls_PadNextCuenta,&$as_denominacion_plan);
								if ($li_Nivel > 1)
								   {
									 $ls_cuenta_ref = $this->int_spg->uf_spg_next_cuenta_nivel( $ls_NextCuenta );
							  	   }	
							 	else	
								   {
											 $ls_cuenta_ref = "             ";
								   }
								$ls_cuenta_cero = $this->int_spg->uf_spg_cuenta_recortar_next($ls_NextCuenta);
								$li_total_rows = $this->int_spg->uf_spg_select_cuenta_sin_cero($ls_codemp,$ls_cuenta_cero,$aa_estpro);
								if ($li_total_rows>1)
								   {
											//$this->msg->message("Existen cuentas de nivel inferior ... no se puede eliminar.");				
								   }
								else
								   {		  
								     $lb_valido = $this->int_spg->uf_spg_delete_cuenta($ls_codemp, $aa_estpro, $ls_NextCuenta);   
								   }
											  
							  } 
						   if ($li_Nivel > 1)
							  {
							     $ls_NextCuenta = $this->int_spg->uf_spg_next_cuenta_nivel( $ls_NextCuenta );
							 	 $li_Nivel      = $this->int_spg->uf_spg_obtener_nivel( $ls_NextCuenta );										
							  }
						   else
							  {
								$li_Nivel = 0 ;
							  }
							}while( $li_Nivel >= 1);
							
					}
						if($lb_valido)
						{
						   $this->int_spg->io_sql->commit();
						   $this->is_msg_error="Registro Eliminado";
						   /////////////////////////////////         SEGURIDAD               /////////////////////////////
							$ls_evento="DELETE";
							$ls_desc_event="Elimino la cuenta ".$as_cuenta_spg." asociada a la programatica ".$aa_estpro[0]."-".$aa_estpro[1]."-".$aa_estpro[2]."-00-00 y a la cuenta contable ".$as_cuenta_scg;
							$ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_security[1],$aa_security[2],$ls_evento,$aa_security[3],$aa_security[4],$ls_desc_event);
							////////////////////////////////         SEGURIDAD               //////////////////////////////
						}
						else
						{
						   $this->int_spg->io_sql->rollback();
						   $this->is_msg_error=$this->int_spg->is_msg_error;
						}
				}
		}
		return $lb_valido;
	}
	
	
 function uf_check_relacion($ls_codemp,$ls_cuenta_spg,$aa_estpro)
	{
		$lb_existe=false;
		$la_posee_cuenta[1]='cxp_dc_spg';
		$la_posee_cuenta[2]='cxp_rd_cargos';
		$la_posee_cuenta[3]='cxp_rd_spg';
		$la_posee_cuenta[4]='scb_movbco_spg';
		$la_posee_cuenta[5]='scb_movbco_spgop';
		$la_posee_cuenta[6]='scb_movcol_spg';
		$la_posee_cuenta[7]='scv_dt_spg';
		$la_posee_cuenta[8]='sep_cuentagasto';
		$la_posee_cuenta[9]='sep_dt_articulos';
		$la_posee_cuenta[10]='sep_dt_concepto';		
		$la_posee_cuenta[11]='sep_dt_servicio';
		$la_posee_cuenta[12]='sep_solicitudcargos';
		$la_posee_cuenta[13]='tepuy_cargos';   //codestpro
		$la_posee_cuenta[14]='sno_dt_spg';
		$la_posee_cuenta[15]='soc_cuentagasto';
		$la_posee_cuenta[16]='soc_solicitudcargos';
		$la_posee_cuenta[17]='spg_dt_cmp';
		for($li=1;($li<=17)&&(!$lb_existe);$li++)
		{
			if(($li==13)||($li==1)||($li==3)||($li==4)||($li==5)||($li==6))
			{
				$ls_aux="  AND codemp='".$ls_codemp."' AND codestpro='".$aa_estpro[0].$aa_estpro[1].$aa_estpro[2].$aa_estpro[3].$aa_estpro[4]."'";
			}
			else
			{
				$ls_aux=" AND codemp='".$ls_codemp."' AND codestpro1='".$aa_estpro[0]."' 
					 AND codestpro2='".$aa_estpro[1]."' AND codestpro3='".$aa_estpro[2]."' AND codestpro4='".$aa_estpro[3]."' AND codestpro5='".$aa_estpro[4]."'";
			}
			$ls_sql="SELECT * FROM ".$la_posee_cuenta[$li]." WHERE spg_cuenta='".$ls_cuenta_spg."' ".$ls_aux;		
					 
			$rs_data=$this->io_sql->select($ls_sql);				 
			if($rs_data===false)
			{
				$this->msg->message( "Error en check relacion ".$this->fun->uf_convertirmsg($this->io_sql->message));
				return false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))	
				{
					$lb_existe=true;
					break;
					return $lb_existe;
				}
				else
				{
					$lb_existe=false;
				}
			}			
		}
		return $lb_existe;
	}

    function uf_buscar_cuentas($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5)
	{
		$lb_valido=true;
		$ls_codestpro1=str_pad($ls_codestpro1,20,"0",STR_PAD_LEFT);
		$ls_codestpro2=str_pad($ls_codestpro2,6,"0",STR_PAD_LEFT);
		$ls_codestpro3=str_pad($ls_codestpro3,3,"0",STR_PAD_LEFT);
		$ls_sql="SELECT codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,denominacion,status, ".
		        "sc_cuenta,nivel,referencia ".
				"FROM spg_cuentas ".
				"WHERE spg_cuentas.codestpro1='".$ls_codestpro1."' and spg_cuentas.codestpro2='".$ls_codestpro2."' ".
				"AND spg_cuentas.codestpro3='".$ls_codestpro3."' and spg_cuentas.codestpro4='".$ls_codestpro4."' ". 
				"AND spg_cuentas.codestpro5='".$ls_codestpro5."' ";	//print "buscar cuentas  ".$ls_sql."<br>";
		$rs_data=$this->io_sql->select($ls_sql);				 
		if($rs_data===false)
		{
			$this->msg->message( "Error en uf_buscar_cuentas ".$this->fun->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			while ($row=$this->io_sql->fetch_row($rs_data))	
			 {
			   //$ls_codemp=$row["codemp"]; 
			 /*  $ls_codest1=$row["codestpro1"]; 
			   $ls_codest2=$row["codestpro2"]; 
			   $ls_codest3=$row["codestpro3"]; 
			   $ls_codest4=$row["codestpro4"]; 
			   $ls_codest5=$row["codestpro5"];*/
			   $ls_spg_cuenta=$row["spg_cuenta"]; 
			   $ls_denominacion=$row["denominacion"]; 
			   $ls_status=$row["status"]; 
			   $ls_sccuenta=$row["sc_cuenta"];
			   $ls_nivel=$row["nivel"]; 
			   $ls_referencia=$row["referencia"];
			   /*$this->dts_reporte->insertRow("codestpro1",$ls_codest1);
			   $this->dts_reporte->insertRow("codestpro2",$ls_codest2);
			   $this->dts_reporte->insertRow("codestpro3",$ls_codest3);
			   $this->dts_reporte->insertRow("codestpro4",$ls_codest4);
			   $this->dts_reporte->insertRow("codestpro5",$ls_codest5);*/
			   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);			
			   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);			
			   $this->dts_reporte->insertRow("status",$ls_status);			
			   $this->dts_reporte->insertRow("sc_cuenta",$ls_sccuenta);
			   $this->dts_reporte->insertRow("nivel",$ls_nivel);			
			   $this->dts_reporte->insertRow("referencia",$ls_referencia);			
		    }
	 
		}			
	$this->io_sql->free_result($rs_data);
	return $lb_valido;
	}
   
    function uf_verificar_cuentas_destino($ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,$ls_codestpro4h,
				                          $ls_codestpro5h,$ls_spg_cuenta)
	{
		$lb_valido=true;
        $ls_codestpro1h=str_pad($ls_codestpro1h,20,"0",STR_PAD_LEFT);
	    $ls_codestpro2h=str_pad($ls_codestpro2h,6,"0",STR_PAD_LEFT);
		$ls_codestpro3h=str_pad($ls_codestpro3h,3,"0",STR_PAD_LEFT);
		$ls_sql="SELECT codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,denominacion,status, ".
		        "sc_cuenta,nivel,referencia ".
				"FROM spg_cuentas ".
				"WHERE spg_cuentas.codestpro1='".$ls_codestpro1h."' and spg_cuentas.codestpro2='".$ls_codestpro2h."' ".
				"AND spg_cuentas.codestpro3='".$ls_codestpro3h."' and spg_cuentas.codestpro4='".$ls_codestpro4h."' ". 
				"AND spg_cuentas.codestpro5='".$ls_codestpro5h."' ".
				"AND spg_cuentas.spg_cuenta='".$ls_spg_cuenta."'";	//print "verificar cuentas".$ls_sql."<br>";
		$rs_data=$this->io_sql->select($ls_sql);			 
		if($rs_data===false)
		{
			$this->msg->message( "Error en uf_verificar_cuentas_destino ".$this->fun->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
	       if ($row=$this->io_sql->fetch_row($rs_data))
		    { 
			  $lb_valido=true; 
		      $this->io_sql->free_result($rs_data);
			}
	 	 else
		    {
		 	  $lb_valido=false;
	  	    }
		}			
	return $lb_valido;
	}

}
?>
