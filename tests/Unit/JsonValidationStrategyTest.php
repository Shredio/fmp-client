<?php declare(strict_types = 1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Shredio\FmpClient\Validator\JsonValidationStrategy;
use Webmozart\Assert\InvalidArgumentException;

final class JsonValidationStrategyTest extends TestCase
{
    private JsonValidationStrategy $strategy;
    
    protected function setUp(): void
    {
        $this->strategy = new JsonValidationStrategy();
    }
    
    public function testValidateIntWithValidInteger(): void
    {
        $result = $this->strategy->validateInt(42, 'test path');
        $this->assertSame(42, $result);
    }
    
    public function testValidateIntWithNull(): void
    {
        $result = $this->strategy->validateInt(null, 'test path');
        $this->assertNull($result);
    }
    
    public function testValidateIntWithInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The test path must be an integer or null. Got: string');
        
        $this->strategy->validateInt('invalid', 'test path');
    }
    
    public function testValidateRequiredIntWithValidInteger(): void
    {
        $result = $this->strategy->validateRequiredInt(123, 'test path');
        $this->assertSame(123, $result);
    }
    
    public function testValidateRequiredIntWithNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The test path must be an integer. Got: NULL');
        
        $this->strategy->validateRequiredInt(null, 'test path');
    }
    
    public function testValidateNumericWithInteger(): void
    {
        $result = $this->strategy->validateNumeric(42, 'test path');
        $this->assertSame(42, $result);
    }
    
    public function testValidateNumericWithFloat(): void
    {
        $result = $this->strategy->validateNumeric(42.5, 'test path');
        $this->assertSame(42.5, $result);
    }
    
    public function testValidateNumericWithNumericString(): void
    {
        $result = $this->strategy->validateNumeric('42', 'test path');
        $this->assertSame(42, $result);
    }
    
    public function testValidateNumericWithFloatString(): void
    {
        $result = $this->strategy->validateNumeric('42.5', 'test path');
        $this->assertSame(42.5, $result);
    }
    
    public function testValidateNumericWithNull(): void
    {
        $result = $this->strategy->validateNumeric(null, 'test path');
        $this->assertNull($result);
    }
    
    public function testValidateNumericWithInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The test path must be numeric or null. Got: string');
        
        $this->strategy->validateNumeric('invalid', 'test path');
    }
    
    public function testValidateRequiredNumericWithInteger(): void
    {
        $result = $this->strategy->validateRequiredNumeric(42, 'test path');
        $this->assertSame(42, $result);
    }
    
    public function testValidateRequiredNumericWithFloat(): void
    {
        $result = $this->strategy->validateRequiredNumeric(42.5, 'test path');
        $this->assertSame(42.5, $result);
    }
    
    public function testValidateRequiredNumericWithNumericString(): void
    {
        $result = $this->strategy->validateRequiredNumeric('123', 'test path');
        $this->assertSame(123, $result);
    }
    
    public function testValidateRequiredNumericWithFloatString(): void
    {
        $result = $this->strategy->validateRequiredNumeric('123.45', 'test path');
        $this->assertSame(123.45, $result);
    }
    
    public function testValidateFloatWithInteger(): void
    {
        $result = $this->strategy->validateFloat(42, 'test path');
        $this->assertSame(42.0, $result);
    }
    
    public function testValidateFloatWithFloat(): void
    {
        $result = $this->strategy->validateFloat(42.5, 'test path');
        $this->assertSame(42.5, $result);
    }
    
    public function testValidateFloatWithNumericString(): void
    {
        $result = $this->strategy->validateFloat('42.5', 'test path');
        $this->assertSame(42.5, $result);
    }
    
    public function testValidateFloatWithInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The test path must be numeric. Got: string');
        
        $this->strategy->validateFloat('invalid', 'test path');
    }
    
    public function testValidateBoolWithTrue(): void
    {
        $result = $this->strategy->validateBool(true, 'test path');
        $this->assertTrue($result);
    }
    
    public function testValidateBoolWithFalse(): void
    {
        $result = $this->strategy->validateBool(false, 'test path');
        $this->assertFalse($result);
    }
    
    public function testValidateBoolWithNull(): void
    {
        $result = $this->strategy->validateBool(null, 'test path');
        $this->assertNull($result);
    }
    
    public function testValidateBoolWithInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The test path must be a boolean or null. Got: string');
        
        $this->strategy->validateBool('invalid', 'test path');
    }
    
    public function testValidateRequiredBoolWithTrue(): void
    {
        $result = $this->strategy->validateRequiredBool(true, 'test path');
        $this->assertTrue($result);
    }
    
    public function testValidateRequiredBoolWithFalse(): void
    {
        $result = $this->strategy->validateRequiredBool(false, 'test path');
        $this->assertFalse($result);
    }
    
    public function testValidateRequiredBoolWithNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The test path must be a boolean. Got: NULL');
        
        $this->strategy->validateRequiredBool(null, 'test path');
    }
    
    public function testValidateRequiredBoolWithInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The test path must be a boolean. Got: string');
        
        $this->strategy->validateRequiredBool('invalid', 'test path');
    }
}