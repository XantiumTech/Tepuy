<?php
class tepuy_sfa_c_cliente
 {
var $ls_sql="";
var $la_emp;
var $is_msg_error;
 	
	function tepuy_sfa_c_cliente()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
        	require_once("../shared/class_folder/tepuy_c_seguridad.php");
	    	require_once("../shared/class_folder/class_funciones.php");
		$this->io_funcion   = new class_funciones();
		$this->seguridad    = new tepuy_c_seguridad();			
        	$io_conect          = new tepuy_include();
		$conn               = $io_conect->uf_conectar();
		$this->la_emp       = $_SESSION["la_empresa"];
		$this->io_sql       = new class_sql($conn); //Instanciando  la clase sql
		$this->io_msg       = new class_mensajes();
	}

	
function uf_insert_clientes($as_codemp,$ar_datos,$aa_seguridad)
{  
 //////////////////////////////////////////////////////////////////////////////
 //	Metodo       uf_insert_cliente
 //	Access       public
 //	Arguments    $as_codemp,$ar_datos,$aa_seguridad
 //	Returns	     $lb_valido. Retorna una variable booleana    
 //	Description  Funcion que se encarga de insertar en la tabla de clientes
 //////////////////////////////////////////////////////////////////////////////
   
  $ls_cedula       = $ar_datos["cedula"];
  $ls_nombre       = $ar_datos["nombre"];
  $ls_apellido     = $ar_datos["apellido"];
  $ls_direccion    = $ar_datos["direccion"];
  $ls_telefono     = $ar_datos["telefono"];
  $ls_celular      = $ar_datos["celular"];
  $ls_email        = $ar_datos["email"];
  $ls_contable     = $ar_datos["contable"];
  //$ls_contablecomp = $ar_datos["contablecomp"];
 // $ls_cuenta       = $ar_datos["cuenta"];
  //$ls_banco        = $ar_datos["banco"];  
  $ls_pais         = $ar_datos["pais"];
  $ls_estado       = $ar_datos["estado"];
  $ls_municipio    = $ar_datos["municipio"];
  $ls_parroquia    = $ar_datos["parroquia"];
  //$ls_tipocuenta   = $ar_datos["cmbtipcue"];
  $ls_rif          = $ar_datos["rif"];
  //$ls_codbansig    = $ar_datos["codbancof"];
  $ls_fecregcli    = $ar_datos["fecregcli"];
  $ls_fecregcli    = $this->io_funcion->uf_convertirdatetobd($ls_fecregcli);
  $ls_naccli       = $ar_datos["nacionalidad"];
  //$ls_numpasben    = $ar_datos["numpasben"];
  $ls_tipconcli    = $ar_datos["tipconcli"];
  $ls_trabajador='O';
  //if ($ls_tipconben=='-'){$ls_tipconben='O';}
  //$ls_tipcuebanben    = $ar_datos["tipcuebanben"];
  
  
  $ls_sql=" INSERT INTO sfa_cliente(codemp,cedcli,rifcli,nomcli,apecli,dircli,telcli,celcli,email,sc_cuenta, ".  		
	" codpai,codest,codmun,codpar,fecregcli,naccli,tipconcli,trabajador)                          ". 
		  "  VALUES('".$as_codemp."','".$ls_cedula."','".$ls_rif."','".$ls_nombre."','".$ls_apellido."','".$ls_direccion."','".$ls_telefono."',         ".
		  " '".$ls_celular."','".$ls_email."','".$ls_contable."', ".
		  " '".$ls_pais."','".$ls_estado."','".$ls_municipio."','".$ls_parroquia."','".$ls_fecregcli."',".
		  " '".$ls_naccli."','".$ls_tipconcli."','".$ls_trabajador."')                                                                     ";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
   {
	 $this->io_sql->rollback();
	 $this->is_msg_error="CLASE->tepuy_sfa_c_cliente; METODO->uf_insert_clientes; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	 $lb_valido=false;
   }
  else
   {   
	 $this->io_sql->commit();
	 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	 $ls_evento="INSERT";
	 $ls_sql = str_replace("'",'`',$ls_sql);
	 $ls_descripcion ="Insert� en SFA al cliente ".$ls_cedula." con ".$ls_sql;
	 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	 $aa_seguridad["ventanas"],$ls_descripcion);
	 /////////////////////////////////         SEGURIDAD               /////////////////////////////   			  
	 $lb_valido=true;   
   }	  	
