<?php
$gi_total=15;

$arbol["sistema"][1]="SEP";
$arbol["nivel"][1]=0;
$arbol["nombre_logico"][1]="Procesos";
$arbol["nombre_fisico"][1]="";
$arbol["id"][1]="001";
$arbol["padre"][1]="000";
$arbol["numero_hijos"][1]=2;

$arbol["sistema"][2]="SEP";
$arbol["nivel"][2]=1;
$arbol["nombre_logico"][2]="Registro de Solicitud de Ejecución Presupuestaria";
$arbol["nombre_fisico"][2]="tepuy_sep_p_solicitud.php";
$arbol["id"][2]="002";
$arbol["padre"][2]="001";
$arbol["numero_hijos"][2]=0;

$arbol["sistema"][3]="SEP";
$arbol["nivel"][3]=1;
$arbol["nombre_logico"][3]="Aprobación de Solicitud de Ejecución Presupuestaria";
$arbol["nombre_fisico"][3]="tepuy_sep_p_aprobacion.php";
$arbol["id"][3]="003";
$arbol["padre"][3]="001";
$arbol["numero_hijos"][3]=0;

$arbol["sistema"][4]="SEP";
$arbol["nivel"][4]=1;
$arbol["nombre_logico"][4]="Anulación de Solicitud de Ejecución Presupuestaria";
$arbol["nombre_fisico"][4]="tepuy_sep_p_anulacion.php";
$arbol["id"][4]="004";
$arbol["padre"][4]="001";
$arbol["numero_hijos"][4]=0;

$arbol["sistema"][5]="SEP";
$arbol["nivel"][5]=1;
$arbol["nombre_logico"][5]="Contabilizar Solicitud de Ejecución Presupuestaria";
$arbol["nombre_fisico"][5]="";
$arbol["id"][5]="005";
$arbol["padre"][5]="001";
$arbol["numero_hijos"][5]=2;

$arbol["sistema"][6]="SEP";
$arbol["nivel"][6]=2;
$arbol["nombre_logico"][6]="Contabilizar Solicitud";
$arbol["nombre_fisico"][6]="tepuy_mis_p_contabiliza_sep.php";
$arbol["id"][6]="006";
$arbol["padre"][6]="005";
$arbol["numero_hijos"][6]=0;

$arbol["sistema"][7]="SEP";
$arbol["nivel"][7]=2;
$arbol["nombre_logico"][7]="Reverso de Contabilización";
$arbol["nombre_fisico"][7]="tepuy_mis_p_reverso_sep.php";
$arbol["id"][7]="007";
$arbol["padre"][7]="005";
$arbol["numero_hijos"][7]=0;

$arbol["sistema"][8]="SEP";
$arbol["nivel"][8]=1;
$arbol["nombre_logico"][8]="Registrar Beneficiario";
$arbol["nombre_fisico"][8]="tepuy_sep_d_beneficiario.php";
$arbol["id"][8]="008";
$arbol["padre"][8]="001";
$arbol["numero_hijos"][8]=0;

$arbol["sistema"][9]="SEP";
$arbol["nivel"][9]=1;
$arbol["nombre_logico"][9]="Tranferencia del Personal de Nómina a Beneficiarios";
$arbol["nombre_fisico"][9]="tepuy_sep_p_transferencia.php";
$arbol["id"][9]="009";
$arbol["padre"][9]="001";
$arbol["numero_hijos"][9]=0;


$arbol["sistema"][10]="SEP";
$arbol["nivel"][10]=0;
$arbol["nombre_logico"][10]="Reportes";
$arbol["nombre_fisico"][10]="";
$arbol["id"][10]="010";
$arbol["padre"][10]="000";
$arbol["numero_hijos"][10]=2;

$arbol["sistema"][11]="SEP";
$arbol["nivel"][11]=1;
$arbol["nombre_logico"][11]="Listado de Solicitudes";
$arbol["nombre_fisico"][11]="tepuy_sep_r_solicitudes.php";
$arbol["id"][11]="011";
$arbol["padre"][11]="010";
$arbol["numero_hijos"][11]=0;

$arbol["sistema"][12]="SEP";
$arbol["nivel"][12]=1;
$arbol["nombre_logico"][12]="Listado de Beneficiarios";
$arbol["nombre_fisico"][12]="tepuy_rpc_r_beneficiario.php";
$arbol["id"][12]="012";
$arbol["padre"][12]="010";
$arbol["numero_hijos"][12]=0;

$arbol["sistema"][13]="SEP";
$arbol["nivel"][13]=0;
$arbol["nombre_logico"][13]="Configuración";
$arbol["nombre_fisico"][13]="";
$arbol["id"][13]="013";
$arbol["padre"][13]="000";
$arbol["numero_hijos"][13]=2;

$arbol["sistema"][14]="SEP";
$arbol["nivel"][14]=1;
$arbol["nombre_logico"][14]="Tipos de Solicitudes";
$arbol["nombre_fisico"][14]="tepuy_sep_d_tipo.php";
$arbol["id"][14]="014";
$arbol["padre"][14]="013";
$arbol["numero_hijos"][14]=0;

$arbol["sistema"][15]="SEP";
$arbol["nivel"][15]=1;
$arbol["nombre_logico"][15]="Concepto para el Pago";
$arbol["nombre_fisico"][15]="tepuy_sep_d_concepto.php";
$arbol["id"][15]="015";
$arbol["padre"][15]="013";
$arbol["numero_hijos"][15]=0;

?>
