//----------DHTML Menu Created using AllWebMenus PRO ver 5.3-#848---------------
//C:\wamp\www\Sub_Menus-Tepuy-Concejo\Tepuy-administrador\menu_tepuy.awm
var awmMenuName='menu';
var awmLibraryBuild=848;
var awmLibraryPath='/awmdata';
var awmImagesPath='/awmdata/menu';
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
var awmHash='EQINQOUCKONSVCZMIWVGIQLAEKFU';
var awmNoMenuPrint=1;
var awmUseTrs=0;
var awmSepr=["0","","","","90","#808080","","3"];
var awmMarg=[0,0,0,0];
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["sistema_tepuy.png",42,40,"menu-tile.png",20,250,"tepuy_pie.png",42,40,"ingresos.png",42,40,"v5_bullets_26.gif",10,10,"item-tile.png",20,26,"tepuy.png",320,392,"gastos.png",25,23,"contabilidad.png",25,23,"sep.png",25,23,"compra.png",25,23,"obras.png",25,23,"viatico.png",25,23,"nomina.png",25,23,"ventas.png",25,23,"orden_pago.png",25,23,"caja_banco.png",25,23,"inventario.png",25,23,"activos.png",25,23,"seguridad.png",25,23,"mantenimiento.png",25,23,"apertura.png",25,23,"salir.png",42,40];
awmCreateCSS(1,2,1,'#000000',n,n,'bold 12px Verdana, Arial, Helvetica, sans-serif',n,'none','0','#000000','0px 10px 0px 10',0);
awmCreateCSS(0,2,1,'#000000',n,n,'bold 12px Verdana, Arial, Helvetica, sans-serif',n,'none','0','#000000','0px 10px 0px 10',0);
awmCreateCSS(1,2,1,'#000000',n,n,'14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,2,1,'#000000',n,n,'14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,1,0,n,'#B2A696',1,n,n,'solid','2','#C0B3A7',0,0);
awmCreateCSS(1,2,0,'#000000',n,5,'13px Verdana, Arial, Helvetica, sans-serif',n,'solid','1','#F4F0EB','5px 20px 5px 20',1);
awmCreateCSS(0,2,0,'#1803FB',n,n,'bold 13px Verdana, Arial, Helvetica, sans-serif',n,'solid','1','#C7BFB4','5px 20px 5px 20',1); /* 1803FB Azul FF0000 Rojo*/
awmCF(6,0,0,0,0);
var s0=awmCreateMenu(0,0,0,0,1,0,0,0,0,10,10,0,0,4,6,1,0,n,n,100,0,0,10,10,0,-1,1,200,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,1,0,0,0,0,0,0,0,1);
it=s0.addItemWithImages(0,1,1,"Sistema Administrativo Integrado Tepuy",n,n,"",0,0,0,3,3,3,n,n,n,"",n,n,n,n,n,0,0,0,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,n,n);
it=s0.addItemWithImages(5,6,6,"Contabilidad Presupuestaria de Ingresos",n,n,"",3,3,3,3,3,3,n,n,n,"",n,n,n,"spi/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,0,0,0,0,0,0,0,n);
it=s0.addItemWithImages(5,6,6,"Contabilidad Presupuestaria de Gastos",n,n,"",7,7,7,3,3,3,n,n,n,"",n,n,n,"spg/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,1,n);
it=s0.addItemWithImages(5,6,6,"Contabilidad",n,n,"",8,8,8,3,3,3,n,n,n,"",n,n,n,"scg/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,2,n);
it=s0.addItemWithImages(5,6,6,"Solicitud de Ejecución Presupuestaria",n,n,"",9,9,9,3,3,3,n,n,n,"",n,n,n,"sep/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,3,n);
it=s0.addItemWithImages(5,6,6,"Sistema Control de Ayudas",n,n,"",9,9,9,3,3,3,n,n,n,"",n,n,n,"sep/tepuywindow_blank_ayuda.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,3,n);
it=s0.addItemWithImages(5,6,6,"Ordenes de Compra",n,n,"",10,10,10,3,3,3,n,n,n,"",n,n,n,"soc/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,9,n);
it=s0.addItemWithImages(5,6,6,"Control de Obras",n,n,"",11,11,11,3,3,3,n,n,n,"",n,n,n,"sob/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,10,n);
it=s0.addItemWithImages(5,6,6,"Control de Viáticos",n,n,"",12,12,12,3,3,3,n,n,n,"scv/tepuywindow_blank.php",n,n,n,"scv/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,4,n);
it=s0.addItemWithImages(5,6,6,"Nóminas",n,n,"",13,13,13,3,3,3,n,n,n,"sno/tepuywindow_blank.php",n,n,n,"sno/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,5,n);
it=s0.addItemWithImages(5,6,6,"Facturación",n,n,"",14,14,14,3,3,3,n,n,n,"sfa/tepuywindow_blank.php",n,n,n,"sfa/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,6,n);
it=s0.addItemWithImages(5,6,6,"Ordenes de Pago",n,n,"",15,15,15,3,3,3,n,n,n,"cxp/tepuywindow_blank.php",n,n,n,"cxp/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,7,n);
it=s0.addItemWithImages(5,6,6,"Caja y Banco",n,n,"",16,16,16,3,3,3,n,n,n,"scb/tepuywindow_blank.php",n,n,n,"scb/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,8,n);
it=s0.addItemWithImages(5,6,6,"Inventario",n,n,"",17,17,17,3,3,3,n,n,n,"siv/tepuywindow_blank.php",n,n,n,"siv/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,11,n);
it=s0.addItemWithImages(5,6,6,"Activos Fijos",n,n,"",18,18,18,3,3,3,n,n,n,"saf/tepuywindow_blank.php",n,n,n,"saf/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,12,n);
it=s0.addItemWithImages(5,6,6,"Seguridad",n,n,"",19,19,19,3,3,3,n,n,n,"sss/tepuywindow_blank.php",n,n,n,"sss/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,13,n);
it=s0.addItemWithImages(5,6,6,"Mantenimiento",n,n,"",20,20,20,3,3,3,n,n,n,"ins/tepuywindow_blank.php",n,n,n,"ins/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,14,n);
it=s0.addItemWithImages(5,6,6,"Apertura",n,n,"",21,21,21,3,3,3,n,n,n,"backres/tepuy_backres_principal.html",n,n,n,"backres/tepuy_backres_principal.html",n,0,0,2,n,n,n,n,n,n,0,0,0,1,0,n,n,n,0,0,0,15,n);
it=s0.addItemWithImages(5,6,6,"Salir",n,n,"",22,22,22,3,3,3,n,n,n,"tepuy_conexion.php",n,n,n,"tepuy_conexion.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,16,n);
it=s0.addItemWithImages(2,3,3,"Sistema Administrativo Tepuy",n,n,"",2,2,2,1,1,1,n,n,n,"",n,n,n,n,n,0,0,0,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,n,n);
s0.pm.buildMenu();
}}
