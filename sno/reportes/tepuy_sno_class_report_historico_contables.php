<?php
class tepuy_sno_class_report_historico_contables
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_sno_class_report_historico_contables()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_sno_class_report_historico_contables
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 22/05/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$this->DS=new class_datastore();
		$this->DS_detalle=new class_datastore();
		$this->DS_detalle_2=new class_datastore();
		require_once("../../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
        $this->ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$this->ls_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
		$this->li_rac=$_SESSION["la_nomina"]["racnom"];
	}// end function tepuy_sno_class_report_historico_contables
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
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
		//				   as_variable  // Variable a buscar
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
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
				$this->io_mensajes->message("CLASE->Report MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	function uf_contableaportes_presupuesto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableaportes_presupuesto
		//         Access: public (desde la clase tepuy_sno_r_contableaportes)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 11/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT sno_thconcepto.codpro as programatica, spg_cuentas.spg_cuenta AS cueprepatcon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
				"   AND sno_thconcepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon ".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thconcepto.codpro, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thunidadadmin.codprouniadm as programatica, spg_cuentas.spg_cuenta AS cueprepatcon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon ".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thunidadadmin.codprouniadm, spg_cuentas.spg_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_presupuesto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableaportes_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_contable()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableaportes_contable
		//         Access: public (desde la clase tepuy_sno_r_contableaportes)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas contables que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 11/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);	
		$li_parametros=trim($this->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I"));
		switch($li_parametros)
		{
			case 0: // La contabilización es global
				$ls_modoaporte=$this->uf_select_config("SNO","NOMINA","CONTABILIZACION APORTES","OCP","C");
				$li_genrecapo=str_pad($this->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO APORTE","0","I"),1,"0");
				break;
			
			case 1: // La contabilización es por nómina
				$ls_modoaporte=trim($_SESSION["la_nomina"]["conaponom"]);
				$li_genrecapo=str_pad(trim($_SESSION["la_nomina"]["recdocapo"]),1,"0");
				break;
		}
		$ls_group=" GROUP BY spg_cuentas.sc_cuenta ";
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos que se 
		// integran directamente con presupuesto estas van por el debe de contabilidad
		$ls_sql="SELECT spg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'D' as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
				"   AND sno_thconcepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				$ls_group;
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos que NO se 
		// integran directamente con presupuesto entonces las buscamos según la estructura de la unidad administrativa a 
		// la que pertenece el personal, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT spg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'D' as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				$ls_group;
		if(($ls_modoaporte=="OC")&&($li_genrecapo=="1"))
		{
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'H' as operacion, sum(sno_thsalida.valsal) as total ".
					"  FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, scg_cuentas, rpc_proveedor ".
					" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
					"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
					"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
					"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_thsalida.valsal <> 0 ".
					"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C' ".
					"   AND sno_thconcepto.codprov <> '----------' ".
					"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
					"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
					"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
					"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
					"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
					"	AND sno_thconcepto.codemp = rpc_proveedor.codemp ".
					"	AND sno_thconcepto.codprov = rpc_proveedor.cod_pro ".
					"   AND scg_cuentas.codemp = rpc_proveedor.codemp ".
					"   AND scg_cuentas.sc_cuenta = rpc_proveedor.sc_cuenta ".
					" GROUP BY scg_cuentas.sc_cuenta ";
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'H' as operacion, sum(sno_thsalida.valsal) as total ".
					"  FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, scg_cuentas, rpc_beneficiario ".
					" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
					"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
					"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
					"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_thsalida.valsal <> 0 ".
					"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C' ".
					"   AND sno_thconcepto.cedben <> '----------' ".
					"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
					"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
					"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
					"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
					"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
					"	AND sno_thconcepto.codemp = rpc_beneficiario.codemp ".
					"	AND sno_thconcepto.cedben = rpc_beneficiario.ced_bene ".
					"   AND scg_cuentas.codemp = rpc_beneficiario.codemp ".
					"   AND scg_cuentas.sc_cuenta = rpc_beneficiario.sc_cuenta ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		}
		else
		{
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'H' as operacion, sum(sno_thsalida.valsal) as total ".
					"  FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, scg_cuentas ".
					" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
					"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
					"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
					"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
					"   AND scg_cuentas.status = 'C'".
					"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
					"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
					"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
					"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
					"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
					"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
					"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					"   AND scg_cuentas.codemp = sno_thconcepto.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_thconcepto.cueconpatcon ".
					" GROUP BY scg_cuentas.sc_cuenta ".
					" ORDER BY operacion, cuenta ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_contable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS_detalle->group_by(array('0'=>'cuenta','1'=>'operacion'),array('0'=>'total'),'total');
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableaportes_contable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_presupuesto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableconceptos_presupuesto
		//         Access: public (desde la clase tepuy_sno_r_contableconceptos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo A, D, P1
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 22/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql="SELECT sno_thconcepto.codpro as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thconcepto.codpro, sno_thconcepto.cueprecon ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thunidadadmin.codprouniadm as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thunidadadmin.codprouniadm, sno_thconcepto.cueprecon ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thconcepto.codpro as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thconcepto.sigcon = 'E'".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thconcepto.codpro, sno_thconcepto.cueprecon ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thunidadadmin.codprouniadm as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thconcepto.sigcon = 'E'".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thunidadadmin.codprouniadm, sno_thconcepto.cueprecon ".
				" ORDER BY programatica, cueprecon";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_presupuesto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableconceptos_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_contable()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contableconceptos_contable 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 08/11/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$ls_group="  GROUP BY scg_cuentas.sc_cuenta ";
		$li_parametros=$this->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I");
		switch($li_parametros)
		{
			case 0: // La contabilización es global
				$ls_cuentapasivo=trim($this->uf_select_config("SNO","CONFIG","CTA.CONTA","-------------------------","C"));
				$ls_modo=trim($this->uf_select_config("SNO","NOMINA","CONTABILIZACION","OCP","C"));
				$li_genrecdoc=str_pad($this->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO","0","I"),1,"0");
				break;
				
			case 1: // La contabilización es por nómina
				$ls_cuentapasivo=trim($_SESSION["la_nomina"]["cueconnom"]);
				$ls_modo=trim($_SESSION["la_nomina"]["consulnom"]);
				$li_genrecdoc=str_pad(trim($_SESSION["la_nomina"]["recdocnom"]),1,"0");
				break;
		}
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		$ls_sql="SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, CAST('D' AS char(1)) as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND sno_thconcepto.intprocon = '1' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				$ls_group;
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D que NO se 
		// integran directamente con presupuesto entonces las buscamos según la estructura de la unidad administrativa a 
		// la que pertenece el personal, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, CAST('D' AS char(1)) as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				$ls_group;
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, CAST('H' AS char(1)) as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND sno_thconcepto.sigcon = 'E' ".
				"   AND sno_thconcepto.intprocon = '1' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				$ls_group;
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D que NO se 
		// integran directamente con presupuesto entonces las buscamos según la estructura de la unidad administrativa a 
		// la que pertenece el personal, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, CAST('H' AS char(1)) as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND sno_thconcepto.sigcon = 'E' ".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				$ls_group;
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, CAST('D' AS char(1)) as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND sno_thconcepto.sigcon = 'B' ".
				"   AND scg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND scg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND scg_cuentas.sc_cuenta = sno_thconcepto.cueconcon ".
				$ls_group;
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, CAST('H' AS char(1)) as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3' )".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND scg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND scg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND scg_cuentas.sc_cuenta = sno_thconcepto.cueconcon ".
				$ls_group;
		if($ls_modo=="OC") // Si el modo de contabilizar la nómina es Compromete y Causa tomamos la cuenta pasivo de la nómina.
		{
			if($li_genrecdoc=="0") // No se genera Recepción de Documentos
			{
				// Buscamos todas aquellas cuentas contables de los conceptos A y D, estas van por el haber de contabilidad
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQL":
						$ls_cadena="CONVERT('".$ls_cuentapasivo."' USING utf8) as cuenta";
						break;
					case "POSTGRE":
						$ls_cadena="CAST('".$ls_cuentapasivo."' AS char(25)) as cuenta";
						break;					
				}
				$ls_sql=$ls_sql." UNION ".
						"SELECT ".$ls_cadena.", MAX(scg_cuentas.denominacion) AS denominacion,  CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
						"  FROM sno_thpersonalnomina, sno_thsalida, sno_banco, scg_cuentas ".
						" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
						"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
						"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3' )".
						"   AND sno_thsalida.valsal <> 0 ".
						"   AND (sno_thpersonalnomina.pagbanper = 1 OR sno_thpersonalnomina.pagtaqper = 1) ".
						"   AND sno_thpersonalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND scg_cuentas.sc_cuenta = '".$ls_cuentapasivo."' ".
						"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
						"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
						"   AND sno_thsalida.codemp = sno_banco.codemp ".
						"   AND sno_thsalida.codnom = sno_banco.codnom ".
						"   AND sno_thsalida.codperi = sno_banco.codperi ".
						"   AND sno_thpersonalnomina.codemp = sno_banco.codemp ".
						"   AND sno_thpersonalnomina.codban = sno_banco.codban ".
						"   AND scg_cuentas.codemp = sno_banco.codemp ".
						" GROUP BY scg_cuentas.sc_cuenta ";
			}
			else // Se genera Recepción de documentos
			{
				$ls_sql=$ls_sql." UNION ".
						"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion,  CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
						"  FROM sno_thpersonalnomina, sno_thsalida, scg_cuentas, sno_thnomina, rpc_proveedor ".
						" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
						"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
						"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3' )".
						"   AND sno_thsalida.valsal <> 0 ".
						"   AND (sno_thpersonalnomina.pagbanper = 1 OR sno_thpersonalnomina.pagtaqper = 1) ".
						"   AND sno_thpersonalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND sno_thnomina.descomnom = 'P'".
						"   AND sno_thnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thnomina.anocurnom = sno_thsalida.anocur ".
						"   AND sno_thnomina.peractnom = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
						"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
						"   AND sno_thnomina.codemp = rpc_proveedor.codemp ".
						"   AND sno_thnomina.codpronom = rpc_proveedor.cod_pro ".
						"   AND rpc_proveedor.codemp = scg_cuentas.codemp ".
						"   AND rpc_proveedor.sc_cuenta = scg_cuentas.sc_cuenta ".
						" GROUP BY scg_cuentas.sc_cuenta ";
				$ls_sql=$ls_sql." UNION ".
						"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion,  CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
						"  FROM sno_thpersonalnomina, sno_thsalida, scg_cuentas, sno_thnomina, rpc_beneficiario ".
						" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
						"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
						"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3' )".
						"   AND sno_thsalida.valsal <> 0 ".
						"   AND (sno_thpersonalnomina.pagbanper = 1 OR sno_thpersonalnomina.pagtaqper = 1) ".
						"   AND sno_thpersonalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND sno_thnomina.descomnom = 'B'".
						"   AND sno_thnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thnomina.anocurnom = sno_thsalida.anocur ".
						"   AND sno_thnomina.peractnom = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
						"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
						"   AND sno_thnomina.codemp = rpc_beneficiario.codemp ".
						"   AND sno_thnomina.codbennom = rpc_beneficiario.ced_bene ".
						"   AND rpc_beneficiario.codemp = scg_cuentas.codemp ".
						"   AND rpc_beneficiario.sc_cuenta = scg_cuentas.sc_cuenta ".
						" GROUP BY scg_cuentas.sc_cuenta ";
			}
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion,  CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
					"  FROM sno_thpersonalnomina, sno_thsalida, scg_cuentas ".
					" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_thsalida.anocur = '".$this->ls_anocurnom."' ".
					"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
					"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3')".
					"   AND sno_thsalida.valsal <> 0".
					"   AND sno_thpersonalnomina.pagbanper = 0 ".
					"   AND sno_thpersonalnomina.pagtaqper = 0 ".
					"   AND sno_thpersonalnomina.pagefeper = 1 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					"   AND scg_cuentas.codemp = sno_thpersonalnomina.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_thpersonalnomina.cueaboper ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		}
		else
		{
			// Buscamos todas aquellas cuentas contables de los conceptos A y D, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion,  CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
					"  FROM sno_thpersonalnomina, sno_thsalida, sno_banco, scg_cuentas ".
					" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_thsalida.anocur = '".$this->ls_anocurnom."' ".
					"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
					"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3')".
					"   AND sno_thsalida.valsal <> 0".
					"   AND (sno_thpersonalnomina.pagbanper = 1  OR sno_thpersonalnomina.pagtaqper = 1) ".
					"   AND sno_thpersonalnomina.pagefeper = 0 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					"   AND sno_thsalida.codemp = sno_banco.codemp ".
					"   AND sno_thsalida.codnom = sno_banco.codnom ".
					"   AND sno_thsalida.codperi = sno_banco.codperi ".
					"   AND sno_thpersonalnomina.codemp = sno_banco.codemp ".
					"   AND sno_thpersonalnomina.codban = sno_banco.codban ".
					"   AND scg_cuentas.codemp = sno_banco.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_banco.codcuecon ".
					" GROUP BY scg_cuentas.sc_cuenta ";
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
					"  FROM sno_thpersonalnomina, sno_thsalida, scg_cuentas ".
					" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_thsalida.anocur = '".$this->ls_anocurnom."' ".
					"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
					"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3')".
					"   AND sno_thsalida.valsal <> 0".
					"   AND sno_thpersonalnomina.pagbanper = 0 ".
					"   AND sno_thpersonalnomina.pagtaqper = 0 ".
					"   AND sno_thpersonalnomina.pagefeper = 1 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					"   AND scg_cuentas.codemp = sno_thpersonalnomina.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_thpersonalnomina.cueaboper ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_contable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS_detalle->group_by(array('0'=>'cuenta','1'=>'operacion'),array('0'=>'total'),'total');
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_contableconceptos_contable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_presupuesto_enmohca()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableconceptos_presupuesto
		//         Access: public (desde la clase tepuy_sno_r_contableconceptos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo A, D, P1
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 22/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql="SELECT sno_thconcepto.codpro as programatica, sno_thconcepto.cueprecon, spg_cuentas.denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thconcepto.codpro, sno_thconcepto.cueprecon, spg_cuentas.denominacion ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thunidadadmin.codprouniadm as programatica, sno_thconcepto.cueprecon, spg_cuentas.denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thunidadadmin.codprouniadm, sno_thconcepto.cueprecon, spg_cuentas.denominacion ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thconcepto.codpro as programatica, sno_thconcepto.cueprecon, spg_cuentas.denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thconcepto.sigcon = 'E'".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thconcepto.codpro, sno_thconcepto.cueprecon, spg_cuentas.denominacion ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thunidadadmin.codprouniadm as programatica, sno_thconcepto.cueprecon, spg_cuentas.denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thconcepto.sigcon = 'E'".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thunidadadmin.codprouniadm, sno_thconcepto.cueprecon, spg_cuentas.denominacion ".
				" ORDER BY programatica, cueprecon";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_presupuesto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS->group_by(array('0'=>'programatica','1'=>'cueprecon'),array('0'=>'total'),'total');
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableconceptos_presupuesto_enmohca
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_presupuesto_proyecto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableaportes_presupuesto_proyecto
		//         Access: public (desde la clase tepuy_sno_r_contableaportes)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT sno_thconcepto.codpro as programatica, spg_cuentas.spg_cuenta AS cueprepatcon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
				"   AND sno_thconcepto.intprocon = '1'".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon ".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thconcepto.codpro, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thunidadadmin.codprouniadm as programatica, spg_cuentas.spg_cuenta AS cueprepatcon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon ".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thunidadadmin.codprouniadm, spg_cuentas.spg_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_presupuesto_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_contableaportes_presupuesto_proyecto_dt();
			$this->DS->group_by(array('0'=>'programatica','1'=>'cueprepatcon'),array('0'=>'total'),'total');		
			$this->DS->sortData('programatica');
		}
		return $lb_valido;
	}// end function uf_contableaportes_presupuesto_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_presupuesto_proyecto_dt()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableaportes_presupuesto_proyecto_dt
		//         Access: public (desde la clase tepuy_sno_r_contableaportes)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena=" ROUND((SUM(sno_thsalida.valsal)*MAX(sno_thproyectopersonal.pordiames)),3) ";
				break;
			case "POSTGRE":
				$ls_cadena=" ROUND(CAST((sum(sno_thsalida.valsal)*MAX(sno_thproyectopersonal.pordiames)) AS NUMERIC),3) ";
				break;					
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT MAX(sno_thproyecto.estproproy) AS estproproy, MAX(spg_cuentas.spg_cuenta) AS spg_cuenta, ".
				"		sum(sno_thsalida.valsal) AS total, MAX(sno_thconcepto.codprov) AS codprov, ".$ls_cadena." AS montoparcial, ".
				"		MAX(sno_thconcepto.cedben) AS cedben, sno_thconcepto.codconc, sno_thproyecto.codproy, sno_thproyectopersonal.codper, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, MAX(sno_thproyectopersonal.pordiames) as pordiames ".
				"  FROM sno_thproyectopersonal, sno_thproyecto, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
				"   AND sno_thconcepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND sno_thproyectopersonal.codemp = sno_thsalida.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thsalida.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thsalida.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thsalida.codperi ".
				"   AND sno_thproyectopersonal.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thproyecto.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi ".
				"   AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon ".
				"   AND substr(sno_thproyecto.estproproy,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thproyecto.estproproy,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thproyecto.estproproy,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thproyecto.estproproy,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thproyecto.estproproy,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thproyectopersonal.codper, sno_thproyecto.codproy, spg_cuentas.spg_cuenta, sno_thconcepto.codconc ".
				" ORDER BY sno_thproyectopersonal.codper, sno_thproyecto.codproy, spg_cuentas.spg_cuenta, sno_thconcepto.codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_presupuesto_proyecto_dt ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_programaticaant="";
			$ls_cuentaant="";
			$ls_denominacionant="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$li_montoparcial=$row["montoparcial"];
				$li_total=$row["total"];
				$ls_estproproy=$row["estproproy"];
				$ls_spgcuenta=$row["spg_cuenta"];
				$ls_denominacion=$row["denominacion"];
				$li_pordiames=$row["pordiames"];
				if(($ls_codper!=$ls_codant)||($ls_spgcuenta!=$ls_cuentaant))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,3)!=round($li_totalant,3))&&($li_pordiames<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),3);
							$this->DS->insertRow("programatica",$ls_programaticaant);
							$this->DS->insertRow("cueprepatcon",$ls_cuentaant);
							$this->DS->insertRow("total",$li_montoparcial);
							$this->DS->insertRow("denominacion",$ls_denominacionant);
						}
					}
					$li_acumulado=$row["montoparcial"];
					$li_montoparcial=round($row["montoparcial"],3);
					$ls_programaticaant=$ls_estproproy;
					$ls_cuentaant=$ls_spgcuenta;
					$ls_codant=$ls_codper;
					$ls_denominacionant=$ls_denominacion;
					$li_pordiamesant=$li_pordiames;
					$li_totalant=$li_total;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_programaticaant=$ls_estproproy;
					$ls_cuentaant=$ls_spgcuenta;
					$li_totalant=$li_total;
					$ls_denominacionant=$ls_denominacion;
				}
				$this->DS->insertRow("programatica",$ls_estproproy);
				$this->DS->insertRow("cueprepatcon",$ls_spgcuenta);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacion);
			}
			if((number_format($li_acumulado,3,".","")!=number_format($li_totalant,3,".",""))&&($li_pordiames<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),3);
				$this->DS->insertRow("programatica",$ls_programaticaant);
				$this->DS->insertRow("cueprepatcon",$ls_cuentaant);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacionant);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableaportes_presupuesto_proyecto_dt
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_contable_proyecto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableaportes_contable_proyecto
		//         Access: public (desde la clase tepuy_sno_r_contableaportes)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas contables que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);	
		$li_parametros=trim($this->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I"));
		switch($li_parametros)
		{
			case 0: // La contabilización es global
				$ls_modoaporte=$this->uf_select_config("SNO","NOMINA","CONTABILIZACION APORTES","OCP","C");
				$li_genrecapo=str_pad($this->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO APORTE","0","I"),1,"0");
				break;
			
			case 1: // La contabilización es por nómina
				$ls_modoaporte=trim($_SESSION["la_nomina"]["conaponom"]);
				$li_genrecapo=str_pad(trim($_SESSION["la_nomina"]["recdocapo"]),1,"0");
				break;
		}
		$ls_group=" GROUP BY spg_cuentas.sc_cuenta ";
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos que se 
		// integran directamente con presupuesto estas van por el debe de contabilidad
		$ls_sql="SELECT spg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'D' as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
				"   AND sno_thconcepto.intprocon = '1' ".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				$ls_group;
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos que NO se 
		// integran directamente con presupuesto entonces las buscamos según la estructura de la unidad administrativa a 
		// la que pertenece el personal, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT spg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'D' as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				$ls_group;
		if(($ls_modoaporte=="OC")&&($li_genrecapo=="1"))
		{
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'H' as operacion, sum(sno_thsalida.valsal) as total ".
					"  FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, scg_cuentas, rpc_proveedor ".
					" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
					"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
					"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
					"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_thsalida.valsal <> 0 ".
					"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C' ".
					"   AND sno_thconcepto.codprov <> '----------' ".
					"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
					"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
					"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
					"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
					"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
					"	AND sno_thconcepto.codemp = rpc_proveedor.codemp ".
					"	AND sno_thconcepto.codprov = rpc_proveedor.cod_pro ".
					"   AND scg_cuentas.codemp = rpc_proveedor.codemp ".
					"   AND scg_cuentas.sc_cuenta = rpc_proveedor.sc_cuenta ".
					" GROUP BY scg_cuentas.sc_cuenta ";
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'H' as operacion, sum(sno_thsalida.valsal) as total ".
					"  FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, scg_cuentas, rpc_beneficiario ".
					" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
					"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
					"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
					"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_thsalida.valsal <> 0 ".
					"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C' ".
					"   AND sno_thconcepto.cedben <> '----------' ".
					"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
					"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
					"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
					"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
					"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
					"	AND sno_thconcepto.codemp = rpc_beneficiario.codemp ".
					"	AND sno_thconcepto.cedben = rpc_beneficiario.ced_bene ".
					"   AND scg_cuentas.codemp = rpc_beneficiario.codemp ".
					"   AND scg_cuentas.sc_cuenta = rpc_beneficiario.sc_cuenta ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		}
		else
		{
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'H' as operacion, sum(sno_thsalida.valsal) as total ".
					"  FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, scg_cuentas ".
					" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
					"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
					"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
					"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
					"   AND scg_cuentas.status = 'C'".
					"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
					"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
					"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
					"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
					"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
					"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
					"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					"   AND scg_cuentas.codemp = sno_thconcepto.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_thconcepto.cueconpatcon ".
					" GROUP BY scg_cuentas.sc_cuenta ".
					" ORDER BY operacion, cuenta ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_contable_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_contableaportes_contable_proyecto_dt();
			$this->DS_detalle->group_by(array('0'=>'cuenta','1'=>'operacion'),array('0'=>'total'),'total');		
			$this->DS_detalle->sortData('operacion');
		}
		return $lb_valido;
	}// end function uf_contableaportes_contable_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_contable_proyecto_dt()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableaportes_contable_proyecto_dt
		//         Access: public (desde la clase tepuy_sno_r_contableaportes)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas contables que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);	
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena=" ROUND((SUM(abs(sno_thsalida.valsal))*MAX(sno_thproyectopersonal.pordiames)),3) ";
				break;
			case "POSTGRE":
				$ls_cadena=" ROUND(CAST((sum(abs(sno_thsalida.valsal))*MAX(sno_thproyectopersonal.pordiames)) AS NUMERIC),3) ";
				break;					
		}
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos que se 
		// integran directamente con presupuesto estas van por el debe de contabilidad
		$ls_sql="SELECT scg_cuentas.sc_cuenta, CAST('D' AS char(1)) as operacion, sum(abs(sno_thsalida.valsal)) as total, ".
				"		".$ls_cadena." AS montoparcial, MAX(sno_thconcepto.codprov) as codprov, MAX(sno_thconcepto.cedben) as cedben, ".
				"		sno_thconcepto.codconc, sno_thproyectopersonal.codper, sno_thproyecto.codproy, MAX(scg_cuentas.denominacion) as denoconta, ".
				"		MAX(sno_thproyectopersonal.pordiames) as pordiames ".
				"  FROM sno_thproyectopersonal, sno_thproyecto, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
				"   AND sno_thconcepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_thproyectopersonal.codemp = sno_thsalida.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thsalida.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thsalida.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thsalida.codperi ".
				"   AND sno_thproyectopersonal.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thproyecto.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi ".
				"   AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_thproyecto.estproproy,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thproyecto.estproproy,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thproyecto.estproproy,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thproyecto.estproproy,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thproyecto.estproproy,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thproyectopersonal.codper, sno_thconcepto.codconc, sno_thproyecto.codproy, scg_cuentas.sc_cuenta ".
				" ORDER BY codper, codconc, codproy, sc_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_contable_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_cuentaant="";
			$ls_operacionant="";
			$ls_denominacionant="";
			$ls_codconcant="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_codconc=$row["codconc"];
				$li_montoparcial=$row["montoparcial"];
				$li_total=$row["total"];
				$ls_cuenta=$row["sc_cuenta"];
				$ls_operacion=$row["operacion"];
				$ls_denominacion=$row["denoconta"];
				$li_pordiames=$row["pordiames"];
				if(($ls_codper!=$ls_codant)||($ls_codconc!=$ls_codconcant))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,3)!=round($li_totalant,3))&&($li_pordiamesant<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),3);
							$this->DS_detalle->insertRow("operacion",$ls_operacionant);
							$this->DS_detalle->insertRow("cuenta",$ls_cuentaant);
							$this->DS_detalle->insertRow("total",$li_montoparcial);
							$this->DS_detalle->insertRow("denoconta",$ls_denominacionant);
						}
					}
					$li_acumulado=$row["montoparcial"];
					$li_montoparcial=round($row["montoparcial"],3);
					$ls_operacionant=$ls_operacion;
					$ls_cuentaant=$ls_cuenta;
					$ls_codconcant=$ls_codconc;
					$ls_codant=$ls_codper;
					$ls_denominacionant=$ls_denominacion;
					$li_pordiamesant=$li_pordiames;
					$li_totalant=$li_total;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_operacionant=$ls_operacion;
					$ls_cuentaant=$ls_cuenta;
					$ls_codconcant=$ls_codconc;
					$li_totalant=$li_total;
					$ls_denominacionant=$ls_denominacion;
				}
				$this->DS_detalle->insertRow("operacion",$ls_operacion);
				$this->DS_detalle->insertRow("cuenta",$ls_cuenta);
				$this->DS_detalle->insertRow("total",$li_montoparcial);
				$this->DS_detalle->insertRow("denoconta",$ls_denominacion);
			}
			if((number_format($li_acumulado,3,".","")!=number_format($li_totalant,3,".",""))&&($li_pordiamesant<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),3);
				$this->DS_detalle->insertRow("operacion",$ls_operacionant);
				$this->DS_detalle->insertRow("cuenta",$ls_cuentaant);
				$this->DS_detalle->insertRow("total",$li_montoparcial);
				$this->DS_detalle->insertRow("denoconta",$ls_denominacionant);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableaportes_contable_proyecto_dt
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_presupuesto_proyecto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableconceptos_presupuesto_proyecto
		//         Access: public (desde la clase tepuy_sno_r_contableconceptos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo A, D, P1
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql="SELECT sno_thconcepto.codpro as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '1' ".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thconcepto.codpro, sno_thconcepto.cueprecon ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thunidadadmin.codprouniadm as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thunidadadmin.codprouniadm, sno_thconcepto.cueprecon ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thconcepto.codpro as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thconcepto.sigcon = 'E'".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '1'".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thconcepto.codpro, sno_thconcepto.cueprecon ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thunidadadmin.codprouniadm as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thconcepto.sigcon = 'E'".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thunidadadmin.codprouniadm, sno_thconcepto.cueprecon ".
				" ORDER BY programatica, cueprecon";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_presupuesto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_contableconceptos_presupuesto_proyecto_dt();
			$this->DS->group_by(array('0'=>'programatica','1'=>'cueprecon'),array('0'=>'total'),'total');		
			$this->DS->sortData('programatica');
		}
		return $lb_valido;
	}// end function uf_contableconceptos_presupuesto_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_presupuesto_proyecto_dt()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableconceptos_presupuesto_proyecto_dt
		//         Access: public (desde la clase tepuy_sno_r_contableconceptos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo A, D, P1
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena=" ROUND((SUM(sno_thsalida.valsal)*MAX(sno_thproyectopersonal.pordiames)),3) ";
				break;
			case "POSTGRE":
				$ls_cadena=" ROUND(CAST((sum(sno_thsalida.valsal)*MAX(sno_thproyectopersonal.pordiames)) AS NUMERIC),3) ";
				break;					
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql="SELECT sno_thproyectopersonal.codper, sno_thproyectopersonal.codproy, MAX(sno_thproyecto.estproproy) AS estproproy, spg_cuentas.spg_cuenta,".
				"		".$ls_cadena." as montoparcial, sum(sno_thsalida.valsal) AS total, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"        MAX(sno_thproyectopersonal.pordiames) AS pordiames ".
				"  FROM sno_thproyectopersonal, sno_thproyecto, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thproyectopersonal.codemp = sno_thsalida.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thsalida.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thsalida.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thsalida.codperi ".
				"   AND sno_thproyectopersonal.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thproyecto.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi ".
				"   AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thproyecto.estproproy,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thproyecto.estproproy,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thproyecto.estproproy,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thproyecto.estproproy,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thproyecto.estproproy,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thproyectopersonal.codper, sno_thproyectopersonal.codproy, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
		$ls_sql="SELECT sno_thproyectopersonal.codper, sno_thproyectopersonal.codproy, MAX(sno_thproyecto.estproproy) AS estproproy, spg_cuentas.spg_cuenta,".
				"		".$ls_cadena." as montoparcial, sum(sno_thsalida.valsal) AS total, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"       MAX(sno_thproyectopersonal.pordiames) AS pordiames ".
				"  FROM sno_thproyectopersonal, sno_thproyecto, sno_thsalida, sno_thconcepto, spg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thconcepto.sigcon = 'E'".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_thproyectopersonal.codemp = sno_thsalida.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thsalida.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thsalida.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thsalida.codperi ".
				"   AND sno_thproyectopersonal.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thproyecto.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi ".
				"   AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thproyecto.estproproy,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thproyecto.estproproy,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thproyecto.estproproy,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thproyecto.estproproy,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thproyecto.estproproy,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thproyectopersonal.codper, sno_thproyectopersonal.codproy, spg_cuentas.spg_cuenta ".
				" ORDER BY codper, spg_cuenta, codproy ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print $this->io_sql->message;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_presupuesto_proyecto_dt ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_programaticaant="";
			$ls_cuentaant="";
			$ls_denominacionant="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$li_montoparcial=round($row["montoparcial"],3);
				$li_total=round($row["total"],3);
				$ls_estproproy=$row["estproproy"];
				$ls_spgcuenta=$row["spg_cuenta"];
				$ls_denominacion=$row["denominacion"];
				$li_pordiames=$row["pordiames"];
				if(($ls_codper!=$ls_codant)||($ls_spgcuenta!=$ls_cuentaant))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,3)!=round($li_totalant,3))&&($li_pordiamesant<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),3);
							$this->DS->insertRow("programatica",$ls_programaticaant);
							$this->DS->insertRow("cueprecon",$ls_cuentaant);
							$this->DS->insertRow("total",$li_montoparcial);
							$this->DS->insertRow("denominacion",$ls_denominacionant);
						}
					}
					$li_acumulado=$row["montoparcial"];
					$li_montoparcial=round($row["montoparcial"],3);
					$ls_programaticaant=$ls_estproproy;
					$ls_cuentaant=$ls_spgcuenta;
					$ls_codant=$ls_codper;
					$ls_denominacionant=$ls_denominacion;
					$li_pordiamesant=$li_pordiames;
					$li_totalant=$li_total;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_programaticaant=$ls_estproproy;
					$ls_cuentaant=$ls_spgcuenta;
					$li_totalant=$li_total;
					$ls_denominacionant=$ls_denominacion;
				}
				$this->DS->insertRow("programatica",$ls_estproproy);
				$this->DS->insertRow("cueprecon",$ls_spgcuenta);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacion);
			}
			if((number_format($li_acumulado,3,".","")!=number_format($li_totalant,3,".",""))&&($li_pordiamesant<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),3);
				$this->DS->insertRow("programatica",$ls_programaticaant);
				$this->DS->insertRow("cueprecon",$ls_cuentaant);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacionant);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableconceptos_presupuesto_proyecto_dt
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_contable_proyecto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contableconceptos_contable_proyecto 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 19/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$ls_group="  GROUP BY scg_cuentas.sc_cuenta ";
		$li_parametros=$this->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I");
		switch($li_parametros)
		{
			case 0: // La contabilización es global
				$ls_cuentapasivo=trim($this->uf_select_config("SNO","CONFIG","CTA.CONTA","-------------------------","C"));
				$ls_modo=trim($this->uf_select_config("SNO","NOMINA","CONTABILIZACION","OCP","C"));
				$li_genrecdoc=str_pad($this->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO","0","I"),1,"0");
				break;
				
			case 1: // La contabilización es por nómina
				$ls_cuentapasivo=trim($_SESSION["la_nomina"]["cueconnom"]);
				$ls_modo=trim($_SESSION["la_nomina"]["consulnom"]);
				$li_genrecdoc=str_pad(trim($_SESSION["la_nomina"]["recdocnom"]),1,"0");
				break;
		}
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		$ls_sql="SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, CAST('D' AS char(1)) as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND sno_thconcepto.intprocon = '1' ".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				$ls_group;
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D que NO se 
		// integran directamente con presupuesto entonces las buscamos según la estructura de la unidad administrativa a 
		// la que pertenece el personal, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, CAST('D' AS char(1)) as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				$ls_group;
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, CAST('H' AS char(1)) as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND sno_thconcepto.sigcon = 'E' ".
				"   AND sno_thconcepto.intprocon = '1' ".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				$ls_group;
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D que NO se 
		// integran directamente con presupuesto entonces las buscamos según la estructura de la unidad administrativa a 
		// la que pertenece el personal, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, CAST('H' AS char(1)) as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND sno_thconcepto.sigcon = 'E' ".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				$ls_group;
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, CAST('D' AS char(1)) as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND sno_thconcepto.sigcon = 'B' ".
				"   AND scg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND scg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND scg_cuentas.sc_cuenta = sno_thconcepto.cueconcon ".
				$ls_group;
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, CAST('H' AS char(1)) as operacion, sum(sno_thsalida.valsal) as total ".
				"  FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3' )".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND scg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND scg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND scg_cuentas.sc_cuenta = sno_thconcepto.cueconcon ".
				$ls_group;
		if($ls_modo=="OC") // Si el modo de contabilizar la nómina es Compromete y Causa tomamos la cuenta pasivo de la nómina.
		{
			if($li_genrecdoc=="0") // No se genera Recepción de Documentos
			{
				// Buscamos todas aquellas cuentas contables de los conceptos A y D, estas van por el haber de contabilidad
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQL":
						$ls_cadena="CONVERT('".$ls_cuentapasivo."' USING utf8) as cuenta";
						break;
					case "POSTGRE":
						$ls_cadena="CAST('".$ls_cuentapasivo."' AS char(25)) as cuenta";
						break;					
				}
				$ls_sql=$ls_sql." UNION ".
						"SELECT ".$ls_cadena.", MAX(scg_cuentas.denominacion) AS denominacion,  CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
						"  FROM sno_thpersonalnomina, sno_thsalida, sno_banco, scg_cuentas ".
						" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
						"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
						"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3' )".
						"   AND sno_thsalida.valsal <> 0 ".
						"   AND (sno_thpersonalnomina.pagbanper = 1  OR sno_thpersonalnomina.pagtaqper = 1) ".
						"   AND sno_thpersonalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND scg_cuentas.sc_cuenta = '".$ls_cuentapasivo."' ".
						"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
						"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
						"   AND sno_thsalida.codemp = sno_banco.codemp ".
						"   AND sno_thsalida.codnom = sno_banco.codnom ".
						"   AND sno_thsalida.codperi = sno_banco.codperi ".
						"   AND sno_thpersonalnomina.codemp = sno_banco.codemp ".
						"   AND sno_thpersonalnomina.codban = sno_banco.codban ".
						"   AND scg_cuentas.codemp = sno_banco.codemp ".
						" GROUP BY scg_cuentas.sc_cuenta ";
			}
			else // Se genera Recepción de documentos
			{
				$ls_sql=$ls_sql." UNION ".
						"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion,  CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
						"  FROM sno_thpersonalnomina, sno_thsalida, scg_cuentas, sno_thnomina, rpc_proveedor ".
						" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
						"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
						"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3' )".
						"   AND sno_thsalida.valsal <> 0 ".
						"   AND (sno_thpersonalnomina.pagbanper = 1 OR sno_thpersonalnomina.pagtaqper = 1) ".
						"   AND sno_thpersonalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND sno_thnomina.descomnom = 'P'".
						"   AND sno_thnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thnomina.anocurnom = sno_thsalida.anocur ".
						"   AND sno_thnomina.peractnom = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
						"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
						"   AND sno_thnomina.codemp = rpc_proveedor.codemp ".
						"   AND sno_thnomina.codpronom = rpc_proveedor.cod_pro ".
						"   AND rpc_proveedor.codemp = scg_cuentas.codemp ".
						"   AND rpc_proveedor.sc_cuenta = scg_cuentas.sc_cuenta ".
						" GROUP BY scg_cuentas.sc_cuenta ";
				$ls_sql=$ls_sql." UNION ".
						"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion,  CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
						"  FROM sno_thpersonalnomina, sno_thsalida, scg_cuentas, sno_thnomina, rpc_beneficiario ".
						" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
						"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
						"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3' )".
						"   AND sno_thsalida.valsal <> 0 ".
						"   AND (sno_thpersonalnomina.pagbanper = 1 OR sno_thpersonalnomina.pagtaqper = 1) ".
						"   AND sno_thpersonalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND sno_thnomina.descomnom = 'B'".
						"   AND sno_thnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thnomina.anocurnom = sno_thsalida.anocur ".
						"   AND sno_thnomina.peractnom = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
						"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
						"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
						"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
						"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
						"   AND sno_thnomina.codemp = rpc_beneficiario.codemp ".
						"   AND sno_thnomina.codbennom = rpc_beneficiario.ced_bene ".
						"   AND rpc_beneficiario.codemp = scg_cuentas.codemp ".
						"   AND rpc_beneficiario.sc_cuenta = scg_cuentas.sc_cuenta ".
						" GROUP BY scg_cuentas.sc_cuenta ";
			}
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion,  CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
					"  FROM sno_thpersonalnomina, sno_thsalida, scg_cuentas ".
					" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_thsalida.anocur = '".$this->ls_anocurnom."' ".
					"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
					"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3')".
					"   AND sno_thsalida.valsal <> 0".
					"   AND sno_thpersonalnomina.pagbanper = 0 ".
					"   AND sno_thpersonalnomina.pagtaqper = 0 ".
					"   AND sno_thpersonalnomina.pagefeper = 1 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					"   AND scg_cuentas.codemp = sno_thpersonalnomina.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_thpersonalnomina.cueaboper ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		}
		else
		{
			// Buscamos todas aquellas cuentas contables de los conceptos A y D, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion,  CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
					"  FROM sno_thpersonalnomina, sno_thsalida, sno_banco, scg_cuentas ".
					" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_thsalida.anocur = '".$this->ls_anocurnom."' ".
					"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
					"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3')".
					"   AND sno_thsalida.valsal <> 0".
					"   AND (sno_thpersonalnomina.pagbanper = 1  OR sno_thpersonalnomina.pagtaqper = 1) ".
					"   AND sno_thpersonalnomina.pagefeper = 0 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					"   AND sno_thsalida.codemp = sno_banco.codemp ".
					"   AND sno_thsalida.codnom = sno_banco.codnom ".
					"   AND sno_thsalida.codperi = sno_banco.codperi ".
					"   AND sno_thpersonalnomina.codemp = sno_banco.codemp ".
					"   AND sno_thpersonalnomina.codban = sno_banco.codban ".
					"   AND scg_cuentas.codemp = sno_banco.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_banco.codcuecon ".
					" GROUP BY scg_cuentas.sc_cuenta ";
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, CAST('H' AS char(1)) as operacion, -sum(sno_thsalida.valsal) as total ".
					"  FROM sno_thpersonalnomina, sno_thsalida, scg_cuentas ".
					" WHERE sno_thsalida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_thsalida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_thsalida.anocur = '".$this->ls_anocurnom."' ".
					"   AND sno_thsalida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1' OR sno_thsalida.tipsal = 'D' ".
					"    OR  sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3')".
					"   AND sno_thsalida.valsal <> 0".
					"   AND sno_thpersonalnomina.pagbanper = 0 ".
					"   AND sno_thpersonalnomina.pagtaqper = 0 ".
					"   AND sno_thpersonalnomina.pagefeper = 1 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
					"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
					"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
					"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
					"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
					"   AND scg_cuentas.codemp = sno_thpersonalnomina.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_thpersonalnomina.cueaboper ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_contable_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_contableconceptos_contable_proyecto_dt();
			$this->DS_detalle->group_by(array('0'=>'cuenta','1'=>'operacion'),array('0'=>'total'),'total');		
			$this->DS_detalle->sortData('operacion');
		}
		return $lb_valido;    
	}// end function uf_contableconceptos_contable_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_contable_proyecto_dt()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contableconceptos_contable_proyecto_dt 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creación: 19/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena=" ROUND((SUM(sno_thsalida.valsal)*MAX(sno_thproyectopersonal.pordiames)),3) ";
				break;
			case "POSTGRE":
				$ls_cadena=" ROUND(CAST((sum(sno_thsalida.valsal)*MAX(sno_thproyectopersonal.pordiames)) AS NUMERIC),3) ";
				break;					
		}
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		$ls_sql="SELECT scg_cuentas.sc_cuenta as cuenta, CAST('D' AS char(1)) as operacion, sum(sno_thsalida.valsal) as total, ".
				"		".$ls_cadena." as montoparcial, sno_thproyectopersonal.codper, sno_thproyectopersonal.codproy, ".
				"		MAX(scg_cuentas.denominacion) AS denominacion, MAX(sno_thproyectopersonal.pordiames) as pordiames, sno_thconcepto.codconc ".
				"  FROM sno_thproyectopersonal, sno_thproyecto, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND sno_thconcepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thproyectopersonal.codemp = sno_thsalida.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thsalida.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thsalida.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thsalida.codperi ".
				"   AND sno_thproyectopersonal.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thproyecto.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi ".
				"   AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_thproyecto.estproproy,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thproyecto.estproproy,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thproyecto.estproproy,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thproyecto.estproproy,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thproyecto.estproproy,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thproyectopersonal.codper, sno_thconcepto.codconc, sno_thproyectopersonal.codproy, scg_cuentas.sc_cuenta ";
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
		$ls_sql="SELECT scg_cuentas.sc_cuenta as cuenta, CAST('D' AS char(1)) as operacion, sum(sno_thsalida.valsal) as total, ".
				"		".$ls_cadena." as montoparcial, sno_thproyectopersonal.codper, sno_thproyectopersonal.codproy, ".
				"		MAX(scg_cuentas.denominacion) AS denominacion, MAX(sno_thproyectopersonal.pordiames) as pordiames, sno_thconcepto.codconc ".
				"  FROM sno_thproyectopersonal, sno_thproyecto, sno_thsalida, sno_thconcepto, spg_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND sno_thconcepto.sigcon = 'E' ".
				"   AND sno_thconcepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_thproyectopersonal.codemp = sno_thsalida.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thsalida.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thsalida.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thsalida.codperi ".
				"   AND sno_thproyectopersonal.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thproyecto.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi ".
				"   AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND spg_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   AND substr(sno_thproyecto.estproproy,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thproyecto.estproproy,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thproyecto.estproproy,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thproyecto.estproproy,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thproyecto.estproproy,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thproyectopersonal.codper, sno_thconcepto.codconc, sno_thproyectopersonal.codproy, scg_cuentas.sc_cuenta ".
				" ORDER BY codper, codconc, codproy,  cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_contable_proyecto_dt ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_cuentaant="";
			$ls_codconcant="";
			$ls_operacionant="";
			$ls_denominacionant="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_codconc=$row["codconc"];
				$li_montoparcial=$row["montoparcial"];
				$li_total=$row["total"];
				$ls_cuenta=$row["cuenta"];
				$ls_operacion=$row["operacion"];
				$ls_denominacion=$row["denominacion"];
				$li_pordiames=$row["pordiames"];
				if(($ls_codper!=$ls_codant)||($ls_codconc!=$ls_codconcant))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,3)!=round($li_totalant,3))&&($li_pordiamesant<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),3);
							$this->DS_detalle->insertRow("operacion",$ls_operacionant);
							$this->DS_detalle->insertRow("cuenta",$ls_cuentaant);
							$this->DS_detalle->insertRow("total",$li_montoparcial);
							$this->DS_detalle->insertRow("denominacion",$ls_denominacionant);
						}
					}
					$li_acumulado=$row["montoparcial"];
					$li_montoparcial=round($row["montoparcial"],3);
					$ls_operacionant=$ls_operacion;
					$ls_cuentaant=$ls_cuenta;
					$ls_codconcant=$ls_codconc;
					$ls_codant=$ls_codper;
					$ls_denominacionant=$ls_denominacion;
					$li_pordiamesant=$li_pordiames;
					$li_totalant=$li_total;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_operacionant=$ls_operacion;
					$ls_cuentaant=$ls_cuenta;
					$ls_codconcant=$ls_codconc;
					$li_totalant=$li_total;
					$ls_denominacionant=$ls_denominacion;
				}
				$this->DS_detalle->insertRow("operacion",$ls_operacion);
				$this->DS_detalle->insertRow("cuenta",$ls_cuenta);
				$this->DS_detalle->insertRow("total",$li_montoparcial);
				$this->DS_detalle->insertRow("denominacion",$ls_denominacion);
			}
			if((round($li_acumulado,3)!=round($li_totalant,3))&&($li_pordiamesant<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),3);
				$this->DS_detalle->insertRow("operacion",$ls_operacionant);
				$this->DS_detalle->insertRow("cuenta",$ls_cuentaant);
				$this->DS_detalle->insertRow("total",$li_montoparcial);
				$this->DS_detalle->insertRow("denominacion",$ls_denominacionant);
			}
			$this->io_sql->free_result($rs_data);
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_contableconceptos_contable_proyecto_dt
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableingresos_ingreso()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableingresos_ingreso
		//         Access: public (desde la clase tepuy_sno_r_contableingresos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas de ingresos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 25/03/2008 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT spi_cuentas.spi_cuenta AS cuenta, MAX(spi_cuentas.denominacion) AS denominacion, ".
				"		sum((sno_thsalida.valsal*sno_thconcepto.poringcon)/100) as total ".
				"  FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, spi_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3' )".
				"   AND sno_thconcepto.intingcon = '1'".
				"   AND spi_cuentas.status = 'C' ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND spi_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spi_cuentas.spi_cuenta = sno_thconcepto.spi_cuenta ".
				" GROUP BY spi_cuentas.spi_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableingresos_ingreso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableingresos_ingreso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableingresos_contable()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableingresos_contable
		//         Access: public (desde la clase tepuy_sno_r_contableingresos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas contables que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 11/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos que se 
		// integran directamente con presupuesto estas van por el debe de contabilidad
		$ls_sql="SELECT spi_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denominacion, 'H' as operacion, ".
				"		sum((sno_thsalida.valsal*sno_thconcepto.poringcon)/100) as total ".
				"  FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, spi_cuentas, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3' )".
				"   AND sno_thconcepto.intingcon = '1'".
				"   AND spi_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND spi_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spi_cuentas.spi_cuenta = sno_thconcepto.spi_cuenta ".
				"   AND spi_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
				"   GROUP BY spi_cuentas.sc_cuenta ";
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos que NO se 
		// integran directamente con presupuesto entonces las buscamos según la estructura de la unidad administrativa a 
		// la que pertenece el personal, estas van por el debe de contabilidad
		$ls_sql=$ls_sql." UNION ".
				"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'D' as operacion, ".
				"		sum((sno_thsalida.valsal*sno_thconcepto.poringcon)/100) as total ".
				"  FROM sno_thpersonalnomina, sno_thsalida, sno_thconcepto, scg_cuentas ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_thsalida.valsal <> 0 ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2' OR sno_thsalida.tipsal = 'P1' OR sno_thsalida.tipsal = 'V3' OR sno_thsalida.tipsal = 'W3' )".
				"   AND sno_thconcepto.intingcon = '1'".
				"   AND scg_cuentas.status = 'C'".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND scg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND scg_cuentas.sc_cuenta = sno_thconcepto.cueconcon  ".
				"   GROUP BY scg_cuentas.sc_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_contable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS_detalle->group_by(array('0'=>'cuenta','1'=>'operacion'),array('0'=>'total'),'total');
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableingresos_contable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_especifico_presupuesto($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
														 $as_subnomdes,$as_subnomhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableconceptos_especifico_presupuesto
		//         Access: public (desde la clase tepuy_sno_r_contableconceptos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo A, D, P1
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 22/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if($as_codestpro1!='00000000000000000000')
		{		
			$ls_criterio="   AND spg_cuentas.codestpro1 = '".str_pad($as_codestpro1,20,"0","0")."'".
						 "   AND spg_cuentas.codestpro2 = '".str_pad($as_codestpro2,6,"0","0")."'".
						 "   AND spg_cuentas.codestpro3 = '".str_pad($as_codestpro3,3,"0","0")."'".
						 "   AND spg_cuentas.codestpro4 = '".str_pad($as_codestpro4,2,"0","0")."'".
						 "   AND spg_cuentas.codestpro5 = '".str_pad($as_codestpro5,2,"0","0")."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codsubnom<='".$as_subnomhas."'";
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql="SELECT sno_thconcepto.codpro as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper, COUNT(sno_thpersonalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, sno_dedicacion, sno_tipopersonal  ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				$ls_criterio.
				"   AND sno_thpersonalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_thpersonalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_thpersonalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thconcepto.codpro, sno_thconcepto.cueprecon, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper  ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thunidadadmin.codprouniadm as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper, COUNT(sno_thpersonalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, sno_dedicacion, sno_tipopersonal  ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				$ls_criterio.
				"   AND sno_thpersonalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_thpersonalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_thpersonalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thunidadadmin.codprouniadm, sno_thconcepto.cueprecon, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper  ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thconcepto.codpro as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper, COUNT(sno_thpersonalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, sno_dedicacion, sno_tipopersonal  ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thconcepto.sigcon = 'E'".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				$ls_criterio.
				"   AND sno_thpersonalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_thpersonalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_thpersonalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thconcepto.codpro, sno_thconcepto.cueprecon, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper  ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thunidadadmin.codprouniadm as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper, COUNT(sno_thpersonalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, sno_dedicacion, sno_tipopersonal  ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thconcepto.sigcon = 'E'".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				$ls_criterio.
				"   AND sno_thpersonalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_thpersonalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_thpersonalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thunidadadmin.codprouniadm, sno_thconcepto.cueprecon, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper  ".
				" ORDER BY programatica, cueprecon";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_presupuesto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableconceptos_especifico_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_especifico_presupuesto_proyecto($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
																  $as_subnomdes,$as_subnomhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableconceptos_presupuesto_proyecto
		//         Access: public (desde la clase tepuy_sno_r_contableconceptos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo A, D, P1
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if($as_codestpro1!='00000000000000000000')
		{		
			$ls_criterio="   AND spg_cuentas.codestpro1 = '".str_pad($as_codestpro1,20,"0","0")."'".
						 "   AND spg_cuentas.codestpro2 = '".str_pad($as_codestpro2,6,"0","0")."'".
						 "   AND spg_cuentas.codestpro3 = '".str_pad($as_codestpro3,3,"0","0")."'".
						 "   AND spg_cuentas.codestpro4 = '".str_pad($as_codestpro4,2,"0","0")."'".
						 "   AND spg_cuentas.codestpro5 = '".str_pad($as_codestpro5,2,"0","0")."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codsubnom<='".$as_subnomhas."'";
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql="SELECT sno_thconcepto.codpro as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper, COUNT(sno_thpersonalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '1' ".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				$ls_criterio.
				"   AND sno_thpersonalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_thpersonalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_thpersonalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thconcepto.codpro, sno_thconcepto.cueprecon, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thunidadadmin.codprouniadm as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper, COUNT(sno_thpersonalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				$ls_criterio.
				"   AND sno_thpersonalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_thpersonalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_thpersonalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thunidadadmin.codprouniadm, sno_thconcepto.cueprecon, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thconcepto.codpro as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper, COUNT(sno_thpersonalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thconcepto.sigcon = 'E'".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '1'".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				$ls_criterio.
				"   AND sno_thpersonalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_thpersonalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_thpersonalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thconcepto.codpro, sno_thconcepto.cueprecon, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thunidadadmin.codprouniadm as programatica, sno_thconcepto.cueprecon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper, COUNT(sno_thpersonalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thconcepto.sigcon = 'E'".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				$ls_criterio.
				"   AND sno_thpersonalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_thpersonalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_thpersonalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thunidadadmin.codprouniadm, sno_thconcepto.cueprecon, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper ".
				" ORDER BY programatica, cueprecon";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_especifico_presupuesto_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_contableconceptos_especifico_presupuesto_proyecto_dt($ls_criterio);
			$this->DS->group_by(array('0'=>'programatica','1'=>'cueprecon','2'=>'codded','3'=>'codtipper'),array('0'=>'total'),'total');		
			$this->DS->sortData('programatica');
		}
		return $lb_valido;
	}// end function uf_contableconceptos_especifico_presupuesto_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_especifico_presupuesto_proyecto_dt($as_criterio)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableconceptos_presupuesto_proyecto_dt
		//         Access: public (desde la clase tepuy_sno_r_contableconceptos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo A, D, P1
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena=" ROUND((SUM(sno_thsalida.valsal)*MAX(sno_thproyectopersonal.pordiames)),3) ";
				break;
			case "POSTGRE":
				$ls_cadena=" ROUND(CAST((sum(sno_thsalida.valsal)*MAX(sno_thproyectopersonal.pordiames)) AS NUMERIC),3) ";
				break;					
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql="SELECT sno_thproyectopersonal.codper, sno_thproyectopersonal.codproy, sno_thproyecto.estproproy, spg_cuentas.spg_cuenta,".
				"		".$ls_cadena." as montoparcial, ".$ls_cadena." AS total, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"       MAX(sno_thproyectopersonal.pordiames) As pordiames, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper, COUNT(sno_thpersonalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_thproyectopersonal, sno_thproyecto, sno_thsalida, sno_thconcepto, spg_cuentas, sno_thpersonalnomina, sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'A' OR sno_thsalida.tipsal = 'V1' OR sno_thsalida.tipsal = 'W1') ".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C'".
				$as_criterio.
				"   AND sno_thproyectopersonal.codemp = sno_thpersonalnomina.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thpersonalnomina.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thpersonalnomina.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thpersonalnomina.codperi ".
				"   AND sno_thproyectopersonal.codper = sno_thpersonalnomina.codper ".
				"   AND sno_thpersonalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_thpersonalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_thpersonalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_thproyectopersonal.codemp = sno_thsalida.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thsalida.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thsalida.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thsalida.codperi ".
				"   AND sno_thproyectopersonal.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thproyecto.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi ".
				"   AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thproyecto.estproproy,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thproyecto.estproproy,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thproyecto.estproproy,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thproyecto.estproproy,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thproyecto.estproproy,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thproyectopersonal.codper, sno_thproyectopersonal.codproy, sno_thproyecto.estproproy, spg_cuentas.spg_cuenta, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper  ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
		$ls_sql="SELECT sno_thproyectopersonal.codper, sno_thproyectopersonal.codproy, sno_thproyecto.estproproy, spg_cuentas.spg_cuenta,".
				"		".$ls_cadena." as montoparcial, ".$ls_cadena." AS total, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"       MAX(sno_thproyectopersonal.pordiames) As pordiames, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper, COUNT(sno_thpersonalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_thproyectopersonal, sno_thproyecto, sno_thsalida, sno_thconcepto, spg_cuentas, sno_thpersonalnomina, sno_dedicacion, sno_tipopersonal  ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'D' OR sno_thsalida.tipsal = 'V2' OR sno_thsalida.tipsal = 'W2') ".
				"   AND sno_thconcepto.sigcon = 'E'".
				"   AND sno_thsalida.valsal <> 0".
				"   AND sno_thconcepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C' ".
				$as_criterio.
				"   AND sno_thproyectopersonal.codemp = sno_thpersonalnomina.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thpersonalnomina.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thpersonalnomina.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thpersonalnomina.codperi ".
				"   AND sno_thproyectopersonal.codper = sno_thpersonalnomina.codper ".
				"   AND sno_thpersonalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_thpersonalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_thpersonalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_thproyectopersonal.codemp = sno_thsalida.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thsalida.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thsalida.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thsalida.codperi ".
				"   AND sno_thproyectopersonal.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thproyecto.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi ".
				"   AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprecon ".
				"   AND substr(sno_thproyecto.estproproy,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thproyecto.estproproy,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thproyecto.estproproy,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thproyecto.estproproy,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thproyecto.estproproy,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thproyectopersonal.codper, sno_thproyectopersonal.codproy, sno_thproyecto.estproproy, spg_cuentas.spg_cuenta, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper  ".
				" ORDER BY codper, spg_cuenta, codproy ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_presupuesto_proyecto_dt ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_programaticaant="";
			$ls_cuentaant="";
			$ls_denominacionant="";
			$ls_coddedant="";
			$ls_codtipperant="";
			$ls_desdedant="";
			$ls_destipperant="";
			$li_totalpersonalant=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_codded=$row["codded"];
				$ls_codtipper=$row["codtipper"];
				$ls_desded=$row["desded"];
				$ls_destipper=$row["destipper"];
				$li_totalpersonal=$row["totalpersonal"];
				$li_montoparcial=round($row["montoparcial"],3);
				$li_total=round($row["total"],3);
				$ls_estproproy=$row["estproproy"];
				$ls_spgcuenta=$row["spg_cuenta"];
				$ls_denominacion=$row["denominacion"];
				$li_pordiames=$row["pordiames"];
				if(($ls_codper!=$ls_codant)||($ls_spgcuenta!=$ls_cuentaant))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,3)!=round($li_totalant,3))&&($li_pordiamesant<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),3);
							$this->DS->insertRow("programatica",$ls_programaticaant);
							$this->DS->insertRow("cueprecon",$ls_cuentaant);
							$this->DS->insertRow("total",$li_montoparcial);
							$this->DS->insertRow("denominacion",$ls_denominacionant);
							$this->DS->insertRow("codded",$ls_coddedant);
							$this->DS->insertRow("codtipper",$ls_codtipperant);
							$this->DS->insertRow("desded",$ls_desdedant);
							$this->DS->insertRow("destipper",$ls_destipperant);
							$this->DS->insertRow("totalpersonal",$li_totalpersonalant);
						}
					}
					$li_acumulado=$row["montoparcial"];
					$li_montoparcial=round($row["montoparcial"],3);
					$ls_programaticaant=$ls_estproproy;
					$ls_cuentaant=$ls_spgcuenta;
					$li_totalant=$li_total;
					$ls_codant=$ls_codper;
					$ls_denominacionant=$ls_denominacion;
					$li_pordiamesant=$li_pordiames;
					$ls_coddedant=$ls_codded;
					$ls_codtipperant=$ls_codtipper;
					$ls_desdedant=$ls_desded;
					$ls_destipperant=$ls_destipper;
					$li_totalpersonalant=$li_totalpersonal;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_programaticaant=$ls_estproproy;
					$ls_cuentaant=$ls_spgcuenta;
					$li_totalant=$li_total;
					$ls_denominacionant=$ls_denominacion;
					$ls_coddedant=$ls_codded;
					$ls_codtipperant=$ls_codtipper;
					$ls_desdedant=$ls_desded;
					$ls_destipperant=$ls_destipper;
					$li_totalpersonalant=$li_totalpersonal;
				}
				$this->DS->insertRow("programatica",$ls_estproproy);
				$this->DS->insertRow("cueprecon",$ls_spgcuenta);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacion);
				$this->DS->insertRow("codded",$ls_codded);
				$this->DS->insertRow("codtipper",$ls_codtipper);
				$this->DS->insertRow("desded",$ls_desded);
				$this->DS->insertRow("destipper",$ls_destipper);
				$this->DS->insertRow("totalpersonal",$li_totalpersonal);
			}
			if((number_format($li_acumulado,3,".","")!=number_format($li_totalant,3,".",""))&&($li_pordiamesant<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),3);
				$this->DS->insertRow("programatica",$ls_programaticaant);
				$this->DS->insertRow("cueprecon",$ls_cuentaant);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacionant);
				$this->DS->insertRow("codded",$ls_coddedant);
				$this->DS->insertRow("codtipper",$ls_codtipperant);
				$this->DS->insertRow("desded",$ls_desdedant);
				$this->DS->insertRow("destipper",$ls_destipperant);
				$this->DS->insertRow("totalpersonal",$li_totalpersonalant);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableconceptos_especifico_presupuesto_proyecto_dt
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_especifico_presupuesto($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
													   $as_subnomdes,$as_subnomhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableaportes_especifico_presupuesto
		//         Access: public (desde la clase tepuy_sno_r_contableaportes)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 11/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if($as_codestpro1!='00000000000000000000')
		{		
			$ls_criterio="   AND spg_cuentas.codestpro1 = '".str_pad($as_codestpro1,20,"0","0")."'".
						 "   AND spg_cuentas.codestpro2 = '".str_pad($as_codestpro2,6,"0","0")."'".
						 "   AND spg_cuentas.codestpro3 = '".str_pad($as_codestpro3,3,"0","0")."'".
						 "   AND spg_cuentas.codestpro4 = '".str_pad($as_codestpro4,2,"0","0")."'".
						 "   AND spg_cuentas.codestpro5 = '".str_pad($as_codestpro5,2,"0","0")."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codsubnom<='".$as_subnomhas."'";
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT sno_thconcepto.codpro as programatica, spg_cuentas.spg_cuenta AS cueprepatcon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper, COUNT(sno_thpersonalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
				"   AND sno_thconcepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_thsalida.valsal <> 0 ".
				$ls_criterio.
				"   AND sno_thpersonalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_thpersonalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_thpersonalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon ".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thconcepto.codpro, spg_cuentas.spg_cuenta, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thunidadadmin.codprouniadm as programatica, spg_cuentas.spg_cuenta AS cueprepatcon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper, COUNT(sno_thpersonalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, sno_dedicacion, sno_tipopersonal  ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_thsalida.valsal <> 0 ".
				$ls_criterio.
				"   AND sno_thpersonalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_thpersonalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_thpersonalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon ".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thunidadadmin.codprouniadm, spg_cuentas.spg_cuenta, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_presupuesto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableaportes_especifico_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_especifico_presupuesto_proyecto($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
																$as_subnomdes,$as_subnomhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableaportes_presupuesto_proyecto
		//         Access: public (desde la clase tepuy_sno_r_contableaportes)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_criterio="";
		if($as_codestpro1!='00000000000000000000')
		{		
			$ls_criterio="   AND spg_cuentas.codestpro1 = '".str_pad($as_codestpro1,20,"0","0")."'".
						 "   AND spg_cuentas.codestpro2 = '".str_pad($as_codestpro2,6,"0","0")."'".
						 "   AND spg_cuentas.codestpro3 = '".str_pad($as_codestpro3,3,"0","0")."'".
						 "   AND spg_cuentas.codestpro4 = '".str_pad($as_codestpro4,2,"0","0")."'".
						 "   AND spg_cuentas.codestpro5 = '".str_pad($as_codestpro5,2,"0","0")."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_thpersonalnomina.codsubnom<='".$as_subnomhas."'";
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT sno_thconcepto.codpro as programatica, spg_cuentas.spg_cuenta AS cueprepatcon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total, sno_thpersonalnomina.codded,  ".
				"		sno_thpersonalnomina.codtipper, COUNT(sno_thpersonalnomina.codper) AS totalpersonal, MAX(sno_dedicacion.desded) AS desded, ".
				"       MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, sno_dedicacion, sno_tipopersonal  ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
				"   AND sno_thconcepto.intprocon = '1'".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_thsalida.valsal <> 0 ".
				$ls_criterio.
				"   AND sno_thpersonalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_thpersonalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_thpersonalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon ".
				"   AND substr(sno_thconcepto.codpro,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thconcepto.codpro,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thconcepto.codpro,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thconcepto.codpro,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thconcepto.codpro,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thconcepto.codpro, spg_cuentas.spg_cuenta, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_thunidadadmin.codprouniadm as programatica, spg_cuentas.spg_cuenta AS cueprepatcon, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, sum(sno_thsalida.valsal) as total, sno_thpersonalnomina.codded,  ".
				"		sno_thpersonalnomina.codtipper, COUNT(sno_thpersonalnomina.codper) AS totalpersonal, MAX(sno_dedicacion.desded) AS desded, ".
				"       MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_thpersonalnomina, sno_thunidadadmin, sno_thsalida, sno_thconcepto, spg_cuentas, sno_dedicacion, sno_tipopersonal    ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
				"   AND sno_thconcepto.intprocon = '0'".
				"   AND sno_thconcepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_thsalida.valsal <> 0 ".
				$ls_criterio.
				"   AND sno_thpersonalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_thpersonalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_thpersonalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_thpersonalnomina.codemp = sno_thsalida.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thsalida.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thsalida.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thsalida.codperi ".
				"   AND sno_thpersonalnomina.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thpersonalnomina.codemp = sno_thunidadadmin.codemp ".
				"   AND sno_thpersonalnomina.codnom = sno_thunidadadmin.codnom ".
				"   AND sno_thpersonalnomina.anocur = sno_thunidadadmin.anocur ".
				"   AND sno_thpersonalnomina.codperi = sno_thunidadadmin.codperi ".
				"   AND sno_thpersonalnomina.minorguniadm = sno_thunidadadmin.minorguniadm ".
				"   AND sno_thpersonalnomina.ofiuniadm = sno_thunidadadmin.ofiuniadm ".
				"   AND sno_thpersonalnomina.uniuniadm = sno_thunidadadmin.uniuniadm ".
				"   AND sno_thpersonalnomina.depuniadm = sno_thunidadadmin.depuniadm ".
				"   AND sno_thpersonalnomina.prouniadm = sno_thunidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon ".
				"   AND substr(sno_thunidadadmin.codprouniadm,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thunidadadmin.codprouniadm,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thunidadadmin.codprouniadm, spg_cuentas.spg_cuenta, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_especifico_presupuesto_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_contableaportes_especifico_presupuesto_proyecto_dt($ls_criterio);
			$this->DS->group_by(array('0'=>'programatica','1'=>'cueprepatcon','2'=>'codded','3'=>'codtipper'),array('0'=>'total'),'total');		
			$this->DS->sortData('programatica');
		}
		return $lb_valido;
	}// end function uf_contableaportes_especifico_presupuesto_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_especifico_presupuesto_proyecto_dt($as_criterio)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableaportes_presupuesto_proyecto_dt
		//         Access: public (desde la clase tepuy_sno_r_contableaportes)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena=" ROUND((SUM(sno_thsalida.valsal)*MAX(sno_thproyectopersonal.pordiames)),3) ";
				break;
			case "POSTGRE":
				$ls_cadena=" ROUND(CAST((sum(sno_thsalida.valsal)*MAX(sno_thproyectopersonal.pordiames)) AS NUMERIC),3) ";
				break;					
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT MAX(sno_thproyecto.estproproy) AS estproproy, MAX(spg_cuentas.spg_cuenta) AS spg_cuenta, ".
				"		".$ls_cadena." AS total, MAX(sno_thconcepto.codprov) AS codprov, ".$ls_cadena." AS montoparcial, ".
				"		MAX(sno_thconcepto.cedben) AS cedben, sno_thconcepto.codconc, sno_thproyecto.codproy, sno_thproyectopersonal.codper, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, MAX(sno_thproyectopersonal.pordiames) AS pordiames, sno_thpersonalnomina.codded, ".
				"		sno_thpersonalnomina.codtipper, COUNT(sno_thpersonalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_thproyectopersonal, sno_thproyecto, sno_thsalida, sno_thconcepto, spg_cuentas, sno_thpersonalnomina, sno_dedicacion, sno_tipopersonal  ".
				" WHERE sno_thsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_thsalida.codnom='".$this->ls_codnom."' ".
				"   AND sno_thsalida.anocur='".$this->ls_anocurnom."' ".
				"   AND sno_thsalida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_thsalida.tipsal = 'P2' OR sno_thsalida.tipsal = 'V4' OR sno_thsalida.tipsal = 'W4')".
				"   AND sno_thconcepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_thsalida.valsal <> 0 ".
				$as_criterio.
				"   AND sno_thproyectopersonal.codemp = sno_thpersonalnomina.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thpersonalnomina.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thpersonalnomina.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thpersonalnomina.codperi ".
				"   AND sno_thproyectopersonal.codper = sno_thpersonalnomina.codper ".
				"   AND sno_thpersonalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_thpersonalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_thpersonalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_thpersonalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_thproyectopersonal.codemp = sno_thsalida.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thsalida.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thsalida.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thsalida.codperi ".
				"   AND sno_thproyectopersonal.codper = sno_thsalida.codper ".
				"   AND sno_thsalida.codemp = sno_thconcepto.codemp ".
				"   AND sno_thsalida.codnom = sno_thconcepto.codnom ".
				"   AND sno_thsalida.anocur = sno_thconcepto.anocur ".
				"   AND sno_thsalida.codperi = sno_thconcepto.codperi ".
				"   AND sno_thsalida.codconc = sno_thconcepto.codconc ".
				"   AND sno_thproyectopersonal.codemp = sno_thproyecto.codemp ".
				"   AND sno_thproyectopersonal.codnom = sno_thproyecto.codnom ".
				"   AND sno_thproyectopersonal.anocur = sno_thproyecto.anocur ".
				"   AND sno_thproyectopersonal.codperi = sno_thproyecto.codperi ".
				"   AND sno_thproyectopersonal.codproy = sno_thproyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_thconcepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_thconcepto.cueprepatcon ".
				"   AND substr(sno_thproyecto.estproproy,1,20) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_thproyecto.estproproy,21,6) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_thproyecto.estproproy,27,3) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_thproyecto.estproproy,30,2) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_thproyecto.estproproy,32,2) = spg_cuentas.codestpro5 ".
				" GROUP BY sno_thproyectopersonal.codper, sno_thproyecto.codproy, sno_thconcepto.codconc, spg_cuentas.spg_cuenta, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper ".
				" ORDER BY sno_thproyectopersonal.codper, sno_thproyecto.codproy, sno_thconcepto.codconc, spg_cuentas.spg_cuenta, sno_thpersonalnomina.codded, sno_thpersonalnomina.codtipper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_especifico_presupuesto_proyecto_dt ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_programaticaant="";
			$ls_cuentaant="";
			$ls_denominacionant="";
			$ls_coddedant="";
			$ls_codtipperant="";
			$ls_desdedant="";
			$ls_destipperant="";
			$li_totalpersonalant=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_codded=$row["codded"];
				$ls_codtipper=$row["codtipper"];
				$ls_desded=$row["desded"];
				$ls_destipper=$row["destipper"];
				$li_totalpersonal=$row["totalpersonal"];
				$li_montoparcial=$row["montoparcial"];
				$li_total=$row["total"];
				$ls_estproproy=$row["estproproy"];
				$ls_spgcuenta=$row["spg_cuenta"];
				$ls_denominacion=$row["denominacion"];
				$li_pordiames=$row["pordiames"];
				if(($ls_codper!=$ls_codant)||($ls_spgcuenta!=$ls_cuentaant))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,3)!=round($li_totalant,3))&&($li_pordiames<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),3);
							$this->DS->insertRow("programatica",$ls_programaticaant);
							$this->DS->insertRow("cueprepatcon",$ls_cuentaant);
							$this->DS->insertRow("total",$li_montoparcial);
							$this->DS->insertRow("denominacion",$ls_denominacionant);
							$this->DS->insertRow("codded",$ls_coddedant);
							$this->DS->insertRow("codtipper",$ls_codtipperant);
							$this->DS->insertRow("desded",$ls_desdedant);
							$this->DS->insertRow("destipper",$ls_destipperant);
							$this->DS->insertRow("totalpersonal",$li_totalpersonalant);
						}
					}
					$li_acumulado=$row["montoparcial"];
					$li_montoparcial=round($row["montoparcial"],3);
					$ls_programaticaant=$ls_estproproy;
					$ls_cuentaant=$ls_spgcuenta;
					$ls_codant=$ls_codper;
					$ls_denominacionant=$ls_denominacion;
					$li_pordiamesant=$li_pordiames;
					$li_totalant=$li_total;
					$ls_coddedant=$ls_codded;
					$ls_codtipperant=$ls_codtipper;
					$ls_desdedant=$ls_desded;
					$ls_destipperant=$ls_destipper;
					$li_totalpersonalant=$li_totalpersonal;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_programaticaant=$ls_estproproy;
					$ls_cuentaant=$ls_spgcuenta;
					$li_totalant=$li_total;
					$ls_denominacionant=$ls_denominacion;
					$ls_coddedant=$ls_codded;
					$ls_codtipperant=$ls_codtipper;
					$ls_desdedant=$ls_desded;
					$ls_destipperant=$ls_destipper;
					$li_totalpersonalant=$li_totalpersonal;
				}
				$this->DS->insertRow("programatica",$ls_estproproy);
				$this->DS->insertRow("cueprepatcon",$ls_spgcuenta);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacion);
				$this->DS->insertRow("codded",$ls_codded);
				$this->DS->insertRow("codtipper",$ls_codtipper);
				$this->DS->insertRow("desded",$ls_desded);
				$this->DS->insertRow("destipper",$ls_destipper);
				$this->DS->insertRow("totalpersonal",$li_totalpersonal);
			}
			if((number_format($li_acumulado,3,".","")!=number_format($li_totalant,3,".",""))&&($li_pordiames<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),3);
				$this->DS->insertRow("programatica",$ls_programaticaant);
				$this->DS->insertRow("cueprepatcon",$ls_cuentaant);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacionant);
				$this->DS->insertRow("codded",$ls_coddedant);
				$this->DS->insertRow("codtipper",$ls_codtipperant);
				$this->DS->insertRow("desded",$ls_desdedant);
				$this->DS->insertRow("destipper",$ls_destipperant);
				$this->DS->insertRow("totalpersonal",$li_totalpersonalant);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableaportes_especifico_presupuesto_proyecto_dt
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>