<?php 
class tepuy_cxp_c_ncnd
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_id_process;
	var $ls_codemp;
	var $io_dscuentas;
	var $io_fun_cxp;
	function tepuy_cxp_c_ncnd($as_path)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_cxp_c_ncnd
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Nelson Barraez
		//  Fecha Creacin: 06/04/2007 								Fecha Ultima Modificacin : 03/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once($as_path."shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once($as_path."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once($as_path."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($as_path."shared/class_folder/tepuy_c_seguridad.php");
		$this->io_seguridad= new tepuy_c_seguridad();
	    require_once($as_path."shared/class_folder/class_fecha.php");		
		$this->io_fecha= new class_fecha();
		require_once($as_path."shared/class_folder/class_generar_id_process.php");
		$this->io_id_process=new class_generar_id_process();		
		require_once($as_path."shared/class_folder/class_datastore.php");		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->io_fun_cxp=new class_funciones_cxp();
		require_once($as_path."shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new tepuy_c_reconvertir_monedabsf();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (tepuy_cxp_p_ncnd.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing.Nelson Barraez
		//  Fecha Creacin: 06/04/2007								Fecha ltima Modificacin : 03/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fecha);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_guardar($la_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (tepuy_cxp_p_ncnd.php)
		//	  Description: Funcion que procesa los datos relacionados a las notas de debito o credito
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacion: 28/05/2007								Fecha Ultima Modificacion : 02/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_numncnd   = trim($this->io_fun_cxp->uf_obtenervalor("txtnumncnd",""));
		$ls_numord    = $this->io_fun_cxp->uf_obtenervalor("txtnumord","");
	    $ls_tipproben = $this->io_fun_cxp->uf_obtenervalor("tipproben","");
		$ls_codproben = $this->io_fun_cxp->uf_obtenervalor("txtcodproben","");
		$ls_numrecdoc = $this->io_fun_cxp->uf_obtenervalor("txtnumrecdoc","");
		$ls_tipdoc    = $this->io_fun_cxp->uf_obtenervalor("txttipdoc","");
		$ls_connota   = $this->io_fun_cxp->uf_obtenervalor("txtconnota","");
		$ld_fecha     = $this->io_fun_cxp->uf_obtenervalor("txtfecregsol","");
		$li_numrowspre= $this->io_fun_cxp->uf_obtenervalor("numrowsprenota",0);
		$li_numrowscon= $this->io_fun_cxp->uf_obtenervalor("numrowsconnota",0);
		$ls_tiponota  = $this->io_fun_cxp->uf_obtenervalor("tiponota","");
		$ldec_monto   = $this->io_fun_cxp->uf_obtenervalor("txtmonto","");
		$ls_existe    = $this->io_fun_cxp->uf_obtenervalor("existe","");//Variable que controla la operacion en la pantalla de Nota de Debito/Credito
		$ls_estsol    = $this->io_fun_cxp->uf_obtenervalor("txtestsol","");
		$ldec_monto   = str_replace(".","",$ldec_monto);
		$ldec_monto   = str_replace(",",".",$ldec_monto);
		$this->io_sql->begin_transaction();
		if(!$this->uf_existe_cabecera($ls_numncnd,$ls_numord,$ls_tipproben,$ls_codproben,$ls_numrecdoc,$ls_tipdoc,$ls_tiponota))
		{
			$lb_valido=$this->uf_guardar_cabecera($ls_numncnd,$ls_numord,$ls_tipproben,$ls_codproben,$ls_numrecdoc,$ls_tipdoc,$ls_connota,$ld_fecha,$ls_tiponota,$ldec_monto,$ls_estsol,$la_seguridad);
			if($lb_valido)			
			{
				$lb_valido=$this->uf_guardar_detalle($ls_numncnd,$ls_numord,$ls_tipproben,$ls_codproben,$ls_numrecdoc,$ls_tipdoc,$ls_tiponota,$li_numrowspre,$li_numrowscon,$lb_existe=false,$la_seguridad);
			}
		}
		elseif($ls_existe=='TRUE')
		{
			$lb_valido=$this->uf_actualizar_cabecera($ls_numncnd,$ls_numord,$ls_tipproben,$ls_codproben,$ls_numrecdoc,$ls_tipdoc,$ls_connota,$ld_fecha,$ls_tiponota,$ldec_monto,$ls_estsol,$la_seguridad);
			$lb_valido=$this->uf_guardar_detalle($ls_numncnd,$ls_numord,$ls_tipproben,$ls_codproben,$ls_numrecdoc,$ls_tipdoc,$ls_tiponota,$li_numrowspre,$li_numrowscon,$lb_existe=true,$la_seguridad);
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("La Nota que intenta registrar ya existe");
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("La Nota fue Registrada");
			$this->io_sql->commit();
		}
		else
		{
			$this->io_sql->rollback();
		}	
		return $lb_valido;			
	}
	
	function uf_existe_cabecera($ls_numncnd,$ls_numord,$ls_tipproben,$ls_codproben,$ls_numrecdoc,$ls_tipdoc,$ls_tiponota)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_existe_cabecera
		//	  Description: Funcion que verifica si existe la nota de debito o credito asociada a la recepcion de documento y solicitud especificada
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacion: 02/06/2007								Fecha ltima Modificacin : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if($ls_tipproben=='P')
		{
			$ls_codpro=$ls_codproben;
			$ls_cedbene='----------';
		}
		else
		{
			$ls_codpro='----------';
			$ls_cedbene=$ls_codproben;
		}
		$ls_sql="SELECT * 
				 FROM cxp_sol_dc 
				 WHERE codemp='".$ls_codemp."'  AND numsol='".$ls_numord."' AND numrecdoc='".$ls_numrecdoc."' 
				 AND codtipdoc='".$ls_tipdoc."' AND ced_bene='".$ls_cedbene."' AND cod_pro='".$ls_codpro."' 
				 AND codope='".$ls_tiponota."'  AND numdc='".$ls_numncnd."'";
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_cxp_c_ncnd METODO->uf_existe_cabecera ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				return true;
			}
			else
			{
				return false;	
			}			
		}
	}
	
	function uf_guardar_cabecera($ls_numncnd,$ls_numord,$ls_tipproben,$ls_codproben,$ls_numrecdoc,$ls_tipdoc,$ls_connota,$ld_fecha,$ls_tiponota,$ldec_monto,$ls_estsol,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_cabecera
		//	  Description: Funcion que guarda los datos de la cabecera relacionados a las notas de debito o credito
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacion: 28/05/2007								Fecha ltima Modificacin : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if($ls_tipproben=='P')
		{
			$ls_codpro=$ls_codproben;
			$ls_cedbene='----------';
		}
		else
		{
			$ls_codpro='----------';
			$ls_cedbene=$ls_codproben;
		}
		$ls_sql="INSERT INTO cxp_sol_dc(codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, 
										codope, numdc,desope, fecope, 
										monto, estnotadc, estafe, estapr, codusuapr, fecaprnc)
				 VALUES('".$ls_codemp."','".$ls_numord."','".$ls_numrecdoc."','".$ls_tipdoc."','".$ls_cedbene."','".$ls_codpro."',
				 		'".$ls_tiponota."','".trim($ls_numncnd)."','".$ls_connota."','".$this->io_funciones->uf_convertirdatetobd($ld_fecha)."',
						'".$ldec_monto."','".$ls_estsol."','0','0','','1900-01-01')";						
		$li_rows=$this->io_sql->execute($ls_sql);
		if($li_rows===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_cxp_c_ncnd METODO->uf_guardar_cabacera ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Inserto la Nota ".$ls_numncnd." de tipo ".$ls_tiponota." Asociado a la solicitud ".$ls_numord." y a la recepcion ".$ls_numrecdoc;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////
			/*$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numord);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numrecdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_tipdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedbene);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_tiponota);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numncnd);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_sol_dc",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			*//////////////////////////////////     RECONVERSION MONETARIA      /////////////////////////////
			return true;
		}
	}
	
	function uf_actualizar_cabecera($ls_numncnd,$ls_numord,$ls_tipproben,$ls_codproben,$ls_numrecdoc,$ls_tipdoc,$ls_connota,$ld_fecha,$ls_tiponota,$ldec_monto,$ls_estsol,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_cabecera
		//	  Description: Funcion que guarda los datos de la cabecera relacionados a las notas de debito o credito
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacion: 28/05/2007								Fecha ltima Modificacin : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if($ls_tipproben=='P')
		{
			$ls_codpro=$ls_codproben;
			$ls_cedbene='----------';
		}
		else
		{
			$ls_codpro='----------';
			$ls_cedbene=$ls_codproben;
		}
		$ls_sql="UPDATE cxp_sol_dc SET desope='".$ls_connota."', monto='".$ldec_monto."' 
				 WHERE codemp='".$ls_codemp."'    AND numsol='".$ls_numord."' AND numrecdoc='".$ls_numrecdoc."'
				 AND   codtipdoc='".$ls_tipdoc."' AND ced_bene='".$ls_cedbene."' AND  cod_pro='".$ls_codpro."' 
				 AND   codope='".$ls_tiponota."'  AND numdc='".$ls_numncnd."'";
		$li_rows=$this->io_sql->execute($ls_sql);
		if($li_rows===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_cxp_c_ncnd METODO->uf_actualizar_cabacera ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizo la Nota ".$ls_numncnd." de tipo ".$ls_tiponota." Asociado a la solicitud ".$ls_numord." y a la recepcion ".$ls_numrecdoc;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			/////////////////////////////////    RECONVERSION MONETARIA       /////////////////////////////
			/*$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numord);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numrecdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_tipdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedbene);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_tiponota);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numncnd);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_sol_dc",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			*//////////////////////////////////     RECONVERSION MONETARIA      /////////////////////////////
			return true;
		}
	}
	
	
	function uf_guardar_detalle($ls_numncnd,$ls_numord,$ls_tipproben,$ls_codproben,$ls_numrecdoc,$ls_tipdoc,$ls_tiponota,$li_numrowspre,$li_numrowscon,$lb_existe,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_detalle
		//	  Description: Funcion que se encarga de registrar los detalles de la nota de debito o credito 
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacion: 02/06/2007								Fecha ltima Modificacin : 03/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];	
		$ls_modalidad=$_SESSION['la_empresa']['estmodest'];	
		if($ls_tipproben=='P')
		{
			$ls_codpro=$ls_codproben;
			$ls_cedbene='----------';
		}
		else
		{
			$ls_codpro='----------';
			$ls_cedbene=$ls_codproben;
		}
		for($li=1;$li<=$li_numrowspre;$li++)
		{
			$ls_cuentaspg = $this->io_fun_cxp->uf_obtenervalor("txtcuentaspgncnd".$li,"");
			$ls_codestpro = $this->io_fun_cxp->uf_obtenervalor("txtcodestproncnd".$li,"");
			$ldec_monto   = $this->io_fun_cxp->uf_obtenervalor("txtmontoncnd".$li,"");
			$ldec_monto   = str_replace(".","",$ldec_monto);
			$ldec_monto   = str_replace(",",".",$ldec_monto);
			$ldec_monto   = abs($ldec_monto);
			switch($ls_modalidad)
			{
				case "1": // Modalidad por Proyecto
					$ls_codestpro=$ls_codestpro."0000";
					break;						
				case "2": // Modalidad por Programa
					$ls_codestpronew=$this->io_funciones->uf_cerosizquierda(substr($ls_codestpro,0,2),20);
					$ls_codestpronew=$ls_codestpronew.$this->io_funciones->uf_cerosizquierda(substr($ls_codestpro,3,2),6);
					$ls_codestpronew=$ls_codestpronew.$this->io_funciones->uf_cerosizquierda(substr($ls_codestpro,6,2),3);
					$ls_codestpronew=$ls_codestpronew.substr($ls_codestpro,9,2);
					$ls_codestpronew=$ls_codestpronew.substr($ls_codestpro,12,2);
					$ls_codestpro=$ls_codestpronew;
					break;
			}
			if($lb_existe && $li==1)
			{
				$ls_sql="DELETE FROM cxp_dc_spg 
						 WHERE codemp='".$ls_codemp."'    AND numsol='".$ls_numord."' AND numrecdoc='".$ls_numrecdoc."'
						 AND   codtipdoc='".$ls_tipdoc."' AND ced_bene='".$ls_cedbene."' AND  cod_pro='".$ls_codpro."' 
						 AND   codope='".$ls_tiponota."'  AND numdc='".$ls_numncnd."'";
				$li_rows=$this->io_sql->execute($ls_sql);
				if($li_rows===false)
				{
					$this->io_mensajes->message("CLASE->tepuy_cxp_c_ncnd METODO->uf_guardar_detalle ERROR-> ".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
				}
				else
				{
					$lb_valido=true;
				}	
			}
			if($lb_valido)
			{
				$ls_sql="INSERT INTO cxp_dc_spg(codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, codope, numdc, codestpro, spg_cuenta, monto)
						 VALUES('".$ls_codemp."','".$ls_numord."','".$ls_numrecdoc."','".$ls_tipdoc."','".$ls_cedbene."','".$ls_codpro."','".$ls_tiponota."','".trim($ls_numncnd)."','".$ls_codestpro."','".$ls_cuentaspg."','".$ldec_monto."')";
				$li_rows=$this->io_sql->execute($ls_sql);
				if($li_rows===false)
				{
					$this->io_mensajes->message("CLASE->tepuy_cxp_c_ncnd METODO->uf_guardar_detalle ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					return false;
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Registro el detalle presupuestario de la Nota ".$ls_numncnd." de tipo ".$ls_tiponota." Asociado a la solicitud ".$ls_numord." y a la recepcion ".$ls_numrecdoc." a la estructura ".$ls_codestpro." y cuenta ".$ls_cuentaspg;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					/////////////////////////////////     RECONVERSION MONETARIA      /////////////////////////////
					/*$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
					$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);
		
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numord);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numrecdoc);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_tipdoc);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedbene);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_tiponota);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdc");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numncnd);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cuentaspg);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_dc_spg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
					*/////////////////////////////////     RECONVERSION MONETARIA      /////////////////////////////
				}		
			}
		}
		for($la=1;$la<=$li_numrowscon;$la++)
		{
			$ls_cuentascg = $this->io_fun_cxp->uf_obtenervalor("txtscgcuentancnd".$la,"");			
			$ldec_mondeb  = $this->io_fun_cxp->uf_obtenervalor("txtdebencnd".$la,"");
			$ldec_monhab  = $this->io_fun_cxp->uf_obtenervalor("txthaberncnd".$la,"");
			$ldec_mondeb   = str_replace(".","",$ldec_mondeb);
			$ldec_mondeb   = str_replace(",",".",$ldec_mondeb);
			$ldec_monhab   = str_replace(".","",$ldec_monhab);
			$ldec_monhab   = str_replace(",",".",$ldec_monhab);
			if($ldec_mondeb==0)
			{
				$ls_debhab='H';
				$ldec_monto=$ldec_monhab;
			}
			else
			{
				$ls_debhab='D';
				$ldec_monto=$ldec_mondeb;
			}
			if($lb_existe && $la==1)
			{
				$ls_sql="DELETE FROM cxp_dc_scg 
						 WHERE codemp='".$ls_codemp."'    AND numsol='".$ls_numord."' AND numrecdoc='".$ls_numrecdoc."'
						 AND   codtipdoc='".$ls_tipdoc."' AND ced_bene='".$ls_cedbene."' AND  cod_pro='".$ls_codpro."' 
						 AND   codope='".$ls_tiponota."'  AND numdc='".$ls_numncnd."'";
				$li_rows=$this->io_sql->execute($ls_sql);
				if($li_rows===false)
				{
					$this->io_mensajes->message("CLASE->tepuy_cxp_c_ncnd METODO->uf_guardar_detalle ERROR-> ".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
				}
				else
				{
					$lb_valido=true;
				}	
			}
			if($lb_valido)
			{
				$ls_sql="INSERT INTO cxp_dc_scg(codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, codope, numdc, debhab, sc_cuenta, monto,estgenasi)
						 VALUES('".$ls_codemp."','".$ls_numord."','".$ls_numrecdoc."','".$ls_tipdoc."','".$ls_cedbene."','".$ls_codpro."','".$ls_tiponota."','".$ls_numncnd."','".$ls_debhab."','".$ls_cuentascg."','".$ldec_monto."','".$la."')";
				$li_rows=$this->io_sql->execute($ls_sql);
				if($li_rows===false)
				{
					$this->io_mensajes->message("CLASE->tepuy_cxp_c_ncnd METODO->uf_guardar_detalle ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					return false;
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Registro el detalle contable de la Nota ".$ls_numncnd." de tipo ".$ls_tiponota." Asociado a la solicitud ".$ls_numord." y a la recepcion ".$ls_numrecdoc." a la cuante ".$ls_cuentascg." con operacion ".$ls_debhab." y un monto de ".$ldec_monto;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					/////////////////////////////////     RECONVERSION MONETARIA      /////////////////////////////
					/**$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
					$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);
		
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numord);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numrecdoc);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_tipdoc);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedbene);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_tiponota);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdc");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numncnd);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cuentascg);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_dc_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
					*//////////////////////////////////     RECONVERSION MONETARIA      /////////////////////////////
				}
			}		
		}
		return $lb_valido;
	}
	
	function uf_delete_nota($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_nota
		//		   Access: public (tepuy_cxp_p_ncnd.php)
		//	  Description: Funcion que elimina por completo la nota de debito 0 credito
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacion: 02/06/2007								Fecha Ultima Modificacion : 03/06/2007	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sql->begin_transaction();
		$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
		$ls_numncnd   = $this->io_fun_cxp->uf_obtenervalor("txtnumncnd","");
		$ls_numord    = $this->io_fun_cxp->uf_obtenervalor("txtnumord","");
	    $ls_tipproben = $this->io_fun_cxp->uf_obtenervalor("tipproben","");
		$ls_codproben = $this->io_fun_cxp->uf_obtenervalor("txtcodproben","");
		$ls_numrecdoc = $this->io_fun_cxp->uf_obtenervalor("txtnumrecdoc","");
		$ls_tipdoc    = $this->io_fun_cxp->uf_obtenervalor("txttipdoc","");
		$ld_fecha     = $this->io_fun_cxp->uf_obtenervalor("txtfecregsol","");
		$ls_tiponota  = $this->io_fun_cxp->uf_obtenervalor("tiponota","");
		if($ls_tipproben=='P')
		{
			$ls_codpro=$ls_codproben;
			$ls_cedbene='----------';
		}
		else
		{
			$ls_codpro='----------';
			$ls_cedbene=$ls_codproben;
		}
		$ls_sql="DELETE FROM cxp_dc_spg 
				 WHERE codemp='".$ls_codemp."'    AND numsol='".$ls_numord."' AND numrecdoc='".$ls_numrecdoc."'
				 AND   codtipdoc='".$ls_tipdoc."' AND ced_bene='".$ls_cedbene."' AND  cod_pro='".$ls_codpro."' 
				 AND   codope='".$ls_tiponota."'  AND numdc='".$ls_numncnd."'";
		$li_rows=$this->io_sql->execute($ls_sql);
		if($li_rows===false)
		{
			$this->io_mensajes->message("CLASE->tepuy_cxp_c_ncnd METODO->uf_delete_nota ERROR-> ".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		if($lb_valido)	
		{
			$ls_sql="DELETE FROM cxp_dc_scg 
					 WHERE codemp='".$ls_codemp."'    AND numsol='".$ls_numord."' AND numrecdoc='".$ls_numrecdoc."'
					 AND   codtipdoc='".$ls_tipdoc."' AND ced_bene='".$ls_cedbene."' AND  cod_pro='".$ls_codpro."' 
					 AND   codope='".$ls_tiponota."'  AND numdc='".$ls_numncnd."'";
			$li_rows=$this->io_sql->execute($ls_sql);
			if($li_rows===false)
			{
				$this->io_mensajes->message("CLASE->tepuy_cxp_c_ncnd METODO->uf_delete_nota ERROR-> ".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				$lb_valido=true;
			}	
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM cxp_sol_dc 
					 WHERE codemp='".$ls_codemp."'    AND numsol='".$ls_numord."' AND numrecdoc='".$ls_numrecdoc."'
					 AND   codtipdoc='".$ls_tipdoc."' AND ced_bene='".$ls_cedbene."' AND  cod_pro='".$ls_codpro."' 
					 AND   codope='".$ls_tiponota."'  AND numdc='".$ls_numncnd."'";
			$li_rows=$this->io_sql->execute($ls_sql);
			if($li_rows===false)
			{
				$this->io_mensajes->message("CLASE->tepuy_cxp_c_ncnd METODO->uf_delete_nota ERROR-> ".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				$lb_valido=true;
			}	
		}
		if($lb_valido)
		{	
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Elimino la Nota ".$ls_numncnd." de tipo ".$ls_tiponota." Asociado a la solicitud ".$ls_numord." y a la recepcion ".$ls_numrecdoc;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			$this->io_mensajes->message("La Nota fue Eliminada");
			$this->io_sql->commit();
		}
		else
		{
			$this->io_sql->rollback();
		}	
		return $lb_valido;
	}
}
?>