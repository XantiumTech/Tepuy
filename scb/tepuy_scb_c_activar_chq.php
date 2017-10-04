<?php
class tepuy_scb_c_activar_chq
{
	var $io_sql;
	var $fun;
	var $msg;
	var $is_msg_error;	
	var $ds_sol;
	var $dat;
	var $ds_temp;
	var $io_sql_aux;
	function tepuy_scb_c_activar_chq()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");			
		$this->io_rcbsf= new tepuy_c_reconvertir_monedabsf();
		$sig_inc=new tepuy_include();
		$con=$sig_inc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->io_sql_aux=new class_sql($con);
		$this->fun=new class_funciones();
		$this->msg=new class_mensajes();
		$this->dat=$_SESSION["la_empresa"];	
		$this->ds_temp=new class_datastore();
		$this->ds_sol=new class_datastore();
	    $this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	}

	function  uf_cargar_cheques($as_codban,$as_ctaban,$object,$li_rows,$as_numche,$as_numchequera)
	{	
		$li_temp=0;
		$la_data=array();
		$ls_codemp=$this->dat["codemp"];
		$ld_fecha=date("Y-m-d");

		$ls_sql="select numche, numchequera, estche, estins from scb_cheques 
					where codemp="."'".$ls_codemp."'". " and codban="."'".$as_codban."'". 
					" and ctaban="."'".$as_ctaban."'". 
					" and estche=1 
					  and estins=0
					  and numche not in (select numdoc from scb_movbco) ";			
					  
		$rs_prog=$this->io_sql->select($ls_sql);	

		if(($rs_prog===false))
		{
			$this->is_msg_error="Error en consulta, ".$this->fun->uf_convertirmsg($this->io_sql->message);			
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_prog))
			{
				$la_data[$li_temp]=$row;
				$li_temp=$li_temp+1;				
				$ls_numche=$row["numche"];
				$as_numche=$ls_numche;
				$ls_numchequera=$row["numchequera"];
				$as_numchequera=$ls_numchequera;
				$ls_estche=$row["estche"];
				  if ($ls_estche==1){
				   $ls_estado='Tomado';
				   }			    
				//$object[$li_temp][1]  = "<input name=chk".$li_temp." type=checkbox id=chk".$li_temp." value=1 class=sin-borde onClick=javascript:uf_selected('".$li_temp."');  >";
				$object[$li_temp][1]  = "<input type=text name=txtnumchequera".$li_temp." id=txtnumchequera".$li_temp."  value='".$as_numchequera."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				$object[$li_temp][2]  = "<input type=text name=txtnumche".$li_temp." id=txtnumche".$li_temp."  value='".$ls_numche."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				$object[$li_temp][3]  = "<input type=text name=txtest".$li_temp." id=txtest".$li_temp."  value='".$ls_estado."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
				
				
			}
			
			$this->io_sql->free_result($rs_prog);
		}
		$li_rows=$li_temp;
		
	
	}//Fin de uf_cargar_programaciones	
	
   function uf_actualizar_estatus_ch($ls_codban,$ls_ctaban)    
   	  {						 
	   $ls_codemp=$this->dat["codemp"];
	   $ls_sql="select numche, numchequera, estche, estins from scb_cheques 
					where codemp="."'".$ls_codemp."'". " and codban="."'".$ls_codban."'". 
					" and ctaban="."'".$ls_ctaban."'". 
					" and estche=1 
					  and estins=0
					  and numche not in (select numdoc from scb_movbco) ";			
					  
		$rs_prog=$this->io_sql->select($ls_sql);	
         print $ls_sql;
		if(($rs_prog===false))
		{
			$this->is_msg_error="Error en consulta, ".$this->fun->uf_convertirmsg($this->io_sql->message);			
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_prog))
			{
				$la_data[$li_temp]=$row;
				$li_temp=$li_temp+1;				
				$ls_numche=$row["numche"];
				$as_numche=$ls_numche;
				$ls_numchequera=$row["numchequera"];
				$as_numchequera=$ls_numchequera;
				$ls_estche=$row["estche"];
				  if ($ls_estche==1){
				   $ls_estado='Tomado';
			}	
		$this->io_sql->free_result($rs_prog);
		}
			$ls_sql="UPDATE scb_cheques 
					SET estche=0
					WHERE 	codban='".$ls_codban."' 
					AND ctaban='".$ls_ctaban."' and numche='".$ls_numche."' 
					AND estche=1 and estins=0";							 
				print $ls_sql;	
			$li_result=$this->io_sql->execute($ls_sql);
				if($li_result===false)
					{
						$this->is_msg_error="Error en actualizar estatus Cheque.".$this->fun->uf_convertirmsg($this->io_sql->message);
						return false;					
					}		 
				else
					{
					return true;
					}
				
	}
   }
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////7
}
?>