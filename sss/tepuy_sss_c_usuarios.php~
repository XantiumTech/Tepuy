<?php 
class tepuy_sss_c_usuarios
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function tepuy_sss_c_usuarios()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->seguridad= new tepuy_c_seguridad;
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new tepuy_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->io_funcion = new class_funciones();
	}

////////////////////////////////// TIPOS DE MENU ///////////////////////////////////////////////////
function uf_sss_select_tipo_menu($as_codemp,$as_codtipomenu) 
{
/////////////////////////////////////////////////////////////////////////////////
//	          Metodo: uf_select_tipos_menu
//	          Access:  public
//	       Arguments: 
//        $as_codemp:  Código de la Empresa.
//     $as_codfuefin:  Código de la Tipo de Usuario a Buscar en la tabla
//                     sss_tipousuario.
//	     Description:  ///.  
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creación:  10/03/2013       Fecha Última Actualización: 31/03/2015.	 
////////////////////////////////////////////////////////////////////////////////// 

  $ls_sql=" SELECT * FROM sss_tipousuario WHERE codusu='".$as_codtipomenu."'";
	//print $ls_sql;
  $rs_clausula=$this->io_sql->select($ls_sql);
  if ($rs_clausula===false)
	 {
	   $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
       $lb_valido=false;
	 }
  else
	 {
	   $li_numrows=$this->io_sql->num_rows($rs_clausula);
	   if ($li_numrows>0)
		  {
		    $lb_valido=true;
		  }
	   else
		  {
		    $lb_valido=false;
		  }
	 }
$this->io_sql->free_result($rs_clausula);
return $lb_valido;
} ///uf_select_tipos_menu

function uf_sss_update_tipo_menu($as_codemp,$as_codtipomenu,$as_dentipomenu,$as_expmenu,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	Metodo: uf_update_tipos_menu
//	Access:  public
//	Arguments: 
//        $as_codemp:  Código de la Empresa.
//        $as_codcla:  Código de la menu a actualizar.
//        $as_dencla:  Denominación del menu a actualizar.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	     Description:  Función que se encarga de actualizar el menu
//                     a invocar que viene como parametro
//                     en la tabla sss_tipousuario.  
//     Elaborado Por:  Ing. Miguel Palencia
// Fecha de Creación:  20/02/2013       Fecha Última Actualización:31/03/2015.	 
////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" UPDATE sss_tipousuario ".
			" SET  nomtipusu='".$as_dentipomenu."', menu='".$as_expmenu."' ".
			" WHERE codusu = '".$as_codtipomenu."'";
		$this->io_sql->begin_transaction();
		$rs_clausula=$this->io_sql->execute($ls_sql);
		if ($rs_clausula===false)
			 {
			   $lb_valido=false;
			   $this->io_sql->rollback();
			   $this->io_msg->message("CLASE->tepuy_SSS_C_usuarios; METODO->uf_update_tipos_menu; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			   $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
			 }
		else
			 {
			   $lb_valido=true;
			   $this->io_sql->commit();
			   $this->io_msg->message('Registro Actualizado !!!');
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			   $ls_evento="UPDATE";
			   $ls_descripcion ="Actualizó en SSS el Menu Número ".$as_codtipomenu;
			   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			   $aa_seguridad["ventanas"],$ls_descripcion);
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////		     
			 }  		 
return $lb_valido;	
 } ////
