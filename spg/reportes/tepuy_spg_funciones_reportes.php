<?PHP 
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_mensajes.php");


/*********************************************************************************************************************************/	
class tepuy_spg_funciones_reportes
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
    function  tepuy_spg_funciones_reportes()
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

    function uf_spg_reporte_select_denestpro1($as_codestpro1,&$as_denestpro1)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro1
	 //         Access :	private
	 //     Argumentos :    $as_procede_ori  // procede origen
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 1
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    27/04/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT denestpro1 ".
             " FROM   spg_ep1 ".
             " WHERE  codemp='".$this->ls_codemp."' AND codestpro1='".$as_codestpro1."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
		                           MÉTODO->uf_spg_reporte_select_denestpro1  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_denestpro1=$row["denestpro1"];
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro1
/**********************************************************************************************************************************/
    function uf_spg_reporte_select_denestpro2($as_codestpro1,$as_codestpro2,&$as_denestpro2)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro2
	 //         Access :	private
	 //     Argumentos :    $as_codestpro2 // codigo
	 //                     $as_denestpro2  // denominacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 1
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    27/04/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT denestpro2 ".
             " FROM   spg_ep2 ".
             " WHERE  codemp='".$this->ls_codemp."' AND  codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
		                           MÉTODO->uf_spg_reporte_select_denestpro2  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_denestpro2=$row["denestpro2"];
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro1
/**********************************************************************************************************************************/
    function uf_spg_reporte_select_denestpro3($as_codestpro1,$as_codestpro2,$as_codestpro3,&$as_denestpro3)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro3
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3 // codigo
	 //                     $as_denestpro3  // denominacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 1
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    27/04/2006          Fecha última Modificacion : 08/09/2006     Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT denestpro3 ".
             " FROM   spg_ep3 ".
             " WHERE  codemp='".$this->ls_codemp."' AND  codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND ".
			 "        codestpro3='".$as_codestpro3."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
		                           MÉTODO->uf_spg_reporte_select_denestpro3  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_denestpro3=$row["denestpro3"];
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro3
/**********************************************************************************************************************************/
    function uf_spg_reporte_select_denestpro4($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,&$as_denestpro4)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro4
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3 // codigo
	 //                     $as_denestpro3  // denominacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 1
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    11/09/2006          Fecha última Modificacion :            Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT denestpro4 ".
             " FROM   spg_ep4 ".
             " WHERE  codemp='".$this->ls_codemp."' AND  codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND ".
			 "        codestpro3='".$as_codestpro3."' AND codestpro4='".$as_codestpro4."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
		                           MÉTODO->uf_spg_reporte_select_denestpro4  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_denestpro4=$row["denestpro4"];
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro4
/**********************************************************************************************************************************/
    function uf_spg_reporte_select_denestpro5($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
	                                          &$as_denestpro5)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro5
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3 // codigo
	 //                     $as_denestpro3  // denominacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 1
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    11/09/2006          Fecha última Modificacion :            Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT denestpro5 ".
             " FROM   spg_ep5 ".
             " WHERE  codemp='".$this->ls_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND ".
			 "        codestpro3='".$as_codestpro3."' AND codestpro4='".$as_codestpro4."' AND codestpro5='".$as_codestpro5."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
		                           MÉTODO->uf_spg_reporte_select_denestpro5  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_denestpro5=$row["denestpro5"];
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro5
/**********************************************************************************************************************************/
    function uf_spg_reporte_select_min_programatica(&$as_codestpro1,&$as_codestpro2,&$as_codestpro3,&$as_codestpro4,&$as_codestpro5)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_programatica
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1..as_codestpro5  // estructura presupuestaria (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 
	 if(($as_codestpro1=='') || ($as_codestpro1=='**'))
	 { 
	 $lb_valido=$this->uf_spg_reporte_select_min_codestpro1($as_codestpro1);
	 }
	 if(($as_codestpro2=='') || ($as_codestpro2=='**'))
	 {
	 $lb_valido=$this->uf_spg_reporte_select_min_codestpro2($as_codestpro1,$as_codestpro2);
	 }
	 if(($as_codestpro3=='') || ($as_codestpro3=='**'))
	 {
	 $lb_valido=$this->uf_spg_reporte_select_min_codestpro3($as_codestpro1,$as_codestpro2,$as_codestpro3);
	 }
	  if(($as_codestpro4=='') || ($as_codestpro4=='**'))
	 {
	 $lb_valido=$this->uf_spg_reporte_select_min_codestpro4($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4);
	 }
	 if(($as_codestpro5=='') || ($as_codestpro5=='**'))
	 {
	 $lb_valido=$this->uf_spg_reporte_select_min_codestpro5($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5);
	 }
	
	return $lb_valido;
  }//uf_spg_reporte_select_min_programatica
  
  
