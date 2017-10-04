<?php
 
 						/** // // // // // // // // // // // // // // ||
 						// this class is used for backup and          ||
 						// restore mysql server databases or entire   ||
 						// server , can compress backup ,send         ||
 						// to ftp server ,restore from compress again ||
 						*/

// Adecuacion de la clase: Miguel Palencia
// Email: jah120@gmail.com
// Fecha: 26/06/2009
 
//set_time_limit('0');
//error_reporting(E_ALL);
error_reporting(0);

abstract class BackupRestore
{
	protected 	$link, $onlys;
	protected 	$db		=	array();
	protected 	$dbr	=	array();
	protected 	$table	=	array();
	protected 	$tdata	=	array();
	protected 	$tdatp	=	array();
	protected 	$text	=	"";
	private 	$debug	=	'0';
	private 	$msg	=	array();
	
		/**
	function construct for logging to database server
	*/
	
	final public function __construct($host = 'localhost', $user = 'root', $pass = '', $structure_only = false)
	{
		set_error_handler(array($this,'handleError'));
		
		try{
			$this->connect($host, $user, $pass);
			$this->onlys = $structure_only;
		}
		catch (exception $e){
			trigger_error($e->getMessage(),E_USER_ERROR);
		}
	}
	
	final public function __destruct()
	{
		if ($this->msg)
		{
			foreach ($this->msg as $rr) {
				echo "<b>Nota</b>" . $rr . "<br>";
			}
		}
		else
		{
			echo "La mision se ha completado exitosamente.";
		}
		//SHOW ERROR NOTES TO USER AFTER END
	}
	
	final public function handleError($errno, $errmsg, $errfile, $errline)
	{
        if ($this->debug == '0')
		{
			switch ($errno)
			{
				case E_USER_ERROR:
					$this->msg[] = $errmsg;
					return true;
				case E_WARNING:
					echo $errmsg;
					exit();
				break;
				
				case E_USER_WARNING:
				case E_USER_NOTICE:
//					$this->msg[] = $errmsg;
//					return true;
				break;
				
				case E_NOTICE:
				case E_STRICT:
					return true;
				break;
				
				default:
					echo "UNKNOWN ERROR OCCURED";
					exit();
			}
		}
		
		if ($this->debug == '1')
		{
			$errmsg = (strpos($errmsg, 'Mysql')) ? mysql_error() : $errmsg;
			echo "<b>".$errno."</b>: ".$errmsg." <b>LINE: ".$errline."</b>"."<br><b><i>In file</i></b> ".$errfile."<hr>";
			exit();
		}
	}
	
	final private function connect($host, $user, $pass)
	{	
		if (! $this->link = mysql_connect($host, $user, $pass))
			throw new exception ("Mysql:couldn't connect to database server or invalid information");
	}
	
	// if you have multiple dbs enter them in that sequence
	// $db1,$db2,..
	// or leave it and it will backup the entire server databases
	
	final public function setDbs($db = '*')
	{
		$db = trim($db);
		if (empty($db) || $db == '*')
		{
			$list = mysql_list_dbs($this->link);
			$rows = mysql_num_rows($list);
			if ( $rows == 0 )
				trigger_error("Mysql:THERE IS NO DATABASES ON THE SERVER!!",E_USER_ERROR);

			for($i = 0; $i < $rows; $i++)
			{
				$this->db[] = mysql_tablename($list, $i);
			}
		}
		else
		{
			$db = explode(",", $db);
			$this->db = $db;
		}
	}
	
	final public function setDbr($db = '*')
	{
		$db = trim($db);
		if (empty($db) || $db == '*')
		{
			$this->dbr = $this->db;
		}
		else
		{
			$db = explode(",", $db);
			$this->dbr = $db;
		}
	}
	
	/**
	// this method will be for selecting tables or ignore it and
	// it will backup all tables
	*/
	
