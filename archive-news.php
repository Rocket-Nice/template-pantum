<?php get_header();
/* Template Name: News Archive */
?>

<?php
get_template_part('template_parts/breadcrumbs', null, [
	'imgBg' => "/wp-content/themes/pantum/assets/img/news-header.jpg",
	'title' => 'Корпоративные новости',
	'subTitle' => 'Самая последняя информация о нашей компании и ее достижениях',
	'textAlignLeft' => true,
	'fontSizeBig' => false,
	'fontColor' => true,
]);

get_template_part('template_parts/menuBox');

// Первый запрос для слайдера
$sliderQueryArgs = array(
	'post_type'      => 'news',
	'posts_per_page' => 4, // Выводим только 4 поста для слайдера
	'orderby'        => 'date',
);
$sliderQuery = new WP_Query($sliderQueryArgs);

// Второй запрос для списка новостей
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$listQueryArgs = array(
	'post_type'      => 'news',
	'posts_per_page' => 6,
	'orderby'        => 'date',
	'paged'          => $paged
);
$listQuery = new WP_Query($listQueryArgs);
?>

<div class="main-content bg-f9 ptb95">
	<div class="wrap1">
		<section class="about-news bra hid pr">
			<div class="about-news__slider news-row">
				<div class="swiper-wrapper">
					<?php
					if ($sliderQuery->have_posts()) :
						while ($sliderQuery->have_posts()) :
							$sliderQuery->the_post();
					?>
							<div class="news-row__slide swiper-slide">
								<div class="news-row__img-container">
									<a href="<?= get_post_permalink() ?>" class="news-row__card tran_scale">
										<img class='news-row__img' src="<?= get_field('news_post_img')['url'] ? get_field('news_post_img')['url'] : '/wp-content/themes/pantum/assets/img/pantum.jpg'; ?>" width="347" height="193" decoding='async'>
									</a>
								</div>

								<div class="news-row__text">
									<p class=" news-row__fresh f-16 c-fff">Актуальное</p>
									<a href="<?= get_post_permalink() ?>" class="news-row__title f-28 mt35"><?= the_title(); ?></a>
									<a href="<?= get_post_permalink() ?>" class="news-row__show-more f-18 c-999 bra mt30">Подробнее</a>
								</div>
							</div>
					<?php endwhile;
					endif; ?>
				</div>
				<div class="news-row-pagination"></div>
				<div class="news-row__buttons">
					<div class="news-row-button-prev"></div>
					<div class="news-row-button-next"></div>
				</div>
			</div>
		</section>

		<section class="newsboard mt70">
			<ul class="newsboard__list">
				<?php
				if ($listQuery->have_posts()) :
					while ($listQuery->have_posts()) :
						$listQuery->the_post();
				?>
						<li class="newsboard__item">

							<a href="<?= get_post_permalink() ?>" class="newsboard__link tran_scale">
								<img class='news-row__img' src="<?= get_field('news_post_img')['url'] ? get_field('news_post_img')['url'] : '/wp-content/themes/pantum/assets/img/pantum.jpg'; ?>" width="347" height="193" loading='lazy' decoding='async'>
							</a>


							<div class="newsboard__text">
								<a href="<?= get_post_permalink() ?>" class="newsboard__title f-24 fb "><?= the_title(); ?></a>
								<a href="<?= get_post_permalink() ?>" class="newsboard__show-more f-18 c-999 mt50">Подробнее</a>
							</div>
						</li>
				<?php endwhile;
				endif; ?>
			</ul>
		</section>

		<?php
		if ($listQuery->found_posts > 6) {
		?>
			<section class="news-pagination news">
				<ol class="news-pagination__list">
					<li class="news-pagination__item"><a href="" class="pagination-link news-pagination__link pagination-link--prev"></a></li>
					<?php
					$max_num_pages = $listQuery->max_num_pages;
					for ($i = 1; $i <= $max_num_pages; $i++) {
						echo '<li class="news-pagination__item"><a href="" class="news-pagination__link pagination-link">' . $i . '</a></li>';
					}
					?>
					<li class="news-pagination__item"><a href="" class="pagination-link news-pagination__link pagination-link--next"></a></li>
				</ol>
				<div class="news-pagination__counter"></div>
			</section>
		<?php } ?>
	</div>
</div>

<?php get_template_part('template_parts/front-page/subscribeForm') ?>

<?php get_footer(); ?>