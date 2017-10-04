<?php
require_once("../shared/class_folder/class_mensajes.php");
class tepuy_scg_procesos
{
	var $rs_oaf="";
	var $is_msg_error;
    var $lb_valido=false;
    var $msg;
 function tepuy_scg_procesos()
 {
	$in=new tepuy_include();
	$this->con=$in->uf_conectar();
    $this->SQL=new class_sql($this->con);
	$this->msg=new class_mensajes();
		
 }
   
 function uf_select_scg_plantillacuentareporte($as_codemp,$as_cod_report)	
 { 
   //////////////////////////////////////////////////////////////////////  
   // Function:uf_select_scg_plantillacuentareporte
   // Argumentos: string $as_codemp,
   //             string $as_cod_report, 
   //             
   // Return      $li_rtn: integer
   // Descripcion: selecciona las plantillas de las cuenta reporte 
   //////////////////////////////////////////////////////////////////////  
   $li_rtn=0; $li_count=0; $li_row=0;
   $ls_sql="";
   
   $ls_sql =   " SELECT cod_report, sc_cuenta,".   
	           " denominacion, ".  
	           " status, ".
	           " asignado,".  
	           " distribuir,".   
	           " enero,".
	           " febrero,".  
	           " marzo,". 
	           " abril,". 
	           " mayo,". 
	           " junio,". 
	           " julio,". 
	           " agosto,". 
	           " septiembre,".   
	           " octubre,".
	           " noviembre,". 
	           " diciembre,". 
	           " nivel,". 
	           " referencia,".
	           " no_fila,".
	           " tipo,".
	           " cta_res, saldo_real_ant, saldo_apro, saldo_mod ".
	           " FROM scg_pc_reporte".
		       " WHERE codemp = '".$as_codemp."'AND trim(cod_report) = '".$as_cod_report."'" ;
	
	   	$rs_oaf=$this->SQL->select($ls_sql);
		$li_row=$this->SQL->num_rows($rs_oaf);
	return	$li_row;
 }	
 
  function uf_select_scg_datastore($as_codemp, $as_cod_report )	
 { // Function:uf_select_scg_plantillacuentareporte
   // Argumentos: string $as_codemp,
   //             string $as_cod_report, 
   // Return      $li_rtn: integer
   // Descripcion: selecciona las plantillas de las cuenta reporte para llenar la tabla 
   ///////////////////////////////////////////////////////////////////////////////////// 
   
   $ls_sql =   " SELECT cod_report, sc_cuenta,".   
	           " denominacion, ".  
	           " status, ".
	           " asignado,".  
	           " distribuir,".   
	           " enero,".
	           " febrero,".  
	           " marzo,". 
	           " abril,". 
	           " mayo,". 
	           " junio,". 
	           " julio,". 
	           " agosto,". 
	           " septiembre,".   
	           " octubre,".
	           " noviembre,". 
	           " diciembre,". 
	           " nivel,". 
	           " referencia,".
	           " no_fila,".
	           " tipo,".
	           " cta_res,".
			   " modrep, saldo_real_ant, saldo_apro, saldo_mod ".
	           " FROM scg_pc_reporte".
		       " WHERE codemp = '".$as_codemp."'AND trim(cod_report) = '".$as_cod_report."'
			     ORDER BY no_fila" ;
	   	$rs_oaf=$this->SQL->select($ls_sql);
	return	$rs_oaf;
 }

  function uf_select_datastore_trim($as_codemp, $as_cod_report)	
 { // Function:uf_select_datastore_trim
   // Argumentos: string $as_codemp,
   //             string $as_cod_report, 
   // Return      $li_rtn: integer
   // Descripcion: selecciona las plantillas de las cuenta reporte para llenar la tabla 
   ///////////////////////////////////////////////////////////////////////////////////// 
   
   $ls_sql =   " SELECT cod_report, sc_cuenta,".   
	           " denominacion, ".  
	           " status, ".
	           " asignado,".  
	           " distribuir,".   
	           " enero,".
	           " febrero,".  
	           " marzo,". 
	           " abril,". 
	           " mayo,". 
	           " junio,". 
	           " julio,". 
	           " agosto,". 
	           " septiembre,".   
	           " octubre,".
	           " noviembre,". 
	           " diciembre,". 
	           " nivel,". 
	           " referencia,".
	           " no_fila,".
	           " tipo,".
	           " cta_res,".
			   " modrep, saldo_real_ant, saldo_apro, saldo_mod ".
	           " FROM scg_pc_reporte".
		       " WHERE codemp ='".$as_codemp."' AND trim(cod_report) ='".$as_cod_report."' AND (modrep='3' OR  modrep='0') " ;
	   	$rs_oaf=$this->SQL->select($ls_sql);
	return	$rs_oaf;
 }
	
  function uf_select_datastore_mensual($as_codemp, $as_cod_report)	
 { // Function:uf_select_datastore_mensual
   // Argumentos: string $as_codemp,
   //             string $as_cod_report, 
   // Return      $li_rtn: integer
   // Descripcion: selecciona las plantillas de las cuenta reporte para llenar la tabla 
   ///////////////////////////////////////////////////////////////////////////////////// 
   
   $ls_sql =   " SELECT cod_report, sc_cuenta,".   
	           " denominacion, ".  
	           " status, ".
	           " asignado,".  
	           " distribuir,".   
	           " enero,".
	           " febrero,".  
	           " marzo,". 
	           " abril,". 
	           " mayo,". 
	           " junio,". 
	           " julio,". 
	           " agosto,". 
	           " septiembre,".   
	           " octubre,".
	           " noviembre,". 
	           " diciembre,". 
	           " nivel,". 
	           " referencia,".
	           " no_fila,".
	           " tipo,".
	           " cta_res,".
			   " modrep, saldo_real_ant, saldo_apro, saldo_mod ".
	           " FROM scg_pc_reporte".
		       " WHERE codemp ='".$as_codemp."' AND trim(cod_report) ='".$as_cod_report."' AND (modrep='1' OR  modrep='0') " ; 
	   	$rs_oaf=$this->SQL->select($ls_sql);
	return	$rs_oaf;
 }
 
