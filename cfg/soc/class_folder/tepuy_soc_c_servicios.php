<?php
class tepuy_soc_c_servicios
{
var $ls_sql;
var $is_msg_error;
	
	function tepuy_soc_c_servicios($conn)
	{
	  require_once("../../shared/class_folder/tepuy_c_seguridad.php");
	  require_once("../../shared/class_folder/class_funciones.php");
	  require_once("../../shared/class_folder/class_mensajes.php");
	  $this->seguridad  = new tepuy_c_seguridad();		           
	  $this->io_funcion = new class_funciones();		  
	  $this->io_sql     = new class_sql($conn);
	  $this->io_msg     = new class_mensajes();
	}

function uf_insert_servicio($as_codemp,$as_codigo,$as_codtipser,$as_denominacion,$ad_precio,$as_spgcuenta,$ar_grid,$ai_total,$as_unimed,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_insert_servicio
// 	          Access:  public
//	       Arguments:  
//        $as_codemp=.
//        $as_codigo=.
//     $as_codtipser=.
//  $as_denominacion=.
//        $ad_precio=.
//     $as_spgcuenta=.
//          $ar_grid=.
//         $ai_total=.
//     $aa_seguridad=.
// 	         Returns:		
//	     Description:  Funcion que carga los valores traidos en la carga de datos desde el $ar_datos y asigna el valor respectivo a cada 
//			           variable y a realiza una busqueda para decidir si el registro ya existe para actualizarlo "UPDATE"o si el registro no existe realizar un "INSERT". 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
		  
  $ad_precio=str_replace('.','',$ad_precio);
  $ad_precio=str_replace(',','.',$ad_precio);
  $ls_sql = " INSERT INTO soc_servicios ".
            " (codemp, codser, codtipser, denser, preser, spg_cuenta, codunimed) ".
			" VALUES ('".$as_codemp."','".$as_codigo."','".$as_codtipser."','".$as_denominacion."',".$ad_precio.",'".$as_spgcuenta."','".$as_unimed."')";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->tepuy_SOC_C_SERVICIO; METODO->uf_insert_servicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
		if ($this->uf_insert_dtcargos($as_codemp,$as_codigo,$ar_grid,$ai_total,$aa_seguridad))
		   {
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insertó en SOC el Servicio ".$as_codigo." con denominación ".$as_denominacion;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////	
		  }
	 }
return $lb_valido;
}

function uf_update_servicio($as_codemp,$as_codser,$as_codtipser,$as_denser,$ad_precio,$as_spgcuenta,$ar_grid,$ai_total,$as_unimed,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo: uf_update_servicio
//	          Access:  public
//		   Arguments:  
//        $as_codemp=.
//        $as_codser=.
//     $as_codtipser=.
//  	  $as_denser=.
//  	  $ad_precio=.
//     $as_spgcuenta=.
//  		$ar_grid=.
//  	   $ai_total=.
//     $aa_seguridad=.		
//	         Returns:  $lb_valido.
//	     Description:  Funcion que se encarga de actualizar los datos de un servicio en la tabla soc_servicios. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 

  $ad_precio=str_replace('.','',$ad_precio);
  $ad_precio=str_replace(',','.',$ad_precio);

  $ls_sql=" UPDATE soc_servicios ".
		  " SET codtipser='".$as_codtipser."',denser='".$as_denser."',preser='".$ad_precio."', ".
		  " spg_cuenta='".$as_spgcuenta."', codunimed='".$as_unimed."' ".
		  " WHERE codemp='".$as_codemp."' AND codser='".$as_codser."'";

  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
  	   $this->io_msg->message("CLASE->tepuy_SOC_C_SERVICIO; METODO->uf_update_servicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
		if ($this->uf_delete_cargosxservicio($as_codemp,$as_codser,$aa_seguridad))
		   {                  
		   if ($this->uf_insert_dtcargos($as_codemp,$as_codser,$ar_grid,$ai_total,$aa_seguridad))
		      {                        
			    $lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó en SOC el Servicio ".$as_codser;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												 $aa_seguridad["ventanas"],$ls_descripcion);
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////
		     }
		}
	 }
return $lb_valido;
} 

