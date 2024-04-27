<?php get_header(); ?>
<div class="main-content single-product">
	<?php
	get_template_part('template_parts/breadcrumbs', null, [
		'imgBg' => "",
		'title' => '',
		'subTitle' => '',
		'textAlignLeft' => true,
		'fontSizeBig' => false,
		'fontColor' => false,
	]);

	// Получаем ID главной страницы
	$frontPageID = get_option('page_on_front');

	// Получаем значение поля products_description_img для текущей сингл страницы товара
	$product_image_url = get_field('products_description_img');

	// Проверяем, что значение получено и не пустое
	if (!empty($product_image_url)) {
	?>
		<input type="hidden" name="product_image_url" value="<?php echo esc_attr($product_image_url['url']); ?>">
	<?php
	}
	?>
	<section class="device-card mt35">
		<div class="wrap1 consumables-card">
			<div class="device-card__sliders">
				<div class="device-slider">
					<div class="swiper-wrapper">
						<?php if (get_field('products_gallery')) :
							foreach (get_field('products_gallery') as $item) : ?>
								<div class="device-slider__slide swiper-slide">
									<img src="<?= $item['url']; ?>" class="device-slider__image" decoding='async' width='312' height='312'>
								</div>
						<?php endforeach;
						endif; ?>
					</div>
				</div>

				<div class="device-thumbs">
					<div class="device-thumbs__slider">
						<div class="swiper-wrapper">
							<?php if (get_field('products_gallery')) :
								foreach (get_field('products_gallery') as $item) : ?>
									<div class="device-thumbs__slide swiper-slide">
										<img src="<?= $item['url']; ?>" class="device-thumbs__image" decoding='async' width='38' height='38'>
									</div>
							<?php endforeach;
							endif; ?>
						</div>
					</div>

					<div class="device-thumbs-prev"></div>
					<div class="device-thumbs-next"></div>
				</div>
			</div>
			<div class="device-card__details device-details">
				<h1 class="device-details__title f-38 fb"><?= the_title(); ?></h1>
				<ul class="device-details__list f-16 c-64656a mt25">
					<?php
					$productDesc = get_field('products_description');
					if ($productDesc) :
						foreach ($productDesc as $item) : ?>
							<li class="device-details__item"><?= $item['products_description_text']; ?></li>
					<?php endforeach;
					endif; ?>

					<li class="device-details__item device-details__item--other">Для устройств:
						<div class="video-search__select-field">
							<h3 class="video-search__select-title f-16">Выберите модель</h3>
							<div class="video-search__select-list">
								<?php
								$taxonomy = 'products-type';
								$terms = get_the_terms(get_the_ID(), $taxonomy);

								$series_term_id = 0;
								foreach ($terms as $term) {
									if ($term->parent === 0) {
										$series_term_id = $term->term_id;
										break;
									}
								}

								if ($series_term_id) {
									$child_terms = get_terms(array(
										'taxonomy' => $taxonomy,
										'parent' => $series_term_id,
									));

									if (!empty($child_terms)) {
										foreach ($child_terms as $child_term) {
											$model_child = get_terms(array(
												'taxonomy' => $taxonomy,
												'parent' => $child_term->term_id,
											));

											if (!empty($model_child)) {
												foreach ($model_child as $model) {
													// Получаем URL записи типа 'consumables', к которой относится данная модель
													$post_objects = get_posts(array(
														'post_type' => 'consumables',
														'tax_query' => array(
															array(
																'taxonomy' => $taxonomy,
																'field' => 'term_id',
																'terms' => $model->term_id,
															),
														),
														'posts_per_page' => 1,
													));

													// Проверяем, выбран ли текущий дочерний элемент
													if (has_term($child_term->term_id, $taxonomy, get_the_ID())) {
														echo '<a class="video-search__select-item f-16">' . $model->name . '</a>';
													}
												}
											}
										}
									}
								}
								?>
							</div>
						</div>
					</li>
					<?php if (get_field("color-change")) : ?>
						<li class="device-details__item color-parent">Выбор цвета: <span class="color"><?= get_field("color-change"); ?></span></li>
					<?php endif; ?>
					<?php if (get_field("count-pages-opt")) : ?>
						<li class="device-details__item">Ресурс: <?= get_field("count-pages-opt"); ?> страниц</li>
					<?php endif; ?>
				</ul>

				<div class="device-details__share device-share">
					<span class="device-share__image"></span>
					<span class="device-share__text f-16 mt5">Поделиться</span>

					<ul class="device-share__social-list">
						<li class="device-share__item">
							<a href="<?= get_field('link_to_twitter', $frontPageID); ?>" class="device-share__link">
								<img src="/wp-content/themes/pantum/assets/img/social_twitter.png" alt="twitter" class="device-share__social-img" loading='lazy' decoding='async' width='22' height='22'>
							</a>
						</li>
						<li class="device-share__item">
							<a href="<?= get_field('link_to_vk', $frontPageID); ?>" class="device-share__link">
								<img src="/wp-content/themes/pantum/assets/img/social_vk.png" alt="vk" class="device-share__social-img" loading='lazy' decoding='async' width='22' height='22'>
							</a>
						</li>
						<li class="device-share__item">
							<a href="<?= get_field('link_to_yt', $frontPageID); ?>" class="device-share__link">
								<img src="/wp-content/themes/pantum/assets/img/youtube.png" alt="youtube" class="device-share__social-img" loading='lazy' decoding='async' width='22' height='22'>
							</a>
						</li>
					</ul>
				</div>
			</div>

		</div>
	</section>

	<section class="device-info">
		<div class="device-info__material device-material mt90">
			<div class="wrap1">
				<h2 class="device-material__title f-48 c-1a1a1a fb ">Рекомендации</h2>
				<div class="device-marerial__slider-container pr mt80">
					<div class="device-material__slider">
						<div class="swiper-wrapper">
							<?php
							if (get_field('rec_product')) :
								foreach (get_field('rec_product') as $item) :
							?>
									<div class="swiper-slide device-material__slide">
										<a href="<?= $item['rec_product_link']; ?>" class="device-material__link">
											<div class="device-material__img-wrapper tran_scale">
												<img src="<?= $item['rec_product_img']; ?>" class="device-material__img" loading='lazy' decoding='async' width='149' height='149'>
												<div class="swiper-lazy-preloader"></div>
											</div>

											<div class="device-material__text mt20">
												<h3 class="device-material__item-title f-18 c-1a1a1a"><?= $item['rec_product_title']; ?></h3>
												<?php if ($item['rec_product_page_count']) : ?>
													<p class="device-material__item-subtitle f-16 c-999"><?= $item['rec_product_page_count']; ?> страниц</p>
												<?php endif; ?>
											</div>
										</a>
									</div>
							<?php endforeach;
							endif; ?>
						</div>
					</div>

					<div class="device-material-pagination"></div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php get_footer(); ?>