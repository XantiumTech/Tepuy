<?php
class tepuy_sno_c_cierre_periodo2
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_sno;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_sno_c_cierre_periodo2()
	{	
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_sno_c_cierre_periodo2
		//		   Access: public (tepuy_sno_c_cierre_periodo)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�n: 15/02/2016 								
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
   		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();				
		require_once("tepuy_sno.php");
        $this->io_sno=new tepuy_sno();		        
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
	}// end function tepuy_sno_c_cierre_periodo2
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hsalida($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hsalida 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hsalida para proceder al cierre del mismo
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�n: 15/02/2016 								
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hsalida ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hsalida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hsalida
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hprenomina($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hprenomina 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hsalida para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 20/02/2016        
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hprenomina ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hprenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hprenomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hresumen($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hresumen 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hresumen para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 20/02/2016        
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hresumen ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hresumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hresumen
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hprestamoperiodo($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hprestamoperiodo 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hprestamoperiodo para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 20/02/2016        
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hprestamosperiodo ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hprestamoperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hprestamoperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hprestamoamortizado($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hprestamoperiodo 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hprestamosamortizado para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 20/02/2016        
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 01/12/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hprestamosamortizado ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hprestamoamortizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hprestamoperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hprestamos($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hprestamos 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica uf_delete_hprestamos para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 20/02/2016        
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hprestamos ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hprestamos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hprestamos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_htipoprestamo($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_htipoprestamo 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hprestamo para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 20/02/2016        
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_htipoprestamo ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_htipoprestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_htipoprestamo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hconstantepersonal($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hconstantepersonal 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hconstantepersonal para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 20/02/2016        
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hconstantepersonal ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hconstantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hconstantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hconstante($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hconstante 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hconstante para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 20/02/2016        
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hconstante ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hconstante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hconstante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hconceptovacacion($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hconceptovacacion 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hconceptovacacion para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 20/02/2016        
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hconceptovacacion ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hconceptovacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hconceptovacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hconceptopersonal($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hconceptopersonal 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hconceptopersonal para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 20/02/2016        
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hconceptopersonal ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hconceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hconceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hprimaconcepto($adt_anocurnom,$as_codperi)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hprimaconcepto 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hprimaconcepto para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 20/02/2016   
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hprimaconcepto ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hprimaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hprimaconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hconcepto($adt_anocurnom,$as_codperi)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hconcepto 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hconcepto para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 20/02/2016   
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hconcepto ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hvacacpersonal($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hvacacpersonal 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hvacacpersonal para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 20/02/2016   
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hvacacpersonal ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hvacacpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hvacacpersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hproyectopersonal($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hproyectopersonal 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hproyectopersonal para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 10/07/2007   
		// Modificado Por: 											Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hproyectopersonal ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hproyectopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hproyectopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hpersonalnomina($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hpersonalnomina 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hpersonalnomina para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 20/02/2016   
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hpersonalnomina ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hpersonalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hpersonalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hasignacioncargo($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hasignacioncargo 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hasignacioncargo para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 02/03/2016         
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hasignacioncargo ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hasignacioncargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hasignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hcargo($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hcargo 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hcargo para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 02/03/2016         
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hcargo ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hcargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hcargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hunidadadmin($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hunidadadmin 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hunidadadmin para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 02/03/2016         
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hunidadadmin ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hunidadadmin ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hunidadadmin
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hclasificacionobrero($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hclasificacionobrero 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hunidadadmin para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 16/04/2008         
		// Modificado Por: 													Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hclasificacionobrero ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hclasificacionobrero ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hclasificacionobrero
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hproyecto($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hproyecto 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hproyecto para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 10/07/2007   
		// Modificado Por: 											Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
                "  FROM sno_hproyecto ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hpersonalproyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hproyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_sno_hprimagrado($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_sno_hprimagrado 
		//		   Access: public (tepuy_sno_c_cierre_periodo)
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina las primas hist�ricas  dado un per�odo y un a�o en curso
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creaci�n: 01/01/2016 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql=" DELETE ".
                "   FROM sno_hprimagrado ".
                "  WHERE codemp='".$this->ls_codemp."' ".
				"    AND codnom='".$this->ls_codnom."' ".
				"    AND anocur='".$adt_anocurnom."' ".
				"    AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_sno_hprimagrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_sno_hprimagrado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hgrado($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hgrado 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hgrado para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 02/03/2016         
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="DELETE ".
                "  FROM sno_hgrado ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hgrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hgrado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_htabla($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_htabla 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_htabla para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 02/03/2016         
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="DELETE ".
                "  FROM sno_htabulador ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_htabla ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_htabla
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hperiodo($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hperiodo 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hperiodo para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 02/03/2016         
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="DELETE ".
                "  FROM sno_hperiodo ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_hsubnomina($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_hsubnomina 
		//	    Arguments: as_codperi // codigo del periodo
		//	               adt_anocurnom // a�o en curso de la nomina
		//	      Returns: lb_valido true si es correcto el delete o false en caso contrario
		//	  Description: Funci�n que elimina el periodos de tabla historica sno_hsubnomina para proceder al cierre del mismo
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 02/03/2016         
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="DELETE ".
                "  FROM sno_hsubnomina ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$adt_anocurnom."' ".
				"   AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_delete_hsubnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_delete_hsubnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hsubnomina($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hsubnomina 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_subnomina en la tabla historica sno_hsubnomina 
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 02/03/2016         
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="INSERT INTO sno_hsubnomina (codemp,codnom,codsubnom,anocur,codperi,dessubnom ) ".
                "     SELECT codemp,codnom,codsubnom,'".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi,dessubnom ".
                "       FROM sno_subnomina ".
                "      WHERE codemp='".$this->ls_codemp."' ".
				"        AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hsubnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hsubnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hperiodo($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hperiodo 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_periodo en la tabla historica sno_hperiodo 
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 02/03/2016         
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="INSERT INTO sno_hperiodo (codemp,codnom,anocur,codperi,fecdesper,fechasper,totper,cerper,conper,apoconper,obsper,peradi,ingconper,fidconper) ".
                "     SELECT codemp,codnom,'".$adt_anocurnom."' as anocur,codperi,fecdesper,fechasper,totper,cerper, ".
				"            conper,apoconper,obsper,peradi,ingconper,fidconper ". 
                "       FROM sno_periodo ".
                "      WHERE codemp='".$this->ls_codemp."' ".
				"        AND codnom='".$this->ls_codnom."' ".
				"        AND codperi='".$as_codperi."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_htabla($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_htabla 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_tabla en la tabla historica sno_htabla 
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 02/03/2016         
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="INSERT INTO sno_htabulador (codemp,codnom,anocur,codperi,codtab,destab,maxpasgra) ".
                "     SELECT codemp,codnom,'".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi,codtab,destab,maxpasgra ".
                "       FROM sno_tabulador ".	
                "      WHERE codemp='".$this->ls_codemp."' ".
				"        AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_htabla ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_htabla
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hgrado($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hgrado 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_grado en la tabla historica sno_hgrado 
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 02/03/2016         
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="INSERT INTO sno_hgrado (codemp,codnom, anocur, codperi, codtab, codgra, codpas, monsalgra, moncomgra ) ".
                "     SELECT codemp,codnom,'".$adt_anocurnom."' AS anocur, '".$as_codperi."' AS codperi,codtab,codgra,codpas,".
				"            monsalgra,moncomgra ".
                "       FROM sno_grado ".
                "      WHERE (codemp='".$this->ls_codemp."') ".
				"        AND (codnom='".$this->ls_codnom."') ";
	    $li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hgrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hgrado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hprimagrado($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hprimagrado 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_primagrado en la tabla historica sno_hprimagrado 
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 02/03/2016         
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="INSERT INTO sno_hprimagrado(codemp, codnom, anocur, codperi, codtab, codpas, codgra, codpri, despri, monpri) ".
                "     SELECT codemp,codnom,'".$adt_anocurnom."' AS anocur, '".$as_codperi."' AS codperi,".
				"            codtab, codpas, codgra, codpri, despri, monpri".
                "       FROM sno_primagrado ".
                "      WHERE (codemp='".$this->ls_codemp."') ".
				"        AND (codnom='".$this->ls_codnom."') ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hprimagrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hprimagrado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hunidadadmin($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hunidadadmin 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_unidadadmin en la tabla historica sno_hunidadadmin 
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 02/03/2016         
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="INSERT INTO sno_hunidadadmin (codemp,codnom,anocur,codperi,minorguniadm,ofiuniadm,uniuniadm, ".
                "            depuniadm,prouniadm,desuniadm,codprouniadm) ".
                "     SELECT codemp,'".$this->ls_codnom."' AS codnom,'".$adt_anocurnom."' AS anocur, ".
                "            '".$as_codperi."' AS codperi,minorguniadm,ofiuniadm,uniuniadm,depuniadm, ".
                "            prouniadm,desuniadm,codprouniadm ".
                "       FROM sno_unidadadmin ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hunidadadmin ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hunidadadmin
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hclasificacionobrero($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hclasificacionobrero 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_unidadadmin en la tabla historica sno_hunidadadmin 
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 02/03/2016         
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="INSERT INTO sno_hclasificacionobrero (codemp, grado, codnom, anocur, codperi, suemin, suemax, tipcla, obscla, anovig, nrogac) ".
                "     SELECT codemp, grado, '".$this->ls_codnom."' AS codnom, '".$adt_anocurnom."' AS anocur, ".
                "            '".$as_codperi."' AS codperi, suemin, suemax, tipcla, obscla, anovig, nrogac ".
                "       FROM sno_clasificacionobrero ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hclasificacionobrero ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hclasificacionobrero
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hproyecto($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hproyecto 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_proyecto en la tabla historica sno_hproyecto
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 10/07/2007         
		// Modificado Por: 												Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="INSERT INTO sno_hproyecto (codemp, codnom, anocur, codperi, codproy, nomproy, estproproy) ".
                "     SELECT codemp,'".$this->ls_codnom."' AS codnom,'".$adt_anocurnom."' AS anocur, ".
                "            '".$as_codperi."' AS codperi, codproy, nomproy, estproproy ".
                "       FROM sno_proyecto ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hproyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hproyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hcargo($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hcargo 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_cargo en la tabla historica sno_hcargo 
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 02/03/2016         
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="INSERT INTO sno_hcargo (codemp,codnom,anocur,codperi,codcar,descar) ".
                "     SELECT codemp,codnom,'".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi,codcar,descar ".
                "       FROM sno_cargo ".
                "      WHERE codemp='".$this->ls_codemp."' ".
				"        AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hcargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hcargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hasignacioncargo($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hasignacioncargo 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_asignacioncargo en la tabla historica sno_hasignacioncargo 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 02/03/2016       
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql=" INSERT INTO sno_hasignacioncargo (codemp, codnom, anocur, codperi, codasicar, denasicar, claasicar, ".
                "                                   codtab, codpas, codgra, codded, codtipper, numvacasicar, numocuasicar, codproasicar, ".
                "                                   minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm) ".
                "      SELECT codemp, codnom, '".$adt_anocurnom."' AS anocur, '".$as_codperi."' AS codperi, codasicar, denasicar, claasicar, ".
				"             codtab, codpas, codgra, codded, codtipper, numvacasicar, numocuasicar, codproasicar, ".
				"             minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm".
                "       FROM sno_asignacioncargo ".
                "      WHERE codemp='".$this->ls_codemp."' ".
				"        AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hasignacioncargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hasignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hpersonalnomina($adt_anocurnom,$as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hpersonalnomina 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_personalnomina en la tabla historica sno_hpersonalnomina 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 02/03/2016       
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql= "INSERT INTO sno_hpersonalnomina (codemp, codnom, anocur, codperi, codper,codsubnom, codtab, codasicar, ".
                 "                                  codgra, codpas, sueper, horper, minorguniadm, ofiuniadm, uniuniadm, ".
				 "                                  depuniadm, prouniadm, pagbanper,codban, codcueban, tipcuebanper, ".
				 "                                  codcar, fecingper, staper, cueaboper, fecculcontr, codded, codtipper, ".
				 "                                  quivacper, codtabvac, sueintper, pagefeper, sueproper, codage, fecegrper, ".
				 "                                  fecsusper,cauegrper, codescdoc, codcladoc, codubifis, tipcestic, conjub, ".
				 "									catjub, codclavia, codunirac, pagtaqper, fecascper, grado ) ".
                 "     SELECT codemp, codnom, '".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi, codper, codsubnom, ".
				 "            codtab, codasicar, codgra, codpas, sueper, horper, minorguniadm, ".
				 "            ofiuniadm, uniuniadm, depuniadm, prouniadm, pagbanper, codban, ".
				 "            codcueban, tipcuebanper, codcar, fecingper, staper, cueaboper, ".
				 "            fecculcontr, codded, codtipper, quivacper, codtabvac, sueintper, ".
				 "            pagefeper, sueproper, codage, fecegrper, fecsusper, cauegrper, ".
				 "            codescdoc, codcladoc, codubifis, tipcestic, conjub, catjub, codclavia, codunirac, pagtaqper, fecascper, grado ".
                 "       FROM sno_personalnomina ".
                 "      WHERE codemp='".$this->ls_codemp."' ".
				 "        AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hpersonalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hpersonalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hproyectopersonal($adt_anocurnom,$as_codperi)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hproyectopersonal 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_proyectopersonal en la tabla historica sno_hproyectopersonal 
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 10/07/2007       
		// Modificado Por: 													Fecha �ltima Modificaci�n : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql= " INSERT INTO sno_hproyectopersonal (codemp, codnom, anocur, codperi, codproy, codper, totdiaper, totdiames, pordiames) ".
                 "      SELECT codemp,'".$this->ls_codnom."' AS codnom,'".$adt_anocurnom."' AS anocur, ".
                 "            '".$as_codperi."' AS codperi, codproy, codper, totdiaper, totdiames, pordiames ".
                 "        FROM sno_proyectopersonal ".
                 "       WHERE codemp='".$this->ls_codemp."' ".
				 "		   AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hproyectopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hproyectopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hvacacpersonal($adt_anocurnom,$as_codperi)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hvacacpersonal 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_vacacpersonal en la tabla historica sno_hvacacpersonal 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 02/03/2016       
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql= " INSERT INTO sno_hvacacpersonal (codemp, anocur, codnom, codperi, codper,codvac, fecvenvac, fecdisvac, ".
		         "                                 fecreivac, diavac, stavac, sueintbonvac, sueintvac, diabonvac, ".
                 "                                 obsvac, diapenvac, persalvac, peringvac, dianorvac, quisalvac, quireivac, ".
                 "                                 periodo_1, cod_1, nro_dias_1, monto_1, periodo_2, cod_2, nro_dias_2, ".
                 "                                 monto_2, periodo_3, cod_3, nro_dias_3, monto_3, periodo_4, cod_4, ".
                 "                                 nro_dias_4, monto_4, periodo_5, cod_5, nro_dias_5, monto_5, diaadivac, ".
                 "                                 diaadibon, diafer, sabdom ) ".
                 "      SELECT sno_vacacpersonal.codemp,'".$adt_anocurnom."' AS anocur,sno_personalnomina.codnom, ".
				 "             '".$as_codperi."' AS codperi,sno_vacacpersonal.codper,sno_vacacpersonal.codvac, ".
				 "			   sno_vacacpersonal.fecvenvac,sno_vacacpersonal.fecdisvac,sno_vacacpersonal.fecreivac, ".
				 "             sno_vacacpersonal.diavac,sno_vacacpersonal.stavac, sno_vacacpersonal.sueintbonvac, ".
				 "             sno_vacacpersonal.sueintvac,sno_vacacpersonal.diabonvac,sno_vacacpersonal.obsvac, ".
				 "			   sno_vacacpersonal.diapenvac,sno_vacacpersonal.persalvac,sno_vacacpersonal.peringvac, sno_vacacpersonal.dianorvac, ".
                 "             sno_vacacpersonal.quisalvac,sno_vacacpersonal.quireivac,sno_vacacpersonal.periodo_1, ".
				 "             sno_vacacpersonal.cod_1,sno_vacacpersonal.nro_dias_1,sno_vacacpersonal.monto_1,".
				 "			   sno_vacacpersonal.periodo_2,sno_vacacpersonal.cod_2,sno_vacacpersonal.nro_dias_2, ".
				 "             sno_vacacpersonal.monto_2,sno_vacacpersonal.periodo_3,sno_vacacpersonal.cod_3,sno_vacacpersonal.nro_dias_3, ".
				 "			   sno_vacacpersonal.monto_3,sno_vacacpersonal.periodo_4,sno_vacacpersonal.cod_4,sno_vacacpersonal.nro_dias_4, ".
				 "             sno_vacacpersonal.monto_4,sno_vacacpersonal.periodo_5,sno_vacacpersonal.cod_5,sno_vacacpersonal.nro_dias_5, ".
				 "             sno_vacacpersonal.monto_5,sno_vacacpersonal.diaadivac,sno_vacacpersonal.diaadibon,sno_vacacpersonal.diafer, ".
				 "			   sno_vacacpersonal.sabdom ".
                 "        FROM sno_personalnomina, sno_vacacpersonal  ".
                 "       WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				 "		   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				 "		   AND sno_personalnomina.codemp=sno_vacacpersonal.codemp ".
				 "		   AND sno_personalnomina.codper=sno_vacacpersonal.codper ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hvacacpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hvacacpersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hconcepto($adt_anocurnom,$as_codperi)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hconcepto 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_concepto en la tabla historica sno_hconcepto 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 02/03/2016       
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql= " INSERT INTO  sno_hconcepto (codemp, codnom, anocur, codperi, codconc, nomcon, conprep, titcon, sigcon, forcon, ".
                 "                             glocon, acumaxcon, valmincon, valmaxcon, concon, cueprecon, cueconcon, ".
                 "                             aplisrcon, sueintcon, intprocon, codpro, codpro1, forpatcon, cueprepatcon, ".
                 "                             cueconpatcon, titretempcon, titretpatcon, valminpatcon, valmaxpatcon, ".
                 "                             codprov, cedben, conprenom, sueintvaccon, aplarccon,conprocon,repacucon,repconsunicon, ".
                 "							   consunicon, quirepcon, asifidper, asifidpat, frevarcon) ".
                 "     SELECT codemp,codnom,'".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi,codconc,nomcon,conprep,titcon, ".
				 "            sigcon,forcon,glocon,acumaxcon,valmincon,valmaxcon,concon,cueprecon,cueconcon,aplisrcon,sueintcon, ".
				 "            intprocon,codpro,codpro1,forpatcon,cueprepatcon,cueconpatcon,titretempcon,titretpatcon,valminpatcon, ".
				 "            valmaxpatcon,codprov,cedben,conprenom,sueintvaccon,aplarccon, conprocon,repacucon,repconsunicon,consunicon, ".
				 "			  quirepcon, asifidper, asifidpat, frevarcon ".
                 "       FROM sno_concepto ".
                 "      WHERE codemp='".$this->ls_codemp."' ".
				 "        AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hprimaconcepto($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hprimaconcepto 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_primaconcepto en la tabla historica sno_hprimaconcepto 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 02/03/2016       
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql= " INSERT INTO sno_hprimaconcepto (codemp, codnom, anocur, codperi, codconc, anopri, valpri)".
                 "      SELECT codemp,codnom,'".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi,codconc,anopri,valpri ".
                 "        FROM sno_primaconcepto ".
                 "       WHERE codemp='".$this->ls_codemp."' ".
				 "		   AND codnom='".$this->ls_codnom."' ".
				 "         AND codconc IN (SELECT codconc FROM sno_concepto ".
				 "							WHERE codemp='".$this->ls_codemp."' ".
				 "         					  AND codnom='".$this->ls_codnom."') ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hprimaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hprimaconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hconceptopersonal($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hconceptopersonal 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_conceptopersonal en la tabla historica sno_hconceptopersonal 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 02/03/2016       
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="INSERT INTO sno_hconceptopersonal (codemp, codnom, codper, anocur, codperi, codconc, aplcon, valcon, ".
                "                                    acuemp, acuiniemp, acupat, acuinipat ) ".
                "     SELECT codemp,codnom,codper,'".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi, ".
                "            codconc,aplcon,valcon,acuemp,acuiniemp,acupat,acuinipat ".
                "       FROM sno_conceptopersonal ".
                "      WHERE codemp='".$this->ls_codemp."' ".
				"		 AND codnom='".$this->ls_codnom."' ".
				"        AND codconc IN (SELECT codconc FROM sno_concepto ".
				"						  WHERE codemp='".$this->ls_codemp."' ".
				"        					AND codnom='".$this->ls_codnom."') ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hconceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hconceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hconceptovacacion($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hconceptovacacion 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo 
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_conceptovacacion en la tabla historica sno_hconceptovacacion 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 02/03/2016       
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql= " INSERT INTO sno_hconceptovacacion (codemp, codnom, anocur, codperi, codconc, forsalvac, acumaxsalvac, ".
                 "                                     minsalvac, maxsalvac, consalvac, forpatsalvac, minpatsalvac, ".
                 "                                     maxpatsalvac, forreivac, acumaxreivac, minreivac, maxreivac, conreivac, ".
                 "                                     forpatreivac, minpatreivac, maxpatreivac) ".
                 "      SELECT codemp,codnom,'".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi,codconc,forsalvac, ".
				 "             acumaxsalvac,minsalvac,maxsalvac,consalvac,forpatsalvac,minpatsalvac,maxpatsalvac,forreivac, ".
                 "             acumaxreivac,minreivac,maxreivac,conreivac,forpatreivac,minpatreivac,maxpatreivac ".
                 "        FROM sno_conceptovacacion ".
                 "       WHERE codemp='".$this->ls_codemp."' ".
				 "		   AND codnom='".$this->ls_codnom."' ".
	             "         AND codconc IN (SELECT codconc ".
				 "                           FROM sno_concepto ".
				 "                          WHERE codemp='".$this->ls_codemp."'  ".
				 "							  AND codnom='".$this->ls_codnom."') ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hconceptovacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hconceptovacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hconstante($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hconstante 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo 
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_constante en la tabla historica sno_hconstante 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 02/03/2016       
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql= "INSERT INTO sno_hconstante (codemp, codnom, anocur, codperi, codcons, nomcon, unicon, equcon, ".
                 "                             topcon, valcon, reicon, tipnumcon ) ".
                 "     SELECT codemp,codnom,'".$adt_anocurnom."' AS anocur, '".$as_codperi."' AS codperi,codcons, ".
				 "            nomcon,unicon,equcon,topcon,valcon,reicon,tipnumcon ".
                 "       FROM sno_constante ".
                 "      WHERE codemp='".$this->ls_codemp."' ".
				 "		  AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hconstante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hconstante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hconstantepersonal($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hconstantepersonal 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo 
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_constantepersonal en la tabla historica sno_hconstantepersonal 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 02/03/2016       
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql= "INSERT INTO sno_hconstantepersonal (codemp, codnom, codper, anocur, codperi, codcons, moncon ) ".
                 "     SELECT codemp,codnom,codper,'".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi,codcons,moncon ".
                 "       FROM sno_constantepersonal ".
                 "      WHERE codemp='".$this->ls_codemp."' ".
				 "		  AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hconstantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hconstantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_htipoprestamo($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_htipoprestamo
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo 
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_tipoprestamo en la tabla historica sno_htipoprestamo
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 02/03/2016       
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql= "INSERT INTO sno_htipoprestamo (codemp, codnom, anocur, codperi, codtippre, destippre) ".
                 "     SELECT codemp,codnom,'".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi,codtippre,destippre ".
                 "       FROM sno_tipoprestamo ".
                 "      WHERE codemp='".$this->ls_codemp."' ".
				 "		  AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_htipoprestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_htipoprestamo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hprestamos($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hprestamos 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo 
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_prestamopersonal en la tabla historica sno_hprestamopersonal 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 02/03/2016       
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="INSERT INTO sno_hprestamos (codemp, codnom, codper, anocur, codperi, codtippre, codconc, numpre, ".
		        "                             monpre, numcuopre, perinipre, monamopre, stapre, fecpre, obsrecpre, obssuspre, tipcuopre ) ".
                "     SELECT codemp,codnom,codper,'".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi,codtippre,codconc, ".
		        "            numpre,monpre,numcuopre,perinipre,monamopre,stapre,fecpre, obsrecpre, obssuspre, tipcuopre ".
                "       FROM sno_prestamos ".
                " 	   WHERE codemp='".$this->ls_codemp."' ".
				"		 AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hprestamos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hprestamos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hprestamoperiodo($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hprestamoperiodo 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo 
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_prestamoperiodo en la tabla historica sno_hprestamoperiodo 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 02/03/2016       
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql=" INSERT INTO sno_hprestamosperiodo (codemp, codnom, codper, anocur, codperi, numpre, codtippre, numcuo, ".
                "                                    percob, feciniper, fecfinper, moncuo, estcuo ) ".
                "      SELECT codemp,codnom,codper,'".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi, ".
                "             numpre,codtippre,numcuo,percob,feciniper,fecfinper,moncuo,estcuo ".
                "        FROM sno_prestamosperiodo ".
                "       WHERE codemp='".$this->ls_codemp."' ".
				"		  AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hprestamoperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hprestamoperiodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hprestamoamortizado($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hprestamoamortizado 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo 
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_prestamosamortizado en la tabla historica sno_hprestamosamortizado 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 02/03/2016       
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 01/12/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql=" INSERT INTO sno_hprestamosamortizado (codemp, codnom, codper, numpre, codtippre, anocur, codperi, numamo, ".
				"										peramo, fecamo, monamo, desamo) ".
                "      SELECT codemp,codnom,codper,numpre, codtippre, '".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi, ".
                "             numamo, peramo, fecamo, monamo, desamo ".
                "        FROM sno_prestamosamortizado ".
                "       WHERE codemp='".$this->ls_codemp."' ".
				"		  AND codnom='".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hprestamoamortizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hprestamoamortizado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hresumen($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hresumen 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo 
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_resumen en la tabla historica sno_hresumen 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 02/03/2016       
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql=" INSERT INTO sno_hresumen (codemp, codnom, codper, anocur, codperi, asires, dedres, apoempres, apopatres, ".
		        "                            priquires, segquires, monnetres, notres) ".
                "      SELECT codemp,codnom,codper,'".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi, ".
                "             asires,dedres,apoempres,apopatres,priquires,segquires,monnetres,notres ".
                "        FROM sno_resumen ".
                " 		WHERE codemp='".$this->ls_codemp."' ".
				"		  AND codnom='".$this->ls_codnom."' ".
				"		  AND codperi='".$as_codperi."' ".
	            "         AND codper IN (SELECT codper FROM sno_personalnomina ".
				"                         WHERE codemp='".$this->ls_codemp."' ".
				"							AND codnom='".$this->ls_codnom."') ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hresumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hresumen
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hsalida($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hsalida 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo 
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//	  Description: Funci�n que inserta los datos de la tabla sno_salida en la tabla historica sno_hsalida 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 02/03/2016       
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="INSERT INTO sno_hsalida (codemp, codnom, codper, anocur, codperi, codconc, tipsal, valsal, monacusal, salsal, priquisal, segquisal) ".
                "     SELECT codemp,codnom,codper,'".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi,codconc,tipsal, ".
				"			 valsal,monacusal,salsal, priquisal, segquisal ".
                "       FROM sno_salida ".
                "      WHERE codemp='".$this->ls_codemp."' ".
				"		 AND codnom='".$this->ls_codnom."' ".
				"		 AND codperi='".$as_codperi."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hsalida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hsalida
	//-----------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_hprenomina($adt_anocurnom,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_hprenomina 
		//	    Arguments: adt_anocurnom // a�o en curso de la nomina 
		//                 as_codperi // codigo del periodo 
		//	      Returns: lb_valido true si es correcto el insert o false en caso contrario
		//    Description: Funci�n que inserta los datos de la tabla sno_prenomina en la tabla historica sno_hprenomina 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 20/02/2016    
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql= " INSERT INTO sno_hprenomina (codemp, codnom, codper, anocur, codperi, codconc, tipprenom, valprenom, valhis) ".
                 "      SELECT codemp,codnom,codper,'".$adt_anocurnom."' AS anocur,'".$as_codperi."' AS codperi,codconc,tipprenom,".
				 "			   valprenom,valhis ".
                 "        FROM sno_prenomina ".
                 "       WHERE codemp='".$this->ls_codemp."' ".
				 "		   AND codnom='".$this->ls_codnom."' ".
				 "		   AND codperi='".$as_codperi."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_insert_hprenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_insert_hprenomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_hnomina($as_codperi)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_hnomina 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que sirve para saber si la nomina se encuentra en al tabla sno_hnomina
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 20/02/2016    
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ldt_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
		$ls_sql="SELECT codnom ".
                "  FROM sno_hnomina  ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocurnom='".$ldt_anocurnom."' ".
				"   AND peractnom='".$as_codperi."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
		  $lb_valido=false;
		  $this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_select_hnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
			  $lb_valido=false;
			} 
		}
		return  $lb_valido;		  
	}// end function uf_select_hnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_acumular_conceptos($as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_acumular_conceptos 
		//  	Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que actualiza los acumulados de los conceptos 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 20/02/2016    
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codper,tipsal,valsal,codconc ".
                "  FROM sno_salida ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$as_codperi."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_acumular_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_tipsal=$row["tipsal"];
				$ld_valsal=$row["valsal"];
				$ls_codper=$row["codper"];
				$ls_codconc=$row["codconc"];
				if(($ls_tipsal=="P2")||($ls_tipsal=="V4")||($ls_tipsal=="W4")) // Aporte Patronal
				{
				  $ls_sql="UPDATE sno_conceptopersonal ".
						  "   SET acupat=(acupat+".$ld_valsal.") ".
						  " WHERE codemp='".$this->ls_codemp."' ".
						  "   AND codnom='".$this->ls_codnom."' ".
						  "   AND codper='".$ls_codper."' ".
						  "   AND codconc='".$ls_codconc."'";  
				}
				else
				{
				   $ls_sql="UPDATE sno_conceptopersonal ".
						   "   SET acuemp=(acuemp+".$ld_valsal.") ".
						   " WHERE codemp='".$this->ls_codemp."' ".
						   "   AND codnom='".$this->ls_codnom."' ".
						   "   AND codper='".$ls_codper."' ".
						   "   AND codconc='".$ls_codconc."'";
				}
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_acumular_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
			}
		}
		return $lb_valido;	
	}// end function uf_acumular_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_vacaciones($as_codper,$as_codvac,$adt_fecdisvac,$adt_fecreivac,$as_stavac)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_vacaciones 
		//	    Arguments: as_codper // codigo del personal
		//                 as_codvac //  codigo de la vacaciones
		//                 adt_fecdisvac  // fecha de disfrute de las vacaciones
		//                 adt_fecreivac  // fecha de reingreso de las vacaciones 
		//                 as_stavac   //  status de las vacaciones
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n actualiza las tablas de vacaciones personal
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 20/02/2016    
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_vacacpersonal ".
                "   SET stavac='".$as_stavac."' ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ".
				"   AND codvac='".$as_codvac."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_update_vacaciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
	   return $lb_valido;
	}// end function uf_update_vacaciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_personalnomina($as_codper,$as_staper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_personalnomina 
		//	    Arguments: as_codper // codigo del periodo
		//                 as_staper  // status del personal
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n que actualiza la tabla de personal nomina 
	    //     Creado por: Ing. Miguel Palencia.
	    // Fecha Creaci�n: 20/02/2016    
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if ($as_staper==1)
		{
			$ls_quivacper=" , ".'quivacper'."=0";
		}
		else
		{
			$ls_quivacper="";
		}
		$ls_sql="UPDATE sno_personalnomina ".
				"   SET staper='".$as_staper."'".$ls_quivacper." ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_update_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;	
	}// end function uf_update_personalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_metodo_vacaciones(&$ai_value)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_metodo_vacaciones 
		//	    Arguments: ai_value // valor del metodo de vacaciones 
		//	      Returns: lb_valido  true si se hizo correctamente el metodo sino false en caso contrario 
		//	  Description: Funci�n que retorna un valor del metodo de vacaciones 
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 15/02/2016          Fecha �ltima Modificacion : 
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT value ".
                "  FROM tepuy_config  ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"	AND codsis='SNO' ".
                "   AND seccion='CONFIG' ".
				"	AND entry='metodo_vacaciones' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
		  $lb_valido=false;
		  $this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_select_metodo_vacaciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		    $lb_valido=true;
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			    $ai_value=$row["value"];
			}
		}
	   return $lb_valido;
	}// end function uf_select_metodo_vacaciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_actualizar_personal_vacaciones($adt_fechasper,$as_codperi,$ai_metodo_vac)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_personal_vacaciones 
		//	    Arguments: adt_fechasper // fecha hasta del perido
		//                 as_codperi  // codigo de periodo 
		//                 ai_metodo_vac  //  metodo de vacaciones 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n actualiza a las personas que salen de vacaciones y actualiza las cuotas de sus  prestamos 
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 10/02/2016          
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_staper=1;//activo
		$ls_stavac=2;//programadas
		$ldt_fechasper=$this->io_funciones->uf_convertirfecmostrar($adt_fechasper);
		if($ai_metodo_vac=="1") //metodo 0
		{
			$ld_desde=$this->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
			$ld_desde=$this->io_sno->uf_suma_fechas($ld_desde,1);
			$ld_desde=$this->io_funciones->uf_convertirdatetobd($ld_desde);	
			switch($_SESSION["la_nomina"]["tippernom"])
			{
				case "0": // N�minas Semanales
					$li_dias=7;
					break;
				case "1": // N�minas Quincenales
					$li_dias=15;
					break;
				case "2": // N�minas Mensuales
					$li_dias=30;
					break;
				case "3": // N�minas Anuales
					$li_dias=365;
					break;
			}
			$ld_hasta=$this->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
			$ld_hasta=$this->io_sno->uf_suma_fechas($ld_hasta,$li_dias);
			$ld_hasta=$this->io_funciones->uf_convertirdatetobd($ld_hasta);
			$ls_sql="SELECT sno_personalnomina.codper, sno_vacacpersonal.codvac, sno_vacacpersonal.fecdisvac, ".
					"		sno_vacacpersonal.fecreivac ".
				    "  FROM sno_personalnomina , sno_vacacpersonal ".
				    " WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				    "   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				    "   AND sno_personalnomina.staper='".$ls_staper."' ".
				    "   AND sno_vacacpersonal.stavac='".$ls_stavac."' ".
				    "   AND sno_vacacpersonal.fecdisvac between '".$ld_desde."' AND '".$ld_hasta."' ".
					"   AND sno_personalnomina.codper=sno_vacacpersonal.codper ".
				    "   AND sno_personalnomina.codemp=sno_vacacpersonal.codemp ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_actualizar_personal_vacaciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codvac=$row["codvac"];
				$ls_codper=$row["codper"];
				$ldt_fecdisvac=$row["fecdisvac"];
				$ldt_fecreivac=$row["fecreivac"];
				$ls_stavac=3;//vacaciones
				$ls_staper=2;//vacaciones
				$lb_valido=$this->uf_update_vacaciones($ls_codper,$ls_codvac,$ldt_fecdisvac,$ldt_fecreivac,$ls_stavac);
				if($lb_valido)
				{
				   $lb_valido=$this->uf_update_personalnomina($ls_codper,$ls_staper);
				}  
			}//while
		}//else
		return $lb_valido;
	}// end function uf_actualizar_personal_vacaciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reingreso_personal_vac($adt_fecdesper,$adt_fechasper,$as_codperi,$ai_metodo_vac)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reingreso_personal_vac 
		//	    Arguments: adt_fechasper // fecha desde del perido
		//                 adt_fecdesper // fecha hasta del perido   
		//                 as_codperi  // codigo de periodo 
		//                 ai_metodo_vac  //  metodo de vacaciones   
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Funci�n  que actualiza los estatus del personal que se encuentra de vacaciones y los coloca
		//                 como activo y vacaciones ya disfrutadas.
	    //     Creado por: Ing. Miguel Palencia
	    // Fecha Creaci�n: 08/02/2016    
		// Modificado Por: Ing. Miguel Palencia						Fecha �ltima Modificaci�n : 29/05/2016
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_staper=2;
		$ls_stavac=3;
		$ldt_fechasper=$this->io_funciones->uf_convertirfecmostrar($adt_fechasper);
		if($ai_metodo_vac==1) //metodo 0
		{
			$ld_desde=$this->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
			$ld_desde=$this->io_sno->uf_suma_fechas($ld_desde,1);
			$ld_desde=$this->io_funciones->uf_convertirdatetobd($ld_desde);	
			switch($_SESSION["la_nomina"]["tippernom"])
			{
				case "0": // N�minas Semanales
					$li_dias=7;
					break;
				case "1": // N�minas Quincenales
					$li_dias=15;
					break;
				case "2": // N�minas Mensuales
					$li_dias=30;
					break;
				case "3": // N�minas Anuales
					$li_dias=365;
					break;
			}
			$ld_hasta=$this->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
			$ld_hasta=$this->io_sno->uf_suma_fechas($ld_hasta,$li_dias);
			$ld_hasta=$this->io_funciones->uf_convertirdatetobd($ld_hasta);
			$ls_sql="SELECT sno_personalnomina.codper, sno_vacacpersonal.codvac, sno_vacacpersonal.fecdisvac, ".
					"		sno_vacacpersonal.fecreivac ".
				    "  FROM sno_personalnomina , sno_vacacpersonal ".
				    " WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				    "   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				    "   AND sno_personalnomina.staper='".$ls_staper."' ".
				    "   AND sno_vacacpersonal.stavac='".$ls_stavac."' ".
				    "   AND sno_vacacpersonal.fecreivac between '".$ld_desde."' AND '".$ld_hasta."' ".
					"   AND sno_personalnomina.codper=sno_vacacpersonal.codper ".
				    "   AND sno_personalnomina.codemp=sno_vacacpersonal.codemp ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo2 M�TODO->uf_reingreso_personal_vac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
            $lb_valido=true;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codvac=$row["codvac"];
				$ls_codper=$row["codper"];
				$ldt_fecdisvac=$row["fecdisvac"];
				$ldt_fecreivac=$row["fecreivac"];
				$ls_stavac=4;
				$ls_staper=1;
				$lb_valido=$this->uf_update_vacaciones($ls_codper,$ls_codvac,$ldt_fecdisvac,$ldt_fecreivac,$ls_stavac);
				if($lb_valido)
				{
				   $lb_valido=$this->uf_update_personalnomina($ls_codper,$ls_staper);
				} 
			}//while
		}//else
		 return $lb_valido;
	}// end function uf_reingreso_personal_vac
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>
