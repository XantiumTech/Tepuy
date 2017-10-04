<?php
class tepuy_sno_c_evaluador
{
	var	$io_sql;
	var	$io_mensajes;
	var	$io_funciones;
	var	$io_sno;
	var	$io_eval;
	var	$io_familiar;
	var	$io_isr;
	var	$io_concepto;
	var	$io_constante;
	var	$io_primaconcepto;
	var $io_feriado;
	var $io_permiso;
	var $io_cestaticket;
	var	$ls_codemp;
	var $ls_codnom;
	var $io_fecha;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_sno_c_evaluador()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_sno_c_evaluador
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("tepuy_sno.php");
		$this->io_sno=new tepuy_sno();
		require_once("../shared/class_folder/evaluate_formula.php");
		$this->io_eval=new evaluate_formula();
		require_once("tepuy_snorh_c_familiar.php");
		$this->io_familiar=new tepuy_snorh_c_familiar();
		require_once("tepuy_snorh_c_isr.php");
		$this->io_isr=new tepuy_snorh_c_isr();
		require_once("tepuy_sno_c_concepto.php");
		$this->io_concepto=new tepuy_sno_c_concepto();
		require_once("tepuy_sno_c_constantes.php");
		$this->io_constante=new tepuy_sno_c_constantes();
		require_once("tepuy_sno_c_primaconcepto.php");		
		$this->io_primaconcepto=new tepuy_sno_c_primaconcepto();
		require_once("tepuy_snorh_c_diaferiado.php");		
		$this->io_feriado=new tepuy_snorh_c_diaferiado();
		require_once("tepuy_snorh_c_permiso.php");		
		$this->io_permiso=new tepuy_snorh_c_permiso();
		require_once("tepuy_snorh_c_ct_met.php");		
		$this->io_cestaticket=new tepuy_snorh_c_ct_met();
		require_once("../srh/class_folder/dao/tepuy_srh_c_tipodeduccion.php");
		$this->io_tipodeduccion=new tepuy_srh_c_tipodeduccion("../");	
		require_once("../shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if(array_key_exists("la_nomina",$_SESSION))
		{
        	$this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		}
		else
		{
			$this->ls_codnom="0000";
		}
	}// end function tepuy_sno_c_evaluador
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_config_session($as_codper)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_config_session
		//		   Access: public 
		//	    Arguments: as_codper // código de personal
		//	      Returns: lb_valido True si se crearon las variable sesion ó False si no se crearon
		//	  Description: función que dado el código de personal y el código del concetpo crea las variables session necesarias
		//				   para el calculo del personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($lb_valido)
		{
			$lb_valido=$this->uf_crear_tablasueldo($as_codper);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_crear_personalnomina($as_codper);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_crear_vacacionpersonal($as_codper);
		}
		return $lb_valido;
	}// end function uf_config_session
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_crear_personalnomina($as_codper)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_crear_personalnomina
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//	      Returns: lb_valido True si se creo la variable sesion ó False si no se creo
		//	  Description: función que dado el código de personal crea una variable session con todos los datos
		//				   de personal nomina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_personalnomina.codper, sno_personalnomina.sueper, sno_personalnomina.sueproper, sno_personalnomina.horper, ".
				"  		sno_personalnomina.staper, sno_personalnomina.fecculcontr, sno_personal.nivacaper, ".
				"  		sno_personal.fecingper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.sexper, ".
				"  		sno_personal.numhijper, sno_personal.anoservpreper, sno_personal.fecnacper, sno_personal.fecingadmpubper, ".
				"		sno_personalnomina.codtabvac, sno_personal.cajahoper, sno_personalnomina.codded, sno_personalnomina.codtipper,".
				"		sno_personalnomina.codcladoc, sno_personalnomina.codescdoc, ".
				"		(SELECT sno_fideicomiso.capantcom  ".
				"          FROM sno_fideicomiso ".
				"         WHERE sno_fideicomiso.codemp = sno_personal.codemp ".
				"           AND sno_fideicomiso.codper = sno_personal.codper) AS capantcom,  ".
				"		(SELECT sno_clasificacionobrero.suemin  ".
				"          FROM sno_clasificacionobrero ".
				"         WHERE sno_clasificacionobrero.codemp = sno_personalnomina.codemp ".
				"           AND sno_clasificacionobrero.grado = sno_personalnomina.grado) AS suemingra  ".
				"  FROM sno_personalnomina, sno_personal ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_personalnomina.codper='".$as_codper."' ".
				"   AND sno_personalnomina.codemp=sno_personal.codemp ".
				"   AND sno_personalnomina.codper=sno_personal.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_crear_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$la_personalnomina=$row;   
				$_SESSION["la_personalnomina"]=$la_personalnomina;
				$_SESSION["la_personalnomina"]["fecculcontr"]=$this->io_funciones->uf_formatovalidofecha($_SESSION["la_personalnomina"]["fecculcontr"]);
				$_SESSION["la_personalnomina"]["fecingper"]=$this->io_funciones->uf_formatovalidofecha($_SESSION["la_personalnomina"]["fecingper"]);
				$_SESSION["la_personalnomina"]["fecnacper"]=$this->io_funciones->uf_formatovalidofecha($_SESSION["la_personalnomina"]["fecnacper"]);
				$_SESSION["la_personalnomina"]["fecingadmpubper"]=$this->io_funciones->uf_formatovalidofecha($_SESSION["la_personalnomina"]["fecingadmpubper"]);
				$_SESSION["la_personalnomina"]["mettabvac"]=$this->io_sno->uf_select_config("SNO","CONFIG","METODO_VACACIONES","0","C");
				$ai_sueldointegral=0;
				$ai_totalarc=0;
				$lb_valido=$this->uf_obtener_sueldointegral($as_codper,$ai_sueldointegral);
				if($lb_valido)
				{
					$_SESSION["la_personalnomina"]["sueldointegral"]=$ai_sueldointegral;
				}
				else
				{
					$_SESSION["la_personalnomina"]["sueldointegral"]=0;
				}
				$lb_valido=$this->uf_obtener_montoarc($as_codper,$ai_totalarc);
				if($lb_valido)
				{
					$_SESSION["la_personalnomina"]["totalarc"]=$ai_totalarc;
				}
				else
				{
					$_SESSION["la_personalnomina"]["totalarc"]=0;
				}
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_crear_personalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_sueldointegral($as_codper,&$ai_sueldointegral)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_sueldointegral
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//	      Returns: lb_valido True si se creo la variable sesion ó False si no se creo
		//	  Description: función que dado el código de personal obtiene la suma de todos los 
		//				   conceptos que pertenecen al sueldo integral
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_sueldointegral=0;
		$ls_sql="SELECT sno_concepto.codconc ".
				"  FROM sno_conceptopersonal, sno_concepto ".
				" WHERE sno_conceptopersonal.codemp='".$this->ls_codemp."'".
				"   AND sno_conceptopersonal.codnom='".$this->ls_codnom."'".
				"   AND sno_conceptopersonal.codper='".$as_codper."'".
				"   AND (sno_concepto.sigcon='A' OR sno_concepto.sigcon='R')".
				"   AND sno_concepto.sueintcon=1".
				"   AND sno_conceptopersonal.codemp=sno_concepto.codemp".
				"   AND sno_conceptopersonal.codnom=sno_concepto.codnom".
				"   AND sno_conceptopersonal.codconc=sno_concepto.codconc";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_obtener_sueldointegral ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codconc=$row["codconc"];
				$lb_valido=$this->uf_crear_conceptopersonal($as_codper,$ls_codconc);
				if($lb_valido)
				{
					$li_sueldo=0;
					$li_glocon=$_SESSION["la_conceptopersonal"]["glocon"];
					$li_aplcon=$_SESSION["la_conceptopersonal"]["aplcon"];
					$ls_concon=$_SESSION["la_conceptopersonal"]["concon"];
					$ls_forcon=$_SESSION["la_conceptopersonal"]["forcon"];
					$li_valmincon=$_SESSION["la_conceptopersonal"]["valmincon"];
					$li_valmaxcon=$_SESSION["la_conceptopersonal"]["valmaxcon"];
					if($li_glocon==1)// si el concepto es global
					{
						if(!(trim($ls_concon)==""))// si tiene condición
						{
							$lb_filtro=false;
							$lb_valido=$this->uf_evaluar($as_codper,$ls_concon,$lb_filtro);
							if(($lb_filtro)&&($lb_valido)) // si la condición es válida
							{
								$lb_valido=$this->uf_evaluar($as_codper,$ls_forcon,$li_sueldo);
							}
						}
						else
						{
							$lb_valido=$this->uf_evaluar($as_codper,$ls_forcon,$li_sueldo);					
						}
					}
					else
					{
						if($li_aplcon==1)// si se aplica el concepto
						{
							if(!(trim($ls_concon)==""))// si tiene condición
							{
								$lb_filtro=false;
								$lb_valido=$this->uf_evaluar($as_codper,$ls_concon,$lb_filtro);
								if(($lb_filtro)&&($lb_valido)) // si la condición es válida
								{
									$lb_valido=$this->uf_evaluar($as_codper,$ls_forcon,$li_sueldo);
								}
							}
							else
							{
								$lb_valido=$this->uf_evaluar($as_codper,$ls_forcon,$li_sueldo);					
							}
						}
					}
					if($li_valmincon>0)
					{
						if($li_sueldo<$li_valmincon)
						{
							$li_sueldo=$li_valmincon;
						}
					}
					if($li_valmaxcon>0)
					{
						if($li_sueldo>$li_valmaxcon)
						{
							$li_sueldo=$li_valmaxcon;
						}
					}
					$ai_sueldointegral=$ai_sueldointegral+$li_sueldo;
					unset($_SESSION["la_conceptopersonal"]);
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_obtener_sueldointegral
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_montoarc($as_codper,&$ai_totalarc)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_montoarc
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//	      Returns: lb_valido True si se creo la variable sesion ó False si no se creo
		//	  Description: función que dado el código de personal obtiene la suma de todos los 
		//				   conceptos que pertenecen al arc
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 18/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_totalarc=0;
		$ls_sql="SELECT sno_concepto.codconc ".
				"  FROM sno_conceptopersonal, sno_concepto ".
				" WHERE sno_conceptopersonal.codemp='".$this->ls_codemp."'".
				"   AND sno_conceptopersonal.codnom='".$this->ls_codnom."'".
				"   AND sno_conceptopersonal.codper='".$as_codper."'".
				"   AND sno_concepto.sigcon='A'".
				"   AND sno_concepto.aplarccon=1".
				"   AND sno_conceptopersonal.codemp=sno_concepto.codemp".
				"   AND sno_conceptopersonal.codnom=sno_concepto.codnom".
				"   AND sno_conceptopersonal.codconc=sno_concepto.codconc";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_obtener_montoarc ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codconc=$row["codconc"];
				$lb_valido=$this->uf_crear_conceptopersonal($as_codper,$ls_codconc);
				if($lb_valido)
				{
					$li_sueldo=0;
					$li_glocon=$_SESSION["la_conceptopersonal"]["glocon"];
					$li_aplcon=$_SESSION["la_conceptopersonal"]["aplcon"];
					$ls_concon=$_SESSION["la_conceptopersonal"]["concon"];
					$ls_forcon=$_SESSION["la_conceptopersonal"]["forcon"];
					$li_valmincon=$_SESSION["la_conceptopersonal"]["valmincon"];
					$li_valmaxcon=$_SESSION["la_conceptopersonal"]["valmaxcon"];
					if($li_glocon==1)// si el concepto es global
					{
						if(!(trim($ls_concon)==""))// si tiene condición
						{
							$lb_filtro=false;
							$lb_valido=$this->uf_evaluar($as_codper,$ls_concon,$lb_filtro);
							if(($lb_filtro)&&($lb_valido)) // si la condición es válida
							{
								$lb_valido=$this->uf_evaluar($as_codper,$ls_forcon,$li_sueldo);
							}
						}
						else
						{
							$lb_valido=$this->uf_evaluar($as_codper,$ls_forcon,$li_sueldo);					
						}
					}
					else
					{
						if($li_aplcon==1)// si se aplica el concepto
						{
							if(!(trim($ls_concon)==""))// si tiene condición
							{
								$lb_filtro=false;
								$lb_valido=$this->uf_evaluar($as_codper,$ls_concon,$lb_filtro);
								if(($lb_filtro)&&($lb_valido)) // si la condición es válida
								{
									$lb_valido=$this->uf_evaluar($as_codper,$ls_forcon,$li_sueldo);
								}
							}
							else
							{
								$lb_valido=$this->uf_evaluar($as_codper,$ls_forcon,$li_sueldo);					
							}
						}
					}
					if($li_valmincon>0)
					{
						if($li_sueldo<$li_valmincon)
						{
							$li_sueldo=$li_valmincon;
						}
					}
					if($li_valmaxcon>0)
					{
						if($li_sueldo>$li_valmaxcon)
						{
							$li_sueldo=$li_valmaxcon;
						}
					}
					$ai_totalarc=$ai_totalarc+$li_sueldo;
					unset($_SESSION["la_conceptopersonal"]);
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_obtener_montoarc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_crear_vacacionpersonal($as_codper)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_crear_vacacionpersonal
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//	      Returns: lb_valido True si se creo la variable sesion ó False si no se creo
		//	  Description: función que dado el código de personal crea una variable session con todos los datos
		//				   de vacación personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_metodovacaciones=trim($_SESSION["la_personalnomina"]["mettabvac"]);
		switch ($ls_metodovacaciones)
		{
			case "1": //METODO #0
				$ld_desde_s=$this->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
				$ld_desde_s=$this->io_sno->uf_suma_fechas($ld_desde_s,1);
				$ld_desde_s=$this->io_funciones->uf_convertirdatetobd($ld_desde_s);	
				switch($_SESSION["la_nomina"]["tippernom"])
				{
					case "0": // Nóminas Semanales
						$li_dias=7;
						break;
					case "1": // Nóminas Quincenales
						$li_dias=15;
						break;
					case "2": // Nóminas Mensuales
						$li_dias=30;
						break;
					case "3": // Nóminas Anuales
						$li_dias=365;
						break;
				}
				$ld_hasta_s=$this->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
				$ld_hasta_s=$this->io_sno->uf_suma_fechas($ld_hasta_s,$li_dias);
				$ls_dia=substr($ld_hasta_s,0,2);
				$ls_mes=substr($ld_hasta_s,3,2);
				$ls_ano=substr($ld_hasta_s,6,4);
				while(checkdate($ls_mes,$ls_dia,$ls_ano)==false)
				{ 
				   $ls_dia=$ls_dia-1; 
				   break;
				} 
				$ld_hasta_s=$ls_dia."/".$ls_mes."/".$ls_ano;
				$ld_hasta_s=$this->io_funciones->uf_convertirdatetobd($ld_hasta_s);
				$ld_desde_r=$_SESSION["la_nomina"]["fecdesper"];
				$ld_hasta_r=$this->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
				$ld_hasta_r=$this->io_sno->uf_suma_fechas($ld_hasta_r,1);
				$ld_hasta_r=$this->io_funciones->uf_convertirdatetobd($ld_hasta_r);
				
				$ls_sql="SELECT codvac, sueintbonvac, sueintvac, fecdisvac, fecreivac, diavac, diabonvac, diaadibon, diaadivac, ".
						"		diafer, sabdom, quisalvac, quireivac ".
						"  FROM sno_vacacpersonal ".
						" WHERE codper='".$as_codper."' ".
						"   AND ((stavac='2' AND (fecdisvac between '".$ld_desde_s."' AND '".$ld_hasta_s."'))".
						"    OR ( stavac='3' AND (fecreivac between '".$ld_desde_s."' AND '".$ld_hasta_s."')))";
				break;
		}
		if($ls_metodovacaciones!="0")
		{
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data==false)
			{
				$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_crear_vacacionpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$la_vacacionpersonal=$row;   
					$_SESSION["la_vacacionpersonal"]=$la_vacacionpersonal;
					$ld_fecdisvac=$_SESSION["la_vacacionpersonal"]["fecdisvac"];
					$ld_fecreivac=$_SESSION["la_vacacionpersonal"]["fecreivac"];
					$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
					$_SESSION["la_vacacionpersonal"]["nrolunes_s"]=$this->io_sno->uf_nro_lunes($ld_fecdisvac,$ld_fecreivac);
					$_SESSION["la_vacacionpersonal"]["nrolunes_r"]=$this->io_sno->uf_nro_lunes($ld_fecreivac,$ld_fechasper);
					$_SESSION["la_vacacionpersonal"]["primera_quincena"]=false;
					$_SESSION["la_vacacionpersonal"]["segunda_quincena"]=false;
				}
				else
				{
					$_SESSION["la_vacacionpersonal"]["codvac"]=0;
					$_SESSION["la_vacacionpersonal"]["sueintbonvac"]=0;
					$_SESSION["la_vacacionpersonal"]["sueintvac"]=0;
					$_SESSION["la_vacacionpersonal"]["fecdisvac"]="1900-01-01";
					$_SESSION["la_vacacionpersonal"]["fecreivac"]="1900-01-01";
					$_SESSION["la_vacacionpersonal"]["diavac"]=0;
					$_SESSION["la_vacacionpersonal"]["diabonvac"]=0;
					$_SESSION["la_vacacionpersonal"]["diaadibon"]=0;
					$_SESSION["la_vacacionpersonal"]["diaadivac"]=0;
					$_SESSION["la_vacacionpersonal"]["diafer"]=0;
					$_SESSION["la_vacacionpersonal"]["sabdom"]=0;
					$_SESSION["la_vacacionpersonal"]["quisalvac"]=0;
					$_SESSION["la_vacacionpersonal"]["quireivac"]=0;
					$_SESSION["la_vacacionpersonal"]["nrolunes_s"]=0;
					$_SESSION["la_vacacionpersonal"]["nrolunes_r"]=0;
					$_SESSION["la_vacacionpersonal"]["primera_quincena"]=false;
					$_SESSION["la_vacacionpersonal"]["segunda_quincena"]=false;
				}
			}
			$this->io_sql->free_result($rs_data);
		}	
		return $lb_valido;
	}// end function uf_crear_vacacionpersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_crear_tablasueldo($as_codper)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_crear_tablasueldo
		//		   Access: private
		//   	Arguments: as_codper // código de personal
		//	      Returns: lb_valido True si se creo la variable sesion ó False si no se creo
		//	  Description: función que dado el código de personal crea una variable session con todos los datos
		//				   de sueldo que tiene asociado
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_personalnomina.codtab, sno_personalnomina.codgra, sno_personalnomina.codpas, ".
				"		sno_grado.monsalgra, sno_grado.moncomgra, ".
				"		COALESCE((SELECT SUM(sno_primagrado.monpri) ".
				"		   			FROM sno_primagrado ".
				"		  		   WHERE sno_primagrado.codemp = sno_grado.codemp ".
				"					 AND sno_primagrado.codtab = sno_grado.codtab ".
				"					 AND sno_primagrado.codpas = sno_grado.codpas ".
				"					 AND sno_primagrado.codgra = sno_grado.codgra ".
				"         		   GROUP BY sno_primagrado.codemp, sno_primagrado.codtab, sno_primagrado.codpas, ".
				"							sno_primagrado.codgra),0) AS monto_primas ".
				"  FROM sno_personalnomina, sno_grado ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_personalnomina.codper='".$as_codper."' ".
				"   AND sno_personalnomina.codemp=sno_grado.codemp ".
				"   AND sno_personalnomina.codnom=sno_grado.codnom ".
				"   AND sno_personalnomina.codtab=sno_grado.codtab ".
				"   AND sno_personalnomina.codgra=sno_grado.codgra ".
				"   AND sno_personalnomina.codpas=sno_grado.codpas ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_crear_tablasueldo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$la_tablasueldo=$row;   
				$_SESSION["la_tablasueldo"]=$la_tablasueldo;
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_crear_conceptopersonal ERROR->Verifique el Tabulador ó grados asociados al personal ".$as_codper);
			}
			$this->io_sql->free_result($rs_data);	
		}		
		return $lb_valido;
	}// end function uf_crear_tablasueldo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_crear_conceptopersonal($as_codper,$as_codconc)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_crear_conceptopersonal
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_codconc // código del concepto
		//        Returns: lb_valido True si se creo la variable sesion ó False si no se creo
		//	  Description: función que dado el código de personal y concepto crea una variable session con todos los datos
		//				   de concepto Personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_concepto.codconc, sno_concepto.nomcon, sno_concepto.titcon, sno_concepto.sigcon, sno_concepto.forcon, ".
				"       sno_concepto.glocon, sno_concepto.acumaxcon, sno_concepto.valmincon, sno_concepto.valmaxcon, sno_concepto.concon, ".
				"		sno_concepto.cueprecon, sno_concepto.cueconcon, sno_concepto.aplisrcon, sno_concepto.sueintcon, sno_concepto.intprocon, ".
				"		sno_concepto.codpro, sno_concepto.forpatcon, sno_concepto.cueprepatcon, sno_concepto.cueconpatcon, sno_concepto.titretempcon, ".
				"		sno_concepto.titretpatcon, sno_concepto.valminpatcon, sno_concepto.valmaxpatcon, sno_concepto.codprov, sno_concepto.cedben, ".
				"		sno_concepto.conprenom, sno_concepto.sueintvaccon, sno_concepto.aplarccon, ".
				"       sno_conceptopersonal.aplcon, sno_conceptopersonal.valcon, sno_conceptopersonal.acuemp, ".
				"  		sno_conceptopersonal.acuiniemp, sno_conceptopersonal.acupat, sno_conceptopersonal.acuinipat, sno_concepto.quirepcon ".
				"  FROM sno_conceptopersonal, sno_concepto ".
				" WHERE sno_conceptopersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_conceptopersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_conceptopersonal.codconc='".$as_codconc."' ".
				"   AND sno_conceptopersonal.codper='".$as_codper."' ".
				"   AND sno_conceptopersonal.codemp=sno_concepto.codemp ".
				"   AND sno_conceptopersonal.codnom=sno_concepto.codnom ".
				"   AND sno_conceptopersonal.codconc=sno_concepto.codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_crear_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$la_conceptopersonal=$row;   
				$_SESSION["la_conceptopersonal"]=$la_conceptopersonal;
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_crear_conceptopersonal ERROR->No hay conceptos asociados para el personal ".$as_codper);
			}
			$this->io_sql->free_result($rs_data);	
		}		
		return $lb_valido;
	}// end function uf_crear_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_evaluar($as_codper,$as_formula,&$as_valor)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_evaluar
		//		   Access: public
		//	    Arguments: as_codper // código de personal
		//				   as_formula // fórmula del concepto
		//				   as_valor // valor que se obtiene de la fórmula
		//	      Returns: lb_valido True si se evalua correctamente la fórmula ó False si hubo error 
		//	  Description: función que dado una formula de devuelve el valor que arroja
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_formula=trim($as_formula);
		$as_formula=strtoupper(trim($as_formula));
		if($lb_valido)
		{
			// Variables de Nómina
			$lb_valido=$this->uf_sustituir($as_codper,"FN[",$as_formula);
		}
		if($lb_valido)
		{
			// Variables de Personal
			$lb_valido=$this->uf_sustituir($as_codper,"PS[",$as_formula);
		}
		if($lb_valido)
		{
			// Variables de Tabla de Sueldo
			$lb_valido=$this->uf_sustituir($as_codper,"TB[",$as_formula);
		}
		if($lb_valido)
		{
			// Variables de Conceptos
			$lb_valido=$this->uf_sustituir($as_codper,"CN[",$as_formula);
		}
		if($lb_valido)
		{
			// Variables de Constantes
			$lb_valido=$this->uf_sustituir($as_codper,"CT[",$as_formula);
		}
		if($lb_valido)
		{
			// Evaluar la Fórmula
			$lb_valido=$this->io_eval->uf_evaluar_nomina($as_formula,$as_valor);
			$as_valor=round($as_valor,2);
		}
		return $lb_valido;
	}// end function uf_evaluar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sustituir($as_codper,$as_exp,&$as_formula)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sustituir
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_exp // Expresión que me identifica que tipo de valor se va a buscar
		//				   as_formula // fórmula del concepto
		//	      Returns: lb_valido True si se sustituye correctamente la fórmula ó False si hubo error 
		//	  Description: función que dado una formula sustituye los valores que son de la nómina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_formula=trim($as_formula);
		$li_pos=strpos($as_formula,$as_exp);
		if($li_pos===false)
		{
			$li_pos=-1;
		}
		while (($li_pos>=0)&&($lb_valido))
		{
			$li=$li_pos;
			while (($li<strlen($as_formula))&&(substr($as_formula,$li,1)<>"]"))
			{
				$li=$li+1;
			}
			if($li==0)
			{
				$lb_valido=false;
				$li_pos=-1;
				break;
			}
			$ls_token=substr($as_formula,(strlen($as_exp)+$li_pos),($li-strlen($as_exp)-$li_pos));
			switch ($as_exp)
			{
				case "FN["://Valor de Nómina
					$lb_valido=$this->uf_valor_nomina($as_codper,$ls_token,$ls_valor);
					break;
					
				case "PS["://Valor de Personal
					$lb_valido=$this->uf_valor_personal($as_codper,$ls_token,$ls_valor);
					break;

				case "TB["://Valor de Tabla de Sueldo
					$ls_token=str_pad($ls_token,20,"0",0);
					$lb_valido=$this->uf_valor_tabla($as_codper,$ls_token,$ls_valor);
					break;

				case "CN["://Valor de Concepto
					$ls_token=str_pad($ls_token,10,"0",0);
					$lb_valido=$this->uf_valor_concepto($as_codper,$ls_token,$ls_valor);
					break;

				case "CT["://Valor de Constante
					$ls_token=str_pad($ls_token,10,"0",0);
					$lb_valido=$this->uf_valor_constante($as_codper,$ls_token,$ls_valor);
					break;
			}
			if($lb_valido)
			{
				$ls_token=substr($as_formula,$li_pos,$li-$li_pos+1);
				$as_formula=str_replace($ls_token,$ls_valor,$as_formula);
				if(strlen($as_formula)>$li_pos)
				{
					$li_pos=strpos($as_formula,$as_exp,$li_pos);
					if($li_pos===false)
					{
						$li_pos=-1;
					}				
				}
				else
				{
					$li_pos=-1;
				}
			}
		}
		return $lb_valido;
	}// end function uf_sustituir
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_valor_nomina($as_codper,$as_token,&$as_valor)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valor_nomina
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_token // token que va a ser reemplazado
		//				   as_valor // valor del token
		//	      Returns: lb_valido True si se sustituye correctamente el valor ó False si hubo error 
		//	  Description: función que dado un token se sutituye por su valor respectivo de la nómina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_valor="";
		$lb_valido=true;
		$as_token=trim($as_token);
		switch ($as_token)
		{
			case "NRO_SEMANA": // Semana en la que se encuentra el periodo
				$as_valor=(intval($_SESSION["la_nomina"]["peractnom"])/4);
				if($as_valor==0)
				{
					$as_valor=4;
				}
				break;

			case "PRIMERA_QUINCENA": // Si es la primera quincena del mes
				if(substr($_SESSION["la_nomina"]["fechasper"],8,2)=="15")
				{
					$as_valor=true;
				}
				else
				{
					$as_valor=0;
				}
				break;

			case "SEGUNDA_QUINCENA": // Si es la segunda quincena del mes
				if(intval(substr($_SESSION["la_nomina"]["fechasper"],8,2))>15)
				{
					$as_valor=true;
				}
				else
				{
					$as_valor=0;
				}
				break;
				
			case "NRO_LUNES": // Número de lunes que tiene el período
				$ld_fecdes=$_SESSION["la_nomina"]["fecdesper"];
				$ld_fechas=$_SESSION["la_nomina"]["fechasper"];
				$as_valor=$this->io_sno->uf_nro_lunes($ld_fecdes,$ld_fechas);
				break;

			case "NRO_LUNESMES": // Número de lunes que tiene el mes
				$ld_fecdes=$_SESSION["la_nomina"]["fecdesper"];
				$ld_fechas=$_SESSION["la_nomina"]["fechasper"];
				$ld_diahasta=strftime("%d",mktime(0,0,0,(substr($ld_fecdes,5,2)+1),0,substr($ld_fecdes,0,4)));
				$ld_desde=substr($ld_fecdes,0,8)."01";
				$ld_hasta=substr($ld_fecdes,0,8).$ld_diahasta;
				$as_valor=$this->io_sno->uf_nro_lunes($ld_desde,$ld_hasta);
				break;

			case "NRO_DIAS_BV_S": // Número de días de bono vacacional 
				$as_valor=intval($_SESSION["la_nomina"]["diabonvacnom"]);
				break;

			case "NRO_DIAS_BV_R": // Número de días de reintegro
				$as_valor=intval($_SESSION["la_nomina"]["diareivacnom"]);
				break;

			case "FIN_MES": // Si es fin de mes
				$ld_fecdes=$_SESSION["la_nomina"]["fecdesper"];
				$ld_fechas=$_SESSION["la_nomina"]["fechasper"];
				$as_valor=$this->io_sno->uf_fin_mes($ld_fecdes,$ld_fechas);
				break;

			case "DIF_DIA_FIN_PERIODO": // Día del Fin del período
				$ai_diafin=intval(substr($_SESSION["la_nomina"]["fechasper"],8,2));
				$as_valor=30-$ai_diafin;
				if($as_valor<0)
				{
					$as_valor="(".$as_valor.")";
				}
				break;

			case "DHABILES": // Días Hábiles que tuvo el periodo
				$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				$li_sabdom=$this->io_sno->uf_nro_sabydom($ld_fecdesper,$ld_fechasper);
				$ld_diades=substr($ld_fecdesper,8,2);
				$ld_mesdes=substr($ld_fecdesper,5,2);
				$ld_anodes=substr($ld_fecdesper,0,4);
				$ld_diahas=substr($ld_fechasper,8,2);
				$ld_meshas=substr($ld_fechasper,5,2);
				$ld_anohas=substr($ld_fechasper,0,4);
				$as_valor=((mktime(0,0,0,$ld_meshas,$ld_diahas,$ld_anohas) - mktime(0,0,0,$ld_mesdes,$ld_diades,$ld_anodes))/86400)+1;
				$as_valor=round($as_valor-$li_sabdom);
				break;

			case "VALOR_CT": // Monto del cesta ticket según la nómina
				$ls_codnom=$_SESSION["la_nomina"]["codnom"];
				$as_valor=$this->io_cestaticket->uf_select_valor_ct($ls_codnom);
				break;

			case substr($as_token,0,16)=="UNIDADTRIBUTARIA": // Valor de la unidad tributaria
				$li_ano=intval(substr($as_token,17,4));
				if($li_ano==0)
				{
					$li_ano=substr($_SESSION["la_empresa"]["periodo"],0,4);
				}
				$as_valor=0;
				$lb_valido=$this->uf_obtener_unidadtributaria($li_ano,&$as_valor);
				break;

			default: // si el token no existe
				$this->io_mensajes->message("ERROR->NOMINA FN[".$as_token."] Nó Válido.");			
				$lb_valido=false;
				break;
		}
		return $lb_valido;
	}// end function uf_valor_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_valor_personal($as_codper,$as_token,&$as_valor)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valor_personal
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_token // valor que va a ser reemplazado
		//				   as_valor // valor del token
		//	      Returns: lb_valido True si se sustituye correctamente el valor ó False si hubo error 
		//	  Description: función que dado un token se sutituye por su valor respectivo del personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_valor="";
		$lb_valido=true;
		$as_token=trim($as_token);
		switch ($as_token)
		{
			case "DIAS_LABORADO": // Número de días laborados por la persona desde que llegó a la institución
				$ld_fechatope=$this->io_funciones->uf_convertirdatetobd($this->io_sno->uf_select_config("SNO","ANTIGUEDAD","FECHA_TOPE","1900-01-01","C"));
				$ld_fecingper=$_SESSION["la_personalnomina"]["fecingper"];
				$ld_diades=substr($ld_fecingper,8,2);
				$ld_mesdes=substr($ld_fecingper,5,2);
				$ld_anodes=substr($ld_fecingper,0,4);
				if(($ld_fechatope!="1900-01-01")&&($ld_fechatope!=""))
				{
					$ld_diahas=substr($ld_fechatope,8,2);
					$ld_meshas=substr($ld_fechatope,5,2);
					$ld_anohas=substr($ld_fechatope,0,4);
				}
				else
				{
					$ld_fechatope=$_SESSION["la_nomina"]["fechasper"];
					$ld_diahas=substr($ld_fechatope,8,2);
					$ld_meshas=substr($ld_fechatope,5,2);
					$ld_anohas=substr($ld_fechatope,0,4);
				}
				$as_valor=((mktime(0,0,0,$ld_meshas,$ld_diahas,$ld_anohas) - mktime(0,0,0,$ld_mesdes,$ld_diades,$ld_anodes))/86400)+1;
				$as_valor=round($as_valor);
				break;

			case "MESES_LABORADO": // Número de meses laborados por la persona desde que llegó
				$ld_fechatope=$this->io_funciones->uf_convertirdatetobd($this->io_sno->uf_select_config("SNO","ANTIGUEDAD","FECHA_TOPE","1900-01-01","C"));
				$ld_fecingper=$_SESSION["la_personalnomina"]["fecingper"];
				$ld_diades=substr($ld_fecingper,8,2);
				$ld_mesdes=substr($ld_fecingper,5,2);
				$ld_anodes=substr($ld_fecingper,0,4);
				if (($ld_fechatope!="1900-01-01")&&($ld_fechatope!=""))
				{
					$ld_diahas=substr($ld_fechatope,8,2);
					$ld_meshas=substr($ld_fechatope,5,2);
					$ld_anohas=substr($ld_fechatope,0,4);
				}
				else
				{
					$ld_fechatope=$_SESSION["la_nomina"]["fechasper"];
					$ld_diahas=substr($ld_fechatope,8,2);
					$ld_meshas=substr($ld_fechatope,5,2);
					$ld_anohas=substr($ld_fechatope,0,4);
				}
				$as_valor=(((mktime(0,0,0,$ld_meshas,$ld_diahas,$ld_anohas) - mktime(0,0,0,$ld_mesdes,$ld_diades,$ld_anodes))/86400)+1)/30;
				$as_valor=round($as_valor);
				break;

			case "SUELDO": // Sueldo de la persona
				$as_valor=$_SESSION["la_personalnomina"]["sueper"];
				break;

			case "SUELDO_MIN_GRADO": // Sueldo Mínimo según el grado que tenga el obrero
				$as_valor=$_SESSION["la_personalnomina"]["suemingra"];
				break;

			case "DIF_SUELDOMIN": //  Diferencia del sueldo Mínimo con respecto al sueldo base
				$as_valor=0;
				$li_sueldominimo=0;
			    $lb_valido=$this->uf_obtener_sueldominimo(&$li_sueldominimo);
				if($lb_valido)
				{
					$li_diferencia=number_format($li_sueldominimo-$_SESSION["la_personalnomina"]["suemingra"],2,".","");
					if($li_diferencia>0)
					{
						$as_valor=$li_diferencia;
					}
				}
				break;

			case "COMPENSACION_OBRERO": // Monto de la Compensación para las nóminas de obreros con clasificación
				$as_valor=$_SESSION["la_personalnomina"]["sueper"]-$_SESSION["la_personalnomina"]["suemingra"];
				if($as_valor<0)
				{
					$as_valor=0;
				}
				break;

			case substr($as_token,0,10)=="SUELDO_ANT": // SUELDO DEL MES ANTERIOR
				$as_valor=0;
				$as_sueldo=0;
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				$ls_ano=substr($ld_fechasper,0,4);
				$li_mes=intval(substr($as_token,11,2));
				if($li_mes==0)
				{
					$ls_mes=substr($ld_fechasper,5,2)-1;
					$ls_mes=str_pad($ls_mes,2,"0",0);
					if($ls_mes=="00")
					{
						$ls_ano=$ls_ano-1;
						$ls_mes="12";
					}
				}
				else
				{
					$ls_mes=str_pad($li_mes,2,"0",0);
					if($ls_mes=="12")
					{
						$ls_ano=$ls_ano-1;
					}
				}
				$lb_valido=$this->uf_obtener_sueldo_ante($as_codper,$ls_mes,$ls_ano,&$as_sueldo);
				if($lb_valido)
				{
					$as_valor=$as_sueldo;
				}
				break;

			case "SUELDO_PROMEDIO": // Sueldo Integral de la persona
				$as_valor=$_SESSION["la_personalnomina"]["sueproper"];
				break;

			case "HORAS": // Horas que labora la persona
				$as_valor=$_SESSION["la_personalnomina"]["horper"];
				break;

			case "SEXO": // Sexo de la persona
				$as_valor="'".$_SESSION["la_personalnomina"]["sexper"]."'";
				break;

			case "NRO_HIJOS": // Número de Hijos de la persona
				$as_valor=$_SESSION["la_personalnomina"]["numhijper"];
				break;
				
			case "A_SERVICIO": // Años de Servicios previos de la persona
				$as_valor=$_SESSION["la_personalnomina"]["anoservpreper"];
				break;
				
			case "EDAD": // Edad de la persona
				$ld_fechas=substr($_SESSION["la_nomina"]["fechasper"],0,4);
				$ld_fecnacper=substr($_SESSION["la_personalnomina"]["fecnacper"],0,4);
				$as_valor=$ld_fechas-$ld_fecnacper;
				$ld_fechas=$_SESSION["la_nomina"]["fechasper"];
				$ld_fecnacper=$_SESSION["la_personalnomina"]["fecnacper"];
				if(intval(substr($ld_fechas,5,2))<intval(substr($ld_fecnacper,5,2)))
				{
					$as_valor=$as_valor-1;
				}
				else
				{
					if(intval(substr($ld_fechas,5,2))==intval(substr($ld_fecnacper,5,2)))
					{
						if(intval(substr($ld_fechas,8,2))<intval(substr($ld_fecnacper,8,2)))
						{
							$as_valor=$as_valor-1;
						}
					}
				}
				break;
				
			case "SUELDO_INTEGRAL": // Sueldo Integral de la persona
				$as_valor=$_SESSION["la_personalnomina"]["sueldointegral"];
				break;
				
			case "ESTATUS": // Estatus de la persona
				$ls_staper=$_SESSION["la_personalnomina"]["staper"];
				switch ($ls_staper)
				{
					case "1":
						$as_valor="'A'";
						break;

					case "2":
						$as_valor="'V'";
						break;

					case "3":
						$as_valor="'E'";
						break;
				}
				break;
				
			case "V_PRIMERA_QUINCENA": // si es la primera quincena de vacaciones de la persona
				$as_valor=$_SESSION["la_vacacionpersonal"]["primera_quincena"];
				break;

			case "V_SEGUNDA_QUINCENA": // si es la segunda quincena de vacaciones de la persona
				$as_valor=$_SESSION["la_vacacionpersonal"]["segunda_quincena"];
				break;

			case "V_DIASBONO": // Días de bono vacacional de la persona
				$as_valor=$_SESSION["la_vacacionpersonal"]["diabonvac"];
				break;

			case "V_DIASBONO_ADIC": // Días adicionales de bono vacacional de la persona
				$as_valor=$_SESSION["la_vacacionpersonal"]["diaadibon"];
				break;

			case "V_NRO_DIAS": // número de días hábiles de vacaciones de la persona
				$as_valor=$_SESSION["la_vacacionpersonal"]["diavac"];
				break;

			case "V_DIASVAC_ADIC": // Dias adicionales de vacaciones de la persona
				$as_valor=$_SESSION["la_vacacionpersonal"]["diaadivac"];
				break;

			case "NRO_LUNES_S": // número de días lunes de salida de vacaciones de la persona
				$as_valor=$_SESSION["la_vacacionpersonal"]["nrolunes_s"];
				break;

			case "NRO_LUNES_R": // número de días lunes de reingreso de vacaciones de la persona
				$as_valor=$_SESSION["la_vacacionpersonal"]["nrolunes_r"];
				break;

			case "V_NRO_QNA_S": // quincena de salida de vacaciones de la persona
				$as_valor=$_SESSION["la_vacacionpersonal"]["quisalvac"];
				break;

			case "V_NRO_QNA_R": // quincena de reingreso de vacaciones de la persona
				$as_valor=$_SESSION["la_vacacionpersonal"]["quireivac"];
				break;

			case "SIV": // sueldo integral de vacaciones de la persona
				$as_valor=$_SESSION["la_vacacionpersonal"]["sueintvac"];
				break;

			case "SISV": // Sueldo integral de bono vacacional de la persona
				$as_valor=$_SESSION["la_vacacionpersonal"]["sueintbonvac"];
				break;

			case "NRO_DIAS_CALEN_S": // número de días calendario de salida de vacaciones de la persona
				$ld_fecreivac=$_SESSION["la_vacacionpersonal"]["fecreivac"];
				$ld_fecdisvac=$_SESSION["la_vacacionpersonal"]["fecdisvac"];
				$ld_diades=substr($ld_fecdisvac,8,2);
				$ld_mesdes=substr($ld_fecdisvac,5,2);
				$ld_anodes=substr($ld_fecdisvac,0,4);
				$ld_diahas=substr($ld_fecreivac,8,2);
				$ld_meshas=substr($ld_fecreivac,5,2);
				$ld_anohas=substr($ld_fecreivac,0,4);
				$as_valor=((mktime(0,0,0,$ld_meshas,$ld_diahas,$ld_anohas) - mktime(0,0,0,$ld_mesdes,$ld_diades,$ld_anodes))/86400)+1;
				$as_valor=round($as_valor);
				break;

			case "NRO_DIAS_CALEN_R": // número de días calendario de reingreso de vacaciones de la persona
				$ld_fecreivac=$_SESSION["la_vacacionpersonal"]["fecreivac"];
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				$ld_diades=substr($ld_fecreivac,8,2);
				$ld_mesdes=substr($ld_fecreivac,5,2);
				$ld_anodes=substr($ld_fecreivac,0,4);
				$ld_diahas=substr($ld_fechasper,8,2);
				$ld_meshas=substr($ld_fechasper,5,2);
				$ld_anohas=substr($ld_fechasper,0,4);
				$as_valor=((mktime(0,0,0,$ld_meshas,$ld_diahas,$ld_anohas) - mktime(0,0,0,$ld_mesdes,$ld_diades,$ld_anodes))/86400)+1;
				$as_valor=round($as_valor);
				break;

			case "NRO_DIAS_HABILES": // número de días hábiles de vacaciones de la persona
				$as_valor=$_SESSION["la_vacacionpersonal"]["diavac"];
				break;

			case "NRO_DIAS_FERIADOS": // número de días feriados de vacaciones de la persona
				$as_valor=$_SESSION["la_vacacionpersonal"]["diafer"];
				break;

			case "NRO_DIAS_SABYDOM": // número de días sabados y domingos de vacaciones de la persona
				$as_valor=$_SESSION["la_vacacionpersonal"]["sabdom"];
				break;

			case "PRIMAS_POR_HIJO": // Primas por Hijo de la persona
				$li_numhijper=$_SESSION["la_personalnomina"]["numhijper"];
				$as_valor=0;
				if($li_numhijper>0)
				{
					$ls_codconc=$_SESSION["la_conceptopersonal"]["codconc"];
					$lb_valido=$this->io_primaconcepto->uf_select_primahijos($ls_codconc,$li_valpri);
					if($lb_valido)
					{
						$as_valor=$li_numhijper*$li_valpri;
					}
				}
				break;

			case "PRIMA_POR_ANTIGUEDAD": // Primas por Antiguedad de la persona
				$as_valor=0;
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
				$ld_fecingper=$_SESSION["la_personalnomina"]["fecingper"];
				$ai_mesingper=intval(substr($ld_fecingper,6,2));
				$ai_meshasper=intval(substr($ld_fechasper,6,2));
				if($ai_mesingper==$ai_meshasper)
				{
					$li_diaingper=intval(substr($ld_fecingper,8,2));
					$li_diadesper=intval(substr($ld_fecdesper,8,2));
					$li_diahasper=intval(substr($ld_fechasper,8,2));
					if(($li_diaingper>=$li_diadesper)&&($li_diaingper<=$li_diahasper))
					{
						$ls_codconc=$_SESSION["la_conceptopersonal"]["codconc"];
						$ld_diades=substr($ld_fecingper,8,2);
						$ld_mesdes=substr($ld_fecingper,5,2);
						$ld_anodes=substr($ld_fecingper,0,4);
						$ld_diahas=substr($ld_fechasper,8,2);
						$ld_meshas=substr($ld_fechasper,5,2);
						$ld_anohas=substr($ld_fechasper,0,4);
						$ai_ano=((mktime(0,0,0,$ld_meshas,$ld_diahas,$ld_anohas) - mktime(0,0,0,$ld_mesdes,$ld_diades,$ld_anodes))/86400)+1;
						$ai_ano=round($ai_ano/365);
						$li_valpri=0;
						$lb_valido=$this->io_primaconcepto->uf_select_primaantiguedad($ls_codconc,$ai_ano,$li_valpri);
						if($lb_valido)
						{
							$as_valor=$li_valpri;
						}
					}
				}				
				break;

			case "COMPENSACION": // Monto Compensación del grado de la persona
				$as_valor=$_SESSION["la_tablasueldo"]["moncomgra"];
				break;

			case "PRIMA_TABULADOR": // Suma de las primas asociadas al tabulador, paso, y grado del personal
				$as_valor=$_SESSION["la_tablasueldo"]["monto_primas"];
				break;

			case substr($as_token,0,12)=="ANTIGUEDAD_A": // Antiguedad en años de la persona
				$li_ano=intval(substr($as_token,13,4));
				if($li_ano==0)
				{
					$ld_fecingper=$_SESSION["la_personalnomina"]["fecingper"];
				}
				else
				{
					$ld_fecingper=$li_ano.substr($_SESSION["la_personalnomina"]["fecingper"],4,6);
				}
				$ld_fechasper=substr($_SESSION["la_nomina"]["fechasper"],0,4);
				$ld_fecing=substr($ld_fecingper,0,4);
				$as_valor=$ld_fechasper-$ld_fecing;
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				if(intval(substr($ld_fechasper,5,2))<intval(substr($ld_fecingper,5,2)))
				{
					$as_valor=$as_valor-1;
				}
				else
				{
					if(intval(substr($ld_fechasper,5,2))==intval(substr($ld_fecingper,5,2)))
					{
						if(intval(substr($ld_fechasper,8,2))<intval(substr($ld_fecingper,8,2)))
						{
							$as_valor=$as_valor-1;
						}
					}
				}
				break;

			case "ANTIG_DC": // Antiguedad en días de la persona
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				$ld_fecingper=$_SESSION["la_personalnomina"]["fecingper"];
				$ld_diades=substr($ld_fecingper,8,2);
				$ld_mesdes=substr($ld_fecingper,5,2);
				$ld_anodes=substr($ld_fecingper,0,4);
				$ld_diahas=substr($ld_fechasper,8,2);
				$ld_meshas=substr($ld_fechasper,5,2);
				$ld_anohas=substr($ld_fechasper,0,4);
				$as_valor=((mktime(0,0,0,$ld_meshas,$ld_diahas,$ld_anohas) - mktime(0,0,0,$ld_mesdes,$ld_diades,$ld_anodes))/86400)+1;
				$as_valor=round($as_valor);
				break;

			case "ANTIGUEDAD_M": // Antiguedad en meses de la persona
				$ld_fechasper=substr($_SESSION["la_nomina"]["fechasper"],0,10);
				$ld_fecingper=substr($_SESSION["la_personalnomina"]["fecingper"],0,10);
				$ld_diades=substr($ld_fecingper,8,2);
				$ld_mesdes=substr($ld_fecingper,5,2);
				$ld_anodes=substr($ld_fecingper,0,4);
				$ld_diahas=substr($ld_fechasper,8,2);
				$ld_meshas=substr($ld_fechasper,5,2);
				$ld_anohas=substr($ld_fechasper,0,4);
				$as_valor=((mktime(0,0,0,$ld_meshas,$ld_diahas,$ld_anohas) - mktime(0,0,0,$ld_mesdes,$ld_diades,$ld_anodes))/86400)+1;
				$as_valor=round($as_valor/30,2);
				break;

			case "ANTIGUEDAD_M_ENTERO": // Antiguedad en meses de la persona pero en valores enteros
				$ld_fechasper=substr($_SESSION["la_nomina"]["fechasper"],0,10);
				$ld_fecingper=substr($_SESSION["la_personalnomina"]["fecingper"],0,10);
				$ld_diades=substr($ld_fecingper,8,2);
				$ld_mesdes=substr($ld_fecingper,5,2);
				$ld_anodes=substr($ld_fecingper,0,4);
				$ld_diahas=substr($ld_fechasper,8,2);
				$ld_meshas=substr($ld_fechasper,5,2);
				$ld_anohas=substr($ld_fechasper,0,4);
				$as_valor=((mktime(0,0,0,$ld_meshas,$ld_diahas,$ld_anohas) - mktime(0,0,0,$ld_mesdes,$ld_diades,$ld_anodes))/86400)+1;
				$as_valor=floor($as_valor/30);
				break;

			case substr($as_token,0,7)=="MENORES": // Número de Hijos Menores de la persona
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				$li_edad=intval(substr($as_token,8,2));
				if($li_edad==0)
				{
					$li_edad=18;
				}
				$lb_valido=$this->io_familiar->uf_load_hijosmenores($ls_codper,$li_edad,$ld_fechasper,$as_valor);
				break;

			case substr($as_token,0,16)=="ESTUDIANTE_MENOR": // Número de Hijos Menores de la persona
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				$li_edaddesde=intval(substr($as_token,17,2));
				$li_edadhasta=intval(substr($as_token,20,2));
				$lb_valido=$this->io_familiar->uf_load_hijosmenores_estudiantes($ls_codper,$li_edaddesde,$li_edadhasta,$ld_fechasper,&$as_valor);
				break;

			case "AR-C": // Monto de ISR del mes en curso de la persona
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$ls_mes=substr($_SESSION["la_nomina"]["fechasper"],5,2);
				$lb_valido=$this->io_isr->uf_load_isrpersonal($ls_codper,$ls_mes,$as_valor);
				break;

			case substr($as_token,0,3)=="VTC": // Total a Cobrar 
				$ls_codconc=str_pad(substr($as_token,4,3),10,"0",0);
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$as_valor=0;
				$li_vtc=0;
				$lb_valido=$this->uf_obtener_vtc($ls_codper,$ls_codconc,$li_vtc);
				if($lb_valido)
				{
					$as_valor=$li_vtc;
				}
				break;

			case substr($as_token,0,3)=="VCA": // Total del Concepto en un período anterior
				$ls_codconc=str_pad(substr($as_token,4,10),10,"0",0);
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$as_valor=0;
				$li_vca=0;
				$li_anopre=0;
				$li_perpre=0;
				$lb_valido=$in_class_sno->uf_periodo_previo($li_anopre,$li_perpre,$li_vca);
				if($lb_valido)
				{
					$lb_valido=$this->uf_obtener_vca($ls_codper,$ls_codconc,$li_anopre,$li_perpre,$li_vca);
					if($lb_valido)
					{
						$as_valor=$li_vca;
					}
				}
				break;

			case substr($as_token,0,9)=="MONTO_ANT": // Monto total de conceptos anteriores
				$ls_tipsal=substr($as_token,10,2);
				$li_meses=substr($as_token,13,2);
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$as_valor=0;
				$li_monto=0;
				$lb_valido=$this->uf_obtener_montoanterior($ls_codper,$ls_tipsal,$li_meses,&$li_monto);
				if($lb_valido)
				{
					$as_valor=$li_monto;
				}
				break;

			case "DHABILES": // Días Hábiles que tuvo el mes sin los feriados, sin los días de permiso que tuvo el personal
				$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				$li_sabdom=$this->io_sno->uf_nro_sabydom($ld_fecdesper,$ld_fechasper);
				$li_diafer=$this->io_feriado->uf_select_feriados($ld_fecdesper,$ld_fechasper);
				$li_diaper=$this->io_permiso->uf_select_diaspermisos($as_codper,$ld_fecdesper,$ld_fechasper);
				$ld_diades=substr($ld_fecdesper,8,2);
				$ld_mesdes=substr($ld_fecdesper,5,2);
				$ld_anodes=substr($ld_fecdesper,0,4);
				$ld_diahas=substr($ld_fechasper,8,2);
				$ld_meshas=substr($ld_fechasper,5,2);
				$ld_anohas=substr($ld_fechasper,0,4);
				$as_valor=((mktime(0,0,0,$ld_meshas,$ld_diahas,$ld_anohas) - mktime(0,0,0,$ld_mesdes,$ld_diades,$ld_anodes))/86400)+1;
				$as_valor=round($as_valor-($li_sabdom+$li_diafer+$li_diaper));
				break;

			case "DHABILES_CONTRATADOS": // Días Hábiles que laboro el personal contratado en el período actual
				$ld_fecculcontr=$_SESSION["la_personalnomina"]["fecculcontr"];
				$as_valor=0;
				if(substr($ld_fecculcontr,0,10)!="1900-01-01")
				{
					$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
					$ld_dia=substr($_SESSION["la_nomina"]["fechasper"],8,2);
					$ld_diades=substr($ld_fecdesper,8,2);
					$ld_mesdes=substr($ld_fecdesper,5,2);
					$ld_anodes=substr($ld_fecdesper,0,4);
					$ld_diahas=substr($ld_fecculcontr,8,2);
					$ld_meshas=substr($ld_fecculcontr,5,2);
					$ld_anohas=substr($ld_fecculcontr,0,4);
					if((($ld_diahas>=$ld_diades)&&($ld_diahas<=$ld_dia))&&($ld_mesdes==$ld_meshas)&&($ld_anodes==$ld_anohas))
					{
						$li_sabdom=$this->io_sno->uf_nro_sabydom($ld_fecdesper,$ld_fecculcontr);
						$li_diafer=$this->io_feriado->uf_select_feriados($ld_fecdesper,$ld_fecculcontr);
						$as_valor=((mktime(0,0,0,$ld_meshas,$ld_diahas,$ld_anohas) - mktime(0,0,0,$ld_mesdes,$ld_diades,$ld_anodes))/86400)+1;
						$as_valor=round($as_valor-($li_sabdom+$li_diafer));					
					}
				}
				break;

			case "DCALENDARIO_CONTRATADOS": // Días Hábiles que laboro el personal contratado en el período actual
				$ld_fecculcontr=$_SESSION["la_personalnomina"]["fecculcontr"];
				$as_valor=0;
				if(substr($ld_fecculcontr,0,10)!="1900-01-01")
				{
					$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
					$ld_dia=substr($_SESSION["la_nomina"]["fechasper"],8,2);
					$ld_diades=substr($ld_fecdesper,8,2);
					$ld_mesdes=substr($ld_fecdesper,5,2);
					$ld_anodes=substr($ld_fecdesper,0,4);
					$ld_diahas=substr($ld_fecculcontr,8,2);
					$ld_meshas=substr($ld_fecculcontr,5,2);
					$ld_anohas=substr($ld_fecculcontr,0,4);
					if((($ld_diahas>=$ld_diades)&&($ld_diahas<=$ld_dia))&&($ld_mesdes==$ld_meshas)&&($ld_anodes==$ld_anohas))
					{
						$as_valor=((mktime(0,0,0,$ld_meshas,$ld_diahas,$ld_anohas) - mktime(0,0,0,$ld_mesdes,$ld_diades,$ld_anodes))/86400)+1;
						$as_valor=round($as_valor);					
					}
				}
				break;
				
			case "CUMP_ORGV": // Si en este período cumple año en el organismo
				$ls_tipo = $_SESSION["la_nomina"]["tippernom"];
				$li_anoact = intval(substr($_SESSION["la_nomina"]["fechasper"],0,4));
				$li_mesact = intval(substr($_SESSION["la_nomina"]["fechasper"],5,2));
				$li_diaact = intval(substr($_SESSION["la_nomina"]["fechasper"],8,2));
				$li_anoper = intval(substr($_SESSION["la_personalnomina"]["fecingper"],0,4));
				$li_mesper = intval(substr($_SESSION["la_personalnomina"]["fecingper"],5,2));
				$li_diaper = intval(substr($_SESSION["la_personalnomina"]["fecingper"],8,2));
				$li_mesdes = intval(substr($_SESSION["la_nomina"]["fecdesper"],5,2));
				$li_diades = intval(substr($_SESSION["la_nomina"]["fecdesper"],8,2));
				$as_valor=0;
				if($li_anoact > $li_anoper)
				{
					if($ls_tipo==0) // es una nómina Semanal
					{
						if($li_mesdes==$li_mesact)
						{
							if($li_mesper==$li_mesact)
							{
								if(($li_diaper>=$li_diades)&&($li_diaper<=$li_diaact))
								{
									$as_valor=1;
								}
							}
						}
						else
						{
							if($li_mesper==$li_mesact)
							{
								if($li_diaper<=$li_diaact)
								{
									$as_valor=1;
								}
							}
							if($li_mesper==$li_mesdes)
							{
								if($li_diaper>=$li_diades)
								{
									$as_valor=1;
								}
							}
						}
					}
					else
					{
						if($li_mesper==$li_mesact)
						{
							if(($li_diaper>=$li_diades)&&($li_diaper<=$li_diaact))
							{
								$as_valor=1;
							}
						}
					}
				}
				break;
				
			case "CUMP_ORGV_MES": // Si en el mes cumple año en el organismo
				$ls_tipo = $_SESSION["la_nomina"]["tippernom"];
				$li_anoact = intval(substr($_SESSION["la_nomina"]["fechasper"],0,4));
				$li_mesact = intval(substr($_SESSION["la_nomina"]["fechasper"],5,2));
				$li_diaact = intval(substr($_SESSION["la_nomina"]["fechasper"],8,2));
				$li_anoper = intval(substr($_SESSION["la_personalnomina"]["fecingper"],0,4));
				$li_mesper = intval(substr($_SESSION["la_personalnomina"]["fecingper"],5,2));
				$li_diaper = intval(substr($_SESSION["la_personalnomina"]["fecingper"],8,2));
				$li_mesdes = intval(substr($_SESSION["la_nomina"]["fecdesper"],5,2));
				$li_diades = intval(substr($_SESSION["la_nomina"]["fecdesper"],8,2));
				$as_valor=0;
				if($li_anoact > $li_anoper)
				{
					if($ls_tipo==0) // es una nómina Semanal
					{
						if($li_mesdes==$li_mesact)
						{
							if($li_mesper==$li_mesact)
							{
								$as_valor=1;
							}
						}
						else
						{
							if($li_mesper==$li_mesact)
							{
								$as_valor=1;
							}
							if($li_mesper==$li_mesdes)
							{
								$as_valor=1;
							}
						}
					}
					else
					{
						if($li_mesper==$li_mesact)
						{
							$as_valor=1;
						}
					}
				}
				break;

			case "ANT_INST": // antiguedad en la institución
				$ld_fecingper=substr($_SESSION["la_personalnomina"]["fecingper"],0,4);
				$ld_fechasper=substr($_SESSION["la_nomina"]["fechasper"],0,4);
				$as_valor=$ld_fechasper-$ld_fecingper;
				$ld_fecingper=$_SESSION["la_personalnomina"]["fecingper"];
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				if(intval(substr($ld_fechasper,5,2))<intval(substr($ld_fecingper,5,2)))
				{
					$as_valor=$as_valor-1;
				}
				else
				{
					if(intval(substr($ld_fechasper,5,2))==intval(substr($ld_fecingper,5,2)))
					{
						if(intval(substr($ld_fechasper,8,2))<intval(substr($ld_fecingper,8,2)))
						{
							$as_valor=$as_valor-1;
						}
					}
				}
				break;

			case "ANT_ADMP": // Antiguedad en la Intitucion desde Personal + Años de Servicio 
				$li_anoprev=$_SESSION["la_personalnomina"]["anoservpreper"];
				$ld_fecingper=substr($_SESSION["la_personalnomina"]["fecingadmpubper"],0,4);
				$ld_fechasper=substr($_SESSION["la_nomina"]["fechasper"],0,4);
				$as_valor=$ld_fechasper-$ld_fecingper;
				$ld_fecingper=$_SESSION["la_personalnomina"]["fecingadmpubper"];
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				if(intval(substr($ld_fechasper,5,2))<intval(substr($ld_fecingper,5,2)))
				{
					$as_valor=$as_valor-1;
				}
				else
				{
					if(intval(substr($ld_fechasper,5,2))==intval(substr($ld_fecingper,5,2)))
					{
						if(intval(substr($ld_fechasper,8,2))<intval(substr($ld_fecingper,8,2)))
						{
							$as_valor=$as_valor-1;
						}
					}
				}
				$as_valor=$as_valor+$li_anoprev;
				break;
				
			case "ANT_ADMP_DIAS": // Antiguedad en la Intitucion en días 
				$ld_fecingper=$_SESSION["la_personalnomina"]["fecingadmpubper"];
				$ld_diades=substr($ld_fecingper,8,2);
				$ld_mesdes=substr($ld_fecingper,5,2);
				$ld_anodes=substr($ld_fecingper,0,4);
				$ld_fechatope=$_SESSION["la_nomina"]["fechasper"];
				$ld_diahas=substr($ld_fechatope,8,2);
				$ld_meshas=substr($ld_fechatope,5,2);
				$ld_anohas=substr($ld_fechatope,0,4);
				$as_valor=((mktime(0,0,0,$ld_meshas,$ld_diahas,$ld_anohas) - mktime(0,0,0,$ld_mesdes,$ld_diades,$ld_anodes))/86400)+1;
				$as_valor=round($as_valor);
				break;
					
			case "PRIMAS_ANTE": // Valor del sueldo integral de vacaciones del mes anterior al calculo
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$as_valor=0;
				$li_prima=0;
				$li_anoant=intval(substr($_SESSION["la_nomina"]["fechasper"],0,4));
				$li_mesant=intval(substr($_SESSION["la_nomina"]["fechasper"],5,2));
				$li_mesant=$li_mesant-1;
				if($li_mesant==0)
				{
					$li_mesant=12;
					$li_anoant=$li_anoant-1;
				}
				$lb_valido=$this->uf_obtener_primas_ante($ls_codper,$li_mesant,$li_anoant,$li_prima);
				if($lb_valido)
				{
					$as_valor=$li_prima;
				}
				break;
				
			case "ANT_EDU": // Devuelve la antiguedad de los educadores 
				$as_valor=0;
				$ld_fecingper=substr($_SESSION["la_personalnomina"]["fecingper"],0,4);
				$ld_fechasper=substr($_SESSION["la_nomina"]["fechasper"],0,4);
				$ai_ano=$ld_fechasper-$ld_fecingper;
				$ld_fecingper=$_SESSION["la_personalnomina"]["fecingper"];
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				if(intval(substr($ld_fechasper,5,2))<intval(substr($ld_fecingper,5,2)))
				{
					$ai_ano=$ai_ano-1;
				}
				else
				{
					if(intval(substr($ld_fechasper,5,2))==intval(substr($ld_fecingper,5,2)))
					{
						if(intval(substr($ld_fechasper,8,2))<intval(substr($ld_fecingper,8,2)))
						{
							$ai_ano=$ai_ano-1;
						}
					}
				}
				if(($ai_ano>=1)&&($ai_ano<=5))
				{
					$as_valor=0.05;
				}
				if(($ai_ano>=6)&&($ai_ano<=10))
				{
					$as_valor=0.10;
				}
				if(($ai_ano>=11)&&($ai_ano<=15))
				{
					$as_valor=0.15;
				}
				if(($ai_ano>=16)&&($ai_ano<=20))
				{
					$as_valor=0.20;
				}
				if(($ai_ano>=21)&&($ai_ano<=25))
				{
					$as_valor=0.25;
				}
				if($ai_ano>=26)
				{
					$as_valor=0.30;
				}
				break;
				
			case "MONTOARC": // total de los conceptos que tienen ARC 
				$as_valor=$_SESSION["la_personalnomina"]["totalarc"];
				break;
				
			case "MONTOARC_MENSUAL": // total de los conceptos que tienen ARC pero en el mes
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$as_valor=0;
				$lb_valido=$this->uf_obtener_montoarc_mesactual($ls_codper,$as_valor);
				if($lb_valido)
				{
					$as_valor=$as_valor+$_SESSION["la_personalnomina"]["totalarc"];
				}
				break;
				
			case "ANTIG_REINGRESO": // antiguedad em fecha de Reingreso en días
				$as_valor=0;
				$ld_fecculcontr=$_SESSION["la_personalnomina"]["fecculcontr"];
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				if(substr($ld_fecculcontr,0,10)!="1900-01-01")
				{
					$ld_diades=substr($ld_fecculcontr,8,2);
					$ld_mesdes=substr($ld_fecculcontr,5,2);
					$ld_anodes=substr($ld_fecculcontr,0,4);
					$ld_diahas=substr($ld_fechasper,8,2);
					$ld_meshas=substr($ld_fechasper,5,2);
					$ld_anohas=substr($ld_fechasper,0,4);
					$as_valor=((mktime(0,0,0,$ld_meshas,$ld_diahas,$ld_anohas) - mktime(0,0,0,$ld_mesdes,$ld_diades,$ld_anodes))/86400)+1;
					$as_valor=round($as_valor);
				}
				break;
				
			case "CAPITALIZA_ANT_COMP": // si Capitaliza antiguedad complementaria 1 sino 0 
				$as_valor=$_SESSION["la_personalnomina"]["capantcom"];
				switch($as_valor)
				{
					case "0": // No capitaliza
						$as_valor=1;
						break;
					case "": // No capitaliza
						$as_valor=1;
						break;
					case "1": // Si capitaliza
						$as_valor=0;
						break;
				}
				break;

			case "NEXO_CONYUGUE": // Si tiene un familiar con nexo tipo conyugue
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$lb_valido=$this->io_familiar->uf_load_nexofamiliar($ls_codper,'C',$as_valor);
				break;

			case "NEXO_HIJO": // Si tiene un familiar con nexo tipo Hijo
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$lb_valido=$this->io_familiar->uf_load_nexofamiliar($ls_codper,'H',$as_valor);
				break;

			case "NEXO_PROGENITOR": // Si tiene un familiar con nexo tipo Progenitor
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$lb_valido=$this->io_familiar->uf_load_nexofamiliar($ls_codper,'P',$as_valor);
				break;

			case "NEXO_HERMANO": // Si tiene un familiar con nexo tipo Hermano
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$lb_valido=$this->io_familiar->uf_load_nexofamiliar($ls_codper,'E',$as_valor);
				break;

			case "SEXO_CONYUGUE": // Si tiene un familiar con nexo tipo Conyugue obtiene el Sexo
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$lb_valido=$this->io_familiar->uf_load_sexofamiliar($ls_codper,'C',$as_valor);
				break;

			case "HC_CONYUGUE": // Si tiene un familiar con nexo tipo conyugue y tiene HC
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$lb_valido=$this->io_familiar->uf_load_hcfamiliar($ls_codper,'C',$as_valor);
				break;

			case "HC_HIJO": // Si tiene un familiar con nexo tipo Hijo y tiene HC
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$lb_valido=$this->io_familiar->uf_load_hcfamiliar($ls_codper,'H',$as_valor);
				break;

			case "HC_PROGENITOR": // Si tiene un familiar con nexo tipo Progenitor y tiene HC
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$lb_valido=$this->io_familiar->uf_load_hcfamiliar($ls_codper,'P',$as_valor);
				break;

			case "HC_HERMANO": // Si tiene un familiar con nexo tipo Hermano y tiene HC
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$lb_valido=$this->io_familiar->uf_load_hcfamiliar($ls_codper,'E',$as_valor);
				break;

			case "HCM_CONYUGUE": // Si tiene un familiar con nexo tipo conyugue y tiene HCM
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$lb_valido=$this->io_familiar->uf_load_hcmfamiliar($ls_codper,'C',$as_valor);
				break;

			case substr($as_token,0,13)=="EDAD_CONYUGUE": // Número de Conyugues comprendidos en un rango de edades
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				$li_edaddesde=intval(substr($as_token,14,2));
				$li_edadhasta=intval(substr($as_token,17,2));
				$lb_valido=$this->io_familiar->uf_load_totalfamiliar($ls_codper,'C',$li_edaddesde,$li_edadhasta,$ld_fechasper,
																	 $as_valor);
				break;

			case substr($as_token,0,9)=="EDAD_HIJO": // Número de Hijos comprendidos en un rango de edades
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				$li_edaddesde=intval(substr($as_token,10,2));
				$li_edadhasta=intval(substr($as_token,13,2));
				$lb_valido=$this->io_familiar->uf_load_totalfamiliar($ls_codper,'H',$li_edaddesde,$li_edadhasta,$ld_fechasper,
																	 $as_valor);
				break;

			case substr($as_token,0,15)=="EDAD_PROGENITOR": // Número de Progenitores comprendidos en un rango de edades
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				$li_edaddesde=intval(substr($as_token,16,2));
				$li_edadhasta=intval(substr($as_token,19,2));
				$lb_valido=$this->io_familiar->uf_load_totalfamiliar($ls_codper,'P',$li_edaddesde,$li_edadhasta,$ld_fechasper,
																	 $as_valor);
				break;

			case substr($as_token,0,12)=="EDAD_HERMANO": // Número de Hermanos comprendidos en un rango de edades
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				$li_edaddesde=intval(substr($as_token,13,2));
				$li_edadhasta=intval(substr($as_token,16,2));
				$lb_valido=$this->io_familiar->uf_load_totalfamiliar($ls_codper,'E',$li_edaddesde,$li_edadhasta,$ld_fechasper,
																	 $as_valor);
				break;
					
			case substr($as_token,0,6)=="CN_ANT": // Verifica si el concepto fue cobrado por el personal los 3 períodos Anteriores
				$ls_codper=$_SESSION["la_personalnomina"]["codper"];
				$as_valor=false;
				$li_anocurnom=intval($_SESSION["la_nomina"]["anocurnom"]);
				$li_anoantnom=$li_anocurnom-1;
				$li_peractnom=intval($_SESSION["la_nomina"]["peractnom"]);
				$li_numpernom=intval($_SESSION["la_nomina"]["numpernom"]);
				switch($li_peractnom)
				{
					case 3:
						$li_numpernom=str_pad($li_numpernom,3,"0",0);
						$ls_criterio=" AND ((sno_hsalida.anocur='".$li_anocurnom."' ".
									 " AND (sno_hsalida.codperi='001' OR sno_hsalida.codperi='002')) ".
									 "	OR  (sno_hsalida.anocur='".$li_anoantnom."' AND sno_hsalida.codperi='".$li_numpernom."'))";
						break;

					case 2:
						$li_numperant=$li_numpernom-1;
						$li_numpernom=str_pad($li_numpernom,3,"0",0);
						$li_numperant=str_pad($li_numperant,3,"0",0);
						$ls_criterio=" AND ((sno_hsalida.anocur='".$li_anocurnom."' AND sno_hsalida.codperi='001') ".
									 "	OR  (sno_hsalida.anocur='".$li_anoantnom."' ".
									 " AND (sno_hsalida.codperi='".$li_numpernom."' OR sno_hsalida.codperi='".$li_numperant."')))";
						break;

					case 1:
						$li_numperant2=$li_numpernom-2;
						$li_numperant=$li_numpernom-1;
						$li_numpernom=str_pad($li_numpernom,3,"0",0);
						$li_numperant=str_pad($li_numperant,3,"0",0);
						$li_numperant2=str_pad($li_numperant2,3,"0",0);
						$ls_criterio=" AND (sno_hsalida.anocur='".$li_anoantnom."' AND (sno_hsalida.codperi='".$li_numpernom."' ".
									 "  OR sno_hsalida.codperi='".$li_numperant."' OR sno_hsalida.codperi='".$li_numperant2."'))";
						break;
						
					default:
						$li_numperant2=$li_peractnom-3;
						$li_numperant=$li_peractnom-2;
						$li_peractnom=$li_peractnom-1;
						$li_peractnom=str_pad($li_peractnom,3,"0",0);
						$li_numperant=str_pad($li_numperant,3,"0",0);
						$li_numperant2=str_pad($li_numperant2,3,"0",0);
						$ls_criterio=" AND (sno_hsalida.anocur='".$li_anocurnom."'  AND (sno_hsalida.codperi='".$li_peractnom."' ".
									 "  OR sno_hsalida.codperi='".$li_numperant."' OR sno_hsalida.codperi='".$li_numperant2."')) ";
						break;
				}
				$ls_concepto=substr($as_token,7,10);
				$ls_concepto=str_pad($ls_concepto,10,"0",0);
				$lb_valido=$this->uf_obtener_concepto_ante($ls_codper,$ls_concepto,$ls_criterio,$lb_cobrado);
				if($lb_valido)
				{
					$as_valor=$lb_cobrado;
				}
				break;

			case "PROFESIONAL": // Si el personal es profesional
				$as_valor=0;
				$li_nivacaper=intval($_SESSION["la_personalnomina"]["nivacaper"]);
				if(($li_nivacaper==4)||($li_nivacaper==5)||($li_nivacaper==6)||($li_nivacaper==7))
				{
					$as_valor=1;
				}
				break;

			case "NIVEL_ACADEMICO": // Devuelve el Nivel academico de las persona
				$as_valor=$_SESSION["la_personalnomina"]["nivacaper"];
				break;

			case "TSU": // Si el personal es Tecnico Superior
				$as_valor=0;
				$li_nivacaper=intval($_SESSION["la_personalnomina"]["nivacaper"]);
				if($li_nivacaper==3)
				{
					$as_valor=1;
				}
				break;

			case "DIAS_BONO_TABVAC": // Días Tabla de vacaciones según el personal
				$as_valor=0;
				$ls_codtabvac=$_SESSION["la_personalnomina"]["codtabvac"];
				$li_anoservpreper=$_SESSION["la_personalnomina"]["anoservpreper"];
				$ld_fecingper=$_SESSION["la_personalnomina"]["fecingper"];
				$ld_fechasper=substr($_SESSION["la_nomina"]["fechasper"],0,4);
				$ld_fecing=substr($ld_fecingper,0,4);
				$li_anios=$ld_fechasper-$ld_fecing;
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				if(intval(substr($ld_fechasper,5,2))<intval(substr($ld_fecingper,5,2)))
				{
					$li_anios=$li_anios-1;
				}
				else
				{
					if(intval(substr($ld_fechasper,5,2))==intval(substr($ld_fecingper,5,2)))
					{
						if(intval(substr($ld_fechasper,8,2))<intval(substr($ld_fecingper,8,2)))
						{
							$li_anios=$li_anios-1;
						}
					}
				}
				$lb_valido=$this->uf_obtener_dias_tabla_vacacion($ls_codtabvac,$li_anios,$li_anoservpreper,&$as_valor);
				break;

			case "DIAS_BONO_TABVAC_MES": // Días Tabla de vacaciones según el personal
				$as_valor=0;
				$ls_codtabvac=$_SESSION["la_personalnomina"]["codtabvac"];
				$li_anoservpreper=$_SESSION["la_personalnomina"]["anoservpreper"];
				$ld_fecingper=$_SESSION["la_personalnomina"]["fecingper"];
				$ld_fechasper=substr($_SESSION["la_nomina"]["fechasper"],0,4);
				$ld_fecing=substr($ld_fecingper,0,4);
				$li_anios=$ld_fechasper-$ld_fecing;
				$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
				if(intval(substr($ld_fechasper,5,2))<intval(substr($ld_fecingper,5,2)))
				{
					$li_anios=$li_anios-1;
				}
				$lb_valido=$this->uf_obtener_dias_tabla_vacacion($ls_codtabvac,$li_anios,$li_anoservpreper,&$as_valor);
				break;

			case "CAJA_AHORRO": // Si el personal tiene caja de ahorro
				$as_valor=intval($_SESSION["la_personalnomina"]["cajahoper"]);
				break;
			///----------------------------calculo de las deducciones configurables-----------------------------------------
			case substr(trim ($as_token),0,9)=="DEDUCCION": // Deducciones personales	
			   	$ls_codper=$_SESSION["la_personalnomina"]["codper"]; // código del personal
				$ls_codtipded=substr($as_token,10,10);  // código del tipo de deducción	
				$ls_codtipded=str_pad($ls_codtipded,10,"0","LEFT");
				$ls_sueldo=$_SESSION["la_personalnomina"]["sueper"]; // Sueldo del personal					
				// ---------------------------- Para calcular la edad de la persona-----------------------------------------
				$ld_fechas=substr($_SESSION["la_nomina"]["fechasper"],0,4);
				$ld_fecnacper=substr($_SESSION["la_personalnomina"]["fecnacper"],0,4);
				$ls_edad=$ld_fechas-$ld_fecnacper;
				$ld_fechas=$_SESSION["la_nomina"]["fechasper"];
				$ld_fecnacper=$_SESSION["la_personalnomina"]["fecnacper"];
				if(intval(substr($ld_fechas,5,2))<intval(substr($ld_fecnacper,5,2)))
				{
					$ls_edad=$ls_edad-1;
				}
				else
				{
					if(intval(substr($ld_fechas,5,2))==intval(substr($ld_fecnacper,5,2)))
					{
						if(intval(substr($ld_fechas,8,2))<intval(substr($ld_fecnacper,8,2)))
						{
							$ls_edad=$ls_edad-1;
						}
					}
				}	
				///----------------------fin del calculo de la edad de la persona----------------------------------------------			
				$ls_sexo="'".$_SESSION["la_personalnomina"]["sexper"]."'"; // Sexo de la persona
				
				$lb_valido=$this->io_tipodeduccion->uf_srh_buscar_deduccion($ls_codper,$ls_codtipded,'1', $ls_sueldo, $ls_edad,
				                                                            $ls_sexo,$as_valor);
																			
				break;
				
				case substr(trim ($as_token),0,14)=="DEDUC_FAMILIAR": // Deducciones de los familiares
					$ls_codper=$_SESSION["la_personalnomina"]["codper"]; // código del personal
					$ls_codtipded=substr($as_token,15,10); // código del tipo de deducción						
					$ls_codtipded=str_pad($ls_codtipded,10,"0","LEFT");												
					$ls_sueldo=$_SESSION["la_personalnomina"]["sueper"]; // Sueldo del personal
					$ld_fecha_hasta=$_SESSION["la_nomina"]["fechasper"]; /// fecha hasta del lapso de la nomina
					$lb_valido=$this->io_tipodeduccion->uf_srh_buscar_deduccion_familiar($ls_codper,$ls_codtipded,'1',$ls_sueldo,
																						 $ld_fecha_hasta, $as_valor);
				break;
				
			   case substr(trim ($as_token),0,16)=="PATRON_DEDUCCION": // Aporte del Patrono para las deducciones personales
			        $ls_codper=$_SESSION["la_personalnomina"]["codper"]; // código del personal
					$ls_codtipded=substr($as_token,17,10); // código del tipo de deducción	
					$ls_codtipded=str_pad($ls_codtipded,10,"0","LEFT");															
					$ls_sueldo=$_SESSION["la_personalnomina"]["sueper"]; // Sueldo del personal
					// ---------------------------- Para calcular la edad de la persona-----------------------------------
					$ld_fechas=substr($_SESSION["la_nomina"]["fechasper"],0,4);
					$ld_fecnacper=substr($_SESSION["la_personalnomina"]["fecnacper"],0,4);
					$ls_edad=$ld_fechas-$ld_fecnacper;
					$ld_fechas=$_SESSION["la_nomina"]["fechasper"];
					$ld_fecnacper=$_SESSION["la_personalnomina"]["fecnacper"];
					if(intval(substr($ld_fechas,5,2))<intval(substr($ld_fecnacper,5,2)))
					{
						$ls_edad=$ls_edad-1;
					}
					else
					{
						if(intval(substr($ld_fechas,5,2))==intval(substr($ld_fecnacper,5,2)))
						{
							if(intval(substr($ld_fechas,8,2))<intval(substr($ld_fecnacper,8,2)))
							{
								$ls_edad=$ls_edad-1;
							}
						}
					}
					// ------------------------------fin del calculo de la persona-----------------------------------------
					$ls_sexo="'".$_SESSION["la_personalnomina"]["sexper"]."'"; // Sexo de la persona
				
					$lb_valido=$this->io_tipodeduccion->uf_srh_buscar_deduccion($ls_codper,$ls_codtipded,'2',$ls_sueldo, $ls_edad,
				                                                            $ls_sexo, $as_valor);				
				break;
							
			case substr(trim($as_token),0,21)=="DEDUC_PATRON_FAMILIAR": //Aporte del Patrono para las deducciones de los familiares
			    
				$ls_codper=$_SESSION["la_personalnomina"]["codper"]; // código del personal
				$ls_codtipded=substr($as_token,22,10); // código del tipo de deducción
				$ls_codtipded=str_pad($ls_codtipded,10,"0","LEFT");											
				$ls_sueldo=$_SESSION["la_personalnomina"]["sueper"]; // Sueldo del personal
				$ld_fecha_hasta=$_SESSION["la_nomina"]["fechasper"]; /// fecha hasta del lapso de la nomina
				$lb_valido=$this->io_tipodeduccion->uf_srh_buscar_deduccion_familiar($ls_codper,$ls_codtipded,'2',$ls_sueldo,
																					 $ld_fecha_hasta, $as_valor);				
				break;
            //-------------------------------------------------------FIN DE LAS DEDUCCIONES CONFIGURABLES-------------------

			case "DEDICACION": // Código de la dedicación del personal
				$as_valor=$_SESSION["la_personalnomina"]["codded"];
				break;
            
			case "TIP_PERSONAL": // Código del tipo de personal
				$as_valor=$_SESSION["la_personalnomina"]["codtipper"];
				break;
            
			case "CLASIFICACION_DOC": // Código de la dedicación del personal
				$as_valor=$_SESSION["la_personalnomina"]["codcladoc"];
				break;
            
			case "ESCALA_DOC": // Código del tipo de personal
				$as_valor=$_SESSION["la_personalnomina"]["codescdoc"];
				break;
			
			//---------------------------------------------------------------------------------------------------
			case "NRO_LUNES": // Número de lunes que tiene el período			
				$ld_fecdes=$_SESSION["la_nomina"]["fecdesper"];
				$ld_fechas=$_SESSION["la_nomina"]["fechasper"];
				$ls_fechaI=$_SESSION["la_personalnomina"]["fecingper"];
				$ls_valido=$this->io_fecha->uf_comparar_fecha($ls_fechaI,$ld_fecdes);
				if ($ls_valido!=true)
				{
				 	$ld_fecdes=$ls_fechaI;
				}
				$as_valor=$this->io_sno->uf_nro_lunes($ld_fecdes,$ld_fechas); //print $as_valor;
				break;

			case "NRO_LUNESMES": // Número de lunes que tiene el mes
				$ld_fecdes=$_SESSION["la_nomina"]["fecdesper"];
				$ld_fechas=$_SESSION["la_nomina"]["fechasper"];
				$ls_fechaI=$_SESSION["la_personalnomina"]["fecingper"];
				$ls_valido=$this->io_fecha->uf_comparar_fecha($ls_fechaI,$ld_fecdes);
				if ($ls_valido==true)
				{
				  	$ls_fecha=substr($ld_fecdes,0,8)."01";
					$ls_valido2=$this->io_fecha->uf_comparar_fecha($ls_fecha,$ls_fechaI);
					if ($ls_valido2==true)
					{
					  	$ld_desde=substr($ls_fechaI,0,8).substr($ls_fechaI,8,10);				
					}
					else
					{
						$ld_desde=substr($ld_fecdes,0,8)."01";
					}
				}
				else
				{
					$ld_fecdes=$ls_fechaI;
				    $ld_desde=substr($ld_fecdes,0,8).substr($ld_fecdes,8,10);
					 
				}
				$ld_diahasta=strftime("%d",mktime(0,0,0,(substr($ld_fecdes,5,2)+1),0,substr($ld_fecdes,0,4)));
				 
				$ld_hasta=substr($ld_fecdes,0,8).$ld_diahasta;
				$as_valor=$this->io_sno->uf_nro_lunes($ld_desde,$ld_hasta);
				break;
			//--------------------------------------------------------------------------------------------------
            
			 default:
				$this->io_mensajes->message("ERROR->PERSONAL PS[".$as_token."] Nó Válido.");
				$lb_valido=false;
				break;

		}
		return $lb_valido;
	}// end function uf_valor_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_valor_tabla($as_codper,$as_token,&$ai_valor)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valor_tabla
		//		   Access: private 
		//	    Arguments: as_codper // código de personal
		//				   as_token // valor que va a ser reemplazado
		//				   ai_valor // valor del token
		//	      Returns: lb_valido True si se sustituye correctamente el valor ó False si hubo error 
		//	  Description: función que dado un token se sutituye por su valor respectivo de la tabla de sueldo
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_valor=0;
		$lb_valido=true;
		$ls_sql="SELECT sno_grado.monsalgra ".
				"  FROM sno_personalnomina, sno_grado ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_personalnomina.codper='".$as_codper."' ".
				"   AND sno_personalnomina.codtab='".$as_token."' ".
				"   AND sno_personalnomina.codemp=sno_grado.codemp ".
				"   AND sno_personalnomina.codnom=sno_grado.codnom ".
				"   AND sno_personalnomina.codtab=sno_grado.codtab ".
				"   AND sno_personalnomina.codgra=sno_grado.codgra ".
				"   AND sno_personalnomina.codpas=sno_grado.codpas ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("ERROR->TABLA SUELDO TB[".$as_token."] Nó Válido.");
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_valor=$row["monsalgra"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_valor_tabla
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_valor_concepto($as_codper,$as_token,&$ai_valor)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valor_concepto
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_token // valor que va a ser reemplazado
		//				   ai_valor // valor del token
		//	      Returns: lb_valido True si se sustituye correctamente el valor ó False si hubo error 
		//	  Description: función que dado un token se sutituye por su valor respectivo del concepto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_aplicar=true;
		$ai_valor=0;
		$as_formula="";
		$lb_valido=true;
		$ls_codconc=$_SESSION["la_conceptopersonal"]["codconc"];
		if(intval($ls_codconc)>intval($as_token))
		{
			$lb_valido=$this->io_concepto->uf_obtener_conceptopersonal($as_codper,$as_token,$la_concepto);
			if($lb_valido)
			{
				if($la_concepto["glocon"]==false)
				{
					$lb_aplicar=$la_concepto["aplcon"];
				}
		
				if($lb_aplicar)
				{
					$as_formula=$la_concepto["forcon"];
					$lb_valido=$this->uf_evaluar($as_codper,$as_formula,$ai_valor);
				}
			}		
			else
			{
				$this->io_mensajes->message("ERROR->CONCEPTO CN[".$as_token."] Nó Válido. Utilizado en el Concepto ".$ls_codconc);
			}
		}
		else
		{
			$this->io_mensajes->message("ERROR->Error de Anidamiento en Concepto ".$ls_codconc.".");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_valor_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_valor_constante($as_codper,$as_token,&$ai_valor)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valor_constante
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_token // valor que va a ser reemplazado
		//				   ai_valor // valor del token
		//	      Returns: lb_valido True si se sustituye correctamente el valor ó False si hubo error 
		//	  Description: función que dado un token se sutituye por su valor respectivo de la constante
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_valor=0;
		$lb_valido=true;
		$ls_codconc=$_SESSION["la_conceptopersonal"]["codconc"];
		$lb_valido=$this->io_constante->uf_obtener_constantepersonal($as_codper,$as_token,$ai_valor);
		if($lb_valido===false)
		{
			$this->io_mensajes->message("ERROR->CONSTANTE CT[".$as_token."] Nó Válido. Utilizado en el Concepto ".$ls_codconc);
		}
		return $lb_valido;
	}// end function uf_valor_constante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_vtc($as_codper,$as_codconc,&$ai_vtc)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_vtc
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_codconc // código del concepto
		//				   ai_vtc // Total a cobrar
		//	      Returns: lb_valido True si se obtuvo el concepto ó False si no se obtuvo
		//	  Description: función que dado el código de personal y concepto calcula todas las asignaciones menos las deducciones
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_vtc=0;
		$ls_sql="SELECT sum(valsal) as valsal ".
				"  FROM sno_salida ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.codconc<>'".$as_codconc."' ".
				"   AND sno_salida.codper='".$as_codper."' ".
				"   AND (sno_salida.tipsal='A' OR sno_salida.tipsal='D' OR sno_salida.tipsal='P1') ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_obtener_vtc ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_vtc=$row["valsal"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_obtener_vtc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_vca($as_codper,$as_codconc,$as_anopre,$as_perpre,&$ai_vca)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_vca
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_codconc // código del concepto
		//				   as_anopre // Ano previo
		//				   as_perpre // perido previo
		//				   ai_vca // Toatl del concepto
		//	      Returns: lb_valido True si se obtuvo el concepto ó False si no se obtuvo
		//	  Description: función que dado el código de personal y concepto calcula el valor del concepto en un período previo
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_vtc=0;
		$ls_sql="SELECT sum(valsal) as valsal ".
				"  FROM sno_hsalida ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.codperi='".$as_perpre."' ".
				"   AND sno_hsalida.anocur='".$as_anopre."' ".
				"   AND sno_hsalida.codconc='".$as_codconc."' ".
				"   AND sno_hsalida.codper='".$as_codper."' ".
				"   AND (sno_hsalida.tipsal='A' OR sno_hsalida.tipsal='D' OR sno_hsalida.tipsal='P1')";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_obtener_vca ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_vtc=$row["valsal"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_obtener_vca
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_primas_ante($as_codper,$as_mes,$as_anopre,&$ai_prima)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_primas_ante
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_codconc // código del concepto
		//				   as_anopre // Ano previo
		//				   as_perpre // perido previo
		//				   ai_vca // Toatl del concepto
		//	      Returns: lb_valido True si se obtuvo el concepto ó False si no se obtuvo
		//	  Description: función que dado el código de personal y busca el sueldo integral de vacaciones dado un mes y año
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 15/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_prima=0;
		$ls_sql="SELECT sum(valsal) as valsal ".
				"  FROM sno_hsalida, sno_hperiodo ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."'".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."'".
				"   AND sno_hsalida.anocur='".$as_anopre."'".
				"   AND sno_hsalida.codper='".$as_codper."'".
				"   AND substr(sno_hperiodo.fecdesper,6,2)='".$as_mes."'".
				"   AND (sno_hsalida.tipsal='A' OR sno_hsalida.tipsal='D' OR sno_hsalida.tipsal='P1') ".
				"   AND sno_hsalida.codemp = sno_hperiodo.codemp ".
				"   AND sno_hsalida.codnom = sno_hperiodo.codnom ".
				"   AND sno_hsalida.anocur = sno_hperiodo.anocur ".
				"   AND sno_hsalida.codperi = sno_hperiodo.codperi ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_obtener_primas_ante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_prima=$row["valsal"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_obtener_primas_ante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_sueldo_ante($as_codper,$as_mes,$as_anocur,&$ai_sueldo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_sueldo_ante
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_mes // Mes del que se quiere calcular el sueldo
		//				   as_anopre // Ano previo
		//				   ai_sueldo // Sueldo Anterior
		//	      Returns: lb_valido True si se obtuvo el concepto ó False si no se obtuvo
		//	  Description: función que dado el código de personal y busca el sueldo Anterior de acuerdo al mes
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 23/11/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_sueldo=0;
		$ls_concepto=$this->io_sno->uf_select_config("SNO","CONFIG","CONCEPTO_SUELDO_ANT","XXXXXXXXXX","C");
		$ls_sql="SELECT sum(valsal) as valsal ".
				"  FROM sno_hsalida, sno_hperiodo ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."'".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."'".
				"   AND sno_hsalida.anocur='".$as_anocur."'".
				"   AND sno_hsalida.codper='".$as_codper."'".
				"   AND sno_hsalida.codconc='".$ls_concepto."'".
				"   AND substr(sno_hperiodo.fecdesper,6,2)='".$as_mes."'".
				"   AND sno_hsalida.codemp = sno_hperiodo.codemp ".
				"   AND sno_hsalida.codnom = sno_hperiodo.codnom ".
				"   AND sno_hsalida.anocur = sno_hperiodo.anocur ".
				"   AND sno_hsalida.codperi = sno_hperiodo.codperi ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_obtener_sueldo_ante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_sueldo=$row["valsal"];
			}
			if(empty($ai_sueldo))
			{
				$ai_sueldo=0;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_obtener_sueldo_ante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_concepto_ante($as_codper,$as_codconc,$as_criterio,&$ab_cobrado)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_concepto_ante
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_codconc // código del concepto
		//				   as_criterio // Criterio de Busqueda
		//				   ab_cobrado // si el concepto es distinto de cero
		//	      Returns: lb_valido True si se obtuvo el concepto ó False si no se obtuvo
		//	  Description: función que dado el código de personal y el concepto verifica si dicho concepto fue pagado
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 03/08/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ab_cobrado=false;
		$ls_sql="SELECT sum(valsal) as valsal ".
				"  FROM sno_hsalida ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."'".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."'".
				"   AND sno_hsalida.codper='".$as_codper."'".
				"   AND sno_hsalida.codconc='".$as_codconc."'".
				$as_criterio;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_obtener_concepto_ante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_valor=round($row["valsal"]);
				if($ai_valor<>0)
				{
					$ab_cobrado=true;
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_obtener_concepto_ante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_dias_tabla_vacacion($as_codtabvac,$ai_anoant,$ai_anopre,&$ai_diabonvac)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_tablavacacion
		//		   Access: public (tepuy_sno_c_vacacion)
		//	    Arguments: as_codtabvac  // código de la tabla de vacacion
		//	    		   ai_anoant  // Año de antiguedad
		//	    		   ai_anopre  // Años de servicio previos
		//	    		   ai_diabonvac  // Días de Bono vacacional
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que verifica si la tabla de vacacion está registrada
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_diabonvac=0;
		$ls_sql="SELECT pertabvac, anoserpre ".
				"  FROM sno_tablavacacion ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codtabvac='".$as_codtabvac."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_obtener_dias_tabla_vacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				if($row["anoserpre"]==1)
				{
					$ai_anoant=$ai_anoant+$ai_anopre;
				}
				if($row["pertabvac"]==0)
				{
					$li_anoxper=5;// Quinquenal
				}
				else
				{
					$li_anoxper=1;// Anual
				}
				$li_quinquenio=(($ai_anoant-1)/$li_anoxper)+1;
			}
			$this->io_sql->free_result($rs_data);
			$ls_sql="SELECT diadisvac, diabonvac, diaadidisvac, diaadibonvac ".
					"  FROM sno_tablavacperiodo ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codtabvac='".$as_codtabvac."'".
					"   AND lappervac<=".$li_quinquenio."".
					" ORDER BY lappervac DESC ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_obtener_dias_tabla_vacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ai_diabonvac=$row["diabonvac"]+$row["diaadibonvac"];
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}// end function uf_obtener_dias_tabla_vacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_sueldominimo(&$ai_sueldominimo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_sueldominimo
		//		   Access: private
		//	    Arguments: ai_sueldominimo // Sueldo Mínimo
		//	      Returns: lb_valido True si se obtuvo el Sueldo mínimo ó False si no se obtuvo
		//	  Description: función que busca el sueldo mínimo vigente
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 14/04/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_sueldominimo=0;
		$ls_sql="SELECT monsuemin ".
				"  FROM sno_sueldominimo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND fecvigsuemin <= '".$_SESSION["la_nomina"]["fechasper"]."'".
				" ORDER BY fecvigsuemin ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_obtener_sueldominimo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_sueldominimo=number_format($row["monsuemin"],2,".","");
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_obtener_concepto_ante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_unidadtributaria($as_anio,&$ai_unidadtributaria)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_unidadtributaria
		//		   Access: private
		//	    Arguments: as_anio // Año
		//	   			   ai_unidadtributaria // Valor de la unidad tributaria
		//	      Returns: lb_valido True si se obtuvo la Unidad Tributaria ó False si no se obtuvo
		//	  Description: función que busca la unidad tributaria del año
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 14/04/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_unidadtributaria=0;
		$ls_sql="SELECT valunitri ".
				"  FROM tepuy_unidad_tributaria ".
				" WHERE anno = '".$as_anio."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_obtener_unidadtributaria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_unidadtributaria=number_format($row["valunitri"],3,".","");
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_obtener_unidadtributaria
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_montoanterior($as_codper,$as_tipsal,$ai_meses,&$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_montoanterior
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_tipsal // Tipo de salida
		//				   ai_meses // Meses anteriores
		//				   ai_monto // Monto
		//	      Returns: lb_valido True si se obtuvo el concepto ó False si no se obtuvo
		//	  Description: función que dado el código de personal y obtiene la suma de los concepto del tipo de salida
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 19/06/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_monto=0;
		$li_mesact=substr($_SESSION["la_nomina"]["fechasper"],5,2);
		$ls_anocur=$_SESSION["la_nomina"]["anocurnom"];
		$li_mesdes=($li_mesact-$ai_meses);
		if(intval($li_mesdes)<0)
		{
			$li_mesdes=1;
		}
		$li_mesdes=str_pad($li_mesdes,2,"0",0);
		switch($as_tipsal)
		{
			case "AS": // Asignación
				$as_tipsal=" (sno_hsalida.tipsal='A' OR sno_hsalida.tipsal='V1' OR sno_hsalida.tipsal='W1') ";
				break;
			case "DE": // Deducción
				$as_tipsal=" (sno_hsalida.tipsal='D' OR sno_hsalida.tipsal='V2' OR sno_hsalida.tipsal='W2') ";
				break;
			case "AP": // Aportes
				$as_tipsal=" (sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='V3' OR sno_hsalida.tipsal='W3') ";
				break;
		}
		$ls_sql="SELECT sum(valsal) as valsal ".
				"  FROM sno_hsalida, sno_hperiodo ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_hsalida.anocur='".$ls_anocur."' ".
				"   AND sno_hsalida.codper='".$as_codper."' ".
				"   AND SUBSTR(sno_hperiodo.fecdesper,6,2) >= '".$li_mesdes."' ".
				"   AND SUBSTR(sno_hperiodo.fecdesper,6,2) < '".$li_mesact."' ".
				"   AND ".$as_tipsal.
				"   AND sno_hsalida.codemp = sno_hperiodo.codemp ".
				"   AND sno_hsalida.codnom = sno_hperiodo.codnom ".
				"   AND sno_hsalida.anocur = sno_hperiodo.anocur ".
				"   AND sno_hsalida.codperi = sno_hperiodo.codperi ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_obtener_vca ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monto=abs($row["valsal"]);
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_obtener_montoanterior
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_montoarc_mesactual($as_codper,&$ai_totalarcant)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_montoarc_mesactual
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//	    		   ai_totalarcant // Monto ARC anterior
		//	      Returns: lb_valido True si se creo la variable sesion ó False si no se creo
		//	  Description: función que dado el código de personal obtiene la suma de todos los 
		//				   conceptos que pertenecen al arc del periodo inmediato anterior
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 15/08/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_totalarcant=0;
		$li_mesact=substr($_SESSION["la_nomina"]["fechasper"],5,2);
		$ls_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
		$ls_sql="SELECT SUM(sno_hsalida.valsal)  as total".
				"  FROM sno_hsalida, sno_hconcepto, sno_hperiodo ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."'".
				"   AND sno_hsalida.codnom='".$this->ls_codnom."'".
				"   AND sno_hsalida.anocur='".$ls_anocurnom."'".
				"   AND sno_hsalida.codper='".$as_codper."'".
				"   AND sno_hconcepto.sigcon='A'".
				"   AND sno_hconcepto.aplarccon=1".
				"   AND SUBSTR(sno_hperiodo.fecdesper,6,2) = '".$li_mesact."' ".
				"   AND sno_hsalida.codemp = sno_hperiodo.codemp ".
				"   AND sno_hsalida.codnom = sno_hperiodo.codnom ".
				"   AND sno_hsalida.anocur = sno_hperiodo.anocur ".
				"   AND sno_hsalida.codperi = sno_hperiodo.codperi ".
				"   AND sno_hsalida.codemp=sno_hconcepto.codemp".
				"   AND sno_hsalida.codnom=sno_hconcepto.codnom".
				"   AND sno_hsalida.anocur=sno_hconcepto.anocur".
				"   AND sno_hsalida.codperi=sno_hconcepto.codperi".
				"   AND sno_hsalida.codconc=sno_hconcepto.codconc";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Evaluador MÉTODO->uf_obtener_montoarc_mesactual ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totalarcant=number_format($row["total"],2,".","");
			}
		}
		return $lb_valido;
	}// end function uf_obtener_montoarc_mesactual
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>