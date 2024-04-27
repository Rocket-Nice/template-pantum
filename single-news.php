
<?php get_header();
/*
Template Name: Single News
Template Post Type: news-type
*/
?>

<?php
		get_template_part('template_parts/breadcrumbs', null, [
			'imgBg' => "/wp-content/themes/pantum/assets/img/news-header.jpg",
			'title' => 'Корпоративные новости',
			'subTitle' => '',
			'textAlignLeft' => true,
			'fontSizeBig' => false,
			'fontColor' => true,
		]);

		// Получаем ID главной страницы
		$frontPageID = get_option('page_on_front');

		// Получаем ID текущей записи
		$current_post_id = get_the_ID();

		// Получаем ID предыдущей записи по дате публикации
		$prev_post_id = get_previous_post()->ID;

		// Получаем ID следующей записи по дате публикации
		$next_post_id = get_next_post()->ID;
	?>
<div class="main-content bg-f9 ptb95">
	<article class="news-page wrap bg-fff bra hid">
		<a href="javascript:window.history.back();" class="news-page__back f-16 c-e00000">В начало</a>
		<h1 class="news-page__title f-36 mt50"><?= the_title(); ?></h1>
		<div class="news-page__date f-16 c-999 mt15"><?= get_the_date('Y-m-d'); ?></div>
		<div class="news-page__content  mt25">
		<?php if (have_rows('single_news_layout')):
			while (have_rows('single_news_layout')):
				the_row();

				?>
				<?php if (get_row_layout() == 'single_news_layout_text'): ?>
					<div class="news-page__text"><?= the_sub_field('single_news_layout_text_text'); ?></div>
				<?php elseif(get_row_layout() == 'single_news_layout_img' ): 
					$imgGallery = get_sub_field('single_news_layout_text_img');
					?>
					<figure class="news-page__image">
						<?php if ($imgGallery):
							foreach($imgGallery as $item): ?>
								<img src="<?= $item['url']; ?>" alt="Обращение компании Pantum к потребителям">
						<?php endforeach; endif; ?>
					</figure>
				<?php
				endif;
				endwhile;
			endif;
		?>
		</div>

		<div class="news-page__share mt50">
			<span class="news-page__share-text f-16 c-666">Поделиться</span>
			<ul class="news-page__list share-list">
				<li class="share-list__item">
					<a href="<?= get_field('link_to_twitter', $frontPageID); ?>" class="share-list__link">
						<img src="/wp-content/themes/pantum/assets/img/social_twitter.png" alt="twitter" class="share-list__img" loading='lazy' decoding='async' width='22' height='22'>
					</a>
				</li>
				<li class="share-list__item">
					<a href="<?= get_field('link_to_vk', $frontPageID); ?>" class="share-list__link">
						<img src="/wp-content/themes/pantum/assets/img/social_vk.png" alt="VK" class="share-list__img" loading='lazy' decoding='async' width='22' height='22'>
					</a>
				</li>
				<li class="share-list__item">
					<a href="<?= get_field('link_to_yt', $frontPageID); ?>" class="share-list__link">
						<img src="/wp-content/themes/pantum/assets/img/youtube.png" alt="YouTube" class="share-list__img" loading='lazy' decoding='async' width='22' height='22'>
					</a>
				</li>
			</ul>
		</div>

		<div class="news-page__navigation mt50" <?= (empty($next_post_id)) ? 'style="justify-content: flex-end;"' : ''; ?>>
			<?php if (!empty($next_post_id)): ?>
				<p class="news-page__nav-title">Предыдущая новость: </p>
				<a href="<?php echo esc_url(get_permalink($next_post_id)); ?>" class="news-page__prev"><?php echo esc_html(get_the_title($next_post_id)); ?></a>
			<?php endif; ?>

			<a href="/news/" class="news-page__news-list-link"></a>

			<?php if (!empty($prev_post_id)): ?>
				<p class="news-page__nav-title">Следующая новость:</p>
				<a href="<?php echo esc_url(get_permalink($prev_post_id)); ?>" class="news-page__next"><?php echo esc_html(get_the_title($prev_post_id)); ?></a>
			<?php endif; ?>
		</div>
	</article>
</div>

<?php get_template_part('template_parts/front-page/subscribeForm') ?>

<?php get_footer(); ?>