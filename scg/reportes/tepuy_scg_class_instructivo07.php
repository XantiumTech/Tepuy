<?php
class tepuy_scg_class_instructivo07
{
	var $la_empresa;
	var $io_fun;
	var $io_sql;
	var $io_sql_aux;
	var $io_msg;
	var $int_scg;
	var $ds_analitico;
	var $ds_reporte;
	var $ds_cab;
	var $ds_egresos;
	var $ds_prebalance;
	var $ds_balance1;
	var $ds_cuentas;
	var $ia_niveles;
	var $ds_programado;
	var $ldec_total_resultado;
	var $io_fecha;
	var $ls_gestor;
	var $li_mes_prox;
	var $la_cuentas;
	var $int_spi;
	var $int_spg;
	var $ds_currep;
	var $ds_reporte2;
	function tepuy_scg_class_instructivo07()
	{
		$this->la_empresa=$_SESSION["la_empresa"];
		$this->ls_gestor = $_SESSION["ls_gestor"];	
		$this->io_fun = new class_funciones();
		$this->siginc=new tepuy_include();
		$this->con=$this->siginc->uf_conectar();
		$this->io_sql= new class_sql($this->con);
		$this->io_sql_aux= new class_sql($this->con);
		$this->io_msg= new class_mensajes();		
		$this->io_fecha=new class_fecha();
		$this->ds_cuentas=new class_datastore();
		$this->ds_cuentas_no_circulante=new class_datastore();
		$this->ds_cuentas_pasivos=new class_datastore();
		$this->ds_cuentas_patrimonio=new class_datastore();
		$this->ds_saldos_activos=new class_datastore();
		$this->ds_saldos_no_activos=new class_datastore();
		$this->ds_saldos_pasivos=new class_datastore();
		$this->ds_saldos_patrimonio=new class_datastore();
		$this->int_scg=new class_tepuy_int_scg();		
		$this->int_spi=new class_tepuy_int_spi();
		$this->int_spg=new class_tepuy_int_spg();
		$this->ia_niveles=array();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];			
	   
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////************************************BALANCE GENERAL*************************************************////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_balance_general_instructivo($as_mesdes,$as_meshas,$as_nivel)
	{
		$this->uf_grupo_cuentas_activos_circulante();			
		$this->uf_procesar_cuentas_contables_activos_circulante($as_mesdes,$as_meshas,&$as_nivel);		
		$this->uf_grupo_cuentas_activos_no_circulante();
		$this->uf_procesar_cuentas_contables_activos_no_circulante($as_mesdes,$as_meshas,$as_nivel);
		$this->uf_grupo_cuentas_pasivos();
		$this->uf_procesar_cuentas_contables_pasivos($as_mesdes,$as_meshas,$as_nivel);
		$this->uf_grupo_cuentas_patrimonio();
		$this->uf_procesar_cuentas_contables_patrimonio($as_mesdes,$as_meshas,$as_nivel);
		return true;
	}
	
//------------------------------------------------------------------------------------------------------------------------------------------
   function uf_procesar_cuentas_contables_activos_circulante($as_mesdes,$as_meshas,$as_nivel)
   {  		
		
		$li_total=$this->ds_cuentas->getRowCount("sc_cuenta");
		$monto1=0;
		$monto2=0;
		$monto3=0;
		$monto4=0;
		$monto5=0;
		$monto6=0;
		$monto7=0;
		$monto8=0;
		$monto1_t=0;
		$monto2_t=0;
		$monto3_t=0;
		$monto4_t=0;
		$monto5_t=0;
		$monto6_t=0;
		$monto7_t=0;
		$monto8_t=0;			
	    for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$ld_enero=0;		   $ld_febrero=0;
			$ld_marzo=0;		   $ld_abril=0;
			$ld_mayo=0;		       $ld_junio=0;
			$ld_julio=0;		   $ld_agosto=0;
			$ld_septiembre=0;      $ld_octubre=0;
			$ld_noviembre=0;	   $ld_diciembre=0;
			$ls_codrep="0408";     $li_nivel="";
			$ls_status="";
			$ld_saldo_real_ant=0;
			$ld_saldo_apro=0;
			$ld_saldo_mod=0;
			$ld_monto_programado=0;
			$ld_monto_acumulado=0;
			$ld_monto_ejecutado_acumulado=0;
			$ls_sc_cuenta=$this->ds_cuentas->getValue("sc_cuenta",$li_i);	
			$ls_denominacion=$this->ds_cuentas->getValue("denominacion",$li_i);
			$ls_tipo=$this->ds_cuentas->getValue("tipo",$li_i);
			$lb_valido=$this->uf_scg_reporte_cargar_programado($ls_codrep,$ls_sc_cuenta,
	                                          $ld_enero,$ld_febrero,$ld_marzo,
											  $ld_abril,$ld_mayo,$ld_junio,
											  $ld_julio,$ld_agosto,$ld_septiembre,
											  $ld_octubre,$ld_noviembre,$ld_diciembre,
											  $ld_saldo_real_ant,$ld_saldo_apro,$ld_saldo_mod,
											  $li_nivel,$ls_status);
			$lb_valido=$this->uf_scg_reporte_calcular_programado($as_mesdes,$as_meshas,$ld_monto_programado,$ld_monto_acumulado,
			                                                     $ld_enero,$ld_febrero,$ld_marzo,
																 $ld_abril,$ld_mayo,$ld_junio,
																 $ld_julio,$ld_agosto,$ld_septiembre,
												                 $ld_octubre,$ld_noviembre,$ld_diciembre);
			
			$this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,$as_mesdes,$as_meshas,$ld_monto_ejecutado_acumulado);
			//variacion absoluta  del periodo entre el  monto ejecutado y monto programado
			if($ld_monto_programado>$ld_monto_ejecutado_acumulado)
			{
				if ($ld_monto_ejecutado_acumulado<0)
				{
					$ld_variacion_absoluta=$ld_monto_ejecutado_acumulado-$ld_monto_programado; 
				}
				else
				{
					$ld_variacion_absoluta=abs($ld_monto_programado-$ld_monto_ejecutado_acumulado); 
				}
			}
			else
			{
		   		$ld_variacion_absoluta=$ld_monto_ejecutado_acumulado; 				
				
			}
			
