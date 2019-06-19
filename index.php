<?php
header('Content-Type: text/html; charset=utf-8');
// Парсер товаров с сайта tinko.ru
// v 0.0.1


// подключаем библиотеку simple html dom, чтобы парсить данные
require_once 'simple_html_dom.php';

class parserTinko {
	// ссылка на сайт
	private $siteUrl = 'https://www.tinko.ru/';
	public $dataProduct = [];

	public function getCategories($url) {

	}

	public function getInfoPage() {
		$html = file_get_html('https://www.tinko.ru/catalog/product/281107/');

		// название товара
		$nameProduct = $html->find('.tovar-detail__title h1', 0)->plaintext;
		// картинка товара
		$imgProduct = $html->find('.tovar-detail__image img', 0)->src;
		// Артикул производителя
		$articulSupProduct = trim($html->find('.tovar-detail__code span')[3]->plaintext);
    	// код продукта
		$codeProduct = trim($html->find('.tovar-detail__code span')[1]->plaintext);
    	// Производитель
		$supProduct = trim($html->find('.tovar-detail__creator a')[0]->plaintext);
    	// розничная цена
		$retailPriceProduct = trim($html->find('.tovar-detail__price span')[0]->plaintext);
    	// оптовая цена
		$wholesaleProduct = trim($html->find('.tovar-detail__price span')[2]->plaintext);
    	// краткое описание
		$briefDescriptionProduct = $html->find('.tovar-detail__short-description span')[1]->plaintext;

		for ($i = 0; $i < 7; $i++) {
			$tab = $html->find('.tabs-links__mobile')[$i];
			$TabText = $html->find('.tabs-links__mobile')[$i]->plaintext;
			
			// Технические характеристики
			if ($TabText == 'Технические характеристики') {
				$characteristicsProduct = $html->find('.characteristics')[0]->innertext;
			}

			// Описание товара
			if ($TabText == 'Описание товара') {
				$descriptionProduct = $tab->next_sibling()->plaintext;
			}

			// Дополнительное оборудование
			if ($TabText == 'Дополнительное оборудование') {
				$additionalProduct = $tab->next_sibling()->innertext;
			}
			// типовые решения
			if ($TabText == 'Типовые решения') {
				$standardSolutions = [];
				foreach ($html->find('.tovar-detail__solutions-title a') as $standardSolution) {
					$standardSolutions[] = [
						'name' => $standardSolution->title,
						'url'  => $standardSolution->href
					];
				}
			}
		}

    	// сертификаты
		$certificatesProducts = [];
		foreach ($html->find('.tabs-content__item-toggle ul li a') as $certificatesProduct) {
			if (strpos($certificatesProduct, '/sertificate/download.php?file=')) {
				$certificatesProducts[] = [
					'url' => $certificatesProduct->href
				];
			}	
		}

		// документация
		$docProducts = [];
		foreach ($html->find('.tovar-detail__docks-info') as $docProduct) {
			$docProducts[] = [
				'name' =>$docProduct->children(0)->plaintext,
				'url' => $docProduct->children(1)->children(0)->href
			];
		}

		$this->dataProduct[] = [
			'name' => $nameProduct,
			'img' => $imgProduct,
			'articul' => $articulSupProduct,
			'code' => $codeProduct,
			'sup' => $supProduct,
			'rprice' => $retailPriceProduct,
			'wprice' => $wholesaleProduct,
			'bdesc' => $briefDescriptionProduct,
			'desc' => $descriptionProduct,
			'cert' => $certificatesProducts,
			'doc' => $docProducts,
			'char' => $characteristicsProduct,
			'addp' => $additionalProduct,
			'solution' => $standardSolutions
		];

		echo "<pre>"; print_r($this->dataProduct); echo  "</pre>";
	}

	// public function debug($ar) {

	// }
}

$parserTinko = new parserTinko();
$parserTinko->getInfoPage();
