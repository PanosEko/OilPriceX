<?php
require_once '../config/definitions.php';

class Db_Client extends PDO
{
    protected static $instance; // holds the instance of the class
    private static $username = DB_USER;
    private static $password = DB_PASS;
    // DSN (Data Source Name) is a string that contains the information required to connect to the database
    private static $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;

    public function __construct()
    {
        // Create a PDO instance
        self::$instance = new PDO(self::$dsn, self::$username, self::$password);
        // Set default fetch mode to associative array
        self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // Disable prepared statements
        self::$instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    }

    /*
     * Acquires all data from database table 'fuel'
     *
     * @return array An array with all the data from the table 'fuel'
     */
    public static function getFuel(): array
    {
        $sql = 'SELECT * FROM fuel';
        $stmt = self::$instance->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /*
     * Acquires all data from database table 'municipality'
     *
     * @return array An array with all the data from the table 'municipality'
     */
    public static function getMunicipalities(): array
    {
        $sql = 'SELECT * FROM municipality';
        $stmt = self::$instance->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /*
     * Acquires all data from database table 'prefecture'
     *
     * @return array An array with all the data from the table 'prefecture'
     */
    public static function getPrefectures(): array
    {
        $sql = 'SELECT * FROM prefecture';
        $stmt = self::$instance->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /*
     * Acquires the data of all the announcements
     *
     * @return array id, title, content, registration date of the announcements
     */
    public static function getAnnouncements(): array
    {
        $sql = 'SELECT *, DATE_FORMAT(registration_date, "%d-%m-%Y") as formatted_date FROM announcement ORDER BY registration_date DESC';
        $stmt = self::$instance->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    /*
    * Acquires the maximum price, minimum price, and average price for each fuel type
    * for the offers that have an end date which is greater than or equal to the specified date
    *
    * @param string $date The date to compare the end date of the offers with
    * @return array An array with the fuel id, fuel type, maximum price, minimum price, and average price for each fuel type
    */
    public static function getFuelOffersByDate($date): array
    {
        //   retrieves the fuel id, fuel type, maximum price, minimum price, and average price for each fuel type
        //   that has an offer end date which is greater than or equal to the specified date
        $sql ="SELECT fuel.id, fuel.type, MAX(offer.price) as max_price, MIN(offer.price) as min_price,
                ROUND(AVG(offer.price),2) as avg_price 
                FROM offer INNER JOIN fuel ON offer.fuel_id = fuel.id WHERE end_date >= :date GROUP BY fuel.id, fuel.type ";
        $stmt = self::$instance->prepare($sql);
        $stmt->execute(['date' => $date]);
        return $stmt->fetchAll();
    }

    /*
     * Determines if the specified tax id is already in the database
     *
     * @param string $tax_id The tax id to check
     * @return bool True if the tax id is in the database, false otherwise
     */
    public static function isTaxIdInDatabase($tax_id): bool
    {
        $sql = 'SELECT * FROM user WHERE tax_id = :tax_id';
        $stmt = self::$instance->prepare($sql);
        $stmt->execute(['tax_id' => $tax_id]);
        if (count($stmt->fetchAll()) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Determines if the specified username is already in the database
     *
     * @param string $username The username to check
     * @return bool True if the username is in the database, false otherwise
     */
    public static function isUsernameInDatabase($username): bool
    {
        $sql = 'SELECT * FROM user WHERE username = :username';
        $stmt = self::$instance->prepare($sql);
        $stmt->execute(['username' => $username]);
        if (count($stmt->fetchAll()) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Acquires the company name of the user with the specified id
     *
     * @param string $user_id The id of the user
     * @return string The company name of the user
     */
    public static function getCompanyName($user_id): string
    {
        $sql = 'SELECT company_name FROM user WHERE id = :id';
        $stmt = self::$instance->prepare($sql);
        $stmt->execute(['id' => $user_id]);
        $row = $stmt->fetch();
        return $row['company_name'];
    }

    /*
     * Determines if the specified username and password are in the database
     *
     * @param string $username The username to check
     * @param string $password The password to check
     * @return array An array with the result of the login attempt and
     * the user id, role, and company name if the login was successful
     */
    public static function login($username, $password): array
    {
        $sql = 'SELECT * FROM user WHERE username = :username AND password = :password';
        $stmt = self::$instance->prepare($sql);
        $stmt->execute(['username' => $username, 'password' => $password]);
        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            $row = $stmt->fetch();
            $response = array("result" => "success", "user_id" => $row['id'],
                "role" => $row['role'], "company_name" => $row['company_name']);
        } else {
            $response = array("result" => "fail");
        }
        return $response;
    }

    /*
     * Inserts a new offer into the database or updates an existing offer
     *
     * @param $user_id string the id of the user who submitted the offer
     * @param $fuel_id string the id of the fuel
     * @param $price string the price of the fuel
     * @param $end_date string the end date of the offer
     *
     * @return string The result of the operation("success" or "fail")
     */
    public static function newOffer($user_id, $fuel_id, $end_date, $price): string
    {
        $date = DateTime::createFromFormat('d/m/Y', $end_date);
        $end_date = $date->format('Y-m-d');
        $existing_offer = self::getOffer($user_id, $fuel_id);
        // if the user has an offer for the same fuel type, update the offer instead of creating a new one
        if($existing_offer === "does not exist"){
           $result = self::submitOffer($user_id, $fuel_id, $end_date, $price);
        }
        else{
            $result = self::updateOffer($existing_offer, $end_date, $price);
        }
        return $result;
    }


    /*
     * Acquires the id of an active offer for the specified user and fuel type
     *
     * @param $user_id string the id of the user
     * @param $fuel_type string the id of the fuel type
     * @return string The id of the offer if it exists, "does not exist" otherwise
     */
    public static function getOffer($user_id, $fuel_type): string
    {
        $current_date = date('Y-m-d');
        $sql = 'SELECT id, end_date FROM offer WHERE user_id = :user_id AND fuel_id = :fuel_id AND end_date >= :current_date';
        $stmt = self::$instance->prepare($sql);
        $stmt->execute(['user_id' => $user_id,  'fuel_id' => $fuel_type, 'current_date' => $current_date]);
        $result = $stmt->fetch();
        if ($result === false) {
            return "does not exist";
        } else{
            return $result['id'];
        }
    }

    /*
     * Updates the price and end date of an existing offer
     *
     * @param $id string the id of the offer
     * @param $end_date string the end date of the offer
     * @param $price string the price of the fuel
     *
     * @return string The result of the operation("success" or "fail")
     */
    public static function updateOffer($id, $end_date, $price): string
    {
        $sql = 'UPDATE offer SET end_date = :end_date, price = :price WHERE id = :id';
        $stmt = self::$instance->prepare($sql);
        $stmt->execute(['id' => $id, 'end_date' => $end_date, 'price' => $price]);
        if ($stmt->rowCount() > 0) {
            return "success";
        } else {
            return "fail";
        }
    }

    /*
     * Inserts a new offer into the database
     *
     * @param $user_id string the id of the user who submitted the offer
     * @param $fuel_id string the id of the fuel
     * @param $price string the price of the fuel
     * @param $end_date string the end date of the offer
     *
     * @return string The result of the operation("success" or "fail")
     */
    public static function submitOffer($user_id, $fuel_id, $end_date, $price): string
    {
        $sql = 'INSERT INTO offer (user_id, fuel_id, end_date, price) VALUES (:user_id, :fuel_id, :end_date, :price)';
        $stmt = self::$instance->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'fuel_id' => $fuel_id,
            'end_date' => $end_date, 'price' => $price]);
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            return "success";
        } else {
            return "fail";
        }
    }

    /*
     * Acquires the data of all the offers for a specific fuel type and a specific prefecture
     *
     * @param $fuel_id string the id of the fuel
     * @param $prefecture_id string the id of the prefecture
     * @return array id, price, end date, fuel type of the offers
     * and the company name, address, email of the users who submitted the offers
     *
     */
    public static function getOffersByFuelAndPrefecture($fuel_id, $prefecture_id): array
    {
        $sql = "SELECT offer.id, offer.price, DATE_FORMAT(offer.end_date, '%d/%m/%Y') AS end_date,
                user.company_name, user.address, user.email, fuel.type FROM offer 
                INNER JOIN user ON offer.user_id = user.id INNER JOIN fuel ON offer.fuel_id = fuel.id 
                WHERE offer.fuel_id = :fuel_id AND user.prefecture_id = :prefecture_id ORDER BY offer.price ASC";
        $stmt = self::$instance->prepare($sql);
        $stmt->execute(['fuel_id' => $fuel_id, 'prefecture_id' => $prefecture_id]);
        return $stmt->fetchAll();
    }


    /*
     * submit a new user to the database and return a string with the result
     *
     * @param $company_name string the name of the company
     * @param $tax_id string the tax id of the company
     * @param $address string the address of the company
     * @param $municipality int the id of the municipality
     * @param $prefecture int the id of the prefecture
     * @param $email string the email of the company
     * @param $role string the role of the user
     * @param $username string the username of the user
     * @param $password string the password of the user
     *
     * @return string The result of the operation("success" or "fail")
     */
    public static function submitUser($company_name, $tax_id, $address, $municipality, $prefecture,$email,
                                      $role, $username, $password): string
    {
        if (self::isTaxIdInDatabase($tax_id)) {
            return "tax_id_exists";
        }

        if (self::isUsernameInDatabase($username)) {
            return "username_exists";
        }

        $sql = 'INSERT INTO user (company_name, tax_id, address, municipality_id , prefecture_id, email,
                  role, username, password) 
        VALUES (:company_name, :tax_id, :address, :municipality, :prefecture, :email, :role,
                :username, :password)';
        $stmt = self::$instance->prepare($sql);
        $stmt->execute(['company_name' => $company_name, 'tax_id' => $tax_id, 'address' => $address,
            'municipality' => $municipality, 'prefecture' => $prefecture, 'email' => $email, 'role' => $role,
            'username' => $username, 'password' => $password]);

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            return "success";
        } else {
            return "fail";
        }

    }

    /*
    * Submits a new announcement to the database table announcements
    *
    * @param registration_date string the date of the announcement
    * @param title string the title of the announcement
    * @param content string the content of the announcement
    *
    * @return string The result of the operation("success" or "fail")
    */
    public static function submitAnnouncement($title, $content): string
    {
        $registration_date = date('Y-m-d');
        $sql = 'INSERT INTO announcement (registration_date, title, content) VALUES (:registration_date, :title, :content)';
        $stmt = self::$instance->prepare($sql);
        $stmt->execute(['registration_date' => $registration_date, 'title' => $title, 'content' => $content]);
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            return "success";
        } else {
            return "fail";
        }
    }

    /*
     * Deletes the announcement with the given id
     *
     * @return string The result of the operation("success" or "fail")
     */
    public static function deleteAnnouncement($id): string
    {
        $sql = 'DELETE FROM announcement WHERE id = :id';
        $stmt = self::$instance->prepare($sql);
        $stmt->execute(['id' => $id]);
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            return "success";
        } else {
            return "fail";
        }
    }

    /*
     * Acquires the data of all the offers for a specific user
     *
     * @param $user_id string the id of the user
     * @return array id, price, end date, fuel type of the offers
     *
     */
    public static function getOffersByUser($user_id): array
    {
        $sql = "SELECT offer.id, offer.price, offer.end_date, fuel.type FROM offer 
                INNER JOIN fuel ON offer.fuel_id = fuel.id WHERE offer.user_id = :user_id";
        $stmt = self::$instance->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    /*
     * Retrieves the data of the user with the given id
     *
     * @param $id string the id of the user
     * @return array the data of the user
     */
    public static function getUserDataById($user_id): array
    {
        $sql = "SELECT user.company_name, user.tax_id, user.address, user.municipality_id, user.prefecture_id,
                user.email, user.role, user.username, user.password, municipality.name AS municipality_name,
                prefecture.name AS prefecture_name FROM user INNER JOIN municipality ON user.municipality_id = municipality.id
                INNER JOIN prefecture ON user.prefecture_id = prefecture.id WHERE user.id = :user_id";
        $stmt = self::$instance->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetch();
    }
}
