<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Validator;

interface ValidationStrategy
{
    public function validateInt(mixed $value, string $path): ?int;
    
    public function validateRequiredInt(mixed $value, string $path): int;
    
    public function validateNumeric(mixed $value, string $path): int|float|null;
    
    public function validateRequiredNumeric(mixed $value, string $path): int|float;
    
    public function validateFloat(mixed $value, string $path): float;
    
    public function validateBool(mixed $value, string $path): ?bool;
    
    public function validateRequiredBool(mixed $value, string $path): bool;
}