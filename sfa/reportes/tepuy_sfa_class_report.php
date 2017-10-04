<?php
class tepuy_sfa_class_report
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_sfa_class_report()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_sfa_class_report
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia 
		// Fecha Creación: 18/06/2007. 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/tepuy_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
						
		$io_include         = new tepuy_include();
		$this->io_conexion  = $io_include->uf_conectar();
		$this->io_sql       = new class_sql($this->io_conexion);	
		$this->io_mensajes  = new class_mensajes();		
		$this->io_funciones = new class_funciones();
		$this->ds=new class_datastore();	
        	$this->ls_codemp    = $_SESSION["la_empresa"]["codemp"];
		$this->ls_codusu    = $_SESSION["la_logusr"];
	}// end function tepuy_sfa_class_report
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_select_factura_imprimir($as_numfactura,&$lb_valido)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_orden_imprimir
		//         Access: public 
		//	    Arguments: as_numordcom    ---> Orden de Compra a imprimir
		//                 $as_estcondat  ---< tipo de la orden de compra bienes o servicios 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca una orden de compra para imprimir
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/06/2007									Fecha Última Modificación :  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT sfa_factura.*, sfa_cliente.nomcli, sfa_cliente.apecli, sfa_cliente.dircli, sfa_cliente.naccli, ".
			" sfa_cliente.telcli, sfa_cliente.celcli, sfa_cliente.email, sfa_cliente.trabajador, ".
			" sss_usuarios.nomusu, sss_usuarios.apeusu, ".
			"       (SELECT denfor FROM sfa_formapago ".
			"         WHERE sfa_factura.forpagfac= sfa_formapago.codfor) AS denforpag, ".
			"       (SELECT forpag FROM sfa_formapago ".
			"         WHERE sfa_factura.forpagfac= sfa_formapago.codfor) AS tipforpag ".
			"   FROM sfa_factura, sfa_cliente, sss_usuarios ".
			"  WHERE sfa_factura.codemp='".$this->ls_codemp."' ".
			"    AND sfa_factura.numfactura='".$as_numfactura."' ".
			"    AND sfa_factura.codemp=sfa_cliente.codemp ".
			"    AND sfa_factura.cedcli=sfa_cliente.cedcli ".
			"    AND sfa_factura.usuario=sss_usuarios.codusu ";
		//print $ls_sql; die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_factura_imprimir ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $rs_data;
	}// end function uf_select_factura_imprimir
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_detalle_factura_imprimir($as_numfactura,&$lb_valido)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_detalle_factura_imprimir
		//         Access: public 
		//	    Arguments: as_numfactura    ---> Factura a imprimir
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca los detalles de la Factura para imprimir
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/11/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT sfa_dt_factura.*, siv_unidadmedida.denunimed, sfa_producto.denpro, sfa_producto.codunimed ".
			" FROM sfa_dt_factura, sfa_cliente, siv_unidadmedida,sfa_producto, sfa_factura ".
			" WHERE  sfa_dt_factura.codemp='".$this->ls_codemp."' AND ".
           		" sfa_dt_factura.numfactura='".$as_numfactura."' AND ".
			" sfa_dt_factura.codemp=sfa_factura.codemp AND ".
			" sfa_dt_factura.numfactura=sfa_factura.numfactura AND ".
			" sfa_factura.codemp=sfa_cliente.codemp AND ".
			" sfa_factura.cedcli=sfa_cliente.cedcli AND ".
			" sfa_dt_factura.codemp=sfa_cliente.codemp AND ".
			" sfa_dt_factura.codemp=sfa_producto.codemp AND ".
			" sfa_dt_factura.codpro=sfa_producto.codpro  AND ".
       			" siv_unidadmedida.codunimed=sfa_producto.codunimed ".
			" ORDER BY sfa_dt_factura.orden ASC ";
		 // print $ls_sql; die();
		$rs_data=$this->io_sql->select($ls_sql);//echo $ls_sql;
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_detalle_orden_imprimir ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $rs_data;
	}// end function uf_select_detalle_orden_imprimir
	//-----------------------------------------------------------------------------------------------------------------------------------



	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_movimiento_inventario($as_codemp,$as_numinginv,$ad_fecmov,$as_logusr,&$lb_valido)	
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_select_movimiento_inventario
	//	           Access:   public
	//  		Arguments:   as_codemp    // codigo de empresa
	//  			     as_numinginv // numero de Registro de Inventario
	//  			     ad_fecmov    // fecha de Registro de Inventario
	//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
	//	      Description:  Función que se encarga de realizar la busqueda  de las ordedes de despacho emitidas 
	//				        en un rango de fecha indicado, ordenados por fecha.
	//         Creado por:  Ing. Miguel Palencia           
	//   Fecha de Cracion:   20/11/2017
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ld_fecmovaux=$this->io_funciones->uf_convertirdatetobd($ad_fecmov);
	$ls_sql="SELECT sfa_movimiento.*,".
		"       (SELECT nomusu FROM sss_usuarios ".
		"         WHERE sfa_movimiento.usuario='".$as_logusr."' AND sfa_movimiento.usuario=sss_usuarios.codusu) AS nomusu, ".
		"       (SELECT apeusu FROM sss_usuarios ".
		"         WHERE sfa_movimiento.usuario='".$as_logusr."' AND sfa_movimiento.usuario=sss_usuarios.codusu) AS apeusu ".
		"  FROM sfa_movimiento ".
		" WHERE codemp='".$as_codemp."' ".
		" AND nummov='".$as_numinginv."' ".
		" AND fecmov='".$ld_fecmovaux."' ".
		" GROUP BY sfa_movimiento.nummov";
	//print $ls_sql;die();
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_factura_imprimir ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
	}
	return $rs_data;


	} //fin  function uf_select_movimiento_inventario

