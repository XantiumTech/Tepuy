	<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/tepuy_include.php");
require_once("../shared/class_folder/tepuy_c_seguridad.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_tepuy_int.php");
require_once("../shared/class_folder/class_tepuy_int_int.php");
require_once("../shared/class_folder/class_tepuy_int_spg.php");
require_once("../shared/class_folder/class_tepuy_int_spi.php");
require_once("../shared/class_folder/class_tepuy_int_scg.php");
require_once("tepuy_siv_c_articuloxalmacen.php");
require_once("tepuy_siv_c_movimientoinventario.php");

class tepuy_siv_c_cerraroc
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function tepuy_siv_c_cerraroc()
	{
		$in=              new tepuy_include();
		$this->sig_int=   new class_tepuy_int();
        $this->io_tepuy_int=new class_tepuy_int_int();
        $this->io_tepuy_spg=new class_tepuy_int_spg();
		$this->con=       $in->uf_conectar();
		$this->io_sql=    new class_sql($this->con);
		$this->seguridad= new tepuy_c_seguridad();
		$this->io_fun=    new class_funciones_db($this->con);
		$this->io_msg=    new class_mensajes();
		$this->io_funcion=new class_funciones();
		$this->io_mov=  new tepuy_siv_c_movimientoinventario();
		$this->ds=new class_datastore();
		$arre=$_SESSION["la_empresa"];
		$this->ls_codemp=$arre["codemp"];
	}
	
	function uf_siv_load_ordenes(&$ai_totrows,&$ao_object,$as_estmov,$ad_fecdes,$ad_fechas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_ordenes
		//         Access: public 
		//      Argumento: $ai_totrows // total de filas del grid
		//  			   $ao_object  // arreglo de objetos
		//  			   $as_estmov  // estatus del movimiento (cerrar o reversar cierre)
		//  			   $ad_fecdes  // fecha de inicio de la busqueda 
		//  			   $ad_fechas  // fecha de cierre de la busqueda
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca las ordenes de compra dependiendo del estatus de pendiente de almacén
		//				   en la tabla soc_ordencompra.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/07/2006							Fecha Última Modificación : 29/07/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecdes=$this->io_funcion->uf_convertirdatetobd($ad_fecdes);
		$ad_fechas=$this->io_funcion->uf_convertirdatetobd($ad_fechas);
		$ls_sql= "SELECT soc_ordencompra.*,".
		 		  "     (SELECT nompro FROM rpc_proveedor".
				  "       WHERE rpc_proveedor.cod_pro=soc_ordencompra.cod_pro) AS nompro,".	
		 		  "     (SELECT denuniadm FROM spg_unidadadministrativa".
				  "       WHERE spg_unidadadministrativa.coduniadm=soc_ordencompra.coduniadm) AS denuniadm".	
				  "	 FROM soc_ordencompra".
				  " WHERE codemp='". $this->ls_codemp ."'".
				  "   AND (estcondat = 'B' OR estcondat = '-')".
				  "   AND estpenalm='". $as_estmov ."'".
				  "   AND fecordcom >= '". $ad_fecdes ."'".
				  "   AND fecordcom <= '". $ad_fechas ."'".
				  " ORDER BY numordcom "; //print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_siv_load_ordenes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i=$li_i + 1;
				$ls_numordcom= $row["numordcom"];
				$ls_nompro=    $row["nompro"];
				$ls_codpro=    $row["cod_pro"];
				$ld_fecordcom= $row["fecordcom"];
				$li_montot=    $row["montot"];
				$ls_coduniadm= $row["coduniadm"];
				$ls_denuniadm= $row["denuniadm"];
				$ld_fecordcom=$this->io_funcion->uf_convertirfecmostrar($ld_fecordcom);
				$li_montot=number_format($li_montot,2,",",".");
				if($ls_coduniadm=="")
				{
					$lb_valido=$this->uf_load_soc_enlace_sep($this->ls_codemp,$ls_numordcom,$aa_coduniadm,$aa_denuniadm);	
					if($lb_valido)
					{
						$li_total=count($aa_coduniadm);
						for($li_j=1;$li_j<=$li_total;$li_j++)
						{
							$ls_coduniadm= $aa_coduniadm[$li_j];
							$ls_denuniadm= $aa_denuniadm[$li_j];
							$ai_totrows=$ai_totrows+1;
							$ao_object[$ai_totrows][1]="<input  name=txtnumordcom".$ai_totrows."  type=text   id=txtnumordcom".$ai_totrows."  class=sin-borde size=20  maxlength=15 value='".$ls_numordcom."' readonly>";
							$ao_object[$ai_totrows][2]="<input  name=txtnompro".$ai_totrows."     type=text   id=txtnompro".$ai_totrows."     class=sin-borde size=40  maxlength=40 value='".$ls_nompro."'    readonly>".
													   "<input  name=txtcodpro".$ai_totrows."     type=hidden id=txtcodpro".$ai_totrows."     class=sin-borde size=40  maxlength=40 value='".$ls_codpro."'    readonly>";
							$ao_object[$ai_totrows][3]="<input  name=txtdenuniadm".$ai_totrows."  type=text   id=txtdenuniadm".$ai_totrows."  class=sin-borde size=45 maxlength=100 value='".$ls_denuniadm."' readonly>".
													   "<input  name=txtcoduniadm".$ai_totrows."  type=hidden id=txtcoduniadm".$ai_totrows."  class=sin-borde size=40  maxlength=40 value='".$ls_coduniadm."' readonly>";
							$ao_object[$ai_totrows][4]="<input  name=txtfecordcom".$ai_totrows."  type=text   id=txtfecordcom".$ai_totrows."  class=sin-borde size=12  maxlength=12 value='".$ld_fecordcom."' readonly>";
							$ao_object[$ai_totrows][5]="<input  name=txtmontot".$ai_totrows."     type=text   id=txtmontot".$ai_totrows."     class=sin-borde size=15  maxlength=15 value='".$li_montot."'    readonly style=text-align:right>";
							$ao_object[$ai_totrows][6]="<input  name=chkprocesar".$ai_totrows."   type='checkbox' class= sin-borde value=1 onchange=ue_validar_oc(".$ai_totrows.")>";
						}
					}

				}
				else
				{
					$ai_totrows=$ai_totrows+1;
					$ao_object[$ai_totrows][1]="<input  name=txtnumordcom".$ai_totrows."  type=text   id=txtnumordcom".$ai_totrows."  class=sin-borde size=20 maxlength=15  value='".$ls_numordcom."' readonly>";
					$ao_object[$ai_totrows][2]="<input  name=txtnompro".$ai_totrows."     type=text   id=txtnompro".$ai_totrows."     class=sin-borde size=40 maxlength=40  value='".$ls_nompro."'    readonly>".
											   "<input  name=txtcodpro".$ai_totrows."     type=hidden id=txtcodpro".$ai_totrows."     class=sin-borde size=40 maxlength=40  value='".$ls_codpro."'    readonly>";
					$ao_object[$ai_totrows][3]="<input  name=txtdenuniadm".$ai_totrows."  type=text   id=txtdenuniadm".$ai_totrows."  class=sin-borde size=45 maxlength=100 value='".$ls_denuniadm."' readonly>".
											   "<input  name=txtcoduniadm".$ai_totrows."  type=hidden id=txtcoduniadm".$ai_totrows."  class=sin-borde size=40 maxlength=40  value='".$ls_coduniadm."' readonly>";
					$ao_object[$ai_totrows][4]="<input  name=txtfecordcom".$ai_totrows."  type=text   id=txtfecordcom".$ai_totrows."  class=sin-borde size=12 maxlength=12  value='".$ld_fecordcom."' readonly>";
					$ao_object[$ai_totrows][5]="<input  name=txtmontot".$ai_totrows."     type=text   id=txtmontot".$ai_totrows."     class=sin-borde size=15 maxlength=15  value='".$li_montot."'    readonly style=text-align:right>";
					$ao_object[$ai_totrows][6]="<input  name=chkprocesar".$ai_totrows."   type='checkbox' class= sin-borde value=1>";
				}

			}//while
			if ($li_i==0)
			{
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input  name=txtnumordcom".$ai_totrows."  type=text id=txtnumordcom".$ai_totrows."  class=sin-borde size=15 maxlength=15 readonly>";
				$ao_object[$ai_totrows][2]="<input  name=txtnompro".$ai_totrows."     type=text id=txtnompro".$ai_totrows."     class=sin-borde size=40 maxlength=40 readonly>";
				$ao_object[$ai_totrows][3]="<input  name=txtdenuniadm".$ai_totrows."  type=text id=txtdenuniadm".$ai_totrows."  class=sin-borde size=40 maxlength=40 readonly>";
				$ao_object[$ai_totrows][4]="<input  name=txtfecordcom".$ai_totrows."  type=text id=txtfecordcom".$ai_totrows."  class=sin-borde size=12 maxlength=12 readonly>";
				$ao_object[$ai_totrows][5]="<input  name=txtmontot".$ai_totrows."     type=text id=txtmontot".$ai_totrows."     class=sin-borde size=15 maxlength=15 readonly>";
				$ao_object[$ai_totrows][6]="<input  name=chkprocesar".$ai_totrows."   type='checkbox'class= sin-borde value=1>";
			}
			$this->io_sql->free_result($rs_data);
		}
		if ($ai_totrows==0)
		{$lb_valido=false;}
		return $lb_valido;
	} // end  function uf_siv_load_ordenes

	function uf_load_soc_enlace_sep($as_codemp,$as_numordcom,&$aa_coduniadm,&$aa_denuniadm)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_load_soc_enlace_sep
		//	           Access: public
		//  		Arguments: $as_codemp    // codigo de empresa
		//  			       $as_numordcom // numero de orden de compra
		//  			       $aa_coduniadm // arreglo de codigos de unidad administrativa
		//  			       $aa_denuniadm // arreglo de denominaciones de unidad administrativa
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de buscar las sep asociadas a una orden de compra al igual que las unidades 
		//                     administrativas
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 03/11/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT soc_ordencompra.coduniadm, soc_ordencompra.numordcom, soc_enlace_sep.numsol,".
			    "       sep_solicitud.coduniadm, spg_unidadadministrativa.denuniadm".
				"  FROM soc_ordencompra, soc_enlace_sep, sep_solicitud, spg_unidadadministrativa".
				" WHERE soc_ordencompra.codemp='".$as_codemp."'".
				"   AND soc_ordencompra.coduniadm=''".
		  		"   AND soc_ordencompra.numordcom='".$as_numordcom."'".
		 		"   AND soc_ordencompra.codemp =  soc_enlace_sep.codemp".
		 		"   AND soc_ordencompra.numordcom =  soc_enlace_sep.numordcom".
				"   AND soc_enlace_sep.codemp = sep_solicitud.codemp".
				"   AND soc_enlace_sep.numsol = sep_solicitud.numsol".
				"   AND sep_solicitud.codemp = spg_unidadadministrativa.codemp".
			 	"   AND sep_solicitud.coduniadm = spg_unidadadministrativa.coduniadm";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_load_soc_enlace_sep ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=1;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_coduniadm[$li_i]= $row["coduniadm"];
				$aa_denuniadm[$li_i]= $row["denuniadm"];
				$li_i++;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_load_soc_enlace_sep


	function uf_siv_update_statusorden($as_codemp,$as_numordcom,$as_estpenalm,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_statusorden
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_estpenalm // estatus de pendiente de almacen
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de la orden de compra que indica si ya fue recibida por el almacen.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 31/07/2006							Fecha Última Modificación : 31/07/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "UPDATE soc_ordencompra".
				 "   SET estpenalm='".$as_estpenalm."'".
				 " WHERE codemp='".$as_codemp."'".
				 "   AND numordcom='".$as_numordcom."'";
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_siv_update_statusorden ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el estatus de la orden de compra numero ".$as_numordcom." Asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion); 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}  // end  function uf_siv_update_statusorden

	function uf_siv_load_dt_ordencompra($as_codemp,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_dt_ordencompra
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los articulos asociados a una  orden de compra ordenados por el campo "orden" en la
		//				   tabla de  soc_dt_bienes 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/09/2006							Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT soc_dt_bienes.*,siv_articulo.codunimed,".
			    "       (SELECT unidad FROM siv_unidadmedida ".
			    "	      WHERE siv_unidadmedida.codunimed = siv_articulo.codunimed) AS unidades".
				" FROM soc_dt_bienes,siv_articulo".
				" WHERE soc_dt_bienes.codemp='". $as_codemp ."'".
 			    " AND soc_dt_bienes.codart=siv_articulo.codart".
				" AND numordcom='". $as_numordcom ."'".
				" ORDER BY orden";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->recepcion MÉTODO->uf_siv_obtener_dt_bienes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codart=    $row["codart"];
				$ls_unidad=    $row["unidad"];
				$li_unidad=    $row["unidades"];
				$li_preuniart= $row["preuniart"];
				$li_canoriart= $row["canart"];
			}//while
		}//else
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_siv_load_dt_ordencompra

	function uf_siv_load_ordencompra($as_codemp,$as_numordcom,&$ad_fecordcom,&$as_coduniadm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_ordencompra
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa 
		//                 $as_numordcom // numero de orden de compra
		//                 $ad_fecordcom // fecha orden de compra
		//                 $as_coduniadm // codigo de unidad administradora (ejecutora)
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene la fecha y la unidad ejecutora de una orden de compra
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM soc_ordencompra  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND numordcom='".$as_numordcom."'".
				  "   AND (estcondat='B' OR estcondat='-')";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_siv_load_ordencompra ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_fecordcom=$row["fecordcom"];
				$as_coduniadm=$row["coduniadm"];
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}  // end function uf_siv_load_ordencompra
	
	function uf_siv_load_cargosarticulo($as_codemp,$as_codart,$ai_preuniart,$ai_pendiente,$ai_unidad,&$aa_moncar,&$aa_spgcuenta,
										&$ai_i)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_cargosarticulo
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa 
		//                 $as_codart    // codigo de articulo
		//                 $ai_preuniart // precio unitario del articulo
		//                 $ai_pendiente // cantidad de articulos que no se entregaron en el almacen
		//                 $ai_unidad    // cantidad de articulos por unidad
		//                 $aa_moncar    // arreglo de montos  de los cargos por articulo
		//                 $aa_spgcuenta // arreglo de cuentas presupuestarias de los  cargos
		//                 $ai_i         // cantidad de cargos por articulos
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los cargos asociados al determinado articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codcar,".
				  "       (SELECT spg_cuenta FROM tepuy_cargos".
				  "	        WHERE siv_cargosarticulo.codemp = tepuy_cargos.codemp".
				  "           AND siv_cargosarticulo.codcar = tepuy_cargos.codcar) AS spg_cuenta,".
				  "       (SELECT porcar FROM tepuy_cargos".
				  "	        WHERE siv_cargosarticulo.codemp = tepuy_cargos.codemp".
				  "           AND siv_cargosarticulo.codcar = tepuy_cargos.codcar) AS porcar".
				  "  FROM siv_cargosarticulo".
				  "	WHERE siv_cargosarticulo.codemp='".$as_codemp."'".
				  "   AND siv_cargosarticulo.codart='".$as_codart."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_siv_load_cargosarticulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_porcar=$row["porcar"];
				$ai_porcar=($ai_porcar / 100);
				$ls_spgcuenta= $row["spg_cuenta"];
				$li_moncar=($ai_preuniart * $ai_pendiente * $ai_porcar);
				$this->ds->insertRow("spg_cuenta",$ls_spgcuenta);
				$this->ds->insertRow("moncar",$li_moncar);

				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}  // end function uf_siv_load_cargosarticulo

	function uf_siv_load_estpre($as_codemp,$as_coduniadm,&$as_codestpro1,&$as_codestpro2,&$as_codestpro3,
							    &$as_codestpro4,&$as_codestpro5)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_estpre
		//         Access: public  
		//      Argumento: $as_codemp      // codigo de empresa 
		//                 $as_coduniadm   // codigo de la unidad ejecutora
		//				   $as_codestpro1  //codigo de estructura programatica nivel 1
		//				   $as_codestpro2  //codigo de estructura programatica nivel 2
		//				   $as_codestpro3  //codigo de estructura programatica nivel 3
		//				   $as_codestpro4  //codigo de estructura programatica nivel 4
		//				   $as_codestpro5  //codigo de estructura programatica nivel 5
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene la estructura presupuestaria de una unidad ejecutora
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5".
				  "  FROM spg_unidadadministrativa  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND coduniadm='".$as_coduniadm."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_siv_load_estpre ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codestpro1=$row["codestpro1"];
				$as_codestpro2=$row["codestpro2"];
				$as_codestpro3=$row["codestpro3"];
				$as_codestpro4=$row["codestpro4"];
				$as_codestpro5=$row["codestpro5"];
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}  // end function uf_siv_load_estpre

	function uf_siv_load_cuentaspg($as_codemp,$as_codart,&$as_spgcuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_cuentaspg
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa 
		//                 $as_codart    // codigo de articulo
		//                 $as_spgcuenta // cuenta presupuestaria
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene la cuenta presupuestaria de un articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT spg_cuenta".
				  "  FROM siv_articulo  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND codart='".$as_codart."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_siv_load_cuentaspg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_spgcuenta=$row["spg_cuenta"];
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}  // end function uf_siv_load_cuentaspg

	function uf_siv_procesar_comprobante($as_codemp,$as_procede,$as_numordcom,$ad_newfeccmp,$as_newprocede,&$as_newcomprobante,&$ad_feccmp,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_procesar_comprobante
		//         Access: public  
		//      Argumento: $as_codemp         // codigo de empresa 
		//                 $as_procede        // procedencia del comprobante
		//                 $as_numordcom      // numero de orden de compra
		//                 $ad_feccmp         // fecha del comprobante actual
		//                 $ad_newfeccmp      // fecha del comprobante a registrar
		//                 $ad_newprocede     // procedencia del comprobante a registrar
		//                 $as_newcomprobante // numero del comprobante a registrar
		//                 $as_newcomprobante // numero del comprobante a registrar
		//                 $aa_seguridad      // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene la cuenta presupuestaria de un articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_valido=$this->uf_siv_load_cmp($as_codemp,$as_procede,$as_numordcom,$ad_feccmp,$as_tipocomp,$as_tipodestino,
										  $as_codpro,$as_cedbene);
		if($lb_valido)
		{
			$lb_valido=$this->uf_siv_obtener_numerocomprobante($as_codemp,$as_newprocede,$as_newcomprobante);
		}
		return $lb_valido;
	}  // end function uf_siv_procesar_comprobante
	
	function uf_siv_load_dt_pendiente($as_codemp,$as_numordcom,$as_coduniadm,&$li_totmonart,&$li_totmoncar,&$ls_valida,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_dt_pendiente
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $li_totmonart // total de monto por recibir (costo de articulos)
		//  			   $li_totmoncar // total de monto por recibir (cargos)
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los articulos asociados a una orden de compra ordenados por el campo "orden" en la
		//				   tabla de soc_dt_bienes, y por articulo busca en la tabla siv_dt_recepcion los pendientes asociados a esos 
		//				   articulos para luego calcular los montos que no se recibieron en el proceso de recepcion de sumistros
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/09/2006							Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_totmonart=0;
		$li_totmoncar=0;
		$ad_fecordcom="";
		$lb_valido=$this->uf_siv_load_estpre($as_codemp,$as_coduniadm,$as_codestpro1,$as_codestpro2,$as_codestpro3,
											 $as_codestpro4,$as_codestpro5);
		if($lb_valido)
		{
			$ls_sql= "SELECT * FROM soc_dt_bienes".
					 " WHERE codemp='". $as_codemp ."'".
					 "   AND numordcom='". $as_numordcom ."'".
					 " ORDER BY orden";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$lb_valido=false;
				$this->io_msg->message("CLASE->recepcion MÉTODO->uf_siv_load_dt_pendiente_I ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				return false;
			}
			else
			{
				$ls_gestor=$_SESSION["ls_gestor"];
				$ls_procede="SOCCOC";
				$ls_descmp="Cierre de Orden de Compra";
				$ld_newfeccmp=date("Y-m-d");
				$ls_newprocede="SPGCMP";
				$ls_tipo_destino= "-";
				$ls_codigo_destino="----------";
				$li_tipo_comp='1';
				$ls_codban  = "---";
				$ls_ctaban  = "-------------------------";
				$lb_valido=$this->uf_siv_procesar_comprobante($as_codemp,$ls_procede,$as_numordcom,$ld_newfeccmp,$ls_newprocede,
															  $as_newcomprobante,$ad_feccmp,$aa_seguridad);
				if($lb_valido)
				{ 
					$lb_valido = $this->io_tepuy_int->uf_int_init($as_codemp,$ls_newprocede,$as_newcomprobante,$ld_newfeccmp,$ls_descmp,$ls_tipo_destino,$ls_codigo_destino,false,$ls_codban,$ls_ctaban,$li_tipo_comp);/////modificado 04/12/2007 
					
				}
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_codart=$row["codart"];
					if($ls_gestor=="ORACLE")
					{
						$ls_sql=  "SELECT siv_dt_recepcion.*,siv_articulo.codunimed,".
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
								  "   AND siv_recepcion.estrec=0".
								  "   AND siv_dt_recepcion.codart='".$ls_codart."'".
								  " ORDER BY siv_dt_recepcion.numconrec DESC ROWNUM <= 1";
					}
					else
					{
						$ls_sql=  "SELECT siv_dt_recepcion.*,siv_articulo.codunimed,siv_cargosarticulo.codcar,".
								  "      (SELECT unidad FROM siv_unidadmedida ".
								  "	       WHERE siv_unidadmedida.codunimed = siv_articulo.codunimed) AS unidades,".
								  "      (SELECT denart FROM siv_articulo".
								  "        WHERE siv_dt_recepcion.codart=siv_articulo.codart) AS denart".
								  "  FROM siv_dt_recepcion, siv_recepcion,siv_articulo,siv_cargosarticulo".
								  " WHERE  siv_dt_recepcion.codemp=siv_recepcion.codemp".
								  "   AND siv_dt_recepcion.codart=siv_articulo.codart".
								  "   AND siv_dt_recepcion.numordcom=siv_recepcion.numordcom".
								  "   AND siv_dt_recepcion.numconrec=siv_recepcion.numconrec ".
								  "   AND siv_dt_recepcion.codemp='".$as_codemp."'".
								  "   AND siv_dt_recepcion.numordcom='".$as_numordcom."'".
								  "   AND siv_recepcion.estrec=0".
								  "   AND siv_dt_recepcion.codart='".$ls_codart."'".
								  " ORDER BY siv_dt_recepcion.numconrec DESC LIMIT  1";
					}
					$rs_data1=$this->io_sql->select($ls_sql);
					if($rs_data1===false)
					{
						$lb_valido=false;
						$this->io_msg->message("CLASE->recepcion MÉTODO->uf_siv_load_dt_pendiente_II ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					}
					else
					{
						if($row=$this->io_sql->fetch_row($rs_data1))
						{
							$li_penart= $row["penart"];
							if($li_penart>0)
							{
								$ls_unidad=    $row["unidad"];
								$li_unidad=    $row["unidades"];
								$li_preuniart= $row["preuniart"];
								$li_penart=    $row["penart"];
								$li_canoriart= $row["canoriart"];
								if($ls_unidad=="D")
								{$li_unidad=1;}
								$as_spgcuenta="";
								$li_monart=($li_preuniart * $li_penart * $li_unidad);

								$lb_valido=$this->uf_siv_load_cuentaspg($as_codemp,$ls_codart,$as_spgcuenta);
								if($lb_valido)
								{
									$lb_valido=$this->uf_siv_load_cargosarticulo($as_codemp,$ls_codart,$li_preuniart,
																				 $li_penart,$li_unidad,$la_moncar,
																				 $aa_spgcargos,$li_i);
								}
								if($lb_valido)
								$li_totmonart=$li_totmonart + $li_monart;
								$li_monartaux="-".$li_monart;
								{ 
									$lb_valido=$this->uf_siv_load_dt_cmp($as_codemp,$ls_procede,$as_numordcom,$as_codestpro1,
																		 $as_codestpro2,$as_codestpro3,$as_codestpro4,
																		 $as_codestpro5,$as_spgcuenta,$as_operacion,
																		 $as_documento,$as_procede,$ai_orden,&$ls_valida);
									if($lb_valido)
									{  
										$ls_mensaje=$this->io_tepuy_spg->uf_operacion_codigo_mensaje($as_operacion);				
										$lb_valido = $this->io_tepuy_int->uf_spg_insert_datastore($as_codemp,$as_codestpro1,
																		  $as_codestpro2,$as_codestpro3,$as_codestpro4,
																		  $as_codestpro5,$as_spgcuenta,$ls_mensaje,
																		  $li_monartaux,$as_documento,$as_procede,$ls_descmp);
									}
																		
								}
							}
						}//if($row=$this->io_sql->fetch_row($rs_data1))
						else
						{
							$this->io_msg->message("No tiene Entradas al Almacen asociadas");	
							return false;
						}
					}//else
				}//while($row=$this->io_sql->fetch_row($li_exec))
				$this->ds->group_by(array('0'=>'spg_cuenta'),array('0'=>'moncar'),'moncar');	
				$totrow=$this->ds->getRowCount("spg_cuenta");
				for($z=1;$z<=$totrow;$z++)
				{ 
					$ls_spgcuenta=$this->ds->data["spg_cuenta"][$z];
					$li_moncar=   $this->ds->data["moncar"][$z];
					$li_moncaraux="-".$li_moncar;
					$lb_valido=$this->uf_siv_load_dt_cmp($as_codemp,$ls_procede,$as_numordcom,$as_codestpro1,$as_codestpro2,
														 $as_codestpro3,$as_codestpro4,$as_codestpro5,$ls_spgcuenta,$as_operacion,
														 $as_documento,$as_procede,$ai_orden,&$ls_valida);
					if($lb_valido)
					{  
						$ls_mensaje=$this->io_tepuy_spg->uf_operacion_codigo_mensaje($as_operacion);				
						$lb_valido = $this->io_tepuy_int->uf_spg_insert_datastore($as_codemp,$as_codestpro1,
														  $as_codestpro2,$as_codestpro3,$as_codestpro4,
														  $as_codestpro5,$ls_spgcuenta,$ls_mensaje,
														  $li_moncaraux,$as_documento,$as_procede,$ls_descmp);
					}
				}
			}
			if  ($lb_valido)
			{  
			   $lb_valido = $this->io_tepuy_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			   if ( $lb_valido===false)
			   { 	   	 
				 $this->io_msg->message("".$this->io_tepuy_int->is_msg_error);
			   }
		   
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function uf_siv_obtener_dt_pendiente

	function uf_siv_load_cmp($as_codemp,$as_procede,$as_comprobante,&$ad_feccmp,&$as_tipocomp,&$as_tipodestino,&$as_codpro,&$as_cedbene)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_cmp
		//         Access: public  
		//      Argumento: $as_codemp      // codigo de empresa 
		//                 $as_procede     // codigo de procedencia
		//                 $as_comprobante // numero de comprobante
		//                 $ad_feccmp      // fecha de comprobante
		//                 $as_tipocomp    // tipo de comprobante
		//                 $as_tipodestino // tipo de destino
		//                 $as_codpro      // codigo de proveedor
		//                 $as_cedbene     // cedula de beneficiario
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los datos (maestro) de un comprobante
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM tepuy_cmp  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND   procede='".$as_procede."'".
				  "   AND   comprobante='".$as_comprobante."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_siv_load_cmp ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false; 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_feccmp=      $row["fecha"];
				$as_tipocomp=    $row["tipo_comp"];
				$as_tipodestino= $row["tipo_destino"];
				$as_codpro=      $row["cod_pro"];
				$as_cedbene=     $row["ced_bene"];
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			$this->io_msg->message("La orden de compra no ha sido contabilizada");
		}
		return $lb_valido;
	}  // end function uf_siv_load_cmp

	function uf_siv_load_dt_cmp($as_codemp,$as_procede,$as_comprobante,$as_codestpro1,$as_codestpro2,$as_codestpro3,
								$as_codestpro4,$as_codestpro5,$as_spgcuenta,&$as_operacion,&$as_documento,&$as_procededoc,&$ai_orden,&$as_valida)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_dt_cmp
		//         Access: public  
		//      Argumento: $as_codemp      // codigo de empresa 
		//                 $as_procede     // codigo de procedencia
		//                 $as_comprobante // numero de comprobante
		//                 $as_codestpro1  // codigo de estructura programatica nivel 1
		//                 $as_codestpro2  // codigo de estructura programatica nivel 2
		//                 $as_codestpro3  // codigo de estructura programatica nivel 3
		//                 $as_codestpro4  // codigo de estructura programatica nivel 4 
		//                 $as_codestpro5  // codigo de estructura programatica nivel 5
		//                 $as_spgcuenta   // codigo de estructura programatica nivel 5
		//                 $as_operacion   // tipo de operacion
		//                 $as_documento   // numero de documento
		//                 $as_procededoc  // procedencia del documento
		//                 $ai_orden       // orden de registro
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los datos relacionados a un comprobante
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/09/2006 								Fecha Última Modificación : 11/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$as_valida=1;
		$ls_sql = "SELECT * FROM spg_dt_cmp  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND procede='".$as_procede."'".
				  "   AND comprobante='".$as_comprobante."'".
				  "   AND codestpro1='".$as_codestpro1."'".
				  "   AND codestpro2='".$as_codestpro2."'".
				  "   AND codestpro3='".$as_codestpro3."'".
				  "   AND codestpro4='".$as_codestpro4."'".
				  "   AND codestpro5='".$as_codestpro5."'".
				  "   AND spg_cuenta='".$as_spgcuenta."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_siv_load_dt_cmp ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_operacion  = $row["operacion"];
				$as_documento  = $row["documento"];
				$as_procededoc = $row["procede_doc"];
				$ai_orden =      $row["orden"];
				$lb_valido=true;
				$as_valida=true;
				$this->io_sql->free_result($rs_data);
			}
		} 
		return $lb_valido;
	}  // end function uf_siv_load_dt_cmp

	function uf_siv_obtener_numerocomprobante($as_codemp,$as_procede,&$as_comprobante)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_obtener_numerocomprobante
		//         Access: public  
		//      Argumento: $as_codemp      // codigo de empresa 
		//                 $as_procede     // codigo de procedencia
		//                 $as_comprobante // numero de comprobante
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene el numero del comprobante que se desea generar
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT comprobante FROM tepuy_cmp ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND procede='".$as_procede."'".
				  " ORDER BY comprobante DESC"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_siv_obtener_numerocomprobante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_comprobante= $row["comprobante"];
				$as_comprobante=$as_comprobante + 1;
				$as_comprobante=$this->io_funcion->uf_cerosizquierda($as_comprobante,15);
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}  // end function uf_siv_obtener_numerocomprobante

	function uf_load_comprobante($as_codemp,$as_numordcom,&$as_comprobante,&$ad_feccmp)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_load_comprobante
		//	           Access: public
		//  		Arguments: $as_codemp      // codigo de empresa
		//  			       $as_numordcom   // numero de orden de compra
		//  			       $as_comprobante // numero de comprobante
		//  			       $ad_feccmp      // fecha del comprobante
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de buscar el numero y la fecha del comprobante dado una orden de compra
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT comprobante,fecha".
				"  FROM spg_dt_cmp".
				" WHERE codemp='". $as_codemp ."'".
				"   AND procede='SPGCMP'".
				"   AND procede_doc='SOCCOC'".
				"   AND operacion='CS'".
				"   AND documento='".$as_numordcom."'".
				" GROUP BY comprobante,fecha";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_load_comprobante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_comprobante= $row["comprobante"];
				$ad_feccmp= $row["fecha"];
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_load_comprobante

