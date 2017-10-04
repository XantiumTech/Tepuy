//----------DHTML Menu Created using AllWebMenus PRO ver 5.3-#848---------------
//C:\wamp\www\Sub_Menus-Tepuy\scb\menu_scb.awm
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
var awmHash='EIQDDLGHDQMCCGAKDOKSRWOABEWB';
var awmNoMenuPrint=1;
var awmUseTrs=0;
var awmSepr=["0","","","","90","#808080","","1"];
var awmMarg=[0,0,0,0];
var YY= 82;//((window.innerHeight/2)-(window.innerHeight/4))-(window.innerHeight/42); //82 
var XX= ((window.innerWidth/2)-444); //110  396 para los menus cortos
//alert("Ancho: " + XX + "Alto: " + YY);
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["caja_banco.png",42,40,"procesos.png",31,22,"main-button-tile.gif",17,34,"indicator.gif",16,8,"definicion.png",31,22,"main-item-left-float.png",17,24,"main-itemOver-left-float.png",15,24,"integrar.png",31,22,"reportes.png",31,22,"configuracion.png",31,22,"mantenimiento.png",31,22,"retorno.png",31,22];
awmCreateCSS(1,2,1,'#FFFFFF',n,n,'14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,2,1,'#FFFFFF',n,n,'14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,1,0,n,'#1803FB',n,n,n,'solid','1','#A7AFBC',0,0); /* 1803FB Azul FF0000 Rojo*/
awmCreateCSS(1,2,1,'#4A4A4A',n,2,'12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,2,1,'#4A4A4A','#E8EBF1',n,'bold 12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,1,0,n,'#A7AFBC',n,n,n,'solid','1','#A7AFBC',0,0);
awmCreateCSS(1,2,0,'#4A4A4A','#E8EBF1',n,'12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,2,0,'#1803FB','#F5F8FE',n,'bold 12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1); /* 1803FB Azul FF0000 Rojo*/
awmCF(3,5,5,0,-1);
awmCF(5,1,0,0,0);
awmCF(6,1,0,0,0);
//var s0=awmCreateMenu(0,0,0,1,1,0,0,0,0,116,82,0,1,2,1,0,1,n,n,100,0,0,116,82,0,-1,1,600,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
//   XX lo que se mueve a lo ancho y YY se desplaza arriba o abajo
var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,XX,YY,0,1,2,1,0,1,n,n,100,0,0,XX,YY,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
it=s0.addItemWithImages(0,1,1,"Caja y Banco",n,n,"",0,0,0,3,3,3,n,n,n,"",n,n,n,n,n,0,0,0,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,n,n);
it=s0.addItemWithImages(3,4,4,"Procesos",n,n,"",1,1,1,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,11,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,17,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Definiciones",n,n,"",4,4,4,1,1,1,n,n,n,"",n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,60,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,9,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Bancos",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_scb_d_banco.php",n,n,n,"../cfg/tepuy_scb_d_banco.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,63,n);
it=s2.addItemWithImages(6,7,7,"Agencias",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_scb_d_agencia.php",n,n,n,"../cfg/tepuy_scb_d_agencia.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,70,n);
it=s2.addItemWithImages(6,7,7,"Tipo de Cuenta",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_scb_d_tipocta.php",n,n,n,"../cfg/tepuy_scb_d_tipocta.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,64,n);
it=s2.addItemWithImages(6,7,7,"Cuentas Bancarias",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_scb_d_ctabanco.php",n,n,n,"../cfg/tepuy_scb_d_ctabanco.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,65,n);
it=s2.addItemWithImages(6,7,7,"Chequera",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_scb_d_chequera.php",n,n,n,"../cfg/tepuy_scb_d_chequera.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,66,n);
it=s2.addItemWithImages(6,7,7,"Tipo de Colocación",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_scb_d_tipocolocacion.php",n,n,n,"../cfg/tepuy_scb_d_tipocolocacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,67,n);
it=s2.addItemWithImages(6,7,7,"Colocación",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_scb_d_colocacion.php",n,n,n,"../cfg/tepuy_scb_d_colocacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,68,n);
it=s2.addItemWithImages(6,7,7,"Concepto de Movimientos",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_scb_d_conceptos.php",n,n,n,"../cfg/tepuy_scb_d_conceptos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,69,n);
it=s1.addItemWithImages(6,7,7,"Movimientos Bancarios",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_movbanco.php",n,n,n,"../tepuy_scb_p_movbanco.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,32,n);
it=s1.addItemWithImages(6,7,7,"Cancelaciones",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,188,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,1,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Programación de Pago",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_progpago.php",n,n,n,"../tepuy_scb_p_progpago.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,1,n);
it=s2.addItemWithImages(6,7,7,"Desprogramación de Pago",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_desprogpago.php",n,n,n,"../tepuy_scb_p_desprogpago.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,6,n);
it=s2.addItemWithImages(6,7,7,"Emisión de Cheques",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_emision_chq.php",n,n,n,"../tepuy_scb_p_emision_chq.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,8,n);
it=s2.addItemWithImages(6,7,7,"Pago con Transferencias",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_pago_transferencia.php",n,n,n,"../tepuy_scb_p_pago_transferencia.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,8,n);
it=s2.addItemWithImages(6,7,7,"Eliminación de Cheques o Pago con Transferencias no Contabilizados",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_elimin_chq.php",n,n,n,"../tepuy_scb_p_elimin_chq.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,9,n);
//it=s2.addItemWithImages(6,7,7,"Pago Directo",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_pago_directo.php",n,n,n,"../tepuy_scb_p_pago_directo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,10,n);
//it=s2.addItemWithImages(6,7,7,"Orden de Pago Directa",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_orden_pago_directo.php",n,n,n,"../tepuy_scb_p_orden_pago_directo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,29,n);
//it=s2.addItemWithImages(6,7,7,"Carta Orden",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_carta_orden_mnd.php",n,n,n,"../tepuy_scb_p_carta_orden_mnd.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,30,n);
//it=s2.addItemWithImages(6,7,7,"Eliminación de Carta Orden no Contabilizada",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_elimin_carta_orden.php",n,n,n,"../tepuy_scb_p_elimin_carta_orden.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,31,n);
it=s1.addItemWithImages(6,7,7,"Conciliación Bancaria",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_conciliacion.php",n,n,n,"../tepuy_scb_p_conciliacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,5,n);
it=s1.addItemWithImages(6,7,7,"Colocaciones",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_movcol.php",n,n,n,"../tepuy_scb_p_movcol.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,33,n);
it=s1.addItemWithImages(6,7,7,"Transferencia Bancaria",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_transferencias.php",n,n,n,"../tepuy_scb_p_transferencias.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,34,n);
it=s1.addItemWithImages(6,7,7,"Entrega de Cheque",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_entregach.php",n,n,n,"../tepuy_scb_p_entregach.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,35,n);
it=s1.addItemWithImages(6,7,7,"Reverso Entrega de Cheque",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_reverso_entregach.php",n,n,n,"../tepuy_scb_p_reverso_entregach.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,36,n);
//it=s1.addItemWithImages(6,7,7,"Procesar Documentos No Contabilizados",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_procesar_no_contabilizables.php",n,n,n,"../tepuy_scb_p_procesar_no_contabilizables.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,37,n);
it=s1.addItemWithImages(6,7,7,"Contabilizar Movimientos Bancarios",n,n,"",7,7,7,1,1,1,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,189,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,76,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Contabilizar",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_contabiliza_scb.php",n,n,n,"../tepuy_mis_p_contabiliza_scb.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,191,n);
it=s2.addItemWithImages(6,7,7,"Reversar Contabilización",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_reverso_scb.php",n,n,n,"../tepuy_mis_p_reverso_scb.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,192,n);
it=s2.addItemWithImages(6,7,7,"Anular Movimiento Bancario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_anula_scb.php",n,n,n,"../tepuy_mis_p_anula_scb.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,18,n);
it=s2.addItemWithImages(6,7,7,"Reversar Anulación de Movimiento Bancario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_reverso_anula_scb.php",n,n,n,"../tepuy_mis_p_reverso_anula_scb.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,19,n);
it=s1.addItemWithImages(6,7,7,"Contabilizar Colocaciones",n,n,"",7,7,7,1,1,1,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,22,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,4,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Contabilizar",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_contabiliza_scbcol.php",n,n,n,"../tepuy_mis_p_contabiliza_scbcol.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,23,n);
it=s2.addItemWithImages(6,7,7,"Reversar Contabilización",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_reverso_scbcol.php",n,n,n,"../tepuy_mis_p_reverso_scbcol.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,24,n);
//it=s1.addItemWithImages(6,7,7,"Contabilizar Orden de Pago Directa",n,n,"",7,7,7,1,1,1,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,7,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,3,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
//it=s2.addItemWithImages(6,7,7,"Contabilizar",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_contabiliza_scbop.php",n,n,n,"../tepuy_mis_p_contabiliza_scbop.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,20,n);
//it=s2.addItemWithImages(6,7,7,"Reversar Contabilización",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_reverso_scbop.php",n,n,n,"../tepuy_mis_p_reverso_scbop.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,21,n);
it=s1.addItemWithImages(6,7,7,"Registrar Beneficiario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_rpc_d_beneficiario.php",n,n,n,"../tepuy_rpc_d_beneficiario.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,71,n);
it=s0.addItemWithImages(3,4,4,"Reportes",n,n,"",8,8,8,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,14,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,18,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Disponibilidad Financiera",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_disponibilidad.php",n,n,n,"../tepuy_scb_r_disponibilidad.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,0,n);
it=s1.addItemWithImages(6,7,7,"Listado de Documentos",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,25,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,5,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Documentos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_documentos.php",n,n,n,"../tepuy_scb_r_documentos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,38,n);
it=s2.addItemWithImages(6,7,7,"Conciliados",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_list_doc_conciliados.php",n,n,n,"../tepuy_scb_r_list_doc_conciliados.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,39,n);
it=s2.addItemWithImages(6,7,7,"Documentos en Tránsito",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_list_doc_transito.php",n,n,n,"../tepuy_scb_r_list_doc_transito.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,40,n);
//it=s1.addItemWithImages(6,7,7,"Listado de Ordenes de Pago Directa",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_ordenpago.php",n,n,n,"../tepuy_scb_r_ordenpago.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,26,n);
it=s1.addItemWithImages(6,7,7,"Conciliación Bancaria",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_conciliacion.php",n,n,n,"../tepuy_scb_r_conciliacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,59,n);
it=s1.addItemWithImages(6,7,7,"Pagos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_pagos.php",n,n,n,"../tepuy_scb_r_pagos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,27,n);
it=s1.addItemWithImages(6,7,7,"Estado de Cuenta",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,44,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,6,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Formato 1",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_estado_cta.php",n,n,n,"../tepuy_scb_r_estado_cta.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,45,n);
it=s2.addItemWithImages(6,7,7,"Resumido",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_estado_ctares.php",n,n,n,"../tepuy_scb_r_estado_ctares.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,46,n);
it=s1.addItemWithImages(6,7,7,"Otros Listados",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,47,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,7,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Listado de Chequeras",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_listadochequeras.php",n,n,n,"../tepuy_scb_r_listadochequeras.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,48,n);
it=s2.addItemWithImages(6,7,7,"Mayor Presupuestario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_mayor_presupuestario.php",n,n,n,"../tepuy_scb_r_mayor_presupuestario.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,49,n);
it=s2.addItemWithImages(6,7,7,"Relación Selectiva de Cheques",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_relacion_sel_chq.php",n,n,n,"../tepuy_scb_r_relacion_sel_chq.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,50,n);
it=s2.addItemWithImages(6,7,7,"Relación Selectiva de Documentos (No Incluye Cheques)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_relacion_sel_docs.php",n,n,n,"../tepuy_scb_r_relacion_sel_docs.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,51,n);
it=s2.addItemWithImages(6,7,7,"Movimiento Presupuestario por Banco",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_spg_x_banco.php",n,n,n,"../tepuy_scb_r_spg_x_banco.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,52,n);
it=s1.addItemWithImages(6,7,7,"Cheques en Custodia",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_chq_custodia_entregados.php",n,n,n,"../tepuy_scb_r_chq_custodia_entregados.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,28,n);
it=s1.addItemWithImages(6,7,7,"Libro Auxiliar de Banco",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_libro_auxiliar_banco.php",n,n,n,"../tepuy_scb_r_libro_auxiliar_banco.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,41,n);
it=s1.addItemWithImages(6,7,7,"Libro Banco",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_libro_banco.php",n,n,n,"../tepuy_scb_r_libro_banco.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,42,n);
it=s1.addItemWithImages(6,7,7,"Listado de Cheques Caducados",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_r_chq_caducados.php",n,n,n,"../tepuy_scb_r_chq_caducados.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,43,n);
it=s0.addItemWithImages(3,4,4,"Configuración",n,n,"",9,9,9,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,17,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,84,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Medidas del Cheque",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_conf_voucher.php",n,n,n,"../tepuy_scb_p_conf_voucher.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,2,n);
//it=s1.addItemWithImages(6,7,7,"Formato Nº de Orden",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_config.php",n,n,n,"../tepuy_scb_config.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,3,n);
//it=s1.addItemWithImages(6,7,7,"Configurar Formato Carta Orden",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_conf_cartaorden.php",n,n,n,"../tepuy_scb_p_conf_cartaorden.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,54,n);
//it=s1.addItemWithImages(6,7,7,"Selección Formato Carta Orden",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_conf_select_cartaorden.php",n,n,n,"../tepuy_scb_p_conf_select_cartaorden.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,53,n);
it=s0.addItemWithImages(3,4,4,"Mantenimiento",n,n,"",10,10,10,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,55,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,8,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Movimientos Descuadrados",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scb_p_mant_descuadrados.php",n,n,n,"../tepuy_scb_p_mant_descuadrados.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,56,n);
it=s0.addItemWithImages(3,4,4,"Retornar",n,n,"",11,11,11,3,3,3,n,n,n,"",n,n,n,"../../tepuy_menu.php",n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,4,n);
s0.pm.buildMenu();
}}
