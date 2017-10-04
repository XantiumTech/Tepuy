<?php
require_once("../shared/class_folder/class_sql.php");
class tepuy_sfa_c_factura
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function tepuy_sfa_c_factura()
	{
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones_db.php");
		require_once("../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
		require_once("../shared/class_folder/class_funciones.php");
		
		$in=               new tepuy_include();
		$this->con=        $in->uf_conectar();
		$this->io_sql=     new class_sql($this->con);
		$this->seguridad=  new tepuy_c_seguridad();
		$this->fun=        new class_funciones_db($this->con);
		$this->io_msg =    new class_mensajes();
		$this->DS=         new class_datastore();
		$this->io_funcion= new class_funciones();
		$this->io_rcbsf=   new tepuy_c_reconvertir_monedabsf();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	}

	function uf_siv_select_recepcion($as_codemp,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_recepcion
		//         Access: public (tepuy_siv_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica que exista una entrada de suministo a almacen en la tabla de  siv_recepcion
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM siv_recepcion  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND numordcom='".$as_numordcom."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_siv_select_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function uf_siv_select_recepcion

	function uf_siv_insert_recepcion($as_codemp,$as_numordcom,$as_codpro,$as_codalm,$ad_fecrec,$as_obsrec,$as_codusu,$as_estpro,$as_estrec,&$as_numconrec,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_recepcion
		//         Access: public (tepuy_siv_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa               $as_numordcom // numero de la orden de compra/factura
		// 				   $as_codpro    // codigo de proveedor			    $as_codalm    // codigo de almacen
		//				   $ad_fecrec    // fecha de recepcion              $as_obsrec    // observacion de la recepcion
		//				   $as_codusu    // codigo del usuario	 			$aa_seguridad // arreglo de registro de seguridad
		//				   $as_estpro    // estatus de la procedencia: 0--> Factura, 1--> Orden de compra
		//				   $as_estrec    // estatus de la recepcion:   0--> Parcial, 1--> Completa
		//				   $as_numconrec // comprobante (numero concecutivo para hacer unica la recepcion)
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que inserta  los  datos  maestros  de  una  entrada  de  suministros a almacen  y genera
		//				   el numero  de  comprobante  de  la  recepcion  de manera que puedan existir varias recepciones para una
		//				   misma orden de compra, en la tabla siv_recepcion
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$io_fun=  new class_funciones_db($this->con);
		//$ls_emp="";
		//$ls_tabla="siv_recepcion";
		//$ls_columna="numconrec";
		$ls_estrevrec="1";
		//$as_numconrec=$io_fun->retonar_ultimo($ls_tabla,$ls_columna);
		//$as_numconrec=$io_fun->uf_generar_codigo($ls_emp,$as_codemp,$ls_tabla,$ls_columna);
		$ls_sql="INSERT INTO siv_recepcion (codemp,numordcom,cod_pro,codalm,fecrec,obsrec,codusu,estpro,estrec,numconrec,estrevrec)".
				" VALUES ('".$as_codemp."','".$as_numordcom."','".$as_codpro."','".$as_codalm."','".$ad_fecrec."','".$as_obsrec."',".
				"         '".$as_codusu."','".$as_estpro."','".$as_estrec."','".$as_numconrec."','".$ls_estrevrec."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_siv_insert_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insert� una Entrada de Suminisros a almacen proveniente del Documento ".$as_numordcom.", y fue enviado al Almacen ".$as_codalm.
								 " Asociado a la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		if(($lb_valido)&&($as_estrec==1)&&($as_estpro==0))
		{
			$lb_valido=$this->uf_siv_update_ordencompra($as_codemp,$as_numordcom,$aa_seguridad);
		}
		return $lb_valido;
	} // end function uf_siv_insert_recepcion

	/*function uf_siv_select_dt_recepcion($as_codemp,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_dt_recepcion
		//         Access: public (tepuy_siv_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe detalles asociados a un maestro de recepcion de suministros a almacen
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * FROM siv_dt_recepcion".
				" WHERE codemp='". $as_codemp ."'".
				"   AND numordcom NOT IN (SELECT numordcom FROM siv_recepcion WHERE estrevrec='0')".
				"   AND numordcom='". $as_numordcom ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_siv_select_dt_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{ 
				$lb_valido=false; 
			}
		}
		return $lb_valido;
	} // end function uf_siv_select_dt_recepcion*/
	
	function uf_siv_select_dt_recepcion($as_codemp,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_dt_recepcion
		//         Access: public (tepuy_siv_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe detalles asociados a un maestro de recepcion de suministros a almacen
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_vali=1; 
		$ls_sql="SELECT siv_dt_recepcion.*,siv_recepcion.numordcom,siv_recepcion.numconrec FROM siv_dt_recepcion,siv_recepcion".
				" WHERE siv_dt_recepcion.codemp='". $as_codemp ."'".
				"   AND siv_dt_recepcion.codemp=siv_recepcion.codemp".
				"   AND siv_dt_recepcion.numordcom=siv_recepcion.numordcom".
				"   AND siv_dt_recepcion.numconrec=siv_recepcion.numconrec".
				"   AND siv_recepcion.estrevrec='1'".
				"   AND siv_dt_recepcion. numordcom='". $as_numordcom ."'";
		//print $ls_sql."<br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_siv_select_dt_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$lb_vali=0;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{ 
				$lb_valido=true;
				$lb_vali=1;
				$as_numconrec=$row["numconrec"]; 
				$as_canart=$row["canart"]; 
				$this->io_sql->free_result($rs_data);
			}
			else
			{  
				$lb_valido=false; 
				$lb_vali=0;
			}
		} 
		return $lb_vali;
	} // end function uf_siv_select_dt_recepcion
	
	function uf_siv_insert_dt_recepcion($as_codemp,$as_numordcom,$as_codart,$as_unidad,$ai_canart,$ai_penart,
	                                    $ai_preuniart,$ai_monsubart,$ai_montotart,$ai_orden,$ai_canoriart,
										$as_numconrec,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_dt_recepcion
		//         Access: public (tepuy_siv_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa               $as_numordcom // numero de la orden de compra/factura
		// 				   $as_codart    // codigo de articulo			    $as_unidad    // codigo de unidad M-->Mayor D->Detal
		//				   $ai_canart    // cantidad recibida de articulos  $ai_penart    // cantidad pendiente de articulos por recibir
		//				   $ai_preuniart // precio unitario del articulo	$ai_monsubart // monto sub-total por articulo
		//				   $ai_montotart // monto total de articulo			$ai_orden     // orden consecutivo de registro
		//				   $as_estrec    // estatus de la recepcion:   0--> Parcial, 1--> Completa
		//				   $ai_canoriart // codigo de procedencia del documento
		//				   $as_numconrec // comprobante (numero concecutivo para hacer unica la recepcion)
		//				   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un detalle de recepcion de articulos a almacen sociado a su respectivo
		//				   maestro en la tabla de  siv_dt_recepcion
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
			$ls_sql="INSERT INTO siv_dt_recepcion (codemp,numordcom,codart,unidad,canart,penart,preuniart,monsubart,montotart,".
					"                              orden,canoriart,numconrec)".
					" VALUES ('".$as_codemp."','".$as_numordcom."','".$as_codart."','".$as_unidad."','".$ai_canart."',".
					"         '".$ai_penart."','".$ai_preuniart."','".$ai_monsubart."','".$ai_montotart."','".$ai_orden."',".
					"         '".$ai_canoriart."','".$as_numconrec."')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->recepcion M�TODO->uf_siv_insert_dt_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Permiti� una entrada a ".$ai_canart." Articulos ".$as_codart." Asociado a la Orden de Compra ".$as_numordcom." de la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_siv_update_ultimocosto($as_codemp,$as_codart,$ai_preuniart,$aa_seguridad);
				if($lb_valido)
				{
					$lb_valido=$this->uf_siv_actualizar_costo_promedio($as_codemp,$as_codart,$aa_seguridad);
					if($lb_valido)
					{
						/*$this->io_rcbsf->io_ds_datos->insertRow("campo","preuniartaux");
						$this->io_rcbsf->io_ds_datos->insertRow("monto",$ai_preuniart);
			
						$this->io_rcbsf->io_ds_datos->insertRow("campo","monsubartaux");
						$this->io_rcbsf->io_ds_datos->insertRow("monto",$ai_monsubart);
						
						$this->io_rcbsf->io_ds_datos->insertRow("campo","montotartaux");
						$this->io_rcbsf->io_ds_datos->insertRow("monto",$ai_montotart);
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numordcom");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numordcom);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numconrec");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_numconrec);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
						$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codart);
						$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
						
						$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("siv_dt_recepcion",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);*/
					    $lb_valido=true;
					}
				}
			}
		    return $lb_valido;
	}  // end   function uf_siv_insert_dt_recepcion

	function uf_siv_obtener_dt_pendiente($as_codemp,$as_numordcom,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_obtener_dt_pendiente
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los articulos asociados a una orden de compra ordenados por el campo "orden" en la
		//				   tabla de soc_dt_bienes, y por articulo busca en la tabla siv_dt_recepcion los pendientes asociados a esos 
		//				   articulos para luego imprimirlos en el grid de la pagina exepto aquellos que ya se recibieron por completo.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "SELECT soc_dt_bienes.codemp,soc_dt_bienes.numordcom,soc_dt_bienes.estcondat,soc_dt_bienes.codart,soc_dt_bienes.unidad,".
		        "   SUM(soc_dt_bienes.canart)as canart ,SUM(soc_dt_bienes.monsubart) as monsubart,".
				"   SUM(soc_dt_bienes.montotart) as montotart,soc_dt_bienes.penart,soc_dt_bienes.preuniart,soc_dt_bienes.orden".
				"   FROM soc_dt_bienes".
				 " WHERE codemp='". $as_codemp ."'".
				 "   AND numordcom='". $as_numordcom ."'".
				 " GROUP BY soc_dt_bienes.codemp,soc_dt_bienes.numordcom,soc_dt_bienes.estcondat,soc_dt_bienes.codart,soc_dt_bienes.unidad".
				 //soc_dt_bienes.penart,soc_dt_bienes.preuniart,soc_dt_bienes.orden".
				 " ORDER BY orden"; 

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$lb_valido=false;
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_siv_obtener_dt_pendiente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{ 
			$ls_gestor=$_SESSION["ls_gestor"];
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			 { 
				$ls_codart=$row["codart"]; 
				
				if($ls_gestor=="ORACLE")
				 {
				    $ls_sql= "SELECT siv_dt_recepcion.*,siv_articulo.codunimed,".
							  "     (SELECT unidad FROM siv_unidadmedida ".
							  "	      WHERE siv_unidadmedida.codunimed = siv_articulo.codunimed) AS unidades,".
							  "     (SELECT denart FROM siv_articulo".
							  "       WHERE siv_dt_recepcion.codart=siv_articulo.codart) AS denart".
							  "  FROM siv_dt_recepcion, siv_recepcion,siv_articulo".
							  " WHERE  siv_dt_recepcion.codemp=siv_recepcion.codemp".
							  "   AND siv_dt_recepcion.codart=siv_articulo.codart".
							  "   AND siv_dt_recepcion.numordcom=siv_recepcion.numordcom".
							  "   AND siv_dt_recepcion.numconrec=siv_recepcion.numconrec ".
							  "   AND siv_dt_recepcion.codemp='". $as_codemp ."'".
							  "   AND siv_dt_recepcion.numordcom='". $as_numordcom ."'".
							  "   AND siv_recepcion.estrec=0".
							  "   AND siv_dt_recepcion.codart='". $ls_codart ."'".
		    				  " ORDER BY siv_dt_recepcion.numconrec DESC ROWNUM <= 1";
				}
				else
				{
					$ls_sql= "SELECT siv_dt_recepcion.*,siv_articulo.codunimed,".
							  "     (SELECT unidad FROM siv_unidadmedida ".
							  "	      WHERE siv_unidadmedida.codunimed = siv_articulo.codunimed) AS unidades,".
							  "     (SELECT denart FROM siv_articulo".
							  "       WHERE siv_dt_recepcion.codart=siv_articulo.codart) AS denart".
							  "  FROM siv_dt_recepcion, siv_recepcion,siv_articulo".
							  " WHERE  siv_dt_recepcion.codemp=siv_recepcion.codemp".
							  "   AND siv_dt_recepcion.codart=siv_articulo.codart".
							  "   AND siv_dt_recepcion.numordcom=siv_recepcion.numordcom".
							  "   AND siv_dt_recepcion.numconrec=siv_recepcion.numconrec ".
							  "   AND siv_dt_recepcion.codemp='". $as_codemp ."'".
							  "   AND siv_dt_recepcion.numordcom='". $as_numordcom ."'".
							  "   AND siv_recepcion.estrec=0".
							  "   AND siv_dt_recepcion.codart='". $ls_codart ."'".
							  " ORDER BY siv_dt_recepcion.numconrec DESC LIMIT  1"; 
				}
				
				$rs_data1=$this->io_sql->select($ls_sql);
				if($rs_data1===false)
				{
					$lb_valido=false;
					$this->io_msg->message("CLASE->recepcion M�TODO->uf_siv_obtener_dt_pendiente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				}
				else
				{
					while($row=$this->io_sql->fetch_row($rs_data1))
					{  
					    $ls_codart=    $row["codart"];
						$ls_denart=    $row["denart"];
						$ls_canart=    $row["canart"];
						$ls_unidad=    $row["unidad"];
						$li_unidad=    $row["unidades"];
						$li_preuniart= $row["preuniart"];
						//$li_penart=    $row["penart"];
						$li_canoriart= $row["canoriart"];
						$li_canart=    "";
						$li_montotart= "";
						switch ($ls_unidad) 
						{
							case "M":
								$ls_unidadaux="Mayor";
								break;
							case "D":
								$ls_unidadaux="Detal";
								break;
						}
					    $ls_sql=" select siv_dt_recepcion.codart,siv_dt_recepcion.numconrec,sum(siv_dt_recepcion.canart)as canart,siv_dt_recepcion.canoriart".
                              "  from siv_dt_recepcion ,siv_recepcion ".
                              "  where siv_dt_recepcion.codemp='". $as_codemp ."' ".
							  "  and siv_dt_recepcion.codemp=siv_recepcion.codemp ".
							  "  and siv_recepcion.numordcom='". $as_numordcom ."'".
							  "  and siv_dt_recepcion.numordcom=siv_recepcion.numordcom ".
							  "  and siv_dt_recepcion.codart='". $ls_codart ."' ".
							  "  and siv_dt_recepcion.numconrec=siv_recepcion.numconrec".
							  "  and siv_recepcion.estrevrec='1' ".
							  " GROUP BY siv_dt_recepcion.codart";
							  
						$rs_data2=$this->io_sql->select($ls_sql);
						if($rs_data2===false)
						{
							$lb_valido=false;
							$this->io_msg->message("CLASE->recepcion M�TODO->uf_siv_obtener_dt_pendiente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
						}
						else
						{
							while($row=$this->io_sql->fetch_row($rs_data2))
							{ 	  
								$ls_codart=    $row["codart"];
								$ls_canart=    $row["canart"];
								$ls_canart=number_format ($ls_canart,2,".",",");
								$ls_cantiorden=$row["canoriart"];  
								$li_penart=$ls_cantiorden-$ls_canart; 
						  
								if($li_penart!=0.00)
								{
									$ai_totrows=$ai_totrows+1;
									$ao_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."    type=text id=txtdenart".$ai_totrows."    class=sin-borde size=15 maxlength=50 value='".$ls_denart."' readonly><input name=txtcodart".$ai_totrows." type=hidden id=txtcodart".$ai_totrows." class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly>";
									$ao_object[$ai_totrows][2]="<input name=txtunidad".$ai_totrows."    type=text id=txtunidad".$ai_totrows."    class=sin-borde size=12 maxlength=12 value='".$ls_unidadaux."' readonly><input name='hidunidad".$ai_totrows."' type='hidden' id='hidunidad".$ai_totrows."' value='".$li_unidad."'>";
									$ao_object[$ai_totrows][3]="<input name=txtcanoriart".$ai_totrows." type=text id=txtcanoriart".$ai_totrows." class=sin-borde size=12 maxlength=12 value='".number_format ($li_canoriart,2,",",".")."'  readonly>";
									$ao_object[$ai_totrows][4]="<input name=txtcanart".$ai_totrows."    type=text id=txtcanart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".$li_canart."'  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur='javascript: ue_calcularpendiente(".$ai_totrows.");'>";
									$ao_object[$ai_totrows][5]="<input name=txtpenart".$ai_totrows."    type=text id=txtpenart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".number_format ($li_penart,2,",",".")."'  onKeyPress=return(ue_formatonumero(this,'.',',',event)); readonly><input name='hidpendiente".$ai_totrows."' type='hidden' id='hidpendiente".$ai_totrows."' value='".$li_penart."'>";
									$ao_object[$ai_totrows][6]="<input name=txtpreuniart".$ai_totrows." type=text id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_preuniart,2,",",".")."' readonly>";
									$ao_object[$ai_totrows][7]="<input name=txtmontotart".$ai_totrows." type=text id=txtmontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".$li_montotart."' readonly>";
									$ao_object[$ai_totrows][8]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a>";
		
								}
							}
						}
					}//while
				}//else
			 }//while($row=$this->io_sql->fetch_row($li_exec))
		}
		if ($ai_totrows==0)
		{$lb_valido=false;}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_siv_obtener_dt_pendiente

	function uf_siv_obtener_dt_bienes($as_codemp,$as_numordcom,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_obtener_dt_bienes
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los articulos asociados a una nueva orden de compra ordenados por el campo "orden" en la
		//				   tabla de  soc_dt_bienes e imprime los resultados obtenidos en el grid de la pagina.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		/*$ls_sql="SELECT soc_dt_bienes.*,siv_articulo.codunimed,".
				"       (SELECT denart FROM siv_articulo".
				"         WHERE soc_dt_bienes.codart=siv_articulo.codart) AS denart,".
			    "       (SELECT unidad FROM siv_unidadmedida ".
			    "	      WHERE siv_unidadmedida.codunimed = siv_articulo.codunimed) AS unidades".
				"  FROM soc_dt_bienes,siv_articulo".
				" WHERE soc_dt_bienes.codemp='". $as_codemp ."'".
 			    "   AND soc_dt_bienes.codart=siv_articulo.codart".
				"   AND numordcom='". $as_numordcom ."'".
				" GROUP BY soc_dt_bienes.codart".
				" ORDER BY orden";*/
		$ls_sql="SELECT soc_dt_bienes.codemp,soc_dt_bienes.numordcom,soc_dt_bienes.estcondat,soc_dt_bienes.codart,soc_dt_bienes.unidad,".
		        "   SUM(soc_dt_bienes.canart)as canart, SUM(soc_dt_bienes.montotart) as montotart,SUM(soc_dt_bienes.monsubart) as monsubart, ".
				"  soc_dt_bienes.penart,soc_dt_bienes.preuniart,soc_dt_bienes.orden,siv_articulo.codunimed, ".
				"       (SELECT denart FROM siv_articulo".
				"         WHERE soc_dt_bienes.codart=siv_articulo.codart) AS denart,".
			    "       (SELECT unidad FROM siv_unidadmedida ".
			    "	      WHERE siv_unidadmedida.codunimed = siv_articulo.codunimed) AS unidades".
				"  FROM soc_dt_bienes,siv_articulo".
				" WHERE soc_dt_bienes.codemp='". $as_codemp ."'".
 			    "   AND soc_dt_bienes.codart=siv_articulo.codart".
				"   AND numordcom='". $as_numordcom ."'".
				" GROUP BY soc_dt_bienes.codemp,soc_dt_bienes.numordcom,
						   soc_dt_bienes.estcondat,soc_dt_bienes.codart,soc_dt_bienes.unidad,soc_dt_bienes.penart ".
				" ORDER BY orden";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_siv_obtener_dt_bienes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{ 
				$ls_codart=    $row["codart"];
				$ls_denart=    $row["denart"];
				$ls_unidad=    $row["unidad"];
				$li_unidad=    $row["unidades"];
				$li_preuniart= $row["preuniart"];
				$li_canoriart= $row["canart"];
				$li_canart=    "";
				$li_montotart= "";
				$li_penart=    "";
				switch ($ls_unidad) 
				{
					case "M":
						$ls_unidadaux="Mayor";
						break;
					case "D":
						$ls_unidadaux="Detal";
						break;
				}
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."    type=text id=txtdenart".$ai_totrows."    class=sin-borde size=15 maxlength=50 value='".$ls_denart."' readonly><input name=txtcodart".$ai_totrows." type=hidden id=txtcodart".$ai_totrows." class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtunidad".$ai_totrows."    type=text id=txtunidad".$ai_totrows."    class=sin-borde size=12 maxlength=12 value='".$ls_unidadaux."' readonly><input name='hidunidad".$ai_totrows."' type='hidden' id='hidunidad".$ai_totrows."' value='". $li_unidad ."'>";
				$ao_object[$ai_totrows][3]="<input name=txtcanoriart".$ai_totrows." type=text id=txtcanoriart".$ai_totrows." class=sin-borde size=12 maxlength=12 value='".number_format ($li_canoriart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtcanart".$ai_totrows."    type=text id=txtcanart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".$li_canart."'  onKeyPress=return(ue_formatonumero(this,'.',',',event));  onBlur='javascript: ue_calcularpendiente(".$ai_totrows.");'>";
				$ao_object[$ai_totrows][5]="<input name=txtpenart".$ai_totrows."    type=text id=txtpenart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".number_format ($li_penart,2,",",".")."' readonly><input name='hidpendiente".$ai_totrows."' type='hidden' id='hidpendiente".$ai_totrows."' value='".$li_penart."'>";
				$ao_object[$ai_totrows][6]="<input name=txtpreuniart".$ai_totrows." type=text id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_preuniart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][7]="<input name=txtmontotart".$ai_totrows." type=text id=txtmontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".$li_montotart."' readonly>";
			    //$ao_object[$ai_totrows][8]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a>";
			    $ao_object[$ai_totrows][8]="";

			}//while
		}//else
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_siv_obtener_dt_bienes

	function uf_siv_obtener_dt_orden($as_codemp,$as_numordcom,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_obtener_dt_orden
		//         Access: public (tepuy_siv_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que deacuerdo a que si una orden de compra es nueva  � no, procesa la busqueda
		//				   e los articulos de forma diferente.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe= $this->uf_siv_select_dt_recepcion($as_codemp,$as_numordcom);
		if($lb_existe==1)
		{ 
			$lb_valido=$this->uf_siv_obtener_dt_pendiente($as_codemp,$as_numordcom,&$ai_totrows,&$ao_object);
		}
		else
		{
			$lb_valido=$this->uf_siv_obtener_dt_bienes($as_codemp,$as_numordcom,&$ai_totrows,&$ao_object);
		}
		return $lb_valido;
	} // end  function uf_siv_obtener_dt_orden
	
	function uf_siv_update_ordencompra($as_codemp,$as_numordcom,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_ordencompra
		//         Access: public (tepuy_siv_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de la orden de compra estpenalm que indica si una orden de compra
		//				   ha sido completa o no. En la tabla soc_ordencompra.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE soc_ordencompra".
		 		   "   SET estpenalm=1".
				   " WHERE codemp='" . $as_codemp ."' ".
				   "   AND numordcom='" . $as_numordcom ."' ";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_siv_update_ordencompra ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
	  return $lb_valido;
	} // end function uf_siv_update_ordencompra

	function uf_siv_obtener_dt_recepcion($as_codemp,$as_numordcom,$as_numconrec,&$ai_totrows,&$ao_object,&$ai_totentsum)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_obtener_dt_recepcion
		//         Access: public (tepuy_siv_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero consecutivo de recepcion
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que busca los articulos asociados a recepcion en la tabla siv_dt_recepcion para luego 
		//                 imprimirlos en el grid de  la pagina exepto que ya se recibieron por completo.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "SELECT siv_dt_recepcion.*,siv_articulo.codunimed,".
				  "      (SELECT unidad FROM siv_unidadmedida ".
				  "	       WHERE siv_unidadmedida.codunimed = siv_articulo.codunimed) AS unidades,".
				  "      (SELECT denart FROM siv_articulo".
				  "        WHERE siv_dt_recepcion.codart=siv_articulo.codart) AS denart".
				  "  FROM siv_dt_recepcion, siv_recepcion,siv_articulo".
				  " WHERE  siv_dt_recepcion.codemp=siv_recepcion.codemp".
				  "   AND siv_dt_recepcion.codart=siv_articulo.codart".
				  "   AND siv_dt_recepcion.numordcom=siv_recepcion.numordcom".
				  "   AND siv_dt_recepcion.numconrec=siv_recepcion.numconrec ".
				  "   AND siv_dt_recepcion.codemp='".$as_codemp."'".
				  "   AND siv_dt_recepcion.numordcom='".$as_numordcom."'".
				  "   AND siv_dt_recepcion.numconrec='".$as_numconrec."'".
				  " ORDER BY siv_dt_recepcion.numconrec";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_siv_obtener_dt_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codart=    $row["codart"];
				$ls_denart=    $row["denart"];
				$ls_unidad=    $row["unidad"];
				$li_unidad=    $row["unidades"];
				$li_preuniart= $row["preuniart"];
				$li_penart=    $row["penart"];
				$li_canoriart= $row["canoriart"];
				$li_canart=    $row["canart"];
				$li_montotart= $row["montotart"];
				$ai_totentsum=($ai_totentsum+$li_montotart);
				switch ($ls_unidad) 
				{
					case "M":
						$ls_unidadaux="Mayor";
						$li_canart= ($li_canart/$li_unidad);
						break;
					case "D":
						$ls_unidadaux="Detal";
						break;
				}
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."    type=text id=txtdenart".$ai_totrows."    class=sin-borde size=15 maxlength=50 value='".$ls_denart."' readonly><input name=txtcodart".$ai_totrows." type=hidden id=txtcodart".$ai_totrows." class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtunidad".$ai_totrows."    type=text id=txtunidad".$ai_totrows."    class=sin-borde size=12 maxlength=12 value='".$ls_unidadaux."' readonly><input name='hidunidad".$ai_totrows."' type='hidden' id='hidunidad".$ai_totrows."' value='".$li_unidad."'>";
				$ao_object[$ai_totrows][3]="<input name=txtcanoriart".$ai_totrows." type=text id=txtcanoriart".$ai_totrows." class=sin-borde size=12 maxlength=12 value='".number_format ($li_canoriart,2,",",".")."'  readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtcanart".$ai_totrows."    type=text id=txtcanart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".number_format ($li_canart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][5]="<input name=txtpenart".$ai_totrows."    type=text id=txtpenart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".number_format ($li_penart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][6]="<input name=txtpreuniart".$ai_totrows." type=text id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_preuniart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][7]="<input name=txtmontotart".$ai_totrows." type=text id=txtmontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_montotart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][8]="";			
			}//while
		}//else
		if ($ai_totrows==0)
		{$lb_valido=false;}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_siv_obtener_dt_recepcion
	
	function uf_siv_update_ultimocosto($as_codemp,$as_codart,$ai_preuniart,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_ultimocosto
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codart     // numero de orden de compra
		//  			   $ai_preuniart  // precio unitario del articulo
		//                 $aa_seguridad  // 
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el monto del ultimo costo con el cual el articulo ha ingresado a la empresa
		//				   en la tabla siv_articulo
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE siv_articulo ".
		 		   "   SET ultcosart='".$ai_preuniart."' ".
				   " WHERE codemp='".$as_codemp."' ".
				   "   AND codart='".$as_codart."' ";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_siv_update_ultimocosto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$this->io_rcbsf->io_ds_datos->insertRow("campo","ultcosartaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ai_preuniart);
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codart);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("siv_articulo",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			//$lb_valido=true;
		}
	    return $lb_valido;
	} // end function uf_siv_update_ultimocosto

	function uf_siv_actualizar_costo_promedio($as_codemp,$as_codart,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_actualizar_costo_promedio
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codart     // numero de orden de compra
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que se encarga de calcular el costo promedio por articulo para luego actualizar
		//				   dicho monto en la tabla de siv_articulo
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_cosproart=0;
		$li_montot=0;		
		$li_conart=0;		
		$ls_sql = "SELECT * FROM siv_dt_recepcion  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND codart='".$as_codart."'" ;
			
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_siv_actualizar_costo_promedio ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		while($row=$this->io_sql->fetch_row($rs_data))
		{
			$li_preuniart=$row["preuniart"];
			$li_canart=$row["canart"];
			$li_montot=$li_montot + ($li_preuniart * $li_canart);
			$li_conart=$li_conart + $li_canart;
		}
		if($li_conart!=0)
		{$li_cosproart=($li_montot / $li_conart);}
		else
		{$li_cosproart=0.0000;}
//		$this->io_sql->free_result($li_exec);
		$lb_valido=$this->uf_siv_update_costo_promedio($as_codemp,$as_codart,$li_cosproart,$aa_seguridad);		
		return $lb_valido;

	}  // end function uf_siv_actualizar_costo_promedio

	function uf_siv_update_costo_promedio($as_codemp,$as_codart,$ai_cosproart,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_costo_promedio
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codart     // numero de orden de compra
		//  			   $as_codalm     // codigo de almacen
		//  			   $ai_cosproart  // costo promedio por articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el costo promedio en un determinado articulo en la tabla siv_articulo
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $li_exce=-1;
		 $lb_existe=true;
		//$lb_existe=$this->uf_siv_select_articulo($as_codemp,$as_codart);
		if($lb_existe)
		{
				$ls_sql = "UPDATE siv_articulo".
						  "   SET cosproart='".$ai_cosproart."' ".
						  " WHERE codemp='".$as_codemp."' ".
						  "   AND codart='".$as_codart."' ";
				$li_row = $this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$this->io_msg->message("CLASE->recepcion M�TODO->uf_siv_update_costo_promedio ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
				}
				else
				{
					$this->io_rcbsf->io_ds_datos->insertRow("campo","cosproartaux");
					$this->io_rcbsf->io_ds_datos->insertRow("monto",$ai_cosproart);
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
					$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codart);
					$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
					
					$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("siv_articulo",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
					//$lb_valido=true;
				}
		} 
		return $lb_valido;
	} // end  function uf_siv_update_costo_promedio

}//fin  class tepuy_sfa_c_factura
?>
