<?php

require_once dirname(__DIR__) . '\Model\Poliza.php';

class User extends Poliza
{

    public function getUsers()
    {
        $sql = 'SELECT * FROM usuarios';

        $query = mysqli_query($this->con,$sql);

        $reg = [];

        $i=0;
        while ($fila = $query->fetch_assoc()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }

    public function getUsersByUsername($username)
    {
        $sql = "SELECT * FROM usuarios WHERE seudonimo= '$username'";

        $query = mysqli_query($this->con,$sql);

        $reg = [];

        $i=0;
        while ($fila = $query->fetch_array()) {
            $reg[$i] = $fila;
            $i++;
        }

        return $reg;

        mysqli_close($this->con);
    }
}