			//variacion porcentual  del periodo entre el  monto ejecutado y monto programado
			if($ld_monto_programado>0)
			{ 
				$ld_porcentaje_variacion=(($ld_monto_programado-$ld_monto_ejecutado_acumulado)/$ld_monto_programado)*100;  
			}
			else
			{
				$ld_porcentaje_variacion=0;  
			}				
			switch($as_nivel)
			{
				case 1:				   
					$ls_variacion=abs($ld_monto_ejecutado_acumulado)-$ld_saldo_real_ant; 
				break;						
				case 2:
				   $ld_monto_ejecutado_ant=0;
				   $this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,1,3,$ld_monto_ejecutado_ant);//I trimestre
				   $ls_variacion=abs($ld_monto_ejecutado_ant-$ld_monto_ejecutado_acumulado);				  
				break;
				case 3:
				   $ld_monto_ejecutado_ant=0;
				   $this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,4,6,$ld_monto_ejecutado_ant);//II trimestre
				   $ls_variacion=abs($ld_monto_ejecutado_ant-$ld_monto_ejecutado_acumulado);									
				break;
				case 4:
				   $ld_monto_ejecutado_ant=0;
				   $this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,7,9,$ld_monto_ejecutado_ant);//I trimestre
				   $ls_variacion=abs($ld_monto_ejecutado_ant-$ld_monto_ejecutado_acumulado);					 
				break;				   	
			}					
			switch($ls_sc_cuenta)
				{
					
					case "112030000":							
						$monto1=$ld_saldo_real_ant;
						$monto2=$ld_saldo_apro;
						$monto3=$ld_saldo_mod;
						$monto4=$ld_monto_programado;
						$monto5=$ld_monto_ejecutado_acumulado;
						$monto6=$ld_variacion_absoluta;
						$monto7=$ld_porcentaje_variacion;
						$monto8=$monto8-$ls_variacion;						
					break;
				}	
			if ($ls_tipo=="3")
			{		
				$this->ds_saldos_activos->insertRow("sc_cuenta",$ls_sc_cuenta);
				$this->ds_saldos_activos->insertRow("denominacion",$ls_denominacion);
				$this->ds_saldos_activos->insertRow("saldo_real_ant",$ld_saldo_real_ant);
				$this->ds_saldos_activos->insertRow("saldo_apro",$ld_saldo_apro);
				$this->ds_saldos_activos->insertRow("saldo_mod",$ld_saldo_mod);
				$this->ds_saldos_activos->insertRow("saldo_programado",$ld_monto_programado);
				$this->ds_saldos_activos->insertRow("saldo_ejecutado",$ld_monto_ejecutado_acumulado);
				$this->ds_saldos_activos->insertRow("absoluta",$ld_variacion_absoluta);
				$this->ds_saldos_activos->insertRow("porcentual",$ld_porcentaje_variacion);		
				$this->ds_saldos_activos->insertRow("varia",$ls_variacion);
				$this->ds_saldos_activos->insertRow("tipo",$ls_tipo);
				
				$monto1_t=$monto1_t+$ld_saldo_real_ant;
				$monto2_t=$monto2_t+$ld_saldo_apro;
				$monto3_t=$monto3_t+$ld_saldo_mod;
				$monto4_t=$monto4_t+$ld_monto_programado;
				$monto5_t=$monto5_t+$ld_monto_ejecutado_acumulado;
				$monto6_t=$monto6_t+$ld_variacion_absoluta;
				$monto7_t=$monto7_t+$ld_porcentaje_variacion;
				$monto8_t=$monto8_t+$ls_variacion;
			} 
			else
			{   
				$this->ds_saldos_activos->insertRow("sc_cuenta",$ls_sc_cuenta);
				$this->ds_saldos_activos->insertRow("denominacion",$ls_denominacion);
				$this->ds_saldos_activos->insertRow("saldo_real_ant",$ld_saldo_real_ant);
				$this->ds_saldos_activos->insertRow("saldo_apro",$ld_saldo_apro);
				$this->ds_saldos_activos->insertRow("saldo_mod",$ld_saldo_mod);
				$this->ds_saldos_activos->insertRow("saldo_programado",$ld_monto_programado);
				$this->ds_saldos_activos->insertRow("saldo_ejecutado",$ld_monto_ejecutado_acumulado);
				$this->ds_saldos_activos->insertRow("absoluta",$ld_variacion_absoluta);
				$this->ds_saldos_activos->insertRow("porcentual",$ld_porcentaje_variacion);	
				$this->ds_saldos_activos->insertRow("varia",$ls_variacion);
				$this->ds_saldos_activos->insertRow("tipo",$ls_tipo);
				if ($ls_tipo=="2")
				{
					switch($ls_sc_cuenta)
					{				
						
						case "224010100":	
							$ls_denominacion="Cuenta a Cobrar Comerciales Netas";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;							
						break;
						case "224010300":	
							$ls_denominacion="INVENTARIOS NETOS";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1_t-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2_t-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3_t-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4_t-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5_t-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6_t-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7_t-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8_t-$ls_variacion);
							$monto1_t=0;
							$monto2_t=0;
							$monto3_t=0;
							$monto4_t=0;
							$monto5_t=0;
							$monto6_t=0;
							$monto7_t=0;
							$monto8_t=0;	
						break;
					}
						
					$this->ds_saldos_activos->insertRow("sc_cuenta",$ls_sc_cuenta);
					$this->ds_saldos_activos->insertRow("denominacion",$ls_denominacion);
					$this->ds_saldos_activos->insertRow("saldo_real_ant",$ld_saldo_real_ant);
					$this->ds_saldos_activos->insertRow("saldo_apro",$ld_saldo_apro);
					$this->ds_saldos_activos->insertRow("saldo_mod",$ld_saldo_mod);
					$this->ds_saldos_activos->insertRow("saldo_programado",$ld_monto_programado);
					$this->ds_saldos_activos->insertRow("saldo_ejecutado",$ld_monto_ejecutado_acumulado);
					$this->ds_saldos_activos->insertRow("absoluta",$ld_variacion_absoluta);
					$this->ds_saldos_activos->insertRow("porcentual",$ld_porcentaje_variacion);	
					$this->ds_saldos_activos->insertRow("varia",$ls_variacion);
					$this->ds_saldos_activos->insertRow("tipo",0);
					}
			}			
		}//fin del for
   }//fin uf_procesar_cuentas_contables_activos
//--------------------------------------------------------------------------------------------------------------------------------------------    
	function uf_grupo_cuentas_activos_circulante()
	{
		$ls_formcont=$this->la_empresa["formcont"];
		$la_cuentas[1]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1000000000000');	
		$la_cuentas[2]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1100000000000');
		$la_cuentas[3]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1110000000000');
		$la_cuentas[4]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1110100000000');
		$la_cuentas[5]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1110101000000');
		$la_cuentas[6]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1110102000000');
		$la_cuentas[7]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1110102010000');
		$la_cuentas[8]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1110102020000');
		$la_cuentas[9]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1110102030000');
		$la_cuentas[10] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1110200000000');
		$la_cuentas[11] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1120000000000');
		$la_cuentas[12] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1120100000000');
		$la_cuentas[13] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1120101000000');
		$la_cuentas[14] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1120102000000');
		$la_cuentas[15] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1120200000000');
		$la_cuentas[16] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1120201000000');
		$la_cuentas[17] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1120202000000');
		$la_cuentas[18] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1120300000000');
		$la_cuentas[19] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '2240101000000');
		$la_cuentas[20] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1120499000000');
		$la_cuentas[21] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1120500000000');
		$la_cuentas[22] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1120600000000');
		$la_cuentas[23] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1121000000000');
		$la_cuentas[24] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1121100000000');
		$la_cuentas[25] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1121900000000');
		$la_cuentas[26] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1130000000000');
		$la_cuentas[27] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1130100000000');
		$la_cuentas[28] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1130200000000');
		$la_cuentas[29] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1130300000000');
		$la_cuentas[30] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1130400000000');
		$la_cuentas[31] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1130500000000');
		$la_cuentas[32] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '2240103000000');
		$la_cuentas[33] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1130600000000');
		$la_cuentas[34] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1130601000000');
		$la_cuentas[35] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1130602000000');
		$la_cuentas[36] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1130603000000');
		$la_cuentas[37] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1140000000000');
		$la_cuentas[38] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1140100000000');
		$la_cuentas[39] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1140103000000');
		$la_cuentas[40] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1140109000000');
		$la_cuentas[41] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1149900000000');
		$la_cuentas[42] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1190000000000');
		$la_cuentas[43] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '1190900000000');
		
		for( $li_pos=1;$li_pos<=43;$li_pos++)
		{
			$ls_cuenta=$la_cuentas[$li_pos];
			$ls_cuenta = substr($ls_cuenta,0,9);

			$ls_sql="SELECT denominacion 
					 FROM   tepuy_plan_unico 
					 WHERE  sc_cuenta = '".$ls_cuenta."'";
					
		   $rs_data=$this->io_sql->select($ls_sql); 
		   if($rs_data===false)	
		   {
				$this->io_msg->message("Error al seleccionar cuenta metodo uf_init_array ".$this->io_fun->uf_convertirmsg($this->io_sql->message));
				print "Error en init array";
				return false;   	
		   }
		   else
		   {
		   		if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_denominacion=$row["denominacion"];
				}
				else
				{
					$ls_denominacion="";
				}			
				$this->ds_cuentas->insertRow("sc_cuenta",$ls_cuenta);
				$this->ds_cuentas->insertRow("denominacion",$ls_denominacion);	
				if ($li_pos==1)
				{  
					$this->ds_cuentas->insertRow("tipo",8);
				}
				elseif ($li_pos==2)
				{  
					$this->ds_cuentas->insertRow("tipo",1);
				}
				elseif ($li_pos==3)
				{  
					$this->ds_cuentas->insertRow("tipo",9);
				}
				elseif ($li_pos==11)
				{  
					$this->ds_cuentas->insertRow("tipo",9);
				}
				elseif ($li_pos==26)
				{  
					$this->ds_cuentas->insertRow("tipo",9);
				}
				elseif ($li_pos==37)
				{  
					$this->ds_cuentas->insertRow("tipo",9);
				}
				elseif ($li_pos==42)
				{  
					$this->ds_cuentas->insertRow("tipo",9);
				}
				elseif ($li_pos==19)
				{
					$this->ds_cuentas->insertRow("tipo",2);
				}elseif ($li_pos==32)
				{
					$this->ds_cuentas->insertRow("tipo",2);
				}				
				elseif ($li_pos==27)
				{
					$this->ds_cuentas->insertRow("tipo",3);
				}
				elseif ($li_pos==28)
				{
					$this->ds_cuentas->insertRow("tipo",3);
				}
				elseif ($li_pos==29)
				{
					$this->ds_cuentas->insertRow("tipo",3);
				}
				elseif ($li_pos==30)
				{
					$this->ds_cuentas->insertRow("tipo",3);
				}
				elseif ($li_pos==31)
				{
					$this->ds_cuentas->insertRow("tipo",3);
				}
				else		
				{
					$this->ds_cuentas->insertRow("tipo",9);
				}				
			}
		}//fin del for
		
    }//fin de uf_primer_grupo_cunetas()
