<?php
$li_i=000;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Definiciones";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;// 001
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=5;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Procesos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;// 002
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=9;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Reportes";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;// 003
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=15;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Configuraci�n";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;//004
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=2;

//---- Definiciones ----//
$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Tipo de Articulo";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_d_tipoarticulo.php";
$arbol["id"][$li_i]=$li_i;//005
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Unidad de Medida";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_d_unidadmedida.php";
$arbol["id"][$li_i]=$li_i;//006
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Almac�n";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_d_almacen.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //008
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Art�culo";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_d_articulo.php";
$arbol["id"][$li_i]=$li_i;//007
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; //008
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Proveedores";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_d_proveedor.php";
$arbol["id"][$li_i]=$li_i;//007
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;
//------------Fin de Definiciones-----------------//

//------------------Procesos--------------------//
$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Entrada de Suministros a Almac�n";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_p_recepcion.php";
$arbol["id"][$li_i]=$li_i;//009
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Transferencia entre Almacenes";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_p_transferencia.php";
$arbol["id"][$li_i]=$li_i;//010
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Despacho de Suministros";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_p_despacho.php";
$arbol["id"][$li_i]=$li_i;//011
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Cierre de Ordenes de Compra";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_p_cerraroc.php";
$arbol["id"][$li_i]=$li_i;//012
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Toma de Inventario";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_p_toma.php";
$arbol["id"][$li_i]=$li_i;//013
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reverso de Entrada de Suministros a Almacen";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_p_revrecepcion.php";
$arbol["id"][$li_i]=$li_i;//014
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reverso de Despachos";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_p_revdespacho.php";
$arbol["id"][$li_i]=$li_i;//015
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reverso de Transferencia";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_p_revtransferencia.php";
$arbol["id"][$li_i]=$li_i;//016
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Contabilizar Despacho";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;//017
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=2;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Contabilizar";
$arbol["nombre_fisico"][$li_i]="tepuy_mis_p_contabiliza_siv.php";
$arbol["id"][$li_i]=$li_i;//018
$arbol["padre"][$li_i]="017";
$arbol["numero_hijos"][$li_i]=0;


$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=2;
$arbol["nombre_logico"][$li_i]="Reversar Contabilizaci�n";
$arbol["nombre_fisico"][$li_i]="tepuy_mis_p_reverso_siv.php";
$arbol["id"][$li_i]=$li_i;//019
$arbol["padre"][$li_i]="017";
$arbol["numero_hijos"][$li_i]=0;


//---------- Fin de Procesos -------------------//

//----------- Inicio de Reportes----------------//
$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Movimientos de Art�culos";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_movimientos.php";
$arbol["id"][$li_i]=$li_i;//020
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Movimientos de Art�culos";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_movimientos.php";
$arbol["id"][$li_i]=$li_i;//021
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Articulos por Solicitar";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_articuloxsolicitar.php";
$arbol["id"][$li_i]=$li_i;//022
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reporte de Articulos por Tipo";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_articuloxtipo.php";
$arbol["id"][$li_i]=$li_i;//023
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Listado de Art�culos por Almacen";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_listadoarticulos.php";
$arbol["id"][$li_i]=$li_i;//024
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Ordenes de Despacho";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_despachos.php";
$arbol["id"][$li_i]=$li_i;//025
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Entradas de Suministros a Almacen";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_recepcion.php";
$arbol["id"][$li_i]=$li_i;//026
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Transferencia entre Almacenes";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_transferencia.php";
$arbol["id"][$li_i]=$li_i;//027
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Resumen de Inventario";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_inventario.php";
$arbol["id"][$li_i]=$li_i;//028
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Listado de Almacenes";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_almacenes.php";
$arbol["id"][$li_i]=$li_i;//029
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Valoraci�n de Inventario";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_valinventario.php";
$arbol["id"][$li_i]=$li_i;//030
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Cierre de Ordenes de Compra";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_cierre.php";
$arbol["id"][$li_i]=$li_i;//031
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Valoraci�n de Toma de Inventario";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_valtoma.php";
$arbol["id"][$li_i]=$li_i;//032
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Valoraci�n de Ajustes de Inventario";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_valajustes.php";
$arbol["id"][$li_i]=$li_i;//033
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Acta de Recepcion de Bienes";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_acta_recepcion_bienes.php";
$arbol["id"][$li_i]=$li_i;//034
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Listado Imputaci�n Presupuestaria del Inventario";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_r_imputacionpresupuestaria.php";
$arbol["id"][$li_i]=$li_i;//035
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

//--------------Configuracion --------------------//

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definir Parametros";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_d_configuracion.php";
$arbol["id"][$li_i]=$li_i;//035
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SIV";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Importar/Exportar Inventario";
$arbol["nombre_fisico"][$li_i]="tepuy_siv_d_imp_exp_inventario.php";
$arbol["id"][$li_i]=$li_i;//035
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=0;

//-----------Fin de Configuraci�n ---------------------//
$gi_total=$li_i;

?>