<?php
class tepuy_sno_c_cierre_periodo
{
	var $io_sql;
	var $io_mensajes;
	var $io_seguridad;
	var $io_funciones;
	var $io_cierre_periodo2;
	var $io_cierre_periodo3;
	var $io_cierre_periodo4;
	var $io_vacacion;
	var $io_sno;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_sno_c_cierre_periodo()
	{	
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_sno_c_cierre_periodo
		//		   Access: public (tepuy_sno_p_manejoperiodo)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 15/02/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2006
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
   		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();				
   		require_once("tepuy_sno_c_cierre_periodo2.php");
		$this->io_cierre_periodo2=new tepuy_sno_c_cierre_periodo2();
		require_once("tepuy_sno_c_cierre_periodo3.php");
		$this->io_cierre_periodo3=new tepuy_sno_c_cierre_periodo3();
		require_once("tepuy_sno_c_cierre_periodo4.php");
		$this->io_cierre_periodo4=new tepuy_sno_c_cierre_periodo4();
		require_once("tepuy_sno_c_vacacion.php");
		$this->io_vacacion=new tepuy_sno_c_vacacion();
		require_once("tepuy_sno.php");
		$this->io_sno=new tepuy_sno();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		
	}// end function tepuy_sno_c_cierre_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (tepuy_sno_p_manejoperiodo)
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
		unset($this->io_cierre_periodo2);
		unset($this->io_cierre_periodo3);
		unset($this->io_cierre_periodo4);
		unset($this->io_vacacion);
		unset($this->io_sno);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
       
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_cierre_periodo($as_codperi,$adt_fecdesper,$adt_fechasper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_cierre_periodo 
		//	    Arguments: as_codperi_actual  //  codigo del periodo a cerrar
		//                 adt_fecdesper  //  fecha desde donde comienza el periodo
		//                 adt_fechasper  //  fecha hasta donde termina el periodo
		//                 aa_seguridad  //  arreglo de las variables de seguridad
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que se encarga de procesar el cierre del periodo 
	    //     Creado por: Ing. Juniors Fraga
	    // Fecha Creaci�n: 13/02/2006          
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ls_conpronom=$_SESSION["la_nomina"]["conpronom"];
	   	$lb_valido=false;
	  	$li_total=$this->uf_verificar_periodo($ls_peractnom);
	   	if($li_total>0)
	   	{
			$this->io_sql->begin_transaction();
			// GENERALMENTE PASA POR LA OPCION 0.
			if($ls_conpronom=="1") // contabilizaci�n por proyectos
			{
				$lb_valido=$this->io_cierre_periodo4->uf_procesar_contabilizacion_proyectos();
			}
			else
			{
				$lb_valido=$this->io_cierre_periodo4->uf_procesar_contabilizacion();
			}
			//return false;
			if($lb_valido)
			{
				$lb_valido=$this->uf_cerrar_periodo($as_codperi,$adt_fecdesper,$adt_fechasper);
			}
			if($lb_valido)
			{
				$ld_fecdes="";
				$ld_fechas="";
				$lb_valido=$this->uf_load_proximoperiodo($ls_peractnom,$ld_fecdes,$ld_fechas);
			}
			if($lb_valido)
			{
				$li_metodo_vac=$this->io_sno->uf_select_config("SNO","CONFIG","METODO_VACACIONES","0","C");
			}
			if(($lb_valido)&&($li_metodo_vac!="0"))
			{
				$lb_valido=$this->io_cierre_periodo2->uf_actualizar_personal_vacaciones($adt_fechasper,$as_codperi,$li_metodo_vac);  
				if($lb_valido)
				{
					$lb_valido=$this->io_cierre_periodo2->uf_reingreso_personal_vac($adt_fecdesper,$adt_fechasper,$as_codperi,$li_metodo_vac);  
				}
			}
			if($lb_valido)
			{
				$lb_valido=$this->io_vacacion->uf_procesar_porvencer($ld_fecdes,$ld_fechas,$aa_seguridad);
			}
			if($lb_valido)
			{
				$ls_periodo=str_pad((intval($ls_peractnom)+1),3,"0",0);
				if((trim($ld_fecdes)!="")&&(trim($ld_fechas)!=""))
				{
					$lb_valido=$this->uf_suspender_contratatados($ls_periodo,$ld_fecdes,$ld_fechas);
					if($lb_valido)
					{
						$lb_valido=$this->uf_load_contratatados_por_suspender($ls_periodo,$ld_fecdes,$ld_fechas);
					}					
				}
			}
			if($lb_valido)
			{
				$lb_valido=$this->io_sno->uf_crear_sessionnomina();
			}
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Cerro el per�odo ".$as_codperi." asociado a la n�mina ".$this->ls_codnom;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////				
			}
			if($lb_valido)
			{
				$this->io_sql->commit(); 
				$this->io_mensajes->message("El Cierre de Per�odo fue procesado.");
				$this->uf_eliminar_carpeta($ls_peractnom);
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_mensajes->message("Ocurrio un error al cerrar el per�odo.");
			}
			if($_SESSION["la_nomina"]["peractnom"]=="000")
			{
				print "<script language=JavaScript>";
				print "location.href='tepuywindow_blank.php'";
				print "</script>";		
			}
		}
		else
		{
			$this->io_mensajes->message("La nomina debe estar previamente calculada. Por favor Verifique.");
		}  
		 return  $lb_valido;    
	}// end function uf_procesar_cierre_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cerrar_periodo($as_codperi,$adt_fecdesper,$adt_fechasper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cerrar_periodo 
		//	    Arguments: as_codperi // codigo del periodo
		//                 adt_fechasper  // fecha del periodo hasta
		//                 adt_fechasper  //  fecha del periodo desde
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que procesa el cierre del periodo 
	    //     Creado por: Ing. Juniors Fraga
	    // Fecha Creaci�n: 09/02/2006     
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_perresnom=$_SESSION["la_nomina"]["perresnom"];
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	    $lb_valido=$this->io_cierre_periodo2->uf_acumular_conceptos($as_codperi);
		if($lb_valido)
		{
			$lb_valido=$this->uf_procesar_historico($as_codperi);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_cierre_periodo3->uf_actualizar_prestamo_cierre();
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_cierre_periodo3->uf_limpiar_constantes();  
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_cierre_periodo3->uf_limpiar_concepto();  
		}
		if($lb_valido)
		{
			//$lb_valido=$this->io_cierre_periodo3->uf_limpiar_proyectopersonal();  
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_cierre_periodo3->uf_actualizar_periodo($as_codperi,$ls_codperi_next,$ls_codperi_prev);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_cierre_periodo3->uf_limpiar_periodo($as_codperi,$ls_codperi_next);  
		}
		if(($lb_valido)&&($ls_codperi_prev))
		{		
			$lb_valido=$this->uf_restaurar_periodo($ls_codperi_next,$as_codperi);
		}
		return  $lb_valido;
	}// end function uf_cerrar_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_historico($as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_historico 
		//	    Arguments: as_codperi // codigo del periodo
		// 	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que procesas las tablas historicas 
	    //     Creado por: Ing. Juniors Fraga
	    // Fecha Creaci�n: 09/02/2006     
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->uf_registrar_nomina_en_historico($as_codperi,$lb_insert);
		if(($lb_valido)&&(!$lb_insert))
		{
		   $lb_valido=$this->uf_eliminar_periodo_historico($as_codperi);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->uf_insert_periodo_historico($as_codperi);
		}
		return  $lb_valido;
	}// end function uf_procesar_historico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_registrar_nomina_en_historico($as_codperi,&$lb_insert)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_registrar_nomina_en_historico 
		//	    Arguments: as_codperi // codigo del periodo
		//	    		   lb_insert // variable que me indica si ctualiz� la n�mina � la inserto
		//	      Returns: lb_valido true si es correcto el registro o false en caso contrario
		//	  Description: Funci�n que registra la nomina en la tablas historica sno_hnomina 
	    //     Creado por: Ing. Juniors Fraga
	    // Fecha Creaci�n: 09/02/2006     
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////
        $lb_insert=false;
		$lb_valido=true;
		$ls_sql="SELECT desnom,tippernom,despernom,anocurnom,fecininom,peractnom,numpernom,tipnom,subnom,racnom,adenom,espnom,".
				"		ctnom,ctmetnom,diabonvacnom,diareivacnom,diainivacnom,diatopvacnom,diaincvacnom,consulnom,descomnom,".
				"		codpronom,codbennom,conaponom,cueconnom,notdebnom,numvounom,recdocnom,tipdocnom,recdocapo,tipdocapo,".
				"		perresnom, conpernom, conpronom, titrepnom, codorgcestic, confidnom, recdocfid, tipdocfid, codbenfid, ".
				"		cueconfid, divcon ".
                "  FROM sno_nomina ".
                " WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
		  $lb_valido=false;
		  $this->io_mensajes->message("CLASE->Cierre Periodo M�TODO->uf_registrar_nomina_en_historico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_desnom=$row["desnom"];
				$ls_tippernom=$row["tippernom"];
				$ls_despernom=$row["despernom"];
				$ldt_anocurnom=$row["anocurnom"];
				$ldt_fecininom=$this->io_funciones->uf_formatovalidofecha($row["fecininom"]);
				$ls_peractnom=$row["peractnom"];
				$li_numpernom=$row["numpernom"];
				$li_tipnom=$row["tipnom"]; 
				$ls_subnom=$row["subnom"]; 
				$ls_racnom=$row["racnom"]; 
				$ls_adenom=$row["adenom"]; 
				$ls_espnom=$row["espnom"]; 
				$ls_ctnom=$row["ctnom"]; 
				$ls_ctmetnom=$row["ctmetnom"]; 
				$li_diabonvacnom=$row["diabonvacnom"]; 
				$li_diareivacnom=$row["diareivacnom"]; 
				$li_diainivacnom=$row["diainivacnom"]; 
				$li_diatopvacnom=$row["diatopvacnom"];  
				$li_diaincvacnom=$row["diaincvacnom"];  
				$ls_consulnom=$row["consulnom"];  
				$ls_descomnom=$row["descomnom"];  
				$ls_codpronom=$row["codpronom"];
				$ls_codbennom=$row["codbennom"];
				$ls_conaponom=$row["conaponom"];
				$ls_cueconnom=$row["cueconnom"];
				$ls_notdebnom=$row["notdebnom"];
				$ls_numvounom=$row["numvounom"];
				$ls_perresnom=$row["perresnom"];
				$ls_recdocnom=$row["recdocnom"];
				$ls_recdocapo=$row["recdocapo"];
				$ls_tipdocnom=$row["tipdocnom"];
				$ls_tipdocapo=$row["tipdocapo"];
				$ls_conpernom=$row["conpernom"];
				$ls_conpronom=$row["conpronom"];
				$ls_titrepnom=$row["titrepnom"];
				$ls_codorgcestic=$row["codorgcestic"];
				$ls_confidnom=$row["confidnom"];
				$ls_recdocfid=$row["recdocfid"];
				$ls_tipdocfid=$row["tipdocfid"];
				$ls_codbenfid=$row["codbenfid"];
				$ls_cueconfid=$row["cueconfid"];
				$ls_divcon=$row["divcon"];
			}
			$lb_existe=$this->io_cierre_periodo2->uf_select_hnomina($as_codperi);
			if(!$lb_existe)
			{
				$ls_sql=" INSERT INTO sno_hnomina(codemp,codnom,desnom,tippernom,despernom,anocurnom,fecininom,peractnom,numpernom, ".
					   "            tipnom,subnom,racnom,adenom,espnom,ctnom,ctmetnom,diabonvacnom,diareivacnom,diainivacnom, ".
					   "            diatopvacnom,diaincvacnom,consulnom,descomnom,codpronom,codbennom,conaponom,cueconnom, ".
					   "			notdebnom,numvounom,perresnom,recdocnom,recdocapo,tipdocnom,tipdocapo,conpernom,conpronom, ".
					   "			titrepnom, codorgcestic,confidnom,recdocfid,tipdocfid,codbenfid,cueconfid, divcon) ".
					   "     VALUES ('".$this->ls_codemp."','".$this->ls_codnom."','".$ls_desnom."','".$ls_tippernom."', ".
					   "             '".$ls_despernom."','".$ldt_anocurnom."','".$ldt_fecininom."','".$ls_peractnom."', ".
					   "             '".$li_numpernom."','".$li_tipnom."','".$ls_subnom."','".$ls_racnom."','".$ls_adenom."', ".
					   "             '".$ls_espnom."','".$ls_ctnom."','".$ls_ctmetnom."','".$li_diabonvacnom."','".$li_diareivacnom."', ".
					   "             '".$li_diainivacnom."','".$li_diatopvacnom."','".$li_diaincvacnom."','".$ls_consulnom."', ".
					   "             '".$ls_descomnom."','".$ls_codpronom."','".$ls_codbennom."','".$ls_conaponom."', ".
					   "             '".$ls_cueconnom."','".$ls_notdebnom."','".$ls_numvounom."','".$ls_perresnom."','".$ls_recdocnom."', ".
					   "			 '".$ls_recdocapo."','".$ls_tipdocnom."','".$ls_tipdocapo."','".$ls_conpernom."','".$ls_conpronom."', ".
					   "			 '".$ls_titrepnom."','".$ls_codorgcestic."','".$ls_confidnom."','".$ls_recdocfid."','".$ls_tipdocfid."',".
					   "			 '".$ls_codbenfid."','".$ls_cueconfid."','".$ls_divcon."') ";
				$lb_insert=true;    
			}
			else
			{
				$ls_sql= "UPDATE sno_hnomina  ".
					   "   SET desnom='".$ls_desnom."', ".
					   "       tippernom='".$ls_tippernom."', ".
					   "       despernom='".$ls_despernom."', ". 
					   "       fecininom='".$ldt_fecininom."', ".
					   "       peractnom='".$ls_peractnom."', ".
					   "       numpernom='".$li_numpernom."', ".
					   "       tipnom='".$li_tipnom."', ".
					   "       racnom='".$ls_racnom."', ".
					   "       adenom='".$ls_adenom."', ". 
					   "       espnom='".$ls_espnom."', ".
					   "       ctnom='".$ls_ctnom."', ".
					   "       ctmetnom='".$ls_ctmetnom."', ".
					   "       diabonvacnom='".$li_diabonvacnom."', ".
					   "       diareivacnom='".$li_diareivacnom."', ".
					   "       subnom='".$ls_subnom."', ".
					   "       diainivacnom='".$li_diainivacnom."', ".
					   "       diatopvacnom='".$li_diatopvacnom."', ".
					   "       diaincvacnom='".$li_diaincvacnom."', ".
					   "       consulnom='".$ls_consulnom."', ".
					   "       descomnom='".$ls_descomnom."', ".
					   "       codpronom='".$ls_codpronom."', ".
					   "       codbennom='".$ls_codbennom."', ".
					   "       conaponom='".$ls_conaponom."', ".
					   "       cueconnom='".$ls_cueconnom."', ".
					   "       notdebnom='".$ls_notdebnom."', ".
					   "       numvounom='".$ls_numvounom."', ".
					   "       recdocnom='".$ls_recdocnom."', ".
					   "       recdocapo='".$ls_recdocapo."', ".
					   "       tipdocnom='".$ls_tipdocnom."', ".
					   "       tipdocapo='".$ls_tipdocapo."', ".
					   "       perresnom='".$ls_perresnom."', ".
					   "       conpernom='".$ls_conpernom."', ".
					   "       conpronom='".$ls_conpronom."', ".
					   "	   titrepnom='".$ls_titrepnom."', ".
					   "       codorgcestic='".$ls_codorgcestic."', ".
					   "       confidnom='".$ls_confidnom."', ".
					   "       recdocfid='".$ls_recdocfid."', ".
					   "       tipdocfid='".$ls_tipdocfid."', ".
					   "       codbenfid='".$ls_codbenfid."', ".
					   "       cueconfid='".$ls_cueconfid."', ".
					   "       divcon='".$ls_divcon."' ".
					   " WHERE codemp='".$this->ls_codemp."' ".
					   "   AND codnom='".$this->ls_codnom."' ".
					   "   AND anocurnom='".$ldt_anocurnom."' ".
					   "   AND peractnom='".$as_codperi."' ";
			} 
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Cierre Periodo M�TODO->uf_registrar_nomina_en_historico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
		}
		return $lb_valido;
	}// end function uf_registrar_nomina_en_historico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_eliminar_periodo_historico($as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_eliminar_periodo_historico 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto los delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de todas las tablas historicas para proceder al cierre del mismo
	    //     Creado por: Ing. Juniors Fraga
	    // Fecha Creaci�n: 09/02/2006     
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $ldt_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
		$lb_valido=$this->io_cierre_periodo2->uf_delete_hsalida($ldt_anocurnom,$as_codperi);
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hprenomina($ldt_anocurnom,$as_codperi);
		} 
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hresumen($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hprestamoperiodo($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hprestamoamortizado($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hprestamos($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_htipoprestamo($ldt_anocurnom,$as_codperi);
		}
        if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hconstantepersonal($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hconstante($ldt_anocurnom,$as_codperi);
		} 
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hconceptovacacion($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hconceptopersonal($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hprimaconcepto($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hconcepto($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hvacacpersonal($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hproyectopersonal($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hpersonalnomina($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido)
		{
           $lb_valido=$this->io_cierre_periodo2->uf_delete_hasignacioncargo($ldt_anocurnom,$as_codperi);		
		}
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hcargo($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hunidadadmin($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hclasificacionobrero($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hproyecto($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido)
		{
			$lb_valido=$this->io_cierre_periodo2->uf_delete_sno_hprimagrado($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hgrado($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_htabla($ldt_anocurnom,$as_codperi);
		} 
        if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hperiodo($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hsubnomina($ldt_anocurnom,$as_codperi);
		} 
		return $lb_valido;
	}// end function uf_eliminar_periodo_historico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_periodo_historico($as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_periodo_historico 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si el insert se hizo correctamente o false en caso contrario
		//	  Description: Funci�n que procesa las tablas historicas 
	    //     Creado por: Ing. Juniors Fraga
	    // Fecha Creaci�n: 09/02/2006     
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		$ldt_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
		$lb_valido=$this->io_cierre_periodo2->uf_insert_hsubnomina($ldt_anocurnom,$as_codperi); 
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hperiodo($ldt_anocurnom,$as_codperi); 
		}  
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_htabla($ldt_anocurnom,$as_codperi); 
		} 
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hgrado($ldt_anocurnom,$as_codperi); 
		}   
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hprimagrado($ldt_anocurnom,$as_codperi); 
		}   
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hunidadadmin($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hclasificacionobrero($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hproyecto($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hcargo($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hasignacioncargo($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hpersonalnomina($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hproyectopersonal($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hvacacpersonal($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hconcepto($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hprimaconcepto($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hconceptopersonal($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hconceptovacacion($ldt_anocurnom,$as_codperi); 
		} 
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hconstante($ldt_anocurnom,$as_codperi); 
		}    
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hconstantepersonal($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_htipoprestamo($ldt_anocurnom,$as_codperi); 
		}    
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hprestamos($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hprestamoperiodo($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hprestamoamortizado($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hresumen($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hprenomina($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hsalida($ldt_anocurnom,$as_codperi); 
		} 
    	return $lb_valido;
	}// end function uf_insert_periodo_historico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_restaurar_periodo($as_codperi_actual,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_restaurar_periodo 
		//	    Arguments: as_codperi // codigo del periodo
		//                 as_codperi_actual // codigo del periodo actual
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que procesa el cierre del periodo 
	    //     Creado por: Ing. Juniors Fraga
	    // Fecha Creaci�n: 13/02/2006   
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////
        $ls_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
		$lb_valido=true;
		$li_total=$this->io_cierre_periodo3->uf_existe_hpersonalnomina($as_codperi_actual,$ls_anocurnom);
		if($li_total>0)
		{
		   $lb_valido=$this->io_cierre_periodo3->uf_reversar_procesar_historico($as_codperi_actual,$as_codperi);
		}
		if($lb_valido)
		{
		   $ls_perires="000";
		   $lb_valido=$this->uf_reestablecer_periodo($ls_perires);
		}
        return $lb_valido;		
	}// end function uf_restaurar_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reestablecer_periodo($as_codperi_actual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reestablecer_periodo 
		//	    Arguments: as_codperi_actual  //  codigo del periodo actual
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que se encarga de reestablecer un periodo 
	    //     Creado por: Ing. Juniors Fraga
	    // Fecha Creaci�n: 11/02/2006 
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	  	$lb_valido=true;
		$ls_sql="UPDATE sno_nomina ".
                "   SET perresnom='".$as_codperi_actual."' ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";     
		$li_row=$this->io_sql->select($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo M�TODO->uf_reestablecer_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_reestablecer_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_proximoperiodo($as_peractnom,&$ad_fecdes,&$ad_fechas)
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_proximoperiodo
		//		   Access: private
		//	    Arguments: as_peractnom // per�odo actual de la n�mina
		//                 ad_fecdes // Fecha desde del pr�ximo per�odo    
		//                 ad_fechas // Fecha hasta del pr�ximo per�odo
		//	      Returns: lb_valido True si se ejecuto correctamente la funci�n y false si hubo error
		//	  Description: funci�n que devuelve la fecha desde y hata del pr�ximo per�odo
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 20/03/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_periodo=str_pad((intval($as_peractnom)+1),3,"0",0);
		$ls_sql="SELECT fecdesper, fechasper ".
				"  FROM sno_periodo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$ls_periodo."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Cierre Periodo M�TODO->uf_load_proximoperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_fecdes=$this->io_funciones->uf_formatovalidofecha($row["fecdesper"]);
				$ad_fechas=$this->io_funciones->uf_formatovalidofecha($row["fechasper"]);
			}
			$this->io_sql->free_result($rs_data);
		}
      	return ($lb_valido);  
    }// end function uf_load_proximoperiodo	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_suspender_contratatados($as_codperi,$adt_fecdesper,$adt_fechasper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_suspender_contratatados 
		//	    Arguments: as_codperi_actual  //  codigo del periodo a cerrar
		//                 adt_fechasper  //  fecha desde donde comienza el periodo
		//                 adt_fechasper  //  fecha hasta donde termina el periodo
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que se encarga de procesar el cierre del periodo 
	    //     Creado por: Ing. Juniors Fraga
	    // Fecha Creaci�n: 13/02/2006 
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_mensaje="";
		$ls_sql="SELECT sno_personalnomina.codper, sno_personalnomina.fecingper, sno_personalnomina.fecculcontr, ".
				"		sno_personal.nomper, sno_personal.apeper ".
                "  FROM sno_personalnomina, sno_periodo, sno_personal ".
                " WHERE sno_periodo.codemp='".$this->ls_codemp."' ".
				"   AND sno_periodo.codnom='".$this->ls_codnom."' ".
				"   AND sno_periodo.codperi='".$as_codperi."' ".
				"   AND sno_personalnomina.fecculcontr>'1900-01-01' ".
				"   AND sno_personalnomina.fecculcontr<='".$adt_fecdesper."' ".
				"   AND sno_personalnomina.staper<>'4' ".
				"   AND sno_personalnomina.codemp=sno_periodo.codemp ".
				"   AND sno_personalnomina.codnom=sno_periodo.codnom ".
				"   AND sno_personalnomina.codemp=sno_personal.codemp ".
				"   AND sno_personalnomina.codper=sno_personal.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo M�TODO->uf_suspender_contratatados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codper=$row["codper"];
				$ls_nomper=$row["nomper"];
				$ls_apeper=$row["apeper"];
				$ls_staper=4;
				$ls_sql="UPDATE sno_personalnomina ".
					    "   SET staper='".$ls_staper."', ".
					    "	   fecsusper='".$adt_fecdesper."' ".
					    " WHERE codemp='".$this->ls_codemp."' ".
					    "   AND codnom='".$this->ls_codnom."' ".
					    "   AND codper='".$ls_codper."' ";
				$ls_mensaje = $ls_mensaje.' C�digo: '.$ls_codper.'  -  '.$ls_apeper.', '.$ls_nomper.'\n';
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$ls_mensaje="";
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Cierre Periodo M�TODO->uf_suspender_contratatados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
			}
		}
		if($ls_mensaje!="")
		{
			$ls_mensaje=' PERSONAL SUSPENDIDO POR FINALIZACI�N DE CONTRATO   \n\n  '.$ls_mensaje;
			$this->io_mensajes->message($ls_mensaje);
		}
		return $lb_valido;
    }// end function uf_suspender_contratatados	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_periodo_anterior(&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_periodo_anterior 
		//	    Arguments: ai_totrows // total de filas que tiene el arreglo
		//                 ao_object // arreglo de objetos 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que busca la informaci�n del per�odo anterior
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
        $ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
        $ls_codperi_ant=$this->io_funciones->uf_rellenar_izq((intval($ls_peractnom)-1),0,3);	
	    $ls_sql="SELECT sno_periodo.codperi, sno_periodo.fecdesper, sno_periodo.fechasper, sno_periodo.cerper,".
				"		sno_periodo.conper, sno_periodo.apoconper, sno_periodo.ingconper, sno_periodo.fidconper, ".
				"		sno_periodo.totper ".
                "  FROM sno_periodo, sno_nomina ".
                " WHERE sno_nomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_nomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_periodo.codperi='".$ls_codperi_ant."' ".
				"   AND sno_periodo.cerper=1 ".
				"   AND sno_nomina.perresnom='000' ".
				"   AND sno_nomina.codemp=sno_periodo.codemp ".
				"   AND sno_nomina.codnom=sno_periodo.codnom ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo M�TODO->uf_select_periodo_anterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codperi=$row["codperi"];  
				$ls_fecdesper=$this->io_funciones->uf_convertirfecmostrar($row["fecdesper"]);
				$ls_fechasper=$this->io_funciones->uf_convertirfecmostrar($row["fechasper"]);
				$li_cerper=$row["cerper"];
				$ls_cerrado="";
				if($li_cerper==1)
				{
					$ls_cerrado="checked";
				}			
			    $li_conper=$row["conper"];
				$ls_contabilizado="";
				if($li_conper==1)
				{
					$ls_contabilizado="checked";
				}			
				$li_apoconper=$row["apoconper"];
				$ls_aporte="";
				if($li_apoconper==1)
				{
					$ls_aporte="checked";
				}			
				$li_ingconper=$row["ingconper"];
				$ls_ingreso="";
				if($li_ingconper==1)
				{
					$ls_ingreso="checked";
				}			
				$li_fidconper=$row["fidconper"];
				$ls_fideicomiso="";
				if($li_fidconper==1)
				{
					$ls_fideicomiso="checked";
				}			
				$li_totper=number_format($row["totper"],2,",",".");
				$ao_object[$ai_totrows][1]="<input type=text name=txtcodperi".$ai_totrows." value=".$ls_codperi." class=sin-borde  size=7  style=text-align:center readonly>";
				$ao_object[$ai_totrows][2]="<input type=text name=txtfecdesper".$ai_totrows." value=".$ls_fecdesper." size=12 class=sin-borde   style=text-align:center readonly >";
				$ao_object[$ai_totrows][3]="<input type=text name=txtfechasper".$ai_totrows." class=sin-borde value=".$ls_fechasper." size=12  style=text-align:center readonly>";
				$ao_object[$ai_totrows][4]="<input name=chkcerrada".$ai_totrows." type=checkbox id=chkcerrada".$ai_totrows." class=sin-borde ".$ls_cerrado." disabled>";
				$ao_object[$ai_totrows][5]="<input name=chkcontabilizada".$ai_totrows." type=checkbox id=chkcontabilizada".$ai_totrows." class=sin-borde ".$ls_contabilizado." disabled><input name='contabilizado".$ai_totrows."' type='hidden' id='contabilizado".$ai_totrows."' value='".$li_conper."'>";
				$ao_object[$ai_totrows][6]="<input name=chkaporte".$ai_totrows." type=checkbox id=chkaporte".$ai_totrows." class=sin-borde ".$ls_aporte." disabled><input name='aporte".$ai_totrows."' type='hidden' id='aporte".$ai_totrows."' value='".$li_apoconper."'>";
				$ao_object[$ai_totrows][7]="<input name=chkingreso".$ai_totrows." type=checkbox id=chkingreso".$ai_totrows." class=sin-borde ".$ls_ingreso." disabled><input name='ingreso".$ai_totrows."' type='hidden' id='ingreso".$ai_totrows."' value='".$li_ingconper."'>";
				$ao_object[$ai_totrows][8]="<input name=chkfideicomiso".$ai_totrows." type=checkbox id=chkfideicomiso".$ai_totrows." class=sin-borde ".$ls_fideicomiso." disabled><input name='fideicomiso".$ai_totrows."' type='hidden' id='fideicomiso".$ai_totrows."' value='".$li_fidconper."'>";
				$ao_object[$ai_totrows][9]="<input type=text name=txttotper".$ai_totrows." value=".$li_totper." class=sin-borde size=17 style=text-align:right readonly>";
		  	}
	  }
		return 	$lb_valido;
    }// end function uf_select_periodo_anterior	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_abrir_periodo($as_codperi_abrir,$as_codperi_actual,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_abrir_periodo 
		//	    Arguments: as_codperi_abrir // codigo del periodo abrir 
		//                 as_codperi_actual  //  codigo del periodo actual
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que se encarga de procesar el per�odo abrir 
	    //     Creado por: Ing. Juniors Fraga
	    // Fecha Creaci�n: 17/02/2006     
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        $ls_codperi_abrir=$as_codperi_abrir;       
		$ls_codperi_actual=$as_codperi_actual;
	    $this->io_sql->begin_transaction();
		$lb_valido=$this->uf_guardar_periodo($as_codperi_actual);
		if($lb_valido)
		{
		    $lb_valido=$this->uf_abrir_periodo($as_codperi_abrir,$as_codperi_actual);
		} 
		if(($lb_valido)&&($as_codperi_actual<>'000'))
		{
		   // $lb_valido=$this->io_cierre_periodo3->uf_limpiar_periodo($as_codperi_actual,$as_codperi_abrir);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_sno->uf_crear_sessionnomina();
		}
		if($lb_valido)
		{		
			$lb_valido=$this->io_cierre_periodo4->uf_delete_contabilizacion($ls_codperi_abrir);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Abrio el per�odo ".$ls_codperi_abrir." asociado a la n�mina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////				
		}
		if($lb_valido)
		{
		   $this->io_sql->commit(); 
		   $this->io_mensajes->message("El abrir per�odo se proceso.");
	    }
	    else
	    {
		   $this->io_sql->rollback();
		   $this->io_mensajes->message("Ocurrio un error al abrir per�odo.");
	    }
		return $lb_valido;
    }// end function uf_procesar_abrir_periodo	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_periodo($as_codperi_actual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_periodo 
		//	    Arguments: as_codperi_actual  //  codigo del periodo actual
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que se encarga de guardar un periodo que no ha sido calculado
	    //     Creado por: Ing. Juniors Fraga
	    // Fecha Creaci�n: 11/02/2006
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
         $lb_valido=$this->uf_procesar_historico($as_codperi_actual); 
		 if($lb_valido)
		 {
		    $lb_valido=$this->uf_reestablecer_periodo($as_codperi_actual);
		 }
		 return  $lb_valido; 
    }// end function uf_guardar_periodo	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_abrir_periodo($as_codperi_abrir,$as_codperi_actual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_abrir_periodo 
		//	    Arguments: as_codperi_abrir // codigo del periodo abrir 
		//                 as_codperi_actual  //  codigo del periodo actual
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que se enacrga de abrir un periodo especifico
	    //     Creado por: Ing. Juniors Fraga
	    // Fecha Creaci�n: 10/02/2006        
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($lb_valido)
		{
			$lb_valido=$this->io_cierre_periodo3->uf_reversar_procesar_historico($as_codperi_abrir,$as_codperi_actual);
		}
		/*if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo4->uf_reversar_acumular_conceptos($as_codperi_abrir);
		}*/
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo4->uf_reversar_actualizar_periodo($as_codperi_abrir);
		}
		return  $lb_valido;
    }// end function uf_abrir_periodo	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_periodo($as_codperi_actual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_periodo 
		//	    Arguments: as_codperi_actual  //  codigo del periodo actual
		//	      Returns: li_total devuelve el total de registros encontrados
		//	  Description: Funci�n que se encarga de verificar si puedo hacer el cierre del periodo
	    //     Creado por: Ing. Juniors Fraga
	    // Fecha Creaci�n: 13/02/2006          Fecha �ltima Modificacion : 
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT COUNT(valsal) AS total ".
                "  FROM sno_salida ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$as_codperi_actual."' ";
	    $rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
	    {
			$li_total=0;
		    $this->io_mensajes->message("CLASE->Cierre Periodo M�TODO->uf_verificar_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
	    }
	    else
	    {
		     if($row=$this->io_sql->fetch_row($rs_data))
			 {
				$li_total=$row["total"];
			 } 
		}
	   return $li_total;
    }// end function uf_verificar_periodo	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contabilizado_ant($as_codperi_actual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contabilizado_ant 
		//	    Arguments: as_codperi_actual  //  codigo del periodo actual
		//	      Returns: lb_contabilizado devuelve si esta contabilizado el per�do anterior
		//	  Description: Funci�n que se encarga de verificar si el per�odo anterior est� contabilizado
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 30/05/2006          Fecha �ltima Modificacion : 
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codperi=str_pad(intval($as_codperi_actual-1),3,"0",0);
		$lb_contabilizado=0;
		if($ls_codperi<>"000")
		{
			$ls_sql="SELECT conper, apoconper, ingconper, fidconper, ".
					"		(SELECT COUNT(sno_hsalida.codconc) ".
					"  		   FROM sno_hsalida ".
					" 		  WHERE sno_hsalida.codemp=sno_periodo.codemp ".
					"   		AND sno_hsalida.codnom=sno_periodo.codnom ".
					"   		AND sno_hsalida.codperi=sno_periodo.codperi ".
					"   		AND (sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='P2') ".
					"   		AND sno_hsalida.valsal<>0) AS existeaporte, ".
					"		(SELECT COUNT(codperi) ".
					"  		   FROM sno_hperiodo ".
					" 		  WHERE codemp='".$this->ls_codemp."' ".
					"  			AND codnom='".$this->ls_codnom."' ".
					"   		AND codperi='".$ls_codperi."') AS existehistorico ".
					"  FROM sno_periodo ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND codperi='".$ls_codperi."' ";
			$rs_data=$this->io_sql->select($ls_sql);
			if ($rs_data===false)
			{
				$lb_contabilizado=0;
				$this->io_mensajes->message("CLASE->Cierre Periodo M�TODO->uf_contabilizado_ant ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$li_conper=$row["conper"];
					$li_apoconper=$row["apoconper"];
					$li_ingconper=$row["ingconper"];
					$li_fidconper=$row["fidconper"];
					$li_existeaporte=intval($row["existeaporte"]);
					$li_existehistorico=intval($row["existehistorico"]);
					if((($li_conper=="1")&&(($li_apoconper=="1")||($li_existeaporte=="0")))||($li_existehistorico==0))
					{
						$lb_contabilizado=1;
					}
				}
				$this->io_sql->free_result($rs_data);	
			}
		}
		else
		{
			$lb_contabilizado=1;
		}
	   	return $lb_contabilizado;
    }// end function uf_contabilizado_ant	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_eliminar_carpeta($as_peractnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_eliminar_carpeta 
		//	    Arguments: as_peractnom  //  codigo del periodo actual
		//	      Returns: lb_contabilizado devuelve si esta contabilizado el per�do anterior
		//	  Description: Funci�n que se encarga de eliminar la carpeta generada por los listados al banco.
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 25/08/2006          Fecha �ltima Modificacion : 
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nomina=$_SESSION["la_nomina"]["codnom"];
		//----------------------------- Para los Disco al Banco
		$ls_ruta="txt/disco_banco/".$ls_nomina."-".$as_peractnom;
		$lista = array();
		$handle = @opendir($ls_ruta);
		while ($file = @readdir($handle))
		{
			 if(($file != '.') && ($file != '..'))
			 {
				@unlink($ls_ruta."/".$file);
			 }
		}
		@closedir($handle);
		$ls_ruta="txt/disco_banco";
		$lista = array();
		$handle = @opendir($ls_ruta);
		while ($file = @readdir($handle))
		{
			 if(($file != '.') && ($file != '..'))
			 {
			 	if($file==$ls_nomina."-".$as_peractnom)
				{
					rmdir($ls_ruta."/".$file);
				}
			 }
		}
		@closedir($handle);
		//----------------------------- Para los Aportes
		$ls_ruta="txt/aportes/".$ls_nomina."-".$as_peractnom;
		$lista = array();
		$handle = @opendir($ls_ruta);
		while ($file = @readdir($handle))
		{
			 if(($file != '.') && ($file != '..'))
			 {
				@unlink($ls_ruta."/".$file);
			 }
		}
		@closedir($handle);
		$ls_ruta="txt/aportes";
		$lista = array();
		$handle = @opendir($ls_ruta);
		while ($file = @readdir($handle))
		{
			 if(($file != '.') && ($file != '..'))
			 {
			 	if($file==$ls_nomina."-".$as_peractnom)
				{
					rmdir($ls_ruta."/".$file);
				}
			 }
		}
		@closedir($handle);
		
	   	return $lb_valido;
    }// end function uf_eliminar_carpeta	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_load_contratatados_por_suspender($as_codperi,$adt_fecdesper,$adt_fechasper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_contratatados_por_suspender 
		//	    Arguments: as_codperi_actual  //  codigo del periodo a cerrar
		//                 adt_fechasper  //  fecha desde donde comienza el periodo
		//                 adt_fechasper  //  fecha hasta donde termina el periodo
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que se encarga de procesar el cierre del periodo 
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 20/08/2007 
		// Modificado Por: 											Fecha �ltima Modificaci�n : 
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_mensaje="";
		$ls_sql="SELECT sno_personalnomina.codper, sno_personalnomina.fecingper, sno_personalnomina.fecculcontr, ".
				"		sno_personal.nomper, sno_personal.apeper ".
                "  FROM sno_personalnomina, sno_periodo, sno_personal ".
                " WHERE sno_periodo.codemp='".$this->ls_codemp."' ".
				"   AND sno_periodo.codnom='".$this->ls_codnom."' ".
				"   AND sno_periodo.codperi='".$as_codperi."' ".
				"   AND sno_personalnomina.fecculcontr>='".$adt_fecdesper."' ".
				"   AND sno_personalnomina.fecculcontr<='".$adt_fechasper."' ".
				"   AND sno_personalnomina.staper<>'4' ".
				"   AND sno_personalnomina.codemp=sno_periodo.codemp ".
				"   AND sno_personalnomina.codnom=sno_periodo.codnom ".
				"   AND sno_personalnomina.codemp=sno_personal.codemp ".
				"   AND sno_personalnomina.codper=sno_personal.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo M�TODO->uf_load_contratatados_por_suspender ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codper=$row["codper"];
				$ls_nomper=$row["nomper"];
				$ls_apeper=$row["apeper"];
				$ls_mensaje = $ls_mensaje.' C�digo: '.$ls_codper.'  -  '.$ls_apeper.', '.$ls_nomper.'\n';
			}
		}
		if($ls_mensaje!="")
		{
			$ls_mensaje=' PERSONAL EL CUAL SE VENCE EL CONTRATO EN EL PERIODO '.$as_codperi.'  \n\n  '.$ls_mensaje;
			$this->io_mensajes->message($ls_mensaje);
		}
		return $lb_valido;
    }// end function uf_load_contratatados_por_suspender	
	//---------------------------------------------------------------------------------------------------------------------------------------

	
	
	
	//-----------------------------------------------------------------------------------------------------------------------------
}
?>
