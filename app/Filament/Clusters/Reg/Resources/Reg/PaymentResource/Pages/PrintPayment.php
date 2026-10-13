<?php
namespace App\Filament\Clusters\Reg\Resources\Reg\PaymentResource\Pages;

use App\Filament\Clusters\Reg\Resources\Reg\PaymentResource;
use App\Models\Reg\Payment;
use Filament\Resources\Pages\Page;


class PrintPayment extends Page
{
    protected static string $resource = PaymentResource::class;

    protected static string $view = 'filament.clusters.reg.resources.student-resource.pages.reg.print-payment';

    public Payment $record;

    public function mount(Payment $record): void
    {
        $this->record = $record;
    }


}
