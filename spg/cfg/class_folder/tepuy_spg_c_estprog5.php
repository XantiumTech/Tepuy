<?php
class tepuy_spg_c_estprog5
{
var $is_msg_error;
	
		function tepuy_spg_c_estprog5($conn)
		{
		  require_once("../../shared/class_folder/tepuy_c_seguridad.php");
	      require_once("../../shared/class_folder/class_funciones.php");
          require_once("../../shared/class_folder/class_mensajes.php");
		  $this->io_seguridad  = new tepuy_c_seguridad();		  
		  $this->io_funcion    = new class_funciones();
		  $this->io_sql        = new class_sql($conn);
		  $this->io_msg        = new class_mensajes();		
		}

function uf_spg_select_estprog5($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_spg_select_estprog5
// 	        Arguments   
//   $as_codestprog1:
//   $as_codestprog2:
//   $as_codestprog3:
//   $as_codestprog4:
//   $as_codestprog5:
//   $as_denestprog5:
//        $as_evento:
//   $as_descripcion:
//	          Access:  public
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de insertar el quinto Nivel de la Estructura Programatica. 
//     Elaborado Por:  Ing. Nelson Barraez.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:08/09/2006.	 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = false;
  $ls_sql    = "SELECT * FROM spg_ep5 WHERE codemp='".$as_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$as_codestpro4."' AND codestpro5='".$as_codestpro5."'";
  $rs_data   = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
 	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->tepuy_SPG_C_ESTPROG5; METODO->uf_spg_select_estprog5; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $li_numrows = $this->io_sql->num_rows($rs_data);
	   if($li_numrows>0)
		 {
		   $lb_valido=true;
		   $this->io_sql->free_result($rs_data);
		 }
	 }
  return $lb_valido;
}

function uf_spg_insert_estprog5($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_denestpro5,$as_codfuefin,$ai_estmodest,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_spg_insert_estprog5
// 	        Arguments   
//        $as_codemp:  Código de la Empresa.
//    $as_codestpro1:  Código del Primer  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro2:  Código del Segundo Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro3:  Código del Tercer  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro4:  Código del Cuarto  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro5:  Código del Quinto  Nivel de la Estructura Presupuestaria o Programática.
//    $as_denestpro5:  Denominacion del código del Quinto  Nivel de la Estructura Presupuestaria o Programática.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc. 
//	          Access:  public
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de insertar el cuarto Nivel de la Estructura Programatica. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  13/09/2006       Fecha Última Actualización:13/09/2006.	 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	  if($as_codfuefin=='')
	  {
	  		$as_codfuefin='--';
	  }
	 $ls_sql = "INSERT INTO spg_ep5(codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,denestpro5,codfuefin) VALUES ('".$as_codemp."','".$as_codestpro1."','".$as_codestpro2."','".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."','".$as_denestpro5."','".$as_codfuefin."') ";
	 $this->io_sql->begin_transaction();
	 $rs_data = $this->io_sql->execute($ls_sql);
	 if ($rs_data===false)		     
	    {
		  $lb_valido          = false;
 	      $this->is_msg_error = "CLASE->tepuy_SPG_C_ESTPROG5; METODO->uf_spg_insert_estprog5; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		 }
	 else
		 {
		   $lb_valido      = true;
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento      = "INSERT";
		   $ls_descripcion = "Insertó en SPG Nuevo Estructura Presupuestaria/programatica ".$as_denestpro5." con codigo ".$as_codestpro3." asociado al Nivel 1 con ".$as_codestpro1." y con el Nivel 2 a ".$as_codestpro2." y al Nivel 3 con ".$as_codestpro3." y en el nivel 4 con ".$as_codestpro4;
		   $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////// 		     
         }
return $lb_valido;
}

function uf_spg_update_estprog5($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_denestpro5,$as_codfuefin,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_spg_update_estprog5
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  Código de la Empresa.
//    $as_codestpro1:  Código del Primer  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro2:  Código del Segundo Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro3:  Código del Tercer  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro4:  Código del Cuarto  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro5:  Código del Quinto  Nivel de la Estructura Presupuestaria o Programática.
//    $as_denestpro5:  Denominación del código del Quinto  Nivel de la Estructura Presupuestaria o Programática.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de modificar la denominacion de tercer nivel de una Estructura Presupuestaria o Programática, 
//                     la funcion devuelve true si el registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  12/09/2006       Fecha Última Actualización:12/09/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

  $ls_sql = " UPDATE spg_ep5 SET denestpro5='".$as_denestpro5."',codfuefin='".$as_codfuefin."' WHERE codemp='".$as_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$as_codestpro4."' AND codestpro5='".$as_codestpro5."'";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->tepuy_SPG_C_ESTPROG5; METODO->uf_spg_update_estprog5; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $lb_valido = true;
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento      = "UPDATE";
	   $ls_descripcion = " Actualizo la denominacion del codigo ".$as_codestpro5." en spg_ep5 asociado al codigo ".$as_codestpro1."en spg_ep1 y al codigo ".$as_codestpro2." en la tabla spg_ep2 y en spg_ep3 con ".$as_codestpro3." y en spg_ep4 con ".$as_codestpro4;
	   $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		     
     }  		      
return $lb_valido;
}