  function uf_cargar_txt_inversiones_0714($as_codemp)	
 {  ////////////////////////////////////////////////////////////////////////////////////
 	//	Function:  uf_cargar_txt_inversiones_0714
	//	Access:  public
	//	Description:  Este método accesa la información de las cuentas de inversion
	//                y procede a insertarla en la tabla SCG_PC_Reporte
	////////////////////////////////////////////////////////////////////////////////////
	
	$ls_linea=""; $ls_cadena_linea=""; $ls_cuenta=""; $ls_denominacion=""; $ls_denominacion_plan=""; $li_no_fila="";
	$ls_codreport=""; $ls_status=""; $ls_ref=""; $ls_tipo=""; $ls_cta_res=""; $ls_sql="";
	$ldc_asignado=0; $ldc_ene=0; $ldc_feb=0; $ldc_mar=0; $ldc_abr=0; $ldc_may=0; $ldc_jun=0;
	$ldc_jul=0; $ldc_ago=0; $ldc_sep=0; $ldc_oct=0; $ldc_nov=0; $ldc_dic=0;
	$li_NumFile=0; $li_Read_Result=0; $li_valid=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0 ;
	$lb_valido=true;
	       
		    $ls_archivo = file("inversiones_0714.txt");
            $ls_linea = count($ls_archivo);
		    
			for ($i=0; $i < $ls_linea; $i++)
			{
	           // Reemplazar por el procesamiento
	           $ls_cadena_linea = $ls_archivo[$i];
	           $ls_codreport    = substr($ls_cadena_linea,0,5);	    //5
			   $ls_cuenta       = substr($ls_cadena_linea,5,25);    //25
			   $ls_denominacion = substr($ls_cadena_linea,30,100);  //100
			   $ls_status       = substr($ls_cadena_linea,130,1); //1
			   $ldc_asignado    = substr($ls_cadena_linea,131,1); //1  
			   $li_distribuir   = substr($ls_cadena_linea,132,1); //1 
			   $ldc_ene         = substr($ls_cadena_linea,133,1); //1  
			   $ldc_feb         = substr($ls_cadena_linea,134,1); //1  
			   $ldc_mar         = substr($ls_cadena_linea,135,1); //1  
			   $ldc_abr         = substr($ls_cadena_linea,136,1); //1  
			   $ldc_may         = substr($ls_cadena_linea,137,1); //1  
			   $ldc_jun         = substr($ls_cadena_linea,138,1); //1  
			   $ldc_jul         = substr($ls_cadena_linea,139,1); //1  
			   $ldc_ago         = substr($ls_cadena_linea,140,1); //1  
			   $ldc_sep         = substr($ls_cadena_linea,141,1); //1  
			   $ldc_oct         = substr($ls_cadena_linea,142,1); //1  
			   $ldc_nov         = substr($ls_cadena_linea,143,1); //1  
			   $ldc_dic         = substr($ls_cadena_linea,144,1); //1  
			   $li_nivel        = substr($ls_cadena_linea,145,1); //1  
			   $ls_ref          = substr($ls_cadena_linea,146,25); //25
			   $li_no_fila++;  //3	
			   $ls_tipo         = ""; //1
			   $ls_cta_res      = ""; 	
			   $ls_codreport    = str_replace("-", " ", $ls_codreport);
			   $ls_cuenta       = str_replace("-", " ", $ls_cuenta);
			   $ls_denominacion = str_replace("-", " ", $ls_denominacion);
	           $ls_ref          = str_replace("-", " ", $ls_ref);
	           $ls_modrep		= "0";  //modalidad mensual 	              			
				  //INSERT
				    $ls_sql = "INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep)".
				    " VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".$ls_status."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',".$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."')" ;
				    
					$li_exec=$this->SQL->execute($ls_sql);                                                                                                                                                                                          
					if($li_exec<=0)
					{
						$is_msg_error = "Error en método uf_cargar_txt_inversiones ";
						//$msg->message("Error en método uf_cargar_txt_inversiones "); 
						$lb_valido = false;
						$li_rtn=0;
					}
					else
					{
						$li_rtn=1;
					}
			
	}
		
	if ($li_rtn=1)
	{
	    $this->SQL->commit();
		$is_msg_error = "Cuentas de Inversión cargadas..";
		//$msg->message("Cuentas de Inversión cargadas..");
		$lb_valido = true;
	}
	else
	{
	    $this->SQL->rollback();
		$is_msg_error = " Cuentas de Inversión no cargadas.." ;
		//$msg->message(" Cuentas de Inversión no cargadas..");
		$lb_valido = false;
	}
	
	return $lb_valido;
	
 } // fin de uf_cargar_txt_inversiones_0714
	
