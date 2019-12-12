<?php
require_once "emp_db_inc.php";

class Employee
{
    public $id;
    public $firstName;
    public $lastName;
    public $age;
    public $dob;
    public $salary;
    public $qualification;
    public $datejoined;


    public function __construct()
    {
        $this->id = NULL;
        $this->firstName = NULL;
        $this->lastName = NULL;
        $this->age = NULL;
        $this->dob = NULL;
        $this->salary = NULL;
        $this->qualification = NULL;
        $this->datejoined = NULL;
    }


    public function __destruct()
    { }


    public function initializeEmployee($employeeID)
    {
        global $pdo;
        $query = 'SELECT * FROM employee_db.employee WHERE (employee_id = :eid)';
        $values = array(':eid' => $employeeID);
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);
            $employee = $res->fetch(PDO::FETCH_ASSOC);
            $this->dob = $employee["dob"];
            $this->id = $employee["employee_id"];
            $this->salary = $employee["salary"];
            $this->firstName = $employee["first_name"];
            $this->lastName = $employee["last_name"];
            $this->dob = $employee["dob"];
            // $this->age = date("Y") - substr($employee["dob"], 0, 4);
            $this->qualification = $employee["qualification_id"];
            $this->datejoined = $employee["date_joined"];
            return $this;
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }
    }


    /* delete employee */
    public function getQualifications()
    {     /* Global $pdo object */
        global $pdo;
        $query = 'SELECT * FROM employee_db.qualification';
        try {
            $res = $pdo->prepare($query);
            $res->execute();
            $qualifications = $res->fetchall(PDO::FETCH_ASSOC);
            return $qualifications;
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }
    }



    /* delete employee */
    public function deleteEmployee()
    {     /* Global $pdo object */
        global $pdo;
        /* If employee has not been inititized, do nothing */
        if (is_null($this->id)) {
            return;
        }
        $query = 'DELETE FROM employee_db.employee WHERE (employee_id = :eid)';
        $values = array(':eid' => $this->id);
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }
    }


    public function addEmployee(
        string $firstName,
        string $lastName,
        string $salary,
        string $qualification,
        string $dob,
        string $datejoined
    ): array {
        /* Global $pdo object */
        global $pdo;

        /* Trim the strings to remove extra spaces */
        $firstName = trim($firstName);
        $lastName = trim($lastName);
        $dob = trim($dob);
        $datejoined = trim($datejoined);
        $salary = trim($salary);
        $qualification = trim($qualification);

        $hasErrors = false;
        $validationR = array();

        /* Check if the user first name is valid. If not, throw an exception */
        if (!$this->isNameValid($firstName)) {
            $validationR["firstName"] = "Please enter a valid first name";
            $hasErrors = true;
        }

        /* Check if the user last name is valid. If not, throw an exception */
        if (!$this->isNameValid($lastName)) {
            $validationR["lastName"] = "Please enter a valid last name";
            $hasErrors = true;
        }

        /* Check if the user last name is valid. If not, throw an exception */
        if (!$this->isSalaryValid($salary)) {
            $validationR["salary"] = "Salary must be between 10 to 500,000";
            $hasErrors = true;
        }

        $validationR["hasErrors"] = $hasErrors;

        // check if any errors in form

        if (!$hasErrors) {
            /* Insert query template */
            $query = 'INSERT INTO employee_db.employee (first_name, last_name, qualification_id, salary, dob, date_joined) VALUES (:firstName, :lastName, :qualification, :salary, :dob, :date_joined)';

            /* Values array for PDO */
            $values = array(':firstName' => $firstName, ':lastName' => $lastName, ':dob' => $dob, ':salary' => $salary, ':qualification' => $qualification, ':date_joined' => $datejoined);

            /* Execute the query */
            try {
                $res = $pdo->prepare($query);
                $res->execute($values);
                $validationR["resultData"] = array("firstName" => $firstName, "lastName" => $lastName);
                $validationR["hasErrors"] = false;
            } catch (PDOException $e) {
                /* If there is a PDO exception, throw a standard exception */
                return array("hasErrors" => true, "errorMsg" => "Error connecting to data base");
            }
        }
        /* Return default validation result */
        return $validationR;
    }

    public function updateEmployee(
        string $firstName = '',
        string $lastName = '',
        string $salary = '',
        string $qualification = '',
        string $dob = '',
        string $datejoined = ''
    ) {
        /* If employee has not been inititized, do nothing */
        if (is_null($this->id)) {
            return array("hasErrors" => true, "errorMsg" => "Employee not initialized");
            return;
        }

        /* Global $pdo object */
        global $pdo;

        $firstName  = $firstName == "" ? $this->firstName : $firstName;
        $lastName  = $lastName == "" ? $this->lastName : $lastName;
        $salary  = $salary == "" ? $this->salary : $salary;
        $qualification  = $qualification == "" ? $this->qualification : $qualification;
        $dob  = $dob == "" ? $this->dob : $dob;
        $datejoined  = $datejoined == "" ? $this->datejoined : $datejoined;

        

        /* Trim the strings to remove extra spaces */

        $firstName = trim($firstName);
        $lastName = trim($lastName);
        $salary = trim($salary);
        $dob = trim($dob);
        $datejoined = trim($datejoined);
        $hasErrors = false;

        /* Check if the user first name is valid. If not, throw an exception */
        if (!$this->isNameValid($firstName)) {
            $validationR["firstName"] = "Please enter a valid first name";
            $hasErrors = true;
        }

        /* Check if the user last name is valid. If not, throw an exception */
        if (!$this->isNameValid($lastName)) {
            $validationR["lastName"] = "Please enter a valid last name";
            $hasErrors = true;
        }

        /* Check if the user last name is valid. If not, throw an exception */
        if (!$this->isSalaryValid($salary)) {
            $validationR["salary"] = "Salary must be between 10 to 500,000";
            $hasErrors = true;
        }

        $validationR["hasErrors"] = $hasErrors;

        // check if any errors in form

        if (!$hasErrors) {
            /* Finally, update employee */
            /* Update query template */
            $query = 'UPDATE employee_db.employee SET first_name = :firstName, last_name = :lastName, qualification_id = :qualification, salary = :salary, dob = :dob, date_joined = :datejoined WHERE employee_id = :id';
            $values = array(':firstName' => $firstName, ':lastName' => $lastName, ':salary' => $salary, ':dob' => $dob, ':qualification' => $qualification, ':datejoined' => $datejoined, ':id' => $this->id);
            /* Execute the query */

            try {
                $res = $pdo->prepare($query);
                $res->execute($values);
                $validationR["resultData"] = array("firstName" => $firstName, "lastName" => $lastName);
                $validationR["hasErrors"] = false;
            } catch (PDOException $e) {
                /* If there is a PDO exception, throw a standard exception */
                return array("hasErrors" => true, "errorMsg" => "$e Error connecting to data base");
            }
        }
        return $validationR;
    }

    /* A sanitization check for the account username */
    public function isNameValid(string $name): bool
    {
        /* Initialize the return variable */
        $valid = TRUE;
        /* Example check: the length must be between 8 and 16 chars */
        $len = mb_strlen($name);
        if (($len < 2) || ($len > 30)) {
            $valid = FALSE;
        }          /* You can add more checks here */
        return $valid;
    }

    /* A sanitization check for the account username */
    public function isSalaryValid(string $salary): bool
    {
        try {
            // simply check if salary has an in value
            $salary = intval($salary, 10);
            if ($salary < 10 || $salary > 500000) {
                return false;
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