///////////////////////////////////////////////////////////////////////////////
	function uf_sfa_select_dt_movimiento_inventario($as_codemp,$as_nummov,&$lb_valido)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_obtener_dt_factura
		//         Access: public (tepuy_sfa_d_factura)
		//      Argumento: $as_codemp    // codigo de empresa
		//		    $as_nummov   // numero de movimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que busca los productos asociados a la factura en la tabla sfa_dt_factura para luego 
		//                 imprimirlos en el grid de la pagina exepto que ya se recibieron por completo.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 10/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "SELECT sfa_dt_movimiento.*, siv_unidadmedida.denunimed, sfa_producto.preproa,sfa_producto.preprob, ".
				  " sfa_producto.preproc, sfa_producto.preprod, ".
				  "      (SELECT codunimed FROM sfa_producto ".
				  "	       WHERE sfa_dt_movimiento.codpro = sfa_producto.codpro) AS codunimed,".
				  "      (SELECT MAX(denpro) FROM sfa_producto".
				  "        WHERE sfa_dt_movimiento.codpro=sfa_producto.codpro) AS denpro".
				  "  FROM sfa_dt_movimiento, sfa_movimiento, sfa_producto, siv_unidadmedida ".
				  " WHERE sfa_dt_movimiento.nummov='".$as_nummov."' ".
				  " AND sfa_dt_movimiento.codemp=sfa_movimiento.codemp".
				  "   AND sfa_dt_movimiento.codpro=sfa_producto.codpro".
				  "   AND sfa_producto.codunimed=siv_unidadmedida.codunimed".
				  "   AND sfa_dt_movimiento.nummov=sfa_movimiento.nummov".
				  "   AND sfa_dt_movimiento.codemp='".$as_codemp."'".
				  "   AND sfa_dt_movimiento.codemp=sfa_movimiento.codemp ".
				  " ORDER BY sfa_dt_movimiento.orden ASC";
		//print $ls_sql; die();
		$rs_data=$this->io_sql->select($ls_sql);//echo $ls_sql;
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sfa_select_dt_movimiento_inventario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $rs_data;
	} // end function uf_sfa_select_dt_movimiento_inventario


  //---------------------------------------------------------------------------------------------------------------------------------------

	function uf_select_resumen_facturas($as_numfacturades,$as_numfacturahas,$as_cedclides,
                                                            $as_cedclihas,$as_fecfacturades,$as_fecfacturahas,$as_rdemi,$as_rdcon,$as_rdanu,
                                                            $as_codprodes,$as_codprohas,$as_formapago,$as_tipocliente,&$lb_valido)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_resumen_factura
		//         Access: public 
		//	    Arguments: as_numfascturades   ---> Numero de Factura Inicial para busqueda
		//	    Arguments: as_numfacturahas   ---> Numero de Factura Final para busqueda
		//	    Arguments: as_cedclides   ---> Cedula de Cliente Inicial para busqueda
		//	    Arguments: as_cedclihas   ---> Cedula de Cliente Final para busqueda
		//	    Arguments: as_fecfacturades   ---> Fecha de Inicio de Busqueda de Factura
		//	    Arguments: as_fecfacturahas   ---> Fecha Final de Busqueda de factura
		//	    Arguments: as_codprodes   ---> Producto Inicio de Busqueda en el Factura
		//	    Arguments: as_codprohas   ---> Producto Final de Busqueda en el Factura
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca los detalles de las facturas para imprimir
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/07/2007									Fecha Última Modificación :  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		//$ab_valido = true;
		$lb_valido = true;  
		$ls_criterio_a = "";
		$ls_criterio_b = "";   
		$ls_criterio_c = "";  
		$ls_criterio_d = "";
		$ls_criterio_e = "";
		$ls_criterio_f = "";
		$ls_criterio_g = "";
		$ls_cad        = "";
		$ls_cadena     = "";
		$ls_sql        = "";
		$ls_parentesis = "";
		if(  (($as_numfacturades!="") && ($as_numfacturahas=="")) || (($as_numfacturades=="") && ($as_numfacturahas!=""))  )
		{
		   $lb_valido = false;
		   $this->io_msg->message("Debe Completar el Rango de Busqueda por Número de Factura!!!"); 	  
		}
		else
		{
			if( ($as_numfacturades!="") && ($as_numfacturahas!="") )
			{
			   $ls_criterio_a = "   numfactura >='".$as_numfacturades."'  AND  numfactura <='".$as_numfacturahas."'    ";
			}
			else
			{
			   $ls_criterio_a ="";
			}
		}

		if(  (($as_cedclides!="") && ($as_cedclihas=="")) || (($as_cedclides=="") && ($as_cedclihas!=""))  )
		{
		   $lb_valido = false;
		   $this->io_msg->message("Debe Completar el Rango de Busqueda por Cliente !!!"); 
		}
		else
		{
			if( ($as_cedclides!="") && ($as_cedclihas!="") )
			{
			   if($ls_criterio_a=="")
			   {
					 $CA_AND="";   //CA = Criterio A
			   } 
			   else
			   {
					 $CA_AND="  AND  ";
			   }
			   $ls_criterio_b  =  $ls_criterio_a.$CA_AND."  cedcli   >='".$as_cedclides."'  AND  cedcli   <='".$as_cedclihas."'  ";
			}
			else
			{
			   $ls_criterio_b = $ls_criterio_a;
			}
		}