	final public function selectTable($table = '*')
	{
		$table = trim($table);
		if($table == '')  $table = '*';
		
		if (! $table=="*" && count($this->db) > '1')
			trigger_error("you can't specify tables if you want more than one db",E_USER_ERROR);
		
		if ($table == "*")
		{
			foreach ($this->db as $name)
			{
				$list = @mysql_list_tables($name);
				$rows = @mysql_num_rows($list);
				for($i = 0; $i < $rows; $i++)
				{
					$this->table[$name][] = mysql_tablename($list, $i);
				}
			}
		}
		else
		{
			$table = explode(",", $table);
			foreach($table as $tb)
				$this->table[$this->db['0']][] = $tb;
		}	
	}
	
	final public function datawTable($table = "", $tpart = "")
	{
		$table = trim($table);
		if ($table != "")
		{
			$table = explode(",", $table);
			foreach($table as $tb)
				$this->tdata[$this->db['0']][] = $tb;
		}
		if ( count ( $tpart ) > 0 )
			foreach($tpart as $tp => $vp)
				foreach($vp as $op => $val)
				{
					$this->tdatp[ $tp ][ $op ] = $val;
//					echo "$tp - $op - " . $this->tdatp[ $tp ][ $op ] ."<br>";
				}
	}
		
	/**
	// method for selecting the query required
 	// for backup
 	// this is ahabit for me to store all queries required in amethod and call it
	*/
	
	final protected function selectQuery($type)
	{
		$query = array(1=>"SHOW CREATE DATABASE ","SHOW CREATE TABLE ","INSERT INTO ","DROP DATABASE IF EXISTS ","DROP TABLE IF EXISTS ","SELECT * FROM ","CREATE DATABASE ");
		return $query[$type];
	}
	
	/**
	method for validate file before restore database
	*/
	
	final protected function getFile($file)
	{
		$this->text = "";
		switch ($file)
		{
			case (!file_exists($file)):
				trigger_error("File doesn't exist!!",E_USER_ERROR);
				return false;
			break;
			case (!is_file($file)):
				trigger_error("This not valid file",E_USER_ERROR);
				return false;
			break;
			case (!is_readable($file)):
				trigger_error("Can't get access to the file!!",E_USER_ERROR);
				return false;
			break;
			case (! ereg("\.sql$",$file) && ! ereg("\.gz$",$file) && ! ereg("\.txt$",$file)):
				trigger_error("This is not avalid file name.",E_USER_ERROR);
				return false;
			break;
			default:
			if(ereg("\.gz$",$file))
			{
				if(!$gz = gzopen($file,'rb'))
				trigger_error("couldn't open compressed file",E_USER_ERROR);
				gzrewind($gz);
				while(!gzeof($gz))
				{
					$this->text .= gzgets($gz);
				}
				gzclose($gz);
			}
			else
			{
				if(!$fp = fopen($file,'rb'))
					trigger_error("Couldn't read from the file",E_USER_ERROR);
		
				flock($fp,'1');
//echo "pp1";
				while(!feof($fp))
				{
					$this->text .= fgets($fp);
				}
//echo "pp2";
				flock($fp,'3');
				fclose($fp);
			}
			if($er=error_get_last())
			{
				trigger_error($er['type'].":".$er['message'],E_USER_WARNING);
			}
			return true;
		}
	}
	
	/**
		this method is for prepare file that be backuped
		//
	*/
	
