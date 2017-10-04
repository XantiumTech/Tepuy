//----------DHTML Menu Created using AllWebMenus PRO ver 5.3-#848---------------
//C:\wamp\www\Sub_Menus-Tepuy-Alcaldia\Tepuy-presupuesto\menu_presupuesto.awm
var awmMenuName='menu_ayudas';
var awmLibraryBuild=848;
var awmLibraryPath='/menu_ayudas';
var awmImagesPath='/menu_ayudas';
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
var awmHash='WTCBMXJMKQTKFETYMSJMGGTAWUHE';
var awmNoMenuPrint=1;
var awmUseTrs=0;
var awmSepr=["0","","",""];
var awmMarg=[0,0,0,0];
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["sistema_tepuy.png",42,40,"menu-tile.png",20,250,"ingresos.png",42,40,"v5_bullets_26.gif",10,10,"item-tile.png",20,26,"tepuy.png",320,392,"gastos.png",42,40,"sep.png",42,40,"compra.png",25,23,"obras.png",42,40,"viaticos.png",42,40,"password.png",42,40,"salir.png",42,40];
awmCreateCSS(1,2,1,'#000000',n,n,'bold 12px Verdana, Arial, Helvetica, sans-serif',n,'none','0','#000000','0px 10px 0px 10',0);
awmCreateCSS(0,2,1,'#000000',n,n,'bold 12px Verdana, Arial, Helvetica, sans-serif',n,'none','0','#000000','0px 10px 0px 10',0);
awmCreateCSS(0,1,0,n,'#B2A696',1,n,n,'solid','2','#C0B3A7',0,0);
awmCreateCSS(1,2,0,'#000000',n,4,'13px Verdana, Arial, Helvetica, sans-serif',n,'solid','1','#F4F0EB','5px 20px 5px 20',1);
awmCreateCSS(0,2,0,'#1803FB',n,n,'bold 13px Verdana, Arial, Helvetica, sans-serif',n,'solid','1','#C7BFB4','5px 20px 5px 20',1); /* 1803FB Azul FF0000 Rojo*/
awmCF(5,0,0,0,0);
var s0=awmCreateMenu(0,0,0,0,1,0,0,0,0,10,10,0,0,2,6,1,0,n,n,100,0,0,10,10,0,-1,1,200,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,1,0,0,0,0,0,0,0,1);
it=s0.addItemWithImages(0,1,1,"Tepuy - Menú Ayudas",n,n,"",0,0,0,3,3,3,n,n,n,"",n,n,n,n,n,0,0,0,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,n,n);
it=s0.addItemWithImages(3,4,4,"Control de Ayudas",n,n,"",7,7,7,3,3,3,n,n,n,"",n,n,n,"sep/tepuywindow_blank_ayuda.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,3,n);
it=s0.addItemWithImages(3,4,4,"Cambiar Password de Usuario",n,n,"",11,11,11,3,3,3,n,n,n,"",n,n,"window.open('sss/tepuy_sss_p_repassword.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=530,height=230,left=250,top=200,location=no,resizable=no');",n,n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,2,n);
it=s0.addItemWithImages(3,4,4,"Salir del Sistema",n,n,"",12,12,12,3,3,3,n,n,n,"tepuy_conexion.php",n,n,n,"tepuy_conexion.php",n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,5,n);
s0.pm.buildMenu();
}}