 function uf_cargar_txt_inversiones($as_codemp)	
 {  ////////////////////////////////////////////////////////////////////////////////////
 	//	Function:  uf_cargar_txt_inversiones
	//	Access:  public
	//	Description:  Este método accesa la información de las cuentas de inversion
	//                y procede a insertarla en la tabla SCG_PC_Reporte
	////////////////////////////////////////////////////////////////////////////////////
	
	$ls_linea=""; $ls_cadena_linea=""; $ls_cuenta=""; $ls_denominacion=""; $ls_denominacion_plan=""; $li_no_fila="";
	$ls_codreport=""; $ls_status=""; $ls_ref=""; $ls_tipo=""; $ls_cta_res=""; $ls_sql="";
	$ldc_asignado=0; $ldc_ene=0; $ldc_feb=0; $ldc_mar=0; $ldc_abr=0; $ldc_may=0; $ldc_jun=0;
	$ldc_jul=0; $ldc_ago=0; $ldc_sep=0; $ldc_oct=0; $ldc_nov=0; $ldc_dic=0;
	$li_NumFile=0; $li_Read_Result=0; $li_valid=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0 ;
	$lb_valido=true;
	       
		    $ls_archivo = file("inversiones.txt");
            $ls_linea = count($ls_archivo);
		    
			for ($i=0; $i < $ls_linea; $i++)
			{
	           // Reemplazar por el procesamiento
	           $ls_cadena_linea = $ls_archivo[$i];
	           $ls_codreport    = substr($ls_cadena_linea,0,5);	    //5
			   $ls_cuenta       = substr($ls_cadena_linea,5,25);    //25
			   $ls_denominacion = substr($ls_cadena_linea,30,100);  //100
			   $ls_status       = substr($ls_cadena_linea,130,1); //1
			   $ldc_asignado    = substr($ls_cadena_linea,131,1); //1  
			   $li_distribuir   = substr($ls_cadena_linea,132,1); //1 
			   $ldc_ene         = substr($ls_cadena_linea,133,1); //1  
			   $ldc_feb         = substr($ls_cadena_linea,134,1); //1  
			   $ldc_mar         = substr($ls_cadena_linea,135,1); //1  
			   $ldc_abr         = substr($ls_cadena_linea,136,1); //1  
			   $ldc_may         = substr($ls_cadena_linea,137,1); //1  
			   $ldc_jun         = substr($ls_cadena_linea,138,1); //1  
			   $ldc_jul         = substr($ls_cadena_linea,139,1); //1  
			   $ldc_ago         = substr($ls_cadena_linea,140,1); //1  
			   $ldc_sep         = substr($ls_cadena_linea,141,1); //1  
			   $ldc_oct         = substr($ls_cadena_linea,142,1); //1  
			   $ldc_nov         = substr($ls_cadena_linea,143,1); //1  
			   $ldc_dic         = substr($ls_cadena_linea,144,1); //1  
			   $li_nivel        = substr($ls_cadena_linea,145,1); //1  
			   $ls_ref          = substr($ls_cadena_linea,146,25); //25
			   $li_no_fila++;  //3	
			   $ls_tipo         = ""; //1
			   $ls_cta_res      = ""; 	
			   $ls_codreport    = str_replace("-", " ", $ls_codreport);
			   $ls_cuenta       = str_replace("-", " ", $ls_cuenta);
			   $ls_denominacion = str_replace("-", " ", $ls_denominacion);
	           $ls_ref          = str_replace("-", " ", $ls_ref);
	           $ls_modrep		= "0";  //modalidad mensual 	              			
				  //INSERT
				    $ls_sql = "INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep)".
				    " VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".$ls_status."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',".$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."')" ;
				    
					$li_exec=$this->SQL->execute($ls_sql);                                                                                                                                                                                          
					if($li_exec<=0)
					{
						$is_msg_error = "Error en método uf_cargar_txt_inversiones ";
						//$msg->message("Error en método uf_cargar_txt_inversiones "); 
						$lb_valido = false;
						$li_rtn=0;
					}
					else
					{
						$li_rtn=1;
					}
			
	}
		
	if ($li_rtn=1)
	{
	    $this->SQL->commit();
		$is_msg_error = "Cuentas de Inversión cargadas..";
		//$msg->message("Cuentas de Inversión cargadas..");
		$lb_valido = true;
	}
	else
	{
	    $this->SQL->rollback();
		$is_msg_error = " Cuentas de Inversión no cargadas.." ;
		//$msg->message(" Cuentas de Inversión no cargadas..");
		$lb_valido = false;
	}
	
	return $lb_valido;
	
 } // fin de uf_cargar_txt_inversiones	
	
	
 function  uf_cargar_txt_edoresultado($as_codemp)	
 {  ///////////////////////////////////////////////////////////////////////////////////////////////
 	//	Function:  uf_cargar_txt_edoresultado
	//	Access:  public
	//	Description:  Este método accesa la información de las cuentas de estado de resultado
	//                y procede a insertarla en la tabla SCG_PC_Reporte
	/////////////////////////////////////////////////////////////////////////////////////////////////
	$ls_linea=""; $ls_cadena_linea=""; $ls_cuenta=""; $ls_denominacion=""; $ls_denominacion_plan=""; $ls_no_fila="";
	$ls_codreport=""; $ls_status=""; $ls_ref=""; $ls_tipo=""; $ls_cta_res=""; $ls_sql="";
	$ldc_asignado=0; $ldc_ene=0; $ldc_feb=0; $ldc_mar=0; $ldc_abr=0; $ldc_may=0; $ldc_jun=0;
	$ldc_jul=0; $ldc_ago=0; $ldc_sep=0; $ldc_oct=0; $ldc_nov=0; $ldc_dic=0;
	$li_NumFile=0; $li_Read_Result=0; $li_valid=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0 ;
	$lb_valido=true; 
	
	        $ls_archivo = file("estado_de_resultado.txt");
			$ls_linea = count($ls_archivo);
			
			for($i=0; $i < $ls_linea; $i++)
	        {
	              // Reemplazar por el procesamiento
	              $ls_cadena_linea = $ls_archivo[$i];
	              $ls_codreport    = substr($ls_cadena_linea,0,5);	    //5
				  $ls_cuenta       = substr($ls_cadena_linea,5,25);    //25
				  $ls_denominacion = substr($ls_cadena_linea,30,100);  //100
				  $ls_status       = substr($ls_cadena_linea,130,1); //1
				  $ldc_asignado    = substr($ls_cadena_linea,131,1); //1  
				  $li_distribuir   = substr($ls_cadena_linea,132,1);   //1 
				  $ldc_ene         = substr($ls_cadena_linea,133,1); //1  
				  $ldc_feb         = substr($ls_cadena_linea,134,1); //1  
				  $ldc_mar         = substr($ls_cadena_linea,135,1); //1  
				  $ldc_abr         = substr($ls_cadena_linea,136,1); //1  
				  $ldc_may         = substr($ls_cadena_linea,137,1); //1  
				  $ldc_jun         = substr($ls_cadena_linea,138,1); //1  
				  $ldc_jul         = substr($ls_cadena_linea,139,1); //1  
				  $ldc_ago         = substr($ls_cadena_linea,140,1); //1  
				  $ldc_sep         = substr($ls_cadena_linea,141,1); //1  
				  $ldc_oct         = substr($ls_cadena_linea,142,1); //1  
				  $ldc_nov         = substr($ls_cadena_linea,143,1); //1  
				  $ldc_dic         = substr($ls_cadena_linea,144,1); //1  
				  $li_nivel        = substr($ls_cadena_linea,145,1); //1  
				  $ls_ref          = substr($ls_cadena_linea,146,25); //25
				  $li_no_fila++;  //3	
				  $ls_tipo         = ""; //1
				  $ls_cta_res      = ""; 	
				  $ls_codreport    = str_replace("-", " ", $ls_codreport);
				  $ls_cuenta       = str_replace("-", " ", $ls_cuenta);
				  $ls_denominacion = str_replace("-", " ", $ls_denominacion);
				  $ls_ref          = str_replace("-", " ", $ls_ref);
	              $ls_modrep	   = "0";		              	              			
				  //INSERT
				    $ls_sql= "INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep)".
				    " VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".trim($ls_status)."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',".$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."')" ;
					$li_exec=$this->SQL->execute($ls_sql);
					if($li_exec<=0)
					{
						$is_msg_error = "Error en método uf_cargar_txt_edoresultado ";
						print $ls_sql;
						print "Error en método uf_cargar_txt_edoresultado "."<br>";
						$lb_valido = false;
						$li_rtn=0;
					}
					else
					{
						$li_rtn=1;
					}
			
		}
	    if ($li_rtn=1)
	    {
           $this->SQL->commit();		
		   $lb_valido = true;  
		   //$msg->message("Cuentas de Estado de Resultado cargadas..");
		   $is_msg_error = "Cuentas de Estado de Resultado cargadas.."	;
		}
	    else
	   {
	      $this->SQL->rollback();
		  $lb_valido = false;
		  $is_msg_error = " Cuentas de Estado de Resultado no cargadas.." ;
		  //$msg->message("Cuentas de Estado de Resultado no cargadas..");
	   }

	return $lb_valido;
	
 } // fin de uf_cargar_txt_edoresultado
 
 
 function uf_cargar_txt_balancegeneral($as_codemp)	
 {  ////////////////////////////////////////////////////////////////////////////////////////////////
 	//	Function:  uf_cargar_txt_balancegeneral
	//	Access:  public
	//	Description:  Este método accesa la información de las cuentas de balance general
	//                y procede a insertarla en la tabla SCG_PC_Reporte
	//               
	/////////////////////////////////////////////////////////////////////////////////////////////////
	$ls_linea=0; $ls_cadena_linea=""; $ls_cuenta=""; $ls_denominacion=""; $ls_denominacion_plan=""; $ls_no_fila="";
	$ls_codreport=""; $ls_status=""; $ls_ref=""; $ls_tipo=""; $ls_cta_res=""; $ls_sql="";
	$ldc_asignado=0; $ldc_ene=0; $ldc_feb=0; $ldc_mar=0; $ldc_abr=0; $ldc_may=0; $ldc_jun=0;
	$ldc_jul=0; $ldc_ago=0; $ldc_sep=0; $ldc_oct=0; $ldc_nov=0; $ldc_dic=0;
	$li_NumFile=0; $li_Read_Result=0; $li_valid=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; //$li_rtn=0;
	$lb_valido=true;
	
	   
		$ls_archivo = file("balance_general.txt");
		$ls_linea = count($ls_archivo);
		for ($i=0; $i < $ls_linea; $i++)   
	    {
	       // Reemplazar por el procesamiento
	       $ls_cadena_linea = $ls_archivo[$i];
		   $ls_codreport    = substr($ls_cadena_linea,0,5);	    //5
		   $ls_cuenta       = substr($ls_cadena_linea,5,25);    //25
		   $ls_denominacion = substr($ls_cadena_linea,30,100);  //100
		   $ls_status       = substr($ls_cadena_linea,130,1); //1
		   $ldc_asignado    = substr($ls_cadena_linea,131,1); //1  
		   $li_distribuir   = substr($ls_cadena_linea,132,1); //1 
		   $ldc_ene         = substr($ls_cadena_linea,133,1); //1  
		   $ldc_feb         = substr($ls_cadena_linea,134,1); //1  
		   $ldc_mar         = substr($ls_cadena_linea,135,1); //1  
		   $ldc_abr         = substr($ls_cadena_linea,136,1); //1  
		   $ldc_may         = substr($ls_cadena_linea,137,1); //1  
		   $ldc_jun         = substr($ls_cadena_linea,138,1); //1  
		   $ldc_jul         = substr($ls_cadena_linea,139,1); //1  
		   $ldc_ago         = substr($ls_cadena_linea,140,1); //1  
		   $ldc_sep         = substr($ls_cadena_linea,141,1); //1  
		   $ldc_oct         = substr($ls_cadena_linea,142,1); //1  
		   $ldc_nov         = substr($ls_cadena_linea,143,1); //1  
		   $ldc_dic         = substr($ls_cadena_linea,144,1); //1  
		   $li_nivel        = substr($ls_cadena_linea,145,1); //1  
		   $ls_ref          = substr($ls_cadena_linea,146,25); //25
		   $li_no_fila++;  //3	
		   $ls_tipo         = ""; //1
		   $ls_cta_res      = ""; 	
		   $ls_codreport    = str_replace("-", " ", $ls_codreport);
		   $ls_cuenta       = str_replace("-", " ", $ls_cuenta);
		   $ls_denominacion = str_replace("-", " ", $ls_denominacion);
	       $ls_ref          = str_replace("-", " ", $ls_ref);
	       $ls_modrep		= "0";      	              			
		  //INSERT
	      $ls_sql= " INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep)".
				   " VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".$ls_status."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',".$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."')" ;
		 	$li_exec=$this->SQL->execute($ls_sql);
						
			if($li_exec<=0)
			{
				$is_msg_error = "Error en método uf_cargar_txt_balancegeneral ";
				//$msg->message("Error en método uf_cargar_txt_balancegeneral");
				$lb_valido = false;
				$li_rtn=0;
			}
			else
			{
				$li_rtn=1;
			}
			
		}
	   	if ($li_rtn=1)
	    {
		  $this->SQL->commit();
		  $lb_valido = true;
		  //$msg->message("Cuentas de Balance General cargadas..");
		  $is_msg_error = "Cuentas de Balance General cargadas.."	;
		}
	
    	else
	   {
		  $this->SQL->rollback();
		  $lb_valido = false;
		  //$msg->message("Cuentas de Balance General no cargadas..");
		  $is_msg_error = " Cuentas de Balance General no cargadas.." ;
	   }

	return $lb_valido;
	
 } // fin de uf_cargar_txt_balancegeneral
 
 
 function uf_cargar_txt_ctaahorroinversion($as_codemp)	
 {  ////////////////////////////////////////////////////////////////////////////////////////////
 	//	Function:  uf_cargar_txt_ctaahorroinversion
	//	Access:  public
	//	Description:  Este método accesa la información de las cuentas de ahorro inversion
	//                y procede a insertarla en la tabla SCG_PC_Reporte
	//               
	////////////////////////////////////////////////////////////////////////////////////////////
	$ls_linea=""; $ls_cadena_linea=""; $ls_cuenta=""; $ls_denominacion=""; $ls_denominacion_plan=""; $ls_no_fila="";
	$ls_codreport=""; $ls_status=""; $ls_ref=""; $ls_tipo=""; $ls_cta_res=""; $ls_sql="";
	$ldc_asignado=0; $ldc_ene=0; $ldc_feb=0; $ldc_mar=0; $ldc_abr=0; $ldc_may=0; $ldc_jun=0;
	$ldc_jul=0; $ldc_ago=0; $ldc_sep=0; $ldc_oct=0; $ldc_nov=0; $ldc_dic=0;
	$li_NumFile=0; $li_Read_Result=0; $li_valid=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0 ;
	$lb_valido=true;
	   
            $ls_archivo = file("cuenta_ahorro_inversion.txt");
			$ls_linea =  count($ls_archivo);	       	       
	        for ($i=0; $i< $ls_linea; $i++)
	        {
	              // Reemplazar por el procesamiento
	              $ls_cadena_linea = $ls_archivo[$i];
	              $ls_codreport    = substr($ls_cadena_linea,0,5);	    //5
				  $ls_cuenta       = substr($ls_cadena_linea,5,25);    //25
				  $ls_denominacion = substr($ls_cadena_linea,30,100);  //100
				  $ls_status       = substr($ls_cadena_linea,130,1); //1
				  $ldc_asignado    = substr($ls_cadena_linea,131,1); //1  
				  $li_distribuir   = substr($ls_cadena_linea,132,1);   //1 
				  $ldc_ene         = substr($ls_cadena_linea,133,1); //1  
				  $ldc_feb         = substr($ls_cadena_linea,134,1); //1  
				  $ldc_mar         = substr($ls_cadena_linea,135,1); //1  
				  $ldc_abr         = substr($ls_cadena_linea,136,1); //1  
				  $ldc_may         = substr($ls_cadena_linea,137,1); //1  
				  $ldc_jun         = substr($ls_cadena_linea,138,1); //1  
				  $ldc_jul         = substr($ls_cadena_linea,139,1); //1  
				  $ldc_ago         = substr($ls_cadena_linea,140,1); //1  
				  $ldc_sep         = substr($ls_cadena_linea,141,1); //1  
				  $ldc_oct         = substr($ls_cadena_linea,142,1); //1  
				  $ldc_nov         = substr($ls_cadena_linea,143,1); //1  
				  $ldc_dic         = substr($ls_cadena_linea,144,1); //1  
				  $li_nivel        = substr($ls_cadena_linea,145,1); //1  
				  $ls_ref          = substr($ls_cadena_linea,146,25); //25
				  $li_no_fila++;  //3	
				  $ls_tipo         = ""; //1
				  $ls_cta_res      = ""; 	
				  $ls_codreport    = str_replace("-", " ", $ls_codreport);
				  $ls_cuenta       = str_replace("-", " ", $ls_cuenta);
				  $ls_denominacion = str_replace("-", " ", $ls_denominacion);
	              $ls_ref          = str_replace("-", " ", $ls_ref);
	              $ls_modrep	   = "0";	              			
				  //INSERT
				   $ls_sql= "INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep)".
				   " VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".$ls_status."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',".$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."')" ;
				                                                                                                                                                                                              
					$li_exec=$this->SQL->execute($ls_sql);
					if($li_exec<=0)
					{
						$is_msg_error = "Error en método uf_cargar_txt_ctaahorroinversion ";
						//$msg->message("Error en método uf_cargar_txt_ctaahorroinversion ");
						$lb_valido = false;
						$li_rtn=0;
					}
					else
					{
						$li_rtn=1;
					}
					
	}
	if ($li_rtn=1)
	{
	    $this->SQL->commit();
		$lb_valido = true;
		//$msg->message("Cuentas de Ahorro Inversion cargadas.. ");
		$is_msg_error = "Cuentas de Ahorro Inversion cargadas.."	;
	}
	
	else
	{
	   $this->SQL->rollback();
	   $lb_valido = false;
	   //$msg->message("Cuentas de Ahorro Inversion no cargadas.. ");
	   $is_msg_error = " Cuentas de Ahorro Inversion no cargadas.." ;
	} 

	return $lb_valido;
	
 } // fin de uf_cargar_txt_catahorroinversion
 	
