<?php
class tepuy_siv_c_proveedor
 {
    var $ls_sql="";
	var $la_emp;
	var $io_msg_error;
	
	function tepuy_siv_c_proveedor()
	{
   		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
        require_once("../shared/class_folder/tepuy_c_seguridad.php");
 	    require_once("../shared/class_folder/class_funciones.php");
 	    $this->io_funcion = new class_funciones();
		$this->seguridad = new tepuy_c_seguridad();	 		
        $io_conect=new tepuy_include();
		$conn=$io_conect->uf_conectar();
		$this->la_emp=$_SESSION["la_empresa"];
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->io_sql=new class_sql($conn); //Instanciando  la clase sql
		$_SESSION["gestor"]="MYSQL";
		$this->gestor=$_SESSION["gestor"];
		$this->io_msg= new class_mensajes();
	}


function uf_select_validar_rif($as_codemp,$as_rif)
{
//////////////////////////////////////////////////////////////////////////////
//	Metodo       uf_validar_rif
//	Access       public
//	Arguments    $as_codemp,$as_rif
//	Returns      una variable booleana ($lb_valido)		
//	Description  Funcion que encarga de verificar si un rif ya se encuentra 
//               Registrado asignado a otro proveedor 
//////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql    = "SELECT * FROM rpc_proveedor WHERE codemp='".$as_codemp."' AND rifpro='".$as_rif."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		  $lb_valido=false;
	      $this->is_msg_error="CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_select_validar_rif; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	   }
	else
	   {
		 $li_numrows = $this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
			{ 
			  $lb_valido = true;
			  $this->io_sql->free_result($rs_data);
			}
	   }
return $lb_valido;
}

function uf_insert_proveedor($as_codemp,$ar_datos,$aa_seguridad)
{  	   
//////////////////////////////////////////////////////////////////////////////
//	Metodo        uf_insert_proveedor
//	Access        public
//	Arguments     $as_codemp,$ar_datos,$aa_seguridad
//	Returns		
//	Description   Funcion que carga los valores traidos en la carga de datos desde el $ar_datos 
//               y asigna el valor respectivo a cada variable y a realiza una busqueda para 
//               decidir si el registro ya existe para actualizarlo "UPDATE"o si 
//			      el registro no existe realizar un "INSERT". 
//////////////////////////////////////////////////////////////////////////////

  $ls_codigo       = $ar_datos["codpro"];
  $ls_nombre       = $ar_datos["nompro"];
  $ls_direccion    = $ar_datos["dirpro"];
  $ls_tiporg       = $ar_datos["tiporg"];
  $ls_telefono     = $ar_datos["telpro"];
  $ls_fax          = $ar_datos["faxpro"];
  $ls_nacionalidad = $ar_datos["nacpro"];
  $ls_especialidad = $ar_datos["esppro"];
  $ls_rif          = $ar_datos["rif"];
  $ls_nit          = $ar_datos["nit"];
  $ls_banco        = $ar_datos["banco"];
  $ls_cuenta       = $ar_datos["cuenta"];
  $ls_moneda       = $ar_datos["moneda"];
  $ls_graemp       = $ar_datos["graemp"];
  $ls_emailrep     = $ar_datos["txtemailrep"];
  $ls_pais         = $ar_datos["pais"];
  $ls_estado       = $ar_datos["estado"];
  $ls_municipio    = $ar_datos["municipio"];
  $ls_parroquia    = $ar_datos["parroquia"];
  $ls_contable     = $ar_datos["contable"];
  $ls_contablecomp = $ar_datos["contablecomp"];
  $ls_observacion  = $ar_datos["observacion"];
  $ls_cedula       = $ar_datos["cedula"];
  $ls_nomrep       = $ar_datos["nomrep"];
  $ls_cargo        = $ar_datos["cargo"];
  $ls_numregRNC    = $ar_datos["numregrnc"];
  $ls_registro     = $ar_datos["registro"];
  $ls_fecreg       = $ar_datos["fecreg"];
  $ls_numero       = $ar_datos["numero"];
  $ls_tomo         = $ar_datos["tomo"];
  $ls_fecregRNC    = $ar_datos["fecregrnc"];
  $ls_fecregmod    = $ar_datos["fecregmod"];
  $ls_regmod       = $ar_datos["regmod"];
  $ls_nummod       = $ar_datos["nummod"];
  $ls_tommod       = $ar_datos["tommod"];
  $ls_numfol       = $ar_datos["numfol"];
  $ls_numfolmod    = $ar_datos["numfolmod"];
  $ls_numlic       = $ar_datos["numlic"];
  $ls_fecvenRNC    = $ar_datos["fecvenrnc"];
  $ls_regSSO       = $ar_datos["regsso"];
  $ls_fecvenSSO    = $ar_datos["fecvensso"];
  $ls_regINCE      = $ar_datos["regince"];
  $ls_fecvenINCE   = $ar_datos["fecvenince"];
  $ls_estprovedor  = $ar_datos["estatus"];
  $ls_pagweb       = $ar_datos["pagweb"];
  $ls_email        = $ar_datos["email"];
  $ls_inspector    = $ar_datos["inspector"];
  $ld_contratista=$ar_datos["estcon"];
  $ld_proveedor=$ar_datos["estpro"];
  $ld_capital      = $ar_datos["capital"];
  $ld_capital      = str_replace('.','',$ld_capital);
  $ld_capital      = str_replace(',','.',$ld_capital);      
  $ld_monmax       = $ar_datos["monmax"];      
  $ld_monmax       = str_replace('.','',$ld_monmax);
  $ld_monmax       = str_replace(',','.',$ld_monmax);
  $ls_codbansig    = $ar_datos["codbancof"];
  $ls_tipconpro    = $ar_datos["tipconpro"];

// agrega cuenta de contratista //
	$ls_sc_ctaant="11128010002";

  if ($ls_tipconpro=='-'){$ls_tipconpro='O';}
  if ($ls_tiporg=='00')
	 {
	   $ls_tiporg = '--';
	 }
    if ($ls_moneda=="000")
	 {
	   $ls_moneda='---';
	 }
  if (empty($ls_fecreg) || ($ls_fecreg=="--"))
     {
	   $ls_fecreg="1900-01-01 00:00:00";
     }
  if (empty($ls_fecregRNC) || ($ls_fecregRNC=="--"))
     {
	   $ls_fecregRNC="1900-01-01 00:00:00";
     }
  if (empty($ls_fecregmod) || ($ls_fecregmod=="--"))
     {
	   $ls_fecregmod="1900-01-01 00:00:00";
     }
  if (empty($ls_fecvenRNC) || ($ls_fecvenRNC=="--"))
     {
	   $ls_fecvenRNC="1900-01-01 00:00:00";
     }
  if (empty($ls_fecvenSSO) || ($ls_fecvenSSO=="--"))
     {
	   $ls_fecvenSSO="1900-01-01 00:00:00";
     }
  if (empty($ls_fecvenINCE) || ($ls_fecvenINCE=="--"))
     {
	   $ls_fecvenINCE="1900-01-01 00:00:00";
     }
  if ($ls_estprovedor=="A")
     {
	   $ls_estprov=0;
     } 	  
  if ($ls_estprovedor=="I")
     {
	   $ls_estprov=1;
     } 
  if ($ls_estprovedor=="B")
     {
	   $ls_estprov=2;
     }
  if ($ls_estprovedor=="S")
     {
	   $ls_estprov=3;
     } 	  
   if ($ls_inspector==1)
	 {
	   $ld_inspector=1;
	 }
  else
	 {
	   $ld_inspector=0;
	 }
  if ($this->uf_select_validar_rif($as_codemp,$ls_rif))
     {				 
	   $this->io_msg->message('El RIF Ya Existe en un Proveedor !!!');           
	   $lb_valido=false;
	 }
  else
	 {
	  $ls_sql=" INSERT INTO rpc_proveedor(codemp,cod_pro,nompro,dirpro,telpro,faxpro,nacpro,codtipoorg,                      ".
		  " codesp,rifpro,nitpro,capital,monmax,codban,ctaban,codmon,sc_cuenta,sc_cuenta_comp,obspro,codpai,codest,                     ".
			  " codmun,codpar,cedrep,nomreppro,carrep,ocei_no_reg,registro,fecreg,nro_reg,tomo_reg,                          ".
			  " ocei_fec_reg,regmod,fecregmod,nummod,tommod,inspector,estpro,estcon,folreg,folmod,numlic,                    ".
			  " estprov,pagweb,email,fecvenrnc,numregsso,fecvensso,numregince,fecvenince,graemp,emailrep,codbansig,tipconpro,sc_ctaant)".
			  " VALUES                                                                                                       ".
			  " ('".$as_codemp."','".$ls_codigo."','".$ls_nombre."','".$ls_direccion."',                                     ".
			  " '".$ls_telefono."','".$ls_fax."','".$ls_nacionalidad."','".$ls_tiporg."',                                    ".
			  " '".$ls_especialidad."','".$ls_rif."','".$ls_nit."',".$ld_capital.",".$ld_monmax.",                           ".
	 " '".$ls_banco."','".$ls_cuenta."','".$ls_moneda."','".$ls_contable."','".$ls_contablecomp."','".$ls_observacion."',                  ".
			  " '".$ls_pais."','".$ls_estado."','".$ls_municipio."','".$ls_parroquia."','".$ls_cedula."',                    ".
			  " '".$ls_nomrep."','".$ls_cargo."','".$ls_numregRNC."','".$ls_registro."','".$ls_fecreg."',                    ".
			  " '".$ls_numero."','".$ls_tomo."','".$ls_fecregRNC."','".$ls_regmod."','".$ls_fecregmod."',                    ".
			  " '".$ls_nummod."','".$ls_tommod."','".$ld_inspector."','".$ld_proveedor."','".$ld_contratista."',             ".
			  " '".$ls_numfol."','".$ls_numfolmod."','".$ls_numlic."','".$ls_estprov."','".$ls_pagweb."','".$ls_email."',    ".
			  " '".$ls_fecvenRNC."','".$ls_regSSO."','".$ls_fecvenSSO."','".$ls_regINCE."','".$ls_fecvenINCE."',             ".
			  " '".$ls_graemp."','".$ls_emailrep."','".$ls_codbansig."','".$ls_tipconpro."','".$ls_sc_ctaant."')                                 ";  
		//print $ls_sql;
	  $this->io_sql->begin_transaction(); 
	  $rs_data=$this->io_sql->execute($ls_sql);
	  if ($rs_data===false)
	     { 
		   $lb_valido=false;
		   $this->is_msg_error="CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_insert_proveedor; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		 }
	  else
	     { 
		   $lb_valido=true;
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento="INSERT";
		   $ls_sql = str_replace("'",'`',$ls_sql);
		   $ls_descripcion ="Insertó en RPC al Proveedor ".$ls_codigo." con ".$ls_sql;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               ///////////////////////////  		   			 
	       if ($lb_valido)
			  {//**********************   Envio del Correo ***********************
			   if (!empty($ls_email))
			      {
				    $ls_asunto="Registro de Proveedores";                 
				    $ls_cuerpo="Su Registro fue Exitoso y su Código es :  ".$ls_codigo;					
				    if (@mail($ls_email,$ls_asunto,$ls_cuerpo))
					   {
					     $this->io_msg->message('Correo Enviado !!!'); 
					   }
				    else
				       {
					     $this->io_msg->message('Falló el Envio del Correo al Proveedor !!!'); 
				       } 	
			      }
		     }
	     }	  	
     }
 return $lb_valido;
 } 

function uf_update_proveedor($as_codemp,$ar_datos,$aa_seguridad)
{  	   
//////////////////////////////////////////////////////////////////////////////
//	Metodo        uf_update_proveedor
//	Access        public
//	Arguments     $as_codemp,$ar_datos,$aa_seguridad
//	Returns		
//	Description   Funcion que carga los valores traidos en la carga de datos desde el $ar_datos 
//               y asigna el valor respectivo a cada variable y a realiza una busqueda para 
//               decidir si el registro ya existe para actualizarlo "UPDATE"o si 
//			      el registro no existe realizar un "INSERT". 
//////////////////////////////////////////////////////////////////////////////

  $ls_codigo=$ar_datos["codpro"];
  $ls_sc_ctaant="11128010002";
  $ls_nombre=$ar_datos["nompro"];
  $ls_direccion=$ar_datos["dirpro"];
  $ls_tiporg=$ar_datos["tiporg"];
  $ls_telefono=$ar_datos["telpro"];
  $ls_fax=$ar_datos["faxpro"];
  $ls_nacionalidad=$ar_datos["nacpro"];
  $ls_especialidad=$ar_datos["esppro"];
  $ls_rif=$ar_datos["rif"];
  $ls_nit=$ar_datos["nit"];
  $ld_capital=$ar_datos["capital"];
  $ld_monmax=$ar_datos["monmax"];
  $ls_banco=$ar_datos["banco"];
  $ls_cuenta=$ar_datos["cuenta"];
  $ls_moneda    = $ar_datos["moneda"];
  $ls_graemp    = $ar_datos["graemp"];
  $ls_emailrep  = $ar_datos["txtemailrep"];
  $ls_codbansig = $ar_datos["codbancof"]; 
  $ls_pais      = $ar_datos["pais"];
  $ls_estado    = $ar_datos["estado"];
  $ls_municipio = $ar_datos["municipio"];
  $ls_parroquia = $ar_datos["parroquia"];
  $ls_tipconpro = $ar_datos["tipconpro"];
  if ($ls_tipconpro=='-'){$ls_tipconpro='O';}
 //FIN UBICACIÓN GEOGRÁFICA
  $ls_contable=$ar_datos["contable"];
  $ls_contablecomp=$ar_datos["contablecomp"];
  $ls_observacion=$ar_datos["observacion"];

  /*Datos del Registro*/
  $ls_cedula=$ar_datos["cedula"];
  $ls_nomrep=$ar_datos["nomrep"];
  $ls_cargo=$ar_datos["cargo"];
  $ls_numregRNC=$ar_datos["numregrnc"];
  $ls_registro=$ar_datos["registro"];
  $ls_fecreg=$ar_datos["fecreg"];
  if(empty($ls_fecreg) || ($ls_fecreg=="--"))
  {
	$ls_fecreg="1900-01-01 00:00:00";
  }
  $ls_numero=$ar_datos["numero"];
  $ls_tomo=$ar_datos["tomo"];
  $ls_fecregRNC=$ar_datos["fecregrnc"];
  if(empty($ls_fecregRNC) || ($ls_fecregRNC=="--"))
  {
	$ls_fecregRNC="1900-01-01 00:00:00";
  }
  $ls_fecregmod=$ar_datos["fecregmod"];
  if(empty($ls_fecregmod) || ($ls_fecregmod=="--"))
  {
	$ls_fecregmod="1900-01-01 00:00:00";
  }
  $ls_regmod=$ar_datos["regmod"];
  $ls_nummod=$ar_datos["nummod"];
  $ls_tommod=$ar_datos["tommod"];
  $ls_numfol=$ar_datos["numfol"];
  $ls_numfolmod=$ar_datos["numfolmod"];
  $ls_numlic=$ar_datos["numlic"];
  $ls_fecvenRNC=$ar_datos["fecvenrnc"];
  if(empty($ls_fecvenRNC) || ($ls_fecvenRNC=="--"))
  {
	$ls_fecvenRNC="1900-01-01 00:00:00";
  }
  $ls_regSSO=$ar_datos["regsso"];
  $ls_fecvenSSO=$ar_datos["fecvensso"];
  if(empty($ls_fecvenSSO) || ($ls_fecvenSSO=="--"))
  {
	$ls_fecvenSSO="1900-01-01 00:00:00";
  }
  $ls_regINCE=$ar_datos["regince"];
  $ls_fecvenINCE=$ar_datos["fecvenince"];
  if(empty($ls_fecvenINCE) || ($ls_fecvenINCE=="--"))
  {
	$ls_fecvenINCE="1900-01-01 00:00:00";
  }
  $ls_estprovedor=$ar_datos["estatus"];
  $ls_pagweb=$ar_datos["pagweb"];
  $ls_email=$ar_datos["email"];
  if ($ls_estprovedor=="A")
     {
	   $ls_estprov=0;
     }	  
  if ($ls_estprovedor=="I")
  {
	 $ls_estprov=1;
  }
  if ($ls_estprovedor=="B")
  {
	 $ls_estprov=2;
  }
  if ($ls_estprovedor=="S")
  {
	 $ls_estprov=3;
  }	  
  
  $ls_inspector=$ar_datos["inspector"];
  
  if ($ls_inspector==1)
	 {
	   $ld_inspector=1;
	 }
  else
	 {
	   $ld_inspector=0;
	 }
  $ld_contratista=$ar_datos["estcon"];
  $ld_proveedor=$ar_datos["estpro"];

  $ld_capital=$ar_datos["capital"];

  $ld_capital=str_replace('.','',$ld_capital);
  $ld_capital=str_replace(',','.',$ld_capital);      
  $ld_monmax=$ar_datos["monmax"];      
 
  $ld_monmax=str_replace('.','',$ld_monmax);
  $ld_monmax=str_replace(',','.',$ld_monmax);

  $ls_sql=" UPDATE rpc_proveedor ".
		  " SET  nompro='".$ls_nombre."',dirpro='".$ls_direccion."',telpro='".$ls_telefono."',".
		  " faxpro='".$ls_fax."',nacpro='".$ls_nacionalidad."',codtipoorg='".$ls_tiporg."',".
		  " codesp='".$ls_especialidad."',rifpro='".$ls_rif."',nitpro='".$ls_nit."',".
		  " capital='".$ld_capital."',monmax='".$ld_monmax."',codban='".$ls_banco."',".
		  " ctaban='".$ls_cuenta."',codmon='".$ls_moneda."',sc_cuenta='".$ls_contable."',sc_cuenta_comp='".$ls_contablecomp."', ".
		  " obspro='".$ls_observacion."',codpai='".$ls_pais."',codest='".$ls_estado."',               ".
		  " codmun='".$ls_municipio."',codpar='".$ls_parroquia."',cedrep='".$ls_cedula."',            ".
		  " nomreppro='".$ls_nomrep."',carrep='".$ls_cargo."',ocei_no_reg='".$ls_numregRNC."',        ".
		  " registro='".$ls_registro."',fecreg='".$ls_fecreg."',nro_reg='".$ls_numero."',             ".
		  " tomo_reg='".$ls_tomo."',ocei_fec_reg='".$ls_fecregRNC."',regmod='".$ls_regmod."',         ".
		  " fecregmod='".$ls_fecregmod."',nummod='".$ls_nummod."',tommod='".$ls_tommod."',            ".
		  " inspector=".$ld_inspector.",estpro=".$ld_proveedor.",estcon=".$ld_contratista.",          ".
		  " folreg='".$ls_numfol."', folmod='".$ls_numfolmod."', numlic='".$ls_numlic."',             ".
		  " pagweb='".$ls_pagweb."',email='".$ls_email."',fecvenrnc='".$ls_fecvenRNC."',              ".
		  " numregsso='".$ls_regSSO."',fecvensso='".$ls_fecvenSSO."',numregince='".$ls_regINCE."',    ".
		  " fecvenince='".$ls_fecvenINCE."',estprov=".$ls_estprov.", graemp='".$ls_graemp."',         ".
		  " emailrep='".$ls_emailrep."', codbansig='".$ls_codbansig."', tipconpro='".$ls_tipconpro."', ".
		  " sc_ctaant='".$ls_sc_ctaant."' ".
		  " WHERE codemp='".$as_codemp."' AND cod_pro='".$ls_codigo."' ";
			//print $ls_sql;
$this->io_sql->begin_transaction(); 
$rs_data=$this->io_sql->execute($ls_sql);
if ($rs_data===false)
   {  
     $lb_valido=false;
     $this->is_msg_error="CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_update_proveedor; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
   }
else
   {  
	   $lb_valido=true;
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="UPDATE";
	   $ls_sql = str_replace("'",'`',$ls_sql);
	   $ls_descripcion ="Actualizó en RPC al Proveedor".$ls_codigo." con ".$ls_sql;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ///////////////////////////  		   
   }     
return $lb_valido;
}

function uf_select_proveedor($as_codemp,$as_codigo)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_proveedor
//	Access        public
//	Arguments     $as_codemp,$as_codpro
//	Returns	      lb_valido. Retorna una variable booleana
//	Description   Busca un registro dentro de la tabla rpc_proveedor en la base de datos y retorna una variable booleana de que existe 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql    = " SELECT * FROM rpc_proveedor WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codigo."'";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		  $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));		 
		  $lb_valido=false;
	   }
	else
	   {
		 $li_numrows = $this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
			{
			 $lb_valido=true;                  
			 $this->io_sql->free_result($rs_data);	
			}
	   }
return $lb_valido;
}

