<?php

class validate_info{
    public function checkEmpty($data, $fields)
    {
        $msg = null;
        foreach ($fields as $value) {
            if (empty($data[$value])) {
                $msg .= "<p>$value field is required</p>";
            }
        }
        return $msg;
    }

    /**
     * Validate username format
     * - 3 to 20 characters
     * - Only letters, numbers, and underscores
     */
    public function validateName($name) {
        return preg_match("/^[a-zA-Z0-9_]{3,20}$/", $name);
    }

    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
?>