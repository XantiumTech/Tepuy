<?php
require_once("../shared/class_folder/class_sql.php");
class tepuy_saf_c_entregaunidad
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function tepuy_saf_c_entregaunidad()
	{
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$in=new tepuy_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=      new class_sql($this->con);
		$this->seguridad=   new tepuy_c_seguridad();
		$this->io_funcion = new class_funciones();
	}
	
	function uf_saf_select_entregaunidad($as_codemp,$as_cmpent,$ad_fecentuni)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_entregaunidad
		//         Access: public (tepuy_siv_p_entregaunidad)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_cmpent //Nº del Comprobante de la entrega de unidad
		//                 $ad_fecentuni //fecha de la entrega de unidad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe una determinada entrega de unidad admninistrativa 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 04/04/2006 								Fecha Última Modificación : 04/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM saf_entregauniadm  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND cmpent='".$as_cmpent."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->entregaunidad MÉTODO->uf_saf_select_entregaunidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}  // end function uf_saf_select_entregaunidad
											
	function  uf_saf_insert_entregaunidad($as_codemp,$as_cmpent,$ad_fecentuni,$as_coduniadm,$as_obsentuni,$as_codusureg,$as_codres,$as_codresnew,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_entregaunidad
		//         Access: public (tepuy_siv_p_centregaunidad)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_cmpent //Nº del Comprobante de la entrega
		//                 $ad_fecentuni //fecha de la entrega
		//                 $as_coduniadm //codigo de la unidad administrativa
		//                 $as_obsentuni //observaciones de la entrega
		//                 $as_codusureg //codigo del usuario que esta haciendo la entrega
		//                 $as_codres // codigo del responsable actual
		//                 $as_codresnew //codigo del nuevo responsable
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta una nueva entrega de unidad administrativa en la tabla saf_entregauniadm
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/04/2006 								Fecha Última Modificación : 03/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fecentuni=$this->io_funcion->uf_convertirdatetobd($ad_fecentuni);
		$ls_sql = "INSERT INTO saf_entregauniadm (codemp,cmpent,fecentuni,codusureg,coduniadm,codres,codresnew,obsentuni) ".
					" VALUES('".$as_codemp."','".$as_cmpent."','".$ld_fecentuni."','".$as_codusureg."','".$as_coduniadm."', ".
					" '".$as_codres."','".$as_codresnew."','".$as_obsentuni."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->entregauniadad MÉTODO->uf_saf_insert_entregaunidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Entrega de la Unidad ".$as_coduniadm."del personal ".$as_codres." al ".$as_codresnew.
								 " Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} //end function  uf_saf_insert_entregaunidad

	function uf_saf_select_unidadxresponsable($as_codemp,$as_codres,$as_coduniadm,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_unidadxresponsable
		//         Access: public (tepuy_siv_p_cambioresponsable)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_codres //codigo del responsable
		//                 $as_coduniadm //codigo de unidad administrativa
		//                 $rs_data //resulset de la busqueda
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los activos que estan asociados a un responsable en particular en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 04/04/2006 								Fecha Última Modificación : 04/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codact, ideact FROM saf_dta  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND coduniadm='".$as_coduniadm."'".
				  " AND codres='".$as_codres."'".
				  " GROUP BY codact, ideact ";		  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->entregaunidad MÉTODO->uf_saf_select_unidadxresponsable ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_codact=$row["codact"];
				$as_ideact=$row["ideact"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_select_unidadxresponsable

	function uf_saf_update_dta($as_codemp,$as_codact,$as_ideact,$as_codresnew,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_dta
		//         Access: public (tepuy_siv_d_cambioresponsable)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_codact //codigo del activo
		//                 $as_ideact //identificación del elemento u objeto
		//                 $as_codresnew //codigo del nuevo responsable
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza un los responsables de un activo en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/04/2006 								Fecha Última Modificación : 03/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "UPDATE saf_dta SET   codrespri='". $as_codresnew ."'".
					" WHERE codemp='" . $as_codemp ."'".
					" AND codact='" . $as_codact ."'".
					" AND ideact='" . $as_ideact ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->cambioresponsable MÉTODO->uf_saf_update_dta ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Responsable del Activo ".$as_codact." Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_dta
	
	function uf_saf_procesar_entregaunidad($as_codemp,$as_cmpent,$ad_fecentuni,$as_coduniadm,$as_obsentuni,$as_codusureg,$as_codres,$as_codresnew,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_procesar_cambioresponsable
		//         Access: public (tepuy_siv_p_cambioresponsable)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_cmpent //Nº del Comprobante de la entrega
		//                 $ad_fecentuni //fecha de la entrega
		//                 $as_coduniadm //codigo de la unidad administrativa
		//                 $as_obsentuni //observaciones de la entrega
		//                 $as_codusureg //codigo del usuario que esta haciendo la entrega
		//                 $as_codres // codigo del responsable actual
		//                 $as_codresnew //codigo del nuevo responsable
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza las operaciones asociadas al cambio de un responsable 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/04/2006 								Fecha Última Modificación : 03/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->io_sql->begin_transaction();
		$lb_valido=$this->uf_saf_insert_entregaunidad($as_codemp,$as_cmpent,$ad_fecentuni,$as_coduniadm,$as_obsentuni,$as_codusureg,$as_codres,$as_codresnew,$aa_seguridad);
		if($lb_valido)
		{
			$rs_data="";
			$lb_valido=$this->uf_saf_select_unidadxresponsable($as_codemp,$as_codres,$as_coduniadm,$rs_data);
			if($lb_valido)
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$as_codact=$row["codact"];
					$as_ideact=$row["ideact"];
					$lb_valido=$this->uf_saf_update_dta($as_codemp,$as_codact,$as_ideact,$as_codresnew,$aa_seguridad);
					if(!$lb_valido)
					{break;}
				}
			}
		}
		if($lb_valido)
		{
			$this->io_sql->commit();
			$this->io_msg->message("El proceso se registró con Éxito".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$this->io_sql->rollback();
			$this->io_msg->message("Ocurrió un error durante el registro".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	} // end  function uf_saf_procesar_cambioresponsable
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	function uf_saf_update_cambioresponsable($as_codemp,$as_codalm,$as_nomfisalm,$as_desalm,$as_telalm,$as_ubialm,$as_nomresalm,$as_telresalm,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_cambioresponsable
		//         Access: public (tepuy_siv_d_almacen)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_cmpmov //Nº del Comprobante del Movimiento
		//                 $ad_feccam //fecha de el cambio de responsable
		//                 $as_obstra //observaciones del cambio de responsable
		//                 $as_codusureg //codigo del usuario que esta haciendo el cambio de responsable
		//                 $as_codres // codigo del responsable actual
		//                 $as_codresnew //codigo del nuevo responsable
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que actualiza un  almacen existente en la tabla de  siv_almacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/04/2006 								Fecha Última Modificación : 03/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 	// **********************FALTA********************************
		$lb_valido=true;
		$ls_sql = "UPDATE saf_cambioresponsable SET   nomfisalm='". $as_nomfisalm ."',".
					" desalm='". $as_desalm ."',".
					" telalm='". $as_telalm ."', ". 
					" ubialm='". $as_ubialm ."', ". 
					" nomresalm='". $as_nomresalm ."',".
					" telresalm='". $as_telresalm ."'".
					" WHERE codalm='" . $as_codalm ."'".
					" AND codemp='" . $as_codemp ."'";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->cambioresponsable MÉTODO->uf_saf_update_cambioresponsable ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Almacén ".$as_codalm." Asociado a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	    return $lb_valido;
	} // end  function uf_saf_update_cambioresponsable


} 
?>
