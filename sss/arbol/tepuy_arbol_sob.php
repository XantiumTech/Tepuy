<?php
$gi_total="33";
////////////////// DEFINICIONES ///////////////////////
$arbol["sistema"][1]="SOB";
$arbol["nivel"][1]=0;
$arbol["nombre_logico"][1]="Definiciones";
$arbol["nombre_fisico"][1]="";
$arbol["id"][1]="001";
$arbol["padre"][1]="000";
$arbol["numero_hijos"][1]=10;

$arbol["sistema"][2]="SOB";
$arbol["nivel"][2]=1;
$arbol["nombre_logico"][2]="Registro de Obras";
$arbol["nombre_fisico"][2]="tepuy_sob_d_obra.php";
$arbol["id"][2]="002";
$arbol["padre"][2]="001";
$arbol["numero_hijos"][2]=0;

$arbol["sistema"][3]="SOB";
$arbol["nivel"][3]=1;
$arbol["nombre_logico"][3]="Tipos de Contrato";
$arbol["nombre_fisico"][3]="tepuy_sob_d_tipocontrato.php";
$arbol["id"][3]="003";
$arbol["padre"][3]="001";
$arbol["numero_hijos"][3]=0;

$arbol["sistema"][4]="SOB";
$arbol["nivel"][4]=1;
$arbol["nombre_logico"][4]="Tipos de Obras";
$arbol["nombre_fisico"][4]="tepuy_sob_d_tipoobra.php";
$arbol["id"][4]="004";
$arbol["padre"][4]="001";
$arbol["numero_hijos"][4]=0;

$arbol["sistema"][5]="SOB";
$arbol["nivel"][5]=1;
$arbol["nombre_logico"][5]="Documentos";
$arbol["nombre_fisico"][5]="tepuy_sob_d_documentos.php";
$arbol["id"][5]="005";
$arbol["padre"][5]="001";
$arbol["numero_hijos"][5]=0;

$arbol["sistema"][6]="SOB";
$arbol["nivel"][6]=1;
$arbol["nombre_logico"][6]="Registrar Contratista";
$arbol["nombre_fisico"][6]="";
$arbol["id"][6]="006";
$arbol["padre"][6]="001";
$arbol["numero_hijos"][6]=2;

$arbol["sistema"][7]="SOB";
$arbol["nivel"][7]=2;
$arbol["nombre_logico"][7]="Tipo de Empresa";
$arbol["nombre_fisico"][7]="tepuy_rpc_d_tipoempresa.php";
$arbol["id"][7]="007";
$arbol["padre"][7]="006";
$arbol["numero_hijos"][7]=0;

$arbol["sistema"][8]="SOB";
$arbol["nivel"][8]=2;
$arbol["nombre_logico"][8]="Ficha";
$arbol["nombre_fisico"][8]="tepuy_rpc_d_proveedor.php";
$arbol["id"][8]="008";
$arbol["padre"][8]="006";
$arbol["numero_hijos"][8]=0;

$arbol["sistema"][9]="SOB";
$arbol["nivel"][9]=1;
$arbol["nombre_logico"][9]="Registrar Comunidades";
$arbol["nombre_fisico"][9]="tepuy_sob_d_comunidad.php";
$arbol["id"][9]="010";
$arbol["padre"][9]="001";
$arbol["numero_hijos"][9]=0;

////////////////////// FIN DE DEFINICIONES //////////////////////////

//////////////////// INICIO DE PROCESOS ////////////////////////////
$arbol["sistema"][10]="SOB";
$arbol["nivel"][10]=0;
$arbol["nombre_logico"][10]="Procesos";
$arbol["nombre_fisico"][10]="";
$arbol["id"][10]="010";
$arbol["padre"][10]="000";
$arbol["numero_hijos"][10]=10;

$arbol["sistema"][11]="SOB";
$arbol["nivel"][11]=1;
$arbol["nombre_logico"][11]="Obras";
$arbol["nombre_fisico"][11]="tepuy_sob_p_obra.php";
$arbol["id"][11]="011";
$arbol["padre"][11]="010";
$arbol["numero_hijos"][11]=0;

