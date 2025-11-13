<?php

declare(strict_types=1);

namespace Tests\Unit\Rules\Auth;

use App\Rules\Auth\AtLeastOneClubSelectedRule;
use Closure;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AtLeastOneClubSelectedRuleTest extends TestCase
{
    #[DataProvider('invalidClubDataProvider')]
    public function test_fails_with_invalid_data(mixed $value): void
    {
        // Arrange
        $subject = new AtLeastOneClubSelectedRule();
        $fail_was_called = false;
        $fail = function (string $message) use (&$fail_was_called): void
        {
            $fail_was_called = true;
            $this->assertEquals('Please select at least one club.', $message);
        };

        // Act
        $subject->validate('clubs', $value, $fail);

        // Assert
        $this->assertTrue($fail_was_called, 'The validation rule should have failed but it passed.');
    }

    public static function invalidClubDataProvider(): array
    {
        return [
            'not an array' => ['some_string'],
            'empty array' => [[]],
            'no club selected' => [[['id' => 1, 'selected' => false]]],
            'selected key is empty string' => [[['id' => 1, 'selected' => '']]],
            'selected key is null' => [[['id' => 1, 'selected' => null]]],
            'selected key is missing' => [[['id' => 1]]],
        ];
    }

    #[DataProvider('validClubDataProvider')]
    public function test_passes_with_valid_data(mixed $value): void
    {
        // Arrange
        $subject = new AtLeastOneClubSelectedRule();
        $fail_was_called = false;
        $fail = function (string $message): void
        {
            $fail_was_called = true;
            $this->fail('The validation rule should have passed but it failed.');
        };

        // Act & Assert
        $subject->validate('clubs', $value, $fail);

        $this->assertFalse($fail_was_called, 'The validation rule should have failed but it passed.');
    }

    public static function validClubDataProvider(): array
    {
        return [
            'one club selected with bool' => [
                [['id' => 1, 'selected' => true], ['id' => 2, 'selected' => false]],
            ],
            'one club selected with string 1' => [
                [['id' => 1, 'selected' => '1'], ['id' => 2, 'selected' => '0']],
            ],
            'multiple clubs selected' => [
                [['id' => 1, 'selected' => true], ['id' => 2, 'selected' => true]],
            ],
        ];
    }
}