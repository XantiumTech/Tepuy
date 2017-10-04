<?Php
/***************************************************************************************/
/*	Clase:	        Valuacion                                                         */    
/*  Fecha:          25/03/2006                                                         */        
/*	Autor:          Miguel Palencia	                                              */     
/***************************************************************************************/
class tepuy_sob_c_valuacion
{
 var $io_funcsob;
 var $io_function;
 var $la_empresa;
 var $io_sql;
 var $is_msg;

function tepuy_sob_c_valuacion()
{

	require_once("../shared/class_folder/tepuy_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("class_folder/tepuy_sob_c_funciones_sob.php");
	require_once("../shared/class_folder/tepuy_c_seguridad.php");
	require_once("../shared/class_folder/class_datastore.php");
	$this->io_ds_spgcuentas=new class_datastore(); // Datastored de cuentas presupuestarias
	$this->io_ds_scgcuentas=new class_datastore(); // Datastored de cuentas contables
	$this->seguridad=   new tepuy_c_seguridad();
	$this->io_funcsob=new tepuy_sob_c_funciones_sob();
	$io_include=new tepuy_include();
	$io_connect=$io_include->uf_conectar();
	$this->io_sql=new class_sql($io_connect);	
	$this->io_function=new class_funciones();
	$this->io_msg=new class_mensajes();
	$this->la_empresa=$_SESSION["la_empresa"];
	$this->ls_codemp=$this->la_empresa["codemp"];

}
    function uf_select_valuacion($as_codval,$as_codcon)
	{
		/***************************************************************************************/
		/*	Function:	    uf_select_asignacion                                               */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si existe el registro en bd                  */ 
		/*	Description:	Funcion que se encarga de verificar si existe o no la asignacion   */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT codval
				 FROM sob_valuacion
			     WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."' AND codcon='".$as_codcon."'";
		//print $ls_sql;
		//die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	}
//////////////////////// BUSCA SI LA VALUACION ESTA PREPARADA PARA ELIMINAR ///////////////////////
function uf_select_valuacion_eliminar($as_codval,$as_codcon,$buscar)
	{
		/***************************************************************************************/
		/*	Function:	    uf_select_asignacion                                               */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si existe el registro en bd                  */ 
		/*	Description:	Funcion que se encarga de verificar si existe o no la asignacion   */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		
		$lb_valido=false;
		
		//$ls_sql="SELECT codval FROM sob_valuacion WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."' AND codcon='".$as_codcon."'";
		$rs_data=$this->io_sql->select($buscar);
		if($rs_data===false)
		{
			print "Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////
/////////// ELIMINA VALUACION /////////////////////
	function uf_eliminar_valuacion($as_codval,$as_codcon,$ad_fecha,$ad_fecinival,$ad_fecfinval,$as_obsval,$ai_amoval,$as_obsamoval,$ai_amoantval,$ai_amototval,$ai_amoresval,$ai_basimpval,$ai_montotval,$ai_subtotpar,$ai_totreten,$ai_subtot,$aa_seguridad,$as_numrecdoc)
	{
		/***************************************************************************************/
		/*	Function:	    uf_guardar_asignacion                                               */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si existe el registro en bd                  */ 
		/*	Description:	Funcion que se encarga de verificar si existe o no la asignacion   */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_act=true;
		$lb_execute=true;
		$lb_valido=false;
		$buscar="SELECT codval FROM sob_valuacion WHERE codemp='".$this->la_empresa["codemp"]."' AND codval='".$as_codval."' AND codcon='".$as_codcon."' AND (estval=1 OR estval=6) AND estgenrd=0";
		//print $buscar;
		//die();
		$lb_flag=$this->uf_select_valuacion_eliminar($as_codval,$as_codcon,$buscar);
		$ld_amoval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amoval);
		$ld_amoantval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amoantval);
		$ld_amototval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amototval);
		$ld_amoresval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amoresval);
		$ld_basimpval=$this->io_funcsob->uf_convertir_cadenanumero($ai_basimpval);
		$ld_montotval=$this->io_funcsob->uf_convertir_cadenanumero($ai_montotval);
		$ld_subtotpar=$this->io_funcsob->uf_convertir_cadenanumero($ai_subtotpar);
		$ld_totreten=$this->io_funcsob->uf_convertir_cadenanumero($ai_totreten);
		$ld_subtot=$this->io_funcsob->uf_convertir_cadenanumero($ai_subtot);
		$ls_codemp=$this->la_empresa["codemp"];
		//print "numrecdoc. ".$as_numrecdoc;
		if($lb_flag)
		{	
	     $ls_sql="DELETE FROM sob_valuacion WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."' AND codcon='".$as_codcon."' AND (estval=1 OR estval=6) AND estgenrd=0";

		 $this->io_msg->message("Valuacion Eliminada");	 		
		}
		else
		{
		     $this->io_msg->message("Esta Valuacion no puede ser eliminada");
		     $lb_execute=false; 				 
		}	
		if($lb_execute)
		{
		 
		 $this->io_sql->begin_transaction();	
		 $li_row=$this->io_sql->execute($ls_sql);
		//print "lirow".$li_row;
		 if($li_row===false)
		  {			
			print "Error en metodo uf_eliminar_valuacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
			print($ls_sql);
		  }
		  else
		  {
			$ls_sql="DELETE FROM sob_valuacionpartida WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."' AND codcon='".$as_codcon."'";
			$this->io_sql->begin_transaction();	
		 	$li_row=$this->io_sql->execute($ls_sql);
			if($li_row)
		  	{
				$ls_sql="DELETE FROM sob_cargovaluacion WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."' AND codcon='".$as_codcon."'";
				$this->io_sql->begin_transaction();	
		 		$li_row=$this->io_sql->execute($ls_sql);
				if($li_row)
		  		{
				
					$ls_sql="DELETE FROM sob_retencionvaluacioncontrato WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."' AND codcon='".$as_codcon."'";
					$this->io_sql->begin_transaction();	
		 			$li_row=$this->io_sql->execute($ls_sql);
					if($li_row)
		  			{	
						//print "aqui elimino";
						/************    SEGURIDAD    **************/		
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminó la Valuación ".$as_codval.", de monto ".$ai_montotval." Asociada a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);  
						/**************************************************/
						$this->io_sql->commit();
						$lb_valido=true;
					}
				}
			}
		  }  
		}
	
	    return $lb_valido;
	}
