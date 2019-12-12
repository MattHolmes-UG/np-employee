<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "db_inc.php";

class Account
{
    /* Class properties (variables) */
    /* The ID of the logged in user (or NULL if there is no logged in user) */
    public $id;
    /* The name of the logged in user (or NULL if there is no logged in user) */
    public $name;
    /* TRUE if the user is authenticated, FALSE otherwise */
    public $authenticated;
    /* Public class methods (functions) */          /* Constructor */
    public function __construct()
    {
        /* Initialize the $id and $name variables to NULL */
        $this->id = NULL;
        $this->name = NULL;
        $this->authenticated = FALSE;
    }


    /* Destructor */

    public function __destruct()
    { }

    /* Login with username and password */
    public function login(string $name, string $passwd): array
    {
        /* Global $pdo object */
        global $pdo;
        /* login success flag defaults to false */
        $loginSuccess = false;
        /*Login fields Validation Result*/
        $validationResult = array("loginSuccess" => $loginSuccess, "hasErrors" => false);

        /* Trim the strings to remove extra spaces */
        $name = trim($name);
        $passwd = trim($passwd);
        /* Check if the user name is valid. If not, return FALSE meaning the authentication failed */
        if (!$this->isNameValid($name)) {
            $validationResult["username"] = "Please enter a valid username.";
        }
        /* Check if the password is valid. If not, return FALSE meaning the authentication failed */
        if (!$this->isPasswdValid($passwd)) {
            $validationResult["password"] = "Please enter a valid password.";
        }

        if (array_key_exists("username", $validationResult) || array_key_exists("password", $validationResult)) {
            $validationResult["hasErrors"] = true;
            return $validationResult;
        }

        /* Look for the account in the db. Note: the account must be enabled (account_enabled = 1) */
        $query = 'SELECT * FROM myschema.accounts WHERE (account_name = :name) AND (account_enabled = 1)';
        /* Values array for PDO */
        $values = array(':name' => $name);
        /* Execute the query */
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {
            /* If there is a PDO exception, throw a standard exception */
            throw new Exception('Database query error');
        }
        $row = $res->fetch(PDO::FETCH_ASSOC);
        /* If there is a result, we must check if the password matches using password_verify() */
        if (is_array($row)) {
            if (password_verify($passwd, $row['account_passwd'])) {
                /* Authentication succeeded. Set the class properties (id and name) */
                $this->id = intval($row['account_id'], 10);
                $this->name = $name;
                $this->authenticated = TRUE;
                /* Register the current Sessions on the database */
                $this->registerLoginSession();
                /* Finally, Return TRUE */
                $validationResult["loginSuccess"] = true;
                $validationResult["message"] = "Welcome back {$row['account_name']}";
                $userData = array("username" => $row["account_name"], "email" => $row["account_email"]);
                $validationResult["accountInstance"] = $this;
                return $validationResult;
            }
        }
        $validationResult["message"] = "No user with the authentication details found!";
        /* If we are here, it means the authentication failed: return FALSE */
        return $validationResult;
    }

    /* Saves the current Session ID with the account ID */
    private function registerLoginSession()
    {
        /* Global $pdo object */
        global $pdo;
        /* Check that a Session has been started */
        if (session_status() == PHP_SESSION_ACTIVE) {
            /* 	Use a REPLACE statement to: 			
            - insert a new row with the session id, if it doesn't exist, or... 			
            - update the row having the session id, if it does exist. 		*/
            $query = 'REPLACE INTO myschema.account_sessions (session_id, account_id, login_time) VALUES (:sid, :accountId, NOW())';
            $values = array(':sid' => session_id(), ':accountId' => $this->id);
            /* Execute the query */
            try {
                $res = $pdo->prepare($query);
                $res->execute($values);
            } catch (PDOException $e) {
                /* If there is a PDO exception, throw a standard exception */
                throw new Exception('Database query error');
            }
        }
    }

