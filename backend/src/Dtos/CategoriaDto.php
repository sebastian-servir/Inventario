<?php

namespace App\Dtos;

use Symfony\Component\Validator\Constraints as Assert;

class CrearCategoriaDto
{
    #[Assert\NotBlank(message: "el nombre es obligatorio")]
    #[Assert\Length(min: 3, minMessage: "El nombre tiene que tener 3 caracteres como minimo")]
    public string $nombre;
}

class ActualizarCategotiaDto
{
    #[Assert\NotBlank(message: "el nombre es obligatorio")]
    #[Assert\Length(min: 3, minMessage: "el nombre tiene que tener 3 caracteres como minimo")]
    public ?string $nombre = null;
}