	final protected function setFile($txt,$nfil,$cfil,$cmp,$ftp,$fhost,$fuser,$fpass,$fport)
	{
		$recognize = "";
		foreach ($this->db as $rec){
			$recognize .= $rec . "_";
		}
		$recognize = ereg_replace("_$", "", $recognize);  //for naming file backuped

		// this the preferred for me format for naming files
		$file = 'backup@' . $recognize . "@" . date('Y-M-d',time());
//		$file = 'bck_' . $recognize . "_" . date('Y-M-d',time());
		
		if ( $nfil != "" ) $file = $nfil;
		
		$file .= '.sql';
		
		if ( $cfil )
		{
			if ( !file_exists($file) )
			{
//				@mkdir($dir, 0777);
				touch($file);
			}
			$fp = fopen($file, "w");
		}
		else
		{
			if(!$fp = fopen($file, 'wb'))
			{
				trigger_error("You may have no enough rights on server",E_USER_ERROR);
			}
		}	
		flock($fp,'2');
		fwrite($fp, $txt);
		flock($fp,'3');
		fclose($fp);
		
		chmod ($file, 0777);
		
		if ($cmp == '1')
		{
			$file = $file . ".gz";
			if(! $gz = gzopen($file,'wb'))
			trigger_error("Script failed to compress backuped file.",E_USER_NOTICE);
			gzwrite($gz, $txt);
			gzclose($gz);
		}
		
		if ($ftp == '1')
		{
			if(! $conn = ftp_connect($fhost,$fport))
				trigger_error("El enlace al servidor FTP no es valido, asegurese de la direccion IP este correcta",E_USER_ERROR);
				
			$log = ftp_login($conn,$fuser,$fpass);
			if(!$log) trigger_error("El usuario o password FTP no es correcto",E_USER_ERROR);
			
			$put = ftp_put($conn,$file,$file,FTP_BINARY);
			if(!$put) trigger_error("No se pudo subir el archivo al servidor remoto FTP",E_USER_WARNING);

			ftp_close($conn);
		}
		else
		{
			if ( ! $cfil )  $this->downBackup($file);
		}	
		return true;
	}
	
	// download backuped file
	
	final private function downBackup($file)
	{
		header('Content-Description:File Transfer');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
   		header('Pragma: public');
   		header('Content-Disposition: attachment; filename='.basename($file));
   		header('Content-Type: application/octet-stream');
    	header('Content-Length: ' . filesize($file));
    	ob_clean();
    	flush();
    	readfile($file);
	}
	
	abstract protected function backupData($nfil="",$cfil=0,$cmp='0',$ftp='0',$fhost='',$fpass='',$fport='21');
	
//	abstract protected function restoreSql($server, $dbuser, $dbpass, $file, $remove = false);
	abstract protected function restoreSql($file, $remove = false);
}


final class BackupRestoreSql extends BackupRestore
{
	
	const HEADERS='******';               // signature
	const FIELDSEP = ';';                 // seperate between queries
	
	
	/* this method for backup
	// you can specify more options like compression (0,1) , send through ftp to server (0,1)
	//
	*/
	
