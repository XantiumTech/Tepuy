<?php
//$i=0;
//$i++;
//$gi_total = $i;
//////////////// PROCESOS /////////////////////
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 0;
$arbol["nombre_logico"][$i] = "Procesos";
$arbol["nombre_fisico"][$i] = "";
$arbol["id"][$i]            = "001";//1
$arbol["padre"][$i]         = "000";
$arbol["numero_hijos"][$i]  = 11;

//--- DEFINICION ---//
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Definicion";
$arbol["nombre_fisico"][$i] = "";
$arbol["id"][$i]            = "002";//2
$arbol["padre"][$i]         = "001";
$arbol["numero_hijos"][$i]  = 8;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Bancos";
$arbol["nombre_fisico"][$i] = "tepuy_scb_d_banco.php";
$arbol["id"][$i]            = "003";//3
$arbol["padre"][$i]         = "002";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Agencias";
$arbol["nombre_fisico"][$i] = "tepuy_scb_d_agencia.php";
$arbol["id"][$i]            = "004";//4
$arbol["padre"][$i]         = "002";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Tipos de Cuentas";
$arbol["nombre_fisico"][$i] = "tepuy_scb_d_tipocta.php";
$arbol["id"][$i]            = "005";//5
$arbol["padre"][$i]         = "002";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Cuentas Bancarias";
$arbol["nombre_fisico"][$i] = "tepuy_scb_d_ctabanco.php";
$arbol["id"][$i]            = "006";//6
$arbol["padre"][$i]         = "002";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Chequeras";
$arbol["nombre_fisico"][$i] = "tepuy_scb_d_chequera.php";
$arbol["id"][$i]            = "007";//7
$arbol["padre"][$i]         = "002";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Tipos de Colocación";
$arbol["nombre_fisico"][$i] = "tepuy_scb_d_tipocolocacion.php";
$arbol["id"][$i]            = "008";//8
$arbol["padre"][$i]         = "002";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Colocación";
$arbol["nombre_fisico"][$i] = "tepuy_scb_d_colocacion.php";
$arbol["id"][$i]            = "009";//9
$arbol["padre"][$i]         = "002";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Conceptos de Movimientos";
$arbol["nombre_fisico"][$i] = "tepuy_scb_d_conceptos.php";
$arbol["id"][$i]            = "010";//10
$arbol["padre"][$i]         = "002";
$arbol["numero_hijos"][$i]  = 0;

//-- Fin de Definicion --//

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Movimientos Bancarios";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_movbanco.php";
$arbol["id"][$i]            = "011";//11
$arbol["padre"][$i]         = "001";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Cancelaciones";
$arbol["nombre_fisico"][$i] = "";
$arbol["id"][$i]            = "012";//12
$arbol["padre"][$i]         = "001";
$arbol["numero_hijos"][$i]  = 4;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Programar Pagos";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_progpago.php";
$arbol["id"][$i]            = "013";//13
$arbol["padre"][$i]         = "012";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Desprogramación de Pagos";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_desprogpago.php";
$arbol["id"][$i]            = "014";//14
$arbol["padre"][$i]         = "012";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Emisión de Cheques";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_emision_chq.php";
$arbol["id"][$i]            = "015";//15
$arbol["padre"][$i]         = "012";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Pago con Transferencias";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_pago_transferencia.php";
$arbol["id"][$i]            = "016";//15
$arbol["padre"][$i]         = "012";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Eliminación de Cheques o Pago con Transferencias No Contabilizados";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_elimin_chq.php";
$arbol["id"][$i]            = "017";//16
$arbol["padre"][$i]         = "012";
$arbol["numero_hijos"][$i]  = 0;

