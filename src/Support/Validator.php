<?php 

namespace SeasonalContent\Support;

class Validator
{
    private array $rules = [
        'saveCategories' => [
            'id' => ['convert_int', 'is_int'],
            'title' => ['required', 'is_string'],
            'date_start' => ['required', 'is_string'],
            'date_end' => ['required'],
        ],
        'deleteCategory' => [
            'id' => ['convert_int', 'is_int']
        ],
        'restoreMainBackup' => [
            'id' => ['convert_int', 'is_int']
        ],
        'updateSeasonContent' => [
            'id' => ['convert_int', 'is_int']
        ],
        'updateMainBackup' => [
            'id' => ['convert_int', 'is_int']
        ]
    ];

    public array $errors = [];

    private string $method;

    private array $validated = [];

    public function __construct(string $method) {
        $this->method = $method;
    }

    public function validate(array $data):array
    {
        foreach ($this->rules[$this->method] as $field => $rules) {
            array_map(function ($rule) use (&$data, $field) {
                $data = $this->proccessArray($data, $rule, $field);
            }, $rules);
        }
        return $this->validated;
    }

    private function proccessArray($data, $rule, $field, $iterration = 0) {
        foreach ($data as $key => &$value) {
            if( is_array($value) ) {
                $value = $this->proccessArray($value, $rule, $field, $key);
                continue;
            }

            if ( is_int($key) && !is_array($value) ) {
                $validationMethod = $rule;
                if ( function_exists($validationMethod) && !$validationMethod($value) ){
                    $this->errors[$iterration][(string) $value] = $validationMethod . " doesn't passed";
                    continue;
                }

                array_push($this->validated, $value);
                continue;
            }

            if ( $rule === 'required' ) {
                if(!array_key_exists($field, $data)) {
                    $this->errors[$iterration][$field] = "This field is required.";
                    unset($this->validated[$iterration]);
                    continue;
                }
            }

            if($field == $key) {
                switch ($rule) {
                    case 'convert_int':
                        try {
                            $value = (int) $value;
                        } catch ( \Exception $e ) {}
                        $value = (int) $value;
                        break;
                    case 'convert_str':
                        try{
                           $value = (string) $value; 
                        } catch ( \Exception $e ) {}
                        break;
                }

                $validationMethod = $rule;
                if ( function_exists($validationMethod) && !$validationMethod($value) ){
                    $this->errors[$iterration][$field] = $validationMethod . " doesn't pass";
                    continue;
                }
                if ( !isset($this->errors[$iterration]) && empty($this->errors[$iterration]) ) {
                    $this->validated[$iterration][$field] = $value;
                }
            }
        }
        return $data;
    }
}