return $lb_valido;	
}
	
function uf_update_cliente($as_codemp,$ar_datos,$aa_seguridad)
{  
//////////////////////////////////////////////////////////////////////////////
//	Metodo       uf_update_cliente
//	Access       public
//	Arguments    $as_codemp,$ar_datos,$aa_seguridad
//	Returns	     $lb_valido.  Retorna una variable booleana    
//	Description  Funcion que se encarga de actualizar en la tabla de clientes
//////////////////////////////////////////////////////////////////////////////
	   
  $ls_cedula       = $ar_datos["cedula"];
  $ls_nombre       = $ar_datos["nombre"];
  $ls_apellido     = $ar_datos["apellido"];
  $ls_direccion    = $ar_datos["direccion"];
  $ls_telefono     = $ar_datos["telefono"];
  $ls_celular      = $ar_datos["celular"];
  $ls_email        = $ar_datos["email"];
  $ls_contable     = $ar_datos["contable"];
  $ls_pais         = $ar_datos["pais"];
  $ls_estado       = $ar_datos["estado"];
  $ls_municipio    = $ar_datos["municipio"];
  $ls_parroquia    = $ar_datos["parroquia"];
 // $ls_tipocuenta   = $ar_datos["cmbtipcue"];
  $ls_rif          = $ar_datos["rif"];
  $ls_naccli       = $ar_datos["nacionalidad"];
  $ls_tipconcli    = $ar_datos["tipconcli"];
 // if ($ls_tipconben=='-'){$ls_tipconben='O';}
 // $ls_tipcuebanben    = $ar_datos["tipcuebanben"];
  
  $ls_sql=" UPDATE sfa_cliente ". 
		  " SET    nomcli='".$ls_nombre."',     apecli='".$ls_apellido."',    ".
		  "        dircli='".$ls_direccion."',  telcli='".$ls_telefono."',    ". 
		  "        celcli='".$ls_celular."',    email='".$ls_email."',         ". 
		  "        sc_cuenta='".$ls_contable."', ". //"', ctaBan='".$ls_cuenta."',       ". 
		 // "        sc_cuenta_comp='".$ls_contablecomp."', ".
		 // "        codban='".$ls_banco."',       codtipcta='".$ls_tipocuenta."',".
		  "        codpai='".$ls_pais."',        codest='".$ls_estado."',       ". 
		  "        codmun='".$ls_municipio."',   codpar='".$ls_parroquia."',    ". 
		  "        rifcli='".$ls_rif."', ".//"',         codbansig='".$ls_codbansig."', ".
		  "        naccli='".$ls_naccli."', tipconcli='".$ls_tipconcli."' ". 
		 // "        tipcuebanben='".$ls_tipcuebanben."'                          ".
		  " WHERE  codemp='".$as_codemp."'  AND  cedcli='".$ls_cedula."'      ";
//print $ls_sql;
 $this->io_sql->begin_transaction();             
 $rs_data=$this->io_sql->execute($ls_sql);
 if ($rs_data===false)
   {                              
	 $this->io_sql->rollback();
	 $this->is_msg_error="CLASE->tepuy_sfa_c_cliente; METODO->uf_update_cliente; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	 $lb_valido=false;
   }
 else
   {   
	 $this->io_sql->commit();
	 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	 $ls_evento="UPDATE";
     $ls_sql = str_replace("'",'`',$ls_sql);
	 $ls_descripcion ="Actualiz� en RPC al cliente".$ls_cedula." con ".$ls_sql;
	 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	 $aa_seguridad["ventanas"],$ls_descripcion);
	 /////////////////////////////////         SEGURIDAD               /////////////////////////// 			     
	 $lb_valido=true;
   }	  	
return $lb_valido;
}

