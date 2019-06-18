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
		$html = file_get_html('https://www.tinko.ru/catalog/product/224008/');

		// получаем название товара
		$nameProduct = $html->find('.tovar-detail__title h1', 0)->plaintext;
		// Артикул производителя
		$articulSupProduct = $html->find('.tovar-detail__code span')[3]->plaintext;
    	// код продукта
		$codeProduct = $html->find('.tovar-detail__code span')[1]->plaintext;
    	// Производитель
		$supProduct = $html->find('.tovar-detail__creator a')[0]->plaintext;
    	// розничная цена
		$retailPriceProduct = $html->find('.tovar-detail__price span')[0]->plaintext;
    	// оптовая цена
		$wholesaleProduct = $html->find('.tovar-detail__price span')[2]->plaintext;
    	// краткое описание
		$briefDescriptionProduct = $html->find('.tovar-detail__short-description span')[1]->plaintext;
 
		for ($i = 0; $i < 7; $i++) {
			$textTab = $html->find('.tabs-links__mobile')[$i];
			$textTabText = $html->find('.tabs-links__mobile')[$i]->plaintext;
			
			// Технические характеристики
			if ($textTabText == 'Технические характеристики') {
				$characteristicsProduct = $html->find('.characteristics')[0]->innertext;
			}

			// Описание товара
			if ($textTabText == 'Описание товара') {
				$descriptionProduct = $textTab->next_sibling()->plaintext;
			}

			// Дополнительное оборудование
			if ($textTabText == 'Дополнительное оборудование') {
				$additionalProduct = $textTab->next_sibling()->innertext;
			}		
		}
    	
    	// сертификаты
		$certificatesProducts = [];
		foreach ($html->find('.tabs-content__item-toggle ul li a') as $certificatesProduct) {
			if (strpos($certificatesProduct, '/sertificate/download.php?file=')) {
				$certificatesProducts[] = $certificatesProduct->href . "<br>";
			}	
		}

		// документация
		$docProducts = [];
		foreach ($html->find('.tovar-detail__docks-link a') as $docProduct) {
			$docProducts[] = $docProduct->href . "<br>";
		}
		// echo $nameProduct;
		$this->dataProduct[] = [
			'name' => $nameProduct,
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
			'addp' => $additionalProduct
		];

		echo "<pre>"; print_r($this->dataProduct); echo  "</pre>";
	}

	// public function debug($ar) {

	// }
}

$parserTinko = new parserTinko();
$parserTinko->getInfoPage();
// $parserTinko->debug($parserTinko->dataProduct);
