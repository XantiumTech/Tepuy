//----------DHTML Menu Created using AllWebMenus PRO ver 5.3-#848---------------
//C:\wamp\www\Sub_Menus-Tepuy\becas\menu_sno.awm
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
var awmHash='RRCDHTKYGXTKFETYMSJMGGTAWUHE';
var awmNoMenuPrint=1;
var awmUseTrs=0;
var awmSepr=["0","","","","90","#808080","","1"];
var awmMarg=[0,0,0,0];
var YY= 82;//((window.innerHeight/2)-(window.innerHeight/4))-(window.innerHeight/42); //82 
var XX= ((window.innerWidth/2)-408); //110  396 para los menus cortos
//alert("Ancho: " + XX + "Alto: " + YY);
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["becas.png",42,40,"definicion.png",31,22,"main-button-tile.gif",17,34,"indicator.gif",16,8,"main-item-left-float.png",17,24,"main-itemOver-left-float.png",15,24,"procesos.png",31,22,"integrar.png",31,22,"reportes.png",31,22,"ivss.png",31,22,"configuracion.png",31,22,"retorno.png",31,22];
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
//var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,90,82,0,1,2,1,0,1,n,n,100,0,0,90,82,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
//   XX lo que se mueve a lo ancho y YY se desplaza arriba o abajo
var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,XX,YY,0,1,2,1,0,1,n,n,100,0,0,XX,YY,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
it=s0.addItemWithImages(0,1,1,"Becas",n,n,"",0,0,0,3,3,3,n,n,n,"",n,n,n,n,n,0,0,0,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,n,n);
it=s0.addItemWithImages(3,4,4,"Definiciones",n,n,"",1,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,8,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,1,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Nóminas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_becas_d_nominas.php",n,n,n,"../tepuy_becas_d_nominas.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,9,n);
it=s1.addItemWithImages(6,7,7,"Tipos de Becas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_becas_d_tipos.php",n,n,n,"../tepuy_becas_d_tipos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,10,n);
it=s1.addItemWithImages(6,7,7,"Ficha del Personal",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_becas_d_personal.php",n,n,n,"../tepuy_becas_d_personal.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,15,n);
it=s0.addItemWithImages(3,4,4,"Procesos",n,n,"",6,6,6,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,11,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,17,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Seleccionar Nómina de Becas",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,"window.open('../tepuy_snorh_p_seleccionarnomina.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=530,height=180,left=250,top=200,location=no,resizable=no');",n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,188,n);
it=s1.addItemWithImages(6,7,7,"Histórico x Nómina de Becas",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,"window.open('../tepuy_snorh_p_seleccionarhnomina.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=530,height=180,left=250,top=200,location=no,resizable=no');",n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,6,n);
it=s1.addItemWithImages(6,7,7,"Buscar Personas en Becas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_p_buscarpersonal.php",n,n,n,"../tepuy_snorh_p_buscarpersonal.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,29,n);
it=s1.addItemWithImages(6,7,7,"Contabilizar Becas",n,n,"",7,7,7,1,1,1,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,189,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,76,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Contabilizar",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_contabiliza_sno.php",n,n,n,"../tepuy_mis_p_contabiliza_sno.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,191,n);
it=s2.addItemWithImages(6,7,7,"Reversar Contabilización",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_reverso_sno.php",n,n,n,"../tepuy_mis_p_reverso_sno.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,192,n);
it=s0.addItemWithImages(3,4,4,"Reportes",n,n,"",8,8,8,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,14,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,18,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Personas",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,0,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,2,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Listado de Personas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_listadopersonal.php",n,n,n,"../tepuy_snorh_r_listadopersonal.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,38,n);
it=s2.addItemWithImages(6,7,7,"Ficha de la Personas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_fichapersonal.php",n,n,n,"../tepuy_snorh_r_fichapersonal.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,41,n);
it=s2.addItemWithImages(6,7,7,"Conceptos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_conceptos.php",n,n,n,"../tepuy_snorh_r_conceptos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,62,n);
it=s2.addItemWithImages(6,7,7,"Listado al Banco",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_listadobanco.php",n,n,n,"../tepuy_snorh_r_listadobanco.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,64,n);
it=s2.addItemWithImages(6,7,7,"Recibo de Pago",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_recibopago.php",n,n,n,"../tepuy_snorh_r_recibopago.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,65,n);
it=s2.addItemWithImages(6,7,7,"Depósitos al Banco",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_depositobanco.php",n,n,n,"../tepuy_snorh_r_depositobanco.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,67,n);
it=s2.addItemWithImages(6,7,7,"Beneficiario de Becas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_personal_beneficiario.php",n,n,n,"../tepuy_snorh_r_personal_beneficiario.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,73,n);
it=s0.addItemWithImages(3,4,4,"Configuración",n,n,"",10,10,10,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,17,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,84,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Cambio ID Personas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_p_personalcambioid.php",n,n,n,"../tepuy_snorh_p_personalcambioid.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,30,n);
it=s0.addItemWithImages(3,4,4,"Retornar",n,n,"",11,11,11,3,3,3,n,n,n,"",n,n,n,"../../tepuy_menu.php",n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,4,n);
s0.pm.buildMenu();
}}
