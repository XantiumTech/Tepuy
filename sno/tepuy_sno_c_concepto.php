<?php
class tepuy_sno_c_concepto
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_prestamo;
	var $io_vacacionconcepto;
	var $io_primaconcepto;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_sno_c_concepto()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_sno_c_concepto
		//		   Access: public (tepuy_sno_d_concepto)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
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
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		$this->io_seguridad= new tepuy_c_seguridad();
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
		require_once("tepuy_sno_c_prestamo.php");
		$this->io_prestamo=new tepuy_sno_c_prestamo();
		require_once("tepuy_sno_c_vacacionconcepto.php");
		$this->io_vacacionconcepto=new tepuy_sno_c_vacacionconcepto();
		require_once("tepuy_sno_c_primaconcepto.php");
		$this->io_primaconcepto=new tepuy_sno_c_primaconcepto();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if(array_key_exists("la_nomina",$_SESSION))
		{
        	$this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
	        $this->ls_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
	        $this->ls_codperi=$_SESSION["la_nomina"]["peractnom"];
		}
		else
		{
			$this->ls_codnom="0000";
	        $this->ls_anocurnom="0000";
	        $this->ls_codperi="000";
		}
		
	}// end function tepuy_sno_c_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (tepuy_sno_d_concepto)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fun_nomina);
		unset($this->io_prestamo);
		unset($this->io_vacacionconcepto);
		unset($this->io_primaconcepto);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_concepto($as_codconc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_concepto
		//		   Access: private
		//	    Arguments: as_codconc // C�digo de concepto
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que verifica si el concepto est� registrado
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codconc ".
				"  FROM sno_concepto ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codconc='".$as_codconc."' ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Concepto M�TODO->uf_select_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
	}// end function uf_select_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_concepto($as_codconc,$as_nomcon,$as_titcon,$as_titconcomp,$as_forcon,$ai_acumaxcon,$ai_valmincon,$ai_valmaxcon,$as_concon,$as_cueprecon,
								$as_cueconcon,$as_codpro,$as_codpro1,$as_sigcon,$as_glocon,$as_aplisrcon,$as_sueintcon,$as_intprocon,$as_forpatcon,
								$as_cueprepatcon,$as_cueconpatcon,$as_titretempcon,$as_titretpatcon,$ai_valminpatcon,$ai_valmaxpatcon,
								$ai_conprenom,$ai_sueintvaccon,$as_codprov,$as_codben,$as_aplarccon,$as_aplconprocon,$as_repacucon,$as_repconsunicon,
								$as_consunicon,$as_quirepcon,$as_frevarcon,$as_asifidper,$as_asifidpat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_concepto
		//		   Access: private
		//	    Arguments: as_codconc  // c�digo de concepto							as_nomcon  // Nombre
		//				   as_titcon  // T�tulo											as_forcon  // Formula
		//				   ai_acumaxcon  // acumulado m�ximo							ai_valmincon  // valor m�nimo
		//				   ai_valmaxcon  // valor m�ximo								as_concon  // condici�n 
		//				   as_cueprecon  // cuenta de presupuesto						as_cueconcon  // cuenta contable
		//				   as_codpro  // c�digo de program�tica							as_sigcon  // signo
		//				   as_glocon  // si la constante es global						as_aplisrcon  // si aplica ISR
		//				   as_sueintcon  // si pertenece al sueldo integral				as_intprocon  // si esta integrada a la program�tica
		//				   as_forpatcon  // f�rmula Patronal							as_cueprepatcon  // cuenta de presupuesto patronal
		//				   as_cueconpatcon  // cuenta contable patronal					as_titretempcon  // t�tulo de retenci�n empleados
		//				   as_titretpatcon  // t�tulo de retenci�n patr�n				ai_valminpatcon  // valor m�nimo patronal
		//				   ai_valmaxpatcon  // valor m�ximo patronal                    ai_conprenom  // Concepto de Pren�mina
		//				   ai_sueintvaccon // Si pertenece al Sueldo Int Vacaciones		aa_seguridad  // arreglo de las variables de seguridad
		//				   as_codprov // c�digo de proveedor							as_codben  //  c�digo de beneficiario
		//				   as_aplarccon  // Si aplica ARC								as_aplconprocon // Aplica contabilizaci�n por proyectos
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que inserta el concepto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_concepto(codemp,codnom,codconc,nomcon,titcon,conprep,forcon,acumaxcon,valmincon,valmaxcon,concon,cueprecon,".
				"cueconcon,codpro,codpro1,sigcon,glocon,aplisrcon,sueintcon,intprocon,forpatcon,cueprepatcon,cueconpatcon,titretempcon,".
				"titretpatcon,valminpatcon,valmaxpatcon,conprenom,sueintvaccon,codprov,cedben,aplarccon,conprocon,repacucon,repconsunicon,".
				"consunicon,quirepcon,frevarcon,asifidper,asifidpat)VALUES ('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codconc."',".
				"'".$as_nomcon."','".$as_titcon."','".$as_titconcomp."','".$as_forcon."',".$ai_acumaxcon.",".$ai_valmincon.",".$ai_valmaxcon.",'".$as_concon."',".
				"'".$as_cueprecon."','".$as_cueconcon."','".$as_codpro."','".$as_codpro1."','".$as_sigcon."','".$as_glocon."','".$as_aplisrcon."',".
				"'".$as_sueintcon."','".$as_intprocon."','".$as_forpatcon."','".$as_cueprepatcon."','".$as_cueconpatcon."','".$as_titretempcon."',".
				"'".$as_titretpatcon."',".$ai_valminpatcon.",".$ai_valmaxpatcon.",".$ai_conprenom.",".$ai_sueintvaccon.",'".$as_codprov."',".
				"'".$as_codben."','".$as_aplarccon."','".$as_aplconprocon."','".$as_repacucon."','".$as_repconsunicon."','".$as_consunicon."','".$as_quirepcon."',".
				"'".$as_frevarcon."','".$as_asifidper."','".$as_asifidpat."')";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if(($li_row===false))
		{
 			$lb_valido=false;
			$this->io_sql->rollback();
			$this->io_mensajes->message("CLASE->Concepto M�TODO->uf_insert_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insert� el concepto ".$as_codconc." asociado a la n�mina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_conceptopersonal($as_codconc,$as_glocon,$aa_seguridad);
			}
			
			if($lb_valido)
			{
				$this->io_mensajes->message("El Concepto fue registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_sql->rollback();
				$this->io_mensajes->message("CLASE->Concepto M�TODO->uf_insert_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		return $lb_valido;
	}// end function uf_insert_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conceptopersonal($as_codconc,$as_glocon,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptopersonal
		//		   Access: private
		//	    Arguments: as_codconc  // c�digo de concepto
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funci�n que graba los conceptos a personal n�mina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_conceptopersonal(codemp,codnom,codper,codconc,aplcon,valcon,acuemp,acuiniemp,acupat,acuinipat) ".
				"SELECT codemp,codnom,codper,'".$as_codconc."',".$as_glocon.",0,0,0,0,0 ".
				"  FROM sno_personalnomina ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Concepto M�TODO->uf_insert_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insert� el conceptopersonal concepto ".$as_codconc.", asociado a la n�mina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_insert_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_concepto($as_codconc,$as_nomcon,$as_titcon,$as_titconcomp,$as_forcon,$ai_acumaxcon,$ai_valmincon,$ai_valmaxcon,$as_concon,$as_cueprecon,
								$as_cueconcon,$as_codpro,$as_codpro1,$as_sigcon,$as_glocon,$as_aplisrcon,$as_sueintcon,$as_intprocon,$as_forpatcon,
								$as_cueprepatcon,$as_cueconpatcon,$as_titretempcon,$as_titretpatcon,$ai_valminpatcon,$ai_valmaxpatcon,
								$ai_conprenom,$ai_sueintvaccon,$as_codprov,$as_codben,$as_aplarccon,$as_aplconprocon,$as_repacucon,$as_repconsunicon,
								$as_consunicon,$as_quirepcon,$as_frevarcon,$as_asifidper,$as_asifidpat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_concepto
		//		   Access: private
		//	    Arguments: as_codconc  // c�digo de concepto							as_nomcon  // Nombre
		//				   as_titcon  // T�tulo											as_forcon  // Formula
		//				   ai_acumaxcon  // acumulado m�ximo							ai_valmincon  // valor m�nimo
		//				   ai_valmaxcon  // valor m�ximo								as_concon  // condici�n 
		//				   as_cueprecon  // cuenta de presupuesto						as_cueconcon  // cuenta contable
		//				   as_codpro  // c�digo de program�tica							as_sigcon  // signo
		//				   as_glocon  // si la constante es global						as_aplisrcon  // si aplica ISR
		//				   as_sueintcon  // si pertenece al sueldo integral				as_intprocon  // si esta integrada a la program�tica
		//				   as_forpatcon  // f�rmula Patronal							as_cueprepatcon  // cuenta de presupuesto patronal
		//				   as_cueconpatcon  // cuenta contable patronal					as_titretempcon  // t�tulo de retenci�n empleados
		//				   as_titretpatcon  // t�tulo de retenci�n patr�n				ai_valminpatcon  // valor m�nimo patronal
		//				   ai_valmaxpatcon  // valor m�ximo patronal                    ai_conprenom  // Concepto de Pren�mina
		//				   ai_sueintvaccon // Si pertenece al Sueldo Int Vacaciones		aa_seguridad  // arreglo de las variables de seguridad
		//				   as_codprov // c�digo de proveedor							as_codben  //  c�digo de beneficiario
		//				   as_aplarccon  // Si aplica ARC								as_aplconprocon  // Aplica Contabilizaci�n por proyectos
		//	      Returns: lb_valido True si se ejecuto el update � False si hubo error en el update
		//	  Description: Funcion que actualiza el concepto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_concepto ".
				"   SET nomcon='".$as_nomcon."', ".
				"		titcon='".$as_titcon."', ".
				"		conprep='".$as_titconcomp."', ".
				"		forcon='".$as_forcon."', ".
				"		acumaxcon=".$ai_acumaxcon.", ".
				"		valmincon=".$ai_valmincon.", ".
				"		valmaxcon=".$ai_valmaxcon.", ".
				"		concon='".$as_concon."', ".
				"		cueprecon='".$as_cueprecon."', ".
				"		cueconcon='".$as_cueconcon."', ".
				"		codpro='".$as_codpro."', ".
				"		codpro1='".$as_codpro1."', ".
				"		glocon='".$as_glocon."', ".
				"		aplisrcon='".$as_aplisrcon."', ".
				"		sueintcon='".$as_sueintcon."', ".
				"		intprocon='".$as_intprocon."', ".
				"		forpatcon='".$as_forpatcon."', ".
				"		cueprepatcon='".$as_cueprepatcon."', ".
				"		cueconpatcon='".$as_cueconpatcon."', ".
				"		titretempcon='".$as_titretempcon."', ".
				"		titretpatcon='".$as_titretpatcon."', ".
				"		valminpatcon=".$ai_valminpatcon.", ".
				"		valmaxpatcon=".$ai_valmaxpatcon.", ".
				"		conprenom=".$ai_conprenom.", ".
				"		codprov='".$as_codprov."', ".
				"		cedben='".$as_codben."', ".
				"		sueintvaccon=".$ai_sueintvaccon.", ".
				"		aplarccon='".$as_aplarccon."', ".
				"		conprocon='".$as_aplconprocon."', ".
				"       repacucon='".$as_repacucon."', ".
				"		repconsunicon='".$as_repconsunicon."', ".
				"		consunicon='".$as_consunicon."', ".
				"		quirepcon='".$as_quirepcon."', ".
				"		frevarcon='".$as_frevarcon."', ".
				"		asifidper='".$as_asifidper."', ".
				"		asifidpat='".$as_asifidpat."' ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codconc='".$as_codconc."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Concepto M�TODO->uf_update_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualiz� el concepto ".$as_codconc." asociado a la n�mina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Concepto fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Concepto M�TODO->uf_update_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_concepto	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codconc,$as_nomcon,$as_titcon,$as_titconcomp,$as_forcon,$ai_acumaxcon,$ai_valmincon,$ai_valmaxcon,$as_concon,$as_cueprecon,
						$as_cueconcon,$as_codpro,$as_codpro1,$as_sigcon,$as_glocon,$as_aplisrcon,$as_sueintcon,$as_intprocon,$as_forpatcon,
						$as_cueprepatcon,$as_cueconpatcon,$as_titretempcon,$as_titretpatcon,$ai_valminpatcon,$ai_valmaxpatcon,
						$ai_conprenom,$ai_sueintvaccon,$as_codprov,$as_codben,$as_aplarccon,$as_aplconprocon,$as_repacucon,$as_repconsunicon,
						$as_consunicon,$as_quirepcon,$as_frevarcon,$as_asifidper,$as_asifidpat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (tepuy_sno_d_concepto)
		//	    Arguments: as_codconc  // c�digo de concepto							as_nomcon  // Nombre
		//				   as_titcon  // T�tulo											as_forcon  // Formula
		//				   ai_acumaxcon  // acumulado m�ximo							ai_valmincon  // valor m�nimo
		//				   ai_valmaxcon  // valor m�ximo								as_concon  // condici�n 
		//				   as_cueprecon  // cuenta de presupuesto						as_cueconcon  // cuenta contable
		//				   as_codpro  // c�digo de program�tica							as_sigcon  // signo
		//				   as_glocon  // si la constante es global						as_aplisrcon  // si aplica ISR
		//				   as_sueintcon  // si pertenece al sueldo integral				as_intprocon  // si esta integrada a la program�tica
		//				   as_forpatcon  // f�rmula Patronal							as_cueprepatcon  // cuenta de presupuesto patronal
		//				   as_cueconpatcon  // cuenta contable patronal					as_titretempcon  // t�tulo de retenci�n empleados
		//				   as_titretpatcon  // t�tulo de retenci�n patr�n				ai_valminpatcon  // valor m�nimo patronal
		//				   ai_valmaxpatcon  // valor m�ximo patronal                    ai_conprenom  // Concepto de Pren�mina
		//				   ai_sueintvaccon // Si pertenece al Sueldo Int Vacaciones		aa_seguridad  // arreglo de las variables de seguridad
		//				   as_codprov // c�digo de proveedor							as_codben  //  c�digo de beneficiario
		//				   as_aplarccon  // Si aplica ARC								as_aplconprocon // Aplica contablizaci�n por proyectos
		//	      Returns: lb_valido True si se ejecuto el update � False si hubo error en el update
		//	  Description: Funcion que actualiza el concepto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;				
		$ai_acumaxcon=str_replace(".","",$ai_acumaxcon);
		$ai_acumaxcon=str_replace(",",".",$ai_acumaxcon);				
		$ai_valmincon=str_replace(".","",$ai_valmincon);
		$ai_valmincon=str_replace(",",".",$ai_valmincon);				
		$ai_valmaxcon=str_replace(".","",$ai_valmaxcon);
		$ai_valmaxcon=str_replace(",",".",$ai_valmaxcon);				
		$ai_valminpatcon=str_replace(".","",$ai_valminpatcon);
		$ai_valminpatcon=str_replace(",",".",$ai_valminpatcon);				
		$ai_valmaxpatcon=str_replace(".","",$ai_valmaxpatcon);
		$ai_valmaxpatcon=str_replace(",",".",$ai_valmaxpatcon);				
		$as_forcon=strtoupper($as_forcon);
		$as_concon=strtoupper($as_concon);
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_concepto($as_codconc)===false)
				{
					$lb_valido=$this->uf_insert_concepto($as_codconc,$as_nomcon,$as_titcon,$as_titconcomp,$as_forcon,$ai_acumaxcon,$ai_valmincon,$ai_valmaxcon,$as_concon,$as_cueprecon,
								$as_cueconcon,$as_codpro,$as_codpro1,$as_sigcon,$as_glocon,$as_aplisrcon,$as_sueintcon,$as_intprocon,$as_forpatcon,
								$as_cueprepatcon,$as_cueconpatcon,$as_titretempcon,$as_titretpatcon,$ai_valminpatcon,$ai_valmaxpatcon,
								$ai_conprenom,$ai_sueintvaccon,$as_codprov,$as_codben,$as_aplarccon,$as_aplconprocon,$as_repacucon,$as_repconsunicon,
								$as_consunicon,$as_quirepcon,$as_frevarcon,$as_asifidper,$as_asifidpat,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Concepto ya existe, no lo puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_concepto($as_codconc)))
				{
					$lb_valido=$this->uf_update_concepto($as_codconc,$as_nomcon,$as_titcon,$as_titconcomp,$as_forcon,$ai_acumaxcon,$ai_valmincon,$ai_valmaxcon,$as_concon,$as_cueprecon,
								$as_cueconcon,$as_codpro,$as_codpro1,$as_sigcon,$as_glocon,$as_aplisrcon,$as_sueintcon,$as_intprocon,$as_forpatcon,
								$as_cueprepatcon,$as_cueconpatcon,$as_titretempcon,$as_titretpatcon,$ai_valminpatcon,$ai_valmaxpatcon,
								$ai_conprenom,$ai_sueintvaccon,$as_codprov,$as_codben,$as_aplarccon,$as_aplconprocon,$as_repacucon,$as_repconsunicon,
								$as_consunicon,$as_quirepcon,$as_frevarcon,$as_asifidper,$as_asifidpat,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Concepto no existe, no lo puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar		
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_salida($as_codconc)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_salida
		//		   Access: private
		//	    Arguments: as_codconc  // c�digo de concepto
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que valida que ninguna salida tenga asociada este concepto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_existe=false;
       	$ls_sql="SELECT codconc ".
				"  FROM sno_salida ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codconc='".$as_codconc."' ";
				
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Concepto N�mina M�TODO->uf_select_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
       	}
       	else
       	{
			if ($row=$this->io_sql->fetch_row($rs_data))
         	{
            	$lb_existe=true;
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe ;    
	}// end function uf_select_salida	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_prenomina($as_codconc)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_prenomina
		//		   Access: private
		//	    Arguments: as_codconc  // c�digo de concepto
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que valida que ninguna prenomina tenga asociada este concepto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n:22/05/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_existe=false;
       	$ls_sql="SELECT codconc ".
				"  FROM sno_prenomina ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codconc='".$as_codconc."' ";
				
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Concepto M�TODO->uf_select_prenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
       	}
       	else
       	{
			if ($row=$this->io_sql->fetch_row($rs_data))
         	{
            	$lb_existe=true;
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe;    
	}// end function uf_select_prenomina	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_conceptopersonal($as_codconc,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_conceptopersonal
		//		   Access: private
		//	    Arguments: as_codconc  // c�digo del concepto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete � False si hubo error en el delete
		//	  Description: Funcion que elimina el conceptopersonal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_conceptopersonal ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codconc='".$as_codconc."' ";
					
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Concepto M�TODO->uf_delete_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Elimin� los conceptopersonal asociados al concepto ".$as_codconc." asociada a la n�mina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido===false)
			{
				$this->io_mensajes->message("CLASE->Concepto M�TODO->uf_delete_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
		}
		return $lb_valido;
    }// end function uf_delete_conceptopersonal		
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_concepto($as_codconc,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_concepto
		//		   Access: public (tepuy_sno_d_concepto)
		//	    Arguments: as_codconc  // c�digo de concepto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete � False si hubo error en el delete
		//	  Description: Funcion que elimina el concepto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if (($this->uf_select_salida($as_codconc)===false)&&
			($this->uf_select_prenomina($as_codconc)===false)&&
			($this->io_vacacionconcepto->uf_select_vacacionconcepto("codconc",$as_codconc)===false)&&
			($this->io_primaconcepto->uf_select_primaconcepto("codconc",$as_codconc,"")===false)&&
			($this->io_prestamo->uf_select_prestamo("codconc",$as_codconc)===false))
		{
			$this->io_sql->begin_transaction();
			$lb_valido=$this->uf_delete_conceptopersonal($as_codconc,$aa_seguridad);
			if($lb_valido)
			{			
				$ls_sql="DELETE ".
						"  FROM sno_concepto ".
						" WHERE codemp='".$this->ls_codemp."' ".
						"   AND codnom='".$this->ls_codnom."' ".
						"   AND codconc='".$as_codconc."' ";
						
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Concepto M�TODO->uf_delete_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="DELETE";
					$ls_descripcion ="Elimin� el concepto  ".$as_codconc." asociada a la n�mina ".$this->ls_codnom;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					if($lb_valido)
					{	
						$this->io_mensajes->message("El Concepto fue Eliminado.");
						$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Concepto M�TODO->uf_delete_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
						$this->io_sql->rollback();
					}
				}
			}
		} 
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("No se puede eliminar el registro. Hay Concepto de vacaciones, Prestamos � primas asociadas a este concepto � ya se calcul� la n�mina � la pren�mina");
		}       
		return $lb_valido;
    }// end function uf_delete_concepto	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_concepto(&$as_existe,&$as_codconc,&$as_nomcon,&$as_titcon,&$as_titconcomp,&$as_forcon,&$ai_acumaxcon,&$ai_valmincon,&$ai_valmaxcon,
							  &$as_concon,&$as_cueprecon,&$as_cueconcon,&$as_codpro,&$as_sigcon,&$as_glocon,&$as_aplisrcon,&$as_sueintcon,
							  &$as_intprocon,&$as_forpatcon,&$as_cueprepatcon,&$as_cueconpatcon,&$as_titretempcon,&$as_titretpatcon,
							  &$ai_valminpatcon,&$ai_valmaxpatcon,&$as_denprecon,&$as_denconcon,&$as_codestpro1,&$as_codestpro2,
							  &$as_codestpro3,&$as_codestpro4,&$as_codestpro5,&$as_denestpro1,&$as_denestpro2,&$as_denestpro3,
							  &$as_denestpro4,&$as_denestpro5,&$as_dencueconpat,&$as_dencueprepat,&$ai_conprenom,&$ai_sueintvaccon,
							  &$as_descon,&$as_coddescon,&$as_desdescon,&$as_aplarccon,&$as_aplconprocon,&$as_repacucon,&$as_repconsunicon,
							  &$as_consunicon,&$as_quirepcon,&$as_frevarcon,&$as_asifidper,&$as_asifidpat,&$as_codestpro11,&$as_codestpro22,
							  &$as_codestpro33,&$as_codestpro44,&$as_codestpro55,&$as_denestpro11,&$as_denestpro22,&$as_denestpro33,
							  &$as_denestpro44,&$as_denestpro55)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_concepto
		//		   Access: public (tepuy_sno_d_concepto)
		//	    Arguments: as_codconc  // c�digo de concepto							as_nomcon  // Nombre
		//				   as_titcon  // T�tulo											as_forcon  // Formula
		//				   ai_acumaxcon  // acumulado m�ximo							ai_valmincon  // valor m�nimo
		//				   ai_valmaxcon  // valor m�ximo								as_concon  // condici�n 
		//				   as_cueprecon  // cuenta de presupuesto						as_cueconcon  // cuenta contable
		//				   as_codpro  // c�digo de program�tica							as_sigcon  // signo
		//				   as_glocon  // si la constante es global						as_aplisrcon  // si aplica ISR
		//				   as_sueintcon  // si pertenece al sueldo integral				as_intprocon  // si esta integrada a la program�tica
		//				   as_forpatcon  // f�rmula Patronal							as_cueprepatcon  // cuenta de presupuesto patronal
		//				   as_cueconpatcon  // cuenta contable patronal					as_titretempcon  // t�tulo de retenci�n empleados
		//				   as_titretpatcon  // t�tulo de retenci�n patr�n				ai_valminpatcon  // valor m�nimo patronal
		//				   ai_valmaxpatcon  // valor m�ximo patronal                    ai_conprenom  // Concepto de Pren�mina
		//				   ai_sueintvaccon // Si pertenece al Sueldo Int Vacaciones		as_descon // Destino de la contabilizaci�n
		//				   as_coddescon // c�digo del destino de la contabilizaci�n		as_desdescon  //  descripci�n del destino de la contabilizaci�n
		//				   as_aplarccon  // Si aplica ARC								as_aplconprocon // Aplica contabilizaci�n por proyectos
		//	      Returns: lb_valido True si se ejecuto el select � False si hubo error en el select
		//	  Description: Funci�n que obtiene los conceptos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_intprocon=="1")
		{
			$ls_programatica="		(SELECT denominacion ".
							 "		   FROM spg_cuentas ".
							 "		  WHERE codemp='".$this->ls_codemp."'	".
							 "		    AND spg_cuentas.codestpro1=substr(sno_concepto.codpro,1,20) ".
							 "		    AND spg_cuentas.codestpro2=substr(sno_concepto.codpro,21,6) ".
							 "		    AND spg_cuentas.codestpro3=substr(sno_concepto.codpro,27,3) ".
							 "		    AND spg_cuentas.codestpro4=substr(sno_concepto.codpro,30,2) ".
							 "		    AND spg_cuentas.codestpro5=substr(sno_concepto.codpro,32,3) ".
							 "		    AND spg_cuentas.spg_cuenta=sno_concepto.cueprecon) as denprecon, ".
							 "		(SELECT denominacion ".
							 "		   FROM spg_cuentas ".
							 "		  WHERE codemp='".$this->ls_codemp."'	".
							 "		    AND spg_cuentas.codestpro1=substr(sno_concepto.codpro1,1,20) ".
							 "		    AND spg_cuentas.codestpro2=substr(sno_concepto.codpro1,21,6) ".
							 "		    AND spg_cuentas.codestpro3=substr(sno_concepto.codpro1,27,3) ".
							 "		    AND spg_cuentas.codestpro4=substr(sno_concepto.codpro1,30,2) ".
							 "		    AND spg_cuentas.codestpro5=substr(sno_concepto.codpro1,32,3) ".
							 "		    AND spg_cuentas.spg_cuenta=sno_concepto.cueprepatcon) as dencueprepat ";
		}
		else
		{
			$ls_programatica=" '' as denprecon, '' as dencueprepat ";
		}
		$ls_sql="SELECT codconc, nomcon, titcon, conprep, sigcon, forcon, glocon, acumaxcon, valmincon, valmaxcon, concon, cueprecon, ".
				"		cueconcon, aplisrcon, sueintcon, intprocon, codpro, codpro1, forpatcon, cueprepatcon, cueconpatcon, titretempcon, ".
				"		titretpatcon, valminpatcon, valmaxpatcon, codprov, cedben, conprenom, sueintvaccon, aplarccon, conprocon, ".
				"		repacucon, repconsunicon, consunicon, quirepcon, frevarcon, asifidper, asifidpat, ".
				"		(SELECT nompro ".
				"		   FROM rpc_proveedor ".
				"		  WHERE codemp='".$this->ls_codemp."'	".
				"		    AND rpc_proveedor.cod_pro=sno_concepto.codprov) as proveedor, ".
				"		(SELECT nombene ".
				"		   FROM rpc_beneficiario ".
				"		  WHERE codemp='".$this->ls_codemp."'	".
				"		    AND rpc_beneficiario.ced_bene=sno_concepto.cedben) as beneficiario, ".
				"		(SELECT denominacion ".
				"		   FROM scg_cuentas ".
				"		  WHERE codemp='".$this->ls_codemp."'	".
				"		    AND scg_cuentas.sc_cuenta=sno_concepto.cueconcon) as dencuecon, ".
				"		(SELECT denominacion ".
				"		   FROM scg_cuentas ".
				"		  WHERE codemp='".$this->ls_codemp."'	".
				"		    AND scg_cuentas.sc_cuenta=sno_concepto.cueconpatcon) as dencueconpat, ".
				"		(SELECT denestpro1 ".
				"		   FROM spg_ep1 ".
				"		  WHERE spg_ep1.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep1.codestpro1=substr(sno_concepto.codpro,1,20)) as denestpro1, ".
				"		(SELECT denestpro2 ".
				"		   FROM spg_ep2 ".
				"		  WHERE spg_ep2.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep2.codestpro1=substr(sno_concepto.codpro,1,20) ".
				"		    AND spg_ep2.codestpro2=substr(sno_concepto.codpro,21,6)) as denestpro2, ".
				"		(SELECT denestpro3 ".
				"		   FROM spg_ep3 ".
				"		  WHERE spg_ep3.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep3.codestpro1=substr(sno_concepto.codpro,1,20) ".
				"		    AND spg_ep3.codestpro2=substr(sno_concepto.codpro,21,6) ".
				"		    AND spg_ep3.codestpro3=substr(sno_concepto.codpro,27,3)) as denestpro3, ".
				"		(SELECT denestpro4 ".
				"		   FROM spg_ep4 ".
				"		  WHERE spg_ep4.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep4.codestpro1=substr(sno_concepto.codpro,1,20) ".
				"		    AND spg_ep4.codestpro2=substr(sno_concepto.codpro,21,6) ".
				"		    AND spg_ep4.codestpro3=substr(sno_concepto.codpro,27,3) ".
				"		    AND spg_ep4.codestpro4=substr(sno_concepto.codpro,30,2)) as denestpro4, ".
				"		(SELECT denestpro5 ".
				"		   FROM spg_ep5 ".
				"		  WHERE spg_ep5.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep5.codestpro1=substr(sno_concepto.codpro,1,20) ".
				"		    AND spg_ep5.codestpro2=substr(sno_concepto.codpro,21,6) ".
				"		    AND spg_ep5.codestpro3=substr(sno_concepto.codpro,27,3) ".
				"		    AND spg_ep5.codestpro4=substr(sno_concepto.codpro,30,2) ".
				"		    AND spg_ep5.codestpro5=substr(sno_concepto.codpro,32,2)) as denestpro5, ".
			// categoria programatica del aporte patronal //
				"		(SELECT denestpro1 ".
				"		   FROM spg_ep1 ".
				"		  WHERE spg_ep1.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep1.codestpro1=substr(sno_concepto.codpro1,1,20)) as denestpro11, ".
				"		(SELECT denestpro2 ".
				"		   FROM spg_ep2 ".
				"		  WHERE spg_ep2.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep2.codestpro1=substr(sno_concepto.codpro1,1,20) ".
				"		    AND spg_ep2.codestpro2=substr(sno_concepto.codpro1,21,6)) as denestpro22, ".
				"		(SELECT denestpro3 ".
				"		   FROM spg_ep3 ".
				"		  WHERE spg_ep3.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep3.codestpro1=substr(sno_concepto.codpro1,1,20) ".
				"		    AND spg_ep3.codestpro2=substr(sno_concepto.codpro1,21,6) ".
				"		    AND spg_ep3.codestpro3=substr(sno_concepto.codpro1,27,3)) as denestpro33, ".
				"		(SELECT denestpro4 ".
				"		   FROM spg_ep4 ".
				"		  WHERE spg_ep4.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep4.codestpro1=substr(sno_concepto.codpro1,1,20) ".
				"		    AND spg_ep4.codestpro2=substr(sno_concepto.codpro1,21,6) ".
				"		    AND spg_ep4.codestpro3=substr(sno_concepto.codpro1,27,3) ".
				"		    AND spg_ep4.codestpro4=substr(sno_concepto.codpro1,30,2)) as denestpro44, ".
				"		(SELECT denestpro5 ".
				"		   FROM spg_ep5 ".
				"		  WHERE spg_ep5.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep5.codestpro1=substr(sno_concepto.codpro1,1,20) ".
				"		    AND spg_ep5.codestpro2=substr(sno_concepto.codpro1,21,6) ".
				"		    AND spg_ep5.codestpro3=substr(sno_concepto.codpro1,27,3) ".
				"		    AND spg_ep5.codestpro4=substr(sno_concepto.codpro1,30,2) ".
				"		    AND spg_ep5.codestpro5=substr(sno_concepto.codpro1,32,2)) as denestpro55, ".
				$ls_programatica.
				"  FROM sno_concepto ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codconc='".$as_codconc."' ";
				//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Concepto M�TODO->uf_load_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			switch($_SESSION["la_empresa"]["estmodest"])
			{
				case "1": // Modalidad por Proyecto
					$li_len1=20;
					$li_len2=6;
					$li_len3=3;
					$li_len4=2;
					$li_len5=2;
					break;
					
				case "2": // Modalidad por Presupuesto
					$li_len1=2;
					$li_len2=2;
					$li_len3=2;
					$li_len4=2;
					$li_len5=2;
					break;
			}
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codconc=$row["codconc"];
				$as_nomcon=$row["nomcon"];
				$as_titcon=$row["titcon"];
				$as_titconcomp=$row["conprep"];
				$as_forcon=$row["forcon"];
				$ai_acumaxcon=$row["acumaxcon"];
				$ai_acumaxcon=$this->io_fun_nomina->uf_formatonumerico($ai_acumaxcon);
				$ai_valmincon=$row["valmincon"];
				$ai_valmincon=$this->io_fun_nomina->uf_formatonumerico($ai_valmincon);
				$ai_valmaxcon=$row["valmaxcon"];
				$ai_valmaxcon=$this->io_fun_nomina->uf_formatonumerico($ai_valmaxcon);
				$as_concon=$row["concon"];
				$as_cueprecon=$row["cueprecon"];
				$as_denprecon=$row["denprecon"];
				$as_cueconcon=$row["cueconcon"];
				$as_denconcon=$row["dencuecon"];
				$ls_codpro=$row["codpro"];
				$ls_codpro1=$row["codpro1"];
				$as_intprocon=$row["intprocon"];
				$as_frevarcon=$row["frevarcon"];
				$as_asifidper=$row["asifidper"];
				$as_asifidpat=$row["asifidpat"];
				if($as_intprocon==1)
				{
					$as_codestpro1=substr($ls_codpro,0,20);
					$as_codestpro1=substr($as_codestpro1,(strlen($as_codestpro1)-$li_len1),$li_len1);
					$as_codestpro2=substr($ls_codpro,20,6);
					$as_codestpro2=substr($as_codestpro2,(strlen($as_codestpro2)-$li_len2),$li_len2);
					$as_codestpro3=substr($ls_codpro,26,3);
					$as_codestpro3=substr($as_codestpro3,(strlen($as_codestpro3)-$li_len3),$li_len3);
					$as_codestpro4=substr($ls_codpro,29,2);
					$as_codestpro4=substr($as_codestpro4,(strlen($as_codestpro4)-$li_len4),$li_len4);
					$as_codestpro5=substr($ls_codpro,31,2);
					$as_codestpro5=substr($as_codestpro5,(strlen($as_codestpro5)-$li_len5),$li_len5);
					$as_denestpro1=$row["denestpro1"];
					$as_denestpro2=$row["denestpro2"];
					$as_denestpro3=$row["denestpro3"];
					$as_denestpro4=$row["denestpro4"];
					$as_denestpro5=$row["denestpro5"];
			// categoria programatica de el aporte patronal //
					$as_codestpro11=substr($ls_codpro1,0,20);
					$as_codestpro11=substr($as_codestpro11,(strlen($as_codestpro11)-$li_len1),$li_len1);
					$as_codestpro22=substr($ls_codpro1,20,6);
					$as_codestpro22=substr($as_codestpro22,(strlen($as_codestpro22)-$li_len2),$li_len2);
					$as_codestpro33=substr($ls_codpro1,26,3);
					$as_codestpro33=substr($as_codestpro33,(strlen($as_codestpro33)-$li_len3),$li_len3);
					$as_codestpro44=substr($ls_codpro1,29,2);
					$as_codestpro44=substr($as_codestpro44,(strlen($as_codestpro44)-$li_len4),$li_len4);
					$as_codestpro55=substr($ls_codpro1,31,2);
					$as_codestpro55=substr($as_codestpro55,(strlen($as_codestpro55)-$li_len5),$li_len5);
					$as_denestpro11=$row["denestpro11"];
					$as_denestpro22=$row["denestpro22"];
					$as_denestpro33=$row["denestpro33"];
					$as_denestpro44=$row["denestpro44"];
					$as_denestpro55=$row["denestpro55"];
				}
				$as_sigcon=$row["sigcon"];
				$as_glocon=$row["glocon"];
				$as_aplisrcon=$row["aplisrcon"];
				$as_sueintcon=$row["sueintcon"];
				$as_forpatcon=$row["forpatcon"];
				$as_cueprepatcon=$row["cueprepatcon"];
				$as_dencueprepat=$row["dencueprepat"];
				$as_cueconpatcon=$row["cueconpatcon"];
				$as_dencueconpat=$row["dencueconpat"];
				$as_titretempcon=$row["titretempcon"];
				$as_titretpatcon=$row["titretpatcon"];
				$ai_valminpatcon=$row["valminpatcon"];
				$ai_valminpatcon=$this->io_fun_nomina->uf_formatonumerico($ai_valminpatcon);
				$ai_valmaxpatcon=$row["valmaxpatcon"];
				$ai_valmaxpatcon=$this->io_fun_nomina->uf_formatonumerico($ai_valmaxpatcon);
				$ai_conprenom=$row["conprenom"];
				$ai_sueintvaccon=$row["sueintvaccon"];
				$as_aplarccon=$row["aplarccon"];
				$as_aplconprocon=$row["conprocon"];
				$ls_codprov=$row["codprov"];
				$ls_codben=$row["cedben"];
				$ls_proveedor=$row["proveedor"];
				$ls_beneficiario=$row["beneficiario"];
				$as_repacucon=$row["repacucon"];
				$as_repconsunicon=$row["repconsunicon"];
				$as_consunicon=$row["consunicon"];		
				$as_quirepcon=$row["quirepcon"];
				if($ls_codprov=="----------")
				{
					$as_descon="B";
					$as_coddescon=$ls_codben;
					$as_desdescon=$ls_beneficiario;
				}
				if($ls_codben=="----------")
				{
					$as_descon="P";
					$as_coddescon=$ls_codprov;
					$as_desdescon=$ls_proveedor;
				}
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_load_concepto	
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_conceptopersonal($as_codper,$as_codconc,&$aa_concepto)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_conceptopersonal
		//		   Access: public (tepuy_sno_c_evaluador)
		//	    Arguments: as_codper // c�digo de personal
		//				   as_codconc // c�digo del concepto
		//				   aa_concepto // Arreglo donde se colocan toda la informaci�n del concepto
		//	      Returns: lb_valido True si se obtuvo el concepto � False si no se obtuvo
		//	  Description: funci�n que dado el c�digo de personal y concepto busca el concepto asociado al personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_concepto.glocon, sno_concepto.forcon, sno_conceptopersonal.aplcon ".
				"  FROM sno_conceptopersonal, sno_concepto ".
				" WHERE sno_conceptopersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_conceptopersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_conceptopersonal.codconc='".$as_codconc."' ".
				"   AND sno_conceptopersonal.codper='".$as_codper."' ".
				"   AND sno_conceptopersonal.codemp=sno_concepto.codemp ".
				"   AND sno_conceptopersonal.codnom=sno_concepto.codnom".
				"   AND sno_conceptopersonal.codconc=sno_concepto.codconc ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Concepto M�TODO->uf_obtener_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_total=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_concepto["glocon"]=$row["glocon"];
				$aa_concepto["forcon"]=$row["forcon"];
				$aa_concepto["aplcon"]=$row["aplcon"];
				$li_total=$li_total+1;
			}
			if($li_total==0)
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_obtener_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_hconcepto($as_codconc,$as_codpro,$as_aplisrcon,$as_sueintcon,$as_intprocon,$as_cueprecon,$as_cueconcon,
								 $as_cueprepatcon,$as_cueconpatcon, $as_codprov,$as_codben,$as_aplarccon,$as_sueintvaccon,
								 $as_aplconprocon,$as_asifidper,$as_asifidpat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_hconcepto
		//		   Access: private
		//	    Arguments: as_codconc  // c�digo de concepto
		//				   as_codpro  // c�digo de program�tica	
		//				   as_aplisrcon  // si aplica ISR
		//				   as_sueintcon  // si pertenece al sueldo integral				
		//				   as_intprocon  // si esta integrada a la program�tica
		//				   as_cueprecon  // cuenta presupuestaria de concepto
		//				   as_cueconcon  // cuenta contable de concepto
		//				   as_cueprepatcon  // cuenta presupuestaria de concepto para los aportes
		//				   as_cueconpatcon  // cuenta contable de concepto para los aportes
		//				   as_codprov  // c�digo del proveedor
		//				   as_codben  // c�digo del beneficiario
		//				   as_aplarccon  // aplica el concepto como arc
		//				   as_sueintvaccon  // aplica el concepto como sueldo integral de vacaciones
		//				   $as_aplconprocon // Contabilizaci�n por proyecto 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update del hist�rico � False si hubo error en el update
		//	  Description: Funcion que actualiza el concepto hist�rico
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/04/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_hconcepto ".
				"   SET codpro='".$as_codpro."', ".
				"		aplisrcon='".$as_aplisrcon."', ".
				"		sueintcon='".$as_sueintcon."', ".
				"		intprocon='".$as_intprocon."', ".
				"		cueprecon='".$as_cueprecon."', ".
				"		cueconcon='".$as_cueconcon."', ".
				"		cueprepatcon='".$as_cueprepatcon."', ".
				"		cueconpatcon='".$as_cueconpatcon."', ".
				"		codprov='".$as_codprov."', ".
				"		cedben='".$as_codben."', ".
				"		aplarccon='".$as_aplarccon."', ".
				"		sueintvaccon='".$as_sueintvaccon."', ".
				"		conprocon='".$as_aplconprocon."', ".
				"       asifidper='".$as_asifidper."', ".
				"       asifidpat='".$as_asifidpat."' ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$this->ls_anocurnom."' ".
				"   AND codperi='".$this->ls_codperi."' ".
				"   AND codconc='".$as_codconc."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Concepto M�TODO->uf_update_hconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			$ls_sql="UPDATE sno_thconcepto ".
					"   SET codpro='".$as_codpro."', ".
					"		aplisrcon='".$as_aplisrcon."', ".
					"		sueintcon='".$as_sueintcon."', ".
					"		intprocon='".$as_intprocon."', ".
					"		cueprecon='".$as_cueprecon."', ".
					"		cueconcon='".$as_cueconcon."', ".
					"		cueprepatcon='".$as_cueprepatcon."', ".
					"		cueconpatcon='".$as_cueconpatcon."', ".
					"		codprov='".$as_codprov."', ".
					"		cedben='".$as_codben."', ".
					"		aplarccon='".$as_aplarccon."', ".
					"		sueintvaccon='".$as_sueintvaccon."', ".
					"		conprocon='".$as_aplconprocon."', ".
				"       asifidper='".$as_asifidper."', ".
				"       asifidpat='".$as_asifidpat."' ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND anocur='".$this->ls_anocurnom."' ".
					"   AND codperi='".$this->ls_codperi."' ".
					"   AND codconc='".$as_codconc."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Concepto M�TODO->uf_update_hconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualiz� el concepto hist�rico ".$as_codconc." asociado a la n�mina ".$this->ls_codnom." A�o ".$this->ls_anocurnom." ".
								 "Per�odo ".$this->ls_codperi;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("El Concepto fue Actualizado.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("El concepto no se actualiz�");
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_update_hconcepto	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_islr()
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_islr
		//		   Access: private
		//	    Arguments: as_codconc  // c�digo de concepto
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que verifica si hay alg�n concepto marcado como islr
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 03/10/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_existe=false;
       	$ls_sql="SELECT codconc ".
				"  FROM sno_concepto ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ".
				"   AND aplisrcon = 1 ";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Concepto N�mina M�TODO->uf_select_islr ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
       	}
       	else
       	{
			if ($row=$this->io_sql->fetch_row($rs_data))
         	{
            	$lb_existe=true;
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe ;    
	}// end function uf_select_islr	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_islr_historico()
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_islr_historico
		//		   Access: private
		//	    Arguments: 
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que verifica si hay alg�n concepto marcado como islr en los hist�ricos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 21/02/2007 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_existe=false;
       	$ls_sql="SELECT codconc ".
				"  FROM sno_hconcepto ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ".
				"   AND anocur = '".$this->ls_anocurnom."' ".
				"   AND codperi = '".$this->ls_codperi."' ".
				"   AND aplisrcon = 1 ";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Concepto N�mina M�TODO->uf_select_islr ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
       	}
       	else
       	{
			if ($row=$this->io_sql->fetch_row($rs_data))
         	{
            	$lb_existe=true;
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe ;    
	}// end function uf_select_islr_historico	
	//-----------------------------------------------------------------------------------------------------------------------------------


	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>
