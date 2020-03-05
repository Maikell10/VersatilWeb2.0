<?php

class Conection{

    protected $con;

    public function __construct()
    {
        $this->con = new mysqli('localhost', 'root', '', 'versatil_sdb');
        if ($this->con) {
            //echo "Connected";
        } else {
            echo "Not Connected";
        }
    }


}

$obj = new Conection();