/*
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Pago Directo";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_pago_directo.php";
$arbol["id"][$i]            = "008";//8
$arbol["padre"][$i]         = "003";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Orden de Pago Directa";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_orden_pago_directo.php";
$arbol["id"][$i]            = "009";//9
$arbol["padre"][$i]         = "003";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Orden de Pago Directa Con Compromiso Previo";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_opd_causapaga.php";
$arbol["id"][$i]            = "010";//10
$arbol["padre"][$i]         = "003";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Carta Orden";
$arbol["nombre_fisico"][$i] = "";
$arbol["id"][$i]            = "011";//3
$arbol["padre"][$i]         = "003";
$arbol["numero_hijos"][$i]  = 2;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 3;
$arbol["nombre_logico"][$i] = "Carta Orden Única Nota de Débito";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_carta_orden.php";
$arbol["id"][$i]            = "012";//11
$arbol["padre"][$i]         = "011";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 3;
$arbol["nombre_logico"][$i] = "Carta Orden Múltiples Notas de Débito";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_carta_orden_mnd.php";
$arbol["id"][$i]            = "013";//11
$arbol["padre"][$i]         = "011";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Eliminación de Carta Orden no Contabilizada";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_elimin_carta_orden.php";
$arbol["id"][$i]            = "014";//12
$arbol["padre"][$i]         = "003";
$arbol["numero_hijos"][$i]  = 0;
*/
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Conciliación Bancaria";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_conciliacion.php";
$arbol["id"][$i]            = "018";//17
$arbol["padre"][$i]         = "001";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Colocaciones";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_movcol.php";
$arbol["id"][$i]            = "019"; //18
$arbol["padre"][$i]         = "001";
$arbol["numero_hijos"][$i]  = 0;
/*
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Retenciones Municipales";
$arbol["nombre_fisico"][$i] = "";
$arbol["id"][$i]            = "017";//15
$arbol["padre"][$i]         = "001";
$arbol["numero_hijos"][$i]  = 3;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Crear Comprobante";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_cmp_ret_mcp.php";
$arbol["id"][$i]            = "018";//16
$arbol["padre"][$i]         = "017";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Modificar Comprobante";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_cmp_ret_mod.php";
$arbol["id"][$i]            = "019";//17
$arbol["padre"][$i]         = "017";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Otros Comprobantes";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_cmp_ret_mun_otros.php";
$arbol["id"][$i]            = "020";//18
$arbol["padre"][$i]         = "017";
$arbol["numero_hijos"][$i]  = 0;
*/
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Transferencias Bancarias";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_transferencias.php";
$arbol["id"][$i]            = "020";//19
$arbol["padre"][$i]         = "001";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Entrega de Cheques";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_entregach.php";
$arbol["id"][$i]            = "021";//20
$arbol["padre"][$i]         = "001";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Reverso de Entrega de Cheques";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_reverso_entregach.php";
$arbol["id"][$i]            = "022";//21
$arbol["padre"][$i]         = "001";
$arbol["numero_hijos"][$i]  = 0;

//--- Contabilizar Movimientos Bancarios --//
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Contabilizar Movimientos Bancarios";
$arbol["nombre_fisico"][$i] = "";
$arbol["id"][$i]            = "023";//22
$arbol["padre"][$i]         = "001";
$arbol["numero_hijos"][$i]  = 4;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Contabilizar Movimientos";
$arbol["nombre_fisico"][$i] = "tepuy_mis_p_contabiliza_scb.php";
$arbol["id"][$i]            = "024";//23
$arbol["padre"][$i]         = "023";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Reversar Contabilización de Movimientos";
$arbol["nombre_fisico"][$i] = "tepuy_mis_p_reverso_scb.php";
$arbol["id"][$i]            = "025";//24
$arbol["padre"][$i]         = "023";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Anular Movimiento Bancario";
$arbol["nombre_fisico"][$i] = "tepuy_mis_p_anula_scb.php";
$arbol["id"][$i]            = "026";//25
$arbol["padre"][$i]         = "023";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Reversar Anulación de Movimiento Bancario";
$arbol["nombre_fisico"][$i] = "tepuy_mis_p_reverso_anula_scb.php";
$arbol["id"][$i]            = "027";//26
$arbol["padre"][$i]         = "023";
$arbol["numero_hijos"][$i]  = 0;
//-----------------------------------------//

//--- Contabilizar Colocaciones --//
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Contabilizar Colocaciones";
$arbol["nombre_fisico"][$i] = "";
$arbol["id"][$i]            = "028";//27
$arbol["padre"][$i]         = "001";
$arbol["numero_hijos"][$i]  = 2;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Contabilizar";
$arbol["nombre_fisico"][$i] = "tepuy_mis_p_contabiliza_scbcol.php";
$arbol["id"][$i]            = "029";//28
$arbol["padre"][$i]         = "028";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Reversar Contabilización";
$arbol["nombre_fisico"][$i] = "tepuy_mis_p_reverso_scbcol.php";
$arbol["id"][$i]            = "030";//29
$arbol["padre"][$i]         = "028";
$arbol["numero_hijos"][$i]  = 0;

