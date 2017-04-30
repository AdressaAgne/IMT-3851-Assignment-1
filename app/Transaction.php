<?php

/**
 * create a new Transaction object
 *
 * @method __construct
 *
 * @author [Agne Ødegaard]
 *
 * @param  integer      $id           the transaction id
 * @param  string      $date          unixtimestamp of the transactione date
 * @param  Account     $from          The account object of the account who are sending money
 * @param  Account     $to            The account object of the account who are resiving money
 * @param  string      $currency_type What money are sent
 * @param  integer      $value         How much are sent
 */
class Transaction {
    
    public $id;
    public $date;
    private $from;  // Account object
    private $to;    // Account object
    public $currency_type;
    public $value;
    
    
    function __construct($id, $date, Account $from, Account $to, $currency_type, $value){
        $this->id = $id;
        $this->date = $date;
        $this->from = $from;
        $this->to   = $to;
        $this->currency_type = $currency_type;
        $this->value = $value;
        
    }
    
    /**
     * get the transaction id
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
     * get the transaction date in d/m/y format
     *
     * @method get_date
     *
     * @author [Agne Ødegaard]
     *
     * @return string   date
     */
    public function get_date(){
        return date("d/m/y", $this->date);
    }
    
    /**
     * get wich account the money is sent from
     *
     * @method get_from
     *
     * @author [Agne Ødegaard]
     *
     * @return Account object
     */
    public function get_from(){
        return $this->from;
    }
    
    /**
     * get wich account the money is sent to
     *
     * @method get_to
     *
     * @author [Agne Ødegaard]
     *
     * @return Account object
     */
    public function get_to(){
        return $this->to;
    }
    
    /**
     * get the type of currency transferd
     *
     * @method get_currency_type
     *
     * @author [Agne Ødegaard]
     *
     * @return string        currency type
     */
    public function get_currency_type(){
        return $this->currency_type;
    }
    
    /**
     * get how much money is transferd
     *
     * @method get_value
     *
     * @author [Agne Ødegaard]
     *
     * @return integer    money
     */
    public function get_value(){
        return $this->value;
    }
    
}