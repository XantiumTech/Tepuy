<?PHP
class tepuy_sno_c_metodo_banco_1
{
	var $io_mensajes;
	var $io_metbanco;
	var $io_sno;
	var $ls_codemp;
	var $ls_nomemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_sno_c_metodo_banco_1()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_sno_c_metodo_banco_1
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 04/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();
		require_once("tepuy_sno.php");
		$this->io_sno=new tepuy_sno();
		require_once("tepuy_snorh_c_metodobanco.php");
		$this->io_metbanco=new tepuy_snorh_c_metodobanco();
   		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_nomemp=$_SESSION["la_empresa"]["nombre"];
		$this->ls_rifemp=$_SESSION["la_empresa"]["rifemp"];
		$this->ls_siglas=$_SESSION["la_empresa"]["titulo"];
	}// end function tepuy_sno_c_metodo_banco_1
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_bod($as_ruta,$ac_nroperi,$ad_fdesde,$ad_fhasta,$aa_ds_banco)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_bod
		//		   Access: public 
		//	    Arguments: ac_nroperi  // codigo del periodo
		//                 ad_fdesde   // fecha desde
		//                 ad_fhasta   // fecha hasta
		//                 aa_ds_banco // arreglo (datastore) datos banco      
		//	  Description: genera el archivo txt a disco para  el banco BOD para pago de nomina
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 04/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$ldec_MontoPrevio=0;
		$ldec_MontoAcumulado=0;
		$li_NroDebitosPrev=0;
		$li_NroCreditosPrev=0;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$ls_desperi="";
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if ($li_count > 0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{	//Numero de cuenta del empleado
				$ls_codcueban=$aa_ds_banco->data["codcueban"][$li_i];
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,12);
				//Monto total a cancelar 
				$ldec_neto = $aa_ds_banco->data["monnetres"][$li_i]; //debo verificar si en el ds ya viene modificado la coma decimal
				$ldec_neto = ($ldec_neto*100);  
				$ldec_neto = $this->io_funciones->uf_cerosizquierda($ldec_neto,15);
				//cedula del empleado
				$ls_cedemp = $this->io_funciones->uf_rellenar_der($aa_ds_banco->data["cedper"][$li_i]," ",15);
				$ls_cadena = $ls_cedemp.$ls_codcueban.$ldec_neto.$ls_desperi."\r\n";
				if ($ls_creararchivo)  //Chequea que el archivo este abierto				
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
				$li_NroCreditosPrev = ($li_NroCreditosPrev + 1);
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_bod
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_metodo_banco_banesco_paymul($as_ruta,$aa_ds_banco,$ad_fecproc,$adec_montot,$as_codcueban,$as_ref)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_banesco_paymul
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 adec_montot // Monto total
		//                 as_codcueban // código de la cuenta bancaria a debitar 
		//	  Description: genera el archivo txt a disco para  el banco Banesco Paymul para pago de nomina
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 04/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/b_paymul.txt";
		$ls_numope=$this->io_sno->uf_select_config("SNO","GEN_DISK","BANESCO_PAYMUL_OP","1","I");
		$ls_numope=intval($this->io_funciones->uf_trim($ls_numope), 10);
		$lb_valido=$this->io_sno->uf_insert_config("SNO","GEN_DISK","BANESCO_PAYMUL_OP",$ls_numope+1,"I");
		if($lb_valido)
		{		
			$ls_numref=$this->io_sno->uf_select_config("SNO","GEN_DISK","BANESCO_PAYMUL_REF","1","I");
			$ls_numref=intval($this->io_funciones->uf_trim($ls_numref),10);
			if ($as_ref==1)
			{
				$lb_valido=$this->io_sno->uf_insert_config("SNO","GEN_DISK","BANESCO_PAYMUL_REF",$ls_numref+1,"I");
			}
		}
		if($lb_valido)
		{		
			$ls_numref=$this->io_funciones->uf_cerosizquierda($ls_numref,8);					 
			$ls_numope=substr($ls_numope,0,9);
			$ls_numope=str_pad($ls_numope,11,"0",0);
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
		}
		if($lb_valido)
		{		
			// Registro de control (Datos Fijos)
			$ls_cadena="HDR"."BANESCO        "."E"."D  95B"."PAYMUL"."P"."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;

				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido = false;
			}		
		}
		if($lb_valido)
		{		
			// Registro de encabezado
			$ad_fecproc=$this->io_funciones->uf_convertirdatetobd($ad_fecproc);
			$ad_fecproc=str_replace("-","",$ad_fecproc);
			$ls_cadena = "01".$this->io_funciones->uf_rellenar_der("SAL"," ",35).$this->io_funciones->uf_rellenar_der("9"," ",3).$this->io_funciones->uf_rellenar_der($ls_numope," ",35).$this->io_funciones->uf_cerosderecha($ad_fecproc,14)."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido = false;
			}		
		}
		if($lb_valido)
		{		
			// Registro de debito
			$ls_rifempresa=str_replace("-","",$_SESSION["la_empresa"]["rifemp"]);
			$ls_rif=$this->io_funciones->uf_rellenar_der($ls_rifempresa," ",17);
			$ldec_montot=$adec_montot;           
			$ldec_montot=($ldec_montot*100);  
			$ldec_montot=$this->io_funciones->uf_cerosizquierda($ldec_montot,15);
			$as_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
			$as_codcueban=$this->io_funciones->uf_rellenar_der(substr(trim($as_codcueban),0,34), " ", 34);
			$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
			$ls_nomemp=$this->io_funciones->uf_rellenar_der(substr(rtrim($ls_nomemp),0,35)," ",35);
			$ls_banesco=$this->io_funciones->uf_rellenar_der("BANESCO"," ",11);
			$li_nrodebitos=1;
			$ls_numref=$this->io_funciones->uf_rellenar_der($ls_numref," ",30);
			$ls_cadena="02".$ls_numref.$ls_rif.$ls_nomemp.$ldec_montot."VEF"." ".$as_codcueban.$ls_banesco.$ad_fecproc."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo, $ls_cadena)===FALSE)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido = false;
			}		
		}	
		if($lb_valido)
		{		
			//Registro de credito
			$li_nrocreditos=0;
			$li_numrecibo=0;
			$li_count=$aa_ds_banco->getRowCount("codcueban");
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$li_numrecibo=$li_numrecibo+1;
				$ls_codcueban=$aa_ds_banco->data["codcueban"][$li_i];; //Numero de cuenta del empleado
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_rellenar_der(substr(trim($ls_codcueban),0,30)," ",30);
				$li_numrecibo=$this->io_funciones->uf_cerosizquierda($li_numrecibo,8);
				$li_numrecibo=$this->io_funciones->uf_rellenar_der($li_numrecibo," ", 30);
				$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i];  //debo verificar si en el ds ya viene modificado la coma decimal
				$ldec_neto=number_format($ldec_neto,2,".","");
				$ldec_neto=number_format($ldec_neto*100,0,"","");
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,15);
				$ls_nacper=$aa_ds_banco->data["nacper"][$li_i];   //Nacionalidad
				$ls_cedper=$aa_ds_banco->data["cedper"][$li_i];   //cedula del personal
				$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper,"0",9);
				$ls_cedper=$this->io_funciones->uf_rellenar_der($ls_nacper.$ls_cedper," ",17);
				$ls_apeper=$aa_ds_banco->data["apeper"][$li_i];
				$ls_nomper=$aa_ds_banco->data["nomper"][$li_i];
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_apeper." ".$ls_nomper, " ", 70);
				$ls_const=$this->io_funciones->uf_rellenar_der("0134", " ", 11);
				$ls_space= $this->io_funciones->uf_rellenar_der(""," ",3); // (3)
				$ls_spacedir=$this->io_funciones->uf_rellenar_der(""," ",70);  //direccion (70)
				$ls_spacetel=$this->io_funciones->uf_rellenar_der(""," ",25);              //telefono (25)
				$ls_spacecicon=$this->io_funciones->uf_rellenar_der(""," ",17);                    //C.I. persona contacto  (17)
				$ls_spacenomcon=$this->io_funciones->uf_rellenar_der(""," ",35);  //Nombre persona contacto (35)
				$ls_spaceficha=$this->io_funciones->uf_rellenar_der(""," ",30);       //Ficha del personal (30)
				$ls_spaceubic=$this->io_funciones->uf_rellenar_der(""," ",21);                //Ubicacion Geografica (21)
				$li_nrocreditos=$li_nrocreditos + 1;
				$ls_cadena="03".$li_numrecibo.$ldec_neto."VEF".$ls_codcueban.$ls_const.$ls_space.$ls_cedper.$ls_personal.
							$ls_spacedir.$ls_spacetel.$ls_spacecicon.$ls_spacenomcon." ".$ls_spaceficha."  ".$ls_spaceubic."42 "."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido = false;
				}		
			} 
		}
		if($lb_valido)
		{		
			//Registro de totales
			$li_nrodebitos=$this->io_funciones->uf_cerosizquierda($li_nrodebitos,15);
			$li_nrocreditos=$this->io_funciones->uf_cerosizquierda($li_nrocreditos,15);
			$ls_cadena="06".$li_nrodebitos.$li_nrocreditos.$ldec_montot;
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido = false;
			}		
		}
		if ($lb_valido)
		{
			@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
			$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
		}
		else
		{
			@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
			$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
		}	
		return $lb_valido;		
    }// end function uf_metodo_banco_banesco_paymul
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_confederado($as_ruta,$aa_ds_banco)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_confederado
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Cofederado para pago de nomina
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=$aa_ds_banco->data["codcueban"][$li_i];
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,10);
				$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i];
				$ldec_neto=($ldec_neto*100);  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,13);
			    $ls_cadena=$ls_codcueban.substr($ldec_neto, 0, 10).".".substr($ldec_neto, 11)."+"."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_confederado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_casa_propia_2003($as_ruta,$aa_ds_banco)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_casa_propia_2003
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Casa Propia 2003 para pago de nomina
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/transfer.txt";
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_cedper=$this->io_funciones->uf_trim($aa_ds_banco->data["cedper"][$li_i]);
				$ls_nomper=trim($aa_ds_banco->data["nomper"][$li_i]);
				$ls_apeper=trim($aa_ds_banco->data["apeper"][$li_i]);
				$ls_personal=$this->io_funciones->uf_rellenar_izq(($ls_nomper." ".$ls_apeper)," ",40);
				$ls_personal=trim($ls_personal);
				$ls_codcueban=$aa_ds_banco->data["codcueban"][$li_i];
				$ls_codcueban=$this->io_funciones->uf_rellenar_izq($ls_codcueban,"0",10);
			    $ls_codcueban=substr($ls_codcueban,0,10);
				$ls_codcueban=$this->io_funciones->uf_rellenar_izq($ls_codcueban,"0",10);
				$ls_tipcuebanper=$aa_ds_banco->data["tipcuebanper"][$li_i];								
				$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i];
				$li_neto_int=substr($ldec_neto,0,17);
				$li_pos=strpos($ldec_neto,".");
				$li_neto_dec=substr($ldec_neto,$li_pos+1,2);
				$ldec_montot=$this->io_funciones->uf_trim($li_neto_int.".".$li_neto_dec);
			    $ls_cadena = $ls_cedper.",".$ls_personal.",".$ls_tipcuebanper.",".$ls_codcueban.",".$ldec_montot.","."0"."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_casa_propia_2003
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_caribe($as_ruta,$aa_ds_banco,$adec_montot,$ad_fecproc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_casa_propia_2003
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 adec_montot // monto toal a depositar
		//                 ad_fecproc // fecha de procesamiento
		//	  Description: genera el archivo txt a disco para  el banco Caribe para pago de nomina
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/carga.txt";
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			//Registro de Cabecera
			$ldec_montot=$adec_montot*100;
			$ldec_montot=$this->io_funciones->uf_cerosizquierda($ldec_montot,20);
			$li_dia=substr($ad_fecproc,0,2);
			$li_mes=substr($ad_fecproc,3,2);
			$li_ano=substr($ad_fecproc,6,4);
			$ld_fecproc=$li_dia.$li_mes.$li_ano;
			$ls_cadena=$ld_fecproc."/".intval($li_count,10)."/".$ldec_montot."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido=false;
			}		
			//Registro de Credito
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i]*100;
			    $ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,20);
				$ls_cedper=$this->io_funciones->uf_trim($aa_ds_banco->data["cedper"][$li_i]);
				$ls_codcueban=$aa_ds_banco->data["codcueban"][$li_i];
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_cadena="C"."/".$ls_codcueban."/".$ls_cedper."/".$ldec_neto."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;

				}		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_caribe
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_total(&$quincena1,&$quincena2,&$total_gen,$aa_ds_banco,$filtro_banco,$filtro_banco2,&$trabajadores)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_buscar_total
	//		   Access: public 
	//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
	//                     $quincena // numero de archivos a generar (1 o 2)
	//	  Description: Totaliza la nomina dependiendo de $quincena= numero de archivos a generar para el pago de la nomina segun el banco
	//	   Creado Por: Ing. Miguel Palencia
	// Fecha Creación: 14/08/2016 								
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ntrabajadores=$aa_ds_banco->getRowCount("codcueban");
		for($j=1;$j<=$ntrabajadores;$j++)
		{
			$abono=$aa_ds_banco->data["monnetres"][$j];
			$sudeban1=substr($aa_ds_banco->data["codcueban"][$j],0,4);
			if($sudeban1==$filtro_banco || $sudeban1==$filtro_banco2)
			{
				$total_gen=$total_gen+$abono;
				$quincena1=$quincena1+floor($abono/2);
				$resta=$abono-floor($abono/2);
				$quincena2=$quincena2+$resta;
				$trabajadores=$trabajadores+1;
			}
			//print "quincena 1:".$quincena1;
			//print "quincena 2:".$quincena2;
		} // fin del for
		//$quincena1=$quincena1."00";
		$quincena1=number_format($quincena1, 2, '', '');
		//$quincena2=str_replace(".","",$quincena2);
		$quincena2=number_format($quincena2, 2, '', '');
		//$total_gen=str_replace(".","",$total_gen);
		$total_gen=number_format($total_gen, 2, '', '');
		//print "Total".$total_gen;
		//print "1era. Quincena :".$quincena1;
		//print "2da.  Quincena :".$quincena2;
		//$total_gen=number_format($total_gen,2);
		return true;
	}// end function uf_buscar_total
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_banfoandes($as_ruta,$as_archivo,$aa_ds_banco,$ad_fecproc,$as_ctaban,$adec_montot)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_banfoandes
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//	  Description: genera el archivo txt a disco para  el banco Banfoandes para pago de nomina
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		//$ls_nombrearchivo=$as_ruta."/bicentenario-".$as_archivo.".txt";
		$li_dia=substr($ad_fecproc,0,2);
		$li_mes=substr($ad_fecproc,3,2);
		$li_ano=substr($ad_fecproc,6,4);
		$ld_fecproc=$li_dia.$li_mes.$li_ano;
		$fecha=$li_ano.$li_mes.$li_dia;
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		//$trabajadores=$this->io_funciones->uf_cerosizquierda($li_count, 4);
		$quincena1=0;
		$quincena2=0;
		$total_gen=0;
		//print $as_ctaban; die();
		$sudeban=substr($as_ctaban,0,4);
		$sudeban2='0158';
		$trabajadores=0;
		$this->uf_buscar_total($quincena1,$quincena2,$total_gen,$aa_ds_banco,$sudeban,$sudeban2,$trabajadores);
		$trabajadores=$this->io_funciones->uf_cerosizquierda($trabajadores, 4);
		// 1.680.963,27 //1.680.963.27
		//$total_gen=substr($adec_montot,0,strlen($adec_montot)-2);
		if($li_count>0)
		{
			for($i=1;$i<=$as_archivo;$i++)
			{
				//Chequea si existe el archivo.
				$ls_nombrearchivo=$as_ruta."/bicentenario-".$i.".txt";
				if (file_exists("$ls_nombrearchivo"))
				{
					if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
					{
						$lb_valido=false;
					}
					else
					{
						$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
					}
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
				}
				for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
				{
					$ls_codcueban=$aa_ds_banco->data["codcueban"][$li_i];
					$ls_cedper=$aa_ds_banco->data["cedper"][$li_i];
			    	$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
					$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban, 20);
					$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i]*100;
					// PRIMERA LINEA DEL ARCHIVO //
					if($li_i===1)
					{
						if($as_archivo==1) // un solo pago
						{
							//print "archivo mensual".$total_gen;
							$total=$total_gen;
						}
						else
						{
							if($i==1)
							{
								//$total=$this->uf_buscar_total($i,$aa_ds_banco,$li_count);
								$total=$quincena1;
							//	print "quincena1".$quincena1;
								//$total=$this->io_funciones->uf_cerosizquierda($total, 17);
							}
							else
							{
								//$total=$this->uf_buscar_total($i,$aa_ds_banco,$li_count);
								//$total1=floor($adec_montot/2);
								//$total=$adec_montot-$total1;
								$total=$quincena2;
							//	print "quincena2".$quincena2;
								//$total=str_replace(".","",$total);
								//$total=$this->io_funciones->uf_cerosizquierda($total, 17);
							}
						}
						$total=$this->io_funciones->uf_cerosizquierda($total, 17);
						$ls_cadena=$as_ctaban.$fecha.$total.$trabajadores."\r\n";
						if ($ls_creararchivo)
						{
							if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
							{
								$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
								$lb_valido=false;
							}
						}
						else
						{
							$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
							$lb_valido=false;
						}
					}
					// FIN DE LA PRIMERA LINEZ
					if($as_archivo==1)
					{
						// Con Decimal //
						$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto, 12);
					}
					else
					{
						// Sin Decimal //
						if($i==1)
						{
							// Primera Quincena //
							$ldec_neto=floor($aa_ds_banco->data["monnetres"][$li_i]/2)*100;
							$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto, 12);
						}
						else // Con Decimal
						{
							// Segunda Quincena //
							$ldec_neto1=floor($aa_ds_banco->data["monnetres"][$li_i]/2)*100;
							$ldec_neto=($aa_ds_banco->data["monnetres"][$li_i]*100)-$ldec_neto1;
							$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto, 12);
						}
						
					}
					$ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper, 10);
					//$ls_cadena=$ls_cedper."066".$ldec_neto.$ls_codcueban.$ld_fecproc."00000000"."\r\n";
					if($sudeban==substr($ls_codcueban,0,4) || $sudeban2==substr($ls_codcueban,0,4))
					{ // SI EL TRABAJADOR ESTA ASOCIADO A ESTE BANCO, PROCEDE A PROCESARLO

						$ls_cadena="3305".$ldec_neto.$ls_codcueban.$ls_cedper."00000000"."\r\n";
					}	
					if ($ls_creararchivo)
					{
						if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
						{
							$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
							$lb_valido=false;
						}
					}
					else
					{
						$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
						$lb_valido=false;
					}		
				} //for $li_i
				if ($lb_valido)
				{
					@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
					$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
				}
				else
				{
					@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
					$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
				}
			} // for $i
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_banfoandes
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_bod_2016($as_ruta,$as_archivo,$aa_ds_banco,$ad_fecproc,$as_codmetban,$as_descrip,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_bod_2016
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 as_codmetban // código del método a banco
		//	  Description: genera el archivo txt a disco para  el banco BOD_version_3 para pago de nomina
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_dia=substr($ad_fecproc,0,2);
		$li_mes=substr($ad_fecproc,3,2);
		$li_ano=substr($ad_fecproc,6,4);
		$ld_fecproc=$li_dia.$li_mes.$li_ano;
		$fecha=$li_ano.$li_mes.$li_dia;
		//$ls_nombrearchivo=$as_ruta."/nomina.txt";
		// RIF //
		$ls_rif=$_SESSION["la_empresa"]["rifemp"];
		// CONTRATO //
		if($ls_rif=='G-20000285-9') // ALCALDIA DEL MUNICIPIO BARINAS
		{
			$contrato="40040200002859001";
		}
		if($ls_rif=='J-30254317-7') // MATADERO MUNICIPAL
		{
			$contrato="40000302543177001";
		}
		$ls_rif=str_replace("-","",$ls_rif);
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=$this->io_funciones->uf_cerosizquierda(substr($ls_codempnom,0,4),4);

		$quincena1=0;
		$quincena2=0;
		$total_gen=0;
		$sudeban=substr($as_ctaban,0,4);
		print " Sudeban: ".$sudeban;
		$trabajadores=0;
		$this->uf_buscar_total($quincena1,$quincena2,$total_gen,$aa_ds_banco,$sudeban,$sudeban2,$trabajadores);
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		$trabajadores=$this->io_funciones->uf_cerosizquierda($trabajadores, 6);
		if(($li_count>0)&&($lb_valido))
		{
			for($i=1;$i<=$as_archivo;$i++)
			{
				$ls_nombrearchivo=$as_ruta."/bod-".$i.".txt";
				//Chequea si existe el archivo.
				if (file_exists("$ls_nombrearchivo"))
				{
					if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
					{
						$lb_valido=false;
					}
					else
					{
						$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
					}
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
				}
			
				for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
				{
					$ls_codnom=substr($aa_ds_banco->data["codnom"][$li_i],2,2);
					$ls_linea="01";
					//$ls_desnom=$aa_ds_banco->data["desnom"][$li_i];
					$ls_desnom="Nomina";
					$ls_codigo="000201515";
					$largo= 20;//72-strlen($ls_rif)+2;
					$blanco=158;
					$final=$this->io_funciones->uf_rellenar_der(""," ",$blanco);
					$ls_desnom=str_pad($ls_desnom,$largo);
					// PRIMERA LINEA DEL ARCHIVO //
					if($li_i==1)
					{
						if($as_archivo==1) // un solo pago
						{
							//print "archivo mensual".$total_gen;
							$total=$total_gen;
						}
						else
						{
							if($i==1)
							{
								//$total=$this->uf_buscar_total($i,$aa_ds_banco,$li_count);
								$total=$quincena1;
							//	print "quincena1".$quincena1;
								//$total=$this->io_funciones->uf_cerosizquierda($total, 17);
							}
							else
							{
								//$total=$this->uf_buscar_total($i,$aa_ds_banco,$li_count);
								//$total1=floor($adec_montot/2);
								//$total=$adec_montot-$total1;
								$total=$quincena2;
							//	print "quincena2".$quincena2;
								//$total=str_replace(".","",$total);
								//$total=$this->io_funciones->uf_cerosizquierda($total, 17);
							}
						}
						$total=$this->io_funciones->uf_cerosizquierda($total, 17);
						$ls_cadena=$ls_linea.$ls_desnom.$ls_rif.$contrato.$ls_codigo.$fecha.$trabajadores.$total."VEB".$final."\r\n";
						if ($ls_creararchivo)
						{
							if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
							{
								$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
								$lb_valido=false;
							}
						}
						else
						{
							$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
							$lb_valido=false;
						}
					}
					// FIN DE LA PRIMERA LINEA DEL ARCHIVO //
					$ls_linea="02";
					$ls_nacper=$aa_ds_banco->data["nacper"][$li_i];
					//$ls_codcueban=substr($aa_ds_banco->data["codcueban"][$li_i],0,20);
					$ls_codcueban=$aa_ds_banco->data["codcueban"][$li_i];
					//$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
					//$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban, 20);
					//print "cuenta original: ".$ls_codcueban;
					//$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i];
					//print $as_archivo;
					if($as_archivo==1)
					{
						// Con Decimal //
						$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i];
					}
					else
					{
						// Sin Decimal //
						if($i==1)
						{
							// Primera Quincena //
							$ldec_neto=floor($aa_ds_banco->data["monnetres"][$li_i]/2);
						}
						else
						{
							// Segunda Quincena //
							$ldec_neto1=floor($aa_ds_banco->data["monnetres"][$li_i]/2);
							$ldec_neto=($aa_ds_banco->data["monnetres"][$li_i])-$ldec_neto1;
						}
						
					}
					//$li_neto_int=$ldec_neto;
					//$li_pos=strpos($ldec_neto,".");
					//$li_neto_dec=substr($ldec_neto,$li_pos,3);
					//$ldec_montot=$this->io_funciones->uf_trim($li_neto_int.$li_neto_dec);
					$ldec_montot=number_format($ldec_neto, 2, '', '');
					//$ldec_montot=str_replace(".","",$ldec_neto);
					$ldec_montot=$this->io_funciones->uf_rellenar_izq($ldec_montot,"0",15);
					$ls_cedper=$this->io_funciones->uf_trim($aa_ds_banco->data["cedper"][$li_i]);
					$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper,"0",9);
					//$ls_orden=$this->io_funciones->uf_rellenar_izq($li_i,"0",9); 
					// Se cambia el correlativo por el total de trabajadores
					$ls_orden=$this->io_funciones->uf_rellenar_izq($li_count,"0",9); 
					$ls_nomper=trim($aa_ds_banco->data["nomper"][$li_i]);
					$ls_apeper=trim($aa_ds_banco->data["apeper"][$li_i]);
					$ls_descripcion1=trim($aa_ds_banco->data["despernom"][$li_i]);
					$as_descrip=substr($as_descrip, 0, 4)." 2016";
					$as_descripcion=$ls_descripcion1." ".trim($as_descrip);
					$ls_personal=$this->io_funciones->uf_rellenar_der($ls_apeper." ".$ls_nomper," ",60);
					$ls_descripcion=$this->io_funciones->uf_rellenar_der($as_descripcion," ",30);
					$ls_codper=$this->io_funciones->uf_rellenar_der(substr($aa_ds_banco->data["codper"][$li_i],0,10)," ",10);
					$li_dia=substr($ad_fecproc,0,2);
					$li_mes=substr($ad_fecproc,3,2);
					$li_ano=substr($ad_fecproc,6,4);
					$ld_fecproc=$li_ano.$li_mes.$li_dia;
					$vacio="";
					$relleno="000000000000000".$this->io_funciones->uf_rellenar_izq($vacio," ",40)."00000000000";
					$ls_cadena='';
					print "sudeban: ".$sudeban." codcuenta: ".substr($ls_codcueban,0,4);
					if($sudeban==substr($ls_codcueban,0,4))
					{ // SI EL TRABAJADOR ESTA ASOCIADO A ESTE BANCO, PROCEDE A PROCESARLO
						$ls_cadena=$ls_linea.$ls_nacper.$ls_cedper.$ls_personal.$ls_orden.$ls_descripcion."CTA".$ls_codcueban.substr($ls_codcueban,0,4).$ld_fecproc.$ldec_montot."VEB".$relleno."\r\n";
					}
					//print $ls_cadena;
					//'"'.$ls_codempnom.'","'.$ls_codcueban.'","'.$ls_cedper.'","'.$ls_personal.'",'.$ldec_montot.','.$ld_fecproc.','.
					//      '"C"'.',"'.$ls_codper.'"'."\r\n";
					if ($ls_creararchivo)
					{
						if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
						{
							$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
							$lb_valido=false;
						}
					}
					else
					{
						$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
						$lb_valido=false;
					}		
				} // for $li_i
				if ($lb_valido)
				{
					@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
					$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
				}
				else
				{
					@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
					$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
				}
			} //for $i
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_bod_2016
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_bod_version_3($as_ruta,$aa_ds_banco,$ad_fecproc,$as_codmetban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_bod_version_3
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 as_codmetban // código del método a banco
		//	  Description: genera el archivo txt a disco para  el banco BOD_version_3 para pago de nomina
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=$this->io_funciones->uf_cerosizquierda(substr($ls_codempnom,0,4),4);	
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if(($li_count>0)&&($lb_valido))
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=substr($aa_ds_banco->data["codcueban"][$li_i],0,12);
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban, 12);
				$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i];
				$li_neto_int=$ldec_neto;
				$li_pos=strpos($ldec_neto,".");
				$li_neto_dec=substr($ldec_neto,$li_pos,3);
				$ldec_montot=$this->io_funciones->uf_trim($li_neto_int.$li_neto_dec);
				$ldec_montot=$this->io_funciones->uf_rellenar_izq($ldec_montot,"0",12);
				$ls_cedper=$this->io_funciones->uf_trim($aa_ds_banco->data["cedper"][$li_i]);
				$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper," ",15); 
				$ls_nomper=trim($aa_ds_banco->data["nomper"][$li_i]);
				$ls_apeper=trim($aa_ds_banco->data["apeper"][$li_i]);
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_nomper.", ".$ls_apeper," ",30);
				$ls_codper=$this->io_funciones->uf_rellenar_der(substr($aa_ds_banco->data["codper"][$li_i],0,10)," ",10);
				$li_dia=substr($ad_fecproc,0,2);
				$li_mes=substr($ad_fecproc,3,2);
				$li_ano=substr($ad_fecproc,6,4);
				$ld_fecproc=$li_ano.$li_mes.$li_dia;
				$ls_cadena='"'.$ls_codempnom.'","'.$ls_codcueban.'","'.$ls_cedper.'","'.$ls_personal.'",'.$ldec_montot.','.$ld_fecproc.','.
				           '"C"'.',"'.$ls_codper.'"'."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_bod_version_3
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_bod_viejo($as_ruta,$aa_ds_banco,$ad_fecproc,$as_codmetban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_bod_viejo
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//                 as_codmetban // código del método a banco
		//	  Description: genera el archivo txt a disco para  el banco BOD_version_3 para pago de nomina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 08/05/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomiter.txt";
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=$this->io_funciones->uf_cerosizquierda(substr($ls_codempnom,0,4),4);	
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if(($li_count>0)&&($lb_valido))
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=substr($aa_ds_banco->data["codcueban"][$li_i],0,10);
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_rellenar_der($ls_codcueban,"0",10);
				$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i];
				$li_neto_int=$ldec_neto;
				$li_pos=strpos($ldec_neto,".");
				$li_neto_dec=substr($ldec_neto,$li_pos,3);
				$ldec_montot=$this->io_funciones->uf_trim($li_neto_int.$li_neto_dec);
				$ldec_montot=$this->io_funciones->uf_rellenar_izq($ldec_montot,"0",12);
				$ls_cedper=$this->io_funciones->uf_trim($aa_ds_banco->data["cedper"][$li_i]);
				$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper,"0",9); 
				$ls_nomper=trim($aa_ds_banco->data["nomper"][$li_i]);
				$ls_apeper=trim($aa_ds_banco->data["apeper"][$li_i]);
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_nomper.", ".$ls_apeper," ",30);
				$ls_codper=$this->io_funciones->uf_rellenar_der(substr($aa_ds_banco->data["codper"][$li_i],0,10)," ",10);
				$li_dia=substr($ad_fecproc,0,2);
				$li_mes=substr($ad_fecproc,3,2);
				$li_ano=substr($ad_fecproc,6,4);
				$ld_fecproc=$li_ano.$li_mes.$li_dia;
				$ls_cadena=$ls_codempnom.",".$ls_codcueban.",".$ls_cedper.",".$ls_personal.",".$ldec_montot.",".$ld_fecproc.","."C".",".$ls_codper."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_bod_viejo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_banesco($as_ruta,$aa_ds_banco,$ad_fecproc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_banesco
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fecproc // fecha de procesamiento
		//	  Description: genera el archivo txt a disco para  el banco BANESCO para pago de nomina
		//	   Creado Por: Ing. Maria Roa
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_space="         "; // 9 espacios
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if(($li_count>0)&&($lb_valido))
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			//Cabecera del archivo
			$ls_cadena="NACIONALIDAD".$ls_space."CEDULA".$ls_space."CUENTA".$ls_space."SUELDO"."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido=false;
			}		
			//Registro de Detalle
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=$aa_ds_banco->data["codcueban"][$li_i];
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i]; 
				$ldec_montot=number_format($ldec_neto*100,0,"","");  
				$ldec_montot=$this->io_funciones->uf_cerosizquierda($ldec_montot,15);
				$ls_cedper=$this->io_funciones->uf_trim($aa_ds_banco->data["cedper"][$li_i]);
			    $ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,10);
				$ls_nacper=$aa_ds_banco->data["nacper"][$li_i];
				$ls_cadena=$ls_nacper.$ls_space.$ls_cedper.$ls_space.$ls_codcueban.$ls_space.$ldec_montot."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_metodo_banco_banesco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_canarias($as_ruta,$aa_ds_banco,$ad_fhasta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_canarias
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//                 ad_fhasta // fecha donde termina el período
		//	  Description: genera el archivo txt a disco para  el banco CANARIAS para pago de nomina
		//	   Creado Por: Ing. Maria Roa
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_dia=substr($ad_fhasta,8,2);
		$li_mes=substr($ad_fhasta,5,2);
		$li_ano=substr($ad_fhasta,0,4);
		$ls_nombrearchivo=$as_ruta."/nomina".$li_dia.$li_mes.$li_ano.".txt";
		//Registro tipo E
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=substr($aa_ds_banco->data["codcueban"][$li_i],0,20);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,20);
				$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i];
				$ldec_neto=($ldec_neto*100);  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,11);
				$ls_nacper=$aa_ds_banco->data["nacper"][$li_i];
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($aa_ds_banco->data["cedper"][$li_i],9);
				$ls_cadena=$ls_nacper.$ls_cedper.$ls_codcueban.$ldec_neto."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_metodo_banco_canarias
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_caracas($as_ruta,$aa_ds_banco,$adec_montot,$as_codcueban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_caracas
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   adec_montot // Monto Total a depositar
		//	    		   as_codcueban // código cuenta bancaria a debitar
		//	  Description: genera el archivo txt a disco para  el banco CARACAS para pago de nomina
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 08/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		//Registro tipo E
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if ($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); // abrimos el archivo que ya existe
				$lb_adicionado=true;
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
				$lb_adicionado=false;
			}
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=substr($aa_ds_banco->data["codcueban"][$li_i],0,11);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban, 11);
				$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i];
				$ldec_neto=($ldec_neto*100);  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,13);
				$ls_space="    ";
				$ls_cadena="NC".$ls_codcueban.$ldec_neto.$ls_space."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
			//Resumen del deposito
			$as_codcueban=substr($as_codcueban,0,11);
			$as_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
			$as_codcueban=$this->io_funciones->uf_cerosizquierda($as_codcueban, 11);
			$adec_montot=round($adec_montot,2); 
			$adec_montot=($adec_montot*100);  
			$adec_montot=$this->io_funciones->uf_cerosizquierda($adec_montot,13);
			$ls_cadena="ND".$as_codcueban.$adec_montot.$ls_space."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido=false;
			}					
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_metodo_banco_caracas
	//-----------------------------------------------------------------------------------------------------------------------------------/*
	
	//-----------------------------------------------------------------------------------------------------------------------------------/*
	function uf_metodo_banco_caroni($as_ruta,$aa_ds_banco)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_caroni
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	  Description: genera el archivo txt a disco para  el banco CARONI para pago de nomina
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 09/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		//Registro tipo E
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if ($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); // abrimos el archivo que ya existe
				$lb_adicionado=true;
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
				$lb_adicionado=false;
			}
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=substr($aa_ds_banco->data["codcueban"][$li_i],0,12);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban, 12);
				$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i];
				$ldec_neto=($ldec_neto*100);  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,10);
				$ls_nacper=$this->io_funciones->uf_rellenar_izq($aa_ds_banco->data["nacper"][$li_i]," ",1);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($aa_ds_banco->data["cedper"][$li_i],10);
				$ls_nomper=$aa_ds_banco->data["nomper"][$li_i];
				$ls_apeper=$aa_ds_banco->data["apeper"][$li_i];
				$ls_nombre=$this->io_funciones->uf_rellenar_der($ls_apeper.", ".$ls_nomper," ",30);
				$ls_cadena=$ls_nacper.$ls_cedper.$ls_nombre.$ls_codcueban.$ldec_neto."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}					
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_metodo_banco_caroni
	//-----------------------------------------------------------------------------------------------------------------------------------/*

	//-----------------------------------------------------------------------------------------------------------------------------------/*
	function uf_metodo_banco_casapropia($as_ruta,$aa_ds_banco,$ad_fecproc,$as_codcuenta,$adec_montot)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_casapropia
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   ad_fecproc // Fecha de procesamiento
		//	    		   as_codcuenta // código de cuenta a debitar
		//	    		   adec_montot // monto total a depositar
		//	  Description: genera el archivo txt a disco para  el Banco Casa Propia para pago de nomina
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 09/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ldec_monpre=0;
		$ldec_monacu=0;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			//Registro del deposito
			$ls_rifemp=str_replace("-","",$this->ls_rifemp);
			$ls_siglas=substr($this->ls_siglas,0,7);
			$li_numdebprev=1;
			$li_numcreprev=$li_count;
			$li_numcreprev=$this->io_funciones->uf_cerosizquierda($li_numcreprev,5);
			$ldec_totdep=$adec_montot;
			$ldec_monacu=$ldec_totdep;
			$ldec_monacu=$this->io_funciones->uf_cerosizquierda($ldec_monacu*100,12); 
			$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcuenta));
			$ls_codcueban=$this->io_funciones->uf_rellenar_der($ls_codcueban," ",25);
			$li_dia=substr($ad_fecproc,0,2);
			$li_mes=substr($ad_fecproc,3,2);
			$li_ano=substr($ad_fecproc,6,4);
			$ld_fecha=$li_ano.$li_mes.$li_dia;
			$ls_cadena=$ls_rifemp.$ls_siglas.$li_numcreprev.$ls_codcueban.$ld_fecha.$ldec_monacu."\r\n";
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido=false;
			}					
			$li_numcreprev=0;
			$li_numcreprev=0;
			//Registro tipo E
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=substr($aa_ds_banco->data["codcueban"][$li_i],0,10);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban, 10);
				$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i];  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto*100, 12);
				$ls_cadena=$ls_codcueban.$ldec_neto."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}					
				$li_numcreprev = $li_numcreprev + 1;
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
	}// end function metodo_banco_casapropia
	//-----------------------------------------------------------------------------------------------------------------------------------/*

	//-----------------------------------------------------------------------------------------------------------------------------------/*
	function uf_metodo_banco_central($as_ruta,$aa_ds_banco,$as_codcueban,$adec_montot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_central
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   ad_fecproc // Fecha de procesamiento
		//	    		   as_codcuenta // código de cuenta a debitar
		//	    		   adec_montot // monto total a depositar
		//	  Description: genera el archivo txt a disco para  el banco CENTRAL para pago de nomina
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 09/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomdes.txt";
		//Registro tipo E
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=substr($aa_ds_banco->data["codcueban"][$li_i],0,10);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban, 10);
				$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i];  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto*100,13);
				$ls_tipcueban=$aa_ds_banco->data["tipcuebanper"][$li_i];
				switch ($ls_tipcueban)
				{
					case "C":
						  $ls_tipocuenta="C";  //Corriente
						  break;
						  
					case "A":
						  $ls_tipocuenta="H";  //Ahorro
						  break;	
						  
					case "L":
						  $ls_tipocuenta="H";  //Activos Liquidos
						  break;	
						  
					default:	
						 $ls_tipocuenta="H";  
						 break;	    
				}
				$li_consecutivo=$this->io_funciones->uf_cerosizquierda($li_i, 8);
				$ls_cadena="A".$ls_tipocuenta."202".$ls_codcueban.$li_consecutivo.$ldec_neto."0506"."\r\n";			
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}					
			} 
			//Registro tipo T
			$li_consecutivo=$this->io_funciones->uf_cerosizquierda($li_i, 8);
			$as_codcueban=substr($as_codcueban,0,10);
			$as_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
			$as_codcueban=$this->io_funciones->uf_cerosizquierda($as_codcueban, 10);
			$adec_montot=$this->io_funciones->uf_cerosizquierda($adec_montot*100,13);
            $ls_cadena="AC402".$as_codcueban.$li_consecutivo.$adec_montot."0506"."\r\n";	
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido=false;
			}					
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_metodo_banco_central
	//-----------------------------------------------------------------------------------------------------------------------------------/*

	//-----------------------------------------------------------------------------------------------------------------------------------/*
	function uf_metodo_banco_del_sur_eap($as_ruta,$aa_ds_banco,$ad_fhasta,$as_codmetban)  
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_del_sur_eap
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   ad_fhasta // Fecha hasta del período
		//	    		   as_codmetban // Código del Método a banco
		//	  Description: genera el archivo txt a disco para  el banco del_sur_eap para pago de nomina
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 09/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		//Registro tipo E
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=$this->io_funciones->uf_cerosizquierda(trim(substr($ls_codempnom,0,4)),4);	
		$ls_numconvenio=$this->io_funciones->uf_cerosizquierda(trim(substr($ls_numconvenio,0,8)),8);	
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if(($li_count>0)&&($lb_valido))
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=substr($aa_ds_banco->data["codcueban"][$li_i],0,10);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban, 10);
				$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i];  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda(($ldec_neto*100),10);
				$ls_cedper=$this->io_funciones->uf_cerosizquierda(trim($aa_ds_banco->data["cedper"][$li_i]),10);
				$ls_nomper=rtrim($aa_ds_banco->data["nomper"][$li_i]);
				$ls_apeper=rtrim($aa_ds_banco->data["apeper"][$li_i]);
				$ls_nombre=substr($ls_nomper." ".$ls_apeper,0,30);
				$ls_nombre=$this->io_funciones->uf_rellenar_der($ls_nombre," ",30);
				$ls_cadena=$ls_codempnom.$ls_cedper.$ls_codcueban.$ldec_neto."C".$ls_numconvenio.$ls_nombre."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}					
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_metodo_banco_del_sur_eap
	//-----------------------------------------------------------------------------------------------------------------------------------/*

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_biv_version_2($as_ruta,$aa_ds_banco,$as_codmetban,$adec_montot)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_eap_micasa
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   as_codmetban // código de método a banco
		//	    		   adec_montot // monto total a depositar
		//	  Description: genera el archivo txt a disco para  el banco industrial de venezuela version_2 para pago de nomina
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 10/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codofinom=substr($ls_codofinom,0,3);
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if(($li_count>0)&&($lb_valido))
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				if ($aa_ds_banco->data["tipcuebanper"][$li_i]=="A")
				{
					$ls_tipcuebanper = "2";
				}
				else
				{
					$ls_tipcuebanper = "1";
				}
				$ls_codcueban=substr($aa_ds_banco->data["codcueban"][$li_i],0,12);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,12);
				$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i];  
				$li_neto_int=substr($ldec_neto,0,10);
				$li_pos=strpos($ldec_neto, ".");
				$li_neto_dec=substr($ldec_neto,$li_pos,3);
				$ldec_montot=$this->io_funciones->uf_trim($li_neto_int.$li_neto_dec);
				$ldec_montot=$this->io_funciones->uf_cerosderecha($ldec_montot,12);
				$ls_cedper=$this->io_funciones->uf_trim($aa_ds_banco->data["cedper"][$li_i]);
				$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper," ",10); 
				$ls_nomper=trim($aa_ds_banco->data["nomper"][$li_i]);
				$ls_nomper=$this->io_funciones->uf_rellenar_der($ls_nomper," ",15);
				$ls_apeper=trim($aa_ds_banco->data["apeper"][$li_i]);
				$ls_apeper=$this->io_funciones->uf_rellenar_der($ls_apeper," ",15);
				$ls_nacper=$aa_ds_banco->data["nacper"][$li_i];
				$ls_cadena=$ls_tipcuebanper.$ls_nacper.$ls_cedper.$ls_apeper.$ls_nomper.$ls_codofinom."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}					
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function metodo_banco_biv_version_2
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_central_v1($as_ruta,$aa_ds_banco,$as_codcuenta,$adec_montot)
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_eap_micasa
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   as_codmetban // código de método a banco
		//	    		   adec_montot // Monto total a depositar
		//	  Description: genera el archivo txt a disco para  el banco CENTRAL version 1 para pago de nomina
		//	   Creado Por: Ing. Juniors Fraga
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Miguel Palencia						Fecha Última Modificación : 10/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomdes.txt";
		//Registro tipo E
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=substr($aa_ds_banco->data["codcueban"][$li_i],0,10);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,10);
				$ldec_neto=$aa_ds_banco->data["monnetres"][$li_i]*100;  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,13);
				$li_reg=$this->io_funciones->uf_cerosizquierda($li_i,8);
				$ls_cadena = "AC202".$ls_codcueban.$li_reg.$ldec_neto."0506"."\r\n";			
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}					
			} 
			if($lb_valido)
			{
				//Registro tipo T
				$ls_codcueban=substr($as_codcuenta,0,10);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,10);
				$ldec_totdep=$this->io_funciones->uf_cerosizquierda($adec_montot*100,13);
				$ls_cadena="AC402".$ls_codcueban.$li_reg.$ldec_totdep."0506"."\r\n";	
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}					
			} 
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
    }// end function uf_metodo_banco_central_v1
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_e_provincial($as_ruta,$aa_ds_banco)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_e_provincial
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Provincial para pago de nomina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 28/08/2006 								
		// Modificado Por: 										Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/BSF0000W.txt";
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if($li_count>0)
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=$aa_ds_banco->data["codcueban"][$li_i];
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=str_pad($ls_codcueban,20," ",0);
				$ls_nacper=trim($aa_ds_banco->data["nacper"][$li_i]);
				$ls_cedper=$this->io_funciones->uf_trim($aa_ds_banco->data["cedper"][$li_i]);
				$ls_cedper=$this->io_funciones->uf_rellenar_der($ls_cedper," ",8); 
				$ls_nomper=trim($aa_ds_banco->data["nomper"][$li_i]);
				$ls_apeper=trim($aa_ds_banco->data["apeper"][$li_i]);
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_nomper.", ".$ls_apeper," ",35);
				$ldec_neto=($aa_ds_banco->data["monnetres"][$li_i]*100);
				$li_neto_int=$this->io_funciones->uf_rellenar_izq($ldec_neto,"0",15);
				$ls_cadena=$ls_nacper.$ls_cedper.$ls_codcueban.$ls_personal.$li_neto_int."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_e_provincial
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_e_provincial_02($as_ruta,$aa_ds_banco)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_e_provincial_02
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Provincial para pago de nomina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 28/08/2006 								
		// Modificado Por: 										Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/BSF0000W.txt";
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if($li_count>0)
		{
			$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=$aa_ds_banco->data["codcueban"][$li_i];
			    $ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=str_pad($ls_codcueban,20," ",0);
				$ls_nacper=trim($aa_ds_banco->data["nacper"][$li_i]);
				$ls_cedper=$this->io_funciones->uf_trim($aa_ds_banco->data["cedper"][$li_i]);
				$ls_cedper=$this->io_funciones->uf_rellenar_der($ls_cedper," ",9); 
				$ls_nomper=trim($aa_ds_banco->data["nomper"][$li_i]);
				$ls_apeper=trim($aa_ds_banco->data["apeper"][$li_i]);
				$ls_personal=substr($ls_nomper.", ".$ls_apeper,0,30);
				$ls_personal=$this->io_funciones->uf_rellenar_der($ls_personal," ",30);
				$ldec_neto=($aa_ds_banco->data["monnetres"][$li_i]*100);
				$li_neto_int=$this->io_funciones->uf_rellenar_izq($ldec_neto,"0",13);
				$ls_cadena=$ls_nacper.$ls_cedper.$ls_codcueban.$ls_personal.$li_neto_int."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_e_provincial_02
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_e_provincial_03($as_ruta,$aa_ds_banco)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_e_provincial_03
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Provincial para pago de nomina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 05/06/2007 								
		// Modificado Por: 										Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/BSF0000W.txt";
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if($li_count>0)
		{
			$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=rtrim($aa_ds_banco->data["codcueban"][$li_i]);
			    $ls_codcueban=str_replace("-","",$ls_codcueban);
				$ls_codcueban=str_pad($ls_codcueban,20," ",0);
				$ls_nacper=rtrim($aa_ds_banco->data["nacper"][$li_i]);
				$ls_cedper=$this->io_funciones->uf_trim($aa_ds_banco->data["cedper"][$li_i]);
				$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper,"0",8); 
				$ls_nomper=rtrim($aa_ds_banco->data["nomper"][$li_i]);
				$ls_apeper=rtrim($aa_ds_banco->data["apeper"][$li_i]);
				$ls_personal=substr($ls_nomper." ".$ls_apeper,0,32);
				$ls_personal=str_pad($ls_personal,32," ");
				$ldec_neto=($aa_ds_banco->data["monnetres"][$li_i]*100);
				$li_neto_int=$this->io_funciones->uf_rellenar_izq($ldec_neto,"0",15);
				$ls_cadena=$ls_nacper.$ls_cedper.$ls_personal.$ls_codcueban.$li_neto_int."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_e_provincial_03
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_e_provincial_04($as_ruta,$aa_ds_banco)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_e_provincial_04
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Provincial para pago de nomina
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 13/05/2008 								
		// Modificado Por: 										Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/NOMINA.txt";
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if($li_count>0)
		{
			$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=rtrim($aa_ds_banco->data["codcueban"][$li_i]);
			    $ls_codcueban=str_replace("-","",$ls_codcueban);
				$ls_codcueban=str_pad($ls_codcueban,20," ",0);
				$ls_nacper=rtrim($aa_ds_banco->data["nacper"][$li_i]);
				$ls_cedper=$this->io_funciones->uf_trim($aa_ds_banco->data["cedper"][$li_i]);
				$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper,"0",9); 
				$ls_nomper=rtrim($aa_ds_banco->data["nomper"][$li_i]);
				$ls_apeper=rtrim($aa_ds_banco->data["apeper"][$li_i]);
				$ls_personal=substr($ls_apeper.", ".$ls_nomper,0,70);
				$ls_personal=str_pad($ls_personal,70," ");
				$ldec_neto=($aa_ds_banco->data["monnetres"][$li_i]*100);
				$li_neto_int=$this->io_funciones->uf_rellenar_izq($ldec_neto,"0",15);				
				$ls_cadena="        ".$ls_nacper.$ls_cedper.$ls_codcueban.$ls_personal.$li_neto_int."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_e_provincial_04
//-------------------------------------------------------------------------------------------------------------------------------
    function uf_metodo_banco_provincial_altamira($as_ruta,$aa_ds_banco,$as_metodo,$as_codcueban,$adec_montot,$ad_fecproc)
	{		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_provincial_altamira
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Provincial para pago de nomina
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 05/06/2008 								
		// Modificado Por: 										Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/NOMINA.txt";
		//Chequea si existe el archivo.
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if ($li_count>0)
		{
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido=false;
				}
				else
				{
					$ls_creararchivo=@fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}		
			if($lb_valido)
			{		
				// Registro de la cabecera (Datos Fijos)
				$ldec_montot=$adec_montot;           
				$ldec_montot=($ldec_montot*100);  
				$ldec_montot=$this->io_funciones->uf_cerosizquierda($ldec_montot,17);
				$ad_fecproc=$this->io_funciones->uf_convertirdatetobd($ad_fecproc);
			    $ad_fecproc=str_replace("-","",$ad_fecproc);
				$ls_rifempresa=str_replace("-","",$_SESSION["la_empresa"]["rifemp"]);
			    $ls_rif=$this->io_funciones->uf_rellenar_izq($ls_rifempresa," ",10);
				$ls_refdebcre=" ";
				$ls_refdebcre=str_pad($ls_refdebcre,8," ");
				$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
			    $ls_nomemp=$this->io_funciones->uf_rellenar_der(substr(rtrim($ls_nomemp),0,27)," ",27);				
				$as_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$as_codcueban));
			    $as_codcueban=substr($as_codcueban,12,8); 
				$cant_registros=str_pad($li_count,7,0,"LEFT");
				$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_metodo,"0",$ls_codempnom,
				                                                          $ls_codofinom,$ls_tipcuecre,
																		  $ls_tipcuedeb,$ls_numconvenio);
				if ($ls_numconvenio=="")
				{
				 	$ls_numconvenio="XXXX";
				}
				if ($ls_codofinom=="")
				{
					$ls_codofinom="XXXX";
				}
				$ls_disponible_C=" ";
				$ls_disponible_C=str_pad($ls_disponible_C,46," ");
				if($lb_valido)
				{		
				    $ls_cadena="01"."01".$ls_numconvenio.$ls_codofinom."00".$ls_tipcuedeb.$as_codcueban.$cant_registros.
					           $ldec_montot."VEB".$ad_fecproc.$ls_rif.$ls_refdebcre.$ls_nomemp.$ls_disponible_C."\r\n";
							  
				}
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
				///----------registro individual obligatorio-------------------------------------------------------------------
				for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			    {
				    $ls_tipo_registro="02";
					$l_codban="0108";
					$ls_dig_cheq="00";
					$ls_codcueban=rtrim($aa_ds_banco->data["codcueban"][$li_i]);
			    	$ls_codcueban=str_replace("-","",$ls_codcueban);
					$li_inicio=strlen($ls_codcueban)-12;
					$ls_codcueban=substr($ls_codcueban,$li_inicio,8);
					$ls_codcueban=$this->io_funciones->uf_rellenar_der($ls_codcueban," ",8);
				
					$ls_tipcta=rtrim($aa_ds_banco->data["tipcuebanper"][$li_i]);
					if($ls_tipcta=="A")
					{
						$ls_tipo="01";
					}
					else
					{
						$ls_tipo="02";
					}
					$ls_nacper=rtrim($aa_ds_banco->data["nacper"][$li_i]);
					$ls_cedper=$this->io_funciones->uf_trim($aa_ds_banco->data["cedper"][$li_i]);
					$ls_cedper=$this->io_funciones->uf_rellenar_izq($ls_cedper,"0",9);
					$referencia=$ls_nacper.$ls_cedper;
					$referencia=str_pad($referencia,16," ");
					$ls_nomper=trim($aa_ds_banco->data["nomper"][$li_i]);
					$ls_apeper=trim($aa_ds_banco->data["apeper"][$li_i]);
					$ls_personal=substr($ls_apeper.", ".$ls_nomper,0,40);
					$ls_personal=str_pad($ls_personal,40," ");
					$ldec_neto=($aa_ds_banco->data["monnetres"][$li_i]*100);
					$otros_datos=" ";
					$otros_datos=str_pad($otros_datos,30," ");					
					$resultado="00";
					$refdebcre=" ";
					$refdebcre=str_pad($refdebcre,8," ");
					$ls_disponible_IO=" ";	
					$ls_disponible_IO=str_pad($ls_disponible_IO,15," ");								
					$li_neto_int=$this->io_funciones->uf_rellenar_izq($ldec_neto,"0",17);				
					$ls_cadena=$ls_tipo_registro.$l_codban.$ls_codofinom.$ls_dig_cheq.$ls_tipo.$ls_codcueban.
					           $referencia.$li_neto_int.$ls_personal.$otros_datos.$resultado.$refdebcre.$ls_disponible_IO."\r\n";
					if ($ls_creararchivo)
					{
						if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
						{
							$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
							$lb_valido=false;
						}
					}
					else
					{
						$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
						$lb_valido=false;
					}		
			    }//fin  del for				
			    //----------------------fin del regitro obligatorio----------------------------------------------------		
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
		
	}// end function uf_metodo_banco_provincial_altamira