function uf_select_proveedor_sep($as_codemp,$as_codigo)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_proveedor_sep
//	Access        public
//	Arguments     $as_codemp,$as_codpro
//	Returns	      lb_valido. Retorna una variable booleana
//	Description   Busca un registro dentro de la tabla rpc_proveedor en la base de datos y retorna una variable booleana de que existe 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql    = " SELECT codemp FROM sep_solicitud WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codigo."'";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {		 
		  $this->is_msg_error="CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_select_proveedor_sep; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);	   
		  $lb_valido=false;
	   }
	else
	   {
		 $li_numrows = $this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
			{
			 $lb_valido=true;                  
			 $this->io_sql->free_result($rs_data);	
			}
	   }
return $lb_valido;
}

function uf_select_proveedor_soc($as_codemp,$as_codigo)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_proveedor_soc
//	Access        public
//	Arguments     $as_codemp,$as_codpro
//	Returns	      lb_valido. Retorna una variable booleana
//	Description   Busca un registro dentro de la tabla rpc_proveedor en la base de datos y retorna una variable booleana de que existe 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql    = " SELECT codemp FROM soc_ordencompra WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codigo."'";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		  $this->is_msg_error="CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_select_proveedor_soc; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);	   		 
		  $lb_valido=false;
	   }
	else
	   {
		 $li_numrows = $this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
			{
			 $lb_valido=true;                  
			 $this->io_sql->free_result($rs_data);	
			}
	   }
