<?php get_header();
/* Template Name: О нас */
?>

<div class="main-content">
	<?php
		get_template_part('template_parts/breadcrumbs', null, [
			'imgBg' => "/wp-content/themes/pantum/assets/img/about-us.jpg",
			'title' => 'Движение к совершенству',
			'subTitle' => 'Мы стремимся стать ведущим сервис-ориентированным предприятием в полиграфической отрасли',
			'textAlignLeft' => true,
			'fontSizeBig' => false,
			'fontColor' => true,
		]);

		get_template_part('template_parts/menuBox');
	?>
	<div class="about-page-wrapper">
		<div class="wrap1 about-container">
			<section class="about">
				<div class="about__info">
					<h2 class="about__title f-42"><?= get_field('video_text_title'); ?></h2>
					<div class="about__history f-20 c-64656a mt30"><?= get_field('video_text_text'); ?></div>
				</div>
				<div class="about__video-container">
					<video class='about__video' controls="" preload="auto" x-webkit-airplay="true" x5-playsinline="true" webkit-playsinline="true" playsinline="true" x5-video-orientation="portraint" x5-video-player-fullscreen="true">
							<source src="<?= get_field('video_text_video')['url']; ?>" type="video/mp4">
					</video>
					<div class="about__video-mask">
						<button class="about__video-button" ></button>
					</div>
				</div>
			</section>

			<section class="data mt75">
				<ul class="data__list">
					<li class="data__item">
						<div class="data__image-container">
							<img src="/wp-content/themes/pantum/assets/img/data-1.png" class="data__image" loading='lazy' decoding='async' width='30' height='30'>
						</div>
						<p class="data__counter counter f-48 mt35" data-to='<?= get_field("increase_counter_years_end"); ?>' data-speed="3000" data-step="10"><?= get_field("increase_counter_years_beginning"); ?></p>
						<h2 class="data__title f-18 c-64656a mt15">Год основания</h2>
					</li>
					<li class="data__item">
						<div class="data__image-container">
							<img src="/wp-content/themes/pantum/assets/img/data-2.png" class="data__image" loading='lazy' decoding='async' width='30' height='30'>
						</div>
						<p class='data__counter mt35'>
						<span class="counter f-48 " data-to='<?= get_field("increase_counter_met_end"); ?>' data-speed="3000" data-step="10000"><?= get_field("increase_counter_met_beginning"); ?></span><span class='f-48'>M²</span></p>
						<h2 class="data__title  f-18 c-64656a mt15">Собственного индустриального парка</h2>
					</li>
					<li class="data__item">
						<div class="data__image-container">
							<img src="/wp-content/themes/pantum/assets/img/data-3.png" class="data__image" loading='lazy' decoding='async' width='30' height='30'>
						</div>
						<p class='data__counter mt35'>
						<span class="counter f-48 " data-to='<?= get_field("increase_counter_country_end"); ?>' data-speed="3000" data-step="1"><?= get_field("increase_counter_country_beginning"); ?></span><span class='f-48'>+</span></p>
						<h2 class="data__title f-18 c-64656a mt15">Стран присутствия</h2>
					</li>
					<li class="data__item">
						<div class="data__image-container">
							<img src="/wp-content/themes/pantum/assets/img/data-4.png" class="data__image" loading='lazy' decoding='async' width='30' height='30'>
						</div>
						<p class='data__counter mt35'>
						<span class="counter f-48 " data-to='<?= get_field("increase_counter_percent_end"); ?>' data-speed="3000" data-step="1"><?= get_field("increase_counter_percent_beginning"); ?></span><span class='f-48'>%</span></p>
						<h2 class="data__title  f-18 c-64656a mt15">Ежегодного роста</h2>
					</li>
				</ul>
			</section>
		</div>
		<!-- Карта представительств -->
		<section class="map">
			<div class="map__wrapper wrap1">
				<h2 class="map__title f-42 c-fff ">Наша география</h2>
				<p class="map__subtitle f-22 c-fff mt15">Мы имеем представительства в более чем <?= get_field('our_location_country'); ?> странах по всему миру</p>
				<p class="map__amount mt60">
					<span class="map__count f-80 c-fff fb"><?= get_field('our_location_country'); ?></span>
					<span class="map__text f-18 c-fff mt15">Стран присутствия</span>
				</p>

				<div class="map__global mt80">
					<img class='map__img' src="/wp-content/themes/pantum/assets/img/about-map.jpg" alt="Карта с расположением представительств компании Pantum по всему миру" loading='lazy' decoding='async' width='347' height='159'>
					<div class="map__dot map-dot active dot-1" data-top="310" data-left="1100">
						<span class='map-dot__animation'>
						</span>

						<div class="map-dot__info bg-fff bra">
							<p class="map-dot__location f-18 c-333">Центр исследований и разработок</p>
							<p class="map-dot__adress f-16 c-999 mt10"> Пекин, Китай / Далянь, Китай / Шэньчжэнь, Китай / Гуанчжоу, Китай</p>
						</div>
					</div>

					<div class="map__dot map-dot active dot-2" data-top="190" data-left="1020">
						<span class='map-dot__animation'>
						</span>

						<div class="map-dot__info bg-fff bra">
							<p class="map-dot__location f-18 c-333">Российская дочерняя компания</p>
							<p class="map-dot__adress f-16 c-999 mt10"></p>
						</div>
					</div>

					<div class="map__dot map-dot active dot-3" data-top="370" data-left="1100">
						<span class='map-dot__animation'>
						</span>

						<div class="map-dot__info bg-fff bra">
							<p class="map-dot__location f-18 c-333"> Производственная база</p>
							<p class="map-dot__adress f-16 c-999 mt10"> Чжухай Китай</p>
						</div>
					</div>

					<div class="map__dot map-dot active dot-4" data-top="320" data-left="455">
						<span class='map-dot__animation'>
						</span>

						<div class="map-dot__info bg-fff bra">
							<p class="map-dot__location f-18 c-333">Филиал в Америке</p>
							<p class="map-dot__adress f-16 c-999 mt10"></p>
						</div>
					</div>
					
					<div class="map__dot map-dot active dot-5" data-top="248" data-left="776">
						<span class='map-dot__animation'>
						</span>

						<div class="map-dot__info bg-fff bra">
							<p class="map-dot__location f-18 c-333">Филиал в Голландии</p>
							<p class="map-dot__adress f-16 c-999 mt10"></p>
						</div>
					</div>
					
					<div class="map__dot map-dot active dot-6"
					data-top="250" data-left="820">
						<span class='map-dot__animation'>
						</span>

						<div class="map-dot__info bg-fff bra">
							<p class="map-dot__location f-18 c-333">Офис в Восточной Европе</p>
							<p class="map-dot__adress f-16 c-999 mt10"></p>
						</div>
					</div>
					
					<div class="map__dot map-dot active dot-7"
					data-top="480" data-left="590">
						<span class='map-dot__animation'>
						</span>

						<div class="map-dot__info bg-fff bra">
							<p class="map-dot__location f-18 c-333">Офис в Бразилии</p>
							<p class="map-dot__adress f-16 c-999 mt10"></p>
						</div>
					</div>
					
					<div class="map__dot map-dot active dot-8"
					data-top="550" data-left="560">
						<span class='map-dot__animation'>
						</span>

						<div class="map-dot__info bg-fff bra">
							<p class="map-dot__location f-18 c-333">Офис в Аргентине</p>
							<p class="map-dot__adress f-16 c-999 mt10"></p>
						</div>
					</div>
					
					<div class="map__dot map-dot active dot-9"
					data-top="405" data-left="1060">
						<span class='map-dot__animation'>
						</span>

						<div class="map-dot__info bg-fff bra">
							<p class="map-dot__location f-18 c-333">Офис в Таиланде</p>
							<p class="map-dot__adress f-16 c-999 mt10"> </p>
						</div>
					</div>
					
					<div class="map__dot map-dot active dot-10"
					data-top="388" data-left="1080">
						<span class='map-dot__animation'>
						</span>

						<div class="map-dot__info bg-fff bra">
							<p class="map-dot__location f-18 c-333">Офис во Вьетнаме</p>
							<p class="map-dot__adress f-16 c-999 mt10"></p>
						</div>
					</div>
					
					<div class="map__dot map-dot active dot-11" data-top="260" data-left="785">
						<span class='map-dot__animation'>
						</span>

						<div class="map-dot__info bg-fff bra">
							<p class="map-dot__location f-18 c-333">Офис в Германии</p>
							<p class="map-dot__adress f-16 c-999 mt10"></p>
						</div>
					</div>
					
					<div class="map__dot map-dot active dot-12" data-top="375" data-left="995">
						<span class='map-dot__animation'>
						</span>

						<div class="map-dot__info bg-fff bra">
							<p class="map-dot__location f-18 c-333">Офис в Индии</p>
							<p class="map-dot__adress f-16 c-999 mt10"></p>
						</div>
					</div>
					
					<div class="map__dot map-dot active dot-13"
					data-top="370" data-left="850">
						<span class='map-dot__animation'>
						</span>

						<div class="map-dot__info bg-fff bra">
							<p class="map-dot__location f-18 c-333">Офис на Ближнем Востоке в Африке</p>
							<p class="map-dot__adress f-16 c-999 mt10"></p>
						</div>
					</div>
					
					<div class="map__dot map-dot active dot-14"
					data-top="480" data-left="825">
						<span class='map-dot__animation'>
						</span>

						<div class="map-dot__info bg-fff bra">
							<p class="map-dot__location f-18 c-333">Офис в Южной Африке</p>
							<p class="map-dot__adress f-16 c-999 mt10"></p>
						</div>
					</div>

					<ul class="map__list c-fff">
						<?php
						$mapContainer = get_field('our_location_and_base');
						if ($mapContainer):
							foreach ($mapContainer as $location): ?>
							<li class="map__item">
								<h3 class="map__branch-title"><?= $location['our_location_base']; ?>: </h3>
								<span class='map__branch-list'><?= $location['our_location_location']; ?></span>
							</li>
						<?php endforeach; endif; ?>
					</ul>
				</div>
			</div>
		</section>

		<!-- Блок слайдера истории -->
		<section class="history mt80">
			<h2 class="history__title f-42">Наш путь</h2>
			<p class="history__subtitle f-22 c-64656a mt15">Основные события</p>
			<div class="history__top wrap1 mt65">
				<div class="history__slider history-slider">
					<div class="swiper-wrapper">
						<?php
						$ourWay = get_field('our_way');
						if ($ourWay):
							$ourWay = array_reverse($ourWay); // Переворачиваем массив
							foreach ($ourWay as $item):
						?>
							<div class="history-slider__slide swiper-slide">
								<div class="history-slider__img-container">
									<img class='history-slider__img' src="<?= $item['our_way_bg']['url']; ?>" width="347" height="183" loading='lazy'>
									<div class="swiper-lazy-preloader"></div>
								</div>

								<div class="history-slider__text">
									<p href="#" class=" history-slider__year f-48 fb"><?= $item['our_way_title']; ?></p>
									<h3 class="history-slider__title f-18 c-707070 mt15"><?= $item['our_way_subtitle']; ?></h3>
								</div>
							</div>
						<?php endforeach; endif; ?>
					</div>
				</div>

				<div class="history-button-prev"></div>
				<div class="history-button-next"></div>
			</div>
			<div class="history__thumbs mt70">
				<div class="thumbs-slider">
					<div class="swiper-wrapper">
						<?php if ($ourWay):
								foreach ($ourWay as $item):
							?>
								<div class="thumbs-slider__slide swiper-slide">
									<div class="thumbs-slider__thumb f-24 c-999 fb">
										<?= $item['our_way_date']; ?>
									</div>
								</div>
							<?php endforeach; endif; ?>
					</div>
				</div>
			</div>
		</section>

		<!-- Блок списка достижений -->
		<section class="achievement mt100">
			<div class="achievement__wrapper wrap1">
				<h2 class="achievement__title f-42">Наши достижения</h2>
				<p class="achievement__subtitle f-22 c-64656a mt15">Инновации и развитие</p>

				<ul class="achievement__list mt40">
					<?php
					$achievements = get_field('achievements');

					if ($achievements):
						$achievements = array_reverse($achievements);
						foreach ($achievements as $item):
					?>
						<li class="achievement__item">
							<p class="achievement__year f-18 c-d2232a"><?= $item['achievements_date']; ?></p>
							<p class="achievement__text f-18 c-64656a"><?= $item['achievements_text']; ?></p>
						</li>
					<?php endforeach; endif; ?>
				</ul>
			</div>
		</section>

			<!-- Блок слайдера достижений -->
			<section class="qualify wrap1 ">
				<div class="qualify__slider qualify-slider">
					<div class="swiper-wrapper">
						<?php
						$achievementsSlides = get_field('achievements_slides');
						if ($achievementsSlides):
							$achievementsSlides = array_reverse($achievementsSlides);
							foreach ($achievementsSlides as $item):
						?>
							<div class="qualify__slide qualify-slide swiper-slide">
								<a href="<?= $item['achievements_slides_img']['url']; ?>" data-fancybox="qualify-gallery" >
									<div class="qualify-slide__img">
										<img src="<?= $item['achievements_slides_img']['url']; ?>" loading='lazy' decoding='async' width='169' height='104'>
										<span class="qualify-slide__img-mask"></span>
										<div class="swiper-lazy-preloader"></div>
									</div>
									<p class="qualify-slide__nomination f-18 c-64656a mt20"><?= $item['achievements_slides_title']; ?></p>
								</a>
							</div>
						<?php endforeach; endif; ?>
					</div>
					<div class="qualify-slider-pagination"></div>
				</div>
			</section>

			<!-- Блок Running Forward -->
			<section class="forward wrap1 mt100">
				<img class="forward__bg bra" src="<?= get_field('running_forward_img')['url']; ?>" loading='lazy' decoding='async' width='375' height='691'>
				<h2 class="forward__title f-42 c-fff">Running Forward</h2>
				
				<div class="forward-slider wrap2 mt250">
					<ul class="forward__list swiper-wrapper   ">
					<?php
					$runningForward = get_field('running_forward_icon_text');

					if ($runningForward):
						foreach ($runningForward as $item):
					?>
						<li class="forward__item swiper-slide">
							<div class="forward__header">
								<h3 class="forward__subtitle f-32"><?= $item['running_forward_title']; ?></h3>
								<div class="forward__img">
									<img src="<?= $item['running_forward_icon']['url']; ?>" width="30" height="17" decoding='acync'>
									
								</div>
							
							</div>
						
							<div class="forward__text f-20 c-707070 mt35"><?= $item['running_forward_text']; ?></div>

						
						</li>

					<?php endforeach; endif; ?>
					</ul>
			
				</div>
				<div class="forward-button-prev"></div>
				<div class="forward-button-next"></div>
			</section>
	</div>
</div>

<?php get_footer(); ?>