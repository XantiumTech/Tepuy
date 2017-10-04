<?php
class tepuy_sno_c_calcularnomina
{
	var $io_sql;
	var $io_mensajes;
	var $io_seguridad;
	var $io_fun_nomina;
	var $io_funciones;
	var $io_evaluador;
	var $io_prestamo;
	var $io_vacacion;
	var $io_sno;
	var $ls_codemp;
	var $ls_codnom;
	var $ls_conpronom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_sno_c_calcularnomina()
	{	
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_sno_c_calcularnomina
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		$this->io_seguridad= new tepuy_c_seguridad();
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("tepuy_sno_c_evaluador.php");
		$this->io_evaluador=new tepuy_sno_c_evaluador();
		require_once("tepuy_sno_c_prestamo.php");
		$this->io_prestamo=new tepuy_sno_c_prestamo();
		require_once("tepuy_sno_c_vacacion.php");
		$this->io_vacacion=new tepuy_sno_c_vacacion();
		require_once("tepuy_sno.php");
		$this->io_sno=new tepuy_sno();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$this->ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$this->ls_conpronom=$_SESSION["la_nomina"]["conpronom"];
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_totalpersonal(&$ai_nropro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_totalpersonal
		//		   Access: private
		//	    Arguments: ai_nropro  // Número de personas a procesar
		//	      Returns: lb_valido True si se ejecutó con éxito el select y false si hubo agún error
		//	  Description: Funcion que obtiene el total de personas a procesar
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_desincorporar=$this->io_sno->uf_select_config("SNO","NOMINA","DESINCORPORAR DE NOMINA","0","C");
		$ls_sql="SELECT count(codper) as total".
				"  FROM sno_personalnomina ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
		switch ($li_desincorporar)
		{
			case "0"; // No se Desincorpora de la nómina 
				$ls_sql=$ls_sql." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2') ";
				break;
	
			case "1"; // Se desincorpora de la nómina
				$ls_sql=$ls_sql." AND sno_personalnomina.staper='1' ";
				break;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_obtener_totalpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_nropro=number_format($row["total"],0);
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_resumenpago($as_peractnom,&$ai_totasi,&$ai_totded,&$ai_totapoemp,&$ai_totapopat,&$ai_totnom,&$ai_nropro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_resumenpago
		//		   Access: public (tepuy_sno_p_calcularnomina.php)
		//	    Arguments: as_peractnom  // período Actual de la nómina
		//				   ai_totasi  // Total de Asignaciones
		//				   ai_totded  // Total de Deducciones
		//				   ai_totapoemp  // Total de Aportes de Empleados
		//				   ai_totapopat  // Total de Aportes de Patron
		//				   ai_totnom  // Total de la Nómina
		//				   ai_nropro  // Número de personas a procesar
		//	      Returns: lb_valido True si se encontro ó False si no se encontró
		//	  Description: Funcion que obtiene el la suma de todo lo que se pago en la nómina 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT COALESCE(SUM(asires),0) AS asign, COALESCE(SUM(dedres),0) AS deduc, COALESCE(SUM(apoempres),0) AS apoemp, ".
				"		COALESCE(SUM(apopatres),0) AS apopat, COALESCE(SUM(monnetres),0) AS totnom ".
				"  FROM sno_resumen ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$as_peractnom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_obtener_resumenpago ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totasi=$this->io_fun_nomina->uf_formatonumerico($row["asign"]);
				$ai_totded=$this->io_fun_nomina->uf_formatonumerico(abs($row["deduc"]));
				$ai_totapoemp=$this->io_fun_nomina->uf_formatonumerico(abs($row["apoemp"]));
				$ai_totapopat=$this->io_fun_nomina->uf_formatonumerico(abs($row["apopat"]));
				$ai_totnom=$this->io_fun_nomina->uf_formatonumerico($row["totnom"]);
			}
			$this->io_sql->free_result($rs_data);		
			$lb_valido=$this->uf_obtener_totalpersonal($ai_nropro);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_existesalida()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_existesalida
		//		   Access: public (tepuy_sno_p_calcularnomina.php)
		//	      Returns: lb_valido True si existe alguna salida y false si no existe Salida
		//	  Description: Funcion que verifica si hay registros en salida
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT count(codper) as total".
				"  FROM sno_resumen ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=true;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_existesalida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				if($row["total"]>0)
				{
					$lb_valido=true;
				}
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesarnomina($aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesarnomina
		//		   Access: public (tepuy_sno_p_calcularnomina.php)
		//	    Arguments: aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True si se proceso correctamente ó False si hubo error 
		//	  Description: función que selecciona el personal y procesa el calculo de la nómina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if ($this->ls_conpronom=="1")
		{ 
		    $ai_totalper=0;
			$this->uf_obtener_totalpersonal($ai_totalper);
			$ai_totalperpro=0;
			$this->uf_obtener_totalpersonalproyecto($ai_totalperpro);
			if ($ai_totalperpro!=0) 
			{
			 		$ls_mensaje=" ";
					$li=0;					
					$this->uf_obtener_informacionpersonalpro($ls_mensaje,$li);
					if ($li>10)
					{
						$ls_mensaje='Existen '.$li.' Personas que no posee Proyectos Asociados en este Nómina \n';
						$this->io_mensajes->message($ls_mensaje);
						$lb_valido=false;
					}
					else
					{
						$ls_mensaje2='El siguiente Personal no posee Proyectos Asociados \n';
						$this->io_mensajes->message($ls_mensaje2.$ls_mensaje);
						$lb_valido=false;
					}				
			}
		}
	  if ($lb_valido)
	  { 
		$li_desincorporar=$this->io_sno->uf_select_config("SNO","NOMINA","DESINCORPORAR DE NOMINA","0","C");
		$lb_valido=true;
		$ls_sql="SELECT sno_personalnomina.codper ".
				"  FROM sno_personalnomina ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ";							
		switch ($li_desincorporar)
		{
			case "0"; // No se Desincorpora de la nómina 
				$ls_sql=$ls_sql." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2') ";
				break;
	
			case "1"; // Se desincorpora de la nómina
				$ls_sql=$ls_sql." AND sno_personalnomina.staper='1' ";
				break;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_procesarnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$this->io_sql->begin_transaction();
			$li_total_nomi=0;
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codper=$row["codper"];
				$lb_valido=$this->uf_calcularnomina($ls_codper,$li_total_nomi);
			}
			$this->io_sql->free_result($rs_data);	
			if($lb_valido)
			{
			   $lb_valido=$this->uf_delete_final_resumen();
			}
			if($lb_valido)
			{
		    	$lb_valido=$this->uf_update_periodos($aa_seguridad);
			}
			if($lb_valido)
			{
			   $lb_valido=$this->uf_generar_rep_vacaciones();
			}
			if($lb_valido)
			{
				$this->io_sql->commit();
				$this->io_mensajes->message("El cálculo de la nómina fue procesado.");
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_mensajes->message("Ocurrio un error al calcular la nómina.");
			}
		}
	}//fin del if (lb_valido)
	else
	{
		$this->io_mensajes->message("Ocurrio un error al calcular la nómina.");
	}	
		return $lb_valido;
}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_calcularnomina($as_codper,&$ad_totnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcularnomina
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//	               ad_totnom  // Total acumulado de la nómina
		//	      Returns: lb_valido  True si se calculó la nómina completa ó False si no se calculó completa
		//	  Description: Funcion que procesa el calculo de los conceptos para el personal dado
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$lb_valido=$this->io_evaluador->uf_config_session($as_codper);
		if ($lb_valido)
		{
			// procesa la nómina del personal
			$lb_valido=$this->uf_procesar_nomina_personal($as_codper,$ad_totnom);
		}
		if($lb_valido)
		{
			// procesa el personal de vacación
			$lb_valido=$this->io_vacacion->uf_calcular_vacacion($as_codper,$ad_totnom);
		}
		unset($_SESSION["la_personalnomina"]);
		unset($_SESSION["la_vacacionpersonal"]);
		unset($_SESSION["la_tablasueldo"]);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_nomina_personal($as_codper,&$ad_totnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_nomina_personal
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//	               ad_totnom  // Total acumulado de la nómina
		//	      Returns: lb_valido  True si se calculó la nómina completa ó False si no se calculó completa
		//	  Description: Funcion que procesa el calculo de los conceptos para el personal dado
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_asires=0;
		$ld_dedres=0;
		$ld_apoempres=0;
		$ld_apopatres=0;
		$ld_priquires=0;
		$ld_segquires=0;
		$ld_monnetres=0;
		$ld_totalneto=0;
		$lb_valido=true;
		$ld_sueper=$_SESSION["la_personalnomina"]["sueper"];
		$ld_horper=$_SESSION["la_personalnomina"]["horper"];
		$li_numhijper=$_SESSION["la_personalnomina"]["numhijper"];
		$lb_valido=$this->uf_insert_resumen($as_codper);
		if($lb_valido)
		{
			$lb_valido=$this->uf_evaluar_conceptopersonal($as_codper,$ld_asires,$ld_dedres,$ld_apoempres,$ld_apopatres,$ld_priquires,
			                                              $ld_segquires,$ld_monnetres,$ad_totnom);
		}
		if($lb_valido)
		{
		   	// comienzo el proceso de prestamos
			$lb_valido=$this->io_prestamo->uf_calcular_prestamo($as_codper,$ld_dedres,$ad_totnom,$ld_priquires,$ld_segquires);
		}
		$ld_totalneto=$ld_asires-($ld_dedres+$ld_apoempres);
		$li_adenom=$_SESSION["la_nomina"]["adenom"];
		$li_divcon=$_SESSION["la_nomina"]["divcon"];
		if($li_adenom==1)
		{
		    $ld_priquires=round(($ld_totalneto/2),2);
			$ld_segquires=$ld_totalneto-$ld_priquires;
		}
		else
		{
			if($li_divcon==0)
			{
				$ld_priquires=$ld_totalneto;
				$ld_segquires=0;
			}
			else
			{
				if(($ld_priquires+$ld_segquires)!=$ld_totalneto)
				{
					$ld_ajuste= $ld_totalneto - ($ld_priquires+$ld_segquires);
					$ld_segquires = $ld_segquires + $ld_ajuste;
				}
			}
		}
		//Verifico si las deducciones son mayor a las asignaciones 
		if($ld_asires<($ld_dedres+$ld_apoempres))
		{
		  	$ls_nomper=$_SESSION["la_personalnomina"]["nomper"];
		  	$ls_apeper=$_SESSION["la_personalnomina"]["apeper"];
		  	$ls_nombre=$ls_nomper." , ".$ls_apeper;
		  	$this->io_mensajes->message("Se ha detectado que la persona Código ".$as_codper." Nombre ".$ls_nombre."  posee Deducciones mayores a las Asignaciones.");
			$lb_valido=false;
		}
		if ($lb_valido)
		{
			$lb_valido=$this->uf_update_resumen_acumulado($as_codper,$ld_asires,$ld_dedres,$ld_apoempres,$ld_apopatres,$ld_priquires,
			                  $ld_segquires,$ld_totalneto);
		}
		$ld_sueproper=$this->uf_calcular_sueldo_promedio($as_codper);    
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_personalnomina($as_codper,$ld_sueproper);
		}
		return  $lb_valido; 
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_resumen($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_resumen
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//	      Returns: lb_valido  True si inserta correctamenta en la tabla  ó False si hubo error
		//	  Description: Funcion que inserta en la tabla resumen   
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_asires=0;
		$ld_dedres=0;
		$ld_apoempres=0;
		$ld_apopatres=0;
		$ld_priquires=0;
		$ld_segquires=0;
		$ld_monnetres=0;
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_resumen (codemp,codnom,codperi,codper,asires,dedres,apoempres,apopatres,priquires,segquires, ".
		        "monnetres) VALUES ('".$this->ls_codemp."','".$this->ls_codnom."','".$this->ls_peractnom."','".$as_codper."', ".
				"'".$ld_asires."','".$ld_dedres."','".$ld_apoempres."','".$ld_apopatres."','".$ld_priquires."','".$ld_segquires."',".
				"'".$ld_monnetres."') ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_insert_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
	   return $lb_valido;	
	 }
	//-----------------------------------------------------------------------------------------------------------------------------------		

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_evaluar_conceptopersonal($as_codper,&$ad_asires,&$ad_dedres,&$ad_apoempres,&$ad_apopatres,&$ad_priquires,&$ad_segquires,
	                                     &$ad_monnetres,&$ad_totnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_evaluar_conceptopersonal
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//                 ad_asires // asignación del resumen 
		//                 ad_dedres //  deducciones  del resumen 
		//                 ad_apoempres // aporte del empleado
		//                 ad_apopatres // aporte del patrón
		//                 ad_priquires // monto primera quincena
		//                 ad_segquires // monto segunda quincena
		//                 ad_monnetres // monto neto del período
		//                 ad_totnom  //   total de la nomina
		//	      Returns: lb_valido True si se evaluaron los conceptos ó False si hubo error
		//	  Description: Funcion que obtiene los conceptos por personal y los evalua
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_concepto.codconc ".
				"  FROM sno_conceptopersonal, sno_concepto ".
				" WHERE sno_conceptopersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_conceptopersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_conceptopersonal.codper='".$as_codper."' ".
				"   AND (sno_conceptopersonal.aplcon = 1  OR sno_concepto.glocon=1)".
				"   AND sno_conceptopersonal.codemp=sno_concepto.codemp ".
				"   AND sno_conceptopersonal.codnom=sno_concepto.codnom ".
				"   AND sno_conceptopersonal.codconc=sno_concepto.codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_evaluar_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=1;
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codconc=$row["codconc"];
				$lb_valido=$this->io_evaluador->uf_crear_conceptopersonal($as_codper,$ls_codconc);
				if($lb_valido)
				{
					$la_conceptopersonal=$_SESSION["la_conceptopersonal"];
					$ls_concon=$la_conceptopersonal["concon"];
					$ls_codconc=$la_conceptopersonal["codconc"];
					$ls_glocon=$la_conceptopersonal["glocon"];
					$ls_aplcon=$la_conceptopersonal["aplcon"];
					$ls_forcon=$la_conceptopersonal["forcon"];
					$ls_sigcon=$la_conceptopersonal["sigcon"];
					$ls_forpatcon=$la_conceptopersonal["forpatcon"];
					$ls_acuemp=$la_conceptopersonal["acuemp"];
					$ls_acupat=$la_conceptopersonal["acupat"];
					$ls_quirepcon=$la_conceptopersonal["quirepcon"];
					$ld_valmincon=$la_conceptopersonal["valmincon"];
					$ld_valmaxcon=$la_conceptopersonal["valmaxcon"];
					$lb_filtro=true;
					$lb_aplica=true;
					if (trim($ls_concon)!="")
					{
						$lb_filtro=false;
						$lb_valido=$this->io_evaluador->uf_evaluar($as_codper,$ls_concon,$lb_filtro);
					}				  
					if($ls_glocon==0)
					{
					    if($ls_aplcon==0)
						{
						 	$lb_aplica=false;
						}
					}
					if(($lb_aplica)&&($lb_filtro))
					{
						$lb_valido=$this->uf_calcular_personal($as_codper,$ls_codconc,$ld_valcon,$ls_forcon,$ld_valmincon,$ld_valmaxcon); 
						if($lb_valido)
						{
							if(($ls_sigcon=="A")||($ls_sigcon=="B"))
							{
							   $lb_valido=$this->uf_guardar_salida($as_codper,$ls_codconc,"A",$ld_valcon,$ls_acuemp,$ls_quirepcon);
							   if($lb_valido)
							   {
								 $ad_asires=$ad_asires + $ld_valcon;
								 $ad_totnom=$ad_totnom + $ld_valcon;    
							   }
							}
							if (($ls_sigcon=="D")||($ls_sigcon=="E"))
							{
							   $lb_valido=$this->uf_guardar_salida($as_codper,$ls_codconc,"D",-$ld_valcon,$ls_acuemp,$ls_quirepcon);
							   if($lb_valido)
							   {
								 $ad_dedres=$ad_dedres + $ld_valcon;
								 $ad_totnom=$ad_totnom - $ld_valcon;
							   }
							}
							if(($ls_sigcon=="P"))
							{
							   $lb_valido=$this->uf_guardar_salida($as_codper,$ls_codconc,"P1",-$ld_valcon,$ls_acuemp,$ls_quirepcon);
							   if($lb_valido)
							   {
								  $ad_totnom=$ad_totnom - $ld_valcon;
								  $ad_apoempres=$ad_apoempres + $ld_valcon;
							   }
							   $lb_ok=$this->uf_calcular_aporte($as_codper,$ls_codconc,$ls_forpatcon,$ld_valconapo,$ls_quirepcon);
							   if(!($lb_ok))
							   {
								  return false;
							   }
							   $lb_valido=$this->uf_guardar_salida($as_codper,$ls_codconc,"P2",-$ld_valconapo,$ls_acupat,$ls_quirepcon);
							   if($lb_valido)
							   {
								 $ad_apopatres=$ad_apopatres + $ld_valconapo;
							   }
							}
							if(($ls_sigcon=="R"))
							{
							   $lb_valido=$this->uf_guardar_salida($as_codper,$ls_codconc,$ls_sigcon,$ld_valcon,0,$ls_quirepcon);
							}
						}	
						if(($lb_valido)&&($_SESSION["la_nomina"]["divcon"]==1))
						{
							switch($ls_sigcon)
							{
								case "D":
									$ld_valcon=$ld_valcon*(-1);
									break;
								case "E":
									$ld_valcon=$ld_valcon*(-1);
									break;
								case "P":
									$ld_valcon=$ld_valcon*(-1);
									break;
								case "R":
									$ld_valcon=0;
									break;
							}
							switch($ls_quirepcon)
							{
								case "1": // Primera Quincena
									$ad_priquires=$ad_priquires+$ld_valcon;
									break;
								case "2": // Segunda Quincena
									$ad_segquires=$ad_segquires+$ld_valcon;
									break;
								case "3": // Ambas Quincena
									$ad_priquires=$ad_priquires+round($ld_valcon/2,2);
									$ad_segquires=$ad_segquires+round($ld_valcon/2,2);
									break;
								case "-": // Ambas Quincena
									$ad_priquires=$ad_priquires+round($ld_valcon/2,2);
									$ad_segquires=$ad_segquires+round($ld_valcon/2,2);
									break;
							}
						}				
					}//if($lb_aplica)
			   }//if($lb_ok)
			   unset($_SESSION["la_concetopersonal"]);
		  }//while
		}//else	
		$this->io_sql->free_result($rs_data);	
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_calcular_personal($as_codper,$as_codconc,&$ad_valcon,$as_forcon,$ad_valmincon,$ad_valmaxcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_personal
		//		   Access: private
		//		Arguments: as_codper // código de personal
		//				   as_codcon // codigo del concepto 
		//                 as_forcon // formula del concepto
		//                 as_valcon //  valor del concepto  
		//	      Returns: lb_valido True si se evaluaron los conceptos ó False si hubo error
		//	  Description: Funcion que calcula los conceptos por personal y los evalua
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->io_evaluador->uf_evaluar($as_codper,$as_forcon,$ad_valcon);
		if($lb_valido)
		{
 	  		if($ad_valmincon>0)//verifico el minimo del concepto 
			{
				if($ad_valcon<$ld_valmincon)
				{
					$ad_valcon=$ad_valmincon;
				}
			}
			if($ad_valmaxcon>0)//verifico el maximo del concepto
			{
				if($ad_valcon>$ld_valmaxcon)
				{
					$ad_valcon=$ad_valmaxcon;
				}
			}
		}
  	  	return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_salida($as_codper,$as_codconc,$as_tipsal,$ad_valsal,$ad_monacusal,$as_quirepcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_salida
		//		   Access: private
		//		Arguments: as_codper // código de personal
		//				   as_codcon // codigo del concepto 
		//                 as_tipsal // tipo de la salida
		//                 ad_valsal //  valor de la salida 
		//                 ad_monacusal // monto acumulado de la salida         
		//	      Returns: lb_valido True si se inserto correctamente ó False si hubo error
		//	  Description: Funcion que inserta en la tabla sno_salida
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_salsal=0;
		$lb_valido=true;
		$li_priquisal=0;
		$li_segquisal=0;
		switch($as_quirepcon)
		{
			case '1':
				$li_priquisal=$ad_valsal;
				break;
			case '2':
				$li_segquisal=$ad_valsal;
				break;
			case '3':
				$li_priquisal=round($ad_valsal/2,2);
				$li_segquisal=round($ad_valsal/2,2);
				if(($li_priquisal+$li_segquisal)!=$ad_valsal)
				{
					$ld_ajuste= $ad_valsal - ($li_priquisal+$li_segquisal);
					$li_segquisal = $li_segquisal + $ld_ajuste;
				}
				break;
		}
		$ls_sql="INSERT INTO sno_salida (codemp,codnom,codperi,codper,codconc,tipsal,valsal,monacusal,salsal,priquisal,segquisal) ". 
				"VALUES ('".$this->ls_codemp."','".$this->ls_codnom."','".$this->ls_peractnom."','".$as_codper."', ".
				"'".$as_codconc."','".$as_tipsal."',".$ad_valsal.",".$ad_monacusal.",".$ld_salsal.",".$li_priquisal.",".$li_segquisal.") ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_guardar_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido; 
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_calcular_aporte($as_codper,$as_codcon,$as_forcon,&$ad_valcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_aporte
		//		   Access: private
		//		Arguments: as_codper // código de personal
		//				   as_codcon // codigo del concepto 
		//                 as_forcon // formula del concepto
		//                 as_valcon //  valor del concepto  
		//	      Returns: lb_valido True si se evaluaron los conceptos ó False si hubo error
		//	  Description: Funcion que calcula los conceptos por personal y los evalua
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=$this->io_evaluador->uf_evaluar($as_codper,$as_forcon,$ad_valcon);
		if($lb_valido)
		{
			$la_conceptopersonal=$_SESSION["la_conceptopersonal"];
			$ld_valminpatcon=$la_conceptopersonal["valminpatcon"];
			$ld_valmaxpatcon=$la_conceptopersonal["valmaxpatcon"];
			if($ld_valminpatcon>0)//verifico el minimo del concepto
			{
				if($ad_valcon<$ld_valminpatcon)
				{
					$ad_valcon=$ld_valminpatcon;
				}
			}
			if($ld_valmaxpatcon>0)//verifico el maximo del concepto
			{
				if($ad_valcon>$ld_valmaxpatcon)
				{
					$ad_valcon=$ld_valmaxpatcon;
				}
			}
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_resumen_acumulado($as_codper,$ad_asires,$ad_dedres,$ad_apoempres,$ad_apopatres,$ad_priquires,$ad_segquires,
	                                     $ad_monnetres)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_resumen_acumulado
		//		   Access: private
		//		Arguments: as_codper // código de personal
		//                 ad_asires //  asignación del resumen    
		//                 ad_dedres  // deduccion del resumen 
		//                 ad_apoempres  // aporte del empleado 
		//                 ad_apopatres  //  aporte del patrón   
		//                 ad_priquires  // monto primera quincena
		//                 ad_segquires //   monto segunda quincena
		//                 ad_monnetres  //  monto neto del período  
		//	      Returns: lb_valido true si realizo el update correctamente   false en caso contrario
		//	  Description: Funcion que actualiza en la tabla de sno_resumen
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_resumen ".
		        "   SET asires=".$ad_asires.", ".
				"       dedres=".$ad_dedres.", ".
		        "       apoempres=".$ad_apoempres.", ".
				"       apopatres=".$ad_apopatres.", ".
				"       priquires=".$ad_priquires.", ".
				"       segquires=".$ad_segquires.", ".
				"       monnetres=".$ad_monnetres." ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ".
				"   AND codper='".$as_codper."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_update_resumen_acumulado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
	   return $lb_valido;	
	 }
    //-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_calcular_sueldo_promedio($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_sueldo_promedio
		//		   Access: private
		//		Arguments: as_codper // código de personal
		//	      Returns: ld_suelprom valor del sueldo promedio del personal
		//	  Description: Funcion que calcula el sueldo promedio del personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_sueproper=0;
	    $ls_sql="SELECT COALESCE((SUM(sueper)/COUNT(codper)),0) AS sueldo ".
                "  FROM sno_hpersonalnomina ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_calcular_sueldo_promedio ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_sueproper=number_format($row["sueldo"],2,".","");
			}
		}
		return $ld_sueproper;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_personalnomina($as_codper,$ad_sueproper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_personalnomina
		//		   Access: private
		//		Arguments: as_codper // código de personal
		//                 ad_sueproper //  sueldo promedio del personal    
		//	      Returns: lb_valido true si realizo el update correctamente   false en caso contrario
		//	  Description: Funcion que actualiza en la tabla  sno_personalnomina el sueldo promedio y el sueldo integral del personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_sueldointegral=$_SESSION["la_personalnomina"]["sueldointegral"];
		$ls_sql="UPDATE sno_personalnomina ".
		        "   SET sueintper=".$ld_sueldointegral.", ".
				"       sueproper=".$ad_sueproper." ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_update_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;	
	 }
    //-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_final_resumen()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_final_resumen
		//		   Access: private
		//	      Returns: lb_valido true si realizo el delete correctamente   false en caso contrario
		//	  Description: Funcion que elimina en la tabla  sno_resumen aquellos registros que no estén en salida
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		    
		$lb_valido=true;
		$ls_sql="DELETE FROM sno_resumen ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"	AND codnom='".$this->ls_codnom."' ".
				"	AND codperi='".$this->ls_peractnom."' ".
				"   AND codnom NOT IN (SELECT codnom FROM sno_salida ".
                "		      			WHERE codemp='".$this->ls_codemp."' ".
				"						  AND codnom='".$this->ls_codnom."' ".
				"						  AND codperi='".$this->ls_peractnom."') ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
        {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_delete_final_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
	   	}
		return $lb_valido;
    }
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_periodos($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_periodos
		//		   Access: private
		//		Arguments: aa_seguridad // arreglo de las variables de seguridad
		//	      Returns: lb_valido true si realizo el update correctamente   false en caso contrario
		//	  Description: Funcion que actualiza en la tabla sno_periodo el monto total
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_totper=0;
		$lb_valido=true;
		$ls_sql="SELECT COALESCE(SUM(asires),0) AS asign, COALESCE(SUM(dedres),0) AS deduc, COALESCE(SUM(apoempres),0) AS apoemp, ".
				"		COALESCE(SUM(apopatres),0) AS apopat, COALESCE(SUM(monnetres),0) AS totnom ".
				"  FROM sno_resumen ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_update_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_totper=number_format($row["totnom"],2,".","");
				$li_totasi=$this->io_fun_nomina->uf_formatonumerico($row["asign"]);
				$li_totded=$this->io_fun_nomina->uf_formatonumerico($row["deduc"]);
				$li_totapoemp=$this->io_fun_nomina->uf_formatonumerico($row["apoemp"]);
				$li_totapopat=$this->io_fun_nomina->uf_formatonumerico($row["apopat"]);
				$li_totnom=$this->io_fun_nomina->uf_formatonumerico($row["totnom"]);
			}			
			$ls_sql="UPDATE sno_periodo ".
					"   SET totper=".$ld_totper." ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND codperi='".$this->ls_peractnom."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
		   	{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_update_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		   	}
		   	else
		   	{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion="Calculó la nómina ".$this->ls_codnom." para el período ".$this->ls_peractnom." ".
								"Total a Asignación ".$li_totasi.", Total Deducción ".$li_totded.", ".
								"Total a Aporte Empleado ".$li_totapoemp.", Total Aporte Patrón ".$li_totapopat.", ".
								"Total Nómina ".$li_totnom;
				$lb_valido=$this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido==false)
				{
					$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_update_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}	
		   }
	   }
	   return $lb_valido;	
	 }
    //-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_generar_rep_vacaciones()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_generar_rep_vacaciones
		//		   Access: private
		//	      Returns: lb_valido True si se genero el reporte de  vacaciones  ó False si no se genero correctamente
		//	  Description: Funcion que devuelve de la tabla tepuy config los valores y el codigo de la vacaciones
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_ok=false;
		$li_vac_reportar=trim($this->io_sno->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$ls_vac_codconvac=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		if(($li_vac_reportar==1)&&($ls_vac_codconvac!=""))
		{
			$lb_valido=$this->uf_delete_salidas_vac($ls_vac_codconvac); 
			$lb_ok=true;
		}
		if(($lb_valido)&&($lb_ok))
		{
			$ld_totneto=0;
			$ls_staper=2; // personal de vacaciones 
			$ls_sql="SELECT sno_personalnomina.sueper, sno_personalnomina.horper, sno_personalnomina.codper,sno_personal.cedper, ".
				    "       sno_personal.nomper,sno_personal.apeper,sno_personal.numhijper ".
				    "  FROM sno_personalnomina , sno_personal ".
				    " WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				    "   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				    "   AND sno_personalnomina.staper='".$ls_staper."' ".
					"   AND sno_personalnomina.codemp=sno_personal.codemp ".
				    "   AND sno_personalnomina.codper=sno_personal.codper ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_generar_rep_vacaciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{	   
					$ls_codper=$row["codper"];
					$li_cuantos=$this->uf_count_resumen($ls_codper);
					if($li_cuantos==0)
					{
						$lb_valido=$this->uf_insert_resumen_vac($ls_codper,$ld_totneto);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_guardar_salida($ls_codper,$ls_vac_codconvac,"R",$ld_totneto,0,3);
					}
				} 
			}	   
	 	}
		return $lb_valido;
	}
   //-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_vac_config(&$as_codvac)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_vac_config
		//		   Access: private
		//		Arguments: as_codvac   // codigo de vacaciones 
		//	      Returns: lb_valido True si se evaluo la vacaciones  ó False si no se evaluo en el tepuy_config
		//	  Description: Funcion que devuelve de la tabla tepuy config los valores y el codigo de la vacaciones
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_valor=$this->io_sno->uf_select_config("SNO", "CONFIG", "MOSTRAR VACACION", "0", "I");
        $as_codvac=$this->io_sno->uf_select_config("SNO", "CONFIG", "COD CONCEPTO VACACION", "", "C");
		if(($ls_valor==1)&&($as_codvac!=""))
		{
			$lb_valido=true;
		}
		return  $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
    
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_salidas_vac($as_codvac)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_salidas_vac
		//		   Access: private
		//		Arguments: as_codvac   // codigo de vacaciones 
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina las salidas para las generar el reporte de vacaciones
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_salida ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ".
				"   AND codconc='".$as_codvac."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_delete_salidas_vac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
    }
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_count_resumen($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_count_resumen
		//		   Access: private
		//		Arguments: as_codper   // código de personal
		//	      Returns: li_cuantos si existe el registro en sno_salida
		//	  Description: Funcion que devuelve si existen  en la tabla resumen  
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	    $li_cuantos=0;
		$ls_sql="SELECT count(codper) AS cuantos ".
                "  FROM sno_resumen ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ".
				"   AND codper='".$as_codper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_count_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $li_cuantos=$row["cuantos"];
			}
		}
		return $li_cuantos;		  
	 }
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_resumen_vac($as_codper,$ad_totneto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_resumen_vac
		//		   Access: private
		//		Arguments: as_codper   // código de personal
		//                 ad_totneto //  total neto  
		//	      Returns: lb_valido True si inserta correctamenta en la tabla  ó False si hubo error
		//	  Description: Funcion que inserta en la tabla resumen  para la generacion de reportes de vacaciones 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_resumen (codemp,codnom,codperi,codper,asires,dedres,apoempres, apopatres, priquires, segquires, ".
				" monnetres) VALUES ('".$this->ls_codemp."', '".$this->ls_codnom."','".$this->ls_peractnom."','".$as_codper."', ".
				"'".$ad_totneto."','".$ad_totneto."','".$ad_totneto."','".$ad_totneto."','".$ad_totneto."','".$ad_totneto."', ".
				"'".$ad_totneto."') ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_insert_resumen_vac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;	
	 }
	//-----------------------------------------------------------------------------------------------------------------------------------		
	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_totalpersonalproyecto(&$as_totalperpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_totalpersonalproyecto
		//		   Access: private
		//	    Arguments: 
		//	      Returns: lb_valido True si se ejecutó con éxito el select y false si hubo agún error
		//	  Description: Funcion que obtiene el total de personal con proyecto
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 27/08/2008								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$li_desincorporar=$this->io_sno->uf_select_config("SNO","NOMINA","DESINCORPORAR DE NOMINA","0","C");		
		$ls_sql=" SELECT count(sno_personalnomina.codper) as contar". 
                "  FROM sno_personalnomina, sno_personal                                     ".
                " WHERE sno_personalnomina.codemp='".$this->ls_codemp."'                     ". 
                "   AND sno_personalnomina.codnom='".$this->ls_codnom."'                     ".
				"   AND sno_personal.codemp=sno_personalnomina.codemp                        ".
				"   AND sno_personal.codper=sno_personalnomina.codper                        ".
                "   AND sno_personalnomina.codper not in                                     ".
				"       (SELECT sno_proyectopersonal.codper FROM sno_proyectopersonal        ".
				"                                            WHERE sno_proyectopersonal.codemp='".$this->ls_codemp."' ".
				"											AND sno_proyectopersonal.codnom='".$this->ls_codnom."')   "; 
				switch ($li_desincorporar)
				{
					case "0"; // No se Desincorpora de la nómina 
						$ls_sql=$ls_sql." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2') ";
						break;
			
					case "1"; // Se desincorpora de la nómina
						$ls_sql=$ls_sql." AND sno_personalnomina.staper='1' ";
						break;
				} 				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_obtener_totalpersonalproyecto ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_totalperpro=$row["contar"];
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// fin de uf_obtener_totalpersonalproyecto
//-------------------------------------------------------------------------------------------------------------------------------------
 //-------------------------------------------------------------------------------------------------------------------------------------
    function uf_obtener_informacionpersonalpro(&$as_mensaje,&$li)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_informacionpersonalpro
		//		   Access: private
		//	    Arguments: 
		//	      Returns: lb_valido True si se ejecutó con éxito el select y false si hubo agún error
		//	  Description: Funcion que obtiene el total de personal con proyecto
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 27/08/2008								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_desincorporar=$this->io_sno->uf_select_config("SNO","NOMINA","DESINCORPORAR DE NOMINA","0","C");		
		$ls_sql=" SELECT sno_personalnomina.codper, sno_personal.nomper, sno_personal.apeper ". 
                "  FROM sno_personalnomina, sno_personal                                     ".
                " WHERE sno_personalnomina.codemp='".$this->ls_codemp."'                     ". 
                "   AND sno_personalnomina.codnom='".$this->ls_codnom."'                     ".
				"   AND sno_personal.codemp=sno_personalnomina.codemp                        ".
				"   AND sno_personal.codper=sno_personalnomina.codper                        ".
                "   AND sno_personalnomina.codper not in                                     ".
				"       (SELECT sno_proyectopersonal.codper FROM sno_proyectopersonal        ".
				"                                            WHERE sno_proyectopersonal.codemp='".$this->ls_codemp."' ".
				"											AND sno_proyectopersonal.codnom='".$this->ls_codnom."')   ";
				
		switch ($li_desincorporar)
		{
			case "0"; // No se Desincorpora de la nómina 
				$ls_sql=$ls_sql." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2') ";
				break;
	
			case "1"; // Se desincorpora de la nómina
				$ls_sql=$ls_sql." AND sno_personalnomina.staper='1' ";
				break;
		}				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_obtener_informacionpersonalpro ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li++;
				$ls_codper=$row["codper"];
				$ls_nomper=$row["nomper"];
				$ls_apeper=$row["apeper"];
				$as_mensaje = $as_mensaje.' Código: '.$ls_codper.'  -  '.$ls_apeper.', '.$ls_nomper.'\n';
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// fin de uf_obtener_informacionpersonalpro
//-----------------------------------------------------------------------------------------------------------------------------------

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>