//print "aqui";die();
	
		if(  (($as_fecfacturades!="") && ($as_fecfacturahas=="")) || (($as_fecfacturades=="") && ($as_fecfacturahas!=""))  )
		{
		   $lb_valido = false;
		   $this->io_msg->message("Debe Completar el Rango de Busqueda por Fechas !!!"); 	
		}
		else
		{				   
		   if( ($as_fecfacturades!="") && ($as_fecfacturahas!="") )
		   {
			   $ls_fecha  = $this->io_funciones->uf_convertirdatetobd($as_fecfacturades);

			   $as_fecfacturades = $ls_fecha;
		
			   $ls_fechas  = $this->io_funciones->uf_convertirdatetobd($as_fecfacturahas);
			   $as_fecfacturahas  = $ls_fechas;
			
			   if($ls_criterio_b=="")
			   {
					 $CB_AND="";  //CB = Criterio B
			   } 
			   else
			   {
					 $CB_AND="  AND  ";
			   }
			   $ls_criterio_c = $ls_criterio_b.$CB_AND."  fecfactura >='".$as_fecfacturades."'  AND  fecfactura <='".$as_fecfacturahas."'  "; 
			 }
		   else
		   {
				$ls_criterio_c = $ls_criterio_b;
		   }			
		}

		if( ($as_rdemi==0) && ($as_rdcon==0) && ($as_rdanu==0) )
		{  
			$ls_criterio_d = $ls_criterio_c; 
		}
		else
		{
	 
		   if($as_rdemi!=0)
		   {
			  if($ls_cadena!="")
			  {
				 $ls_cad=" OR   estfac = 1  ";
				 $ls_cadena=$ls_cadena.$ls_cad;
			  }
			  else
			  {
				  $ls_cadena=" (  estfac = 1 ";
			  }		  
		   }
		   else
		   {
			 $ls_cadena=$ls_cadena;
		   }
		   if($as_rdcon!=0)
		   {
			  if($ls_cadena!="")
			  {
				 $ls_cad=" OR   estfac = 2  ";
				 $ls_cadena=$ls_cadena.$ls_cad;
			  }
			  else
			  {
				 $ls_cadena=" (  estfac = 2 ";
			  }
		   }
		   else
		   {
			 $ls_cadena=$ls_cadena;
		   }

		   if($as_rdanu!=0)
		   {
			  if($ls_cadena!="")
			  {
				 $ls_cad=" OR   estfac = 3  ";
				 $ls_cadena=$ls_cadena.$ls_cad;
			  }
			  else
			  {
				 $ls_cadena=" (  estfac = 3 ";
			  }
		   }
		   else
		   {
			 $ls_cadena=$ls_cadena;
		   }
	
		   $ls_parentesis="   )   ";
	
		   if(empty($ls_criterio_c))
		   {
			  $CC_AND=""; //CC = Criterio C
		   }
		   else
		   {
			  $CC_AND="   AND   ";
		   }
		   	$ls_criterio_d=$ls_criterio_c.$CC_AND.$ls_cadena.$ls_parentesis;	   
	   	   }

		if($as_formapago!="---")
		{
			if($ls_criterio_d=="")
			{
				 $CD_AND="";   //CD = Criterio D
			} 
			else
			{
				 $CD_AND="  AND  ";
			}
			$ls_criterio_e  =  $ls_criterio_d.$CD_AND."  forpagfac ='".$as_formapago."'  ";

		}
		else
		{
			$ls_criterio_e = $ls_criterio_d;
		}

		if(  (($as_codprodes!="") && ($as_codprohas=="")) || (($as_codprodes=="") && ($as_codprohas!=""))  )
		{
		   $lb_valido = false;
		   $this->io_msg->message("Debe Completar el Rango de Busqueda por Producto !!!"); 
		}
		else

		{
			if( ($as_codprodes!="") && ($as_codprohas!="") )
			{
			 	if($ls_criterio_e=="")
			   	{
					$CF_AND="";   //CF = Criterio F
			   	}
				else
				{
					$CF_AND="  AND  ";
				}
				$ls_criterio_f = $ls_criterio_e.$CF_AND."  numfactura in (SELECT numfactura FROM sfa_dt_factura WHERE codpro >='".
				$as_codprodes."' AND codpro<='".$as_codprohas."') ";
			}
			else
			{
				$ls_criterio_f = $ls_criterio_e;
			}
		}
		if($ls_criterio_f!="")
		{
		   $ls_sql=	" SELECT sfa_factura.*,  ".
				" (SELECT nomcli FROM sfa_cliente WHERE sfa_cliente.cedcli=sfa_factura.cedcli) AS nomcli, ".
				" (SELECT apecli FROM sfa_cliente WHERE sfa_cliente.cedcli=sfa_factura.cedcli) AS apecli, ".
				" (SELECT denfor FROM sfa_formapago WHERE sfa_formapago.codfor=sfa_factura.forpagfac) AS formadepago  ".
				" FROM sfa_factura ".
				" WHERE codemp='".$this->ls_codemp."'  AND ".$ls_criterio_f." ".
				" ORDER BY numfactura ASC";
		}
		else
		{
		   $ls_sql=	" SELECT sfa_factura.*,  ".
				" (SELECT nomcli FROM sfa_cliente WHERE sfa_cliente.cedcli=sfa_factura.cedcli) AS nomcli, ".
				" (SELECT apecli FROM sfa_cliente WHERE sfa_cliente.cedcli=sfa_factura.cedcli) AS apecli,  ".
				" (SELECT denfor FROM sfa_formapago WHERE sfa_formapago.codfor=sfa_factura.forpagfac) AS formadepago  ".
				" FROM sfa_factura ".
				" WHERE codemp='".$this->ls_codemp."' ".
				" ORDER BY numfactura ASC";
		}
		//print $ls_sql; die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_resumen_factura".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
        return $rs_data;
    }//fin de uf_select_resumen_factura
   //---------------------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------------------


///////////////////////////////////////////////////////////////////////////////
	function uf_select_dt_resumen_factura($as_numfactura,$as_cedcli,&$lb_valido)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_dt_resumen_factura
		//         Access: public (tepuy_sfa_rpp_resumen_facturas)
		//      Argumento: $as_codemp    // codigo de empresa
		//		   $as_cedcli    // cedula del cliente
		//  			$as_numfactura // numero de factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que busca los productos asociados a la factura en la tabla sfa_dt_factura para luego 
		//                 imprimirlos en el grid de la pagina exepto que ya se recibieron por completo.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 10/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "SELECT sfa_dt_factura.*, siv_unidadmedida.denunimed, sfa_factura.estfac, sfa_cliente.trabajador, ".
				  "      (SELECT codunimed FROM sfa_producto ".
				  "	       WHERE sfa_dt_factura.codpro = sfa_producto.codpro) AS codunimed,".
				  "      (SELECT MAX(denpro) FROM sfa_producto".
				  "        WHERE sfa_dt_factura.codpro=sfa_producto.codpro) AS denpro".
				  "  FROM sfa_dt_factura, sfa_factura,sfa_producto, siv_unidadmedida, sfa_cliente ".
				  " WHERE sfa_dt_factura.codemp=sfa_factura.codemp".
				  "   AND sfa_dt_factura.codpro=sfa_producto.codpro".
				  "   AND sfa_producto.codunimed=siv_unidadmedida.codunimed".
				  "   AND sfa_dt_factura.numfactura=sfa_factura.numfactura".
				  "   AND sfa_dt_factura.codemp='".$this->ls_codemp."'".
				  "   AND sfa_factura.cedcli='".$as_cedcli."'".
				  "   AND sfa_factura.cedcli=sfa_cliente.cedcli".
				  "   AND sfa_dt_factura.codemp=sfa_factura.codemp ".
				  "   AND sfa_dt_factura.numfactura='".$as_numfactura."'".
				  " ORDER BY sfa_dt_factura.orden ASC";
		//print $ls_sql;die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->FACTURA MÉTODO->uf_sfa_obtener_dt_resumen_factura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		return $rs_data;
	} // end function uf_select_dt_resumen_factura

