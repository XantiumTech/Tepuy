//----------DHTML Menu Created using AllWebMenus PRO ver 5.3-#848---------------
//C:\wamp\www\Sub_Menus-Tepuy\cxp\menu_cxp.awm
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
var awmHash='NXBWMJKJLEHALAFAEAHAKADAMADK';
var awmNoMenuPrint=1;
var awmUseTrs=0;
var awmSepr=["0","","","","90","#808080","","1"];
var awmMarg=[0,0,0,0];
var YY= 82;//((window.innerHeight/2)-(window.innerHeight/4))-(window.innerHeight/42); //82 
var XX= ((window.innerWidth/2)-620); //110  396 para los menus cortos
//alert("Ancho: " + XX + "Alto: " + YY);
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["orden_p.png",42,40,"recepcion.png",31,22,"main-button-tile.gif",17,34,"indicator.gif",16,8,"main-item-left-float.png",17,24,"main-itemOver-left-float.png",15,24,"orden_pago.png",31,22,"integrar.png",31,22,"retencion-1.png",31,22,"reportes.png",31,22,"configuracion.png",31,22,"retorno.png",31,22];
awmCreateCSS(1,2,1,'#FFFFFF',n,n,'14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,2,1,'#FFFFFF',n,n,'14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,1,0,n,'#1803FB',n,n,n,'solid','1','#A7AFBC',0,0); /* 1803FB Azul FF0000 Rojo*/
awmCreateCSS(1,2,1,'#4A4A4A',n,2,'11px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,2,1,'#4A4A4A','#E8EBF1',n,'bold 11px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,1,0,n,'#A7AFBC',n,n,n,'solid','1','#A7AFBC',0,0);
awmCreateCSS(1,2,0,'#4A4A4A','#E8EBF1',n,'12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,2,0,'#1803FB','#F5F8FE',n,'bold 12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1); /* 1803FB Azul FF0000 Rojo*/
awmCF(3,5,5,0,-1);
awmCF(4,1,0,0,0);
awmCF(5,1,0,0,0);
//var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,100,82,0,1,2,1,0,1,n,n,100,0,0,100,82,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
//   XX lo que se mueve a lo ancho y YY se desplaza arriba o abajo
var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,XX,YY,0,1,2,1,0,1,n,n,100,0,0,XX,YY,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
it=s0.addItemWithImages(0,1,1,"Ordenes de Pago",n,n,"",0,0,0,3,3,3,n,n,n,"",n,n,n,n,n,0,0,0,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,n,n);
it=s0.addItemWithImages(3,4,4,"Recibir Documentos",n,n,"",1,1,1,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,11,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,17,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Registro",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_p_recepcioncontable.php",n,n,n,"../tepuy_cxp_p_recepcioncontable.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,188,n);
it=s1.addItemWithImages(6,7,7,"Aprobación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_p_aprobacionrecepcion.php",n,n,n,"../tepuy_cxp_p_aprobacionrecepcion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,5,n);
it=s1.addItemWithImages(6,7,7,"Anulación",n,n,"",n,n,n,1,1,1,n,n,n,"../tepuy_cxp_p_anulacionrecepcion.php",n,n,n,"../tepuy_cxp_p_anulacionrecepcion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,189,n);

it=s1.addItemWithImages(6,7,7,"Reintegros (NC)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_p_ncnd.php",n,n,n,"../tepuy_cxp_p_ncnd.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,7,n);
it=s1.addItemWithImages(6,7,7,"Contabilizar Reintegro ",n,n,"",7,7,7,1,1,1,n,n,n,"../tepuy_cxp_p_contabiliza_ncd.php",n,n,n,"../tepuy_cxp_p_contabiliza_ncd.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,9,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,2,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);


it=s1.addItemWithImages(6,7,7,"Registrar Beneficiarios",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_rpc_d_beneficiario.php",n,n,n,"../tepuy_rpc_d_beneficiario.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,7,n);
it=s1.addItemWithImages(6,7,7,"Registrar Proveedores",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_rpc_d_proveedor.php",n,n,n,"../tepuy_rpc_d_proveedor.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,7,n);
it=s0.addItemWithImages(3,4,4,"Orden de pago",n,n,"",6,6,6,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,1,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,1,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Registro",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_p_solicitudpago.php",n,n,n,"../tepuy_cxp_p_solicitudpago.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,6,n);
it=s1.addItemWithImages(6,7,7,"Aprobación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_p_aprobacionsolicitudpago.php",n,n,n,"../tepuy_cxp_p_aprobacionsolicitudpago.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,8,n);
it=s1.addItemWithImages(6,7,7,"Contabilizar Orden de Pago",n,n,"",7,7,7,1,1,1,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,9,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,2,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Contabilizar",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_contabiliza_cxp.php",n,n,n,"../tepuy_mis_p_contabiliza_cxp.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,10,n);
it=s2.addItemWithImages(6,7,7,"Reversar Contabilización",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_reverso_cxp.php",n,n,n,"../tepuy_mis_p_reverso_cxp.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,12,n);
it=s2.addItemWithImages(6,7,7,"Anular Orden de Pago",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_anula_cxp.php",n,n,n,"../tepuy_mis_p_anula_cxp.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,13,n);
it=s2.addItemWithImages(6,7,7,"Reversar Anulación de Orden de Pago",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_reverso_anula_cxp.php",n,n,n,"../tepuy_mis_p_reverso_anula_cxp.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,15,n);
it=s0.addItemWithImages(3,4,4,"Comprobantes de Retención",n,n,"",8,8,8,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,16,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,3,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
//it=s1.addItemWithImages(6,7,7,"Crear Comprobante 2015",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_p_cmp_retencion.php",n,n,n,"../tepuy_cxp_p_cmp_retencion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,18,n);
it=s1.addItemWithImages(6,7,7,"Crear Comprobante",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_p_cmp_retencion_2016.php",n,n,n,"../tepuy_cxp_p_cmp_retencion_2016.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,18,n);
it=s1.addItemWithImages(6,7,7,"Editar Comprobante",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_p_modcmpret.php",n,n,n,"../tepuy_cxp_p_modcmpret.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,19,n);
it=s0.addItemWithImages(3,4,4,"Reportes",n,n,"",9,9,9,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,14,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,18,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Recepción de Documentos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_recepciones.php",n,n,n,"../tepuy_cxp_r_recepciones.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,0,n);
it=s1.addItemWithImages(6,7,7,"Ordenes de Pago",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,25,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,7,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Relación Consecutiva",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_relacionsolicitudes.php",n,n,n,"../tepuy_cxp_r_relacionsolicitudes.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,32,n);
it=s2.addItemWithImages(6,7,7,"Relación de Ordenes",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_solicitudes.php",n,n,n,"../tepuy_cxp_r_solicitudes.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,50,n);
it=s2.addItemWithImages(6,7,7,"Formato 1",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_solicitudesf1.php",n,n,n,"../tepuy_cxp_r_solicitudesf1.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,33,n);
it=s2.addItemWithImages(6,7,7,"Formato 2",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_solicitudesf2.php",n,n,n,"../tepuy_cxp_r_solicitudesf2.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,34,n);
it=s1.addItemWithImages(6,7,7,"Retenciones",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,26,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,8,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"I.V.A.",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_retencionesiva.php",n,n,n,"../tepuy_cxp_r_retencionesiva.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,35,n);
it=s2.addItemWithImages(6,7,7,"Declaración Informativa de I.V.A.",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_retencionesdeclaracioniva.php",n,n,n,"../tepuy_cxp_r_retencionesdeclaracioniva.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,36,n);
it=s2.addItemWithImages(6,7,7,"I.S.L.R.",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_retencionesislr.php",n,n,n,"../tepuy_cxp_r_retencionesislr.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,37,n);
it=s2.addItemWithImages(6,7,7,"Declaración Informativa de I.S.L.R.",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_retencionesdeclaracionislr.php",n,n,n,"../tepuy_cxp_r_retencionesdeclaracionislr.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,38,n);
it=s2.addItemWithImages(6,7,7,"Formato Unificado de Retenciones",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_retencionesunificadas.php",n,n,n,"../tepuy_cxp_r_retencionesunificadas.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,39,n);
it=s2.addItemWithImages(6,7,7,"Municipales (I.S.A.E.)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_retencionesmunicipales.php",n,n,n,"../tepuy_cxp_r_retencionesmunicipales.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,39,n);
it=s2.addItemWithImages(6,7,7,"Aporte Social",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_retencionesaporte.php",n,n,n,"../tepuy_cxp_r_retencionesaporte.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,40,n);
it=s2.addItemWithImages(6,7,7,"Timbre Fiscal",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_retencionestimbrefiscal.php",n,n,n,"../tepuy_cxp_r_retencionestimbrefiscal.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,40,n);
it=s2.addItemWithImages(6,7,7,"General",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_retencionesgeneral.php",n,n,n,"../tepuy_cxp_r_retencionesgeneral.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,41,n);
it=s2.addItemWithImages(6,7,7,"Específico",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_retencionesespecifico.php",n,n,n,"../tepuy_cxp_r_retencionesespecifico.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,42,n);
it=s1.addItemWithImages(6,7,7,"Resumen de Ordenes de pago",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_cxpresumido.php",n,n,n,"../tepuy_cxp_r_cxpresumido.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,49,n);
it=s1.addItemWithImages(6,7,7,"AR-C",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_arc.php",n,n,n,"../tepuy_cxp_r_arc.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,28,n);
it=s1.addItemWithImages(6,7,7,"Libro de Compras",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,31,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,9,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"General",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_librocompra.php",n,n,n,"../tepuy_cxp_r_librocompra.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,43,n);
it=s2.addItemWithImages(6,7,7,"Resumido",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_librocompra_res.php",n,n,n,"../tepuy_cxp_r_librocompra_res.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,44,n);
it=s1.addItemWithImages(6,7,7,"Libro de I.S.L.R. / Timbre Fiscal / Imp. Municipal",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cxp_r_libro_islr_timbrefiscal.php",n,n,n,"../tepuy_cxp_r_libro_islr_timbrefiscal.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,47,n);
it=s1.addItemWithImages(6,7,7,"Listado de Tipos de Documentos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_soc_r_aceptacion_servicios.php",n,n,n,"../tepuy_soc_r_aceptacion_servicios.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,46,n);
it=s0.addItemWithImages(3,4,4,"Configuración",n,n,"",10,10,10,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,17,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,84,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"I.V.A. (Otros Créditos)",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_cxp_d_otroscreditos.php",n,n,n,"../cfg/tepuy_cxp_d_otroscreditos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,2,n);
it=s1.addItemWithImages(6,7,7,"Retenciones/Deducciones",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_cxp_d_deducciones.php",n,n,n,"../cfg/tepuy_cxp_d_deducciones.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,3,n);
it=s1.addItemWithImages(6,7,7,"Documentos",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_cxp_d_documentos.php",n,n,n,"../cfg/tepuy_cxp_d_documentos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,45,n);
it=s1.addItemWithImages(6,7,7,"Establecer Contadores en la Retenciones",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_cxp_d_inicio_de_contadores.php",n,n,n,"../cfg/tepuy_cxp_d_inicio_de_contadores.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,45,n);
it=s0.addItemWithImages(3,4,4,"Retornar",n,n,"",11,11,11,3,3,3,n,n,n,"",n,n,n,"../../tepuy_menu.php",n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,4,n);
s0.pm.buildMenu();
}}
