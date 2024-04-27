<?php wp_footer(); 
// Получаем ID главной страницы
$frontPageID = get_option('page_on_front'); ?>
    <footer class="footer">	
			<div class="wrap1">
				<div class="footer__top">
					<a href="/" class="footer__logo">
						<img src="/wp-content/themes/pantum/assets/img/footer_mobile_logo.png" alt="Логотип компании PANTUM" class='footer__logo-image' decoding='async' width='220' height='25'>
					</a>
					<ul class="social__list mobile">
							<li class="social__item">
								<a href="<?= get_field('link_to_twitter', $frontPageID); ?>" class="social__link">
									<img src="/wp-content/themes/pantum/assets/img/social_twitter.png" alt="социальная сеть twitter" class="social__image" loading='lazy' decoding='async' width='22' height='22'>
								</a>
							</li>
							<li class="social__item">
								<a href="<?= get_field('link_to_vk', $frontPageID); ?>" class="social__link">
									<img src="/wp-content/themes/pantum/assets/img/social_vk.png" alt="социальная сеть вконтакте" class="social__image" loading='lazy' decoding='async' width='22' height='22'> 
								</a>
							</li>
							<li class="social__item">
								<a href="<?= get_field('link_to_yt', $frontPageID); ?>" class="social__link">
									<img src="/wp-content/themes/pantum/assets/img/youtube.png" alt="канал на youtube" class="social__image" loading='lazy' decoding='async' width='22' height='22'>
								</a>
							</li>
							<?php 
							$socialLink = get_field('link_to_socials', $frontPageID);
							$socialLinkEnd = get_field('link_to_socials_end', $frontPageID);
							
							if ($socialLink): ?>
								<li class="social__item">
									<a href="<?= $socialLink; ?>" class="social__link">
										<img src="<?= get_field('link_to_socials_icon', $frontPageID)['url']; ?>" alt="канал на youtube" class="social__image" loading='lazy' decoding='async' width='22' height='22'>
									</a>
								</li>
							<?php endif; ?>
							<?php
							if ($socialLinkEnd): ?>
								<li class="social__item">
									<a href="<?= $socialLink; ?>" class="social__link">
										<img src="<?= get_field('link_to_socials_end_icon', $frontPageID)['url']; ?>" alt="канал на youtube" class="social__image" loading='lazy' decoding='async' width='22' height='22'>
									</a>
								</li>
							<?php endif; ?>

					</ul>
					<div class="footer__top-wrapper">
						<ul class="footer__list">
							<li class="footer__item">
								<a href="<?= get_field('first_row_first', $frontPageID); ?>" class="footer__link footer__link--header  fb"><?= get_field('first_row_first_title', $frontPageID); ?></a>
								<a href="<?= get_field('first_row_second', $frontPageID); ?>" class="footer__link"><?= get_field('first_row_second_title', $frontPageID); ?></a>
								<a href="<?= get_field('first_row_third', $frontPageID); ?>" class="footer__link"><?= get_field('first_row_third_title', $frontPageID); ?></a>
							</li>

							<li class="footer__item">
								<a href="<?= get_field('second_row_first', $frontPageID); ?>" class="footer__link footer__link--header  fb"><?= get_field('second_row_first_title', $frontPageID); ?></a>
								<a href="<?= get_field('second_row_second', $frontPageID); ?>" class="footer__link"><?= get_field('second_row_second_title', $frontPageID); ?></a>
								<a href="<?= get_field('second_row_third', $frontPageID); ?>" class="footer__link"><?= get_field('second_row_third_title', $frontPageID); ?></a>
							</li>

							<li class="footer__item">
								<a href="<?= get_field('third_row_first', $frontPageID); ?>" class="footer__link footer__link--header  fb"><?= get_field('third_row_first_title', $frontPageID); ?></a>
								<a href="<?= get_field('third_row_second', $frontPageID); ?>" class="footer__link"><?= get_field('third_row_second_title', $frontPageID); ?></a>
								<a href="<?= get_field('third_row_third', $frontPageID); ?>" class="footer__link"><?= get_field('third_row_third_title', $frontPageID); ?></a>
							</li>
						</ul>
					<div class="footer__social social">
						<p class="social__header">Будьте на связи</p>
						<ul class="social__list">
							<li class="social__item">
								<a href="<?= get_field('link_to_twitter', $frontPageID); ?>" class="social__link">
									<img src="/wp-content/themes/pantum/assets/img/social_twitter.png" alt="социальная сеть twitter" class="social__image" loading='lazy' decoding='async' width='22' height='22'>
								</a>
							</li>
							<li class="social__item">
								<a href="<?= get_field('link_to_vk', $frontPageID); ?>" class="social__link">
									<img src="/wp-content/themes/pantum/assets/img/social_vk.png" alt="социальная сеть вконтакте" class="social__image" loading='lazy' decoding='async' width='22' height='22'>
								</a>
							</li>
							<li class="social__item">
								<a href="<?= get_field('link_to_yt', $frontPageID); ?>" class="social__link">
									<img src="/wp-content/themes/pantum/assets/img/youtube.png" alt="канал на youtube" class="social__image" loading='lazy' decoding='async' width='22' height='22'>
								</a>
							</li>
							<?php 
							$socialLink = get_field('link_to_socials', $frontPageID);
							$socialLinkEnd = get_field('link_to_socials_end', $frontPageID);
							
							if ($socialLink): ?>
								<li class="social__item">
									<a href="<?= $socialLink; ?>" class="social__link">
										<img src="<?= get_field('link_to_socials_icon', $frontPageID)['url']; ?>" alt="канал на youtube" class="social__image" loading='lazy' decoding='async' width='22' height='22'>
									</a>
								</li>
							<?php endif; ?>
							<?php
							if ($socialLinkEnd): ?>
								<li class="social__item">
									<a href="<?= $socialLink; ?>" class="social__link">
										<img src="<?= get_field('link_to_socials_end_icon', $frontPageID)['url']; ?>" alt="канал на youtube" class="social__image" loading='lazy' decoding='async' width='22' height='22'>
									</a>
								</li>
							<?php endif; ?>

						</ul>
					</div>
					</div>
				</div>

				<div class="footer__bottom">
					<p class="footer__copyright">Copyright © 2024 Pantum International Limited. Все права защищены</p>

					<ul class="footer__privacy privacy">
					<li class="privacy__item">
						<a href="/privacy/" class="privacy__link">Политика конфиденциальности</a>
					</li>
					<li class="privacy__item">
						<a href="/privacy-pantum/" class="privacy__link">Политика конфиденциальности приложения Pantum</a>
					</li>
					<li class="privacy__item">
						<a href="/privacy-cookie/" class="privacy__link">Использование файлов cookie</a>
					</li>
				</ul>
				</div>
				</div>
		</footer>
</body>