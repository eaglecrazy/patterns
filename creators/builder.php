<?php

/**
 * Имеет смысл использовать паттерн Строитель только тогда, когда ваши продукты
 * достаточно сложны и требуют обширной конфигурации.
 *
 * В отличие от других порождающих паттернов, различные конкретные строители
 * могут производить несвязанные продукты. Другими словами, результаты различных
 * строителей могут не всегда следовать одному и тому же интерфейсу.
 */
class SedanProduct
{
    public $engine;
    public $body;
    public $suspension;
    public $transmission;
    public $audio = '';
}

/**
 * Интерфейс Строителя объявляет создающие методы для различных частей объектов
 * Продуктов.
 */
interface CarBuilder
{
    public function reset(): void;

    public function getCar();

    public function makeEngine($power, $cylinders, $fuelType): void;

    public function makeBody($color, $seatsCount): void;

    public function makeSuspension($wheelsCount, $bumperType): void;

    public function makeTransmission($gearboxType): void;

    public function makeAudio($audio): void;
}

/**
 * Классы Конкретного Строителя следуют интерфейсу Строителя и предоставляют
 * конкретные реализации шагов построения. Ваша программа может иметь несколько
 * вариантов Строителей, реализованных по-разному.
 */
class SedanBuilder implements CarBuilder
{
    /**
     * Новый экземпляр строителя должен содержать пустой объект продукта,
     * который используется в дальнейшей сборке.
     */
    private $product;

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): void
    {
        $this->product = new SedanProduct();
    }

    public function makeEngine($power, $cylinders, $fuelType): void
    {
        $this->product->engine = "мощность: $power, цилиндров: $cylinders, топливо: $fuelType<br>";
        $engine = $this->product->engine;
        echo "Сделали двигатель $engine";
    }

    public function makeBody($color, $seatsCount): void
    {
        $this->product->body = "цвет: $color, количество пассажиров: $seatsCount<br>";
        $body = $this->product->body;
        echo "Сделали кузов $body";
    }

    public function makeSuspension($wheelsCount, $bumperType): void
    {
        $this->product->suspension = "колёс: $wheelsCount, амортизаторы: $bumperType<br>";
        $suspension = $this->product->suspension;
        echo "Сделали подвеску $suspension";
    }

    public function makeTransmission($gearboxType): void
    {
        $this->product->transmission = "КПП: $gearboxType<br>";
        $transmission = $this->product->transmission;
        echo "Сделали трансмиссию $transmission";
    }

    public function makeAudio($audio): void
    {
        $this->product->audio = "аудиосистема: $audio<br>";
        $audio = $this->product->audio;
        echo "Сделали аудио $audio";
    }


    /**
     * Конкретные Строители должны предоставить свои собственные методы
     * получения результатов. Это связано с тем, что различные типы строителей
     * могут создавать совершенно разные продукты с разными интерфейсами.
     * Поэтому такие методы не могут быть объявлены в базовом интерфейсе
     * Строителя (по крайней мере, в статически типизированном языке
     * программирования). Обратите внимание, что PHP является динамически
     * типизированным языком, и этот метод может быть в базовом интерфейсе.
     * Однако мы не будем объявлять его здесь для ясности.
     *
     * Как правило, после возвращения конечного результата клиенту, экземпляр
     * строителя должен быть готов к началу производства следующего продукта.
     * Поэтому обычной практикой является вызов метода сброса в конце тела
     * метода getProduct. Однако такое поведение не является обязательным, вы
     * можете заставить своих строителей ждать явного запроса на сброс из кода
     * клиента, прежде чем избавиться от предыдущего результата.*/
    public function getCar()
    {
        $temp = $this->product;
        $this->product = new SedanProduct();
        return $temp;
    }
}


/**
 * Директор отвечает только за выполнение шагов построения в определённой
 * последовательности. Это полезно при производстве продуктов в определённом
 * порядке или особой конфигурации. Строго говоря, класс Директор необязателен,
 * так как клиент может напрямую управлять строителями.
 */
class Director
{
    //строитель
    private $builder;

    /**
     * Директор работает с любым экземпляром строителя, который передаётся ему
     * клиентским кодом. Таким образом, клиентский код может изменить конечный
     * тип вновь собираемого продукта.
     */
    public function setBuilder(CarBuilder $builder): void
    {
        $this->builder = $builder;
    }

    /**
     * Директор может строить несколько вариаций продукта, используя одинаковые
     * шаги построения.
     */
    public function makeStandart(){
        $this->builder->makeEngine('200 лошадиных сил', '8', 'дизель');
        $this->builder->makeBody('красный', '4');
        $this->builder->makeSuspension('4', 'гидравлические');
        $this->builder->makeTransmission('автомат');
    }

    public function makeExtended(){
        $this->makeStandart();
        $this->builder->makeAudio('крутая аудиосистема');
    }

}

/**
 * Клиентский код создаёт объект-строитель, передаёт его директору, а затем
 * инициирует процесс построения. Конечный результат извлекается из объекта-
 * строителя.
 */
function clientCodeBuilder(Director $director){
    $builder = new SedanBuilder();

    //работаем с директором
    $director->setBuilder($builder);

    echo 'Стандартная комплектация<br>';
    $director->makeStandart();
    var_dump($builder->getCar());

    echo '<hr>Расширенная комплектация<br>';
    $director->makeExtended();
    var_dump($builder->getCar());

    //работаем без директора напрямую со строителем
    echo '<hr>Особенная комплектация без мотора<br>';
    $builder->makeBody('красный', '4');
    $builder->makeSuspension('4', 'гидравлические');
    $builder->makeTransmission('автомат');
    $sedan = $builder->getCar();
    var_dump($sedan);
}


$director = new Director;
clientCodeBuilder($director);
//
//
