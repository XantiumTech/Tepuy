<?php
require_once("../shared/class_folder/class_sql.php");
class tepuy_sfa_c_producto
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	//-----------------------------------------------------------------------------------------------------------------------------
	function tepuy_sfa_c_producto()
	{
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->io_msg=new class_mensajes();
		$in=new tepuy_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad=new tepuy_c_seguridad();
		$this->io_funcion=new class_funciones();
		$this->io_rcbsf=new tepuy_c_reconvertir_monedabsf();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	}
	//-----------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_sfa_select_catalogo(&$ai_estnum,&$ai_estcmp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_select_catalogo
		//         Access: public (tepuy_sfa_d_producto)
		//      Argumento: $ai_estnum //estatus que indica si la codificion es numerica o alfanumerica
		//				   $ai_estcmp // Estatus que indica si se van a agregar ceros a la izq. del codigo de Producto
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene la configuracion del inventario
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación: 08/10/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT metodo, estcatsig, estnum, estcmp".
				"  FROM siv_config".
				" WHERE id=1 ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Producto MÉTODO->uf_sfa_select_catalogo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_estcatsig= $row["estcatsig"];
				$ai_estnum= $row["estnum"];
				$ai_estcmp= $row["estcmp"];
				if($li_estcatsig==1)
				{$lb_valido=true;}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_sfa_select_catalogo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_sfa_select_producto($as_codemp,$as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_select_producto
		//         Access: public (tepuy_sfa_d_producto)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codart //codigo de Producto
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe un determinado Producto en la tabla siv_producto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codpro".
				"  FROM sfa_producto  ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codpro='".$as_codpro."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Producto MÉTODO->uf_sfa_select_producto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}// end function uf_sfa_select_producto
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_sfa_existencia_producto($as_codemp,$as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_existencia_producto
		//         Access: public (tepuy_sfa_d_producto)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codpro //codigo de Producto
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe un determinado Producto en la tabla siv_producto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$existencia=0;
		$ls_sql="SELECT exipro".
				"  FROM sfa_producto  ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codpro='".$as_codpro."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Producto MÉTODO->uf_sfa_existencia_producto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$existencia=$row["exipro"];
				$this->io_sql->free_result($rs_data);
			}
		}
		return $existencia;
	}// end function uf_sfa_existencia_producto
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_sfa_select_producto_inventario($as_codemp,$as_codpro,$as_fecrecpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_select_producto_inventario
		//         Access: public (tepuy_sfa_d_inventario_producto)
		//      Argumento: $as_codemp //codigo de empresa 
		//		$as_codpro //codigo de Producto
		//		$as_feccrepro // fecha de registro del movimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe un determinado Producto en la tabla siv_producto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/11/2016 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codpro".
				"  FROM sfa_producto_act  ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codpro='".$as_codpro."'".
				"   AND fecrecpro='".$as_fecrecpro."'" ;
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Producto MÉTODO->uf_sfa_select_producto_inventario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}// end function uf_sfa_select_producto_inventario
	//-----------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------
	function  uf_sfa_insert_producto($as_codemp,$as_codpro,$as_denpro,$as_codtippro,$as_codunimed,$ad_feccrepro,$as_obspro,
									 $ai_exipro,$ai_exiinipro,$ai_minpro,$ai_maxpro,$ai_preproa,$ai_preprob, 
									 $ai_preproc,$ai_preprod,$ad_fecvenpro,$as_spg_cuenta,$ai_pespro,$ai_altpro,$ai_ancpro,
									 $ai_propro,$as_fotpro,$as_codcatsig,$as_sccuenta,$aa_seguridad,$as_codmil,$as_serpro,
									 $as_fabpro,$as_ubipro,$as_docpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_insert_producto
		//         Access: public (tepuy_sfa_d_producto)
		//     Argumentos: $as_codemp     //codigo de empresa                 $as_codart    // codigo de Producto
		//				   $as_denart     // denominacion del Producto        $as_codtipart // codigo de tipo de Producto
		//			       $as_codunimed  // codigo de unidad de medida       $ad_feccreart // fecha de creacion del Producto
		//				   $as_obsart     // observacion del Producto		  $ai_exiart    // existencia del Producto
		//				   $ai_exiiniart  // existencia inicial del Producto  $ai_minart    // existencia minima del Producto
		//				   $ai_maxart     // existencia maxima del Producto   $ai_prearta   // precio A del Producto
		//				   $ai_preartb    // precio B del Producto		      $ai_preartc   // precio C del Producto
		//				   $ai_preartd    // precio D del Producto			  $ad_fecvenart // fecha de vencimiento del Producto
		//				   $as_spg_cuenta // numero de cuenta presupuestaria  $ai_pesart    // peso del Producto
		//				   $ai_altart     // altura del Producto			  $ai_ancart    // ancho del Producto
		//				   $ai_proart     // profundidad del Producto		  $as_codcatsig // codigo del catalogo sigecof
		//				   $as_sccuenta   // cuenta contable de gasto         $aa_seguridad // arreglo de registro de seguridad
		//                 $as_codmil     // codigo del catalogo milco
		//				   $as_serart     // serial del Producto			  $as_fabart    // fabricante del Producto
		//				   $as_ubiart     // ubicacion del  Producto		  $as_docart    // documento del Producto
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un Producto en la tabla de  siv_producto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 30/08/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($ai_exipro=="")
		{$ai_exipro=0;}
		if($ai_minpro=="")
		{$ai_minpro=0;}
		if($ai_maxpro=="")
		{$ai_maxpro=0;}
		if($ai_preproa=="")
		{$ai_preproa=0;}
		if($ai_preprob=="")
		{$ai_preprob=0;}
		if($ai_preproc=="")
		{$ai_preproc=0;}
		if($ai_preprod=="")
		{$ai_preprod=0;}
		if($ai_pespro=="")
		{$ai_pespro=0;}
		if($ai_altpro=="")
		{$ai_altpro=0;}
		if($ai_ancpro=="")
		{$ai_ancpro=0;}
		if($ai_propro=="")
		{$ai_propro=0;}
		if($ad_fecvenpro=="")
		{$ad_fecvenpro="1900-01-01";}
		$ai_exiinipro=0.00;
		$this->io_sql->begin_transaction();
		$ls_sql="INSERT INTO sfa_producto (codemp,codpro,denpro,codtippro,codunimed,feccrepro,obspro,exipro,exiinipro, ".
				"                          minpro,maxpro,preproa,preprob,preproc,preprod,fecvenpro,spi_cuenta,sc_cuenta)".
				" VALUES ('".$as_codemp."','".$as_codpro."','".$as_denpro."','".$as_codtippro."','".$as_codunimed."',".
				"         '".$ad_feccrepro."','".$as_obspro."',".$ai_exipro.",".$ai_exiinipro.",".$ai_minpro.",".$ai_maxpro.",".
				"          ".$ai_preproa.",".$ai_preprob.",".$ai_preproc.",".$ai_preprod.",'".$ad_fecvenpro."','".$as_spg_cuenta."','".$as_sccuenta."')";
		//print $ls_sql; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->producto MÉTODO->uf_sfa_insert_producto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Producto ".$as_codpro." Asociado a la Empresa ".$as_codemp;
				$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);

				if($lb_valido)
				{
					//$lb_valido=true;
					$this->io_sql->commit();
				}
				else
				{
					//$lb_valido=false;
					$this->io_sql->rollback();
				} 
		}
		return $lb_valido;
	} // end  function  uf_sfa_insert_producto
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function  uf_sfa_insert_producto_inventario($as_codemp,$as_codpro,$as_denpro,$as_codtippro,$as_codunimed,$ad_feccrepro,$as_obspro,
									 $ai_exipro,$ai_prepro,$ad_fecvenpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_insert_producto
		//         Access: public (tepuy_sfa_d_producto)
		//     Argumentos: $as_codemp     //codigo de empresa                 
		//				$as_codpro    // codigo de Producto
		//				$as_denpro     // denominacion del Producto
		//			        $as_codtipart // codigo de tipo de Producto
		//			        $as_codunimed  // codigo de unidad de medida  
		//			        $ad_feccreart // fecha de registro del Producto
		//				$as_obspro     // observacion del Producto
		//				$ai_prepro    // precio  del Producto
		//			  	$ad_fecvenpro // fecha de vencimiento del Producto
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un Producto en la tabla de  sfa_producto_act
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 11/11/2016 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_usuario=$_SESSION["la_logusr"];
		$this->io_sql->begin_transaction();
		$ls_sql="INSERT INTO sfa_producto_act (codemp,codpro,fecrecpro,obspro,exipro,fecvenpro,usuario)".
				" VALUES ('".$as_codemp."','".$as_codpro."', '".$ad_feccrepro."','".$as_obspro."',".$ai_exipro.", '".$ad_fecvenpro."','".$ls_usuario."')";
		//print $ls_sql; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->producto MÉTODO->uf_sfa_insert_producto_inventario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Producto en el Inventario".$as_codpro." Asociado a la Empresa ".$as_codemp;
				$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				

				if($lb_valido)
				{
					//$lb_valido=true;
					$this->io_sql->commit();
				}
				else
				{
					//$lb_valido=false;
					$this->io_sql->rollback();
				} 
		}
		return $lb_valido;
	} // end  function  uf_sfa_insert_producto_inventario
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function  uf_sfa_update_inv_producto($as_codemp,$as_codpro,$nuevo,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_update_producto
		//         Access: public (uf_sfa_update_inv_producto)
		//     Argumentos: $as_codemp     //codigo de empresa
		//                 $as_codpro    // codigo de Producto
		//		$nueva     // Actualiza Existencia del Producto

		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un Producto en la tabla de  siv_producto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/11/2011
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql="UPDATE sfa_producto".
		 		 "   SET exipro=".$nuevo." WHERE codemp='".$as_codemp."' AND codpro='" .$as_codpro."'";
        $this->io_sql->begin_transaction();
		//print $ls_sql; //die();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Producto MÉTODO->uf_sfa_update_inv_producto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó La existencia del Producto ".$as_codpro."En ".$nuevo." Asociado a la Empresa ".$as_codemp;
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			if($lb_valido)
			{
				//$lb_valido=true;
				$this->io_sql->commit();
			}
			else
			{
				//$lb_valido=false;
				$this->io_sql->rollback();
			}
		}
	  return $lb_valido;
	} // end function  uf_sfa_update_inv_producto
	//-----------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------
	function  uf_sfa_update_producto($as_codemp,$as_codpro,$as_denpro,$as_codtippro,$as_codunimed,$ad_feccrepro,$as_obspro,
									 $ai_exipro,$ai_exiinipro,$ai_minpro,$ai_maxpro,$ai_preproa,$ai_preprob, 
									 $ai_preproc,$ai_preprod,$ad_fecvenpro,$as_spg_cuenta,$ai_pespro,$ai_altpro,$ai_ancpro,
									 $ai_propro,$as_fotpro,$as_codcatsig,$as_sccuenta,$aa_seguridad,$as_codmil,$as_serpro,
									 $as_fabpro,$as_ubipro,$as_docpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_update_producto
		//         Access: public (tepuy_sfa_d_producto)
		//     Argumentos: $as_codemp     //codigo de empresa                 $as_codart    // codigo de Producto
		//				   $as_denart     // denominacion del Producto        $as_codtipart // codigo de tipo de Producto
		//			       $as_codunimed // codigo de unidad de medida        $ad_feccreart // fecha de creacion del Producto
		//				   $as_obsart    // observacion del Producto		  $ai_exiart    // existencia del Producto
		//				   $ai_exiiniart // existencia inicial del Producto   $ai_minart    // existencia minima del Producto
		//				   $ai_maxart    // existencia maxima del Producto    $ai_prearta   // precio A del Producto
		//				   $ai_preartb   // precio B del Producto		      $ai_preartc   // precio C del Producto
		//				   $ai_preartd   // precio D del Producto			  $ad_fecvenart // fecha de vencimiento del Producto
		//				   $as_spg_cuenta// numero de cuenta presupuestaria   $ai_pesart    // peso del Producto
		//				   $ai_altart    // altura del Producto				  $ai_ancart    // ancho del Producto
		//				   $ai_proart    // profundidad del Producto		  $as_fotart     // foto del Producto
		//                 $as_codcatsig // codgido del catalogo SIGECOF      $aa_seguridad // arreglo de registro de seguridad
		//				   $as_sccuenta  // cuenta contable de gasto          $as_codmil   // codigo del catalogo milco
		//				   $as_serart     // serial del Producto			  $as_fabart    // fabricante del Producto
		//				   $as_ubiart     // ubicacion del  Producto		  $as_docart    // documento del Producto
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un Producto en la tabla de  siv_producto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ai_exiinipro=0.00;
		 $ls_sql="UPDATE sfa_producto".
		 		 "   SET denpro='". $as_denpro."',codtippro='". $as_codtippro."',codunimed='". $as_codunimed ."',".
				 " 		 feccrepro='". $ad_feccrepro."',obspro='". $as_obspro."',exipro='". $ai_exipro."',".
				 " 		 exiinipro='". $ai_exiinipro."',minpro='". $ai_minpro."',maxpro='". $ai_maxpro."',". 
				 " 		 preproa='". $ai_preproa ."',preprob='". $ai_preprob ."',preproc='". $ai_preartc ."', ". 
				 " 		 preprod='". $ai_preprod ."',fecvenpro='". $ad_fecvenpro."',spi_cuenta='". $as_spg_cuenta ."',".
				// "		 pespro='". $ai_pespro."',altpro='". $ai_altpro."',ancpro='". $ai_ancpro."',".
				// "		 propro='". $ai_propro."',fotpro='". $as_fotpro."',codcatsig='". $as_codcatsig ."',".
				 "		 sc_cuenta='". $as_sccuenta ."' ".//"', codmil='".$as_codmil."', ".
				// "	     serpro='".$as_serpro."',fabpro='".$as_fabpro."',ubipro='".$as_ubipro."',docpro='".$as_docpro."'".
				 " WHERE codpro='" . $as_codpro ."'".
				 "   AND codemp='" . $as_codemp ."'";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Producto MÉTODO->uf_sfa_update_producto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Producto ".$as_codpro." Asociado a la Empresa ".$as_codemp;
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			if($lb_valido)
			{
				//$lb_valido=true;
				$this->io_sql->commit();
			}
			else
			{
				//$lb_valido=false;
				$this->io_sql->rollback();
			}
		}
	  return $lb_valido;
	} // end function  uf_sfa_update_producto
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_sfa_delete_producto($as_codemp,$as_codpro, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_delete_producto
		//         Access: public (tepuy_sfa_d_producto)
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_codart    //codigo de Producto
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que llama a la verificacion de algun Producto en las tablas de siv_componetearticulo y
		//				   en la de siv_dt_recepcion y en caso de no encontrarse procede a su eliminacion
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=$this->uf_sfa_select_dt_factura($as_codemp,$as_codpro);
		if($lb_existe)
		{
			$this->io_msg->message("El Producto tiene entradas registradas en la empresa");		
			$lb_valido=false;
		}
		else
		{
			$ls_sql=" DELETE FROM sfa_producto".
				"  WHERE codemp= '".$as_codemp. "'".
				"    AND codpro= '".$as_codpro. "'"; 
			$this->io_sql->begin_transaction();	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->Producto MÉTODO->uf_sfa_delete_producto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Producto ".$as_codpro." Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
				$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
				$aa_seguridad["ventanas"],$ls_descripcion);
			//////////////////////////////////         SEGURIDAD               /////////////////////////////			
				if($lb_variable)
				{
					$lb_valido=true;
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
	} // end  function uf_sfa_delete_producto
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_sfa_delete_producto_inventario($as_codemp,$as_codpro,$as_fecrecpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_delete_producto
		//         Access: public (tepuy_sfa_d_producto)
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_codart    //codigo de Producto
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que llama a la verificacion de algun Producto en las tablas de siv_componetearticulo y
		//				   en la de siv_dt_recepcion y en caso de no encontrarse procede a su eliminacion
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=$this->uf_sfa_select_dt_factura($as_codemp,$as_codpro,$as_fecrecpro);
		if($lb_existe)
		{
			$this->io_msg->message("El Producto tiene facturas registradas a la fecha");		
			$lb_valido=false;
		}
		else
		{
			$ls_sql=" DELETE FROM sfa_producto_act".
				"  WHERE codemp= '".$as_codemp. "'".
				"    AND codpro= '".$as_codpro. "'".
				"    AND fecrecpro='".$as_fecrecpro."'"; 
			$this->io_sql->begin_transaction();	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->Producto MÉTODO->uf_sfa_delete_producto_inventario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Producto agregado al Inventario ".$as_codpro." Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
				$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
				$aa_seguridad["ventanas"],$ls_descripcion);
			//////////////////////////////////         SEGURIDAD               /////////////////////////////			
				if($lb_variable)
				{
					$lb_valido=true;
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
	} // end  function uf_sfa_delete_producto_inventario
	//-----------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_sfa_select_dt_factura($as_codemp,$as_codpro,$as_fecrecpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_select_dt_recepcion
		//         Access: private
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_codart    //codigo de Producto
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si un Producto ha tenido alguna entrada en la empresa
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT a.codpro,b.fecfactura".
				"  FROM sfa_dt_factura a, sfa_factura b".
				" WHERE a.codemp='".$as_codemp."'".
				"   AND a.codpro='".$as_codpro."'" .
				"   AND a.codemp='".$as_codemp."'" .
				"   AND b.fecfactura='".$as_fecrecpro."'" ;
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Producto MÉTODO->uf_sfa_select_dt_factura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end  function uf_sfa_select_dt_factura
	//-----------------------------------------------------------------------------------------------------------------------------

	
	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_sfa_select_dt_cargos($as_codemp,$as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_select_dt_cargos
		//         Access: private
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_codart    //codigo de Producto
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si un Producto tiene algun cargo asociado
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codpro".
				"  FROM siv_cargosarticulo ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codpro='".$as_codpro."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Producto MÉTODO->uf_sfa_select_dt_cargos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end  function uf_sfa_select_dt_cargos
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_sfa_select_cuentaspg($as_codemp,&$as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_select_cuentaspg
		//         Access: public (tepuy_sfa_d_producto)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_cuenta //numero de cuenta presupuestaria
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe una determinada cuenta presupuestaria
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 28/03/2006 								Fecha Última Modificación : 28/03/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT spi_cuenta".
				"  FROM spi_cuentas  ".
				" WHERE codemp='".$as_codemp."'".
				"   AND spi_cuenta LIKE '".$as_cuenta."%'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Producto MÉTODO->uf_sfa_select_cuentaspg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_cuenta=$row["spi_cuenta"];
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}// end function uf_sfa_select_cuentaspg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_upload($as_nomfot,$as_tipfot,$as_tamfot,$as_nomtemfot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_upload
		//		   Access: public (tepuy_snorh_d_personal)
		//	    Arguments: as_nomfot  // Nombre Foto
		//				   as_tipfot  // Tipo Foto
		//				   as_tamfot  // Tamaño Foto
		//				   as_nomtemfot  // Nombre Temporal
		//	      Returns: Retorna un booleano
		//	  Description: Funcion que sube una foto al servidor
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if ($as_nomfot!="")
		{
			if (!((strpos($as_tipfot, "gif") || strpos($as_tipfot, "jpeg") || strpos($as_tipfot, "png")) && ($as_tamfot < 100000))) 
			{ 
				$lb_valido=false;
				$as_nomfot="";
				$this->io_msg->message("El archivo de la foto no es válido.");
			}
			else
			{ 
				if (!((move_uploaded_file($as_nomtemfot, "fotosarticulos/".$as_nomfot))))
				{
					$lb_valido=false;
					$as_nomfot="";
		        	$this->io_msg->message("CLASE->Producto MÉTODO->uf_upload ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}
		}
		return $lb_valido;	
    }// end function uf_upload
	//-----------------------------------------------------------------------------------------------------------------------------
   


} 
?>
