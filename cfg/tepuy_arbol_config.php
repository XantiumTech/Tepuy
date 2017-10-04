<?php
$gi_total=47;
$arbol["sistema"][1]="Sistemas";
$arbol["nivel"][1]=0;
$arbol["nombre_logico"][1]="tepuy";
$arbol["nombre_fisico"][1]="";
$arbol["id"][1]="001";
$arbol["padre"][1]="000";
$arbol["numero_hijos"][1]=4;

$arbol["sistema"][2]="Sistemas";
$arbol["nivel"][2]=1;
$arbol["nombre_logico"][2]="Empresa";
$arbol["nombre_fisico"][2]="../cfg/tepuy_cfg_d_empresa.php";
$arbol["id"][2]="002";
$arbol["padre"][2]="001";
$arbol["numero_hijos"][2]=0;

$arbol["sistema"][3]="Sistemas";
$arbol["nivel"][3]=1;
$arbol["nombre_logico"][3]="Procedencias";
$arbol["nombre_fisico"][3]="../cfg/tepuy_cfg_d_procedencia.php";
$arbol["id"][3]="003";
$arbol["padre"][3]="001";
$arbol["numero_hijos"][3]=0;

$arbol["sistema"][4]="Sistemas";
$arbol["nivel"][4]=1;
$arbol["nombre_logico"][4]="Ubicacion Geografica";
$arbol["nombre_fisico"][4]="";
$arbol["id"][4]="004";
$arbol["padre"][4]="001";
$arbol["numero_hijos"][4]=5;

$arbol["sistema"][5]="Sistemas";
$arbol["nivel"][5]=2;
$arbol["nombre_logico"][5]="Paises";
$arbol["nombre_fisico"][5]="../cfg/rpc/tepuy_rpc_d_pais.php";
$arbol["id"][5]="005";
$arbol["padre"][5]="004";
$arbol["numero_hijos"][5]=0;

$arbol["sistema"][6]="Sistemas";
$arbol["nivel"][6]=2;
$arbol["nombre_logico"][6]="Estados";
$arbol["nombre_fisico"][6]="../cfg/rpc/tepuy_rpc_d_estado.php";
$arbol["id"][6]="006";
$arbol["padre"][6]="004";
$arbol["numero_hijos"][6]=0;

$arbol["sistema"][7]="Sistemas";
$arbol["nivel"][7]=2;
$arbol["nombre_logico"][7]="Municipios";
$arbol["nombre_fisico"][7]="../cfg/rpc/tepuy_rpc_d_municipio.php";
$arbol["id"][7]="007";
$arbol["padre"][7]="004";
$arbol["numero_hijos"][7]=0;

$arbol["sistema"][8]="Sistemas";
$arbol["nivel"][8]=2;
$arbol["nombre_logico"][8]="Parroquias";
$arbol["nombre_fisico"][8]="../cfg/rpc/tepuy_rpc_d_parroquia.php";
$arbol["id"][8]="008";
$arbol["padre"][8]="004";
$arbol["numero_hijos"][8]=0;

$arbol["sistema"][9]="Sistemas";
$arbol["nivel"][9]=0;
$arbol["nombre_logico"][9]="Contabilidad Patrimonial/Fiscal";
$arbol["nombre_fisico"][9]="";
$arbol["id"][9]="009";
$arbol["padre"][9]="000";
$arbol["numero_hijos"][9]=3;

$arbol["sistema"][10]="Sistemas";
$arbol["nivel"][10]=1;
$arbol["nombre_logico"][10]="Plan de Cuentas Patrimoniales";
$arbol["nombre_fisico"][10]="../cfg/scg/tepuy_scg_d_plan_unico.php";
$arbol["id"][10]="010";
$arbol["padre"][10]="009";
$arbol["numero_hijos"][10]=0;

$arbol["sistema"][11]="Sistemas";
$arbol["nivel"][11]=1;
$arbol["nombre_logico"][11]="Catalogo de Recursos Y Egresos";
$arbol["nombre_fisico"][11]="../cfg/scg/tepuy_scg_d_plan_unicore.php";
$arbol["id"][11]="011";
$arbol["padre"][11]="009";
$arbol["numero_hijos"][11]=0;

$arbol["sistema"][12]="Sistemas";
$arbol["nivel"][12]=1;
$arbol["nombre_logico"][12]="Plan de Cuentas";
$arbol["nombre_fisico"][12]="../cfg/scg/tepuy_scg_d_plan_ctas.php";
$arbol["id"][12]="012";
$arbol["padre"][12]="009";
$arbol["numero_hijos"][12]=0;

