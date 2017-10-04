<?php
class tepuy_soc_c_analisis_cotizacion
{
	function tepuy_soc_c_analisis_cotizacion()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	   Function:  tepuy_soc_c_analisis_cotizacion
		//	Description:  Constructor de la Clase
		//////////////////////////////////////////////////////////////////////////////
		global $ls_empresa;
		global $io_include;
		global $io_conexion;	
		global $io_sql;
		global $io_mensajes;
		global  $io_funciones;
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_generar_id_process_soc.php");
		require_once("../shared/class_folder/tepuy_c_generar_consecutivo.php");

		$io_include          = new tepuy_include();
		$io_conexion		 = $io_include->uf_conectar();		
		$this->io_sql        = new class_sql($io_conexion);				
		$this->io_mensajes   = new class_mensajes();	
		$this->io_funciones  = new class_funciones();	
		$this->io_seguridad  = new tepuy_c_seguridad();
		$this->io_dscuentas  = new class_datastore();
		$this->io_dscargos   = new class_datastore();
		$this->ls_codemp     = $_SESSION["la_empresa"]["codemp"];
		$this->io_id_process = new class_generar_id_process_soc();
		$this->io_keygen     = new tepuy_c_generar_consecutivo(); 	
	}//Fin del constructor de la clase	
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_items_cotizacion(&$aa_items)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items_cotizacion
		//		   Access: public
		//		   return:arreglo con los bienes/servicios de la cotizacion dada
		//	  Description: Metodo que  devuelve los bienes/servicios de una cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 09/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_items=array();
		$lb_valido=false;		
		//Tomando los datos del querystring
		$as_tipsolcot=$_GET["tipsolcot"];
		$li_totalcotizaciones=$_GET["totalcotizaciones"];
		for($li_i=1;$li_i<=$li_totalcotizaciones;$li_i++)
		{	
			$ls_codpro=$_GET["codpro".$li_i];
			$ls_nompro=$_GET["nompro".$li_i];
			$ls_numcot=$_GET["numcot".$li_i];
			if($as_tipsolcot=='B')//Si la solicitud es de Bienes
			{
				$ls_sql= "SELECT a.denart as denominacion, d.montotart as monto
							FROM soc_dtcot_bienes d, siv_articulo a
							WHERE d.codemp='$this->ls_codemp' AND d.numcot='$ls_numcot'
							AND d.cod_pro='$ls_codpro' AND d.codemp=a.codemp AND a.codart=d.codart";					
			}
			elseif($as_tipsolcot=='S') //Si la solicitud es de Servicios
			{
				
				$ls_sql= "SELECT a.denser as denominacion, d.montotser as monto
							FROM soc_dtcot_servicio d, soc_servicios a
							WHERE d.codemp='$this->ls_codemp' AND d.numcot='$ls_numcot'
							AND d.cod_pro='$ls_codpro' AND d.codemp=a.codemp AND a.codser=d.codser";		
			}
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("ERROR->uf_select_items_cotizacion".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;	
			}
			else
			{
				
				while($row=$this->io_sql->fetch_row($rs_data))
				{					
					$aa_items[$row["denominacion"]][$ls_nompro]=$row["monto"];									
				}
			}	
		}	
		
	}  //Fin funcion uf_select_items_cotizacion
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_analisis_cualitativo(&$la_arre2)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items_cotizacion
		//		   Access: public
		//		   return:arreglo con los calificadores de un conjunto de proveedores dados
		//	  Description: Metodo que  devuelve los calificadores de un conjunto de proveedores dados
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 09/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_arre1=array();
		$la_arre2=array();		
		$lb_valido=true;		
		//Tomando los datos del querystring
		$li_totalcotizaciones=$_GET["totalcotizaciones"];
		$ls_proveedores="(";
		$ls_parentesis="";
		for($li_i=1;$li_i<=$li_totalcotizaciones;$li_i++)//Construyendo la consulta sql;
		{	
			$ls_codpro=$_GET["codpro".$li_i];
			$ls_parentesis=$ls_parentesis.")";
			$ls_proveedores=$ls_proveedores."'".$ls_codpro."'";
			if($li_i<$li_totalcotizaciones)
				$ls_proveedores=$ls_proveedores.",";				
		}
		$ls_proveedores=$ls_proveedores.")";
		
		$ls_sql="SELECT DISTINCT codclas FROM rpc_clasifxprov c WHERE cod_pro IN $ls_proveedores
					AND codemp='$this->ls_codemp'  AND status=0 AND codclas IN ";
					
		for($li_i=1;$li_i<=$li_totalcotizaciones;$li_i++)
		{	
			$ls_codpro=$_GET["codpro".$li_i];
			$ls_sql=$ls_sql."(SELECT codclas FROM rpc_clasifxprov  WHERE cod_pro='$ls_codpro' ";
			if($li_i<$li_totalcotizaciones)			
			 $ls_sql=$ls_sql."AND codclas IN ";
		}
		$ls_sql=$ls_sql.$ls_parentesis;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{			
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{					
				$li_i++;
				$la_arre1[$li_i]=$row["codclas"];									
			}
		}
		if(($lb_valido) && ($li_totcalificadores=count($la_arre1))>0)//Si existen calificadores en comun y no ocurrio ningun error, se buscan los valores 
		{																				//de cada calificador por proveedor
				for($li_i=1;$li_i<=$li_totalcotizaciones;$li_i++)
				{
					$ls_codpro=$_GET["codpro".$li_i];
					$ls_nompro=$_GET["nompro".$li_i];
					$la_calificadores=array();
					for($li_j=1; $li_j<=$li_totcalificadores;$li_j++)
					{ 
							$ls_codclas=$la_arre1[$li_j];
							$ls_sql="SELECT c.denclas, cp.nivstatus 
									   FROM rpc_clasifxprov cp, rpc_clasificacion c
									  WHERE c.codemp='$this->ls_codemp' 
										AND cp.cod_pro='$ls_codpro' 
										AND cp.codclas='$ls_codclas' 
										AND c.codemp=cp.codemp 
										AND cp.codclas=c.codclas";
										
							$rs_data=$this->io_sql->select($ls_sql);
							if($rs_data===false)
							{
								$this->io_mensajes->message("ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
								$lb_valido=false;	
							}
							else
							{			
								while($row=$this->io_sql->fetch_row($rs_data))
								{					
									
									switch($row["nivstatus"])
									{
										case "0":
											$la_calificadores[$row["denclas"]]="Ninguno";
										break;
										case "1":
											$la_calificadores[$row["denclas"]] ="Bueno";
										break;
										case "2":
											$la_calificadores[$row["denclas"]] ="Regular";
										break;
										case "3":
											$la_calificadores[$row["denclas"]]="Malo";
										break;
									}
									
								}
							}
					}
					$la_arre2[$ls_nompro]=$la_calificadores;					
				}
		}
	}  //Fin funcion uf_analisis_cualitativo
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_analisis_cualitativo_items(&$aa_items)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_analisis_cualitativo_items 
		//		   Access: public
		//		   return: arreglo con los calificadores de los bienes/servicios por cotizacion
		//	  Description: Metodo que  devuelve los calificadores de los bienes/servicios de una cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 			Fecha: 23/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_items=array();
		$lb_valido=false;		
		//Tomando los datos del querystring
		$as_tipsolcot=$_GET["tipsolcot"];
		$li_totalcotizaciones=$_GET["totalcotizaciones"];
		for($li_i=1;$li_i<=$li_totalcotizaciones;$li_i++)
		{	
			$ls_codpro=$_GET["codpro".$li_i];
			$ls_nompro=$_GET["nompro".$li_i];
			$ls_numcot=$_GET["numcot".$li_i];
			if($as_tipsolcot=='B')//Si la solicitud es de Bienes
			{
				$ls_sql= "SELECT a.denart as denominacion,d.nivcalart AS calificacion
							FROM soc_dtcot_bienes d, siv_articulo a
							WHERE d.codemp='$this->ls_codemp' AND d.numcot='$ls_numcot'
							AND d.cod_pro='$ls_codpro' AND d.codemp=a.codemp AND a.codart=d.codart";					
			}
			elseif($as_tipsolcot=='S') //Si la solicitud es de Servicios
			{
				
				$ls_sql= "SELECT a.denser as denominacion, d.nivcalser AS calificacion
							FROM soc_dtcot_servicio d, soc_servicios a
							WHERE d.codemp='$this->ls_codemp' AND d.numcot='$ls_numcot'
							AND d.cod_pro='$ls_codpro' AND d.codemp=a.codemp AND a.codser=d.codser";		
			}
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("ERROR->uf_analisis_cualitativo_items".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;	
			}
			else
			{
				
				while($row=$this->io_sql->fetch_row($rs_data))
				{					
					switch($row["calificacion"])
					{
						case "E":
							$ls_calificacion="Excelente";
						break;
						case "B":
							$ls_calificacion="Bueno";
						break;
						case "R":
							$ls_calificacion="Regular";
						break;
						case "M":
							$ls_calificacion="Malo";
						break;
						case "P":
							$ls_calificacion="Muy Malo";
						break;
					}
					
					$aa_items[$row["denominacion"]][$ls_nompro]=$ls_calificacion;									
				}
			}	
		}	
		
	}  //Fin funcion uf_analisis_cualitativo_items	
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_proveedores_item($as_tipsolcot, $as_numsolcot,$as_coditem,&$aa_proveedores)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_proveedores_item 
		//		   Access: public
		//		   return: arreglo con los proveedores que cotizaron un determinado bien/servicio
		//	  Description: Metodo que  devuelve los proveedores que cotizaron un determinado bien/servicio
		//	   Creado Por: Ing. Laura Cabré
		// 			Fecha: 28/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_proveedores=array();
		$lb_valido=false;		
		if($as_tipsolcot=='B')//Si la solicitud es de Bienes
		{
			$ls_sql= "SELECT c.numcot , c.cod_pro, r.nompro, d.canart as cantidad, d.preuniart as preciounitario, d.moniva, d.montotart as montototal,
						d.nivcalart as calidad
						FROM rpc_proveedor r, soc_cotizacion c, soc_dtcot_bienes d
						WHERE c.codemp='$this->ls_codemp' 
						  AND c.numsolcot='$as_numsolcot' 
						  AND d.codart='$as_coditem'
						  AND c.codemp=d.codemp 
						  AND d.codemp=r.codemp 
						  AND c.cod_pro=d.cod_pro 
						  AND c.cod_pro=r.cod_pro 
						  AND c.numcot=d.numcot";					
		}
		elseif($as_tipsolcot=='S') //Si la solicitud es de Servicios
		{
			
			$ls_sql= "SELECT c.numcot, c.cod_pro, r.nompro, d.canser as cantidad, d.monuniser as preciounitario, d.moniva, d.montotser as montototal,
						d.nivcalser as calidad 
						FROM rpc_proveedor r, soc_cotizacion c, soc_dtcot_servicio d
						WHERE c.codemp='$this->ls_codemp' 
						  AND c.numsolcot='$as_numsolcot' 
						  AND d.codser='$as_coditem'
						  AND c.codemp=d.codemp 
						  AND d.codemp=r.codemp 
						  AND c.cod_pro=d.cod_pro 
						  AND c.cod_pro=r.cod_pro 
						  AND c.numcot=d.numcot";		
		}
	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_proveedores_item".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=1;
			while($row=$this->io_sql->fetch_row($rs_data))
			{					
				$aa_proveedores[$li_i]=$row;
				$li_i++;									
			}
		}		
		
	}  //Fin funcion uf_proveedores_items
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cotizacion($as_numcot,$as_codpro,$as_numsolcot,&$la_cotizacion,&$la_dt_cotizacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cotizacion
		//		   Access: public
		//	    Arguments: $as_numcot-->Numero de Cotizacion
		//						$as_codpro--->Codigo del Proveedor
		//						$as_numsol--->Numero de Solicitud de Cotizacion
		//		return	:		Arreglo con datos de la cotizacion, arreglo con los bienes/servicios 
		//	  Description: Metodo que  imprime la informacion de una cotizacion en particular
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 28/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cotizacion=array();
		$la_dt_cotizacion=array();
		$lb_valido=false;				
		$ls_sql= "SELECT c.feccot, c.obscot, c.monsubtot, c.monimpcot, c.montotcot, c.diaentcom, c.forpagcom, c.poriva, s.tipsolcot 
					FROM soc_cotizacion c, soc_sol_cotizacion s  
					WHERE c.codemp='$this->ls_codemp' 
					  AND c.numsolcot='$as_numsolcot' 
					  AND c.numcot='$as_numcot' 
					  AND c.cod_pro='$as_codpro' 
					  AND c.codemp=s.codemp 
					  AND c.numsolcot=s.numsolcot";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
			{
				$la_cotizacion=$row;									
				$lb_valido=true;			
			}			
		}
		if($lb_valido)
		{
			$this->uf_select_items($as_numcot,$as_codpro,$row["tipsolcot"],$la_dt_cotizacion);
		}	
	}//fin de uf_select_cotizacion
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cargos($as_coditem,$ls_tipsolcot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cargos
		//		   Access: public
		//	    Arguments: $as_coditem-->codigo del item
		//		return	 : arreglo con los cargos asociados al item
		//	  Description: Metodo que  retorna los cargos asociados al item
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 22/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cargos=array();
		$lb_valido=false;
		if($ls_tipsolcot=="B")
		{				
			$ls_sql= "SELECT s.codart, s.codcar, c.formula,c.codestpro, c.spg_cuenta 
					    FROM siv_cargosarticulo s, tepuy_cargos c
					   WHERE s.codemp='$this->ls_codemp' AND s.codart='$as_coditem' AND s.codemp=c.codemp
					and s.codcar=c.codcar";
		}
		else
		{
			$ls_sql= "SELECT s.codser, s.codcar, c.formula ,c.codestpro, c.spg_cuenta 
					FROM soc_serviciocargo s, tepuy_cargos c
					WHERE s.codemp='$this->ls_codemp' AND s.codser='$as_coditem' AND s.codemp=c.codemp
					and s.codcar=c.codcar";			
		}
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			print $this->io_sql->message;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))//
			{
				$la_cargos=$row;				
			}			
		}
		return $la_cargos;	
	}//fin de uf_select_cotizacion
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuentas_presupuestarias($as_coditem,$ls_tipsolcot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cuentas_presupuestarias
		//		   Access: public
		//	    Arguments: $as_coditem-->codigo del item
		//		return	 : arreglo con las cuentas de gasto asociadas a un item
		//	  Description: Metodo que  retorna  las cuentas de gasto asociadas a un item
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 23/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cuentas=array();
		$lb_valido=false;
		if($ls_tipsolcot=="B")
		{				
			$ls_sql= "SELECT a.spg_cuenta, c.codestpro1, c.codestpro2, c.codestpro3, c.codestpro4, c.codestpro5
					FROM siv_articulo a, spg_cuentas c
					WHERE a.codemp='$this->ls_codemp' 
					  AND a.codart='$as_coditem' 
					  AND a.codemp=c.codemp 
					  AND a.spg_cuenta=c.spg_cuenta";
		}
		else
		{
			$ls_sql= "SELECT a.spg_cuenta, c.codestpro1, c.codestpro2, c.codestpro3, c.codestpro4, c.codestpro5
					    FROM soc_servicios a, spg_cuentas c
					   WHERE a.codemp='$this->ls_codemp' 
						 AND a.codser='$as_coditem' 
					     AND a.codemp=c.codemp 
						 AND a.spg_cuenta=c.spg_cuenta";			
		}
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			print $this->io_sql->message;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))//
			{
				$la_cuentas["spg_cuenta"]=$row["spg_cuenta"];				
				$la_cuentas["programatica"]=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
			}			
		}
		
		return $la_cuentas;	
	}//fin de uf_select_cuentas_presupuestarias
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cotizacion_analisis()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cotizacion_analisis
		//		   Access: public
		//		  return :	arreglo que contiene las cotizaciones que participaron en un determinado analisis 
		//	  Description: Metodo que  devuelve las cotizaciones que participaron en un determinado analisis
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 14/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_proveedores=array();
		$lb_valido=false;
		$ls_numanacot=$_POST["txtnumero"];				
		$ls_sql= "SELECT cxa.numcot, cxa.cod_pro
				  FROM soc_cotxanalisis cxa
				  WHERE cxa.codemp='$this->ls_codemp' AND cxa.numanacot='$ls_numanacot'";		
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_cotizacion_analisis".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
			{
				$li_i++;
				$aa_proveedores[$li_i]=$row;					
			}																
		}
		return $aa_proveedores;
	}//fin de uf_select_cotizacion_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitud_cotizacion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitud_cotizacion
		//		   Access: public
		//		  return :	campo que contiene la solicitud asociada a una cotizacion especifica
		//	  Description: Metodo que  devuelve la solicitud asociada a una cotizacion especifica
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 22/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_numanacot=$_POST["txtnumero"];				
		$ls_sql= "SELECT DISTINCT c.numsolcot
				  FROM soc_cotxanalisis cxa, soc_cotizacion c
				  WHERE cxa.codemp='$this->ls_codemp' AND cxa.numanacot='$ls_numanacot'
          		  AND c.codemp=cxa.codemp AND c.cod_pro=cxa.cod_pro AND c.numcot=cxa.numcot";		
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_solicitud_cotizacion()".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
			{
				$ls_solicitud=$row["numsolcot"];					
			}																
		}
		return $ls_solicitud;
	}//fin de uf_select_solicitud_cotizacion()
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_bienes_servicios($as_coditem,$as_tipo,$as_codpro,$as_numcot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_bienes_servicios
		//		   Access: public
		//		  return :	arreglo que contiene algunos datos basicos que faltan se los bienes/servicios
		//	  Description: Metodo que  devuelve algunos datos basicos que faltan se los bienes/servicios
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 21/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_datos=array();
		$lb_valido=false;
		if($as_tipo=="B")
		{
			$ls_sql= "SELECT a.spg_cuenta, d.unidad 
						FROM siv_articulo a, soc_dtcot_bienes d
						WHERE a.codemp='$this->ls_codemp' 
						  AND a.codart='$as_coditem' 
						  AND d.cod_pro='$as_codpro' 
						  AND d.numcot='$as_numcot' 
						  AND a.codemp=d.codemp
						  AND a.codart=d.codart";				
		}
		else
		{
			$ls_sql= "SELECT a.spg_cuenta
						FROM soc_servicios a, soc_dtcot_servicio d
					   WHERE a.codemp='$this->ls_codemp' 
					     AND a.codser='$as_coditem' 
						 AND d.cod_pro='$as_codpro' 
						 AND d.numcot='$as_numcot' 
						 AND a.codemp=d.codemp
						 AND a.codser=d.codser";	
		}
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_bienes_servicios".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
			{
				$aa_datos["spg_cuenta"]=$row["spg_cuenta"];	
				
				if(array_key_exists("unidad",$row))
					$aa_datos["unidad"]=$row["unidad"];					
			}																
		}
		return $aa_datos;
	}//fin de uf_select_cotizacion_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_items($as_numcot,$as_codpro,$as_tipsolcot,&$aa_items)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items
		//		   Access: public
		//	    Arguments: $as_numcot-->Numero de Cotizacion
		//						$as_codpro--->Codigo del Proveedor
		//						$as_tipsolcot--->Si la cotizacion es de bienes o servicios
		//		return	:		arreglo con los bienes/servicios 
		//	  Description: Metodo que  devuelve los bienes/servicios de una cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 29/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_items=array();
		$lb_valido=false;				
		if($as_tipsolcot=='B')//Si la solicitud es de Bienes
		{
			$ls_sql= "SELECT d.codart as codigo, d.canart as cantidad, d.preuniart as preciouni, d.moniva as iva, d.monsubart as subtotal, d.montotart
						     as total, a.denart as denominacion
						FROM soc_dtcot_bienes d, siv_articulo a
					   WHERE d.codemp='$this->ls_codemp' AND d.numcot='$as_numcot' AND  d.cod_pro='$as_codpro'
						AND d.codemp=a.codemp AND  a.codart=d.codart  
						ORDER BY d.orden";					
		}
		elseif($as_tipsolcot=='S') //Si la solicitud es de Servicios
		{
			
			$ls_sql= "SELECT d.codser as codigo, d.canser as cantidad, d.monuniser as preciouni, d.moniva as iva, d.monsubser as subtotal,
						     d.montotser as total, a.denser as denominacion
						FROM soc_dtcot_servicio d, soc_servicios a 
					   WHERE d.codemp='$this->ls_codemp' 
					     AND d.numcot='$as_numcot'
						 AND d.cod_pro='$as_codpro' 
						 AND d.codemp=a.codemp 
						 AND a.codser=d.codser 
					   ORDER BY d.orden";		
		}
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_select_items  ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;	
			}
			else
			{
				$li_i=0;
				while($row=$this->io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
				{
					$li_i++;
					$aa_items[$li_i][1]=$row["codigo"];
					$aa_items[$li_i][2]="<div align=left>".$row["denominacion"]."</div>";
					$aa_items[$li_i][3]="<div align=right>".number_format($row["cantidad"],2,",",".")."</div>";		
					$aa_items[$li_i][4]="<div align=right>".number_format($row["preciouni"],2,",",".")."</div>";	
					$aa_items[$li_i][5]="<div align=right>".number_format($row["subtotal"],2,",",".")."</div>";
					$aa_items[$li_i][6]="<div align=right>".number_format($row["iva"],2,",",".")."</div>";
					$aa_items[$li_i][7]="<div align=right>".number_format($row["total"],2,",",".")."</div>";		
				}																
			}		
	}  //Fin funcion uf_select_items
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_analisis($aa_seguridad,&$as_numanacot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_analisis
		//		   Access: public
		//		return	:  True si se inserto correctamente
		//   Description: Metodo que guarda la cabecera del analisis de cotizacion
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 03/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$lb_valido=$this->io_id_process->uf_check_id('soc_analisicotizacion','numanacot',"","",$as_numanacot);
		$lb_valido = $this->io_keygen->uf_verificar_numero_generado('SOC','soc_analisicotizacion','numanacot','SOCANA',15,"","","",$as_numanacot);
		$lb_valido=true;
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_fecanacot=$this->io_funciones->uf_convertirdatetobd($_POST["txtfecha"]);
		$ls_estanacot=0;
		$li_totcotizaciones=$_POST["totalcotizaciones"];
		$ls_obsanacot=$_POST["txtobservacion"];
		$ls_tipsolcot=$_POST["txttipsolcot1"];
		$ls_numsolcot=$_POST["txtnumsol1"];
		$ls_sql="INSERT INTO soc_analisicotizacion 
				 (codemp,numanacot,fecanacot,codusu,estana,obsana,tipsolcot,numsolcot)
				 VALUES
				 ('$this->ls_codemp','$as_numanacot','$ls_fecanacot','$ls_codusu',$ls_estanacot,'$ls_obsanacot','$ls_tipsolcot','$ls_numsolcot')";				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062')
			{
			 	$this->uf_insert_analisis($aa_seguridad,$as_numanacot);
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_insert_analisis ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		else
		{						
			if($li_row>0)
			{	
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Análisis de Cotizacion $as_numanacot, asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}		
			
		}
		return $lb_valido;
	}//fin de funcion uf_insert_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_cotizacion_analisis($aa_seguridad,$as_numanacot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cotizacion_analisis
		//		   Access: public
		//		return	: True si se inserto correctamente
		//   Description: Metodo que guarda las cotizaciones asociadas a un analisis de cotizacion determinado
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 03/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_totcotizaciones=$_POST["totalcotizaciones"];
		for($li_i=1;(($li_i<$li_totcotizaciones)&& ($lb_valido));$li_i++)
		{
			$ls_numcot=$_POST["txtnumcot".$li_i];
			$ls_codpro=$_POST["txtcodpro".$li_i];
			$ls_sql="INSERT INTO soc_cotxanalisis
					 (codemp,numanacot,numcot,cod_pro)
					 VALUES
					 ('$this->ls_codemp','$as_numanacot','$ls_numcot','$ls_codpro')";
			
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_insert_cotizacion_analisis ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 

			}
			else
			{						
				if($li_row>0)
				{	
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Se asocio la Cotizacion $ls_numcot, al analisis de cotización $as_numanacot, para la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
			}
		}
		return $lb_valido;
	}//fin de funcion uf_insert_cotizacion_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_items_analisis_cotizacion($aa_seguridad,$as_numanacot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_items_analisis_cotizacion
		//		   Access: public
		//		  return : true o false
		//    Description: Metodo que  inserta el detalle del analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 	        Fecha: 15/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido     = true; 
		$ls_tipsolcot  = $_POST["txttipsolcot1"];
		$li_totalitems = $_POST["totalitems"];
		for($li_i=1;($li_i<=$li_totalitems)&&($lb_valido);$li_i++)
		{
			$ls_codpro=$_POST["txtcodproselec".$li_i];
			if($ls_codpro!="")//Se chequea si se le asigno un proveedor a este item, con la finalidad de permitir que el usuario tenga la libertad de no incluir
								// algunos items dentro del analisis de cotizacion (actualmente esta deshabilitada esta opcion  en una funcion javascript en el guardar)
			{
				$ls_coditem   = $_POST["txtcoditem".$li_i];
				$ls_numcot    = $_POST["txtnumcotsele".$li_i];			
				$ls_obsanacot = $_POST["txtobservacion".$li_i];				
				if($ls_tipsolcot=="B")//En caso de que sean bienes guardo el detalle en la tabla soc_dtac_bienes
				{
					$ls_item="Bien";
					$ls_sql="INSERT INTO soc_dtac_bienes (codemp, numanacot, codart, numcot, cod_pro, estsel, ordpro, obsanacot) VALUES ('".$this->ls_codemp."','".$as_numanacot."','".$ls_coditem."','".$ls_numcot."','".$ls_codpro."',0,".$li_i.",'".$ls_obsanacot."')"; 	
				}
				elseif($ls_tipsolcot=="S")//En caso de que sean servicios guardo el detalle en la tabla soc_dtac_servicios
				{
					$ls_sql="INSERT INTO soc_dtac_servicios
							(codemp, numanacot, codser, numcot, cod_pro, estsel, ordpro, obsanacot)
							VALUES
							('$this->ls_codemp','$as_numanacot','$ls_coditem','$ls_numcot','$ls_codpro',0,$li_i,'$ls_obsanacot')";
					$ls_item="Servicio";
				}
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
				 	$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_insert_items_analisis_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					if($li_row>0)
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="INSERT";
						$ls_descripcion ="Insertó el $ls_item $ls_coditem al Analisis de Cotizacion ".$as_numanacot.
										 ", asociado a la empresa ".$this->ls_codemp;
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					}
				}
			}
		}
		return $lb_valido;
	}//fin funcion uf_insert_items_analisis_cotizacion
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------	
function uf_insert_orden_compra($as_codpro,$ai_total,$ai_totaliva, $ai_subtotal,$aa_seguridad,&$ls_numordcom)
	{/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_insert_orden_compra
	//	    Arguments: as_codpro  --->   Codigo del proveedor al cual se le esta haciendo la orden de compra
	//	      Returns: devuelve true si se inserto correctamente la cabecera de la orden de compra o false en caso contrario
	//	  Description: Funcion que que se encarga dde insertar una orden de compra
	//	   Creado Por: Ing. Laura Cabré
	// Fecha Creación: 20/06/2007 								Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//$lb_valido=$this->io_id_process->uf_check_id('soc_ordencompra','numordcom','estcondat',$as_tipocompra,&$ls_numero); 
	require_once("../shared/class_folder/class_generar_id_process_soc.php");
	$io_id_process_soc= new class_generar_id_process_soc();
	$ls_tipsolcot=$_POST["txttipsolcot1"];
	$ls_numanacot=$_POST["txtnumero"];
	$ls_conordcom=$_POST["txtobservacion"];
	$ls_fecordcom=$this->io_funciones->uf_convertirdatetobd($_POST["txtfecha"]);	
	if($ls_tipsolcot=="B")  
	{		
		$ls_numordcom=$io_id_process_soc->uf_generar_id_process_soc("soc_ordencompra","numordcom","estcondat","B");
		$ld_monsubtotbie=$ai_subtotal;
		$ld_monsubtotser=0;
	}
	else
	{
		$ls_numordcom=$io_id_process_soc->uf_generar_id_process_soc("soc_ordencompra","numordcom","estcondat","S");
		$ld_monsubtotbie=0;
		$ld_monsubtotser=$ai_subtotal;
	}
	$lb_valido=true;
	if($lb_valido)
	{
     	$ld_monsubtotbie = 0;
     	$ld_monsubtotser = 0;
     	$ld_monbasimp = 0;
     	$ld_mondes = 0;
		$li_estpenalm = 0;
		$li_estapro   = 0;
		$ld_fecaprord = "1900-01-01";
		$ls_codusuapr = "";
		$ls_numpolcon = 0;
		$ls_fecent = "1900-01-01";
		$as_rb_rblugcom = 0;
		$as_codmon='---';
		$as_codfuefin='--';
		$as_estcom=0;
		$as_diaplacom=0;
		$as_codtipmod="--";
		$as_coduniadm="---";
		$ai_estsegcom=0;   	
		$ad_porsegcom=0;
		$ad_monsegcom=0;
		$as_forpag="-";
		$as_concom="-";
		$as_conordcom="";
		$ld_mondes=0;
		$as_codpai="---";
		$as_codest="---";
		$as_codmun="---";
		$as_codpar="---";
		$as_lugentnomdep="";
		$as_lugentdir="";
		$ad_antpag=0;
		$ad_tascamordcom=0;
		$ad_montotdiv=0;
		$as_obscom="";
		
		
		
		$ls_sql=" INSERT INTO soc_ordencompra (codemp, numordcom, estcondat, cod_pro, codmon, codfuefin, ".
		        "                              fecordcom, estsegcom, porsegcom, monsegcom, forpagcom, estcom, diaplacom, ".
				"							   concom, obscom, monsubtotbie, monsubtotser, monsubtot, monbasimp, monimp, ".
				"							   mondes, montot, estpenalm, codpai, codest, codmun, codpar, lugentnomdep, ".
				"							   lugentdir, monant, estlugcom, tascamordcom, montotdiv, estapro, fecaprord, ".
				"                              codusuapr, numpolcon, coduniadm, obsordcom, fecent,numanacot,codtipmod) ".
				" VALUES ('".$this->ls_codemp."','".$ls_numordcom."','".$ls_tipsolcot."','".$as_codpro."','".$as_codmon."', ".
				"         '".$as_codfuefin."','".$ls_fecordcom."','".$ai_estsegcom."',".$ad_porsegcom.",".
				"         '".$ad_monsegcom."','".$as_forpag."','".$as_estcom."','".$as_diaplacom."','".$as_concom."', ".
				"         '".$ls_conordcom."',".$ld_monsubtotbie.",".$ld_monsubtotser.",".$ai_subtotal.",".$ld_monbasimp.", ".
				"         ".$ai_totaliva.",".$ld_mondes.",".$ai_total.",".$li_estpenalm.",'".$as_codpai."', ".
				"         '".$as_codest."','".$as_codmun."','".$as_codpar."','".$as_lugentnomdep."','".$as_lugentdir."', ".
				"         ".$ad_antpag.",".$as_rb_rblugcom.",".$ad_tascamordcom.",".$ad_montotdiv.",".$li_estapro.", ".
				"         '".$ld_fecaprord."','".$ls_codusuapr."','".$ls_numpolcon."','".$as_coduniadm."','".$as_obscom."', ".
				"         '".$ls_fecent."','".$ls_numanacot."','$as_codtipmod')";        
		//print $ls_sql."<br>";
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Registro Orden De Compra MÉTODO->uf_insert_orden_compra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Orden de Compra ".$ls_numordcom." tipo ".$ls_tipsolcot." de fecha".$ls_fecordcom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		/*	if($as_estcondat=="B")
			{
				$lb_valido=$this->uf_insert_bienes($ls_numordcom,$as_estcondat,$ai_totrowbienes,$aa_seguridad,$as_tipsol);
			}
			elseif($as_estcondat=="S")
			{
				$lb_valido=$this->uf_insert_servicios($as_numordcom,$as_estcondat,$ai_totrowservicios,$aa_seguridad,$as_tipsol);
			}
	        /*if($lb_valido)
			{
			    $lb_valido=$this->uf_insert_cargos($as_numordcom,$ai_totrowcargos,$as_estcondat,$aa_seguridad);
			}
	        if($lb_valido)
			{
			    $lb_valido=$this->uf_insert_cuentas_presupuestarias($as_numordcom,$as_estcondat,$ai_totrowcuentas,$ai_totrowcuentascargo,$aa_seguridad);
			}
	        if($lb_valido)
			{
				$lb_valido=$this->uf_insert_cuentas_cargos($as_numordcom,$as_estcondat,$ai_totrowcuentascargo,$ai_totrowcargos,$aa_seguridad);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_validar_cuentas($as_numordcom,&$as_estcom,$as_estcondat);
			}
	        if($lb_valido)
			{
			  if($as_tipsol=='SEP')
			  {
			 	 $lb_valido=$this->uf_insert_enlace_sep($as_numordcom,$as_estcondat,$as_estcom,$ai_totrowbienes,$aa_seguridad);
			  }	 
			}
			if($lb_valido)
			{	
				if($as_estcondat=='B')
				{
					$this->io_mensajes->message("La Orden de Compra fue Registrada.");
					$this->io_sql->commit();
				}
				else
				{
					$this->io_mensajes->message("La Orden de Servicio fue Registrada.");
					$this->io_sql->commit();
				}
			}
			else
			{
				if($as_estcondat=='B')
				{
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Registrar la Orden de Compra."); 
					$this->io_sql->rollback();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Registrar la Orden de Servicio."); 
					$this->io_sql->rollback();
				}
				
			}*/
	    }
	}
		return $lb_valido;
	}// fin uf_insert_orden_compra
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_bienes($as_numordcom,$aa_seguridad,$as_items,$as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_bienes
		//		   Access: private
		//	    Arguments: as_numordcom  ---> número de la Orden de Compra
		//                 as_items  ---> listado de indices de items q van a ser guardados
		//	      Returns: true si se insertaron los bienes correctamente o false en caso contrario
		//	  Description: este metodo inserta los bienes de una   orden de compra
		//	   Creado Por: Ing. Laura Cabre
		// Fecha Creación: 21/06/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$la_pos=explode("-",$as_items);
		$li_totalbienes=count($la_pos);		
		for($li_i=0;($li_i<$li_totalbienes)&&($lb_valido);$li_i++)
		{
			$li_pos=$la_pos[$li_i];
			$ls_codart=$_POST["txtcoditem".$li_pos];
			$ls_denart=$_POST["txtnomitem".$li_pos];
			$li_canart=$this->uf_convertir($_POST["txtcanselec".$li_pos]);
			//$ls_unidad=$_POST["cmbunidad".$li];
			$ld_preuniart=$this->uf_convertir($_POST["txtpreuniselec".$li_pos]);
			$ld_monsubart=(($this->uf_convertir($_POST["txtpreuniselec".$li_pos])) * ($this->uf_convertir($_POST["txtcanselec".$li_pos])));
			$ld_montotart=$this->uf_convertir($_POST["txtmonselec".$li_pos]);
			$ld_monimp=$this->uf_convertir($_POST["txtivaselec".$li_pos]);
			$ls_numcot=$_POST["txtnumcotsele".$li_pos];
			$la_data=$this->uf_select_bienes_servicios($ls_codart,"B",$as_codpro,$ls_numcot);
			$ls_unidad=$la_data["unidad"];	
			$ls_numsolord="";	
			
	        $ls_sql=" INSERT INTO soc_dt_bienes (codemp, numordcom, estcondat, codart, unidad, canart, ".
			        "							 penart, preuniart, monsubart, montotart, orden)".
                    "  VALUES ('".$this->ls_codemp."','".$as_numordcom."','B', ".
					"          '".$ls_codart."','".$ls_unidad."',".$li_canart.",0, ".
					"           ".$ld_preuniart.",".$ld_monsubart.",".$ld_montotart.",".$li_i.")";                                                                       
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
			    $this->io_mensajes->message("CLASE->Registro Orden De Compra MÉTODO->uf_insert_bienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó el Articulo ".$ls_codart." a la Orden de Compra  ".$as_numordcom." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			    }
			    $lb_valido=$this->uf_insert_cargos($as_numordcom,"B",$aa_seguridad,$ls_codart,$ld_monsubart,$ld_monimp,$ld_montotart);
			}
		}
		return $lb_valido;
	}// end function uf_insert_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------	
	//--------------------------------------------------------------------------------------------------------------------
	function uf_insert_servicios($as_numordcom,$aa_seguridad,$as_items,$as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_servicios
		//		   Access: private
		//	    Arguments: as_numordcom  ---> número de la Orden de Compra
		//                 as_items  ---> listado de indices de items q van a ser guardados
		//	      Returns: true si se insertaron los bienes correctamente o false en caso contrario
		//	  Description: este metodo inserta los bienes de una   orden de compra
		//	   Creado Por: Ing. Laura Cabre
		// Fecha Creación: 21/06/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$la_pos=explode("-",$as_items);
		$li_totalbienes=count($la_pos);	
		for($li_i=0;($li_i<$li_totalbienes)&&($lb_valido);$li_i++)
		{
			$li_pos=$la_pos[$li_i];
			$ls_codart=$_POST["txtcoditem".$li_pos];
			$ls_denart=$_POST["txtnomitem".$li_pos];
			$li_canart=$this->uf_convertir($_POST["txtcanselec".$li_pos]);
			//$ls_unidad=$_POST["cmbunidad".$li];
			$ld_preuniart=$this->uf_convertir($_POST["txtpreuniselec".$li_pos]);
			$ld_monsubart=(($this->uf_convertir($_POST["txtpreuniselec".$li_pos])) * ($this->uf_convertir($_POST["txtcanselec".$li_pos])));
			$ld_montotart=$this->uf_convertir($_POST["txtmonselec".$li_pos]);
			$ld_monimp=$this->uf_convertir($_POST["txtivaselec".$li_pos]);
			$ls_numcot=$_POST["txtnumcotsele".$li_pos];
			$la_data=$this->uf_select_bienes_servicios($ls_codart,"S",$as_codpro,$ls_numcot);
			//$ls_unidad=$la_data["unidad"];	
			$ls_numsolord="";	
			
	        $ls_sql=" INSERT INTO soc_dt_servicio (codemp, numordcom, estcondat, codser, canser, ".
			        "							 monuniser, monsubser, montotser, orden)".
                    "  VALUES ('".$this->ls_codemp."','".$as_numordcom."','S', ".
					"          '".$ls_codart."',".$li_canart.", ".
					"           ".$ld_preuniart.",".$ld_monsubart.",".$ld_montotart.",".$li_i.")";                                                                       
			
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
			    $this->io_mensajes->message("CLASE->Registro Orden De Compra MÉTODO->uf_insert_servicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó el servicio ".$ls_codart." a la Orden de Compra  ".$as_numordcom." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
					$lb_valido=$this->uf_insert_cargos($as_numordcom,"S",$aa_seguridad,$ls_codart,$ld_monsubart,$ld_monimp,$ld_montotart);	
			    }
			}
		}
		return $lb_valido;
	}// end function uf_insert_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------	
	//--------------------------------------------------------------------------------------------------------------------
	function uf_insert_cargos($as_numordcom,$as_estcondat,$aa_seguridad,$as_coditem,$ad_monbasimp,$as_monimp,$as_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cargos
		//		   Access: private
		//	    Arguments: as_numordcom  ---> número de la orden de compra		
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se insertaron los cargos correctamente o false en caso contrario
		//	  Description: Funcion que inserta los cargos de una Orden de Compra en la tabla segun el tipo de la orden 
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por. Yozelin Barragan 
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 12/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cargos=$this->uf_select_cargos($as_coditem,$as_estcondat);
		$lb_valido=true;
		if(count($la_cargos)>0)
			{
			switch($as_estcondat)
			{
				case "B": // si es de Bienes
					$ls_tabla="soc_dta_cargos";
					$ls_campo="codart";
				break;
				
				case "S": // si es de Servicios
					$ls_tabla="soc_dts_cargos";
					$ls_campo="codser";
				break;
			}	
			$ls_codcar=$la_cargos["codcar"];
			$ls_formulacargo=$la_cargos["formula"];	
	
			$ls_sql="INSERT INTO ".$ls_tabla." (codemp, numordcom, estcondat, ".$ls_campo.", codcar, monbasimp, monimp, monto, formula)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$as_coditem."','".$ls_codcar."',".
					" 			  ".$ad_monbasimp.",".$as_monimp.",".$as_monto.",'".$ls_formulacargo."')";        
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Registro Orden De Compra MÉTODO->uf_insert_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Cargo ".$ls_codcar." a la Orden de Compra ".$as_numordcom. "Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
	
		return $lb_valido;
	}// end function uf_insert_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentas_presupuestarias($as_numordcom,$as_estcondat,$as_items,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentas
		//		   Access: private
		//	    Arguments: as_numordcom  ---> Número de la orden de compra 
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   as_items  	---> items de la orden de compra
		//				   aa_seguridad  ---> arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las cuentas de una Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: Ing. Yozelin Barrgan, Ing. Laura Cabre
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 21/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$la_pos=explode("-",$as_items);
		$li_totalbienes=count($la_pos);
		$this->io_dscuentas->data=array();		
		for($li_i=0;($li_i<$li_totalbienes)&&($lb_valido);$li_i++)
		{
			$li_pos=$la_pos[$li_i];
			$ls_codart=$_POST["txtcoditem".$li_pos];
			$la_cuentas=$this->uf_select_cuentas_presupuestarias($ls_codart,$as_estcondat);
			if(count($la_cuentas)>0)
			{
				$ls_codpro=$la_cuentas["programatica"];
				$ls_cuenta=$la_cuentas["spg_cuenta"];
				$li_moncue=(($this->uf_convertir($_POST["txtpreuniselec".$li_pos])) * ($this->uf_convertir($_POST["txtcanselec".$li_pos])));
				$this->io_dscuentas->insertRow("codestpro",$ls_codpro);	
				$this->io_dscuentas->insertRow("cuenta",$ls_cuenta);			
				$this->io_dscuentas->insertRow("moncue",$li_moncue);
			}			
		}
		//Por cada item se guarda su respectiva cuenta de cargo
			
		for($li_i=0;($li_i<$li_totalbienes)&&($lb_valido);$li_i++)
		{
			$li_pos=$la_pos[$li_i];
			$ls_codart=$_POST["txtcoditem".$li_pos];
			$la_cargos=$this->uf_select_cargos($ls_codart,$as_estcondat);
			if(count($la_cargos)>0)
			{
				$ls_codpro=$la_cargos["codestpro"];
				$ls_cuenta=$la_cargos["spg_cuenta"];
				$li_moncue=$this->uf_convertir($_POST["txtivaselec".$li_pos]);
				$this->io_dscuentas->insertRow("codestpro",$ls_codpro);	
				$this->io_dscuentas->insertRow("cuenta",$ls_cuenta);			
				$this->io_dscuentas->insertRow("moncue",$li_moncue);			
			}
		}
		if(count($this->io_dscuentas->data)>0)
		{
			$this->io_dscuentas->group_by(array('0'=>'codestpro','1'=>'cuenta'),array('0'=>'moncue'),'moncue');
			$li_total=$this->io_dscuentas->getRowCount('codestpro');	
			for($li_fila=1;$li_fila<=$li_total;$li_fila++)
			{
				$ls_codpro=$this->io_dscuentas->getValue('codestpro',$li_fila);
				$ls_cuenta=$this->io_dscuentas->getValue('cuenta',$li_fila);
				$li_moncue=$this->io_dscuentas->getValue('moncue',$li_fila);
				$ls_codestpro1=substr($ls_codpro,0,20);
				$ls_codestpro2=substr($ls_codpro,20,6);
				$ls_codestpro3=substr($ls_codpro,26,3);
				$ls_codestpro4=substr($ls_codpro,29,2);
				$ls_codestpro5=substr($ls_codpro,31,2);
				
				$ls_sql="INSERT INTO soc_cuentagasto (codemp, numordcom, estcondat, codestpro1, codestpro2, codestpro3, codestpro4,  ".
						"							  codestpro5,spg_cuenta, monto)".
						"	  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$ls_codestpro1."','".$ls_codestpro2."',".
						" 			  '".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_cuenta."',".$li_moncue.")";        
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Registro Orden De Compra MÉTODO->uf_insert_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó la Cuenta ".$ls_cuenta." de programatica ".$ls_codpro." a la orden de compra ".$as_numordcom. " Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_cuentas_presupuestarias
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_insert_cuentas_cargos($as_numordcom,$as_estcondat,$as_items,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentas_cargos
		//		   Access: private
		//	    Arguments: as_numordcom  ---> numero de la orden de compra
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   ai_totrowcuentascargo  ---> filas del grid cuentas cargos
		//				   ai_totrowcargos  ---> filas del grid de los creditos
		//				   aa_seguridad  ---> variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: este metodo inserta la cuentas de los cargos asociadas a una orden de compra
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: Ing. Yozelin Barrgan, Ing Laura Cabre
		// Fecha Creación: 24/06/2007 								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$la_pos=explode("-",$as_items);
		$li_totalbienes=count($la_pos);
		$this->io_dscargos->data=array();	
		for($li_i=0;($li_i<$li_totalbienes)&&($lb_valido);$li_i++)
		{
			$li_pos=$la_pos[$li_i];
			$ls_codart=$_POST["txtcoditem".$li_pos];
			$la_cargos=$this->uf_select_cargos($ls_codart,$as_estcondat);			
			if(count($la_cargos))
			{
				$ls_codcar=$la_cargos["codcar"];
				$ld_bascar=(($this->uf_convertir($_POST["txtpreuniselec".$li_pos])) * ($this->uf_convertir($_POST["txtcanselec".$li_pos])));
				$ld_moncar=$this->uf_convertir($_POST["txtivaselec".$li_pos]);
				$ls_formulacargo=$la_cargos["formula"];		
				$ls_codpro=$la_cargos["codestpro"];
				$ls_spg_cuenta=$la_cargos["spg_cuenta"];
				$this->io_dscargos->insertRow("codcar",$ls_codcar);	
				$this->io_dscargos->insertRow("monobjret",$ld_bascar);	
				$this->io_dscargos->insertRow("monret",$ld_moncar);	
				$this->io_dscargos->insertRow("formula",$ls_formulacargo);
				$this->io_dscargos->insertRow("codestpro",$ls_codpro);	
				$this->io_dscargos->insertRow("spg_cuenta",$ls_spg_cuenta);
			}
		}
		$this->io_dscargos->group_by(array('0'=>'codcar'),array('0'=>'monobjret','1'=>'monret'),'monobjret');
		$li_row=$this->io_dscargos->getRowCount("codcar");
		for($li_i=1;($li_i<=$li_row)&&($lb_valido);$li_i++)
		{
			$ls_codcargo=$this->io_dscargos->getValue("codcar",$li_i);
			$ls_codpro=$this->io_dscargos->getValue("codestpro",$li_i);
			$ls_spg_cuenta=$this->io_dscargos->getValue("spg_cuenta",$li_i);
			$ld_moncuecar=$this->io_dscargos->getValue("monret",$li_i);
			$ld_monobjret=$this->io_dscargos->getValue("monobjret",$li_row);
			$ld_monret=$this->io_dscargos->getValue("monret",$li_row);
			$ls_formula=$this->io_dscargos->getValue("formula",$li_row);		
			$ls_codestpro1=substr($ls_codpro,0,20);
			$ls_codestpro2=substr($ls_codpro,20,6);
			$ls_codestpro3=substr($ls_codpro,26,3);
			$ls_codestpro4=substr($ls_codpro,29,2);
			$ls_codestpro5=substr($ls_codpro,31,2);
			$ls_sc_cuenta="";
			$lb_valido=$this->uf_select_cuentacontable($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
													   $ls_spg_cuenta,&$ls_sc_cuenta);
			if($lb_valido)
			{
				$ls_sql="INSERT INTO soc_solicitudcargos (codemp, numordcom,  estcondat, codcar, monobjret, monret, codestpro1, ".
						"                                 codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, sc_cuenta, ".
						"								  formula, monto) ".
						"	  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$ls_codcargo."',".$ld_monobjret.", ".
						"			  ".$ld_monret.",'".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."', ".
						" 			  '".$ls_codestpro4."','".$ls_codestpro5."','".$ls_spg_cuenta."','".$ls_sc_cuenta."','".$ls_formula."', ".
						"			   ".$ld_moncuecar.")";        
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Registro Orden De Compra MÉTODO->uf_insert_cuentas_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó la Cuenta ".$ls_spg_cuenta." de programatica ".$ls_codpro." al cargos ".$ls_codcargo." de la orden de compra  ".$as_numordcom. " Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
			else
			{
				$this->io_mensajes->message("ERROR-> La cuenta Presupuestaria ".$ls_spg_cuenta." No tiene cuenta contable asociada."); 
			}
		}
		return $lb_valido;
	}// end function uf_insert_cuentas_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuentacontable($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,&$as_sccuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cuentacontable
		//		   Access: private
		//	    Arguments: as_codestpro1  --->  Còdigo de Estructura Programàtica
		//	    		   as_codestpro2  --->  Còdigo de Estructura Programàtica
		//	    		   as_codestpro3  --->  Còdigo de Estructura Programàtica
		//	    		   as_codestpro4  --->  Còdigo de Estructura Programàtica
		//	    		   as_codestpro5  --->  Còdigo de Estructura Programàtica
		//	    		   as_spgcuenta   --->  Cuentas Presupuestarias
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que obtiene la cuenta contable 
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_sccuenta="";
		$ls_sql="SELECT sc_cuenta ".
				"  FROM spg_cuentas ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codestpro1='".$as_codestpro1."' ".
				"   AND codestpro2='".$as_codestpro2."' ".
				"   AND codestpro3='".$as_codestpro3."' ".
				"   AND codestpro4='".$as_codestpro4."' ".
				"   AND codestpro5='".$as_codestpro5."' ".
				"   AND spg_cuenta='".$as_spgcuenta."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Registro Orden De Compra MÉTODO->uf_select_cuentacontable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_sccuenta=$row["sc_cuenta"];
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_select_cuentacontable
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	

	function uf_validar_cuentas($as_numordcom,&$as_estcom,$as_estcondat)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_cuentas
		//		   Access: private
		//		 Argument: as_numordcom ---> mumero de la orden de compra
		//				   as_estcom  ---> estatus de la orden de compra
		//                 as_estcondat ---> tipo de la orden de compra bienes o servicios
		//	  Description: Función que busca que las cuentas presupuestarias estén en la programática seleccionada
		//				   de ser asi coloca la sep en emitida sino la coloca en registrada
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 12/05/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, TRIM(spg_cuenta) AS spg_cuenta, monto, ".
				"	    (SELECT (asignado-(comprometido+precomprometido)+aumento-disminucion) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = soc_cuentagasto.codemp ".
				"			AND spg_cuentas.codestpro1 = soc_cuentagasto.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = soc_cuentagasto.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = soc_cuentagasto.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = soc_cuentagasto.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = soc_cuentagasto.codestpro5 ".
				"			AND spg_cuentas.spg_cuenta = soc_cuentagasto.spg_cuenta) AS disponibilidad, ".		
				"		(SELECT COUNT(codemp) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = soc_cuentagasto.codemp ".
				"			AND spg_cuentas.codestpro1 = soc_cuentagasto.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = soc_cuentagasto.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = soc_cuentagasto.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = soc_cuentagasto.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = soc_cuentagasto.codestpro5 ".
				"			AND spg_cuentas.spg_cuenta = soc_cuentagasto.spg_cuenta) AS existe ".		
				"  FROM soc_cuentagasto  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numordcom='".$as_numordcom."' ".
				"   AND estcondat='".$as_estcondat."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Registro Orden De Compra MÉTODO->uf_validar_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$lb_existe=true;
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_existe))
			{
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"];
				$ls_spg_cuenta=$row["spg_cuenta"];
				$li_monto=$row["monto"];
				$li_disponibilidad=$row["disponibilidad"];
				$li_existe=$row["existe"];
				if($li_existe>0)
				{
					if($li_monto>$li_disponibilidad)
					{
						$li_monto=number_format($li_monto,2,",",".");
						$li_disponibilidad=number_format($li_disponibilidad,2,",",".");
						$this->io_mensajes->message("No hay Disponibilidad en la cuenta ".$ls_spg_cuenta." Disponible=[".$li_disponibilidad."] Cuenta=[".$li_monto."]"); 
					}
				}
				else
				{
					$lb_existe = false;
					$this->io_mensajes->message("La cuenta ".$ls_spg_cuenta." No Existe en la Estructura ".$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.""); 
				}
				
			}
			$this->io_sql->free_result($rs_data);	
			if($lb_existe)
			{
				$as_estcom=1; // EMITIDA SE DEBE CAMBIAR EN LETRAS (E)
			}
			else
			{
				$as_estcom=0; // REGISTRO SE DEBE CAMBIAR EN LETRAS (R)
			}
			$ls_sql="UPDATE soc_ordencompra ".
					"   SET estcom='".$as_estcom."' ".
					" WHERE codemp = '".$this->ls_codemp."' AND ".
					"	    numordcom = '".$as_numordcom."' AND ".
					"       estcondat= '".$as_estcondat."'  ";
			//$this->io_sql->begin_transaction();				
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Registro Orden De Compra MÉTODO->uf_validar_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				//$this->io_sql->rollback();
			}
			else
			{
				//$this->io_sql->commit();			
			}
		}
		return $lb_valido;
	}// end function uf_validar_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_cotizacion($ai_estatus,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Funcion: uf_update_estatus_cotizacion
		//		   Acceso: public
		//     Parametros: $ai_estatus-->0 libera, 1 asocia
		//		  return : true o false
		//    Description: Metodo que libera o asocia las cotizaciones a los analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 	        Fecha: 14/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$la_cotizaciones=$this->uf_select_cotizacion_analisis();
		$li_totalcotizaciones=count($la_cotizaciones);
		for($li_i=1;(($li_i<=$li_totalcotizaciones) &&($lb_valido));$li_i++)
		{
			$ls_codpro=$la_cotizaciones[$li_i]["cod_pro"];
			$ls_numcot=$la_cotizaciones[$li_i]["numcot"];
			$ls_sql="UPDATE soc_cotizacion 
						SET estcot=$ai_estatus
						WHERE codemp='$this->ls_codemp'
						AND cod_pro='$ls_codpro'
						AND numcot='$ls_numcot'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->f_update_estatus_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				if($li_row>0)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizo es estatus de la cotizacion ".$ls_numcot."  al valor $ai_estatus, Asociado a la empresa $this->ls_codemp";
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
		}	
		return $lb_valido;				
	}//fin de uf_update_estatus_cotizacion
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------	
function uf_update_estatus_solicitud_cotizacion($ai_estatus,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Funcion: uf_update_estatus_solicitud_cotizacion
		//		   Acceso: public
		//     Parametros: $ai_estatus-->R libera, P asocia
		//		  return : true o false
		//    Description: Metodo que libera o asocia las solicitudes de cotizaciones a los analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 	        Fecha: 23/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_numsolcot=$this->uf_select_solicitud_cotizacion();
		$ls_sql="UPDATE soc_sol_cotizacion 
				SET estcot='$ai_estatus'
				WHERE codemp='$this->ls_codemp'
				AND numsolcot='$ls_numsolcot'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_update_estatus_solicitud_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($li_row>0)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo es estatus del analisis de cotizacion ".$ls_numsolcot."  al valor $ai_estatus, Asociado a la empresa $this->ls_codemp";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}	
		return $lb_valido;				
	}//fin de uf_update_estatus_solicitud_cotizacion
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------	
	function uf_existen_ordenes_compra()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_existen_ordenes_compra
		//		   Access: public
		//		  return : true si el analisis tiene asociada al menos una orden de compra
		//    Description: Metodo que  detecta si un analisis de cotizaciones tiene asociada al menos una orden de compra
		//	   Creado Por: Ing. Laura Cabré
		// 	        Fecha: 22/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cargos=array();
		$lb_valido=false;
		$ls_numanacot=$_POST["txtnumero"];
		$ls_tipsolcot=$_POST["txttipsolcot1"];
		$ls_sql= "SELECT numanacot 
				 FROM soc_ordencompra 
				 WHERE codemp='$this->ls_codemp' AND numanacot='$ls_numanacot' AND estcondat='$ls_tipsolcot'";
		
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_existen_ordenes_compra".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			print $this->io_sql->message;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))//
			{
				$lb_valido=true;				
			}		
		}
		return $lb_valido;
	}//fin de uf_existen_ordenes_compra
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------	
	function uf_estado_analisis()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_estado_analisis
		//		   Access: public
		//		  return : estado del analisis de cotizacion
		//    Description: Metodo que  retorna el estado del analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 	        Fecha: 12/08/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cargos=array();
		$lb_valido=false;
		$ls_numanacot=$_POST["txtnumero"];
		$ls_sql= "SELECT estana 
				 FROM soc_analisicotizacion
				 WHERE codemp='$this->ls_codemp' AND numanacot='$ls_numanacot'";
		
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_estado_analisis".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			print $this->io_sql->message;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))//
			{
				$ls_estado=$row["estana"];				
			}		
		}
		return $ls_estado;
	}//fin de uf_estado_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_update_analisis($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_analisis
		//		   Access: public
		// 		   return: True si se actualizo correctamente
		//    Description: Metodo que actualiza la cabecera del analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 	        Fecha: 13/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_numanacot=$_POST["txtnumero"];
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_fecanacot=$this->io_funciones->uf_convertirdatetobd($_POST["txtfecha"]);
		$ls_estanacot=0;
		$li_totcotizaciones=$_POST["totalcotizaciones"];
		$ls_obsanacot=$_POST["txtobservacion"];
		$ls_tipsolcot=$_POST["txttipsolcot1"];
		$ls_numsolcot=$_POST["txtnumsol1"];
		$ls_sql="UPDATE soc_analisicotizacion 
				 SET fecanacot='$ls_fecanacot',codusu='$ls_codusu',estana=$ls_estanacot,obsana='$ls_obsanacot',tipsolcot='$ls_tipsolcot',numsolcot='$ls_numsolcot'
				 WHERE 
				 codemp='$this->ls_codemp' AND numanacot='$ls_numanacot'";				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_update_analisis ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{						
			if($li_row>0)
			{	
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó el Análisis de Cotizacion ".$ls_numanacot.", asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}		
			
		}
		return $lb_valido;
	}//fin de funcion uf_update_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------	
	function uf_update_cotizacion_analisis($aa_seguridad,$as_numanacot)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_cotizacion_analisis
		//		   Access: public
		//		  return : true o false
		//   Description: Metodo que  actualiza la asociacion de las cotizaciones a un  analisis de cotizacion determinado
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 13/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->uf_delete_cotizacion_analisis($aa_seguridad);
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_cotizacion_analisis($aa_seguridad,$as_numanacot);
		}
		return $lb_valido;		
	}//uf_update_cotizacion_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------	
	function uf_update_items_analisis_cotizacion($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_items_analisis_cotizacion
		//		   Access: public
		//		  return : true o false
		//   Description: Metodo que  actualiza el detalle del analisis de cotizacion
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 11/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_tipsolcot=$_POST["txttipsolcot1"];
		$ls_numanacot=$_POST["txtnumero"];
		$li_totalitems=$_POST["totalitems"];
		for($li_i=1;($li_i<=$li_totalitems)&&($lb_valido);$li_i++)
		{
			$ls_codpro=$_POST["txtcodproselec".$li_i];
			if($ls_codpro!="")//Se chequea si se le asigno un proveedor a este item, con la finalidad de permitir que el usuario tenga la libertad de no incluir
								// algunos items dentro del analisis de cotizacion (actualmente esta deshabilitada esta opcion  en una funcion javascript en el guardar)
			{
				$ls_coditem=$_POST["txtcoditem".$li_i];
				$ls_numcot=$_POST["txtnumcotsele".$li_i];			
				$ls_obsanacot=$_POST["txtobservacion".$li_i];
				
				if($ls_tipsolcot=="B")//En caso de que sean bienes actualizo el detalle en la tabla soc_dtac_bienes
				{
					$ls_sql="UPDATE soc_dtac_bienes
							SET numcot='$ls_numcot', cod_pro='$ls_codpro', estsel=0, ordpro=$li_i, obsanacot='$ls_obsanacot'
							WHERE
							codemp='$this->ls_codemp' AND numanacot='$ls_numanacot' AND codart='$ls_coditem'"; 	
					$ls_item="Bien";
				}
				elseif($ls_tipsolcot=="S")//En caso de que sean servicios actualizo el detalle en la tabla soc_dtac_servicios
				{
					$ls_sql="UPDATE soc_dtac_servicios
							SET numcot='$ls_numcot', cod_pro='$ls_codpro', estsel=0, ordpro=$li_i, obsanacot='$ls_obsanacot'
							WHERE
							codemp='$this->ls_codemp' AND numanacot='$ls_numanacot' AND codser='$ls_coditem'"; 	
					$ls_item="Servicio";
				}
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_update_items_analisis_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					if($li_row>0)
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="UPDATE";
						$ls_descripcion ="Actualizo el $ls_item ".$ls_coditem." al Analisis de Cotizacion ".$ls_numanacot.
										 " Asociado a la empresa ".$this->ls_codemp;
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					}
				}
			}
		}
		return $lb_valido;
	}//fin funcion uf_update_items_analisis_cotizacion
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------	
	function uf_delete_analisis($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_analisis
		//		   Access: public
		//		return	:  True si se elimino correctamente
		//   Description: Metodo que elimina la cabecera del analisis de cotizacion
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 03/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_numanacot=$_POST["txtnumero"];		
		$ls_sql="DELETE FROM soc_analisicotizacion 
				 WHERE codemp='$this->ls_codemp' AND numanacot='$ls_numanacot'";				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_delete_analisis ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{						
			if($li_row>0)
			{	
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Elimino el Análisis de Cotizacion ".$ls_numanacot.", asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}		
			
		}
		return $lb_valido;
	}//fin de funcion uf_delete_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------	
	function uf_delete_cotizacion_analisis($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_cotizacion_analisis
		//		   Access: public
		//		return	:  True si se elimino correctamente
		//   Description: Metodo que elimina las cotizaciones asociadas a un analisis de cotizacion determinado
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 03/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_numanacot=$_POST["txtnumero"];		
		$ls_sql="DELETE FROM soc_cotxanalisis
				 WHERE
				 codemp='$this->ls_codemp' AND numanacot='$ls_numanacot'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_delete_cotizacion_analisis ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{						
			if($li_row>0)
			{	
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Se elimino la asociacion de todas las cotizaciones asociadas al analisis $ls_numanacot, para la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}

		}	
		return $lb_valido;
	}//fin de funcion uf_delete_cotizacion_analisis
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------	
	function uf_delete_items_analisis_cotizacion($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_items_analisis_cotizacion
		//		   Access: public
		//		  return : true o false
		//   Description: Metodo que  elimina el detalle del analisis de cotizacion
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 015/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_tipsolcot=$_POST["txttipsolcot1"];
		$ls_numanacot=$_POST["txtnumero"];
		if($ls_tipsolcot=="B")//En caso de que sean bienes guardo el detalle en la tabla soc_dtac_bienes
		{
			$ls_sql="DELETE FROM soc_dtac_bienes
					WHERE codemp='$this->ls_codemp' AND numanacot='$ls_numanacot'"; 	
			$ls_item="Bienes";
		}
		elseif($ls_tipsolcot=="S")//En caso de que sean servicios guardo el detalle en la tabla soc_dtac_servicios
		{
			$ls_sql="DELETE FROM soc_dtac_servicios
					WHERE codemp='$this->ls_codemp' AND numanacot='$ls_numanacot'";
			$ls_item="Servicios";
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Analisis Cotizacion MÉTODO->uf_delete_items_analisis_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Elimino  $ls_item asociados al Analisis de Cotizacion $ls_numanacot de la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}			
		return $lb_valido;
	}//fin funcion uf_delete_items_analisis_cotizacion
	function uf_obtener_ganadores()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_ganadores
		//		   Access: public
		//		  return : arreglo que coniene los proveedores ganadores con los items en los cuales gano
		//   Description: Metodo que  agrupa los proveedores ganadores con sus respectivos items
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 20/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_totalcotizaciones=$_POST["totalitems"];
		$la_cotizaciones=array();
		$la_cotizacionessinrepetidos=array();
		$la_ganadores=array();
		for($li_i=1;$li_i<=$li_totalcotizaciones;$li_i++)
		{
			$la_cotizaciones[$li_i]=$_POST["txtcodproselec".$li_i];
		}		
		$la_cotizacionessinrepetidos=array_unique($la_cotizaciones);//Se eliminan los repetidos
		$la_cotizacionessinrepetidos=array_merge($la_cotizacionessinrepetidos);	//se reordena la matriz
		$li_totcotnorepetidas=count($la_cotizacionessinrepetidos);
		for($li_i=0;$li_i<$li_totcotnorepetidas;$li_i++)
		{
		 	$ls_proveedor=$la_cotizacionessinrepetidos[$li_i];
			$ls_pos="";
		 	$li_subtotal=0;
		 	$li_totaliva=0;
		 	$li_total=0;
			for($li_j=1;$li_j<=$li_totalcotizaciones;$li_j++)
		 	{
				if($ls_proveedor==$la_cotizaciones[$li_j])
				{
					$li_subtotal+=(($this->uf_convertir($_POST["txtpreuniselec".$li_j])) * ($this->uf_convertir($_POST["txtcanselec".$li_j])));
					$li_totaliva+=$this->uf_convertir($_POST["txtivaselec".$li_j]);
					if($ls_pos!="")
						$ls_pos.="-";
					$ls_pos.=$li_j;
								
				}				
			}
			$li_total=$li_totaliva+$li_subtotal;
			$la_ganadores[$li_i]["proveedor"]=$ls_proveedor;
			$la_ganadores[$li_i]["pos"]=$ls_pos;		 
			$la_ganadores[$li_i]["subtotal"]=$li_subtotal;
			$la_ganadores[$li_i]["totaliva"]=$li_totaliva;
			$la_ganadores[$li_i]["total"]=$li_total;
		}
	
		return $la_ganadores;
		
		
	}//fin de uf_obtener_ganadores
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_update(&$ls_numanacot,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_update
		//		   Access: public
		//		  return : true o false
		//   Description: Metodo que  inserta o actualiza el analisis de cotizacion
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 09/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_catalogo=$_POST["catalogo"];
		$lb_valido=false;
		if($ls_catalogo=="T")// En caso de que el analisis venga de un catalogo, se hace update 
		{
			if($this->uf_estado_analisis() != 1)//Si no ha sido aprobada
			{
				$this->io_sql->begin_transaction();
				$lb_valido=$this->uf_update_estatus_solicitud_cotizacion("R",$aa_seguridad);
				if($lb_valido)
				{
					$lb_valido=$this->uf_update_estatus_cotizacion(0,$aa_seguridad);
				}
				if($lb_valido)
				{
					$lb_valido=	$this->uf_update_analisis($aa_seguridad);	
				}				
				if($lb_valido)
				{
					$ls_numanacot=$_POST["txtnumero"];
					$lb_valido=$this->uf_update_cotizacion_analisis($aa_seguridad,$ls_numanacot);
				}
				if($lb_valido)
				{
					$lb_valido=$this->uf_update_items_analisis_cotizacion($aa_seguridad);
				}
				if($lb_valido)
				{
					$lb_valido=$this->uf_update_estatus_solicitud_cotizacion("P",$aa_seguridad);
				}
				if($lb_valido)
				{
					$lb_valido=$this->uf_update_estatus_cotizacion(1,$aa_seguridad);
				}

				if($lb_valido)
				{
					$this->io_sql->commit();
					$this->io_mensajes->message("El Análisis de Cotizaciones fue actualizado");
				}
				else
				{
					$this->io_sql->rollback();
					$this->io_mensajes->message("El Análisis de Cotizaciones no pudo ser actualizado");
				}
			}
			else
			{
				$this->io_mensajes->message("El Análisis de Cotización no puede ser modificado ya que fue Aprobado");
			}
		}
		else //En caso de que sea un nuevo analisis, se inserta
		{
			$this->io_sql->begin_transaction();
			$ls_numanacot = $_POST["txtnumero"];
			$ls_numsolaux = $ls_numanacot;
			$lb_valido    =	$this->uf_insert_analisis($aa_seguridad,$ls_numanacot);
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_cotizacion_analisis($aa_seguridad,$ls_numanacot);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_items_analisis_cotizacion($aa_seguridad,$ls_numanacot);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_update_estatus_solicitud_cotizacion("P",$aa_seguridad);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_update_estatus_cotizacion(1,$aa_seguridad);
			}
			
			if($lb_valido)
			{
				if($ls_numsolaux!=$ls_numanacot)
				{
					$this->io_mensajes->message("Se Asigno el Numero al Análisis de Cotización: ".$ls_numanacot);
				}
				$this->io_sql->commit();
				$this->io_mensajes->message("El Análisis de Cotizaciones fue registrado");
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_mensajes->message("El Análisis de Cotizaciones no pudo ser registrado");
			}
		}
		//return true;
		return $lb_valido;
	}// fin de uf_insert_update
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_delete($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete
		//		   Access: public
		//		  return : true o false
		//   Description: Metodo que  elimina el analisis de cotizacion
		//	  Creado Por: Ing. Laura Cabré
		// 	       Fecha: 13/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//if(true)
		if($this->uf_estado_analisis() != 1)//Si no ha sido aprobada
		{
			$this->io_sql->begin_transaction();
			$lb_valido=$this->uf_update_estatus_solicitud_cotizacion("R",$aa_seguridad);
			if($lb_valido)
			{
				$lb_valido=$this->uf_update_estatus_cotizacion(0,$aa_seguridad);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_delete_items_analisis_cotizacion($aa_seguridad);
			}			
			if($lb_valido)
			{
				$lb_valido=$this->uf_delete_cotizacion_analisis($aa_seguridad);
			}
			if($lb_valido)
			{
				$lb_valido=	$this->uf_delete_analisis($aa_seguridad);				
			}
						
			if($lb_valido)
			{
				$this->io_sql->commit();
				$this->io_mensajes->message("El Análisis de Cotizaciones fue eliminado");
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_mensajes->message("El Análisis de Cotizaciones no pudo ser eliminado");
			}
		}
		else
		{
			$this->io_mensajes->message("El Análisis de Cotización no puede ser eliminado ya que fue Aprobado");
			$lb_valido=false;
		}		
		return $lb_valido;
	}// fin de uf_delete
	function uf_convertir($ls_numero)
	{
		$ls_numero=str_replace(".","",$ls_numero);
		$ls_numero=str_replace(",",".",$ls_numero);
		return $ls_numero;
	}	
}
?>