    /* Login using Sessions */
    public function sessionLogin(): bool
    {
        /* Global $pdo object */
        global $pdo;
        /* Check that the Session has been started */
        if (session_status() == PHP_SESSION_ACTIVE) {
            /*  			
            Query template to look for the current session ID on the account_sessions table. 			
            The query also make sure the Session is not older than 7 days 		
            */
            $query = 'SELECT * FROM myschema.account_sessions, myschema.accounts WHERE (account_sessions.session_id = :sid) ' .
                'AND (account_sessions.login_time >= (NOW() - INTERVAL 7 DAY)) AND (account_sessions.account_id = accounts.account_id) ' .
                'AND (accounts.account_enabled = 1)';
            /* Values array for PDO */
            $values = array(':sid' => session_id());
            /* Execute the query */
            try {
                $res = $pdo->prepare($query);
                $res->execute($values);
            } catch (PDOException $e) {
                /* If there is a PDO exception, throw a standard exception */
                throw new Exception('Database query error');
            }
            $row = $res->fetch(PDO::FETCH_ASSOC);
            if (is_array($row)) {
                /* Authentication succeeded. Set the class properties (id and name) and return TRUE*/
                $this->id = intval($row['account_id'], 10);
                $this->name = $row['account_name'];
                $this->authenticated = TRUE;
                return TRUE;
            }
        }
        /* If we are here, the authentication failed */
        return FALSE;
    }

    /* Logout the current user */
    public function logout()
    {     /* Global $pdo object */
        global $pdo;
        /* If there is no logged in user, do nothing */
        if (is_null($this->id)) {
            return;
        }
        /* Reset the account-related properties */
        $this->id = NULL;
        $this->name = NULL;
        $this->authenticated = FALSE;
        /* If there is an open Session, remove it from the account_sessions table */
        if (session_status() == PHP_SESSION_ACTIVE) {
            /* Delete query */
            $query = 'DELETE FROM myschema.account_sessions WHERE (session_id = :sid)';
            /* Values array for PDO */
            $values = array(':sid' => session_id());
            /* Execute the query */
            try {
                $res = $pdo->prepare($query);
                $res->execute($values);
            } catch (PDOException $e) {
                /* If there is a PDO exception, throw a standard exception */
                throw new Exception('Database query error');
            }
        }
    }

    /* "Getter" function for the $authenticated variable     Returns TRUE if the remote user is authenticated */
    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    /* Close all account Sessions except for the current one (aka: "logout from other devices") */
    public function closeOtherSessions()
    {
        /* Global $pdo object */
        global $pdo;
        /* If there is no logged in user, do nothing */
        if (is_null($this->id)) {
            return;
        }
        /* Check that a Session has been started */
        if (session_status() == PHP_SESSION_ACTIVE) {
            /* Delete all account Sessions with session_id different from the current one */
            $query = 'DELETE FROM myschema.account_sessions WHERE (session_id != :sid) AND (account_id = :account_id)';
            /* Values array for PDO */
            $values = array(':sid' => session_id(), ':account_id' => $this->id);
            /* Execute the query */
            try {
                $res = $pdo->prepare($query);
                $res->execute($values);
            } catch (PDOException $e) {
                /* If there is a PDO exception, throw a standard exception */
                throw new Exception('Database query error');
            }
        }
    }

