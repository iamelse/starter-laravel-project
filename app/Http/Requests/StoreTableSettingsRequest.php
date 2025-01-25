<?php

namespace App\Http\Requests;

use App\Helpers\TableHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\User\Models\User;

class StoreTableSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $modelClass = User::class;
        $modelInstance = app($modelClass)->newInstance();
        $tableName = $modelInstance->getTable();
        
        return [
            'columns' => ['required', 'array', 'min:1'],
            'columns.*' => [
                'string',
                Rule::in(TableHelper::getColumnsForTable($tableName)),
            ],
            'limit' => ['nullable', Rule::in([5, 10, 20, 50, 100])],
        ];
    }
}