//---------------------------------------------------------------------------------------------------------------------------------------

	function uf_select_consolidado_facturas($as_numfacturades,$as_numfacturahas,$as_cedclides,
                                                            $as_cedclihas,$as_fecfacturades,$as_fecfacturahas,$as_rdemi,$as_rdcon,$as_rdanu,
                                                            $as_codprodes,$as_codprohas,$as_formapago,$as_tipocliente,&$lb_valido)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_consolidado_facturas
		//         Access: public 
		//	    Arguments: as_numfascturades   ---> Numero de Factura Inicial para busqueda
		//	    Arguments: as_numfacturahas   ---> Numero de Factura Final para busqueda
		//	    Arguments: as_cedclides   ---> Cedula de Cliente Inicial para busqueda
		//	    Arguments: as_cedclihas   ---> Cedula de Cliente Final para busqueda
		//	    Arguments: as_fecfacturades   ---> Fecha de Inicio de Busqueda de Factura
		//	    Arguments: as_fecfacturahas   ---> Fecha Final de Busqueda de factura
		//	    Arguments: as_codprodes   ---> Producto Inicio de Busqueda en el Factura
		//	    Arguments: as_codprohas   ---> Producto Final de Busqueda en el Factura
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca los detalles de las facturas para imprimir
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/07/2007									Fecha Última Modificación :  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		//$ab_valido = true;
		$lb_valido = true;  
		$ls_criterio_a = "";
		$ls_criterio_b = "";   
		$ls_criterio_c = "";  
		$ls_criterio_d = "";
		$ls_criterio_e = "";
		$ls_criterio_f = "";
		$ls_criterio_g = "";
		$ls_cad        = "";
		$ls_cadena     = "";
		$ls_sql        = "";
		$ls_parentesis = "";
		//print "Tipo Cliente: ".$as_tipocliente;die();
		if(  (($as_numfacturades!="") && ($as_numfacturahas=="")) || (($as_numfacturades=="") && ($as_numfacturahas!=""))  )
		{
		   $lb_valido = false;
		   $this->io_msg->message("Debe Completar el Rango de Busqueda por Número de Factura!!!"); 	  
		}
		else
		{
			if( ($as_numfacturades!="") && ($as_numfacturahas!="") )
			{
			   $ls_criterio_a = "   numfactura >='".$as_numfacturades."'  AND  numfactura <='".$as_numfacturahas."'    ";
			}
			else
			{
			   $ls_criterio_a ="";
			}
		}

		if(  (($as_cedclides!="") && ($as_cedclihas=="")) || (($as_cedclides=="") && ($as_cedclihas!=""))  )
		{
		   $lb_valido = false;
		   $this->io_msg->message("Debe Completar el Rango de Busqueda por Cliente !!!"); 
		}
		else
		{
			if( ($as_cedclides!="") && ($as_cedclihas!="") )
			{
			   if($ls_criterio_a=="")
			   {
					 $CA_AND="";   //CA = Criterio A
			   } 
			   else
			   {
					 $CA_AND="  AND  ";
			   }
			   $ls_criterio_b  =  $ls_criterio_a.$CA_AND."  cedcli   >='".$as_cedclides."'  AND  cedcli   <='".$as_cedclihas."'  ";
			}
			else
			{
			   $ls_criterio_b = $ls_criterio_a;
			}
		}
//print "aqui";die();
	
		if(  (($as_fecfacturades!="") && ($as_fecfacturahas=="")) || (($as_fecfacturades=="") && ($as_fecfacturahas!=""))  )
		{
		   $lb_valido = false;
		   $this->io_msg->message("Debe Completar el Rango de Busqueda por Fechas !!!"); 	
		}
		else
		{				   
		   if( ($as_fecfacturades!="") && ($as_fecfacturahas!="") )
		   {
			   $ls_fecha  = $this->io_funciones->uf_convertirdatetobd($as_fecfacturades);

			   $as_fecfacturades = $ls_fecha;
		
			   $ls_fechas  = $this->io_funciones->uf_convertirdatetobd($as_fecfacturahas);
			   $as_fecfacturahas  = $ls_fechas;
			
			   if($ls_criterio_b=="")
			   {
					 $CB_AND="";  //CB = Criterio B
			   } 
			   else
			   {
					 $CB_AND="  AND  ";
			   }
			   $ls_criterio_c = $ls_criterio_b.$CB_AND."  fecfactura >='".$as_fecfacturades."'  AND  fecfactura <='".$as_fecfacturahas."'  "; 
			 }
		   else
		   {
				$ls_criterio_c = $ls_criterio_b;
		   }			
		}

		if( ($as_rdemi==0) && ($as_rdcon==0) && ($as_rdanu==0) )
		{  
			$ls_criterio_d = $ls_criterio_c; 
		}
		else
		{
	 
		   if($as_rdemi!=0)
		   {
			  if($ls_cadena!="")
			  {
				 $ls_cad=" OR   estfac = 1  ";
				 $ls_cadena=$ls_cadena.$ls_cad;
			  }
			  else
			  {
				  $ls_cadena=" (  estfac = 1 ";
			  }		  
		   }
		   else
		   {
			 $ls_cadena=$ls_cadena;
		   }
		   if($as_rdcon!=0)
		   {
			  if($ls_cadena!="")
			  {
				 $ls_cad=" OR   estfac = 2  ";
				 $ls_cadena=$ls_cadena.$ls_cad;
			  }
			  else
			  {
				 $ls_cadena=" (  estfac = 2 ";
			  }
		   }
		   else
		   {
			 $ls_cadena=$ls_cadena;
		   }

		   if($as_rdanu!=0)
		   {
			  if($ls_cadena!="")
			  {
				 $ls_cad=" OR   estfac = 3  ";
				 $ls_cadena=$ls_cadena.$ls_cad;
			  }
			  else
			  {
				 $ls_cadena=" (  estfac = 3 ";
			  }
		   }
		   else
		   {
			 $ls_cadena=$ls_cadena;
		   }
	
		   $ls_parentesis="   )   ";
	
		   if(empty($ls_criterio_c))
		   {
			  $CC_AND=""; //CC = Criterio C
		   }
		   else
		   {
			  $CC_AND="   AND   ";
		   }
		   	$ls_criterio_d=$ls_criterio_c.$CC_AND.$ls_cadena.$ls_parentesis;	   
	   	   }

		if($as_formapago!="---")
		{
			if($ls_criterio_d=="")
			{
				 $CD_AND="";   //CD = Criterio D
			} 
			else
			{
				 $CD_AND="  AND  ";
			}
			$ls_criterio_e  =  $ls_criterio_d.$CD_AND."  forpagfac ='".$as_formapago."'  ";

		}
		else
		{
			$ls_criterio_e = $ls_criterio_d;
		}

		if(  (($as_codprodes!="") && ($as_codprohas=="")) || (($as_codprodes=="") && ($as_codprohas!=""))  )
		{
		   $lb_valido = false;
		   $this->io_msg->message("Debe Completar el Rango de Busqueda por Producto !!!"); 
		}
		else
		{
			if( ($as_codprodes!="") && ($as_codprohas!="") )
			{
			 	if($ls_criterio_e=="")
			   	{
					$CF_AND="";   //CF = Criterio F
			   	}
				else
				{
					$CF_AND="  AND  ";
				}
				$ls_criterio_f = $ls_criterio_e.$CF_AND."  numfactura in (SELECT numfactura FROM sfa_dt_factura WHERE codpro >='".
				$as_codprodes."' AND codpro<='".$as_codprohas."') ";
			}
			else
			{
				$ls_criterio_f = $ls_criterio_e;
			}
		}
		if($as_tipocliente!=1)
		{
			$ls_criterio_g = $ls_criterio_f;	
		}
		else
		{
				if($ls_criterio_f=="")
			   	{
					$CG_AND="";   //CF = Criterio F
			   	}
				else
				{
					$CG_AND="  AND  ";
				}
				$ls_criterio_g = $ls_criterio_f.$CG_AND." sfa_cliente.trabajador='1' ";
		}

		if($ls_criterio_f!="")
		{
		   $ls_sql=	" SELECT sfa_factura.*,  ".
				" (SELECT nomcli FROM sfa_cliente WHERE sfa_cliente.cedcli=sfa_factura.cedcli) AS nomcli, ".
				" (SELECT apecli FROM sfa_cliente WHERE sfa_cliente.cedcli=sfa_factura.cedcli) AS apecli, ".
				" (SELECT denfor FROM sfa_formapago WHERE sfa_formapago.codfor=sfa_factura.forpagfac) AS formadepago  ".
				" FROM sfa_factura ".
				" WHERE codemp='".$this->ls_codemp."'  AND ".$ls_criterio_f." ".
				" GROUP BY cedcli ASC";
		}
		else
		{
		   $ls_sql=	" SELECT sfa_factura.*,  ".
				" (SELECT nomcli FROM sfa_cliente WHERE sfa_cliente.cedcli=sfa_factura.cedcli) AS nomcli, ".
				" (SELECT apecli FROM sfa_cliente WHERE sfa_cliente.cedcli=sfa_factura.cedcli) AS apecli,  ".
				" (SELECT denfor FROM sfa_formapago WHERE sfa_formapago.codfor=sfa_factura.forpagfac) AS formadepago  ".
				" FROM sfa_factura ".
				" WHERE codemp='".$this->ls_codemp."' ".
				" GROUP BY cedcli ASC";
		}
		//print $ls_sql; die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_resumen_factura".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
        return $rs_data;
    }//fin de uf_select_consolidado_facturas