function uf_select_cliente($as_codemp,$as_cedcli)
{
//////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_select_cliente
//	Access       public     
//	Arguments    $as_codemp,$as_cedcli
//	Returns	     $lb_valido. Retorna una variable booleana
//	Description  Busca un registro dentro de la tabla rpc_cliente en 
//              la base de datos y retorna una variable booleana de que existe 
//////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql    = "SELECT * FROM sfa_cliente WHERE codemp='".$as_codemp."' AND cedcli='" .$as_cedcli."'";
	//print $ls_sql;
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $this->io_msg_message("CLASE->tepuy_sfa_c_cliente; METODO->uf_update_cliente; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

function uf_delete_cliente($as_codemp,$as_cedcli,$aa_seguridad)
{   
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_cliente
//	          Access:  public
//	       Arguments: 
//        $as_codemp:
//        $as_cedben:
//     $aa_seguridad: 
//	         Returns:  $lb_valido.
//	     Description:  Funci�n que se encarga de actualizar los datos de una modalidad en la tabla soc_modalidadclausulas.  
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creaci�n:  20/02/2006       Fecha �ltima Actualizaci�n:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 

	$lb_valido   = false;
	$lb_existe   = $this->uf_select_cliente($as_codemp,$as_cedcli);
	$lb_relacion = $this->uf_check_relaciones($as_codemp,$as_cedcli);
	if(($lb_existe)&&(!$lb_relacion))
	  {
	    $ls_sql=" DELETE FROM sfa_cliente WHERE codemp='".$as_codemp."' AND cedcli='".$as_cedcli."'";
 	    $this->io_sql->begin_transaction();
	    $rs_data=$this->io_sql->execute($ls_sql);
	    if ($rs_data===false)
	       { 
		     $this->io_sql->rollback();
		     $this->is_msg_error="CLASE->tepuy_sfa_c_cliente; METODO->uf_delete_cliente; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		     $lb_valido=false;echo $this->io_sql->message;
	       }
	    else
	       {
		     $lb_valido = true;
		     $this->io_sql->commit();
		     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			 $ls_evento="DELETE";
			 $ls_descripcion ="Elimin� en SFA al cliente ".$as_cedcli;
			 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			 $aa_seguridad["ventanas"],$ls_descripcion);
			 /////////////////////////////////         SEGURIDAD               ///////////////////////////// 	  	     
	       }
	}	   
return $lb_valido;
}	                         

function uf_select_banco($as_codemp)
{
 //////////////////////////////////////////////////////////////////////////////
 //	Funcion      uf_select_banco
 //	Access       public
 //	Arguments    $as_codemp
 //	Returns	     rs_data. Retorna una resulset
 //	Description  Devuelve un resulset con todos los bancos registrados para dicho 
 //              codigo de empresa.
 //////////////////////////////////////////////////////////////////////////////

   $ls_sql=" SELECT * FROM scb_banco WHERE codemp='".$as_codemp."'ORDER BY nomban ASC ";
   $rs_data=$this->io_sql->select($ls_sql);
   $li_numrows=$this->io_sql->num_rows($rs_data);	   
   if ($li_numrows>0)
	  {
		$lb_valido=true;
	  }
   else
	 {
	   $lb_valido=false;
	   if ($this->io_sql->message!="")
		  {                              
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  }           
	 }	
   if ($lb_valido)
	  {
		return $rs_data;         
	  }
}

function uf_select_tipo_cuenta()
{
 //////////////////////////////////////////////////////////////////////////////
 //	Funcion      uf_select_tipo_cuenta
 //	Access       public
 //	Description  Devuelve un resulset con todos los tipos de 
 //              cuentas 
 //////////////////////////////////////////////////////////////////////////////

	$ls_sql=" SELECT * FROM scb_tipocuenta ORDER BY codtipcta ";
	$rs_data=$this->io_sql->select($ls_sql);
	$li_numrows=$this->io_sql->num_rows($rs_data);	   
	if ($li_numrows>0)
	 {
		 $lb_valido=true;
	 }
	else
	 {
		$lb_valido=false;
		if ($this->io_sql->message!="")
		   {                              
			 $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   }           
	 }	
	if($lb_valido)
	{
	  return $rs_data;         
	}
}

function uf_select_pais()
{
 //////////////////////////////////////////////////////////////////////////////
 //	Funcion      uf_select_pais
 //	Access       public
 //	Returns	     rs_data. Retorna una resulset
 //	Description  Devuelve un resulset con todos los paises de la tabla tepuy_pais.*/	
 //////////////////////////////////////////////////////////////////////////////

   $ls_sql=" SELECT * FROM tepuy_pais ORDER BY despai ASC ";
   $rs_data=$this->io_sql->select($ls_sql);
   $li_numrows=$this->io_sql->num_rows($rs_data);	   
   if ($li_numrows>0)
	 {
		 $lb_valido=true;
	 }
   else
	 {
		$lb_valido=false;
		if ($this->io_sql->message!="")
		   {                              
			 $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   }           
	 }	
   if($lb_valido)
   {
	  return $rs_data;         
   }
}

function uf_select_estado($as_codpai)
{
 //////////////////////////////////////////////////////////////////////////////
 //	Funcion      uf_select_estado
 //	Access       public
 //	Arguments    $as_codpai
 //	Returns	     rs_data. Retorna una resulset
 //	Description  Devuelve un resulset con todos los estados asociados a un pais 
 //              en espec�fico de la tabla tepuy_estados.	
 //////////////////////////////////////////////////////////////////////////////

   $ls_sql=" SELECT * FROM tepuy_estados WHERE codpai='".$as_codpai."' ORDER BY desest ASC ";
   $rs_data=$this->io_sql->select($ls_sql);
   $li_numrows=$this->io_sql->num_rows($rs_data);	   
   if ($li_numrows>0)
	 {
		 $lb_valido=true;
	 }
   else
	 {
		$lb_valido=false;
		if ($this->io_sql->message!="")
		   {                              
			 $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   }           
	 }	
   if($lb_valido)
   {
	  return $rs_data;         
   }
}

function uf_select_municipio($as_codpai,$as_codest)
{
 //////////////////////////////////////////////////////////////////////////////
 //
 //	Funcion      uf_select_municipio
 //	Access       public
 //	Arguments    $as_codpai,$as_codest
 //	Returns	     rs_data. Retorna una resulset
 //	Description  Devuelve un resulset con todos los municipios asociados a 
 //              un pais y un estado en espec�fico de la tabla tepuy_municipio.	
 //
 //////////////////////////////////////////////////////////////////////////////

   $ls_sql=" SELECT * FROM tepuy_municipio WHERE codpai='".$as_codpai."' AND codest='".$as_codest."' ORDER BY denmun";
   $rs_data=$this->io_sql->select($ls_sql);
   $li_numrows=$this->io_sql->num_rows($rs_data);	   
   if ($li_numrows>0)
	 {
		 $lb_valido=true;
	 }
   else
	 {
		$lb_valido=false;
		if ($this->io_sql->message!="")
		   {                              
			 $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   }           
	 }	
   if($lb_valido)
   {
	  return $rs_data;         
   }
}

function uf_select_parroquia($as_codpai,$as_codest,$as_codmun)
{
 //////////////////////////////////////////////////////////////////////////////
 //
 //	Funcion      uf_select_parroquia
 //	Access       public
 //	Arguments    $as_codpai,$as_codest,$as_codmun
 //	Returns	     rs_data. Retorna una resulset
 //	Description  Devuelve un resulset con todas las parroquias asociadas a un pais,
 //              estado,municipio en espec�fico de la tabla tepuy_parroquia.
 //
 //////////////////////////////////////////////////////////////////////////////

   $ls_sql=" SELECT * ".
		   " FROM tepuy_parroquia ". 
		   " WHERE codpai='".$as_codpai."' AND codest='".$as_codest."' ".
		   " AND codmun='".$as_codmun."' ". 
		   " ORDER BY codpar ";

   $rs_data=$this->io_sql->select($ls_sql);
   $li_numrows=$this->io_sql->num_rows($rs_data);	   
   if ($li_numrows>0)
	 {
		 $lb_valido=true;
	 }
   else
	 {
		$lb_valido=false;
		if ($this->io_sql->message!="")
		   {                              
			 $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   }           
	 }	
   if($lb_valido)
   {
	  return $rs_data;         
   }
}
	
function uf_check_relaciones($as_codemp,$as_cedcli)
{
	$ls_sql  = "SELECT numfactura FROM sfa_factura WHERE codemp='".$as_codemp."' AND trim(cedcli)='".trim($as_cedcli)."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$lb_valido=false;
		$this->is_msg_error="Error en consulta ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if ($row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_valido=true;
			$this->is_msg_error="El cliente no puede ser eliminado, posee facturas asociados !!!";
		}
		else
		{
			$lb_valido=false;
			$this->io_sql->free_result($rs_data);
		}
	}																			    		return $lb_valido;	
}	

