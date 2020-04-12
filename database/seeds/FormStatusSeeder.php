<?php

use App\FormStatus;
use Illuminate\Database\Seeder;

class FormStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listStatus = ['Menunggu Persetujuan', 'Menunggu Pembayaran', 'Terbayarkan'];
        foreach ($listStatus as $status) {
            $newStatus = new FormStatus();
            $newStatus->status = $status;
            $newStatus->save();
        }
    }
}
