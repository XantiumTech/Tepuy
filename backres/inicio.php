<?php
// Creacion: Jose Tua
// Email: jah120@gmail.com
// Fecha: 26/06/2009

session_start();

header('Content-Type: text/html; charset=UTF-8');

@extract($_GET);	@extract($_POST);
$usuario = "root"; $claveac = "root";

$dirlibs = dirname(__FILE__) . "/libs/";
$dirback = dirname(__FILE__) . "/backups/";
$dirimag = dirname(__FILE__) . "/../shared/imagebank/";
$filedata = dirname(__FILE__) . "/../tepuy_config.php";

include ( $filedata );  // cargar últimos datos de configuración

function cApertura($ls_sql)
{
	require_once("../tepuy_config.php");
	require_once("../cfg/class_folder/tepuy_cfg_c_empresa.php");
	require_once("../shared/class_folder/tepuy_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_mensajes.php");
	$io_conect = new tepuy_include();
	$msg = new class_mensajes();

	$con = $io_conect->uf_conectar();
	$obj_sql = new class_sql($con);
	$result = $obj_sql->select($ls_sql);
	$li_row = $obj_sql->num_rows($result);
	$res = 0;
	
	if($row = $obj_sql->fetch_row($result))
	{
		$res = 1;
	}
	
	return $res;
}

