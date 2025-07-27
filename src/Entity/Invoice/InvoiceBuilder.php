<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Invoice;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

abstract class InvoiceBuilder
{
    protected Invoice $invoice;
    public function __construct()
    {
        $this->invoice = new Invoice;
    }

    public function withCompany(string $company, string $cnpj): self
    {
        $this->invoice->companyName = $company;
        $this->invoice->cnpj = $cnpj;

        return $this;
    }

    public function withItem(Budget $budget): self
    {
        $this->invoice->items[] = $budget;

        return $this;
    }

    public function withNote(string $note): self
    {
        $this->invoice->note = $note;

        return $this;
    }

    abstract public function build(): Invoice;
}
