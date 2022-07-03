# Vouch Backend Exercise

## Somethings  I would improve
- better validation - e.g. date parsing, address checks, currency checks etc..
- creation of endpoint with upload tool, currently on run from command
- Allow for dynamic file input from command and endpoint.

## Answer to problem 1

###See [solution to problem 1](solution.md)

---

# Pre-reqs for running code

Make sure you have docker and docker-compose installed on your machine

## Getting started

There is a make file to facilitate things. its worthwhile having a look to see what's possible.

`make init` will build the docker image, container and run some first time setup

## Running the code

you need to exec into the container and run it from the command line

`make exec`

once there you will need run the CalculateAffordabilityCommand command as such:

`php artisan calculate:affordability`

`php artisan calculate:affordability 1000` - where 1000 is the current rent

## Tests

`make test` - There are unit tests from outside container

## Code sniffer

Code adheres to code style standards

`make sniff` - runs the sniffer
`makes sniff-fix` - runs the sniffer and tries to fix any errors

## Static analysis

Uses PHPstan analyse code and show errors - All errors are fixed at time of commit

`make analyse`

# Code coverage

Ordinarily I would not commit the code coverage report, in the reports directory.  You can view the report
from my runs at `reports/coverage/index.html`

`make coverage` - generates the report - won't work as I removed xdebug install step from docker file as it effects
performance.

---

# Original vouch readme

One of the most frustrating things about the lettings process for tenants is the affordability checks.

Usually this is done after the tenant has viewed multiple properties, selected one and paid a security deposit. If the tenant fails the affordability check they will not be allowed into the property and will need to restart their search. In some cases they may lose the security deposit.

In order to ease this frustration we want you to build a service that allows tenants to carry out an affordability check against a list of properties.

A tenant is able to afford a property if their monthly recurring income exceeds their monthly recurring expenses by the total of the monthly rent times 125%.

1/ Given the test data below write a unit test and a respective function/service that will use the list of properties and a list of bank transactions and calculate which properties the tenant can afford:
###  (answer in solution.md file)
```
//Given 
string[][] statementData = new string[][] {
    new string[]{"1st January 2020", "Direct Debit", "Gas & Electricity", "£95.06", "", "£1200.04"},
    new string[]{"2nd January 2020", "ATM", "HSBC Holborn", "£20.00", "", "£1180.04"},
    new string[]{"3rd January 2020", "Standing Order", "London Room", "£500.00", "", "£680.04"},
    new string[]{"4th January 2020", "Bank Credit", "Awesome Job Ltd", "", "£1254.23", "£1934.27"},
    new string[]{"1st February 2020", "Direct Debit", "Gas & Electricity", "£95.06", "", "£1839.21"},
    new string[]{"2nd February 2020", "ATM", "@Random", "£50.00", "", "£1789.21"},
    new string[]{"3rd February 2020", "Standing Order", "London Room", "£500.00", "", "£1289.21"},
    new string[]{"4th February 2020", "Bank Credit", "Awesome Job Ltd", "", "£1254.23", "£2543.44"}
}; // date, type, description, money out, money in, balance

string[][] propertiesData = new string[][]{
    new string[]{"1", "1, Oxford Street", "300"},
    new string[]{"2", "12, St John Avenue", "750"},
    new string[]{"3", "Flat 43, Expensive Block", "1200},
    new string[]{"4", "Flat 44, Expensive Block", "1150"}
}; // id, address, rent per month

const expected = new Property(1, "1, Oxford Street", 300000) 
            
// When
const affordableProperties = affordabilityService.Check(statementData propertiesData);
        
// Then
Assert.AreEqual(expected, affordableProperties)
        
// Assuming class object of
class Property
{
    public int Id { get; set; }
    public string Address { get; set; }
    public int RentPerMonthPence { get; set; }

    // Public constructor.
    public Contact(int id, string address, int rentPerMonthPence)
    {
        Id = id;
        Address = address;
        RentPerMonthPence = rentPerMonthPence;
    }
}
```

2/ Build a program that uses the files `bank_statement.csv` and `properties.csv` provided in `/files` and runs the through the service to calculate the correct result.

3/ What other tests should be written to ensure our functionality is working as intended and handles errors gracefully? Implement said tests.
