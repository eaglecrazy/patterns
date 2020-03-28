<?php


/**
    Вместо абстрактного класса может использоваться интерфейс
 */
abstract class Vehicle
{
    private $name = 'Транспортное средство';

    abstract function move();

    public function getname()
    {
        return $this->name;
    }

    public function load()
    {
        $n = $this->getname();
        echo "$n загружен.<br>";
    }

    public function unload()
    {
        echo "$this->name  разгружен.<br>";
    }

    public function work()
    {
        $this->load();
        $this->move();
        $this->unload();
    }
}

class Car extends Vehicle
{
    private $name = 'Автомобиль';

    public function move()
    {
        echo "$this->name едет по дороге.<br>";
    }
}

class Plane extends Vehicle
{
    private $name = 'Самолёт';

    public function move()
    {
        echo "$this->name летит по воздуху.<br>";
    }
}

class Boat extends Vehicle
{
    private $name = 'Самолёт';

    public function move()
    {
        echo "$this->name плывёт по воде.<br>";
    }
}

/**
 * Класс Создатель объявляет фабричный метод, который должен возвращать объект
 * класса Продукт. Подклассы Создателя обычно предоставляют реализацию этого
 * метода.
 */
abstract class VehicleCreator
{
    /**
     * Обратите внимание, что Создатель может также обеспечить реализацию
     * фабричного метода по умолчанию.
     */
    abstract public function createVehicle(): Vehicle; //фабричный метод

    /**
     * Также заметьте, что, несмотря на название, основная обязанность Создателя
     * не заключается в создании продуктов. Обычно он содержит некоторую базовую
     * бизнес-логику, которая основана на объектах Продуктов, возвращаемых
     * фабричным методом. Подклассы могут косвенно изменять эту бизнес-логику,
     * переопределяя фабричный метод и возвращая из него другой тип продукта.
     */


    public function fabric()
    {
        echo 'На фабрике собирается транспортное средство<br>';
    }
}

/**
 * Конкретные Создатели переопределяют фабричный метод для того, чтобы изменить
 * тип результирующего продукта.
 */
class CarCreator extends VehicleCreator
{
    /**
     * Обратите внимание, что сигнатура метода по-прежнему использует тип
     * абстрактного продукта, хотя фактически из метода возвращается конкретный
     * продукт. Таким образом, Создатель может оставаться независимым от
     * конкретных классов продуктов.
     */
    public function createVehicle(): Vehicle //фабричный метод
    {
        $this->fabric();
        return new Car();
    }
}

class PlaneCreator extends VehicleCreator
{
    public function createVehicle(): Vehicle //фабричный метод
    {
        $this->fabric();
        return new Plane();
    }
}

class BoatCreator extends VehicleCreator
{
    public function createVehicle(): Vehicle //фабричный метод
    {
        $this->fabric();
        return new Boat();
    }
}


function clientCode(VehicleCreator $creator){
    echo 'Клиентский код<br>';
    $vehicle = $creator->createVehicle();
    $vehicle->work();
}

/**
 * Приложение выбирает тип создателя в зависимости от конфигурации или среды.
 */
$x = 3;
if($x === 1){
    clientCode(new CarCreator());
} elseif ($x === 2){
    clientCode(new PlaneCreator());
} else {
    clientCode(new BoatCreator());
}