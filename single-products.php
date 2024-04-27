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
	$selectProduct = '';

	// Проверяем, что значение получено и не пустое
	if (!empty($product_image_url)) {
	?>
		<input type="hidden" name="product_image_url" value="<?php echo esc_attr($product_image_url['url']); ?>">
	<?php
	}

	?>
	<section class="device-card mt35">
		<div class="wrap1">
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

					<li class="device-details__item device-details__item--other">Другая модель
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
													// Получаем URL записи типа 'products', к которой относится данная модель
													$post_objects = get_posts(array(
														'post_type' => 'products',
														'tax_query' => array(
															array(
																'taxonomy' => $taxonomy,
																'field' => 'term_id',
																'terms' => $model->term_id,
															),
														),
														'posts_per_page' => 1,
													));

													if ($post_objects) {
														$post = $post_objects[0];
														setup_postdata($post);
														$post_url = get_permalink($post);
														wp_reset_postdata();

														// Получаем URL текущей страницы
														$current_url = home_url($_SERVER['REQUEST_URI']);

														// Проверяем, если URL записи не равен текущему URL
														if ($post_url !== $current_url) {

															// Выводим ссылку на запись типа 'products'
															echo '<a class="video-search__select-item f-16" href="' . esc_url($post_url) . '">' . $model->name . '</a>';
														} else {
															$selectProduct = $model->name;
														}
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
		<?php if (have_rows('products_flexible') && get_field('products_technic')) : ?>
			<div class="device-info__navigation wrap1 f-18">
				<?php if (have_rows('products_flexible')) { ?>
					<a href="#anchor1" class="device-info__navigation-link">Описание</a>
				<?php } ?>
				<?php if (get_field('products_technic')) { ?>
					<a href="#anchor2" class="device-info__navigation-link">Технические характеристики</a>
				<?php } ?>
				<a href="#anchor3" class="device-info__navigation-link">Расходные материалы</a>
			</div>
		<?php endif; ?>

		<div class="device-info__description device-description" id='anchor1'>
			<?php
			if (have_rows('products_flexible')) :
				while (have_rows('products_flexible')) : the_row();
					if (get_row_layout() == 'products_flexible_big_photo_text') :
						$img = get_sub_field('products_flexible_big_photo_text_img');
						$title = get_sub_field('products_flexible_big_photo_text_title');
						$text = get_sub_field('products_flexible_big_photo_text_text');
			?>
						<div class="device-description__box1 device-box1 mt70">
							<div class="device-box1__img-wrapper">
								<img src="<?= $img['url']; ?>" class="device-box1__img" loading='lazy' decoding='async' width='375' height='141'>
							</div>
							<div class="device-box1__text-content">
								<h2 class="device-box1__title f-48 c-1a1a1a fb"><?= $title; ?></h2>
								<p class="device-box1__text f-22 mt30"><?= $text; ?></p>
							</div>
						</div>
					<?php
					elseif (get_row_layout() == 'products_flexible_left_photo') :
						$img = get_sub_field('products_flexible_left_photo_img');
						$title = get_sub_field('products_flexible_left_photo_title');
						$text = get_sub_field('products_flexible_left_photo_text');
					?>
						<div class="device-description__box2 device-box2 mt70">
							<div class="wrap1 hid">
								<div class="device-box2__content">
									<div class="device-box2__img-wrapper">
										<img src="<?= $img['url']; ?>" class="device-box5__img" class="device-box2__img" loading='lazy' decoding='async' width='167' height='94'>
									</div>
									<div class="device-box2__text-content">
										<h2 class="device-box1__title f-48 c-1a1a1a fb"><?= $title; ?></h2>
										<p class="device-box1__text f-22 mt30"><?= $text; ?></p>
									</div>
								</div>
							</div>
						</div>
					<?php
					elseif (get_row_layout() == 'products_flexible_left_photo_outline') :
						$img = get_sub_field('products_flexible_left_photo_outline_img');
						$title = get_sub_field('products_flexible_left_photo_outline_title');
						$text = get_sub_field('products_flexible_left_photo_outline_text');
					?>
						<div class="device-description__box3 device-box3  mt70 ">
							<div class="wrap2">
								<div class="device-box3__header">
									<h2 class="device-box3__title f-48 fb tc "><?= $title; ?></h2>
									<p class="device-box3__subtitle f-22 mt30 tc"><?= $text; ?></p>
								</div>

								<div class="device-box3__img-wrapper ">
									<img src="<?= $img['url']; ?>" class="device-box3__img" loading='lazy' decoding='async' width='347' height='195'>
								</div>
							</div>

						</div>
					<?php
					elseif (get_row_layout() == 'products_flexible_right_photo') :
						$img = get_sub_field('products_flexible_right_photo_img');
						$title = get_sub_field('products_flexible_right_photo_title');
						$text = get_sub_field('products_flexible_right_photo_text');
					?>
						<div class="device-description__box4 device-box4 mt70">
							<div class="wrap1">
								<div class="device-box3__header">
									<h2 class="device-box3__title f-48 fb tc "><?= $title; ?></h2>
									<p class="device-box3__subtitle f-22 mt30 tc"><?= $text; ?></p>
								</div>
							</div>
							<div class="device-box4__img-wrapper mt70">
								<img src="<?= $img['url']; ?>" class="device-box4__img" loading='lazy' decoding='async' width='375' height='156'>
							</div>
						</div>
					<?php
					elseif (get_row_layout() == 'products_flexible_text_photo_down_small') :
						$img = get_sub_field('products_flexible_text_photo_down_small_img');
						$title = get_sub_field('products_flexible_text_photo_down_small_title');
						$text = get_sub_field('products_flexible_text_photo_down_small_text');
					?>
						<div class="device-description__box5 device-box5 mt70">
							<div class="wrap1 hid">
								<div class="device-box2__content">
									<div class="device-box5__img-wrapper">
										<img src="<?= $img['url']; ?>" class="device-box5__img" loading='lazy' decoding='async' width='190' height='211'>
									</div>
									<div class="device-box5__text-content c-1a1a1a hid">
										<p class="device-box5__model f-32 bra"><?= $title; ?></p>
										<p class="device-box5__title f-32 mt10"></p>
										<p class="device-box5__subtitle f-48 fb"><?= $text; ?></p>
									</div>
								</div>
							</div>
						</div>
					<?php
					elseif (get_row_layout() == 'products_flexible_text_photo_down_big') :
						$img = get_sub_field('products_flexible_text_photo_down_small_img');
						$title = get_sub_field('products_flexible_text_photo_down_small_title');
						$text = get_sub_field('products_flexible_text_photo_down_small_text');
					?>
						<div class="device-description__box6 device-box6 mt70">
							<div class="wrap1 hid">
								<div class="device-box2__content">
									<div class="device-box2__img-wrapper">
										<img src="<?= $img['url']; ?>" class="device-box5__img" class="device-box2__img" loading='lazy' decoding='async' width='167' height='94'>
									</div>
									<div class="device-box2__text-content">
										<h2 class="device-box1__title f-48 c-1a1a1a fb"><?= $title; ?></h2>
										<p class="device-box1__text f-22 mt30"><?= $text; ?></p>
									</div>
								</div>
							</div>
						</div>
			<?php
					endif;
				endwhile;
			endif;
			?>


		</div>
		<?php if (get_field('products_technic')) : ?>
			<div class="device-info__specifications device-specifications mt70 mb90" id="anchor2">
				<div class="wrap1 hid">
					<h2 class="device-specifications__title f-48 c-1a1a1a tc fb">Технические характеристики</h2>
					<table class="device-specifations__table device-table mt40">
						<tbody>
							<?php
							if (get_field('products_technic')) :
								foreach (get_field('products_technic') as $item) :
							?>
									<tr class="device-table__row <?= ($item['products_technic_desc_hidden']) ? 'device-table__row--hidden' : '' ?>">
										<th class="device-table__header">
											<h3 class="device-table__title f-22 fb c-1a1a1a"><?= $item['products_technic_title']; ?></h3>
										</th>
										<td class="device-table__content">
											<table class="device-table__inner-table">
												<tbody>
													<?php
													if ($item['products_technic_desc']) :
														foreach ($item['products_technic_desc'] as $itemDesk) :
													?>
															<tr class="device-table__inner-row">
																<td class="device-table__key"><?= $itemDesk['products_technic_desc_subtitle']; ?></td>
																<td class="device-table__parametr"><?= $itemDesk['products_technic_desc_desc']; ?></td>
															</tr>
													<?php endforeach;
													endif; ?>
												</tbody>
											</table>
										</td>
									</tr>
							<?php endforeach;
							endif; ?>

							<tr class="device-table__row device-table__row--hidden">
								<th class="device-table__header">
									<h3 class="device-table__title f-22 fb c-1a1a1a"><?= get_field('products_technic_note_title'); ?></h3>
								</th>
								<td class="device-table__content">
									<table class="device-table__inner-table">
										<tbody>
											<?php
											if (get_field('products_technic_note_notes')) :
												foreach (get_field('products_technic_note_notes') as $item) :
											?>
													<tr class="device-table__inner-row">
														<td class="device-table__key">
														</td>
														<td class="device-table__parametr"><?= $item['products_technic_note_notes_note']; ?></td>
													</tr>
											<?php endforeach;
											endif; ?>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>

					<button type='submit' class="device-specifications__button--more f-18 c-64656a mt50">Показать все</button>

				</div>

			</div>
		<?php endif; ?>
		<div class="device-info__material device-material mt90" id='anchor3'>
			<div class="wrap1">
				<h2 class="device-material__title f-48 c-1a1a1a fb ">Расходные материалы</h2>
				<div class="device-marerial__slider-container pr mt80">
					<div class="device-material__slider">
						<div class="swiper-wrapper">
							<?php
							$model_names = array();
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
												$args = array(
													'post_type' => 'consumables',
													'tax_query' => array(
														array(
															'taxonomy' => $taxonomy,
															'field' => 'term_id',
															'terms' => $model->term_id,
														),
													),
												);

												$query = new WP_Query($args);

												if ($query->have_posts()) {
													while ($query->have_posts()) {
														$query->the_post();
														$post_url = get_permalink();
														$product_image_url = get_field('products_description_img');
														// Получаем заголовок поста
														$title = get_the_title();
														// Добавляем $model->name и заголовок в массив $model_names
														$model_names[] = array(
															'name' => $model->name,
															'url' => $post_url,
															'title' => $title,
															'img' => $product_image_url,
															'countPage' => get_field("count-pages-opt"),
														);
													}
													wp_reset_postdata();
												}
											}
										}
									}
								}
							}

							foreach ($model_names as $model_item) {
								if ($selectProduct === $model_item['name']) {
									$image_url = $model_item['img']['url'];
									// Получаем заголовок из массива $model_names
									$title = $model_item['title']; ?>

									<div class="swiper-slide device-material__slide">
										<a href="<?= esc_url($model_item['url']); ?>" class="device-material__link">
											<div class="device-material__img-wrapper tran_scale">
												<img src="<?= $image_url; ?>" class="device-material__img" loading="lazy" decoding="async" width="149" height="149">
												<div class="swiper-lazy-preloader"></div>
											</div>
											<div class="device-material__text mt20">
												<h3 class="device-material__item-title f-18 c-1a1a1a"><?= $title; ?></h3>
												<?php if ($model_item['countPage']) { ?>
													<p class="device-material__item-subtitle f-16 c-999"><?= $model_item['countPage']; ?> страниц</p>
												<?php } ?>
											</div>
										</a>
									</div>
							<?php
								}
							}
							?>
						</div>
					</div>

					<div class="device-material-pagination"></div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php get_footer(); ?>