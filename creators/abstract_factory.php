<?php

/**
 * Каждый отдельный продукт семейства продуктов должен иметь базовый интерфейс.
 * Все вариации продукта должны реализовывать этот интерфейс.
 */
interface AbstractCar
{
    public function park(AbstractGarage $garage);
}

/**
 * Конкретные продукты создаются соответствующими Конкретными Фабриками.
 */
class BigCar implements AbstractCar
{
    public function park(AbstractGarage $garage)
    {
        $garage->open();
        echo('Большая машина въехала в гараж<br>');
    }
}

class SmallCar implements AbstractCar
{
    public function park(AbstractGarage $garage)
    {
        $garage->open();
        echo('Маленькая машина въехала в гараж<br>');
    }
}


/**
 * Базовый интерфейс другого продукта. Все продукты могут взаимодействовать друг
 * с другом, но правильное взаимодействие возможно только между продуктами одной
 * и той же конкретной вариации.
 */
interface AbstractGarage
{
    public function open();
}

class BigGarage implements AbstractGarage
{
    public function open()
    {
        echo('Большой гараж открылся.<br>');
    }
}

class SmallGarage implements AbstractGarage
{
    public function open()
    {
        echo('Маленький гараж открылся.<br>');
    }
}


/**
 * Интерфейс Абстрактной Фабрики объявляет набор методов, которые возвращают
 * различные абстрактные продукты. Эти продукты называются семейством и связаны
 * темой или концепцией высокого уровня. Продукты одного семейства обычно могут
 * взаимодействовать между собой. Семейство продуктов может иметь несколько
 * вариаций, но продукты одной вариации несовместимы с продуктами другой.
 */
interface AbstractFactory
{
    public function createCar(): AbstractCar;

    public function createGarage(): AbstractGarage;
}


/**
 * Конкретная Фабрика производит семейство продуктов одной вариации. Фабрика
 * гарантирует совместимость полученных продуктов. Обратите внимание, что
 * сигнатуры методов Конкретной Фабрики возвращают абстрактный продукт, в то
 * время как внутри метода создается экземпляр конкретного продукта.
 */
class BigFactory implements AbstractFactory
{
    public function createCar(): AbstractCar
    {
        echo 'Сделали большой автомобиль<br>';
        return new BigCar();
    }

    public function createGarage(): AbstractGarage
    {
        echo 'Сделали большой гараж<br>';
        return new BigGarage();
    }
}
/**
 * Каждая Конкретная Фабрика имеет соответствующую вариацию продукта.
 */
class SmallFactory implements AbstractFactory
{
    public function createCar(): AbstractCar
    {
        echo 'Сделали маленький автомобиль<br>';
        return new SmallCar();
    }

    public function createGarage(): AbstractGarage
    {
        echo 'Сделали маленький гараж<br>';
        return new SmallGarage();
    }
}

function clientCode(AbstractFactory $factory){
    $car = $factory->createCar();
    $garage = $factory->createGarage();
    $car->park($garage);
    echo '<hr>';
}

/**
 * Теперь в зависимости от системы делаем большие или маленькие объекты
 */
clientCode(new BigFactory());
clientCode(new SmallFactory());