/**********************************************************************************************************************************/
 function uf_spg_reporte_select_min_codestpro1(&$as_codestpro1)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro1
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql="SELECT * FROM spg_ep1 WHERE codemp='".$this->ls_codemp."' ORDER BY codestpro1  asc  limit 1 ";
	
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
		                           MÉTODO->uf_spg_reporte_select_min_codestpro1  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro1
/**********************************************************************************************************************************/
function uf_spg_reporte_select_min_codestpro2($as_codestpro1,&$as_codestpro2)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro2
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 1 (referencia)          
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro2) as codestpro2 ".
             " FROM   spg_ep2 ".
             " WHERE  codemp = '".$this->ls_codemp."' AND codestpro1='".$as_codestpro1."' ";
	
	 
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
		                           MÉTODO->uf_spg_reporte_select_min_codestpro2  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro2=$row["codestpro2"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro2
/*******************************************************************/
 function uf_spg_reporte_select_min_codestpro3($as_codestpro1,$as_codestpro2,&$as_codestpro3)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro3
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3  // codigo de estructura programatica 3 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro3) as codestpro3 ".
             " FROM   spg_ep3 ".
             " WHERE  codemp = '".$this->ls_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."'  ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
							      MÉTODO->uf_spg_reporte_select_min_codestpro3  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro3=$row["codestpro3"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro3
/**********************************************************************************************************************************/
    function uf_spg_reporte_select_min_codestpro4($as_codestpro1,$as_codestpro2,$as_codestpro3,&$as_codestpro4)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro4
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2 
	 //                     $as_codestpro3  // codigo de estructura programatica 3 
	 //                     $as_codestpro4  // codigo de estructura programatica 4  (referencia)         
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro4) as codestpro4 ".
             " FROM   spg_ep4 ".
             " WHERE  codemp = '".$this->ls_codemp."' AND codestpro1='".$as_codestpro1."' AND ".
			 "        codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
							      MÉTODO->uf_spg_reporte_select_min_codestpro4  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro4=$row["codestpro4"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro4
/**********************************************************************************************************************************/
    function uf_spg_reporte_select_min_codestpro5($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,&$as_codestpro5)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro5
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2 
	 //                     $as_codestpro3  // codigo de estructura programatica 3 
	 //                     $as_codestpro4  // codigo de estructura programatica 4  
	 //                     $as_codestpro4  // codigo de estructura programatica 5  (referencia) 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro5) as codestpro5 ".
             " FROM   spg_ep5 ".
             " WHERE  codemp = '".$this->ls_codemp."' AND codestpro1='".$as_codestpro1."' AND ".
			 "        codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND  ".
			 "        codestpro4='".$as_codestpro4."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
							      MÉTODO->uf_spg_reporte_select_min_codestpro5  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro5=$row["codestpro5"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro3

/**********************************************************************************************************************************/
    function uf_spg_reporte_select_max_programatica(&$as_codestpro1,&$as_codestpro2,&$as_codestpro3,&$as_codestpro4,&$as_codestpro5)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_programatica
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1..as_codestpro5  // codigo de la estructura (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if(($as_codestpro1=='') || ($as_codestpro1=='**'))
	 { 
	 $lb_valido=$this->uf_spg_reporte_select_max_codestpro1($as_codestpro1);
	 }
	 if(($as_codestpro2=='') || ($as_codestpro2=='**'))
	 { 
		 $lb_valido=$this->uf_spg_reporte_select_max_codestpro2($as_codestpro1,$as_codestpro2);
	 }
	 if(($as_codestpro3=='') || ($as_codestpro3=='**'))
	 { 
		 $lb_valido=$this->uf_spg_reporte_select_max_codestpro3($as_codestpro1,$as_codestpro2,$as_codestpro3);
	 }
	 if(($as_codestpro4=='') || ($as_codestpro4=='**'))
	 { 
		 $lb_valido=$this->uf_spg_reporte_select_max_codestpro4($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4);
	 }
	 if(($as_codestpro5=='') || ($as_codestpro5=='**'))
	 { 
		 $lb_valido=$this->uf_spg_reporte_select_max_codestpro5($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5);
	 }
	return $lb_valido;
  }//uf_spg_reporte_select_max_programatica
/**********************************************************************************************************************************/
    function uf_spg_reporte_select_max_codestpro1(&$as_codestpro1)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro1
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql="SELECT * FROM spg_ep1 WHERE codemp='".$this->ls_codemp."' ORDER BY codestpro1  desc limit 1 ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
		                           MÉTODO->uf_spg_reporte_select_max_codestpro1  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro1
/**********************************************************************************************************************************/
    function uf_spg_reporte_select_max_codestpro2($as_codestpro1,&$as_codestpro2)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro2
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 1 (referencia)          
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT max(codestpro2) as codestpro2 ".
             " FROM   spg_ep2 ".
             " WHERE  codemp = '".$this->ls_codemp."' AND codestpro1='".$as_codestpro1."' ";
			// print($ls_sql.'<br>');
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		   $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
		                           MÉTODO->uf_spg_reporte_select_max_codestpro2  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro2=$row["codestpro2"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro2
/**********************************************************************************************************************************/
    function uf_spg_reporte_select_max_codestpro3($as_codestpro1,$as_codestpro2,&$as_codestpro3)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro3
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3  // codigo de estructura programatica 3 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT max(codestpro3) as codestpro3 ".
             " FROM   spg_ep3 ".
             " WHERE  codemp = '".$this->ls_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."'  ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
							      MÉTODO->uf_spg_reporte_select_max_codestpro3  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro3=$row["codestpro3"];
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
    function uf_spg_reporte_select_max_codestpro4($as_codestpro1,$as_codestpro2,$as_codestpro3,&$as_codestpro4)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro4
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2 
	 //                     $as_codestpro3  // codigo de estructura programatica 3 
	 //                     $as_codestpro4  // codigo de estructura programatica 4  (referencia)         
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT max(codestpro4) as codestpro4 ".
             " FROM   spg_ep4 ".
             " WHERE  codemp = '".$this->ls_codemp."' AND codestpro1='".$as_codestpro1."' AND ".
			 "        codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' ";

	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
							      MÉTODO->uf_spg_reporte_select_max_codestpro4  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro4=$row["codestpro4"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro4
/**********************************************************************************************************************************/
    function uf_spg_reporte_select_max_codestpro5($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,&$as_codestpro5)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro5
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2 
	 //                     $as_codestpro3  // codigo de estructura programatica 3 
	 //                     $as_codestpro4  // codigo de estructura programatica 4  
	 //                     $as_codestpro4  // codigo de estructura programatica 5  (referencia) 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT max(codestpro5) as codestpro5 ".
             " FROM   spg_ep5 ".
             " WHERE  codemp = '".$this->ls_codemp."' AND codestpro1='".$as_codestpro1."' AND ".
			 "        codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND  ".
			 "        codestpro4='".$as_codestpro4."' ";

	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
							      MÉTODO->uf_spg_reporte_select_max_codestpro5  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      $lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro5=$row["codestpro5"];
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
    function uf_spg_min_coduniadm_sinprogramatica(&$as_coduniadm)
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
	 $ls_sql=" SELECT min(coduniadm) as coduniadm ".
			 " FROM   spg_unidadadministrativa ".
             " WHERE  codemp='".$this->ls_codemp."'  ".
             " ORDER BY coduniadm ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
							      MÉTODO->uf_spg_min_coduniadm_sinprogramatica  
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
  }//uf_spg_min_coduniadm_sinprogramatica
/**********************************************************************************************************************************/
    function uf_spg_max_coduniadm_sinprogramatica(&$as_coduniadm)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_coduniadm
	 //         Access :	private
	 //     Argumentos :    $coduniadm     //  codigo de la unidad administrativas
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spg_unidadadministrativa
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :08/09/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_gestor = $_SESSION["ls_gestor"];	 
	 $ls_sql=" SELECT max(coduniadm) as coduniadm ".
			 " FROM   spg_unidadadministrativa ".
			 " WHERE  codemp='".$this->ls_codemp."'  ".
             " ORDER BY coduniadm ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
							      MÉTODO->uf_spg_max_coduniadm _sinprogramatica
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
  }//uf_spg_max_coduniadm_sinprogramatica
/********************************************************************************************************************************/
	
	
	
	function uf_spg_reporte_select_min_cuenta(&$as_spg_cuenta)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_cuenta
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta minima (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT min(spg_cuenta) as spg_cuenta ".
             " FROM spg_cuentas ".
             " WHERE codemp = '".$this->ls_codemp."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
							      MÉTODO->uf_spg_reporte_select_min_cuenta  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_spg_cuenta=$row["spg_cuenta"];
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
    function uf_spg_reporte_select_max_cuenta(&$as_spg_cuenta)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_cuenta
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta maxima (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql=" SELECT max(spg_cuenta) as spg_cuenta ".
             " FROM spg_cuentas ".
             " WHERE codemp = '".$this->ls_codemp."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	      $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
							      MÉTODO->uf_spg_reporte_select_max_cuenta  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_spg_cuenta=$row["spg_cuenta"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_cuenta
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
/****************************************************************************************************************************************/	
function uf_nombre_mes_desde_hasta($ai_mesdes,$ai_meshas)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function: 	  uf_load_nombre_mes
	//	Description:  Funcion que se encarga de obtener el numero de un mes a partir de su nombre.
	//	Arguments:	  - $ls_mes: Mes de la fecha a obtener el ultimo dia.	
	//				  - $ls_ano: Año de la fecha a obtener el ultimo dia.
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $ls_nombre_mesdes=$this->io_fecha->uf_load_nombre_mes($ai_mesdes);
    $ls_nombre_meshas=$this->io_fecha->uf_load_nombre_mes($ai_meshas);
	$ls_nombremes=$ls_nombre_mesdes."-".$ls_nombre_meshas;
  return $ls_nombremes;
 }
/****************************************************************************************************************************************/	
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
/*********************************************************************************************************************************/
    function uf_spg_select_provee_benef($as_cod_pro,$as_ced_bene,&$as_nompro,&$as_nombene)
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_select_provee_benef
	  //        Argumentos :    
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    17/10/2006                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_gestor = $_SESSION["ls_gestor"];
		if(strtoupper($ls_gestor)=="MYSQL")
		{
			$ls_cadena="CONCAT( rtrim(XBF.apebene),', ',XBF.nombene)";
		}
		else
		{
			$ls_cadena="rtrim(XBF.apebene)||', '||XBF.nombene";
		}
		$ls_sql = " SELECT PRV.nompro as nompro,  ".$ls_cadena."  as nombene ".
                  " FROM   tepuy_cmp CMP, rpc_beneficiario BEN, rpc_proveedor PRV ".
                  " WHERE  CMP.cod_pro=PRV.cod_pro AND CMP.ced_bene=BEN.ced_bene AND CMP.cod_pro='".$as_cod_pro."' AND ".
                  "        CMP.ced_bene='".$as_nompro."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
	      $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
							      MÉTODO->uf_spg_select_provee_benef  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		   if($row=$this->io_sql->fetch_row($rs_data))
		   {
			  $as_nompro=$row["nompro"];
			  $as_nombene=$row["nombene"];
		   }
		   $this->io_sql->free_result($rs_data);
		}
		return  $lb_valido;
   }//fin uf_spg_reportes_llenar_datastore_cuentas()
/****************************************************************************************************************************************/	
 function uf_spg_select_fuentefinanciamiento(&$as_minfuefin,&$as_maxfuefin)
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function : uf_spg_select_fuentefinanciamiento
	  //        Argumentos :  
      //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	  //	   Description : Envia por referencia el minimo y el maximo de las fuente financiamineto
	  //        Creado por : Ing. Yozelin Barragán.
	  //    Fecha Creación : 31/10/2007                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_sql = " SELECT min(codfuefin) as minfuefin,      ".
		          "        max(codfuefin) as maxfuefin       ". 
                  " FROM   tepuy_fuentefinanciamiento       ".
                  " WHERE  codemp='".$this->ls_codemp."'     ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
	      $this->io_msg->message("CLASE->tepuy_spg_funciones_reportes  
							      MÉTODO->uf_spg_select_fuentefinanciamiento  
							      ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		   if($row=$this->io_sql->fetch_row($rs_data))
		   {
			  $as_minfuefin=$row["minfuefin"];
			  $as_maxfuefin=$row["maxfuefin"];
		   }
		   $this->io_sql->free_result($rs_data);
		}
		return  $lb_valido;
   }//fin uf_spg_select_fuentefinanciamiento()

	function uf_filtro_seguridad_programatica($as_tabla,&$as_filtro)
	{
	 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	     Function: uf_filtro_seguridad_programatica
	 //		   Access: public
	 //	    Arguments: $as_codemp      -- Codigo de la Empresa
	 //                $as_tabla       -- Nombre de la Tabla utilizada en la consulta para buscar las programaticas
	 //	      Returns: $as_filtro -- String que contiene la sentencia que filtra las programaticas de las consultas.
	 //	  Descripcion: Función que retorna el filtro de programaticas dado los permisos del usuario logueado 
	 //	   Creado Por: Ing. Arnaldo Suárez
	 // Fecha Creación: 22/02/2008 								Fecha Última Modificación : 
	 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $ls_gestor    = $_SESSION["ls_gestor"];
		$li_estmodest = $_SESSION["la_empresa"]["estmodest"];
		$ls_usuario   = $_SESSION["la_logusr"];
		$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
		 if (strtoupper($ls_gestor) == "MYSQL")
		 {
		  $as_filtro = " AND CONCAT('".$ls_codemp."','SPG','".$ls_usuario."',".$as_tabla.".codestpro1,".$as_tabla.".codestpro2,".$as_tabla.".codestpro3,".$as_tabla.".codestpro4,".$as_tabla.".codestpro5) IN (SELECT distinct CONCAT(codemp,codsis,codusu,codintper) 
		                       FROM sss_permisos_internos WHERE codemp = '".$ls_codemp."' AND codusu = '".$ls_usuario."' AND codsis = 'SPG')";
		 }
		 else
		 {
		  $as_filtro = " AND '".$ls_codemp."'||'SPG'||'".$ls_usuario."'||".$as_tabla.".codestpro1||".$as_tabla.".codestpro2||".$as_tabla.".codestpro3||".$as_tabla.".codestpro4||".$as_tabla.".codestpro5 IN (SELECT distinct codemp||codsis||codusu||codintper
		                       FROM sss_permisos_internos WHERE codemp = '".$ls_codemp."' AND codusu = '".$ls_usuario."' AND codsis = 'SPG')";
		 }
		//}
	return $as_filtro;	 
	}
	
	function uf_get_spg_cuenta($as_spg_cuenta,&$as_spg_partida,&$as_spg_generica,&$as_spg_especifica,&$as_spg_subesp)
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function : uf_get_spg_cuenta
	  //        Argumentos :  
      //	       Returns : Cuenta separada
	  //	   Description : Envía por referencia una cuenta por partida, generica, especifica y sub-específica
	  //        Creado por : Ing. Arnaldo Suárez
	  //    Fecha Creación : 25/05/2008                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_spg_partida = substr($as_spg_cuenta,0,3);
		$as_spg_generica = substr($as_spg_cuenta,3,2);
		$as_spg_especifica = substr($as_spg_cuenta,5,2);
		$as_spg_subesp = substr($as_spg_cuenta,7,2);
		
		return  $lb_valido;
   }//fin uf_get_spg_cuenta()	
}//fin de la clase
?>