<?php
class tepuy_scb_c_carta_orden
{
	var $io_sql;
	var $io_function;
	var $io_msg;
	var $is_msg_error;	
	var $ds_sol;
	var $dat;
	var $ds_temp;
	var $io_sql_aux;
	function tepuy_scb_c_carta_orden()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/tepuy_include.php");
        require_once("tepuy_c_cuentas_banco.php");
	    require_once("../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
		$this->io_rcbsf     = new tepuy_c_reconvertir_monedabsf();
		$io_siginc   	    = new tepuy_include();
		$io_connect		    = $io_siginc->uf_conectar();
		$this->io_sql	    = new class_sql($io_connect);
		$this->io_sql_aux   = new class_sql($io_connect);
		$this->io_function  = new class_funciones();
		$this->io_msg	    = new class_mensajes();
		$this->dat		    = $_SESSION["la_empresa"];	
		$this->ds_temp	    = new class_datastore();
		$this->ds_sol 	    = new class_datastore();
	    $this->io_ctaban    = new tepuy_c_cuentas_banco();
	    $this->li_candeccon = $_SESSION["la_empresa"]["candeccon"];
	    $this->li_tipconmon = $_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon = $_SESSION["la_empresa"]["redconmon"];
	    $this->la_seguridad = "";
	}