///////////////////////////////////////////////////////////////////////////////
	function uf_select_dt_consolidado_factura($as_cedcli,&$lb_valido)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_dt_consolidado_factura
		//         Access: public (tepuy_sfa_rpp_consolidado_facturas)
		//      Argumento: $as_codemp    // codigo de empresa
		//		   $as_cedcli    // cedula del cliente
		//  			$as_numfactura // numero de factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que busca los productos asociados a la factura en la tabla sfa_dt_factura para luego 
		//                 imprimirlos en el grid de la pagina exepto que ya se recibieron por completo.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 10/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "SELECT sfa_dt_factura.codpro, sfa_dt_factura.cantpro, sfa_dt_factura.preunipro,sfa_dt_factura.monsubpro, sfa_dt_factura.moncarpro, sfa_dt_factura.montotpro, ".
			 "siv_unidadmedida.denunimed, sfa_factura.estfac, sfa_cliente.trabajador, sfa_factura.cedcli, ".
				  "      (SELECT codunimed FROM sfa_producto ".
				  "	       WHERE sfa_dt_factura.codpro = sfa_producto.codpro) AS codunimed,".
				  "      (SELECT MAX(denpro) FROM sfa_producto".
				  "        WHERE sfa_dt_factura.codpro=sfa_producto.codpro) AS denpro".
				  "  FROM sfa_dt_factura, sfa_factura,sfa_producto, siv_unidadmedida, sfa_cliente ".
				  " WHERE sfa_dt_factura.codemp=sfa_factura.codemp".
				  "   AND sfa_dt_factura.codpro=sfa_producto.codpro".
				  "   AND sfa_producto.codunimed=siv_unidadmedida.codunimed".
				  "   AND sfa_dt_factura.numfactura=sfa_factura.numfactura".
				  "   AND sfa_dt_factura.codemp='".$this->ls_codemp."'".
				  "   AND sfa_factura.cedcli='".$as_cedcli."'".
				  "   AND sfa_factura.cedcli=sfa_cliente.cedcli".
				  //"   AND sfa_dt_factura.codemp=sfa_factura.codemp ".
				 // "   AND sfa_dt_factura.numfactura='".$as_numfactura."'".
				  " ORDER BY sfa_dt_factura.codpro ASC";
		//print $ls_sql;die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->FACTURA MÉTODO->uf_sfa_obtener_dt_cpnsolidado_factura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		return $rs_data;
	} // end function uf_select_dt_consolidado_factura
   
  //---------------------------------------------------------------------------------------------------------------------------------------

 
  //---------------------------------------------------------------------------------------------------------------------------------------

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////               Funciones del Reporte de Resumen de Inventario                 ///////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_inventario($as_codemp,$as_codprodes,$as_codprohas,$ad_fecdesde,$ad_fechasta,$ai_ordenpro)	
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_select_inventario
	//	           Access:   public
	//  		Arguments:  
	//			as_codemp     // codigo de empresa
	//  			as_codprodes  // codigo de inicio del intervalo de Productos para la busqueda
	//  			as_codprohas  // codigo de cierre del intervalo de Productos para la busqueda
	//  			ad_fecdesde   // fecha de inicio del intervalo de dias para la busqueda
	//  			ad_fechasta   // fecha de cierre del intervalo de dias para la busqueda
	//  			ai_ordenpro   // parametro por el cual vamos a ordenar los resultados
	//				         obtenidos en la consulta   0-> Por codigo del Producto articulo 1-> Por denominacion del Producto
	//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
	//	      Description:  Función que se encarga de realizar la busqueda  de los productos que estan registrados en la tabla 
	//			    sfa_productos ordenando los resultados por codigo de articulo o por denominacion segun sea lo indicado
	//			    ademas de buscar los otros datos necesarios para generar el reporte.
	//         Creado por:  Ing. Miguel Palencia           
	//   Fecha de Cracion:   23/11/2016
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		$ls_sqlintfec="";
		$ls_sqlintfec1="";
		//print "fecha1: ".$ad_fecdesde." fecha2: ".$ad_fechasta;
		//print "codprodes: ".strlen($as_codprodes)." codprohas: ".strlen($as_codprohas);
		if((strlen($as_codprodes)!='117')&&(strlen($as_codprohas)!='117'))
		{
			$ls_sqlint=" AND codpro >='". $as_codprodes ."' AND codpro <='". $as_codprohas ."'";
		}

		if((!empty($ad_fecdesde))&&(!empty($ad_fechasta)))
		{
			$ld_auxdesde=substr($ad_fecdesde,6,4)."-".substr($ad_fecdesde,3,2)."-".substr($ad_fecdesde,0,2);
			//$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_fechasta);
			$ld_auxhasta=substr($ad_fechasta,6,4)."-".substr($ad_fechasta,3,2)."-".substr($ad_fechasta,0,2);
			//print "fecha1: ".$ld_auxdesde." fecha2: ".$ld_auxhasta;
			$ls_sqlintfec=" AND fecmov >='". $ld_auxdesde ."' AND fecmov <='". $ld_auxhasta ."'";
			$ls_sqlintfec1=" AND sfa_factura.fecfactura >='". $ld_auxdesde ."' AND sfa_factura.fecfactura <='". $ld_auxhasta ."'";
		}
		if($ai_ordenpro==0)
		{
			$ls_order="codpro";
		}
		else
		{
			$ls_order="denpro";
		}
		$ls_sql=" SELECT sfa_producto.*,".
				"        (SELECT count(codpro) FROM sfa_producto) AS total, ".
				"        (SELECT denunimed FROM siv_unidadmedida".
				"          WHERE siv_unidadmedida.codunimed=sfa_producto.codunimed) AS denunimed,".
				"        (SELECT SUM(cantpro) FROM sfa_dt_movimiento".
				"          WHERE sfa_dt_movimiento.codpro=sfa_producto.codpro".
				//"            AND sfa_dt_movimiento.opeinv='ENT'".
				//"            AND sfa_dt_movimiento.codprodoc<>'REV'".
				"            AND sfa_dt_movimiento.cantpro > 0 ". $ls_sqlintfec ." ) AS entradas, ".
				//"		     AND numdocori NOT IN (SELECT numdoc FROM sfa_dt_movimiento".
				//"                                   WHERE codemp='". $as_codemp ."'".
   				//"                                     AND cantpro > 0".
				//"                                     AND codprodoc ='REV')) AS entradas,".
				"        (SELECT SUM(cantpro) FROM sfa_dt_factura".
				"          WHERE sfa_dt_factura.codpro=sfa_producto.codpro".
				//"            AND sfa_dt_movimiento.opeinv='SAL' ". $ls_sqlintfec ."".
				//"            AND sfa_dt_movimiento.codpro<>'REV'".
				"		     AND numfactura IN (SELECT sfa_dt_factura.numfactura FROM sfa_dt_factura, sfa_factura ".
				"                                   WHERE sfa_dt_factura.codemp='". $as_codemp ."'".
   				"                                     AND sfa_dt_factura.cantpro > 0 ".$ls_sqlintfec1 ." )) AS salidas, ".
				//"                                     AND codprodoc ='REV')) AS salidas".
				"        (SELECT SUM(sfa_dt_factura.monsubpro) FROM sfa_dt_factura, sfa_factura ".
				"          WHERE sfa_dt_factura.codpro=sfa_producto.codpro AND sfa_dt_factura.numfactura=sfa_factura.numfactura ".
				$ls_sqlintfec1.") AS montofacturado ".
				//"            AND sfa_dt_movimiento.opeinv='ENT'".
				//"            AND sfa_dt_movimiento.codprodoc<>'REV'".
				" FROM sfa_producto ".
				" WHERE codemp='".$as_codemp."' ".
				$ls_sqlint.
				" ORDER BY ". $ls_order."";
				//print $ls_sql;die();
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_inventario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_inventario

   //---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_nombre_Cliente($as_cedcli,$arg1,$arg3)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_nombre_Cliente
		//		   Access: private 
		//	    Arguments: as_codpro //cedula de Cliente
		//    Description: Function que devuelve la denominacion de la cuenta presupuestaria
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creación: 21/11/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $arg2=" codemp='".$this->ls_codemp."' ";
		 $ls_sql=$arg1." ".$arg2." ".$arg3;
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_nombre_cliente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_nomcli=trim($row["nomcli"])." ".trim($row["apecli"]);
				$lb_valido=true;
			 }	
			$this->io_sql->free_result($rs);
		 } 
		 return $as_nomcli;    
	}//fin 	uf_select_nombre_Cliente
   //---------------------------------------------------------------------------------------------------------------------------------	
	
