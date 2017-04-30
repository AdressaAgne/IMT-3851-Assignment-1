<?php

/**
 * Create a new Customer object
 *
 * @method __construct
 *
 * @author [Agne Ødegaard]
 *
 * @param  integer     $id        the customers id
 * @param  string      $name      the customers first name
 * @param  string      $surname   the customers surname
 * @param  string      $birthdate unixtimestamp of the customers birthday
 * @param  string      $address   the customers address
 */
class Customer {
    
    public $id;
    public $name;
    public $surname;
    public $birthdate;
    public $address;
    
    public $assets = 0;
    
    private $accounts = []; // Account Objects
    
    function __construct($id, $name, $surname, $birthdate, $address){
        
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->birthdate = $birthdate;
        $this->address = $address;
        
    }
    
    /**
     * get the customres id
     *
     * @method get_id
     *
     * @author [Agne Ødegaard]
     *
     * @return integer id
     */
    public function get_id(){
        return $this->id;
    }
    
    /**
     * get the customes full name
     *
     * @method get_name
     *
     * @author [Agne Ødegaard]
     *
     * @return string name
     */
    public function get_name(){
        return ucwords($this->name . " " . $this->surname);
    }
    
    /**
     * get the customers birthday in d/m/y format
     *
     * @method get_birthdate
     *
     * @author [Agne Ødegaard]
     *
     * @return string       date
     */
    public function get_birthdate(){
        return date("d/m/y", $this->birthdate);
    }
    
    /**
     * the customers address
     *
     * @method get_address
     *
     * @author [Agne Ødegaard]
     *
     * @return string      address
     */
    public function get_address(){
        return $this->address;
    }
    
    /**
     *  Get the customers Accounts
     *
     * @method get_accounts
     *
     * @author [Agne Ødegaard]
     *
     * @return array of Account objects
     */
    public function get_accounts(){
        return $this->accounts;
    }
    
    /**
     * Add an Account to the Customer
     *
     * @method add_account
     *
     * @author [Agne Ødegaard]
     *
     * @param  Account     $a
     */
    public function add_account(Account $a){
        $this->accounts[$a->id] = $a;
    }
    
    /**
     * loop trough the accounts and find the assets of each account.
     *
     * @method get_assets
     *
     * @author [Agne Ødegaard]
     *
     * @return integer     All the customers assets in all Accounts combines
     */
    public function get_assets(){
        if($this->assets > 0) return $this->assets;
        
        foreach($this->accounts as $account){
            $this->assets += $account->get_balance();
        }
        
        return $this->assets;
    }
}