	function uf_generar_num_documento($as_codemp,$as_codope)
	{
		 $ls_sql="SELECT numdoc FROM scb_movbco ".
		         " WHERE codemp='".$as_codemp."' AND codope='".$as_codope."' AND substring(numdoc,1,3)='CO0'".
				 " ORDER BY numdoc DESC";
		 $rs_funciondb=$this->io_sql->select($ls_sql);
		  if ($row=$this->io_sql->fetch_row($rs_funciondb))
		  { 
			  $codigo=substr($row["numdoc"],2,13);
			  settype($codigo,'int'); 
			  $codigo = $codigo + 1;                              // Le sumo uno al entero.
			  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
			  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,13);
			  $ls_codigo="CO".$ls_codigo;
		  }
		  else
		  {
			  $codigo="1";
			  $ls_codigo="CO".$this->io_function->uf_cerosizquierda($codigo,13);
		  }
		return $ls_codigo;
	}
	function  uf_cargar_programaciones($as_proben,$as_codigo,$as_codban,$as_ctaban,$object,$li_rows,$ai_tipvia)
	{
		
		$li_temp   = 0;
		$ls_codemp = $this->dat["codemp"];
		$ls_cadaux = "";
		if ($ai_tipvia=='1')
		   {
		     $ls_cadaux = " AND b.esttipvia = '1'";
		   }
		else
		   {
		     $ls_cadaux = " AND b.esttipvia <> '1'";
		   }
		if($as_proben=='B')
		{
		//Busco las programaciones de pago para beneficiarios junto con el monto retenido y el objeto a retencion de CXP en caso de poseer
		$ls_sql = " SELECT TMP.codemp,TMP.numsol,TMP.ced_bene,TMP.nombene,TMP.apebene,TMP.consol,TMP.monsol,TMP.obssol, ".
		          "        TMP.estprosol,TMP.fecpropag,TMP.codban,TMP.ctaban,TMP.fecemisol,TMP.numrecdoc,				".
				  "        max(TMP.nomban) as nomban,max(TMP.denctaban) as denctaban,max(TMP.codtipcta) as codtipcta,   ".
				  "        max(TMP.sc_cuenta) as sc_cuenta,max(TMP.nomtipcta) as nomtipcta,	 ".
				  "        sum(COALESCE(rd.monobjret,0)) as monobjret,sum(COALESCE(rd.monret,0)) as monret				".
				  "  FROM (SELECT max(a.codemp) as codemp,max(a.numsol) as numsol,max(a.ced_bene) as ced_bene,  		".
				  "               max(c.nombene) as nombene,max(c.apebene) as apebene,max(a.consol) as consol,			".
				  "               max(a.monsol) as monsol,max(a.obssol) as obssol,max(a.estprosol) as estprosol,		".
				  "               max(b.fecpropag) as fecpropag, max(b.codban) as codban,max(b.ctaban) as ctaban, 	 	".
				  "               max(a.fecemisol) as fecemisol,max(d.numrecdoc) as numrecdoc,max(e.nomban) as nomban,  ".
				  "               max(f.dencta) as denctaban,max(f.codtipcta) as codtipcta,max(f.sc_cuenta) as sc_cuenta,".
				  "               max(g.nomtipcta) as nomtipcta            ".
				  "          FROM cxp_solicitudes a,scb_prog_pago b,rpc_beneficiario c,cxp_dt_solicitudes d,			".
				  "               scb_banco e,scb_ctabanco f,scb_tipocuenta g											".
				  "         WHERE a.codemp='".$ls_codemp."' 															".
				  "           AND a.tipproben='".$as_proben."' $ls_cadaux												".
				  "           AND a.estprosol='S'																		".
				  "           AND b.estmov='P' 																			".
				  "           AND a.codemp=b.codemp  																	".
				  "           AND a.numsol=b.numsol 																	".
				  "           AND a.numsol=d.numsol 																	".
				  "			  AND a.ced_bene=c.ced_bene 																".
				  "           AND e.codban=b.codban 																	".
				  "           AND e.codemp=f.codemp 																	".
				  "           AND e.codban=f.codban 																	".
				  "           AND b.ctaban=f.ctaban 																	".
                  "           AND g.codtipcta=f.codtipcta																".
				  "         GROUP BY a.ced_bene,a.numsol".																
				  "         ORDER BY ced_bene,numsol asc) TMP 														    ".
				  "LEFT OUTER JOIN cxp_rd_cargos rd 																	".
				  "  ON TMP.numrecdoc=rd.numrecdoc 																		".
				  " AND TMP.codemp=rd.codemp																			".
				  "GROUP BY rd.numrecdoc,TMP.numsol,TMP.codemp,TMP.ced_bene,TMP.nombene,TMP.apebene,TMP.consol,TMP.monsol,   ".
				  "			TMP.obssol,TMP.estprosol,TMP.fecpropag,TMP.codban,TMP.nomban,TMP.ctaban,TMP.fecemisol,TMP.numrecdoc         ";				
		}
		else
		{
		//Busco las programaciones de pago para proveedores junto con el monto retenido y el objeto a retencion de CXP en caso de poseer
		$ls_sql="  SELECT TMP.codemp,TMP.numsol,TMP.cod_pro,TMP.nompro,TMP.consol,TMP.monsol,TMP.obssol,TMP.estprosol,           ".
       			"		  TMP.fecpropag,TMP.codban,TMP.ctaban,TMP.fecemisol,TMP.numrecdoc,max(TMP.nomban) as nomban,             ".
				"		  max(TMP.denctaban) as denctaban,max(TMP.codtipcta) as codtipcta,max(TMP.sc_cuenta) as sc_cuenta,       ".
				"		  max(TMP.nomtipcta) as nomtipcta,                                      ".
				"		  sum(COALESCE(rd.monobjret,0)) as monobjret,sum(COALESCE(rd.monret,0)) as monret			  			 ".
		        "  FROM	(SELECT max(a.codemp) as codemp,max(a.numsol) as numsol,max(a.cod_pro) as cod_pro,						 ".
				"				max(c.nompro) as nompro,max(a.consol) as consol, max(a.monsol) as monsol,max(a.obssol) as obssol,".
				"				max(a.estprosol) as estprosol,max(b.fecpropag) as fecpropag, max(b.codban) as codban,		     ".             
				"  				max(b.ctaban) as ctaban,max(a.fecemisol) as fecemisol,max(d.numrecdoc) as numrecdoc,             ".
			    "				max(e.nomban) as nomban,max(f.dencta) as denctaban,max(f.codtipcta) as codtipcta,                ".
				"				max(f.sc_cuenta) as sc_cuenta,max(g.nomtipcta) as nomtipcta  ".
				" 		   FROM cxp_solicitudes a,scb_prog_pago b,rpc_proveedor c,cxp_dt_solicitudes d,	  					     ".
			    "               scb_banco e,scb_ctabanco f,scb_tipocuenta g												  		 ".
				"         WHERE a.codemp='".$ls_codemp."' 																  	     ".
				"           AND a.estprosol='S' 																		         ".
				"           AND b.estmov='P'																			         ".
				"           AND a.tipproben='".$as_proben."' $ls_cadaux													  		 ".
				"           AND a.codemp=b.codemp 																		  		 ".
				"           AND a.numsol=b.numsol 																		  		 ".
				"		    AND a.cod_pro=c.cod_pro 																	  		 ".
				"           AND a.numsol=d.numsol 																		  		 ".
				"           AND e.codban=b.codban 																		  		 ".
			    "           AND e.codemp=f.codemp 																		  		 ".
			    "           AND e.codban=f.codban 																		  		 ".
			    "           AND b.ctaban=f.ctaban 																		  		 ".
                "           AND g.codtipcta=f.codtipcta																			 ".
		  	    "         GROUP BY a.cod_pro,a.numsol																			 ".
				"         ORDER BY numsol asc)TMP                                                                       		 ".
				"  LEFT OUTER JOIN cxp_rd_cargos rd 																		  	 ".
				"            ON TMP.numrecdoc=rd.numrecdoc AND TMP.codemp=rd.codemp 									  		 ".
				"  GROUP BY rd.numrecdoc,TMP.numsol,TMP.codemp,TMP.cod_pro,TMP.nompro,TMP.consol,TMP.monsol,TMP.obssol,   		 ".
				"           TMP.estprosol,TMP.fecpropag,TMP.codban,TMP.ctaban,TMP.fecemisol,TMP.numrecdoc                 		 ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en consulta, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->io_sql->message;
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				if($as_proben=='P')
				{
					$ls_codprovben=$row["cod_pro"];
					$ls_nomproben=$row["nompro"];
				}
				else
				{
					$ls_codprovben = $row["ced_bene"];
					$ls_nomproben  = $row["nombene"];
				    $ls_apebene    = $row["apebene"];
					if (!empty($ls_apebene))
					   {
					     $ls_nomproben = $ls_nomproben.', '.$ls_apebene;
					   }
				}
				$ls_numsol		= $row["numsol"];
				$ls_consol		= $row["consol"];
				$ldec_monsol	= $row["monsol"];
				$ls_codban		= $row["codban"];
				$ls_nomban		= $row["nomban"];
				$ls_ctaban		= $row["ctaban"];
				$ls_denctaban   = $row["denctaban"];
				$ldec_monobjret = $row["monobjret"];
				$ldec_monret	= $row["monret"];
			    $ls_codtipcta   = $row["codtipcta"];
				$ls_nomtipcta   = $row["nomtipcta"];
				$ls_sccuenta    = $row["sc_cuenta"];
				$ls_codfuefin    = "--";
                $ld_disponible  = 0; 
				$this->io_ctaban->uf_verificar_saldo($ls_codban,$ls_ctaban,&$ld_disponible);
				//Busco el monto que ya se abono a la solicitud programada
				$li_montonotas=0;
				$lb_valido=$this->uf_load_notas_asociadas($ls_codemp,$ls_numsol,&$li_montonotas);
				$lb_valido=$this->uf_load_fuentefinancimiento($ls_codemp,$ls_numsol,&$ls_codfuefin);
				$ldec_montocancelado = $this->uf_select_solcxp_montocancelado($ls_codemp,$ls_numsol,$ls_codban,$ls_ctaban);
				//Calculo el monto pendiente
				$ldec_montopendiente = ($ldec_monsol-$ldec_montocancelado)+$li_montonotas;
				if ($ldec_montopendiente>0) 
				   {
	 				 $li_temp=$li_temp+1;
					 $object[$li_temp][1]  = "<input type=checkbox name=chk".$li_temp."    	          id=chk".$li_temp."      			value=1                                        			  class=sin-borde  onClick=javascript:uf_selected('".$li_temp."');><input type=hidden   name=txtcodban".$li_temp."  id=txtcodban".$li_temp." value='".$ls_codban."' readonly>";
					 $object[$li_temp][2]  = "<input type=text     name=txtnumsol".$li_temp."  	      id=txtnumsol".$li_temp." 			value='".$ls_numsol."'                         			  class=sin-borde  readonly style=text-align:center size=15 maxlength=15>";
					 $object[$li_temp][3]  = "<input type=text     name=txtconsol".$li_temp."  	      id=txtconsol".$li_temp."          value='".$ls_consol."' title='".$ls_consol."'  			  class=sin-borde  readonly style=text-align:left size=30 maxlength=254>";
					 $object[$li_temp][4]  = "<input type=hidden   name=txtcodproben".$li_temp."  	  id=txtcodproben".$li_temp."       value='".$ls_codprovben."'><input type=text name=txtnomproben".$li_temp." id=txtnomproben".$li_temp."  value='".$ls_nomproben."' title='".$ls_nomproben."' class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
					 $object[$li_temp][5]  = "<input type=text     name=txtmonsol".$li_temp."  	   	  id=txtmonsol".$li_temp."  		value='".number_format($ldec_monsol,2,",",".")."'         class=sin-borde readonly style=text-align:right size=16 maxlength=20>";
					 $object[$li_temp][6]  = "<input type=text 	   name=txtmontopendiente".$li_temp."  id=txtmontopendiente".$li_temp."  value='".number_format($ldec_montopendiente,2,",",".")."' class=sin-borde readonly style=text-align:right size=16 maxlength=20>";
					 $object[$li_temp][7]  = "<input type=text 	   name=txtmonto".$li_temp."   		  id=txtmonto".$li_temp."           value='".number_format($ldec_montopendiente,2,",",".")."' class=sin-borde onBlur=javascript:uf_actualizar_monto(".$li_temp."); style=text-align:right size=16 maxlength=20>";				
					 $object[$li_temp][8]  = "<input type=hidden   name=txtmonobjret".$li_temp." 	  id=txtmonobjret".$li_temp."  		value='".number_format($ldec_monobjret,2,",",".")."'      class=sin-borde readonly style=text-align:right size=15 maxlength=20><input type=text name=txtmonret".$li_temp."  value='".number_format($ldec_monret,2,",",".")."' title='".number_format($ldec_monret,2,",",".")." ( Mon.Obj.Ret : ".number_format($ldec_monobjret,2,",",".").")'  class=sin-borde readonly style=text-align:right size=15 maxlength=20> ";
					 $object[$li_temp][9]  = "<input type=text     name=txtnomban".$li_temp."  	      id=txtnomban".$li_temp."          value='".$ls_nomban."' title='".$ls_nomban."'  			  class=sin-borde  readonly style=text-align:left size=30 maxlength=30>";
					 $object[$li_temp][10] = "<input type=text     name=txtctaban".$li_temp."  	      id=txtctaban".$li_temp."          value='".$ls_ctaban."' title='".$ls_ctaban."'  			  class=sin-borde  readonly style=text-align:center size=25 maxlength=25><input type=hidden  name=txtdenctaban".$li_temp."  id=txtdenctaban".$li_temp."  value='".$ls_denctaban."'>".
					 						 "<input type=hidden  name=txtcodtipcta".$li_temp."  id=txtcodtipcta".$li_temp."  value='".$ls_codtipcta."'>".
											 "<input type=hidden  name=txtnomtipcta".$li_temp."  id=txtnomtipcta".$li_temp."  value='".$ls_nomtipcta."'>".
											 "<input type=hidden  name=txtscgcuenta".$li_temp."  id=txtscgcuenta".$li_temp."  value='".$ls_sccuenta."'>".
											 "<input type=hidden  name=txtdisponible".$li_temp." id=txtdisponible".$li_temp." value='".number_format($ld_disponible,2,',','.')."'>".
											 "<input type=hidden  name=txtcodfuefin".$li_temp."  id=txtcodfuefin".$li_temp."  value='".$ls_codfuefin."'>";				
				   }
			}
			if($li_temp==0)
			{
				$li_temp=1;
				$object[$li_temp][1]  = "<input name=chk".$li_temp." type=checkbox id=chk".$li_temp." value=1 class=sin-borde onClick=javascript:uf_selected('".$li_temp."');><input type=hidden   name=txtcodban".$li_temp."  id=txtcodban".$li_temp." value='' readonly>";
				$object[$li_temp][2]  = "<input type=text     name=txtnumsol".$li_temp."          id=txtnumsol".$li_temp."          value=''  class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				$object[$li_temp][3]  = "<input type=text     name=txtconsol".$li_temp."          id=txtconsol".$li_temp."          value=''  class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
				$object[$li_temp][4]  = "<input type=hidden   name=txtcodproben".$li_temp."       id=txtcodproben".$li_temp."       value=''  class=sin-borde readonly style=text-align:left size=30 maxlength=254><input type=text name=txtnomproben".$li_temp." id=txtnomproben".$li_temp." value='' size=30 maxlength=254 class=sin-borde>";
				$object[$li_temp][5]  = "<input type=text     name=txtmonsol".$li_temp."          id=txtmonsol".$li_temp."          value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=16 maxlength=20>";
				$object[$li_temp][6]  = "<input type=text     name=txtmontopendiente".$li_temp."  id=txtmontopendiente".$li_temp."  value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=16 maxlength=20>";				
				$object[$li_temp][7]  = "<input type=text     name=txtmonto".$li_temp."           id=txtmonto".$li_temp."           value='".number_format(0,2,",",".")."' class=sin-borde onBlur=javascript:uf_actualizar_monto(".$li_temp."); style=text-align:right size=16 maxlength=20>";							
				$object[$li_temp][8]  = "<input type=hidden   name=txtmonobjret".$li_temp."       id=txtmonobjret".$li_temp."       value='".number_format(0,2,",",".")."'  class=sin-borde readonly style=text-align:right size=15 maxlength=20><input type=text name=txtmonret".$li_temp."  value='".number_format(0,2,",",".")."'  class=sin-borde readonly style=text-align:right size=15 maxlength=20>";
				$object[$li_temp][9]  = "<input type=text     name=txtnomban".$li_temp."  	      id=txtnomban".$li_temp."          value=''  class=sin-borde  readonly style=text-align:left size=30 maxlength=30>";
				$object[$li_temp][10] = "<input type=text     name=txtctaban".$li_temp."  	      id=txtctaban".$li_temp."          value=''  class=sin-borde  readonly style=text-align:center size=25 maxlength=25>".
										"<input type=hidden  name=txtdenctaban".$li_temp."  id=txtdenctaban".$li_temp."  value=''>".
										"<input type=hidden  name=txtdenctaban".$li_temp."  id=txtdenctaban".$li_temp."  value=''>".
										"<input type=hidden  name=txtcodtipcta".$li_temp."  id=txtcodtipcta".$li_temp."  value=''>".
										"<input type=hidden  name=txtnomtipcta".$li_temp."  id=txtnomtipcta".$li_temp."  value=''>".
										"<input type=hidden  name=txtscgcuenta".$li_temp."  id=txtscgcuenta".$li_temp."  value=''>".
										"<input type=hidden  name=txtdisponible".$li_temp."  id=txtdisponible".$li_temp."  value='0,00'>".
										"<input type=hidden  name=txtcodfuefin".$li_temp."  id=txtcodfuefin".$li_temp."  value=''>";			
			}
			$this->io_sql->free_result($rs_data);
		}
		$li_rows=$li_temp;
	
	}//Fin de uf_cargar_programaciones
	
	
	function uf_load_notas_asociadas($as_codemp,$as_numsol,&$ai_montonotas)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_load_notas_asociadas
		//	          Access:  public
		//	        Arguments  as_codemp //  Código de la Empresa.
		//                     as_numsol //  Número de Identificación de la Solicitud de Pago.
		//                     ai_montonotas //  monto de las Notas de Débito y Crédito.
		//	         Returns:  lb_valido.
		//	     Description:  Función que se encarga de buscar las notas de debito y crédito asociadas a la solicitud de pago. 
		//     Elaborado Por:  Ing. Miguel Palencia
		// Fecha de Creación:  26/09/2007       Fecha Última Actualización:
		////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=true;
		$ai_montonotas=0;
		$ls_sql= "SELECT SUM(CASE cxp_sol_dc.codope WHEN 'NC' THEN (-1*cxp_sol_dc.monto) ".
			   "                                 			ELSE (cxp_sol_dc.monto) END) as total ".
			   "  FROM cxp_dt_solicitudes, cxp_sol_dc ".
			   " WHERE cxp_dt_solicitudes.codemp='".$as_codemp."' ".
			   "   AND cxp_dt_solicitudes.numsol='".$as_numsol."' ".
			   "   AND cxp_sol_dc.estnotadc= 'C' ".
			   "   AND cxp_dt_solicitudes.codemp = cxp_sol_dc.codemp ".
			   "   AND cxp_dt_solicitudes.numsol = cxp_sol_dc.numsol ".
			   "   AND cxp_dt_solicitudes.numrecdoc = cxp_sol_dc.numrecdoc ".
			   "   AND cxp_dt_solicitudes.codtipdoc = cxp_sol_dc.codtipdoc ".
			   "   AND cxp_dt_solicitudes.ced_bene = cxp_sol_dc.ced_bene ".
			   "   AND cxp_dt_solicitudes.cod_pro = cxp_sol_dc.cod_pro";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_load_notas_asociadas".$this->fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_montonotas=$row["total"];
			}
		}
		return $lb_valido;
	}	
	
	function uf_load_fuentefinancimiento($as_codemp,$as_numsol,&$as_codfuefin)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_load_fuentefinancimiento
		//	          Access:  public
		//	        Arguments  as_codemp //  Código de la Empresa.
		//                     as_numsol //  Número de Identificación de la Solicitud de Pago.
		//                     as_codfuefin //  fuente de financiemiento
		//	         Returns:  lb_valido.
		//	     Description:  Función que se encarga de buscar las notas de debito y crédito asociadas a la solicitud de pago. 
		//     Elaborado Por:  Ing. Miguel Palencia
		// Fecha de Creación:  26/09/2007       Fecha Última Actualización:
		////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=true;
		$as_codfuefin="--";
		$ls_sql= "SELECT codfuefin ".
			   "  FROM cxp_solicitudes ".
			   " WHERE codemp='".$as_codemp."' ".
			   "   AND numsol='".$as_numsol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_load_fuentefinancimiento".$this->fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codfuefin=$row["codfuefin"];
			}
		}
		return $lb_valido;
	}	
	
	function uf_select_solcxp_montocancelado($ls_codemp,$ls_numsol,$ls_codban,$ls_ctaban)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	uf_select_solcxp_montocancelado
	// 	Access:		public    -Accesado por el metodo uf_cargar_programaciones
	//  Returns:	Decimal--- Valor decimal con el monto que ha sido cancelado o abonado para la solicitud
	//	Description:	Funcion que suma los montos cancelados o abonados para cada solicitud
	//////////////////////////////////////////////////////////////////////////////
		
		$ls_sql="SELECT sum(monto) as monto
				 FROM   cxp_sol_banco 
				 WHERE  codemp='".$ls_codemp."' AND numsol='".$ls_numsol."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND estmov<>'A' AND estmov<>'O'";
		$rs_data=	$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en consulta,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_montocancelado=$row["monto"];
			}
			else
			{
				$ldec_montocancelado=0;
			}
			$this->io_sql->free_result($rs_data);			
		}
		return $ldec_montocancelado;
	
	}//Fin de uf_select_solcxp_montocancelado
	
	function uf_select_ctaprovbene($as_provbene,$as_codprobene,$as_codban,$as_ctaban)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	  uf_select_catprovben
	//  Access:		  public
	//	Returns:	  String--- Retorno la cuenta contable del proveedor o beneficiario y como parametro de referenica el banco y la cuenta de banco del mismo
	//	Description:  Funcion que busca el banco, la cuenta de banbco y la cuenta contable del proveedor o beneficiario.
	//////////////////////////////////////////////////////////////////////////////
		
		$ls_codemp=$this->dat["codemp"];
		if($as_provbene=='P')
		{
			
			$ls_sql="SELECT codban,ctaban,sc_cuenta
					 FROM   rpc_proveedor 
					 WHERE  codemp='".$ls_codemp."' AND cod_pro='".$as_codprobene."'";
		}
		else
		{
			$ls_sql="SELECT codban,ctaban,sc_cuenta
					 FROM   rpc_beneficiario 
					 WHERE  codemp='".$ls_codemp."' AND ced_bene='".$as_codprobene."'";
		}	
		$rs_data=	$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en consulta,".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codban=$row["codban"];
				$as_ctaban=$row["ctaban"];
				$ls_cuenta_scg=$row["sc_cuenta"];
			}
			else
			{
				$ls_cuenta_scg="";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ls_cuenta_scg;
	
	}//Fin de uf_select_ctaprovbene
	
	function uf_generar_numdoc($ls_numcarord)
	{
		
	
	}
	
	function uf_select_mov_x_provedorbeneficiario($ls_numcarord,$ls_codban,$ls_ctaban,$ls_codope,$ls_codproben,$ls_tipproben)
	{
		if($ls_tipproben=='P')
		{
			$ls_sql="SELECT numdoc 
					 FROM scb_movbco
					 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='".$ls_codope."' AND numcarord='".$ls_numcarord."' AND cod_pro='".$ls_codproben."'";
		}
		else
		{
			$ls_sql="SELECT numdoc 
					 FROM scb_movbco
					 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND codope='".$ls_codope."' AND numcarord='".$ls_numcarord."' AND cod_pro='".$ls_codproben."'";
		}

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_mg_error="Error en metodo uf_select_documento,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data)){  return true; }
			else{ return false;}			
		}
	}
	
	
	function uf_procesar_movbanco($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,
								  $ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,$ls_estreglib,$ls_tipproben,
								  $ls_numcarord,$ls_codfuefin)
	{
		$ls_codemp=$this->dat["codemp"];
		$ls_codusu=$_SESSION["la_logusr"];

		$this->uf_insert_movimiento($ls_codemp,$ls_codusu,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,
									$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,
									$ls_estreglib,$ls_tipproben,$ls_numcarord);

	}
	
	function uf_select_monto_actual($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ldec_monto,$ldec_monobjret,$ldec_monret)
	{
		$lb_valido=true;
		$ldt_fecha=$this->io_function->uf_convertirdatetobd($ldt_fecha);
		$ls_sql="SELECT * 
				 FROM scb_movbco 
				 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."' AND codope='".$ls_codope."' AND fecmov='".$ldt_fecha."'";
		
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->is_msg_error="Error en select movimiento,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
			$ldec_monto=0;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_monto=$row["monto"];
				$ldec_monobjret=$row["monobjret"];
				$ldec_monret=$row["monret"];
			}
			else
			{
				$ldec_monto=0;
				$ldec_monobjret=0;
				$ldec_monret=0;
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}
	
	function uf_update_monto_movimiento($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_estmov)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//
		// -Funcion que inserta la cabecera del movimiento  bancario
		//
		///////////////////////////////////////////////////////////////////////////////////////////////
		$ldt_fecha=$this->io_function->uf_convertirdatetobd($ldt_fecha);
			
		$ls_sql="UPDATE scb_movbco SET monto=".$ldec_monto.",monobjret=".$ldec_monobjret.",monret=".$ldec_monret."
				 WHERE codemp='".$ls_codemp."' 
				   AND codban='".$ls_codban."' 
				   AND ctaban='".$ls_ctaban."'
				   AND numdoc='".$ls_numdoc."'
				   AND codope='".$ls_codope."' 
				   AND fecmov='".$ldt_fecha."'";
	
		$li_result=$this->io_sql->execute($ls_sql);

		if($li_result===false)
		{
			$this->is_msg_error=" Fallo Actualizacion de movimiento, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			return false;
		}
		else
		{
			$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monobjret);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","monretaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monret);
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estmov);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_seguridad);
			
			$this->is_msg_error="Registro Actualizado";
			return true;
		}
	}

	
