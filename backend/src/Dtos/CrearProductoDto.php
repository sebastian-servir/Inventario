<?php

namespace App\Dtos;

use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

class CrearProductoDto
{  
    #[Map(if: false)]
    #[Assert\NotNull]
    #[Assert\Positive]
    public ?int $categoria = null;

    #[Assert\NotBlank(message: "El nombre no puede quedar vacio")]
    public string $nombre;

    #[Assert\NotBlank(message: "La cantidad no puede quedar vacia")]
    #[Assert\PositiveOrZero(message: "La cantidad no puede ser cero")]
    public int $cantidad;

    #[Assert\NotBlank(message: "El precio no puede quedar vacio")]
    public string $precio;
}