//////////////////////////////////////////////////
	
	function uf_guardar_valuacion($as_codval,$as_codcon,$ad_fecha,$ad_fecinival,$ad_fecfinval,$as_obsval,$ai_amoval,$as_obsamoval,$ai_amoantval,$ai_amototval,$ai_amoresval,$ai_basimpval,$ai_montotval,$ai_subtotpar,$ai_totreten,$ai_subtot,$aa_seguridad,$as_numrecdoc,$as_numref,$as_numfactura)
	{
		/***************************************************************************************/
		/*	Function:	    uf_guardar_asignacion                                               */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si existe el registro en bd                  */ 
		/*	Description:	Funcion que se encarga de verificar si existe o no la asignacion   */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_act=true;
		$lb_execute=true;
		$lb_valido=false;
		$lb_flag=$this->uf_select_valuacion($as_codval,$as_codcon);
		$ld_amoval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amoval);
		$ld_amoantval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amoantval);
		$ld_amototval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amototval);
		$ld_amoresval=$this->io_funcsob->uf_convertir_cadenanumero($ai_amoresval);
		$ld_basimpval=$this->io_funcsob->uf_convertir_cadenanumero($ai_basimpval);
		$ld_montotval=$this->io_funcsob->uf_convertir_cadenanumero($ai_montotval);
		$ld_subtotpar=$this->io_funcsob->uf_convertir_cadenanumero($ai_subtotpar);
		$ld_totreten=$this->io_funcsob->uf_convertir_cadenanumero($ai_totreten);
		$ld_subtot=$this->io_funcsob->uf_convertir_cadenanumero($ai_subtot);
		$ls_codemp=$this->la_empresa["codemp"];
		//print "numrecdoc. ".$as_numrecdoc;

		if(!$lb_flag)
		{	
		
	     $ls_sql="INSERT INTO sob_valuacion(codemp,codval,codcon,fecha,fecinival,fecfinval,obsval,amoval,obsamoval,amoantval,amototval,
amoresval,estval,basimpval,montotval,subtotpar,totreten,subtot,numrecdoc,numref,numfac)
		         VALUES ('".$ls_codemp."','".$as_codval."','".$as_codcon."','".$ad_fecha."','".$ad_fecinival."','".$ad_fecfinval."','".$as_obsval."',".$ld_amoval.",'".$as_obsamoval."',".$ld_amoantval.",".$ld_amototval.",".$ld_amoresval.",1,".$ld_basimpval.",".$ld_montotval.",".$ld_subtotpar.",".$ld_totreten.",".$ld_subtot.",'".$as_numrecdoc."', '".$as_numref."', '".$as_numfactura."')";

		//print $ls_sql;
		//die();
		 $this->io_msg->message("Registro Incluido");
		}
		else
		{
		  $lb_val=$this->uf_select_estado($as_codval,&$ls_estasi);
		  
		  if(($ls_estasi==1)||($ls_estasi==6))
		   {
		    $lb_act=false; 
		 /*   $ls_sql=" UPDATE sob_valuacion 
				    SET numrecdoc='".$as_numrecdoc."',codcon='".$as_codcon."',fecha='".$ad_fecha."',fecinival='".$ad_fecinival."',fecfinval='".$ad_fecfinval."',obsval='".$as_obsval."',amoval='".$ld_amoval."',obsamoval='".$as_obsamoval."',amoantval=".$ld_amoantval.",amototval=".$ld_amototval.",amoresval=".$ld_amoresval.",basimpval=".$ld_basimpval.",montotval=".$ld_montotval.",subtotpar=".$ld_subtotpar.",totreten=".$ld_totreten.",subtot=".$ld_subtot.",estval=1 WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."'";	*/

			 $ls_sql=" UPDATE sob_valuacion 
				    SET numrecdoc='".$as_numrecdoc."',fecha='".$ad_fecha."',fecinival='".$ad_fecinival."',fecfinval='".$ad_fecfinval."',obsval='".$as_obsval."',amoval='".$ld_amoval."',obsamoval='".$as_obsamoval."',amoantval=".$ld_amoantval.",amototval=".$ld_amototval.",amoresval=".$ld_amoresval.",basimpval=".$ld_basimpval.",montotval=".$ld_montotval.",subtotpar=".$ld_subtotpar.",totreten=".$ld_totreten.",subtot=".$ld_subtot.",estval=1,numref='".$as_numref."', numfac='".$as_numfactura."' WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."'"." AND codcon='".$as_codcon."'";
				   // print $ls_sql;
				   // die();
		    $this->io_msg->message("Registro Actualizado");		    
		   }
		   else
		   {
		     $this->io_msg->message("Esta Valuacion no puede ser modificada");
		     $lb_execute=false;
		   } 				 
		}	
		if($lb_execute)
		{
		 
		 $this->io_sql->begin_transaction();	
		//print $ls_sql;

		 $li_row=$this->io_sql->execute($ls_sql);
		 if($li_row===false)
		  {			
			print "Error en metodo uf_guardar_valuacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
			//print($ls_sql);
		  }
		  else
		  {
			if($li_row>0)
			{
				/************    SEGURIDAD    **************/		
				if($lb_act)
				{
				  $ls_evento="INSERT";
				  $ls_descripcion ="Insertó la Valuación ".$as_codval.", de monto ".$ai_montotval." Asociada a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);  
				}
				else
				{
				  $ls_evento="UPDATE";
				  $ls_descripcion ="Actualizó la Valuación ".$as_codval.", de monto ".$ai_montotval." Asociada a la Empresa ".$ls_codemp;
				  $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				}
				/**************************************************/
				$this->io_sql->commit();
				$lb_valido=true;				
			}
			else
			{
				
				$this->io_sql->rollback();
			}
		
		  }  
		}
	
	    return $lb_valido;
	}
    function uf_select_partidasasignadas($as_codcon,&$aa_data,&$ai_rows)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT apo.codpar,p.nompar,u.nomuni,apo.preparasi,apo.prerefparasi,(apo.canparobrasi-apo.canasipareje)+apo.canvarpar AS canxeje,apo.canasipareje,c.codasi,a.codobr
FROM sob_contrato c, sob_asignacion a, sob_asignacionpartidaobra apo, sob_partida p, sob_unidad u
WHERE c.codemp='".$ls_codemp."' AND a.codemp='".$ls_codemp."' AND apo.codemp='".$ls_codemp."' AND p.codemp='".$ls_codemp."' AND u.codemp='".$ls_codemp."' AND c.codcon='".$as_codcon."' AND c.codasi=a.codasi AND a.codasi=apo.codasi AND p.codpar=apo.codpar AND u.coduni=p.coduni";

		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}	
   	function uf_select_allpartidas($as_codval,$as_codasi,&$aa_data,&$ai_rows)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT apo.codemp,apo.codasi,apo.codobr,apo.codpar,p.nompar,u.nomuni,(apo.canparobrasi-apo.canasipareje)+apo.canvarpar AS canxeje,apo.canasipareje,apo.prerefparasi,apo.preparasi,vp.canvalpar
		         FROM sob_asignacionpartidaobra apo LEFT JOIN sob_valuacionpartida vp ON ((apo.codpar=vp.codpar) AND (apo.codemp=vp.codemp) AND (apo.codasi=vp.codasi) AND vp.codval='".$as_codval."'),sob_partida p,sob_unidad u 
				 WHERE apo.codasi='".$as_codasi."' AND apo.codemp='".$ls_codemp."' AND p.codemp='".$ls_codemp."' AND u.codemp='".$ls_codemp."' AND apo.codpar=p.codpar AND p.coduni=u.coduni ORDER BY apo.codpar ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
  
   function uf_select_partidas($as_codval,$as_codcon,&$aa_data,&$ai_rows)
    {
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT  vp.codemp,vp.codval,vp.codcon,vp.codpar,p.nompar,u.nomuni,vp.canvalpar,(apo.canparobrasi-apo.canasipareje)+apo.canvarpar AS canxeje,apo.canasipareje
				 FROM sob_valuacionpartida vp, sob_partida p, sob_unidad u,sob_asignacionpartidaobra apo
				 WHERE apo.codemp='".$ls_codemp."' AND u.codemp='".$ls_codemp."' AND p.codemp='".$ls_codemp."' AND vp.codemp='".$ls_codemp."' AND vp.codval='".$as_codval."' AND vp.codcon='".$as_codcon."' AND vp.codpar=apo.codpar AND vp.codpar=p.codpar AND p.coduni=u.coduni";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	function uf_guardar_dtpartidas($as_codval,$as_codcon,$as_codobr,$as_codpar,$as_codasi,$ad_cantidad,$ad_prerefparasi,$ad_preparval,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ld_cant=$this->io_funcsob->uf_convertir_cadenanumero($ad_cantidad);
		$ld_prerefparasi=$this->io_funcsob->uf_convertir_cadenanumero($ad_prerefparasi);
		$ld_preparval=$this->io_funcsob->uf_convertir_cadenanumero($ad_preparval);
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="INSERT INTO sob_valuacionpartida (codemp,codobr,codpar,codasi,codval,codcon,canvalpar,prerefparasi,preparval)
		         VALUES ('".$ls_codemp."','".$as_codobr."','".$as_codpar."','".$as_codasi."','".$as_codval."','".$as_codcon."',".$ld_cant.",".$ld_prerefparasi.",".$ld_preparval.")";		
		
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_guardar_dt".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
		}
		else
		{
			if($li_row>0)
			{
			     /************    SEGURIDAD    **************/		 
				  $ls_evento="INSERT";
				  $ls_descripcion ="Insertó la Partida ".$as_codpar.", Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/	
				$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{
				
				$this->io_sql->rollback();
			}
		
		}		
		return $lb_valido;
	}	
	function uf_delete_dtpartidas($as_codval,$as_codcon,$as_codpar,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="DELETE FROM sob_valuacionpartida
					WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."' AND codcon='".$as_codcon."' AND codpar='".$as_codpar."'";		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			print"Error en metodo eliminar_dtpartidas".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			/*************    SEGURIDAD    **************/		 
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Partida ".$as_codpar.",Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;	
	
	}
