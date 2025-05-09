<?php
namespace App\Helpers;

use PDO;

class Validator {
    private $data;
    private $db;
    private $errors = [];

    public function __construct($data) {
        $this->data = $data;
        $this->db = Database::getInstance()->getConnection();
    }

    public function required($field) {
        if (empty($this->data[$field])) {
            $this->addError($field, "The $field field is required.");
        }
        return $this;
    }

    public function validateNumeric($field) {
        if (!is_numeric($this->data[$field])) {
            $this->addError($field, "The $field field must be numeric.");
        }
        return $this;
    }

    public function minValue($field, $minValue) {
        if ($this->data[$field] < $minValue) {
            $this->addError($field, "The $field field must be at least $minValue.");
        }
        return $this;
    }

    public function greaterThan($field, $value) {
        if ($this->data[$field] <= $value) {
            $this->addError($field, "The $field field must be greater than $value.");
        }
        return $this;
    }

    public function in($field, $array) {
        if (!in_array($this->data[$field], $array)) {
            $values = implode(',', $array);
            $this->addError($field, "The $field field must be one of $values.");
        }
        return $this;
    }


    public function exists($field, $table, $column) {
        $sql = "SELECT COUNT(*) FROM $table WHERE $column = :value;";
        $stmt = $this->db->prepare($sql);


        $stmt->execute([
            "value" => $this->data[$field],
        ]);

        $count = $stmt->fetchColumn() ?? 0;

        if ($count == 0) {
            $this->addError($field, "The $field field is invalid.");
        }

        return $this;
    }

    public function unique($field, $table, $column, $exception = null) {
        $sql = "SELECT COUNT(*) FROM $table WHERE $column = :value";

        if($exception) {
            $sql .= " AND id <> :exception";
        }

        $stmt = $this->db->prepare($sql);

        if ($exception) {
            $stmt->bindParam(':exception', $exception);
        }
        
        $stmt->bindParam(':value', $this->data[$field]);
        $stmt->execute();
        
        $count = $stmt->fetchColumn() ?? 0;

        if ($count > 0) {
            $this->addError($field, "The $field is already existed.");
        }

        return $this;
    }

    public function email($field) {
        if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "The $field must be a valid email address.");
        }
        return $this;
    }

    public function minLength($field, $length) {
        if (strlen($this->data[$field]) < $length) {
            $this->addError($field, "The $field must be at least $length characters.");
        }
        return $this;
    }

    public function maxLength($field, $length) {
        if (strlen($this->data[$field]) > $length) {
            $this->addError($field, "The $field must not exceed $length characters.");
        }
        return $this;
    }

    private function addError($field, $message) {
        $this->errors[$field][] = $message;
    }

    public function fails() {
        return !empty($this->errors);
    }

    public function errors() {
        return $this->errors;
    }
}