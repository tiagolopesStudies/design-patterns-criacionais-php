<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Invoice;

class ServiceInvoiceBuilder extends InvoiceBuilder
{
    public function build(): Invoice
    {
        $total = $this->invoice->getTotalValue();
        $this->invoice->taxValue = $total * 0.3;

        return $this->invoice;
    }
}