//-------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_corp_banca($as_ruta,$aa_ds_banco,$adec_montot,$as_codperi,$as_perides,$as_perihas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_corp_banca
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco 
		//	  Description: genera el archivo txt a disco para  el banco Corp Banca para pago de nomina
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 14/05/2008 								
		// Modificado Por: 										Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if($li_count>0)
		{
			$ls_creararchivo=@fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=rtrim($aa_ds_banco->data["codcueban"][$li_i]);				
			    $ls_codcueban=str_replace("-","",$ls_codcueban);
				$tamano=strlen($ls_codcueban);
				if ($tamano>10)
				 {
				   $ls_codcueban=substr($ls_codcueban,$tamano-10,$tamano);				
				 }
				$ls_codcueban=str_pad($ls_codcueban,12,"0","left");
				$ls_nacper=rtrim($aa_ds_banco->data["nacper"][$li_i]);
				$ls_cedper=$this->io_funciones->uf_trim($aa_ds_banco->data["cedper"][$li_i]);
				$ls_cedper=$this->io_funciones->uf_rellenar_der($ls_cedper," ",15); 
				$ls_nomper=rtrim($aa_ds_banco->data["nomper"][$li_i]);
				$ls_apeper=rtrim($aa_ds_banco->data["apeper"][$li_i]);
				$ls_personal=substr($ls_apeper.", ".$ls_nomper,0,70);
				$ls_personal=str_pad($ls_personal,70," ");
				$ldec_neto=($aa_ds_banco->data["monnetres"][$li_i]*100);
				$li_neto_int=$this->io_funciones->uf_rellenar_izq($ldec_neto,"0",15);				
				$ls_ano1=substr($as_perides,2,2);
				$ls_mes1=substr($as_perides,5,2);
				$ls_dia1=substr($as_perides,8,2);
				$ls_ano2=substr($as_perihas,2,2);
				$ls_mes2=substr($as_perihas,5,2);
				$ls_dia2=substr($as_perihas,8,2);
				$ls_cadena=$ls_cedper.$ls_codcueban.$li_neto_int.$as_codperi.$ls_dia1.
				           $ls_mes1.$ls_ano1.$ls_dia2.$ls_mes2.$ls_ano2."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}		
			}
		
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_corp_banca
	//-------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_deltesoro($as_ruta,$aa_ds_banco,$ad_fecproc,$as_codmetban)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_deltesoro
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   ad_fecproc // Fecha de procesamiento
		//	    		   as_codmetban // Código del Metodo
		//	  Description: genera el archivo txt a disco para  el banco idel Tesoro para pago de nomina
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 12/06/2008 								
		// Modificado Por: 											Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=substr($ls_codempnom,0,4);
		$li_dia=substr($ad_fecproc,0,2);
		$li_mes=substr($ad_fecproc,3,2);
		$li_ano=substr($ad_fecproc,6,4);
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if(($li_count>0)&&($lb_valido))
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_codcueban=substr($aa_ds_banco->data["codcueban"][$li_i],0,12);
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,12);
				$ldec_neto=number_format($aa_ds_banco->data["monnetres"][$li_i],2,".","");  
				$ldec_neto=number_format($ldec_neto*100,0,"","");  
				$ldec_neto=$this->io_funciones->uf_cerosderecha($ldec_neto,15);
				$ls_cadena=$ls_codempnom.$li_dia.$li_mes.$li_ano.$ls_codcueban.$ldec_neto."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}					
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_deltesoro
	//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_deltesoro_2008($as_ruta,$aa_ds_banco,$ad_fecproc,$as_codmetban,$adec_montot,$as_codcueban)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_deltesoro_2008
		//		   Access: public 
		//	    Arguments: aa_ds_banco // arreglo (datastore) datos banco  
		//	    		   ad_fecproc // fecha de procesamiento
		//                 as_codmetban // código del método a banco
		//	    		   adec_montot // monto total a ser aplicado
		//                 as_codcueban // código de cuenta de banco
		//	  Description: genera el archivo txt a disco para  el banco idel Tesoro para pago de nomina, según instrutivo
		//                 este método contiene datos en la cabecera y el detalle.
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 02/10/2005 								
		// Modificado Por: 											Fecha Última Modificación : /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nombrearchivo=$as_ruta."/nomina.txt";
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuedeb="";
		$ls_tipcuecre="";
		$ls_numconvenio="";
		$lb_valido=$this->io_metbanco->uf_load_metodobanco_nomina($as_codmetban,"0",$ls_codempnom,$ls_codofinom,$ls_tipcuecre,$ls_tipcuedeb,$ls_numconvenio);
		$ls_codempnom=substr($ls_codempnom,0,4);
		$li_diapp=substr($ad_fecproc,0,2);
		$li_mespp=substr($ad_fecproc,3,2);
		$li_anopp=substr($ad_fecproc,8,2);
		$li_count=$aa_ds_banco->getRowCount("codcueban");
		if(($li_count>0)&&($lb_valido))
		{
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			/// PARA LA CABECERA DEL ARCHIVO
			$ls_codempnom=str_pad($ls_codempnom,4,"0","left"); // se completa hasta cuatro digitos	
			$ls_rif=$this->ls_rifemp;
			$ls_rif=str_replace("-","",$ls_rif);
			$ls_rif=str_pad($ls_rif,15," ");
			$adec_montot=number_format($adec_montot,2,".","");  
			$adec_montot=number_format($adec_montot*100,0,"","");  
			$adec_montot=$this->io_funciones->uf_cerosizquierda($adec_montot,15);
			$ld_fecreg=date("d/m/Y");
			$ld_anoreg=substr($ld_fecreg,8,2);
			$ld_mesreg=substr($ld_fecreg,3,2);
			$ld_diareg=substr($ld_fecreg,0,2);
			$ld_fecreg=$ld_anoreg.$ld_mesreg.$ld_diareg;
			$li_totreg=str_pad($li_count,5,"0","left");
			$ld_fecpago=$li_anopp.$li_mespp.$li_diapp;
			$ls_nrocuebanemp=$as_codcueban;
			$ls_nrocuebanemp=$this->io_funciones->uf_trim(str_replace("-","",$ls_nrocuebanemp));
			$ls_nrocuebanemp=$this->io_funciones->uf_cerosizquierda($ls_nrocuebanemp,20);
			$ls_codnom=$aa_ds_banco->data["codnom"][1];	
			$ls_codnom=str_pad($ls_codnom,10,"0","left");
			$ls_cabecera='H'.$ls_codempnom.$ls_codnom.$ls_nrocuebanemp.$ls_rif.$ld_fecreg.$ld_fecpago.$li_totreg.$adec_montot."\r\n";
			
			if ($ls_creararchivo)
			{
				if (@fwrite($ls_creararchivo,$ls_cabecera)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido=false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
				$lb_valido=false;
			}		
			/// PARA EL DETALLE DEL ARCHIVO
			for($li_i=1;(($li_i<=$li_count)&&($lb_valido));$li_i++)
			{
				$ls_tipreg='D';
				$ls_codcueban=$aa_ds_banco->data["codcueban"][$li_i];
				$ls_codcueban=$this->io_funciones->uf_trim(str_replace("-","",$ls_codcueban));
				$ls_codcueban=$this->io_funciones->uf_cerosizquierda($ls_codcueban,20);
				$ls_nacper=$aa_ds_banco->data["nacper"][$li_i];
				$ls_cedper=$aa_ds_banco->data["cedper"][$li_i];
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,9);
				$ls_cedula=$ls_nacper.$ls_cedper;
				$ls_cedula=str_pad($ls_cedula,15," ");
				$ldec_neto=number_format($aa_ds_banco->data["monnetres"][$li_i],2,".","");  
				$ldec_neto=number_format($ldec_neto*100,0,"","");  
				$ldec_neto=$this->io_funciones->uf_cerosizquierda($ldec_neto,15);
				
				$ls_cadena=$ls_tipreg.$ls_codcueban.$ls_cedula.$ldec_neto."\r\n";
				if ($ls_creararchivo)
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido=false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo  ".$ls_nombrearchivo);
					$lb_valido=false;
				}					
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_deltesoro_2008
	//-----------------------------------------------------------------------------------------------------------------------------------
	
}
?>
