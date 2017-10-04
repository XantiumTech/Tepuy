<?php
class tepuy_soc_c_modcla
{
var $ls_sql;
	
		function tepuy_soc_c_modcla($conn)
		{
		  require_once("../../shared/class_folder/tepuy_c_seguridad.php");	  
          require_once("../../shared/class_folder/class_funciones.php");		  
		  require_once("../../shared/class_folder/class_mensajes.php");
		  $this->seguridad  = new tepuy_c_seguridad();		
		  $this->io_funcion = new class_funciones();
		  $this->io_sql     = new class_sql($conn);
		  $this->io_msg     = new class_mensajes();
		}
 
function uf_insert_modalidad($as_codemp,$as_codigo,$as_denominacion,$ar_grid,$ai_total,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_insert_modalidad
//	          Access:  public
// 	       Arguments:  
//        $as_codemp:.
//        $as_codigo:.
//     $as_codtipser:.
//  $as_denominacion:.
//        $ad_precio:.
//     $as_spgcuenta:.
//   		$ar_grid:.
//         $ai_total:.
//     $aa_seguridad:.
//	         Returns:  $lb_valido.
//	     Description:  Funci�n que se encarga de insertar una nueva modalidad en la tabla soc_modalidadclausulas. 
//     Elaborado Por:  Ing. N�stor Falc�n.
// Fecha de Creaci�n:  20/02/2006       Fecha �ltima Actualizaci�n:09/03/2006.	 
//////////////////////////////////////////////////////////////////////////////  

  $ls_sql = "INSERT INTO soc_modalidadclausulas (codemp, codtipmod, denmodcla) VALUES ('".$as_codemp."','".$as_codigo."','".$as_denominacion."')";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->tepuy_SOC_C_MODCLA; METODO->uf_insert_modalidad; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
		if ($this->uf_insert_dt_modalidad($as_codemp,$as_codigo,$ar_grid,$ai_total,$aa_seguridad))
		   {
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insert� en SOC la Modalidad ".$as_codigo." con denominaci�n ".$as_denominacion;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////	
		  }
	 }
return $lb_valido;
}

function uf_update_modalidad($as_codemp,$as_codigo,$as_denominacion,$ar_grid,$ai_total,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_update_modalidad
//	          Access:  public
//	       Arguments: 
//        $as_codemp:
//        $as_codigo:
//  $as_denominacion:
//          $ar_grid:
//         $ai_total:
//     $aa_seguridad: 
//	         Returns:  $lb_valido.
//	     Description:  Funci�n que se encarga de actualizar los datos de una modalidad en la tabla soc_modalidadclausulas.  
//     Elaborado Por:  Ing. N�stor Falc�n.
// Fecha de Creaci�n:  20/02/2006       Fecha �ltima Actualizaci�n:09/03/2006.	 
//////////////////////////////////////////////////////////////////////////////  

  $lb_valido=false;
  $ls_sql=" UPDATE soc_modalidadclausulas ".
		  "    SET denmodcla='".$as_denominacion."'".
		  "  WHERE codemp='".$as_codemp."' AND codtipmod='".$as_codigo."'";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
  	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->tepuy_SOC_C_MODCLA; METODO->uf_update_modalidad; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
		if ($this->uf_delete_modxcla($as_codemp,$as_codigo,$aa_seguridad))
		   {                  
		   if ($this->uf_insert_dt_modalidad($as_codemp,$as_codigo,$ar_grid,$ai_total,$aa_seguridad))
		      {                        
			    $lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualiz� en SOC la Modalidad ".$as_codigo;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												 $aa_seguridad["ventanas"],$ls_descripcion);
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////
		     }
		}
	 }
return $lb_valido;
} 

