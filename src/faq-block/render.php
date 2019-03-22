<?php

function lh_render_block_faqs( $attributes ){

  $block_title    = isset( $attributes['blockTitle'] ) ? $attributes['blockTitle'] : false;
  $postsToShow    = isset( $attributes['postsToShow'] ) ? $attributes['postsToShow'] : '5';

  $allowCrossSite = $attributes['allowCrossSite'];
  $crossSiteIds = $attributes['crossSiteIds'];

  $args = array(
		'post_type'				  => 'lh_faq',
		'posts_per_page' 	  => $postsToShow,
		'post_status' 		  => 'publish',
		'order'       		  => ( isset($attributes['order']) )?$attributes['order']:'DESC',
		'orderby'     		  => ( isset($attributes['orderBy']) )?$attributes['orderBy']:'date',
	);
  if( isset( $attributes['lh_faq_category'] ) ){
    $args['tax_query'] = array(
      array(
         'taxonomy' => 'lh_faq_category',
         'field' => 'term_id',
         'terms' => $attributes['lh_faq_category'],
       )
    );
  }

  if( $allowCrossSite && $crossSiteIds != '' ){
    $siteIDs = explode( ',', $crossSiteIds );
    $faqs = [];
    foreach( $siteIDs as $siteID ){
      switch_to_blog( $siteID );
      $tempFaqs = get_posts( $args );
      array_push( $faqs, $tempFaqs );
      restore_current_blog();
    }
  }else{
    $faqs = get_posts( $args );
  }

  $returnHtml = '<div class="wp-block-faqs">';

  $returnHtml .= '<h2>Frequently Asked Questions</h2>';

  $returnHtml .= '<div class="inner">';

  foreach ( $faqs as $post ) {
    $returnHtml .= singleFaqBlock($post);
  }

  $returnHtml .= '</div></div>';

	return $returnHtml;
}

function singleFaqBlock( $post ){
  $post_id = $post->ID;
  $title = get_the_title( $post_id );
  if ( ! $title ) {
    $title = __( '(Untitled)', 'gutenberg' );
  }
  $content = $post->post_content;
  $content = apply_filters('the_content', $content);
  $content = str_replace(']]>', ']]&gt;', $content);

  $returnHtml = sprintf(
    '<div class="faq-block"><h5 class="faq-title">%1$s</h5>%2$s</div>',
    esc_html( $title ),
    $content
  );
  return $returnHtml;
}
