# Padrão Factory Method

## Problemática

Em construção...

## Solução

Criação de interface para registro de log:
```php
interface LogWriter
{
    public function write(string $message): void;
}
```

Implementação da interface:
```php
class FileLogWriter implements LogWriter
{
    /** @var resource $file */
    private $file;
    public function __construct(string $filepath)
    {
        if (! file_exists($filepath)) {
            touch($filepath);
        }

        $file = fopen(filename: $filepath, mode: 'a+');
        if (! $file) {
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
```

Criação de classe abstrata que fabrica objetos que implementam a interface:
```php
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
```

Criação de classe que extende o fabricador:
```php
class FileLogManager extends LogManager
{
    public function __construct(public string $filepath)
    {
    }

    public function makeLogWriter(): LogWriter
    {
        return new FileLogWriter($this->filepath);
    }
}
```

Exemplo de utilização:
```php
$clientName = 'Tiago Lopes';
$filename   = 'order-log.log';
$filepath   = __DIR__ . '/../logs/' . $filename;

$logManager = new FileLogManager($filepath);
$logManager->log(severity: 'info', message: 'Order created for client: ' . $clientName);
```