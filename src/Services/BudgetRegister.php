<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Services;

use Exception;
use Tiagolopes\DesignPatterns\Entity\Budget\Budget;
use Tiagolopes\DesignPatterns\Entity\Status\Finalized;
use Tiagolopes\DesignPatterns\Http\HttpAdapter;

readonly class BudgetRegister
{
    public function __construct(private HttpAdapter $httpAdapter)
    {
    }

    public function register(Budget $budget): array
    {
        if (! $budget->status instanceof Finalized) {
            throw new Exception('Budget could not be registered because it is not finalized.');
        }

        return $this->httpAdapter->post(
            url: 'http://localhost:3000/budgets',
            data: [
                'value' => $budget->value,
                'items_count' => $budget->itemsCount,
            ],
        );
    }
}
