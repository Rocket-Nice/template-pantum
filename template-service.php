<?php get_header();
/* Template Name: Сервис и поддержка */
?>

<div class="main-content">
	<?php
	get_template_part('template_parts/breadcrumbs', null, [
		'imgBg' => "/wp-content/themes/pantum/assets/img/service_and_support.jpg",
		'title' => 'Безупречный сервис',
		'subTitle' => 'Высококлассное обслуживание для каждого пользователя',
		'textAlignLeft' => false,
		'fontSizeBig' => true,
		'fontColor' => true,
	]);

	// Получаем ID главной страницы
	$frontPageID = get_option('page_on_front');
	?>
	<section class="service">
		<div class="wrap">
			<div class="service__header">
				<h1 class="service__title f-42" data-aos="fade-up">Добро пожаловать в службу поддержки клиентов Pantum</h1>
				<p class="service__subtitle f-18">Как мы можем вам помочь?</p>
			</div>

			<ul class="service__list">
				<li class="service__item">
					<a href="/driver/" class="service__link service__link--drivers">
					<div class="service__image">
						<img src="/wp-content/themes/pantum/assets/img/service_drivers.png"  decoding='async' width='50' height='100'>
					</div>
					<h2 class="service__section f-20">Драйверы</h2>
					</a>
				</li>
				<li class="service__item">
					<a href="/service-centers/" class="service__link service__link--service-centr">
					<div class="service__image">
						<img src="/wp-content/themes/pantum/assets/img/service_location.png" decoding='async' width='50' height='100' >
					</div>
					<h2 class="service__section f-20">Сервисные центры</h2>
					</a>
				</li>
				<li class="service__item">
					<a href="/service-policy/" class="service__link service__link--policy">
					<div class="service__image">
						<img src="/wp-content/themes/pantum/assets/img/service_policy.png" decoding='async' width='50' height='100'>
					</div>
					<h2 class="service__section f-20">Сервисная политика</h2>
					</a>
				</li>
				<li class="service__item">
					<a href="/authentication/" class="service__link">
					<div class="service__image">
						<img src="/wp-content/themes/pantum/assets/img/service_check.png" decoding='async' width='50' height='100'>
					</div>
					<h2 class="service__section f-20">Проверить подлинность <span>расходных материалов</span> </h2>
					</a>
				</li>
			</ul>

			<div class="service__social service-social">
				<div class="service__header service-social__header">
				<h2 class="service__title f-42">Наши социальные сети</h2>
				<p class="service__subtitle service-social__subtitle f-18">Вы можете связаться с нами в любое время</p>
				</div>


				<ul class="service-social__list">
					<li class="service-social__item">
						<a href="mailto:servicerussia@pantum.com " class="service-social__link">
							<div class="service-social__image">
								<img src="/wp-content/themes/pantum/assets/img/service_mail.png" loading='lazy'>
							</div>
							<p class="service-social__name">Servicerussia</p>
						</a>
					</li>
					<li class="service-social__item">
						<a href="<?= get_field('link_to_twitter', $frontPageID); ?>" class="service-social__link">
						<div class="service-social__image">
								<img src="/wp-content/themes/pantum/assets/img/service_twitter.png" loading='lazy' decoding='async' width='30' height='60'>
							</div>
							<p class="service-social__name">Twitter</p>
						</a>
					</li>
					<li class="service-social__item">
						<a href="<?= get_field('link_to_yt', $frontPageID); ?>" class="service-social__link">
						<div class="service-social__image">
								<img src="/wp-content/themes/pantum/assets/img/service_youtube.png" loading='lazy' decoding='async' width='30' height='60'>
							</div>
							<p class="service-social__name">Youtube</p>
						</a>
					</li>
					<li class="service-social__item">
						<a href="<?= get_field('link_to_vk', $frontPageID); ?>" class="service-social__link">
						<div class="service-social__image">
								<img src="/wp-content/themes/pantum/assets/img/service_vk.png" loading='lazy' decoding='async' width='30' height='60'>
							</div>
							<p class="service-social__name">VK</p>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</section>
</div>

<?php get_footer(); ?>