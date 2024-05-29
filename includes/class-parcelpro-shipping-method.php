<?php

class Parcelpro_Shipping_Method extends WC_Shipping_Method
{
    public function __construct($id)
    {
        parent::__construct();
        $this->id = $id;
        $this->supports = [];
    }
}
