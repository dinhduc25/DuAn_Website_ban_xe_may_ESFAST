<?php

namespace App\Core;

use PDO;

class Validator
{
    private array $data;
    private array $rules;
    private array $errors = [];

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->validate();
    }

    public static function make(array $data, array $rules): self
    {
        return new self($data, $rules);
    }

    private function validate(): void
    {
        foreach ($this->rules as $field => $fieldRules) {
            $rulesList = is_string($fieldRules) ? explode('|', $fieldRules) : $fieldRules;
            $value = $this->data[$field] ?? null;

            foreach ($rulesList as $rule) {
                $params = [];
                if (str_contains($rule, ':')) {
                    list($ruleName, $paramStr) = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                } else {
                    $ruleName = $rule;
                }

                $this->applyRule($field, $value, $ruleName, $params);
            }
        }
    }

    private function applyRule(string $field, mixed $value, string $rule, array $params): void
    {
        switch ($rule) {
            case 'required':
                if ($value === null || trim((string)$value) === '') {
                    $this->addError($field, "Trường này không được để trống.");
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "Địa chỉ email không đúng định dạng.");
                }
                break;

            case 'phone':
                if (!empty($value) && !preg_match('/^(0[3|5|7|8|9])[0-9]{8}$/', (string)$value)) {
                    $this->addError($field, "Số điện thoại không hợp lệ (10 chữ số, đầu số VN).");
                }
                break;

            case 'min':
                $min = (int)($params[0] ?? 0);
                if (!empty($value) && mb_strlen((string)$value) < $min) {
                    $this->addError($field, "Độ dài tối thiểu là $min ký tự.");
                }
                break;

            case 'max':
                $max = (int)($params[0] ?? 0);
                if (!empty($value) && mb_strlen((string)$value) > $max) {
                    $this->addError($field, "Độ dài tối đa là $max ký tự.");
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, "Giá trị phải là số.");
                }
                break;

            case 'match':
                $otherField = $params[0] ?? '';
                $otherValue = $this->data[$otherField] ?? null;
                if ($value !== $otherValue) {
                    $this->addError($field, "Giá trị nhập lại không trùng khớp.");
                }
                break;

            case 'in':
                if (!empty($value) && !in_array($value, $params, true)) {
                    $this->addError($field, "Giá trị chọn không hợp lệ.");
                }
                break;

            case 'unique':
                $table = $params[0] ?? '';
                $column = $params[1] ?? $field;
                $ignoreId = $params[2] ?? null;

                if (!empty($value) && !empty($table)) {
                    $db = Database::getInstance();
                    $sql = "SELECT COUNT(*) FROM `{$table}` WHERE `{$column}` = :val";
                    $binds = [':val' => $value];

                    if (!empty($ignoreId)) {
                        $sql .= " AND `id` != :ignoreId";
                        $binds[':ignoreId'] = $ignoreId;
                    }

                    $stmt = $db->prepare($sql);
                    $stmt->execute($binds);
                    if ((int)$stmt->fetchColumn() > 0) {
                        $this->addError($field, "Thông tin này đã được sử dụng trên hệ thống.");
                    }
                }
                break;
        }
    }

    private function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function fails(): bool
    {
        return !$this->passes();
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function first(string $field): ?string
    {
        return $this->errors[$field] ?? null;
    }
}