	public function backupData($nfil="", $cfil=0, $cmp='0', $ftp='0', $fhost='', 
							   $fuser='anonymous', $fpass='', $fport='21')
	{		
		if($ftp == '1')
		{
			if ( empty($fhost) )
			{
				trigger_error("You must specify ftp host name as you select ftp option",E_USER_ERROR);
			}
			if( strpos($fhost, "ftp://") )
			{
				str_replace("ftp://", "", $fhost);
			}
		}
		
		$this->text = "";
//		$this->text.=self::HEADERS;
	
		/**
		this will begin save database to file
		*/

		$i = 0; // iterador de la base con nombre nuevo		
		foreach($this->db as $key)
		{
//			$result = mysql_query($this->selectQuery('1') . "$key", $this->link);
			$res = mysql_query("SHOW CREATE DATABASE " . $key, $this->link);
			while($row = mysql_fetch_row($res))
			{
				$this->text .= "\r\n";

				$this->text .= "DROP DATABASE IF EXISTS ";
				if ( count($this->dbr) > 0 )
					$this->text .= "`" . $this->dbr[$i] . "`";
				else
					$this->text .= $key;
				
				$this->text .= self::FIELDSEP . "\n";

				if ( count($this->dbr) > 0 )
					$this->text .= "CREATE DATABASE `" . $this->dbr[$i] . "`";

				else
					$this->text .= $row['1'];			

				$this->text .= self::FIELDSEP . "\n";

				$this->text .= "\nUSE ";
				if ( count($this->dbr) > 0 )
					$this->text .= "`" . $this->dbr[$i] . "`";
				else
					$this->text .= $row['1'];
				$this->text .= self::FIELDSEP . "\n";
			}

			$this->text .= "\n";
			$this->text .= "SET AUTOCOMMIT = 0" . self::FIELDSEP . "\n";
			$this->text .= "SET FOREIGN_KEY_CHECKS = 0" . self::FIELDSEP . "\n";
			$this->text .= "SET UNIQUE_CHECKS = 0" . self::FIELDSEP . "\n";
			$this->text .= "\n";
			
			// this will save tables related to that database
			mysql_select_db($key);

			foreach($this->table[$key] as $select)
			{

			// no sea solo la estructura y este entre las tablas listadas
				$found = true;
				if ( count($this->tdata[$key] ) > 0 )
					$found = array_search ( $select, $this->tdata[$key] );

			///////////////////////////////////////////////////////

			// revisando si las tablas son vistas
				$vista = 0;
				$q  = "SELECT * FROM INFORMATION_SCHEMA.VIEWS WHERE ";
				$q .= "table_schema = '$key' and table_name = '$select'";
				$result0 = mysql_query($q, $this->link);
				if ( @mysql_num_rows($result0) > 0 )
				{
					$vista = 1;
					@mysql_free_result($result0);
				}

			///////////////////////////////////////////////////////

				if ( ! count( $this->table[$key] ) == '0'  &&  $vista == 0 )
				{
					$result = mysql_query("SHOW CREATE TABLE " . $select, $this->link);
					while($row = mysql_fetch_row($result))
					{
						$this->text .= "DROP TABLE IF EXISTS " . $select;
						$this->text .= self::FIELDSEP . "\n";
						$this->text .= $row['1'];
						$this->text .= self::FIELDSEP . "\n\n";

						if ( $this->onlys == 'false' && $found )
						{
							$valor = array();
							$colum = array();
							if ( count ($this->tdatp[$select]) > 1 )
							{
								$q1  = "SELECT " . $this->tdatp[$select]['0'];
								$q1 .= " FROM "  . $select;
//								$q1 .= " WHERE " . $this->tdatp[$select]['1'];
								$q1 .= " " . $this->tdatp[$select]['1'];
//echo "1- " . $q1 . "<br>";
								$result1 = mysql_query($q1, $this->link);

								$num_rows   = mysql_num_rows($result1);
								$num_fields = mysql_num_fields($result1);

								for ($x = 0; $x < $num_fields; $x++)
								{
									$field_name = mysql_field_name($result1, $x);

									$colum[$x] = $field_name;

//									$q2  = "SHOW COLUMNS FROM " . $select;
//									$q2 .= " WHERE field = '$field_name'";
/*									$q2  = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE ";
									$q2 .= "table_schema = '$key' AND table_name = '$select' ";
									$q2 .= "AND colum_name = '$field_name'";
									$r0 = mysql_query($q2, $this->link);
									if ( $r0 )
									{
										$colum[$x] = $field_name;
									}
*/

/*									$r0 = mysql_db_query($key, "SHOW FIELDS FROM `{$select}`");
									while($f0 = mysql_fetch_object($r0))
									{
										$campo = $row->Field;
										if ( $campo == $field_name )
										{
											$colum[$x] = $field_name;
											break;
										}
									}
*/
								}

								for ($i = 0; $i < $num_rows; $i++)
								{
									$row = mysql_fetch_object($result1);
									for ($x = 0; $x < $num_fields; $x++)
									{
										$field_name = mysql_field_name($result1, $x);

										$valor[$field_name][$i] = $row->$field_name;

//										$q2  = "SHOW COLUMNS FROM " . $select;
//										$q2 .= " WHERE field = '$field_name'";
/*										$q2  = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE ";
										$q2 .= "table_schema = '$key' AND table_name = '$select' ";
										$q2 .= "AND colum_name = '$field_name'";
										$r0 = mysql_query($q2, $this->link);
										if ( $r0 )
										{
											$valor[$field_name][$i] = $row->$field_name;
										}
*/

/*										$r0 = mysql_db_query($key, "SHOW FIELDS FROM `{$select}`");
										while($f0 = mysql_fetch_object($r0))
										{
											$campo = $row->Field;
											if ( $campo == $field_name )
											{
												$colum[$x] = $field_name;
												break;
											}
										}
*/
									}
								}
								@mysql_free_result($result1);
							}

//////////////////////////////

							$this->text .= "LOCK TABLES " . $select . " WRITE";
							$this->text .= self::FIELDSEP . "\n";
							
							$q = "SELECT ";
							$found = stripos ($this->tdatp[$select]['1'], "W");
							// W de where entre los dos primeros caracteres del objeto condicional
							if ( $found !== false && $found > 1 )
							{
								$q .= "A.*";
							}
							else
							{
								$q .= "*";
							}
							$q .= " FROM " . $select;
//							$q = "SELECT $select.* FROM " . $select;
//echo $q . " - encontrado - $found<br>";
/////////////////////////////////////////////////////
							if ( count ($this->tdatp[$select]) > 1 )
							{
//								$q .= " WHERE " . $this->tdatp[$select]['1'];
								$q .= " " . $this->tdatp[$select]['1'];
//echo "2- " . $q . "<br><br>";
								$result2 = mysql_query($q, $this->link);

								$num_rows   = mysql_num_rows($result2);
								$num_fields = mysql_num_fields($result2);

								for ($i = 0; $i < $num_rows; $i++)
								{
									$row = mysql_fetch_object($result2);

									$fields = "";
									$values = "";
									for ($x = 0; $x < $num_fields; $x++)
									{	
										$field_name = mysql_field_name($result2, $x);
/*										
//										$q2  = "SHOW COLUMNS FROM " . $select;
//										$q2 .= " WHERE field = '$field_name'";
										$q2  = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE ";
										$q2 .= "table_schema = '$key' AND table_name = '$select' ";
										$q2 .= "AND colum_name = '$field_name'";
										$r0 = mysql_query($q2, $this->link);
echo $q2 . " - " . $field_name . "<br>";
										if ( $r0 )
										{
											$fields .= "`$field_name`,";
										}
*/

/*										$r0 = mysql_db_query($key, "SHOW FIELDS FROM `{$select}`");
										while($f0 = mysql_fetch_object($r0))
										{
											$campo = $row->Field;
											if ( $campo == $field_name )
											{
												$fields .= "`$field_name`,";
												break;
											}
										}
*/
										$fields .= "`$field_name`,";

										$ci = "";
										for ($j = 0; $j < count($colum); $j++)
										{
											if ( $field_name == $colum[$j] )
											{
												$ci = $colum[$j];
												break;
											}
										}
										$vtxt = "";
										if ( $ci != "" )
										{
											$vtxt = $valor[ $ci ][ $i ];
//											$values .= "'" . $valor[ $ci ][ $i ] . "',";

//				echo "col: " . $ci . " - " . $valor[$ci][$i] . "<br>";
										}
										else
										{
											$vtxt = $row->$field_name;

//											$values .= "'" . str_replace('\"', '"', mysql_escape_string($row->$field_name)) . "',";
										}
										$values .= "'" . mysql_real_escape_string($vtxt, $this->link) . "',";
									}

									$fields = substr($fields, 0, strlen($fields)-1);
									$values = substr($values, 0, strlen($values)-1);

									$this->text .= "INSERT INTO " . $select . " (" . $fields . ") VALUES(" . $values . ")";
									$this->text .= self::FIELDSEP . "\n";
								}
							}
							else
							{
								$result2 = mysql_query($q, $this->link);
								while($row2 = mysql_fetch_row($result2))
								{
									$txt = "";
									foreach ($row2 as $val)
									{
										$val = mysql_real_escape_string($val, $this->link);
										$txt .= "'" . $val . "', " ;
									}
									if ( $txt != "" )
										$txt = substr ( $txt, 0, strlen ($txt) - 2 ) ;

									$this->text .= "INSERT INTO " . $select . " VALUES(" . $txt . ")";
									$this->text .= self::FIELDSEP . "\n";
								}
							}
							$this->text .= "UNLOCK TABLES";
							$this->text .= self::FIELDSEP . "\n";
							
							$this->text .= "\n";

							@mysql_free_result($result2);
						}
					}
					@mysql_free_result($result);
				}
				//@mysql_free_result($res);
			}
		
			$this->text .= "\n";
			$this->text .= "SET UNIQUE_CHECKS = 1" . self::FIELDSEP . "\n";
			$this->text .= "SET FOREIGN_KEY_CHECKS = 1" . self::FIELDSEP . "\n";
			$this->text .= "SET COMMIT" . self::FIELDSEP . "\n";
			$this->text .= "SET AUTOCOMMIT = 1" . self::FIELDSEP . "\n";
			$this->text .= "\n";
			
			//* finish database Dump *//

/*			$this->text .= "\r\n### " ;
			if ( count($this->dbr) > 0 ) $this->text .= $this->dbr[$i];
			else 			 $this->text .= $key;
			
			$this->text .= " DATABASE DUMP COMPLETED ###\n";
*/

//			$this->text .= "\n###_DATABASE_DUMP_COMPLETED_###\n";

			$i++;
		
		}
//		mysql_close($this->link);

		if ( !$this->setFile($this->text, $nfil, $cfil, $cmp, $ftp, $fhost, $fuser, $fpass, $fport) )
			trigger_error("Something goes wrong with file creation",E_USER_ERROR);
		
		return true;               // all things is done correctly
	}
	