function uf_load_personal($as_codemp,$as_cedula1,$as_cedula2,$as_orden,&$lb_valido)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	         Funcion:  uf_load_personal
//	          Access:  public
//	       Arguments:  $lb_valido = Variable booleana que retorna valido=true si la sentencia sql fue ejecuta con exito,
//                     en caso contrario $lb_valido=false.
//	         Returns:  rs_data. Retorna una resulset con el personal a transferir.
//	     Description:  Devuelve un resulset con todas las personas que se encuentran activas en la tabla sno_personal,
//                     para luego ser transferidas al modulo de Proveedores y clientes en la tabla de rpc_cliente.
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creaci�n:  02/02/2007       Fecha �ltima Actualizaci�n:02/02/2007.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT cedper, nomper, apeper ".
				"  FROM sno_personal ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND estper='1' ".
				"   AND cedper >= '".$as_cedula1."'".
				"   AND cedper <= '".$as_cedula2."'".
				"   AND cedper NOT IN (SELECT cedcli FROM sfa_cliente WHERE codemp = '".$as_codemp."')".
				" ORDER BY cedper ASC ";
	 $rs_data = $this->io_sql->select($ls_sql);
     if ($rs_data===false)
	    {
		  $lb_valido = false;
		  $this->io_msg->message("CLASE->tepuy_sfa_c_cliente; METODO->uf_load_personal; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
     else
	    {
		  $li_numrows = $this->io_sql->num_rows($rs_data);
		  if ($li_numrows<=0)
		     {
			   $lb_valido = false;
			 }
		}
     return $rs_data;
 }
 
function uf_load_datos_personal($as_codemp,$as_cedula)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	         Funcion:  uf_load_datos_personal
//	          Access:  public
//	       Arguments:  $as_codemp = C�digo de la empresa.
//                     $as_cedula = C�dula o C�digo del personal a encontrar su informacion.
//	         Returns:  rs_data. Retorna una resulset con el personal a transferir.
//	     Description:  Funci�n que carga toda la informaci�n de un personal en una estructura de datos tipo resulset.
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creaci�n:  02/11/2016       Fecha �ltima Actualizaci�n:02/11/2016.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  $ls_sql    = "SELECT codemp,cedper,codpai,codest,codmun,codpar,coreleper,nacper,nomper,apeper,dirper,telhabper,telmovper,fecingper, ".
  			   "	   (SELECT MAX(codban) ".
			   "          FROM sno_personalnomina ".
			   "         WHERE sno_personalnomina.codemp = sno_personal.codemp ".
			   "		   AND sno_personalnomina.codper = sno_personal.codper ".
			   "		 GROUP BY sno_personalnomina.codper	".
			   "		 ORDER BY sno_personalnomina.codper) AS codban, ".
  			   "	   (SELECT MAX(codcueban) ".
			   "          FROM sno_personalnomina ".
			   "         WHERE sno_personalnomina.codemp = sno_personal.codemp ".
			   "		   AND sno_personalnomina.codper = sno_personal.codper ".
			   "		 GROUP BY sno_personalnomina.codper	".
			   "		 ORDER BY sno_personalnomina.codper) AS ctaban ".
               "  FROM sno_personal ".
			   " WHERE codemp='".$as_codemp."' ".
			   "   AND cedper='".$as_cedula."' ";
//print $ls_sql;
  $rs_data   = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $this->is_msg_error="CLASE->tepuy_sep_c_cliente; METODO->uf_load_datos_personal; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	 }
  return $rs_data;
}

