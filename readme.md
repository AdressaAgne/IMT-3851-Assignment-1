*Agne Ødegaard - 140699*

# HOW to use my application

sadly i did not follow the assignment 100%, i have all frontend on 1 page. i think this is just nicer. 
1 Class file for accounts, transactions and customers. with one app class that have sorting, populating read and write functions that basically run everthing.

the code structure on index.php is not optimal, i would like to have an MVC to work with but i feel that would complicate this assignment a bit too much. so i tried to minimalize the code on index.php. by using short-hand if's and just calling functions in App.

### Sorting

In the page header you have 4 links, one should be "desc" or "asc" it will change depening on what way its already sorted in. you have 3 other values, click what you want to sort by.

### Look at an account
Click the link on an account to show the account and its withdrawals and deposits, again click different withdrawals or deposits to go to that account.

### uploading
choose csv/new_customer.csv and select customer then press the upload button... the uploading form is at the bottom of index.php
i also made csv/new_accounts.csv and csv/new_transactions.csv for the new customer added.