//------------------------------------------------------------------------------------------------------------------------------------------   
   function uf_procesar_cuentas_contables_activos_no_circulante($as_mesdes,$as_meshas,$as_nivel)
   {  		
		$li_total=$this->ds_cuentas_no_circulante->getRowCount("sc_cuenta"); 
		$monto1=0;
		$monto2=0;
		$monto3=0;
		$monto4=0;
		$monto5=0;
		$monto6=0;
		$monto7=0;
		$monto8=0;		
	    for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$ld_enero=0;		   $ld_febrero=0;
			$ld_marzo=0;		   $ld_abril=0;
			$ld_mayo=0;		       $ld_junio=0;
			$ld_julio=0;		   $ld_agosto=0;
			$ld_septiembre=0;      $ld_octubre=0;
			$ld_noviembre=0;	   $ld_diciembre=0;
			$ls_codrep="0408";     $li_nivel="";
			$ls_status="";
			$ld_saldo_real_ant=0;
			$ld_saldo_apro=0;
			$ld_saldo_mod=0;
			$ld_monto_programado=0;
			$ld_monto_acumulado=0;
			$ld_monto_ejecutado_acumulado=0;
			$ls_variacion=0;
			$ls_sc_cuenta=$this->ds_cuentas_no_circulante->getValue("sc_cuenta",$li_i);	
			$ls_denominacion=$this->ds_cuentas_no_circulante->getValue("denominacion",$li_i);
			$ls_tipo=$this->ds_cuentas_no_circulante->getValue("tipo",$li_i); 
			$lb_valido=$this->uf_scg_reporte_cargar_programado($ls_codrep,$ls_sc_cuenta,
	                                          $ld_enero,$ld_febrero,$ld_marzo,
											  $ld_abril,$ld_mayo,$ld_junio,
											  $ld_julio,$ld_agosto,$ld_septiembre,
											  $ld_octubre,$ld_noviembre,$ld_diciembre,
											  $ld_saldo_real_ant,$ld_saldo_apro,$ld_saldo_mod,
											  $li_nivel,$ls_status);
			$lb_valido=$this->uf_scg_reporte_calcular_programado($as_mesdes,$as_meshas,$ld_monto_programado,$ld_monto_acumulado,
			                                                     $ld_enero,$ld_febrero,$ld_marzo,
																 $ld_abril,$ld_mayo,$ld_junio,
																 $ld_julio,$ld_agosto,$ld_septiembre,
												                 $ld_octubre,$ld_noviembre,$ld_diciembre);
			$this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,$as_mesdes,$as_meshas,$ld_monto_ejecutado_acumulado);
			//variacion absoluta  del periodo entre el  monto ejecutado y monto programado
			if($ld_monto_programado>$ld_monto_ejecutado_acumulado)
			{
				if ($ld_monto_ejecutado_acumulado<0)
				{
					$ld_variacion_absoluta=$ld_monto_ejecutado_acumulado-$ld_monto_programado; 
				}
				else
				{
					$ld_variacion_absoluta=abs($ld_monto_programado-$ld_monto_ejecutado_acumulado); 
				}
			}
			else
			{
		   		$ld_variacion_absoluta=($ld_monto_ejecutado_acumulado); 				
			}
			
			//variacion porcentual  del periodo entre el  monto ejecutado y monto programado
			if($ld_monto_programado>0)
			{ 
				$ld_porcentaje_variacion=(($ld_monto_programado-$ld_monto_ejecutado_acumulado)/$ld_monto_programado)*100;  
			}
			else
			{
				$ld_porcentaje_variacion=0;  
			}				
			switch($as_nivel)
			{
				case "1":				   
					$ls_variacion=abs($ld_monto_ejecutado_acumulado)-$ld_saldo_real_ant; 
					break;						
		
				case "2":
				   $ld_monto_ejecutado_ant=0;
				   $this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,1,3,$ld_monto_ejecutado_ant);//I trimestre
				   $ls_variacion=abs($ld_monto_ejecutado_ant-$ld_monto_ejecutado_acumulado);				  
				   break;
				   	
				case "3":
				   $ld_monto_ejecutado_ant=0;
				   $this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,4,6,$ld_monto_ejecutado_ant);//II trimestre
				   $ls_variacion=abs($ld_monto_ejecutado_ant-$ld_monto_ejecutado_acumulado);				  
				   break;	
				   
				case "4":
				   $ld_monto_ejecutado_ant=0;
				   $this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,7,9,$ld_monto_ejecutado_ant);//I trimestre
				   $ls_variacion=abs($ld_monto_ejecutado_ant-$ld_monto_ejecutado_acumulado);
				   break;					   	
			}					
			if ($ls_tipo=="3")
			{		
				$this->ds_saldos_no_activos->insertRow("sc_cuenta",$ls_sc_cuenta);
				$this->ds_saldos_no_activos->insertRow("denominacion",$ls_denominacion);
				$this->ds_saldos_no_activos->insertRow("saldo_real_ant",$ld_saldo_real_ant);
				$this->ds_saldos_no_activos->insertRow("saldo_apro",$ld_saldo_apro);
				$this->ds_saldos_no_activos->insertRow("saldo_mod",$ld_saldo_mod);
				$this->ds_saldos_no_activos->insertRow("saldo_programado",$ld_monto_programado);
				$this->ds_saldos_no_activos->insertRow("saldo_ejecutado",$ld_monto_ejecutado_acumulado);
				$this->ds_saldos_no_activos->insertRow("absoluta",$ld_variacion_absoluta);
				$this->ds_saldos_no_activos->insertRow("porcentual",$ld_porcentaje_variacion);		
				$this->ds_saldos_no_activos->insertRow("varia",$ls_variacion);
				$this->ds_saldos_no_activos->insertRow("tipo",$ls_tipo);
				$monto1=$monto1+$ld_saldo_real_ant;
				$monto2=$monto2+$ld_saldo_apro;
				$monto3=$monto3+$ld_saldo_mod;
				$monto4=$monto4+$ld_monto_programado;
				$monto5=$monto5+$ld_monto_ejecutado_acumulado;
				$monto6=$monto6+$ld_variacion_absoluta;
				$monto7=$monto7+$ld_porcentaje_variacion;
				$monto8=$monto8+$ls_variacion;
			} 			
			else
			{ 
				$this->ds_saldos_no_activos->insertRow("sc_cuenta",$ls_sc_cuenta);
				$this->ds_saldos_no_activos->insertRow("denominacion",$ls_denominacion);
				$this->ds_saldos_no_activos->insertRow("saldo_real_ant",$ld_saldo_real_ant);
				$this->ds_saldos_no_activos->insertRow("saldo_apro",$ld_saldo_apro);
				$this->ds_saldos_no_activos->insertRow("saldo_mod",$ld_saldo_mod);
				$this->ds_saldos_no_activos->insertRow("saldo_programado",$ld_monto_programado);
				$this->ds_saldos_no_activos->insertRow("saldo_ejecutado",$ld_monto_ejecutado_acumulado);
				$this->ds_saldos_no_activos->insertRow("absoluta",$ld_variacion_absoluta);
				$this->ds_saldos_no_activos->insertRow("porcentual",$ld_porcentaje_variacion);		
				$this->ds_saldos_no_activos->insertRow("varia",$ls_variacion);
				$this->ds_saldos_no_activos->insertRow("tipo",$ls_tipo);
				if ($ls_tipo=="2")
			    {
					switch($ls_sc_cuenta)
					{
						case "225010100":	
							$ls_denominacion="Edificios e instalaciones- Neto";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;	
						break;	
						
						case "225010200":	
							$ls_denominacion="Maquinaria y demás equipos de cosntrucción, campo, industria y taller - neto";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;	
						break;	
						case "225010300":	
							$ls_denominacion="Equipo de transporte, tracción y elevación - Neto";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;	
						break;		
						case "225010400":	
							$ls_denominacion="Equipos de comunicaciones y señalamiento - Neto";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;	
						break;	
						case "225010500":	
							$ls_denominacion="Equipos médicos-quirúrgicos, dentales y veterinarios - Neto";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;	
						break;		
						case "225010600":	
							$ls_denominacion="Equipos científicos, reliogosos, de enseñanaza y recreación - Neto";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;	
						break;	
						case "225010700":	
							$ls_denominacion="Equipos para la seguridad pública - Neto";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;	
						break;	
						case "225010800":	
							$ls_denominacion="Máquina, muebles y demás equipos de oficina - Neto";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;	
						break;	
						case "225010900":	
							$ls_denominacion="Semovientes - Neto";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;	
						break;		
						case "225011900":	
							$ls_denominacion="Otros bienes de uso - Neto";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;	
						break;	
						case "225020100":	
							$ls_denominacion="Marca de Fabrica y Patente de inversión - Neto";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;	
						break;	
						case "225020200":	
							$ls_denominacion="Derechos de Autor - Neto";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;	
						break;	
						case "225020300":	
							$ls_denominacion="Gasto de Organización - Neto";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;	
						break;			
						case "225020500":	
							$ls_denominacion="Estudios y Proyectos  - Neto";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;	
						break;	
						case "225021900":	
							$ls_denominacion="Otros Activos Intangibles  - Neto";
							$ls_sc_cuenta="";	
							$ld_saldo_real_ant=abs($monto1-$ld_saldo_real_ant);
							$ld_saldo_apro=abs($monto2-$ld_saldo_apro);
							$ld_saldo_mod=abs($monto3-$ld_saldo_mod);
							$ld_monto_programado=abs($monto4-$ld_monto_programado);
							$ld_monto_ejecutado_acumulado=abs($monto5-$ld_monto_ejecutado_acumulado);
							$ld_variacion_absoluta=abs($monto6-$ld_variacion_absoluta);
							$ld_porcentaje_variacion=abs($monto7-$ld_porcentaje_variacion);
							$ls_variacion=abs($monto8-$ls_variacion);
							$monto1=0;
							$monto2=0;
							$monto3=0;
							$monto4=0;
							$monto5=0;
							$monto6=0;
							$monto7=0;
							$monto8=0;	
						break;	
																							
					}				
						
					$this->ds_saldos_no_activos->insertRow("sc_cuenta",$ls_sc_cuenta);
					$this->ds_saldos_no_activos->insertRow("denominacion",$ls_denominacion);
					$this->ds_saldos_no_activos->insertRow("saldo_real_ant",$ld_saldo_real_ant);
					$this->ds_saldos_no_activos->insertRow("saldo_apro",$ld_saldo_apro);
					$this->ds_saldos_no_activos->insertRow("saldo_mod",$ld_saldo_mod);
					$this->ds_saldos_no_activos->insertRow("saldo_programado",$ld_monto_programado);
					$this->ds_saldos_no_activos->insertRow("saldo_ejecutado",$ld_monto_ejecutado_acumulado);
					$this->ds_saldos_no_activos->insertRow("absoluta",$ld_variacion_absoluta);
					$this->ds_saldos_no_activos->insertRow("porcentual",$ld_porcentaje_variacion);		
					$this->ds_saldos_no_activos->insertRow("varia",$ls_variacion);
					$this->ds_saldos_no_activos->insertRow("tipo",0);					
				}
			}
					if ($ls_tipo=="8")
					{	
					    $this->ds_saldos_no_activos->insertRow("sc_cuenta",$ls_sc_cuenta);
						$this->ds_saldos_no_activos->insertRow("denominacion",$ls_denominacion);
						$this->ds_saldos_no_activos->insertRow("saldo_real_ant",$ld_saldo_real_ant);
						$this->ds_saldos_no_activos->insertRow("saldo_apro",$ld_saldo_apro);
						$this->ds_saldos_no_activos->insertRow("saldo_mod",$ld_saldo_mod);
						$this->ds_saldos_no_activos->insertRow("saldo_programado",$ld_monto_programado);
						$this->ds_saldos_no_activos->insertRow("saldo_ejecutado",$ld_monto_ejecutado_acumulado);
						$this->ds_saldos_no_activos->insertRow("absoluta",$ld_variacion_absoluta);
						$this->ds_saldos_no_activos->insertRow("porcentual",$ld_porcentaje_variacion);		
						$this->ds_saldos_no_activos->insertRow("varia",$ls_variacion);
						$this->ds_saldos_no_activos->insertRow("tipo",1);				
					} 					
		}//fin del for
   }//fin uf_procesar_cuentas_contables_activos
