<?php declare(strict_types = 1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Shredio\FmpClient\Validator\CsvValidationStrategy;
use Webmozart\Assert\InvalidArgumentException;

final class CsvValidationStrategyTest extends TestCase
{
    private CsvValidationStrategy $strategy;
    
    protected function setUp(): void
    {
        $this->strategy = new CsvValidationStrategy();
    }
    
    public function testValidateIntWithValidIntegerString(): void
    {
        $result = $this->strategy->validateInt('42', 'test path');
        $this->assertSame(42, $result);
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
    
    public function testValidateIntWithEmptyString(): void
    {
        $result = $this->strategy->validateInt('', 'test path');
        $this->assertNull($result);
    }
    
    public function testValidateIntWithInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The test path must be an integerish or null. Got: string');
        
        $this->strategy->validateInt('invalid', 'test path');
    }
    
    public function testValidateRequiredIntWithValidIntegerString(): void
    {
        $result = $this->strategy->validateRequiredInt('123', 'test path');
        $this->assertSame(123, $result);
    }
    
    public function testValidateRequiredIntWithValidInteger(): void
    {
        $result = $this->strategy->validateRequiredInt(123, 'test path');
        $this->assertSame(123, $result);
    }
    
    public function testValidateRequiredIntWithInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The test path must be an integerish. Got: string');
        
        $this->strategy->validateRequiredInt('invalid', 'test path');
    }
    
    public function testValidateNumericWithIntegerString(): void
    {
        $result = $this->strategy->validateNumeric('42', 'test path');
        $this->assertSame(42, $result);
    }
    
    public function testValidateNumericWithFloatString(): void
    {
        $result = $this->strategy->validateNumeric('42.5', 'test path');
        $this->assertSame(42.5, $result);
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
    
    public function testValidateNumericWithNull(): void
    {
        $result = $this->strategy->validateNumeric(null, 'test path');
        $this->assertNull($result);
    }
    
    public function testValidateNumericWithEmptyString(): void
    {
        $result = $this->strategy->validateNumeric('', 'test path');
        $this->assertNull($result);
    }
    
    public function testValidateNumericWithInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The test path must be numeric or null. Got: string');
        
        $this->strategy->validateNumeric('invalid', 'test path');
    }
    
    public function testValidateRequiredNumericWithIntegerString(): void
    {
        $result = $this->strategy->validateRequiredNumeric('42', 'test path');
        $this->assertSame(42, $result);
    }
    
    public function testValidateRequiredNumericWithFloatString(): void
    {
        $result = $this->strategy->validateRequiredNumeric('42.5', 'test path');
        $this->assertSame(42.5, $result);
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
    
    public function testValidateFloatWithIntegerString(): void
    {
        $result = $this->strategy->validateFloat('42', 'test path');
        $this->assertSame(42.0, $result);
    }
    
    public function testValidateFloatWithFloatString(): void
    {
        $result = $this->strategy->validateFloat('42.5', 'test path');
        $this->assertSame(42.5, $result);
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
    
    public function testValidateFloatWithInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The test path must be numeric. Got: string');
        
        $this->strategy->validateFloat('invalid', 'test path');
    }
    
    public function testValidateBoolWithTrueString(): void
    {
        $result = $this->strategy->validateBool('true', 'test path');
        $this->assertTrue($result);
    }
    
    public function testValidateBoolWithFalseString(): void
    {
        $result = $this->strategy->validateBool('false', 'test path');
        $this->assertFalse($result);
    }
    
    public function testValidateBoolWith1String(): void
    {
        $result = $this->strategy->validateBool('1', 'test path');
        $this->assertTrue($result);
    }
    
    public function testValidateBoolWith0String(): void
    {
        $result = $this->strategy->validateBool('0', 'test path');
        $this->assertFalse($result);
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
    
    public function testValidateBoolWithEmptyString(): void
    {
        $result = $this->strategy->validateBool('', 'test path');
        $this->assertNull($result);
    }
    
    public function testValidateBoolWithInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The test path must be a boolean or null. Got: integer');
        
        $this->strategy->validateBool(42, 'test path');
    }
    
    public function testValidateRequiredBoolWithTrueString(): void
    {
        $result = $this->strategy->validateRequiredBool('true', 'test path');
        $this->assertTrue($result);
    }
    
    public function testValidateRequiredBoolWithFalseString(): void
    {
        $result = $this->strategy->validateRequiredBool('false', 'test path');
        $this->assertFalse($result);
    }
    
    public function testValidateRequiredBoolWith1String(): void
    {
        $result = $this->strategy->validateRequiredBool('1', 'test path');
        $this->assertTrue($result);
    }
    
    public function testValidateRequiredBoolWith0String(): void
    {
        $result = $this->strategy->validateRequiredBool('0', 'test path');
        $this->assertFalse($result);
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
    
    public function testValidateRequiredBoolWithInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The test path must be a boolean. Got: integer');
        
        $this->strategy->validateRequiredBool(42, 'test path');
    }
    
    public function testValidateBoolWithTrueStringVariants(): void
    {
        $trueVariants = ['true', 'True', 'TRUE', '1', 'yes', 'on'];
        
        foreach ($trueVariants as $variant) {
            $result = $this->strategy->validateBool($variant, 'test path');
            $this->assertTrue($result, "Failed for variant: $variant");
        }
    }
    
    public function testValidateBoolWithFalseStringVariants(): void
    {
        $falseVariants = ['false', 'False', 'FALSE', '0', 'no', 'off'];
        
        foreach ($falseVariants as $variant) {
            $result = $this->strategy->validateBool($variant, 'test path');
            $this->assertFalse($result, "Failed for variant: $variant");
        }
    }
}