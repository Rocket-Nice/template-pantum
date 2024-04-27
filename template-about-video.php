<?php get_header();
/* Template Name: Видеотека*/
?>

<div class="main-content">
	<?php
		get_template_part('template_parts/breadcrumbs', null, [
			'imgBg' => "/wp-content/themes/pantum/assets/img/video-libr.jpg",
			'title' => 'Видеотека',
			'subTitle' => 'Просто. Понятно. Удобно',
			'textAlignLeft' => true,
			'fontSizeBig' => false,
			'fontColor' => true,
		]);

		get_template_part('template_parts/menuBox');
	?>
	<div class="about-page-wrapper">
		<h1 class="visually-hidden">Видеотeка компании Pantum</h1>
		<div class="wrap1 main_wrap sidebar videolib">
			<div class="sidebar__left sidebar-left">
				<ul class="sidebar-left__list">
					<li class="sidebar-left__item">
						<h2 class="sidebar-left__title sidebar-left__title--current f-18 about-us">
							<a href='/scene/' class="sidebar-left__link">О нас</a>
						</h2>
					</li>
					<li class="sidebar-left__item">
						<h2 class="sidebar-left__title f-18 ">
							<a href="/operation/" class="sidebar-left__link">О товаре</a>
						</h2>
						<ul class="sidebar-left__inner-list videolib__inner-list accordion__content">
							<li class="sidebar-left__inner-item">
								<a href="#" class="sidebar-left__inner-link sidebar-left__inner-link--current
								videolib__inner-link videolib__inner-link--current">Прочие инструкции</a>
							</li>
							<li class="sidebar-left__inner-item">
								<a href="#" class="sidebar-left__inner-link videolib__inner-link">Руководство по установке драйвера </a>
							</li>
							<li class="sidebar-left__inner-item">
								<a href="#" class="sidebar-left__inner-link videolib__inner-link">Руководство по распаковке нового принтера</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>

			<div class="sidebar__right sidebar-right">
			
				<ul class="sidebar-right__list mt30">
					<?php if (get_field('videoteka-about-us')): 
						foreach(get_field('videoteka-about-us') as $item): ?>
							<li class="sidebar-right__item video-item" data-video="<?= $item['videoteka-about-us_video']['url']; ?>">
								<a class="video-item__video-link tran-scale ">
									<img src="<?= ($item['videoteka-about-us_img']['url']) ? $item['videoteka-about-us_img']['url'] : '/wp-content/themes/pantum/assets/img/videoLib-video-1.jpg' ?>"  class="video-item__preview-image"  decoding='async' width='500' height='278'>
									<div class="video-item__video-mask">
										<div class="video-item__video-play-btn"></div>
									</div>
								</a>
								<div class="video-item__text-content">
									<h2 class="video-item__title f-16 fb"><?= $item['videoteka-about-us_title']; ?></h2>
									<p class="video-item__info f-14 c-999 mt10"></p>
									<p class="video-item__date f-14 c-999 mt20"><?= get_the_date('Y.m.d'); ?></p>
								</div>
							</li>	
					<?php endforeach; endif; ?>
				</ul>
			</div>
		</div>
	</div>
</div>


<div class="modal-show-video">
	<div class="modal-show-video__wrapper ">
		<video class='modal-show-video__video' controls="" poster="" preload="auto" x-webkit-airplay="true" x5-playsinline="true" webkit-playsinline="true" playsinline="true" x5-video-orientation="portraint" x5-video-player-fullscreen="true" src="">

			<source src="" type="video/mp4">
		</video>
		<a class="modal-show-video__close">
			<img src="/wp-content/themes/pantum/assets/img/video_close.png">
		</a>
	</div>
</div>
<?php get_footer(); ?>