function uf_update_partidavaluacion($as_codval,$as_codcon,$ad_canpar,$aa_seguridad)
	{
	
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ld_cant=$this->io_funcsob->uf_convertir_cadenanumero($ad_canpar);
		$ls_sql="UPDATE sob_valuacionpartida
				 SET canvalpar='".ld_cant."'
				 WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."' AND codcon='".$as_codcon."' AND codpar='".$as_codpar."'";		
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_partidavaluacion ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
		}
		else
		{
			if($li_row>0)
			{
				/*************    SEGURIDAD    **************/		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la cantidad ejecutada de la partida ".$as_codpar.", Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{
				
				$this->io_sql->rollback();	
			}
		
		}		
		return $lb_valido;
	  }	
    function uf_update_cantidaejecutada($as_codasi,$as_codpar,$ad_canpar,$ad_caneje,$ad_oldcan,$aa_seguridad)
	 {
	
		$lb_valido=false;
		$ld_teje=0;
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_canp=$this->io_funcsob->uf_convertir_cadenanumero($ad_canpar);
		if($ad_oldcan!=0)
		{
		 $ld_tejeA=$ad_caneje-$ad_oldcan; 
		 $ld_teje=$ld_tejeA+$ad_canp;
		}
		else
		{
	     $ld_teje=$ad_caneje+$ad_canp; 
		}
		
				
		$ls_sql="UPDATE sob_asignacionpartidaobra
				SET canasipareje=".$ld_teje."
				WHERE codemp='".$ls_codemp."' AND codasi='".$as_codasi."' AND codpar='".$as_codpar."'";		
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_cantidadejecutada ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
		}
		else
		{
			if($li_row>0)
			{
			    /*************    SEGURIDAD    **************/		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la cantidad ejecutada de la partida ".$as_codpar.", Detalle de la Asignacion ".$as_codasi." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{
				
				$this->io_sql->rollback();	
			}
		
		}		
		return $lb_valido;
	  }
	 function uf_update_Actcantidaejecutada($as_codasi,$as_codpar,$ad_canpar,$ad_caneje,$aa_seguridad)
	 {
	
		$lb_valido=false;
		$ld_teje=0;
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_canp=$this->io_funcsob->uf_convertir_cadenanumero($ad_canpar);
	    $ld_teje=$ad_caneje-$ad_canp; 
	
		$ls_sql="UPDATE sob_asignacionpartidaobra
				SET canasipareje=".$ld_teje."
				WHERE codemp='".$ls_codemp."' AND codasi='".$as_codasi."' AND codpar='".$as_codpar."'";		
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_Actcantidadejecutada ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
		}
		else
		{
			if($li_row>0)
			{
				  /*************    SEGURIDAD    **************/		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la cantidad ejecutada de la partida ".$as_codpar.", Detalle de la Asignacion ".$as_codasi." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{
				
				$this->io_sql->rollback();	
			}
		
		}		
		return $lb_valido;
	  }
	function uf_update_dtpartidas($as_codval,$as_codcon,$aa_partidasnuevas,$ai_totalfilas,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$ls_codemp=$this->la_empresa["codemp"];
		$lb_valido=$this->uf_select_partidas($as_codval,$as_codcon,$la_partidasviejas,$li_totalviejas);
		$li_totalnuevas=$ai_totalfilas;
		
		for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			$lb_update=false;
			for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
			{
				if( ($la_partidasviejas["codemp"][$li_j] == $ls_codemp) && ($la_partidasviejas["codval"][$li_j] == $as_codval) && ($la_partidasviejas["codcon"][$li_j] == $as_codcon) &&  ($la_partidasviejas["codpar"][$li_j] == $aa_partidasnuevas["codpar"][$li_i]) )
				{
				  if($la_partidasviejas["canvalpar"][$li_j] != $this->io_funcsob->uf_convertir_cadenanumero($aa_partidasnuevas["cant"][$li_i]))
					{
					  $lb_update=true;
					}
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$this->uf_guardar_dtpartidas($as_codval,$as_codcon,$aa_partidasnuevas["codobr"][$li_i],$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["codasi"][$li_i],$aa_partidasnuevas["cant"][$li_i],$aa_partidasnuevas["preref"][$li_i],$aa_partidasnuevas["preval"][$li_i],$aa_seguridad);
				 $this->uf_update_cantidaejecutada($aa_partidasnuevas["codasi"][$li_i],$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["cant"][$li_i],$aa_partidasnuevas["canteje"][$li_i],0.000,$aa_seguridad);
			}
			if($lb_update)
			{
			    $this->uf_update_partidaasignacion($as_codasi,$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["cant"][$li_i],$aa_partidasnuevas["pre"][$li_i],$aa_seguridad);
			    	$this->uf_update_cantidaasignada($aa_partidasnuevas["codasi"][$li_i],$aa_partidasnuevas["codpar"][$li_i],$aa_partidasnuevas["cant"][$li_i],$aa_partidasnuevas["canteje"][$li_i],$la_partidasviejas["canparval"][$li_i],$aa_seguridad);
			}
		}
		for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				if( ($la_partidasviejas["codemp"][$li_j] == $ls_codemp) && ($la_partidasviejas["codval"][$li_j] == $as_codval) && ($la_partidasviejas["codcon"][$li_j] == $as_codcon) &&  ($la_partidasviejas["codpar"][$li_j] == $aa_partidasnuevas["codpar"][$li_i]) )
				{
				  $lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$this->uf_delete_dtpartidas($as_codval,$as_codcon,$la_partidasviejas["codpar"][$li_j],$aa_seguridad);
				
			}
		}			
	}	
	function uf_select_retenciones ($as_codval,$as_codcon,&$aa_data,&$ai_rows)
	{
	 /***************************************************************************************/
	 /*	Function:	    uf_change_estatus_asi                                               */    
     /* Access:			public                                                              */ 
	 /*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro   */ 
	 /*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion        */    
	 /*  Fecha:          25/03/2006                                                         */        
	 /*	Autor:          Miguel Palencia	                                                */     
	 /***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT rvc.codemp,rvc.codcon,rvc.codval,rvc.codded,rvc.monret,rvc.montotret,d.dended,d.sc_cuenta,d.monded,d.formula 
				 FROM sob_retencionvaluacioncontrato rvc, tepuy_deducciones d
				 WHERE rvc.codemp='".$ls_codemp."' AND d.codemp='".$ls_codemp."' AND rvc.codded=d.codded AND rvc.codcon='".$as_codcon."' AND rvc.codval='".$as_codval."'";

//print $ls_sql;

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select retenciones".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	function uf_guardar_retenciones($as_codval,$as_codcon,$as_codded,$ai_monret,$ai_montotret,$aa_seguridad)             
    { 
	    /***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ld_monret=$this->io_funcsob->uf_convertir_cadenanumero($ai_monret);
		$ld_montotret=$this->io_funcsob->uf_convertir_cadenanumero($ai_montotret);
		$ls_sql="INSERT INTO sob_retencionvaluacioncontrato (codemp,codval,codcon,codded,monret,montotret)
		         VALUES ('".$ls_codemp."','".$as_codval."','".$as_codcon."','".$as_codded."','".$ld_monret."','".$ld_montotret."')";
		//print $ls_sql; 
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_guardar_retenciones".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
		}
		else
		{
			if($li_row>0)
			{
			    /************    SEGURIDAD    **************/		 
				  $ls_evento="INSERT";
				  $ls_descripcion ="Insertó la Retencion ".$as_codded.", Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{
				
				$this->io_sql->rollback();
			}
		
		}		
		return $lb_valido;
	}
	function uf_delete_retenciones($as_codval,$as_codcon,$as_codded,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="DELETE FROM sob_retencionvaluacioncontrato
					WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."' AND codcon='".$as_codcon."' AND codded='".$as_codded."'";		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			print"Error en metodo eliminar_retencion".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			/*************    SEGURIDAD    **************/		 
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Retencion ".$as_codded.",Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/	
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;	
	
	}
	function uf_update_retencion($as_codval,$as_codcon,$as_codded,$ai_monret,$ai_montotret,$aa_seguridad)
	 {
	
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ld_monret=$this->io_funcsob->uf_convertir_cadenanumero($ai_monret);
		$ld_montotret=$this->io_funcsob->uf_convertir_cadenanumero($ai_montotret);
		$ls_sql="UPDATE sob_retencionvaluacioncontrato
				SET monret=".$ld_monret.", montotret=".$ld_montotret."
				WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."' AND codded='".$as_codded."'";		
		$this->io_sql->begin_transaction();	
		//print($ls_sql);
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_retencion ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
		}
		else
		{
			if($li_row>0)
			{
				/*************    SEGURIDAD    **************/		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la retencion ".$as_codpar.", Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{
				
				$this->io_sql->rollback();	
			}
		
		}		
		return $lb_valido;
	  }

	function uf_update_retenciones($as_codval,$as_codcon,$aa_retencionesnuevas,$ai_totalfilas,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$lb_update=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$lb_valido=$this->uf_select_retenciones ($as_codval,$as_codcon,$la_retencionesviejas,$li_totalviejas);
			$li_totalnuevas=$ai_totalfilas;
		for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
			{
				if( ($la_retencionesviejas["codemp"][$li_j] == $ls_codemp) && ($la_retencionesviejas["codval"][$li_j] == $as_codval) && ($la_retencionesviejas["codcon"][$li_j] == $as_codcon) && ($la_retencionesviejas["codded"][$li_j] == $aa_retencionesnuevas["codret"][$li_i]) )
				{
				  if($la_retencionesviejas["monret"][$li_j] != $aa_retencionesnuevas["monto"][$li_i])
					{
					  $lb_update=true;
					}
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$this->uf_guardar_retenciones($as_codval,$as_codcon,$aa_retencionesnuevas["codret"][$li_i],$aa_retencionesnuevas["monret"][$li_i],$aa_retencionesnuevas["montotret"][$li_i],$aa_seguridad);
			}
			if ($lb_update)
			{
			    $this->uf_update_retencion($as_codval,$as_codcon,$aa_retencionesnuevas["codret"][$li_i],$aa_retencionesnuevas["monret"][$li_i],$aa_retencionesnuevas["montotret"][$li_i],$aa_seguridad);
			}
		}
		
		for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				if( ($la_retencionesviejas["codemp"][$li_j] == $ls_codemp) && ($la_retencionesviejas["codval"][$li_j] == $as_codval) && ($la_retencionesviejas["codcon"][$li_j] == $as_codcon) && ($la_retencionesviejas["codded"][$li_j] == $aa_retencionesnuevas["codret"][$li_i]) )
				{
					
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$this->uf_delete_retenciones($as_codval,$as_codcon,$la_retencionesviejas["codded"][$li_j],$aa_seguridad);
			}
		}			
	}
