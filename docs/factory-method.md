# Padrão Factory Method

## Definição

O padrão Factory Method é um design pattern criacional que **define uma interface para criar objetos, mas permite que as subclasses decidam qual classe instanciar**. Ele promove o baixo acoplamento ao eliminar a necessidade de vincular classes específicas à aplicação, delegando a responsabilidade de criação para subclasses.

## Quando Usar

- **Criação de objetos relacionados**: Quando você precisa criar famílias de objetos relacionados
- **Flexibilidade de implementação**: Permitir que subclasses escolham o tipo específico a ser criado
- **Desacoplamento**: Evitar dependência direta de classes concretas
- **Extensibilidade**: Facilitar adição de novos tipos sem modificar código existente

## Problemática

### Acoplamento Direto com Classes Concretas
Quando o código depende diretamente de classes específicas, fica difícil de manter e estender:

```php
class OrderService
{
    public function processOrder(Order $order): void
    {
        // ❌ Acoplamento direto - difícil de testar e estender
        $fileWriter = new FileLogWriter('/logs/orders.log');
        $fileWriter->write("Order {$order->getId()} processed");

        // E se quisermos logar no stdout também?
        // E se quisermos mudar o formato do log?
        // Teríamos que modificar esta classe...
    }
}
```

### Problemas da Abordagem Direta
- **Violação do Open/Closed Principle**: Modificar código para adicionar novos tipos
- **Dificuldade de testes**: Hard-coded dependencies dificultam mocking
- **Rigidez**: Mudanças nas implementações afetam múltiplas classes
- **Duplicação**: Lógica de criação espalhada pelo código

## Solução

### 1. Interface para o Produto
Definição de interface comum para todos os objetos que serão criados:

```php
interface LogWriter
{
    public function write(string $message): void;
}
```

### 2. Implementações Concretas
Diferentes implementações da interface, cada uma com sua lógica específica:

```php
class FileLogWriter implements LogWriter
{
    /** @var resource $file */
    private $file;

    public function __construct(string $filepath)
    {
        if (!file_exists($filepath)) {
            touch($filepath);
        }

        $file = fopen(filename: $filepath, mode: 'a+');
        if (!$file) {
            throw new RuntimeException('Could not open file');
        }

        $this->file = $file;
    }

    public function write(string $message): void
    {
        fwrite($this->file, $message);
    }

    public function __destruct()
    {
        fclose($this->file);
    }
}

class StdoutLogWriter implements LogWriter
{
    public function write(string $message): void
    {
        echo $message;
    }
}

class DatabaseLogWriter implements LogWriter
{
    public function __construct(private PDO $connection) {}

    public function write(string $message): void
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO logs (message, created_at) VALUES (?, ?)'
        );
        $stmt->execute([$message, date('Y-m-d H:i:s')]);
    }
}
```

### 3. Factory Method Abstrato
Classe abstrata que define o método de criação e a lógica de uso:

```php
abstract class LogManager
{
    /**
     * Template method que usa o factory method
     */
    public function log(string $severity, string $message): void
    {
        $logWriter = $this->makeLogWriter(); // Factory Method

        $today = date('Y-m-d H:i:s');
        $formattedMessage = "[$today]-[$severity]: $message" . PHP_EOL;
        
        $logWriter->write($formattedMessage);
    }

    /**
     * Factory Method - subclasses decidem qual LogWriter criar
     */
    abstract public function makeLogWriter(): LogWriter;
}
```

### 4. Factories Concretas
Implementações específicas que decidem qual produto criar:

```php
class FileLogManager extends LogManager
{
    public function __construct(private string $filepath) {}

    public function makeLogWriter(): LogWriter
    {
        return new FileLogWriter($this->filepath);
    }
}

class StdoutLogManager extends LogManager
{
    public function makeLogWriter(): LogWriter
    {
        return new StdoutLogWriter();
    }
}

class DatabaseLogManager extends LogManager
{
    public function __construct(private PDO $connection) {}

    public function makeLogWriter(): LogWriter
    {
        return new DatabaseLogWriter($this->connection);
    }
}
```

## Utilização Flexível

