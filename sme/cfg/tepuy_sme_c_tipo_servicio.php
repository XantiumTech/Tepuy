<?php
class tepuy_sme_c_tipo_servicio
{
	var $ls_sql;
	
	function tepuy_sme_c_tipo_servicio($conn)
	{
	  require_once("../../shared/class_folder/class_mensajes.php");
	  require_once("../../shared/class_folder/tepuy_c_seguridad.php");
	  require_once("../../shared/class_folder/class_funciones.php");
	  $this->seguridad = new tepuy_c_seguridad();
	  $this->io_sql       = new class_sql($conn);
	  $this->io_msg       = new class_mensajes();		
	  $this->io_funcion   = new class_funciones();
	  $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}
////////////// NUEVO /////////////////
   function uf_obteneroperacion()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_obteneroperacion
		//	Returns:	$operacion valor de la variable
		//	Description: Función que obtiene que tipo de operación se va a ejecutar
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists("operacion",$_POST))
		{
			$operacion=$_POST["operacion"];
		}
		else
		{
			$operacion="NUEVO";
		}
		//print "Operacion :".$operacion;
   		return $operacion; 
   }
   //--------------------------------------------------------------
   //--------------------------------------------------------------
   function uf_obteneroperacionvia()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_obteneroperacion
		//	Returns:	$operacion valor de la variable
		//	Description: Función que obtiene que tipo de operación se va a ejecutar
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists("operacion",$_POST))
		{
			$operacion=$_POST["operacion"];
		}
		else
		{
			$operacion="BUSCARDETALLE";
		}
		//print "Operacion :".$operacion;
   		return $operacion; 
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_obtenerexiste()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_obtenerexiste
		//	Returns:	$existe valor de la variable
		//	Description: Función que obtiene si existe el registro ó no
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists("existe",$_POST))
		{
			$existe=$_POST["existe"];
		}
		else
		{
			$existe="FALSE";
		}
   		return $existe; 
   }
   //--------------------------------------------------------------
	
   //--------------------------------------------------------------
   function uf_seleccionarcombo($as_valores,$as_seleccionado,&$aa_parametro,$li_total)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_seleccionarcombo
		//	Arguments:    as_valores  // valores que contiene el combo
		//				  as_seleccionado  // Valor que se debe seleccionar
		//				  aa_parametro  // arreglo de valores
		//				  li_total  // Valor toatl de valores
		//	Description: Función que seleciona un valor de un combo
		//////////////////////////////////////////////////////////////////////////////
   		$la_valores = split("-",$as_valores);
		for($li_index=0;$li_index<$li_total;++$li_index)
		{
			if($la_valores[$li_index]==$as_seleccionado)
			{
				$aa_parametro[$li_index]=" selected";
			}
		}
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_obtenervalor($as_valor, $as_valordefecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_obtenervalor
		//	Arguments:    as_valor  // Variable que deseamos obtener
		//				  as_valordefecto  // Valor por defecto de la variable
		//	Returns:	  $valor contenido de la variable
		//	Description: Función que obtiene el valor de una variable que viene de un submit
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists($as_valor,$_POST))
		{
			$valor=$_POST[$as_valor];
		}
		else
		{
			$valor=$as_valordefecto;
		}
   		return $valor; 
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_obtenervariable($as_variable, $as_caso1, $as_caso2, $as_valor1, $as_valor2, $as_defecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_obtenervariable
		//	Arguments: as_variable  // Variable que deseamos obtener
		//			   as_caso1  // condición 1
		//			   as_caso2  // condición 2
		//			   as_valor1  // Valor si se cumple la condición 1
		//			   as_valor2  // Valor si se cumple la condición 2
		//	  		   as_defecto  // Valor por defecto de la variable
		//	Returns:	 $valor contenido de la variable
		//	Description: Función que dependiendo del caso trae un valor u otro
		//////////////////////////////////////////////////////////////////////////////
		switch($as_variable)
		{
			case $as_caso1:
				$valor = $as_valor1;
				break;
					
			case $as_caso2:
				$valor = $as_valor2;
				break;					
			
			default:
				$valor = $as_defecto;
				break;
		}
   		return $valor; 
   }
   //--------------------------------------------------------------

  //-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_formatonumerico($as_valor)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:     uf_formatonumerico
		//	Arguments:    as_valor  // valor sin formato numérico
		//	Returns:	  $as_valor valor numérico formateado
		//	Description:  Función que le da formato a los valores numéricos que vienen de la BD
		//////////////////////////////////////////////////////////////////////////////
		$as_valor=str_replace(".",",",$as_valor);
		$li_poscoma = strpos($as_valor, ",");
		$li_contador = 1;
		if ($li_poscoma==0)
		{
			$li_poscoma = strlen($as_valor);
			$as_valor = $as_valor.",00";
		}
		$as_valor = substr($as_valor,0,$li_poscoma+3);
		$li_poscoma = $li_poscoma - 1;
		for($li_index=$li_poscoma;$li_index>=0;--$li_index)
		{
			if(($li_contador==3)&&(($li_index-1)>=0)) 
			{
				$as_valor = substr($as_valor,0,$li_index).".".substr($as_valor,$li_index);
				$li_contador=1;
			}
			else
			{
				$li_contador=$li_contador + 1;
			}
		}
		return $as_valor;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_obtenertipo()
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_obtenertipo
		//	Description: Función que obtiene que tipo de llamada del catalogo
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists("tipo",$_GET))
		{
			$tipo=$_GET["tipo"];
		}
		else
		{
			$tipo="";
		}
   		return $tipo; 
   	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_obtenervalor_get($as_variable,$as_valordefecto)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_obtenertipo
		//	Description: Función que obtiene que tipo de llamada del catalogo
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists($as_variable,$_GET))
		{
			$valor=$_GET[$as_variable];
		}
		else
		{
			$valor=$as_valordefecto;
		}
   		return $valor; 
   	}
	//-----------------------------------------------------------------------------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_asignarvalor($as_valor, $as_valordefecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_asignarvalor
		//	Arguments:    as_valor  // Variable que deseamos obtener
		//				  as_valordefecto  // Valor por defecto de la variable
		//	Returns:	  $valor contenido de la variable
		//	Description: Función que obtiene el valor de una variable que viene de un submit
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists($as_valor,$_POST))
		{
			$valor=$_POST[$as_valor];
		}
		else
		{
			$valor=$as_valordefecto;
		}
		
		if ($valor=="")
		{
			$valor=$as_valordefecto;
		}
   		return $valor; 
   }
   //--------------------------------------------------------------

