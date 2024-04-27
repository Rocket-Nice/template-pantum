export default function breadcrumbs() {
    // Находим блок с классом breadcrumbs
    var breadcrumbsBlock = document.querySelector('.breadcrumbs');
    // Если блок найден
    if (breadcrumbsBlock) {
        // Находим все ссылки внутри блока с классом breadcrumbs, содержащие "/about-company/"
        var aboutCompanyLinks = breadcrumbsBlock.querySelectorAll('a[href*="/about-company/"]');
        // Проходим по каждой найденной ссылке
        aboutCompanyLinks.forEach(function (link) {
            // Получаем значение атрибута href ссылки
            var hrefValue = link.getAttribute('href');
            // Проверяем, является ли "/about-company/" последним сегментом URL
            if (hrefValue.endsWith('/about-company/')) {
                // Заменяем атрибут href ссылки на "/about-company/about-us/"
                link.setAttribute('href', hrefValue.replace('/about-company/', '/about-company/about-us/'));
            }
        });
    }
}
