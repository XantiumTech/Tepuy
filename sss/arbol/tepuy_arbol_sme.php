<?php
$gi_total=15;

$arbol["sistema"][1]="SME";
$arbol["nivel"][1]=0;
$arbol["nombre_logico"][1]="Procesos";
$arbol["nombre_fisico"][1]="";
$arbol["id"][1]="001";
$arbol["padre"][1]="000";
$arbol["numero_hijos"][1]=2;

$arbol["sistema"][2]="SME";
$arbol["nivel"][2]=1;
$arbol["nombre_logico"][2]="Registro de Solicitud";
$arbol["nombre_fisico"][2]="tepuy_sme_p_solicitud.php";
$arbol["id"][2]="002";
$arbol["padre"][2]="001";
$arbol["numero_hijos"][2]=0;

$arbol["sistema"][3]="SME";
$arbol["nivel"][3]=1;
$arbol["nombre_logico"][3]="Aprobación de Solicitud";
$arbol["nombre_fisico"][3]="tepuy_sme_p_aprobacion.php";
$arbol["id"][3]="003";
$arbol["padre"][3]="001";
$arbol["numero_hijos"][3]=0;

$arbol["sistema"][4]="SME";
$arbol["nivel"][4]=1;
$arbol["nombre_logico"][4]="Anulación de Solicitud de Ejecución Presupuestaria";
$arbol["nombre_fisico"][4]="tepuy_sme_p_anulacion.php";
$arbol["id"][4]="004";
$arbol["padre"][4]="001";
$arbol["numero_hijos"][4]=0;

$arbol["sistema"][5]="SME";
$arbol["nivel"][5]=1;
$arbol["nombre_logico"][5]="Registro de Proveedores";
$arbol["nombre_fisico"][5]="tepuy_sme_d_proveedor.php";
$arbol["id"][5]="005";
$arbol["padre"][5]="001";
$arbol["numero_hijos"][5]=0;

$arbol["sistema"][6]="SME";
$arbol["nivel"][6]=1;
$arbol["nombre_logico"][6]="Buscar Personal";
$arbol["nombre_fisico"][6]="tepuy_sme_p_buscarpersonal.php";
$arbol["id"][6]="006";
$arbol["padre"][6]="001";
$arbol["numero_hijos"][6]=0;


$arbol["sistema"][7]="SME";
$arbol["nivel"][7]=0;
$arbol["nombre_logico"][7]="Reportes";
$arbol["nombre_fisico"][7]="";
$arbol["id"][7]="007";
$arbol["padre"][7]="000";
$arbol["numero_hijos"][7]=2;

$arbol["sistema"][8]="SME";
$arbol["nivel"][8]=1;
$arbol["nombre_logico"][8]="Listado de Solicitudes";
$arbol["nombre_fisico"][8]="tepuy_sme_r_solicitudes.php";
$arbol["id"][8]="008";
$arbol["padre"][8]="007";
$arbol["numero_hijos"][8]=0;

$arbol["sistema"][9]="SME";
$arbol["nivel"][9]=0;
$arbol["nombre_logico"][9]="Configuración";
$arbol["nombre_fisico"][9]="";
$arbol["id"][9]="009";
$arbol["padre"][9]="000";
$arbol["numero_hijos"][9]=2;

$arbol["sistema"][10]="SME";
$arbol["nivel"][10]=1;
$arbol["nombre_logico"][10]="Tipos de Solicitudes";
$arbol["nombre_fisico"][10]="tepuy_sme_d_tipo_servicio.php";
$arbol["id"][10]="010";
$arbol["padre"][10]="009";
$arbol["numero_hijos"][10]=0;

$arbol["sistema"][11]="SME";
$arbol["nivel"][11]=1;
$arbol["nombre_logico"][11]="Establecer Monto Tope según Tipo";
$arbol["nombre_fisico"][11]="tepuy_sme_d_monto_tipo_servicio.php";
$arbol["id"][11]="011";
$arbol["padre"][11]="009";
$arbol["numero_hijos"][11]=0;

?>