 function uf_cargar_origen_y_aplic_fondos_txt($as_codemp)	
 {  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 	//	Function:  uf_cargar_origen_y_aplic_fondos_txt
	//	Access:  public
	//	Description:  Este método accesa la información del código y denominación de las 
	//      cuentas de origen y aplic de fondos y procede a insertarla en la tabla SCG_PlantillaCuentaReporte
	//               
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$ls_linea=""; $ls_cadena_linea=""; $ls_cuenta=""; $ls_denominacion=""; $ls_denominacion_plan=""; $ls_no_fila="";
	$ls_codreport=""; $ls_status=""; $ls_ref=""; $ls_tipo=""; $ls_cta_res=""; $ls_sql="";
	$ldc_asignado=0; $ldc_ene=0; $ldc_feb=0; $ldc_mar=0; $ldc_abr=0; $ldc_may=0; $ldc_jun=0;
	$ldc_jul=0; $ldc_ago=0; $ldc_sep=0; $ldc_oct=0; $ldc_nov=0; $ldc_dic=0;
	$li_NumFile=0; $li_Read_Result=0; $li_valid=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0 ;
	$lb_valido=true;
	
	        $ls_archivo = file("origen_y_aplic_fondos.txt");
			$ls_linea = count($ls_archivo);
	        for ($i=0; $i < $ls_linea; $i++)
	        {
	              // Reemplazar por el procesamiento
	              $ls_cadena_linea = $ls_archivo[$i];
	              $ls_codreport    = substr($ls_cadena_linea,0,5);	    //5
				  $ls_cuenta       = substr($ls_cadena_linea,5,25);    //25
				  $ls_denominacion = substr($ls_cadena_linea,30,100);  //100
				  $ls_status       = substr($ls_cadena_linea,130,1); //1
				  $ldc_asignado    = substr($ls_cadena_linea,131,1); //1  
				  $li_distribuir   = substr($ls_cadena_linea,132,1); //1 
				  $ldc_ene         = substr($ls_cadena_linea,133,1); //1  
				  $ldc_feb         = substr($ls_cadena_linea,134,1); //1  
				  $ldc_mar         = substr($ls_cadena_linea,135,1); //1  
				  $ldc_abr         = substr($ls_cadena_linea,136,1); //1  
				  $ldc_may         = substr($ls_cadena_linea,137,1); //1  
				  $ldc_jun         = substr($ls_cadena_linea,138,1); //1  
				  $ldc_jul         = substr($ls_cadena_linea,139,1); //1  
				  $ldc_ago         = substr($ls_cadena_linea,140,1); //1  
				  $ldc_sep         = substr($ls_cadena_linea,141,1); //1  
				  $ldc_oct         = substr($ls_cadena_linea,142,1); //1  
				  $ldc_nov         = substr($ls_cadena_linea,143,1); //1  
				  $ldc_dic         = substr($ls_cadena_linea,144,1); //1  
				  $li_nivel        = substr($ls_cadena_linea,145,1); //1  
				  $ls_ref          = substr($ls_cadena_linea,146,1); //25
				  $ls_no_fila      = substr($ls_cadena_linea,171,1); //3	
				  $ls_tipo         = substr($ls_cadena_linea,174,1); //1
				  $ls_cta_res      = substr($ls_cadena_linea,175,strlen($ls_cadena_linea)); 	
				  $ls_codreport    = str_replace("-", " ", $ls_codreport);
				  $ls_cuenta       = str_replace("-", " ", $ls_cuenta);
				  $ls_denominacion = str_replace("-", " ", $ls_denominacion);
	              $ls_no_fila      = str_replace("-", " ", $ls_no_fila);
	              $li_no_fila      = trim($ls_no_fila); 
	              $ls_ref          = str_replace("-", " ",$ls_ref); 
	              $ls_modrep	   = "0";			
				  //INSERT
				    $ls_sql= "INSERT INTO scg_pc_reporte (codemp,cod_report,sc_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,no_fila,tipo,cta_res,modrep)".
				    " VALUES('".trim($as_codemp)."','".trim($ls_codreport)."','".trim($ls_cuenta)."','".trim($ls_denominacion)."','".$ls_status."',".$ldc_asignado.",".$li_distribuir.",".$ldc_ene.",".$ldc_feb.",".$ldc_mar.",".$ldc_abr.",".$ldc_may.",".$ldc_jun.",".$ldc_jul.",".$ldc_ago.",".$ldc_sep.",".$ldc_oct.",".$ldc_nov.",".$ldc_dic.",".$li_nivel.",'".trim($ls_ref)."',".$li_no_fila.",'".$ls_tipo."','".$ls_cta_res."','".$ls_modrep."')" ;
				    $li_exec=$this->SQL->execute($ls_sql);                                                                                                                                                                                         
					
					if($li_exec<=0)
					{
						$is_msg_error = "Error en método uf_cargar_origen_y_aplic_fondos_txt ";
						$lb_valido = false;
						$li_rtn=0;
					}
					else
					{
						$li_rtn=1;
					}
		}
		
		$this->SQL->begin_transaction();
		
	   if($li_rtn=1)
	   {
		    $this->SQL->commit();
		    $lb_valido = true;
		    $is_msg_error = "Origen y Aplicación de Fondos cargado.."	;
		}
		else
		{
			$this->SQL->rollback();
			$lb_valido = false;
			$is_msg_error = "Origen y Aplicación de Fondos no fue cargado..";
		}
 return $lb_valido;
	
 } // fin de uf_cargar_origen_y_aplic_fondos_txt
	
