<?php
require_once("../shared/class_folder/class_sql.php");
class tepuy_sfa_c_tipo_producto
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function tepuy_sfa_c_tipo_producto()
	{
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		$this->io_msg=new class_mensajes();
		$this->io_funcion = new class_funciones();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new tepuy_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new tepuy_c_seguridad();
	}
	
	function uf_sfa_select_tipo_producto($as_codtippro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_select_tipo_producto
		//         Access: public (tepuy_sfa_d_tipo_producto)
		//      Argumento: $as_codtippro    // codigo de tipo de Producto
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un tipo de Producto en la tabla de  sfa_tipoproducto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/02/2006							Fecha �ltima Modificaci�n: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sfa_tipoproducto  ".
				  " WHERE codtippro='".$as_codtippro."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipo_producto M�TODO->uf_sfa_select_tipo_producto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_sfa_select_tipo_producto

	function  uf_sfa_insert_tipo_producto($as_codtippro,$as_dentippro,$as_obstippro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_insert_tipo_producto
		//         Access: public (tepuy_sfa_d_tipo_producto)
		//      Argumento: $as_codtippro   // codigo de tipo de Producto
	    //                 $as_dentippro   // denominacion de tipo de Producto
	    //                 $as_obstippro   // observacion de tipo de Producto
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un tipo de Producto en la tabla de sfa_tipoproducto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/02/2006							Fecha �ltima Modificaci�n: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO sfa_tipoproducto (codtippro, dentippro, obstippro) ".
					" VALUES('".$as_codtippro."','".$as_dentippro."','".$as_obstippro."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipo_producto M�TODO->uf_sfa_insert_tipo_producto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insert� el Tipo de Producto ".$as_codtippro;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_sfa_insert_tipo_producto

	function uf_sfa_update_tipo_producto($as_codtippro,$as_dentippro,$as_obstippro,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_update_tipo_producto
		//         Access: public (tepuy_sfa_d_tipo_producto)
		//      Argumento: $as_codtippro   // codigo de tipo de Producto
	    //                 $as_dentippro   // denominacion de tipo de Producto
	    //                 $as_obstippro   // observacion de tipo de Producto
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un tipo de Producto en la tabla de sfa_tipoproducto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/02/2006							Fecha �ltima Modificaci�n: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE sfa_tipoproducto SET   dentippro='". $as_dentippro ."',obstippro='". $as_obstippro ."' ". 
				   " WHERE codtippro='" . $as_codtippro ."' ";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tipo_producto M�TODO->uf_sfa_update_tipo_producto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modific� el Tipo de Producto ".$as_codtippro;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_sfa_update_tipo_producto

	function uf_sfa_delete_tipo_producto($as_codtippro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_delete_tipo_producto
		//         Access: public (tepuy_sfa_d_tipo_producto)
		//      Argumento: $as_codtippro   // codigo de tipo de Producto
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un tipo de Producto en la tabla de sfa_tipoproducto verificando que este no este siendo
		//				   utilizado por ningun Producto.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/02/2006							Fecha �ltima Modificaci�n: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_sfa_select_productoproducto($as_codtippro);
		if($lb_existe)
		{
			$this->io_msg->message("El tipo de Producto tiene Productos asociados");		
			$lb_valido=false;
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM sfa_tipoproducto".
						 " WHERE codtippro= '".$as_codtippro. "'"; 
			//print $ls_sql;
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->tipo_producto M�TODO->uf_sfa_delete_tipo_producto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Elimin� el Tipo de Producto ".$as_codtippro;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return $lb_valido;
	} // end function uf_sfa_delete_tipo_producto

	function uf_sfa_select_productoproducto($as_codtippro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_tipoarticuloarticulo
		//         Access: private
		//      Argumento: $as_codtippr   // codigo de tipo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen articulos que estan utilizando el tipo de articulo seleccionado
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/02/2006							Fecha �ltima Modificaci�n: 01/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sfa_producto  ".
				  " WHERE codtippro='".$as_codtippro."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tipoproducto M�TODO->uf_sfa_select_tipoproductoproducto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_sfa_select_tipoproductproducto
	


}// end   class tepuy_sfa_c_tipo_producto
?>
