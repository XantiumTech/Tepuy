<?PHP
class tepuy_sob_c_obra
{
	var $io_funcion;
	var $is_msg_error;
	var $io_sql;
	var $is_empresa;
	var $io_msg;
	var $io_funsob;
		
	function tepuy_sob_c_obra()
	{						
		require_once ("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_funciones.php");		
		require_once ("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		$this->seguridad=   new tepuy_c_seguridad();
		$this->io_function = new class_funciones();	
		require_once("class_folder/tepuy_sob_c_funciones_sob.php");
		$this->io_funsob=new tepuy_sob_c_funciones_sob();			
		$io_include=new tepuy_include();
		$io_connect=$io_include->uf_conectar();		
		$this->io_sql= new class_sql($io_connect);					
		$this->is_empresa=$_SESSION["la_empresa"];
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_msg=new class_mensajes();
	}
	
	function uf_select_partida ($as_codigo)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_partida
		//  Access:			public
		//	Returns:		Boolean, Retorna true si existe el registro en bd
		//	Description:	Funcion que se encarga de verificar si existe o no el tipo de partida.
		//  Fecha:          08/03/2006
		//	Autor:          Ing. Miguel Palencia
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_empresa=$this->is_empresa["codemp"];
		$ls_sql="SELECT * 
				 FROM sob_partida 
				 WHERE codpar='".$as_codigo."' AND codemp='".$ls_empresa."'";
		
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
	
	
	function uf_guardar_partida($as_codpar,$as_nompar,$as_coduni,$ad_prepar,$as_codcovpar,$as_codcatpar,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_guardar_partida
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de guardar el tipo de partida.
		//  Fecha:          08/03/2006
		//	Autor:          Ing. Miguel Palencia
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ad_prepar=$this->io_funsob->uf_convertir_cadenanumero($ad_prepar);
		$ls_empresa=$this->is_empresa["codemp"];
		$lb_existe=$this->uf_select_partida ($as_codpar);
		if(!$lb_existe)
		{
			$ls_sql="INSERT INTO sob_partida (codemp,codpar,nompar,despar,coduni,prepar,codcovpar,codcatpar)
						VALUES ('".$ls_empresa."','".$as_codpar."','".$as_nompar."','".$as_nompar."','".$as_coduni."','".$ad_prepar."','".$as_codcovpar."','".$as_codcatpar."')";					
		}
		else
		{
			$ls_sql="UPDATE sob_partida
						SET nompar='".$as_nompar."', despar='".$as_despar."', coduni='".$as_coduni."',prepar='".$ad_prepar."', codcovpar='".$as_codcovpar."',codcatpar='".$as_codcatpar."' 
						WHERE codpar='".$as_codpar."' AND codemp='".$ls_empresa."'";
		}
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->is_msg_error="Error en metodo uf_guardar_partida".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->is_msg_error;
			
		}
		else
		{
			if($li_row>0)
			{				
				if (!$lb_existe)
				{					
					$this->is_msg_error="Registro Incluido!!!";
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó la Partida ".$as_codpar." Asociada a la Empresa ".$ls_empresa;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
				else
				{
					$this->is_msg_error="Registro Modificado!!!";
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó la Partida ".$as_codpar." Asociada a la Empresa ".$ls_empresa;
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
				if(!$lb_existe) 
				{
					$this->is_msg_error="Registro no Incluido!!!";
						
				}	
				else
				{
					$lb_valido=0;
				}			
			}
		
		}		
		return $lb_valido;
	}
	
	function uf_detectar_dependencia($as_codpar)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_detectar_dependencia
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de determinar si una partida esta siendo utilizada en otra tabla.
		//  Fecha:          17/04/2006
		//	Autor:          Ing. Miguel Palencia	
		//////////////////////////////////////////////////////////////////////////////
		$ls_empresa=$this->is_empresa["codemp"];
		$ls_cadena="SELECT codobr 
					FROM sob_partidaobra 
					WHERE codpar='".$as_codpar."' AND codemp='".$ls_empresa."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en consulta ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			return $lb_valido;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$this->is_msg_error="Esta Partida no puede ser Eliminada, esta siendo utilizada por una Obra!!!";
				$lb_valido=0;
				return $lb_valido;
			}
			else
			{
				$lb_valido=1;
				return $lb_valido;
			}
		}
	
	}
	
	function uf_eliminar_partida($as_codigo,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_eliminar_partida
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de eliminar el tipo de partida.
		//  Fecha:          08/03/2006
		//	Autor:          Ing. Miguel Palencia	
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ls_empresa=$this->is_empresa["codemp"];
		$lb_existe=$this-> uf_select_partida ($as_codigo);
		if ($lb_existe)
		{
			$lb_permitirdelete=$this->uf_detectar_dependencia($as_codigo);
			if($lb_permitirdelete===1)
			{			
				$ls_sql="DELETE FROM sob_partida
							WHERE codpar='".$as_codigo."' AND codemp='".$ls_empresa."'";		
				$this->io_sql->begin_transaction();
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$this->io_sql->rollback();
					$this->is_msg_error="Error en metodo uf_eliminar_partida".$this->io_function->uf_convertirmsg($this->io_sql->message);
					print $this->is_msg_error;
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó la Partida ".$as_codigo." Asociada a la Empresa ".$ls_empresa;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////			
					$this->io_sql->commit();
				}
			}
			else
			{
				if($lb_permitirdelete===0)
				{
					$this->io_msg->message($this->is_msg_error);
					$lb_valido=0;
				}
			}
		}
		else
		{
			$this->io_msg->message("El Registro no Existe");
		}
		return $lb_valido;		
	}	
}
?>