  function  uf_count_scg_plantillacuentareporte( $as_codemp, $as_cod_report, $as_sc_cuenta )	
  {	////////////////////////////////////////////////////////////////////////////
    //Function:	uf_count_scg_plantillacuentareporte
    // Argumentos:  string as_codemp,
    //              string as_cod_report,
    //              string as_sc_cuenta
	// Descripción:	Devuelve si encontro scg_plantillacuentareporte
	//////////////////////////////////////////////////////////////////////////////
	$li_rtn=0; $li_count=0;
	$ls_sql="";
	
	$ls_sql = " SELECT COUNT(sc_cuenta) as total " .
	         "  FROM scg_pc_reporte " .
	         "  WHERE codemp	= '".trim($as_codemp)."' AND " .
	         "        cod_report = '".trim($as_cod_report)."' AND " .
	         "        sc_cuenta  = '".trim($as_sc_cuenta)."'" ;
	    //print $ls_sql;
	    $rs_oaf=$this->SQL->select($ls_sql);
		if ($row=$this->SQL->fetch_row($rs_oaf))
		{
			$li_rtn = $row["total"] ;  // Nº de registros
		}
		else
		{
			$li_rtn = 0; //no existen registros
			$is_msg_error= " Error en la función uf_count_scg_plantillacuentareporte. ";
		}
	
	return	$li_rtn;
		
  }	//fin de uf_count_scg_plantillacuentareporte
  
