<?PHP
class tepuy_sob_c_sistemaconstructivo
{
	var $io_funcion;
	var $is_msg_error;
	var $io_sql;
	var $is_empresa;
	var $io_msg;
	
	function tepuy_sob_c_sistemaconstructivo()
	{						
		require_once ("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once ("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		$this->seguridad=   new tepuy_c_seguridad();
		$this->io_function = new class_funciones();		
		$io_include=new tepuy_include();
		$io_connect=$io_include->uf_conectar();		
		$this->io_sql= new class_sql($io_connect);		
		$this->is_empresa=$_SESSION["la_empresa"];
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_msg=new class_mensajes();
				
	}
	
	function uf_select_sistemaconstructivo ($as_codigo)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_sistemaconstructivo
		// Access:			public
		//	Returns:		Boolean, Retorna true si existe el registro en bd
		//	Description:	Funcion que se encarga de verificar si existe o no el tipo de Contrato.
		//  Fecha:          08/03/2006
		//	Autor:          Ing. Laura Cabr�
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_empresa=$this->is_empresa["codemp"];
		$ls_sql="SELECT * 
				 FROM sob_sistemaconstructivo 
				 WHERE codemp='".$ls_empresa."' AND codsiscon='".$as_codigo."'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			else
			{
				$this->is_msg_error="No encontro registro";
			}
		}
		return $lb_valido;
	}
	
	
	function uf_guardar_sistemaconstructivo($as_codigo,$as_tipo,$as_descripcion,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_guardar_sistemaconstructivo
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de guardar el tipo de Contrato.
		//  Fecha:          08/03/2006
		//	Autor:          Ing. Laura Cabr�	
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_existe=$this->uf_select_sistemaconstructivo ($as_codigo);
		$ls_empresa=$this->is_empresa["codemp"];
		if(!$lb_existe)
		{
			$ls_sql="INSERT INTO sob_sistemaconstructivo (codemp,codsiscon,nomsiscon,dessiscon)
						VALUES ('".$ls_empresa."','".$as_codigo."','".$as_tipo."','".$as_descripcion."')";			
		}
		else
		{
			$ls_sql="UPDATE sob_sistemaconstructivo
						SET nomsiscon='".$as_tipo."', dessiscon='".$as_descripcion."' 
						WHERE codsiscon='".$as_codigo."' AND codemp='".$ls_empresa."'";			
		}
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->is_msg_error="Error en metodo uf_guardar_sistemaconstructivo".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->is_msg_error;
			
		}
		else
		{
			if($li_row>0)
			{
				if(!$lb_existe)
				{
					$this->is_msg_error="Registro Incluido!!!";
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insert� el Sistema Constructivo ".$as_codigo." Asociado a la Empresa ".$ls_empresa;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					$this->is_msg_error="Registro Actualizado!!!";
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualiz� el Sistema Constructivo ".$as_codigo." Asociado a la Empresa ".$ls_empresa;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}				
				$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{
				
				$this->io_sql->rollback();
				if($lb_existe) 
				{
					$this->is_msg_error="No se actualizo el registro".$this->io_function->uf_convertirmsg($this->io_sql->message);
					print $this->is_msg_error;
				}
					else
					{
						$this->is_msg_error="No se incluyo el registro".$this->io_function->uf_convertirmsg($this->io_sql->message);
						print $this->is_msg_error;
					}				
			}
		
		}		
		return $lb_valido;
	}
	
	function uf_detectar_dependencia($as_codigo)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_detectar_dependencia
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de determinar si un sistema constructivo esta siendo utilizada en otra tabla.
		//  Fecha:          17/04/2006
		//	Autor:          Ing. Laura Cabr�	
		//////////////////////////////////////////////////////////////////////////////
		$ls_empresa=$this->is_empresa["codemp"];
		$ls_cadena="SELECT codobr 
					FROM sob_obra 
					WHERE codsiscon='".$as_codigo."' AND codemp='".$ls_empresa."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en consulta ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);			
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$this->is_msg_error="Este Sistema Constructivo no puede ser eliminado, esta siendo utilizado por una Obra!!!";
				$lb_valido=0;				
			}
			else
			{
				$lb_valido=1;				
			}
		}
		return $lb_valido;
	
	}
	
	function uf_eliminar_sistemaconstructivo($as_codigo,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_eliminar_sistemaconstructivo
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de eliminar el tipo de Contrato.
		//  Fecha:          08/03/2006
		//	Autor:          Ing. Laura Cabr�
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_empresa=$this->is_empresa["codemp"];
		$lb_existe=$this->uf_select_sistemaconstructivo($as_codigo);
		if($lb_existe)
		{
			$lb_permitirdelete=$this->uf_detectar_dependencia($as_codigo);
			if($lb_permitirdelete===1)
			{
				$ls_sql="DELETE FROM sob_sistemaconstructivo
							WHERE codsiscon='".$as_codigo."' AND codemp='".$ls_empresa."'";		
				$this->io_sql->begin_transaction();
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$this->io_sql->rollback();
					$this->is_msg_error="Error en metodo eliminar_sistemaconstructivo".$this->io_function->uf_convertirmsg($this->io_sql->message);
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Elimin� el Sistema Constructivo".$as_codigo." Asociado a la Empresa ".$ls_empresa;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$this->io_sql->commit();
				}
			}
			elseif($lb_permitirdelete===0)
			{
				$this->io_msg->message($this->is_msg_error);
				$lb_valido=0;
			}
		}
		else
		{
			$this->io_msg->message("El Registro no Existe!!!");
			$lb_valido=0;
		}
		return $lb_valido;		
	}
}
?>
