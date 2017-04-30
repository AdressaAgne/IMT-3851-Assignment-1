<?php

require_once('app/Customer.php');
require_once('app/Account.php');
require_once('app/Transaction.php');

/**
 * Inisiation of the application
 *
 * @method __construct
 *
 * @author [Agne Ødegaard]
 */
class App{
    
    public $customers = [];
    public $accounts = [];
    public $transactions = [];
    public static $by = 'name';
    
    function __construct(){

        $this->populate();
        
    }
    
    /**
     * Populate the $customers, $accounts and $transactions arrays with data from the csv files.
     *
     * @method populate
     *
     * @author [Agne Ødegaard]
     *
     * @return void
     */
    private function populate(){
        
        $customers = $this->read('customers');
        // Loop trhough all the Customers in customers.csv
        foreach($customers as $c){
            
            // populate the customers array
            $this->customers[$c['id']] = new Customer(
                $c['id'],
                $c['name'],
                $c['surname'],
                $c['birthdate'],
                $c['address']);
            
        }
        
        $accounts = $this->read('accounts');
        // Loop trhough all the Accounts in accounts.csv
        foreach($accounts as $a){
            
            // populate the accounts array
            $this->accounts[$a['id']] = new Account(
                $a['id'],
                $this->customers[$a['holder']],
                $a['account_number'],
                $a['currency_type'],
                $a['balance']);
            
            // add the correct account to the customers account array
            $this->customers[$a['holder']]->add_account($this->accounts[$a['id']]);
            
        }
        
        $transactions = $this->read('transactions');
        // Loop trhough all the Transactions in transactions.csv
        foreach($transactions as $t){
            
            // populate the transactions array
            $this->transactions[$t['id']] = new Transaction(
                $t['id'],
                $t['date'],
                $this->accounts[$t['from']],
                $this->accounts[$t['to']],
                $t['currency_type'],
                $t['value']);
            
            // add the correct deposit to the corresponing account deposit array
            $this->accounts[$t['from']]->add_deposit($this->transactions[$t['id']]);
            
            // add the correct withdrawals to the corresponing account withdrawals array
            $this->accounts[$t['to']]->add_withdrawal($this->transactions[$t['id']]);
            
        }
        
        // sort the customers array after name
        $this->sorting($this->customers, "name");
        
    }
    
    /**
     * sort an array by refference
     *
     * @method sorting
     *
     * @author [Agne Ødegaard]
     *
     * @param  array  $array an array of objects you wan to sort
     * @param  string  $by    the paramater name you wan to sort by
     *
     * @return void
     */
    public function sorting(&$array, $by){
        self::$by = $by;
        usort($array, [$this, (isset($_GET['desc']) ? 'desc' : 'asc')]);
    }
    
    /**
     * sort by asc, help function for usort
     *
     * @method asc
     *
     * @author [Agne Ødegaard]
     *
     * @return string compareson
     */
    public function asc($a, $b) {
        $by = self::$by;
        return strcmp($a->$by, $b->$by);
    }

    /**
     * sort by desc, help function for usort
     *
     * @method asc
     *
     * @author [Agne Ødegaard]
     *
     * @return string compareson
     */
    public function desc($a, $b) {
        $by = self::$by;
        return strcmp($b->$by, $a->$by);
    }
    
    /**
     * read a csv file inside the csv folder
     *
     * @method read
     *
     * @author [Agne Ødegaard]
     *
     * @param  string $file filename without extension
     *
     * @return array with csv data
     */
    protected function read($file){
        
        //read a file and convert it to an array
        $array = array_map('str_getcsv', file("csv/$file.csv"));
        
        // Set the first row as keys
        array_walk($array, function(&$value) use ($array) {
            $value = array_combine($array[0], $value);
        });
        
        //remove the first row
        array_shift($array);
        
        return $array;
    }
    
