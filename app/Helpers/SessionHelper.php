<?php
namespace App\Helpers;

class SessionHelper
{
    // Start the session if it hasn't already been started
    public static function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Set a flash message (used for success or other temporary messages)
    public static function setFlashMessage($key, $message)
    {
        $_SESSION[$key] = $message;
    }

    // Get a flash message and remove it from the session
    public static function getFlashMessage($key)
    {
        if (isset($_SESSION[$key])) {
            $message = $_SESSION[$key];
            unset($_SESSION[$key]); // Clear after getting
            return $message;
        }
        return null;
    }

    // Set validation errors (used for form validation)
    public static function setValidationErrors($errors)
    {
        $_SESSION['validation_errors'] = $errors;
    }

    public static function setOldValues($values)
    {
        $_SESSION['old'] = $values;
    }

    // Get validation errors and remove them from the session
    public static function getValidationErrors()
    {
        if (isset($_SESSION['validation_errors'])) {
            $errors = $_SESSION['validation_errors'];
            unset($_SESSION['validation_errors']); // Clear after getting
            return $errors;
        }
        return [];
    }

    public static function getOldValues()
    {
        if (isset($_SESSION['old'])) {
            $errors = $_SESSION['old'];
            unset($_SESSION['old']); // Clear after getting
            return $errors;
        }
        return [];
    }

    // Check if a session variable is set (helpful for form persistence)
    public static function getSessionValue($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    // Clear a session variable
    public static function clear($key)
    {
        unset($_SESSION[$key]);
    }
}
