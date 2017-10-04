<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/tepuy_c_seguridad.php");
require_once("../shared/class_folder/class_funciones.php");

class tepuy_saf_c_condicion
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function tepuy_saf_c_condicion()
	{
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new tepuy_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new tepuy_c_seguridad();
		$this->io_funcion = new class_funciones();
	}//fin de la function tepuy_saf_c_metodos()
	
	function uf_saf_select_condicion($as_codigo)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_saf_select_condicion
	//	Access:    public
	//	Arguments:
	//  $as_codigo    // codigo de condicion del bien
	//	Returns:		$lb_valido-----> true: encontrado false: no encontrado
	//	Description:  Esta funcion busca una condicion en la tabla de  saf_conservacionbien
	//              
	//////////////////////////////////////////////////////////////////////////////		
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_cadena="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "SELECT * FROM saf_conservacionbien  ".
				  " WHERE codconbie='".$as_codigo."'" ;
		
			
		$li_exec=$this->io_sql->select($ls_sql);

		if($li_exec===false)
		{
			$this->io_msg->message("CLASE->condicion MÉTODO->uf_saf_select_condicion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($li_exec))
			{
				$lb_valido=true;
				
			}
			else
			{
				$lb_valido=false;
			}
		}
			
		$this->io_sql->free_result($li_exec);
		return $lb_valido;
	
	}//fin de la function uf_saf_select_condicion

	function  uf_saf_insert_condicion($as_codigo,$as_denominacion,$as_descripcion,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_saf_insert_condicion
	//	Access:    public
	//	Arguments:
	//  $as_codigo    // codigo de condicion del bien
	//  as_denominacion // denominacion de la condicion del bien
	//  as_descripcion       // descricion de la condicion del bien
	//  aa_seguridad   // arreglo de registro de seguridad
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion inserta una condicion del bien en la tabla de  saf_conservacionbien
	//              
	//////////////////////////////////////////////////////////////////////////////		
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO saf_conservacionbien (codconbie, denconbie, desconbie) ".
					" VALUES('".$as_codigo."','".$as_denominacion."','".$as_descripcion."')" ;
		
			
		$li_exec=$this->io_sql->execute($ls_sql);
			
		if($li_exec===false)
		{
			$this->io_msg->message("CLASE->condicion MÉTODO->uf_saf_insert_condicion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Condición del bien ".$as_codigo;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		
		return $lb_valido;

	}//fin de la uf_saf_insert_condicion

	function uf_saf_update_condicion($as_codigo,$as_denominacion,$as_descripcion,$aa_seguridad) 
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_saf_update_condicion
	//	Access:    public
	//	Arguments:
	//  $as_codigo      // codigo de condicion del bien
	//  as_denominacion // denominacion de la condicion del bien
	//  as_descripcion  // descricion de la condicion del bien
	//  aa_seguridad    // arreglo de registro de seguridad
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion modifica una condicion del bien en la tabla de  saf_conservacionbien
	//              
	//////////////////////////////////////////////////////////////////////////////		
	 $lb_valido=true;
	 $ls_cadena="";
	 $li_exce=-1;
	
	 $ls_sql = "UPDATE saf_conservacionbien SET   denconbie='". $as_denominacion ."', desconbie='". $as_descripcion ."'". 
	 		   "WHERE codconbie='" . $as_codigo ."' ";
	 
        $this->io_sql->begin_transaction();
		$li_exec = $this->io_sql->execute($ls_sql);
		if($li_exec===false)
		{
			$this->io_msg->message("CLASE->condicion MÉTODO->uf_saf_update_condicion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la Condición del bien ".$as_codigo;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}

 
	  return $lb_valido;

	}// fin de la function uf_sss_update_condicion

	function uf_saf_delete_condicion($as_codigo,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_saf_delete_condicion
	//	Access:    public
	//	Arguments:
	//  $as_codigo     // codigo de condicion del bien
	//  aa_seguridad   // arreglo de registro de seguridad
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion elimina una condicion en la tabla de  saf_conservacionbien
	//              
	//////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$ib_db_error = false;
		$this->is_msg_error = "";
		$msg=new class_mensajes();
		$ls_sql = " DELETE FROM saf_conservacionbien".
					 " WHERE codconbie= '".$as_codigo. "'"; 
	
		$this->io_sql->begin_transaction();	
		$li_exec=$this->io_sql->execute($ls_sql);

		if($li_exec===false)
		{
			$this->io_msg->message("CLASE->condicion MÉTODO->uf_saf_delete_condicion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Condición del bien ".$as_codigo;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
			$this->io_sql->commit();
		}
			
		 		
		return $lb_valido;
	
	} //fin de uf_saf_delete_condicion

}//fin de la class tepuy_saf_c_condicion
?>