/*	function  uf_siv_insert_cmp($as_codemp,$as_procede,$as_comprobante,$ad_feccmp,$as_desccmp,$as_tipocomp,$as_tipodestino,
								$as_codpro,$as_cedbene,$as_total,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_cmp
		//         Access: public 
		//      Argumento: $as_codemp      //codigo de empresa 
		//				   $as_procede     //procedencia del comprobante
		//				   $as_comprobante //numero del comprobante
		//				   $ad_feccmp      //fecha de elaboracion del comprobante
		//				   $as_desccmp     //descripcion del comprobante
		//				   $as_tipocomp    //tipo  comprobante
		//				   $as_tipodestino //tipo de destino del comprobante
		//				   $as_codpro      //codigo del proveedor
		//				   $as_cedbene     //cedula del beneficiario
		//				   $as_total       //monto total del comprobante
		//				   $aa_seguridad   //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un nuevo comprobante presupuestaria en la tabla tepuy_cmp
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "INSERT INTO tepuy_cmp (codemp,procede,comprobante,fecha,descripcion,tipo_comp,tipo_destino,cod_pro,".
		          "                        ced_bene,total) ".
				  " VALUES('".$as_codemp."','".$as_procede."','".$as_comprobante."','".$ad_feccmp."','".$as_desccmp."',".
				  "        '".$as_tipocomp."','".$as_tipodestino."','".$as_codpro."','".$as_cedbene."','".$as_total."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_siv_insert_cmp ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el comprobante ".$as_comprobante." con Procedencia ".$as_procede.
								 "Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} //end function  uf_siv_insert_cmp

	function  uf_siv_insert_dt_cmp($as_codemp,$as_newprocede,$as_comprobante,$ad_feccmp,$as_codestpro1,$as_codestpro2,
								   $as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,$as_procede,$as_documento,
								   $as_operacion,$as_descripcion,$ai_monto,$ai_orden,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_dt_cmp
		//         Access: public 
		//      Argumento: $as_codemp      //codigo de empresa 
		//				   $as_newprocede  //procedencia del comprobante 
		//				   $as_comprobante //numero del comprobante
		//				   $ad_feccmp      //fecha de elaboracion del comprobante
		//				   $as_codestpro1  //codigo de estructura programatica nivel 1
		//				   $as_codestpro2  //codigo de estructura programatica nivel 2
		//				   $as_codestpro3  //codigo de estructura programatica nivel 3
		//				   $as_codestpro4  //codigo de estructura programatica nivel 4
		//				   $as_codestpro5  //codigo de estructura programatica nivel 5
		//				   $as_spgcuenta   //cuenta presupuestaria
		//				   $as_procede     //procedencia del documento
		//				   $as_documento   //numero de documento
		//				   $as_operacion   //operacion
		//				   $as_descripcion //descripcion del documento
		//				   $ai_monto       //monto del asiento presupuestario
		//				   $ai_orden       //orden en que se registra el asiento presupuestario
		//				   $aa_seguridad   //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta los detalles de un nuevo comprobante presupuestario en la tabla spg_dt_cmp
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "INSERT INTO spg_dt_cmp (codemp,procede,comprobante,fecha,codestpro1,codestpro2,codestpro3,codestpro4, ".
				  "                        codestpro5,spg_cuenta,procede_doc,documento,operacion,descripcion,monto,orden)".
				  " VALUES('".$as_codemp."','".$as_newprocede."','".$as_comprobante."','".$ad_feccmp."','".$as_codestpro1."',".
				  "        '".$as_codestpro2."','".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."',".
				  "        '".$as_spgcuenta."','".$as_procede."','".$as_documento."','".$as_operacion."','".$as_descripcion."',".
				  "        '".$ai_monto."','".$ai_orden."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_siv_insert_dt_cmp ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	} //end function  uf_siv_insert_dt_cmp
*/	