$arbol["sistema"][12]="SOB";
$arbol["nivel"][12]=1;
$arbol["nombre_logico"][12]="Asignaciones/Punto de Cuenta";
$arbol["nombre_fisico"][12]="tepuy_sob_d_asignacion.php";
$arbol["id"][12]="012";
$arbol["padre"][12]="010";
$arbol["numero_hijos"][12]=0;

$arbol["sistema"][13]="SOB";
$arbol["nivel"][13]=1;
$arbol["nombre_logico"][13]="Contratos";
$arbol["nombre_fisico"][13]="tepuy_sob_d_contrato.php";
$arbol["id"][13]="013";
$arbol["padre"][13]="010";
$arbol["numero_hijos"][13]=0;

$arbol["sistema"][14]="SOB";
$arbol["nivel"][14]=1;
$arbol["nombre_logico"][14]="Anticipos";
$arbol["nombre_fisico"][14]="tepuy_sob_d_anticipo.php";
$arbol["id"][14]="014";
$arbol["padre"][14]="010";
$arbol["numero_hijos"][14]=0;

$arbol["sistema"][15]="SOB";
$arbol["nivel"][15]=1;
$arbol["nombre_logico"][15]="Valuaciones";
$arbol["nombre_fisico"][15]="tepuy_sob_d_valuacion.php";
$arbol["id"][15]="015";
$arbol["padre"][15]="010";
$arbol["numero_hijos"][15]=0;

$arbol["sistema"][16]="SOB";
$arbol["nivel"][16]=1;
$arbol["nombre_logico"][16]="Variaciones del Contrato";
$arbol["nombre_fisico"][16]="tepuy_sob_d_variacion.php";
$arbol["id"][16]="016";
$arbol["padre"][16]="010";
$arbol["numero_hijos"][16]=0;

$arbol["sistema"][17]="SOB";
$arbol["nivel"][17]=1;
$arbol["nombre_logico"][17]="Actas";
$arbol["nombre_fisico"][17]="tepuy_sob_d_acta.php";
$arbol["id"][17]="017";
$arbol["padre"][17]="010";
$arbol["numero_hijos"][17]=0;

$arbol["sistema"][18]="SOB";
$arbol["nivel"][18]=1;
$arbol["nombre_logico"][18]="Reverso de Envio de Recepcion de Documentos";
$arbol["nombre_fisico"][18]="tepuy_sob_p_revanticipo_rd.php";
$arbol["id"][18]="018";
$arbol["padre"][18]="010";
$arbol["numero_hijos"][18]=0;

$arbol["sistema"][19]="SOB";
$arbol["nivel"][19]=1;
$arbol["nombre_logico"][19]="Contabilizar Asignaciones";
$arbol["nombre_fisico"][19]="";
$arbol["id"][19]="019";
$arbol["padre"][19]="010";
$arbol["numero_hijos"][19]=4;

$arbol["sistema"][20]="SOB";
$arbol["nivel"][20]=2;
$arbol["nombre_logico"][20]="Contabilizar Asignaciones";
$arbol["nombre_fisico"][20]="tepuy_mis_p_contabiliza_asignacion_sob.php";
$arbol["id"][20]="020";
$arbol["padre"][20]="019";
$arbol["numero_hijos"][20]=0;

$arbol["sistema"][21]="SOB";
$arbol["nivel"][21]=2;
$arbol["nombre_logico"][21]="Reverso de Contabilizacion";
$arbol["nombre_fisico"][21]="tepuy_mis_p_reverso_asignacion_sob.php";
$arbol["id"][21]="021";
$arbol["padre"][21]="019";
$arbol["numero_hijos"][21]=0;

$arbol["sistema"][22]="SOB";
$arbol["nivel"][22]=2;
$arbol["nombre_logico"][22]="Anular Asignacion";
$arbol["nombre_fisico"][22]="tepuy_mis_p_anula_asignacion_sob.php";
$arbol["id"][22]="022";
$arbol["padre"][22]="019";
$arbol["numero_hijos"][22]=0;

