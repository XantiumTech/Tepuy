<?php
$gi_total=47;
/////////////// RECEPCION DE DOCUMENTOS ////////////
 // 001
$arbol["sistema"][1]="CXP";
$arbol["nivel"][1]=0;
$arbol["nombre_logico"][1]="Recepción de Documentos";
$arbol["nombre_fisico"][1]="";
$arbol["id"][1]="001";
$arbol["padre"][1]="000";
$arbol["numero_hijos"][1]=4;

 // 002
$arbol["sistema"][2]="CXP";
$arbol["nivel"][2]=1;
$arbol["nombre_logico"][2]="Recepción de Documentos";
$arbol["nombre_fisico"][2]="tepuy_cxp_p_recepcioncontable.php";
$arbol["id"][2]="002";
$arbol["padre"][2]="001";
$arbol["numero_hijos"][2]=0;

 // 003
$arbol["sistema"][3]="CXP";
$arbol["nivel"][3]=1;
$arbol["nombre_logico"][3]="Aprobación de Recepción de Documentos";
$arbol["nombre_fisico"][3]="tepuy_cxp_p_aprobacionrecepcion.php";
$arbol["id"][3]="003";
$arbol["padre"][3]="001";
$arbol["numero_hijos"][3]=0;

 //004
$arbol["sistema"][4]="CXP";
$arbol["nivel"][4]=1;
$arbol["nombre_logico"][4]="Anulación de Recepción de Documentos";
$arbol["nombre_fisico"][4]="tepuy_cxp_p_anulacionrecepcion.php";
$arbol["id"][4]="004";
$arbol["padre"][4]="001";
$arbol["numero_hijos"][4]=0;

 //005
$arbol["sistema"][5]="CXP";
$arbol["nivel"][5]=1;
$arbol["nombre_logico"][5]="Registro de Beneficiarios";
$arbol["nombre_fisico"][5]="tepuy_rpc_d_beneficiario.php";
$arbol["id"][5]="005";
$arbol["padre"][5]="001";
$arbol["numero_hijos"][5]=0;

 //006
$arbol["sistema"][6]="CXP";
$arbol["nivel"][6]=1;
$arbol["nombre_logico"][6]="Registro de Proveedores";
$arbol["nombre_fisico"][6]="tepuy_rpc_d_proveedor.php";
$arbol["id"][6]="006";
$arbol["padre"][6]="001";
$arbol["numero_hijos"][6]=0;
///////////////// FIN DE LA RECEPCION DE DOCUMENTO ////////////////
///////////////// ORDEN DE PAGO ///////////////////
 // 007
$arbol["sistema"][7]="CXP";
$arbol["nivel"][7]=0;
$arbol["nombre_logico"][7]="Ordenes de Pago";
$arbol["nombre_fisico"][7]="";
$arbol["id"][7]="007";
$arbol["padre"][7]="000";
$arbol["numero_hijos"][7]=3;

 //008
$arbol["sistema"][8]="CXP";
$arbol["nivel"][8]=1;
$arbol["nombre_logico"][8]="Registro de Orden de Pago";
$arbol["nombre_fisico"][8]="tepuy_cxp_p_solicitudpago.php";
$arbol["id"][8]="008";
$arbol["padre"][8]="007";
$arbol["numero_hijos"][8]=0;

 //009
$arbol["sistema"][9]="CXP";
$arbol["nivel"][9]=1;
$arbol["nombre_logico"][9]="Aprobación de Orden de Pago";
$arbol["nombre_fisico"][9]="tepuy_cxp_p_aprobacionsolicitudpago.php";
$arbol["id"][9]="009";
$arbol["padre"][9]="007";
$arbol["numero_hijos"][9]=0;

 // 010
$arbol["sistema"][10]="CXP";
$arbol["nivel"][10]=1;
$arbol["nombre_logico"][10]="Contabilización";
$arbol["nombre_fisico"][10]="";
$arbol["id"][10]="010";
$arbol["padre"][10]="007";
$arbol["numero_hijos"][10]=4;

 //011 