//------------------------------------------------------------------------------------------------------------------------------------------
    function uf_grupo_cuentas_activos_no_circulante()
	{
		$ls_formcont=$this->la_empresa["formcont"];
		$la_cuentas[1]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '120000000');
		$la_cuentas[2]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '121000000');
		$la_cuentas[3]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '121010000');
		$la_cuentas[4]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '121010100');
		$la_cuentas[5]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '121010200');
		$la_cuentas[6]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '121020000');
		$la_cuentas[7]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '121020100');
		$la_cuentas[8]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '121020200');
		$la_cuentas[9]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '121030000');
		$la_cuentas[10]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '121030100');
		$la_cuentas[11]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '121030200');
		$la_cuentas[12]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '122000000');
		$la_cuentas[13]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '122010000');
		$la_cuentas[14]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '122020000');
		$la_cuentas[15]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '122030000');
		$la_cuentas[16]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '122040000');
		$la_cuentas[17]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '122050000');
		$la_cuentas[18]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123000000');
		$la_cuentas[19]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123010000');
		$la_cuentas[20]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123010100');
		$la_cuentas[21]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225010100');
		$la_cuentas[22]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123010200');
		$la_cuentas[23]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225010200');
		$la_cuentas[24]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123010300');
		$la_cuentas[25]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225010300');
		$la_cuentas[26]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123010400');
		$la_cuentas[27]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225010400');
		$la_cuentas[28]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123010500');
		$la_cuentas[29]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225010500');
		$la_cuentas[30]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123010600');
		$la_cuentas[31]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225010600');
		$la_cuentas[32]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123010700');
		$la_cuentas[33]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225010700');
		$la_cuentas[34]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123010800');
		$la_cuentas[35]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225010800');
		$la_cuentas[36]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123010900');
		$la_cuentas[37]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225010900');
		$la_cuentas[38]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123011900');
		$la_cuentas[39]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225011900');
		$la_cuentas[40]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123020000');
		$la_cuentas[41]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123030000');
		$la_cuentas[42]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123040000');
		$la_cuentas[43]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123050000');
		$la_cuentas[44]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123050100');
		$la_cuentas[45]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123050200');
		$la_cuentas[46]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '124000000');
		$la_cuentas[47]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '124010000');
		$la_cuentas[48]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225020100');
		$la_cuentas[49]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '124020000');
		$la_cuentas[50]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225020200');
		$la_cuentas[51]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '124030000');
		$la_cuentas[52]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225020300');
		$la_cuentas[53]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '124040000');
		$la_cuentas[54]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225020400');
		$la_cuentas[55]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '124050000');
		$la_cuentas[56]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225020500');
		$la_cuentas[57]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '124190000');
		$la_cuentas[58]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225021900');
		$la_cuentas[59]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '125000000');
		$la_cuentas[60]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '125010000');
		$la_cuentas[61]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '125010600');
		$la_cuentas[62]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '125010900');
		$la_cuentas[63]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '125090000');
		$la_cuentas[64]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '129000000');
		$la_cuentas[65]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '129010000');
		$la_cuentas[66]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '129010100');
		$la_cuentas[67]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '129090000');
				
		for( $li_pos=1;$li_pos<=67;$li_pos++)
		{
			$ls_cuenta=$la_cuentas[$li_pos];
			$ls_cuenta = substr($ls_cuenta,0,9);

			$ls_sql="SELECT denominacion 
					 FROM   tepuy_plan_unico 
					 WHERE  sc_cuenta = '".$ls_cuenta."'";
					
		   $rs_data=$this->io_sql->select($ls_sql); 
		   if($rs_data===false)	
		   {
				$this->io_msg->message("Error al seleccionar cuenta metodo uf_init_array ".$this->io_fun->uf_convertirmsg($this->io_sql->message));
				print "Error en init array";
				return false;   	
		   }
		   else
		   {
		   		if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_denominacion=$row["denominacion"];
				}
				else
				{
					$ls_denominacion="";
				}			
				$this->ds_cuentas_no_circulante->insertRow("sc_cuenta",$ls_cuenta);
				$this->ds_cuentas_no_circulante->insertRow("denominacion",$ls_denominacion);				
				
				if ($li_pos==1)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",8);
				}
				elseif ($li_pos==12)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",9);
				}
				elseif ($li_pos==18)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",9);
				}
				elseif ($li_pos==46)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",9);
				}
				elseif ($li_pos==59)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",9);
				}
				elseif ($li_pos==64)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",9);
				}
				elseif ($li_pos==20)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}
				elseif ($li_pos==21)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				elseif ($li_pos==22)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}elseif ($li_pos==23)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				elseif ($li_pos==24)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}elseif ($li_pos==25)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				elseif ($li_pos==26)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}
				elseif ($li_pos==27)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				elseif ($li_pos==28)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}
				elseif ($li_pos==29)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				elseif ($li_pos==30)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}
				elseif ($li_pos==31)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				elseif ($li_pos==32)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}
				elseif ($li_pos==33)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				elseif ($li_pos==34)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}
				elseif ($li_pos==35)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				elseif ($li_pos==36)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}
				elseif ($li_pos==37)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				elseif ($li_pos==38)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}
				elseif ($li_pos==39)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				elseif ($li_pos==47)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}
				elseif ($li_pos==48)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				elseif ($li_pos==49)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}
				elseif ($li_pos==50)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				elseif ($li_pos==51)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}
				elseif ($li_pos==52)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				elseif ($li_pos==53)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}
				elseif ($li_pos==54)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				elseif ($li_pos==55)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}
				elseif ($li_pos==56)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				elseif ($li_pos==57)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",3);
				}
				elseif ($li_pos==58)
				{  
					$this->ds_cuentas_no_circulante->insertRow("tipo",2);
				}
				else		
				{
					$this->ds_cuentas_no_circulante->insertRow("tipo",9);
				}			
			}
		}//fin del for
		
    }//fin de uf_grupo_cuentas_activos_no_circulante()