$arbol["sistema"][13]="Sistemas";
$arbol["nivel"][13]=0;
$arbol["nombre_logico"][13]="Presupuesto de Gasto";
$arbol["nombre_fisico"][13]="";
$arbol["id"][13]="013";
$arbol["padre"][13]="000";
$arbol["numero_hijos"][13]=6;

$arbol["sistema"][14]="Sistemas";
$arbol["nivel"][14]=1;
$arbol["nombre_logico"][14]="Estructura Presupuestaria";
$arbol["nombre_fisico"][14]="../cfg/spg/tepuy_spg_d_estprog1.php";
$arbol["id"][14]="014";
$arbol["padre"][14]="013";
$arbol["numero_hijos"][14]=0;

$arbol["sistema"][15]="Sistemas";
$arbol["nivel"][15]=1;
$arbol["nombre_logico"][15]="Fuente de Financiamiento";
$arbol["nombre_fisico"][15]="../cfg/spg/tepuy_spg_d_fuentfinan.php";
$arbol["id"][15]="015";
$arbol["padre"][15]="013";
$arbol["numero_hijos"][15]=0;

$arbol["sistema"][16]="Sistemas";
$arbol["nivel"][16]=1;
$arbol["nombre_logico"][16]="Plan de Cuentas";
$arbol["nombre_fisico"][16]="../cfg/spg/tepuy_spg_d_planctas.php";
$arbol["id"][16]="016";
$arbol["padre"][16]="013";
$arbol["numero_hijos"][16]=0;

$arbol["sistema"][17]="Sistemas";
$arbol["nivel"][17]=1;
$arbol["nombre_logico"][17]="Unidad Administradora";
$arbol["nombre_fisico"][17]="../cfg/spg/tepuy_spg_d_uniadm.php";
$arbol["id"][17]="017";
$arbol["padre"][17]="013";
$arbol["numero_hijos"][17]=0;

$arbol["sistema"][18]="Sistemas";
$arbol["nivel"][18]=1;
$arbol["nombre_logico"][18]="Unidad Ejecutora";
$arbol["nombre_fisico"][18]="../cfg/spg/tepuy_spg_d_unidad.php";
$arbol["id"][18]="018";
$arbol["padre"][18]="013";
$arbol["numero_hijos"][18]=0;

$arbol["sistema"][19]="Sistemas";
$arbol["nivel"][19]=0;
$arbol["nombre_logico"][19]="Cuentas Por Pagar";
$arbol["nombre_fisico"][19]="";
$arbol["id"][19]="019";
$arbol["padre"][19]="000";
$arbol["numero_hijos"][19]=4;

$arbol["sistema"][20]="Sistemas";
$arbol["nivel"][20]=1;
$arbol["nombre_logico"][20]="Deducciones";
$arbol["nombre_fisico"][20]="../cfg/cxp/tepuy_cxp_d_deducciones.php";
$arbol["id"][20]="020";
$arbol["padre"][20]="019";
$arbol["numero_hijos"][20]=0;

$arbol["sistema"][21]="Sistemas";
$arbol["nivel"][21]=1;
$arbol["nombre_logico"][21]="Otros Creditos";
$arbol["nombre_fisico"][21]="../cfg/cxp/tepuy_cxp_d_otroscreditos.php";
$arbol["id"][21]="021";
$arbol["padre"][21]="019";
$arbol["numero_hijos"][21]=0;

$arbol["sistema"][22]="Sistemas";
$arbol["nivel"][22]=1;
$arbol["nombre_logico"][22]="Documentos";
$arbol["nombre_fisico"][22]="../cfg/cxp/tepuy_cxp_d_documentos.php";
$arbol["id"][22]="022";
$arbol["padre"][22]="019";
$arbol["numero_hijos"][22]=0;

$arbol["sistema"][23]="Sistemas";
$arbol["nivel"][23]=1;
$arbol["nombre_logico"][23]="Clasificador";
$arbol["nombre_fisico"][23]="../cfg/cxp/tepuy_cxp_d_clasificador.php";
$arbol["id"][23]="023";
$arbol["padre"][23]="019";
$arbol["numero_hijos"][23]=0;

$arbol["sistema"][24]="Sistemas";
$arbol["nivel"][24]=0;
$arbol["nombre_logico"][24]="Solicitud de Ejecucion Presupuestaria";
$arbol["nombre_fisico"][24]="";
$arbol["id"][24]="024";
$arbol["padre"][24]="000";
$arbol["numero_hijos"][24]=2;