//---------------------------------------------------------------------------------------------------------------------------------------	

	function uf_calcular_montos($ai_totrow,$aa_items,&$aa_totales,$as_tipo_proveedor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_montos
		//		   Access: public
		//		  return :	arreglo  montos totalizados
		//	  Description: Metodo que  devuelve arreglo  montos totalizados
		//	   Creado Por: Ing. Miguel Palencia
		// 			  Fecha: 09/08/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$li_subtotal=0;
		 	$li_totaliva=0;
		 	$li_total=0;
		 	$aa_totales=array();
			for($li_j=1;$li_j<=$ai_totrow;$li_j++)
		 	{
				$li_subtotal+=(($aa_items[$li_j]["precio"]) * ($aa_items[$li_j]["cantidad"]));
				if($as_tipo_proveedor != "F") //En caso de que el roveedor sea formal no se le calculan los cargos
					$li_totaliva+=$aa_items[$li_j]["moniva"];	
			}
			$li_total=$li_totaliva+$li_subtotal;		 
			$aa_totales["subtotal"]=$li_subtotal;
			$aa_totales["totaliva"]=$li_totaliva;
			$aa_totales["total"]=$li_total;
	}
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	
	function uf_load_nombre_usuario()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_load_nombre_usuario
		//		   Access:  public
		//		 Argument: 
		//	  Description:  Función que obtiene el nombre completo del usuario que imprime el documento
		//	   Creado Por:  Ing. Miguel Palencia
		// Fecha Creación:  22/10/2007								Fecha Última Modificación:
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_nombre="";
		$ls_sql="SELECT nomusu,apeusu".
			  	"  FROM sss_usuarios".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codusu='".$this->ls_codusu."'";
		$rs_data= $this->io_sql->select($ls_sql);//print $ls_sql;
		if ($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->tepuy_sfa_class_report.php->MÉTODO->uf_load_nombre_usuario.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_nombre= $row["nomusu"]." ".$row["apeusu"];
			}
		}
	  return $ls_nombre;
	}// end function uf_load_nombre_usuario
