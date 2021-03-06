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

class tepuy_sfa_c_movimientoinventario
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function tepuy_sfa_c_movimientoinventario()
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
	
	function uf_sfa_select_movimiento($as_codemp,$as_nummov,$as_fecmov,&$ao_objexiste,&$li_totexipro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_select_movimiento
		//         Access: public 
		//      Argumento: $as_nummov    // numero de movimiento
		//                 $as_fecmov    // fecha de movimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe un componente en la tabla de  siv_movimiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sfa_movimiento  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND nummov='".$as_nummov."'".
				  " AND fecmov='".$as_fecmov."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_sfa_select_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->uf_sfa_cargar_exipro_dt_movimiento($as_codemp,$as_nummov,$as_fecmov,&$ao_objexiste,&$li_totexipro);
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function uf_sfa_select_movimiento


	function uf_sfa_cargar_exipro_dt_movimiento($as_codemp,$as_nummov,$ad_fecmov,&$lo_objexiste,&$li_totexipro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_cargar_exipro_dt_movimiento
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_nummov    // numero de movimiento
		//                 $ad_fecmov    // fecha de movimiento
		//                 $as_codpro    // codigo del producto
		//                 $as_canproqueexistia    // numero de productos que existian antes de este ingreso
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica los detalles asociados a un  movimientos  en la tabla de  siv_dt_movimiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * FROM sfa_dt_movimiento".
				" WHERE codemp='". $as_codemp ."'".
				"   AND nummov='". $as_nummov ."'".
				"   AND fecmov='". $ad_fecmov ."'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_sfa_cargar_exipro_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$li_totexipro=1;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$exipro=$row["exipro"];		
				$lo_objexiste[$li_totexipro][1]=$exipro;
				//print "valor k".$li_totexipro." ".$lo_objexiste[$li_totexipro][1];
				$li_totexipro=($li_totexipro+1);
			}
			$this->io_sql->free_result($rs_data);
		}
		//die();
		return $lb_valido;
	} // end  function uf_sfa_cargar_exipro_dt_movimiento


	function uf_sfa_insert_movimiento($as_codemp,&$as_nummov,$as_numcontrol,$as_peraut,$ad_fecmov,$as_obsmov,$as_totentsum,$as_codusu,$aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_insert_movimiento
		//         Access: public 
		//      Argumento: $as_nummov    // numero de movimiento
		//      	   $as_numcontrol// numero de control interno
		//      	   $as_peraux    // cedula de la persona que autoriza el movimiento
		//                 $as_fecmov    // fecha de movimiento
		//                 $as_obmov     // Observaciones en el movimiento
		//      	   $as_totentsum // Monto total del movimiento
		//                 $as_codusu    // codigo del usuario
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un maestro de movimiento en la tabla de  siv_movimiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 17/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sfa_movimiento (codemp, nummov, numconint, fecmov, cedaut, obsmov, totalmov, usuario)".
				" VALUES ('".$as_codemp."', '".$as_nummov."','".$as_numcontrol."', '".$ad_fecmov."', '".
				$as_peraut."', '".$as_obsmov."', ".$as_totentsum.", '".$as_codusu."')";
		//print $ls_sql;
		//die();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_sfa_insert_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insert� el Movimiento de Inventario de Productos N.".$as_nummov." de Fecha ".$ad_fecmov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function uf_sfa_insert_movimiento

	function uf_sfa_update_movimiento($as_codemp,&$as_nummov,$as_numcontrol,$as_peraut,$ad_fecmov,$as_obsmov,$as_totentsum,$as_codusu,$aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_insert_movimiento
		//         Access: public 
		//      Argumento: $as_nummov    // numero de movimiento
		//      	   $as_numcontrol// numero de control interno
		//      	   $as_peraux    // cedula de la persona que autoriza el movimiento
		//                 $as_fecmov    // fecha de movimiento
		//                 $as_obmov     // Observaciones en el movimiento
		//      	   $as_totentsum // Monto total del movimiento
		//                 $as_codusu    // codigo del usuario
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un maestro de movimiento en la tabla de  siv_movimiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 17/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql =	"UPDATE sfa_movimiento SET numconint='".$as_numcontrol."', ".
				" cedaut='".$as_peraut."', ".
				" obsmov='".$as_obsmov."', ".
				" totalmov='".$as_totentsum."', ".
				" usuario='".$as_codusu."' ".
				" WHERE codemp='".$as_codemp."'".
				" AND nummov='".$as_nummov."'".
				" AND fecmov='".$ad_fecmov."'";
		//print $ls_sql;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_sfa_update_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualiz� el Movimiento de Inventario de Productos N.".$as_nummov." de Fecha ".$ad_fecmov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function uf_sfa_update_movimiento
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 
	function uf_sfa_select_dt_movimiento($as_codemp,$as_nummov,$ad_fecmov,$as_codpro,&$canproqueexistia)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_insert_movimiento
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_nummov    // numero de movimiento
		//                 $ad_fecmov    // fecha de movimiento
		//                 $as_codpro    // codigo del producto
		//                 $as_canproqueexistia    // numero de productos que existian antes de este ingreso
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica los detalles asociados a un  movimientos  en la tabla de  siv_dt_movimiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * FROM sfa_dt_movimiento".
				" WHERE codemp='". $as_codemp ."'".
				"   AND nummov='". $as_nummov ."'".
				"   AND fecmov='". $ad_fecmov ."'".
				"   AND codpro='". $as_codpro ."'";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_sfa_select_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$canproqueexistia=$row["exipro"];
				
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end  function uf_sfa_select_dt_movimiento
	

	function uf_sfa_update_dt_movimiento($as_codemp,$as_nummov,$ad_fecmov,$as_codpro,$ai_canoripro,$ai_canpro,$as_codusu,$aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_insert_movimiento
		//         Access: public 
		//      Argumento: $as_nummov    // numero de movimiento
		//                 $ad_fecmov    // fecha de movimiento
		//                 $as_codpro    // Codigo del producto
		//      	   $ai_canoripro // Cantidad de productos que existen antes de este ingreso
		//      	   $ai_canpro    // Cantidad de productos a ingresar
		//                 $as_codusu    // codigo del usuario
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un maestro de movimiento en la tabla de  siv_movimiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 17/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql =	"UPDATE sfa_dt_movimiento SET exipro=".$ai_canoripro.", cantpro=".$ai_canpro." ".
				" WHERE codemp='".$as_codemp."' ".
				" AND codpro='".$as_codpro."' ".
				" AND nummov='".$as_nummov."' ".
				" AND fecmov='".$ad_fecmov."' ";
		//print $ls_sql;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_sfa_update_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualiz� el Movimiento de Inventario de Productos N.".$as_nummov." del Producto ".$as_codpro." en Fecha ".$ad_fecmov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function uf_sfa_update_dt_movimiento


	function uf_sfa_insert_dt_movimiento($as_codemp,$as_nummov,$ad_fecmov,$as_codpro,$ai_canoripro,$ai_canpro,$ai_preunipro,$as_codusu,$orden,$la_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_insert_movimiento
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//		   $ai_canart    // cantidad de articulos
		//                 $as_numinginv // numero de movimiento
		//                 $ad_fecmov    // fecha de movimiento	
		//                 $as_codpro    // codigo de articulo
		//                 $ai_canoripro // cantidad de productos que existian antes de este ingreso
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un detalle de movimiento generado al momento de actualizar inventario de productos,
		//		   en la tabla de  sfa_dt_movimiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sfa_dt_movimiento (codemp,nummov,fecmov,codpro,exipro,cantpro,preunipro,orden,usuario)".
		" VALUES ('".$as_codemp."', '".$as_nummov."', '".$ad_fecmov."', '".$as_codpro."', ".$ai_canoripro.", ".
		$ai_canpro.", ".$ai_preunipro.", '".$orden."', '".$as_codusu."')";
	//print $ls_sql;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_sfa_insert_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	} // end function uf_sfa_insert_dt_movimiento

	function uf_sfa_delete_dt_movimiento($as_codemp,$as_nummov,$ad_fecmov,$as_codpro,$ai_canoripro,$ai_canpro,$as_codusu,$aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_insert_movimiento
		//         Access: public 
		//      Argumento: $as_nummov    // numero de movimiento
		//                 $ad_fecmov    // fecha de movimiento
		//                 $as_codpro    // Codigo del producto
		//      	   $ai_canoripro // Cantidad de productos que existen antes de este ingreso
		//      	   $ai_canpro    // Cantidad de productos a ingresar
		//                 $as_codusu    // codigo del usuario
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un maestro de movimiento en la tabla de  siv_movimiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 17/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql =	"DELETE FROM sfa_dt_movimiento ".
				" WHERE codemp='".$as_codemp."' ".
				" AND nummov='".$as_nummov."' ".
				" AND fecmov='".$ad_fecmov."' ";
		//print $ls_sql;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_sfa_update_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			/*	$ls_evento="DELETE";
				$ls_descripcion ="Actualiz� el Movimiento de Inventario de Productos N.".$as_nummov." del Producto ".$as_codpro." en Fecha ".$ad_fecmov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);*/
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function uf_sfa_delete_dt_movimiento

///////////////////////////////////////////////////////////////////////////////
	function uf_sfa_obtener_dt_movimiento($as_codemp,$as_nummov,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_obtener_dt_factura
		//         Access: public (tepuy_sfa_d_factura)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			$as_numfactura // numero de factura
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que busca los productos asociados a la factura en la tabla sfa_dt_factura para luego 
		//                 imprimirlos en el grid de la pagina exepto que ya se recibieron por completo.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "SELECT sfa_dt_movimiento.*, siv_unidadmedida.denunimed, sfa_producto.preproa, ".
				  "      (SELECT codunimed FROM sfa_producto ".
				  "	       WHERE sfa_dt_movimiento.codpro = sfa_producto.codpro) AS codunimed,".
				  "      (SELECT MAX(denpro) FROM sfa_producto".
				  "        WHERE sfa_dt_movimiento.codpro=sfa_producto.codpro) AS denpro".
				  "  FROM sfa_dt_movimiento, sfa_movimiento, sfa_producto, siv_unidadmedida ".
				  " WHERE sfa_dt_movimiento.codemp=sfa_movimiento.codemp".
				  "   AND sfa_dt_movimiento.codpro=sfa_producto.codpro".
				  "   AND sfa_producto.codunimed=siv_unidadmedida.codunimed".
				  "   AND sfa_dt_movimiento.nummov=sfa_movimiento.nummov".
				  "   AND sfa_dt_movimiento.codemp='".$as_codemp."'".
				  "   AND sfa_dt_movimiento.codemp=sfa_movimiento.codemp ".
				  "   AND sfa_dt_movimiento.nummov='".$as_nummov."'".
				  " ORDER BY sfa_dt_movimiento.orden ASC";
		//print $ls_sql; die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->FACTURA M�TODO->uf_sfa_obtener_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codart=	$row["codpro"];
				$as_denart=	$row["denpro"];
				$as_unidad=	$row["denunimed"];
				$ai_preuniart=	$row["preunipro"];
				$ai_canoriart=	$row["exipro"];
				$ai_canart=	$row["cantpro"];
				$ai_costoa=	$row["preproa"];
				$ai_totart=	($ai_preuniart*$ai_canart);
				//$ai_totsuminv=	($ai_totsuminv+$ai_totart);
				
				$ai_totart=	number_format($ai_totart,2,",",".");
				$ai_canart=	number_format($ai_canart,2,",",".");
				$ai_preuniart=	number_format($ai_preuniart,2,",",".");
				$ai_canoriart=	number_format($ai_canoriart,2,",",".");
				$ai_totrows=$ai_totrows+1;

				$ao_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."    type=text id=txtdenart".$ai_totrows."    class=sin-borde size=20 maxlength=50 value='".$as_denart."' readonly><input name=txtcodart".$ai_totrows."    type=hidden id=txtcodart".$ai_totrows."    class=sin-borde size=20 maxlength=20 value='".$as_codart."' onKeyUp='javascript: ue_validarnumerosinpunto(this);' readonly><a href='javascript: ue_catproducto(".$ai_totrows.");'><img src='../shared/imagebank/tools15/buscar.png' alt='Codigo del Producto' width='18' height='18' border='0'></a>";
				$ao_object[$ai_totrows][2]="<div align='center'></div><input name=txtunimed".$ai_totrows."    type=text id=txtunimed".$ai_totrows."    class=sin-borde size=12 maxlength=12 value='".$as_unidad."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtcanoriart".$ai_totrows." type=text id=txtcanoriart".$ai_totrows." class=sin-borde size=12 maxlength=12 value='".$ai_canoriart."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtcanart".$ai_totrows."    type=text id=txtcanart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".$ai_canart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
				$ao_object[$ai_totrows][5]="<input name=txtpreuniart".$ai_totrows." type=text id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".$ai_preuniart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
				$ao_object[$ai_totrows][6]="<input name=txtmontotart".$ai_totrows." type=text id=txtmontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".$ai_totart."' onKeyUp='javascript: ue_validarnumero(this);' style='text-align:right' readonly>";
				$ao_object[$ai_totrows][7]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a>";
				$ao_object[$ai_totrows][8]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0></a>"; 
			//	$ai_totrows=$ai_totrows+1;
			}//while
			$ai_totrows=$ai_totrows+1;
//			uf_agregarlineablanca($ao_object,$li_totrows);
			//die();sfa_dt_movimiento
		}//else
		if ($ai_totrows==0)
		{$lb_valido=false;}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_sfa_obtener_dt_movimiento


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

///////////////////////////////////////////////////////////////////////////////
	function uf_sfa_select_inventario($as_codemp,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_select_inventario
		//         Access: public (tepuy_sfa_d_reactuzalizar_inventario)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $ai_totrows   // total de productos (filas) encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que busca los productos asociados a los movimientos de entrada y salida de inventario 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "SELECT sfa_producto.codpro,sfa_producto.denpro,sfa_producto.codunimed, ".
				  "      (SELECT denunimed FROM siv_unidadmedida ".
				  "	       WHERE sfa_producto.codunimed = siv_unidadmedida.codunimed) AS denunimed,".
				  "      (SELECT SUM(cantpro) FROM sfa_dt_movimiento".
				  "        WHERE sfa_dt_movimiento.codpro=sfa_producto.codpro) AS canproing, ".
				  "      (SELECT SUM(cantpro) FROM sfa_dt_factura".
				  "        WHERE sfa_dt_factura.codpro=sfa_producto.codpro AND numfactura IN (SELECT numfactura FROM sfa_factura WHERE sfa_factura.estfac!='3')) AS canprofac ".
				  "  FROM sfa_producto ".
				  " WHERE sfa_producto.codemp='".$as_codemp."'".
				  " ORDER BY sfa_producto.codpro ASC";	
		/*$ls_sql= "SELECT sfa_dt_movimiento.*, siv_unidadmedida.denunimed, sfa_producto.preproa, ".
				  "      (SELECT codunimed FROM sfa_producto ".
				  "	       WHERE sfa_dt_movimiento.codpro = sfa_producto.codpro) AS codunimed,".
				  "      (SELECT MAX(denpro) FROM sfa_producto".
				  "        WHERE sfa_dt_movimiento.codpro=sfa_producto.codpro) AS denpro".
				  "  FROM sfa_dt_movimiento, sfa_movimiento, sfa_producto, siv_unidadmedida ".
				  " WHERE sfa_dt_movimiento.codemp=sfa_movimiento.codemp".
				  "   AND sfa_dt_movimiento.codpro=sfa_producto.codpro".
				  "   AND sfa_producto.codunimed=siv_unidadmedida.codunimed".
				  "   AND sfa_dt_movimiento.nummov=sfa_movimiento.nummov".
				  "   AND sfa_dt_movimiento.codemp='".$as_codemp."'".
				  "   AND sfa_dt_movimiento.codemp=sfa_movimiento.codemp ".
				  "   AND sfa_dt_movimiento.nummov='".$as_nummov."'".
				  " ORDER BY sfa_dt_movimiento.orden ASC";*/
		//print $ls_sql; die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->FACTURA M�TODO->uf_sfa_select_inventario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codpro=	$row["codpro"];
				$as_denpro=	$row["denpro"];
				$as_unidad=	$row["denunimed"];
				$ai_canpro=	$row["canproing"];
				$ai_canfac=	$row["canprofac"];
				$ai_respro=	($ai_canpro-$ai_canfac);
				//$ai_totsuminv=	($ai_totsuminv+$ai_totart);
				
				$ai_respro=	number_format($ai_respro,2,",",".");
				$ai_canpro=	number_format($ai_canpro,2,",",".");
				$ai_canfac=	number_format($ai_canfac,2,",",".");
				//print $as_codpro." ".$as_denpro." ".$ai_canpro." ".$ai_canfac;
				$ai_totrows=$ai_totrows+1;

				$ao_object[$ai_totrows][1]="<input name=txtdenpro".$ai_totrows."    type=text id=txtdenpro".$ai_totrows."    class=sin-borde size=20 maxlength=50 value='".$as_denpro."' readonly><input name=txtcodpro".$ai_totrows."    type=hidden id=txtcodpro".$ai_totrows."    class=sin-borde size=20 maxlength=20 value='".$as_codpro."' </a>";
				$ao_object[$ai_totrows][2]="<div align='center'></div><input name=txtunimed".$ai_totrows."    type=text id=txtunimed".$ai_totrows."    class=sin-borde size=12 maxlength=12 value='".$as_unidad."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtcanpro".$ai_totrows."    type=text id=txtcanpro".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".$ai_canpro."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtcanfac".$ai_totrows." type=text id=txtcanfac".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".$ai_canfac."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
				$ao_object[$ai_totrows][5]="<input name=txtcanres".$ai_totrows." type=text id=txtcanres".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".$ai_respro."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
			//	$ai_totrows=$ai_totrows+1;
			}//while
			$ai_totrows=$ai_totrows+1;
//			uf_agregarlineablanca($ao_object,$li_totrows);
			//die();
		}//else
		if ($ai_totrows==0)
		{$lb_valido=false;}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_sfa_obtener_dt_movimiento

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_sfa_update_inventario($as_codemp,$as_codpro,$ai_existencia,$as_codusu,$aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_insert_movimiento
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de la empresa
		//      	   $as_codpro    // Codigo del producto
		//      	   $ai_existencia// Existencia actual del producto
		//                 $as_codusu    // codigo del usuario
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un maestro de movimiento en la tabla de  siv_movimiento
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 13/01/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql =	"UPDATE sfa_producto SET exipro='".$ai_existencia."' ".
				" WHERE codemp='".$as_codemp."'".
				" AND codpro='".$as_codpro."'";
		//print $ls_sql; die();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimientoinventario M�TODO->uf_sfa_update_inventario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualiz� el Inventario de Productos Cod.".$as_codpro;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function uf_sfa_update_movimiento

} // end class tepuy_sfa_c_movimientoinventario
?>