function uf_sss_insert_tipo_menu($as_codemp,$as_codtipomenu,$as_dentipomenu,$as_expmenu,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	Metodo: uf_insert_tipos_menu
//	Access:  public
//	Arguments: 
//        $as_codemp:  Código de la Empresa.
//        $as_codcla:  Código de la Tipo de Menu a Insertar.
//        $as_dencla:  Denominación del Tipo de Menu que se va a Insertar.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	     Description:  Función que se encarga de insertar en la tabla sss_tipousuario
//                     un código, denominación y menu.php el Tipo de Menu.  
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creación:  20/02/2013       Fecha Última Actualización:31/03/2015.	 
//////////////////////////////////////////////////////////////////////////////  
	$lb_valido=false;
	$ls_sql = " INSERT INTO sss_tipousuario ".
	        " ( codusu, nomtipusu, menu) ".
		" VALUES ('".$as_codtipomenu."','".$as_dentipomenu."','".$as_expmenu."')";
				
	$this->io_sql->begin_transaction();
	$rs_clausula=$this->io_sql->execute($ls_sql);
	if ($rs_clausula===false)		     
	{
		$lb_valido=false;
		$this->io_sql->rollback();
		$this->io_msg->message("CLASE->tepuy_SSS_C_USUARIOS; METODO->uf_insert_tipos_menu; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		$this->io_sql->commit();
		$this->io_msg->message('Registro Incluido !!!');
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="INSERT";
		$ls_descripcion ="Insertó en SSS El Tipo de Menu #".$as_codtipomenu;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////// 		     
		$lb_valido=true;
	}
$this->io_sql->close();
return $lb_valido;
}

function uf_sss_delete_tipo_menu($as_codemp,$as_coduac,$as_denuac,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_tipo_menu
//	          Access:  public
//	        Arguments  
//       $as_codclas:  Código del Tipo de Menu.
//       $as_denclas:  Denominación del Menu
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de eliminar en la tabla sss_tipousuario
//                     un Clasificador de la recepcion de documentos.  
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creación:  28/03/2013       Fecha Última Actualización:31/03/2015.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
	$lb_valido   = false;
	$ls_sql = " DELETE FROM sss_tipousuario WHERE codusu='".$as_coduac."'";	    
	$this->io_sql->begin_transaction();
	$rs_data = $this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	{
		$lb_valido=false;
		$this->io_msg->message("CLASE->tepuy_SSS_C_USUARIOS; METODO->uf_delete_tipo_menu; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="DELETE";
		$ls_descripcion ="Eliminó en SSS el Tipo de Menu ".$as_coduac." con denominacíon ".$as_denuac;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               ////////////////////////////
		$lb_valido=true;
	} 		 
return $lb_valido;
}
///////////////////////////////FIN DE TIPOS DE MENU/////////////////////////////////////////////////
function uf_load_menu_usuario($as_seleccionado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_tiposolicitud
		//		   Access: private
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//	  Description: Función que busca en la tabla de tipo de solicitud los tipos de SEP
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codusu, nomtipusu, menu ".
			"  FROM sss_tipousuario ".
			" ORDER BY codusu  ASC ";	
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Menu Usuario MÉTODO->uf_menu_usuario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			print "<select name='cmbtipusu' id='cmbtipusu' onChange='javascript: ue_cargargrid();'>";
			print " <option value='-'>-- Seleccione Uno --</option>";
			//print " Sel: ".$ls_codtipayuda."-".$ls_modsep."-".$ls_estope;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_seleccionado="";
				/*$ls_codtipsol=$row["codtipsol"];
				$ls_dentipsol=$row["dentipsol"];
				$ls_modsep=trim($row["modsep"]);
				$ls_estope=trim($row["estope"]);
				$ls_operacion="";*/
				$ls_codtipusu=$row["codusu"];
				$ls_dentipusu=$row["nomtipusu"];
				$ls_menu=$row["menu"];
				if($as_seleccionado==$ls_codtipusu)
				{
					$ls_seleccionado="selected";
				}
				print "<option value='".$ls_codtiusu."-".$ls_seleccionado.">".$ls_dentipusu." - ".$ls_operacion."</option>";
			}
			$this->io_sql->free_result($rs_data);	
			print "</select>";
		}
		return $lb_valido;
	}// end function uf_load_menu_usuario
	//-----------------------------------------------------------------------------------------------------------------------------------

	function  uf_sss_select_usuarios($as_codemp,$as_codusu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_grupos
		//         Access: public (tepuy_sss_d_usuarios)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codusu    // codigo de usuario
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de verificar la existencia de un usuario en la tabla sss_usuarios
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sss_usuarios  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codusu='".$as_codusu."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuarios MÉTODO->uf_sss_select_usuarios ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_select_usuarios

	function  uf_sss_insert_usuario($ad_ultingusu,$as_codemp,$as_codusu,$as_nomusu,$as_apeusu,$as_cedusu,
									$as_pwdusu,$as_telusu,$as_nota,$as_fotousu,$as_tipusu,$aa_seguridad )
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_usuario
		//         Access: public (tepuy_sss_d_usuarios)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codusu    // codigo de usuario
		//  			   $as_nomusu    // nombre de usuario
		//  			   $as_apeusu    // apellido de usuario
		//  			   $as_cedusu    // cedula de usuario
		//  			   $as_pwdusu    // password encriptado de usuario
		//  			   $as_telusu    // telefono de usuario
		//  			   $as_nota      // observaciones de usuario
		//  			   $as_fotousu   // foto de usuario
		//  			   $ad_ultingusu // fecha de ultimo ingreso del usuario
		//  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de insertar un usuario en la tabla sss_usuarios
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "INSERT INTO sss_usuarios (codemp, codusu, cedusu, nomusu, apeusu, pwdusu, telusu, nota, ultingusu, fotousu, tipusu ) ".
				  " VALUES('".$as_codemp."','".$as_codusu."','".$as_cedusu."','".$as_nomusu."','".$as_apeusu."','".$as_pwdusu."',".
				  "        '".$as_telusu."','".$as_nota."','".$ad_ultingusu."','".$as_fotousu."','".$as_tipusu."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuarios MÉTODO->uf_sss_insert_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if ($lb_variable)
			{
				$lb_valido=true;
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}	
		}
		return $lb_valido;
	}  // end  function  uf_sss_insert_usuario
	
	function uf_sss_delete_usuario($as_codemp,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_usuario
		//         Access: public (tepuy_sss_d_usuarios)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codusu    // codigo de usuario
		//  			   $aa_seguridad // codigo de usuario
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de eliminar un usuario en la tabla sss_usuarios verificando su integridad referencial
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_existe=$this->uf_sss_select_eventos($as_codemp,$as_codusu);
		if($lb_existe)
		{
			$this->io_msg->message("El usuario tiene registros de eventos asociados");
			$lb_valido=false;
		}
		else
		{
			$lb_existe=$this->uf_sss_select_permisos($as_codemp,$as_codusu);
			if($lb_existe)
			{
				$this->io_msg->message("El usuario tiene registros de permisos");
				$lb_valido=false;
			}
		}
		if(!$lb_existe)
		{
			$ls_sql = " DELETE FROM sss_usuarios".
					  " WHERE codemp= '".$as_codemp. "'".
					  " AND codusu= '".$as_codusu."'"; 
			$this->io_sql->begin_transaction();	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->usuarios MÉTODO->uf_sss_delete_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				if ($lb_variable)
				{
					$lb_valido=true;
					$this->io_sql->commit();
				}
				else
				{
					$this->io_sql->rollback();
				}	
			}
		}
		return $lb_valido;
	} // end  function uf_sss_delete_usuario
	
	function uf_sss_update_usuario($as_codemp,$as_codusu,$as_cedusu,$as_nomusu,$as_apeusu,$as_telusu,$as_nota,$as_nomarch,$as_tipusu,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_update_usuario
		//         Access: public (tepuy_sss_d_usuarios)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codusu    // codigo de usuario
		//  			   $as_nomusu    // nombre de usuario
		//  			   $as_apeusu    // apellido de usuario
		//  			   $as_cedusu    // cedula de usuario
		//  			   $as_telusu    // telefono de usuario
		//  			   $as_nota      // observaciones de usuario
		//  			   $as_nomarch   // nombre del archivo de la foto
		//  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de modificar un usuario en la tabla sss_usuarios
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
		 $ls_sqlfoto="";
		 if($as_nomarch!="")
		 {
		 	$ls_sqlfoto=", fotousu='". $as_nomarch ."'";
		 }
		 $ls_sql = "UPDATE sss_usuarios SET  cedusu='". $as_cedusu ."',nomusu='". $as_nomusu ."',apeusu='". $as_apeusu ."',".
				   "tipusu='". $as_tipusu ."', telusu='". $as_telusu ."', nota='". $as_nota ."'". $ls_sqlfoto ."".
				   " WHERE codemp='" .$as_codemp ."'".
				   " AND codusu='" .$as_codusu ."'";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuarios MÉTODO->uf_sss_update_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if ($lb_variable)
			{
				$lb_valido=true;
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}	
		}
	  return $lb_valido;
	}  // end  function uf_sss_update_usuario
	
	function  uf_sss_select_eventos($as_codemp,$as_codusu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_eventos
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codusu    // codigo de usuario
		//	      Returns: Retorna un Booleano
		//    Description: Función que verifica si un usuario tiene registrado algun evento en la tabla sss_registro_eventos
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sss_registro_eventos  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codusu='".$as_codusu."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuarios MÉTODO->uf_sss_select_eventos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_select_eventos

	function  uf_sss_select_permisos($as_codemp,$as_codusu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_permisos
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codusu    // codigo de usuario
		//	      Returns: Retorna un Booleano
		//    Description: Función que verifica si un usuario tiene registrado algun permiso en la tabla sss_derechos_usuarios
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sss_derechos_usuarios  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codusu='".$as_codusu."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuarios MÉTODO->uf_sss_select_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end   function  uf_sss_select_permisos
	
}//end  class tepuy_sss_c_usuarios

?>
