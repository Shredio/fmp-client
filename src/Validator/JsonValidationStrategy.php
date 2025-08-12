<?php declare(strict_types = 1);

namespace Shredio\FmpClient\Validator;

use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class JsonValidationStrategy implements ValidationStrategy
{
    public function validateInt(mixed $value, string $path): ?int
    {
        if ($value === null) {
            return null;
        }
        
        Assert::integer($value, sprintf('The %s must be an integer or null. Got: %%s', $path));
        return $value;
    }
    
    public function validateRequiredInt(mixed $value, string $path): int
    {
        Assert::integer($value, sprintf('The %s must be an integer. Got: %%s', $path));
        return $value;
    }
    
    public function validateNumeric(mixed $value, string $path): int|float|null
    {
        if ($value === null) {
            return null;
        }
        
        Assert::numeric($value, sprintf('The %s must be numeric or null. Got: %%s', $path));
        
        if (is_string($value)) {
            return str_contains($value, '.') ? (float) $value : (int) $value;
        }
        
        return $value;
    }
    
    public function validateRequiredNumeric(mixed $value, string $path): int|float
    {
        Assert::numeric($value, sprintf('The %s must be numeric. Got: %%s', $path));
        
        if (is_string($value)) {
            return str_contains($value, '.') ? (float) $value : (int) $value;
        }
        
        return $value;
    }
    
    public function validateFloat(mixed $value, string $path): float
    {
        Assert::numeric($value, sprintf('The %s must be numeric. Got: %%s', $path));
        return (float) $value;
    }
    
    public function validateBool(mixed $value, string $path): ?bool
    {
        if ($value === null) {
            return null;
        }
        
        Assert::boolean($value, sprintf('The %s must be a boolean or null. Got: %%s', $path));
        return $value;
    }
    
    public function validateRequiredBool(mixed $value, string $path): bool
    {
        Assert::boolean($value, sprintf('The %s must be a boolean. Got: %%s', $path));
        return $value;
    }
}