function uf_spg_delete_estpro5($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_denestpro5,$aa_seguridad)
{
  $lb_tiene  = $this->uf_check_relaciones($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5);
  $lb_existe = $this->uf_spg_select_estprog5($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5);
  $lb_valido = false;
  if (($lb_existe) && (!$lb_tiene))
     {
	   $ls_sql  = "DELETE FROM spg_ep5                                                            ".
	              " WHERE codemp='".$as_codemp."'         AND codestpro1='".$as_codestpro1."' AND ".
	              "       codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND ".
				  "       codestpro4='".$as_codestpro4."' AND codestpro5='".$as_codestpro5."'     ";
	   $rs_data = $this->io_sql->execute($ls_sql);
	   if ($rs_data===false)
	      {
		    $lb_valido = false;
			$this->is_msg->message("CLASE->tepuy_SPG_C_ESTPROG5; METODO->uf_delete_estpro5; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  }
	   else
	      {
			 $this->is_msg_error = "Registro Eliminado !!!";
			 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			 $ls_evento      = "DELETE";
			 $ls_descripcion = "Elimino de Presupuesto la Estructuta 5 con denominacion".$as_denestpro5;
			 $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			 $aa_seguridad["ventanas"],$ls_descripcion);
			 /////////////////////////////////         SEGURIDAD               /////////////////////////// 		   
			 $lb_valido=true;
		  } 
	   }
return $lb_valido;
}

function uf_check_relaciones($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_check_relaciones
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  Código de la Empresa.
//    $as_codestpro1:  Código del Primer  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro2:  Código del Segundo Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro3:  Código del Tercer  Nivel de la Estructura Presupuestaria o Programática.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existen tablas relacionadas al Código de la Clasificación. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  $lb_valido = false;
  $ls_sql  = "SELECT * FROM spg_cuentas                                                                                          ".
             " WHERE codemp='".$as_codemp."'         AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND ".
             "       codestpro3='".$as_codestpro3."' AND codestpro4='".$as_codestpro4."' AND codestpro5='".$as_codestpro5."'     ";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	  {
		$lb_valido=false;
        $this->io_msg->message("CLASE->tepuy_SPG_C_ESTPROG5; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
	else
	  {
		if ($row=$this->io_sql->fetch_row($rs_data))
		   {
			 $lb_valido=true;
			 $this->is_msg_error="El Registro no puede ser eliminado, posee registros asociados a otras tablas !!!";
		   }
	  }
	return $lb_valido;	
}



function uf_load_tiposolicitud($as_seleccionado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_tiposolicitud
		//		   Access: private
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//	  Description: Función que busca en la tabla de tipo de solicitud los tipos de SEP
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 11/10/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codtipsol, dentipsol, estope, modsep ".
				"  FROM sep_tiposolicitud ".
				" ORDER BY modsep, estope  ASC ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_tiposolicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			print "<select name='cmbcodtipsol' id='cmbcodtipsol' onChange='javascript: ue_cargargrid();'>";
			print " <option value='-'>-- Seleccione Uno --</option>";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_seleccionado="";
				$ls_codtipsol=$row["codtipsol"];
				$ls_dentipsol=$row["dentipsol"];
				$ls_modsep=trim($row["modsep"]);
				$ls_estope=trim($row["estope"]);
				$ls_operacion="";
				switch($ls_estope)
				{
					case"R":// Precompromiso
						$ls_operacion="Precompromiso";
						break;
					case"O":// Compromiso
						$ls_operacion="Compromiso";
						break;
					case"S":// Sin Afectacion
						$ls_operacion="Sin Afectacion Presupuestaria";
						break;
				}
				if($as_seleccionado==$ls_codtipsol."-".$ls_modsep."-".$ls_estope)
				{
					$ls_seleccionado="selected";
				}
				print "<option value='".$ls_codtipsol."-".$ls_modsep."-".$ls_estope."' ".$ls_seleccionado.">".$ls_dentipsol." - ".$ls_operacion."</option>";
			}
			$this->io_sql->free_result($rs_data);	
			print "</select>";
		}
		return $lb_valido;
	}// end function uf_load_tiposolicitud






}
?>		