//-----------------------------------------//

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Registrar Beneficiario";
$arbol["nombre_fisico"][$i] = "tepuy_rpc_d_beneficiario.php";
$arbol["id"][$i]            = "031";//30
$arbol["padre"][$i]         = "001";
$arbol["numero_hijos"][$i]  = 0;

/////// FIN DE PROCESOS /////////////////////
/*
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Eliminación Anulados Monto 0";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_elimin_anulado.php";
$arbol["id"][$i]            = "024";//22
$arbol["padre"][$i]         = "001";
$arbol["numero_hijos"][$i]  = 0;
*/

//// INICIO DE MENU DE REPORTES /////
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 0;
$arbol["nombre_logico"][$i] = "Reportes";
$arbol["nombre_fisico"][$i] = "";
$arbol["id"][$i]            = "032";//31
$arbol["padre"][$i]         = "000";
$arbol["numero_hijos"][$i]  = 10;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Disponibilidad Financiera";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_disponibilidad.php";
$arbol["id"][$i]            = "033";//32
$arbol["padre"][$i]         = "032";
$arbol["numero_hijos"][$i]  = 0;

///-- Listado de Documentos ----//
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Listado de Documentos";
$arbol["nombre_fisico"][$i] = "";
$arbol["id"][$i]            = "034";//33
$arbol["padre"][$i]         = "032";
$arbol["numero_hijos"][$i]  = 3;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Documentos";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_documentos.php";
$arbol["id"][$i]            = "035";//34
$arbol["padre"][$i]         = "034";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Conciliados";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_list_doc_conciliados.php";
$arbol["id"][$i]            = "036";//34
$arbol["padre"][$i]         = "034";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Documentos en Tránsito";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_list_doc_transito.php";
$arbol["id"][$i]            = "037";//35
$arbol["padre"][$i]         = "034";
$arbol["numero_hijos"][$i]  = 0;
//--------- Documentos ---//
/*
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Listado de Ordenes de Pago Directa";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_ordenpago.php";
$arbol["id"][$i]            = "031";//29
$arbol["padre"][$i]         = "025";
$arbol["numero_hijos"][$i]  = 0;*/

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Conciliación Bancaria";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_conciliacion.php";
$arbol["id"][$i]            = "038";//36
$arbol["padre"][$i]         = "032";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Pagos";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_pagos.php";
$arbol["id"][$i]            = "039";//37
$arbol["padre"][$i]         = "032";
$arbol["numero_hijos"][$i]  = 0;

/*
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Registros Contables";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_reg_contables.php";
$arbol["id"][$i]            = "033";//31
$arbol["padre"][$i]         = "025";
$arbol["numero_hijos"][$i]  = 0;*/

//--- Estado de Cuentas ---//
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Estado de Cuenta";
$arbol["nombre_fisico"][$i] = "";
$arbol["id"][$i]            = "040";//38
$arbol["padre"][$i]         = "032";
$arbol["numero_hijos"][$i]  = 2;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Formato 1";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_estado_cta.php";
$arbol["id"][$i]            = "041";//39
$arbol["padre"][$i]         = "040";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Resumido";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_estado_ctares.php";
$arbol["id"][$i]            = "042";//40
$arbol["padre"][$i]         = "040";
$arbol["numero_hijos"][$i]  = 0;

//-- Fin Estado de Cuentas

