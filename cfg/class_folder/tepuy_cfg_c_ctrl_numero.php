<?php
class tepuy_cfg_c_ctrl_numero
 {
    var $ls_sql="";
	var $io_msg_error;
	
	function tepuy_cfg_c_ctrl_numero()//Constructor de la Clase.
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
        require_once("../shared/class_folder/class_funciones.php");
		$this->seguridad  = new tepuy_c_seguridad();		  
        $this->io_funcion = new class_funciones();
		$io_conect        = new tepuy_include();
		$conn             = $io_conect->uf_conectar();
		$this->la_emp     = $_SESSION["la_empresa"];
		$this->codemp     = $_SESSION["la_empresa"]["codemp"];
		$this->ls_usuario = $_SESSION["la_logusr"];
		$this->io_sql     = new class_sql($conn); //Instanciando  la clase sql
		$this->io_msg     = new class_mensajes();
	}

function uf_select_ctrl_numero($as_codemp, $as_codsis,$as_procede, $as_codusu)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	   Function:  uf_select_procedencia
//	     Access:  public
//    Arguments:
//   $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	    Returns:  $lb_valido= Variable que devuelve true si encontro el registro 
//                de lo contrario devuelve false. 
//	Description:  Este m�todo que se ancarga de buscar el C�digo de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql  = " SELECT * FROM tepuy_ctrl_numero  ".
	           " WHERE codemp  ='".$as_codemp."'".
	           " AND   codsis  ='".$as_codsis."'".
	           " AND   procede ='".$as_procede."'".
	           " AND   codusu  ='".$as_codusu."'"; 

	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
	     $this->io_msg->message("CLASE->tepuy_cfg_c_ctrl_numero; METODO->uf_select_ctrl_numero;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
	        {  
		     //$lb_valido=true;
	          $this->io_sql->free_result($rs_data);
			}
	     else
	        {
	  	      $lb_valido=false;
	        }	 
      }
return $lb_valido;
}



function uf_buscar_campo($as_tabla,$as_campo,$as_criterio)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	   Function:  uf_buscar_campo
//	     Access:  public
//    Arguments:
//   $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	    Returns:  $lb_valido= Variable que devuelve true si encontro el registro 
//                de lo contrario devuelve false. 
//	Description:  Este m�todo que se ancarga de buscar el C�digo de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$as_cosis="";
	$ls_sql  = " SELECT ".$as_campo." FROM ".$as_tabla." ".
	           " WHERE ".$as_criterio." ";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
	     $this->io_msg->message("CLASE->tepuy_cfg_c_ctrl_numero; METODO->uf_buscar_campo;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
        {  
	      $row=$this->io_sql->fetch_row($rs_data);
	      $as_cosis=$row["codsis"];
          $this->io_sql->free_result($rs_data);
		}
     	else
        {
  	      $lb_valido=false;
        }	 
      }
return array($lb_valido,$as_cosis);
}

function uf_delete_ctrl_numero($ar_datos,$aa_seguridad)
{   
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Function:  uf_delete_procedencia
//	Access:  public
//	Arguments:
// $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	  Returns:	$lb_valido= Variable que devuelve true si encontro el registro 
//                          de lo contrario devuelve false. 
//	Description: Este m�todo que se ancarga de buscar el C�digo de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$as_codigo  = $ar_datos["codigo"];
	$as_codproc = $ar_datos["codsis"];
	$as_maxlon  = $ar_datos["maxlon"];
	$as_prefijo = $ar_datos["prefijo"];
	$as_numini  = $ar_datos["numini"];
	$as_numfin  = $ar_datos["numfin"];
	$as_numact  = $ar_datos["nunact"];
	
	
	
			list($lb_valido,$as_codsis)=$this->uf_buscar_campo("tepuy_procedencias","codsis"," procede='".$as_codproc."'");
			$lb_valido = false;
			$ls_sql    = " DELETE FROM tepuy_ctrl_numero WHERE codemp='".$this->codemp."' AND codsis='".$as_codsis."' AND procede='".$as_codproc."' AND codusu='".$this->ls_usuario."' AND id='".$as_codigo."'";
		    $this->io_sql->begin_transaction();
			$rs_data=$this->io_sql->execute($ls_sql);
			if ($rs_data===false)
			   { 
			          $this->io_msg_error="CLASE->tepuy_CFG_C_PROCEDENCIAS; METODO->uf_delete_procedencia;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);   
			   }
		    else
			   {
			     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			     $ls_evento="DELETE";
			     $ls_descripcion ="Elimin� en CFG el Control Numerico ".$as_codigo." del Sistema ".$as_codsis." y usuario ".$this->ls_usuario;
			     $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			     $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			     $aa_seguridad["ventanas"],$ls_descripcion);
			     /////////////////////////////////         SEGURIDAD               ///////////////////////////// 
				 $lb_valido = true;
			   }
	return $lb_valido;
}	  
                 