//--------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_descuentos($as_numordcom,$as_estcondat)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_cuenta_gasto
		//         Access: public 
		//	    Arguments: as_numordcom    ---> Orden de Compra a imprimir
		//                 $as_estcondat  ---< tipo de la orden de compra bienes o servicios 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las cuenats de gastos de la  orden de compra para imprimir
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/06/2007									Fecha Última Modificación :  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->ds_soc_desc = new class_datastore();
		$ls_sql=" SELECT monto										".
				" FROM   soc_oc_deducciones							".                         
				" WHERE  codemp='".$this->ls_codemp."'  AND         ".
				"        numordcom='".$as_numordcom."'  AND         ".
				"        estcondat='".$as_estcondat."'              ";    
		$rs_data=$this->io_sql->select($ls_sql);
		$li_numrows=$this->io_sql->num_rows($rs_data);	   	  
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_descuentos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_soc_desc->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_cuenta_gasto
//-----------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------
   function uf_select_pais($as_codpais,&$as_denominacion)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_denominacion_unidad_medida
		//		   Access: private 
		//	    Arguments: as_cuenta //codigo de la cuenta
		//	   			   as_denominacion // denominacion de la cuenta
		//    Description: Function que devuelve la denominacion de la cuenta presupuestaria
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql=" SELECT despai  ".
				 " FROM   tepuy_pais ".
				 " WHERE  codpai='".$as_codpais."'";       
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_pais ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_denominacion=$row["despai"];     
				$lb_valido=true;
			 }	
			$this->io_sql->free_result($rs);
		 } 
		 return $lb_valido;    
	}//fin 	uf_select_denominacionspg
//-----------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------
	 function uf_select_estado($as_codpais,$as_codestado,&$as_denestado)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_denominacion_unidad_medida
		//		   Access: private 
		//	    Arguments: as_cuenta //codigo de la cuenta
		//	   			   as_denominacion // denominacion de la cuenta
		//    Description: Function que devuelve la denominacion de la cuenta presupuestaria
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql=" SELECT desest ".
				 " FROM   tepuy_estados ".
				 " WHERE  codpai='".$as_codpais."' and codest='".$as_codestado."'"; 
				// print  $ls_sql;     
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_pais ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_denestado=$row["desest"];     
				$lb_valido=true;
			 }	
			 else
			 {$as_denestado="";}
			$this->io_sql->free_result($rs);
		 } 
		 return $lb_valido;    
	}//fin 	uf_select_denominacionspg
//-----------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------
	 function uf_select_municipio($as_codpais,$as_codestado,$as_codmun,&$as_denmuni)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_denominacion_unidad_medida
		//		   Access: private 
		//	    Arguments: as_cuenta //codigo de la cuenta
		//	   			   as_denominacion // denominacion de la cuenta
		//    Description: Function que devuelve la denominacion de la cuenta presupuestaria
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql=" SELECT denmun ".
				 " FROM   tepuy_municipio ".
				 " WHERE  codpai='".$as_codpais."' and codest='".$as_codestado."' and codmun='".$as_codmun."'"; 
			//	 print  $ls_sql;     
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_pais ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_denmuni=$row["denmun"];     
				$lb_valido=true;
			 }	
			 else
			 {$as_denestado="";}
			$this->io_sql->free_result($rs);
		 } 
		 return $lb_valido;    
	}//fin 	uf_select_denominacionspg
//-----------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------
	 function uf_select_parroquia($as_codpais,$as_codestado,$as_codmun,$as_parro,&$as_denparro)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_denominacion_unidad_medida
		//		   Access: private 
		//	    Arguments: as_cuenta //codigo de la cuenta
		//	   			   as_denominacion // denominacion de la cuenta
		//    Description: Function que devuelve la denominacion de la cuenta presupuestaria
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql=" SELECT denpar ".
				 " FROM   tepuy_parroquia ".
				 " WHERE  codpai='".$as_codpais."' and codest='".$as_codestado."' and codmun='".$as_codmun."' and codpar='".$as_parro."'" ; 
			//	 print  $ls_sql;     
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_pais ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_denparro=$row["denpar"];     
				$lb_valido=true;
			 }	
			 else
			 {$as_denestado="";}
			$this->io_sql->free_result($rs);
		 } 
		 return $lb_valido;    
	}//fin 	uf_select_denominacionspg
//-----------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------
    function uf_select_moneda($as_codmon,&$as_denmon)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_denominacion_unidad_medida
		//		   Access: private 
		//	    Arguments: as_cuenta //codigo de la cuenta
		//	   			   as_denominacion // denominacion de la cuenta
		//    Description: Function que devuelve la denominacion de la cuenta presupuestaria
		//	   Creado Por: Ing. Miguel Palencia.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql=" SELECT denmon ".
				 " FROM   tepuy_moneda ".
				 " WHERE  codmon='".$as_codmon."'" ; 
				// print  $ls_sql;     
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_pais ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_denmon=$row["denmon"];     
				$lb_valido=true;
			 }				
			$this->io_sql->free_result($rs);
		 } 
		 return $lb_valido;    
	}//fin 	uf_select_denominacionspg
