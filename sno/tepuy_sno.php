<?php
class tepuy_sno
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	var $ls_codnom;
	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_sno()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_sno
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
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		$this->io_seguridad= new tepuy_c_seguridad();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if(array_key_exists("la_nomina",$_SESSION))
		{
        	$this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		}
		else
		{
			$this->ls_codnom="0000";
		}
	}// end function tepuy_sno
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que obtiene una variable de la tabla config
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_valor="";
		$ls_sql="SELECT value ".
				"  FROM tepuy_config ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codsis='".$as_sistema."' ".
				"   AND seccion='".$as_seccion."' ".
				"   AND entry='".$as_variable."' ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_select_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_valor=$row["value"];
				$li_i=$li_i+1;
			}
			if($li_i==0)
			{
				$lb_valido=$this->uf_insert_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo);
				if ($lb_valido)
				{
					$ls_valor=$this->uf_select_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo);
				}
			}
			$this->io_sql->free_result($rs_data);		
		}
		return rtrim($ls_valor);
	}// end function uf_select_config
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que inserta la variable de configuración
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();		
		$ls_sql="DELETE ".
				"  FROM tepuy_config ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codsis='".$as_sistema."' ".
				"   AND seccion='".$as_seccion."' ".
				"   AND entry='".$as_variable."' ";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			switch ($as_tipo)
			{
				case "C"://Caracter
					$valor = $as_valor;
					break;

				case "D"://Double
					$as_valor=str_replace(".","",$as_valor);
					$as_valor=str_replace(",",".",$as_valor);
					$valor = $as_valor;
					break;

				case "B"://Boolean
					$valor = $as_valor;
					break;

				case "I"://Integer
					$valor = intval($as_valor);
					break;
			}
			$ls_sql="INSERT INTO tepuy_config(codemp, codsis, seccion, entry, value, type)VALUES ".
					"('".$this->ls_codemp."','".$as_sistema."','".$as_seccion."','".$as_variable."','".$valor."','".$as_tipo."')";
					
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				$this->io_sql->commit();
			}
		}
		return $lb_valido;
	}// end function uf_insert_config	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_fin_mes($ad_fecdes,$ad_fechas)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_fin_mes
		//		   Access: public
		//	    Arguments: ad_fecdes // Fecha Desde
		//				   ad_fechas // fecha Hasta
		//	      Returns: lb_valido True si se cumple la condición de fin de mes ó False si no se cumple
		//	  Description: función que dada una fecha de inicio y una fecha fin se verifica si es fin de mes
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fechas=$this->io_funciones->uf_convertirfecmostrar($ad_fechas);
		$ad_fechas=$this->uf_suma_fechas($ad_fechas,1);
		$ls_mesdes=substr($ad_fecdes,5,2);
		$ls_meshas=substr($ad_fechas,3,2);
		if($ls_mesdes==$ls_meshas)
		{
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_fin_mes	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_suma_fechas($ad_fecha,$ai_ndias)
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_suma_fechas
		//		   Access: public
		//	    Arguments: ad_fecha // Fecha a la que se desa sumar
		//                 ai_ndias // Cantidad de dias a sumar          
		//	      Returns: nuevafecha-> variable date
		//	  Description: suma una cantidad de dias pasado por parametros  a una fecha pasada por parametros 
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($ai_ndias>0)
		{
			$dia=substr($ad_fecha,0,2);      
			$mes=substr($ad_fecha,3,2);      
			$anio=substr($ad_fecha,6,4);      
			$ultimo_dia=date("d",mktime(0, 0, 0,$mes+1,0,$anio));
			$dias_adelanto=$ai_ndias;
			$siguiente=$dia+$dias_adelanto;
			if($ultimo_dia<$siguiente)
			{        
				$dia_final=$siguiente-$ultimo_dia;
				$mes++;         
				if($ai_ndias=='365')
				{
					$dia_final=$dia;
				}    
				if($mes=='13')
				{            
					$anio++;
					$mes='01';        
				}      
				$fecha_final=str_pad($dia_final,2,"0",0).'/'.str_pad($mes,2,"0",0).'/'.$anio; 
			}
			else   
			{ 
				$fecha_final=str_pad($siguiente,2,"0",0).'/'.str_pad($mes,2,"0",0).'/'.$anio; 
			} 
			$ls_dia=substr($fecha_final,0,2);
			$ls_mes=substr($fecha_final,3,2);
			$ls_ano=substr($fecha_final,6,4);
			while(checkdate($ls_mes,$ls_dia,$ls_ano)==false)
			{ 
			   $ls_dia=$ls_dia-1; 
			   break;
			} 
			$fecha_final=$ls_dia."/".$ls_mes."/".$ls_ano;
		}
		else
		{
			$fecha_final=$ad_fecha;
		}
		return $fecha_final;
    }// end function uf_suma_fechas	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_nro_lunes($ad_fecdes,$ad_fechas)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_nro_lunes
		//		   Access: public
		//	    Arguments: ad_fecdes // Fecha Desde
		//				   ad_fechas // fecha Hasta
		//	      Returns: $li_valor cantidad de lunes del rango de fecha
		//	  Description: función que dada una fecha de inicio y una fecha fin se cuentan cuantos lunes tienen
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_valor=0;
		$ad_fecdes=$this->io_funciones->uf_convertirfecmostrar($ad_fecdes);
		$ad_fechas=$this->io_funciones->uf_convertirfecmostrar($ad_fechas);
		$ld_desde=mktime(0,0,0,substr($ad_fecdes,3,2),substr($ad_fecdes,0,2),substr($ad_fecdes,6,4));
		$ld_hasta=mktime(0,0,0,substr($ad_fechas,3,2),substr($ad_fechas,0,2),substr($ad_fechas,6,4));
		while ($ld_desde<=$ld_hasta)
		{
			$ld_fecha=mktime(0,0,0,substr($ad_fecdes,3,2),substr($ad_fecdes,0,2),substr($ad_fecdes,6,4));
			if(strftime('%w',$ld_fecha)==1)
			{
				$li_valor=$li_valor+1;
			}
			$ad_fecdes=$this->uf_suma_fechas($ad_fecdes,1);
			$ld_desde=mktime(0,0,0,substr($ad_fecdes,3,2),substr($ad_fecdes,0,2),substr($ad_fecdes,6,4));
		}
		return $li_valor;
	}// end function uf_nro_lunes	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_nro_sabydom($ad_fecdes,$ad_fechas)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_nro_sabydom
		//		   Access: public
		//	    Arguments: ad_fecdes // Fecha Desde
		//				   ad_fechas // fecha Hasta
		//				   ai_dia // día de la semana 6->sábado y 0->Domingo
		//	      Returns: $li_valor cantidad de sábados y domingos del rango de fecha
		//	  Description: función que dada una fecha de inicio y una fecha fin se cuentan cuantos sábados y domingos tienen
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_valor=0;
		$ad_fecdes=$this->io_funciones->uf_convertirfecmostrar($ad_fecdes);
		$ad_fechas=$this->io_funciones->uf_convertirfecmostrar($ad_fechas);
		$ld_desde=mktime(0,0,0,substr($ad_fecdes,3,2),substr($ad_fecdes,0,2),substr($ad_fecdes,6,4));
		$ld_hasta=mktime(0,0,0,substr($ad_fechas,3,2),substr($ad_fechas,0,2),substr($ad_fechas,6,4));
		while ($ld_desde<=$ld_hasta)
		{
			$ld_fecha=mktime(0,0,0,substr($ad_fecdes,3,2),substr($ad_fecdes,0,2),substr($ad_fecdes,6,4));
			if((strftime('%w',$ld_fecha)==6)||(strftime('%w',$ld_fecha)==0))
			{
				$li_valor=$li_valor+1;
			}
			$ad_fecdes=$this->uf_suma_fechas($ad_fecdes,1);
			$ld_desde=mktime(0,0,0,substr($ad_fecdes,3,2),substr($ad_fecdes,0,2),substr($ad_fecdes,6,4));
		}
		return $li_valor;
	}// end function uf_nro_sabydom	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_periodo_previo(&$ai_anoprev,&$ai_periprev)
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_periodo_previo
		//		   Access: public
		//	    Arguments: ai_anoprev // Año Previo
		//                 ai_periprev // periodo previo          
		//	      Returns: lb_valido True si se ejecuto correctamente la funación y false si hubo error
		//	  Description: función que devuelve el período previo de la nómina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_anoprev=$_SESSION["la_nomina"]["anocurnom"];
		$ai_periprev=(intval($_SESSION["la_nomina"]["peractnom"])-1);
		if($ai_periprev<1)
		{
			$ai_anoprev=(intval($ai_anoprev)-1);
			$ls_sql="SELECT numpernom ".
					"  FROM sno_hnomina ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND anocurnom='".$ai_anoprev."' ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_periodo_previo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ai_periprev=$row["numpernom"];
				}
				if($ai_periprev<1)
				{
					$lb_valido=false;
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		$ai_periprev=str_pad($ai_periprev,3,"0",0);
      	return ($lb_valido);  
    }// end function uf_periodo_previo	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_crear_sessionnomina()
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_crear_sessionnomina
		//		   Access: public (en toda las pantallas de procesos)
		//	      Returns: lb_valido True si se ejecuto correctamente la función y false si hubo error
		//	  Description: Función que crea la sessión de la nómina actual
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 20/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if(array_key_exists("nom",$_GET))
		{
			$ls_nomina=$_GET["nom"];
		}
		else
		{
			$ls_nomina=$_SESSION["la_nomina"]["codnom"];
		}
		$ls_sql="SELECT sno_nomina.codnom, sno_nomina.desnom, sno_nomina.peractnom, sno_nomina.diabonvacnom, sno_nomina.diareivacnom, ".
				"		sno_nomina.adenom, sno_periodo.fecdesper, sno_periodo.fechasper, sno_periodo.cerper, sno_periodo.conper,".
				"		sno_periodo.totper, sno_nomina.anocurnom, sno_nomina.tippernom, sno_nomina.perresnom, sno_nomina.racnom, ".
				"		sno_nomina.subnom, sno_nomina.espnom, sno_nomina.consulnom, sno_nomina.descomnom, sno_nomina.codpronom, ".
				"		sno_nomina.codbennom, sno_nomina.conaponom, sno_nomina.cueconnom, sno_nomina.notdebnom, ".
				"       sno_nomina.numvounom, sno_nomina.recdocnom, sno_nomina.recdocapo, sno_nomina.tipdocnom, sno_nomina.tipdocapo, ".
				"		sno_nomina.tipnom, sno_nomina.fecininom, sno_nomina.numpernom, sno_nomina.conpernom, sno_nomina.conpronom, ".
				"		sno_nomina.titrepnom, sno_nomina.divcon, sno_nomina.subnom ".
				"  FROM sno_nomina, sno_periodo ".
				" WHERE sno_nomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_nomina.codnom='".$ls_nomina."' ".
				"   AND sno_nomina.codemp=sno_periodo.codemp ".
				"   AND sno_nomina.codnom=sno_periodo.codnom ".
				"   AND sno_nomina.peractnom=sno_periodo.codperi ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_crear_sessionnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				unset($_SESSION["la_nomina"]);
				$_SESSION["la_nomina"]=$row;
				$ld_fecdesper=$this->io_funciones->uf_formatovalidofecha($_SESSION["la_nomina"]["fecdesper"]);
				$ld_fechasper=$this->io_funciones->uf_formatovalidofecha($_SESSION["la_nomina"]["fechasper"]);
				$_SESSION["la_nomina"]["fecdesper"]=$ld_fecdesper;
				$_SESSION["la_nomina"]["fechasper"]=$ld_fechasper;
				$ld_fecdesper=$this->io_funciones->uf_convertirfecmostrar($ld_fecdesper);
				$ld_fechasper=$this->io_funciones->uf_convertirfecmostrar($ld_fechasper);
				$ls_desper=" Año <strong>".$_SESSION["la_nomina"]["anocurnom"]."</strong> Período <strong>".$_SESSION["la_nomina"]["peractnom"]."</strong> ".$ld_fecdesper." - ".$ld_fechasper."";
				$_SESSION["la_nomina"]["descripcionperiodo"]=$ls_desper;
				$_SESSION["la_nomina"]["tiponomina"]="NORMAL";
			}
			else
			{
				unset($_SESSION["la_nomina"]);
				$lb_valido=false;
				$this->io_mensajes->message("Favor verifique los datos de la nómina. No se pueden cargar los datos."); 
				print "<script language=JavaScript>";
				print "location.href='tepuywindow_blank.php'";
				print "</script>";		
			}
			$this->io_sql->free_result($rs_data);
		}
      	return ($lb_valido);  
    }// end function uf_crear_sessionnomina	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_crear_sessionhnomina()
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_crear_sessionhnomina
		//		   Access: public (en toda las pantallas de procesos)
		//	      Returns: lb_valido True si se ejecuto correctamente la función y false si hubo error
		//	  Description: Función que crea la sessión de la nómina actual
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 20/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if(array_key_exists("codnom",$_GET))
		{
			$ls_codnom=$_GET["codnom"];
			$li_anocurnom=$_GET["anocurnom"];
			$ls_peractnom=$_GET["peractnom"];
		}
		else
		{
			$ls_codnom=$_SESSION["la_nomina"]["codnom"];
			$li_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
			$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
			unset($_SESSION["la_nomina"]);
		}
		$ls_sql="SELECT sno_hnomina.codnom, sno_hnomina.desnom, sno_hnomina.peractnom, sno_hnomina.diabonvacnom, sno_hnomina.diareivacnom, ".
				"		sno_hnomina.adenom, sno_hperiodo.fecdesper, sno_hperiodo.fechasper, sno_hperiodo.cerper, sno_hperiodo.conper,".
				"		sno_hperiodo.totper, sno_hnomina.anocurnom, sno_hnomina.tippernom, sno_hnomina.perresnom, sno_hnomina.racnom, ".
				"		sno_hnomina.subnom, sno_hnomina.espnom, sno_hnomina.consulnom, sno_hnomina.descomnom, sno_hnomina.codpronom, ".
				"		sno_hnomina.codbennom, sno_hnomina.conaponom, sno_hnomina.cueconnom, sno_hnomina.notdebnom, ".
				"       sno_hnomina.numvounom, sno_hnomina.recdocnom, sno_hnomina.recdocapo, sno_hnomina.tipdocnom, sno_hnomina.tipdocapo, ".
				"		sno_hnomina.tipnom, sno_hnomina.fecininom, sno_hnomina.numpernom, sno_hnomina.conpernom, sno_hnomina.conpronom, ".
				"		sno_hnomina.titrepnom, sno_hnomina.divcon, sno_hnomina.subnom ".
				"  FROM sno_hnomina, sno_hperiodo ".
				" WHERE sno_hnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hnomina.codnom='".$ls_codnom."' ".
				"   AND sno_hnomina.anocurnom='".$li_anocurnom."' ".
				"   AND sno_hnomina.peractnom='".$ls_peractnom."' ".
				"   AND sno_hnomina.codemp=sno_hperiodo.codemp ".
				"   AND sno_hnomina.codnom=sno_hperiodo.codnom ".
				"   AND sno_hnomina.anocurnom=sno_hperiodo.anocur ".
				"   AND sno_hnomina.peractnom=sno_hperiodo.codperi ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_crear_sessionhnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$_SESSION["la_nomina"]=$row;
				$ld_fecdesper=$this->io_funciones->uf_formatovalidofecha($_SESSION["la_nomina"]["fecdesper"]);
				$ld_fechasper=$this->io_funciones->uf_formatovalidofecha($_SESSION["la_nomina"]["fechasper"]);
				$_SESSION["la_nomina"]["fecdesper"]=$ld_fecdesper;
				$_SESSION["la_nomina"]["fechasper"]=$ld_fechasper;
				$ld_fecdesper=$this->io_funciones->uf_convertirfecmostrar($ld_fecdesper);
				$ld_fechasper=$this->io_funciones->uf_convertirfecmostrar($ld_fechasper);
				$ls_desper="<strong>Histórico</strong> - Año <strong>".$_SESSION["la_nomina"]["anocurnom"]."</strong> Período <strong>".$_SESSION["la_nomina"]["peractnom"]."</strong> ".$ld_fecdesper." - ".$ld_fechasper."";
				$_SESSION["la_nomina"]["descripcionperiodo"]=$ls_desper;
				$_SESSION["la_nomina"]["tiponomina"]="HISTORICA";
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("Favor verifique los datos de los históricos. No se pueden cargar los datos."); 
				print "<script language=JavaScript>";
				print "location.href='tepuywindow_blank.php'";
				print "</script>";		
			}
			$this->io_sql->free_result($rs_data);
		}
      	return ($lb_valido);  
    }// end function uf_crear_sessionhnomina	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_configuracion($ai_vac_reportar,$as_vac_codconvac,$as_vac_metvac,$as_est_codconcsue,$as_est_estnom,$as_est_ordcons,
									  $as_est_ordconc,$as_est_estrec,$ai_est_numlin,$as_est_prilpt,$as_est_agrsem,$ai_con_parnom,
									  $as_con_consue,$as_con_cuecon,$as_con_conapo,$as_con_conpro,$ai_con_agrcon,$ai_con_gennotdeb,
									  $ai_con_genvou,$as_con_descon,$ai_par_excpersus,$ai_par_perrep,$ad_par_fecfinano,$as_par_metcalfid,
									  $as_fpj_codorgfpj,$as_fpj_codconcfpj,$as_fpj_metfpj,$as_lph_codconclph,$as_lph_metlph,$as_fpa_codconcfpa,
									  $as_fpa_metfpa,$ai_fps_antcom,$ai_fps_fraali,$as_fps_metfps,$as_man_cueconc,$as_man_cueconccaj,
									  $ai_man_actblofor,$ai_man_actblocalnom,$as_man_metrescon,$as_dis_metdisnom,$ai_con_genrecdoc,
									  $ai_con_genrecdocapo,$as_con_tipdocnom,$as_con_tipdocapo,$as_ipas_codorgipas,
									  $as_ipas_codconcahoipas,$as_ipas_codconcseripas,$as_ipas_conhipespipas,$as_ipas_conhipampipas,
									  $as_ipas_conhipconipas,$as_ipas_conhiphipipas,$as_ipas_conhiplphipas,$as_ipas_conhipvivipas,
									  $as_ipas_conperipas,$as_ipas_conturipas,$as_ipas_conproipas,$as_ipas_conasiipas,
									  $as_ipas_convehipas,$as_ipas_concomipas,$as_ivss_numemp,$ai_vac_desincorporar,$as_par_concsuelant,
									  $as_par_confpre,$as_par_camuniadm,$as_par_campasogrado,$as_par_incperben,$as_par_cueconben,
									  $as_par_codunirac,$as_par_comautrac,$as_par_ajusuerac,$ai_par_loncueban,$as_par_modpensiones,$ai_par_valloncueban, 
									  $ai_par_valporpre,$as_con_confidnom,$as_con_recdocfid,$as_con_tipdocfid,
									  $as_con_cueconfid,$as_con_codbenfid,$as_ivss_metodo,$ai_par_alfnumcodper,$ai_prestamo,$ai_fps_intasiextra,
									  $aa_seguridad)
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_configuracion
		//		   Access: public (tepuy_snorh_p_configuracion.php)
		//	    Arguments: ai_vac_reportar // reportar vacaciones             as_vac_codconvac // código de concepto de vacaciones
		//				   as_vac_metvac // método de vacaciones              as_est_codconcsue // código de concepto de sueldo
		//				   as_est_estnom // estilo de la nómina				  as_est_ordcons // ordenar las constantes
		//				   as_est_ordconc // ordenar los conceptos            as_est_estrec // estilo de la nómina  
		//				   ai_est_numlin // número de líneas del recibo       as_est_prilpt // reporte 
		//				   as_est_agrsem // agrupación de semana              ai_con_parnom // parámetros por nómina
		//				   as_con_consue // contabilización de los sueldos	  as_con_cuecon // cuenta contable 
		//				   as_con_conapo // contabilización de aportes        as_con_conpro // contabilización de programática 
		//				   ai_con_agrcon // agrupar contable   				  ai_con_gennotdeb // generar nota de débito
		//				   ai_con_genvou // generar voucher a nota de débito  as_con_descon // contabilización de programática 
		//				   ai_par_excpersus // excluir personas suspendidas   ai_par_perrep // no permitir repetidos
		//				   ad_par_fecfinano // fecha tope de bono de fin año  as_par_metcalfid // método de fideicomiso
		//				   as_fpj_codorgfpj // código de organismo            as_fpj_codconcfpj // código de concepto 
		//				   as_fpj_metfpj // método de fpj                     as_lph_codconclph // código de concepto 
		//				   as_lph_metlph // método de lph 					  as_fpa_codconcfpa // código de concepto
		//				   as_fpa_metfpa // método de fpa                     ai_fps_antcom // Antiguedad complementaria 
		//				   ai_fps_fraali // Fracción de Alicuota              as_fps_metfps // método de fps 
		//				   as_man_cueconc // cuentas de conceptos   		  as_man_cueconccaj // cuentas de conceptos caja
		//				   ai_man_actblofor // bloqueo formulas conceptos 	  ai_man_actblocalnom // activar bloqueo de cálculo de la nómina 
		//				   as_man_metrescon // método de resumen contable     as_dis_metdisnom // método de disco de nómina
		//				   ai_con_genrecdoc // generar recepción documento    ai_con_genrecdocapo // Generar recepción de documento aportes
		//				   as_con_tipdocnom // Tipo de documento de nom       as_con_tipdocapo // tipo de documento de aporte
		//				   as_ipas_codorgipas // código de Organismo IPASME   as_ipas_codconcahoipas // Código Concepto Ahorro IPASME
		//				   as_ipas_codconcseripas //  Código Concepto Servicio Asistencial IPASME
		//				   as_ipas_conhipespipas //  Código Concepto Hipotecario Especial IPASME
		//				   as_ipas_conhipampipas //  Código Concepto Hipotecario Ampliación IPASME
		//				   as_ipas_conhipconipas //  Código Concepto Hipotecario Construcción IPASME
		//				   as_ipas_conhiphipipas //  Código Concepto Hipotecario Hipoteca IPASME
		//				   as_ipas_conhiplphipas //  Código Concepto Hipotecario LPH IPASME
		//				   as_ipas_conhipvivipas //  Código Concepto Hipotecario Vivienda IPASME
		//				   as_ipas_conperipas //  Código Concepto Personal IPASME
		//				   as_ipas_conturipas //  Código Concepto Turisticos IPASME
		//				   as_ipas_conproipas //  Código Concepto Proveduria IPASME
		//				   as_ipas_conasiipas //  Código Concepto Asistenciales IPASME
		//				   as_ipas_convehipas //  Código Concepto Vehiculo IPASME
		//				   as_ipas_concomipas //  Código Concepto Comerciales IPASME	
		//				   as_ivss_numemp	 // Código de empresa que asigna el  IVSS
		//				   ai_vac_desincorporar	 // Si se desincorpora cuando el personal este de vacaciones 
		//				   as_par_concsuelant // Código de concepto de sueldo anterior
		//				   as_par_camuniadm  // Cambiar unidad administrativa
		//				   as_par_campasogrado // Cambiar paso y grado
		//				   as_par_incperben // Incluir el personal como beneficiario.
		//				   as_par_cueconben // Cuenta contable para los beneficiarios
		//				   as_par_codunirac // Código ünico de RAC
		//				   as_par_comautrac // Compensación automática de RAC
		//				   as_par_ajusuerac // Ajuste de sueldo de rac
		//				   as_par_modpensiones  // Modificar datos de pensiones
		//				   ai_par_valporpre // Validar que el prestamo no sea mayor al 30% del sueldo
		//				   as_ivss_metodo // Metodo sueldos (Integral o neto) a usar en IVSS
		//				   ai_par_alfnumcodper // Permitir alfanumericos en el código de personal
		//	      Returns: lb_valido True si se ejecuto correctamente el proceso y false si hubo error
		//	  Description: Función que graba todos los campos de la configuración
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 04/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lb_valido=true;
		//-------------------------------------VACACIONES------------------------------------------------------
		if($lb_valido)
		{// reportar vacaciones
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","MOSTRAR VACACION",$ai_vac_reportar,"C");
		}
		if($lb_valido)
		{// desincorporar de nómina vacaciones
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","DESINCORPORAR DE NOMINA",$ai_vac_desincorporar,"C");
		}
		if($lb_valido)
		{// código de concepto de vacaciones
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO VACACION",$as_vac_codconvac,"C");
		}
		if($lb_valido)
		{// método de vacaciones
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","METODO_VACACIONES",$as_vac_metvac,"C");
		}
		//------------------------------------------------------------------------------------------------------

		//--------------------------------------ESTILO DE NÓMINA-----------------------------------------------
		if($lb_valido)
		{// código de concepto de sueldo
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","SNO COD SUELDO",$as_est_codconcsue,"C");
		}
		if($lb_valido)
		{// estilo de la nómina
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","REP NOMINA",$as_est_estnom,"C");
		}
		if($lb_valido)
		{// ordenar las constantes
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","ORDEN CONSTANTE",$as_est_ordcons,"C");
		}
		if($lb_valido)
		{// ordenar los conceptos
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","ORDEN CONCEPTO",$as_est_ordconc,"C");
		}
		if($lb_valido)
		{// estilo de la nómina
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","REP RECIBOS",$as_est_estrec,"C");
		}
		if($lb_valido)
		{// número de líneas del recibo
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","REP RECIBO LINEAS",$ai_est_numlin,"C");
		}
		if($lb_valido)
		{// reporte
			$lb_valido=$this->uf_insert_config("SNO","PRINT","RECIBOS",$as_est_prilpt,"C");
		}
		if($lb_valido)
		{// agrupación de semana
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","NOM_SEM_SR",$as_est_agrsem,"C");
		}
		//-----------------------------------------------------------------------------------------------------

		//-------------------------------------CONTABILIZACION-------------------------------------------------
		if($lb_valido)
		{// parámetros por nómina
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","CONTA GLOBAL",$ai_con_parnom,"I");
		}
		if($lb_valido)
		{// contabilización de los sueldos
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","CONTABILIZACION",$as_con_consue,"C");
		}
		if($lb_valido)
		{// cuenta contable 
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","CTA.CONTA",$as_con_cuecon,"C");
		}
		if($lb_valido)
		{// contabilización de aportes
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","CONTABILIZACION APORTES",$as_con_conapo,"C");
		}
		if($lb_valido)
		{// contabilización de programática
			$lb_valido=$this->uf_insert_config("SNO","SPG","CONTABILIZACION",$as_con_conpro,"C");
		}
		if($lb_valido)
		{// agrupar contable
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","AGRUPARCONTA",$ai_con_agrcon,"I");
		}
		if($lb_valido)
		{// generar nota de débito
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","GENERAR NOTA DEBITO",$ai_con_gennotdeb,"I");
		}
		if($lb_valido)
		{// generar recepción de documento
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO",$ai_con_genrecdoc,"I");
		}
		if($lb_valido)
		{// generar recepción de documento aportes
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO APORTE",$ai_con_genrecdocapo,"I");
		}
		if($lb_valido)
		{// Tipo de documento de nómina
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","TIPO DOCUMENTO NOMINA",$as_con_tipdocnom,"C");
		}
		if($lb_valido)
		{// Tipo de documento de aporte
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","TIPO DOCUMENTO APORTE",$as_con_tipdocapo,"C");
		}
		if($lb_valido)
		{// generar voucher a nota de débito
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","VOUCHER GENERAR",$ai_con_genvou,"I");
		}
		if($lb_valido)
		{// contabilización de programática
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","CONTABILIZACION DESTINO",$as_con_descon,"C");
		}
		if($lb_valido)
		{// contabilización de programática
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","CONTABILIZACION FIDEICOMISO",$as_con_confidnom,"C");
		}
		if($lb_valido)
		{// contabilización de programática
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO FIDEICOMISO",$as_con_recdocfid,"I");
		}
		if($lb_valido)
		{// contabilización de programática
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","TIPO DOCUMENTO FIDEICOMISO",$as_con_tipdocfid,"C");
		}
		if($lb_valido)
		{// contabilización de programática
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","CTA.CONTABLE_FIDEICOMISO",$as_con_cueconfid,"C");
		}
		if($lb_valido)
		{// contabilización de programática
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","DESTINO FIDEICOMISO",$as_con_codbenfid,"C");
		}
		//-----------------------------------------------------------------------------------------------------

		//------------------------------------------PARÁMETROS-------------------------------------------------
		if($lb_valido)
		{// excluir personas suspendidas
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","EXCLUIR_SUSPENDIDOS",$ai_par_excpersus,"I");
		}
		if($lb_valido)
		{// no permitir repetidos
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","NOPERMITIR_REPETIDOS",$ai_par_perrep,"I");
		}
		if($lb_valido)
		{// fecha tope de bono de fin de año
			$lb_valido=$this->uf_insert_config("SNO","ANTIGUEDAD","FECHA_TOPE",$ad_par_fecfinano,"C");
		}
		if($lb_valido)
		{// método de fideicomiso
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","METODO FIDECOMISO",$as_par_metcalfid,"C");
		}
		if($lb_valido)
		{// Código de concepto de sueldo
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","CONCEPTO_SUELDO_ANT",$as_par_concsuelant,"C");
		}
		if($lb_valido)
		{// Configuración de prestamos por cuotas ó por montos
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","CONFIGURACION_PRESTAMO",$as_par_confpre,"C");
		}
		if($lb_valido)
		{// Configuración de si las nóminas están por rac que se pueda cambiar la unidad administrativa
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","CAMBIAR_UNIDAD_ADM_RAC",$as_par_camuniadm,"I");
		}
		if($lb_valido)
		{// Configuración de si las nóminas están por rac que se pueda cambiar el Paso y el Grado
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","CAMBIAR_PASO_GRADO_RAC",$as_par_campasogrado,"I");
		}
		if($lb_valido)
		{// Configuración de si el personal se agrega automáticamente como beneficiario
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","INCLUIR_A_BENEFICIARIO",$as_par_incperben,"I");
		}
		if($lb_valido)
		{// Configuración de la cuenta contable para el beneficiario
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","CUENTA_CONTABLE_BENEFICIARIO",$as_par_cueconben,"C");
		}
		if($lb_valido)
		{// Configuración del código unico de rac
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","CODIGO_UNICO_RAC",$as_par_codunirac,"I");
		}
		if($lb_valido)
		{// Configuración de la compensación automática
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","COMPENSACION_AUTOMATICA_RAC",$as_par_comautrac,"I");
		}
		if($lb_valido)
		{// Configuración del ajuste de sueldo del rac
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","AJUSTAR_SUELDO_RAC",$as_par_ajusuerac,"I");
		}
		if($lb_valido)
		{// Configuración del ajuste de sueldo del rac
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","CAMBIAR_PENSIONES",$as_par_modpensiones,"I");
		}
		if($lb_valido)
		{// Configuración del validacion de lontitud de cuenta de banco
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","LONGITUD_CUENTA_BANCO",$ai_par_loncueban,"I");
		}
		if($lb_valido)
		{// Configuración del validacion de lontitud de cuenta de banco
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","VALIDAR_LONGITUD_CUEBANCO",$ai_par_valloncueban,"I");
		}
		if($lb_valido)
		{// Configuración del validacion de lontitud de cuenta de banco
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","VAL_PORCENTAJE_PRESTAMO",$ai_par_valporpre,"I");
		}
		if($lb_valido)
		{// Configuración del validacion de lontitud de cuenta de banco
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","ALFNUM_CODPER",$ai_par_alfnumcodper,"I");
		}
		if($lb_valido)
		{// Configuración del validacion de prestamos al personal del mismo tipo
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","VAL_TIPO_PRESTAMO",$ai_prestamo,"I");
		}
		//------------------------------------------------------------------------------------------------------
		
		//-------------------------------------Aportes FPJ-----------------------------------------------------
		if($lb_valido)
		{// código de organismo
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD ORGANISMO FPJ",$as_fpj_codorgfpj,"C");
		}
		if($lb_valido)
		{// código de concepto
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO FPJ",$as_fpj_codconcfpj,"C");
		}
		if($lb_valido)
		{// método de fpj
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","METODO FPJ",$as_fpj_metfpj,"C");
		}
		//-----------------------------------------------------------------------------------------------------

		//-------------------------------------Aportes LPH-----------------------------------------------------
		if($lb_valido)
		{// código de concepto
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO LPH",$as_lph_codconclph,"C");
		}
		if($lb_valido)
		{// método de lph
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","METODO LPH",$as_lph_metlph,"C");
		}
		//-----------------------------------------------------------------------------------------------------
		
		//-------------------------------------Aportes FPA-----------------------------------------------------
		if($lb_valido)
		{// código de concepto
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO FPA",$as_fpa_codconcfpa,"C");
		}
		if($lb_valido)
		{// método de fpa
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","METODO FPA",$as_fpa_metfpa,"C");
		}
		//-----------------------------------------------------------------------------------------------------
		
		//-------------------------------------Aportes FPS-----------------------------------------------------
		if($lb_valido)
		{// Antiguedad complementaria
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COMPLEMENTO ANTIGUEDAD",$ai_fps_antcom,"I");
		}
		{// Fracción de Alicuota
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","FRACCION ALICUOTA",$ai_fps_fraali,"I");
		}
		if($lb_valido)
		{// método de fps
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","METODO FPS",$as_fps_metfps,"C");
		}
		if($lb_valido)
		{// método de fps
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","INT_ASIG_EXTRA",$ai_fps_intasiextra,"I");
		}
		//-----------------------------------------------------------------------------------------------------

		//-------------------------------------Mantenimiento---------------------------------------------------
		if($lb_valido)
		{// cuentas de conceptos
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","SPGCUENTA",$as_man_cueconc,"C");
		}
		if($lb_valido)
		{// cuentas de conceptos caja
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","CTACAJA",$as_man_cueconccaj,"C");
		}
		if($lb_valido)
		{// activar bloqueo de formulas de conceptos
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","ACTIVAR_BLOQUEO",$ai_man_actblofor,"I");
		}
		if($lb_valido)
		{// activar bloqueo de cálculo de la nómina
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","BLOQUEO_ACTIVAR",$ai_man_actblocalnom,"I");
		}
		if($lb_valido)
		{// método de resumen contable
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","METODO RESUMEN CONTABLE",$as_man_metrescon,"C");
		}
		//-----------------------------------------------------------------------------------------------------
		
		//-------------------------------------Disco Nómina----------------------------------------------------
		if($lb_valido)
		{// método de disco de nómina
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","METODO GD NOMINA",$as_dis_metdisnom,"C");
		}
		//-----------------------------------------------------------------------------------------------------

		//-------------------------------------Aportes IPASME-----------------------------------------------------
		if($lb_valido)
		{// Código de Organismo
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD ORGANISMO IPAS",$as_ipas_codorgipas,"C");
		}
		if($lb_valido)
		{// Código de Concepto de Ahorro
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO AHORRO IPAS",$as_ipas_codconcahoipas,"C");
		}
		if($lb_valido)
		{// Código de Concepto de Servicio Asistencial
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO SERVICIO IPAS",$as_ipas_codconcseripas,"C");
		}
		if($lb_valido)
		{// Código de Concepto Hipotecario Especial
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO ESPECIAL IPAS",$as_ipas_conhipespipas,"C");
		}
		if($lb_valido)
		{// Código de Concepto Hipotecario Ampliación
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO AMLIACION IPAS",$as_ipas_conhipampipas,"C");
		}
		if($lb_valido)
		{// Código de Concepto Hipotecario Construcción
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO CONSTRUCCION IPAS",$as_ipas_conhipconipas,"C");
		}
		if($lb_valido)
		{// Código de Concepto Hipotecario Hipoteca
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO HIPOTECA IPAS",$as_ipas_conhiphipipas,"C");
		}
		if($lb_valido)
		{// Código de Concepto Hipotecario LPH
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO LPH IPAS",$as_ipas_conhiplphipas,"C");
		}
		if($lb_valido)
		{// Código de Concepto Hipotecario Vivienda
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO VIVIENDA IPAS",$as_ipas_conhipvivipas,"C");
		}
		if($lb_valido)
		{// Código de Concepto Personal
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO PERSONAL IPAS",$as_ipas_conperipas,"C");
		}
		if($lb_valido)
		{// Código de Concepto Turísticos
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO TURISTICOS IPAS",$as_ipas_conturipas,"C");
		}
		if($lb_valido)
		{// Código de Concepto Proveeduria
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO PROVEEDURIA IPAS",$as_ipas_conproipas,"C");
		}
		if($lb_valido)
		{// Código de Concepto Asistenciales
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO ASISTENCIALES IPAS",$as_ipas_conasiipas,"C");
		}
		if($lb_valido)
		{// Código de Concepto Vehículos
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO VEHICULOS IPAS",$as_ipas_convehipas,"C");
		}
		if($lb_valido)
		{// Código de Concepto Comerciales
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD CONCEPTO COMERCIALES IPAS",$as_ipas_concomipas,"C");
		}
		//-----------------------------------------------------------------------------------------------------
		
		//-------------------------------------IVSS-----------------------------------------------------
		if($lb_valido)
		{// Código de Empresa 
			$lb_valido=$this->uf_insert_config("SNO","NOMINA","COD ORGANISMO IVSS",$as_ivss_numemp,"C");
		}
		if($lb_valido)
		{// Metodo sueldo 
			$lb_valido=$this->uf_insert_config("SNO","CONFIG","METODO IVSS",$as_ivss_metodo,"C");
		}
		//-----------------------------------------------------------------------------------------------------
		
		if($lb_valido)
		{ 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la configuración de las nómina.";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////				
		}
      	return ($lb_valido);  
    }// end function uf_guardar_configuracion	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reparar_subnominas($aa_seguridad)
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reparar_subnominas
		//		   Access: public (tepuy_snorh_p_configuracion.php)
		//	    Arguments: aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto correctamente la función y false si hubo error
		//	  Description: Función que le inserta subnóminas a todas aquellas nóminas que no tengan sub nóminas asociadas
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();		
		$ls_sql="INSERT INTO sno_subnomina (codemp, codnom, codsubnom, dessubnom) ".
				"     SELECT codemp, codnom, '0000000000' AS codsubnom, 'Sin Subnomina' AS dessubnom ".
				"       FROM sno_nomina ".
				"      WHERE codemp='".$this->ls_codemp."' ".
				"        AND codnom NOT IN (SELECT codnom FROM sno_subnomina WHERE (codemp='".$this->ls_codemp."'))";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_reparar_subnominas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Se realizó el proceso de reparar subnóminas.";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////				
			if($lb_valido)
			{	
				$this->io_mensajes->message("El proceso de reparar subnóminas se realizó correctamente.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_reparar_subnominas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
      	return ($lb_valido);  
    }// end function uf_reparar_subnominas	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reparar_conceptopersonal($aa_seguridad)
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reparar_conceptopersonal
		//		   Access: public (tepuy_snorh_p_configuracion.php)
		//	    Arguments: aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto correctamente la función y false si hubo error
		//	  Description: Función que le inserta los conceptos y constantes a personal que no se le haya asociado alguno
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();		
		$ls_sql="INSERT INTO sno_conceptopersonal (codemp, codnom, codper, codconc, aplcon, valcon, acuemp, acuiniemp, acupat, acuinipat) ".
				"     SELECT sno_personalnomina.codemp, concepto.codnom, sno_personalnomina.codper, concepto.codconc, ".
				"			 concepto.glocon, 0, 0, 0, 0, 0 ".
				"       FROM sno_personalnomina, sno_concepto concepto ".
				"      WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"        AND sno_personalnomina.codper NOT IN (SELECT sno_conceptopersonal.codper FROM sno_conceptopersonal ".
				"												 WHERE sno_conceptopersonal.codemp='".$this->ls_codemp."' ".
				"												   AND sno_conceptopersonal.codnom=concepto.codnom".
				"												   AND sno_conceptopersonal.codconc=concepto.codconc) ".
				"        AND sno_personalnomina.codemp=concepto.codemp ".
				"		 AND sno_personalnomina.codnom=concepto.codnom ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_reparar_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			$ls_sql="INSERT INTO sno_constantepersonal (codemp, codnom, codper, codcons, moncon) ".
					"     SELECT sno_personalnomina.codemp, constante.codnom, sno_personalnomina.codper, constante.codcons, constante.valcon ".
					"       FROM sno_personalnomina, sno_constante constante ".
					"      WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					"        AND sno_personalnomina.codper NOT IN (SELECT sno_constantepersonal.codper FROM sno_constantepersonal ".
					"												 WHERE sno_constantepersonal.codemp='".$this->ls_codemp."' ".
					"												   AND sno_constantepersonal.codnom=constante.codnom".
					"												   AND sno_constantepersonal.codcons=constante.codcons) ".
					"        AND sno_personalnomina.codemp=constante.codemp ".
					"		 AND sno_personalnomina.codnom=constante.codnom ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_reparar_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Se realizó el proceso de reparar concepto-personal.";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////				
				if($lb_valido)
				{	
					$this->io_mensajes->message("El proceso de reparar concepto-personal se realizó correctamente.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_reparar_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
			}
		}
      	return ($lb_valido);  
    }// end function uf_reparar_conceptopersonal	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_recalcular_sueldointegral($aa_seguridad)
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_recalcular_sueldointegral
		//		   Access: public (tepuy_snorh_p_configuracion.php)
		//	    Arguments: aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto correctamente la función y false si hubo error
		//	  Description: Función que le actualiza el sueldo integral del personal en las tablas de históricos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();		
		$ls_sql="UPDATE sno_hpersonalnomina ".
				"   SET sueintper=(SELECT COALESCE(SUM(sno_hsalida.valsal),0.00) ".
				"					 FROM sno_hsalida, sno_hconcepto,sno_hpersonalnomina ".
				"					WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"					  AND sno_hconcepto.sueintcon=1 ".
				"					  AND sno_hsalida.codemp=sno_hconcepto.codemp ".
				"					  AND sno_hsalida.codnom=sno_hconcepto.codnom ".
				"					  AND sno_hsalida.anocur=sno_hconcepto.anocur ".
				"					  AND sno_hsalida.codperi=sno_hconcepto.codperi ".
				"					  AND sno_hsalida.codconc=sno_hconcepto.codconc ".
				"					  AND sno_hsalida.codemp=sno_hpersonalnomina.codemp ".
				"					  AND sno_hsalida.codnom=sno_hpersonalnomina.codnom ".
				"					  AND sno_hsalida.anocur=sno_hpersonalnomina.anocur ".
				"					  AND sno_hsalida.codperi=sno_hpersonalnomina.codperi ".
				"					  AND sno_hsalida.codper=sno_hpersonalnomina.codper) ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_recalcular_sueldointegral ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Se realizó el proceso de recalcular sueldo integral.";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////				
			if($lb_valido)
			{	
				$this->io_mensajes->message("El proceso de recalcular sueldo integral se realizó correctamente.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_recalcular_sueldointegral ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
      	return ($lb_valido);  
    }// end function uf_recalcular_sueldointegral
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_mantenimiento_historicos($aa_seguridad)
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_mantenimiento_historicos
		//		   Access: public (tepuy_snorh_p_configuracion.php)
		//	    Arguments: aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto correctamente la función y false si hubo error
		//	  Description: Función que elimina de la tabla de resumen histórico los registros que no están en la tabla de salida
		//				   históricos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();		
		$ls_sql="DELETE ".
				"  FROM sno_hresumen ".
				" WHERE codnom NOT IN (SELECT codnom ".
				"					  	  FROM sno_hsalida ".
				"					  	 WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"						   AND sno_hsalida.codemp=sno_hresumen.codemp ".
				"					  	   AND sno_hsalida.codnom=sno_hresumen.codnom ".
				"						   AND sno_hsalida.anocur=sno_hresumen.anocur ".
				"					  	   AND sno_hsalida.codperi=sno_hresumen.codperi ".
				"					  	   AND sno_hsalida.codper=sno_hresumen.codper) ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_mantenimiento_historicos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Se realizó el proceso de mantenimiento históricos.";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////				
			if($lb_valido)
			{	
				$this->io_mensajes->message("El proceso de mantenimiento históricos se realizó correctamente.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_mantenimiento_historicos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
      	return ($lb_valido);  
    }// end function uf_mantenimiento_historicos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_mantenimiento_repararacumuladoconceptos($aa_seguridad)
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_mantenimiento_repararacumuladoconceptos
		//		   Access: public (tepuy_snorh_p_configuracion.php)
		//	    Arguments: aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto correctamente la función y false si hubo error
		//	  Description: Función que actualiza el valor acumulado de los conceptos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_periodo.codnom, sno_nomina.anocurnom, sno_periodo.codperi ".
				"  FROM sno_periodo, sno_nomina ".
				" WHERE sno_periodo.codemp='".$this->ls_codemp."' ".
				"	AND sno_periodo.cerper = 1 ".
				"   AND sno_periodo.codemp = sno_nomina.codemp ".
				"	AND sno_periodo.codnom = sno_nomina.codnom ".
				" ORDER BY sno_periodo.codnom, sno_nomina.anocurnom, sno_periodo.codperi ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_mantenimiento_repararacumuladoconceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$this->io_sql->begin_transaction();
			if($lb_valido)
			{
				$ls_sql="UPDATE sno_hconceptopersonal ".
						"   SET acupat =  0,  ".
						"  		acuemp =  0  ".
						" WHERE codemp='".$this->ls_codemp."'  ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_mantenimiento_repararacumuladoconceptos 2 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
			}
			if($lb_valido)
			{
				$ls_sql="UPDATE sno_conceptopersonal ".
						"   SET acupat =  0,  ".
						"  		acuemp =  0  ".
						" WHERE codemp='".$this->ls_codemp."'  ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_mantenimiento_repararacumuladoconceptos 2 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
			}
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codnom=$row["codnom"];
				$ls_anocurnom=$row["anocurnom"];
				$ls_codperi=$row["codperi"];
				$ls_sql="UPDATE sno_hconceptopersonal ".
						"   SET acuemp =  COALESCE((SELECT SUM(valsal) ".
						"		   FROM sno_hsalida ".
						"                  WHERE sno_hsalida.codemp=sno_hconceptopersonal.codemp ".
						"		    AND sno_hsalida.anocur=sno_hconceptopersonal.anocur ". 
						"		    AND sno_hsalida.codnom=sno_hconceptopersonal.codnom ". 
						"                    AND sno_hsalida.codconc=sno_hconceptopersonal.codconc  ".
						"                    AND sno_hsalida.codper=sno_hconceptopersonal.codper ".
						"                    AND sno_hsalida.codperi <=  sno_hconceptopersonal.codperi ".
						"		    AND tipsal='P1'),0)  ".
						" WHERE codemp='".$this->ls_codemp."'  ".
						"   AND anocur='".$ls_anocurnom."' ".
						"   AND codperi='".$ls_codperi."'".
						"   AND codnom IN (SELECT codnom  ".
						"		    		 FROM sno_hconcepto  ".
						"                   WHERE sno_hconcepto.codemp=sno_hconceptopersonal.codemp ".
						"                     AND sno_hconcepto.codnom=sno_hconceptopersonal.codnom ".
						"                     AND sno_hconcepto.anocur=sno_hconceptopersonal.anocur ".
						"                     AND sno_hconcepto.codperi=sno_hconceptopersonal.codperi ".
						"                     AND sno_hconcepto.codconc=sno_hconceptopersonal.codconc ".
						"                     AND sno_hconcepto.sigcon='P') ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_mantenimiento_repararacumuladoconceptos 1 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_hconceptopersonal ".
							"   SET acupat =  COALESCE((SELECT SUM(valsal) ".
							"		   FROM sno_hsalida ".
							"                  WHERE sno_hsalida.codemp=sno_hconceptopersonal.codemp ".
							"		    AND sno_hsalida.anocur=sno_hconceptopersonal.anocur ". 
							"		    AND sno_hsalida.codnom=sno_hconceptopersonal.codnom ". 
							"                    AND sno_hsalida.codconc=sno_hconceptopersonal.codconc  ".
							"                    AND sno_hsalida.codper=sno_hconceptopersonal.codper ".
							"                    AND sno_hsalida.codperi <=  sno_hconceptopersonal.codperi ".
							"		    AND tipsal='P2'),0)  ".
							" WHERE codemp='".$this->ls_codemp."'  ".
							"   AND anocur='".$ls_anocurnom."' ".
							"   AND codperi='".$ls_codperi."'".
							"   AND codnom IN (SELECT codnom  ".
							"		    		 FROM sno_hconcepto  ".
							"                   WHERE sno_hconcepto.codemp=sno_hconceptopersonal.codemp ".
							"                     AND sno_hconcepto.codnom=sno_hconceptopersonal.codnom ".
							"                     AND sno_hconcepto.anocur=sno_hconceptopersonal.anocur ".
							"                     AND sno_hconcepto.codperi=sno_hconceptopersonal.codperi ".
							"                     AND sno_hconcepto.codconc=sno_hconceptopersonal.codconc ".
							"                     AND sno_hconcepto.sigcon='P') ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_mantenimiento_repararacumuladoconceptos 2 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					}
				}
				if($lb_valido)
				{
					$ls_sql="UPDATE sno_hconceptopersonal ".
							"   SET acuemp =  COALESCE((SELECT SUM(valsal) ".
							"		   FROM sno_hsalida ".
							"                  WHERE sno_hsalida.codemp=sno_hconceptopersonal.codemp ".
							"		    AND sno_hsalida.anocur=sno_hconceptopersonal.anocur ". 
							"		    AND sno_hsalida.codnom=sno_hconceptopersonal.codnom ". 
							"                    AND sno_hsalida.codconc=sno_hconceptopersonal.codconc  ".
							"                    AND sno_hsalida.codper=sno_hconceptopersonal.codper ".
							"                    AND sno_hsalida.codperi <=  sno_hconceptopersonal.codperi),0)  ".
							" WHERE codemp='".$this->ls_codemp."'  ".
							"   AND anocur='".$ls_anocurnom."' ".
							"   AND codperi='".$ls_codperi."'".
							"   AND codnom IN (SELECT codnom  ".
							"		    		 FROM sno_hconcepto  ".
							"                   WHERE sno_hconcepto.codemp=sno_hconceptopersonal.codemp ".
							"                     AND sno_hconcepto.codnom=sno_hconceptopersonal.codnom ".
							"                     AND sno_hconcepto.anocur=sno_hconceptopersonal.anocur ".
							"                     AND sno_hconcepto.codperi=sno_hconceptopersonal.codperi ".
							"                     AND sno_hconcepto.codconc=sno_hconceptopersonal.codconc ".
							"                     AND sno_hconcepto.sigcon<>'P') ";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_mantenimiento_repararacumuladoconceptos 3 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					}
				}
			}
			if($lb_valido)
			{
				$ls_sql="UPDATE sno_conceptopersonal ".
						"   SET acuemp =  COALESCE((SELECT SUM(valsal) ".
						"		   					  FROM sno_hsalida ".
						"                  			 WHERE sno_hsalida.codemp=sno_conceptopersonal.codemp ".
						"		    				   AND sno_hsalida.codnom=sno_conceptopersonal.codnom ". 
						"                              AND sno_hsalida.codconc=sno_conceptopersonal.codconc  ".
						"                              AND sno_hsalida.codper=sno_conceptopersonal.codper ".
						"		                       AND sno_hsalida.tipsal='P1'),0)  ".
						" WHERE codemp='".$this->ls_codemp."'  ".
						"   AND codnom IN (SELECT codnom  ".
						"		    		 FROM sno_concepto  ".
						"                   WHERE sno_concepto.codemp=sno_conceptopersonal.codemp ".
						"                     AND sno_concepto.codnom=sno_conceptopersonal.codnom ".
						"                     AND sno_concepto.codconc=sno_conceptopersonal.codconc ".
						"                     AND sno_concepto.sigcon='P') ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_mantenimiento_repararacumuladoconceptos 4 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
			}
			if($lb_valido)
			{
				$ls_sql="UPDATE sno_conceptopersonal ".
						"   SET acupat =  COALESCE((SELECT SUM(valsal) ".
						"		   					  FROM sno_hsalida ".
						"                  			 WHERE sno_hsalida.codemp=sno_conceptopersonal.codemp ".
						"		    				   AND sno_hsalida.codnom=sno_conceptopersonal.codnom ". 
						"                              AND sno_hsalida.codconc=sno_conceptopersonal.codconc  ".
						"                              AND sno_hsalida.codper=sno_conceptopersonal.codper ".
						"		                       AND sno_hsalida.tipsal='P2'),0)  ".
						" WHERE codemp='".$this->ls_codemp."'  ".
						"   AND codnom IN (SELECT codnom  ".
						"		    		 FROM sno_concepto  ".
						"                   WHERE sno_concepto.codemp=sno_conceptopersonal.codemp ".
						"                     AND sno_concepto.codnom=sno_conceptopersonal.codnom ".
						"                     AND sno_concepto.codconc=sno_conceptopersonal.codconc ".
						"                     AND sno_concepto.sigcon='P') ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_mantenimiento_repararacumuladoconceptos 5 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
			}
			if($lb_valido)
			{
				$ls_sql="UPDATE sno_conceptopersonal ".
						"   SET acuemp =  COALESCE((SELECT SUM(valsal) ".
						"		   					  FROM sno_hsalida ".
						"                  			 WHERE sno_hsalida.codemp=sno_conceptopersonal.codemp ".
						"		    				   AND sno_hsalida.codnom=sno_conceptopersonal.codnom ". 
						"                              AND sno_hsalida.codconc=sno_conceptopersonal.codconc  ".
						"                              AND sno_hsalida.codper=sno_conceptopersonal.codper),0)  ".
						" WHERE codemp='".$this->ls_codemp."'  ".
						"   AND codnom IN (SELECT codnom  ".
						"		    		 FROM sno_concepto  ".
						"                   WHERE sno_concepto.codemp=sno_conceptopersonal.codemp ".
						"                     AND sno_concepto.codnom=sno_conceptopersonal.codnom ".
						"                     AND sno_concepto.codconc=sno_conceptopersonal.codconc ".
						"                     AND sno_concepto.sigcon<>'P') ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_mantenimiento_repararacumuladoconceptos 6 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
			}
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Se realizó el proceso de Reparar Acumulado de Conceptos.";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////				
			if($lb_valido)
			{	
				$this->io_mensajes->message("El proceso de Reparar Acumulado de Conceptos se realizó correctamente.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_mantenimiento_repararacumuladoconceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
      	return ($lb_valido);  
    }// end function uf_mantenimiento_repararacumuladoconceptos
	//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
   function uf_numero_IVSS()
   { 		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_numero_IVSS
		//		   Access: public (tepuy_snorh_p_configuracion.php)
		//	    Arguments:
		//	      Returns: lb_valido True si se ejecuto correctamente la función y false si hubo error
		//	  Description: Función que busca el numero de seguro social en tepuy_empresa
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 26/08/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		$ls_sql="SELECT nroivss FROM tepuy_empresa WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_numero_IVSS ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_nroivss=$row["nroivss"];
			}		
		}
		if ($as_nroivss=="")
		{
			$as_nroivss="XXXXXXXXX";
		}		
		return $as_nroivss;
   }//fin de uf_numero_IVSS()
 //-------------------------------------------------------------------------------------------------------------------------------------
}
?>