<?php

namespace App\DTO;

class ProfileDTO
{
    public string $name;
    public string $firstName;
    public string $imagePath;
    public string $status;

    public function __construct(string $name, string $firstName, string $imagePath, string $status)
    {
        $this->name = $name;
        $this->firstName = $firstName;
        $this->imagePath = $imagePath;
        $this->status = $status;
    }
}