$arbol["sistema"][11]="CXP";
$arbol["nivel"][11]=2;
$arbol["nombre_logico"][11]="Contabilizar";
$arbol["nombre_fisico"][11]="tepuy_mis_p_contabiliza_cxp.php";
$arbol["id"][11]="011";
$arbol["padre"][11]="010";
$arbol["numero_hijos"][11]=0;

 // 012
$arbol["sistema"][12]="CXP";
$arbol["nivel"][12]=2;
$arbol["nombre_logico"][12]="Reverar Contabilización";
$arbol["nombre_fisico"][12]="tepuy_mis_p_reverso_cxp.php";
$arbol["id"][12]="012";
$arbol["padre"][12]="010";
$arbol["numero_hijos"][12]=0;

 // 013
$arbol["sistema"][13]="CXP";
$arbol["nivel"][13]=2;
$arbol["nombre_logico"][13]="Anular la Orden de Pago";
$arbol["nombre_fisico"][13]="tepuy_mis_p_anula_cxp.php";
$arbol["id"][13]="013";
$arbol["padre"][13]="010";
$arbol["numero_hijos"][13]=0;

 // 014
$arbol["sistema"][14]="CXP";
$arbol["nivel"][14]=2;
$arbol["nombre_logico"][14]="Reversar Anulación de Orden de Pago";
$arbol["nombre_fisico"][14]="tepuy_mis_p_reverso_anula_cxp.php";
$arbol["id"][14]="014";
$arbol["padre"][14]="010";
$arbol["numero_hijos"][14]=0;

//////////// FIN DE ORDEN DE PAGO //////////////

/////////////////// COMPROBANTES DE RETENCION ///////////////////////
 // 015
$arbol["sistema"][15]="CXP";
$arbol["nivel"][15]=0;
$arbol["nombre_logico"][15]="Comprobantes de Retención";
$arbol["nombre_fisico"][15]="";
$arbol["id"][15]="015";
$arbol["padre"][15]="000";
$arbol["numero_hijos"][15]=2;

 //016
$arbol["sistema"][16]="CXP";
$arbol["nivel"][16]=1;
$arbol["nombre_logico"][16]="Crear Comprobantes";
$arbol["nombre_fisico"][16]="tepuy_cxp_p_cmp_retencion.php";
$arbol["id"][16]="016";
$arbol["padre"][16]="015";
$arbol["numero_hijos"][16]=0;

 // 017
$arbol["sistema"][17]="CXP";
$arbol["nivel"][17]=1;
$arbol["nombre_logico"][17]="Editar Comprobantes";
$arbol["nombre_fisico"][17]="tepuy_cxp_p_modcmpret.php";
$arbol["id"][17]="017";
$arbol["padre"][17]="015";
$arbol["numero_hijos"][17]=0;

////////////////// FIN DE COMPROBANTES DE RETENCION ///////////////////

////////////////// REPORTES ///////////////////////
 // 018
$arbol["sistema"][18]="CXP";
$arbol["nivel"][18]=0;
$arbol["nombre_logico"][18]="Reportes";
$arbol["nombre_fisico"][18]="";
$arbol["id"][18]="018";
$arbol["padre"][18]="000";
$arbol["numero_hijos"][18]=8;

 // 019 
$arbol["sistema"][19]="CXP";
$arbol["nivel"][19]=1;
$arbol["nombre_logico"][19]="Recepciones de Documentos";
$arbol["nombre_fisico"][19]="tepuy_cxp_r_recepciones.php";
$arbol["id"][19]="019";
$arbol["padre"][19]="018";
$arbol["numero_hijos"][19]=0;

// 020
$arbol["sistema"][20]="CXP";
$arbol["nivel"][20]=1;
$arbol["nombre_logico"][20]="Ordenes de Pago";
$arbol["nombre_fisico"][20]="";
$arbol["id"][20]="020";
$arbol["padre"][20]="018";
$arbol["numero_hijos"][20]=4;

 // 021