//------------------------------------------------------------------------------------------------------------------------------------------   
	function uf_grupo_cuentas_pasivos()
	{
		$ls_formcont=$this->la_empresa["formcont"];
		$la_cuentas[1]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'200000000');
		$la_cuentas[2]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'210000000');
		$la_cuentas[3]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'211010000');
		$la_cuentas[4]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'211020000');
		$la_cuentas[5]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'211030000');
		$la_cuentas[6]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'211040000');
		$la_cuentas[7]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'211050000');
		$la_cuentas[8]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'214000000');
		$la_cuentas[9]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'214010000');
		$la_cuentas[10]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'214090000');
		$la_cuentas[11]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'219000000');
		$la_cuentas[12]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'219090000');
		$la_cuentas[13]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'220000000');
		$la_cuentas[14]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'221000000');
		$la_cuentas[15]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'221010000');
		$la_cuentas[16]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'221020000');
		$la_cuentas[17]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'224000000');
		$la_cuentas[18]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'224010000');
		$la_cuentas[19]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'224010200');
		$la_cuentas[20]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'224010400');
		$la_cuentas[21]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'224010900');
		$la_cuentas[22]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'224020000');
		$la_cuentas[23]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'229000000');
		$la_cuentas[24]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'229090000');
				
		for( $li_pos=1;$li_pos<=24;$li_pos++)
		{
			$ls_cuenta=$la_cuentas[$li_pos];
			$ls_cuenta = substr($ls_cuenta,0,9);

			$ls_sql="SELECT denominacion 
					 FROM   tepuy_plan_unico 
					 WHERE  sc_cuenta = '".$ls_cuenta."'";
					
		   $rs_data=$this->io_sql->select($ls_sql); 
		   if($rs_data===false)	
		   {
				$this->io_msg->message("Error al seleccionar cuenta metodo uf_init_array ".$this->io_fun->uf_convertirmsg($this->io_sql->message));
				print "Error en init array";
				return false;   	
		   }
		   else
		   {
		   		if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_denominacion=$row["denominacion"];
				}
				else
				{
					$ls_denominacion="";
				}			
				$this->ds_cuentas_pasivos->insertRow("sc_cuenta",$ls_cuenta);
				$this->ds_cuentas_pasivos->insertRow("denominacion",$ls_denominacion);
				if ($li_pos==1)
				{  
					$this->ds_cuentas_pasivos->insertRow("tipo",1);
				}
				elseif ($li_pos==2)
				{  
					$this->ds_cuentas_pasivos->insertRow("tipo",2);
				}
				elseif ($li_pos==13)
				{  
					$this->ds_cuentas_pasivos->insertRow("tipo",9);
				}
				else				
				{  
					$this->ds_cuentas_pasivos->insertRow("tipo",9);
				}	
				$this->ds_cuentas_pasivos->insertRow("suma",0);
				switch($li_pos)
				{
				  case 15:
				  $this->ds_cuentas_pasivos->insertRow("suma",1);
				  break;
				  case 16:
				  $this->ds_cuentas_pasivos->insertRow("suma",1);
				  break;
				  case 17:
				  $this->ds_cuentas_pasivos->insertRow("suma",1);
				  break;				  
				   case 19:
				  $this->ds_cuentas_pasivos->insertRow("suma",1);
				  break;
				  case 20:
				  $this->ds_cuentas_pasivos->insertRow("suma",1);
				  break;
				  case 21:
				  $this->ds_cuentas_pasivos->insertRow("suma",1);
				  break;
				  case 22:
				  $this->ds_cuentas_pasivos->insertRow("suma",1);
				  break;
				  case 23:
				  $this->ds_cuentas_pasivos->insertRow("suma",1);
				  break;
				  case 25:
				  $this->ds_cuentas_pasivos->insertRow("suma",1);
				  break;
				  
				  case 3:
				  $this->ds_cuentas_pasivos->insertRow("suma",2);
				  break;
				  case 4:
				  $this->ds_cuentas_pasivos->insertRow("suma",2);
				  break;
				  case 5:
				  $this->ds_cuentas_pasivos->insertRow("suma",2);
				  break;				  
				   case 6:
				  $this->ds_cuentas_pasivos->insertRow("suma",2);
				  break;
				  case 7:
				  $this->ds_cuentas_pasivos->insertRow("suma",2);
				  break;
				  case 8:
				  $this->ds_cuentas_pasivos->insertRow("suma",2);
				  break;
				  case 10:
				  $this->ds_cuentas_pasivos->insertRow("suma",2);
				  break;
				  case 10:
				  $this->ds_cuentas_pasivos->insertRow("suma",2);
				  break;
				  case 13:
				  $this->ds_cuentas_pasivos->insertRow("suma",2);
				  break;
				}
			}
		}//fin del for
		
    }//fin de uf_grupo_cuentas_pasivos
