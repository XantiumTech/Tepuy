// JavaScript Document

var url="../../php/tepuy_srh_a_asignar_concurso.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


function ue_cancelar()
{
     ue_nuevo();
    scrollTo(0,0);

}




function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroreg","txtfecha", "txtcodcon", "txtobs");
  
 
  var la_mensajes=new Array ("el número de registro","la fecha de asignacion", "el codigo del concurso", "la observación");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img; 
	  function onGuardar(respuesta)
	  {
	    alert(respuesta.responseText); 
	    ue_cancelar();
	   	
	  }
	
	  //Arreglo 
	  var personal = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	 g=2;
	 total=0;
	 for (f=1; f<(filas.length - 2); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
		var registro = 
		{
		  "nroreg"      : $F('txtnroreg'),
		  "codper" 		: columnas[0].value,
		  "carper"      : columnas[2].value,
		  "depto"   	: columnas[3].value,
		  "tipo"        : 'I'
		}
		g++;
		personal[f-1] = registro;
	  }
	  //Arreglo  Personal Externo
	  var personalext = new Array();
	  var filas = $('grid2').getElementsByTagName("tr");
	 g=2;
	 total=0;
	 for (f=1; f<(filas.length - 2); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
		var registro = 
		{
		  "nroreg"      : $F('txtnroreg'),
		  "codper" 		: columnas[0].value,
		  "tipo"        : 'E'
		}
		g++;
		personalext[f-1] = registro;
	  }
	  var solicitud = 
	  {
		"nroreg"      : $F('txtnroreg'),
		"fecha"       : $F('txtfecha'),
	    "codcon"      : $F('txtcodcon'),
		"obs"         : $F('txtobs'),
		"personal"	  : personal,
		"personalext" : personalext
		};
	
	
	  var objeto = JSON.stringify(solicitud);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtnroreg");
  var la_mensajes=new Array ("el número de registro. Seleccione una Asignacion de Personal Catálogo");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{
	  divResultado = document.getElementById('mostrar');
	  divResultado.innerHTML= '';
	  function onEliminar(respuesta)
	  {
		ue_cancelar();
		alert(respuesta.responseText);
	  }
	  
	  params = "operacion=ue_eliminar&nroreg="+$F('txtnroreg');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	  ue_cancelar();
	  alert("Eliminación Cancelada !!!");	  
	}
  }
}



function ue_nuevo()
{
  f=document.form1;
  f.operacion.value="NUEVO";
  f.existe.value="FALSE";		
  f.action="tepuy_srh_p_asignar_concurso.php";
  f.submit();
}