$arbol["sistema"][21]="CXP";
$arbol["nivel"][21]=2;
$arbol["nombre_logico"][21]="Relacion Consecutiva de O. de Pago";
$arbol["nombre_fisico"][21]="tepuy_cxp_r_relacionsolicitudes.php";
$arbol["id"][21]="021";
$arbol["padre"][21]="020";
$arbol["numero_hijos"][21]=0;

 // 022
$arbol["sistema"][22]="CXP";
$arbol["nivel"][22]=2;
$arbol["nombre_logico"][22]="Relacion de Ordenes";
$arbol["nombre_fisico"][22]="tepuy_cxp_r_solicitudes.php";
$arbol["id"][22]="022";
$arbol["padre"][22]="020";
$arbol["numero_hijos"][22]=0;

 // 023 
$arbol["sistema"][23]="CXP";
$arbol["nivel"][23]=2;
$arbol["nombre_logico"][23]="Reportes - O. Pago Formato 1";
$arbol["nombre_fisico"][23]="tepuy_cxp_r_solicitudesf1.php";
$arbol["id"][23]="023";
$arbol["padre"][23]="020";
$arbol["numero_hijos"][23]=0;

 // 024
$arbol["sistema"][24]="CXP";
$arbol["nivel"][24]=2;
$arbol["nombre_logico"][24]="Reportes - O. Pago Formato 2";
$arbol["nombre_fisico"][24]="tepuy_cxp_r_solicitudesf2.php";
$arbol["id"][24]="024";
$arbol["padre"][24]="020";
$arbol["numero_hijos"][24]=0;

 // 025
$arbol["sistema"][25]="CXP";
$arbol["nivel"][25]=1;
$arbol["nombre_logico"][25]="Retenciones";
$arbol["nombre_fisico"][25]="";
$arbol["id"][25]="025";
$arbol["padre"][25]="018";
$arbol["numero_hijos"][25]=10;

 // 026 
$arbol["sistema"][26]="CXP";
$arbol["nivel"][26]=2;
$arbol["nombre_logico"][26]="Reportes - Retenciones IVA";
$arbol["nombre_fisico"][26]="tepuy_cxp_r_retencionesiva.php";
$arbol["id"][26]="026";
$arbol["padre"][26]="025";
$arbol["numero_hijos"][26]=0;

 // 027 
$arbol["sistema"][27]="CXP";
$arbol["nivel"][27]=2;
$arbol["nombre_logico"][27]="Declaracion Informativa de I.V.A.";
$arbol["nombre_fisico"][27]="tepuy_cxp_r_retencionesdeclaracioniva.php";
$arbol["id"][27]="027";
$arbol["padre"][27]="025";
$arbol["numero_hijos"][27]=0;

 // 028
$arbol["sistema"][28]="CXP";
$arbol["nivel"][28]=2;
$arbol["nombre_logico"][28]="Retenciones I.S.L.R.";
$arbol["nombre_fisico"][28]="tepuy_cxp_r_retencionesislr.php";
$arbol["id"][28]="028";
$arbol["padre"][28]="025";
$arbol["numero_hijos"][28]=0;

 // 029
$arbol["sistema"][29]="CXP";
$arbol["nivel"][29]=2;
$arbol["nombre_logico"][29]="Declaracion Informativa de I.S.L.R.";
$arbol["nombre_fisico"][29]="tepuy_cxp_r_retencionesdeclaracionislr.php";
$arbol["id"][29]="029";
$arbol["padre"][29]="025";
$arbol["numero_hijos"][29]=0;

 // 030
$arbol["sistema"][30]="CXP";
$arbol["nivel"][30]=2;
$arbol["nombre_logico"][30]="Formato Unificado de Retenciones";
$arbol["nombre_fisico"][30]="tepuy_cxp_r_retencionesunificadas.php";
$arbol["id"][30]="030";
$arbol["padre"][30]="025";
$arbol["numero_hijos"][30]=0;

 // 031
