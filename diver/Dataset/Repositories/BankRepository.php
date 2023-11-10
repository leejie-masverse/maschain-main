<?php

namespace Diver\Dataset\Repositories;

use Diver\Dataset\Bank;
use Illuminate\Support\Facades\DB;
use Diver\Dataset\Country;

class BankRepository
{
    /**
     * @param array $input
     * @return mixed
     */
    public function create(array $input)
    {
        $data = data_only($input, [
            'bank.name',
            'bank.type',
            'bank.billplz_code',
            'bank.image_base64',
        ]);

        return DB::transaction(function () use ($data) {
            $bank = Bank::create($data['bank']);

            return $bank;
        });
    }

    public function update(Bank $bank, array $input)
    {
        $data = data_only($input, [
            'bank.name',
            'bank.billplz_code',
            'bank.type',
            'bank.image_base64',
        ]);

        return DB::transaction(function () use ($bank, $data) {
            $bank->update($data['bank']);

            return $bank->refresh();
        });
    }

    public function delete(Bank $bank, $forceDelete = false)
    {
        return DB::transaction(function () use ($bank, $forceDelete) {
            return $forceDelete ? $bank->forceDelete() : $bank->delete();
        });
    }
}
