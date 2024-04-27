<section class="news mt85">
	<div class="wrap1 pr">
			<div class="news-slider">
				<div class="swiper-wrapper">
					<?php
					$sliderQueryArgs = array(
						'post_type'      => 'news',
						'posts_per_page' => 4, // Выводим только 4 поста для слайдера
						'orderby'        => 'date',
					);
					$sliderQuery = new WP_Query($sliderQueryArgs);

					if ($sliderQuery->have_posts()):
                        while ($sliderQuery->have_posts()):
                            $sliderQuery->the_post();
					?>
						<div class="news-slider__slide swiper-slide">
							<div class="news-slider__image">
								<a href="<?= get_post_permalink() ?>" class="news-slider__link">
									<img class="news-slider__picture" src="<?= get_field('news_post_img')['url'] ? get_field('news_post_img')['url'] : '/wp-content/themes/pantum/assets/img/pantum.jpg'; ?>" width="347" height="193" loading='lazy'  decoding='async'>
									<div class="swiper-lazy-preloader"></div>
								</a>
							</div>
							<div class="news-slider__text">
								<a href="<?= get_post_permalink() ?>" class="news-slider__header f-48">Новости</a>
								<a href="<?= get_post_permalink() ?>" class="news-slider__title f-24"><?= the_title(); ?></a>
								<a href="<?= get_post_permalink() ?>" class="news-slider__show-more f-16 c-999">Подробнее</a>
							</div>
						</div>
					<?php endwhile; endif; ?>
				</div>
				<div class="news-slider-button-pagination"></div>
			</div>

			<div class="news-slider-button-prev"></div>
			<div class="news-slider-button-next"></div>
		</div>

</section>