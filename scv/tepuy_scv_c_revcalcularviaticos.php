<?php

class tepuy_siv_c_revcalcularviaticos
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function tepuy_siv_c_revcalcularviaticos()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones_db.php");
		require_once("../shared/class_folder/class_funciones.php");
		$in=              new tepuy_include();
		$this->con=       $in->uf_conectar();
		$this->io_sql=    new class_sql($this->con);
		$this->seguridad= new tepuy_c_seguridad();
		$this->io_fun=    new class_funciones_db($this->con);
		$this->DS=        new class_datastore();
		$this->ds_detpersonal=new class_datastore();
		$this->io_msg=    new class_mensajes();
		$this->io_funcion=new class_funciones();
	}
	
	function uf_scv_buscar_personal_dt($as_codemp,$as_codsolvia,&$ai_total_per)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_buscar_personal_dt
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de la solicitud de viaticos
		//	      Returns: Retorna las fichas asociada al personal
		//    Description: Funcion que busca la recepcion de documentos generada desde viaticos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT ficha FROM scv_dt_personal  WHERE codemp='".$as_codemp."'".
				  "   AND codsolvia='".$as_codsolvia."'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		$ai_total_per=$this->io_sql->num_rows($rs_data);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revcalcularviaticos MÉTODO->uf_scv_buscar_personal_dt ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			//print "Total Personas: ".$ai_total_per;
			if ($ai_total_per>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detpersonal->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}

	function uf_scv_select_estatus_recepcion($as_codemp,$as_codsolvia,&$ab_registro,&$as_numrecdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_estatus_recepcion
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de la solicitud de viaticos
		//  			   $ab_registro  // indica si alguna de las recepciones de documentos ha sido pasada a otro estatus
		//  			   $as_numrecdoc // numeto de la recepcion de documento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica el estatus que se encuentra la recepcion de documentos generada desde viaticos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		//$as_numrecdoc=$this->io_funcion->uf_cerosizquierda($as_codsolvia,11);
		//$as_numrecdoc="SCV-".$as_numrecdoc;
		//$as_numrecdoc="SCV-00000".$as_codsolvia;
		$ls_sql = "SELECT estprodoc,estaprord".
		          "  FROM cxp_rd  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND numrecdoc='".$as_numrecdoc."'".
				  "   AND procede='SCVSOV'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revcalcularviaticos MÉTODO->uf_scv_select_estatus_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ab_registro=true;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ls_estaprord=$row["estaprord"];
				$ls_estprodoc=$row["estprodoc"];
				if(($ls_estprodoc!="R")||($ls_estaprord!=0))
				{
					$ab_registro=false;
					break;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function uf_scv_select_estatus_recepcion

	function uf_scv_delete_dt_rd_scg($as_codemp,$as_numrecdoc,$as_codsolvia,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_delete_dt_rd_scg
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero concecutivo de recepción
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un detalle contable de una recepcion de documentos generada por una solicitud de 
		//                 viaticos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_rd_scg".
				" WHERE codemp='". $as_codemp ."'".
				"   AND numrecdoc='". $as_numrecdoc ."'".
				"   AND procede_doc='SCVSOV'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revcalcularviaticos MÉTODO->uf_scv_delete_dt_rd_scg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Reversó el detalle contable de la recepcion de documento ".$as_numrecdoc." mediante el reverso de".
			                 " la solicitud de viaticos".$as_codsolvia." asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion); 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function uf_scv_delete_dt_rd_scg
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_delete_movimiento($as_codemp,$as_procede,$as_comprobante,$as_fecha)//($as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_cuenta,$as_procede_doc,$as_documento,
					//				  $as_operacion,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_delete_movimiento
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procede // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   as_cuenta // cuenta
		//       		   as_procede_doc // Procede del movimiento
		//       		   as_documento // Número del Documento
		//       		   as_operacion // Operación si es debe ó haber
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Este método elimina el movimineto contable
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Juniors Fraga
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		//$ls_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
		$ls_sql="DELETE FROM scg_dt_cmp ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND procede='".$as_procede."' ".
				"   AND comprobante='".$as_comprobante ."' ".
				"   AND fecha= '".$as_fecha."' ";
		//print $ls_sql;
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error = "CLASE->tepuy_int_scg MÉTODO->uf_scg_delete_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
	    return $lb_valido;
	} // end function uf_scg_delete_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_datos_beneficiario($as_codemp,$as_ced_bene)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_datos_beneficiario
		//		   Access: private
		//	    Arguments: as_codemp  // Código de Empresa
		//				   as_ced_bene  // Cédula del Beneficiario
		//	      Returns: Retorna un bollean valido
		//	  Description: método que obtiene los datos de un beneficiario específico
		//	   Creado Por: Ing. Juniors Fraga
		// Modificado Por: Ing. Miguel Palencia								Fecha Última Modificación : 10/01/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT * ".
		        "  FROM rpc_beneficiario ".
                " WHERE codemp='".$as_codemp."' ".
				"   AND ced_bene='".$as_ced_bene."'";				  
		$rs_data=$this->io_sql->select($ls_sql);		
		if($rs_data===false)
		{
            $this->io_msg->message("CLASE->Integración CXP MÉTODO->uf_obtener_datos_beneficiario ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe = true; // si existe se procedera a registrar en el datastore.				
				$this->dts_beneficiario->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$this->io_msg->message("El Beneficiario no existe en la base de datos.");
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_obtener_datos_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function uf_scv_delete_dt_rd_spg($as_codemp,$as_numrecdoc,$as_codsolvia,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_delete_dt_rd_spg
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero concecutivo de recepción
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un detalle contable de una recepcion de documentos generada por una solicitud de 
		//                 viaticos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_rd_spg".
				" WHERE codemp='". $as_codemp ."'".
				"   AND numrecdoc='". $as_numrecdoc ."'".
				"   AND procede_doc='SCVSOV'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revcalcularviaticos MÉTODO->uf_scv_delete_dt_rd_spg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Reversó el detalle presupuestario de la recepcion de documento ".$as_numrecdoc." mediante el reverso".
			                 " de la solicitud de viaticos".$as_codsolvia." asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion); 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function uf_scv_delete_dt_rd_spg

	function uf_scv_delete_rd($as_codemp,$as_numrecdoc,$as_codsolvia,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_delete_rd
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numrecdoc // numero de recepcion de documentos
		//  			   $as_codsolvia // codigo de solicitud de viaticos
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina las recepciones de documentos originadas de una solicitud de viaticos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_rd".
				" WHERE codemp='". $as_codemp ."'".
				"   AND numrecdoc='". $as_numrecdoc."'".
				"   AND cod_pro='----------'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revcalcularviaticos MÉTODO->uf_scv_delete_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Reversó  la recepcion de documento ".$as_numrecdoc." mediante el reverso".
			                 " de la solicitud de viaticos".$as_codsolvia." asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion); 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function uf_scv_delete_recepcion

	function uf_scv_delete_dt_scg($as_codemp,$as_codsolvia,$ls_codcom,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_delete_dt_scg
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de solicitud de viaticos 
		//  			   $ls_codcom    // codigo de comprobante
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un  Booleano
		//    Description: Funcion que elimina un detalle contable de una solicitud de viaticos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM scv_dt_scg".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codsolvia='". $as_codsolvia ."'".
				"   AND codcom='". $ls_codcom ."'";
		//print $ls_sql;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revcalcularviaticos MÉTODO->uf_scv_delete_dt_scg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Reversó el detalle contable de la solicitud de viaticos".$as_codsolvia.
							 " asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion); 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function uf_scv_delete_dt_scg

	function uf_scv_delete_dt_spg($as_codemp,$as_codsolvia,$ls_codcom,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_delete_dt_spg
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de solicitud de viaticos 
		//  			   $ls_codcom    // codigo de comprobante
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un detalle presupuestario de una solicitud de viaticos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM scv_dt_spg".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codsolvia='". $as_codsolvia ."'".
				"   AND codcom='". $ls_codcom ."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revcalcularviaticos MÉTODO->uf_scv_delete_dt_spg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion="Reversó el detalle presupuestario de la solicitud de viaticos".$as_codsolvia.
							" asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion); 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function uf_scv_delete_dt_spg

	function uf_scv_obtener_solicitud($as_numsol,$ad_fecregdes,$ad_fecreghas,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_obtener_solicitud
		//         Access: public  
		//      Argumento: $as_numsol    // numero de solicitud
		//  			   $ad_fecregdes // Fecha desde
		//  			   $ad_fecreghas // Fecha hasta
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca las entradas de las solicitudes de viaticos en estatus de "Procesada" para luego
		//				   imprimirlos en el grid de  la pagina.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecregdes=$this->io_funcion->uf_convertirdatetobd($ad_fecregdes);
		$ad_fecreghas=$this->io_funcion->uf_convertirdatetobd($ad_fecreghas);
		$ls_sql= "SELECT codsolvia,codmis,codrut,coduniadm,fecsolvia,".
				 "       (SELECT denmis".
				 "          FROM scv_misiones".
				 "         WHERE scv_misiones.codemp=scv_solicitudviatico.codemp".
				 "           AND scv_misiones.codmis=scv_solicitudviatico.codmis) AS denmis,".
				 "       (SELECT MAX(desrut) AS desrut ".
				 "          FROM scv_rutas".
				 "         WHERE scv_rutas.codemp=scv_solicitudviatico.codemp".
				 "           AND scv_rutas.codrut=scv_solicitudviatico.codrut GROUP BY scv_rutas.codrut) AS desrut,".
				 "       (SELECT denuniadm".
				 "          FROM spg_unidadadministrativa".
				 "         WHERE spg_unidadadministrativa.codemp=scv_solicitudviatico.codemp".
				 "           AND spg_unidadadministrativa.coduniadm=scv_solicitudviatico.coduniadm) AS denuniadm".
				 "  FROM scv_solicitudviatico".
				 " WHERE estsolvia='P'".
				 "   AND codsolvia LIKE '%".$as_numsol."%'".
				 "   AND fecsolvia >='".$ad_fecregdes."'".
				 "   AND fecsolvia <='".$ad_fecreghas."'".
				 " ORDER BY codsolvia ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revcalcularviaticos MÉTODO->uf_scv_obtener_solicitud ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i=$li_i + 1;
				$ls_codsolvia= $row["codsolvia"];
				$ls_denmis=    $row["denmis"];
				$ls_desrut=    $row["desrut"];
				$ls_denuniadm= $row["denuniadm"];
				$ld_fecsolvia= $row["fecsolvia"];
				$ld_fecsolvia=$this->io_funcion->uf_convertirfecmostrar($ld_fecsolvia);

				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtcodsolvia".$ai_totrows." type=text id=txtcodsolvia".$ai_totrows." class=sin-borde size=12 value='".$ls_codsolvia."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdenmis".$ai_totrows."    type=text id=txtdenmis".$ai_totrows."    class=sin-borde size=44 value='".$ls_denmis."'    readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtdesrut".$ai_totrows."    type=text id=txtdesrut".$ai_totrows."    class=sin-borde size=37 value='".$ls_desrut."'    readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtdenuniadm".$ai_totrows." type=text id=txtdenuniadm".$ai_totrows." class=sin-borde size=34 value='".$ls_denuniadm."' readonly>";
				$ao_object[$ai_totrows][5]="<input name=txtfecsolvia".$ai_totrows." type=text id=txtfecsolvia".$ai_totrows." class=sin-borde size=10 value='".$ld_fecsolvia."' readonly>";
				$ao_object[$ai_totrows][6]="<input type='checkbox' name=chkreversar".$ai_totrows." class= sin-borde value=1>";

			}//while
			if ($li_i==0)
			{
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtcodsolvia".$ai_totrows." type=text id=txtcodsolvia".$ai_totrows." class=sin-borde size=12 readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdenmis".$ai_totrows."    type=text id=txtdenmis".$ai_totrows."    class=sin-borde size=44 readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtdesrut".$ai_totrows."    type=text id=txtdesrut".$ai_totrows."    class=sin-borde size=37 readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtdenuniadm".$ai_totrows." type=text id=txtdenuniadm".$ai_totrows." class=sin-borde size=34 readonly>";
				$ao_object[$ai_totrows][5]="<input name=txtfecsolvia".$ai_totrows." type=text id=txtfecsolvia".$ai_totrows." class=sin-borde size=10 readonly>";
				$ao_object[$ai_totrows][6]="<input type='checkbox' name=chkreversar".$ai_totrows." class= sin-borde value=1>";
			}
			$this->io_sql->free_result($rs_data);
		}
		if ($ai_totrows==0)
		{$lb_valido=false;}
		return $lb_valido;
	}// fin de la function uf_scv_obtener_solicitud

	function uf_scv_update_solivitud_viaticos($as_codemp,$as_codsolvia,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_update_solivitud_viaticos
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $ls_codsolvia // codigo de solicitud de viaticos 
		//        		   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//	  Description: Función que se encarga de poner en estado de registrada a una solicitud de viaticos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" UPDATE scv_solicitudviatico".
				"    SET monsolvia=0, ".
				"        estsolvia='R'".
				"  WHERE codemp='".$as_codemp."'".
				"    AND codsolvia='".$as_codsolvia."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if ($li_row===false)
		{
			$this->io_msg->message("CLASE->revcalcularviaticos METODO->uf_scv_update_solivitud_viaticos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion= "Reversó la solicitud de viaticos ".$as_codsolvia." Asociada a la empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ///////////////////////////
			$lb_valido=true;
		}
		return $lb_valido;
	} // fin function uf_scv_update_rutas

}//end  class tepuy_scv_c_revcalcularviaticos
?>