$arbol["sistema"][31]="CXP";
$arbol["nivel"][31]=2;
$arbol["nombre_logico"][31]="Retenciones Municipales";
$arbol["nombre_fisico"][31]="tepuy_cxp_r_retencionesmunicipales.php";
$arbol["id"][31]="031";
$arbol["padre"][31]="025";
$arbol["numero_hijos"][31]=0;

 // 032 
$arbol["sistema"][32]="CXP";
$arbol["nivel"][32]=2;
$arbol["nombre_logico"][32]="Reportes - Retenciones Aporte Social";
$arbol["nombre_fisico"][32]="tepuy_cxp_r_retencionesaporte.php";
$arbol["id"][32]="032";
$arbol["padre"][32]="025";
$arbol["numero_hijos"][32]=0;

 // 033
$arbol["sistema"][33]="CXP";
$arbol["nivel"][33]=2;
$arbol["nombre_logico"][33]="Reportes - Retenciones Timbre Fiscal";
$arbol["nombre_fisico"][33]="tepuy_cxp_r_retencionestimbrefiscal.php";
$arbol["id"][33]="033";
$arbol["padre"][33]="025";
$arbol["numero_hijos"][33]=0;

 // 034
$arbol["sistema"][34]="CXP";
$arbol["nivel"][34]=2;
$arbol["nombre_logico"][34]="Retenciones General";
$arbol["nombre_fisico"][34]="tepuy_cxp_r_retencionesgeneral.php";
$arbol["id"][34]="034";
$arbol["padre"][34]="025";
$arbol["numero_hijos"][34]=0;

 // 035
$arbol["sistema"][35]="CXP";
$arbol["nivel"][35]=2;
$arbol["nombre_logico"][35]="Retenciones Especifico";
$arbol["nombre_fisico"][35]="tepuy_cxp_r_retencionesespecifico.php";
$arbol["id"][35]="035";
$arbol["padre"][35]="025";
$arbol["numero_hijos"][35]=0;

 // 036
$arbol["sistema"][36]="CXP";
$arbol["nivel"][36]=1;
$arbol["nombre_logico"][36]="Resumen de Ordenes de Pago";
$arbol["nombre_fisico"][36]="tepuy_cxp_r_cxpresumido.php";
$arbol["id"][36]="036";
$arbol["padre"][36]="018";
$arbol["numero_hijos"][36]=0;

 // 037
$arbol["sistema"][37]="CXP";
$arbol["nivel"][37]=1;
$arbol["nombre_logico"][37]="AR-C";
$arbol["nombre_fisico"][37]="tepuy_cxp_r_arc.php";
$arbol["id"][37]="037";
$arbol["padre"][37]="018";
$arbol["numero_hijos"][37]=0;

 // 038
$arbol["sistema"][38]="CXP";
$arbol["nivel"][38]=1;
$arbol["nombre_logico"][38]="Libro de Compras";
$arbol["nombre_fisico"][38]="";
$arbol["id"][38]="038";
$arbol["padre"][38]="018";
$arbol["numero_hijos"][38]=2;


 // 039
$arbol["sistema"][39]="CXP";
$arbol["nivel"][39]=2;
$arbol["nombre_logico"][39]="Libro Compra General";
$arbol["nombre_fisico"][39]="tepuy_cxp_r_librocompra.php";
$arbol["id"][39]="039";
$arbol["padre"][39]="038";
$arbol["numero_hijos"][39]=0;

 // 040
$arbol["sistema"][40]="CXP";
$arbol["nivel"][40]=2;
$arbol["nombre_logico"][40]="Libro Compra Resumido";
$arbol["nombre_fisico"][40]="tepuy_cxp_r_librocompra_res.php";
$arbol["id"][40]="040";
$arbol["padre"][40]="038";
$arbol["numero_hijos"][40]=0;

