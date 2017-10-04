//----------DHTML Menu Created using AllWebMenus PRO ver 5.3-#848---------------
//C:\wamp\www\Sub_Menus-Tepuy\sps\menu_sps.awm
var awmMenuName='menu_sps';
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
var awmHash='CYTAFWWQJXSUMIUWHKYYPMWATOYL';
var awmNoMenuPrint=1;
var awmUseTrs=0;
var awmSepr=["0","","","","90","#808080","","1"];
var awmMarg=[0,0,0,0];
var YY= 82;//((window.innerHeight/2)-(window.innerHeight/4))-(window.innerHeight/42); //82 
var XX= ((window.innerWidth/2)-380); //110  396 para los menus cortos
//alert("Ancho: " + XX + "Alto: " + YY);
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["prestaciones.png",42,40,"definicion.png",31,22,"main-button-tile.gif",17,34,"indicator.gif",16,8,"main-item-left-float.png",17,24,"main-itemOver-left-float.png",15,24,"procesos.png",31,22,"reportes.png",31,22,"retorno.png",31,22];
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
//var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,325,82,0,1,2,1,0,1,n,n,100,0,0,325,82,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
//   XX lo que se mueve a lo ancho y YY se desplaza arriba o abajo
var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,XX,YY,0,1,2,1,0,1,n,n,100,0,0,XX,YY,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
it=s0.addItemWithImages(0,1,1,"Prestaciones Sociales",n,n,"",0,0,0,3,3,3,n,n,n,"",n,n,n,n,n,0,0,0,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,n,n);
it=s0.addItemWithImages(3,4,4,"Definiciones",n,n,"",1,1,1,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,1,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,1,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Artículos",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_def_articulos.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_def_articulos.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,2,n);
it=s1.addItemWithImages(6,7,7,"Causas de Retiro",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_def_causaretiro.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_def_causaretiro.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,3,n);
it=s1.addItemWithImages(6,7,7,"Tasas de Interés",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_def_tasainteres.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_def_tasainteres.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,6,n);
it=s1.addItemWithImages(6,7,7,"Carta de Anticipo",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_def_cartaanticipo.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_def_cartaanticipo.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,8,n);
it=s1.addItemWithImages(6,7,7,"Registrar Beneficiario",n,n,"",n,n,n,3,3,3,n,n,n,"../html/tepuy_rpc_d_beneficiario.php",n,n,n,"../html/tepuy_rpc_d_beneficiario.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,16,n);
it=s0.addItemWithImages(3,4,4,"Procesos",n,n,"",6,6,6,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,11,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,17,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Registro de Sueldos",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_sueldos.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_sueldos.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,188,n);
it=s1.addItemWithImages(6,7,7,"Registro de Deuda Anterior",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_deudaanterior.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_deudaanterior.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,5,n);
it=s1.addItemWithImages(6,7,7,"Anticipos",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,19,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,2,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Solciitud de Anticipo",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_anticipos.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_anticipos.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,9,n);
it=s2.addItemWithImages(6,7,7,"Aprobación de Anticipo",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_anticipos.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_anticipos.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,10,n);
it=s1.addItemWithImages(6,7,7,"Antigüedad",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,29,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,3,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Cálculo de Antigüedad",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_antiguedad.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_antiguedad.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,12,n);
it=s2.addItemWithImages(6,7,7,"Antigüedad por Nómina",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_antg_nomina.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_antg_nomina.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,13,n);
it=s1.addItemWithImages(6,7,7,"Liquidación",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,7,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,4,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Liquidación",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_liquidaciones.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_liquidaciones.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,15,n);
it=s2.addItemWithImages(6,7,7,"Aprobación de Liquidación",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_aprobacionliquidacion.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_pro_aprobacionliquidacion.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,17,n);
it=s0.addItemWithImages(3,4,4,"Reportes",n,n,"",7,7,7,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,14,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,18,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Detalle de Antigüedad",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_rep_detalle_antiguedad.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_rep_detalle_antiguedad.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,0,n);
it=s1.addItemWithImages(6,7,7,"Liquidación de Prestaciones Sociales",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_rep_liquidacion.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_rep_liquidacion.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,25,n);
it=s1.addItemWithImages(6,7,7,"Anticipo de Prestaciones Sociales",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_rep_anicipo.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_rep_anicipo.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,26,n);
it=s1.addItemWithImages(6,7,7,"Histórico de Sueldos",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_rep_detalle_sueldos.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_rep_detalle_sueldos.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,18,n);
it=s1.addItemWithImages(6,7,7,"Deuda por Prestación Social",n,n,"",n,n,n,3,3,3,n,n,n,"../php/sps_seguridad.php?pagina=sps_rep_deuda_ps.html.php",n,n,n,"../php/sps_seguridad.php?pagina=sps_rep_deuda_ps.html.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,20,n);
it=s1.addItemWithImages(6,7,7,"Listado de Beneficiarios",n,n,"",n,n,n,3,3,3,n,n,n,"../../html/tepuy_rpc_r_beneficiario.php",n,n,n,"../../html/tepuy_rpc_r_beneficiario.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,27,n);
it=s0.addItemWithImages(3,4,4,"Retornar",n,n,"",8,8,8,3,3,3,n,n,n,"",n,n,n,"../../../tepuy_menu.php",n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,4,n);
s0.pm.buildMenu();
}}
