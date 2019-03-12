<?php
/**
 * The main template file
 *
 * @package WordPress
 * @subpackage Candid Style Guide
 * @since Candid Style Guide 1.0
 */
 ?>
 <!DOCTYPE html>

 <html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<title><?php bloginfo( 'title' ) ?> <?php bloginfo( 'description' ) ?></title>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>

		<div id="page">

			<aside>

				<header>
					<h4><?php bloginfo( 'description' ) ?></h4>
				</header>

				<nav>
					<?php

					$page_links = wp_get_nav_menu_items( 'menu' );
					foreach( $page_links as $i => $page_link ):
						$page_id = $page_link->object_id;
						$page = get_page( $page_id );
						$page_title = $page_link->title;
						$page_slug = slugify( $page_title );
						$page_is_active = $page_id == $post->ID;
						$page_url = $page_is_active ? '#' : $page_link->url;

						echo '<div class="page-group' . ( $page_is_active ? ' active' : '' ) . '">';

							echo '<a class="page-link" href="' . $page_url . '">';
								echo $page_title;
							echo '</a>';

							if( $page_is_active && have_rows( 'sections', $id ) ):

								echo '<div class="section-links">';
									echo '<div class="links-inner">';

										while( have_rows( 'sections', $id ) ) : the_row();
											$section_title = get_sub_field( 'title' );
											$section_slug = slugify( $section_title );
											echo '<a class="section-link" href="' . $page->url . '#'.$section_slug.'">';
												echo $section_title;
											echo '</a>';
										endwhile;

									echo '</div>';
								echo '</div>';

							endif;

						echo '</div>';

					endforeach;
					?>
				</nav>

			</aside>


			<main>
				<div class="inner">

					<?php if( is_front_page() ): ?>

						<header id="header">
							<h1 class="candid-logo">
								How to be <span class="screen-reader-text">Candid.</span><span class="logo-text">.</span>
							</h1>
						</header>

					<?php endif; ?>

					<?php
					// $page_links = wp_get_nav_menu_items( 'menu' );
					// foreach( $pages as $i => $page ):
					$id = $post->ID;
					$page_slug = slugify( $page_title );
					
					echo '<article class="page" id="'.$page_slug.'">';

						if( $page_title = $post->post_title ):

							echo '<h2 class="page-title">' . $page_title . '</h2>';

						endif;

						if( $page_summary = $post->post_content ):

							echo '<h3 class="page-copy">' . $page_summary . '</h3>';

						endif;

						if( have_rows( 'sections', $id ) ):

							while( have_rows( 'sections', $id ) ) : the_row();

								$section_title = get_sub_field( 'title' );
								$section_slug = slugify( $section_title );
								$section_copy = get_sub_field( 'copy' );
								$section_gallery = get_sub_field( 'gallery' );

								echo '<section id="'.$section_slug.'">';
									echo '<h5 class="section-title"><strong>'.$section_title.'</strong></h5>';
									
									if( $section_copy ):
										
										echo '<div class="section-copy">';
											echo $section_copy;
										echo '</div>';

									endif;

									if( $section_gallery ):

										echo '<div class="section-gallery">';

											foreach( $section_gallery as $i => $image ):

												echo '<div class="gallery-image">';

													echo wp_get_attachment_image( $image['ID'], 'large' );

												echo '</div>';							

											endforeach;

										echo '</div>';

									endif;

								echo '</section>';

							endwhile;

						endif;

					echo '</article>';

					// endforeach;
					?>

				</div>
			</main>

		</div>			
	</body>
	<?php wp_footer(); ?>
</html>