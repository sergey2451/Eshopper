<?php
class Cart
{

	public static function addProduct($id)
	{
		$id = intval($id);

		// Пустой массив для товаров в корзине
		$productsCart = array();

		// Если в корзине уже есть товары (они хранятся в сессии)
		if (isset($_SESSION['products'])) {
			// То заполним наш массив товарами
			$productsInCart = $_SESSION['products'];
		}

		// Если товар есть в корзине, но был добавлен ещё раз, увеличим количество
		if (array_key_exists($id, $productsInCart)) {
			$productsInCart[$id]++;
		} else {
			// Добавляем новый товар в корзину
			$productsInCart[$id] = 1;
		}

		$_SESSION['products'] = $productsInCart;

		return self::countItems();
	}

	/**
	 * Подсчёт кколичества товаров в корзине (в сессии)
	 * @return int
	 */
	public static function countItems()
	{
		if (isset($_SESSION['products'])) {
			$count = 0;
			foreach ($_SESSION['products'] as $id => $quantity) {
				$count = $count + $quantity;
			}
			return $count;
		} else {
			return 0;
		}
	}

	public static function getProducts()
	{
		if (isset($_SESSION['products'])) {
			return $_SESSION['products'];
		}
		return false;
	}

	public static function getTotalPrice($products)
	{
		$productsInCart = self::getProducts();

		$total = 0;

		if ($productsInCart) {
			foreach ($products as $item) {
				$total += $item['price'] * $productsInCart[$item['id']];
			}
		}

		return $total;
	}

	public static function clear()
	{
		if (isset($_SESSION['products'])) {
			unset($_SESSION['products']);
		}
	}

	/**
	 * Удаляем товар с указанным id из корзины
	 */
	public static function deleteProduct($id)
	{

		// Получаем массив с идентификаторами и количеством товаров в корзине
		$productsInCart = self::getProducts();

		// Удаляем из массива элемент с указанным id
		unset($productsInCart[$id]);

		// Записываем массив товаров с удалённым элементом в сессию
		$_SESSION['products'] = $productsInCart;
	}
}
