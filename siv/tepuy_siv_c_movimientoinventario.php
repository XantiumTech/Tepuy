<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/tepuy_c_seguridad.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
require_once("../shared/class_folder/tepuy_c_generar_consecutivo.php");
//$io_key= new tepuy_c_generar_consecutivo();

class tepuy_siv_c_movimientoinventario
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function tepuy_siv_c_movimientoinventario()
	{
		$in                 = new tepuy_include();
		$this->con          = $in->uf_conectar();
		$this->io_sql       = new class_sql($this->con);
		$this->seguridad    = new tepuy_c_seguridad();
		$this->fun          = new class_funciones_db($this->con);
		$this->DS           = new class_datastore();
		$this->io_msg       = new class_mensajes();
		$this->io_funcion   = new class_funciones();
		$this->io_rcbsf     = new tepuy_c_reconvertir_monedabsf();
		$this->li_candeccon = $_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon = $_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon = $_SESSION["la_empresa"]["redconmon"];
		$this->ls_codemp= $_SESSION["la_empresa"]["codemp"];
        $this->io_key= new tepuy_c_generar_consecutivo();

	}
	
	function uf_siv_select_movimiento($as_nummov,$as_fecmov)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_movimiento
		//         Access: public 
		//      Argumento: $as_nummov    // numero de movimiento
		//                 $as_fecmov    // fecha de movimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe un componente en la tabla de  siv_movimiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM siv_movimiento  ".
				  " WHERE nummov='".$as_nummov."'".
				  "   AND fecmov='".$as_fecmov."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_siv_select_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function uf_siv_select_movimiento

	function uf_siv_insert_movimiento(&$as_nummov,$ad_fecmov,$as_nomsol,$as_codusu,$aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_movimiento
		//         Access: public 
		//      Argumento: $as_nummov    // numero de movimiento
		//                 $as_fecmov    // fecha de movimiento
		//                 $as_nomsol    // nombre del solicitante
		//                 $as_codusu    // codigo del usuario
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un maestro de movimiento en la tabla de  siv_movimiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_nummov=$this->io_key->uf_generar_numero_nuevo("SIV","siv_movimiento","nummov","SIV",15,"","","");
		$ls_sql="INSERT INTO siv_movimiento ( nummov, fecmov, nomsol, codusu)".
				" VALUES ('".$as_nummov."','".$ad_fecmov."','".$as_nomsol."','".$as_codusu."')";
		//print $ls_sql;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_siv_insert_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	/*			$ls_evento="INSERT";
				$ls_descripcion ="Insert� el Movimiento ".$as_nummov." de Fecha ".$ad_fecmov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);*/
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function uf_siv_insert_movimiento
	 
	function uf_siv_select_dt_movimiento($as_codemp,$as_nummov,$ad_fecmov,$as_codart,$as_codalm,$as_opeinv,$as_codprodoc,$as_numdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_movimiento
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_nummov    // numero de movimiento
		//                 $ad_fecmov    // fecha de movimiento
		//                 $as_codart    // codigo de articulo
		//                 $as_codalm    // codigo de almacen
		//                 $as_opeinv    // codigo de operacion de inventario
		//                 $as_codprodoc // codigo de procedencia del documento
		//                 $as_numdoc    // numero de documento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica los detalles asociados a un  movimientos  en la tabla de  siv_dt_movimiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * FROM siv_dt_movimiento".
				" WHERE codemp='". $as_codemp ."'".
				"   AND nummov='". $as_nummov ."'".
				"   AND fecmov='". $ad_fecmov ."'".
				"   AND codart='". $as_codart ."'".
				"   AND codalm='". $as_codalm ."'".
				"   AND opeinv='". $as_opeinv ."'".
				"   AND codprodoc='". $as_codprodoc ."'".
				"   AND numdoc='". $as_numdoc ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_siv_select_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end  function uf_siv_select_dt_movimiento
	
	function uf_siv_insert_dt_movimiento($as_codemp,$as_nummov,$ad_fecmov,$as_codart,$as_codalm,$as_opeinv,$as_codprodoc,$as_numdoc,
										 $ai_canart,$ai_cosart,$as_promov,$as_numdocori,$ai_candesart,$ad_fecdesart,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_movimiento
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa					$ai_canart    // cantidad de articulos
		//                 $as_nummov    // numero de movimiento				$ai_cosart    // costo del articulo
		//                 $ad_fecmov    // fecha de movimiento					$as_promov    // procedencia del documento
		//                 $as_codart    // codigo de articulo					$as_numdocori // numero de documento original
		//                 $as_codalm    // codigo de almacen					$as_numdoc    // numero de documento
		//                 $as_opeinv    // codigo de operacion de inventario	$ad_fecdesart // fecha de el ultimo despacho del articulo
		//                 $as_codprodoc // codigo de procedencia del documento	$aa_seguridad // arreglo de registro de seguridad	
		//                 $ai_candesart // cantidad de articulos que restan por despachar
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un detalle de movimiento generado en cualquiera de los procesos de inventario,
		//				   en la tabla de  siv_dt_movimiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO siv_dt_movimiento (codemp,nummov,fecmov,codart,codalm,opeinv,codprodoc,numdoc,canart,cosart,promov,".
				"                               numdocori,candesart,fecdesart)".
				" VALUES ('".$as_codemp."','".$as_nummov."','".$ad_fecmov."','".$as_codart."','".$as_codalm."','".$as_opeinv."',".
				"         '".$as_codprodoc."','".$as_numdoc."','".$ai_canart."','".$ai_cosart."','".$as_promov."','".$as_numdocori."',".
				"         '".$ai_candesart."','".$ad_fecdesart."')";
		//print $ls_sql;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_siv_insert_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			/*$this->io_rcbsf->io_ds_datos->insertRow("campo","cosartaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ai_cosart);

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","nummov");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_nummov);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecmov");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ad_fecmov);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codart);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codalm");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codalm);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","opeinv");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_opeinv);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codprodoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codprodoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("siv_dt_movimiento",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);*/
			$lb_valido=true;
		}
		return $lb_valido;
	} // end function uf_siv_insert_dt_movimiento

    function uf_generar_codigo($ab_empresa,$as_codemp,$as_tabla,$as_columna)
	{ 
		//////////////////////////////////////////////////////////////////////////////////////////
		//	Function :  uf_generar_codigo
		//	  Access :  public
		//	Arguments:
		//           ab_empresa   // Si usara el campo empresa como filtro      
		//           as_codemp    // codigo de la empresa
		//           as_tabla     // Nombre de la tabla 
		//           as_campo     // nombre del campo que desea incrementar
		//           ai_length    // longitud del campo
		//	  Returns:	ls_codigo   // representa el codigo incrementado o generado
		//	Description:  Este m�todo genera el numero consecutivo del c�digo de
		//                cualquier tabla deseada
		///////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=$this->fun->uf_select_table($as_tabla);
		if ($lb_existe)
		   {
			  $lb_existe=$this->fun->uf_select_column($as_tabla,$as_columna);
			  if ($lb_existe)
			  {
				   $li_longitud=$this->fun->uf_longitud_columna_char($as_tabla,$as_columna) ;
				   if ($ab_empresa)
				   { 
						  $ls_sql="SELECT ".$as_columna." FROM ".$as_tabla." WHERE codemp='".$as_codemp."' ORDER BY ".$as_columna." DESC";		
						  $rs_funciondb=$this->io_sql->select($ls_sql);
						  if ($row=$this->io_sql->fetch_row($rs_funciondb))
						  { 
							  $codigo=$row[$as_columna];
							  settype($codigo,'int');                             // Asigna el tipo a la variable.
							  $codigo = $codigo + 1;                              // Le sumo uno al entero.
							  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
							  $ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud);
						  }
						  else
						  {
							  $codigo="1";
							  $ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud);
						  }
					}	
					else
					{  
						  $ls_sql="SELECT ".$as_columna." FROM ".$as_tabla." WHERE ".$as_columna." <>'0000000APERTURA'  ORDER BY ".$as_columna." DESC";		
						  $rs_funciondb=$this->io_sql->select($ls_sql);
						  if ($row=$this->io_sql->fetch_row($rs_funciondb))
						  { 
							   $codigo=$row[$as_columna];
							   settype($codigo,'int');                                          // Asigna el tipo a la variable.
							   $codigo = $codigo + 1;                                           // Le sumo uno al entero.
							   settype($codigo,'string');                                       // Lo convierto a varchar nuevamente.
							   $ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud); 
						   }   
						   else
						   {
							   $codigo="1";
							   $ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud);
						   }
					}// SI NO TIENE CODIGO DE EMPRESA
				}
				else
				{
					$ls_codigo="";
					$this->is_msg_error="No existe el campo" ;
				}
		 }
		 else
		{
			$ls_codigo="";
			$this->is_msg_error="No existe la tabla	" ;
		}
	    return $ls_codigo; //print "c�digo".$ls_codigo;
	 } // end function

} // end class tepuy_siv_c_movimientoinventario
?>
