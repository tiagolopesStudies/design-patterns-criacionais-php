# Padrão Singleton

## Problemática

Classe de conexão com o banco sendo instanciada mais de uma vez, criando múltiplas conexões:
```php
class Connection extends PDO
{
    private function __construct()
    {
        $dsn = 'sqlite:database.sqlite3';

        parent::__construct($dsn);
    }

    public static function getInstance(): self
    {
        return new self();
    }
}
```

```php
$db1 = Connection::getInstance();
$db2 = Connection::getInstance();
$db3 = Connection::getInstance();

var_dump($db1, $db2, $db3); // 3 instâncias diferentes
```

## Solução

Criação de atributo estático que armazena a própria instância da classe.
Ao invés de sempre criar uma instância nova, retorna a que já existe.
```php
class Connection extends PDO
{
    private static ?self $instance = null;
    private function __construct()
    {
        $dsn = 'sqlite:database.sqlite3';

        parent::__construct($dsn);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }
}
```

```php
$db1 = Connection::getInstance();
$db2 = Connection::getInstance();
$db3 = Connection::getInstance();

var_dump($db1, $db2, $db3); // as três variáveis utilizam a mesma instância
```