/////////////////////
function uf_buscar_cargos($as_codcar,&$as_codestpro,&$as_spgcuenta)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          27/03/2009                                                         */        
		/*	Autor:         Miguel Palencia		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT codestpro,spg_cuenta FROM tepuy_cargos WHERE codemp='".$ls_codemp."' AND codcar='".$as_codcar."'";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_codestpro=$la_row["codestpro"];
				$as_spgcuenta=$la_row["spg_cuenta"];
			}			
		}		
		return $lb_valido;
	}
/////////////////////////////// BUSCO LOS CARGOS ASOCIADOS A LA ASIGNACION DE LA OBRA Y LOS CLAVO EN LA VALUACION /////
function uf_select_cargoasignacion ($as_codcon,&$aa_data,&$ai_rows)
{
 	//////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_select_cargoasignacion
	 //	Access:  public
	 //	Returns: arreglo con datos de los cargos de una asignacion
	 //	Description: Funcion que permite obtener todos los cargos asociados a una
	 //				  asignacion determinada
	 // Fecha: 24/03/2014
     // Autor: Ing.Miguel Palencia
	 //////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql=" SELECT ca.codcar, c.dencar as dencar,c.formula as formula,ca.codestprog,ca.spg_cuenta,s.montoaux as monto 
				  FROM sob_cargoasignacion ca, tepuy_cargos c, sob_contrato s
				  WHERE ca.codemp='".$ls_codemp."' AND c.codemp='".$ls_codemp."' ".
				"AND ca.codcar=c.codcar AND ca.codcar=c.codcar".
				" AND s.codcon='".$as_codcon."' AND s.codasi=ca.codasi";

		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);   
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_select_cargoasignacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
}	

//*******************************************************************************************************//

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////

    function uf_select_cargos($as_codval,$as_codcon,&$aa_data,&$ai_rows)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT v.codemp,v.codcon,v.codval,v.codcar,c.dencar,v.monto,v.formula
				 FROM sob_cargovaluacion v, tepuy_cargos c 
				 WHERE v.codemp='".$ls_codemp."' AND c.codemp='".$ls_codemp."' AND v.codval='".$as_codval."' AND v.codcon='".$as_codcon."' AND v.codcar=c.codcar";

			 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	function uf_guardar_dtcargos($as_codval,$as_codcon,$as_codcar,$as_basimp,$as_monto,$as_formula,$as_codestpro,$as_spgcuenta,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_monto=$this->io_funcsob->uf_convertir_cadenanumero($as_monto);
		$ad_basimp=$this->io_funcsob->uf_convertir_cadenanumero($as_basimp);
	// AQUI SE MODIFICO EL INSERT PORQUE EL MONTO DE LA BASE IMPONIBLE DEBE SER EN EL MONTO DEL CARGO Y NO
	//  LA BASE IMPONIBLE DE LA VALUACION 
		$ls_sql="INSERT INTO sob_cargovaluacion (codemp,codcar,codval,codcon,basimp,monto,formula,codestprog,spg_cuenta)
		         VALUES ('".$ls_codemp."','".$as_codcar."','".$as_codval."','".$as_codcon."','".$ad_basimp."','".$ad_monto."','".$as_formula."','".$as_codestpro."','".$as_spgcuenta."')";
		//print $ls_sql;
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_guardar_dt".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
		}
		else
		{
			if($li_row>0)
			{
		    	/************    SEGURIDAD    **************/		 
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Cargo ".$as_codcar.", Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{
				
				$this->io_sql->rollback();
			}
		
		}		
		return $lb_valido;
	}	
	function uf_delete_dtcargos($as_codval,$as_codcon,$as_codcar,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="DELETE FROM sob_cargovaluacion
					WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."' AND codcon='".$as_codcon."' AND codcar='".$as_codcar."'";		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			print"Error en metodo eliminar_dtpartidas".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
		    /*************    SEGURIDAD    **************/		 
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Cargo ".$as_codcar.",Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/	
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;	
	
	}
	function uf_update_dtcargos($as_codval,$as_codcon,$as_basimp,$aa_cargosnuevos,$ai_totalfilas,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$lb_valido=$this->uf_select_cargos($as_codval,$as_codcon,$la_cargosviejos,$li_totalviejos);
		$li_totalnuevas=$ai_totalfilas;
		for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalviejos;$li_j++)
			{
				if( ($la_cargosviejos["codemp"][$li_j] == $ls_codemp) && ($la_cargosviejos["codval"][$li_j] == $as_codval) && ($la_cargosviejos["codcon"][$li_j] == $as_codcon) && ($la_cargosviejos["codcar"][$li_j] == $aa_cargosnuevos["codcar"][$li_i]) )
				{
					
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$valido=$this->uf_buscar_cargos($aa_cargosnuevos["codcar"][$li_i],$as_codestpro,$as_spgcuenta);
				$this->uf_guardar_dtcargos($as_codval,$as_codcon,$aa_cargosnuevos["codcar"][$li_i],$as_basimp,$aa_cargosnuevos["monto"][$li_i],$aa_cargosnuevos["formula"][$li_i],$as_codestpro,$as_spgcuenta,$aa_seguridad);
			}
		}
		
		for ($li_j=1;$li_j<=$li_totalviejos;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				if( ($la_cargosviejos["codemp"][$li_j] == $ls_codemp) && ($la_cargosviejos["codval"][$li_j] == $as_codval) && ($la_cargosviejos["codcon"][$li_j] == $as_codcon) &&  ($la_cargosviejos["codcar"][$li_j] == $aa_cargosnuevos["codcar"][$li_i]) )
				{
				  $lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$this->uf_delete_dtcargos($as_codval,$as_codcon,$la_cargosviejos["codcar"][$li_j],$aa_seguridad);
				
			}
		}			
	}
    function uf_update_estado($as_codval,$ai_estatus,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="UPDATE sob_valuacion
		         SET estval=".$ai_estatus."
				 WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."'";		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			print"Error en metodo uf_change_estatus_asi".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
		    if($ai_estatus==3)
			{
			/*************    SEGURIDAD    **************/		 
			$ls_evento="DELETE";
			$ls_descripcion ="Anulo la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/
			}
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;		
	}
	function uf_select_estado($as_codval,&$estasi)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT estval
		         FROM  sob_valuacion
		         WHERE codemp='".$ls_codemp."' AND codval='".$as_codval."'";		
		$rs_data=$this->io_sql->select($ls_sql);
	    if($rs_data===false)
	     {
		  print "Error en select estado".$this->io_function->uf_convertirmsg($this->io_sql->message);
	     }
	     else
	     {
		 if($la_row=$this->io_sql->fetch_row($rs_data))
		  {
			$estasi=$la_row["estval"];
			$lb_valido=true;
		  }		
	    }
     	return $lb_valido;
    }
    function uf_select_anticipos($as_codcon,&$ls_anti)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT SUM(a.montotant) as totant
FROM  sob_contrato c, sob_anticipo a
WHERE c.codemp='".$ls_codemp."' AND a.codemp='".$ls_codemp."' AND c.codcon='".$as_codcon."' AND a.codcon=c.codcon";
				 		
		$rs_data=$this->io_sql->select($ls_sql);
	    if($rs_data===false)
	     {
		  print "Error en select estado".$this->io_function->uf_convertirmsg($this->io_sql->message);
	     }
	     else
	     {
		 if($la_row=$this->io_sql->fetch_row($rs_data))
		  {
			$ls_anti=$la_row["totant"];
			$lb_valido=true;
		  }		
	    }
     	return $lb_valido;
   }