  function uf_insert_scg_plantillacuentareporte($la_datos)  ///ojo con esto como haer con los objeto
  { /////////////////////////////////////////////////////////////////
    //Funtion: uf_insert_scg_plantillacuentareporte
    //Argumentos: ao_datos, arreglo de objects
	//Descripción:	Inserta datos en scg_plantillacuentareporte
	///////////////////////////////////////////////////////////////////
	$li_rtn=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=-2;
	$ls_codemp=""; $ls_cod_report=""; $ls_sc_cuenta=""; $ls_denominacion=""; $ls_status="";
	$ldc_enero=0; $ldc_febrero=0; $ldc_marzo=0; $ldc_abril=0; $ldc_mayo=0; $ldc_junio=0; $ldc_asignado=0;
	$ldc_julio=0; $ldc_agosto=0; $ldc_septiembre=0; $ldc_octubre=0; $ldc_noviembre=0; $ldc_diciembre=0;
	$ls_tipo=""; $ls_cta_res=""; $ls_referencia=""; $ls_sql="";
	$ldc_saldoreal   =  "";
	$ldc_saldoapro   =  "";
	$ldc_saldomod    =  "";
	
	$la_empresa      =  $_SESSION["la_empresa"];
	$ls_codemp       =  $la_empresa["codemp"];
	$ls_cod_report   =  $la_datos[0];
	$ls_sc_cuenta    =  $la_datos[1];
	$ls_denominacion =  $la_datos[2];
	$ls_status       =  $la_datos[3]; 
	$ldc_asignado    =  $la_datos[4];
	$li_distribuir   =  $la_datos[5];
	
	$ldc_saldoreal   =  $la_datos[6];
	$ldc_saldoapro   =  $la_datos[7];
	$ldc_saldomod    =  $la_datos[8];
		
	$ldc_enero       =  $la_datos[9];
	$ldc_febrero     =  $la_datos[10];
	$ldc_marzo       =  $la_datos[11];
	$ldc_abril       =  $la_datos[12];
	$ldc_mayo        =  $la_datos[13];
	$ldc_junio       =  $la_datos[14];
	$ldc_julio       =  $la_datos[15];
	$ldc_agosto      =  $la_datos[16];
	$ldc_septiembre  =  $la_datos[17];
	$ldc_octubre     =  $la_datos[18];
	$ldc_noviembre   =  $la_datos[19];
	$ldc_diciembre   =  $la_datos[20];
	
	$li_nivel        =  $la_datos[21];
	$ls_referencia   =  $la_datos[22];
	$li_no_fila      =  $la_datos[23];    
	$ls_tipo         =  $la_datos[24]; 
	$ls_cta_res      =  $la_datos[25];
	$ls_modrep		 =  $la_datos[26];
	
	$ld_asignado=str_replace('.','',$ldc_asignado);
	$ld_asignado=str_replace(',','.',$ldc_asignado);	
	$ldc_saldoreal=str_replace('.','',$ldc_saldoreal);
	$ldc_saldoreal=str_replace(',','.',$ldc_saldoreal);		
	$ldc_saldoapro=str_replace('.','',$ldc_saldoapro);
	$ldc_saldoapro=str_replace(',','.',$ldc_saldoapro);
	$ldc_saldomod=str_replace('.','',$ldc_saldomod);
	$ldc_saldomod=str_replace(',','.',$ldc_saldomod);			
	$ld_enero=str_replace('.','',$ldc_enero);
	$ld_enero=str_replace(',','.',$ld_enero);
	$ld_febrero=str_replace('.','',$ldc_febrero);
	$ld_febrero=str_replace(',','.',$ld_febrero);
	$ld_marzo=str_replace('.','',$ldc_marzo);
	$ld_marzo=str_replace(',','.',$ld_marzo);
	$ld_abril=str_replace('.','',$ldc_abril);
	$ld_abril=str_replace(',','.',$ld_abril);
	$ld_mayo=str_replace('.','',$ldc_mayo);
	$ld_mayo=str_replace(',','.',$ld_mayo);
	$ld_junio=str_replace('.','',$ldc_junio);
	$ld_junio=str_replace(',','.',$ld_junio);
	$ld_julio=str_replace('.','',$ldc_julio);
	$ld_julio=str_replace(',','.',$ld_julio);
	$ld_agosto=str_replace('.','',$ldc_agosto);
	$ld_agosto=str_replace(',','.',$ld_agosto);
	$ld_septiembre=str_replace('.','',$ldc_septiembre);
	$ld_septiembre=str_replace(',','.',$ld_septiembre);
	$ld_octubre=str_replace('.','',$ldc_octubre);
	$ld_octubre=str_replace(',','.',$ld_octubre);
	$ld_noviembre=str_replace('.','',$ldc_noviembre);
	$ld_noviembre=str_replace(',','.',$ld_noviembre);
	$ld_diciembre=str_replace('.','',$ldc_diciembre);
	$ld_diciembre=str_replace(',','.',$ld_diciembre);
	
    $ls_sql = " INSERT INTO scg_pc_reporte ".  
	         " (codemp, " . 
	         "  cod_report,".    
	         "  sc_cuenta,".   
	         "  denominacion,".   
	         "  status,".   
	         "  asignado,".   
	         "  distribuir,".   
	         "  enero,".   
	         "  febrero,".   
	         "  marzo,".   
	         "  abril,".   
	         "  mayo,".   
	         "  junio,".   
	         "  julio,".   
	         "  agosto,".   
	         "  septiembre,".   
	         "  octubre,".   
	         "  noviembre,".   
	         "  diciembre,".   
	         "  nivel,".   
	         "  referencia,".   
	         "  no_fila,".   
	         "  tipo,".   
	         "  cta_res,".
			 "  modrep, saldo_real_ant, saldo_apro, saldo_mod )".  
	 " VALUES ('".$ls_codemp."',". 
	         " '".$ls_cod_report."',". 
	         " '".$ls_sc_cuenta."',". 
	         " '".$ls_denominacion."',". 
	         " '".$ls_status."',". 
	         " '".$ldc_asignado."',". 
	         " '".$li_distribuir."',".
	         " '".$ldc_enero."',". 
	         " '".$ldc_febrero."',". 
	         " '".$ldc_marzo."',".  
	         " '".$ldc_abril."',". 
	         " '".$ldc_mayo."',". 
	         " '".$ldc_junio."',". 
	         " '".$ldc_julio."',". 
	         " '".$ldc_agosto."',". 
	         " '".$ldc_septiembre."',". 
	         " '".$ldc_octubre."',". 
	         " '".$ldc_noviembre."',". 
	         " '".$ldc_diciembre."',". 
	         "  ".$li_nivel.",". 
	         " '".$ls_referencia."',". 
	         " ".$li_no_fila.",". 
	         " '".$ls_tipo."',". 
	         " '".$ls_cta_res."',".
			 " '".$ls_modrep."', '".$ldc_saldoreal."','".$ldc_saldoapro."','".$ldc_saldomod."' )";
	    
	   $li_exec=$this->SQL->execute($ls_sql);	
		if($li_exec<=0)
		{
			$is_msg_error = "Error en método uf_insert_scg_plantillacuentareporte ";
			$li_rtn=0;
		}
		else
		{
			$li_rtn=1;
		}
		
	return	$li_rtn;
  }		  

  function uf_update_scg_plantillacuentareporte($la_datos)		
  { ///////////////////////////////////////////////////////////////////
    //Funtion: uf_update_scg_plantillacuentareporte
    //Argumentos: ao_datos, arreglo de objects
	//Descripción:	Actuliza datos en scg_pc_reporte
	///////////////////////////////////////////////////////////////////
  
	$li_rtn=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; 
	$ls_codemp=""; $ls_cod_report=""; $ls_sc_cuenta=""; $ls_denominacion=""; $ls_status="";
	$ldc_enero=0; $ldc_febrero=0; $ldc_marzo=0; $ldc_abril=0; $ldc_mayo=0; $ldc_junio=0; $ldc_asignado=0;
	$ldc_julio=0; $ldc_agosto=0; $ldc_septiembre=0; $ldc_octubre=0; $ldc_noviembre=0; $ldc_diciembre=0;
	$ls_tipo=""; $ls_cta_res=""; $ls_referencia=""; $ls_sql="";
	$ldc_saldoreal   =  "";
	$ldc_saldoapro   =  "";
	$ldc_saldomod    =  "";
	
    $la_empresa      =  $_SESSION["la_empresa"];
    $ls_codemp       =  $la_empresa["codemp"];
	$ls_cod_report   =  $la_datos[0];
	$ls_sc_cuenta    =  $la_datos[1];
	$ls_denominacion =  $la_datos[2];
	$ls_status       =  $la_datos[3]; 
	$ldc_asignado    =  $la_datos[4];
	$li_distribuir   =  $la_datos[5];
		
	$ldc_saldoreal   =  $la_datos[6];
	$ldc_saldoapro   =  $la_datos[7];
	$ldc_saldomod    =  $la_datos[8];
		
	$ldc_enero       =  $la_datos[9];
	$ldc_febrero     =  $la_datos[10];
	$ldc_marzo       =  $la_datos[11];
	$ldc_abril       =  $la_datos[12];
	$ldc_mayo        =  $la_datos[13];
	$ldc_junio       =  $la_datos[14];
	$ldc_julio       =  $la_datos[15];
	$ldc_agosto      =  $la_datos[16];
	$ldc_septiembre  =  $la_datos[17];
	$ldc_octubre     =  $la_datos[18];
	$ldc_noviembre   =  $la_datos[19];
	$ldc_diciembre   =  $la_datos[20];
	
	$li_nivel        =  $la_datos[21];
	$ls_referencia   =  $la_datos[22];
	$li_no_fila      =  $la_datos[23];    
	$ls_tipo         =  $la_datos[24]; 
	$ls_cta_res      =  $la_datos[25];
	$ls_modrep		 =  $la_datos[26];	

	$ls_sql = " UPDATE scg_pc_reporte SET status  = '".$ls_status."',".   
							         " asignado	  = '".$ldc_asignado."',".   
							         " distribuir = '".$li_distribuir."',".   
							         " enero	  = '".$ldc_enero."',".
							         " febrero	  = '".$ldc_febrero."',".   
							         " marzo	  = '".$ldc_marzo."',".   
							         " abril	  = '".$ldc_abril."',".   
							         " mayo		  = '".$ldc_mayo."',".   
							         " junio	  = '".$ldc_junio."',".   
							         " julio	  = '".$ldc_julio."',".   
							         " agosto	  = '".$ldc_agosto."',".   
							         " septiembre = '".$ldc_septiembre."',".   
							         " octubre 	  = '".$ldc_octubre."',".   
							         " noviembre  = '".$ldc_noviembre."',".   
							         " diciembre  = '".$ldc_diciembre."',".   
							         " nivel	  = '".$li_nivel."',".   
							         " referencia = '".$ls_referencia."',".   
							         " no_fila	  =  ".$li_no_fila.",".   
							         " tipo		  = '".$ls_tipo."',".   
							         " cta_res	  = '".$ls_cta_res."',". 
									 " modrep     = '".$ls_modrep."', ".
									 " saldo_real_ant = '".$ldc_saldoreal."', ".
									 " saldo_apro     = '".$ldc_saldoapro."', ".
									 " saldo_mod      = '".$ldc_saldomod."'    ". 
  						          " WHERE codemp = '".$ls_codemp."' AND ".		
							      "       cod_report = '".$ls_cod_report."' AND ".
							      "       sc_cuenta = '".$ls_sc_cuenta."' ";
									//print $ls_sql;
		$li_numrows=$this->SQL->execute($ls_sql);	
	    if(($li_numrows==false)&&($this->SQL->message!=""))
	    {
			$lb_valido=false;
			$this->is_msg_error="Error en metodo update".$this->SQL->message;
			print $this->is_msg_error;
	    }
	    else
	    {
			$lb_valido=true;
	    }
		/*if($li_exec<=0)
		{
			$is_msg_error = "Error en método uf_update_scg_plantillacuentareporte ";
			$li_rtn=0;
		}
		else
		{
			$li_rtn=1;
		}*/
		
	return	$lb_valido;	
	
  }	//fin de uf_update_scg_plantillacuentareporte
  
	
  function uf_sql_transaction($ab_valido) 
  {
  	 $lb_valido=false;
  	 
	$this->SQL->begin_transaction(); 
  	if ($ab_valido)
  	{
		$this->SQL->commit();
		$lb_valido=true;
	}
	else
	{
		$this->SQL->rollback();
		$lb_valido=false;
	}
  	
  	return $lb_valido;
  }

