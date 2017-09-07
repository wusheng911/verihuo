<?php namespace App\Contracts\Order;

interface Repository {

    /**
     * place order.
     * @param array $skus [['sku_id' => 2, 'quantity' => 99], ...]
     * @param int $address_id selected address id
     * @param int $paymethod_id payment method id 
     * @param string $remark the customer leaves message
     * @return Order or false
     */
    public function create($skus, $address_id, $paymethod_id, $remark);
}