$arbol["sistema"][23]="SOB";
$arbol["nivel"][23]=2;
$arbol["nombre_logico"][23]="Reversar Anulacion";
$arbol["nombre_fisico"][23]="tepuy_mis_p_revanula_asignacion_sob.php";
$arbol["id"][23]="023";
$arbol["padre"][23]="019";
$arbol["numero_hijos"][23]=0;

$arbol["sistema"][24]="SOB";
$arbol["nivel"][24]=1;
$arbol["nombre_logico"][24]="Contabilizar Contratos";
$arbol["nombre_fisico"][24]="";
$arbol["id"][24]="024";
$arbol["padre"][24]="010";
$arbol["numero_hijos"][24]=4;

$arbol["sistema"][25]="SOB";
$arbol["nivel"][25]=2;
$arbol["nombre_logico"][25]="Contabilizar Contratos";
$arbol["nombre_fisico"][25]="tepuy_mis_p_contabiliza_contrato_sob.php";
$arbol["id"][25]="025";
$arbol["padre"][25]="024";
$arbol["numero_hijos"][25]=0;

$arbol["sistema"][26]="SOB";
$arbol["nivel"][26]=2;
$arbol["nombre_logico"][26]="Reverso de Contabilizacion";
$arbol["nombre_fisico"][26]="tepuy_mis_p_reverso_contrato_sob.php";
$arbol["id"][26]="026";
$arbol["padre"][26]="024";
$arbol["numero_hijos"][26]=0;

$arbol["sistema"][27]="SOB";
$arbol["nivel"][27]=2;
$arbol["nombre_logico"][27]="Anular Contrato";
$arbol["nombre_fisico"][27]="tepuy_mis_p_anula_contrato_sob.php";
$arbol["id"][27]="027";
$arbol["padre"][27]="024";
$arbol["numero_hijos"][27]=0;

$arbol["sistema"][28]="SOB";
$arbol["nivel"][28]=2;
$arbol["nombre_logico"][28]="Reversar Anulacion";
$arbol["nombre_fisico"][28]="tepuy_mis_p_revanula_contrato_sob.php";
$arbol["id"][28]="028";
$arbol["padre"][28]="024";
$arbol["numero_hijos"][28]=0;
///////////////////////// FIN DE PROCESOS //////////////////////////

///////////////////////INICIO DE REPORTES //////////////////////////
$arbol["sistema"][29]="SOB";
$arbol["nivel"][29]=0;
$arbol["nombre_logico"][29]="Reportes";
$arbol["nombre_fisico"][29]="";
$arbol["id"][29]="029";
$arbol["padre"][29]="000";
$arbol["numero_hijos"][29]=5;

$arbol["sistema"][30]="SOB";
$arbol["nivel"][30]=1;
$arbol["nombre_logico"][30]="Obras";
$arbol["nombre_fisico"][30]="tepuy_sob_r_reporteobra.php";
$arbol["id"][30]="030";
$arbol["padre"][30]="029";
$arbol["numero_hijos"][30]=0;

$arbol["sistema"][31]="SOB";
$arbol["nivel"][31]=1;
$arbol["nombre_logico"][31]="Asignaciones por Obras";
$arbol["nombre_fisico"][31]="tepuy_sob_r_reporteasignacionesobra.php";
$arbol["id"][31]="031";
$arbol["padre"][31]="029";
$arbol["numero_hijos"][31]=0;

$arbol["sistema"][32]="SOB";
$arbol["nivel"][32]=1;
$arbol["nombre_logico"][32]="Seguimineto de Obras";
$arbol["nombre_fisico"][32]="tepuy_sob_r_reporteseguimientoobra.php";
$arbol["id"][32]="032";
$arbol["padre"][32]="029";
$arbol["numero_hijos"][32]=0;

$arbol["sistema"][33]="SOB";
$arbol["nivel"][33]=1;
$arbol["nombre_logico"][33]="Documentos";
$arbol["nombre_fisico"][33]="tepuy_sob_r_documentos.php";
$arbol["id"][33]="033";
$arbol["padre"][33]="029";
$arbol["numero_hijos"][33]=0;
////////////////////// FIN DE REPORTES /////////////////////////
?>