function uf_verificar_procede($as_codemp, $as_prefijo,$as_procede, $as_codusu)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	   Function:  uf_select_procedencia
//	     Access:  public
//    Arguments:
//   $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	    Returns:  $lb_valido= Variable que devuelve true si encontro el registro 
//                de lo contrario devuelve false. 
//	Description:  Este m�todo que se ancarga de buscar el C�digo de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql  = "SELECT COUNT(PREFIJO) AS existe FROM tepuy_ctrl_numero WHERE codemp='".$as_codemp."' AND prefijo='".$as_prefijo."' AND procede='".$as_procede."' AND  codusu<>'".$as_codusu."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
	     $this->io_msg->message("CLASE->tepuy_cfg_c_ctrl_numero; METODO->uf_select_ctrl_numero;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
	        {  
		     $row=$this->io_sql->fetch_row($rs_data);
	     	 $as_existe=$row["existe"];
	          $this->io_sql->free_result($rs_data);
			}
	     else
	        {
	  	      $lb_valido=false;
	        }	 
      }
return array($lb_valido,$as_existe);
}


function uf_verificar_eliminacion($as_procede)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	   Function:  uf_select_procedencia
//	     Access:  public
//    Arguments:
//   $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	    Returns:  $lb_valido= Variable que devuelve true si encontro el registro 
//                de lo contrario devuelve false. 
//	Description:  Este m�todo que se ancarga de buscar el C�digo de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	//$ls_sql  = "SELECT COUNT(PREFIJO) AS existe FROM tepuy_ctrl_numero WHERE codemp='".$as_codemp."' AND prefijo='".$as_prefijo."' AND procede='".$as_procede."' AND  codusu<>'".$as_codusu."'";
	if($as_procede=="SEPSPC")
	{
	$ls_sql="SELECT COUNT(PREFIJO) AS existe FROM tepuy_ctrl_numero,sep_solicitud
			 WHERE sep_solicitud.codemp=tepuy_ctrl_numero.codemp AND
			      SUBSTR(sep_solicitud.numsol,1,6)=tepuy_ctrl_numero.prefijo AND
			      tepuy_ctrl_numero.procede='".$as_procede."' AND
			      codusu='".$this->ls_usuario."'";
	}
	elseif($as_procede=="CXPSOP") 
	{
		$ls_sql="SELECT COUNT(PREFIJO) AS existe FROM tepuy_ctrl_numero,cxp_solicitudes
			 WHERE cxp_solicitudes.codemp=tepuy_ctrl_numero.codemp AND
			      SUBSTR(cxp_solicitudes.numsol,1,6)=tepuy_ctrl_numero.prefijo AND
			      tepuy_ctrl_numero.procede='".$as_procede."' AND
			      codusu='".$this->ls_usuario."'";
	}
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
	     $this->io_msg->message("CLASE->tepuy_cfg_c_ctrl_numero; METODO->uf_select_ctrl_numero;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
	        {  
		     $row=$this->io_sql->fetch_row($rs_data);
	     	 $as_existe=$row["existe"];
	          $this->io_sql->free_result($rs_data);
			}
	     else
	        {
	  	      $lb_valido=false;
	        }	 
      }
