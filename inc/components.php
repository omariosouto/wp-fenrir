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
		$componentFolder = explode("/", $dir_base);
		end($componentFolder);
		$key = key($componentFolder);
		$componentFolder = $componentFolder[$key];

		// se o arquivo não existir
		if (!file_exists($file)) {
			// lançamos uma exceção
			wp_die('Arquivo [' . $file . '] não existe!');
		}

		// se o arquivo existe, o incluímos
		if (strpos($file, '.php') !== false) {
			include($file);
		}

		if (strpos($file, '.min.js') !== false) {
		    $componentUrl = get_template_directory_uri() . '/components/' . $componentFolder . '/' . $d;
		    component_enqueue_files($componentFolder, $componentUrl);
		}

		if (strpos($file, '.min.css') !== false) {
		    $componentUrl = get_template_directory_uri() . '/components/' . $componentFolder . '/' . $d;
		    component_enqueue_files($componentFolder, $componentUrl);
		}
	}

}


/**
*	@FUNCTION 'component_enqueue_files':
*	Adiciona os arquivos necessários para o funcionamento do componente no front-end
**/
function component_enqueue_files($componentName, $componentUrl)
{
	if (!wp_script_is('component-' . $componentName . '-script', 'enqueued')) 
	{
		wp_enqueue_script('component-' . $componentName . '-script', $componentUrl);
	}

	if (!wp_style_is('component-' . $componentName . '-styles', 'enqueued')) 
	{
		wp_enqueue_style('component-' . $componentName . '-styles', $componentUrl);
	}
}