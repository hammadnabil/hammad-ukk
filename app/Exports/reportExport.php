<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class reportExport implements FromCollection, WithHeadings
{
    protected $filterType;
    protected $date;
    protected $month;

    public function __construct($filterType, $date, $month)
    {
        $this->filterType = $filterType;
        $this->date = $date;
        $this->month = $month;
    }

    public function collection()
    {
        $query = Transaction::with('cashier');

        if ($this->filterType == 'day' && $this->date) {
            $query->whereDate('created_at', $this->date);
        } elseif ($this->filterType == 'month' && $this->month) {
            $query->whereMonth('created_at', date('m', strtotime($this->month)))
                  ->whereYear('created_at', date('Y', strtotime($this->month)));
        }

        return $query->get()->map(function ($transaction) {
            return [
                'tanggal' => $transaction->paid_at->format('d-m-Y'),
                'kasir' => $transaction->cashier->name ?? 'Tidak diketahui',
                'total_transaksi' => $transaction->total_price
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kasir',
            'Total Transaksi'
        ];
    }
}