return array($lb_valido,$as_existe);
}
/*
function uf_verificar_id($as_codigo,$as_tabla,$as_campo)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	   Function:  uf_buscar_campo
//	     Access:  public
//    Arguments:
//   $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	    Returns:  $lb_valido= Variable que devuelve true si encontro el registro 
//                de lo contrario devuelve false. 
//	Description:  Este m�todo que se ancarga de buscar el C�digo de Procedencia enviado por parametro.
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$as_cosis="";
	$ls_sql  = " SELECT ".$as_campo." FROM ".$as_tabla." ".
	           " WHERE ".$as_criterio." ";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
	     $this->io_msg->message("CLASE->tepuy_cfg_c_ctrl_numero; METODO->uf_buscar_campo;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
        {  
	      $row=$this->io_sql->fetch_row($rs_data);
	      $as_cosis=$row["codsis"];
          $this->io_sql->free_result($rs_data);
		}
     	else
        {
  	      $lb_valido=false;
        }	 
      }
return array($lb_valido,$as_cosis);
}

*/
function  uf_sss_load_usuarios_disponibles($as_empresa,$as_codsis,$as_prefijo,&$aa_disponibles)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_usuarios_disponibles
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $aa_usuarios    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Funci�n que carga los datos de los usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 30/10/2006								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		list($lb_valido,$as_codproc)=$this->uf_buscar_campo("tepuy_procedencias","codsis"," procede='".$as_codsis."'");
		$ls_sql="Select  sss_usuarios.codusu,sss_usuarios.cedusu,sss_usuarios.nomusu,sss_usuarios.apeusu".
				" from sss_usuarios where sss_usuarios.codusu not in".
				"   (select codusu from tepuy_ctrl_numero where tepuy_ctrl_numero.codemp='".$as_empresa."' ".
				"    and tepuy_ctrl_numero.codsis='".$as_codproc."' and tepuy_ctrl_numero.procede='".$as_codsis."') ".
				" ORDER BY codusu";  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tepuy_cfg_c_ctrl_numero M�TODO->uf_sss_load_usuarios_disponibles ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_disponibles[$li_pos]["codusu"]=$row["codusu"];
				$aa_disponibles[$li_pos]["nomusu"]=$row["nomusu"];  
				$aa_disponibles[$li_pos]["apeusu"]=$row["apeusu"];  
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_usuarios
	
	function  uf_sss_load_usuarios_asignados($as_codemp,$as_prefijo,&$aa_asignados)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_usuarios_asignados
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_prefijo    // prefijo 
		//                 $aa_disponibles    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Funci�n que carga los datos de los usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 30/10/2006								Fecha �ltima Modificaci�n :28/08/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT tepuy_ctrl_numero.codsis,tepuy_ctrl_numero.procede,tepuy_ctrl_numero.codusu,tepuy_ctrl_numero.prefijo,".
		        " (SELECT nomusu from sss_usuarios where codemp ='".$as_codemp."' and tepuy_ctrl_numero.codusu=sss_usuarios.codusu) as nomusu,".
			    " (SELECT apeusu from sss_usuarios where codemp ='".$as_codemp."' and tepuy_ctrl_numero.codusu=sss_usuarios.codusu) as apeusu,".
				" (SELECT cedusu from sss_usuarios where codemp ='".$as_codemp."' and tepuy_ctrl_numero.codusu=sss_usuarios.codusu) as cedusu".
				" FROM tepuy_ctrl_numero,sss_usuarios".
				" WHERE tepuy_ctrl_numero.codemp ='".$as_codemp."' ".
				" AND  tepuy_ctrl_numero.codemp=sss_usuarios.codemp AND tepuy_ctrl_numero.codusu=sss_usuarios.codusu".
				" AND tepuy_ctrl_numero.prefijo='".$as_prefijo."'".
				" ORDER BY nomusu";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tepuy_cfg_c_ctrl_numero M�TODO->uf_sss_load_usuarios_asignados ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;  
				$aa_asignados[$li_pos]["codusu"]=$row["codusu"];
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_usuarios_asignados
	
   function  uf_sss_buscar_usuarios_disponibles($as_empresa,$as_codsis,$as_prefijo,&$aa_disponibles)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_buscar_usuarios_disponibles
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $aa_usuarios    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Funci�n que carga los datos de los usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 30/10/2006								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		list($lb_valido,$as_codproc)=$this->uf_buscar_campo("tepuy_procedencias","codsis"," procede='".$as_codsis."'");
		$ls_sql="Select  sss_usuarios.codusu,sss_usuarios.cedusu,sss_usuarios.nomusu,sss_usuarios.apeusu".
				" from sss_usuarios where sss_usuarios.codusu not in".
				"   (select codusu from tepuy_ctrl_numero where tepuy_ctrl_numero.codemp='".$as_empresa."' ".
				"    and tepuy_ctrl_numero.codsis='".$as_codproc."' and tepuy_ctrl_numero.procede='".$as_codsis."' )".
				//"    and tepuy_ctrl_numero.prefijo='".$as_prefijo."') ".
				" ORDER BY codusu"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tepuy_cfg_c_ctrl_numero M�TODO->uf_sss_buscar_usuarios_disponibles ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_disponibles[$li_pos]["codusu"]=$row["codusu"];
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_buscar_usuarios_disponibles
    
	function  uf_sss_buscar_usuarios_asignados($as_empresa,$as_codsis,$as_prefijo,&$aa_asignados)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_buscar_usuarios_asignados
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_prefijo    // prefijo 
		//                 $aa_disponibles    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Funci�n que carga los datos de los usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 30/10/2006								Fecha �ltima Modificaci�n :28/08/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		list($lb_valido,$as_codproc)=$this->uf_buscar_campo("tepuy_procedencias","codsis"," procede='".$as_codsis."'");
		$ls_sql="SELECT codusu from tepuy_ctrl_numero ".
				" WHERE tepuy_ctrl_numero.codemp ='".$as_empresa."' ".
				" AND  tepuy_ctrl_numero.procede='".$as_codsis."'".
				" AND tepuy_ctrl_numero.codsis='".$as_codproc."'".
				" AND tepuy_ctrl_numero.prefijo='".$as_prefijo."'".
				" ORDER BY codusu";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tepuy_cfg_c_ctrl_numero M�TODO->uf_sss_buscar_usuarios_asignados ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;  
				$aa_asignados[$li_pos]["codusu"]=$row["codusu"];
				$li_pos=$li_pos+1;
				$lb_valido1=$li_pos;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido1;
	}  // end  function  uf_sss_buscar_usuarios_asignados 
	
	function  uf_verificar_comprobante($as_empresa,$as_prefijo,$as_codsis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_comprobante
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $aa_usuarios    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Funci�n que carga los datos de los usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 30/10/2006								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		list($lb_valido,$as_codproc)=$this->uf_buscar_campo("tepuy_procedencias","codsis"," procede='".$as_codsis."'");
		$ls_sql="SELECT * FROM tepuy_ctrl_numero".
				" WHERE tepuy_ctrl_numero.codemp ='".$as_empresa."' ".
				" and tepuy_ctrl_numero.codsis='".$as_codproc."'".
				" and tepuy_ctrl_numero.procede='".$as_codsis."'".
				" ORDER BY codusu";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tepuy_cfg_c_ctrl_numero M�TODO->uf_verificar_comprobante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_verificar_comprobante  
 function uf_guardar_ctrl_numero($ar_datos,$as_codusu,$as_codsis,$aa_seguridad)
{  	   
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Function:  uf_guardar_procedencia
//	Access:  public
//	Arguments: $ar_datos,$aa_seguridad,$aa_codusu
//   ar_datos=  Arreglo Cargado con la informaci�n proveniente de la Interfaz de Procedencias
//	Returns:	$lb_valido= Variable que devuelve true si la operaci�n 
//                          fue exitosa de lo contrario devuelve false 
//	Description:Este m�todo se encarga de realizar la inserci�n del registro si este existe con los 
//              datos,de lo contrario realiza una actualizaci�n con los datos cargados en el arreglo 
//              $ar_datos                  
/////////////////////////////////////////////////////////////////////////////////////////////////////////
  $as_longprefijo="";
  $as_codigo  = $ar_datos["codigo"];
  $as_codproc = $ar_datos["codsis"];
  $as_maxlon  = $ar_datos["maxlon"];
  $as_prefijo = $ar_datos["prefijo"];
  $as_numini  = $ar_datos["numini"];
  $as_numfin  = $ar_datos["numfin"];
  $as_numact  = $ar_datos["nunact"];

    $ls_sql="  INSERT INTO tepuy_ctrl_numero ".
			" (codemp,codsis ,procede,codusu,id,prefijo,nro_inicial,nro_final,maxlen,nro_actual,estact)".
			" VALUES ('".$this->codemp."','".$as_codsis."','".$as_codproc."','".$as_codusu."','".$as_codigo."','".$as_prefijo."','".$as_numini."','".$as_numfin."','".$as_maxlon."','".$as_numact."',1)";

	$this->io_sql->begin_transaction();             
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
		 $this->io_sql->rollback();
		 $this->io_msg->message("CLASE->tepuy_cfg_c_ctrl_numero; METODO->uf_guardar_ctrl_numero;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
		 $lb_valido=false;
	   }
	else
	   {   
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="INSERT";
		 $ls_descripcion ="Insert� en CFG un Nuevo control n�mero  ".$as_codigo." para el sistema".$as_codsis." y Procede ".$as_codproc;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion); 
		 /////////////////////////////////         SEGURIDAD               ///////////////////////////
		 $lb_valido=true;
	   }	  	
	return $lb_valido;
	$this->io_sql->close();
 }