function uf_delete_modalidad($as_codemp,$as_codigo,$aa_seguridad)
{          		 
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_modalidad
//	          Access:  public
//	        Arguments 
//        $as_codemp:  C�digo de la Empresa.
//        $as_codigo:
//     $aa_seguridad:
//	         Returns:  $lb_valido.
//	     Description:  Funci�n que se encarga de eliminar una modalidad en la tabla soc_modalidadclausulas.  
//     Elaborado Por:  Ing. N�stor Falc�n.
// Fecha de Creaci�n:  20/02/2006       Fecha �ltima Actualizaci�n:09/03/2006.	 
//////////////////////////////////////////////////////////////////////////////  

   if ($this->uf_delete_modxcla($as_codemp,$as_codigo,$aa_seguridad))
      {
	    $ls_sql  = "DELETE FROM soc_modalidadclausulas WHERE codemp='".$as_codemp."' AND codtipmod='".$as_codigo."'";	      
	    $rs_data = $this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		   {
				$lb_valido=false;
	            $this->io_msg->message("CLASE->tepuy_SOC_C_MODCLA; METODO->uf_delete_modalidad; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   }
		else
		   {
		     $lb_valido=true;
			 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			 $ls_evento="DELETE";
			 $ls_descripcion ="Elimin� en SOC la Modalidad ".$as_codigo;
			 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											 $aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
	       }
 	  }
   return $lb_valido;
}

function uf_delete_modxcla($as_codemp,$as_codigo,$aa_seguridad)
{          		 
//////////////////////////////////////////////////////////////////////////////
//	          Metodo: uf_delete_modxcla
//	          Access:  public
//	       Arguments:  
//        $as_codemp: 
//        $as_codigo: 
//     $aa_seguridad:
//	         Returns:  $lb_valido.
//	     Description:  Funci�n que se encarga de eliminar las modalidades por clausulas en la tabla soc_dtm_clausulas.  
//     Elaborado Por:  Ing. N�stor Falc�n.
// Fecha de Creaci�n:  20/02/2006       Fecha �ltima Actualizaci�n:09/03/2006.	 
//////////////////////////////////////////////////////////////////////////////  

  $lb_valido = false;        
  $ls_sql    = "DELETE FROM soc_dtm_clausulas WHERE codemp='".$as_codemp."' AND codtipmod='".$as_codigo."'";	 
  $this->io_sql->begin_transaction();
  $rs_data   = $this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
       $lb_valido=false;
	   $this->io_msg->message("CLASE->tepuy_SOC_C_MODCLA; METODO->uf_delete_modxcla; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="DELETE";
		$ls_descripcion ="Elimin� las Clausulas asociados a la Modalidad ".$as_codigo;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////*/            
	   $lb_valido=true;
	 } 		 
  return $lb_valido;
}

function uf_select_modalidad($as_codemp,$as_codigo) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_modalidad
// 	          Access:  public
//	       Arguments:  
//        $as_codemp:.
//        $as_codigo:.
//	         Returns:  $lb_valido.
//	     Description:  Funci�n que se encarga de verificar si existe o no una modalidad, la funcion devuelve true si el
//                     registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. N�stor Falc�n.
// Fecha de Creaci�n:  20/02/2006       Fecha �ltima Actualizaci�n:09/03/2006.	 
//////////////////////////////////////////////////////////////////////////////  
  $lb_valido = false;
  $ls_sql  = "SELECT codtipmod FROM soc_modalidadclausulas WHERE codemp='".$as_codemp."' AND codtipmod='".$as_codigo."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	 }
  else
	 {
	   $li_numrows=$this->io_sql->num_rows($rs_data);
	   if ($li_numrows>0)
		  {
	        $lb_valido=true;
            $this->io_sql->free_result($rs_data);
 		  }
	 } 
  return $lb_valido;
}

function uf_insert_dt_modalidad($as_codemp,$as_codigo,$ar_grid,$ai_total,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	         Funcion: uf_insert_dt_modalidad
//	          Access:  public
// 	       Arguments:  
//        $as_codemp:.
//        $as_codigo:.
//          $ar_grid:.
//         $ai_total:.
//     $aa_seguridad:.
//	         Returns:  $lb_valido.
//	     Description:  Funci�n que se encarga de insertar detalles para una modalidad en la tabla soc_dtm_clausulas. 
//     Elaborado Por:  Ing. N�stor Falc�n.
// Fecha de Creaci�n:  20/02/2006       Fecha �ltima Actualizaci�n:09/03/2006.	 
//////////////////////////////////////////////////////////////////////////////

	 $lb_valido=true;
	 for($i=1;$i<=$ai_total;$i++)
	   {
		  if ($lb_valido)
		     {
			 $ls_codcla=$ar_grid["clausula"][$i];    
             if(!empty($ls_codcla))			            
			   {
				 $ls_sql=" INSERT INTO soc_dtm_clausulas (codemp, codtipmod, codcla) VALUES ('".$as_codemp."','".$as_codigo."','".$ls_codcla."')"; 
				 $rs_data=$this->io_sql->execute($ls_sql);              
				 if ($rs_data===false)
					{				 
					  $lb_valido=false;  
					  $this->io_msg->message('Error en Insertar Cargos Para el Servicio !!!');  			     	
					  $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
					  $this->io_sql->rollback();
					}
				else
					{				 
					  $lb_valido=true;  		                    
					  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
					  $ls_evento      ="INSERT";
					  $ls_descripcion =" Insert� en SOC cargos asociados al Servicio ".$as_codigo;
					  $ls_variable    = $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
					  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
					  $aa_seguridad["ventanas"],$ls_descripcion);
					  /////////////////////////////////         SEGURIDAD               ///////////////////////////// 
				  }  				
		     }
		  }
	  } 
return $lb_valido;
}
}//Fin de la Clase...
?> 