/////////////////////////////////////
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (tepuy_sep_p_solicitud.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 03/03/2016								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_msg);		
		unset($this->io_funcion);		
		unset($this->seguridad);
		unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------
///////////////////////////////  SERVICIOS MEDICOS   ////////////////////////////////
function uf_insert_serviciomedico($as_codtipservicio,$as_dentipservicio,$aa_seguridad) 
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function     : uf_insert_serviciomedico
	//	Access       : public
	//	Arguments    :
	//  as_codtip    = Código del Tipo de Servicio Medico.
	//  as_dentip    = Denominación del Tipo de Servicio Medico.
	//  aa_seguridad = Arreglo cargado con la información de usuario, ventanas, sistema etc.
	//	Description  : Este método se encarga de insertar un nuevo tipo de SEP en la Tabla 
	//                 sme_tiposervicio en la base de datos seleccionada .
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$as_codemp=$this->ls_codemp;	
	$ls_sql=" INSERT INTO sme_tiposervicio ". 
			" (codemp, codtipservicio, dentipservicio) ". 
			" VALUES ('".$as_codemp."','".$as_codtipservicio."','".$as_dentipservicio."')";
	//print $ls_sql;
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido=false;
		 $this->io_msg->message("CLASE->tepuy_sme_c_tipo_servicio; METODO->uf_insert_serviciomedico; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
	else
	 {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="INSERT";
	   $ls_descripcion ="Insertó Tipo de Servicio Médico en SME ".$as_codtip;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////
	   $lb_valido=true;
	 }   
     return $lb_valido;
}