function uf_actualizar_ctrl_numero($ar_datos,$as_codusu,$as_codsis,$aa_seguridad)
{  	   
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Function:  uf_actualizar_ctrl_numero
//	Access:  public
//	Arguments: $ar_datos,$aa_seguridad,$aa_codusu
//   ar_datos=  Arreglo Cargado con la informaci�n proveniente de la Interfaz de Procedencias
//	Returns:	$lb_valido= Variable que devuelve true si la operaci�n 
//                          fue exitosa de lo contrario devuelve false 
//	Description:Este m�todo se encarga de actualizar el registro si este existe con los 
//              datos,de lo contrario realiza una actualizaci�n con los datos cargados en el arreglo 
//              $ar_datos                  
/////////////////////////////////////////////////////////////////////////////////////////////////////////
  $as_codigo  = $ar_datos["codigo"];
  $as_codproc = $ar_datos["codsis"];
  $as_maxlon  = $ar_datos["maxlon"];
  $as_prefijo = $ar_datos["prefijo"]; 
  $as_numini  = $ar_datos["numini"];
  $as_numfin  = $ar_datos["numfin"];
  $as_numact  = $ar_datos["nunact"];
 
    $lb_valido=true;
	$ls_sql=" UPDATE tepuy_ctrl_numero ".
		    " SET  prefijo      = '".$as_prefijo."', ".
			"      nro_inicial  = '".$as_numini."',  ".
			"      nro_final    = '".$as_numfin."',  ".
			"      maxlen       = '".$as_maxlon."',  ".
			"      nro_actual   = '".$as_numact."',  ".
		    "      estact       = 1                  ".
		    " WHERE codemp='".$this->codemp."' AND codsis='".$as_codsis."' AND procede='".$as_codproc."'".
			" AND codusu='".$as_codusu."'";

	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
		 $this->io_sql->rollback();
		 $this->io_msg->message("CLASE->tepuy_cfg_c_ctrl_numero; METODO->uf_actualizar_ctrl_numero;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
		 $lb_valido=false;
	   }
	else
	   {   
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="UPDATE";
		 $ls_descripcion ="Actualiz� en CFG control n�mero  ".$as_codigo." para el sistema".$as_codsis." y Procede ".$as_codproc;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               ///////////////////////////
		 $lb_valido=true;
	   }	  
	return $lb_valido;	
  $this->io_sql->close();
 }	
   