return $lb_valido;
}

function uf_select_proveedor_cxp($as_codemp,$as_codigo)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_proveedor_cxp
//	Access        public
//	Arguments     $as_codemp,$as_codpro
//	Returns	      lb_valido. Retorna una variable booleana
//	Description   Busca un registro dentro de la tabla rpc_proveedor en la base de datos y retorna una variable booleana de que existe 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql    = " SELECT codemp FROM cxp_rd WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codigo."'";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $this->is_msg_error="CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_select_proveedor_cxp; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);	   
		 $lb_valido=false;
	   }
	else
	   {
		 $li_numrows = $this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
			{
			 $lb_valido=true;                  
			 $this->io_sql->free_result($rs_data);	
			}
	   }
return $lb_valido;
}

function uf_select_proveedor_scb($as_codemp,$as_codigo)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_proveedor_scb
//	Access        public
//	Arguments     $as_codemp,$as_codpro
//	Returns	      lb_valido. Retorna una variable booleana
//	Description   Busca un registro dentro de la tabla rpc_proveedor en la base de datos y retorna una variable booleana de que existe 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql    = "SELECT codemp FROM scb_movbco WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codigo."'";	
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		  $this->is_msg_error="CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_select_proveedor_scb; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);	   		 
		  $lb_valido=false;
	   }
	else
	   {
		 $li_numrows = $this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
			{
			 $lb_valido=true;                  
			 $this->io_sql->free_result($rs_data);	
			}
	   }