$arbol["sistema"][25]="Sistemas";
$arbol["nivel"][25]=1;
$arbol["nombre_logico"][25]="Tipo";
$arbol["nombre_fisico"][25]="../cfg/sep/tepuy_sep_d_tipo.php";
$arbol["id"][25]="025";
$arbol["padre"][25]="024";
$arbol["numero_hijos"][25]=0;

$arbol["sistema"][26]="Sistemas";
$arbol["nivel"][26]=1;
$arbol["nombre_logico"][26]="Concepto";
$arbol["nombre_fisico"][26]="../cfg/sep/tepuy_sep_d_concepto.php";
$arbol["id"][26]="026";
$arbol["padre"][26]="024";
$arbol["numero_hijos"][26]=0;

$arbol["sistema"][27]="Sistemas";
$arbol["nivel"][27]=0;
$arbol["nombre_logico"][27]="Ordenes de Compras";
$arbol["nombre_fisico"][27]="";
$arbol["id"][27]="027";
$arbol["padre"][27]="000";
$arbol["numero_hijos"][27]=4;

$arbol["sistema"][28]="Sistemas";
$arbol["nivel"][28]=1;
$arbol["nombre_logico"][28]="Tipo de Servicios";
$arbol["nombre_fisico"][28]="../cfg/soc/tepuy_soc_d_tiposer.php";
$arbol["id"][28]="028";
$arbol["padre"][28]="027";
$arbol["numero_hijos"][28]=0;

$arbol["sistema"][29]="Sistemas";
$arbol["nivel"][29]=1;
$arbol["nombre_logico"][29]="Servicios";
$arbol["nombre_fisico"][29]="../cfg/soc/tepuy_soc_d_servicio.php";
$arbol["id"][29]="029";
$arbol["padre"][29]="027";
$arbol["numero_hijos"][29]=0;

$arbol["sistema"][30]="Sistemas";
$arbol["nivel"][30]=1;
$arbol["nombre_logico"][30]="Clausulas ";
$arbol["nombre_fisico"][30]="../cfg/soc/tepuy_soc_d_clausulas.php";
$arbol["id"][30]="030";
$arbol["padre"][30]="027";
$arbol["numero_hijos"][30]=0;

$arbol["sistema"][31]="Sistemas";
$arbol["nivel"][31]=1;
$arbol["nombre_logico"][31]="Modalidad de Clausulas";
$arbol["nombre_fisico"][31]="../cfg/soc/tepuy_soc_d_modcla.php";
$arbol["id"][31]="030";
$arbol["padre"][31]="027";
$arbol["numero_hijos"][31]=0;

$arbol["sistema"][32]="Sistemas";
$arbol["nivel"][32]=0;
$arbol["nombre_logico"][32]="Banco";
$arbol["nombre_fisico"][32]="";
$arbol["id"][32]="032";
$arbol["padre"][32]="000";
$arbol["numero_hijos"][32]=8;

$arbol["sistema"][33]="Sistemas";
$arbol["nivel"][33]=1;
$arbol["nombre_logico"][33]="Banco";
$arbol["nombre_fisico"][33]="../cfg/scb/tepuy_scb_d_banco.php";
$arbol["id"][33]="033";
$arbol["padre"][33]="032";
$arbol["numero_hijos"][33]=0;

$arbol["sistema"][34]="Sistemas";
$arbol["nivel"][34]=1;
$arbol["nombre_logico"][34]="Tipo de Cuenta";
$arbol["nombre_fisico"][34]="../cfg/scb/tepuy_scb_d_tipocta.php";
$arbol["id"][34]="034";
$arbol["padre"][34]="032";
$arbol["numero_hijos"][34]=0;

$arbol["sistema"][35]="Sistemas";
$arbol["nivel"][35]=1;
$arbol["nombre_logico"][35]="Cuenta Banco";
$arbol["nombre_fisico"][35]="../cfg/scb/tepuy_scb_d_ctabanco.php";
$arbol["id"][35]="035";
$arbol["padre"][35]="032";
$arbol["numero_hijos"][35]=0;

$arbol["sistema"][36]="Sistemas";
$arbol["nivel"][36]=1;
$arbol["nombre_logico"][36]="Chequera";
$arbol["nombre_fisico"][36]="../cfg/scb/tepuy_scb_d_chequera.php";
$arbol["id"][36]="036";
$arbol["padre"][36]="032";
$arbol["numero_hijos"][36]=0;