function  uf_buscar_usuarios_disponibles($as_empresa,$as_codsis,$as_prefijo,&$aa_disponibles)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_usuarios_disponibles
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $aa_usuarios    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Funci�n que carga los datos de los usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 30/10/2006								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false; 
		list($lb_valido,$as_codproc)=$this->uf_buscar_campo("tepuy_procedencias","codsis"," procede='".$as_codsis."'");
		$ls_sql="Select  sss_usuarios.codusu,sss_usuarios.cedusu,sss_usuarios.nomusu,sss_usuarios.apeusu".
				" from sss_usuarios where sss_usuarios.codusu not in".
				"   (select codusu from tepuy_ctrl_numero where tepuy_ctrl_numero.codemp='".$as_empresa."' ".
				"    and tepuy_ctrl_numero.codsis='".$as_codproc."' and tepuy_ctrl_numero.procede='".$as_codsis."') ".
				//"    and tepuy_ctrl_numero.prefijo='".$as_prefijo."') ".
				" ORDER BY codusu";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tepuy_cfg_c_ctrl_numero M�TODO->uf_buscar_usuarios_disponibles ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_disponibles[$li_pos]["codusu"]=$row["codusu"];
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_buscar_usuarios_disponibles
	
function uf_delete_usuarioasignados($as_empresa,$as_codsis,$as_codproc,$as_prefijo,$aa_asignados,$as_codusu,$aa_seguridad)
{   
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Function:  uf_sss_delete_usuarioasignados
//	Access:  public
//	Arguments:
// $as_codigo=  Valor a buscar dentro de la tabla de procedencias.
//	  Returns:	$lb_valido= Variable que devuelve true si encontro el registro 
//                          de lo contrario devuelve false. 
//	Description: Este m�todo que se encarga de borrar los usuarios asinados a un prefijo.
/////////////////////////////////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql    = " DELETE FROM tepuy_ctrl_numero ".
	              " WHERE tepuy_ctrl_numero.codemp ='".$as_empresa."' ".
				  " AND  tepuy_ctrl_numero.procede='".$as_codproc."'".
				  " AND tepuy_ctrl_numero.codsis='".$as_codsis."'".
				  " AND tepuy_ctrl_numero.prefijo='".$as_prefijo."'".
				  " AND tepuy_ctrl_numero.codusu='".$as_codusu."'"; 
    $this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   { 
	          $this->io_msg_error="CLASE->tepuy_CFG_C_PROCEDENCIAS; METODO->uf_sss_delete_usuarioasignados;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);   
	   }
    else
	   {
	     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	     $ls_evento="DELETE";
	     $ls_descripcion ="Elimin� en CFG en la tabla siges_ctrl_numero, el codigo de usuario ".$as_codusu;
	     $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	     $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	     $aa_seguridad["ventanas"],$ls_descripcion);
	     /////////////////////////////////         SEGURIDAD               ///////////////////////////// 
		 $lb_valido = true;
	   }
	return $lb_valido;
}	       