//------------------------------------------------------------------------------------------------------------------------------------------   

   function uf_procesar_cuentas_contables_pasivos($as_mesdes,$as_meshas,$as_nivel)
   {  		
		$li_total=$this->ds_cuentas_pasivos->getRowCount("sc_cuenta");
		$monto1=0;
		$monto2=0;
		$monto3=0;
		$monto4=0;
		$monto5=0;
		$monto6=0;
		$monto7=0;
		$monto8=0;
		$monto1_t=0;
		$monto2_t=0;
		$monto3_t=0;
		$monto4_t=0;
		$monto5_t=0;
		$monto6_t=0;
		$monto7_t=0;
		$monto8_t=0;			
	    for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$ld_enero=0;		   $ld_febrero=0;
			$ld_marzo=0;		   $ld_abril=0;
			$ld_mayo=0;		       $ld_junio=0;
			$ld_julio=0;		   $ld_agosto=0;
			$ld_septiembre=0;      $ld_octubre=0;
			$ld_noviembre=0;	   $ld_diciembre=0;
			$ls_codrep="0408";     $li_nivel="";
			$ls_status="";
			$ld_saldo_real_ant=0;
			$ld_saldo_apro=0;
			$ld_saldo_mod=0;
			$ld_monto_programado=0;
			$ld_monto_acumulado=0;
			$ld_monto_ejecutado_acumulado=0;
			$ls_sc_cuenta=$this->ds_cuentas_pasivos->getValue("sc_cuenta",$li_i);	
			$ls_denominacion=$this->ds_cuentas_pasivos->getValue("denominacion",$li_i);
			$ls_tipo=$this->ds_cuentas_pasivos->getValue("tipo",$li_i);
			$suma=$this->ds_cuentas_pasivos->getValue("suma",$li_i);
			$lb_valido=$this->uf_scg_reporte_cargar_programado($ls_codrep,$ls_sc_cuenta,
	                                          $ld_enero,$ld_febrero,$ld_marzo,
											  $ld_abril,$ld_mayo,$ld_junio,
											  $ld_julio,$ld_agosto,$ld_septiembre,
											  $ld_octubre,$ld_noviembre,$ld_diciembre,
											  $ld_saldo_real_ant,$ld_saldo_apro,$ld_saldo_mod,
											  $li_nivel,$ls_status);
			$lb_valido=$this->uf_scg_reporte_calcular_programado($as_mesdes,$as_meshas,$ld_monto_programado,$ld_monto_acumulado,
			                                                     $ld_enero,$ld_febrero,$ld_marzo,
																 $ld_abril,$ld_mayo,$ld_junio,
																 $ld_julio,$ld_agosto,$ld_septiembre,
												                 $ld_octubre,$ld_noviembre,$ld_diciembre);
			$this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,$as_mesdes,$as_meshas,$ld_monto_ejecutado_acumulado);
			//variacion absoluta  del periodo entre el  monto ejecutado y monto programado
			if($ld_monto_programado>$ld_monto_ejecutado_acumulado)
			{
				if ($ld_monto_ejecutado_acumulado<0)
				{
					$ld_variacion_absoluta=$ld_monto_ejecutado_acumulado-$ld_monto_programado; 
				}
				else
				{
					$ld_variacion_absoluta=abs($ld_monto_programado-$ld_monto_ejecutado_acumulado); 
				}
			}
			else
			{
		   		$ld_variacion_absoluta=($ld_monto_ejecutado_acumulado); 				
			}
			
			//variacion porcentual  del periodo entre el  monto ejecutado y monto programado
			if($ld_monto_programado>0)
			{ 
				$ld_porcentaje_variacion=(($ld_monto_programado-$ld_monto_ejecutado_acumulado)/$ld_monto_programado)*100;  
			}
			else
			{
				$ld_porcentaje_variacion=0;  
			}				
			switch($as_nivel)
			{
				case 1:				   
					$ls_variacion=abs($ld_monto_ejecutado_acumulado)-$ld_saldo_real_ant; 
				break;						
				case 2:
				   $ld_monto_ejecutado_ant=0;
				   $this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,1,3,$ld_monto_ejecutado_ant);//I trimestre
				   $ls_variacion=abs($ld_monto_ejecutado_ant-$ld_monto_ejecutado_acumulado);				  
				break;
				case 3:
				   $ld_monto_ejecutado_ant=0;
				   $this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,4,6,$ld_monto_ejecutado_ant);//II trimestre
				   $ls_variacion=abs($ld_monto_ejecutado_ant-$ld_monto_ejecutado_acumulado);									
				break;
				case 4:
				   $ld_monto_ejecutado_ant=0;
				   $this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,7,9,$ld_monto_ejecutado_ant);//I trimestre
				   $ls_variacion=abs($ld_monto_ejecutado_ant-$ld_monto_ejecutado_acumulado);					 
				break;				   	
			}
			
			
			$this->ds_saldos_pasivos->insertRow("sc_cuenta",$ls_sc_cuenta);
			$this->ds_saldos_pasivos->insertRow("denominacion",$ls_denominacion);
			$this->ds_saldos_pasivos->insertRow("saldo_real_ant",$ld_saldo_real_ant);
			$this->ds_saldos_pasivos->insertRow("saldo_apro",$ld_saldo_apro);
			$this->ds_saldos_pasivos->insertRow("saldo_mod",$ld_saldo_mod);
			$this->ds_saldos_pasivos->insertRow("saldo_programado",$ld_monto_programado);
			$this->ds_saldos_pasivos->insertRow("saldo_ejecutado",$ld_monto_ejecutado_acumulado);
			$this->ds_saldos_pasivos->insertRow("absoluta",$ld_variacion_absoluta);
			$this->ds_saldos_pasivos->insertRow("porcentual",$ld_porcentaje_variacion);	
			$this->ds_saldos_pasivos->insertRow("varia",$ls_variacion);
			$this->ds_saldos_pasivos->insertRow("tipo",$ls_tipo);	
			$this->ds_saldos_pasivos->insertRow("suma",$suma);				
						
		}//fin del for
   }//fin uf_procesar_cuentas_contables_pasivos
//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_grupo_cuentas_patrimonio()
	{
		$ls_formcont=$this->la_empresa["formcont"];
		$la_cuentas[1]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'300000000');
		$la_cuentas[2]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'320000000');	
		$la_cuentas[3]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'321000000');
		$la_cuentas[4]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'321010000');	
		$la_cuentas[5]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'322000000');	
		$la_cuentas[6]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'322010000');	
		$la_cuentas[7]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'322010100');	
		$la_cuentas[8]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'322010200');	
		$la_cuentas[9]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'322010300');
		$la_cuentas[10]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'322020000');
		$la_cuentas[11]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'322020100');	
		$la_cuentas[12]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'322020200');	
		$la_cuentas[13]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'323000000');	
		$la_cuentas[14]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'323010000');
		$la_cuentas[15]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'324000000');	
		$la_cuentas[16]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'324010000');	
		$la_cuentas[17]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'325000000');	
		$la_cuentas[18]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'325010000');	
		$la_cuentas[19]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont,'325020000');	
		
				
		for( $li_pos=1;$li_pos<=19;$li_pos++)
		{
			$ls_cuenta=$la_cuentas[$li_pos];
			$ls_cuenta = substr($ls_cuenta,0,9);

			$ls_sql="SELECT denominacion 
					 FROM   tepuy_plan_unico 
					 WHERE  sc_cuenta = '".$ls_cuenta."'";
					
		   $rs_data=$this->io_sql->select($ls_sql); 
		   if($rs_data===false)	
		   {
				$this->io_msg->message("Error al seleccionar cuenta metodo uf_init_array ".$this->io_fun->uf_convertirmsg($this->io_sql->message));
				print "Error en init array";
				return false;   	
		   }
		   else
		   {
		   		if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_denominacion=$row["denominacion"];
				}
				else
				{
					$ls_denominacion="";
				}			
				$this->ds_cuentas_patrimonio->insertRow("sc_cuenta",$ls_cuenta);
				$this->ds_cuentas_patrimonio->insertRow("denominacion",$ls_denominacion);					
				$this->ds_cuentas_patrimonio->insertRow("suma",0);
				switch($li_pos)
				{
				  case 1:
				  $this->ds_cuentas_patrimonio->insertRow("tipo",1);	
				  break;
				  case 4:
				  $this->ds_cuentas_patrimonio->insertRow("suma",1);
				  break;
				  case 6:
				  $this->ds_cuentas_patrimonio->insertRow("suma",1);
				  break;
				  case 7:
				  $this->ds_cuentas_patrimonio->insertRow("suma",1);
				  break;
				  case 8:
				  $this->ds_cuentas_patrimonio->insertRow("suma",1);
				  break;
				  case 9:
				  $this->ds_cuentas_patrimonio->insertRow("suma",1);
				  break;
				  case 10:
				  $this->ds_cuentas_patrimonio->insertRow("suma",1);
				  break;
				  case 11:
				  $this->ds_cuentas_patrimonio->insertRow("suma",1);
				  break;
				  case 12:
				  $this->ds_cuentas_patrimonio->insertRow("suma",1);
				  break;
				  case 14:
				  $this->ds_cuentas_patrimonio->insertRow("suma",1);
				  break;
				  case 16:
				  $this->ds_cuentas_patrimonio->insertRow("suma",1);
				  break;
				  case 18:
				  $this->ds_cuentas_patrimonio->insertRow("suma",1);
				  break;
				  case 19:
				  $this->ds_cuentas_patrimonio->insertRow("suma",1);
				  break;
				}
							
			}
		}//fin del for
		
    }//fin de uf_grupo_cuentas_patrimonio
