# Padrão Singleton

## Definição

O padrão Singleton é um design pattern criacional que **garante que uma classe tenha apenas uma instância** e fornece um ponto de acesso global para essa instância. É útil quando você precisa de exatamente um objeto para coordenar ações em todo o sistema.

## Quando Usar

- **Conexões com banco de dados**: Evitar múltiplas conexões desnecessárias
- **Logs de sistema**: Centralizar a escrita de logs em um único objeto
- **Configurações globais**: Manter configurações consistentes em toda a aplicação
- **Cache**: Gerenciar um cache único para toda a aplicação

## Problemática

Classe de conexão com o banco sendo instanciada mais de uma vez, criando múltiplas conexões desnecessárias e desperdiçando recursos:

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
        return new self(); // ❌ Problema: sempre cria uma nova instância
    }
}
```

```php
$db1 = Connection::getInstance();
$db2 = Connection::getInstance();
$db3 = Connection::getInstance();

var_dump($db1 === $db2); // false - instâncias diferentes
var_dump($db2 === $db3); // false - instâncias diferentes
// Resultado: 3 conexões desnecessárias com o banco de dados
```

## Solução

Implementação do padrão Singleton com **atributo estático** que armazena a única instância da classe:

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

    // Previne clonagem da instância
    private function __clone(): void {}

    // Previne deserialização da instância
    public function __wakeup(): void
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}
```

### Uso da Solução

```php
$db1 = Connection::getInstance();
$db2 = Connection::getInstance();
$db3 = Connection::getInstance();

var_dump($db1 === $db2); // true - mesma instância
var_dump($db2 === $db3); // true - mesma instância
// Resultado: apenas 1 conexão reutilizada
```

## Características do Padrão

### ✅ Vantagens
- **Economia de recursos**: Evita criação desnecessária de objetos custosos
- **Acesso global**: Ponto de acesso único e controlado
- **Estado consistente**: Mantém estado único em toda a aplicação
- **Lazy initialization**: Instância criada apenas quando necessário

### ⚠️ Desvantagens
- **Dificulta testes**: Cria dependência global difícil de mockar
- **Viola SRP**: A classe gerencia sua própria instância além de sua responsabilidade principal
- **Problemas de concorrência**: Precisa de cuidados especiais em ambientes multi-thread

## Implementação Completa no Projeto

```php
<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Database;

use PDO;

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

    private function __clone(): void {}

    public function __wakeup(): void
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}
```

## Alternativas Modernas

Em aplicações modernas, considere usar **Dependency Injection** ao invés do Singleton para melhor testabilidade e flexibilidade:

```php
// Ao invés de Singleton
$connection = Connection::getInstance();

// Use injeção de dependência
class UserRepository
{
    public function __construct(private PDO $connection) {}
    
    public function findById(int $id): ?User
    {
        // usa $this->connection
    }
}
```