function uf_delete_servicio($as_codemp,$as_codser,$as_denser,$aa_seguridad)
{          		 
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_servicio
//	          Access:  public
//	       Arguments:
//        $as_codemp:.
//        $as_codser:.
//        $as_denser:.
//     $aa_seguridad:.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de eliminar un servicio en la tabla soc_servicios. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  
  if ($this->uf_delete_cargosxservicio($as_codemp,$as_codser,$aa_seguridad))
     {
	   $ls_sql  = " DELETE FROM soc_servicios WHERE codemp='".$as_codemp."' AND codser='".$as_codser."'";	           
	   $rs_data = $this->io_sql->execute($ls_sql);
	   if ($rs_data===false)
		  { 
		    $lb_valido=false;
		    $this->is_msg_error="CLASE->tepuy_SOC_C_SERVICIO; METODO->uf_delete_servicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		  }
	   else
		  { 
		    $lb_valido=true;
		    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		    $ls_evento="DELETE";
		    $ls_descripcion ="Eliminó en SOC el Servicio ".$as_codser. " con denominacion ".$as_denser;
		    $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		    /////////////////////////////////         SEGURIDAD               /////////////////////////////
	 	  }  		 
     }  
  return $lb_valido;
}

function uf_delete_cargosxservicio($as_codemp,$as_codser,$aa_seguridad)
{          		 
//////////////////////////////////////////////////////////////////////////////
//	          Metodo: uf_delete_cargosxservicio
//	          Access:  public
//	       Arguments:  
//        $as_codemp:.
//        $as_codser:.
//     $aa_seguridad:.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de eliminar los cargos asociados a un servicio en la tabla soc_serviciocargo. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 

  $lb_valido=false;        
  $ls_sql = " DELETE FROM soc_serviciocargo WHERE codemp='".$as_codemp."' AND codser='".$as_codser."'";	    
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
       $lb_valido=false;
	   $this->io_sql->rollback();
 	   $this->is_msg_error="CLASE->tepuy_SOC_C_SERVICIO; METODO->uf_delete_cargosxservicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	 }
  else
	 {
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="DELETE";
		$ls_descripcion ="Eliminó los Cargos asociados al Servicio ".$as_codser;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////*/            
	   $lb_valido=true;
	 } 		 
  return $lb_valido;
}

function uf_select_servicio($as_codemp,$as_codigo) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_servicio
//	          Access:  public
//	       Arguments:  
//        $as_codemp:.
//        $as_codigo:.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existe o no un servicio, la funcion devuelve 
//                     true en caso de encontrarlo, caso contrario devuelve false. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $lb_valido = false;
  $ls_sql    = "SELECT codser FROM soc_servicios WHERE codemp='".$as_codemp."' AND codser='".$as_codigo."'";
  $rs_data   = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
       $lb_valido=false;
 	   $this->io_msg->message("CLASE->tepuy_SOC_C_SERVICIO; METODO->uf_select_servicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

function uf_insert_dtcargos($as_codemp,$as_codigo,$ar_grid,$ai_total,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	         Funcion:  uf_insert_dtcargos
//	          Access:  public
//	       Arguments:  
//        $as_codemp:.
//        $as_codigo:.
//          $ar_grid:.
//         $ai_total:.
//     $aa_seguridad:.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de insertar detalles de cargo para un servicio. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 

	 $lb_valido=true;
	 for($i=1;$i<=$ai_total;$i++)
	   {
		  if ($lb_valido)
		     {
			 $ls_codcar = $ar_grid["cargo"][$i];               
			 $ls_sql    = " INSERT INTO soc_serviciocargo (codemp, codcar, codser) VALUES ('".$as_codemp."','".$ls_codcar."','".$as_codigo."')";                                                       
			 $rs_data   = $this->io_sql->execute($ls_sql);              
			 if ($rs_data===false)
				{				 
				  $lb_valido=false;  
	              $this->io_msg->message("CLASE->tepuy_SOC_C_SERVICIO; METODO->uf_insert_dtcargos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				}
			else
				{				 
				  $lb_valido=true;  		                    
				  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
				  $ls_evento      ="INSERT";
				  $ls_descripcion =" Insertó en SOC cargos asociados al Servicio ".$as_codigo;
				  $ls_variable    = $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
				  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
				  $aa_seguridad["ventanas"],$ls_descripcion);
				  /////////////////////////////////         SEGURIDAD               ///////////////////////////// 
			  }  				
		  }
	  } 
return $lb_valido;
}
	 
function uf_insert_unidadmedida($as_codemp,$as_unimed,$as_denunimed,$as_unidad,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_insert_unidadmedida
// 	          Access:  public
//	       Arguments:  
//        $as_codemp=.
//        $as_unimed=.
//     $aa_seguridad=.
// 	         Returns:		
//	     Description:  función que inserta la unidad de medida en la tabla siv_unidadmedida 
//     Elaborado Por:  Ing. Gloriely Fréitez.
// Fecha de Creación:  29/05/2008       	 
////////////////////////////////////////////////////////////////////////////// 
   $ls_sql = " INSERT INTO siv_unidadmedida ".
            " (codunimed,denunimed,unidad,obsunimed,tiposep) ".
			" VALUES ('".$as_unimed."','".$as_denunimed."','".$as_unidad."','','S')"; 
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->tepuy_SOC_C_SERVICIO; METODO->uf_insert_unidadmedida; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
		  $lb_valido=true;
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insertó en SIV la unidad de medida ".$as_unimed." con denominación ".$as_denunimed;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////	
	 }
return $lb_valido;
}

function uf_select_unidadmedida($as_unimed,$la_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_unidadmedida
//	          Access:  public
//	       Arguments:  
//        $as_codigo:.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existe o no una unidad de medida, la funcion devuelve 
//                     true en caso de encontrarlo, caso contrario devuelve false. 
//     Elaborado Por:  Ing.Gloriely Fréitez.
// Fecha de Creación:  29/05/2008       	 
////////////////////////////////////////////////////////////////////////////// 
  $lb_valido = false;
  $ls_sql    = "SELECT codunimed FROM siv_unidadmedida WHERE codunimed='".$as_unimed."'";
  $rs_data   = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
       $lb_valido=false;
 	   $this->io_msg->message("CLASE->tepuy_SOC_C_SERVICIO; METODO->uf_select_unidadmedida; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
}//Fin de la Clase...
?> 