return $lb_valido;
}

function uf_select_proveedor_sno($as_codemp,$as_codigo)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_proveedor_sno
//	Access        public
//	Arguments     $as_codemp,$as_codpro
//	Returns	      lb_valido. Retorna una variable booleana
//	Description   Busca un registro dentro de la tabla rpc_proveedor en la base de datos y retorna una variable booleana de que existe 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql    = "SELECT codemp FROM sno_dt_spg WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codigo."'";	
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		  $this->is_msg_error="CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_select_proveedor_sno; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);	   		 
		  $lb_valido=false;
	   }
	else
	   {
		 $li_numrows = $this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
			{
			 $lb_valido=true;                  
			 $this->io_sql->free_result($rs_data);	
			}
	   }
	if($lb_valido===false)
	{
		$ls_sql    = "SELECT codemp FROM sno_dt_scg WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codigo."'";	
		$rs_data   = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			  $this->is_msg_error="CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_select_proveedor_sno; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);	   		 
			  $lb_valido=false;
		   }
		else
		   {
			 $li_numrows = $this->io_sql->num_rows($rs_data);
			 if ($li_numrows>0)
				{
				 $lb_valido=true;                  
				 $this->io_sql->free_result($rs_data);	
				}
		   }
	}
return $lb_valido;
}