    /* Add a new account to the system and return its ID (the account_id column of the accounts table) */
    public function addAccount(string $username, string $email, string $passwd, string $passwd2): array
    {
        /* Global $pdo object */
        global $pdo;

        /* Trim the strings to remove extra spaces */
        $fname = trim($username);
        $email = trim($email);
        $passwd = trim($passwd);
        $passwd2 = trim($passwd2);

        $hasErrors = false;
        $validationR = array();

        /* Check if the user first name is valid. If not, throw an exception */
        if (!$this->isNameValid($username)) {
            $validationR["username"] = "Username must be more than 8 characters";
            $hasErrors = true;
        }

        /* Check if the user first name is valid. If not, throw an exception */
        if (!$this->isEmailValid($email)) {
            $validationR["email"] = "Please enter a valid email";
            $hasErrors = true;
        }

        /* Check if the password is valid. If not, throw an exception */
        if (!$this->isPasswdValid($passwd)) {
            $validationR["password"] = "Password must be longer than 8 characters";
            $hasErrors = true;
        }
        /* Check if an account having the same name already exists. If it does, throw an exception */
        if (!is_null($this->getIdFromEmail($email))) {
            $validationR["email_na"] = "User with email already exist";
            $hasErrors = true;
        }

        if (!is_null($this->getIdFromUserName($username))) {
            $validationR["username_na"] = "User with username already exist";
            $hasErrors = true;
        }

        if ($passwd != $passwd2) {
            $validationR["password_confirm"] = "Password does not match the confirmatory password";
            $hasErrors = true;
        }

        $validationR["hasErrors"] = $hasErrors;

        // check if any errors in form

        if (!$hasErrors) {
            /* Insert query template */
            $query = 'INSERT INTO myschema.accounts (account_name, account_email, account_passwd) VALUES (:name, :email, :passwd)';
            /* Password hash */
            $hash = password_hash($passwd, PASSWORD_DEFAULT);
            /* Values array for PDO */
            $values = array(':name' => $username, ':email' => $email, ':passwd' => $hash);

            /* Execute the query */
            try {
                $res = $pdo->prepare($query);
                $res->execute($values);
                $validationR["resultData"] = array("username" => $username, "email" => $email);
                $validationR["hasErrors"] = false;
            } catch (PDOException $e) {
                /* If there is a PDO exception, throw a standard exception */
                return array("hasError" => true, "errorMsg" => "Error connecting to data base");
            }
        }
        /* Return the new ID */
        return $validationR;

        // try {	
        //     $sql = "INSERT INTO user(username, email, password) VALUES(?,?,?)";
        //     $stmt = $conn->prepare($sql);
        //     $stmt->bind_param("sss", $urname, $email, $passwd);

        //     $urname = $username;
        //     $email = $email;
        //     $passwd = $hash;

        //     $stmt->execute();   

        //     $validationR["resultData"] = array("username"=> $username, "email"=>$email);
        // } 
        // catch (PDOException $e) { 	   
        //     /* If there is a PDO exception, throw a standard exception */ 	   
        //     return array("hasError"=> true, "errorMsg"=>"Error connecting to data base");
        //      } 	
        //       	/* Return the new ID */ 	
        // return $validationR; 
    }

    public function editAccount(int $id, string $name, string $passwd, bool $enabled)
    {
        /* Global $pdo object */
        global $pdo;
        /* Trim the strings to remove extra spaces */
        $name = trim($name);
        $passwd = trim($passwd);
        /* Check if the ID is valid */
        if (!$this->isIdValid($id)) {
            throw new Exception('Invalid account ID');
        }
        /* Check if the user name is valid. */
        if (!$this->isNameValid($name)) {
            throw new Exception('Invalid user name');
        }
        /* Check if the password is valid. */
        if (!$this->isPasswdValid($passwd)) {
            throw new Exception('Invalid password');
        }
        /* Check if an account having the same name already exists (except for this one). */
        $idFromName = $this->getIdFromName($name);
        if (!is_null($idFromName) && ($idFromName != $id)) {
            throw new Exception('User name already used');
        }
        /* Finally, edit the account */
        /* Edit query template */
        $query = 'UPDATE myschema.accounts SET account_name = :name, account_passwd = :passwd, account_enabled = :enabled WHERE account_id = :id';
        /* Password hash */
        $hash = password_hash($passwd, PASSWORD_DEFAULT);
        /* Int value for the $enabled variable (0 = false, 1 = true) */
        $intEnabled = $enabled ? 1 : 0;
        /* Values array for PDO */
        $values = array(':name' => $name, ':passwd' => $hash, ':enabled' => $intEnabled, ':id' => $id);
        /* Execute the query */
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {
            /* If there is a PDO exception, throw a standard exception */
            throw new Exception('Database query error');
        }
    }