//-- Otros Listados ----///
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Otros Listados";
$arbol["nombre_fisico"][$i] = "";
$arbol["id"][$i]            = "043";//41
$arbol["padre"][$i]         = "032";
$arbol["numero_hijos"][$i]  = 5;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Listado de Chequeras";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_listadochequeras.php";
$arbol["id"][$i]            = "044";//42
$arbol["padre"][$i]         = "043";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Mayor Presupuestario";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_mayor_presupuestario.php";
$arbol["id"][$i]            = "045"; //43
$arbol["padre"][$i]         = "043";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Relación Selectiva de Cheques";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_relacion_sel_chq.php";
$arbol["id"][$i]            = "046";//44
$arbol["padre"][$i]         = "043";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Relación Selectiva de Documentos (No Incluye Cheques)";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_relacion_sel_docs.php";
$arbol["id"][$i]            = "047";//45
$arbol["padre"][$i]         = "043";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Movimientos Presupuestarios Por Banco";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_spg_x_banco.php";
$arbol["id"][$i]            = "048";//46
$arbol["padre"][$i]         = "043";
$arbol["numero_hijos"][$i]  = 0;
//--------- Fin de Otros Listados ----//

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Cheques en Custodia";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_chq_custodia_entregados.php";
$arbol["id"][$i]            = "049";//47
$arbol["padre"][$i]         = "032";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Libro Auxiliar de Banco";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_libro_auxiliar_banco.php";
$arbol["id"][$i]            = "050"; //48
$arbol["padre"][$i]         = "032";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Libro Banco";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_libro_banco.php";
$arbol["id"][$i]            = "051";//49
$arbol["padre"][$i]         = "032";
$arbol["numero_hijos"][$i]  = 0;
/*
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Comprobante Retención Municipal";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_comp_ret_mun.php";
$arbol["id"][$i]            = "045";//43
$arbol["padre"][$i]         = "025";
$arbol["numero_hijos"][$i]  = 0;*/

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Listado Cheques Caducados";
$arbol["nombre_fisico"][$i] = "tepuy_scb_r_chq_caducados.php";
$arbol["id"][$i]            = "052"; //50
$arbol["padre"][$i]         = "032";
$arbol["numero_hijos"][$i]  = 0;
//-------------- Fin de Reportes -------------///

//--------- Inicio de Configuracion -----------///
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 0;
$arbol["nombre_logico"][$i] = "Configuración";
$arbol["nombre_fisico"][$i] = "";
$arbol["id"][$i]            = "053";//51
$arbol["padre"][$i]         = "000";
$arbol["numero_hijos"][$i]  = 4;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Medidas de Cheque Voucher";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_conf_voucher.php";
$arbol["id"][$i]            = "054";//52
$arbol["padre"][$i]         = "053";
$arbol["numero_hijos"][$i]  = 0;

/*
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Formato N° Orden de Pago";
$arbol["nombre_fisico"][$i] = "tepuy_scb_config.php";
$arbol["id"][$i]            = "047";//45
$arbol["padre"][$i]         = "046";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Selección Formato Carta Orden";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_conf_select_cartaorden.php";
$arbol["id"][$i]            = "049";//47
$arbol["padre"][$i]         = "046";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Configuración Formato Carta Orden";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_conf_cartaorden.php";
$arbol["id"][$i]            = "050";//48
$arbol["padre"][$i]         = "046";
$arbol["numero_hijos"][$i]  = 0;*/

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 0;
$arbol["nombre_logico"][$i] = "Mantenimiento";
$arbol["nombre_fisico"][$i] = "";
$arbol["id"][$i]            = "055";//53
$arbol["padre"][$i]         = "000";
$arbol["numero_hijos"][$i]  = 1;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Movimientos Descuadrados";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_mant_descuadrados.php";
$arbol["id"][$i]            = "056";//54
$arbol["padre"][$i]         = "055";
$arbol["numero_hijos"][$i]  = 0;
/*
$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Error de Banco";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_concilerror.php";
$arbol["id"][$i]            = "053";
$arbol["padre"][$i]         = "001";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 2;
$arbol["nombre_logico"][$i] = "Activar Cheques Seleccionados";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_activar_chq.php";
$arbol["id"][$i]            = "054";//12
$arbol["padre"][$i]         = "003";
$arbol["numero_hijos"][$i]  = 0;

$i++;
$arbol["sistema"][$i]       = "SCB";
$arbol["nivel"][$i]         = 1;
$arbol["nombre_logico"][$i] = "Procesar Documentos no Contabilizables";
$arbol["nombre_fisico"][$i] = "tepuy_scb_p_procesar_no_contabilizables.php";
$arbol["id"][$i]            = "055";
$arbol["padre"][$i]         = "001";
$arbol["numero_hijos"][$i]  = 0;
*/

$gi_total = $i;
?>
