<?php 
  
class tepuy_scg_class_definicion
{
  var  $is_msg_error="";
  var  $lb_valido=false;
  
function uf_delete_planunico($as_cuenta,$as_denominacion)
{      
	$inc=new tepuy_include();
	$con=$inc->uf_conectar();
	$SQL=new class_sql($con);
        
		$ls_sql="";
		$lb_valido=true;
		
		$ls_sql = "DELETE FROM tepuy_plan_unico WHERE sc_cuenta='".$as_cuenta."'";
		$numrows=$SQL->execute($ls_sql);
		if($numrows===false)
	    {
		   $this->is_msg_error="Error al eliminar";
		   $lb_valido = false;
		   $SQL->rollback();
		   $this->ib_db_error = true ;
        }
	    else
	    {
		    $lb_valido=true;
			$SQL->commit();
	    }

 return $lb_valido;
}

function uf_select_PlanUnico()
{
	$inc=new tepuy_include();
	$con=$inc->uf_conectar();
	$SQL=new class_sql($con);
 
	$rs="";
	$ls_sql="";
	$lb_valido=true;
      	     	     	
     	$ls_sql="SELECT * FROM tepuy_plan_unico";
        $rs=$SQL->select($ls_sql);
		$li_num=$SQL->num_rows($rs);
		if ($li_num===false)
        {
 		   $lb_valido=false;
		   $is_msg_error =  "Error en Select Plan Unico. ";
		}
		else
		{
		   $lb_valido=true;  
		}		       
	return $rs;	
}



function uf_delete_planunicore($as_cuenta,$as_denominacion)
{      

		$inc=new tepuy_include();
		$con=$inc->uf_conectar();
		$SQL=new class_sql($con);
		$ls_sql="";
		$lb_valido=true;

		$ls_sql = "DELETE FROM tepuy_plan_unico_re WHERE sig_cuenta='".$as_cuenta."' AND denominacion='".$as_denominacion."'";
		$SQL->begin_transaction();
		$numrows=$SQL->execute($ls_sql);
		if($numrows===false)
		{
		   $this->is_msg_error="Error al eliminar";
		   $lb_valido = false;
		   $SQL->rollback();
		   $this->ib_db_error = true ;
		}
		else
		{
		    $lb_valido=true;
			$SQL->commit();
        }
	    
	 return $lb_valido;
}

}
?>