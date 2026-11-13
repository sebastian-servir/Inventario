<?php

namespace App\Dtos;

use Symfony\Component\Validator\Constraints as Assert;

class CrearProductoDto
{
    public ?int $categoria;

    #[Assert\NotBlank(message: "El nombre no puede quedar vacio")]
    public string $nombre;

    #[Assert\NotBlank(message: "La cantidad no puede quedar vacia")]
    #[Assert\PositiveOrZero(message: "La cantidad no puede ser cero")]
    public int $cantidad;

    #[Assert\NotBlank(message: "El precio no puede quedar vacio")]
    #[Assert\PositiveOrZero(message: "el precio no puede ser cero")]
    public float $precio;
}