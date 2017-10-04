<?PHP
class tepuy_snorh_c_programacionreporte
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personal;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_snorh_c_programacionreporte()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_snorh_c_programacionreporte
		//		   Access: public (tepuy_snorh_p_programacionreporte)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 21/06/2006 								Fecha �ltima Modificaci�n : 
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
		$this->io_seguridad=new tepuy_c_seguridad();
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
	}// end function tepuy_snorh_c_programacionreporte
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (tepuy_snorh_p_programacionreporte)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 21/06/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fun_nomina);
        unset($this->ls_codemp);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_programacionreporte($as_codrep)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_programacionreporte
		//		   Access: private
		//	    Arguments: as_codrep  // c�digo del reporte
		//	      Returns: lb_existe True si existe � False si no existe
		//	  Description: Funcion que verifica si la programaci�n de reporte est� registrada
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 22/06/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codrep ".
				"  FROM sno_programacionreporte ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codrep='".$as_codrep."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Programaci�n Reporte M�TODO->uf_select_programacionreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_programacionreporte
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_programacionreporte0406($as_reporte)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_programacionreporte0406
		//		   Access: public (tepuy_snorh_p_programacionreporte)
		//	    Arguments: as_reporte  // c�digo del Reporte
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar � False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos las clasificaciones del la programaci�n de reporte
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 22/06/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_dedicacion.codded, sno_tipopersonal.codtipper ".
				"  FROM sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_dedicacion.codemp='".$this->ls_codemp."' ".
				"   AND sno_dedicacion.codded<>'000'".
				"   AND sno_tipopersonal.codtipper<>'0000' ".
				"   AND sno_dedicacion.codemp = sno_tipopersonal.codemp ".
				"	AND sno_dedicacion.codded = sno_tipopersonal.codded ".
				" ORDER BY sno_dedicacion.codded,sno_tipopersonal.codtipper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Programaci�n Reporte M�TODO->uf_insert_programacionreporte0406 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ls_codigo="";
			$this->io_sql->begin_transaction();
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codded=$row["codded"];
				$ls_codtipper=$row["codtipper"];
				if($ls_codigo!=$ls_codded)
				{
					$ls_codigo=$ls_codded;
					$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
							"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
							"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
							"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','0000',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,".
							"			  0,0,0,0,0,0,0,0,0,0,0,0)";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Programaci�n Reporte M�TODO->uf_insert_programacionreporte0406 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
					}
				}
				$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
						"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
						"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
						"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','".$ls_codtipper."',0,0,0,0,0,0,0,".
						"			  0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Programaci�n Reporte M�TODO->uf_insert_programacionreporte0406 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
				}
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{	
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_programacionreporte0406
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_programacionreporte0506($as_reporte)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_programacionreporte0506
		//		   Access: public (tepuy_snorh_p_programacionreporte)
		//	    Arguments: as_reporte  // c�digo del Reporte
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar � False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos las clasificaciones del la programaci�n de reporte
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 22/06/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_dedicacion.codded, sno_tipopersonal.codtipper ".
				"  FROM sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_dedicacion.codemp='".$this->ls_codemp."' ".
				"   AND sno_dedicacion.codded<>'000'".
				"   AND sno_tipopersonal.codtipper<>'0000' ".
				"   AND sno_dedicacion.codemp = sno_tipopersonal.codemp ".
				"	AND sno_dedicacion.codded = sno_tipopersonal.codded ".
				" ORDER BY sno_dedicacion.codded,sno_tipopersonal.codtipper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Programaci�n Reporte M�TODO->uf_insert_programacionreporte0711 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ls_codigo="";
			$this->io_sql->begin_transaction();
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codded=$row["codded"];
				$ls_codtipper=$row["codtipper"];
				if($ls_codigo!=$ls_codded)
				{
					$ls_codigo=$ls_codded;
					$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
							"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
							"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
							"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','0000',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,".
							"			  0,0,0,0,0,0,0,0,0,0,0,0)";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Programaci�n Reporte M�TODO->uf_insert_programacionreporte0711 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
					}
					
				}
				$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
						"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
						"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
						"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','".$ls_codtipper."',0,0,0,0,0,0,0,".
						"			  0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Programaci�n Reporte M�TODO->uf_insert_programacionreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
				}
				
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{	
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_programacionreporte0506
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_programacionreporte0711($as_reporte)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_programacionreporte0711
		//		   Access: public (tepuy_snorh_p_programacionreporte)
		//	    Arguments: as_reporte  // c�digo del Reporte
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar � False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos las clasificaciones del la programaci�n de reporte
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 22/06/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_dedicacion.codded, sno_tipopersonal.codtipper ".
				"  FROM sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_dedicacion.codemp='".$this->ls_codemp."' ".
				"   AND sno_dedicacion.codded<>'000'".
				"   AND sno_tipopersonal.codtipper<>'0000' ".
				"   AND sno_dedicacion.codemp = sno_tipopersonal.codemp ".
				"	AND sno_dedicacion.codded = sno_tipopersonal.codded ".
				" ORDER BY sno_dedicacion.codded,sno_tipopersonal.codtipper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Programaci�n Reporte M�TODO->uf_insert_programacionreporte0711 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ls_codigo="";
			$this->io_sql->begin_transaction();
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codded=$row["codded"];
				$ls_codtipper=$row["codtipper"];
				if($ls_codigo!=$ls_codded)
				{
					$ls_codigo=$ls_codded;
					$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
							"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
							"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
							"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','0000',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,".
							"			  0,0,0,0,0,0,0,0,0,0,0,0)";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Programaci�n Reporte M�TODO->uf_insert_programacionreporte0711 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
					}
					
				}
				$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
						"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
						"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
						"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','".$ls_codtipper."',0,0,0,0,0,0,0,".
						"			  0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Programaci�n Reporte M�TODO->uf_insert_programacionreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
				}
				
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{	
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_programacionreporte0711
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_programacionreporte0712($as_reporte)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_programacionreporte0712
		//		   Access: public (tepuy_snorh_p_programacionreporte)
		//	    Arguments: as_reporte  // c�digo del Reporte
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar � False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos las clasificaciones del la programaci�n de reporte
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 22/06/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_ded=1;($li_ded<=3)&&($lb_valido);$li_ded++)
		{
			$ls_codded=str_pad($li_ded,3,"0",0);
			$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
					"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
					"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
					"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','0000',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,".
					"			  0,0,0,0,0,0,0,0,0,0,0,0)";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Programaci�n Reporte M�TODO->uf_insert_programacionreporte0712 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
			}
			
			for($li_tip=1;($li_tip<=3)&&($lb_valido);$li_tip++)
			{
				$ls_codtipper=str_pad($li_tip,4,"0",0);
				$ls_sql="INSERT INTO sno_programacionreporte(codemp,codrep,codded,codtipper,numcar,totasi,dismonasi,monene,monfeb,".
						"			 monmar,monabr,monmay,monjun,monjul,monago,monsep,monoct,monnov,mondic,carene,carfeb,carmar,".
						"			 carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic)".
						"     VALUES ('".$this->ls_codemp."','".$as_reporte."','".$ls_codded."','".$ls_codtipper."',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,".
						"			  0,0,0,0,0,0,0,0,0,0,0,0)";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Programaci�n Reporte M�TODO->uf_insert_programacionreporte0712 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
				}
				
			}
		}
		if($lb_valido)
		{	
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_insert_programacionreporte0712
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_programacionreporte($as_reporte,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_programacionreporte
		//		   Access: public (tepuy_snorh_p_programacionreporte)
		//	    Arguments: as_reporte  // c�digo del Reporte
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar � False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos las clasificaciones del la programaci�n de reporte
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 22/06/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codrep, codded, codtipper, numcar, totasi, dismonasi, monene, monfeb, monmar, monabr, monmay, ".
				"		monjun, monjul, monago, monsep, monoct, monnov, mondic, carene, carfeb, carmar, carabr, carmay, carjun, ".
				"		carjul, carago, carsep, caroct, carnov, cardic, ".
				"		(SELECT desded FROM sno_dedicacion ".
				"		  WHERE sno_programacionreporte.codemp = sno_dedicacion.codemp ".
				"			AND sno_programacionreporte.codded = sno_dedicacion.codded) as desded,".
				"		(SELECT destipper FROM sno_tipopersonal ".
				"		  WHERE sno_programacionreporte.codemp = sno_tipopersonal.codemp ".
				"			AND sno_programacionreporte.codded = sno_tipopersonal.codded ".
				"			AND sno_programacionreporte.codtipper = sno_tipopersonal.codtipper) as destipper ".
				"  FROM sno_programacionreporte ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codrep='".$as_reporte."' ".
				" ORDER BY sno_programacionreporte.codded,sno_programacionreporte.codtipper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Programaci�n Reporte M�TODO->uf_load_programacionreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			$ls_codigo="";
			$ls_denominacion="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codded=$row["codded"];
				$ls_desded=$row["desded"];
				$ls_codtipper=$row["codtipper"];
				$ls_destipper=$row["destipper"];
				if($as_reporte=="0712")
				{
					switch($ls_codded)
					{
						case "001":
							$ls_desded="DOCENTE";
							break;
						case "002":
							$ls_desded="ADMINISTRATIVO";
							break;
						case "003":
							$ls_desded="OBRERO";
							break;
					}
					switch($ls_codtipper)
					{
						case "0001":
							$ls_destipper="JUBILADO";
							break;
						case "0002":
							$ls_destipper="PENSIONADO";
							break;
						case "0003":
							$ls_destipper="ASIGNACI�N A SOBREVIVIENTE";
							break;
					}
				}
				$li_numcar=$row["numcar"];
				$li_totasi=$this->io_fun_nomina->uf_formatonumerico($row["totasi"]);
				$li_dismonasi=$row["dismonasi"];
				$ls_distrbucionauto="";
				$ls_distrbucionmanu="";
				if($li_dismonasi==0)// Autom�tica
				{
					$ls_distrbucionauto="Selected";
				}
				else
				{
					$ls_distrbucionmanu="Selected";
				}
				$li_monene=$this->io_fun_nomina->uf_formatonumerico($row["monene"]);
				$li_monfeb=$this->io_fun_nomina->uf_formatonumerico($row["monfeb"]);
				$li_monmar=$this->io_fun_nomina->uf_formatonumerico($row["monmar"]);
				$li_monabr=$this->io_fun_nomina->uf_formatonumerico($row["monabr"]);
				$li_monmay=$this->io_fun_nomina->uf_formatonumerico($row["monmay"]);
				$li_monjun=$this->io_fun_nomina->uf_formatonumerico($row["monjun"]);
				$li_monjul=$this->io_fun_nomina->uf_formatonumerico($row["monjul"]);
				$li_monago=$this->io_fun_nomina->uf_formatonumerico($row["monago"]);
				$li_monsep=$this->io_fun_nomina->uf_formatonumerico($row["monsep"]);
				$li_monoct=$this->io_fun_nomina->uf_formatonumerico($row["monoct"]);
				$li_monnov=$this->io_fun_nomina->uf_formatonumerico($row["monnov"]);
				$li_mondic=$this->io_fun_nomina->uf_formatonumerico($row["mondic"]);
				$li_carene=$row["carene"];
				$li_carfeb=$row["carfeb"];
				$li_carmar=$row["carmar"];
				$li_carabr=$row["carabr"];
				$li_carmay=$row["carmay"];
				$li_carjun=$row["carjun"];
				$li_carjul=$row["carjul"];
				$li_carago=$row["carago"];
				$li_carsep=$row["carsep"];
				$li_caroct=$row["caroct"];
				$li_carnov=$row["carnov"];
				$li_cardic=$row["cardic"];
				if($ls_codigo!=$ls_codded)
				{
					$ls_codigo=$ls_codded;
					$ls_denominacion=$ls_desded;
					$ao_object[$ai_totrows][1]="<input name=txtcodigo".$ai_totrows." type=text id=txtcodigo".$ai_totrows." class=formato-azul size=4 maxlength=4 style='text-align:center' value='".$ls_codigo."' readonly><input name=txtcodded".$ai_totrows." type=hidden id=txtcodded".$ai_totrows." value='".$ls_codded."'><input name=txtcodtipper".$ai_totrows." type=hidden id=txtcodtipper".$ai_totrows." value='0000'>";
					$ao_object[$ai_totrows][2]="<input name=txtdescripcion".$ai_totrows." type=text id=txtdescripcion".$ai_totrows." class=formato-azul size=45 maxlength=45 style='text-align:center' value='".$ls_denominacion."' readonly>";
					$ao_object[$ai_totrows][3]="<input name=txtnumcar".$ai_totrows." type=text id=txtnumcar".$ai_totrows." class=formato-azul size=10 maxlength=10 style='text-align:right' onKeyUp='javascript: ue_validarnumero(this);' value='".$li_numcar."' readonly>";
					$ao_object[$ai_totrows][4]="<input name=txttotasi".$ai_totrows." type=text id=txttotasi".$ai_totrows." class=formato-azul size=20 maxlength=20 style='text-align:right' onKeyPress=return(ue_formatonumero(this,'.',',',event)); value='".$li_totasi."' readonly>";
					$ao_object[$ai_totrows][5]="<input name=cmbdismonasi".$ai_totrows." type=text id=cmbdismonasi".$ai_totrows." class=formato-azul size=12 maxlength=12 readonly>";
					$ao_object[$ai_totrows][6]="<div align='center'><a href=javascript:uf_abrir_meses(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a></div>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonene".$ai_totrows." type=hidden id=txtmonene".$ai_totrows." value='".$li_monene."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonfeb".$ai_totrows." type=hidden id=txtmonfeb".$ai_totrows." value='".$li_monfeb."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonmar".$ai_totrows." type=hidden id=txtmonmar".$ai_totrows." value='".$li_monmar."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonabr".$ai_totrows." type=hidden id=txtmonabr".$ai_totrows." value='".$li_monabr."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonmay".$ai_totrows." type=hidden id=txtmonmay".$ai_totrows." value='".$li_monmay."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonjun".$ai_totrows." type=hidden id=txtmonjun".$ai_totrows." value='".$li_monjun."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonjul".$ai_totrows." type=hidden id=txtmonjul".$ai_totrows." value='".$li_monjul."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonago".$ai_totrows." type=hidden id=txtmonago".$ai_totrows." value='".$li_monago."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonsep".$ai_totrows." type=hidden id=txtmonsep".$ai_totrows." value='".$li_monsep."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonoct".$ai_totrows." type=hidden id=txtmonoct".$ai_totrows." value='".$li_monoct."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonnov".$ai_totrows." type=hidden id=txtmonnov".$ai_totrows." value='".$li_monnov."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmondic".$ai_totrows." type=hidden id=txtmondic".$ai_totrows." value='".$li_mondic."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarene".$ai_totrows." type=hidden id=txtcarene".$ai_totrows." value='".$li_carene."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarfeb".$ai_totrows." type=hidden id=txtcarfeb".$ai_totrows." value='".$li_carfeb."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarmar".$ai_totrows." type=hidden id=txtcarmar".$ai_totrows." value='".$li_carmar."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarabr".$ai_totrows." type=hidden id=txtcarabr".$ai_totrows." value='".$li_carabr."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarmay".$ai_totrows." type=hidden id=txtcarmay".$ai_totrows." value='".$li_carmay."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarjun".$ai_totrows." type=hidden id=txtcarjun".$ai_totrows." value='".$li_carjun."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarjul".$ai_totrows." type=hidden id=txtcarjul".$ai_totrows." value='".$li_carjul."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarago".$ai_totrows." type=hidden id=txtcarago".$ai_totrows." value='".$li_carago."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarsep".$ai_totrows." type=hidden id=txtcarsep".$ai_totrows." value='".$li_carsep."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcaroct".$ai_totrows." type=hidden id=txtcaroct".$ai_totrows." value='".$li_caroct."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarnov".$ai_totrows." type=hidden id=txtcarnov".$ai_totrows." value='".$li_carnov."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcardic".$ai_totrows." type=hidden id=txtcardic".$ai_totrows." value='".$li_cardic."'>";
				}
				else
				{
					$ao_object[$ai_totrows][1]="<input name=txtcodigo".$ai_totrows." type=text id=txtcodigo".$ai_totrows." class=sin-borde size=4 maxlength=4 style='text-align:center' value='".$ls_codtipper."' readonly><input name=txtcodded".$ai_totrows." type=hidden id=txtcodded".$ai_totrows." value='".$ls_codded."'><input name=txtcodtipper".$ai_totrows." type=hidden id=txtcodtipper".$ai_totrows." value='".$ls_codtipper."'>";
					$ao_object[$ai_totrows][2]="<input name=txtdescripcion".$ai_totrows." type=text id=txtdescripcion".$ai_totrows." class=sin-borde size=45 maxlength=45 style='text-align:center' value='".$ls_destipper."' readonly>";
					$ao_object[$ai_totrows][3]="<input name=txtnumcar".$ai_totrows." type=text id=txtnumcar".$ai_totrows." class=sin-borde size=10 maxlength=10 style='text-align:right' onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_actualizar_cargos(".$ls_codded.");' value='".$li_numcar."'>";
					$ao_object[$ai_totrows][4]="<input name=txttotasi".$ai_totrows." type=text id=txttotasi".$ai_totrows." class=sin-borde size=20 maxlength=20 style='text-align:right' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur='javascript: ue_actualizar_asignacion(".$ls_codded.");' onChange='javascript: ue_actualizar_meses(".$ai_totrows.");' value='".$li_totasi."'>";
					$ao_object[$ai_totrows][5]="<select name=cmbdismonasi".$ai_totrows." id=cmbdismonasi".$ai_totrows." onChange='javascript: ue_actualizar_meses(".$ai_totrows.");'><option value='0' ".$ls_distrbucionauto.">Autom�tica</option><option value='1' ".$ls_distrbucionmanu.">Manual</option></select>";
					$ao_object[$ai_totrows][6]="<div align='center'><a href=javascript:uf_abrir_meses(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a></div>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonene".$ai_totrows." type=hidden id=txtmonene".$ai_totrows." value='".$li_monene."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonfeb".$ai_totrows." type=hidden id=txtmonfeb".$ai_totrows." value='".$li_monfeb."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonmar".$ai_totrows." type=hidden id=txtmonmar".$ai_totrows." value='".$li_monmar."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonabr".$ai_totrows." type=hidden id=txtmonabr".$ai_totrows." value='".$li_monabr."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonmay".$ai_totrows." type=hidden id=txtmonmay".$ai_totrows." value='".$li_monmay."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonjun".$ai_totrows." type=hidden id=txtmonjun".$ai_totrows." value='".$li_monjun."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonjul".$ai_totrows." type=hidden id=txtmonjul".$ai_totrows." value='".$li_monjul."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonago".$ai_totrows." type=hidden id=txtmonago".$ai_totrows." value='".$li_monago."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonsep".$ai_totrows." type=hidden id=txtmonsep".$ai_totrows." value='".$li_monsep."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonoct".$ai_totrows." type=hidden id=txtmonoct".$ai_totrows." value='".$li_monoct."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmonnov".$ai_totrows." type=hidden id=txtmonnov".$ai_totrows." value='".$li_monnov."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtmondic".$ai_totrows." type=hidden id=txtmondic".$ai_totrows." value='".$li_mondic."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarene".$ai_totrows." type=hidden id=txtcarene".$ai_totrows." value='".$li_carene."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarfeb".$ai_totrows." type=hidden id=txtcarfeb".$ai_totrows." value='".$li_carfeb."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarmar".$ai_totrows." type=hidden id=txtcarmar".$ai_totrows." value='".$li_carmar."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarabr".$ai_totrows." type=hidden id=txtcarabr".$ai_totrows." value='".$li_carabr."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarmay".$ai_totrows." type=hidden id=txtcarmay".$ai_totrows." value='".$li_carmay."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarjun".$ai_totrows." type=hidden id=txtcarjun".$ai_totrows." value='".$li_carjun."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarjul".$ai_totrows." type=hidden id=txtcarjul".$ai_totrows." value='".$li_carjul."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarago".$ai_totrows." type=hidden id=txtcarago".$ai_totrows." value='".$li_carago."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarsep".$ai_totrows." type=hidden id=txtcarsep".$ai_totrows." value='".$li_carsep."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcaroct".$ai_totrows." type=hidden id=txtcaroct".$ai_totrows." value='".$li_caroct."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcarnov".$ai_totrows." type=hidden id=txtcarnov".$ai_totrows." value='".$li_carnov."'>";
					$ao_object[$ai_totrows][6]=$ao_object[$ai_totrows][6]."<input name=txtcardic".$ai_totrows." type=hidden id=txtcardic".$ai_totrows." value='".$li_cardic."'>";
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_programacionreporte
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_programacionreporte($as_codrep,$as_codded,$as_codtipper,$ai_numcar,$ai_totasi,$ai_dismonasi,$ai_monene,
										   $ai_monfeb,$ai_monmar,$ai_monabr,$ai_monmay,$ai_monjun,$ai_monjul,$ai_monago,
										   $ai_monsep,$ai_monoct,$ai_monnov,$ai_mondic,$ai_carene,$ai_carfeb,$ai_carmar,$ai_carabr,
										   $ai_carmay,$ai_carjun,$ai_carjul,$ai_carago,$ai_carsep,$ai_caroct,$ai_carnov,$ai_cardic,										   
										   $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_programacionreporte
		//		   Access: private
		//	    Arguments: as_codrep  // c�digo del reporte
		//				   as_codded  // C�digo de Dedicaci�n
		//				   as_codtipper  // C�digo de Tipo de Personal
		//				   ai_numcar  // N�mero de cargos
		//				   ai_totasi  // total de asignaci�n
		//				   ai_dismonasi  // Distribuci�n
		//				   ai_monene  // Monto enero
		//				   ai_monfeb  // Monto Febrero
		//				   ai_monmar  // Monto Marzo
		//				   ai_monabr  // Monto Abril
		//				   ai_monmay  // Monto Mayo
		//				   ai_monjun  // Monto Junio
		//				   ai_monjul  // Monto Julio
		//				   ai_monago  // Monto Agosto
		//				   ai_monsep  // Monto Septiembre
		//				   ai_monoct  // Monto Octubre
		//				   ai_monnov  // Monto Noviembre
		//				   ai_mondic  // Monto Diciembre
		//				   ai_carene  // total cargo enero
		//				   ai_carfeb  // total cargo Febrero
		//				   ai_carmar  // total cargo Marzo
		//				   ai_carabr  // total cargo Abril
		//				   ai_carmay  // total cargo Mayo
		//				   ai_carjun  // total cargo Junio
		//				   ai_carjul  // total cargo Julio
		//				   ai_carago  // total cargo Agosto
		//				   ai_carsep  // total cargo Septiembre
		//				   ai_caroct  // total cargo Octubre
		//				   ai_carnov  // total cargo Noviembre
		//				   ai_cardic  // Monto Diciembre
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update � False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_programaci�nreporte
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 23/06/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_programacionreporte ".
				"   SET numcar = ".$ai_numcar.", ".
				"		totasi = ".$ai_totasi.", ".
				"		dismonasi = ".$ai_dismonasi.", ".
				"		monene = ".$ai_monene.", ".
				"		monfeb = ".$ai_monfeb.", ".
				"		monmar = ".$ai_monmar.", ".
				"		monabr = ".$ai_monabr.", ".
				"		monmay = ".$ai_monmay.", ".
				"		monjun = ".$ai_monjun.", ".
				"		monjul = ".$ai_monjul.", ".
				"		monago = ".$ai_monago.", ".
				"		monsep = ".$ai_monsep.", ".
				"		monoct = ".$ai_monoct.", ".
				"		monnov = ".$ai_monnov.", ".
				"		mondic = ".$ai_mondic.", ".
				"		carene = ".$ai_carene.", ".
				"		carfeb = ".$ai_carfeb.", ".
				"		carmar = ".$ai_carmar.", ".
				"		carabr = ".$ai_carabr.", ".
				"		carmay = ".$ai_carmay.", ".
				"		carjun = ".$ai_carjun.", ".
				"		carjul = ".$ai_carjul.", ".
				"		carago = ".$ai_carago.", ".
				"		carsep = ".$ai_carsep.", ".
				"		caroct = ".$ai_caroct.", ".
				"		carnov = ".$ai_carnov.", ".
				"		cardic = ".$ai_cardic." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codrep='".$as_codrep."'".
				"   AND codded='".$as_codded."'".
				"   AND codtipper='".$as_codtipper."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Programaci�n Reporte M�TODO->uf_update_programacionreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		} 		
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualiz� la Programaci�n de Reporte Reporte ".$as_codrep." Dedicaci�n ".$as_codded." Tipo Personal".$as_codtipper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			
		}
		return $lb_valido;
	}// end function uf_update_programacionreporte
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_programacionreporte($as_codrep,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_programacionreporte
		//		   Access: private
		//	    Arguments: as_codrep  // c�digo del reporte
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update � False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_programaci�nreporte
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 05/12/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_programacionreporte ".
				"   SET numcar = 0, ".
				"		totasi = 0, ".
				"		dismonasi = 0, ".
				"		monene = 0, ".
				"		monfeb = 0, ".
				"		monmar = 0, ".
				"		monabr = 0, ".
				"		monmay = 0, ".
				"		monjun = 0, ".
				"		monjul = 0, ".
				"		monago = 0, ".
				"		monsep = 0, ".
				"		monoct = 0, ".
				"		monnov = 0, ".
				"		mondic = 0, ".
				"		carene = 0, ".
				"		carfeb = 0, ".
				"		carmar = 0, ".
				"		carabr = 0, ".
				"		carmay = 0, ".
				"		carjun = 0, ".
				"		carjul = 0, ".
				"		carago = 0, ".
				"		carsep = 0, ".
				"		caroct = 0, ".
				"		carnov = 0, ".
				"		cardic = 0 ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codrep='".$as_codrep."'";
       	$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Programaci�n Reporte M�TODO->uf_delete_programacionreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		} 		
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Elimin� la Programaci�n de Reporte Reporte ".$as_codrep;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			
			if($lb_valido)
			{
				$this->io_mensajes->message("La Programaci�n de Reporte Fu� Eliminada.");
				$this->io_sql->commit();
			}
			else
			{
				$this->io_mensajes->message("Ocurrio un error al Eliminar la Programaci�n de Reporte.");
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_delete_programacionreporte
	//-----------------------------------------------------------------------------------------------------------------------------------

	
}
?>