function uf_insert_movimiento($ls_codemp,$ls_codusu,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ldt_fecha,$ls_conmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto,$ldec_monobjret,$ldec_monret,$ls_chevau,$ls_estmov,$li_estmovint,$li_cobrapaga,$ls_estbpd,$ls_procede,$ls_estreglib,$ls_tipproben,$ls_numcarord)
{
	////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// -Funcion que inserta la cabecera del movimiento  bancario
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	$ldt_fecha=$this->io_function->uf_convertirdatetobd($ldt_fecha);
	
	$ls_sql="INSERT INTO scb_movbco(codemp,codusu,codban,ctaban,numdoc,codope,fecmov,conmov,codconmov,cod_pro,ced_bene,nomproben,monto,monobjret,monret,chevau,estmov,estmovint,estcobing,esttra,estbpd,procede,estcon,feccon,estreglib,tipo_destino,numcarord,codfuefin)
			 VALUES                ('".$ls_codemp."','".$ls_codusu."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$ls_codope."','".$ldt_fecha."','".$ls_conmov."','".$ls_codconmov."','".$ls_codpro."','".$ls_cedbene."','".$ls_nomproben."',".$ldec_monto.",".$ldec_monobjret.",".$ldec_monret.",'".$ls_chevau."','".$ls_estmov."',".$li_estmovint.",".$li_cobrapaga.", 0    ,'".$ls_estbpd."','".$ls_procede."',   0  ,'1900-01-01','".$ls_estreglib."','".$ls_tipproben."','".$ls_numcarord."','--')";

	$li_result=$this->io_sql->execute($ls_sql);
	if(($li_result===false))
	{
		$this->is_msg_error="Fallo insercion de movimiento, ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->io_sql->message;
		return false;		
	}
	else
	{
  	    $this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
		$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);

		$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
		$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monobjret);

		$this->io_rcbsf->io_ds_datos->insertRow("campo","monretaux");
		$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monret);
		
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

		$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
		$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estmov);
		$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_seguridad);	
		$this->is_msg_error="Registro insertado";
		//print $this->is_msg_error;
		return true;		
	}
	
}
	
	function uf_procesar_carta_orden($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_numsol,$ls_estmov,$ldec_monto,$ls_estdoc)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_procesar_emision_scq
	// Access:			public
	//	Returns:		Boolean Retorna si proceso correctamente
	//	Description:	Funcion que se encarga de guardar los detalles d ela emision de cheque
	//////////////////////////////////////////////////////////////////////////////
	
		$ls_codemp=$this->dat["codemp"];
	
		$ls_sql="INSERT INTO cxp_sol_banco(codemp,codban,ctaban,numdoc,codope,numsol,estmov,monto)
		 		 VALUES('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$ls_codope."','".$ls_numsol."','".$ls_estmov."',".$ldec_monto.")";
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en insert cxp_sol_banco,".$this->io_function->uf_convertirmsg($this->io_sql->message);		
			print $this->io_sql->message;
		}
		else
		{
         	
			$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estmov);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_sol_banco",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_seguridad);
 
			$lb_valido=true;
			if($ls_estdoc=='C')
			{
				$ls_sql="UPDATE scb_prog_pago
						 SET    estmov = '".$ls_estmov."'
	 					 WHERE  codemp='".$ls_codemp."' AND numsol='".$ls_numsol."'";
				$li_row=$this->io_sql->execute($ls_sql);
				if(($li_row===false))
				{
					$lb_valido=false;
					$this->is_msg_error="Error en actualizar scb_prog_pago, ".$this->io_function->uf_convertirmsg($this->io_sql->message);										
				}
				else
				{
					$lb_valido=true;							
				}				
			}
		}

		return $lb_valido;
	
	}//Fin de  uf_procesar_emision_chq
	
	
	function uf_buscar_dt_cxpspg($as_numsol)
	{
	//////////////////////////////////////////////////////////////////////////////
	//
	//	Function:	    uf_buscar_dt_cxpspg
	// 					
	// 	Access:			public
	//
	//	Returns:		Boolean Retorna si proceso correctamente
	//
	//	Description:	Funcion que se buscar el detalle presupuestario de una solicitud de pago 
	//               
	//////////////////////////////////////////////////////////////////////////////
		$li_row=0;
		$lb_valido=false;
		$aa_dt_cxpspg=array();
		$ls_codemp=$this->dat["codemp"];
		$ls_sql="SELECT numsol, numdoc, monto as montochq 
				 FROM cxp_sol_banco 
				 WHERE codemp='".$ls_codemp."' AND numsol ='".$as_numsol."' AND 
				 (estmov='N' OR estmov='C')";
			 
		$rs_data=	$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en consulta,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_row=$li_row+1;
				$ls_cheque=$row["numdoc"];
				$ls_numsol=$row["numsol"];
				$ldec_montochq=$row["montochq"];
				$ls_sql="SELECT codestpro, spg_cuenta, sum(monto) as monto
						 FROM scb_movbco_spg
		    			 WHERE codemp='".$ls_codemp."' AND procede_doc='CXPSOP' AND numdoc='".$ls_cheque."' AND documento ='".$ls_numsol."' 
						 GROUP BY codestpro, spg_cuenta ";	
				$rs_dt_spgchq=	$this->io_sql_aux->select($ls_sql);	

				if($rs_dt_spgchq===false)
				{
					$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql_aux->message);
					print $this->is_msg_error;	
					$lb_valido=false;		
				}
				else
				{
					while($row=$this->io_sql_aux->fetch_row($rs_dt_spgchq))
					{
						$ls_codestpro1=substr($row["codestpro"],0,20);
						$ls_codestpro2=substr($row["codestpro"],20,6);
						$ls_codestpro3=substr($row["codestpro"],26,3);
						$ls_codestpro4=substr($row["codestpro"],29,2);	
						$ls_codestpro5=substr($row["codestpro"],31,2);
						$ls_spgcuenta=$row["spg_cuenta"];						
						$ldec_monto=$row["monto"];
						$this->ds_temp->insertRow("codestpro1",$ls_codestpro1);
						$this->ds_temp->insertRow("codestpro2",$ls_codestpro2);
						$this->ds_temp->insertRow("codestpro3",$ls_codestpro3);
						$this->ds_temp->insertRow("codestpro4",$ls_codestpro4);
						$this->ds_temp->insertRow("codestpro5",$ls_codestpro5);
						$this->ds_temp->insertRow("spg_cuenta",$ls_spgcuenta);
						$this->ds_temp->insertRow("monto",$ldec_monto);
					}
				$this->io_sql_aux->free_result($rs_dt_spgchq);
				} 
			}
		}
			if(array_key_exists("codestpro1",$this->ds_temp->data))
			{
				if($this->ds_temp->getRowCount("codestpro1")>0)
				{
					$arr_group[0]="codestpro1";
					$arr_group[1]="codestpro2";
					$arr_group[2]="codestpro3";
					$arr_group[3]="codestpro4";
					$arr_group[4]="codestpro5";
					$arr_group[5]="spg_cuenta";
					//Agrupo el datastore por programaticas y cuentas y sumo el monto
					$this->ds_temp->group_by($arr_group,array('0'=>"monto"),"monto");
				}			
			}
		$li_row=0;
		// esta era la consulta anterior antes de que no tomara las notas de débito y crédito