    public function deleteAccount(int $id)
    {
        /* Global $pdo object */
        global $pdo;
        /* Check if the ID is valid */
        if (!$this->isIdValid($id)) {
            throw new Exception('Invalid account ID');
        }
        /* Query template */
        $query = 'DELETE FROM myschema.accounts WHERE account_id = :id';
        /* Values array for PDO */
        $values = array(':id' => $id);
        /* Execute the query */
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {
            /* If there is a PDO exception, throw a standard exception */
            throw new Exception('Database query error');
        }      /* Delete the Sessions related to the account */
        $query = 'DELETE FROM myschema.account_sessions WHERE (account_id = :id)';
        /* Values array for PDO */
        $values = array(':id' => $id);
        /* Execute the query */
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {
            /* If there is a PDO exception, throw a standard exception */
            throw new Exception('Database query error');
        }
    }

    public function isIdValid(int $id): bool
    {
        /* Initialize the return variable */
        $valid = TRUE;
        /* Example check: the ID must be between 1 and 1000000 */
        if (($id < 1) || ($id > 1000000)) {
            $valid = FALSE;
        }
        /* You can add more checks here */
        return $valid;
    }

    /* A sanitization check for the account username */
    public function isNameValid(string $name): bool
    {
        /* Initialize the return variable */
        $valid = TRUE;
        /* Example check: the length must be between 8 and 16 chars */
        $len = mb_strlen($name);
        if (($len < 8) || ($len > 16)) {
            $valid = FALSE;
        }          /* You can add more checks here */
        return $valid;
    }

    /* A sanitization check for the account username */
    public function isEmailValid(string $name): bool
    {
        /* Initialize the return variable */
        $valid = TRUE;
        /* Example check: the length must be between 8 and 16 chars */
        $len = mb_strlen($name);
        if ($len < 4) {
            $valid = FALSE;
        }          /* You can add more checks here */
        return $valid;
    }

    /* A sanitization check for the account password */
    public function isPasswdValid(string $passwd): bool
    {
        /* Initialize the return variable */
        $valid = TRUE;
        /* Example check: the length must be between 8 and 16 chars */
        $len = mb_strlen($passwd);
        if (($len < 8) || ($len > 16)) {
            $valid = FALSE;
        }          /* You can add more checks here */
        return $valid;
    }

    /* Returns the account id having $name as name, or NULL if it's not found */
    public function getIdFromEmail(string $email): ?int
    {
        /* Global $pdo object */
        global $pdo;
        /* Since this method is public, we check $email again here */
        if (!$this->isEmailValid($email)) {
            throw new Exception('Invalid user email');
        }
        /* Initialize the return value. If no account is found, return NULL */
        $id = NULL;
        /* Search the ID on the database */
        $query = 'SELECT account_id FROM myschema.accounts WHERE (account_email = :email)';
        $values = array(':email' => $email);
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {
            /* If there is a PDO exception, throw a standard exception */
            throw new Exception('Database query error');
        }
        $row = $res->fetch(PDO::FETCH_ASSOC);
        /* There is a result: get it's ID */
        if (is_array($row)) {
            $id = intval($row['account_id'], 10);
        }
        return $id;
    }

    /* Returns the account id having $name as name, or NULL if it's not found */
    public function getIdFromUserName(string $username): ?int
    {
        /* Global $pdo object */
        global $pdo;
        /* Initialize the return value. If no account is found, return NULL */
        $id = NULL;
        /* Search the ID on the database */
        $query = 'SELECT account_id FROM myschema.accounts WHERE (account_name = :username)';
        $values = array(':username' => $username);
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);
        } catch (PDOException $e) {
            /* If there is a PDO exception, throw a standard exception */
            throw new Exception('Database query error');
        }
        $row = $res->fetch(PDO::FETCH_ASSOC);
        /* There is a result: get it's ID */
        if (is_array($row)) {
            $id = intval($row['account_id'], 10);
        }

        return $id;
    }
}