    /**
     * append data to a csv file
     *
     * @method write
     *
     * @author [Agne Ødegaard]
     *
     * @param  string $file filename
     * @param  string $data new data to append
     *
     * @return boolean      success/fail
     */
    private function write($file, $data){
        //todo
        $old_content = file_get_contents($file);
        
        $new_data = explode(PHP_EOL, $data);
        
        array_shift($new_data);
        
        $data = implode(PHP_EOL, $new_data);
        
        $new_data = $old_content . PHP_EOL . $data;
        
        if(file_put_contents($file, $new_data)) return true;
        
        return false;
    }
    
    /**
     * Upload a new file to the csv database
     *
     * @method upload
     *
     * @author [Agne Ødegaard]
     *
     * @param  array $file $_FILE data
     * @param  string $type [account|transaction|customer]
     *
     * @return [boolean|string]       [if data is valid|success]
     */
    public function upload($file, $type){
        
        //Check if the uplaoded file is a csv file
        if($file['type'] != 'text/csv') return 'wrong filetype';
        
        if(!move_uploaded_file($file['tmp_name'], 'csv/temp.csv')) return 'could not upload file';
        //contents of uplaoded file
        $contents = $this->read('temp');
        
        //check if the file has valid data
        $check = $this->checkIfValidData($contents, $type);
        if(gettype($check) != 'boolean') return $check;
        
        //write the uploaded data to the existing file, no need to upload the file the users selected we just need to get the contents.
        if($this->write('csv/'.$type.'s.csv', file_get_contents('csv/temp.csv'))){
            unlink('csv/temp.csv');
            return 'uplaoded';
        }
        
    }
        
    
    /**
     * check if the datasets are valid
     *
     * @method checkIfValidData
     *
     * @author [Agne Ødegaard]
     *
     * @param  array           $contents [file contents]
     * @param  string           $type    [account|transaction|customer]
     *
     * @return boolean                   data is valid 
     */
    private function checkIfValidData($contents, $type){
        switch($type){
            
            case 'account' :
                
                foreach($contents as $row){
                    
                    //check if the account already exists
                    if(array_key_exists($row['id'], $this->accounts)) return 'account already exists';
                    
                    //check if the account holder exists
                    if(!array_key_exists($row['holder'], $this->customers)) return 'account with id '.$row['id'].' does not have an existing customer.';
                }
                
                break;
                
            case 'customer' :
                foreach($contents as $row){
                    //check if the customer already exists
                    if(array_key_exists($row['id'], $this->customers)) return 'customer already exists';
                }
                break;
                
            case 'transaction':
                foreach($contents as $row) {
                    //check if the transaction already exists
                    if(array_key_exists($row['id'], $this->transactions)) return 'the transaction does already exists';
                    
                    //check if the account from and the account to exists
                    if(!array_key_exists($row['from'], $this->accounts) || !array_key_exists($row['to'], $this->accounts)) return 'no accounts with id '.$row['id'];
                    
                }
                break;
                
            default :
                return 'invalid data type';
                break;    
        }
        
        return true;
    }
        
    
    /**
     * change a get request key=value and return the new get request url string
     *
     * @method GET_request
     *
     * @author [Agne Ødegaard]
     *
     * @param  string      $key   $_GET key
     * @param  string      $value $_GET value
     *
     * @return string             http query
     */
    public function GET_request($key, $value){
        $arr = $_GET;
        $arr[$key] = $value;
        return "/?".http_build_query($arr);
    }
    
    /**
     * unset a getvalue and return the new string
     *
     * @method unset_get
     *
     * @author [Agne Ødegaard]
     *
     * @param  string    $key $_GET key
     *
     * @return string         http query
     */
    public function unset_get($key){
        $arr = $_GET;
        unset($arr[$key]);
        return "/?".http_build_query($arr);
    }
    
    /**
     * toogle a get value on and off and return the new string
     *
     * @method toogle_get
     *
     * @author [Agne Ødegaard]
     *
     * @param  string     $key $_GET key
     *
     * @return string          http query
     */
    public function toogle_get($key){
        if(isset($_GET[$key])){
            return $this->unset_get($key);
        } else {
            return $this->get_request($key, '');
        }
    }
        
}
$app = new App();