$db_name = $db_rbms = "";
//$db_name = "db_ffalcon_" . date("Y");
$titulo  = "Sistema de Respaldo de DB";
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION))||(!array_key_exists("la_empresa",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "alert('Su conexion ha sido cerrada, para continuar vuelva a entrar al Sistema');";
	print "location.href='tepuy_conexion.php'";
	print "</script>";		
}
else
{
	$db_name = $_SESSION["ls_database"];
	$db_rbms = $_SESSION["ls_gestor"];
	
	$gestor = $db_rbms;
	$servid = $_SESSION["ls_hostname"];
}
/*
$autherror  = "Por favor, introduce un usuario y contraseña para acceder";

function authenticate()
{
    header('WWW-Authenticate: Basic realm="' . $titulo . '"');
    header('HTTP/1.0 401 Unauthorized');
	echo "<title>" . $titulo . "</title>";
    echo "<li><h2>" . $titulo . " :</h2>" . $autherror . ".\n";
    exit;
}
$uauth = $_SERVER['PHP_AUTH_USER'];
if ( !isset($uauth) || ($_POST['SeenBefore'] == 1 && $_POST['OldAuth'] == $uauth)) {
    authenticate();
} elseif ($uauth != $usuario or $_SERVER['PHP_AUTH_PW'] != $claveac) {
     authenticate();
}
*/

$info_guardada = 0;

if ( $_REQUEST["task"] )
{
//	$gestor = $_REQUEST["gestor"];
//	$servid = $_REQUEST["hostname"];
	$baseda = $_REQUEST["database"];

//	$puerto = $_REQUEST["port"];
//	$ulogin = $_REQUEST["login"];
//	$uclave = $_REQUEST["password"];
	
	$imlogo = $_FILES['logo']['name'];
	$iancho = 0;	$ialtur = 0;

	$checkm = $_REQUEST["opc"];
	$tamche = count( $checkm );

	$stronly = $_REQUEST["chg"]; // solo la estructura de la db

	$raddow = $_REQUEST["dow"];
	$sftp = $_REQUEST["sftp"];
	$uftp = $_REQUEST["uftp"];
	$cftp = $_REQUEST["cftp"];
	$pftp = $_REQUEST["pftp"];
	
/////////////////////////////////////////////////
	$tipsys = "";
	if ( $gestor == "MYSQLT") 	$tipsys = "my";
	if ( $gestor == "POSTGRES") $tipsys = "pg";

	require_once($dirlibs . "configuration.class.php");

	require_once ($dirlibs . $tipsys . "_backup_restore.class.php");

	$b = new configuration( $filedata );

	$b->configure();  // capturar datos iniciales de archivo config
		
	$puerto = $empresa["port"][ $b->currentSystemNumber() ] ;
	$ulogin = $empresa["login"][ $b->currentSystemNumber() ] ;
	$uclave = $empresa["password"][ $b->currentSystemNumber() ] ;

	//////////////////////////////////////////////////////
//echo $servid."-". $ulogin."-". $uclave."-". $stronly;

	$obj = new BackupRestoreSql($servid, $ulogin, $uclave, $stronly);

	$apertura = $b->retApertura();

//	$status = $obj->comprobarApertura( $apertura );
	$status = cApertura( $apertura );

//$status = 1;
//echo $status;
	if ( $status )
	{

		$tabla = $b->retTables();
		/////////////////////////////////////////////////
		// determinar tablas a recuperar informacion en la db nueva
		$arrtab = "";
		$arrcon = array();

		for($i = 0; $i <= $tamche; $i++)
		{
			$j = $checkm[$i];

			for($k = 0; $k < count( $tabla[ $j ] ); $k++)
			{
				$nn = $tabla[ $j ][ $k ];
				$arrtab .=  $nn . ",";

				for($l = 0; $l < count($tabla[$nn], 1) ; $l++)
				{
	//				if ( !empty($tabla[ $nn ][ $l ]) )
					$arrcon[ $nn ][ $l ] = $tabla[ $nn ][ $l ];
	//echo "$k - ".$nn. " - c: " . $arrcon[ $nn ][ $l ] . "<br>";
				}
			}
		}
		if ( strlen ( $arrtab ) )
		{
			$arrtab = substr ( $arrtab, 0, strlen ($arrtab) - 1 ) ;
	//		echo $arrtab;
		}

	/////////////////////////////////////////////////

		if ( $imlogo ) {

			require_once($dirlibs . "upload.class.php");

			$upload = new Upload();
			$upload->SetFileName( $imlogo );
			$upload->SetTempName($_FILES['logo']['tmp_name']);
			$upload->SetUploadDirectory( $dirimag ); //Upload directory, this should be writable
			$upload->SetValidExtensions(array('gif', 'jpg', 'jpeg', 'png')); //Extensions that are allowed if none are set all extensions will be allowed.
			//$upload->SetIsImage(true); //If this is set to be true, you can make use of the MaximumWidth and MaximumHeight functions.
			//$upload->SetMaximumWidth(60); // Maximum width of images
			//$upload->SetMaximumHeight(400); //Maximum height of images
			$upload->SetMaximumFileSize(300000); //Maximum file size in bytes, if this is not set, the value in your php.ini file will be the maximum value

			$iancho = $upload->GetWidth();
			$ialtur = $upload->GetHeight();

			if ( ! $upload->UploadFile() ) {
				echo "Error al subir el archivo...";
			}
		}
		else
		{
			$iancho = $empresa["width"][ $b->currentSystemNumber() ] ;
			$ialtur = $empresa["height"][ $b->currentSystemNumber() ] ;
			$imlogo = $empresa["logo"][ $b->currentSystemNumber() ] ;
		}

		  // nombre de db anterior
		if ( $db_name != "" )  $dbolde = $db_name ;
		else  $dbolde = $empresa["database"][ $b->currentSystemNumber() ] ;

	////////////////////////////////////////////////////////////////

//		$namefile = 'backup@' . $dbolde . "@" . date('Y-M-d',time());
		$namefile = 'bck_' . $dbolde . "_" . date('Y-M-d',time());
		$file_output = $dirback . $namefile;
		
		$obj->setDbs( $dbolde );
		$obj->setDbr( $baseda );  // base nueva

		$obj->selectTable();  // listado de tablas a recuperar informacion
		$obj->datawTable( $arrtab, $arrcon );  // listado de tablas a recuperar informacion

		// 0 = archivo descargable, 1 = crear archivo en disco, 2 = via ftp
		$ctr = false;

		if ( $raddow != 2 )
			$ctr = $obj->backupData($file_output, $raddow);
		else
			$ctr = $obj->backupData($file_output, $raddow, 1, $sftp, $uftp, $cftp, $pftp);

		
		if ( $ctr == true )
		{
			echo "<center><font color=red>";
			echo "Se creo satisfactoriamente el backup de la base de datos";
			switch ( $raddow )
			{
			case 1:
				echo " en el directorio: ";
				echo "<strong>" . $dirback . "</strong>";
				break;
			case 2:
				echo " dispuesto para ser <strong>descargado</strong>";
				break;
			case 3:
				echo " transferido vía <strong>FTP</strong>";
				break;
			}
			echo "</font></center>";
		}

	/////////////////////////////////
//$info_guardada = 1;

		$info_guardada = 0;
//echo 		$ctr;
		if ( $ctr == true )
		{
			$delfile = true;
			if ( $raddow == 1 )  $delfile = false;
//echo $file_output . ".sql" ;
//echo $delfile;
			$ctr = $obj->restoreSql($file_output . ".sql", $delfile);

			if ( $ctr )
			{
				echo "<center><font color=blue>";
				echo "Se subi&oacute; exitosamente el backup ($namefile.sql) de la base de datos";
				echo "</font></center>";
			}
			
			// se escribe en archivo inicial de configuracion de acceso a las db

			if ( ! $b->verificarDato("database", $baseda) )
			{
				// si no existe esta base de datos en archivo de configuracion
	
				$company = array($servid, $puerto, $baseda, $ulogin, $uclave, $gestor, $iancho, $ialtur, $imlogo);

				if ( $b->savenew( $company ) )  $info_guardada = 1;
			}

	/*
	echo '
	<form action="inicio.php" method="post" name="upForm" class="form-validate" autocomplete="off" enctype="multipart/form-data">

	<input class="inputbox validate required none dbnamemsg" type="file" name="nfile" />

	<input type=submit name="subir" value="Subir">
	</form>
	';
	*/
			// nombre de archivo .sql, .gz, .txt, y si va a ser borrado o no del disco
	//		$obj->restoreSql($file_output . ".sql", $delfile);

	//		$gdb = shell_exec($dirlibs . "myphpexec.sh " . $namefile . ".sql");
	/*		$gdb = shell_exec('which mysql');
		echo $gdb . "<br>";
			$com = 'sudo ' . $gdb . ' ' . $baseda . ' < "' . $file_output . '.sql" 2>&1';
		echo $com . "<br>";
			system( $com );
			*/

		}

	}

}

if ( $_REQUEST["subir"] )
	{

	$nfile  = $_FILES['nfile']['name'];
echo $nfile;
		$delfile = true;
		if ( $raddow == 1 )  $delfile = false;
		// nombre de archivo .sql, .gz, .txt, y si va a ser borrado o no del disco
	require_once ($dirlibs . "my_backup_restore.class.php");
	$obj = new BackupRestoreSql("localhost", "root", "portatil", false);

		$obj->restoreSql($dirback . $nfile, $delfile);

//		$gdb = shell_exec($dirlibs . "myphpexec.sh " . $namefile . ".sql");
/*		$gdb = shell_exec('which mysql');
	echo $gdb . "<br>";
		$com = 'sudo ' . $gdb . ' ' . $baseda . ' < "' . $file_output . '.sql" 2>&1';
	echo $com . "<br>";
		system( $com );
		*/
	}
?>

<!--<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">-->
<link href='css/template.css' rel='stylesheet' type='text/css' />

<script language="JavaScript" type="text/javascript">
<!--
	function verport() {
		var op = document.adminForm.gestor.selectedIndex;
		switch ( op )
		{
			case 1: document.adminForm.port.value = '1070';
				break;
			case 2: document.adminForm.port.value = '3306';
				break;
			case 3: document.adminForm.port.value = '5432';
				break;
			default: document.adminForm.port.value = '';
				break;
		}
		document.adminForm.hostname.value = '<?php echo $_SERVER['SERVER_NAME']; ?>';
		if ( op > 0 && op < 4 )  document.adminForm.login.focus();
		else  document.adminForm.port.focus();
	}

	function validateForm() {
		var DBname = document.adminForm.database;
/*		var DBtype = document.adminForm.gestor;
		var DBhostname = document.adminForm.hostname;
		var DBuser = document.adminForm.login;

		var regex=/^[a-zA-Z]+[a-zA-Z0-9_]*$/;

		if ( DBtype.selectedIndex == 0 ) {
			alert( 'Por favor, seleccione un tipo de base de datos' );
			DBtype.focus();
			return;
		} else if (DBhostname.value == '') {
			alert( 'Por favor, ingrese un nombre de servidor' );
			DBhostname.focus();
			return;
		} else if (DBuser.value == '') {
			alert( 'Por favor, ingrese un usuario de la base de datos' );
			DBuser.focus();
			return;
		} else*/ if (DBname.value == '') {
			alert( 'Por favor, ingrese un nombre de la base de datos' );
			DBname.focus();
			return;
		} else if (DBname.value.length > 64) {
			alert('El nombre de la base de datos no debe exceder los 64 caracteres');
			DBname.focus();
			return;
		} else if ( ! document.adminForm.sftp.disabled ) {
			var sFtp = document.adminForm.uftp;
			var uFtp = document.adminForm.uftp;
			var cFtp = document.adminForm.cftp;
			var pFtp = document.adminForm.pftp;
			if ( sFtp.value == '' )
			{
				alert( 'Por favor, ingrese la direccion (dominio o IP) del servicio FTP' );
				sFtp.focus();
				return;
			}
			if ( uFtp.value == '' )
			{
				alert( 'Por favor, ingrese un nombre de usuario para el protocolo FTP' );
				uFtp.focus();
				return;
			}
			if ( cFtp.value == '' )
			{
				alert( 'Por favor, escriba el password de usuario para el protocolo FTP' );
				cFtp.focus();
				return;
			}
			if ( pFtp.value == '' )
			{
				alert( 'Por favor, escriba el puerto del servicio FTP (21)' );
				pFtp.focus();
				return;
			}
		} else {
			verLoader('visible');
			document.adminForm.task.value = "1";
			document.adminForm.submit();
		}
	}

	function cancelForm()
	{
		if ( document.getElementById('loader').style.visibility == 'visible' )
		{
			alert( 'Disculpe, espere hasta que el proceso culmine...' );
		}
		else
		{
			parent.location.target = '_parent';
			parent.location.href = '../tepuy_menu.php';
		}
//		ventana.close(this);
	}
		
	function checker()
	{
		op = -1;
		for (i = 0; i < document.adminForm.dow.length; i++)
		{
			if ( document.adminForm.dow[i].checked )  op = i; 
		}
		if ( op == 2 )
		{
			txtFTP( false );
			document.adminForm.sftp.focus();
			document.adminForm.sftp.select();
		}
		else  txtFTP( true );
	}

	function checkOptions(v)
	{
		 for (i = 0; i < document.adminForm.elements.length; i++)
			if(document.adminForm.elements[i].type == "checkbox")
				document.adminForm.elements[i].checked = v;
	}
	
	function verifikSubs()
	{
		 for (i = 0; i < document.adminForm.elements.length; i++)
			if (document.adminForm.elements[i].type == "checkbox")
			{
				switch ( document.adminForm.elements[i].value )
				{
				case '2':
					document.getElementById('saldocont').disabled = !document.adminForm.elements[i].checked;
					if ( ! document.adminForm.elements[i].checked )
						document.getElementById('saldocont').checked = false;
					break;
				case '4':
					document.getElementById('cuentapre').disabled = !document.adminForm.elements[i].checked;
					if ( ! document.adminForm.elements[i].checked )
						document.getElementById('cuentapre').checked = false;
					break;
				}
			}
	}
	
	function loadSubs()
	{
		document.getElementById('saldocont').checked = false;
		document.getElementById('cuentapre').checked = false;
		verifikSubs();
/*
		for (j = 0; j < document.adminForm.gestor.length; j++)
		{
			if ( document.adminForm.gestor.options[j].value == '<?php echo $db_rbms; ?>' )
			{
				document.adminForm.gestor.selectedIndex = j;
				verport();
				break;
			}
		}
*/
		document.adminForm.database.select();
		document.adminForm.database.focus();
	}
	
	function irPaso2(url) {
		location.href = url;
	}
	
	function txtFTP(v)
	{
		document.adminForm.sftp.disabled = v;
		document.adminForm.uftp.disabled = v;
		document.adminForm.cftp.disabled = v;
		document.adminForm.pftp.disabled = v;
	}
	
	function verLoader(v)
	{
		 'visible';
		document.getElementById('loader').style.visibility = v;
		if (v == 'visible')  document.onkeydown = shant;
	}
	
	function verError()
	{
		alert('El ejercicio fiscal del año pasado aun no ha sido cerrado\nPor favor, confirme esta información con su administrador...');
	}
	
	function run() {
		txtFTP( true );
		checkOptions( 1 );
		verLoader('hidden');
		document.adminForm.task.value = "0"; // por defecto	
		loadSubs();
<?php
if ( $_REQUEST["task"] && ! $status ) {
?>
		verError();
<?php
  }
?>	
	}

	function shant()
	{
		alert('Disculpe, esta pagina no permite usar el teclado mientras se ejecuta en backup...');
	}
	
	window.onload = run;
//-->
</script>

<form action="inicio.php" method="post" name="adminForm" class="form-validate" autocomplete="off" enctype="multipart/form-data">
<!--<div id="right">-->
	<div id="rightpad">
		<div id="step">
			<div class="t">
		<div class="t">
			<div class="t"></div>
		</div>
		</div>
		<div class="m">
				<div class="far-right">
					<div class="button1-right"><div class="prev"><a onclick="cancelForm();" alt="Cancelar">Cancelar</a></div></div>
					<div class="button1-left"><div class="next"><a onclick="validateForm();" alt="Aceptar">Aceptar</div></div>
				</div>
				<span class="step">Configuración de la base de datos</span>
				<div id="loader"><center><img src='images/ajax-loader.gif'></center></div>
			</div>
		<div class="b">
		<div class="b">
			<div class="b"></div>
		</div>
		</div>
	</div>

	<div id="installer">
			<div class="t">
		<div class="t">
			<div class="t"></div>
		</div>
		</div>
		<div class="m">

				<h2>Parámetros de la conexión:</h2>
				<div class="install-text">
				
				<center><img src="images/database.png" /></center>
				<p>Configurar Tepuy para que funcione en su servidor requiere de cuatro pasos sencillos...</p>
				<p>Configuración básica</p>
				<p>1. Seleccionar el tipo de base de datos que utilizará en la lista desplegable (generalmente <strong>mysql</strong>)</p>
				<p>2. Escribir el nombre del servidor en el que se instalará tepuy.</p>
				<p>3. Escribir el nombre de usuario de la BD, la contraseña y el nombre de la nueva base de datos que utilizará para tepuy.</p>
				<p>4. Configuración avanzada</p>
				<p>- Si se desear hacer una base de datos nueva con tablas con información básica inicial de instalaciones anteriores de tepuy, deberá indicar cómo proceder; de lo contrario se hará un sistema estándar (vacío de datos).</p>
				<p>- Considere las opciones adicionales respecto a guardar una copia de la base de datos en disco, hacer un archivo descargable para almacenamiento secundario o envió de éste mediante protocolo de servicio FTP.</p>

							<table class="content2">
								<tr>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>
										<input type="radio" name="dow" value="1" checked="checked" onclick="checker()" />
									</td>
									<td colspan="2">
										<label for="vars_dbolddel">
											Hacer una Copia en el directorio .../tepuy/backpus/
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" name="dow" value="0" onclick="checker()" />
									</td>
									<td colspan="2">
										<label for="vars_dboldbackup">
											Hacer archivo Descargable
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" name="dow" value="2" onclick="checker()" />
									</td>
									<td colspan="2">
										<label for="vars_dboldbackup">
											Habilitar la capa FTP para la gestión de archivo
										</label>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<label for="ftphost">
											<span id="ftphostmsg">Servidor FTP</span>
										</label>
									</td>
									<td>
										<input class="inputbox validate notrequired isftp ftphostmsg" type="text" name="sftp" value="127.0.0.1" size="30"/>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<label for="ftpport">
											<span id="ftpportmsg">Puerto FTP</span>
										</label>
									</td>
									<td>
										<input class="inputbox validate notrequired isftp ftpportmsg" type="text" name="pftp" value="21" size="30"/>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<label for="ftpuser">
											<span id="ftpusermsg">Nombre del usuario FTP</span>
										</label>
									</td>
									<td>
										<input class="inputbox validate notrequired isftp ftpusermsg" type="text" name="uftp" value="" size="30"/>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<label for="ftppass">
											<span id="ftppassmsg">Contraseña de FTP</span>
										</label>
									</td>
									<td>
										<input class="inputbox validate notrequired isftp ftppassmsg" type="password" name="cftp" value="" size="30"/>
									</td>
								</tr>
							</table>

				</div>
								
				<div class="install-body">
				<div class="t">
			<div class="t">
				<div class="t"></div>
			</div>
			</div>
			<div class="m">
							<h3 class="title-smenu" title="Basico">Configuración básica</h3>
							<div class="section-smenu">
								<table class="content2">
								<tr>
									<td></td>
									<td></td>
									<td></td>
								</tr>
<!--								<tr>
									<td colspan="2">
										<label for="vars_dbtype">
											Tipo de base de datos
										</label>
										<br />
										<select id="vars_dbtype" name="gestor" class="inputbox" size="1" onchange="verport()">
										<option value="" selected>Seleccione el tipo</option>
											<option value="INFORMIX">Informix</option>
											<option value="MYSQLT">MySQL</option>
											<option value="POSTGRES">PostgreSQL</option>
										</select>
									</td>
									<td>
										<em>
										Normalmente será <strong>MySQL</strong>.
										</em>
									</td>
								</tr>

								<tr>
									<td colspan="2">
										<label for="vars_dbtype">
											Puerto de la base de datos
										</label>
										<br />
										<input id="vars_dbport" class="inputbox validate required none dbhostnamemsg" type="text" name="port" value="" />
									</td>
									<td>
										<em>
										Conjunto de <strong>cuatro</strong> digitos.
										</em>
									</td>
								</tr>

								<tr>
									<td colspan="2">
										<label for="vars_dbhostname">
											<span id="dbhostnamemsg">Nombre del servidor</span>
										</label>
										<br />
										<input id="vars_dbhostname" class="inputbox validate required none dbhostnamemsg" type="text" name="hostname" value="" />
									</td>
									<td>
										<em>
										Normalmente <strong>localhost</strong> o un nombre de host provisto por su proveedor.
										</em>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<label for="vars_dbusername">
											<span id="dbusernamemsg">Nombre de usuario</span>
										</label>
										<br />
										<input id="vars_dbusername" class="inputbox validate required none dbusernamemsg" type="text" name="login" value="" />
									</td>
									<td>
										<em>
										Puede ser algo como <strong>root</strong> o un nombre de usuario, para la base de datos, asignado por su proveedor.
										</em>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<label for="vars_dbpassword">
											Contraseña
										</label>
										<br />
										<input id="vars_dbpassword" class="inputbox" type="password" name="password" value="" />
									</td>
									<td>
										<em>
										Por razones de seguridad el uso de una contraseña para la cuenta de la base de datos MySQL es altamente recomendado. Esta es la contraseña para acceder a su base de datos. Es posible que esta información sea predeterminada por su proveedor.
										</em>
									</td>
								</tr>
-->
								<tr>
									<td colspan="2">
										<label for="vars_dbname">
											<span id="dbnamemsg">Nombre de la base de datos</span>
										</label>
										<br />
										<input id="vars_dbname" class="inputbox validate required none dbnamemsg" type="text" name="database" value="<?php echo $db_name; ?>" />
									</td>
									<td>
										<em>
										Algunos hosts permiten solo una base de datos por cuenta. Si debe instalar más de un sitio Joomla! con una misma base de datos, puede modificar la opción de prefijo de tabla en la sección <strong>Parámetros avanzados</strong> para cada sitio instalado.
										</em>
									</td>
								</tr>

								<tr>
									<td colspan="2">
										<label for="vars_logo">
											<span id="dbnamemsg">Logo organizacional</span>
										</label>
										<br />
										<input id="vars_logo" class="inputbox validate required none dbnamemsg" type="file" name="logo" />
									</td>
									<td>
										<em>
										Suba el logo institucional para identificar la sesión de trabajo en tepuy.
										</em>
									</td>
								</tr>

								</table>
								<br /><br />
							</div>
							<h3 class="title-smenu moofx-toggler" title="Avanzado">Configuración avanzada</h3>
							<div class="section-smenu moofx-slider">
								<table class="content2">
								<tr>
									<td></td>
									<td width="28%"></td>
									<td></td>
								</tr>
								<tr>
									<td>
										<input type="radio" name="chg" value="false" checked="checked" onclick="checkOptions(1); loadSubs();" />
									</td>
									<td>
										<label for="vars_dbolddel">
											Incluir un banco de datos previo
										</label>
									</td>
									<td>
										<em>
										Las tablas existentes en la base de datos, de instalaciones anteriores del tepuy, serán <strong>copiadas</strong> en el nuevo año lectivo del sistema.
										</em>
									</td>
								</tr>
								<tr>
									<td>
										<input type="radio" name="chg" value="true" onclick="checkOptions(0); loadSubs();" />
									</td>

									<td>
										<label for="vars_dboldbackup">
											Sólo estructura de la base de datos
										</label>
									</td>

									<td>
										<em>
										Se creará la estructura de las tablas en la base de datos, sin datos previos (<strong>modo estándar</strong>).
										</em>
									</td>
								</tr>
								</table>

						</div>
						<div class="clr"></div>

							<h3 class="title-smenu moofx-toggler" title="Cargar">Cargar Datos para el Proceso de Apertura de Nuevo Año</h3>
							<div class="section-smenu moofx-slider">
								<table class="content2">
								<tr>
									<td></td>
									<td width="28%"></td>
									<td></td>
								</tr>
								<tr>
									<td>
<input name="opc[]" class="element checkbox" type="checkbox" value="1" />
									</td>
									<td>
										<label for="vars_dbolddel">
Empresa
										</label>
									</td>
									<td>
										<em>
										<strong>Datos institucionales y conceptos básicos</strong>, así como también la tipificación inicial de los documentos electrónicos dentro del tepuy.
										</em>
									</td>
								</tr>
								<tr>
									<td>
<input name="opc[]" class="element checkbox" type="checkbox" value="2" onclick="verifikSubs()" />
									</td>
									<td>
										<label for="vars_dbolddel">
Plan de Cuenta Contable
										</label>
									</td>
									<td>
										<em>
										Estructuración de <strong>normativas de cuentas</strong> (a partir de necesidades organizacionales de administración) para los ejercicio fiscales correspondientes.
										</em>
									</td>
								</tr>
								<tr>
<!-- ini: depende de plan de cuenta -->
								<tr>
									<td>
									</td>
									<td>
<input name="opc[]" id="saldocont" class="element checkbox" type="checkbox" value="3" />
										<label for="vars_dbolddel">
Saldos Contables
										</label>
									</td>
									<td>
										<em>
										Acientos de  <strong>apertura y saldos</strong>.
										</em>
									</td>
								</tr>
<!-- fin: depende de plan de cuenta -->
								<tr>
									<td>
<input name="opc[]" class="element checkbox" type="checkbox" value="4" onclick="verifikSubs()" />
									</td>
									<td>
										<label for="vars_dbolddel">
Estructura Presupuestaria
										</label>
									</td>
									<td>
										<em>
										Estructura organizativa sobre <strong>proyectos o acciones centralizadas manejadas anualmente</strong>.
										</em>
									</td>
								</tr>
								<tr>
									<td>
									</td>
									<td>
<input name="opc[]" id="cuentapre" class="element checkbox" type="checkbox" value="5" />
										<label for="vars_dbolddel">
Plan de Cuenta Presupuestaria
										</label>
									</td>
									<td>
										<em>
										Proyección de la <strong>función presupuestaria</strong>.
										</em>
									</td>
								</tr>
								<tr>
									<td>
<input name="opc[]" class="element checkbox" type="checkbox" value="6" />
									</td>
									<td>
										<label for="vars_dbolddel">
Proveedores
										</label>
									</td>
									<td>
										<em>
										Datos de los <strong>proveedores de bienes y servicios</strong>.
										</em>
									</td>
								</tr>
								<tr>
									<td>
<input name="opc[]" class="element checkbox" type="checkbox" value="7" />
									</td>
									<td>
										<label for="vars_dboldbackup">
Beneficiarios
										</label>
									</td>

									<td>
										<em>
										<strong>Datos de los beneficiarios</strong> de los procesos contables.
										</em>
									</td>
								</tr>

								<tr>
									<td>
<input name="opc[]" class="element checkbox" type="checkbox" value="8" />
									</td>
									<td>
										<label for="vars_dboldbackup">
Inventario y Artículos
										</label>
									</td>
									<td>
										<em>
										Datos iniciales sobre <strong>inventarios y artículos</strong> (por familias, segmentos, componentes, unidades de medida, etc).
										</em>
									</td>
								</tr>

								<tr>
									<td>
<input name="opc[]" class="element checkbox" type="checkbox" value="9" />
									</td>
									<td>
										<label for="vars_dbolddel">
Servicios
										</label>
									</td>
									<td>
										<em>
										Datos iniciales sobre los <strong>servicios, según cargos y tipos de servicio</strong>.
										</em>
									</td>
								</tr>

								<tr>
									<td>
<input name="opc[]" class="element checkbox" type="checkbox" value="10" />
									</td>
									<td>
										<label for="vars_dbolddel">
Cuentas y Saldos de Banco
										</label>
									</td>
									<td>
										<em>
										Datos <strong>iniciales de cuentas y traslados de saldos entre cuentas de banco</strong>, así como también los movimientos de apertura.
										</em>
									</td>
								</tr>

								<tr>
									<td>
<input name="opc[]" class="element checkbox" type="checkbox" value="11" />
									</td>
									<td>
										<label for="vars_dbolddel">
Nóminas
										</label>
									</td>
									<td>
										<em>
										Estructura organizativa e históricos sobre el pago de <strong>nóminas a personal</strong>.
										</em>
									</td>
								</tr>

								<tr>
									<td>
<input name="opc[]" class="element checkbox" type="checkbox" value="12" />
									</td>
									<td>
										<label for="vars_dbolddel">
Personal
										</label>
									</td>
									<td>
										<em>
										<strong>Datos básicos del personal</strong> de la organización.
										</em>
									</td>
								</tr>

								<tr>
									<td>
<input name="opc[]" class="element checkbox" type="checkbox" value="13" />
									</td>
									<td>
										<label for="vars_dbolddel">
Definiciones de Viáticos
										</label>
									</td>
									<td>
										<em>
										Categorías, ciudades, distancias, rutas, tarifas, trasnportes, otras asignaciones, etc.
										</em>
									</td>
								</tr>

								</table>

						</div>

			</div>
			<div class="b">
				<div class="b">
					<div class="b"></div>
				</div>
			</div>
					<div class="clr"></div>
				</div>
		<div class="clr"></div>
		</div>
		<div class="b">
			<div class="b">
				<div class="b"></div>
			</div>
		</div>
		</div>
	</div>
<!--</div>-->

<div class="clr"></div>
<input type="hidden" name="task" value="" />
</form>

<br>
