<?php get_template_part( 'template-parts/email-notifications/header', '', $args  ); ?>
                                                               	<?php echo wp_kses( $args['body'], array(
																		'a'          => array(
																			'href'  => array(),
																			'title' => array()
																		),
																		'b'          => array(),
																		'i'          => array(),
																		'strong'     => array(),
																		'em'         => array(),
																		'u'          => array(),
																		'del'        => array(),
																		'blockquote' => array(),
																		'sub'        => array(),
																		'sup'        => array(),
																		'br'         => array()

																	) ); ?>
<?php get_template_part( 'template-parts/email-notifications/footer', '', $args  ); ?>