//-----------------------------------------------------------------------------------------------------------------------------------
   function uf_procesar_cuentas_contables_patrimonio($as_mesdes,$as_meshas,$as_nivel)
   {  		
		$li_total=$this->ds_cuentas_patrimonio->getRowCount("sc_cuenta");
		$monto1=0;
		$monto2=0;
		$monto3=0;
		$monto4=0;
		$monto5=0;
		$monto6=0;
		$monto7=0;
		$monto8=0;
		$monto1_t=0;
		$monto2_t=0;
		$monto3_t=0;
		$monto4_t=0;
		$monto5_t=0;
		$monto6_t=0;
		$monto7_t=0;
		$monto8_t=0;			
	    for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$ld_enero=0;		   $ld_febrero=0;
			$ld_marzo=0;		   $ld_abril=0;
			$ld_mayo=0;		       $ld_junio=0;
			$ld_julio=0;		   $ld_agosto=0;
			$ld_septiembre=0;      $ld_octubre=0;
			$ld_noviembre=0;	   $ld_diciembre=0;
			$ls_codrep="0408";     $li_nivel="";
			$ls_status="";
			$ld_saldo_real_ant=0;
			$ld_saldo_apro=0;
			$ld_saldo_mod=0;
			$ld_monto_programado=0;
			$ld_monto_acumulado=0;
			$ld_monto_ejecutado_acumulado=0;
			$ls_sc_cuenta=$this->ds_cuentas_patrimonio->getValue("sc_cuenta",$li_i);	
			$ls_denominacion=$this->ds_cuentas_patrimonio->getValue("denominacion",$li_i);
			$ls_tipo=$this->ds_cuentas_patrimonio->getValue("tipo",$li_i);
			$suma=$this->ds_cuentas_patrimonio->getValue("suma",$li_i);
			$lb_valido=$this->uf_scg_reporte_cargar_programado($ls_codrep,$ls_sc_cuenta,
	                                          $ld_enero,$ld_febrero,$ld_marzo,
											  $ld_abril,$ld_mayo,$ld_junio,
											  $ld_julio,$ld_agosto,$ld_septiembre,
											  $ld_octubre,$ld_noviembre,$ld_diciembre,
											  $ld_saldo_real_ant,$ld_saldo_apro,$ld_saldo_mod,
											  $li_nivel,$ls_status);
			$lb_valido=$this->uf_scg_reporte_calcular_programado($as_mesdes,$as_meshas,$ld_monto_programado,$ld_monto_acumulado,
			                                                     $ld_enero,$ld_febrero,$ld_marzo,
																 $ld_abril,$ld_mayo,$ld_junio,
																 $ld_julio,$ld_agosto,$ld_septiembre,
												                 $ld_octubre,$ld_noviembre,$ld_diciembre);
			$this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,$as_mesdes,$as_meshas,$ld_monto_ejecutado_acumulado);
			//variacion absoluta  del periodo entre el  monto ejecutado y monto programado
			if($ld_monto_programado>$ld_monto_ejecutado_acumulado)
			{
				if ($ld_monto_ejecutado_acumulado<0)
				{
					$ld_variacion_absoluta=$ld_monto_ejecutado_acumulado-$ld_monto_programado; 
				}
				else
				{
					$ld_variacion_absoluta=abs($ld_monto_programado-$ld_monto_ejecutado_acumulado); 
				}
			}
			else
			{
		   		$ld_variacion_absoluta=($ld_monto_ejecutado_acumulado); 				
			}
			
			//variacion porcentual  del periodo entre el  monto ejecutado y monto programado
			if($ld_monto_programado>0)
			{ 
				$ld_porcentaje_variacion=(($ld_monto_programado-$ld_monto_ejecutado_acumulado)/$ld_monto_programado)*100;  
			}
			else
			{
				$ld_porcentaje_variacion=0;  
			}				
			switch($as_nivel)
			{
				case 1:				   
					$ls_variacion=abs($ld_saldo_real_ant-$ld_monto_ejecutado_acumulado); 
				break;						
				case 2:
				   $ld_monto_ejecutado_ant=0;
				   $this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,1,3,$ld_monto_ejecutado_ant);//I trimestre
				   $ls_variacion=abs($ld_monto_ejecutado_ant-$ld_monto_ejecutado_acumulado);				  
				break;
				case 3:
				   $ld_monto_ejecutado_ant=0;
				   $this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,4,6,$ld_monto_ejecutado_ant);//II trimestre
				   $ls_variacion=abs($ld_monto_ejecutado_ant-$ld_monto_ejecutado_acumulado);									
				break;
				case 4:
				   $ld_monto_ejecutado_ant=0;
				   $this->uf_scg_reporte_calcular_ejecutado_acumulado($ls_sc_cuenta,7,9,$ld_monto_ejecutado_ant);//I trimestre
				   $ls_variacion=abs($ld_monto_ejecutado_ant-$ld_monto_ejecutado_acumulado);					 
				break;				   	
			}			
			
			if ($ls_sc_cuenta=="321010000")
			{
				$this->ds_saldos_patrimonio->insertRow("sc_cuenta",$ls_sc_cuenta);
				$this->ds_saldos_patrimonio->insertRow("denominacion",$ls_denominacion);
				$this->ds_saldos_patrimonio->insertRow("saldo_real_ant",$ld_saldo_real_ant);
				$this->ds_saldos_patrimonio->insertRow("saldo_apro",$ld_saldo_apro);
				$this->ds_saldos_patrimonio->insertRow("saldo_mod",$ld_saldo_mod);
				$this->ds_saldos_patrimonio->insertRow("saldo_programado",$ld_monto_programado);
				$this->ds_saldos_patrimonio->insertRow("saldo_ejecutado",$ld_monto_ejecutado_acumulado);
				$this->ds_saldos_patrimonio->insertRow("absoluta",$ld_variacion_absoluta);
				$this->ds_saldos_patrimonio->insertRow("porcentual",$ld_porcentaje_variacion);	
				$this->ds_saldos_patrimonio->insertRow("varia",$ls_variacion);
				$this->ds_saldos_patrimonio->insertRow("tipo",$ls_tipo);	
				$this->ds_saldos_patrimonio->insertRow("suma",$suma);	
				
				$this->ds_saldos_patrimonio->insertRow("sc_cuenta",'');
				$this->ds_saldos_patrimonio->insertRow("denominacion",'TOTAL CAPITAL INSTITUCIONAL');
				$this->ds_saldos_patrimonio->insertRow("saldo_real_ant",$ld_saldo_real_ant);
				$this->ds_saldos_patrimonio->insertRow("saldo_apro",$ld_saldo_apro);
				$this->ds_saldos_patrimonio->insertRow("saldo_mod",$ld_saldo_mod);
				$this->ds_saldos_patrimonio->insertRow("saldo_programado",$ld_monto_programado);
				$this->ds_saldos_patrimonio->insertRow("saldo_ejecutado",$ld_monto_ejecutado_acumulado);
				$this->ds_saldos_patrimonio->insertRow("absoluta",$ld_variacion_absoluta);
				$this->ds_saldos_patrimonio->insertRow("porcentual",$ld_porcentaje_variacion);	
				$this->ds_saldos_patrimonio->insertRow("varia",$ls_variacion);
				$this->ds_saldos_patrimonio->insertRow("tipo",$ls_tipo);	
				$this->ds_saldos_patrimonio->insertRow("suma",0);
			}
			else
			{
				$this->ds_saldos_patrimonio->insertRow("sc_cuenta",$ls_sc_cuenta);
				$this->ds_saldos_patrimonio->insertRow("denominacion",$ls_denominacion);
				$this->ds_saldos_patrimonio->insertRow("saldo_real_ant",$ld_saldo_real_ant);
				$this->ds_saldos_patrimonio->insertRow("saldo_apro",$ld_saldo_apro);
				$this->ds_saldos_patrimonio->insertRow("saldo_mod",$ld_saldo_mod);
				$this->ds_saldos_patrimonio->insertRow("saldo_programado",$ld_monto_programado);
				$this->ds_saldos_patrimonio->insertRow("saldo_ejecutado",$ld_monto_ejecutado_acumulado);
				$this->ds_saldos_patrimonio->insertRow("absoluta",$ld_variacion_absoluta);
				$this->ds_saldos_patrimonio->insertRow("porcentual",$ld_porcentaje_variacion);	
				$this->ds_saldos_patrimonio->insertRow("varia",$ls_variacion);
				$this->ds_saldos_patrimonio->insertRow("tipo",$ls_tipo);	
				$this->ds_saldos_patrimonio->insertRow("suma",$suma);
			}			
						
		}//fin del for
   }//fin uf_procesar_cuentas_contables_patrimonio
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_scg_reporte_cargar_programado($as_codrep,$as_sc_cuenta,
	                                          &$ad_enero,&$ad_febrero,&$ad_marzo,
											  &$ad_abril,&$ad_mayo,&$ad_junio,
											  &$ad_julio,&$ad_agosto,&$ad_septiembre,
											  &$ad_octubre,&$ad_noviembre,&$ad_diciembre,
											  &$ad_saldo_real_ant,&$ad_saldo_apro,&$ad_saldo_mod,
											  &$ai_nivel,&$as_status)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_cargar_programado
	 //         Access :	private
	 //     Argumentos :    $as_codrep  -->  codigo del reporte
	 //                     $as_sc_cuenta -->  codigo de la  cuenta 
	 //                     $ad_enero .. $ad_diciembre --> monto programado para cada  mes  
	 //						&$ad_saldo_real_ant // saldo real dela ño anterior
	 //						&$ad_saldo_apro // saldo presupuestario aprobado
	 //						&$ad_saldo_mod // saldo Modificado
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte por referencia los saldos iniciales programados y ejecutados.   
	 //     Creado por :    Ing. Jennifer Rivero
	 // Fecha Creación :    13/07/2008               Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $ls_sql=" SELECT sum(asignado) as asignado, sum(enero) as enero, sum(febrero) as febrero, sum(marzo) as marzo, ".
              "        sum(abril) as abril, sum(mayo) as mayo,sum(junio) as junio, sum(julio) as julio, ".
       		  "		   sum(agosto) as agosto, sum(septiembre) as septiembre,sum(octubre) as octubre, ".
              "        sum(noviembre) as noviembre,sum(diciembre) as diciembre, nivel, status, denominacion, ".
			  "        sum(saldo_real_ant) as saldo_real_ant, sum(saldo_apro) as saldo_apro, sum(saldo_mod) as saldo_mod ".
              " FROM   scg_pc_reporte ".
              " WHERE  codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."' AND substr(sc_cuenta,1,9)='".$as_sc_cuenta."' ".
              " GROUP BY sc_cuenta "; 
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		  $this->io_msg->message("CLASE->tepuy_scg_class_instructivo07  MÉTODO->uf_scg_reporte_cargar_programado  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		  $lb_valido = false;
	  }
	  else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			   $ai_nivel=$row["nivel"];
			   $as_status=$row["status"];
			   $ad_asignado=$row["asignado"];
			   $ad_enero=$row["enero"];
			   $ad_febrero=$row["febrero"];
			   $ad_marzo=$row["marzo"];
			   $ad_abril=$row["abril"];
			   $ad_mayo=$row["mayo"];
			   $ad_junio=$row["junio"];
			   $ad_julio=$row["julio"];
			   $ad_agosto=$row["agosto"];
			   $ad_septiembre=$row["septiembre"];
			   $ad_octubre=$row["octubre"];
			   $ad_noviembre=$row["noviembre"];
			   $ad_diciembre=$row["diciembre"];
			   $ad_saldo_real_ant=$row["saldo_real_ant"];
			   $ad_saldo_apro=$row["saldo_apro"];
			   $ad_saldo_mod=$row["saldo_mod"];
		       $lb_valido = true;
	    }
		else
		{
			   $ai_nivel="";
			   $as_status="";
			   $ad_asignado=0;
			   $ad_enero=0;
			   $ad_febrero=0;
			   $ad_marzo=0;
			   $ad_abril=0;
			   $ad_mayo=0;
			   $ad_junio=0;
			   $ad_julio=0;
			   $ad_agosto=0;
			   $ad_septiembre=0;
			   $ad_octubre=0;
			   $ad_noviembre=0;
			   $ad_diciembre=0;
			    $ad_saldo_real_ant=0;
			   $ad_saldo_apro=0;
			   $ad_saldo_mod=0;
		       $lb_valido = true;
		}
		$this->io_sql->free_result($rs_data);
      }//else
	 return $lb_valido;
   }//fin uf_scg_reporte_select_saldo_empresa
