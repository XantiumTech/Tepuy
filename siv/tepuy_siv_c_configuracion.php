<?php
require_once("../shared/class_folder/class_sql.php");
class tepuy_siv_c_configuracion
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

   //---------------------------------------------------------------------------------------------------------------------------
	function tepuy_siv_c_configuracion()
	{
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_fecha.php");
		$in=new tepuy_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=    new class_sql($this->con);
		$this->seguridad= new tepuy_c_seguridad();
		$this->io_msg=    new class_mensajes();
		$this->io_funcion=new class_funciones();
		$this->io_fec= new class_fecha();
	}
   //---------------------------------------------------------------------------------------------------------------------------
	

	function uf_siv_certifica_archivo($as_codemp,$as_archivo,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_certifica_archivo
		//      Argumento: $as_coemp     // codigo de la empresa
		//                 $as_archivo  // archivo a validar
		//		   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda dentro del archivo que certifique que tiene el formato de importacion
		//		   Correcto, valida primera y ultima linea
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 12/06/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$valido=false;
		//print "Logro conseguir el Archivo: ". $_FILES['fichero']['tmp_name'];//.$as_archivo;
		//echo "Archivo: ".file_exists($as_archivo);
		if (file_exists($as_archivo)) // revisa archivo
		{
			$archivo=@fopen("$as_archivo","r"); //abrimos el archivo para escritura
			if ($archivo)
			{
				$linea=1;
	    			while (($line = fgets($archivo)) !== false)
				{
					if($linea==1)
					{
						if(substr($line,0,9)=="@TepuySCV")
						{
							$valido=true;
							//print substr($line,9,4);die();
						}
						if(substr($line,9,4)==substr($_SESSION["la_empresa"]["periodo"],0,4))
						{
							$this->io_msg->message("El archivo $as_archivo no puede ser incorporado al mismo Ejercicio Economico Financiero. Consulte al Administrador del Sistema");
							$valido=false;
						}
					}
					$linea=$linea+1;
				}
			}
			fclose($archivo);
		}
		return $valido;
	} // Fin de uf_siv_certifica_archivo
   //---------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
	function uf_siv_importar_archivo($as_codemp,$as_archivo,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_importar_archivo
		//      Argumento: $as_coemp     // codigo de la empresa
		//                 $as_archivo  // archivo a validar
		//		   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda dentro del archivo que certifique que tiene el formato de importacion
		//		   Correcto, valida primera y ultima linea
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 12/06/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_archivo=trim($as_archivo);
		$lb_valido=false;
		//print "X".$as_archivo."X";
		if(substr(trim($as_archivo),1,1)==":")
		{
			$as_archivo=substr($as_archivo,12,strlen($as_archivo));
		}
		$as_archivo="txt/".$as_archivo;
		//print "Archivo: ".$as_archivo;
		$archivo=@fopen("$as_archivo","r"); //abrimos el archivo para lectura
		if ($archivo)
		{
			//$cuantas=0;
	    		while (($ls_registro = fgets($archivo)) !== false)
			{
				$ls_registro=trim($ls_registro);
				if(substr($ls_registro,0,1)=='"')
				{	// Comienzo de linea elimino doble comilla
					//print $ls_registro;
					$ls_registro = str_replace('"', "", $ls_registro);
					//$cuantas=$cuantas+1;
					//print "Van ".$cuantas." Filas";
				}
				$ls_lectura=substr($ls_registro,0,6);
				switch ($ls_lectura) 
				{
					case "@Tepuy": //@TepuySCV Linea de identificacion de archivo e inicio de tabla
						$tabla=substr($ls_registro,14,strlen($ls_registro));
						$ls_ejercicio=substr($ls_registro,9,4);
						$ls_cadena="";
						$ls_cabecera="";
						break;
					case "Fin@Te"://Fin@TepuySCV Linea de final de tabla
						$ls_cadena="";
						$ls_cabecera="";
						break;
					case 'INSERT': //INSERT IN (Linea de encabezado de registro
						$ls_cabecera=substr($ls_registro,0,strlen($ls_registro));
						$ls_cadena="";
						break;
					case '`codem': //INSERT IN (Linea de encabezado de registro
						$ls_cabecera=$ls_cabecera.$ls_registro.") ";
						$ls_cadena="";
						break;
					case '`nummo': //INSERT IN (Linea de encabezado de registro
						$ls_cabecera=$ls_cabecera.$ls_registro.") ";
						$ls_cadena="";
						break;
					default:
						$ls_cadena=$ls_cabecera." VALUES ".$ls_registro;
						$li_row=$this->io_sql->execute($ls_cadena);
						//print $ls_cadena;
						if($li_row)
						{
							//print $ls_cadena;
							$lb_valido=true;
						}
				}
		
			}
		fclose($archivo);
		if($lb_valido)
		{
			$this->io_sql->commit();
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó Inventario Inicial ".$ls_ejercicio;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
		}
	return $lb_valido;
	} // Fin de uf_siv_importar_archivo
   //---------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
	function uf_siv_exportar_archivo($as_codemp,$as_archivo,$li_articulo,$li_asiento,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_certifica_archivo
		//      Argumento: $as_coemp     // codigo de la empresa
		//                 $as_archivo  // archivo a validar
		//		   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda dentro del archivo que certifique que tiene el formato de importacion
		//		   Correcto, valida primera y ultima linea
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 12/06/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_file=$as_archivo;
		$as_archivo="txt/".$as_archivo;
		$archivoscv=$as_archivo;
		//print "Logro conseguir el Archivo: ". $_FILES['fichero']['tmp_name'];//.$as_archivo;
		//print "Archivo: ".$as_archivo." Articulo: ".$li_articulo." Inicial: ".$li_asiento;
		$valido=true;
		if (file_exists($as_archivo)) // revisa archivo
		{
			//print "Existe";
			if(@unlink("$as_archivo")===false)
			{ // Si falla la eliminacion del archivo
				$this->io_msg->message("El archivo $as_archivo no puede ser reemplazado, consulte al Administrador del Sistema");
				$valido=false;
			}
			else
			{ // Si elimina el archivo logramos abrirlo nuevamente para proceder a cargarle los datos
				//$this->io_msg->message("El archivo $as_archivo fue eliminado");
				$valido=true;
			}
		}
		if($valido)
		{
			//header( 'Content-Type: text/csv' );
			//header( 'Content-Disposition: attachment;filename='.$archivoscv);
			$fp = fopen("$as_archivo","w");
			$periodo=substr($_SESSION["la_empresa"]["periodo"],0,4);
			$sepcampo = ";"; //separador de campo
			$sep_linea[]="  ";
			/// Exporta Tabla de Articulos //
			if($li_articulo==1)
			{
				$query = "SHOW COLUMNS FROM siv_articulo";
				$result = $this->io_sql->select($query);
				$linea1[]="@TepuySCV".$periodo."|siv_articulo";
				fputcsv($fp, $linea1);
				//print $linea1;
				//$hasta = $this->io_sql->num_rows($result);
				$header1[]='INSERT INTO `siv_articulo` (';
				fputcsv($fp, $header1); 
				while ($row = $this->io_sql->fetch_row($result))
				{
					$header2[] = "`".$row["Field"]."`";
					//print $row["Field"];
			
				}
				$this->io_sql->free_result($result);
				fputcsv($fp, $header2,','); 
				$ls_sql="SELECT * FROM siv_articulo WHERE codemp='".$as_codemp."'";
				//print $ls_sql; die();
				$rs_data=$this->io_sql->select($ls_sql);
				if($rs_data)
				{
					while($row = $this->io_sql->fetch_row($rs_data))
					{
						$codart="'".$row["codart"]."', ";
						$denart="'".$row["denart"]."', ";
						$codtipart="'".$row["codtipart"]."', ";
						$codunimed="'".$row["codunimed"]."', ";
						$feccreart="'".$row["feccreart"]."', ";
						$obsart="'".$row["obsart"]."', ";
						$exiart=$row["exiart"].", ";
						$exiiniart=$row["exiiniart"].", ";
						$minart=$row["minart"].", ";
						$maxart=$row["maxart"].", ";
						$reoart=$row["reoart"].", ";
						$prearta=$row["prearta"].", ";
						$preartb=$row["preartb"].", ";
						$preartc=$row["preartc"].", ";
						$preartd=$row["preartd"].", ";
						$fecvenart="'".$row["fecvenart"]."', ";
						$codcatsig="'".$row["codcatsig"]."', ";
						$spg_cuenta="'".$row["spg_cuenta"]."', ";
						$sc_cuenta="'".$row["sc_cuenta"]."', ";
						$pesart=$row["pesart"].", ";
						$altart=$row["altart"].", ";
						$ancart=$row["ancart"].", ";
						$proart=$row["proart"].", ";
						$ultcosart=$row["ultcosart"].", ";
						$cosproart=$row["cosproart"].", ";
						$fotart="'".$row["fotart"]."', ";
						$preartaaux=$row["preartaaux"].", ";
						$preartbaux=$row["preartbaux"].", ";
						$preartcaux=$row["preartcaux"].", ";
						$preartdaux=$row["preartdaux"].", ";
						//$ultcosartaux=$row["ultcosartaux"].", ";
						//$cosproartaux=$row["cosproartaux"].", ";
						if(strlen(trim($row["ultcosartaux"]))==0)
						{
							$ultcosartaux='NULL, ';
						}
						else
						{
							$ultcosartaux=$row["ultcosartaux"].", ";
						}
						if(strlen(trim($row["cosproartaux"]))==0)
						{
							$cosproartaux='NULL, ';
						}
						else
						{
							$cosproartaux=$row["cosproartaux"].", ";
						}
						$serart="'".$row["serart"]."', ";
						$ubiart="'".$row["ubiart"]."', ";
						$docart="'".$row["docart"]."', ";
						$fabart="'".$row["fabart"]."', ";
						$codmil="'".$row["codmil"]."', ";
						$estact="'".$row["estact"]."', ";
						if(strlen(trim($row["codact"]))==0)
						{
							$codact='NULL, ';
						}
						else
						{
							$codact="'".$row["codact"]."', ";
						}
						$codseg="'".$row["codseg"]."', ";
						$codfami="'".$row["codfami"]."', ";
						$codclase="'".$row["codclase"]."', ";
						$codprod="'".$row["codprod"]."' ";
						$reg[0]="('".$as_codemp."', ".$codart.$denart.$codtipart.$codunimed.$feccreart.$obsart.$exiart.$exiiniart.$minart.$maxart.$reoart.$prearta.$preartb.$preartc.$preartd.$fecvenart.$codcatsig.$spg_cuenta.$sc_cuenta.$pesart.$altart.$ancart.$proart.$ultcosart.$cosproart.$fotart.$preartaaux.$preartbaux.$preartcaux.$preartdaux.$ultcosartaux.$cosproartaux.$serart.$ubiart.$docart.$fabart.$codmil.$estact.$codact.$codseg.$codfami.$codclase.$codprod.");";
						fputcsv($fp, $reg);
						//print $row["codart"];
					}
					$linea2[]="Fin".$linea1[0];
					fputcsv($fp, $linea2);
					//fputcsv($fp, $sep_linea);
				}
			}
			/// Fin de Tabla de Articulos //

			/// Inicia Asiento de Apertura proximo ejercicio ///
			if($li_asiento==1)
			{
				$ls_sqlintfec=" AND fecmov >='".$periodo."-01-01' AND fecmov <='".$periodo."-12-31' ";
				$query = " SELECT siv_articulo.*,".
					"        (SELECT count(codart) FROM siv_articulo) AS total, ".
					"        (SELECT denunimed FROM siv_unidadmedida".
					"          WHERE siv_unidadmedida.codunimed=siv_articulo.codunimed) AS denunimed,".
					"        (SELECT unidad FROM siv_unidadmedida".
					"          WHERE siv_unidadmedida.codunimed=siv_articulo.codunimed) AS unidad,".
					"        (SELECT SUM(canart) FROM siv_dt_movimiento".
					"          WHERE siv_dt_movimiento.codart=siv_articulo.codart".
					"            AND siv_dt_movimiento.opeinv='ENT'".
					"            AND siv_dt_movimiento.codprodoc<>'REV'".
					"            AND siv_dt_movimiento.canart > 0 ". $ls_sqlintfec ."".
					"		     AND numdocori NOT IN (SELECT numdoc FROM siv_dt_movimiento".
					"                                   WHERE codemp='". $as_codemp ."'".
   					"                                     AND canart > 0".
					"                                     AND codprodoc ='REV')) AS entradas,".
					"        (SELECT SUM(canart) FROM siv_dt_movimiento".
					"          WHERE siv_dt_movimiento.codart=siv_articulo.codart".
					"            AND siv_dt_movimiento.opeinv='SAL' ". $ls_sqlintfec ."".
					"            AND siv_dt_movimiento.codprodoc<>'REV'".
					"		     AND numdocori NOT IN (SELECT numdoc FROM siv_dt_movimiento".
					"                                   WHERE codemp='". $as_codemp ."'".
   					"                                     AND canart > 0".
					"                                     AND codprodoc ='REV')) AS salidas".
					" FROM siv_articulo".
					" WHERE codemp='".$as_codemp."' ".
					" ORDER BY codart";
				$result = $this->io_sql->select($query);
				$existe_inventario=false;
				while($row = $this->io_sql->fetch_row($result))
				{
					$ls_entradas = $row["entradas"];
					$ls_salidas = $row["salidas"];
					$inventario=$ls_entradas-$ls_salidas;
					if($inventario>0)
					{
						$existe_inventario=true;
					}
				}
				$this->io_sql->free_result($result);
				if($existe_inventario) // Si existen registros a exportar
				{
					////////////// Extrae datos para la Tabla: siv_articuloalmacen
					$linea3[]="@TepuySCV".$periodo."|siv_articuloalmacen";
					$ls_sql = "SHOW COLUMNS FROM siv_articuloalmacen";
					$result = $this->io_sql->select($ls_sql);
					$linea1[]="@TepuySCV".$periodo."|siv_articuloalmacen";
					fputcsv($fp, $linea1);
					//print $linea1;
					//$hasta = $this->io_sql->num_rows($result);
					$header3[]="INSERT INTO `siv_articuloalmacen` (";
					fputcsv($fp, $header3); 
					while ($row = $this->io_sql->fetch_row($result))
					{
						$header4[] = "`".$row["Field"]."`";
					}
					$this->io_sql->free_result($result);
					fputcsv($fp, $header4,',');
					$result = $this->io_sql->select($query);

					$ls_almacen="0000000001";
					while($row = $this->io_sql->fetch_row($result))
					{
						$ls_codart = $row["codart"];
						$ls_entradas = $row["entradas"];
						$ls_salidas = $row["salidas"];
						$ls_codunimed=$row["codunimed"];
						$inventario=$ls_entradas-$ls_salidas;
						if($inventario>0)
						{
							$reg[0]="('".$as_codemp."', '".$ls_codart."', '".$ls_almacen."', '".$ls_codunimed."', ".$inventario.");";
							fputcsv($fp, $reg);
						}
					}
					$this->io_sql->free_result($result);
					$result = $this->io_sql->select($query);
					$linea4[]="Fin".$linea3[0];
					fputcsv($fp, $linea4);
					//fputcsv($fp, $sep_linea);
					//rewind( $fp );
					/// Fin de Estraccion de Datos para Tabla: siv_articuloalmacen ///

					// Extrae datos para la Tabla: siv_dt_movimiento
					$linea5[]="@TepuySCV".$periodo."|siv_dt_movimiento";
					fputcsv($fp, $linea5);
					$ls_sql = "SHOW COLUMNS FROM siv_dt_movimiento";
					$result = $this->io_sql->select($ls_sql);
					$header5[]="INSERT INTO `siv_dt_movimiento` (";
					fputcsv($fp, $header5); 
					while ($row = $this->io_sql->fetch_row($result))
					{
						$header6[] = "`".$row["Field"]."`";
					}
					$this->io_sql->free_result($result);
					fputcsv($fp, $header6,','); 
					$result = $this->io_sql->select($query);
					$periodo1=$periodo+1;
					$ls_nummov="INVINI-0101".$periodo1;
					$ld_fecha=$periodo1."-01-01";
					$ls_operacion="ENT";
					$ls_codigo="INV";
					$ls_promo="RPC";
					while($row1 = $this->io_sql->fetch_row($result))
					{
						$ls_codart = $row1["codart"];
						$ls_ultcosart = $row1["ultcosart"];
						$ls_entradas = $row1["entradas"];
						$ls_salidas = $row1["salidas"];
						$inventario=$ls_entradas-$ls_salidas;
						//print "Inventario: ".$inventario;
						if($inventario>0)
						{
							$reg[0]="('".$as_codemp."', '".$ls_nummov."', '".$ld_fecha."', '".$ls_codart."', '".$ls_almacen."', '".$ls_operacion."', '".$ls_codigo."', '".$ls_nummov."', ".$inventario.",".$ls_ultcosart.", '".$ls_promo."', '".$ls_nummov."',".$inventario.", '".$ld_fecha."', NULL);";
							fputcsv($fp, $reg);
						}
					}
					$linea6[]="Fin".$linea5[0];
					fputcsv($fp, $linea6);
					$this->io_sql->free_result($result);
					// Fin de Estraccion de Datos para Tabla: siv_dt_movimiento ///

					// Extrae datos para la Tabla: siv_dt_recepcion //
					$linea7[]="@TepuySCV".$periodo."|siv_dt_recepcion|";
					fputcsv($fp, $linea7);
					$ls_sql = "SHOW COLUMNS FROM siv_dt_recepcion";
					$result = $this->io_sql->select($ls_sql);
					$header7[]="INSERT INTO `siv_dt_recepcion` (";
					fputcsv($fp, $header7); 
					while ($row = $this->io_sql->fetch_row($result))
					{
						$header8[] = "`".$row["Field"]."`";
					}
					$this->io_sql->free_result($result);
					fputcsv($fp, $header8,',');
					$ls_orden=1;
					$ls_penart=0.00;
					$ls_unidad="M";
					$result = $this->io_sql->select($query);
					while($row1 = $this->io_sql->fetch_row($result))
					{
						$ls_codart = $row1["codart"];
						$ls_ultcosart = $row1["ultcosart"];
						$ls_entradas = $row1["entradas"];
						$ls_salidas = $row1["salidas"];
						$ls_codunimed=$row1["codunimed"];
						$inventario=$ls_entradas-$ls_salidas;
						$ls_subtotal=$ls_ultcosart*$inventario;
						if($inventario>0)
						{
							$reg[0]="('".$as_codemp."', '".$ls_nummov."', '".$ls_nummov."', '".$ls_codart."', '".$ls_codunimed."', ".$inventario.", ".$inventario.", ".$ls_penart.", ".$ls_ultcosart.", ".$ls_subtotal.", ".$ls_subtotal.", ".$ls_orden.", NULL, NULL, NULL, 0);";
							fputcsv($fp, $reg);
							$ls_orden=$ls_orden+1;
						}
					}
					$linea8[]="Fin".$linea7[0];
					fputcsv($fp, $linea8);
					$this->io_sql->free_result($result);
					// Fin de Estraer datos de la Tabla: siv_dt_recepcion //

					// Extrae Datos para la Tabla siv_movimiento //
					$linea9[]="@TepuySCV".$periodo."|siv_movimiento|";
					fputcsv($fp, $linea9);
					$ls_sql = "SHOW COLUMNS FROM siv_movimiento";
					$result = $this->io_sql->select($ls_sql);
					$header9[]="INSERT INTO `siv_movimiento` (";
					fputcsv($fp, $header9); 
					while ($row = $this->io_sql->fetch_row($result))
					{
						$header10[] = "`".$row["Field"]."`";
					}
					$this->io_sql->free_result($result);
					fputcsv($fp, $header10,',');
					$reg[0]="('".$ls_nummov."', '".$ld_fecha."', '"."Recepcion"."', '".$_SESSION["la_logusr"]."');"; 
					fputcsv($fp, $reg);
					$linea10[]="Fin".$linea9[0];
					fputcsv($fp, $linea10);
					// Fin de proceso de extraer Datos para la Tabla siv_movimiento //

					// Extrae Datos para la Tabla siv_recepcion //
					$linea11[]="@TepuySCV".$periodo."|siv_recepcion|";
					fputcsv($fp, $linea11);
					$ls_sql = "SHOW COLUMNS FROM siv_recepcion";
					$result = $this->io_sql->select($ls_sql);
					$header11[]="INSERT INTO `siv_recepcion` (";
					fputcsv($fp, $header11); 
					while ($row = $this->io_sql->fetch_row($result))
					{
						$header12[] = "`".$row["Field"]."`";
					}
					$this->io_sql->free_result($result);
					fputcsv($fp, $header12);
					$reg[0]='';
					$reg[0]="('".$as_codemp."', '".$ls_nummov."', '".$ls_nummov."', '', '".$ls_almacen."', '".$ld_fecha."', 'INVENTARIO INICIAL', '".$_SESSION["la_logusr"]."', 1,1,1);";
					fputcsv($fp, $reg);
					$linea12[]="Fin".$linea11[0];
					fputcsv($fp, $linea12);
					// Extrae Datos para la Tabla siv_recepcion //

				} // End If (Cuando existen movimientos a exportar //
			}/// Fin del Asiento de Apertura proximo ejercicio ///
			fclose( $fp );
		}
	return $valido;
	} // Fin de uf_siv_exportar_archivo
   //---------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
	function uf_siv_load_configuracion(&$as_metodo,&$as_estcatsig,&$as_estnum,&$as_estcmp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_configuracion
		//         Access: public (tepuy_siv_d_configuracion)
		//      Argumento: $as_metodo     // metodo de inventario
		//                 $as_estcatsig  // estatus de uso del catalogo sigescof
		//                 $as_estnum     // estatus de la codificacion de los articulos
		//                 $as_estcmp     // estatus que indica si se desea autocompletar con ceros a la izquierda
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda del metodo que esta registrado en la tabla siv_config
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 10/02/2017							Fecha Última Modificación : 25/08/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT id, metodo, estcatsig, estnum, estcmp".
				"  FROM siv_config  ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->configuracion MÉTODO->uf_siv_select_configuracion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_metodo=$row["metodo"];
				$as_estcatsig=$row["estcatsig"];
				$as_estnum=$row["estnum"];
				$as_estcmp=$row["estcmp"];
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;

	}// end  function uf_siv_load_configuracion
   //---------------------------------------------------------------------------------------------------------------------------
	
   //---------------------------------------------------------------------------------------------------------------------------
	function uf_siv_select_configuracion($as_id,$as_metodo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_configuracion
		//         Access: public (tepuy_siv_d_configuracion)
		//      Argumento: $as_id        // codigo del metodo
		//                 $as_metodo    // denominación del metodo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda del metodo que esta registrado en la tabla siv_config
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 10/02/2017							Fecha Última Modificación : 10/02/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM siv_config  ".
				  " WHERE id='".$as_id."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->configuracion MÉTODO->uf_siv_select_configuracion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

	}// end  function uf_siv_select_configuracion

   //---------------------------------------------------------------------------------------------------------------------------
	function uf_process_configuracion($as_codemp,$as_id,$as_metodo,$ai_estcatsig,$ai_estnum,$ai_estcmp,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_process_configuracion
		//         Access: public (tepuy_siv_d_configuracion)
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_id        // codigo del metodo
		//                 $as_metodo    // denominación del metodo
		//                 $ai_estcatsig // estatus de uso del catalogo SIGECOF
		//                 $ai_estnum    // estatus de uso del codigo de articulo
		//                 $ai_estcmp    // estatus que indica si se desea autocompletar con ceros a la izquierda
	    //  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion donde se procesa la configuracion del inventario
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 03/10/2007							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe= $this->uf_siv_select_configuracion($as_id,$as_metodo);
		if ($lb_existe)
		{
			$ld_date= date("d/m/Y");
			$lb_existe=$this->uf_siv_select_articulos($as_codemp);
			if($lb_existe)
			{
				$lb_existe=$this->uf_siv_select_movimientos($as_codemp);
				if($lb_existe)
				{
					$lb_pervalido=$this->io_fec->uf_valida_fecha_periodo($ld_date,$as_codemp);
					if(!$lb_pervalido)
					{
						$lb_valido=$this->uf_siv_procesar_configuracion($as_id,$as_metodo,$ai_estcatsig,$ai_estnum,$ai_estcmp,$aa_seguridad);
						if($lb_valido)
						{$this->io_msg->message("La configuración de inventario fue actualizada.");}	
						else
						{$this->io_msg->message("La configuración de inventario no pudo ser actualizada");}
					}
					else
					{$this->io_msg->message("Ya existe un metodo de Inventario para este periodo");}
						
				}
				else
				{
					$lb_valido=$this->uf_siv_procesar_configuracion($as_id,$as_metodo,$ai_estcatsig,$ai_estnum,$ai_estcmp,$aa_seguridad);
					if($lb_valido)
					{$this->io_msg->message("La configuración de inventario fue actualizada.");}	
					else
					{$this->io_msg->message("La configuración de inventario no pudo ser actualizada");}
				}
			}
			else
			{
				$lb_valido=$this->uf_siv_procesar_configuracion($as_id,$as_metodo,$ai_estcatsig,$ai_estnum,$ai_estcmp,$aa_seguridad);

				if($lb_valido)
				{$this->io_msg->message("La configuración de inventario fue actualizada.");}	
				else
				{$this->io_msg->message("La configuración de inventario no pudo ser actualizada");}
			}
		}
		else
		{
			$lb_valido=$this->uf_siv_procesar_configuracion($as_id,$as_metodo,$ai_estcatsig,$ai_estnum,$ai_estcmp,$aa_seguridad);
			if($lb_valido)
			{$this->io_msg->message("La configuración de inventario fue actualizada.");}	
			else
			{$this->io_msg->message("La configuración de inventario no pudo ser actualizada");}
		}
					
	}// end  function uf_process_configuracion
   //---------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
	function uf_siv_insert_configuracion($as_id,$as_metodo,$ai_estcatsig,$ai_estnum,$ai_estcmp,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_configuracion
		//         Access: public (tepuy_siv_d_configuracion)
		//      Argumento: $as_id        // codigo del metodo
		//                 $as_metodo    // denominación del metodo
		//                 $ai_estcatsig // estatus de uso del catalogo SIGECOF
		//                 $ai_estnum    // estatus de uso del codigo de articulo
		//                 $ai_estcmp    // estatus que indica si se desea autocompletar con ceros a la izquierda
	    //  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un metodo de inventario en la tabla de siv_config. Solo debe existir un metodo 
	    //				   registrado en esta tabla.
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 10/02/2017							Fecha Última Modificación : 25/08/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO siv_config (id,metodo,estcatsig,estnum,estcmp) ".
				  "VALUES(".$as_id.",'".$as_metodo."',".$ai_estcatsig.",".$ai_estnum.",".$ai_estcmp.")" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->configuracion MÉTODO->uf_siv_insert_configuracion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la configuracion ".$as_metodo;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
		return $lb_valido;
	} // end function uf_siv_insert_configuracion
   //---------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
	function uf_siv_update_configuracion($as_id,$as_metodo,$ai_estcatsig,$ai_estnum,$ai_estcmp,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_configuracion
		//         Access: public (tepuy_siv_d_configuracion)
		//      Argumento: $as_id        // codigo del metodo
		//                 $as_metodo    // denominación del metodo
		//                 $li_estcatsig    // estatus de uso del catalogo SIGECOF
		//                 $ai_estnum    // estatus de uso del codigo de articulo
		//                 $ai_estcmp    // estatus que indica si se desea autocompletar con ceros a la izquierda
	    //  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica el metodo de inventario en la tabla de siv_config. Solo debe existir un metodo 
	    //				   registrado en esta tabla.
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 10/02/2017							Fecha Última Modificación : 25/08/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $this->io_sql->begin_transaction();
		 $ls_sql = "UPDATE siv_config".
		 		   "   SET metodo='". $as_metodo ."',".
				   "       estcatsig='". $ai_estcatsig ."',".
				   "       estnum='". $ai_estnum ."',".
				   "       estcmp='". $ai_estcmp ."'".
				   " WHERE id='" . $as_id ."'";
		 $li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->configuracion MÉTODO->uf_siv_update_configuracion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la configuracion ".$as_metodo;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end function uf_siv_update_configuracion
   //---------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
	function uf_siv_procesar_configuracion($as_id,$as_metodo,$ai_estcatsig,$ai_estnum,$ai_estcmp,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_procesar_configuracion
		//         Access: public (tepuy_siv_d_configuracion)
		//      Argumento: $as_id        // codigo del metodo
		//                 $as_metodo    // denominación del metodo
		//                 $ai_estcatsig    // estatus de uso del catalogo SIGECOF
		//                 $ai_estnum    // estatus de uso del codigo de articulo
		//                 $ai_estcmp    // estatus que indica si se desea autocompletar con ceros a la izquierda
	    //  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza el proceso de buscar el metodo de una configuración, en caso de no existir ninguna
	    //                 insertarlo, caso contrario actualizarlo.
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 10/02/2017							Fecha Última Modificación : 10/02/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=false;
		$lb_existe=$this->uf_siv_select_configuracion($as_id,$as_metodo);
		if($lb_existe)
		{
			$lb_valido=$this->uf_siv_update_configuracion($as_id,$as_metodo,$ai_estcatsig,$ai_estnum,$ai_estcmp,$aa_seguridad);
		}
		else
		{
			$lb_valido=$this->uf_siv_insert_configuracion($as_id,$as_metodo,$ai_estcatsig,$ai_estnum,$ai_estcmp,$aa_seguridad);
		}			
		return $lb_valido;

	} // end function uf_siv_procesar_configuracion
   //---------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
	function uf_siv_select_articulos($as_codemp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_articulos
		//         Access: public (tepuy_siv_d_configuracion)
		//      Argumento: $as_codemp  // codigo de empresa
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen articulos asociados a la empresa
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 07/06/2017							Fecha Última Modificación : 07/06/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codart".
				"  FROM siv_articulo  ".
				" WHERE codemp='".$as_codemp."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->configuracion MÉTODO->uf_siv_select_articulos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

	}// end  function uf_siv_select_articulos
   //---------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
	function uf_siv_select_movimientos($as_codemp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_movimientos
		//         Access: public (tepuy_siv_d_configuracion)
		//      Argumento: $as_codemp  // codigo de empresa
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen movimientos de inventario  asociados a la empresa
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 25/07/2017							Fecha Última Modificación : 25/07/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM siv_movimiento";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->configuracion MÉTODO->uf_siv_select_movimientos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

	}// end  function uf_siv_select_movimientos
   //---------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
	function uf_siv_load_configuraciondespacho($as_codemp,&$as_value)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_configuraciondespacho
		//         Access: public (tepuy_siv_d_configuracion)
		//      Argumento: $as_codemp     // codigo de empresa
		//                 $as_estcatsig  // estatus de contabilizacion de despacho
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda del estatus de contabilizacion de los despachos
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 11/01/2007							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT value".
		          "  FROM tepuy_config".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND codsis='SIV'".
				  "   AND seccion='CONFIG'".
				  "   AND entry='CONTA DESPACHO'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->configuracion MÉTODO->uf_siv_load_configuraciondespacho ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_value=$row["value"];
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;

	}// end  function uf_siv_load_configuraciondespacho
   //---------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
	function uf_siv_insert_configuraciondespacho($as_codemp,$as_value,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_configuraciondespacho
		//         Access: public (tepuy_siv_d_configuracion)
		//      Argumento: $as_codemp     // codigo de empresa
		//                 $as_estcatsig  // estatus de contabilizacion de despacho
	    //  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta el estatus de la contabilizacion del despacho de inventario
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 11/01/2007							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO tepuy_config (codemp,codsis,seccion,entry,type,value) ".
				  "VALUES('".$as_codemp."','SIV','CONFIG','CONTA DESPACHO','','".$as_value."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->configuracion MÉTODO->uf_siv_insert_configuracion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el estatus de contabilización de despacho ".$as_value." para la empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
		return $lb_valido;
	} // end function uf_siv_insert_configuraciondespacho
   //---------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
	function uf_siv_update_configuraciondespacho($as_codemp,$as_value,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_configuraciondespacho
		//         Access: public (tepuy_siv_d_configuracion)
		//      Argumento: $as_codemp     // codigo de empresa
		//                 $as_estcatsig  // estatus de contabilizacion de despacho
	    //  			   $aa_seguridad  // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica el metodo de inventario en la tabla de siv_config. Solo debe existir un metodo 
	    //				   registrado en esta tabla.
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 10/02/2017							Fecha Última Modificación : 25/08/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $this->io_sql->begin_transaction();
		 $ls_sql = "UPDATE tepuy_config".
		 		   "   SET value='".$as_value."'".
				   " WHERE codemp='".$as_codemp."'".
				   "   AND codsis='SIV'".
				   "   AND seccion='CONFIG'".
				   "   AND entry='CONTA DESPACHO'";
		 $li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->configuracion MÉTODO->uf_siv_update_configuraciondespacho ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el estatus de contabilización de despacho ".$as_value." para la empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end function uf_siv_update_configuraciondespacho
   //---------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------
	function uf_siv_procesar_configuraciondespacho($as_codemp,$as_value,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_procesar_configuraciondespacho
		//         Access: public (tepuy_siv_d_configuracion)
		//      Argumento: $as_codemp     // codigo de empresa
		//                 $as_estcatsig  // estatus de contabilizacion de despacho
	    //  			   $aa_seguridad  // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza el proceso de buscar el estatus de contabilizacion del despacho, en caso de 
	    //                 no existir ninguna insertarlo, caso contrario actualizarlo.
		//	   Creado Por: Ing.Miguel Palencia
		// Fecha Creación: 11/01/2017							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=false;
		$as_valueaux=$as_value;
		$lb_existe=$this->uf_siv_load_configuraciondespacho($as_codemp,$as_valueaux);
		if($lb_existe)
		{
			$lb_valido=$this->uf_siv_update_configuraciondespacho($as_codemp,$as_value,$aa_seguridad);
		}
		else
		{
			$lb_valido=$this->uf_siv_insert_configuraciondespacho($as_codemp,$as_value,$aa_seguridad);
		}			
		return $lb_valido;

	} // end function uf_siv_procesar_configuraciondespacho
   //---------------------------------------------------------------------------------------------------------------------------

} // end class tepuy_siv_c_configuracion
?>