function  uf_verificar_prefijo($as_empresa,&$as_codsis,&$as_prefi,&$li_sal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_prefijo
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $aa_usuarios    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Funci�n que verifica si ya exite un documento con ese prefijo
		//	   Creado Por: 
		// Fecha Creaci�n: 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_vali=false;
		$li_sal=0;
		$as_longprefijo= strlen($as_prefi); 
	    if($as_longprefijo==4)
	    {
		  $as_prefi="00".$as_prefi;
	    } 
		list($lb_valido,$as_codproc)=$this->uf_buscar_campo("tepuy_procedencias","codsis"," procede='".$as_codsis."'");
	
		$ls_sql="Select  tepuy_ctrl_numero.codsis,tepuy_ctrl_numero.prefijo".
				" FROM tepuy_ctrl_numero ".
	              " WHERE tepuy_ctrl_numero.codemp ='".$as_empresa."' ".
				  " AND  tepuy_ctrl_numero.codsis='".$as_codproc."'".
				  " AND tepuy_ctrl_numero.procede='".$as_codsis."'".
				  " AND tepuy_ctrl_numero.prefijo='".$as_prefi."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->uf_verificar_prefijo M�TODO->uf_sss_load_usuarios_disponibles ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{ 
			  $li_numrows=$this->io_sql->num_rows($rs_data);       
			  if ($li_numrows>=1)
			  {
				 $lb_vali=true;
				 $li_sal=1;				
				 $this->io_sql->free_result($rs_data);  
			  }
		 } 
		return $lb_vali;
	}  // end  function  uf_verificar_prefijo
		           
}//Fin de la Clase.
?>