function uf_insert_cliente($as_codemp,$ar_datos,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	         Funcion:  uf_insert_cliente
//	          Access:  public
//	       Arguments:  $as_codemp   : C�digo de la empresa.
//                     $ar_datos    : Arreglo cargado con la informaci�n de inter�s del personal a insertar como cliente.
//                     $aa_seguridad: Arreglo cargado con la informaci�n de nombre de la pantalla, nonmbre del usuario,etc.
//	         Returns:  $lb_valido = Variable booleana que retorna valido=true si la sentencia sql fue ejecuta con exito,
//                     en caso contrario $lb_valido=false.
//	     Description:  Funci�n que se encarga de realizar la insercion del personal encontrado en sno_personal que no est�
//                     registrado como cliente en la tabla rpc_cliente.
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creaci�n:  05/02/2007       Fecha �ltima Actualizaci�n:05/02/2007.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $ls_cedula       = $ar_datos["cedula"];
  $ls_nombre       = $ar_datos["nombre"];
  $ls_apellido     = $ar_datos["apellido"];
  $ls_direccion    = $ar_datos["direccion"];
  $ls_telefono     = $ar_datos["telefono"];
  $ls_celular      = $ar_datos["celular"];
  $ls_email        = $ar_datos["email"];
  $ls_contable     = $ar_datos["contable"];
 // $ls_contablecomp = $ar_datos["contablecomp"];
  $ls_pais         = $ar_datos["pais"];
  $ls_estado       = $ar_datos["estado"];
  $ls_municipio    = $ar_datos["municipio"];
  $ls_parroquia    = $ar_datos["parroquia"];
  $ls_naccli       = $ar_datos["nacionalidad"];
  $ls_tipconcli    = $ar_datos["tipconcli"];
  $ls_bansigcof    = '---';
  $ls_fecregcli       = trim($ar_datos["fecregcli"]);

  $ls_trabajador=1;
  if($ls_codban=="")
  {
  	  $ls_codban       = '---';
  }
  $ls_ctaban       = $ar_datos["ctaban"];				     				   
  
  
  if ($ls_tipconben=='-'){$ls_tipconben='F';}
  $this->uf_load_and_insert_codbansig();
  $ls_sql=" INSERT INTO sfa_cliente(codemp,cedcli,nomcli,apecli,dircli,telcli,celcli,email,sc_cuenta,                ".
          " codpai,codest,codmun,codpar,fecregcli,trabajador,naccli)                                                            ". 
		  "  VALUES('".$as_codemp."','".$ls_cedula."','".$ls_nombre."','".$ls_apellido."','".$ls_direccion."','".$ls_telefono."',".
		  " '".$ls_celular."','".$ls_email."','".$ls_contable."','".$ls_pais."','".$ls_estado."','".$ls_municipio."',            ".
		  "'".$ls_parroquia."','".$ls_fecregcli."','".$ls_trabajador."','".$ls_naccli."')";
	//print $ls_sql;
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
     {
	   $this->is_msg_error="CLASE->tepuy_sfa_c_cliente; METODO->uf_insert_personal; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	   $lb_valido=false;
     }
  else
     {   
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento      = "INSERT";
	   $ls_sql         = str_replace("'",'`',$ls_sql);
	   $ls_descripcion = "Insert� en SFA al Personal ".$ls_cedula." como Cliente ".$ls_sql;
	   $ls_variable    = $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////   			  
	  $lb_valido=true;   
     }	  	
  return $lb_valido;	
}

