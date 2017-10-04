<?
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - tepuy_sob_c_tipoobra
 // Autor:       - Juniors Fraga.		
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con 
 //                respecto a la tabla tipo obra.
 // Fecha:       - 10/03/2006     
 //////////////////////////////////////////////////////////////////////////////////////////
class tepuy_sob_c_tipoobra
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;
 
 
function tepuy_sob_c_tipoobra()
{
	require_once ("../shared/class_folder/tepuy_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/tepuy_c_seguridad.php");
	$this->seguridad=   new tepuy_c_seguridad();
	$this->io_funcion = new class_funciones();		
	$io_include = new tepuy_include();
	$io_connect = $io_include->uf_conectar();		
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];	
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();
}

		
	function uf_select_tobra($ls_codtob)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).		
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////	
	
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sob_tipoobra 
		            WHERE codemp='".$ls_codemp."' AND codtob='".$ls_codtob."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en consulta ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
	}
	

	function uf_guardar_tobra($ls_codtob,$ls_nomtob,$ls_destob,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_unidad
		// Parameters:  - $ls_coduni( Codigo de la Unidad de Medida).		
		//			    - $ls_codtun( Codigo de el tipo de Unidad de Medida). 	
		//			    - $ls_nomuni( Nombre de la Unidad).
		//              - $ls_desuni( Breve Descripcion de la Unidad).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_tobra($ls_codtob);

		if(!$lb_existe)
		{
            $ls_cadena= " INSERT INTO sob_tipoobra(codemp,codtob,nomtob,destob) 
			              VALUES ('".$ls_codemp."','".$ls_codtob."','".$ls_nomtob."','".$ls_destob."') ";
			$this->io_msgc="Registro Incluido";		
		}
		else
		{
			$ls_cadena= "UPDATE sob_tipoobra 
			             SET nomtob='".$ls_nomtob."', destob='".$ls_destob."' 
						 WHERE codemp='".$ls_codemp."' AND codtob='".$ls_codtob."'";
			$this->io_msgc="Registro Actualizado";
		}

		$this->io_sql->begin_transaction();

		$li_numrows=$this->io_sql->execute($ls_cadena);

		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_conceptos".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;
				if(!$lb_existe)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó el Tipo de Obra ".$ls_codtob." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó el Tipo de Obra ".$ls_codtob." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
				if(!$lb_existe)
				{
					$lb_valido=false;
					$this->io_msgc="No inserto el registro";
				}
				else
				{
					$lb_valido=0;					
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
		//	Description:	Funcion que se encarga de determinar si un Tipo de Obra esta siendo utilizado en otra tabla.
		//  Fecha:          17/04/2006
		//	Autor:          Ing. Laura Cabré	
		//////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT codobr 
					FROM sob_obra 
					WHERE codtob='".$as_codigo."' AND codemp='".$ls_codemp."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni===false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en consulta ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			print $this->io_msgc;	
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$this->io_msgc="Este Tipo de Obra no puede ser eliminado, esta siendo utilizado por una Obra!!!";
				$lb_valido=0;				
			}
			else
			{
				$lb_valido=1;				
			}
		}
		return $lb_valido;
	
	}

	function uf_delete_tobra($ls_codtob,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).		
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_tobra($ls_codtob);
		
		if(($lb_existe))
		{
			$lb_permitirdelete=$this->uf_detectar_dependencia($ls_codtob);
			if($lb_permitirdelete===1)
			{
				$ls_cadena= " DELETE FROM sob_tipoobra 
							  WHERE codemp='".$ls_codemp."' AND codtob='".$ls_codtob."'";
				$this->io_msgc="Registro Eliminado!!!";		
	
				$this->io_sql->begin_transaction();
	
				$li_numrows=$this->io_sql->execute($ls_cadena);
	
				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_conceptos ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msgc;
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminó el Tipo de Obra ".$ls_codtob." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->io_msgc="Registro No Eliminado!!!";
						$this->io_sql->rollback();
					}
	
				}
			}
			elseif($lb_permitirdelete===0)
			{
				$this->io_msg->message($this->io_msgc);
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