function uf_update_serviciomedico($as_codemp,$as_codtipservicio,$as_dentipservicio,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo: uf_update_serviciomedico
//	          Access:  public
//	       Arguments: 
//        $as_codemp:  Código de la Empresa.
//        $as_codcla:  Código del Tipo de Servicio medico a actualizar.
//        $as_dencla:  Denominación del Tipo de Servicio que se va a actualizar.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de actualizar la denominacion
//                     de una clausula para la clausula que viene como parametro
//                     en la tabla sme_tiposervicio.  
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creación:  03/03/2016       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $as_codemp=$this->ls_codemp;	
  $ls_sql=" UPDATE sme_tiposervicio ".
		  " SET  dentipservicio='".$as_dentipservicio."' ".
		  " WHERE codemp= '".$as_codemp."' AND codtipservicio = '".$as_codtipservicio."'";
//print $ls_sql;
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->tepuy_sme_c_tipo_servicio; METODO->uf_insert_serviciomedico; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $lb_valido=true;
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="UPDATE";
	   $ls_descripcion ="Actualizó en SME El Tipo de Servicio Médico ".$as_codtipservicio;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		     
     }  		      
return $lb_valido;
} 
		
function uf_delete_serviciomedico($as_codemp,$as_codtipservicio,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_serviciomedico
//	          Access:  public
//	       Arguments: 
//        $as_codemp:  Código de la Empresa.
//        $as_codcla:  Código del Tipo de Servicio Medico a eliminar.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	     Description:  Función que se encarga de eliminar la clausula que 
//                     viene como parametro  en la tabla sme_tiposervicio.  
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creación:  03/03/2016       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
 
  $lb_valido = false;
  $as_codemp=$this->ls_codemp;
  $lb_existe= $this->uf_check_relaciones1($as_codemp,$as_codtipservicio);
  if($lb_existe)
  {
	$lb_valido=false;
  }
  else
  {
  	$ls_sql    = "DELETE FROM sme_tiposervicio WHERE codemp= '".$as_codemp."' AND codtipservicio='".$as_codtipservicio."'";	    
  	$this->io_sql->begin_transaction();
  	$rs_data=$this->io_sql->execute($ls_sql);
  	if ($rs_data===false)
     	{
       		$lb_valido=false;
       		$this->io_msg->message("CLASE->tepuy_sme_c_tipo_servicio; METODO->uf_delete_serviciomedico; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
     	}
  	else
     	{
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="DELETE";
		$ls_descripcion ="Eliminó en SME Tipo de Servicio Médico ".$as_codtipservicio;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
	}
  }
  return $lb_valido;
}

function uf_select_serviciomedico($as_codemp,$as_codtipservicio) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo: uf_select_clausula
//	          Access:  public
//	       Arguments: 
//        $as_codemp:  Código de la Empresa.
//        $as_codcla:  Código del Tipo de Servicio Medico
//	     Description:  Función que se encarga verificar si existe el código
//                     de la clausula que viene como parametro.En caso de encontrarla
//                     devuelve true, caso contrario devuelve false.  
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creación:  03/03/2016       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 

  $lb_valido = false;
  $ls_sql    = "SELECT * FROM sme_tiposervicio WHERE codtipservicio='".$as_codtipservicio."'";
//print $ls_sql;
  $rs_data   = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->tepuy_sme_c_tipo_servicio; METODO->uf_select_tipoyuda; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

/////////// FUNCION QUE MUESTRA LOS CONCEPTO A RELACIONAR CON EL SERVICIO MEDICO /////////////
	function uf_load_conceptopago($as_seleccionado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_conceptopago
		//		   Access: private
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//	  Description: Función que busca en la tabla de tipo de solicitud los tipos de SEP
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codtipsol, dentipsol, estope, modsep ".
				"  FROM sep_tiposolicitud WHERE dentipsol like '%GAST% %MEDI%'".
				" AND dentipsol not like '%AYUDA%' ORDER BY codtipsol  ASC";	
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE-> Establece el concepto de tarifa segun monto->uf_load_conceptopago ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			print "<select name='cmbcodtipsol' id='cmbcodtipsol' onChange='javascript: ue_cargargrid();'>";
			print " <option value='00'>-- Seleccione Uno --</option>";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_seleccionado="";
				$ls_codtipsol=$row["codtipsol"];
				$ls_dentipsol=$row["dentipsol"];
				$ls_modsep=trim($row["modsep"]);
				$ls_estope=trim($row["estope"]);
				$ls_operacion="";
				if($as_seleccionado==$ls_codtipsol)
				{
					$ls_seleccionado="selected";
				}
				print "<option value='".$ls_codtipsol."' ".$ls_seleccionado.">".$ls_dentipsol." - ".$ls_operacion."</option>";
			}
			$this->io_sql->free_result($rs_data);	
			print "</select>";
		}
		return $lb_valido;
	}// end function uf_load_tiposolicitudayuda
