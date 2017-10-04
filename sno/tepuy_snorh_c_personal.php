<?php
class tepuy_snorh_c_personal
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_fun_nomina;
	var $io_personalnomina;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_snorh_c_personal()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_snorh_c_personal
		//		   Access: public (tepuy_snorh_d_personal)
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
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		$this->io_seguridad= new tepuy_c_seguridad();
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
		require_once("tepuy_sno_c_personalnomina.php");
		$this->io_personalnomina=new tepuy_sno_c_personalnomina();
		require_once("tepuy_sno.php");
		$this->io_sno=new tepuy_sno();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
	}// end function tepuy_snorh_c_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (tepuy_snorh_d_personal)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fun_nomina);
		unset($this->io_personalnomina);
        unset($this->ls_codemp);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_personal($as_campo,$as_valor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_personal
		//		   Access: public (tepuy_snorh_d_profesion, uf_guardar)
		//	    Arguments: as_campo  // Campo que se quiere filtrar
		//			  	   as_valor  // Valor por el cual se filtra
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si existe un personal asociado a ese campo con ese valor
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT ".$as_campo." FROM sno_personal ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND ".$as_campo."='".$as_valor."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_select_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_personal
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_personal($as_codper,$as_cedper,$as_nomper,$as_apeper,$as_dirper,$ad_fecnacper,$as_edocivper, 
								$as_telhabper,$as_telmovper,$as_sexper,$ai_estaper,$ai_pesper,$as_codpro,$as_nivacaper,$as_catper,
								$as_cajahoper,$ai_numhijper,$as_contraper,$ai_tipvivper,$as_tenvivper,$ai_monpagvivper,$as_cuecajahoper,
		 						$as_cuelphper,$as_cuefidper,$ad_fecingadmpubper,$ad_fecingper, $ai_anoservpreper,$ad_fecegrper,
								$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_cauegrper,$as_obsegrper,$as_cedbenper,$as_obsper,
								$as_nomfot,$as_nacper,$as_coreleper,$as_cenmedper,$as_turper,$as_horper,$as_hcmper,$as_tipsanper,
								$as_codcom,$as_codran,$as_numexpper,$ai_incluirbeneficiario,$as_cuentacontable,$as_codpainac,
								$as_codestnac,$as_enviorec,$ls_talcamper, $la_talpanper, $la_talzapper, $ai_anoservprecont,
								$ai_anoservprefijo,	$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_personal
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal     as_cedper // Cédula                  as_nomper // Nombre
		//				   as_apeper // Apellido                as_dirper // Dirección               ad_fecnacper // Fecha Nacimiento
		//				   as_edocivper // Estado Civil         as_telhabper // Teléfono Habitación  as_telmovper // Teléfono Móvil
		//				   as_sexper // Sexo                    ai_estaper // Estatura               ai_pesper // Peso
		//                 as_codpro // Profesión               as_nivacaper // Nivel Académico      as_catper // Categoría
		//                 as_cajahoper // tiene Caja de Ahorro ai_numhijper // Número de Hijos      as_contraper // Conyuge trabaja
		//                 ai_tipvivper // Tipo de vivienda     as_tenvivper // Tenencia Vivienda    ai_monpagvivper // Monto Pagado Vivienda
		//				   as_cuecajahoper // Cta Caja Ahorro   as_cuelphper // Cta Ley Política     as_cuefidper // Cuenta Fideicomiso
		//                 ad_fecingadmpubper // Fecha Ing Adm  ad_fecingper // Fecha Ing Ins        ai_anoservpreper // Año de servicio Previo
		//				   ad_fecegrper // Fecha Egreso Ins     as_codpai // Pais                    as_codest // Estado
		//				   as_codmun // Municipio               as_codpar // Parroquia               as_cauegrper // Causa Egreso Personal
		//                 as_obsegrper // Observación Egreso   as_cedbenper // cédula Beneficiario  as_obsper // Observación
		//				   as_nomfot // Ruta Foto			   as_nacper // nacionalidad del personal as_coreleper // Correo Electrónico
		//				   as_cenmedper // Centro médico del IVSS
		//				   as_turper // Turno del Personal      as_horper // Horario del Personal	 as_hcmper // Si el personal tiene hcm
		//				   as_tipsanper // Tipo de Sangre de Personal
		//				   as_codcom // Código de Componente    as_codran // Código de Rango		 as_numexpper // Número de Expediente
		//				   ai_incluirbeneficiario // Incluir Personal Como beneficiario
		//				   as_cuentacontable // Cuenta contable del beneficiario
		//				   as_codpainac // Código de País de Nacimiento as_codestnac // Código de Estado de Nacimiento
		//                 as_enviorec // mdo de envio del recibo de pago
		//                 ai_anoservprecont  // años de servicios previos como contratado
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_personal el registro del personal asociado
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_personal".
				"(codemp,codper,cedper,nomper,apeper,dirper,fecnacper,edocivper,telhabper,telmovper,sexper,estaper,pesper,codpro,nivacaper,".
				"catper,cajahoper,numhijper,contraper,tipvivper,tenvivper,monpagvivper,cuecajahoper,cuelphper,cuefidper,fecingadmpubper,".
				"fecingper,anoservpreper,fecegrper,codpai,codest,codmun,codpar,cauegrper,obsegrper,cedbenper,obsper,fotper,estper,".
				"porisrper,nacper,coreleper,cenmedper,turper,horper,hcmper,tipsanper,codcom,codran,numexpper,codpainac,codestnac,enviorec, ".
				" talcamper, talpanper, talzapper, anoservprecont, anoservprefijo)VALUES".
				"('".$this->ls_codemp."','".$as_codper."','".$as_cedper."','".$as_nomper."','".$as_apeper."','".$as_dirper."',".
				"'".$ad_fecnacper."','".$as_edocivper."','".$as_telhabper."','".$as_telmovper."','".$as_sexper."',".$ai_estaper.",".
				"".$ai_pesper.",'".$as_codpro."','".$as_nivacaper."','".$as_catper."','".$as_cajahoper."',".$ai_numhijper.",'".$as_contraper."',".
				"'".$ai_tipvivper."','".$as_tenvivper."',".$ai_monpagvivper.",'".$as_cuecajahoper."','".$as_cuelphper."','".$as_cuefidper."',".
				"'".$ad_fecingadmpubper."','".$ad_fecingper."',".$ai_anoservpreper.",'".$ad_fecegrper."','".$as_codpai."','".$as_codest."',".
				"'".$as_codmun."','".$as_codpar."','".$as_cauegrper."','".$as_obsegrper."','".$as_cedbenper."','".$as_obsper."','".$as_nomfot."',".
				"'1',0.00,'".$as_nacper."','".$as_coreleper."','".$as_cenmedper."','".$as_turper."','".$as_horper."','".$as_hcmper."', ".
				"'".$as_tipsanper."','".$as_codcom."','".$as_codran."','".$as_numexpper."','".$as_codpainac."','".$as_codestnac."','".$as_enviorec."','".$la_talcamper."','".$la_talpanper."',".$la_talzapper.",'".$ai_anoservprecont."','".$ai_anoservprefijo."')";
				
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_insert_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_isrpersonal($as_codper,$aa_seguridad);
			}
			if($lb_valido)
			{
				if($ai_incluirbeneficiario=="1")
				{
					if($as_cuentacontable!="")
					{
						$lb_valido=$this->uf_insert_beneficiario($as_cedper,$as_nomper,$as_apeper,$as_dirper,$as_telhabper,$as_telmovper,
																 $as_coreleper,$as_cuentacontable,$as_codpai,$as_codest,$as_codmun,$as_codpar,
																 $as_nacper,$aa_seguridad);
					}
					else
					{
						
						$lb_valido=false;
						$this->io_mensajes->message("Ocurrio un error al registar el personal como Beneficiario Verifique los datos.");
					}
				}
			}
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Personal fue Registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_insert_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_isrpersonal($as_codper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_isrpersonal
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_isr
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_index=1;(($li_index<13)&&($lb_valido));++$li_index)
		{
			$ls_codimp= $li_index."";
			if (strlen($ls_codimp)==1)
			{
				$ls_codimp="0".$ls_codimp;
			}
			$ls_sql="INSERT INTO sno_personalisr ".
					"(codemp,codper,codisr,porisr)VALUES('".$this->ls_codemp."','".$as_codper."','".$ls_codimp."',0.00)";
			$li_row=$this->io_sql->execute($ls_sql);
			if ($li_row===false)
			{
				$lb_valido=false;
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el %ISR del mes".$ls_codimp." asociado al personal ".$as_codper;
				$lb_valido=$this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			}
		
		}
		if($lb_valido===false)
		{
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_insert_isrpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_insert_isrpersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_personal($as_codper,$as_cedper,$as_nomper,$as_apeper,$as_dirper,$ad_fecnacper,$as_edocivper, 
								$as_telhabper,$as_telmovper,$as_sexper,$ai_estaper,$ai_pesper,$as_codpro,$as_nivacaper,$as_catper,
								$as_cajahoper,$ai_numhijper,$as_contraper,$ai_tipvivper,$as_tenvivper,$ai_monpagvivper,$as_cuecajahoper,
		 						$as_cuelphper,$as_cuefidper,$ad_fecingadmpubper,$ad_fecingper, $ai_anoservpreper,$ad_fecegrper,
								$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_cauegrper,$as_obsegrper,$as_cedbenper,$as_obsper,
								$as_nacper,$as_coreleper,$as_nomfot,$as_cenmedper,$as_turper,$as_horper,$as_hcmper,$as_tipsanper,
								$as_codcom,$as_codran,$as_numexpper,$as_codpainac,$as_codestnac,$as_enviorec,
								$la_talcamper, $la_talpanper, $la_talzapper, $ai_anoservprecont,$ai_anoservprefijo,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_personal
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal     as_cedper // Cédula                  as_nomper // Nombre
		//				   as_apeper // Apellido                as_dirper // Dirección               ad_fecnacper // Fecha Nacimiento
		//				   as_edocivper // Estado Civil         as_telhabper // Teléfono Habitación  as_telmovper // Teléfono Móvil
		//				   as_sexper // Sexo                    ai_estaper // Estatura               ai_pesper // Peso
		//                 as_codpro // Profesión               as_nivacaper // Nivel Académico      as_catper // Categoría
		//                 as_cajahoper // tiene Caja de Ahorro ai_numhijper // Número de Hijos      as_contraper // Conyuge trabaja
		//                 ai_tipvivper // Tipo de vivienda     as_tenvivper // Tenencia Vivienda    ai_monpagvivper // Monto Pagado Vivienda
		//				   as_cuecajahoper // Cta Caja Ahorro   as_cuelphper // Cta Ley Política     as_cuefidper // Cuenta Fideicomiso
		//                 ad_fecingadmpubper // Fecha Ing Adm  ad_fecingper // Fecha Ing Ins        ai_anoservpreper // Año de servicio Previo
		//				   ad_fecegrper // Fecha Egreso Ins     as_codpai // Pais                    as_codest // Estado
		//				   as_codmun // Municipio               as_codpar // Parroquia               as_cauegrper // Causa Egreso Personal
		//                 as_obsegrper // Observación Egreso   as_cedbenper // cédula Beneficiario  as_obsper // Observación
		//				   as_nomfot // Ruta Foto			   as_nacper // nacionalidad del personal as_coreleper // Correo Electrónico
		//				   as_cenmedper // Centro médico del IVSS
		//				   as_turper // Turno del Personal      as_horper // Horario del Personal
		//				   as_tipsanper // Tipo de Sangre de Personal
		//				   as_codcom // Código de Componente    as_codran // Código de Rango		 as_numexpper // Número de Expediente
		//				   as_codpainac // Código de País de Nacimiento as_codestnac // Código de Estado de Nacimiento
		//                 as_enviorec // modo de envio del recibo de pago
		//                 ai_anoservprecont // años de servicicos previos como contratado
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update as_hcmper // Si el personal tiene hcm
		//	  Description: Funcion que actualiza el personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sqlfot="";
	//print "foto: ".$as_nomfot;
		if($as_nomfot!="")
		{
			$ls_sqlfot=", fotper='".$as_nomfot."' ";
		}
		$ls_sql="UPDATE sno_personal ".
				"SET cedper='".$as_cedper."', ".
				"	 nomper='".$as_nomper."', ".
				"	 apeper='".$as_apeper."', ".
				"	 dirper='".$as_dirper."', ".
				"	 fecnacper='".$ad_fecnacper."', ".
				"	 edocivper='".$as_edocivper."', ".
				"	 telhabper='".$as_telhabper."', ".
				"	 telmovper='".$as_telmovper."', ".
				"	 sexper='".$as_sexper."', ".
				"	 estaper=".$ai_estaper.", ".
				"	 pesper=".$ai_pesper.", ".
				"	 codpro='".$as_codpro."', ".
				"	 nivacaper='".$as_nivacaper."', ".
				"	 catper='".$as_catper."', ".
				"	 cajahoper='".$as_cajahoper."', ".
				"	 numhijper=".$ai_numhijper.", ".
				"	 contraper='".$as_contraper."', ".
				"	 tipvivper=".$ai_tipvivper.", ".
				"	 tenvivper='".$as_tenvivper."', ".
				"	 monpagvivper=".$ai_monpagvivper.", ".
				"	 cuecajahoper='".$as_cuecajahoper."', ".
				"	 cuelphper='".$as_cuelphper."', ".
				"	 cuefidper='".$as_cuefidper."', ".
				"	 fecingadmpubper='".$ad_fecingadmpubper."', ".
				"	 fecingper='".$ad_fecingper."', ".
				"	 anoservpreper=".$ai_anoservpreper.", ".
				"	 fecegrper='".$ad_fecegrper."', ".
				"	 codpai='".$as_codpai."', ".
				"	 cauegrper='".$as_cauegrper."', ".
				"	 codest='".$as_codest."', ".
				"	 codmun='".$as_codmun."', ".
				"	 codpar='".$as_codpar."', ".
				"	 obsegrper='".$as_obsegrper."', ".
				"	 cedbenper='".$as_cedbenper."', ".
				"	 obsper='".$as_obsper."', ".
				"	 nacper='".$as_nacper."', ".
				"	 coreleper='".$as_coreleper."', ".
				"	 cenmedper ='".$as_cenmedper."', ".
				"	 turper = '".$as_turper."', ".
				"	 horper = '".$as_horper."', ".
				"	 hcmper = '".$as_hcmper."', ".
				"	 tipsanper = '".$as_tipsanper."', ".
				"	 codcom = '".$as_codcom."', ".
				"	 codran = '".$as_codran."', ".
				"	 numexpper = '".$as_numexpper."', ".
				"	 codpainac = '".$as_codpainac."', ".
				"	 codestnac = '".$as_codestnac."', ".
				"	 enviorec = '".$as_enviorec."', ".
				"    talcamper='".$la_talcamper."', ".
				"    talpanper='".$la_talpanper."', ".
				"	 talzapper=".$la_talzapper.", ".
				"	 anoservprecont='".$ai_anoservprecont."', ".	
				"	 anoservprefijo='".$ai_anoservprefijo."' ".	
				$ls_sqlfot.
				"WHERE codemp='".$this->ls_codemp."'".
				"  AND codper='".$as_codper."'"; 
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_update_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Personal fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_update_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codper,$as_cedper,$as_nomper,$as_apeper,$as_dirper,$ad_fecnacper,$as_edocivper, 
						$as_telhabper,$as_telmovper,$as_sexper,$ai_estaper,$ai_pesper,$as_codpro,$as_nivacaper,$as_catper,
						$as_cajahoper,$ai_numhijper,$as_contraper,$ai_tipvivper,$as_tenvivper,$ai_monpagvivper,$as_cuecajahoper,
						$as_cuelphper,$as_cuefidper,$ad_fecingadmpubper,$ad_fecingper, $ai_anoservpreper,$ad_fecegrper,
						$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_cauegrper,$as_obsegrper,$as_cedbenper,$as_obsper,$as_nomfot,
						$as_nacper,$as_coreleper,$as_cenmedper,$as_turper,$as_horper,$as_hcmper,$as_tipsanper,$as_codcom,$as_codran,
						$as_numexpper,$as_codpainac,$as_codestnac,$as_enviorec,$la_talcamper, $la_talpanper, $la_talzapper, 
						$ai_anoservprecont, $ai_anoservprefijo, $aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (tepuy_snorh_d_personal)
		//	    Arguments: as_codper  // Código de Personal     as_cedper // Cédula                  as_nomper // Nombre
		//				   as_apeper // Apellido                as_dirper // Dirección               ad_fecnacper // Fecha Nacimiento
		//				   as_edocivper // Estado Civil         as_telhabper // Teléfono Habitación  as_telmovper // Teléfono Móvil
		//				   as_sexper // Sexo                    ai_estaper // Estatura               ai_pesper // Peso
		//                 as_codpro // Profesión               as_nivacaper // Nivel Académico      as_catper // Categoría
		//                 as_cajahoper // tiene Caja de Ahorro ai_numhijper // Número de Hijos      as_contraper // Conyuge trabaja
		//                 ai_tipvivper // Tipo de vivienda     as_tenvivper // Tenencia Vivienda    ai_monpagvivper // Monto Pagado Vivienda
		//				   as_cuecajahoper // Cta Caja Ahorro   as_cuelphper // Cta Ley Política     as_cuefidper // Cuenta Fideicomiso
		//                 ad_fecingadmpubper // Fecha Ing Adm  ad_fecingper // Fecha Ing Ins        ai_anoservpreper // Año de servicio Previo
		//				   ad_fecegrper // Fecha Egreso Ins     as_codpai // Pais                    as_codest // Estado
		//				   as_codmun // Municipio               as_codpar // Parroquia               as_cauegrper // Causa Egreso Personal
		//                 as_obsegrper // Observación Egreso   as_cedbenper // cédula Beneficiario  as_obsper // Observación
		//				   as_nomfot // Ruta Foto			   as_nacper // nacionalidad del personal as_coreleper // Correo Electrónico
		//				   as_cenmedper // Centro médico del IVSS
		//				   as_turper // Turno del Personal      as_horper // Horario del Personal     as_hcmper // si el personal tiene HCM
		//				   as_tipsanper // Tipo de Sangre de Personal
		//				   as_codcom // Código de Componente    as_codran // Código de Rango          as_numexpper // Número de Expediente
		//				   as_codpainac // Código de País de Nacimiento as_codestnac // Código de Estado de Nacimiento
		//                 as_enviorec // modo de envio del recibo de pago
		//                 ai_anoservprecont // años previos como contratado
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que almacena el personal
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;	
		$ad_fecnacper=$this->io_funciones->uf_convertirdatetobd($ad_fecnacper);
		$ad_fecingadmpubper=$this->io_funciones->uf_convertirdatetobd($ad_fecingadmpubper);
		$ad_fecingper=$this->io_funciones->uf_convertirdatetobd($ad_fecingper);
		$ad_fecegrper=$this->io_funciones->uf_convertirdatetobd($ad_fecegrper);
		$ai_estaper=str_replace(".","",$ai_estaper);
		$ai_estaper=str_replace(",",".",$ai_estaper);
		$ai_pesper=str_replace(".","",$ai_pesper);
		$ai_pesper=str_replace(",",".",$ai_pesper);
		$ai_monpagvivper=str_replace(".","",$ai_monpagvivper);
		$ai_monpagvivper=str_replace(",",".",$ai_monpagvivper);
		$la_talzapper=str_replace(",",".",$la_talzapper);
		switch($as_existe)
		{
			case "FALSE":
				if($this->uf_select_personal("codper",$as_codper)===false)
				{
					if($this->uf_select_personal("cedper",$as_cedper)===false)
					{
						$lb_valido=true;
						$li_incluirbeneficiario=trim($this->io_sno->uf_select_config("SNO","CONFIG","INCLUIR_A_BENEFICIARIO","0","I"));
						$ls_cuentacontable=trim($this->io_sno->uf_select_config("SNO","CONFIG","CUENTA_CONTABLE_BENEFICIARIO","","C"));
						if(($li_incluirbeneficiario=="1")&&($ls_cuentacontable==""))
						{
							$this->io_mensajes->message("ERROR->Debe Colocar la Cuenta Contable en Mantenimiento->Configuración para poder incluir el personal como beneficiario.");
							$this->io_mensajes->message("ERROR->El Personal no fue registrado.");
							$lb_valido=false;
						}
						if($lb_valido)
						{
							$lb_valido=$this->uf_insert_personal($as_codper,$as_cedper,$as_nomper,$as_apeper,$as_dirper,
																 $ad_fecnacper,$as_edocivper,$as_telhabper,$as_telmovper,$as_sexper,
																 $ai_estaper,$ai_pesper,$as_codpro,$as_nivacaper,$as_catper,
																 $as_cajahoper,$ai_numhijper,$as_contraper,$ai_tipvivper,$as_tenvivper,
																 $ai_monpagvivper,$as_cuecajahoper,$as_cuelphper,$as_cuefidper,
																 $ad_fecingadmpubper,$ad_fecingper, $ai_anoservpreper,$ad_fecegrper,
																 $as_codpai,$as_codest,$as_codmun,$as_codpar,$as_cauegrper,$as_obsegrper,
																 $as_cedbenper,$as_obsper,$as_nomfot,$as_nacper,$as_coreleper,$as_cenmedper,
																 $as_turper,$as_horper,$as_hcmper,$as_tipsanper,$as_codcom,$as_codran,
																 $as_numexpper,$li_incluirbeneficiario,$ls_cuentacontable,$as_codpainac,
																 $as_codestnac,$as_enviorec,
																 $la_talcamper, $la_talpanper, $la_talzapper,
																 $ai_anoservprecont, $ai_anoservprefijo, $aa_seguridad);
						}
						
					}
					else
					{
						$this->io_mensajes->message("La Cédula ya existe, no lo puede incluir.");
					}
				}
				else
				{
					$this->io_mensajes->message("El Código ya existe, no lo puede incluir.");
				}
				break;
				
			case "TRUE":
				if(($this->uf_select_personal("codper",$as_codper)))
				{
					$lb_valido=$this->uf_update_personal($as_codper,$as_cedper,$as_nomper,$as_apeper,$as_dirper,$ad_fecnacper,$as_edocivper, 
						$as_telhabper,$as_telmovper,$as_sexper,$ai_estaper,$ai_pesper,$as_codpro,$as_nivacaper,$as_catper,
						$as_cajahoper,$ai_numhijper,$as_contraper,$ai_tipvivper,$as_tenvivper,$ai_monpagvivper,$as_cuecajahoper,
						$as_cuelphper,$as_cuefidper,$ad_fecingadmpubper,$ad_fecingper, $ai_anoservpreper,$ad_fecegrper,
						$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_cauegrper,$as_obsegrper,$as_cedbenper,$as_obsper,$as_nacper,
						$as_coreleper,$as_nomfot,$as_cenmedper,$as_turper,$as_horper,$as_hcmper,$as_tipsanper,$as_codcom,$as_codran,
						$as_numexpper,$as_codpainac,$as_codestnac,$as_enviorec,$la_talcamper, $la_talpanper, $la_talzapper,
						$ai_anoservprecont,	$ai_anoservprefijo, $aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Personal no existe, no lo puede actualizar.");
				}
				break;
		}		
		
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_personal(&$as_codper,&$as_existe,&$as_cedper,&$as_nomper,&$as_apeper,&$as_dirper,&$ad_fecnacper,&$as_edocivper, 
							  &$as_telhabper,&$as_telmovper,&$as_sexper,&$ai_estaper,&$ai_pesper,&$as_codpro,&$as_nivacaper,&$as_catper,
							  &$as_cajahoper,&$ai_numhijper,&$as_contraper,&$as_tipvivper,&$as_tenvivper,&$ai_monpagvivper,&$as_cuecajahoper,
							  &$as_cuelphper,&$as_cuefidper,&$ad_fecingadmpubper,&$ad_fecingper, &$ai_anoservpreper,&$ad_fecegrper,
							  &$as_codpai,&$as_codest,&$as_codmun,&$as_codpar,&$as_cauegrper,&$as_obsegrper,&$as_cedbenper,&$as_obsper,&$as_estper,
						      &$as_despro,&$as_despai,&$as_desest,&$as_desmun,&$as_despar,&$as_nomfot,&$as_nacper,&$as_coreleper,&$as_cenmedper,
							  &$as_turper,&$as_horper,&$as_hcmper,&$as_tipsanper,&$as_codcom,&$as_codran,&$as_descom,&$as_desran,&$as_numexpper,
							  &$as_codpainac,&$as_codestnac,&$as_despainac, &$as_desestnac, &$as_enviorec,&$la_talcamper, 
							  &$la_talpanper, &$la_talzapper, &$ai_anoservprecont, &$ai_anoservprefijo, &$ls_cauegrper2)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_personal
		//		   Access: public (tepuy_snorh_d_personal)
		//	    Arguments: as_codper  // Código de Personal     as_cedper // Cédula                  as_nomper // Nombre
		//				   as_apeper // Apellido                as_dirper // Dirección               ad_fecnacper // Fecha Nacimiento
		//				   as_edocivper // Estado Civil         as_telhabper // Teléfono Habitación  as_telmovper // Teléfono Móvil
		//				   as_sexper // Sexo                    ai_estaper // Estatura               ai_pesper // Peso
		//                 as_codpro // Profesión               as_nivacaper // Nivel Académico      as_catper // Categoría
		//                 as_cajahoper // tiene Caja de Ahorro ai_numhijper // Número de Hijos      as_contraper // Conyuge trabaja
		//                 ai_tipvivper // Tipo de vivienda     as_tenvivper // Tenencia Vivienda    ai_monpagvivper // Monto Pagado Vivienda
		//				   as_cuecajahoper // Cta Caja Ahorro   as_cuelphper // Cta Ley Política     as_cuefidper // Cuenta Fideicomiso
		//                 ad_fecingadmpubper // Fecha Ing Adm  ad_fecingper // Fecha Ing Ins        ai_anoservpreper // Año de servicio Previo
		//				   ad_fecegrper // Fecha Egreso Ins     as_codpai // Pais                    as_codest // Estado
		//				   as_codmun // Municipio               as_codpar // Parroquia               as_cauegrper // Causa Egreso Personal
		//                 as_obsegrper // Observación Egreso   as_cedbenper // cédula Beneficiario  as_obsper // Observación
		//				   as_nomfot // Ruta Foto			   as_nacper // nacionalidad del personal as_coreleper // Correo Electrónico
		//				   as_turper // Turno del Personal      as_horper // Horario del Personal     as_hcmper // si el personal tiene hcm
		//				   as_codcom // Código de Componente    as_codran // Código de Rango
		//				   as_descom // Descripcion de Componente    as_desran // Descripcion de Rango as_numexpper // Número de Expediente del Personal
		//                 as_enviorec // modo del recibo de pago
		//                 ai_anoservprecont // años previos como contratado
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Funcion que obtiene un  personal en específico
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.dirper, ".
				"		sno_personal.fecnacper, sno_personal.edocivper, sno_personal.telhabper, sno_personal.telmovper, ".
				"		sno_personal.sexper, sno_personal.estaper, sno_personal.pesper, sno_personal.codpro, sno_personal.nivacaper, ".
				"		sno_personal.catper, sno_personal.cajahoper, sno_personal.numhijper, sno_personal.contraper, ".
				"		sno_personal.tipvivper, sno_personal.tenvivper, sno_personal.monpagvivper, sno_personal.ingbrumen, ".
				"		sno_personal.cuecajahoper, sno_personal.cuelphper, sno_personal.cuefidper, sno_personal.fecingadmpubper, ".
				"		sno_personal.vacper, sno_personal.porisrper, sno_personal.fecingper, sno_personal.anoservpreper, sno_personal.hcmper, ".
				"		sno_personal.cedbenper, sno_personal.fecegrper, sno_personal.estper, sno_personal.fotper, sno_personal.codpai, ".
				"		sno_personal.codest, sno_personal.codmun, sno_personal.codpar, sno_personal.obsper, sno_personal.cauegrper, ".
				"		sno_personal.obsegrper, sno_personal.nacper, sno_personal.coreleper, sno_personal.cenmedper, sno_personal.turper, ".
				"		sno_personal.horper, sno_profesion.despro, tepuy_pais.despai, tepuy_estados.desest, tepuy_municipio.denmun, ".
				"		tepuy_parroquia.denpar, sno_personal.tipsanper, sno_personal.codcom, sno_personal.codran, sno_personal.numexpper, sno_personal.enviorec, sno_personal.talcamper, sno_personal.talzapper, sno_personal.talpanper, ".
				"		sno_personal.codpainac, sno_personal.codestnac, ". 
				"		(SELECT descom FROM sno_componente WHERE sno_componente.codemp = sno_personal.codemp AND sno_componente.codcom = sno_personal.codcom ) AS descom, ".
				"		(SELECT desran FROM sno_rango WHERE sno_rango.codemp = sno_personal.codemp AND sno_rango.codcom = sno_personal.codcom AND sno_rango.codran = sno_personal.codran) AS desran, ".
				"		(SELECT despai FROM tepuy_pais WHERE tepuy_pais.codpai = sno_personal.codpainac ) AS despainac, ".
				"		(SELECT desest FROM tepuy_estados WHERE tepuy_estados.codpai = sno_personal.codpainac AND tepuy_estados.codest = sno_personal.codestnac ) AS desestnac ".
				"  FROM sno_personal, sno_profesion, tepuy_pais, tepuy_estados, tepuy_municipio, tepuy_parroquia ".
				" WHERE sno_personal.codemp='".$this->ls_codemp."' ".
				"   AND sno_personal.codper='".$as_codper."' ".
				"	AND sno_profesion.codemp = sno_personal.codemp ".
				"   AND sno_profesion.codpro = sno_personal.codpro ".
				"   AND tepuy_pais.codpai = sno_personal.codpai ".
				"   AND tepuy_estados.codpai = sno_personal.codpai ".
				"   AND tepuy_estados.codest = sno_personal.codest ".
				"   AND tepuy_municipio.codpai = sno_personal.codpai ".
				"   AND tepuy_municipio.codest = sno_personal.codest ".
				"   AND tepuy_municipio.codmun = sno_personal.codmun ".
				"   AND tepuy_parroquia.codpai = sno_personal.codpai ".
				"   AND tepuy_parroquia.codest = sno_personal.codest ".
				"   AND tepuy_parroquia.codmun = sno_personal.codmun ".
				"   AND tepuy_parroquia.codpar = sno_personal.codpar ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_load_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codper=$row["codper"];
				$as_cedper=$row["cedper"];
				$as_nomper=$row["nomper"];
				$as_apeper=$row["apeper"];				
				$as_dirper=$row["dirper"];				
				$ad_fecnacper=$this->io_funciones->uf_convertirfecmostrar($row["fecnacper"]);				
				$as_edocivper=$row["edocivper"];			
				$as_telhabper=$row["telhabper"];				
				$as_telmovper=$row["telmovper"];				
				$as_sexper=$row["sexper"];			
				$as_nacper=$row["nacper"];
				$ai_estaper=$row["estaper"];			
				$ai_estaper=$this->io_fun_nomina->uf_formatonumerico($ai_estaper);
				$ai_pesper=$row["pesper"];			
				$ai_pesper=$this->io_fun_nomina->uf_formatonumerico($ai_pesper);
				$as_codpro=$row["codpro"];	
				$as_nivacaper=$row["nivacaper"];
				$as_codpai=$row["codpai"];	
				$as_codest=$row["codest"];	
				$as_codmun=$row["codmun"];	
				$as_codpar=$row["codpar"];	
				$as_catper=$row["catper"];	
				$as_cedbenper=$row["cedbenper"];	
				$ai_numhijper=$row["numhijper"];	
				$as_obsper=$row["obsper"];	
				$as_contraper=$row["contraper"];			
				$as_tipvivper=$row["tipvivper"];	
				$as_tenvivper=$row["tenvivper"];	
				$ai_monpagvivper=$row["monpagvivper"];	
				$ai_monpagvivper=$this->io_fun_nomina->uf_formatonumerico($ai_monpagvivper);				
				$as_cuecajahoper=$row["cuecajahoper"];	
				$as_cuelphper=$row["cuelphper"];	
				$as_cuefidper=$row["cuefidper"];	
				$as_cajahoper=$row["cajahoper"];
				$ad_fecingadmpubper=$this->io_funciones->uf_convertirfecmostrar($row["fecingadmpubper"]);				
				$ai_anoservpreper=$row["anoservpreper"];	
				$ai_anoservprefijo=$this->uf_select_anotrabajoantfijo($as_codper);
				$ai_anoservprecont=$this->uf_select_anotrabajoantcontratado($as_codper);	
				$ad_fecingper=$this->io_funciones->uf_convertirfecmostrar($row["fecingper"]);				
				$ad_fecegrper=$this->io_funciones->uf_convertirfecmostrar($row["fecegrper"]);				
				$as_cauegrper=$row["cauegrper"];			
				$as_obsegrper=$row["obsegrper"];	
				$as_existe="TRUE";	
				$as_enviorec=$row["enviorec"];		
				$as_estper=$row["estper"];		
				switch ($as_estper)
				{
					case "0":
						$as_estper="PRE INGRESO";
						break;
					
					case "1":
						$as_estper="ACTIVO";
						break;
						
					case "2":
						$as_estper="N/A";
						break;
						
					case "3":
						$as_estper="EGRESADO";
						break;
				}
				$as_despro=$row["despro"];
				$as_despai=$row["despai"];
				$as_desest=$row["desest"];
				$as_desmun=$row["denmun"];
				$as_despar=$row["denpar"];
				$as_nomfot=$row["fotper"];
				$as_coreleper=$row["coreleper"];
				$as_cenmedper=$row["cenmedper"];
				$as_turper=$row["turper"];
				$as_horper=$row["horper"];
				$as_hcmper=$row["hcmper"];
				$as_tipsanper=$row["tipsanper"];
				$as_codcom=$row["codcom"];
				$as_codran=$row["codran"];
				$as_descom=$row["descom"];
				$as_desran=$row["desran"];
				$as_numexpper=$row["numexpper"];
				$as_codpainac=$row["codpainac"];
				$as_codestnac=$row["codestnac"];
				$as_despainac=$row["despainac"];
				$as_desestnac=$row["desestnac"];
				$la_talcamper=$row["talcamper"];
				$la_talpanper=$row["talpanper"];
				$la_talzapper=$row["talzapper"];
				if ($as_cauegrper=="N")
				{
					$ls_cauegrper2="Ninguno";
				}
				if ($as_cauegrper=="D")
				{
					$ls_cauegrper2="Despedido";
				}
				if ($as_cauegrper=="1")
				{
					$ls_cauegrper2="Despedido 102";
				}
				if ($as_cauegrper=="2")
				{
					$ls_cauegrper2="Despedido 125";
				}
				if ($as_cauegrper=="P")
				{
					$ls_cauegrper2="Pensionado";
				}
				if ($as_cauegrper=="R")
				{
					$ls_cauegrper2="Renuncia";
				}
				if ($as_cauegrper=="T")
				{
					$ls_cauegrper2="Traslado";
				}
				if ($as_cauegrper=="J")
				{
					$ls_cauegrper2="Jubilado";
				}
				if ($as_cauegrper=="F")
				{
					$ls_cauegrper2="Fallecido";
				}						
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_upload($as_nomfot,$as_tipfot,$as_tamfot,$as_nomtemfot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_upload
		//		   Access: public (tepuy_snorh_d_personal)
		//	    Arguments: as_nomfot  // Nombre Foto
		//				   as_tipfot  // Tipo Foto
		//				   as_tamfot  // Tamaño Foto
		//				   as_nomtemfot  // Nombre Temporal
		//	      Returns: as_nomfot sale vacia si da un error y con el mismo valor si se subio correctamente
		//	  Description: Funcion que sube una foto al servidor
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_tipfot=strtolower($as_tipfot);
		
		if ($as_nomfot!="")
		{
			if (!((strpos($as_tipfot, "gif") || strpos($as_tipfot, "jpeg") || strpos($as_tipfot, "png")) && ($as_tamfot < 1900000))) 
			{ 
				$as_nomfot="";
				$this->io_mensajes->message("El archivo de la foto no es válido.");
			}
			else
			{ 
				if (!((move_uploaded_file($as_nomtemfot, "fotospersonal/".$as_nomfot))))
				{
					$as_nomfot="";
		        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_upload ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}
		}
		return $as_nomfot;	
    }
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_personalestatus($as_codper,$as_estper,$ad_fecegrper,$as_cauegrper,$as_obsegrper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function : uf_update_personalestatus
		//		   Access : public (tepuy_snorh_p_personalcambioestatus)
		//	    Arguments : as_codper  // Código de Personal
		//				    as_estper  // Estatus de Personal
		//				    ad_fecegrper  // Fecha de Egreso
		//				    as_cauegrper  // Causa de egreso
		//				    as_obsegrper // Observación del Egreso		
		//	      Returns : $lb_valido True si se ejecuto el cambio ó False si hubo error al ejecuatr el cambio
		//	  Description : Funcion que actualiza el estatus del personal
		//				    esta función es llamada de la pantalla tepuy_snorh_p_personalcambioestatus.php	
		//	   Creado Por : Ing. Miguel Palencia
		// Fecha Creación : 14/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecegrper=$this->io_funciones->uf_convertirdatetobd($ad_fecegrper);
		$ls_estpernom="";
		$ls_sql="UPDATE sno_personal ".
				"   SET estper='".$as_estper."', ".
				"		fecegrper='".$ad_fecegrper."', ".
				"		cauegrper='".$as_cauegrper."', ".
				"		obsegrper='".$as_obsegrper."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"	AND codper='".$as_codper."'";

		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Personal MÉTODO->uf_update_personalestatus ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Cambió el Estatus del personal ".$as_codper." Estatus ".$as_estper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			switch ($as_estper)
			{
				case "0": //Pre-Ingreso en nómina lo coloco como No Asignado
					$ls_estpernom="0";
					break;

				case "1": //Activo en nómina lo coloco como Activo
					$ls_estpernom="1";
					break;

				case "2": //No Asignado en nómina lo coloco como No Asignado
					$ls_estpernom="0";
					break;

				case "3": //Egresado en nómina lo coloco como Egresado
					$ls_estpernom="3";
					break;
			}
			if($ls_estpernom!="")
			{
				$lb_valido=$this->io_personalnomina->uf_update_estatus($as_codper,$ls_estpernom,$ad_fecegrper,$as_obsegrper,"1",$aa_seguridad);
			}
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Personal fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_update_personalestatus ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_codigopersonal($as_cedper,&$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_codigopersonal
		//		   Access: public (tepuy_sno_c_impexpdato)
		//	    Arguments: as_cedper  // Cédula del personal
		//			  	   as_codper  // código del personal
		//	      Returns: lb_valido True si el select no tuvo errores ó False si hubo error
		//	  Description: Funcion que obtiene el código de personal dada una cédula
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 28/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codper FROM sno_personal ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND cedper='".$as_cedper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_load_codigopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codper=$row["codper"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_codigopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_cambioid($as_codper,$as_codnue,$as_obscodnue,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_cambioid
		//		   Access: public (tepuy_snorh_p_personalcambioid)
		//	    Arguments: as_codper  // Código del personal
		//			  	   as_codnue  // Nuevo Código del personal
		//			  	   as_obscodnue  // Observación por medio del cual se esta cambiando el código del personal
		//			  	   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si el proceso se ejecutó con éxito ó False si hubo error
		//	  Description: Funcion que obtiene que cambia el código del personal por uno nuevo
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 30/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		if($this->uf_select_personal("codper",$as_codper)===false)
		{// Verificamos que el código viejo exista en la BD
			$lb_valido=false;
			$this->io_mensajes->message("El Código ".$as_codper." No existe. No se puede modificar.");
		}
		if(($this->uf_select_personal("codper",$as_codnue))&&($lb_valido))
		{// Verificamos que le código nuevo no exista en la BD
			$lb_valido=false;
			$this->io_mensajes->message("El Código ".$as_codnue." Existe. No se puede asociar a este personal.");
		}
		if($lb_valido)
		{// Insertamos todos los registros con el nuevo código
			$lb_valido=$this->uf_insert_registro($as_codper,$as_codnue);
		}
		if($lb_valido)
		{// eliminamos todos los registros del viejo código
			$lb_valido=$this->uf_delete_registro($as_codper);
		}
		if($lb_valido) 
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Cambió el código del personal ".$as_codper." al código ".$as_codnue." observación ".$as_obscodnue;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////				
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("El Código del personal fue cambiado.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un error al Cambiar el código del personal."); 
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_procesar_cambioid
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_registro($as_codper,$as_codnue)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_registro
		//		   Access: private
		//	    Arguments: as_codper  // Código del personal
		//			  	   as_codnue  // Nuevo Código del personal
		//	      Returns: lb_valido True si se ejecutaron los insert ó False si hubo error en los insert
		//	  Description: Funcion que inserta todos los registros del personal con el nuevo código
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 30/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($lb_valido)
		{// Insertamos en Personal
			$ls_sql="INSERT INTO sno_personal (codemp, codper, cedper, nomper, apeper, dirper, fecnacper, edocivper, telhabper, ".
					"			 telmovper, sexper, estaper, pesper, codpro, nivacaper, catper, cajahoper, numhijper, contraper, ".
					"			 tipvivper, tenvivper, monpagvivper, ingbrumen, cuecajahoper, cuelphper, cuefidper, fecingadmpubper, ".
					"			 vacper, porisrper, fecingper, anoservpreper, cedbenper, fecegrper, estper, fotper, codpai, codest, ".
					"			 codmun, codpar, obsper, cauegrper, obsegrper, nacper, coreleper, cenmedper, turper, horper, hcmper, ".
					"			 tipsanper, codcom, codran, numexpper, codpainac, codestnac, monpagvivperaux, ingbrumenaux, anoservprecont,  anoservprefijo) ".
					"     SELECT codemp, '".$as_codnue."' as codper, cedper, nomper, apeper, dirper, fecnacper, edocivper, telhabper, ".
					"			 telmovper, sexper, estaper, pesper, codpro, nivacaper, catper, cajahoper, numhijper, contraper, ".
					"			 tipvivper, tenvivper, monpagvivper, ingbrumen, cuecajahoper, cuelphper, cuefidper, fecingadmpubper, ".
					"			 vacper, porisrper, fecingper, anoservpreper, cedbenper, fecegrper, estper, fotper, codpai, codest, ".
					"			 codmun, codpar, obsper, cauegrper, obsegrper, nacper, coreleper, cenmedper, turper, horper, hcmper, ".
					"			 tipsanper, codcom, codran, numexpper, codpainac, codestnac, monpagvivperaux, ingbrumenaux, anoservprecont,  anoservprefijo ".
					"       FROM sno_personal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los Familiares del personal
			$ls_sql="INSERT INTO sno_familiar (codemp, codper, cedfam, nomfam, apefam, sexfam, fecnacfam, nexfam, estfam, hcmfam, hcfam) ".
					"     SELECT codemp, '".$as_codnue."' as  codper, cedfam, nomfam, apefam, sexfam, fecnacfam, nexfam, estfam, hcmfam, hcfam ".
					"       FROM sno_familiar ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los Trabajos Anteriores del personal
			$ls_sql="INSERT INTO sno_trabajoanterior (codemp, codper, codtraant, emptraant, ultcartraant, ultsuetraant, ".
					"			 fecingtraant, fecrettraant, emppubtraant, codded, anolab, meslab, dialab, ultsuetraantaux) ".
					"     SELECT codemp, '".$as_codnue."' as codper, codtraant, emptraant, ultcartraant, ultsuetraant, ".
					"			 fecingtraant, fecrettraant, emppubtraant, codded, anolab, meslab, dialab, ultsuetraantaux ".
					"       FROM sno_trabajoanterior ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los Estudios Realizados del personal
			$ls_sql="INSERT INTO sno_estudiorealizado (codemp, codper, codestrea, tipestrea, insestrea, desestrea, titestrea, calestrea, fecgraestrea, escval, feciniact, fecfinact) ".
					"     SELECT codemp, '".$as_codnue."' as codper, codestrea, tipestrea, insestrea, desestrea, titestrea, calestrea, fecgraestrea, escval, feciniact, fecfinact ".
					"       FROM sno_estudiorealizado ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el ISR por meses del personal
			$ls_sql="INSERT INTO sno_personalisr (codemp, codper, codisr, porisr) ".
					"     SELECT codemp, '".$as_codnue."' as codper, codisr, porisr ".
					"       FROM sno_personalisr ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los permisos del personal
			$ls_sql="INSERT INTO sno_permiso (codemp, codper, numper, feciniper, fecfinper, numdiaper, afevacper, tipper, obsper, remper) ".
					"     SELECT codemp, '".$as_codnue."' as codper, numper, feciniper, fecfinper, numdiaper, afevacper, tipper, obsper, remper ".
					"       FROM sno_permiso ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el fideicomiso del personal
			$ls_sql="INSERT INTO sno_fideicomiso (codemp, codper, codfid, ficfid, ubifid, cuefid, fecingfid, capfid, capantcom) ".
					"     SELECT codemp, '".$as_codnue."' as  codper, codfid, ficfid, ubifid, cuefid, fecingfid, capfid, capantcom ".
					"       FROM sno_fideicomiso ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el beneficiario del personal
			$ls_sql="INSERT INTO sno_beneficiario (codemp, codper, codben, cedben, nomben, apeben, dirben, telben, tipben, nomcheben, ".
					"			 porpagben, monpagben, codban, ctaban, sc_cuenta, forpagben, nacben, tipcueben) ".
					"     SELECT codemp, '".$as_codnue."' as  codper, codben, cedben, nomben, apeben, dirben, telben, tipben, nomcheben, ".
					"			 porpagben, monpagben, codban, ctaban, sc_cuenta, forpagben, nacben, tipcueben  ".
					"       FROM sno_beneficiario ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos las vacaciones del personal
			$ls_sql="INSERT INTO sno_vacacpersonal (codemp, codper, codvac, fecvenvac, fecdisvac, fecreivac, diavac, stavac, ".
					"			 sueintbonvac, sueintvac, diabonvac, obsvac, diapenvac, persalvac, peringvac, dianorvac, quisalvac, ".
					"			 quireivac, diaadivac, diaadibon, diafer, sabdom, periodo_1, cod_1, nro_dias_1, Monto_1, periodo_2, ".
					"			 cod_2, nro_dias_2, Monto_2, periodo_3, cod_3, nro_dias_3, Monto_3, periodo_4, cod_4, nro_dias_4, ".
					"			 Monto_4, periodo_5, cod_5, nro_dias_5, Monto_5, diapag, pagcan) ".
					"     SELECT codemp, '".$as_codnue."' as codper, codvac, fecvenvac, fecdisvac, fecreivac, diavac, stavac, ".
					"			 sueintbonvac, sueintvac, diabonvac, obsvac, diapenvac, persalvac, peringvac, dianorvac, quisalvac, ".
					"			 quireivac, diaadivac, diaadibon, diafer, sabdom, periodo_1, cod_1, nro_dias_1, Monto_1, periodo_2, ".
					"			 cod_2, nro_dias_2, Monto_2, periodo_3, cod_3, nro_dias_3, Monto_3, periodo_4, cod_4, nro_dias_4, ".
					"			 Monto_4, periodo_5, cod_5, nro_dias_5, Monto_5, diapag, pagcan ".
					"       FROM sno_vacacpersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el fideicomiso por período del personal
			$ls_sql="INSERT INTO sno_fideiperiodo (codemp, codnom, codper, anocurper, mescurper, bonvacper, bonfinper, sueintper, apoper, bonextper, diafid, diaadi) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocurper, mescurper, bonvacper, bonfinper, sueintper, apoper, bonextper, diafid, diaadi ".
					"       FROM sno_fideiperiodo ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el afiliado a ipasme  del personal
			$ls_sql="INSERT INTO sno_ipasme_afiliado (codemp, codper, tiptraafi, coddep, actlabafi, tipafiafi, codban, cuebanafi, ".
					"			 tipcueafi, codent, codmun, codloc, urbafi, aveafi, nomresafi, pisafi, zonafi, numresafi) ".
					"     SELECT codemp, '".$as_codnue."' as codper, tiptraafi, coddep, actlabafi, tipafiafi, codban, cuebanafi, ".
					"			 tipcueafi, codent, codmun, codloc, urbafi, aveafi, nomresafi, pisafi, zonafi, numresafi ".
					"       FROM sno_ipasme_afiliado ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el beneficiario de ipasme  del personal
			$ls_sql="INSERT INTO sno_ipasme_beneficiario (codemp, codper, codben, cedben, tiptraben, codpare, nacben, prinomben, ".
					"			 segnomben, priapeben, segapeben, sexben, fecnacben, estcivben, fecfalben, codban, numcueben, tipcueben) ".
					"     SELECT codemp, '".$as_codnue."' as codper, codben, cedben, tiptraben, codpare, nacben, prinomben, ".
					"			 segnomben, priapeben, segapeben, sexben, fecnacben, estcivben, fecfalben, codban, numcueben, tipcueben ".
					"       FROM sno_ipasme_beneficiario ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el personal nómina
			$ls_sql="INSERT INTO sno_personalnomina (codemp, codnom, codper, codsubnom, codasicar, codtab, codgra, codpas, sueper, ".
					"			 horper, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, pagbanper, codban, codcueban, ".
					"			 tipcuebanper, codcar, fecingper, staper, cueaboper, fecculcontr, codded, codtipper, quivacper, ".
					"			 codtabvac, sueintper, pagefeper, sueproper, codage, fecegrper, fecsusper, cauegrper, codescdoc, ".
					"			 codcladoc, codubifis, tipcestic, conjub, catjub, codclavia, codunirac, fecascper, pagtaqper, grado) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, codsubnom, codasicar, codtab, codgra, codpas, sueper, ".
					"			 horper, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, pagbanper, codban, codcueban, ".
					"			 tipcuebanper, codcar, fecingper, staper, cueaboper, fecculcontr, codded, codtipper, quivacper, ".
					"			 codtabvac, sueintper, pagefeper, sueproper, codage, fecegrper, fecsusper, cauegrper, codescdoc, ".
					"			 codcladoc, codubifis, tipcestic, conjub, catjub, codclavia, codunirac, fecascper, pagtaqper, grado ".
					"       FROM sno_personalnomina ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos las constantes del personal nómina
			$ls_sql="INSERT INTO sno_constantepersonal (codemp, codnom, codper, codcons, moncon) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, codcons, moncon ".
					"       FROM sno_constantepersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los conceptos del personal nómina
			$ls_sql="INSERT INTO sno_conceptopersonal (codemp, codnom, codper, codconc, aplcon, valcon, acuemp, acuiniemp, acupat, acuinipat) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, codconc, aplcon, valcon, acuemp, acuiniemp, acupat, acuinipat ".
					"       FROM sno_conceptopersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los proyectos del personal nómina
			$ls_sql="INSERT INTO sno_proyectopersonal (codemp, codnom, codproy, codper, totdiaper, totdiames, pordiames) ".
					"     SELECT codemp, codnom, codproy, '".$as_codnue."' as codper, totdiaper, totdiames, pordiames ".
					"       FROM sno_proyectopersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los prestamos del personal nómina
			$ls_sql="INSERT INTO sno_prestamos (codemp, codnom, codper, numpre, codtippre, codconc, monpre, numcuopre, perinipre, ".
					"			 monamopre, stapre, fecpre, obsrecpre, obssuspre) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, numpre, codtippre, codconc, monpre, numcuopre, perinipre, ".
					"			 monamopre, stapre, fecpre, obsrecpre, obssuspre ".
					"       FROM sno_prestamos ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los prestamos período del personal nómina
			$ls_sql="INSERT INTO sno_prestamosperiodo (codemp, codnom, codper, numpre, codtippre, numcuo, percob, feciniper, fecfinper, moncuo, estcuo) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, numpre, codtippre, numcuo, percob, feciniper, fecfinper, moncuo, estcuo ".
					"       FROM sno_prestamosperiodo ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos los prestamos amortizado del personal nómina
			$ls_sql="INSERT INTO sno_prestamosamortizado (codemp, codnom, codper, numpre, codtippre, numamo, peramo, fecamo, monamo, desamo) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, numpre, codtippre, numamo, peramo, fecamo, monamo, desamo ".
					"       FROM sno_prestamosamortizado ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos la prenómina del personal nómina
			$ls_sql="INSERT INTO sno_prenomina (codemp, codnom, codper, codperi, codconc, tipprenom, valprenom, valhis) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, codperi, codconc, tipprenom, valprenom, valhis ".
					"       FROM sno_prenomina ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos la salida del personal nómina
			$ls_sql="INSERT INTO sno_salida (codemp, codnom, codperi, codper, codconc, tipsal, valsal, monacusal, salsal) ".
					"     SELECT codemp, codnom, codperi, '".$as_codnue."' as codper, codconc, tipsal, valsal, monacusal, salsal ".
					"       FROM sno_salida ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos el resumen del personal nómina
			$ls_sql="INSERT INTO sno_resumen (codemp, codnom, codperi, codper, asires, dedres, apoempres, apopatres, priquires, ".
					"			 segquires, monnetres, notres) ".
					"     SELECT codemp, codnom, codperi, '".$as_codnue."' as  codper, asires, dedres, apoempres, apopatres, priquires, ".
					"			 segquires, monnetres, notres ".
					"       FROM sno_resumen ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos histórico del personal nómina
			$ls_sql="INSERT INTO sno_hpersonalnomina (codemp, codnom, anocur, codperi, codasicar, codper, codsubnom, codtab, codgra, ".
					"			 codpas, sueper, horper, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, pagbanper, codban, ".
					"			 codcueban, tipcuebanper, codcar, fecingper, staper, cueaboper, fecculcontr, codded, codtipper, quivacper, ".
					"			 codtabvac, sueintper, pagefeper, sueproper, codage, fecegrper, fecsusper, cauegrper, codescdoc, codcladoc, ".
					"			 codubifis, tipcestic, conjub, catjub, codclavia) ".
					"     SELECT codemp, codnom, anocur, codperi, codasicar, '".$as_codnue."' as codper, codsubnom, codtab, codgra, ".
					"			 codpas, sueper, horper, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, pagbanper, codban, ".
					"			 codcueban, tipcuebanper, codcar, fecingper, staper, cueaboper, fecculcontr, codded, codtipper, quivacper, ".
					"			 codtabvac, sueintper, pagefeper, sueproper, codage, fecegrper, fecsusper, cauegrper, codescdoc, codcladoc, ".
					"			 codubifis, tipcestic, conjub, catjub, codclavia ".
					"       FROM sno_hpersonalnomina ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos histórico de las constantes personal nómina
			$ls_sql="INSERT INTO sno_hconstantepersonal (codemp, codnom, codper, anocur, codperi, codcons, moncon) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocur, codperi, codcons, moncon ".
					"       FROM sno_hconstantepersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos histórico de los conceptos personal nómina
			$ls_sql="INSERT INTO sno_hconceptopersonal (codemp, codnom, codper, anocur, codperi, codconc, aplcon, valcon, acuemp, acuiniemp, acupat, acuinipat) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocur, codperi, codconc, aplcon, valcon, acuemp, acuiniemp, acupat, acuinipat ".
					"       FROM sno_hconceptopersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos histórico de los proyectos personal nómina
			$ls_sql="INSERT INTO sno_hproyectopersonal (codemp, codnom, anocur, codperi, codproy, codper, totdiaper, totdiames, pordiames) ".
					"     SELECT codemp, codnom, anocur, codperi, codproy, '".$as_codnue."' as codper, totdiaper, totdiames, pordiames ".
					"       FROM sno_hproyectopersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos histórico de los prestamos personal nómina
			$ls_sql="INSERT INTO sno_hprestamos (codemp, codnom, codper, anocur, codperi, numpre, codtippre, codconc, monpre, numcuopre, perinipre, ".
					"			 monamopre, stapre, fecpre, obsrecpre, obssuspre) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocur, codperi, numpre, codtippre, codconc, monpre, numcuopre, perinipre, ".
					"			 monamopre, stapre, fecpre, obsrecpre, obssuspre ".
					"       FROM sno_hprestamos ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos histórico de los prestamos período personal nómina
			$ls_sql="INSERT INTO sno_hprestamosperiodo (codemp, codnom, codper, anocur, codperi, numpre, codtippre, numcuo, percob, feciniper, fecfinper, moncuo, estcuo) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocur, codperi, numpre, codtippre, numcuo, percob, feciniper, fecfinper, moncuo, estcuo ".
					"       FROM sno_hprestamosperiodo ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos histórico de los prestamos amortizado personal nómina
			$ls_sql="INSERT INTO sno_hprestamosamortizado (codemp, codnom, codper, numpre, codtippre, anocur, codperi, numamo, peramo, fecamo, monamo, desamo) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, numpre, codtippre, anocur, codperi, numamo, peramo, fecamo, monamo, desamo ".
					"       FROM sno_hprestamosamortizado ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos histórico de la prenómina personal nómina
			$ls_sql="INSERT INTO sno_hprenomina (codemp, codnom, codper, anocur, codperi, codconc, tipprenom, valprenom, valhis) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocur, codperi, codconc, tipprenom, valprenom, valhis ".
					"       FROM sno_hprenomina ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos histórico de la salida personal nómina
			$ls_sql="INSERT INTO sno_hsalida (codemp, codnom, codper, anocur, codperi, codconc, tipsal, valsal, monacusal, salsal, priquisal, segquisal) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocur, codperi, codconc, tipsal, valsal, monacusal, salsal, priquisal, segquisal ".
					"       FROM sno_hsalida ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos histórico del resumen personal nómina
			$ls_sql="INSERT INTO sno_hresumen (codemp, codnom, codper, anocur, codperi, asires, dedres, apoempres, apopatres, priquires, segquires, monnetres, notres) ".
					"     SELECT codemp, codnom, '".$as_codnue."' as codper, anocur, codperi, asires, dedres, apoempres, apopatres, priquires, segquires, monnetres, notres ".
					"       FROM sno_hresumen ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Insertamos histórico del resumen personal nómina
			$ls_sql="INSERT INTO sno_hvacacpersonal (codemp, codnom, anocur, codperi, codper, codvac, fecvenvac, fecdisvac, fecreivac, ".
					"			 diavac, stavac, sueintbonvac, sueintvac, diabonvac, obsvac, diapenvac, persalvac, peringvac, dianorvac, ".
					"			 quisalvac, quireivac, diaadivac, diaadibon, diafer, sabdom, periodo_1, cod_1, nro_dias_1, Monto_1, ".
					"			 periodo_2, cod_2, nro_dias_2, Monto_2, periodo_3, cod_3, nro_dias_3, Monto_3, periodo_4, cod_4, nro_dias_4, ".
					"			 Monto_4, periodo_5, cod_5, nro_dias_5, Monto_5, diapag, pagcan) ".
					"     SELECT codemp, codnom, anocur, codperi, '".$as_codnue."' as codper, codvac, fecvenvac, fecdisvac, fecreivac, ".
					"			 diavac, stavac, sueintbonvac, sueintvac, diabonvac, obsvac, diapenvac, persalvac, peringvac, dianorvac, ".
					"			 quisalvac, quireivac, diaadivac, diaadibon, diafer, sabdom, periodo_1, cod_1, nro_dias_1, Monto_1, ".
					"			 periodo_2, cod_2, nro_dias_2, Monto_2, periodo_3, cod_3, nro_dias_3, Monto_3, periodo_4, cod_4, nro_dias_4, ".
					"			 Monto_4, periodo_5, cod_5, nro_dias_5, Monto_5, diapag, pagcan ".
					"       FROM sno_hvacacpersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		return $lb_valido;
	}// end function uf_insert_registro
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_registro($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_registro
		//		   Access: private
		//	    Arguments: as_codper  // Código del personal
		//	      Returns: lb_valido True si se ejecutaron los delete ó False si hubo error en los delete
		//	  Description: Funcion que elimina todos los registros del personal con el viejo código
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 30/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($lb_valido)
		{// eliminamos histórico del resumen personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_hvacacpersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos histórico del resumen personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_hresumen ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos histórico de la salida personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_hsalida ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos histórico de la prenómina personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_hprenomina ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos histórico de los prestamos período personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_hprestamosamortizado ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos histórico de los prestamos período personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_hprestamosperiodo ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos histórico de los prestamos personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_hprestamos ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos histórico de los proyectos personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_hproyectopersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos histórico de los conceptos personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_hconceptopersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos histórico de las constantes personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_hconstantepersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos histórico del personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_hpersonalnomina ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos la salida del personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_salida ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos el resumen del personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_resumen ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos la prenómina del personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_prenomina ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los prestamos período del personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_prestamosamortizado ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los prestamos período del personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_prestamosperiodo ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los prestamos del personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_prestamos ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los conceptos del personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_conceptopersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos las constantes del personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_constantepersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los proyectos del personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_proyectopersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos el personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_personalnomina ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos el fideicomiso por período del personal
			$ls_sql="DELETE ".
					"  FROM sno_fideiperiodo ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los beneficiarios al ipasme del personal
			$ls_sql="DELETE ".
					"  FROM sno_ipasme_beneficiario ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los afiliados al ipasme del personal
			$ls_sql="DELETE ".
					"  FROM sno_ipasme_afiliado ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos las vacaciones del personal
			$ls_sql="DELETE ".
					"  FROM sno_vacacpersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos el fideicomiso del personal
			$ls_sql="DELETE ".
					"  FROM sno_fideicomiso ".
					"  WHERE codemp='".$this->ls_codemp."'".
					"    AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los permisos del personal
			$ls_sql="DELETE ".
					"  FROM sno_permiso ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos el ISR por meses del personal
			$ls_sql="DELETE ".
					"  FROM sno_personalisr ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los Estudios Realizados del personal
			$ls_sql="DELETE ".
					"  FROM sno_estudiorealizado ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los Trabajos Anteriores del personal
			$ls_sql="DELETE ".
					"  FROM sno_trabajoanterior ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los Familiares del personal
			$ls_sql="DELETE ".
					"  FROM sno_familiar ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos los Beneficiarios del personal
			$ls_sql="DELETE ".
					"  FROM sno_beneficiario ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// Eliminamos en Personal
			$ls_sql="DELETE ".
					"  FROM sno_personal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		return $lb_valido;
	}// end function uf_delete_registro
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_sql($as_sql)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_sql
		//		   Access: private
		//	    Arguments: as_sql  // Sentencia SQL que se quiere ejecutar
		//	      Returns: lb_valido True si se ejecuto el sql ó False si hubo error en el sql
		//	  Description: Funcion que ejecuta un sql
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 30/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_row=$this->io_sql->execute($as_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_procesar_sql ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_procesar_sql
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_fechaingreso($as_codper,&$ad_fecingper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_fechaingreso
		//		   Access: public (tepuy_snorh_c_fideicomiso)
		//	    Arguments: as_codper  // Código del personal
		//			  	   ad_fecingper  // Fecha de Ingreso Personal
		//	      Returns: lb_valido True si el select no tuvo errores ó False si hubo error
		//	  Description: Funcion que obtiene el código la fecha de ingreso a la institución
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 12/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT fecingper FROM sno_personal ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_load_fechaingreso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_fecingper=$row["fecingper"];
				if(strtotime($ad_fecingper)<=strtotime("1997-06-19"))
				{
					$ad_fecingper="1997-06-19";
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_fechaingreso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_beneficiario($as_cedper,$as_nomper,$as_apeper,$as_dirper,$as_telhabper,$as_telmovper,$as_coreleper,
									$as_cuentacontable,$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_nacper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_beneficiario
		//		   Access: private
		//	    Arguments: as_cedper  // Cédula del personal
		//			  	   as_nomper  // Nombre del Personal
		//			  	   as_apeper  // Apellido del Personal
		//			  	   as_dirper  // Dirección del Personal
		//			  	   as_telhabper  // Teléfono de Habitación del Personal
		//			  	   as_telmovper  // Teléfono Móvil del Personal
		//			  	   as_coreleper  // Correo del Personal
		//			  	   as_cuentacontable  // Cuenta Contable
		//			  	   as_codpai  // Código del País
		//			  	   as_codest  // Código del Estado
		//			  	   as_codmun  // Código del Municipio
		//			  	   as_codpar  // Código del Parroquia
		//			  	   as_nacper  // Naconalidad
		//			  	   aa_seguridad  // Arreglo de las Variables de Seguridad
		//	      Returns: lb_valido True si el select no tuvo errores ó False si hubo error
		//	  Description: Funcion que inserta el personal como beneficiario
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 02/08/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_tipconben="F";
		$ls_bansigcof='---';
		$ls_codban='---';
		$ls_ctaban="";	
		$ls_sql="SELECT ced_bene ".
				"  FROM rpc_beneficiario ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND ced_bene='".$as_cedper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_insert_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
        		$this->io_mensajes->message("La Cédula del personal ya existe como beneficiario"); 
			}
			else
			{
				$ls_sql="INSERT INTO rpc_beneficiario(codemp, ced_bene, nombene, apebene, dirbene, telbene, celbene, email, sc_cuenta, ".
						"codpai,codest,codmun,codpar,nacben,tipconben,codbansig,codban,ctaban,fecregben) VALUES ('".$this->ls_codemp."', ".
						"'".$as_cedper."', '".$as_nomper."', '".$as_apeper."', '".$as_dirper."', '".$as_telhabper."', '".$as_telmovper."', ".
						"'".$as_coreleper."', '".$as_cuentacontable."', '".$as_codpai."', '".$as_codest."', '".$as_codmun."', '".$as_codpar."', ".
						"'".$as_nacper."','".$ls_tipconben."','".$ls_bansigcof."','".$ls_codban."','".$ls_ctaban."','".date('Y-m-d')."') ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_insert_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó el Personal ".$as_cedper." como un beneficiario";
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
					 
				 }	  	
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;	
	}// end function uf_insert_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------
 function uf_buscar_periodo($as_codper)
	{
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_periodo
		//		   Access: private
		//	    Arguments: 
		//	      Returns: $codperi, devuelve el codigo del maximo periodo calculado a una persona
		//	  Description: 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/09/2008								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$codperi="";
		$ls_sql=" SELECT MAX(sno_hresumen.codperi) AS codperi      ".
		        "   FROM sno_hresumen                               ".
		        "  WHERE sno_hresumen.codemp='".$this->ls_codemp."' ".
				"    AND sno_hresumen.codper='".$as_codper."'       ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_buscar_periodo ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 			
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$codperi=$row["codperi"];				
			}
			$this->io_sql->free_result($rs_data);
		}
     	return $codperi;
	}// fin de uf_buscar_periodo
//------------------------------------------------------------------------------------------------------------------------------------

    function uf_buscar_nomina($as_codperi, $as_codper)
	{
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_nomina
		//		   Access: private
		//	    Arguments: 
		//	      Returns: $codnom, devuelve el codigo del maxima nomina calculado a una persona
		//	  Description: 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/09/2008								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$codnom="";
		$ls_sql=" SELECT  MAX(sno_hresumen.codnom) AS codnom ".
		        "   FROM  sno_hresumen                       ".
				"  WHERE  sno_hresumen.codemp='".$this->ls_codemp."'".
				"    AND  sno_hresumen.codper='".$as_codper."'".
				"    AND  sno_hresumen.codperi='".$as_codperi."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_buscar_nomina ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 			
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$codnom=$row["codnom"];				
			}
			$this->io_sql->free_result($rs_data);
		}
     	return $codnom;
	}// fin de uf_buscar_nomina
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_buscar_fecha_periodo($as_codper)
	{
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_fecha_periodo
		//		   Access: private
		//	    Arguments: 
		//	      Returns: $codnom, devuelve el codigo del maxima nomina calculado a una persona
		//	  Description: 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/09/2008								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_fechaperi="";
		$as_codperi=$this->uf_buscar_periodo($as_codper);
		$as_codnom=$this->uf_buscar_nomina($as_codperi, $as_codper);
		if (($as_codperi!="")&&($as_codnom!=""))
		{
		
			$ls_sql=" SELECT sno_hperiodo.fechasper                    ".
					"   FROM sno_hperiodo                              ".
					"  WHERE sno_hperiodo.codemp='".$this->ls_codemp."'".
					"    AND sno_hperiodo.codperi='".$as_codperi."'    ". 
					"    AND sno_hperiodo.codnom='".$as_codnom."'      "; 
					
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_buscar_fecha_periodo ERROR->".
											$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 			
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$as_fechaperi=$row["fechasper"];				
				}
				$this->io_sql->free_result($rs_data);
			}
		}
     	return $as_fechaperi;
	}// fin de uf_buscar_fecha_periodo
//------------------------------------------------------------------------------------------------------------------------------------

	function uf_buscar_personal_nomina($as_codper)
	{
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_buscar_personal_nomina
		//		   Access: private
		//	    Arguments: 
		//	      Returns: $codperi, devuelve el codigo del maximo periodo calculado a una persona
		//	  Description: 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 02/09/2008								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$codper="";
		$ls_sql=" SELECT DISTINCT(codper) as codper       ".
		        "   FROM sno_resumen                      ".
				"  WHERE codemp='".$this->ls_codemp."'    ".
				"    AND codper='".$as_codper."'          ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_buscar_personal_nomina ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 			
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$codper=$row["codper"];				
			}
			$this->io_sql->free_result($rs_data);
		}
     	return $codper;
	}// fin de uf_buscar_periodo
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_anotrabajoantfijo($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_anotrabajoantfijo
		//		   Access: public (tepuy_snorh_d_trabajoanterior)
		//	    Arguments: as_codper // Código de Personal				       
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que busca los años de trabajo previos como fijo
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="";	
		$anofijo=0;	
		  $ls_sql=" SELECT SUM(anolab) as anolab FROM sno_trabajoanterior ".
				  "	 WHERE codemp='".$this->ls_codemp."'   ". 
				  "	   AND codper='".$as_codper."'         ".
				  "	   AND emppubtraant='1'                ".
				  "	   AND (codded='100' OR codded='200')  "; 
				  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_select_anotrabajoantfijo ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$anofijo=$row["anolab"]; 
			}
			if ($anofijo=="")
			{
				$anofijo=0;
			}			
			$this->io_sql->free_result($rs_data);
		}
		return $anofijo;
	}// end function uf_select_trabajoanterior
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_select_anotrabajoantcontratado($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_anotrabajoantcontratado
		//		   Access: public (tepuy_snorh_d_trabajoanterior)
		//	    Arguments: as_codper // Código de Personal				       
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que busca los años de trabajo previos como fijo
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="";	
		$anocont=0;	
		  $ls_sql=" SELECT SUM(anolab) as anolab FROM sno_trabajoanterior ".
				  "	 WHERE codemp='".$this->ls_codemp."'   ". 
				  "	   AND codper='".$as_codper."'         ".
				  "	   AND emppubtraant='1'                ".
				  "	   AND (codded='300')  ";
				  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_select_anotrabajoantfijo ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$anocont=$row["anolab"]; 
			}
			if ($anocont=="")
			{
				$anocont=0;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $anocont;
	}//uf_select_anotrabajoantcontratado
//------------------------------------------------------------------------------------------------------------------------------------
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>
