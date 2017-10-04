<?PHP 
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_mensajes.php");
/*********************************************************************************************************************************/	
class tepuy_spi_funciones_reportes
{
    //conexion	
	var $sqlca;   
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; // datastore empresa
	var $dts_reporte;
	var $obj="";
	var $io_sql;
	var $io_include;
	var $io_connect;
	var $io_function;	
	var $io_msg;
	var $io_fecha;
	var $tepuy_int_spg;
/**********************************************************************************************************************************/	
    function  tepuy_spi_funciones_reportes()
    {
		$this->io_function=new class_funciones() ;
		$this->io_include=new tepuy_include();
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->obj=new class_datastore();
		$this->dts_reporte=new class_datastore();
		$this->io_fecha = new class_fecha();
		$this->io_msg=new class_mensajes();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
    }
/**********************************************************************************************************************************/
    function uf_spg_reporte_select_min_coduniadm($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
	                                             &$as_coduniadm)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_coduniadm
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2 
	 //                     $as_codestpro3  // codigo de estructura programatica 3 
	 //                     $as_codestpro4  // codigo de estructura programatica 4  
	 //                     $as_codestpro5  // codigo de estructura programatica 5  (referencia)
	 //                     $coduniadm     //  codigo de la unidad administrativas
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_gestor = $_SESSION["ls_gestor"];
	 $ls_estructura=$as_codestpro1.$as_codestpro2.$as_codestpro3.$as_codestpro4.$as_codestpro5;
	 if (strtoupper($ls_gestor)=="MYSQL")
	 {
		   if($ls_estructura!="")
		   {
		      $ls_cadena=" AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)=";
		   }
		   else
		   {
		      $ls_cadena="";
		   }
	 }
	 else
	 {
		   if($ls_estructura!="")
		   {
		       $ls_cadena=" AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5=";
		   }
		   else
		   {
		       $ls_cadena="";
		   }
	 }
	 $ls_sql=" SELECT min(coduniadm) as coduniadm ".
			 " FROM   spg_unidadadministrativa ".
             " WHERE  codemp='".$this->ls_codemp."'  ".
			 "        ".$ls_cadena." '".$ls_estructura."' ".
             " ORDER BY coduniadm ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
							      MÉTODO->uf_spg_reporte_select_min_coduniadm  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_coduniadm=$row["coduniadm"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro3
/**********************************************************************************************************************************/
    function uf_spg_reporte_select_max_coduniadm($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
	                                             &$as_coduniadm)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_coduniadm
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2 
	 //                     $as_codestpro3  // codigo de estructura programatica 3 
	 //                     $as_codestpro4  // codigo de estructura programatica 4  
	 //                     $as_codestpro5  // codigo de estructura programatica 5  (referencia)
	 //                     $coduniadm     //  codigo de la unidad administrativas
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_gestor = $_SESSION["ls_gestor"];
	 $ls_estructura=$as_codestpro1.$as_codestpro2.$as_codestpro3.$as_codestpro4.$as_codestpro5;
	 if (strtoupper($ls_gestor)=="MYSQL")
	 {
		   if($ls_estructura!="")
		   {
		      $ls_cadena=" AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)=";
		   }
		   else
		   {
		      $ls_cadena="";
		   }
	 }
	 else
	 {
		   if($ls_estructura!="")
		   {
		       $ls_cadena=" AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5=";
		   }
		   else
		   {
		       $ls_cadena="";
		   }
	 }
	 $ls_sql=" SELECT max(coduniadm) as coduniadm ".
			 " FROM   spg_unidadadministrativa ".
             " WHERE  codemp='".$this->ls_codemp."'  ".
			 "        ".$ls_cadena." '".$ls_estructura."' ".
             " ORDER BY coduniadm ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
							      MÉTODO->uf_spg_reporte_select_max_coduniadm  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_coduniadm=$row["coduniadm"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_coduniadm
/********************************************************************************************************************************/
    function uf_spi_reporte_select_min_cuenta(&$as_spi_cuenta)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_select_min_cuenta
	 //         Access :	private
	 //     Argumentos :    $as_spi_cuenta  // cuenta minima (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT min(spi_cuenta) as spi_cuenta ".
             " FROM spi_cuentas ".
             " WHERE codemp = '".$this->ls_codemp."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->tepuy_spi_funciones_reportes  
							      MÉTODO->uf_spi_reporte_select_min_cuenta  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_spi_cuenta=$row["spi_cuenta"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_cuenta
/**********************************************************************************************************************************/
    function uf_spi_reporte_select_max_cuenta(&$as_spi_cuenta)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_select_max_cuenta
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta maxima (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT max(spi_cuenta) as spi_cuenta ".
             " FROM spi_cuentas ".
             " WHERE codemp = '".$this->ls_codemp."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->tepuy_spi_funciones_reportes  
							      MÉTODO->uf_spi_reporte_select_max_cuenta  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_spi_cuenta=$row["spi_cuenta"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spi_reporte_select_max_cuenta
/*********************************************************************************************************************************/
    function uf_spg_reportes_select_denominacion($as_spg_cuenta,&$as_denominacion)
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_reportes_select_denominacion
	  //        Argumentos :    
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    28/08/2006                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT denominacion FROM spg_cuentas WHERE codemp='".$this->ls_codemp."' AND spg_cuenta='".$as_spg_cuenta."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
	      $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
							      MÉTODO->uf_spg_reportes_select_denominacion  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		   if($row=$this->io_sql->fetch_row($rs_data))
		   {
			  $as_denominacion=$row["denominacion"];
		   }
		   $this->io_sql->free_result($rs_data);
		}
		return  $lb_valido;
   }//fin uf_spg_reportes_llenar_datastore_cuentas()
/**********************************************************************************************************************************/

	function uf_get_spi_cuenta($as_spi_cuenta,&$as_spi_ramo,&$as_spi_subramo,&$as_spi_especifica,&$as_spi_subesp)
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function : uf_get_spi_cuenta
	  //        Argumentos :  
      //	       Returns : Cuenta separada
	  //	   Description : Envía por referencia una cuenta por ramo, subramo, especifica y sub-específica
	  //        Creado por : Ing. Arnaldo Suárez
	  //    Fecha Creación : 25/05/2008                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_spi_ramo = substr($as_spi_cuenta,0,3);
		$as_spi_subramo = substr($as_spi_cuenta,3,2);
		$as_spi_especifica = substr($as_spi_cuenta,5,2);
		$as_spi_subesp = substr($as_spi_cuenta,7,2);
		
		return  $lb_valido;
   }//fin uf_get_spg_cuenta()
   
   function uf_load_seguridad_reporte($as_sistema,$as_ventanas,$as_descripcion)
   {
		//////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_seguridad_reporte
		//		   Access: public (en todas las clases que usen seguridad)
		//	    Arguments: as_sistema // Sistema del que se desea verificar la seguridad
		//				   as_ventanas // Ventana del que se desea verificar la seguridad
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	  Description: Función que obtiene el valor de una variable que viene de un submit
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 27/04/2006 					Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/tepuy_c_seguridad.php");
		$io_seguridad= new tepuy_c_seguridad();
		$ls_empresa=$_SESSION["la_empresa"]["codemp"];
		$ls_logusr=$_SESSION["la_logusr"];
		$la_seguridad["empresa"]=$ls_empresa;
		$la_seguridad["logusr"]=$ls_logusr;
		$la_seguridad["sistema"]=$as_sistema;
		$la_seguridad["ventanas"]=$as_ventanas;
		$ls_evento="REPORT";
		$lb_valido= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
								$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
								$la_seguridad["ventanas"],$as_descripcion);
		unset($io_seguridad);
		return $lb_valido;
   }// end function uf_load_seguridad
}//fin de la clase
?>