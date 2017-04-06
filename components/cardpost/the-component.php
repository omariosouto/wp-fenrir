<?php function the_component_cardpost( $args = array() ) {

		global $post;

		$default_args = array(
			'title' => $post->post_title,
			'class' => '',
			'date' => get_the_time( 'd\/m\/Y', $post->ID ),
			'excerpt' => string_count_words( get_the_excerpt($post->ID), 30 ),
			'permalink' => get_permalink( $post->ID ),
			'link_text' => 'Saiba mais',
			'thumbnail_url' => '',
		);

		$args = component_merge_args( $default_args, $args );
?>

<article class="component-cardpost <?php echo $args['class']; ?>">
	<header>
		<time><?php echo $args['date']; ?></time>
		<h2><a href="<?php echo $args['permalink']; ?>"><?php echo $args['title']; ?></a></h2>
	</header>
	<p><?php echo $args['excerpt']; ?></p>
	<a href="<?php echo $args['permalink']; ?>" class="cardpost-lnk"><?php echo $args['link_text']; ?></a>
</article>


<?php
/* Arquivos do Componente */
$dependencies = array(
	'dist/index.min.css',
);
// inclui os arquivos da lista de dependencias
component_include_dependencies( dirname(__FILE__), $dependencies );
?>

<?php } ?>