//--------------------------------------------------------
//	Funci�n que valida que no se incluyan comillas simples 
//	en los textos ya que da�ana la consulta SQL
//--------------------------------------------------------
function ue_validarcomillas(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto != "'")&&(texto != '"')&&(texto != "\\"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}

//--------------------------------------------------------
//	Funci�n que valida que solo se incluyan n�meros en los textos
//--------------------------------------------------------
function ue_validarnumero(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}

//--------------------------------------------------------
//	Funci�n que valida que el texto no est� vacio
//--------------------------------------------------------
function ue_validarvacio(valor)
{
	var texto;
	while(''+valor.charAt(0)==' ')
	{
		valor=valor.substring(1,valor.length)
	}
	texto = valor;
	return texto;
}

//--------------------------------------------------------
//	Funci�n que rellena un campo con ceros a la izquierda
//--------------------------------------------------------
function ue_rellenarcampo(valor,maxlon)
{
	var total;
	var auxiliar;
	var longitud;
	var index;
	
	total=0;
    auxiliar=valor.value;
	longitud=valor.value.length;
	total=maxlon-longitud;
	if (total < maxlon)
	{
		for (index=0;index<total;index++)
		{
		   auxiliar="0"+auxiliar;      
		}
		valor.value = auxiliar;
	}
}

//--------------------------------------------------------
//	Funci�n que formatea un n�mero
//--------------------------------------------------------
function ue_formatonumero(fld, milSep, decSep, e)
{ 
	var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
	
	if (fld.readOnly==true) return false; 
	if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Return
	if (whichCode == 127) return true; // Suprimir
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
    	if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
    	if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
     	fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
    return false; 
}

//--------------------------------------------------------
//	Funci�n que verifica que la fecha  no tenga letras
//--------------------------------------------------------
function ue_validarfecha(valor)
{
	var texto;
	if ((valor=="dd/mm/aaaa")||(valor=="")||(valor=="01/01/1900"))
	{
		texto="1900-01-01";
	}
	else
	{
		texto = valor;
	}
	return texto;
}

//--------------------------------------------------------
//	Funci�n que valida que solo se incluyan n�meros(1234567890),guiones(-) y Espacios en blanco
//--------------------------------------------------------
function ue_validartelefono(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||
		   (texto=="8")||(texto=="9")||(texto=="-")||(texto==" ")||(texto=="(")||(texto==")"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}

//--------------------------------------------------------
//	Funci�n que le da formato a la fecha
//--------------------------------------------------------
function ue_formatofecha(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++)
		{
			val2 += val[r]	
		}
		if(nums)
		{
			for(z=0;z<val2.length;z++)
			{
				if(isNaN(val2.charAt(z)))
				{
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++)
		{
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++)
		{
			if(q ==0)
			{
				val = val3[q]
			}
			else
			{
				if(val3[q] != "")
				{
					val += sep + val3[q]
				}
			}
		}
		d.value = val
		d.valant = val
	}
}
//--------------------------------------------------------
//	Funci�n que valida el correo electr�nico
//--------------------------------------------------------
function ue_validarcorreo(obj)
{
	//expresion regular
    var filtro=/^[A-Za-z][A-Za-z0-9_]*@[A-Za-z0-9_]+\.[A-Za-z0-9_.]+[A-za-z]$/;
	if (obj.length==0)
	{
		return true;
	}
	else
	{
		if(filtro.test(obj)==false)
		{
			alert("Email No V�lido.");
			return false;
		}
		else
		{
			return true;
		}
	}
}

//--------------------------------------------------------
//	Funci�n que verifica que la fecha 2 sea mayor que la fecha 1
//--------------------------------------------------------
function ue_comparar_fechas(fecha1,fecha2)
{
	vali=false;
	dia1 = fecha1.substr(0,2);
	mes1 = fecha1.substr(3,2);
	ano1 = fecha1.substr(6,4);
	dia2 = fecha2.substr(0,2);
	mes2 = fecha2.substr(3,2);
	ano2 = fecha2.substr(6,4);
	if (ano1 < ano2)
	{
		vali = true; 
	}
    else 
	{ 
    	if (ano1 == ano2)
	 	{ 
      		if (mes1 < mes2)
	  		{
	   			vali = true; 
	  		}
      		else 
	  		{ 
       			if (mes1 == mes2)
	   			{
 					if (dia1 <= dia2)
					{
		 				vali = true; 
					}
	   			}
      		} 
     	} 	
	}
	return vali;
}

//--------------------------------------------------------
//	Funci�n que Instancia el objeto ajax
//--------------------------------------------------------
function objetoAjax()
{
	var xmlhttp=false;
	try
	{
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E)
		{
			xmlhttp = false;
  		}
	}
	if(!xmlhttp && typeof XMLHttpRequest!='undefined')
	{
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

//--------------------------------------------------------
//	Funci�n que llena unos objeto ocultos para el orden de las consultas de los cat�logos
//--------------------------------------------------------
function ue_orden(campo)
{
	f=document.formulario;
	if(f.campoorden.value==campo)
	{
		if(f.orden.value=="ASC")
		{
			f.orden.value="DESC";
		}
		else
		{
			f.orden.value="ASC";
		}
	}
	else
	{
		f.campoorden.value=campo;
		f.orden.value="ASC";
	}
	ue_search();
}

//--------------------------------------------------------
//	Funci�n que convierte los montos para poder hacer calculos
//--------------------------------------------------------
function ue_formato_calculo(monto)
{
	while(monto.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monto=monto.replace(".","");
	}
	monto=monto.replace(",",".");	
	return monto;
}


function ue_formato_numero(monto)
{
	while(monto.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monto=monto.replace(".",",");
	}
	return monto;
}

//--------------------------------------------------------
//	Funci�n que actualiza el total de las filas de un campo local
//--------------------------------------------------------
function ue_calcular_total_fila_local(campo)
{
	existe=true;
	li_i=1;
	while(existe)
	{
		existe=document.getElementById(campo+li_i);
		if(existe!=null)
		{
			li_i=li_i+1;
		}
		else
		{
			existe=false;
			li_i=li_i-1;
		}
	}
	return li_i
}

//--------------------------------------------------------
//	Funci�n que actualiza el total de las filas de un campo de un pagina opener
//--------------------------------------------------------
function ue_calcular_total_fila_opener(campo)
{
	existe=true;
	li_i=1;
	while(existe)
	{
		existe=opener.document.getElementById(campo+li_i);
		if(existe!=null)
		{
			li_i=li_i+1;
		}
		else
		{
			existe=false;
			li_i=li_i-1;
		}
	}
	return li_i
}

//--------------------------------------------------------
//	Funci�n que verifica si la tecla es enter y llama al metodo ue_search de los cat�logos
//--------------------------------------------------------
function ue_mostrar(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (keycode == 13)
	{
		ue_search();
		return false;
	}
	else
		return true
}

//--------------------------------------------------------
//	Funci�n que verifica si un campo esta vacio y de ser asi lo mando a ese campo
//--------------------------------------------------------
function ue_validarcampo(campo,mensaje,foco)
{
	valido=true;
	if(campo=="")
	{
		alert(mensaje);
		foco.focus();
		valido=false;
	}
	return valido;
}

function redondear(cantidad, decimales) 
{
	var cantidad = parseFloat(cantidad);
	var decimales = parseFloat(decimales);
	decimales = (!decimales ? 2 : decimales);
    primera=Math.round(cantidad * Math.pow(10, decimales+1)) / Math.pow(10, decimales+1);
    return Math.round(primera * Math.pow(10, decimales)) / Math.pow(10, decimales);
}
