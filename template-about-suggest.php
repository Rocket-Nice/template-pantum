<?php get_header();
/* Template Name: Обращение в Pantum */
?>

<div class="main-content">

	<?php
	get_template_part('template_parts/breadcrumbs', null, [
		'imgBg' => "/wp-content/themes/pantum/assets/img/connect-pantum.jpg",
		'title' => 'Обращение в Pantum',
		'subTitle' => 'Мы здесь, чтобы ответить на ваши вопросы, решить ваши проблемы и сделать использование нашего оборудования самым удобным',
		'textAlignLeft' => true,
		'fontSizeBig' => false,
		'fontColor' => true,
	]);

	get_template_part('template_parts/menuBox');
	?>
	<section class="suggest">
		<div class="suggest__container wrap1">
			<h1 class="suggest__title f-42">Написать в Pantum</h1>

			<!-- HTML форма -->
			<form class="suggest__form suggest-form" method="post" enctype="multipart/form-data" id="suggestForm">
				<div class="suggest-form__header">
					<label class="suggest-form__title">
						<p class="suggest-form__subtitle">Ваше имя<span>*</span></p>
						<input type="text" name="name" placeholder="Введите ваше имя" class="suggest-form__input" required>
					</label>

					<label class="suggest-form__title">
						<p class="suggest-form__subtitle">Ваш E-mail<span>*</span></p>
						<input type="email" name="email" placeholder="Введите ваш E-mail" class="suggest-form__input" required>
					</label>
				</div>

				<label class="suggest-form__title">
					<p class="suggest-form__subtitle">Город<span>*</span></p>
					<input type="text" name="place" placeholder="Пожалуйста, укажите ваш город" class="suggest-form__input" required>
				</label>

				<label class="suggest-form__title">
					<p class="suggest-form__subtitle">Текст запроса (не более 500 символов)<span>*</span></p>
					<textarea name="message" class="suggest-form__message" maxlength="500" required></textarea>
					<span class="counter-text">
						<span class="counter-text__current">0</span> / <span class="counter-text__total">500</span>
					</span>
				</label>

				<label class="suggest-form__title suggest-form__image-load">
					<p class="suggest-form__load-text">Загрузить документ</p>
					<input type="file" name="file" class="suggest-form__load" multiple="multiple">
				</label>

				<ul class="suggest-form__preload-list">

				</ul>

				<label class="suggest-form__title suggest-form__title ">
					<p class="suggest-form__subtitle">Подтвердите, что вы не робот<span>*</span></p>
					<div class="suggest-form__code">
						<div class="g-recaptcha" id='g-recaptcha' data-sitekey="6LcIHr4pAAAAAOy09r725JueI0xGLFwAzkkbl1a0"></div>
						<div class="recaptcha-error"></div>
					</div>
				</label>


				<label class="suggest-form__title suggest-form__title--policy">
					<input type="checkbox" name="code" class="suggest-form__checkbox" required> Я согласен c
					<a href="/privacy/" class="suggest-form__link">&#60;Политикой конфиденциальности&#62;</a>
				</label>

				<input type="submit" class="suggest-form__button" value="Отправить">
				<div class="form-success-message">Ваше обращение успешно отправлено!</div>
			</form>


			<script type="text/javascript">
				var onloadCallback = function() {
					grecaptcha.render('g-recaptcha', {
						'6LcIHr4pAAAAALirleDre2FmDp-qADYWo7-aJMgx': '6LcIHr4pAAAAAOy09r725JueI0xGLFwAzkkbl1a0'
					});
				};
			</script>
			<script src="https://www.google.com/recaptcha/api.js" async defer>
			</script>

		</div>
	</section>
</div>

<?php get_footer(); ?>