	/**
	this method for restore server database
	//
	*/
	
//	public function restoreSql($server, $dbuser, $dbpass, $file, $remove = false)
	public function restoreSql($file, $remove = false)
	{
		$seldb = "";
		$sucess = false;
//echo "db: " . $file;
		if ( $this->getFile($file) )
		{
//echo "paso11";

//			$this->text = preg_replace("/###.* DATABASE DUMP COMPLETED ###/", self::FIELDSEP, $this->text);
//			$this->text = preg_replace("/###_DATABASE_DUMP_COMPLETED_###/", self::FIELDSEP, $this->text);

			// ignore database dump complete message
//	echo "paso";

// tamaño de memoria dinamica para asignacion de php.ini
$tam_mem = strlen($this->text) * 3 / 1024;
$tim_mem = $tam_mem / 10;

ini_set('max_execution_time', $tim_mem);
ini_set('max_input_time', $tim_mem);
ini_set('memory_limit', $tam_mem . 'M');

			foreach ($tt = explode(self::FIELDSEP, $this->text) as $query)
			{
//echo "paso21";
				if ( !empty($query) )
				{
//echo "paso31";
					$rs = mysql_query($query, $this->link);
			
					if ( strstr($query, "CREATE DATABASE") )
					{
						$seldb = substr($query, strpos($query,'`')+1, strlen($query));
						$seldb = substr($seldb, '0', strpos($seldb, "`"));
						mysql_select_db($seldb, $this->link);
						// could use query with "use database " and the name of database
//echo "paso41";
//						break;
					}
				}
			}
			
			@mysql_close($this->link);
			$sucess = true;
		}
		else
		{
			$sucess = false;
			//exit();
		}
/*		
		$dump = shell_exec('which mysql');
		$gdb = $dump." --host=".$server." --user=".$dbuser." --pass=".$dbpass." --database=".$seldb." < ".$file;
echo $gdb;
		@shell_exec( $gdb );
*/
		if ( $remove ) {
			// se borra el archivo temporal
			unlink( $file );
		}
				
		return $sucess;
	}
	
	public function comprobarApertura($q)
	{
		$status = 0;
		if ( !empty($q) )
		{
			echo $q;
			if ( mysql_query($q, $this->link) )
			{
				$status = 1;
			}
		}
		return $status;
	}
	
}

//*****  EXAMPLES *****//

/** FOR BACKUP FILE  **/
/**
 || $obj=new Backuprestoresql()   // you can specify information of your server or make it
 								  // default as localhost and user root with no pass
 || $obj->setDbs()              // leave it blank or * and it will backup entire server
 								  // or specify dbs "more one db1,db2,..."
 								// caution :: make sure you have rights to write on dbs you choose
 || $obj->selectTable()         // leave it blank or * and it will backup all tables
 								  // or specify tables "more one table1,table2,..."
 || $obj->backupData()     // here will be the backup you can choose compression (0,1)
 								  // ftp (0,1) if you specify ftp you must specify at least host
*/



/** FOR RESTORE FROM FILE  **/
/**
 || $obj=new Backuprestoresql()   // you can specify information of your server or make it
 								  // default as localhost and user root with no pass
 || $obj->restoreSql('file path') // specify location of your file accepted extension sql,gz,txt
*/

?>
