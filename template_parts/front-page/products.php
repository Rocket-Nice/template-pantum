<section class="products mt20">
	<div class="products-slider">
		<div class="swiper-wrapper">
			<?php if (get_field('main_slider_second')): 
				foreach (get_field('main_slider_second') as $item): 
					$thumbnailMobile = wp_get_attachment_image_src($item['main_second_bg']['ID'], 'medium');
					$thumbnailTablet = wp_get_attachment_image_src($item['main_second_bg']['ID'], 'medium_large');
				?>
					<div class="products-slider__slide swiper-slide">
						<div class="products-slider__image">
							<a href="<?= $item['main_second_link']; ?>"> 
							<picture>
								<source srcset='<?= $thumbnailMobile[0]?>' media='(max-width: 475px)'>
								<source srcset='<?= $thumbnailTablet[0]?>' media='(max-width: 1024px)'>
								<img class="products-slider__picture" src="<?= $item['main_second_bg']['url']; ?>" width='296'height='197' loading='lazy' decoding='async' > 
							</picture>
							<div class="swiper-lazy-preloader"></div></a>
						</div>
						<div class="products-slider__text <?php if (!$item['black_or_white_text']) { ?> products-slider__text--white <?php } ?>">
							<h2 class="products-slider__title f-24"><?= $item['main_second_series']; ?></h2>
							<div class="products-slider__promo f-48"><?= $item['main_second_title']; ?></div>
							<a href="<?= $item['main_second_link']; ?>" class="products-slider__link">Подробнее</a>
						</div>
					</div>
			<?php endforeach; endif; ?>
		</div>
		<div class="products-slider-button-prev "></div>
		<div class="products-slider-button-next"></div>
		<div class="news-slider-pagination"></div>

		<div class="prev-slide"></div>
		<div class="next-slide"></div>
	</div>
</section>