function uf_delete_proveedor($as_codemp,$as_codpro,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Metodo        uf_delete_proveedor
//	Access        public
//	Arguments     $as_codemp,$as_codpro,$aa_seguridad
//	Returns	      lb_valido. Retorna una variable booleana
//	Description   Metodo que se encarga de eliminar un Proveedor
//               en la base de datos y retorna una variable booleana de que fue eliminada 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   $lb_valido=true;
   
   if($this->uf_validardelete($as_codemp,$as_codpro))
   { 
			if ($this->uf_delete_detalles($as_codemp,$as_codpro,$aa_seguridad))
			{ 
				 $ls_sql  = "DELETE FROM rpc_proveedor WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codpro."'";
				 $rs_data = $this->io_sql->execute($ls_sql);
				 if ($rs_data===false)
				 {
					  $lb_valido = false;
					  $this->io_msg->message("CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_delete_proveedor; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				 }
			}
			else
			{
				 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
				 $ls_evento="DELETE";
				 $ls_descripcion ="Eliminó en RPC al Proveedor ".$as_codpro;
				 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
				 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
				 $aa_seguridad["ventanas"],$ls_descripcion);
				 /////////////////////////////////         SEGURIDAD               ///////////////////////////  		   
				 $lb_valido=true;
			}
	}
	else
	{
	   $this->io_msg->message("El Proveedor no puede ser eliminado, posee registros asociados a otras tablas !!!");
       $lb_valido = false;
	}		   			   
return $lb_valido;
}	                         
    
function uf_delete_detalles($as_codemp,$as_codpro,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_delete_detalles
//	Access       public
//	Arguments    $as_codemp,$as_codpro
//	Returns	     lb_valido. Retorna una variable booleana
//	Description  Funcion que se encarga validar si se puede eliminar un Proveedor 
//               en la base de datos y retorna una variable booleana de que es valido 
//////////////////////////////////////////////////////////////////////////////

 $lb_valido = false;
 if ($this->uf_select_proveedor($as_codemp,$as_codpro))
    {
      $ls_sql  = " DELETE FROM rpc_docxprov WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codpro."'";
      $rs_data = $this->io_sql->execute($ls_sql);
      if ($rs_data===false)
	     { 
           $lb_valido = false;
		   $this->io_msg->message("CLASE->tepuy_RPC_C_PROVEEDOR; METODO->validar_delete(rpc_docxprov); ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }
 	  else
	     {
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
 		   $ls_evento="DELETE";
		   $ls_descripcion ="Eliminó Documentos del Proveedor en RPC"." ".$as_codpro;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               ///////////////////////////
		   $lb_valido = true;
		 }
      if ($lb_valido)
	     {
           $ls_sql = "DELETE FROM rpc_clasifxprov WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codpro."'";
           $rs_data= $this->io_sql->execute($ls_sql);
		   if ($rs_data===false)
	      	  {  
		        $lb_valido = false;
		        $this->io_msg->message("CLASE->tepuy_RPC_C_PROVEEDOR; METODO->validar_delete(rpc_clasixprov); ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		      }
 		   else
		      {
		        /////////////////////////////////         SEGURIDAD               /////////////////////////////		
 		        $ls_evento="DELETE";
 		        $ls_descripcion ="Eliminó Calificaciones por Proveedor de la Tabla rpc_clasifxprov en RPC del Proveedor "." ".$as_codpro;
		        $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
				$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
				$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               ///////////////////////////
			    $lb_valido = true;
			  }
		 }	  	       
      if ($lb_valido)
	     {
			$ls_sql="DELETE ".
					"  FROM rpc_espexprov ".
					" WHERE codemp='".$as_codemp."'".
					"   AND cod_pro='".$as_codpro."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_msg->message("CLASE->tepuy_RPC_C_PROVEEDOR MÉTODO->validar_delete ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó las Especialidades asociadas al provedor ".$as_codpro;
				$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		 
		 }
      if ($lb_valido)
	     {
			$ls_sql="DELETE  FROM rpc_proveedorsocios WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codpro."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_msg->message("CLASE->tepuy_RPC_C_PROVEEDOR MÉTODO->validar_delete ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó los Socios asociados al proveedor ".$as_codpro;
				$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}		 
		 }		 
    }
return $lb_valido;
}

function uf_validardelete($as_codemp,$as_codpro)
{
//////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_validardelete
//	Access       public
//	Arguments    $as_codemp,$as_codpro
//	Returns	     lb_valido. Retorna una variable booleana
//	Description  Funcion que se encarga validar si se puede eliminar un Proveedor 
//               en la base de datos y retorna una variable booleana de que es valido 
//////////////////////////////////////////////////////////////////////////////

 $lb_valido = false;
 if ($this->uf_select_proveedor($as_codemp,$as_codpro))
    {
        if (!$this->uf_select_proveedor_sep($as_codemp,$as_codpro))
		{
		       if (!$this->uf_select_proveedor_soc($as_codemp,$as_codpro))
			   {			        
					if (!$this->uf_select_proveedor_cxp($as_codemp,$as_codpro))
					{					     
						 if (!$this->uf_select_proveedor_scb($as_codemp,$as_codpro))
						 {								
							 if (!$this->uf_select_proveedor_sno($as_codemp,$as_codpro))
							 {															
									$lb_valido = true;
							 }
						 } 	
						 
					} 	
			   } 	
		 } 	
    }
return $lb_valido;
}
	
function uf_select_llenarcombo_banco($ls_codemp)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_select_llenarcombo_banco
//	Access       public
//	Arguments    $ls_codemp
//	Returns	     rs_data. Retorna un resulset cargado con los bancos creados en la tabla scb_banco. 
//	Description  Devuelve un resulset con todos los bancos registrados para dicho 
//               codigo de empresa.
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql    = "SELECT * FROM scb_banco WHERE codemp='".$ls_codemp."' ORDER BY nomban ASC";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido = false;
		 $this->io_msg->message("CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_select_llenarcombo_banco; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	  {
	   	$li_numrows = $this->io_sql->num_rows($rs_data);	   
        if ($li_numrows>0)
		   {
		     $lb_valido = true;
		   }
	  }
return $rs_data;         
}

function uf_select_llenarcombo_tipoorganizacion($ls_codemp)
{
//////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_llenarcombo_tipoorganizacion
//	Access        public
//	Arguments     $ls_codemp
//	Returns	      rs. Retorna una resulset
//	Description   Devuelve un resulset con todos los tipos de organización de la tabla rpc_tipo_organizacion.
//////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql  = "SELECT * FROM rpc_tipo_organizacion ORDER BY dentipoorg ASC";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido = false;
		 $this->io_msg->message("CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_select_llenarcombo_tipoorganizacion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	  {
	   	$li_numrows = $this->io_sql->num_rows($rs_data);	   
        if ($li_numrows>0)
		   {
		     $lb_valido = true;
		   }
	  }
return $rs_data;         
}

function uf_select_llenarcombo_especialidad($ls_codemp)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_llenarcombo_especialidad
//	Access        public
//	Arguments     $ls_codemp: Código de la Empresa.
//	Returns	      rs. Retorna una resulset
//	Description   Devuelve un resulset con todas las especialidades de la tabla rpc_especialidad.
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql  = "SELECT * FROM rpc_especialidad ORDER BY denesp ASC";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido = false;
		 $this->io_msg->message("CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_select_llenarcombo_especialidad; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	  {
	   	$li_numrows = $this->io_sql->num_rows($rs_data);	   
        if ($li_numrows>0)
		   {
		     $lb_valido = true;
		   }
	  }
return $rs_data;         
}

function uf_select_llenarcombo_moneda($ls_codemp)
{
//////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_select_llenarcombo_moneda
//	Access       public
//	Arguments    
//   $ls_codemp  Código de la empresa.
//	    Returns	 rs. Retorna una resulset con los tipos de moneda creadas.
//	Description  Devuelve un resulset con todas las monedas de la tabla tepuy_moneda.
//////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql    = " SELECT * FROM tepuy_moneda ORDER BY denmon ASC";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido = false;
		 $this->io_msg->message("CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_select_llenarcombo_moneda; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	  {
	   	$li_numrows = $this->io_sql->num_rows($rs_data);	   
        if ($li_numrows>0)
		   {
		     $lb_valido = true;
		   }
	  }
return $rs_data;         
}

function uf_load_paises()
{
//////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_load_paises
//	Access       public
//	Arguments  
//	Returns	     rs. Retorna una resulset
//	Description  Devuelve un resulset con todos los paises de la tabla tepuy_pais.*/	
//////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql    = " SELECT * FROM tepuy_pais ORDER BY despai ASC";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido = false;
	     $this->io_msg->message("CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_load_paises; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
	     $li_numrows = $this->io_sql->num_rows($rs_data);	   
	     if ($li_numrows>0)
	        {
	          $lb_valido=true;
	        }	
       }
return $rs_data;
}

function uf_load_estados($as_codpai)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	    Funcion  uf_load_estados
//	     Access  public
//	  Arguments    
//  $as_codpai:  Código del Pais.
//	    Returns	 rs_data. Retorna una resulset cargado con los estados creados para el pais que viene como parametro.
//	Description  Devuelve un resulset con todos los estados asociados a un pais en específico de la tabla tepuy_estados.	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql  = "SELECT * FROM tepuy_estados WHERE codpai='".$as_codpai."' ORDER BY desest ASC";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido = false;
	     $this->io_msg->message("CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_load_estados; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
	     $li_numrows = $this->io_sql->num_rows($rs_data);	   
	     if ($li_numrows>0)
	        {
	          $lb_valido=true;
	        }	
       }
return $rs_data;
}

function uf_load_municipios($as_codpai,$as_codest)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_load_municipios
//	Access       public
//	Arguments    $as_codpai,$as_codest
//	Returns	     rs. Retorna una resulset
//	Description  Devuelve un resulset con todos los municipios asociados a 
//              un pais y un estado en específico de la tabla tepuy_municipio.	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$lb_valido = false;
	$ls_sql    = "SELECT * FROM tepuy_municipio WHERE codpai='".$as_codpai."' AND codest='".$as_codest."' ORDER BY denmun";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido = false;
	     $this->io_msg->message("CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_load_municipios; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
	     $li_numrows = $this->io_sql->num_rows($rs_data);	   
	     if ($li_numrows>0)
	        {
	          $lb_valido=true;
	        }	
       }
return $rs_data;
}

function uf_load_parroquias($as_codpai,$as_codest,$as_codmun)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_load_parroquias
//	Access        public
//	Arguments     $as_codpai,$as_codest,$as_codmun
//	Returns	      rs.  Retorna una resulset
//	Description   Devuelve un resulset con todas las parroquias asociadas a un pais,
//                estado,municipio en específico de la tabla tepuy_parroquia.
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $lb_valido = false;
	$ls_sql    = "SELECT * FROM tepuy_parroquia ".
                 " WHERE codpai='".$as_codpai."' AND codest='".$as_codest."' AND codmun='".$as_codmun."'".
                 " ORDER BY denpar ASC";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido = false;
	     $this->io_msg->message("CLASE->tepuy_RPC_C_PROVEEDOR; METODO->uf_load_parroquias; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
	     $li_numrows = $this->io_sql->num_rows($rs_data);	   
	     if ($li_numrows>0)
	        {
	          $lb_valido=true;
	        }	
       }
return $rs_data;
}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// PARA LA CONVERSIÓN MONETARIA
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_rpcproveedor($ar_datos,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_rpcproveedor
		//		   Access: private
		//	    Arguments: ar_datos  // arreglo de datos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		require_once("../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new tepuy_c_reconvertir_monedabsf();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		$ls_cod_pro= $ar_datos["codpro"];
		$li_capital=$ar_datos["capital"];
		$li_capital=str_replace('.','',$li_capital);
		$li_capital=str_replace(',','.',$li_capital);      
		$li_monmax=$ar_datos["monmax"];      
		$li_monmax=str_replace('.','',$li_monmax);
		$li_monmax=str_replace(',','.',$li_monmax);
		
		// Campos a Convertir
		$this->io_rcbsf->io_ds_datos->insertRow("campo","capitalaux");
		$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_capital);
		
		$this->io_rcbsf->io_ds_datos->insertRow("campo","monmaxaux");
		$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monmax);
		
		// Filtros de los Campos
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cod_pro);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("rpc_proveedor",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
		unset($this->io_rcbsf);
		return $lb_valido;
	}// end function uf_convertir_rpcproveedor
	//-----------------------------------------------------------------------------------------------------------------------------
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}//Fin de la Clase.
?>