function uf_select_variaciones($as_codcon,&$ls_vari,$as_tipvar)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT SUM(vc.monto) as variacion
FROM  sob_contrato c, sob_variacioncontrato vc
WHERE c.codemp='".$ls_codemp."' AND vc.codemp='".$ls_codemp."' AND c.codcon='".$as_codcon."' AND vc.codcon=c.codcon and (vc.tipvar=".$as_tipvar.")";
				 		
		$rs_data=$this->io_sql->select($ls_sql);
	    if($rs_data===false)
	     {
		  print "Error en select estado".$this->io_function->uf_convertirmsg($this->io_sql->message);
	     }
	     else
	     {
		 if($la_row=$this->io_sql->fetch_row($rs_data))
		  {
			$ls_vari=$la_row["variacion"];
			$lb_valido=true;
		  }		
	    }
     	return $lb_valido;
   }
   function uf_select_contrato($as_codcon,&$aa_data)
   {
		/***************************************************************************************/
		/*	Function:	    uf_select_contrato                                                 */    
		/*	Description:	Funcion que se encarga de buscar en bd los datos de un contrato    */    
		/*  Fecha:          17/04/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT co.feccon,co.monto,co.estcon,ai.puncueasi,ob.desobr,ai.basimpasi as imponible
                 FROM sob_contrato co,sob_asignacion ai,sob_obra ob
                 WHERE co.codemp='".$ls_codemp."' AND ai.codemp='".$ls_codemp."' AND ob.codemp='".$ls_codemp."' AND co.codcon='".$as_codcon."' AND ai.codasi=co.codasi AND ai.codobr=ob.codobr";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$aa_data="";
			}
		}
		return $lb_valido;
	}
   function uf_select_valanterior($as_codcon,$as_codval,&$aa_data)
   {
		/***************************************************************************************/
		/*	Function:	    uf_select_contrato                                                 */    
		/*	Description:	Funcion que se encarga de buscar en bd los datos de un contrato    */    
		/*  Fecha:          17/04/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$li_codval=$as_codval-1;
		$ls_codval=$this->io_function->uf_cerosizquierda($li_codval,3);
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT  amoval,SUM(amototval) as amototval,SUM(amoresval) as amoresval
                 FROM sob_valuacion 
                 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' ";//AND codval='".$ls_codval."'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$aa_data="";
			}
		}
		return $lb_valido;
	}
   function uf_select_newcodigo($as_codcon,&$as_codval)
   {
		/***************************************************************************************/
		/*	Function:	    uf_select_contrato                                                 */    
		/*	Description:	Funcion que se encarga de asignar un nuevo codigo a la valuacion   */    
		/*  Fecha:          17/04/2006                                                         */        
		/*	Autor:          Miguel Palencia	                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT codval
                 FROM sob_valuacion 
                 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' ORDER BY codval DESC ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$li_codval=$row["codval"];
				settype($li_codval,'int');
				$li_codval=$li_codval+1;
				settype($li_codval,'string');
		        $as_codval=$this->io_function->uf_cerosizquierda($li_codval,3);
			}
			else
			{
				$as_codval="001";
			}
		}
		return $lb_valido;
	}
		function uf_ejecucion_financiera($as_codcon,&$ad_total)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_ejecucion_financiera
		// Access:			public
		//	Returns:		Boolean, Retorna true si existe el registro en bd
		//	Description:	Funcion que se encarga de retornar el monto total de la ejecucion financiera de un contrato
		//  Fecha:          13/06/2006
		//	Autor:          Ing. Miguel Palencia		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ad_total=0;		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT sum(subtotpar) as total
				 FROM sob_valuacion
				 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."'";
				 //print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select montoanticipocontratos".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			$lb_valido=false;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_data=$this->io_sql->obtener_datos($rs_data);
				$ad_total=$aa_data["total"][1];
			}
			else
				$ad_total=0;			
		}	
		return $lb_valido;
	}
	function uf_amortizacion_anticipo($as_codcon,&$ad_amortizacion)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_amortizacion_anticipo
		// Access:			public
		//	Returns:		Boolean, Retorna true si existe el registro en bd
		//	Description:	Funcion que se encarga de retornar la amortizacion total acumulada para
		//					un contrato
		//  Fecha:          14/06/2006
		//	Autor:          Ing. Miguel Palencia		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ad_amortizacion=0;		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT amototval 
				FROM sob_valuacion 
				WHERE fecfinval IN 
				(SELECT MAX(fecfinval) 
				FROM sob_valuacion 
				WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."')";
				 //print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_amortizacion_anticipo".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			$lb_valido=false;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_data=$this->io_sql->obtener_datos($rs_data);
				$ad_amortizacion=$aa_data["amototval"][1];
			}
			else
				$ad_amortizacion=0;			
		}	
		return $lb_valido;
	}

function uf_select_asignacionpartidaobra ($as_codasi,&$aa_data)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_select_asignacionpartidaobra
	// Access:			public
	//	Returns:		Boolean, Retorna true si existe el registro en bd
	//	Description:	Funcion que se encarga de retornar las partidas por asignacion
	//  Fecha:          22/03/2006
	//	Autor:          Ing. Miguel Palencia		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT * 
			FROM sob_asignacionpartidaobra 
			WHERE codemp='$ls_codemp' AND codasi='$as_codasi'";
	//print $ls_sql;
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en select_asignacionpartidaobra".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_valido=true;
			$aa_data=$this->io_sql->obtener_datos($rs_data);
		}
		else
		{
			$aa_data="";
			$lb_valido=0;
		}
	}
	return $lb_valido;
}

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_contabilizado($as_codasi)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_contabilizado
		//		   Access: private
		//	    Arguments: $as_codasi // Codigo de Asignacion
 		//	      Returns: $lb_valido Indica si la asignacion esta contabilizada
		//	  Description: Verifica la contabilizacion de la asignacion
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ls_sql="SELECT estspgscg". 
				"  FROM sob_asignacion". 
				" WHERE sob_asignacion.codemp='".$this->ls_codemp."'".
				"   AND sob_asignacion.codasi='".$as_codasi."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select cuentacontable".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_estspgscg=$row["estspgscg"];
				if($ls_estspgscg==1)
				{
					$lb_valido=true;		
				}
			}			
		}	
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_recepcion_documentos($as_numrecdoc,$as_numref,$as_numfactura,$as_codtipdoc,$as_conval,$ad_fecval,$ai_montotval,$ai_totreten,$ai_totconcar,$as_codcon,
											  $ai_basimpval,$as_codasi,$as_codval,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_recepcion_documentos
		//		   Access: private
		//	    Arguments: $as_numrecdoc    // Numero de Recepcion de documentos
		//				   $as_codtipdoc 	// Codigo de tipo de documento
		//				   $as_codtipdoc	// codigo de tipo de documento
		//				   $as_conval	    // Codigo de Valuacion
		//				   $ad_fecval  		// Fecha de Valuacion
		//				   $ai_montotval  	// Monto total de valuacion
		//				   $ai_totretten    // Monto total de retenciones
		//				   $ai_totcargos    // Monto total de cargos
		//				   $as_codcon       // Codigo del contrato
		//				   $ai_basimpval    // Base Imponible Valuacion
		//				   $as_codasi       // Codigo de Asignacion
		//				   $aa_seguridad    // Arreglo de las variables de seguridad
		//	      Returns: $lb_valido True si se genero la recepción de documento correctamente
		//	  Description: Retorna un Booleano
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
        	$ls_tipodestino= "P";			
		$ls_cedbene= "----------";	
		$ls_codpro=$this->uf_select_contratista($as_codcon);
		$li_totcargos=($ai_totconcar-$ai_basimpval);
		//$as_numrecdoc="000000000002";
		//$as_numrecdoc="VAL".substr($as_codcon,6,7).$as_codval;
		$lb_existe=$this->uf_select_recepcion($as_numrecdoc,$as_codtipdoc,$ls_cedbene,$ls_codpro);
		//print "documento: ".$as_numrecdoc;
		//print "tipo documento:".$as_codtipdoc;
		//print "proveedor:".$ls_codpro;
		//print "codigo valuacion:".$as_codval;
		
		if(!$lb_existe)
		{
		//MODIFIQUE AQUI PARA QUE EL TOTAL DEL DOCUMENTO SEA EN LUGAR DE LA VARIABLE .$ai_montotval por $neto_a_pagar  // YA QUE ESTE ES EL MONTO NETO O TOTAL A PAGAR EN LA ORDEN Linea 1377 y 1386 (INSERT)
			$neto_a_pagar=$ai_montotval-$ai_totreten;
			$ad_fecval= $this->io_function->uf_convertirdatetobd($ad_fecval);
			$this->io_sql->begin_transaction();	
			$ls_sql="INSERT INTO cxp_rd (codemp,numrecdoc,numref,numfac,codtipdoc,ced_bene,cod_pro,dencondoc,fecemidoc, fecregdoc, fecvendoc,".
					"                    montotdoc, mondeddoc,moncardoc,tipproben,estprodoc,procede,estlibcom,estaprord,".
					"                    fecaprord,usuaprord,estimpmun,codcla)".
					"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_numfactura."','".$as_numref."','".$as_codtipdoc."','".$ls_cedbene."',".
					"             '".$ls_codpro."','".$as_conval."','".$ad_fecval."','".$ad_fecval."','".$ad_fecval."',
					"               .$neto_a_pagar.",".$ai_totreten.",".$li_totcargos.",'".$ls_tipodestino."','R','SOBCON',0,0,'1900-01-01','OBRAS',0,'--')";
				//print $ls_sql;	
			//print $ai_totreten;
			//print " PROCESO: " .$ls_sql;
			//return false;	
					
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_procesar_recepcion_documentos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_dt_recepcion_documento($as_numrecdoc,$as_codtipdoc,$ls_cedbene,$ls_codpro,$ai_basimpval,$as_codcon,$as_codasi,$as_codval);
				if($lb_valido)
				{
					$lb_valido=$this->uf_update_estatus_generacion_valuacion_rd($as_codcon, $as_codval,$aa_seguridad);
				}
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="PROCESS";
					$ls_descripcion="Generó la Recepción de Documento de la llave contrato-valuacion <b>".$as_numrecdoc."</b>";
					$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													  $aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
			}
			if($lb_valido)
			{
				$this->io_sql->commit();	
				$this->io_msg->message("La Recepcion de Documentos se Genero con Exito.");
			}
			else
			{
				$this->io_sql->rollback();	
				$this->io_msg->message("No se Genero la Recepcion de Documentos");
			}
		}
		else
		{
			$this->io_msg->message("El Nro. de Ficha en la Recepcion de Documentos (Ordenes de Pago) ya Existe.");
			$lb_valido=false;
		}
		return $lb_valido;
	}  // end function uf_procesar_recepcion_documentos
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_generacion_valuacion_rd($as_codcon, $as_conval,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_recepcion_documentos
		//		   Access: private
		//	    Arguments: $as_conant	    // descripcion del documento
		//				   $as_codcon       // Codigo del contrato
		//				   $aa_seguridad    // Arreglo de las variables de seguridad
		//	      Returns: $lb_valido True si se genero la recepción de documento correctamente
		//	  Description: Retorna un Booleano
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 05/05/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="UPDATE sob_valuacion".
				"   SET estgenrd='1'".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcon='".$as_codcon."'".
				"   AND codval='".$as_conval."'";	
		//print $ls_sql;	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
           	$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_update_estatus_generacion_valuacion_rd ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$this->io_sql->rollback();
			
		}
		else
		{
			//print "aqui";
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el estatus de generacion de R.D. de la Valuacion ".$as_codval." del Contrato ".$as_codcon." Asociado a la Empresa ".$this->ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			$lb_valido=true;
			
		}		
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------


	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_contratista($as_codcon)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_contratista
		//		   Access: private
		//	    Arguments: $as_codcon    // codigo de contrato
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene el codigo del proveedor relacionado con el contrato
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT sob_asignacion.cod_pro". 
				"  FROM sob_contrato , sob_asignacion". 
				" WHERE sob_contrato.codemp='".$this->ls_codemp."'".
				"   AND sob_contrato.codcon='".$as_codcon."'".
				"   AND sob_contrato.codemp=sob_asignacion.codemp".
				"   AND sob_contrato.codasi=sob_asignacion.codasi";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_select_contratista ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				//$la_data=$this->io_sql->obtener_datos($rs_data);
				$ls_codpro=$row["cod_pro"];
			}			
		}	
		return $ls_codpro;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_recepcion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_recepcion
		//		   Access: private
		//	    Arguments: $as_numrecdoc // Numero de Recepcion de Documentos
		//                 $as_codtipdoc // Codigo de Tipo de Documento
		//                 $as_cedbene   // Cedula de Beneficiario
		//                 $as_codpro    // Codigo de Proveedor
 		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Verifica la existencia de una Recepcion de Documentos
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT numrecdoc". 
				"  FROM cxp_rd". 
				" WHERE cxp_rd.codemp='".$ls_codemp."'".
				"   AND cxp_rd.numrecdoc='".$as_numrecdoc."'".
				"   AND cxp_rd.codtipdoc='".$as_codtipdoc."'".
				"   AND cxp_rd.cod_pro='".$as_codpro."'".
				"   AND cxp_rd.ced_bene='".$as_cedbene."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_select_recepcion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;		
			}			
		}	
		return $lb_existe;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_dt_recepcion_documento($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$ai_basimpval,$as_codcon,$as_codasi,$as_codval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_recepcion_documentos
		//		   Access: private
		//	    Arguments: $as_numrecdoc    // Numero de Recepcion de documentos
		//				   $as_codtipdoc 	// Codigo de tipo de documento
		//				   $as_cedbene   	// Cedula de Beneficiario
		//				   $as_codpro   	// Codigo de proveedor
		//				   $as_conval	    // Codigo de Valuacion
		//				   $ad_fecval  		// Fecha de Valuacion
		//				   $ai_montotval  	// Monto total de valuacion
		//				   $ai_totretten    // Monto total de retenciones
		//				   $ai_totcargos    // Monto total de cargos
		//				   $as_codcon       // Codigo del contrato
		//				   $ai_basimpval    // Base Imponible Valuacion
		//				   $as_codasi       // Codigo de Asignacion
		//				   $aa_seguridad    // Arreglo de las variables de seguridad
		//	      Returns: $lb_valido True 
		//	  Description: Retorna un Booleano
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_valido=$this->uf_obtener_estructura($as_codasi,$as_codestpro,&$as_spgcuenta);
	//print "cuenta extraida ".$as_spgcuenta;
	//print "programatica: ".$as_codestpro;
		$as_sccuenta=$this->uf_obtener_contable1($as_codasi,$as_codestpro,&$as_spgcuenta);
		if($lb_valido)
		{//$as_sccuentaprint "numero de documento".$as_numrecdoc;
			$ls_sql="INSERT INTO cxp_rd_spg (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, codestpro,".
					"                        spg_cuenta, monto, codfuefin)".
					"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
					"             '".$as_codpro."','SOBCON','".$as_codcon."','".$as_codestpro."','".$as_spgcuenta."',".
					"               ".$ai_basimpval.",'--')";
			$li_row=$this->io_sql->execute($ls_sql);
			//print "esta es una:".$ls_sql;
			if($li_row===false)
			{ 
				$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_procesar_recepcion_documentos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
			else
			{
				$lb_valido=true;
			//print "contable: ".$as_sccuenta." cuenta presupuestaria :".$as_spgcuenta;
				$ls_sccuenta=$this->uf_select_cuentacontable($as_spgcuenta,$as_codestpro);
				$this->io_ds_scgcuentas->insertRow("sccuenta",$ls_sccuenta);
				$this->io_ds_scgcuentas->insertRow("debhab","D");
				$this->io_ds_scgcuentas->insertRow("monto",$ai_basimpval);
///////////////////
			
				$ls_sql1="INSERT INTO cxp_rd_scg (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, debhab, sc_cuenta,".
						"				 		 monto, estgenasi, estasicon)".
						"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
						"             '".$as_codpro."','SOBCON','".$as_codcon."','D','".$as_sccuenta."',".
						"               ".$ai_basimpval.",0,'A')";
				//print $ls_sql1;
				
				$li_row=$this->io_sql->execute($ls_sql1);
				if($li_row===false)
				{ 
					$this->io_msg->message("CLASE->Valuacionxxxx MÉTODO->uf_dt_recepcion_documentos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				} 
///////////////////
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_cargos($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_codval,$as_codcon);
			}
			if($lb_valido)
			{
				//print "documento ".$as_numrecdoc." tipo de documento ".$as_codtipdoc." cedula ben ".$as_cedbene." cod pñroveedor ".$as_codpro." codigo valuacion ".$as_codval." codigo contrato ".$as_codcon;
				$lb_valido=$this->uf_insert_deducciones($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_codval,$as_codcon);
			}
		}
		return $lb_valido;
	}

////////////////
function uf_obtener_contable1($as_codasi,&$as_codestpro,$as_spgcuenta)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_estructura
		//		   Access: private
		//	    Arguments: $as_codasi    // codigo de asignacion
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene la cuenta contable que esta asociada al presupuesto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$lb_valido=false;		
		$ls_sql="SELECT sc_cuenta FROM spg_cuentas WHERE codemp='".$this->ls_codemp."' AND codestpro1='".substr($as_codestpro,0,20)."' AND codestpro2='".substr($as_codestpro,20,6)."' AND codestpro3='".substr($as_codestpro,26,3)."' AND codestpro4='".substr($as_codestpro,29,2)."' AND codestpro5='".substr($as_codestpro,31,2)."' AND spg_cuenta='".$as_spgcuenta."';";
//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_obtener_contable1 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));	
			return false;		
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_sccuenta=$row["sc_cuenta"];
				//print $as_sccuenta;
			}
	
		}	
		return $as_sccuenta;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
//////////////
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_estructura($as_codasi,&$as_codestpro,&$as_spgcuenta)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_estructura
		//		   Access: private
		//	    Arguments: $as_codasi    // codigo de asignacion
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene la estructura de la asignacion
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_codestpro="";		
		$as_spgcuenta="";
		$lb_valido=false;		
		$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta FROM  sob_cuentasasignacion WHERE codemp='".$this->ls_codemp."' AND codasi='".$as_codasi."' AND spg_cuenta NOT IN (SELECT spg_cuenta FROM tepuy_cargos GROUP BY spg_cuenta);";

// ESTO ME LO CAMBIO TUA
//		$ls_sql="SELECT distinct(a.codestpro1),a.codestpro2,a.codestpro3,a.codestpro4,a.codestpro5,b.spg_cuenta FROM sob_cuentasasignacion a, tepuy_cargos b WHERE a.codemp=b.codemp AND a.codasi='".$as_codasi."' AND a.codemp='".$this->ls_codemp."'";

//print "hhh:".$ls_sql;

$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_obtener_estructura ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));	
			return false;		
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codestpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
				$as_spgcuenta=$row["spg_cuenta"];
				$lb_valido=true;
			//print "estpro:".$as_codestpro." - spgcuenta: ". $as_spgcuenta;
			}			
		}	
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cargos($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_codval,$as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_estructura
		//		   Access: private
		//	    Arguments: $as_numrecdoc    // Numero de Recepcion de documentos
		//				   $as_codtipdoc 	// Codigo de tipo de documento
		//				   $as_cedbene   	// Cedula de Beneficiario
		//				   $as_codval   	// Codigo de valuacion
		//				   $as_codcon   	// Codigo de proveedor
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene la estructura de la asignacion
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ls_sql="SELECT codcar,basimp,monto,formula,codestprog,spg_cuenta FROM sob_cargovaluacion". 
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codval='".$as_codval."'".
				"   AND codcon='".$as_codcon."'";
		$rs_data=$this->io_sql->select($ls_sql);
		//print $ls_sql;
		
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_obtener_estructura ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));	
			return false;		
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codcar=$row["codcar"];
				$li_basimp=$row["basimp"];
				$li_monto=$row["monto"];
				$li_basimp1=$row["monto"];
				$ls_formula=$row["formula"];
				$ls_codestpro=$row["codestprog"];
				$ls_spgcuenta=$row["spg_cuenta"];
				$ls_porcar=$this->uf_select_porcar($ls_codcar);
				$li_monto1=$this->uf_buscar_monto_retenido($as_codcon,$as_codval,$li_basimp1);
				$ls_sql="INSERT INTO cxp_rd_cargos (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, codcar, procede_doc, numdoccom,".
						"                           monobjret, monret, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5,".
						"							spg_cuenta, porcar, formula)".
						"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
						"             '".$as_codpro."','".$ls_codcar."','SOBCON','".$as_codcon."',".$li_basimp1.",".$li_monto1.",".
						"             '".substr($ls_codestpro,0,20)."','".substr($ls_codestpro,20,6)."','".substr($ls_codestpro,26,3)."',".
						"             '".substr($ls_codestpro,29,2)."','".substr($ls_codestpro,31,2)."','".$ls_spgcuenta."','".$ls_porcar."','".$ls_formula."')";
				$li_row=$this->io_sql->execute($ls_sql);
				//print "aqui deben estar:".$ls_sql;
				//return false;
				if($li_row===false)
				{ 
					$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_insert_cargos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				}
				else
				{
					$this->io_ds_spgcuentas->insertRow("spgcuenta",$ls_spgcuenta);
					$this->io_ds_spgcuentas->insertRow("codestpro",$ls_codestpro);
					$this->io_ds_spgcuentas->insertRow("monto",$li_monto);
					$this->io_ds_spgcuentas->insertRow("basimp",$li_basimp);
					// esto lo movi desde abajo con algunos ajuste //
					$ls_sql1="INSERT INTO cxp_rd_spg (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, codestpro,".
						"                        spg_cuenta, monto, codfuefin)".
						"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
						"             '".$as_codpro."','SOBCON','".$as_codcon."','".$ls_codestpro."','".$ls_spgcuenta."',".
						"               ".$li_monto.",'--')";
				//print "chequeando:          ".$ls_sql1;
				$li_row=$this->io_sql->execute($ls_sql1);
				if($li_row===false)
				{ 
					$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_insert_cargos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				} 
				else
				{
					//$ls_sccuenta=$this->uf_select_cuentacontable($as_spgcuenta,$as_codestpro);
					$as_sccuenta=$this->uf_obtener_contable1($as_codasi,$ls_codestpro,&$ls_spgcuenta);
				//	print "presupuestaria: ".$ls_spgcuenta." programatica: ".$ls_codestpro." contable: ".$as_sccuenta;
					//$this->io_ds_scgcuentas->insertRow("sccuenta",$ls_sccuenta);
					//$this->io_ds_scgcuentas->insertRow("debhab","D");
/////////////////// ESTO ES PARA AGREGAR LA CUENTA CONTABLE Y EL MONTO
			
				$ls_sql1="INSERT INTO cxp_rd_scg (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, debhab, sc_cuenta,".
						"				 		 monto, estgenasi, estasicon)".
						"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
						"             '".$as_codpro."','SOBCON','".$as_codcon."','D','".$as_sccuenta."',".
						"               ".$li_monto.",0,'A')";
				//print $ls_sql1;
				// YA PASAMOS TODOS LOS CARGOS A NIVEL DEL DEBE Y EL HABER
				$li_row=$this->io_sql->execute($ls_sql1);
				if($li_row===false)
				{ 
					$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_insert_cargos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				} 
///////////////////

					$lb_valido=true;
				}
				}
				$lb_valido=true;
			}
		// ELIMINAMOS ESTO PARA EVITAR TRABAJAR CON EL ARRAY Y LO COLOCAMOS ARRIBA
			/*
			$this->io_ds_spgcuentas->group_by(array('0'=>'spgcuenta','1'=>'codestpro'),array('0'=>'monto','1'=>'basimp'),'monto');
			$li_totrow=$this->io_ds_spgcuentas->getRowCount('spgcuenta');	
			for($li_fila=1;$li_fila<=$li_totrow;$li_fila++)
			{
				$ls_spgcuenta=$this->io_ds_spgcuentas->getValue('spgcuenta',$li_fila);
				$ls_codestpro=$this->io_ds_spgcuentas->getValue('codestpro',$li_fila);
				$li_monto=$this->io_ds_spgcuentas->getValue('monto',$li_fila);
				$li_basimp=$this->io_ds_spgcuentas->getValue('basimp',$li_fila);
				
				$ls_sql="INSERT INTO cxp_rd_spg (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, codestpro,".
						"                        spg_cuenta, monto, codfuefin)".
						"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
						"             '".$as_codpro."','SOBCON','".$as_codcon."','".$ls_codestpro."','".$ls_spgcuenta."',".
						"               ".$li_monto.",'--')";
		print $ls_sql;
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_procesar_recepcion_documentos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				} 
				else
				{
					$ls_sccuenta=$this->uf_select_cuentacontable($as_spgcuenta,$as_codestpro);
					$this->io_ds_scgcuentas->insertRow("sccuenta",$ls_sccuenta);
					$this->io_ds_scgcuentas->insertRow("debhab","D");
					$lb_valido=true;
				}
		
			}*/
		}	
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	function uf_buscar_monto_retenido($aa_codcon,$aa_codval,$aa_basimp1)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_porcar
		//		   Access: private
		//	    Arguments: $as_codcar    // codigo de cargo
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene el codigo del proveedor relacionado con el contrato
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_porcar="";		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT montotret". 
				"  FROM sob_retencionvaluacioncontrato". 
				" WHERE codemp='".$this->ls_codemp."'".
				" AND codval='".$aa_codval."'".
				" AND codcon='".$aa_codcon."'".
				" AND monret='".$aa_basimp1."'";
		//print "aqui va el monto a filtrar".$aa_basimp1;
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_select_monto_retenido ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_montotret=$row["montotret"];
			}			
		}	
		return $ls_montotret;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------


	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_porcar($as_codcar)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_porcar
		//		   Access: private
		//	    Arguments: $as_codcar    // codigo de cargo
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene el codigo del proveedor relacionado con el contrato
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_porcar="";		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT porcar". 
				"  FROM tepuy_cargos". 
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcar='".$as_codcar."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_select_porcar ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_porcar=$row["porcar"];
			}			
		}	
		return $ls_porcar;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_porcretencion($as_codded)
	{
	
		$ls_porded="";		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT porded FROM tepuy_deducciones". 
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codded='".$as_codded."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_select_porcretencion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_porded=$row["porded"];
			}			
		}	
		return $ls_porded;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_tiporetencion($as_codded)
	{
	
		$ls_impuesto = 0;		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT iva FROM tepuy_deducciones". 
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codded='".$as_codded."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_select_tiporetencion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_impuesto=$row["iva"];
			}			
		}	
		return $ls_impuesto;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_porded($as_codded,&$as_sccuenta)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_porded
		//		   Access: private
		//	    Arguments: $as_codcar    // codigo de cargo
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene el codigo del proveedor relacionado con el contrato
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_porded="";		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT porded,sc_cuenta". 
				"  FROM tepuy_deducciones". 
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codded='".$as_codded."'";
//print "$ls_sql";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_select_porded ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_porded=$row["porded"];
				$as_sccuenta=$row["sc_cuenta"];
			}			
		}	
		return $ls_porded;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuentacontable($as_spgcuenta,$as_codestpro)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cuentacontable
		//		   Access: private
		//	    Arguments: $as_spgcuenta // Cuenta Presupuestaria
		//				   $as_codestpro // Codigo de estructura programatica
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene el codigo del proveedor relacionado con el contrato
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sccuenta="";		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT sc_cuenta". 
				"  FROM spg_cuentas". 
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND spg_cuenta='".$as_spgcuenta."'".
				"   AND codestpro1='".substr($as_codestpro,0,20)."'".
				"   AND codestpro2='".substr($as_codestpro,20,6)."'".
				"   AND codestpro3='".substr($as_codestpro,26,3)."'".
				"   AND codestpro4='".substr($as_codestpro,29,2)."'".
				"   AND codestpro5='".substr($as_codestpro,31,2)."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_select_cuentacontable ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_sccuenta=$row["sc_cuenta"];
			}			
		}	
		return $ls_sccuenta;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_deducciones($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,$as_codval,$as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_deducciones
		//		   Access: private
		//	    Arguments: $as_numrecdoc    // Numero de Recepcion de documentos
		//				   $as_codtipdoc 	// Codigo de tipo de documento
		//				   $as_cedbene   	// Cedula de Beneficiario
		//				   $as_codval   	// Codigo de valuacion
		//				   $as_codcon   	// Codigo de proveedor
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene la estructura de la asignacion
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ls_sql="SELECT codded,monret,montotret FROM sob_retencionvaluacioncontrato". 
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codval='".$as_codval."'".
				"   AND codcon='".$as_codcon."'";
//print "$ls_sql";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_insert_deducciones ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));	
			return false;		
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codded=$row["codded"];
				$li_monret=$row["monret"];
				$li_montotret=$row["montotret"];
				$ls_porded=$this->uf_select_porded($ls_codded,$ls_sccuenta);
				$ls_sql="INSERT INTO cxp_rd_deducciones (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, codded, procede_doc, numdoccom, monobjret,".
						" 								 monret, sc_cuenta, porded, estcmp)".
						"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
						"             '".$as_codpro."','".$ls_codded."','SOBCON','".$as_codcon."',".$li_monret.",".$li_montotret.",".
						"             '".$ls_sccuenta."',".$ls_porded.",'0')";
				//print $ls_sql;
				$li_row=$this->io_sql->execute($ls_sql);

				if($li_row===false)
				{ 
					$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_insert_deducciones ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				}
				else
				{
					$this->io_ds_scgcuentas->insertRow("sccuenta",$ls_sccuenta);
					$this->io_ds_scgcuentas->insertRow("debhab","H");
					$this->io_ds_scgcuentas->insertRow("monto",$li_montotret);
		// ESTO ES NUEVO, SE REUBICA AQUI PARA EVITAR ARMAR UN ARRAY  //
			$ls_sql1="INSERT INTO cxp_rd_scg (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, debhab, sc_cuenta,".
						"				 		 monto, estgenasi, estasicon)".
						"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
						"             '".$as_codpro."','SOBCON','".$as_codcon."','H','".$ls_sccuenta."',".
						"               ".$li_montotret.",0,'A')";
			//	print $ls_sql1;
		
				$li_row=$this->io_sql->execute($ls_sql1);
				if($li_row===false)
				{ 
					$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_insert_deducciones ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				} 
				
				}
				$lb_valido=true;
			}	
			$this->io_ds_scgcuentas->group_by(array('0'=>'sccuenta','1'=>'debhab'),array('0'=>'monto'),'monto');
			/*$li_totrow=$this->io_ds_scgcuentas->getRowCount('sccuenta');
			print "filas...".$li_totrow." - ";
			for($li_fila=1; $li_fila <= $li_totrow;$li_fila++) // ($li_fila=1; $li_fila <= $li_totrow;$li_fila++)
			{
				$ls_sccuenta=$this->io_ds_spgcuentas->getValue('sccuenta',$li_fila);
				$ls_debhab=$this->io_ds_spgcuentas->getValue('debhab',$li_fila);
				$li_monto=$this->io_ds_spgcuentas->getValue('monto',$li_fila);
				$ls_sql="INSERT INTO cxp_rd_scg (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, debhab, sc_cuenta,".
						"				 		 monto, estgenasi, estasicon)".
						"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
						"             '".$as_codpro."','SOBCON','".$as_codcon."','".$ls_debhab."','".$ls_sccuenta."',".
						"               ".$li_monto.",0,'A')";
				print $ls_sql;
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("CLASE->Valuacionxxxx MÉTODO->uf_insert_deducciones ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				} 
			}*/
			$this->io_ds_scgcuentas->group_by(array('0'=>'debhab'),array('0'=>'monto'),'monto');
			$li_totdebhab=$this->io_ds_scgcuentas->getRowCount('sccuenta');	
			$li_totdeb=0;
			$li_tothab=0;
			for($li_fildebhab=1;$li_fildebhab<=$li_totrow;$li_fildebhab++)
			{
				$ls_debhab=$this->io_ds_spgcuentas->getValue('debhab',$li_fila);
				$li_monto=$this->io_ds_spgcuentas->getValue('monto',$li_fila);
				if($ls_debhab=="D")
				{$li_totdeb=$li_totdeb+$li_monto;}
				else
				{$li_tothab=$li_tothab+$li_monto;}
				
			}
			//$li_totpro=($li_totdeb-$li_tothab);
			//print "empresa ".$this->ls_codemp." Valuacion ".$as_codval." Contrato ".$as_codcon;
			$li_totpro=$this->uf_obtener_datos_valuacion($as_codval,$as_codcon);
			//print "DEBE ".$li_totdeb." HABER".$li_tothab;
			$lb_valido=$this->uf_select_cuenta_proveedor($as_codpro,$as_sccuentapro);
			if($lb_valido)
			{
				$ls_sql="INSERT INTO cxp_rd_scg (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, debhab, sc_cuenta,".
						"				 		 monto, estgenasi, estasicon)".
						"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$as_cedbene."',".
						"             '".$as_codpro."','SOBCON','".$as_codcon."','H','".$as_sccuentapro."',".
						"               ".$li_totpro.",0,'A')";
			//	print $ls_sql;
			
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{ 
					$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_insert_deducciones ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				} 
				else
				{
					$lb_valido=true;
				}
			}
		}	
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuenta_proveedor($as_codpro,&$as_sccuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cuenta_proveedor
		//		   Access: private
		//	    Arguments: $as_codcon    // codigo de contrato
		//                 $as_sccuenta  // Cuenta de contratista
		//                 $as_ctaant    // Cuenta de anticipo de contratista
		//	      Returns: $lb_valido Devuelve un booleano
		//	  Description: Obtiene las cuentas contables para el asiento del anticipo
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 30/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT sc_cuenta,sc_ctaant". 
				"  FROM rpc_proveedor". 
				" WHERE codemp='".$ls_codemp."'".
				"   AND cod_pro='".$as_codpro."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
				$this->io_msg->message("CLASE->Anticipo MÉTODO->uf_select_cuenta_proveedor ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				//$la_data=$this->io_sql->obtener_datos($rs_data);
				$as_sccuenta=$row["sc_cuenta"];
				if($as_sccuenta!="")
				{
					$lb_valido=true;
				}
				else
				{
					$this->io_msg->message("Falta por configurar la cuenta contable del proveedor");
				}
			}			
		}	
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
////////////////
function uf_obtener_datos_valuacion(&$as_codval,$as_codcon)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_estructura
		//		   Access: private
		//	    Arguments: $as_codasi    // codigo de asignacion
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene la cuenta contable que esta asociada al presupuesto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$lb_valido=false;		
		$ls_sql="SELECT subtot,totreten FROM sob_valuacion WHERE codemp='".$this->ls_codemp."' AND codcon='".$as_codcon."' AND codval='".$as_codval."';";
//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Valuacion MÉTODO->uf_obtener_datos_valuacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));	
			return false;		
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_subtot=$row["subtot"];
				$as_totreten=$row["totreten"];
				$neto_a_cobrar=($as_subtot - $as_totreten);
				//print $as_sccuenta;
			}
	
		}	
		return $neto_a_cobrar;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
//////////////

	
}
?>
