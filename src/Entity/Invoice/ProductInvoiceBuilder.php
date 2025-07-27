<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Invoice;

class ProductInvoiceBuilder extends InvoiceBuilder
{
    public function build(): Invoice
    {
        $total = $this->invoice->getTotalValue();
        $this->invoice->taxValue = $total * 0.1;

        return $this->invoice;
    }
}