// 041
$arbol["sistema"][41]="CXP";
$arbol["nivel"][41]=1;
$arbol["nombre_logico"][41]="Libro de I.S.L.R. /Timbre Fiscal/ Imp. Municipal";
$arbol["nombre_fisico"][41]="tepuy_cxp_r_libro_islr_timbrefiscal.php";
$arbol["id"][41]="041";
$arbol["padre"][41]="018";
$arbol["numero_hijos"][41]=0;

 // 042
$arbol["sistema"][42]="CXP";
$arbol["nivel"][42]=1;
$arbol["nombre_logico"][42]="Listado de Tipos de Documentos";
$arbol["nombre_fisico"][42]="tepuy_cxp_r_relaciondnc.php";
$arbol["id"][42]="042";
$arbol["padre"][42]="018";
$arbol["numero_hijos"][42]=0;

//////////////// FIN DE REPORTES /////////////////

////////////// CONFIGURACION /////////////////////
 // 043
$arbol["sistema"][43]="CXP";
$arbol["nivel"][43]=0;
$arbol["nombre_logico"][43]="Configuración";
$arbol["nombre_fisico"][43]="";
$arbol["id"][43]="043";
$arbol["padre"][43]="000";
$arbol["numero_hijos"][43]=4;

 // 044
$arbol["sistema"][44]="CXP";
$arbol["nivel"][44]=1;
$arbol["nombre_logico"][44]="Otros Créditos";
$arbol["nombre_fisico"][44]="tepuy_cxp_d_otroscreditos.php";
$arbol["id"][44]="044";
$arbol["padre"][44]="043";
$arbol["numero_hijos"][44]=0;

 // 045
$arbol["sistema"][45]="CXP";
$arbol["nivel"][45]=1;
$arbol["nombre_logico"][45]="Retenciones/Deducciones";
$arbol["nombre_fisico"][45]="tepuy_cxp_d_deducciones.php";
$arbol["id"][45]="045";
$arbol["padre"][45]="043";
$arbol["numero_hijos"][45]=0;

 // 046
$arbol["sistema"][46]="CXP";
$arbol["nivel"][46]=1;
$arbol["nombre_logico"][46]="Documentos";
$arbol["nombre_fisico"][46]="tepuy_cxp_d_documentos.php";
$arbol["id"][46]="046";
$arbol["padre"][46]="043";
$arbol["numero_hijos"][46]=0;

 // 047
$arbol["sistema"][47]="CXP";
$arbol["nivel"][47]=1;
$arbol["nombre_logico"][47]="Establecer Contadores en las Retenciones";
$arbol["nombre_fisico"][47]="tepuy_cxp_d_inicio_de_contadores.php";
$arbol["id"][47]="047";
$arbol["padre"][47]="043";
$arbol["numero_hijos"][47]=0;


//////////// FIN DE CONFIGURACIN /////////////////

/* // 
$arbol["sistema"][]="CXP";
$arbol["nivel"][]=1;
$arbol["nombre_logico"][]="Reportes - Relacion de Facturas";
$arbol["nombre_fisico"][]="tepuy_cxp_r_relacionfacturas.php";
$arbol["id"][]=;
$arbol["padre"][]="004";
$arbol["numero_hijos"][]=0;

 // 
$arbol["sistema"][]="CXP";
$arbol["nivel"][]=1;
$arbol["nombre_logico"][]="Reportes - Relación de Saldos por Solicitud";
$arbol["nombre_fisico"][]="tepuy_cxp_r_relacionsaldos.php";
$arbol["id"][]=;
$arbol["padre"][]="004";
$arbol["numero_hijos"][]=0;

 // 
$arbol["sistema"][]="CXP";
$arbol["nivel"][]=1;
$arbol["nombre_logico"][]="Reportes - Relación de Notas de Débito y Crédito";
$arbol["nombre_fisico"][]="tepuy_cxp_r_relacionndnc.php";
$arbol["id"][]=;
$arbol["padre"][]="004";
$arbol["numero_hijos"][]=0;*/

//$gi_total=;
?>
