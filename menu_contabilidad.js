//----------DHTML Menu Created using AllWebMenus PRO ver 5.3-#848---------------
//C:\wamp\www\Sub_Menus-Tepuy-Alcaldia\Tepuy_contabilidad\menu_contabilidad.awm
var awmMenuName='menu_contabilidad';
var awmLibraryBuild=848;
var awmLibraryPath='/menu_contabilidad';
var awmImagesPath='/menu_contabilidad';
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
var awmHash='LQQPSDHKZGXWDOPGGYBQWIHAISRC';
var awmNoMenuPrint=1;
var awmUseTrs=0;
var awmSepr=["0","","",""];
var awmMarg=[0,0,0,0];
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["sistema_tepuy.png",42,40,"menu-tile.png",20,250,"ingresos.png",42,40,"v5_bullets_26.gif",10,10,"item-tile.png",20,26,"tepuy.png",320,392,"gastos.png",42,40,"contabilidad.png",42,40,"compras.png",42,40,"viaticos.png",42,40,"obras.png",42,40,"orden_p.png",42,40,"caja_banco.png",42,40,"inventario.png",42,40,"activos.png",42,40,"password.png",42,40,"salir.png",42,40];
awmCreateCSS(1,2,1,'#000000',n,n,'bold 12px Verdana, Arial, Helvetica, sans-serif',n,'none','0','#000000','0px 10px 0px 10',0);
awmCreateCSS(0,2,1,'#000000',n,n,'bold 12px Verdana, Arial, Helvetica, sans-serif',n,'none','0','#000000','0px 10px 0px 10',0);
awmCreateCSS(0,1,0,n,'#B2A696',1,n,n,'solid','2','#C0B3A7',0,0);
awmCreateCSS(1,2,0,'#000000',n,4,'13px Verdana, Arial, Helvetica, sans-serif',n,'solid','1','#F4F0EB','5px 20px 5px 20',1);
awmCreateCSS(0,2,0,'#FF0000',n,n,'bold 13px Verdana, Arial, Helvetica, sans-serif',n,'solid','1','#C7BFB4','5px 20px 5px 20',1);
awmCreateCSS(1,2,0,'#000000',n,4,'14px Verdana, Arial, Helvetica, sans-serif',n,'solid','1','#F4F0EB','5px 20px 5px 20',1);
awmCreateCSS(0,2,0,'#1803FB',n,n,'bold 14px Verdana, Arial, Helvetica, sans-serif',n,'solid','1','#C7BFB4','5px 20px 5px 20',1); /* 1803FB Azul FF0000 Rojo*/
awmCF(5,0,0,0,0);
var s0=awmCreateMenu(0,0,0,0,1,0,0,0,0,10,10,0,0,2,6,1,0,n,n,100,0,0,10,10,0,-1,1,200,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,1,0,0,0,0,0,0,0,1);
it=s0.addItemWithImages(0,1,1,"Sistema Administrativo Integrado Tepuy",n,n,"",0,0,0,3,3,3,n,n,n,"",n,n,n,n,n,0,0,0,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,n,n);
it=s0.addItemWithImages(3,4,4,"Contabilidad Presupuestaria de Ingresos",n,n,"",2,2,2,3,3,3,n,n,n,"",n,n,n,"spi/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,0,0,0,0,0,0,0,n);
it=s0.addItemWithImages(3,4,4,"Contabilidad Presupuestaria de Gastos",n,n,"",6,6,6,3,3,3,n,n,n,"",n,n,n,"spg/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,1,n);
it=s0.addItemWithImages(5,6,6,"Contabilidad Fiscal",n,n,"",7,7,7,3,3,3,n,n,n,"",n,n,n,"scf/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,2,n);
it=s0.addItemWithImages(3,4,4,"Ordenes de Compra",n,n,"",8,8,8,3,3,3,n,n,n,"",n,n,n,"soc/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,9,n);
it=s0.addItemWithImages(3,4,4,"Control de Viáticos",n,n,"",9,9,9,3,3,3,n,n,n,"scv/tepuywindow_blank.php",n,n,n,"scv/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,4,n);
it=s0.addItemWithImages(3,4,4,"Control de Obras",n,n,"",10,10,10,3,3,3,n,n,n,"",n,n,n,"sob/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,10,n);
it=s0.addItemWithImages(3,4,4,"Ordenes de Pago",n,n,"",11,11,11,3,3,3,n,n,n,"cxp/tepuywindow_blank.php",n,n,n,"cxp/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,7,n);
it=s0.addItemWithImages(3,4,4,"Caja y Banco",n,n,"",12,12,12,3,3,3,n,n,n,"scb/tepuywindow_blank.php",n,n,n,"scb/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,8,n);
it=s0.addItemWithImages(3,4,4,"Inventario",n,n,"",13,13,13,3,3,3,n,n,n,"siv/tepuywindow_blank.php",n,n,n,"siv/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,11,n);
it=s0.addItemWithImages(3,4,4,"Activos Fijos",n,n,"",14,14,14,3,3,3,n,n,n,"saf/tepuywindow_blank.php",n,n,n,"saf/tepuywindow_blank.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,12,n);
it=s0.addItemWithImages(3,4,4,"Cambiar Password de Usuario",n,n,"",15,15,15,3,3,3,n,n,n,"",n,n,"window.open('sss/tepuy_sss_p_repassword.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=530,height=180,left=250,top=200,location=no,resizable=no');",n,n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,14,n);
it=s0.addItemWithImages(3,4,4,"Salir del Sistema",n,n,"",16,16,16,3,3,3,n,n,n,"tepuy_conexion.php",n,n,n,"tepuy_conexion.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,15,n);
s0.pm.buildMenu();
}}
