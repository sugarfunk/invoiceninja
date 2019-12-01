<?php
/**
 * Invoice Ninja (https://invoiceninja.com)
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2019. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/AAL
 */

namespace App\Jobs\Invoice;

use App\Events\Payment\PaymentWasCreated;
use App\Factory\PaymentFactory;
use App\Jobs\Client\UpdateClientBalance;
use App\Jobs\Client\UpdateClientPaidToDate;
use App\Jobs\Company\UpdateCompanyLedgerWithPayment;
use App\Jobs\Invoice\ApplyPaymentToInvoice;
use App\Models\Invoice;
use App\Models\Payment;
use App\Repositories\InvoiceRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ApplyClientPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Payment $payment)
    {

        $this->payment = $payment;

    }

    /**
     * Execute the job.
     *
     * 
     * @return void
     */
    public function handle()
    {

        $client = $this->payment->client;
        $client->credit_balance += $this->payment->amount;
        $client->save();

    }

}