function uf_siv_buscar_cuentagasto($as_codemp,$as_numordcom,$as_coduniadm,$as_escondat,&$ao_object,&$as_codestpro1,
                                   &$as_codestpro2,&$as_codestpro3,&$as_codestpro4,&$as_codestpro5,&$spg_cuenta,&$as_montonuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_buscar_cuentagasto
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca la estructura presupuestaria que tienen los articulos en la orden de compra
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 13/08/2008							Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	    $ls_sql= "SELECT soc_cuentagasto.codestpro1,soc_cuentagasto.codestpro2,soc_cuentagasto.codestpro3, ".
		         "       soc_cuentagasto.codestpro4,soc_cuentagasto.codestpro5,soc_cuentagasto.spg_cuenta,".
				 "       soc_cuentagasto.monto".
		         "  FROM soc_cuentagasto".
				 " WHERE codemp='". $as_codemp ."'".
				 "   AND numordcom='".$as_numordcom."'".
				 "   AND estcondat='".$as_escondat."'";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$lb_valido=false;
				$this->io_msg->message("CLASE->recepcion MÉTODO->uf_siv_buscar_cuentagasto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				return false;
			}
			else
			{
 			   $li_i=0;
			   while($row=$this->io_sql->fetch_row($rs_data))
			   {
				    $li_i=$li_i + 1;
					$as_codestpro1= $row["codestpro1"];
					$as_codestpro2= $row["codestpro2"];
					$as_codestpro3= $row["codestpro3"];
					$as_codestpro4= $row["codestpro4"];
					$as_codestpro5= $row["codestpro5"];
					$spg_cuenta= $row["spg_cuenta"];
					$as_monto= $row["monto"];
					$as_estpresu=$as_codestpro1.$as_codestpro2.$as_codestpro3.$as_codestpro4.$as_codestpro5;
					$lb_valido=true;
				
					$ao_object[$li_i][1]="<input name=txtestpres".$li_i." type=text id=txtestpres".$li_i." class=sin-borde size=40 maxlength=15 value='".$as_estpresu."' readonly>";
					$ao_object[$li_i][2]="<input name=txtcuenta".$li_i." type=text id=txtcuenta".$li_i." class=sin-borde size=20 maxlength=15 value='".$spg_cuenta."' readonly>";
					$ao_object[$li_i][3]="<input name=txtmonto".$li_i." type=text id=txtmonto".$li_i." class=sin-borde size=12 maxlength=12 value='".number_format ($as_montonuevo,2,",",".")."' onKeyPress=return(currency_Format(this,'.',',',event)) >";
					$ao_object[$li_i][4]="<input type='checkbox' name=chkcasar".$li_i." class= sin-borde value=1>";
					
				}// fin del while
				if ($li_i==0)
				{
					$li_i=$li_i+1;
					$ao_object[$li_i][1]="<input name=txtestpres".$li_i." type=text id=txtestpres".$li_i." class=sin-borde size=15 maxlength=15 readonly>";
					$ao_object[$li_i][2]="<input name=txtcuenta".$li_i." type=text id=txtcuenta".$li_i." class=sin-borde size=15 maxlength=15 readonly>";
					$ao_object[$li_i][3]="<input name=txtmonto".$li_i." type=text id=txtmonto".$li_i." class=sin-borde size=12 maxlength=12 >";
					$ao_object[$li_i][4]="<input type='checkbox' name=chkcasar".$li_i." class= sin-borde value=1>";
				}
			  $this->io_sql->free_result($rs_data);
			}
		return $lb_valido;
	} // end function uf_siv_buscar_cuentagasto
	