//////////////////////////////////////////////////////////////////////////////////////////////

///////// FUNCION QUE UBICA LOS TIPOS DE SERVICIOS MEDICOS QUE SE APLICAN //////////
	function uf_cmb_tiposervicio($as_seleccionado)
	{
		$lb_valido=true;
		$ls_sql="SELECT  codtipservicio, dentipservicio ". 
		        "  FROM   sme_tiposervicio  WHERE codemp='".$this->ls_codemp."'"." ORDER BY codtipservicio";
		//print $ls_sql;
		$rs_result=$this->io_sql->select($ls_sql);		
		if($rs_result===false)
		 {
			$this->io_msg->message("CLASE->Buscar Tipos de Servicios Médicos MÉTODO->uf_cmb_tiposervicios ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			//return false;			
		 }
		 else
		 {
			print "<select name='cmbcodtiptar' id='cmbcodtiptar' onchange='javascript: ue_cargargrid();'>";
			print " <option value='00'>-- Seleccione Servicio Médico--</option>";
			while($row=$this->io_sql->fetch_row($rs_result))
			{
				$ls_seleccionado="";
				$ls_codtipservicio=$row["codtipservicio"];
				$ls_dentipservicio=$row["dentipservicio"];
				$ls_operacion="";
				if($as_seleccionado==$ls_codtipservicio)
				{
					$ls_seleccionado="selected";
				}
			print "<option value='".$ls_codtipservicio."' ".$ls_seleccionado.">".$ls_dentipservicio." - ".$ls_operacion."</option>";
			}
			$this->io_sql->free_result($rs_result);
			print "</select>";
		 }
	return $lb_valido;
	}
/////////////////////// FIN DE LA FUNCION DE BUSQUEDAS DE TIPOS DE SERVICIOS MEDICOS /////////

///////// FUNCION QUE UBICA LOS TIPOS DE SERVICIOS MEDICOS QUE SE APLICAN //////////
	function uf_cmb_nomina($as_seleccionado)
	{
		$lb_valido=true;
		$ls_sql="SELECT  a.codnom, a.desnom ". 
		        "  FROM   sno_nomina as a WHERE a.codemp='".$this->ls_codemp."'";
		$rs_result=$this->io_sql->select($ls_sql);		
		if($rs_result===false)
		 {
			$this->io_msg->message("CLASE->Buscar Tipos de Servicios Médicos MÉTODO->uf_cmb_nomina ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			//return false;
		 }
		 else
		 {
			print "<select name='cmbnomina' id='cmbnomina' onChange='javascript: ue_cargargrid();'>";
			print " <option value='00'>-- Seleccione Una Nomina--</option>";
			while($row=$this->io_sql->fetch_row($rs_result))
			{
				$ls_seleccionado="";
				$ls_codnom=$row["codnom"];
				$ls_desnom=$row["desnom"];
				$ls_operacion="";
				if($as_seleccionado==$ls_codnom)
				{
					$ls_seleccionado="selected";
				}
				print "<option value='".$ls_codnom."' ".$ls_seleccionado.">".$ls_desnom." - ".$ls_operacion."</option>";
			}
			$this->io_sql->free_result($rs_result);	
			print "</select>";
		}
		return $lb_valido;
	}
/////////////////////// FIN DE LA FUNCION DE BUSQUEDAS DE TIPOS DE SERVICIOS MEDICOS /////////
/////////////////////////// COMIENZAN LAS RUTINAS CON RESPECTO AL MONTO SEGUN TIPO //////////////////////////
	function uf_sme_update_montotiposervicio($as_codemp,$as_codtar,$as_codtiptar,$as_nomina,$as_dentra,$as_tartra,$as_tarfam,$as_spg_cuenta,$as_codestpro,$as_conceptopago,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sme_update_montotiposervicio
		//         Access: public  
		//      Argumento: 		   $as_codemp    //codigo de empresa
		//				   $as_codtra    //codigo del servicio medico
		//				   $as_codtiptra //codigo de tipo del tipo de Servicio Médico
		//				   $as_dentra    //Observaciones a la definicion del servicio medico
		//				   $as_nomina    //Nomina asociada al servicio medico
		//				   $as_tartra $as_tarfam    //Tarifa Tope del Trabajador y Familiar del servicio medico
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza un registro de monto segun tipo de servicio en la tabla sme_montoseguntipo
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 03/03/2016 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 	$lb_valido=true;
		$ls_sql = " UPDATE sme_montoseguntipo".
				  "    SET   codtipservicio='". $as_codtiptar ."'".
				  ", codnom='". $as_nomina .""."', montotrabajador=". $as_tartra ."".
				  ", montofamiliar=". $as_tarfam ."".", observacion='". $as_dentra ."'".
				  ", spg_cuenta='". $as_spg_cuenta .""."', codestpro='". $as_codestpro ."'".", codtipsol='". $as_conceptopago ."'".
				  "  WHERE codemp='" . $as_codemp ."'".
				  "    AND codtar='" . $as_codtar ."'";
		//print $ls_sql;
		$this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Monto segun tipo de servicio MÉTODO->uf_sme_update_montotiposervicio ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Monto según Tipo de Servicio ".$as_codtar." Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	    return $lb_valido;
	} // end  function uf_sme_update_montotiposervicio
///////////////////////////
	function uf_sme_select_montotiposervicio($as_codemp,$as_codtar,$as_codtiptra,$as_nomina)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sme_select_montotiposervicio
		//         Access: public 
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_codtra    //codigo del servicio
		//                 $as_codtiptra //codigo de tipo de Servicio
		//                 $as_nomina    //codigo de la nomina
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe el servicio medico en la tabla sme_montoseguntipo
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 03/03/2016 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sme_montoseguntipo  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND codnom='".$as_nomina."'" .
				  "   AND codtipservicio='".$as_codtiptra."'" ;
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Monto Tipo segun Tipo de Servicio MÉTODO->uf_sme_select_montotiposervicio ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end uf_sme_select_montotiposervicio
////////////////////
	function  uf_sme_insert_montotiposervicio($as_codemp,$as_codtar,$as_codtiptar,$as_nomina,$as_dentra,$as_tartra,$as_tarfam,$as_spg_cuenta,$as_codestpro,$as_conceptopago,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sme_insert_montotiposervicio
		//         Access: public 
		//      Argumento: 		   $as_codemp    //codigo de empresa
		//				   $as_codtra    //codigo del servicio medico
		//				   $as_codtiptra //codigo de tipo del tipo de Servicio Médico
		//				   $as_dentra    //Observaciones a la definicion del servicio medico
		//				   $as_nomina    //Nomina asociada al servicio medico
		//				   $as_tartra $as_tarfam    //Tarifa Tope del Trabajador y Familiar del servicio medico
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un registro de servicio medico en la tabla sme_montoseguntipo
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 03/03/2016 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO sme_montoseguntipo (codemp,codtar,codtipservicio,codnom,montotrabajador,montofamiliar,spg_cuenta,codestpro,codtipsol,observacion) ".
				  "     VALUES ('".$as_codemp."','".$as_codtar."','".$as_codtiptar."','".$as_nomina."','".
$as_tartra."','".$as_tarfam."','".$as_spg_cuenta."','".$as_codestpro."','".$as_conceptopago."','".$as_dentra."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Monto según tipo de Servicio Médico MÉTODO->uf_sme_insert_montotiposervicio ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Servicio Médico ".$as_codtar." Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				
				if($lb_valido)
				{
					$this->io_sql->commit();
				}
				else
				{
					$this->io_sql->rollback();
				}
		}
		return $lb_valido;
	} //end uf_sme_insert_montotiposervicio

	function uf_sme_delete_montotiposervicio($as_codemp,$as_codtar,$as_codtiptar,$as_codnom,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sme_delete_montotiposervcio
		//         Access: public (tepuy_sme_d_montotipo_servicio)
		//      Argumento: 		   $as_codemp    //codigo de empresa
		//				   $as_codtra    //codigo del servicio medico
		//				   $as_codtiptra //codigo de tipo del tipo de Servicio Médico
		//				   $as_dentra    //Observaciones a la definicion del servicio medico
		//				   $as_nomina    //Nomina asociada al servicio medico
		//				   $as_tartra $as_tarfam    //Tarifa Tope del Trabajador y Familiar del servicio medico
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un registro de servicio medico en la tabla sme_montoseguntipo
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 03/03/2016 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_check_relaciones($as_codemp,$as_codtiptar);
		if($lb_existe)
		{
			$lb_valido=false;
		}
		else
		{
			$ls_sql = " DELETE FROM sme_montoseguntipo".
					"  WHERE codemp= '".$as_codemp. "'".
					"    AND codtar= '".$as_codtar. "'".
					"    AND codtipservicio= '".$as_codtiptar. "'".
					"    AND codnom= '".$as_codnom. "'"; 
			//print $ls_sql;
			$this->io_sql->begin_transaction();	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->Tarifa Servicio Medico MÉTODO->uf_sme_delete_montotiposervicio ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la tarifa Segun tipo de Servicio medico ".$as_codtar." Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}			
		return $lb_valido;
	} //end function uf_sme_montotiposervicio
	function uf_check_relaciones($as_codemp,$as_codtar)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Metodo:  uf_check_relaciones
		//	      Access:  public
		// 	   Arguments:  $as_codemp // codigo de empresa.
		//     			   $as_codtar // codigo de Tarifa de Servicio Medico
		//	      Returns: Retorna un Booleano
		//	  Description: Función que se encarga de verificar si existen tablas relacionadas al Código de la Servicio Medico. 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 29/08/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$ls_sql="SELECT codtar".
				"  FROM sme_dt_beneficiario".
				" WHERE codemp='".$as_codemp."'".
				"   AND codtar='".$as_codtar."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		  {
			$lb_valido=false;
			$this->io_msg->message("CLASE->tepuy_sme_c_tipo_servicios METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  }
		else
		  {
			if($row=$this->io_sql->fetch_row($rs_data))
			  {
				$lb_valido=true;
				$this->is_msg_error="La Tarifa de este Servicio medico no puede ser eliminado, posee registros asociados a otras tablas";
			  }
			else
			  {
				$lb_valido=false;
				$this->is_msg_error="Registro no encontrado";
			  }
		}
		return $lb_valido;	
	}
	function uf_check_relaciones1($as_codemp,$as_codtipservicio)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Metodo:  uf_check_relaciones
		//	      Access:  public
		// 	   Arguments:  $as_codemp // codigo de empresa.
		//     			   $as_codtar // codigo de Tarifa de Servicio Medico
		//	      Returns: Retorna un Booleano
		//	  Description: Función que se encarga de verificar si existen tablas relacionadas al Código de la Servicio Medico. 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 29/08/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$ls_sql="SELECT codtipservicio".
				"  FROM sme_montoseguntipo".
				" WHERE codemp='".$as_codemp."'".
				"   AND codtipservicio='".$as_codtipservicio."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		  {
			$lb_valido=false;
			$this->io_msg->message("CLASE->tepuy_sme_c_tipo_servicios METODO->uf_check_relaciones1; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  }
		else
		  {
			if($row=$this->io_sql->fetch_row($rs_data))
			  {
				$lb_valido=true;
				$this->is_msg_error="La Tipo de Servicio Medico no puede ser eliminado, posee registros asociados a otras tablas";
			  }
			else
			  {
				$lb_valido=false;
				$this->is_msg_error="Registro no encontrado";
			  }
		}
		return $lb_valido;	
	}

////////////////////////// FINALIZA RUTINAS DE LOS SERVICIOS SEGUN MONTO Y TIPO DE SERVICIOS /////////////////

///////////////////////////// FIN TIPO DE SERVICIOS MEDICOS ////////////////////////////////

}// Fin de la Clase tepuy_sme_c_tipo_servicio.
?> 
