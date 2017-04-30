<?php

/**
 * [Account, Create a new Account]
 * @param integer      $id               [Account ID]
 * @param Customer     $holder           [Class of account owner]
 * @param integer      $account_number   [the account number]
 * @param string       $currency_type    [what type of currency the account is holding]
 * @param integer      $balance          [the accounts balance]
 */

class Account {
    
    public $id;
    private $holder;              // Customer Object
    public $account_number;
    public $currency_type;
    public $balance;
    
    public $deposits = [];         //Transaction obejcts
    public $withdrawals = [];      //Transaction obejcts
    
    function __construct($id, Customer $holder, $account_number, $currency_type, $balance){
        
        $this->id = $id;
        $this->holder = $holder;
        $this->account_number = $account_number;
        $this->currency_type = $currency_type;
        $this->balance = $balance;
        
    }
    
    /**
     * Get the account id
     *
     * @method get_id
     *
     * @author [Agne Ødegaard]
     *
     * @return integer
     */
    public function get_id(){
        return $this->id;
    }
    
    /**
     * get the accounts ower Customers class
     *
     * @method get_holder
     *
     * @author [Agne Ødegaard]
     *
     * @return object Customer 
     */
    public function get_holder(){
        return $this->holder;
    }
    
    /**
     * get the accounts account number
     *
     * @method get_account_number
     *
     * @author [Agne Ødegaard]
     *
     * @return integer
     */
    public function get_account_number(){
        return $this->account_number;
    }    
    
    /**
     * get the accounts currency type
     *
     * @method get_currency_type
     *
     * @author [Agne Ødegaard]
     *
     * @return string
     */
    public function get_currency_type(){
        return $this->currency_type;
    }    
    
    /**
     * get the current balance in the account
     *
     * @method get_balance
     *
     * @author [Agne Ødegaard]
     *
     * @return integer 
     */
    public function get_balance(){
        return $this->balance;
    }    
    
    /**
     * get all the withdrawals transactions
     *
     * @method get_withdrawals
     *
     * @author [Agne Ødegaard]
     *
     * @return array of Transaction objects
     */
    public function get_withdrawals(){
        return $this->withdrawals;
    }   
    
    /**
     * get all the deposit transactions
     *
     * @method get_deposits
     *
     * @author [Agne Ødegaard]
     *
     * @return array of Transaction objects
     */
    public function get_deposits(){
        return $this->deposits;
    }
    
    /**
     * Add a new withdrawl to the withdrawals list
     *
     * @method add_withdrawal
     *
     * @author [Agne Ødegaard]
     *
     * @param  Transaction    $w a transaction
     */
    public function add_withdrawal(Transaction $w){
        $this->withdrawals[$w->id] = $w;
    }
    
    /**
     * Add a new deposit to the deposit list
     *
     * @method add_deposit
     *
     * @author [Agne Ødegaard]
     *
     * @param  Transaction $d a transaction
     */
    public function add_deposit(Transaction $d){
        $this->deposits[$d->id] = $d;
    } 
}