function ue_nuevo_codigo()
{
  function onNuevo(respuesta)
  {
	if ($('txtnroreg').value=="") {
	
	$('txtnroreg').value  = trim(respuesta.responseText);
	$('txtfecha').focus();
	}
  }	

  params = "operacion=ue_nuevo_codigo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}




function catalogo_concurso()
{
      f= document.form1;
     
     if(f.hidstatus.value=='C')
  {
   pagina="../catalogos/tepuy_srh_cat_concurso.php?valor=1";
  
  }else
  {
   pagina="../catalogos/tepuy_srh_cat_concurso.php?valor=0";
  } 
  window.open(pagina,"catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}


function catalogo_personal()
{
      f= document.form1;

	if (f.txtcodtipconcur.value=="") {
		alert ('Debe llenar los datos del registro de concurso.');
	}
	else {
		variable1=f.txtcodtipconcur.value;	
		if ((variable1.search('externo')!=-1)||(variable1.search('Externo')!=-1)||(variable1.search('EXTERNO')!=-1)||(variable1.search('externos')!=-1)||(variable1.search('Externos')!=-1)||(variable1.search('Externos')!=-1)){
			alert ('No puede agregar personal interno a la organización. El concurso es solamente para personal externo.');
			}
		else {
		  if(f.hidstatus.value=='C')
		  {
		   pagina="../catalogos/tepuy_srh_cat_personal.php?valor_cat=0"+"&tipo=5";
		  
		  }else
		  {
		   pagina="../catalogos/tepuy_srh_cat_personal.php?valor_cat=1"+"&tipo=5";
		  } 
		  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
		  
		}
	}
  }
  
  
 function catalogo_solicitud_empleo()
{
      f= document.form1;

 if (f.txtcodtipconcur.value=="") {
		alert ('Debe llenar los datos del registro de concurso.');
	}
	else {
	variable=f.txtcodtipconcur.value;	
		if ((variable.search('interno')!=-1)||(variable.search('Interno')!=-1)||(variable.search('INTERNO')!=-1)||(variable.search('internos')!=-1)||(variable.search('Internos')!=-1)||(variable.search('INTERNOS')!=-1)) {
			alert ('No puede agregar personal externo a la organización. El concurso es solamente para personal interno.');
			}
		else {
		  if(f.hidstatus.value=='C')
		  {
		   pagina="../catalogos/tepuy_srh_cat_solicitud_empleo.php?valor_cat=0";
		  
		  }else
		  {
		   pagina="../catalogos/tepuy_srh_cat_solicitud_empleo.php?valor_cat=1";
		  } 
		  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
		  
		}
	}
  }

function ue_buscar()
{
	f=document.form1;
	window.open("../catalogos/tepuy_srh_cat_asignar_concurso.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=545,height=430,left=50,top=50,location=no,resizable=yes");
	}




function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	if(li_total==li_row)
	{
		li_codcamnew=eval("f.txtcodper"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			li_codcam=eval("f.txtcodper"+li_i+".value");
			if((li_codcam==li_codcamnew)&&(li_i!=li_row))
			{
				alert("El personal ya fue agregado. Seleccione Otro.");
				lb_valido=true;
			}
		}
		
		ls_nroreg=ue_validarvacio(f.txtnroreg.value);
				
		ls_codcam=eval("f.txtcodper"+li_row+".value");
		ls_codcam=ue_validarvacio(ls_codcam);
		ls_dencam=eval("f.txtnomper"+li_row+".value");
		ls_dencam=ue_validarvacio(ls_dencam);
		ls_carper=eval("f.txtcarper"+li_row+".value");
		ls_carper=ue_validarvacio(ls_carper);
		ls_dep=eval("f.txtdep"+li_row+".value");
		ls_dep=ue_validarvacio(ls_dep);
		if((ls_nroreg=="")||(ls_codcam==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGAR_PERSONA_INTERNA";
			f.action="tepuy_srh_p_asignar_concurso.php";
			f.submit();
		}
	}
}

function uf_delete_dt(li_row)
{
	f=document.form1;
	li_total=f.totalfilas.value;
	if(li_total>li_row)
	{
		li_codcam=eval("f.txtcodper"+li_row+".value");
		li_codcam=ue_validarvacio(li_codcam);
		if(li_codcam=="")
		{
			alert("la fila a eliminar no debe estar vacio el código de personal");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINAR_PERSONA_INTERNA"
				f.action="tepuy_srh_p_asignar_concurso.php";
				f.submit();
			}
		}
	}
}



function uf_agregar_dt2(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas2.value;
	if(li_total==li_row)
	{
		li_codcamnew=eval("f.txtcedper"+li_row+".value");
		li_total=f.totalfilas2.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			li_codcam=eval("f.txtcedper"+li_i+".value");
			if((li_codcam==li_codcamnew)&&(li_i!=li_row))
			{
				alert("El personal ya fue agregado. Seleccione Otro.");
				lb_valido=true;
			}
		}
		
		ls_nroreg=ue_validarvacio(f.txtnroreg.value);
				
		ls_codcam=eval("f.txtcedper"+li_row+".value");
		ls_codcam=ue_validarvacio(ls_codcam);
		ls_dencam=eval("f.txtnomsol"+li_row+".value");
		ls_dencam=ue_validarvacio(ls_dencam);
		
		if((ls_nroreg=="")||(ls_codcam==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGAR_PERSONA_EXTERNA";
			f.action="tepuy_srh_p_asignar_concurso.php";
			f.submit();
		}
	}
}

function uf_delete_dt2(li_row)
{
	f=document.form1;
	li_total=f.totalfilas2.value;
	if(li_total>li_row)
	{
		li_codcam=eval("f.txtcedper"+li_row+".value");
		li_codcam=ue_validarvacio(li_codcam);
		if(li_codcam=="")
		{
			alert("la fila a eliminar no debe estar la cédula de personal");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINAR_PERSONA_EXTERNA"
				f.action="tepuy_srh_p_asignar_concurso.php";
				f.submit();
			}
		}
	}
}



function ue_cerrar()
{
	window.location.href="tepuywindow_blank.php";
}


//FUNCIONES PARA EL CALENDARIO

// Esta es la funcion que detecta cuando el usuario hace click en el calendario, necesaria
function selected(cal, date) {
  cal.sel.value = date; // just update the date in the input field.
                           
  if (cal.dateClicked )
      cal.callCloseHandler();
}


function closeHandler(cal) {
  cal.hide();                        // hide the calendar

  _dynarch_popupCalendar = null;
}


function showCalendar(id, format, showsTime, showsOtherMonths) {
  var el = document.getElementById(id);
  if (_dynarch_popupCalendar != null) {
    // we already have some calendar created
    _dynarch_popupCalendar.hide();                 // so we hide it first.
  } else {
    // first-time call, create the calendar.

    var cal = new Calendar(1, null, selected, closeHandler);
    if (typeof showsTime == "string") {
      cal.showsTime = true;
      cal.time24 = (showsTime == "24");
    }
    if (showsOtherMonths) {
      cal.showsOtherMonths = true;
    }
    _dynarch_popupCalendar = cal;                  // remember it in the global var
    cal.setRange(1900, 2070);        // min/max year allowed.
    cal.create();
  }
  _dynarch_popupCalendar.setDateFormat(format);    // set the specified date format
  _dynarch_popupCalendar.parseDate(el.value);      // try to parse the text in field
  _dynarch_popupCalendar.sel = el;                 // inform it what input field we use
 _dynarch_popupCalendar.showAtElement(el, "T");        // show the calendar

  return false;
}