//--------------------------------------------------------------------------------------------------------------------
function uf_select_pais_municipio_estado($ls_numordcom,$ls_estcondat,$ls_codpai,$ls_codest,$ls_codmun)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_municipio_estado
		//         Access: public 
		//	    Arguments: 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la descripción del municipio y del estado
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 14/04/2008									Fecha Última Modificación :  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT  tepuy_pais.despai,tepuy_estados.desest,tepuy_municipio.denmun  ".
		     	"   FROM tepuy_pais,tepuy_estados,tepuy_municipio,soc_ordencompra  ".
				"  WHERE soc_ordencompra.numordcom='".$ls_numordcom."' 				  ".
				"  AND soc_ordencompra.estcondat='".$ls_estcondat."' 				  ".
				"  AND tepuy_pais.codpai='".$ls_codpai."'          ".
                "  AND tepuy_estados.codpai='".$ls_codpai."'       ".
                "  AND tepuy_estados.codest='".$ls_codest."'       ". 
				"  AND tepuy_estados.codpai=tepuy_municipio.codpai ".
                "  AND tepuy_municipio.codest='".$ls_codest."'     ".
                "  AND tepuy_municipio.codmun='".$ls_codmun."'" ; 
				
		//print $ls_sql;
		$rs_datos=$this->io_sql->select($ls_sql);
		if($rs_datos===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_pais_municipio_estado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $rs_datos;
	}// end function uf_select_pais_municipio_estados

	function uf_select_report_ventas($ld_fecdesde,$ld_fechasta,$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_dt_spg_nota
		//		   Access: public 
		//	  Description: 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 24/01/2017 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql=" SELECT sfa_factura.*,  ".
			" (SELECT nomcli FROM sfa_cliente WHERE sfa_cliente.cedcli=sfa_factura.cedcli) AS nomcli, ".
			" (SELECT apecli FROM sfa_cliente WHERE sfa_cliente.cedcli=sfa_factura.cedcli) AS apecli, ".
			" (SELECT rifcli FROM sfa_cliente WHERE sfa_cliente.cedcli=sfa_factura.cedcli) AS rifcli, ".
			" (SELECT denfor FROM sfa_formapago WHERE sfa_formapago.codfor=sfa_factura.forpagfac) AS formadepago  ".
			" FROM sfa_factura ".
			" WHERE codemp='".$this->ls_codemp."' ".
			" ORDER BY numfactura ASC";

		/*$ls_sql= "SELECT sfa_dt_factura.*, siv_unidadmedida.denunimed, sfa_factura.estfac, sfa_cliente.trabajador, ".
				  " (SELECT nomcli FROM sfa_cliente WHERE sfa_cliente.cedcli=sfa_factura.cedcli) AS nomcli, ".
				  " (SELECT apecli FROM sfa_cliente WHERE sfa_cliente.cedcli=sfa_factura.cedcli) AS apecli, ".
				  "      (SELECT codunimed FROM sfa_producto ".
				  "	       WHERE sfa_dt_factura.codpro = sfa_producto.codpro) AS codunimed,".
				  "      (SELECT MAX(denpro) FROM sfa_producto".
				  "        WHERE sfa_dt_factura.codpro=sfa_producto.codpro) AS denpro".
				  "  FROM sfa_dt_factura, sfa_factura,sfa_producto, siv_unidadmedida, sfa_cliente ".
				  " WHERE sfa_dt_factura.codemp=sfa_factura.codemp".
				  "   AND sfa_dt_factura.codpro=sfa_producto.codpro".
				  "   AND sfa_producto.codunimed=siv_unidadmedida.codunimed".
				  "   AND sfa_dt_factura.numfactura=sfa_factura.numfactura".
				  "   AND sfa_dt_factura.codemp='".$this->ls_codemp."'".
				  "   AND sfa_factura.cedcli='".$as_cedcli."'".
				  "   AND sfa_factura.cedcli=sfa_cliente.cedcli".
				  "   AND sfa_dt_factura.codemp=sfa_factura.codemp ".
				  "   AND sfa_dt_factura.numfactura='".$as_numfactura."'".
				  " ORDER BY sfa_dt_factura.orden ASC";*/
		

		//print $ls_sql;die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_report_ventas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($this->io_sql->num_rows($rs_data)>0)
			{
			//	mysql_data_seek( $rs_data,0);//Devuelvo el puntero al comienzo
				$lb_valido=true;	
			}
			else
			{
				$lb_valido=false;
			}
			//$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}
	function uf_retenciones_del_cliente_unificadas($as_rif,$as_mes,$as_anio,$as_numfactura,$as_fecfactura)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retenciones_del_cliente_unificadas
		//         Access: public  
		//	    Arguments:	as_numcom // Numero de comprobante
		//	    		as_mes    // mes del comprobante
		//	    		as_anio   // año del comprobante
		//			as_fecsol // Fecha del Comprobante	
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Función que busca la información de los diferentes comprobantes
		//	   Creado Por: Ing. Miguel Palencia / Ing. Juniors Fraga
		// Fecha Creación: 17/09/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fechadesde=$as_anio."-".$as_mes."-01";
		$ld_fechahasta=$as_anio."-".$as_mes."-".substr($this->io_fecha->uf_last_day($as_mes,$as_anio),0,2);
		$ls_sql="SELECT scb_cmp_ret.numcom, scb_cmp_ret.codret, scb_cmp_ret.fecrep, scb_cmp_ret.perfiscal, ".
			" scb_cmp_ret.codsujret, scb_cmp_ret.nomsujret, scb_cmp_ret.rif, scb_cmp_ret.dirsujret, scb_cmp_ret.estcmpret, ". 				" scb_cmp_ret.numlic, scb_dt_cmp_ret.numdoc as numficha, cxp_rd.numfac as numfac, scb_dt_cmp_ret.fecfac, ".
			" scb_dt_cmp_ret.numcon, scb_dt_cmp_ret.totcmp_con_iva, scb_dt_cmp_ret.numsop as numsol, ".
			" cxp_solicitudes.consol,cxp_rd.procede as procede ".
			"  FROM scb_cmp_ret, scb_dt_cmp_ret, cxp_rd, cxp_solicitudes, cxp_dt_solicitudes ".
			" WHERE scb_cmp_ret.codemp='".$this->ls_codemp."' ".
			//"   AND codret ='".$as_tiporeten."'".
			//"   AND scb_cmp_ret.numcom=scb_dt_cmp_ret.numcom ".
			"   AND scb_cmp_ret.rif ='".$as_rif."'".
			//"   AND scb_cmp_ret.fecrep>='".$ld_fechadesde."' "." AND scb_cmp_ret.fecrep<='".$ld_fechahasta."' ".
  		    // Incluido por Ing. Arnaldo Paredes para filtrar por la fecha del comprobante.
			"   AND scb_cmp_ret.fecrep ='".$as_fecsol."' ".			
			"   AND scb_dt_cmp_ret.numfac=cxp_dt_solicitudes.numrecdoc ".
			// Se agregó la comparación del campo Numero de Comprobante para filtrar por orden de pago.
			"   AND scb_dt_cmp_ret.numcom=scb_cmp_ret.numcom ". 
			"   AND cxp_rd.numrecdoc=scb_dt_cmp_ret.numdoc ".
			"   AND scb_dt_cmp_ret.numsop='".$as_numsol."' ".
			"   AND cxp_solicitudes.numsol='".$as_numsol."' ".
			" GROUP BY scb_cmp_ret.codret DESC";
				//"   AND scb_dt_cmp_ret.numfac=cxp_rd.numrecdoc ".
				//"   AND scb_cmp_ret.fecrep<='".$ld_fechahasta."' ORDER BY scb_cmp_ret.codret DESC";
				//"   AND numcom='".$as_numcom."' ";
		//print $ls_sql;
		//die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retenciones_del_cliente_unificadas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retenciones_del_cliente_unificadas
	//-----------------------------------------------------------------------------------------------------------------------------------

}//FIN DE LA CLASE.
?>
