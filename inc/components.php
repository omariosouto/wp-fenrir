<?php 
/**
*	@WP 'components.php'
*
*	@VAR $component_files: Array contendo os componentes que serão importados para serem
*	utilizadas na aplicação WP atual
**/

// obtemos uma referência para o diretório atual
$baseDir = substr(dirname(__FILE__), 0, -3);
$dir = $baseDir . 'components/';

$compDir = scandir($dir);
unset($compDir[0]);
unset($compDir[1]);

// percorremos a lista de arquivos definidos no Array
foreach ($compDir as $key => $componentFolder) {
	$component = $dir . $componentFolder . '/init.php';
	if (!file_exists($component)) {
		// lançamos uma exceção
		wp_die('Arquivo do Componente [' . $component . '] não existe!');
	}
	// se o arquivo existe, o incluímos
	include($component);
}


/**
*	@FUNCTION 'component_include_dependencies': Inclui os arquivos de dependência de um determinado componente
*	@param $dir_base (string): caminho do diretório do componente
*	@param $dependencies (array): array contendo as dependências a serem incluídas na aplicação
*	@return (void)
**/
function component_include_dependencies( $dir_base, $dependencies )
{
	// verificamos se foi enviado um ARRAY
	if (!is_array($dependencies)) return false;

	// percorremos o array de dependências
	foreach ($dependencies as $d)
	{
		// obtemos o caminho completo do arquivo da dependencia
		$file = $dir_base . '/' . $d;
		$componentFolderBase = explode("/", $dir_base);
		end($componentFolderBase);
		$key = key($componentFolderBase);
		$componentFolder = $componentFolderBase[$key];
		$libName = $componentFolderBase[$key];

		// se o arquivo não existir
		if (!file_exists($file)) {
			// lançamos uma exceção
			wp_die('Arquivo [' . $file . '] não existe!');
		}

		// se o arquivo existe, o incluímos
		if (strpos($file, '.php') !== false) {
			include($file);
		}

		// if (strpos($file, '.min.js') !== false) {

		// 	if(strpos($file, '/libs/')) {
		// 		//var_dump(explode($file));
		// 		//echo "<h1>" . get_template_directory_uri() . '/libs/' . $libName . "</h1>";
		// 	} else {
		//     	$componentUrl = get_template_directory_uri() . '/components/' . $componentFolder . '/' . $d;
		//     	echo "<h1>$componentUrl</h1>";
		// 	}
		    
		//     component_enqueue_files($componentFolder, $componentUrl, 'css');
		// }

		// if (strpos($file, '.min.css') !== false) {
		//     $componentUrl = get_template_directory_uri() . '/components/' . $componentFolder . '/' . $d;
		//     component_enqueue_files($componentFolder, $componentUrl, 'js');
		// }
	}

}

function my_print($value) {
	echo'<pre>';
	var_dump($value);
	echo'</pre>';
}

function get_component_name ($dir_base) {
	$componentName = explode('/', $dir_base);
	end($componentName);
	return $componentName[key($componentName)];
}

function component_include_front_dependencies( $dir_base, $dependencies ) {
	echo '[component_include_front_dependencies()]';

	$componentName = get_component_name($dir_base);

	my_print('aa');

	foreach ($dependencies as $dependencie) {
		$dependencieOrigin = $dependencie[0];
		$dependencieUrl  = $dependencie[1];
		$dependencieType  = $dependencie[2];
		component_enqueue_files($dependencieOrigin, $dependencieUrl, $componentName, $dependencieType);
	}
}


/**
*	@FUNCTION 'component_enqueue_files':
*	Adiciona os arquivos necessários para o funcionamento do componente no front-end
**/
function component_enqueue_files($dependencieOrigin, $dependencieUrl, $componentName, $dependencieType) {
	// Define o formato padrão para nome de dependencias
	$dependencieName = sanitize_title($dependencieUrl);

	switch($dependencieOrigin) {
		case 'external':
			// Chama os Scripts
			fenrir_enqueue_javascripts($dependencieUrl, $dependencieName, $dependencieType);
			fenrir_enqueue_css($dependencieUrl, $dependencieName, $dependencieType);
			//echo '<strong>'.$dependencieType.'</strong>' . $dependencieUrl . '<br>';
			break;
		case 'dist':
			// Define o nome para dependencias de distribuição
			$dependencieName = sanitize_title($componentName . '_' . $dependencieUrl);
			$dependencieUrl = get_template_directory_uri() . '/components/' . $componentName . '/dist/' . $dependencieUrl;
			// Chama os Scripts
			fenrir_enqueue_javascripts($dependencieUrl, $dependencieName, $dependencieType);
			fenrir_enqueue_css($dependencieUrl, $dependencieName, $dependencieType);
			//echo '<strong>'.$dependencieType.'</strong>' . $dependencieUrl . '<br>';
			break;
		case 'libs':
			$dependencieUrl = get_template_directory_uri() . '/libs/' . $dependencieUrl;
			// Chama os Scripts
			fenrir_enqueue_javascripts($dependencieUrl, $dependencieName, $dependencieType);
			fenrir_enqueue_css($dependencieUrl, $dependencieName, $dependencieType);
			//echo '<strong>'.$dependencieType.'</strong>' . $dependencieUrl . '<br>';
			break;
		default:
			wp_die('Componente: [' . $dependencieUrl . '], é de um tipo inválido: "' . $dependencieOrigin . '"');
	}
}

function fenrir_enqueue_javascripts($dependencieUrl, $dependencieName, $dependencieType) {
	if (!wp_script_is('component-' . $dependencieName . '-script', 'enqueued') && $dependencieType === 'js') 
	{
		wp_enqueue_script('component-' . $dependencieName . '-script', $dependencieUrl);
		return true;
	} else {
		return false;
	}
}

function fenrir_enqueue_css($dependencieUrl, $dependencieName, $dependencieType) {
	if (!wp_style_is('component-' . $dependencieName . '-styles', 'enqueued') && $dependencieType === 'css') 
	{
		wp_enqueue_style('component-' . $dependencieName . '-styles', $dependencieUrl);
		return true;
	} else {
		return false;
	}
}


/**
*	@FUNCTION 'the_component': Encapsula a chamada de componentes na aplicação. Ela checa se a função do componente chamado existe, se existir exibe o componente. Caso contrário ignora a chamada.
*	@param $component_name (string): Nome do componente a ser exibido
*	@return (void)
**/
function the_component( $component_name, $args = array() )
{
	// obtem o nome da função do componente chamado
	$component_function = 'the_component_' . $component_name;
	// verifica se a função existe
	if (function_exists($component_function)) {
		// se existe, o componente está ativo
		// então, executamos a função de exibição do componente
		call_user_func($component_function, $args);
	}
}