$arbol["sistema"][37]="Sistemas";
$arbol["nivel"][37]=1;
$arbol["nombre_logico"][37]="Tipo de Colocacion";
$arbol["nombre_fisico"][37]="../cfg/scb/tepuy_scb_d_tipocolocacion.php";
$arbol["id"][37]="037";
$arbol["padre"][37]="032";
$arbol["numero_hijos"][37]=0;

$arbol["sistema"][38]="Sistemas";
$arbol["nivel"][38]=1;
$arbol["nombre_logico"][38]="Colocación";
$arbol["nombre_fisico"][38]="../cfg/scb/tepuy_scb_d_colocacion.php";
$arbol["id"][38]="038";
$arbol["padre"][38]="032";
$arbol["numero_hijos"][38]=0;

$arbol["sistema"][39]="Sistemas";
$arbol["nivel"][39]=1;
$arbol["nombre_logico"][39]="Conceptos de Movimientos";
$arbol["nombre_fisico"][39]="../cfg/scb/tepuy_scb_d_conceptos.php";
$arbol["id"][39]="039";
$arbol["padre"][39]="032";
$arbol["numero_hijos"][39]=0;

$arbol["sistema"][40]="Sistemas";
$arbol["nivel"][40]=1;
$arbol["nombre_logico"][40]="Agencias";
$arbol["nombre_fisico"][40]="../cfg/scb/tepuy_scb_d_agencia.php";
$arbol["id"][40]="040";
$arbol["padre"][40]="032";
$arbol["numero_hijos"][40]=0;

$arbol["sistema"][41]="Sistemas";
$arbol["nivel"][41]=0;
$arbol["nombre_logico"][41]="Presupuesto de Ingreso";
$arbol["nombre_fisico"][41]="";
$arbol["id"][41]="041";
$arbol["padre"][41]="000";
$arbol["numero_hijos"][41]=1;

$arbol["sistema"][42]       = "Sistemas";
$arbol["nivel"][42]         = 1;
$arbol["nombre_logico"][42] = "Plan de Cuentas";
$arbol["nombre_fisico"][42] = "../cfg/spi/tepuy_spi_d_planctas.php";
$arbol["id"][42]            = "042";
$arbol["padre"][42]         = "041";
$arbol["numero_hijos"][42]  = 0;

$arbol["sistema"][43]       = "Sistemas";
$arbol["nivel"][43]         = 1;
$arbol["nombre_logico"][43] = "Comunidades";
$arbol["nombre_fisico"][43] = "../cfg/rpc/tepuy_rpc_d_comunidad.php";
$arbol["id"][43]            = "043";
$arbol["padre"][43]         = "001";
$arbol["numero_hijos"][43]  = 0;

$arbol["sistema"][44]       = "Sistemas";
$arbol["nivel"][44]         = 2;
$arbol["nombre_logico"][44] = "Ciudades";
$arbol["nombre_fisico"][44] = "../cfg/rpc/tepuy_scv_d_ciudad.php";
$arbol["id"][44]            = "044";
$arbol["padre"][44]         = "004";
$arbol["numero_hijos"][44]  = 0;

$arbol["sistema"][45]       = "Sistemas";
$arbol["nivel"][45]         = 1;
$arbol["nombre_logico"][45] = "Control Numero";
$arbol["nombre_fisico"][45] = "../cfg/tepuy_cfg_d_ctrl_numero.php";
$arbol["id"][45]            = "045";
$arbol["padre"][45]         = "001";
$arbol["numero_hijos"][45]  = 0;

$arbol["sistema"][46]       = "Sistemas";
$arbol["nivel"][46]         = 1;
$arbol["nombre_logico"][46] = "Unidad Tributaria";
$arbol["nombre_fisico"][46] = "../cfg/tepuy_cfg_d_unidad_tributaria.php";
$arbol["id"][46]            = "046";
$arbol["padre"][46]         = "001";
$arbol["numero_hijos"][46]  = 0;

$arbol["sistema"][47]="Sistemas";
$arbol["nivel"][47]=1;
$arbol["nombre_logico"][47]="Traspasos de Cuentas entre Estructuras Presupuestarias";
$arbol["nombre_fisico"][47]="../cfg/spg/tepuy_spg_d_copiarplandecuentas.php";
$arbol["id"][47]="047";
$arbol["padre"][47]="013";
$arbol["numero_hijos"][47]=0;
?>