	function uf_eliminar_cmp( $as_codemp, $as_comprobante, $as_procede, $as_fecha)
	{
		$ls_sql="";
		$li_exec=0;
		$lb_valido=false;
		
		$ls_sql="DELETE FROM tepuy_cmp WHERE codemp='".$as_codemp."' AND comprobante='".$as_comprobante."' AND procede='".$as_procede."' AND fecha=".$as_fecha." AND procede ='SCGCMP' " ;
		
		$li_exec=$this->SQL->execute($ls_sql);
	
		if($li_exec>0)
		{
			$this->SQL->commit();
			$lb_valido=true;
		}
		else
		{
		    $this->SQL->rollback();
			$lb_valido=false;
			$is_msg_error="Error en eliminar comprobante";
		}
     return $lb_valido;
	}


    function uf_consultar_asig_previa( $as_sc_cuenta )
    {
    	$ls_sql="";
    	$ldc_asig_previo ;
    	
    	$ls_sql = " SELECT asignado FROM scg_pc_reporte WHERE sc_cuenta = '".$as_sc_cuenta."'";
    	$rs_oaf=$this->SQL->select($ls_sql);
		if ($row=$this->SQL->fecth_row($rs_oaf))
		{
			$ldc_asig_previo = $row["asignado"] ;  
		}
		else
		{
			$ldc_asig_previo = 0; //no existen registros
			$is_msg_error= " Error en la función uf_consultar_asig_previa. ";
		}
		
      return $ldc_asig_previo;	
    	
    } //fin de uf_consultar_asig_previa
	
    function uf_select_cta_referencia($as_codemp, $as_sc_cuenta )	
	{   // Function:uf_select_cta_refencia
	   // Argumentos: string $as_codemp,
	   //             string $as_sc_cuenta, 
	   //             
	   // Return      $ls_cta_ref: string
	   // Descripcion: selecciona la cuenta de referecia de sc_cuenta
	   //////////////////////////////////////////////////////////////////////  
	   $ls_sql=""; $ls_cta_ref="";
	   
	   $ls_sql = " SELECT  referencia,".
		         " FROM    scg_pc_reporte".
			     " WHERE   codemp = '".$as_codemp."' AND TRIM(sc_cuenta) = '".$as_sc_cuenta."'" ;
		
	  $rs_oaf=$this->SQL->select($ls_sql);
	  if ($row=$this->SQL->fecth_row($rs_oaf))
	  {
		$ls_cta_ref = $row["referencia"] ;
	  }
	  else
	  {
		$ls_cta_ref = ""; //no existen registros
		$is_msg_error= " Error en la función uf_select_scg_plantillacuentareporte. ";
	  }
		
	 return	$ls_cta_ref;
		
	 } // fin de uf_select_cta_referencia
    
    
     function uf_buscar_cuenta($as_codemp, $as_sc_cuenta )	
	 { // Function:uf_buscar_cuenta
	   // Argumentos: string as_codemp,
	   //             string as_sc_cuenta, 
	   //             
	   // Return      li_count
	   // Descripcion: 
	   //////////////////////////////////////////////////////////////////////  
	   $ls_sql="";
	   $li_rtn=0;
	      
	   $ls_sql = " SELECT COUNT(sc_cuenta) as total,".
		         " FROM scg_pc_reporte".
			     " WHERE codemp = '".$as_codemp."' AND TRIM(sc_cuenta) = '".$as_sc_cuenta."'" ;
	   $rs_oaf=$this->SQL->execute($ls_sql);
	   if ($row=$this->SQL->fecth_row($rs_oaf))
	   {
		   $li_rtn = $row["total"] ;  // Nº de registros
	   }
	   else
	   {
		  $li_rtn = 0; //no existen registros
		  $is_msg_error= " Error en la función uf_buscar_cuenta." ;
	   }
	return	$li_rtn;
		
    } // fin de uf_buscar_cuenta
   