function uf_siv_obtener_dt_pendiente($as_codemp,$as_numordcom,$as_coduniadm,$as_monto,&$ai_totrows,
									 &$ls_codigoart,&$ls_denomiart,&$ls_escondat,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_obtener_dt_pendiente
		//         Access: public (tepuy_siv_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca las entradas de suministros a los almacenes en la tabla de siv_recepcion para luego
		//				   mprimirlos en el grid de  la pagina.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 14/08/08							Fecha Última Modificación: 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_func=  new class_funciones();
		$lb_valido=true; 
		$ls_sql= "SELECT *, ".
		         " (select denart from siv_articulo where siv_articulo.codemp=soc_dt_bienes.codemp ".
				 "     and siv_articulo.codart=soc_dt_bienes.codart) as denart". 
		         " FROM soc_dt_bienes".
				 " WHERE codemp='".$as_codemp."'".
				 "   AND numordcom='".$as_numordcom."'".
				 " ORDER BY orden";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->tepuy_siv_c_cerraroc MÉTODO->uf_siv_obtener_dt_pendiente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i=$li_i + 1;
				$ls_numordcom= $row["numordcom"];
				$ls_escondat=$row["estcondat"];
				$ls_codigoart=$row["codart"];
				$ls_denomiart=$row["denart"];
				$ls_unidad=$row["unidad"];
				$ls_canart=$row["canart"];
				$ls_penart=$row["penart"];
				$ls_preuniart=$row["preuniart"];
                $ls_monsubart=$row["monsubart"];
				$ls_montotart=$row["montotart"];
				$ls_orden=$row["orden"];
				
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtestpres".$ai_totrows." type=text id=txtestpres".$ai_totrows." class=sin-borde size=40 maxlength=15 value='' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtcuenta".$ai_totrows." type=text id=txtcuenta".$ai_totrows." class=sin-borde size=20 maxlength=15 value='' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtmonto".$ai_totrows." type=text id=txtmonto".$ai_totrows." class=sin-borde size=12 maxlength=12 value='' >";
				$ao_object[$ai_totrows][4]="<input type='checkbox' name=chkcasar".$ai_totrows." class= sin-borde value=1>";

			}//while
			if ($li_i==0)
			{
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtestpres".$ai_totrows." type=text id=txtestpres".$ai_totrows." class=sin-borde size=15 maxlength=15 readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtcuenta".$ai_totrows." type=text id=txtcuenta".$ai_totrows." class=sin-borde size=15 maxlength=15 readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtmonto".$ai_totrows." type=text id=txtmonto".$ai_totrows." class=sin-borde size=12 maxlength=12 >";
				$ao_object[$ai_totrows][4]="<input type='checkbox' name=chkcasar".$ai_totrows." class= sin-borde value=1>";
			}
			$this->io_sql->free_result($rs_data);
		}
		if ($ai_totrows==0)
		{$lb_valido=false;}
		return $lb_valido; 
	}
	
	function  uf_siv_insertar_tepuy_cmp($as_codemp,$as_procede,$as_comprobante,$ad_feccmp,$as_codban,$as_ctaban,$as_desccmp,
	                                     $as_tipocomp,$as_tipodestino,$as_codpro,$as_cedbene,$as_total,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insertar_tepuy_cmp
		//         Access: public 
		//      Argumento: $as_codemp      //codigo de empresa 
		//				   $as_procede     //procedencia del comprobante
		//				   $as_comprobante //numero del comprobante
		//				   $ad_feccmp      //fecha de elaboracion del comprobante
		//				   $as_desccmp     //descripcion del comprobante
		//				   $as_tipocomp    //tipo  comprobante
		//				   $as_tipodestino //tipo de destino del comprobante
		//				   $as_codpro      //codigo del proveedor
		//				   $as_cedbene     //cedula del beneficiario
		//				   $as_total       //monto total del comprobante
		//				   $aa_seguridad   //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un nuevo comprobante presupuestaria en la tabla tepuy_cmp
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_num=1; 
		$as_total=(-$as_total); 
		$ls_sql = "INSERT INTO tepuy_cmp (codemp,procede,comprobante,fecha,codban,ctaban,descripcion,tipo_comp,tipo_destino,cod_pro,".
		          "                        ced_bene,total) ".
				  " VALUES('".$as_codemp."','".$as_procede."','".$as_comprobante."','".$ad_feccmp."','".$as_codban."','".$as_ctaban."','".$as_desccmp."',".
				  "        '".$as_tipocomp."','".$as_tipodestino."','".$as_codpro."','".$as_cedbene."','".$as_total."')" ;
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_siv_insertar_tepuy_cmp ERROR->".
			            $this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
			$ls_num=0;
		}
		else
		{ 
				$lb_valido=true;
				$ls_num==1;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				/*$ls_evento="INSERT";
				$ls_descripcion ="Insertó el comprobante ".$as_comprobante." con Procedencia ".$as_procede.
								 "Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);*/
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		
		}
		//return $lb_valido;
		return $ls_num;
	} //end function  uf_siv_insertar_tepuy_cmp
	
	function uf_siv_insertasiento_spg_dt_cmp($as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_codban,$as_ctaban,
											  $as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
											  $as_spgcuenta,$as_documento,$as_procededoc,$as_operacion,$as_descripcion,
											  $ai_orden,$ai_monart,&$ao_object,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insertasiento_spg_dt_cmp
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_estpenalm // estatus de pendiente de almacen
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta en nuevo asiento negativo en la tabla spg_dt_cmp ya que el comprometido resulto mayor a lo que se despacho..
		//	   Creado Por: 
		// Fecha Creación: 18/08/08							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ai_monart=(-$ai_monart);
		$ls_sql= "INSERT INTO spg_dt_cmp(codemp,procede,comprobante,fecha,codban,ctaban,codestpro1,codestpro2,codestpro3,codestpro4,".
		         "                       codestpro5,spg_cuenta,procede_doc,documento,operacion,descripcion,monto,orden)".
				  " VALUES ('".$as_codemp."','".$as_procede."','".$as_comprobante."','".$as_fecha."','".$as_codban."','".$as_ctaban."',".
				  " '".$as_codestpro1."','".$as_codestpro2."','".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."','".$as_spgcuenta."',".
				  " '".$as_procededoc."','".$as_documento."','".$as_operacion."','".$as_descripcion."','".$ai_monart."',".$ai_orden.")";
	   
		$as_estructura=$as_codestpro1.$as_codestpro2.$as_codestpro3.$as_codestpro4.$as_codestpro5; 
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_siv_insertasiento_spg_dt_cmp ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó en spg_dt_cmp el nuevo asiento por causa de la moficación contable en al orden de compra ".$as_comprobante." Asociado a la estrucutra ".$as_estructura." de la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}  // end  function uf_siv_insertasiento_spg_dt_cmp
	
	function uf_siv_buscar_dt_cmp($as_codemp,&$as_procede,&$as_comprobante,&$as_fecha,&$as_codban,&$as_ctaban,&$as_codestpro1,
	                             &$as_codestpro2,&$as_codestpro3,&$as_codestpro4,&$as_codestpro5,&$as_spgcuenta,&$as_documento,
								 &$as_procededoc,&$as_operacion,&$as_descripcion,&$ai_orden,&$as_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_buscar_dt_cmp
		//         Access: public  
		//      Argumento: $as_codemp      // codigo de empresa 
		//                 $as_procede     // codigo de procedencia
		//                 $as_comprobante // numero de comprobante
		//                 $as_codestpro1  // codigo de estructura programatica nivel 1
		//                 $as_codestpro2  // codigo de estructura programatica nivel 2
		//                 $as_codestpro3  // codigo de estructura programatica nivel 3
		//                 $as_codestpro4  // codigo de estructura programatica nivel 4 
		//                 $as_codestpro5  // codigo de estructura programatica nivel 5
		//                 $as_spgcuenta   // codigo de estructura programatica nivel 5
		//                 $as_operacion   // tipo de operacion
		//                 $as_documento   // numero de documento
		//                 $as_procededoc  // procedencia del documento
		//                 $ai_orden       // orden de registro
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los datos relacionados a un comprobante
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/09/2006 								Fecha Última Modificación : 18/08/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM spg_dt_cmp  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND procede='".$as_procede."'".
				  "   AND comprobante='".$as_comprobante."'".
				  "   AND codestpro1='".$as_codestpro1."'".
				  "   AND codestpro2='".$as_codestpro2."'".
				  "   AND codestpro3='".$as_codestpro3."'".
				  "   AND codestpro4='".$as_codestpro4."'".
				  "   AND codestpro5='".$as_codestpro5."'".
				  "   AND spg_cuenta='".$as_spgcuenta."'"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_siv_buscar_dt_cmp ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			    $as_procede  = $row["procede"];
				$as_comprobante= $row["comprobante"];
				$as_fecha= $row["fecha"];
				$as_codban= $row["codban"];
				$as_ctaban= $row["ctaban"];
				$as_codestpro1= $row["codestpro1"];
				$as_codestpro2= $row["codestpro2"];
				$as_codestpro3= $row["codestpro3"];
                $as_codestpro4= $row["codestpro4"];
				$as_codestpro5= $row["codestpro5"];				
				$as_operacion  = $row["operacion"];
				$as_descripcion= $row["descripcion"];
				$as_documento  = $row["documento"];
				$as_procededoc = $row["procede_doc"];
				$ai_orden = $row["orden"];
				$as_monto = $row["monto"];
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}  // end function uf_siv_buscar_dt_cmp
	
  function uf_siv_buscar_dt_ordencompra($as_codemp,$as_numordcom,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_buscar_dt_ordencompra
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los articulos asociados a una  orden de compra ordenados por el campo "orden" en la
		//				   tabla de  soc_dt_bienes 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/09/2006							Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ai_totrows=0;
		$ls_sql="SELECT soc_dt_bienes.*,siv_articulo.codunimed,".
			    "       (SELECT unidad FROM siv_unidadmedida ".
			    "	      WHERE siv_unidadmedida.codunimed = siv_articulo.codunimed) AS unidades".
				" FROM soc_dt_bienes,siv_articulo".
				" WHERE soc_dt_bienes.codemp='". $as_codemp ."'".
 			    " AND soc_dt_bienes.codart=siv_articulo.codart".
				" AND numordcom='". $as_numordcom ."'".
				" ORDER BY orden";  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->recepcion MÉTODO->uf_siv_buscar_dt_ordencompra ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codart=    $row["codart"];
				$ls_unidad=    $row["unidad"];
				$li_unidad=    $row["unidades"];
				$li_preuniart= $row["preuniart"];
				$li_canoriart= $row["canart"];
				$ai_totrows=$ai_totrows+1; 
			}//while
			$this->io_sql->free_result($rs_data); 
		}//else 

		return $lb_valido;
	} // end function uf_siv_buscar_dt_ordencompra
	
	function uf_siv_buscar_cargos($as_codemp,$as_numordcom,$as_coduniadm,$as_escondat,&$ao_object,&$as_codestpro1,
                                   &$as_codestpro2,&$as_codestpro3,&$as_codestpro4,&$as_codestpro5,&$spg_cuenta,&$as_montonuevo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_buscar_cargos
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca la estructura presupuestaria que tienen los articulos en la orden de compra
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 13/08/2008							Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	    $ls_sql= "SELECT * ".
		         "  FROM soc_solicitudcargos".
				 " WHERE codemp='". $as_codemp ."'".
				 "   AND numordcom='".$as_numordcom."'".
				 "   AND estcondat='".$as_escondat."'";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$lb_valido=false;
				$this->io_msg->message("CLASE->recepcion MÉTODO->uf_siv_buscar_cargos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				return false;
			}
			else
			{
 			   $li_i=0;
			   while($row=$this->io_sql->fetch_row($rs_data))
			   {
				    $li_i=$li_i + 1;
					$as_codcar= $row["codcar"];
					$as_codestpro1= $row["codestpro1"];
					$as_codestpro2= $row["codestpro2"];
					$as_codestpro3= $row["codestpro3"];
					$as_codestpro4= $row["codestpro4"];
					$as_codestpro5= $row["codestpro5"];
					$spg_cuenta= $row["spg_cuenta"];
					//$as_montobjret= $row["montobjret"];
					//$as_monret= $row["monret"];
					$as_estpresu=$as_codestpro1.$as_codestpro2.$as_codestpro3.$as_codestpro4.$as_codestpro5;
					$lb_valido=true;
				
					$ao_object[$li_i][1]="<input name=txtestpres".$li_i." type=text id=txtestpres".$li_i." class=sin-borde size=40 maxlength=15 value='".$as_estpresu."' readonly>";
					$ao_object[$li_i][2]="<input name=txtcuenta".$li_i." type=text id=txtcuenta".$li_i." class=sin-borde size=20 maxlength=15 value='".$spg_cuenta."' readonly>";
					$ao_object[$li_i][3]="<input name=txtmonto".$li_i." type=text id=txtmonto".$li_i." class=sin-borde size=12 maxlength=12 value='".number_format ($as_montonuevo,2,",",".")."' onKeyPress=return(currency_Format(this,'.',',',event)) >";
					$ao_object[$li_i][4]="<input type='checkbox' name=chkcasar".$li_i." class= sin-borde value=1>";
					
				}// fin del while
				if ($li_i==0)
				{
					$li_i=$li_i+1;
					$ao_object[$li_i][1]="<input name=txtestpres".$ai_totrows." type=text id=txtestpres".$li_i." class=sin-borde size=15 maxlength=15 readonly>";
					$ao_object[$li_i][2]="<input name=txtcuenta".$ai_totrows." type=text id=txtcuenta".$li_i." class=sin-borde size=15 maxlength=15 readonly>";
					$ao_object[$li_i][3]="<input name=txtmonto".$ai_totrows." type=text id=txtmonto".$li_i." class=sin-borde size=12 maxlength=12 >";
					$ao_object[$li_i][4]="<input type='checkbox' name=chkcasar".$li_i." class= sin-borde value=1>";
				}
			  $this->io_sql->free_result($rs_data);
			}
		return $lb_valido;
	} // end function uf_siv_buscar_cargos
	
  function uf_verificar_orden($as_codemp,$as_numordcom,$ad_fecordcom,&$lb_vali)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_verificar_orden
		//	           Access: public
		//  		Arguments: $as_codemp    // codigo de empresa
		//  			       $as_numordcom // numero de orden de compra
		//  			       $aa_coduniadm // arreglo de codigos de unidad administrativa
		//  			       $aa_denuniadm // arreglo de denominaciones de unidad administrativa
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de buscar las sep asociadas a una orden de compra al igual que las unidades 
		//                     administrativas
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 03/11/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_vali=0;
		$ad_fecordcom=$this->io_funcion->uf_convertirdatetobd($ad_fecordcom);
		$ls_sql="SELECT estpenalm from soc_ordencompra".
				 " WHERE codemp='".$as_codemp."'".
				 "   AND numordcom='".$as_numordcom."'".
				 "   AND estcondat = 'B'".
				 "   AND fecordcom='".$ad_fecordcom."'".
				 "   AND estpenalm=1"; 
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cerrar_oc MÉTODO->uf_verificar_orden ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		  if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
                $lb_vali=1;
			}
			else
			{
				$lb_valido=false;
				$lb_vali=0;
			}
			
		}
		return $lb_valido; 
	} //fin  function uf_verificar_orden
   
}//end  class tepuy_siv_c_cerraroc
?>