function uf_load_and_insert_codbansig()
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	         Funcion:  uf_load_and_insert_codbansig
//	          Access:  public
//	         Returns:  $lb_valido = Variable booleana que retorna valido=true si la sentencia sql fue ejecuta con exito,
//                     en caso contrario $lb_valido=false.
//	     Description:  Funci�n que se encarga de realizar una consulta para verificar que exista el Codigo del Banco SIGECOF por defecto,
//                     si no es encontrado el registro, la funci�n realiza la inserci�n del mismo.
//     Elaborado Por:  Ing. Miguel Palencia.
// Fecha de Creaci�n:  05/02/2007       Fecha �ltima Actualizaci�n:05/02/2007.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  $ls_sql  = "SELECT codbansig FROM tepuy_banco_sigecof WHERE codbansig='---'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $this->io_msg->message("CLASE->tepuy_sep_c_cliente; METODO->uf_load_and_insert_codbansig(SELECT); ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   $lb_valido=false;
	 }
  else
     {
	   $li_numrows = $this->io_sql->num_rows($rs_data);
	   if ($li_numrows<=0)
	      {
		    $ls_sql   = "INSERT INTO tepuy_banco_sigecof (codbansig, denbansig) VALUES ('---','---seleccione---')";
		    $rs_datos = $this->io_sql->execute($ls_sql);
			if ($rs_datos===false)
			   {
	             $this->io_msg->message("CLASE->tepuy_sep_c_cliente; METODO->uf_load_and_insert_codbansig(INSERT); ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	             $lb_valido=false;
			   }
		  }
	 } 
  return $lb_valido;
} 
}
?>