### Uso Básico
```php
$clientName = 'Tiago Lopes';

// ✅ Flexível - pode trocar implementação facilmente
$fileLogger = new FileLogManager(__DIR__ . '/../logs/order-log.log');
$fileLogger->log('info', "Order created for client: $clientName");

$consoleLogger = new StdoutLogManager();
$consoleLogger->log('debug', "Processing order for: $clientName");

$dbLogger = new DatabaseLogManager($pdo);
$dbLogger->log('error', "Failed to process order for: $clientName");
```

### Uso com Factory de Factories
```php
class LogManagerFactory
{
    public static function create(string $type, array $config = []): LogManager
    {
        return match($type) {
            'file' => new FileLogManager($config['filepath']),
            'stdout' => new StdoutLogManager(),
            'database' => new DatabaseLogManager($config['connection']),
            default => throw new InvalidArgumentException("Unknown log type: $type")
        };
    }
}

// Uso simplificado
$logger = LogManagerFactory::create('file', ['filepath' => '/logs/app.log']);
$logger->log('info', 'Application started');
```

### Configuração Dinâmica
```php
class OrderService
{
    public function __construct(private LogManager $logger) {}

    public function processOrder(Order $order): void
    {
        // ✅ Desacoplado - funciona com qualquer implementação
        $this->logger->log('info', "Processing order {$order->getId()}");
        
        // Lógica de processamento...
        
        $this->logger->log('info', "Order {$order->getId()} completed");
    }
}

// Flexibilidade na configuração
$orderService = new OrderService(
    new FileLogManager('/logs/orders.log')
);

// Ou para debugging
$debugOrderService = new OrderService(
    new StdoutLogManager()
);
```

## Características do Padrão

### ✅ Vantagens
- **Desacoplamento**: Cliente não depende de classes concretas
- **Extensibilidade**: Fácil adicionar novos tipos sem modificar código existente
- **Reutilização**: Template method pode ser reutilizado com diferentes factories
- **Testabilidade**: Facilita criação de mocks e testes unitários
- **Single Responsibility**: Cada factory tem uma responsabilidade específica

### ⚠️ Desvantagens
- **Complexidade**: Introduz mais classes e hierarquias
- **Overhead**: Pode ser excessivo para casos simples

## Implementação Completa no Projeto

### Estrutura Organizada
```php
<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Log;

// Interface do produto
interface LogWriter
{
    public function write(string $message): void;
}

// Creator abstrato com factory method
abstract class LogManager
{
    public function log(string $severity, string $message): void
    {
        $logWriter = $this->makeLogWriter();

        $today = date('Y-m-d H:i:s');
        $logWriter->write("[$today]-[$severity]: $message" . PHP_EOL);
    }

    abstract public function makeLogWriter(): LogWriter;
}

// Concrete creator
class FileLogManager extends LogManager
{
    public function __construct(public string $filepath) {}

    public function makeLogWriter(): LogWriter
    {
        return new FileLogWriter($this->filepath);
    }
}
```

## Variações do Padrão

### Factory Method com Parâmetros
```php
abstract class LogManager
{
    public function log(string $severity, string $message, array $context = []): void
    {
        $logWriter = $this->makeLogWriter($severity, $context);
        // ...
    }

    abstract protected function makeLogWriter(string $severity, array $context): LogWriter;
}

class AdaptiveLogManager extends LogManager
{
    protected function makeLogWriter(string $severity, array $context): LogWriter
    {
        // Escolhe o writer baseado na severidade
        return match($severity) {
            'error', 'critical' => new DatabaseLogWriter($this->connection),
            'debug' => new StdoutLogWriter(),
            default => new FileLogWriter($this->filepath)
        };
    }
}
```

### Factory Method com Registry
```php
class PluggableLogManager extends LogManager
{
    private array $writerFactories = [];

    public function registerWriterFactory(string $type, callable $factory): void
    {
        $this->writerFactories[$type] = $factory;
    }

    public function makeLogWriter(): LogWriter
    {
        $type = $this->getCurrentLogType();
        
        if (!isset($this->writerFactories[$type])) {
            throw new RuntimeException("No factory registered for type: $type");
        }

        return ($this->writerFactories[$type])();
    }
}
```

O Factory Method é fundamental para criar sistemas flexíveis e extensíveis, permitindo que o código cliente trabalhe com abstrações ao invés de implementações específicas, facilitando manutenção e evolução do software.