//------------------------------------------------------------------------------------------------------------------------------------------
    function uf_scg_reporte_calcular_programado($ai_mesdes,$ai_meshas,&$ad_monto_programado,&$ad_monto_acumulado,$ad_enero,$ad_febrero,
												$ad_marzo,$ad_abril,$ad_mayo,$ad_junio,$ad_julio,$ad_agosto,$ad_septiembre,
												$ad_octubre,$ad_noviembre,$ad_diciembre)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_calcular_programado
	 //         Access :	private
	 //     Argumentos :    $as_estructura_desde  // codigo programatico desde
	 //                     $as_estructura_hasta  //  codigo programatico hasta
	 //                     $as_mesdes  // mes  desde
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_programado // monto programado del mes (referencia)  
	 //                     $ad_monto_acumulado // monto programado del acumulado (referencia)  
	 //                     $ad_enero .. $ad_diciembre  // monto programado desde  enero  hasta diciembre  
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	metodo que calcula los montos programados y los acumulados
	 //     Creado por :    Ing. Jennifer Rivero
	 // Fecha Creación :    13/07/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $lb_valido=true;
     $li_mesdes=intval($ai_mesdes);
     $li_meshas=intval($ai_meshas);
     if(!(($li_mesdes>=1)&&($li_meshas<=12)))
     {
	   $lb_valido=false;
     }
     if($lb_valido)
     {
	   for($i=$li_mesdes;$i<=$li_meshas;$i++)
	   {
		 switch ($li_mesdes)
		 {
			 case 1:
				  $ad_monto_programado=$ad_monto_programado+$ad_enero;
			 break;
			 case 2:
				  $ad_monto_programado=$ad_monto_programado+$ad_febrero;
			 break;
			 case 3:
				  $ad_monto_programado=$ad_monto_programado+$ad_marzo;
			 break;
			 case 4:
				  $ad_monto_programado=$ad_monto_programado+$ad_abril;
			 break;
			 case 5:
				  $ad_monto_programado=$ad_monto_programado+$ad_mayo;
			 break;
			 case 6:
				  $ad_monto_programado=$ad_monto_programado+$ad_junio;
			 break;
			 case 7:
				  $ad_monto_programado=$ad_monto_programado+$ad_julio;
			 break;
			 case 8:
				  $ad_monto_programado=$ad_monto_programado+$ad_agosto;
			 break;
			 case 9:
				  $ad_monto_programado=$ad_monto_programado+$ad_septiembre;
			 break;
			 case 10:
				  $ad_monto_programado=$ad_monto_programado+$ad_octubre;
			 break;
			 case 11:
				  $ad_monto_programado=$ad_monto_programado+$ad_noviembre;
			 break;
			 case 12:
				  $ad_monto_programado=$ad_monto_programado+$ad_diciembre;
			 break;
		 }//switch
	   }//for
	   for($i=1;$i<=$li_meshas;$i++)
	   {
		 switch ($i)
		 {
			 case 1:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_enero;
			 break;
			 case 2:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_febrero;
			 break;
			 case 3:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_marzo;
			 break;
			 case 4:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_abril;
			 break;
			 case 5:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_mayo;
			 break;
			 case 6:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_junio;
			 break;
			 case 7:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_julio;
			 break;
			 case 8:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_agosto;
			 break;
			 case 9:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_septiembre;
			 break;
			 case 10:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_octubre;
			 break;
			 case 11:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_noviembre;
			 break;
			 case 12:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_diciembre;
			 break;
		 }//switch
	   }//for		
	   }//if
	   return  $lb_valido; 
   }//fin uf_scg_reporte_calcular_programado
//-------------------------------------------------------------------------------------------------------------------------------------------
   function uf_scg_reporte_calcular_ejecutado_acumulado($as_sc_cuenta,$ls_mesdes,$ls_meshas,&$ad_monto_ejecutado_acumulado)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_calcular_ejecutado_acumulado
	 //         Access :	private
	 //     Argumentos :    $as_mesdes  // mes  desde 
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_ejecutado // monto ejecutado del mes (referencia)  
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	Metodo que calcula los montos ejecutados y los acumulados
	 //     Creado por :    Ing. Jennifer Rivero
	 // Fecha Creación :    13/07/2008         Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $ad_monto_ejecutado_acumulado=0;
	  $lb_valido=true;
	  $ldt_periodo=$_SESSION["la_empresa"]["periodo"];
	  $li_ano=substr($ldt_periodo,0,4);
	  $ls_diades="01";
	  $ls_diahas=$this->io_fecha->uf_last_day($ls_meshas,$li_ano);
	  $ls_diahas=substr($ls_diahas,0,3)."0".substr($ls_diahas,3,9);   
	  $ls_diahas=$this->io_fun->uf_convertirdatetobd($ls_diahas);
	  $ldt_fecdes=$ls_diades."/".$ls_mesdes."/".$li_ano;	//print $ls_diahas."<br>";	
	  $adt_feshas= $ls_diahas;	  
	  $ls_cuenta=$this->int_scg->uf_pad_scg_cuenta($_SESSION["la_empresa"]["formcont"],$as_sc_cuenta);

	  $ls_sql=" SELECT COALESCE(sum(debe_mes),0) as debe_mes, COALESCE(sum(haber_mes),0) as haber_mes ".
              " FROM   scg_saldos ".
              " WHERE  codemp='".$this->ls_codemp."'  AND  sc_cuenta like '".$ls_cuenta."' AND  ".
			  "        fecsal <='".$adt_feshas."' "; 	  
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		  $this->io_msg->message("CLASE->tepuy_scg_class_instructivo07  MÉTODO->uf_scg_reporte_calcular_ejecutado_acumulado  ERROR->".
		                         $this->io_function->uf_convertirmsg($this->io_sql->message));
		  $lb_valido = false;
	  }
	  else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
          $ld_debe_mes=$row["debe_mes"];
		  $ld_haber_mes=$row["haber_mes"];
		  $ad_monto_ejecutado_acumulado=$ld_debe_mes-$ld_haber_mes;
		}//if
		$this->io_sql->free_result($rs_data);
	  }//else
	  return $lb_valido;
    }//fin uf_scg_reporte_calcular_ejecutado
//------------------------------------------------------------------------------------------------------------------------------------------
	
}
?>