/*		$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,sum(monto) as monto 
				 FROM spg_dt_cmp
				 WHERE codemp='".$ls_codemp."' AND procede='CXPSOP' AND comprobante='".$as_numsol."'
				 GROUP BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta";*/
		$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,sum(monto) as monto ,descripcion ".
				"	FROM spg_dt_cmp ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND procede='CXPSOP' ".
				"   AND comprobante='".$as_numsol."' ".
				" GROUP BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,descripcion ".
				" UNION ".
				"SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,sum(spg_dt_cmp.monto) as monto ,descripcion ".
				"	FROM spg_dt_cmp, cxp_dt_solicitudes, cxp_sol_dc ".
				" WHERE spg_dt_cmp.codemp='".$ls_codemp."' ".
				"   AND spg_dt_cmp.procede='CXPNOC' ".
				"   AND spg_dt_cmp.comprobante=LPAD(cxp_sol_dc.numdc,15,'0') ".
				"   AND cxp_dt_solicitudes.numsol='".$as_numsol."' ".
				"   AND cxp_dt_solicitudes.codemp = cxp_sol_dc.codemp ".
				"   AND cxp_dt_solicitudes.numsol = cxp_sol_dc.numsol ".
				"   AND cxp_dt_solicitudes.numrecdoc = cxp_sol_dc.numrecdoc ".
				"   AND cxp_dt_solicitudes.codtipdoc = cxp_sol_dc.codtipdoc ".
				"   AND cxp_dt_solicitudes.ced_bene = cxp_sol_dc.ced_bene ".
				"   AND cxp_dt_solicitudes.cod_pro = cxp_sol_dc.cod_pro ".
				" GROUP BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,descripcion ".
				" UNION  ".
				"SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,sum(spg_dt_cmp.monto) as monto ,descripcion ".
				"	FROM spg_dt_cmp, cxp_dt_solicitudes, cxp_sol_dc ".
				" WHERE spg_dt_cmp.codemp='".$ls_codemp."' ".
				"   AND spg_dt_cmp.procede='CXPNOD' ".
				"   AND spg_dt_cmp.comprobante=LPAD(cxp_sol_dc.numdc,15,'0') ".
				"   AND cxp_dt_solicitudes.numsol='".$as_numsol."' ".
				"   AND cxp_dt_solicitudes.codemp = cxp_sol_dc.codemp ".
				"   AND cxp_dt_solicitudes.numsol = cxp_sol_dc.numsol ".
				"   AND cxp_dt_solicitudes.numrecdoc = cxp_sol_dc.numrecdoc ".
				"   AND cxp_dt_solicitudes.codtipdoc = cxp_sol_dc.codtipdoc ".
				"   AND cxp_dt_solicitudes.ced_bene = cxp_sol_dc.ced_bene ".
				"   AND cxp_dt_solicitudes.cod_pro = cxp_sol_dc.cod_pro ".
				" GROUP BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,descripcion ";
		$rs_data_dtcxpspg=	$this->io_sql->select($ls_sql);
		if($rs_data_dtcxpspg===false)
		{
			$this->is_msg_error="Error en consulta,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data_dtcxpspg))
			{
				$li_row=$li_row+1;
				$ls_codestpro1=$row["codestpro1"];
				$aa_dt_cxpspg["codestpro1"][$li_row] = $ls_codestpro1;
				$ls_codestpro2=$row["codestpro2"];
				$aa_dt_cxpspg["codestpro2"][$li_row] = $ls_codestpro2;
				$ls_codestpro3=$row["codestpro3"];
				$aa_dt_cxpspg["codestpro3"][$li_row] = $ls_codestpro3;
				$ls_codestpro4=$row["codestpro4"];
				$aa_dt_cxpspg["codestpro4"][$li_row] = $ls_codestpro4;
				$ls_codestpro5=$row["codestpro5"];
				$aa_dt_cxpspg["codestpro5"][$li_row] = $ls_codestpro5;
				$ls_spg_cuenta=$row["spg_cuenta"];
				$aa_dt_cxpspg["spg_cuenta"][$li_row] = $ls_spg_cuenta;			
				$ldec_monto=$row["monto"];
				$aa_dt_cxpspg["monto"][$li_row]      = $ldec_monto;			

				// AQUI ESTABA FALLANDO YA QUE ME ESTABA RESTANDO 2 VECES CUANDO TENIA UN PAGO ANTERIOR				
				/*$li_row_tots=$this->ds_temp->getRowCount("codestpro1");
				if($li_row_tots>0)
				{
					for($li_i=1;$li_i<=$li_row_tots;$li_i++)
					{
						$ls_estpro1=$this->ds_temp->getValue("codestpro1",$li_i);
						$ls_estpro2=$this->ds_temp->getValue("codestpro2",$li_i);
						$ls_estpro3=$this->ds_temp->getValue("codestpro3",$li_i);
						$ls_estpro4=$this->ds_temp->getValue("codestpro4",$li_i);
						$ls_estpro5=$this->ds_temp->getValue("codestpro5",$li_i);
						$ls_cuentaspg=$this->ds_temp->getValue("spg_cuenta",$li_i);
						$ldec_montotmp=$this->ds_temp->getValue("monto",$li_i);
						if(($ls_codestpro1==$ls_estpro1)&&($ls_codestpro2==$ls_estpro2)&&($ls_codestpro3==$ls_estpro3)&&($ls_codestpro4==$ls_estpro4)&&($ls_codestpro5==$ls_estpro5)&&($ls_spg_cuenta==$ls_cuentaspg))
						{
							$ldec_new_monto=doubleval($ldec_monto)-doubleval($ldec_montotmp);
							$aa_dt_cxpspg["monto"][$li_row]=$ldec_new_monto;
						}//End if
					}//End For
				}//End if	*/		
				
			}//End While
			$this->io_sql->free_result($rs_data_dtcxpspg);
			//Asigno la matriz de detalles presupuestarios al datastore.
			$arr_group[0]="codestpro1";
			$arr_group[1]="codestpro2";
			$arr_group[2]="codestpro3";
			$arr_group[3]="codestpro4";
			$arr_group[4]="codestpro5";
			$arr_group[5]="spg_cuenta";
			$this->ds_sol->data=$aa_dt_cxpspg;
			$this->ds_sol->group_by($arr_group,array('0'=>"monto"),"monto");
			$li_row=$this->ds_sol->getRowCount("codestpro1");
			if($li_row>0)
			{
				for($li_j=1;$li_j<=$li_row;$li_j++)
				{
					$ls_codestpro1=$this->ds_sol->getValue("codestpro1",$li_j);
					$ls_codestpro2=$this->ds_sol->getValue("codestpro2",$li_j);
					$ls_codestpro3=$this->ds_sol->getValue("codestpro3",$li_j);
					$ls_codestpro4=$this->ds_sol->getValue("codestpro4",$li_j);
					$ls_codestpro5=$this->ds_temp->getValue("codestpro5",$li_j);
					$ls_spg_cuenta=$this->ds_sol->getValue("spg_cuenta",$li_j);
					$ldec_monto=$this->ds_sol->getValue("monto",$li_j);
					$li_row_tots=$this->ds_temp->getRowCount("codestpro1");
					if($li_row_tots>0)
					{
						for($li_i=1;$li_i<=$li_row_tots;$li_i++)
						{
							$ls_estpro1=$this->ds_temp->getValue("codestpro1",$li_i);
							$ls_estpro2=$this->ds_temp->getValue("codestpro2",$li_i);
							$ls_estpro3=$this->ds_temp->getValue("codestpro3",$li_i);
							$ls_estpro4=$this->ds_temp->getValue("codestpro4",$li_i);
							$ls_estpro5=$this->ds_temp->getValue("codestpro5",$li_i);
							$ls_cuentaspg=$this->ds_temp->getValue("spg_cuenta",$li_i);
							$ldec_montotmp=$this->ds_temp->getValue("monto",$li_i);
							
							if(($ls_codestpro1==$ls_estpro1)&&($ls_codestpro2==$ls_estpro2)&&($ls_codestpro3==$ls_estpro3)&&($ls_codestpro4==$ls_estpro4)&&($ls_codestpro5==$ls_estpro5)&&($ls_spg_cuenta==$ls_cuentaspg))
							{
								$ldec_new_monto=doubleval($ldec_monto)-doubleval($ldec_montotmp);
								$this->ds_sol->updateRow("monto",$ldec_new_monto,$li_j);
							}//End if
						}//End For
					}//End if	
				}
			}			
		}//End if
	}//Fin uf_buscar_dt_cxpspg.
	
	function uf_procesar_dtmov($ls_codemp, $ls_codban, $ls_cuenta_banco, $ls_numcarord, $ls_codope,$ls_estmov, $ls_cod_pro, $ls_cedbene, $ls_numsol, $ldec_monto,$ls_ctabanbene)
	{
		$lb_valido=true;
		$ls_sql="INSERT INTO scb_dt_movbco(codemp, codban, ctaban, numdoc, codope, estmov, cod_pro, ced_bene, numsolpag, monsolpag,ctabanbene)
				 VALUES('$ls_codemp','$ls_codban','$ls_cuenta_banco','$ls_numcarord','$ls_codope','$ls_estmov','$ls_cod_pro','$ls_cedbene','$ls_numsol','$ldec_monto','$ls_ctabanbene')";

		$li_exec=$this->io_sql->execute($ls_sql);				 
		if($li_exec===false)
		{
			$this->is_msg_error="Error en consulta,".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
		    $this->io_rcbsf->io_ds_datos->insertRow("campo","monsolpagaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_monto);
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cuenta_banco);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numcarord);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estmov);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cod_pro);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedbene);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsolpag");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_dt_movbco",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_seguridad);
			return true;
		}
		return $lb_valido;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_fuentefinancimiento($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$as_codfuefin)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_fuentefinancimiento
		//		   Access: public  
		//	    Arguments: as_codemp  // Código de empresa
		//				   as_codban  // Código de Banco
		//				   as_ctaban  // Cuenta del Banco
		//				   as_numdoc  // Número de Documento
		//				   as_codope  // Código de Operación
		//				   as_estmov  // Estatus del Movimiento
		//				   as_codfuefin  // código de La fuente de Financiamiento
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la fuente de financiamiento por movimiento de banco
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 09/10/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codfuefin ".
				"  FROM scb_movbco_fuefinanciamiento ".
				" WHERE	codemp='".$as_codemp."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND codope='".$as_codope."' ".
				"   AND estmov='".$as_estmov."' ".
				"   AND numdoc='".$as_numdoc."' ".
				"   AND codfuefin='".$as_codfuefin."' ";
		$rs_data=$this->io_sql->select($ls_sql);	
		if($rs_data===false)
		{
			$this->is_msg_error="Error en consulta,".$this->fun->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;	
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
				$ls_sql="INSERT INTO scb_movbco_fuefinanciamiento(codemp, codban, ctaban, numdoc, codope, estmov, codfuefin) VALUES ".
						"('".$as_codemp."','".$as_codban."','".$as_ctaban."','".$as_numdoc."','".$as_codope."','".$as_estmov."','".$as_codfuefin."')";
				$li_numrow=$this->io_sql->execute($ls_sql);
				if($li_numrow===false)
				{
					$lb_valido=false;
					print $this->io_sql->message;
					$this->msg->message("CLASE->Movimiento de Banco MÉTODO->uf_insert_fuentefinancimiento ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message));
				}
			}
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>