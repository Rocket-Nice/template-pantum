<?php get_header();
/* Template Name: Проверка подлинности */
?>

<div class="main-content mt100 pb120 service-authentication bg-f5">
	<?php
		get_template_part('template_parts/breadcrumbs', null, [
			'imgBg' => "",
			'title' => '',
			'subTitle' => '',
			'textAlignLeft' => true,
			'fontSizeBig' => false,
			'fontColor' => true,
		]);
	?>
	<h1 class="visually-hidden">Проверка подлинности расходных материалов</h1>
	<section class="authentication wrap1 bra ">

		<div class="authentication__wrapper bra bg-fff">

			<div class="authentication__header">
					<div class="authentication__img-container">
						<img src="/wp-content/themes/pantum/assets/img/authentication-icon.png" class="authentication__img" width='32' height='32'>
					</div>
					<h2 class="authentication__title f-18 mt20">Проверить подлинность <br> расходных материалов</h2>
			</div>

			<form action="#" class="authentication__form authentication-form mt40" enctype='multipart/form-data' method='post'>
				<label class="authentication-form__title">
				Введите серийный номер картриджа или фотобарабана <span>*</span>
					<input type="text" name='number' autocomplete='off' class="authentication-form__input">
				</label>

				<div class="authentication-form__more  mt15">
					<a class="authentication-form__link f-14 c-777">Как узнать где серийный номер?</a>

					<div class="authentication-modal">
						<p class="authentication-modal__text">Его можно найти:</p>
						<p class="authentication-modal__text">1.На коробке</p>
						<div class="authentication-modal__img">
							<img src="/wp-content/themes/pantum/assets/img/authentication-box-code.jpg">
						</div>

						<p class="authentication-modal__text">2.На картридже</p>
						<div class="authentication-modal__img">
							<img src="/wp-content/themes/pantum/assets/img/authentication-toner-code.jpg">
						
						</div>
					</div>
					
				</div>

				<div class="authentication-form__more">
					<a class="authentication-form__link f-14 c-777">Как проверить подлинность расходных материалов?</a>

					<div class="authentication-modal">
			
						<p class="authentication-modal__text">1.Смажьте серую область защитной этикетки чистой водой. Логотип исчезнет при воздействии воды и вернеться в исходное состояние после высыхания. </p>
						<div class="authentication-modal__img">
							<img class='bra' src="/wp-content/themes/pantum/assets/img/verify-1.gif">
						</div>

						<p class="authentication-modal__text">2.Закрасьте серую область фломастером. Логотип сохранит изначальный цвет и останется после высыхания.</p>
						<div class="authentication-modal__img">
							<img class='bra'  src="/wp-content/themes/pantum/assets/img/verify-2.gif">
						
						</div>
					</div>
					
				</div>

				<label class='authentication-form__title'>Введите проверочный код <span>*</span>
					<div class="authentication-form__check">
						<input autocomplete='off' type="text" name='code' class="authentication-form__input authentication-form__input--code">
						<a href="#" class="authentication-form__verify">
							<img src="/wp-content/themes/pantum/assets/img/authentication-code.png" alt="проверочный код" class="authentication-form__check-img" >
						</a>
					</div>
				</label>

				<input type="submit" class="authentication-form__button " value='Найти'>
			</form>
			
			</div>

		</section>
	<div class="authentication-maskbg"></div>

	<div class="authentication-close">
		<img src="/wp-content/themes/pantum/assets/img/authentication_close.png" >
	</div>

	<div class="authentication-result">
		<p class="authentication-result__title f-36 ">Результаты поиска</p>
		<div class="authentication-result__close">
		</div>
		<div class="authentication-result__content">
			<div class="authentication-result__success mt40">
				<p class="authentication-result__success-title f-18 bra"><img src="/wp-content/themes/pantum/assets/img/success.png" >Товар подлинный. Приятного использования.</p>
				<table class="authentication-result__table">
					<tbody>
						<tr>
							<td>Серийный номер</td>
							<td>201052001401AA0GNB</td>
						</tr>
						<tr>
							<td>Модель продукта</td>
							<td>PLC420XB</td>
						</tr>
						<tr>
							<td>Дата производства</td>
							<td>2022-11-02</td>
						</tr>
						<tr>
							<td>Страна продажи</td>
							<td>Russian Federation</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="authentication-result__error" id='errorModal'>
				<img src="/wp-content/themes/pantum/assets/img/noresult.png">
				<p class='f-16 c-999 tc mt35' id='errorMessage'>
				К сожалению, <br> мы не можем найти информацию о данном товаре.

				</p>

				<p class='f-16 c-999 tc mt35' id='errorMessage'>
				Проверочный код не верный

				</p>
			</div>
		</div>
	</div>

</div>

<?php get_footer(); ?>