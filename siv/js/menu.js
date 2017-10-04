//----------DHTML Menu Created using AllWebMenus PRO ver 5.3-#848---------------
//C:\wamp\www\Sub_Menus-Tepuy\siv\menu_siv.awm
var awmMenuName='menu';
var awmLibraryBuild=848;
var awmLibraryPath='/';
var awmImagesPath='/';
var awmSupported=(navigator.appName + navigator.appVersion.substring(0,1)=="Netscape5" || document.all || document.layers || navigator.userAgent.indexOf('Opera')>-1 || navigator.userAgent.indexOf('Konqueror')>-1)?1:0;
if (awmAltUrl!='' && !awmSupported) window.location.replace(awmAltUrl);
if (awmSupported){
var nua=navigator.userAgent,scriptNo=(nua.indexOf('Chrome')>-1)?2:((nua.indexOf('Safari')>-1)?7:(nua.indexOf('Gecko')>-1)?2:((nua.indexOf('Opera')>-1)?4:1));
var mpi=document.location,xt="";
var mpa=mpi.protocol+"//"+mpi.host;
var mpi=mpi.protocol+"//"+mpi.host+mpi.pathname;
if(scriptNo==1){oBC=document.all.tags("BASE");if(oBC && oBC.length) if(oBC[0].href) mpi=oBC[0].href;}
while (mpi.search(/\\/)>-1) mpi=mpi.replace("\\","/");
mpi=mpi.substring(0,mpi.lastIndexOf("/")+1);
var e=document.getElementsByTagName("SCRIPT");
for (var i=0;i<e.length;i++){if (e[i].src){if (e[i].src.indexOf(awmMenuName+".js")!=-1){xt=e[i].src.split("/");if (xt[xt.length-1]==awmMenuName+".js"){xt=e[i].src.substring(0,e[i].src.length-awmMenuName.length-3);if (e[i].src.indexOf("://")!=-1){mpi=xt;}else{if(xt.substring(0,1)=="/")mpi=mpa+xt; else mpi+=xt;}}}}}
while (mpi.search(/\/\.\//)>-1) {mpi=mpi.replace("/./","/");}
var awmMenuPath=mpi.substring(0,mpi.length-1);
while (awmMenuPath.search("'")>-1) {awmMenuPath=awmMenuPath.replace("'","%27");}
document.write("<SCRIPT SRC='"+awmMenuPath+awmLibraryPath+"/awmlib"+scriptNo+".js'><\/SCRIPT>");
var n=null;
awmzindex=1000;
}

var awmImageName='';
var awmPosID='';
var awmSubmenusFrame='';
var awmSubmenusFrameOffset;
var awmOptimize=0;
var awmHash='XDKBKDFSMKHALAFAEAHAKADAMADK';
var awmNoMenuPrint=1;
var awmUseTrs=0;
var awmSepr=["0","","","","90","#808080","","1"];
var awmMarg=[0,0,0,0];
var YY= 82;//((window.innerHeight/2)-(window.innerHeight/4))-(window.innerHeight/42); //82 
var XX= ((window.innerWidth/2)-420); //110  396 para los menus cortos
//alert("Ancho: " + XX + "Alto: " + YY);
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["inventario.png",42,40,"definicion.png",31,22,"main-button-tile.gif",17,34,"indicator.gif",16,8,"main-item-left-float.png",17,24,"main-itemOver-left-float.png",15,24,"procesos.png",31,22,"integrar.png",31,22,"reportes.png",31,22,"configuracion.png",31,22,"retorno.png",31,22];
awmCreateCSS(1,2,1,'#FFFFFF',n,n,'14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,2,1,'#FFFFFF',n,n,'14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,1,0,n,'#1803FB',n,n,n,'solid','1','#A7AFBC',0,0); /* 1803FB Azul FF0000 Rojo*/
awmCreateCSS(1,2,1,'#4A4A4A',n,2,'12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,2,1,'#4A4A4A','#E8EBF1',n,'bold 12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,1,0,n,'#A7AFBC',n,n,n,'solid','1','#A7AFBC',0,0);
awmCreateCSS(1,2,0,'#4A4A4A','#E8EBF1',n,'12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,2,0,'#1803FB','#F5F8FE',n,'bold 12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1); /* 1803FB Azul FF0000 Rojo*/
awmCF(3,5,5,0,-1);
awmCF(4,1,0,0,0);
awmCF(5,1,0,0,0);
//var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,100,82,0,1,2,1,0,1,n,n,100,0,0,100,82,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
//   XX lo que se mueve a lo ancho y YY se desplaza arriba o abajo
var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,XX,YY,0,1,2,1,0,1,n,n,100,0,0,XX,YY,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
it=s0.addItemWithImages(0,1,1,"Inventario",n,n,"",0,0,0,3,3,3,n,n,n,"",n,n,n,n,n,0,0,0,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,n,n);
it=s0.addItemWithImages(3,4,4,"Definiciones",n,n,"",1,1,1,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,8,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,1,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Tipo de Artículo",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_d_tipoarticulo.php",n,n,n,"../tepuy_siv_d_tipoarticulo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,9,n);
it=s1.addItemWithImages(6,7,7,"Unidad de Medida",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_d_unidadmedida.php",n,n,n,"../tepuy_siv_d_unidadmedida.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,10,n);
it=s1.addItemWithImages(6,7,7,"Almacen",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_d_almacen.php",n,n,n,"../tepuy_siv_d_almacen.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,12,n);
it=s1.addItemWithImages(6,7,7,"Artículo",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_d_articulo.php",n,n,n,"../tepuy_siv_d_articulo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,13,n);
it=s1.addItemWithImages(6,7,7,"Proveedores",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_d_proveedor.php",n,n,n,"../tepuy_siv_d_proveedor.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,13,n);
it=s0.addItemWithImages(3,4,4,"Procesos",n,n,"",6,6,6,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,11,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,17,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Entrada de Suministros al Almacen",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_p_recepcion.php",n,n,n,"../tepuy_siv_p_recepcion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,188,n);
it=s1.addItemWithImages(6,7,7,"Transferencia entre Almacenes",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_p_transferencia.php",n,n,n,"../tepuy_siv_p_transferencia.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,5,n);
it=s1.addItemWithImages(6,7,7,"Despacho",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_p_despacho.php",n,n,n,"../tepuy_siv_p_despacho.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,6,n);
it=s1.addItemWithImages(6,7,7,"Cierre de Ordenes de Compra",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_p_cerraroc.php",n,n,n,"../tepuy_siv_p_cerraroc.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,27,n);
it=s1.addItemWithImages(6,7,7,"Toma de Inventario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_p_toma.php",n,n,n,"../tepuy_siv_p_toma.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,28,n);
it=s1.addItemWithImages(6,7,7,"Reverso de Entrada de Suministro al Almacen",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_p_revrecepcion.php",n,n,n,"../tepuy_siv_p_revrecepcion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,29,n);
it=s1.addItemWithImages(6,7,7,"Reverso de Despacho",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_p_revdespacho.php",n,n,n,"../tepuy_siv_p_revdespacho.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,7,n);
it=s1.addItemWithImages(6,7,7,"Reverso de Transferencia",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_p_revtransferencia.php",n,n,n,"../tepuy_siv_p_revtransferencia.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,15,n);
it=s1.addItemWithImages(6,7,7,"Contabilizar Despacho",n,n,"",7,7,7,1,1,1,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,189,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,76,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Contabilizar",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_contabiliza_siv.php",n,n,n,"../tepuy_mis_p_contabiliza_siv.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,191,n);
it=s2.addItemWithImages(6,7,7,"Reversar Contabilización",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_reverso_siv.php",n,n,n,"../tepuy_mis_p_reverso_siv.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,192,n);
it=s0.addItemWithImages(3,4,4,"Reportes",n,n,"",8,8,8,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,14,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,18,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Existencia de Almacen",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_articuloxalmacen.php",n,n,n,"../tepuy_siv_r_articuloxalmacen.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,0,n);
it=s1.addItemWithImages(6,7,7,"Movimientos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_movimientos.php",n,n,n,"../tepuy_siv_r_movimientos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,1,n);
it=s1.addItemWithImages(6,7,7,"Artículos por Solicitar",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,32,n);
it=s1.addItemWithImages(6,7,7,"Listado de Artículos por Almacen",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_listadoarticulos.php",n,n,n,"../tepuy_siv_r_listadoarticulos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,33,n);
it=s1.addItemWithImages(6,7,7,"Ordenes de Despacho",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_despachos.php",n,n,n,"../tepuy_siv_r_despachos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,34,n);
it=s1.addItemWithImages(6,7,7,"Entrada de Suministros al Almacen",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_recepcion.php",n,n,n,"../tepuy_siv_r_recepcion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,35,n);
it=s1.addItemWithImages(6,7,7,"Transferencia entre Almacenes",n,n,"",n,n,n,1,3,3,n,n,n,"../tepuy_siv_r_transferencia.php",n,n,n,"../tepuy_siv_r_transferencia.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,36,n);
it=s1.addItemWithImages(6,7,7,"Resumen de Inventario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_inventario.php",n,n,n,"../tepuy_siv_r_inventario.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,24,n);
it=s1.addItemWithImages(6,7,7,"Listado de Almacenes",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_almacenes.php",n,n,n,"../tepuy_siv_r_almacenes.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,25,n);
it=s1.addItemWithImages(6,7,7,"Valoración de Inventario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_valinventario.php",n,n,n,"../tepuy_siv_r_valinventario.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,23,n);
it=s1.addItemWithImages(6,7,7,"Cierre de Ordenes de Compra",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_cierre.php",n,n,n,"../tepuy_siv_r_cierre.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,22,n);
it=s1.addItemWithImages(6,7,7,"Valoración de Toma de Inventario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_valtoma.php",n,n,n,"../tepuy_siv_r_valtoma.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,21,n);
it=s1.addItemWithImages(6,7,7,"Valoración de Ajuste de Inventario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_valajustes.php",n,n,n,"../tepuy_siv_r_valajustes.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,20,n);
it=s1.addItemWithImages(6,7,7,"Acta de Recepción de Bienes",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_acta_recepcion_bienes.php",n,n,n,"../tepuy_siv_r_acta_recepcion_bienes.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,19,n);
it=s1.addItemWithImages(6,7,7,"Listado Imputación Presupuestaria del Inventario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_imputacionpresupuestaria.php",n,n,n,"../tepuy_siv_r_imputacionpresupuestaria.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,37,n);
it=s0.addItemWithImages(3,4,4,"Configuración",n,n,"",9,9,9,3,3,3,n,n,n,"",n,n,n,"",n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,17,n);

var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,1,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Parametros",n,n,"",n,n,n,3,3,3,n,n,n,".../tepuy_siv_d_configuracion.php",n,n,n,"../tepuy_siv_d_configuracion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,9,n);
it=s1.addItemWithImages(6,7,7,"Importar/Exportar Inventario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_d_imp_exp_inventario.php",n,n,n,"../tepuy_siv_d_imp_exp_inventario.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,10,n);
it=s0.addItemWithImages(3,4,4,"Retornar",n,n,"",10,10,10,3,3,3,n,n,n,"",n,n,n,"../../tepuy_menu.php",n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,4,n);
s0.pm.buildMenu();
}}