   function uf_obt_nivel_cta( $as_cuenta )
  	 {  ////////////////////////////////////////////////////////////////////////////////////////////
  	 	// Function:    uf_obt_nivel_cta
  	    // Acesso:      Public
  	    // Argumentos:  as_sc_cuenta: String
  	    // Descripción  Busca en la tabla scg_pc_report el nivel de la cuenta que pasa por parametro 		  	  
  	 	/////////////////////////////////////////////////////////////////////////////////////////////
  	 	
  	 	$ls_sql="";
  	 	$ls_sql = "SELECT nivel FROM scg_pc_reporte WHERE sc_cuenta = '".$as_cuenta."'";	
  	 	$rs_oaf = $this->SQL->select($ls_sql);
		if ($row=$this->SQL->fetch_row($rs_oaf))
		{
			$li_nivel = $row["nivel"];
		}
		else
		{
			$li_nivel = 0; //no existen registros
			$is_msg_error= " Error en la función uf_select_scg_plantillacuentareporte. ";
		}
	return $li_nivel;
  	 	
   }
/***************************************************************************************************************************************/	 
   function uf_cuenta_sin_ceros( $as_cuenta )
   {    //////////////////////////////////////////////////////////////////////// 
        // Function:   uf_cuenta_sin_ceros
	    // Acceso:     public
	    // Argumentos: as_cuenta
	    // Descripción: Elimina los ceros a la derecha de la cuenta contable   
	 	////////////////////////////////////////////////////////////////////////
	 	$li_lenCta=0; $li_cero=1;
	 	$ls_cta_ceros=""; $ls_cad="";
	 	$lb_encontrado=true;
	 	global $msg;
	 	$li_lenCta = strlen(trim($as_cuenta));
		
		$ls_cad = substr(trim($as_cuenta), strlen(trim($as_cuenta))-1, 1 );
		$li_cero = $ls_cad;
	 	
	 	if ($li_cero == 0)
	 	{
			$ls_cta_ceros = substr(trim($as_cuenta), 0 , 11);
	  	}
	 	
	 	do  
		{
			$ls_cad = substr(trim($ls_cta_ceros), strlen($ls_cta_ceros)-1, 1);
	 		$li_cero = intval($ls_cad);
			$li_cant=strlen($ls_cta_ceros)-1;
	 		if ($li_cero == 0 )
	 		{
				$ls_cta_ceros = substr(trim($ls_cta_ceros), 0 , $li_cant);
	 			$lb_encontrado=true;
	 	 	}
	 	 	else
	 	 	{
	 	 		$lb_encontrado = false;
	 	 	}
	 		
	 	}while ( $lb_encontrado == true ); 
	 	
	  	return $ls_cta_ceros;
	 }
/***************************************************************************************************************************************/	 
	 function uf_disable_cta_inferior( $as_cta_ceros, $as_sc_cuenta,$as_cod_report )
	 {
	 	$lb_valido=true;
	 	$li_row = 0; $li_contador=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0;
	  	$ls_codemp=""; $ls_cod_report=""; $ls_sc_cuenta=""; $ls_denominacion=""; $ls_status="";
		$ldc_enero=0; $ldc_febrero=0; $ldc_marzo=0; $ldc_abril=0; $ldc_mayo=0; $ldc_junio=0; $ldc_asignado=0;
		$ldc_julio=0; $ldc_agosto=0; $ldc_septiembre=0; $ldc_octubre=0; $ldc_noviembre=0; $ldc_diciembre=0;
		$ls_tipo=""; $ls_cta_res=""; $ls_referencia=""; $ls_sql="";
		global $msg;
		$data=array();
	    $la_empresa = $_SESSION["la_empresa"];	
		$ls_codemp = $la_empresa["codemp"]; 
	 		
	 	$ls_sql = " SELECT * FROM scg_pc_reporte WHERE sc_cuenta like '".$as_cta_ceros."%' and sc_cuenta <> '".$as_sc_cuenta."' and cod_report='".$as_cod_report."' order by sc_cuenta " ;
		$rs_oaf=$this->SQL->select($ls_sql);
		$li_row=$this->SQL->num_rows($rs_oaf);
		if ($row=$this->SQL->fetch_row($rs_oaf))
		{
			while ($row=$this->SQL->fetch_row($rs_oaf))
			{	
				$ldc_asignado = $row["asignado"];
				$ls_sc_cuenta = $row["sc_cuenta"];
										
				if (!($ldc_asignado == 0))
				{
					$li_rtn = 1 ;
					$msg->message("La cuenta ".$ls_sc_cuenta." tiene asignación. ");
					break;
				}
				else
				{
					$li_contador = $li_contador + 1;
				} 	
			} //cierre del while
			
			if ($li_contador + 1 == $li_row )
			{   
				$ls_sql = " SELECT * FROM scg_pc_reporte WHERE sc_cuenta like '".$as_cta_ceros."%' and sc_cuenta <> '".$as_sc_cuenta."' and cod_report='".$as_cod_report."' order by sc_cuenta " ;
                $rs_oaf=$this->SQL->select($ls_sql);
				if($rs_oaf===false)
				{
				  $data=0;
				  $this->is_msg_error="Error en metodo uf_disable_cta_inferior".$this->SQL->message;
				  print $this->is_msg_error;
				}
				else
				{
					$i=1;
					$data=array();
					while($row=$this->SQL->fetch_row($rs_oaf))
					{
						$ls_sc_cuenta  =  $row["sc_cuenta"];
						$data[$i]=$ls_sc_cuenta;
						$i=$i+1;
					}// cierre del while rs_oaf.next (update)
				}
			}// cierre del if (li_contador == li_row)
  }//cierre del if
return $data;
} // fin de uf_disable_cta_inferior

	

function uf_select_Cuentas($as_codemp)
{
	$ls_sql="";
	$rs_CatCta=null;
	 	
	$ls_sql="SELECT sc_cuenta, denominacion, status, asignado, distribuir,".
				   " enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre,". 
				   " nivel, referencia ".
			       " FROM scg_cuentas " . 
				   " WHERE codemp = '".$as_codemp."' ". 
				   " ORDER BY sc_cuenta";
		
    $rs_CatCta=$this->SQL->select($ls_sql);
	
	if ($rs_CatCta=false)
	{
	   $is_msg_error = "Error en el SELECT de la función uf_select_Cuentas";
	   $rs_CatCta = false;
	}
		 
	return $rs_CatCta;
}//uf_select_Cuentas
	
function delete_scg_pc_reporte($as_cod_report)
{      
	   
		$ls_sql="";
		$lb_valido=true;
		
		$ls_sql = "DELETE FROM scg_pc_reporte WHERE cod_report='".$as_cod_report."' ";
		$numrows=$this->SQL->execute($ls_sql);
		$this->SQL->begin_transaction();
		if($numrows>0)
	    {
		    $lb_valido=true;
			$this->SQL->commit();
        }
	    else
	    {
		   $this->is_msg_error="Error al eliminar";
		   $lb_valido = false;
		   $this->SQL->rollback();
		   $this->ib_db_error = true ;
	    }

 return $lb_valido;

}

function  uf_select_reporte($as_codemp,&$ai_cuantos,$as_cod_report)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:     uf_select_reporte 
	//	Arguments:    $as_codemp // codigo de la empresa
	//                $ai_cuantos  // cuantos 
	//                $as_cod_report  // codigo del reporte             
	//	Returns:	  $lb_valido true si es correcto los delete o false en caso contrario
	//	Description:  Función que elimina el periodos de todas las tablas historicas para proceder al cierre del mismo
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $ls_sql=" SELECT count(cod_report) as cuantos ".
            " FROM   scg_pc_reporte ".
            " WHERE  codemp='".$as_codemp."' AND cod_report='".$as_cod_report."' ";
   	$rs_cod=$this->SQL->select($ls_sql);
	if($rs_cod===false)
	{
		  $lb_valido=false;
		  $this->is_msg_error="Error en metodo uf_disable_cta_inferior".$this->SQL->message;
		  print $this->is_msg_error;
	}
	else
	{
		  $lb_valido=true;
		  if($row=$this->SQL->fetch_row($rs_cod))
		  {
		    $ai_cuantos=$row["cuantos"];
		  }
	}
 return $lb_valido;
}

function uf_consultar_status_cuenta( $as_sc_cuenta )
{
	$ls_sql="";
	$ls_status="" ;
	
	$ls_sql = " SELECT status FROM scg_pc_reporte WHERE sc_cuenta = '".$as_sc_cuenta."'";
	$rs_oaf=$this->SQL->select($ls_sql);
	if ($row=$this->SQL->fetch_row($rs_oaf))
	{
		$ls_status = $row["status"] ;  
	}
	/*else
	{
		$ldc_asig_previo = 0; //no existen registros
		$is_msg_error= " Error en la función uf_consultar_asig_previa. ";
	}*/
	
  return $ls_status;	
	
} //fin de uf_consultar_asig_previa
	
	 
} //fin de la class SCG_procesos

?>


