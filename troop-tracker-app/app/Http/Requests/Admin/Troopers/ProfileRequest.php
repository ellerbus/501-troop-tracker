<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Troopers;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator as ValidatorInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Handles the validation for the user registration form.
 *
 * This class defines the base validation rules for user registration and dynamically
 * adds rules based on the organizations a user selects, including custom rules for
 * organization-specific identifiers and unit selections. It also customizes error messages
 * for a better user experience.
 */
class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool Returns true as registration is open to guests.
     */
    public function authorize(): bool
    {
        $trooper = $this->route('trooper');

        if ($trooper == null)
        {
            throw new AuthorizationException('Trooper not found or unauthorized.');
        }

        return $this->user()->can('update', $trooper);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed> The combined validation rules for the registration form.
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:240'],
            'phone' => ['nullable', 'string', 'max:10'],
            'membership_status' => ['nullable', 'string', 'max:16', 'in:' . MembershipStatus::toValidator()],
            'membership_role' => ['nullable', 'string', 'max:16', 'in:' . MembershipRole::toValidator()],
        ];

        return $rules;
    }

    public function validateInputs(): array
    {
        $validator = Validator::make($this->all(), $this->rules());

        if ($validator->fails())
        {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Prepare the data for validation.
     *
     * This method sanitizes the phone number by removing any non-digit characters.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('phone'))
        {
            $this->merge([
                'phone' => preg_replace('/\D+/', '', $this->input('phone') ?? ''),
            ]);
        }
    }

    protected function failedValidation(ValidatorInterface $validator): void
    {
    }

}