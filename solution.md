# Solution to pseudocode problem 1 from Readme

This is clearly some c style syntax I'm not 100% familiar with, so it's not intended to be run.
I've shown the algorithm that I've chosen to solve the problem.
There appears to be no `c` block annotation for the snipped block below so it has java syntax highlighting.

The income for this user is `£1254.23` with outgoings of `£595.06`  his affordablity is `£695.17`
So property 1 is the only the user can afford.   Although there is the consideration that we should exlcude the standing
order for the current rent, as this will no longer be a factor in the users affordabilty.

```java
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

//Unit test class
class AffordabilityServiceTest()
{
    public testAffordability()
    {
        const expected = new Property(1, "1, Oxford Street", 300000);
        const affordabilityService = new AffordabilityService();
        const affordableProperties = affordabilityService.Check(statementData, propertiesData);

        // solution finds all, not just one.
        Assert.count(1, affordableProperties);
        affordableProperty = affordableProperties[0];
        Assert.AreEqual(expected, affordableProperty);
    }
}


// Assuming class object of
class Property
{
    public int Id { get; set; }
    public string Address { get; set; }
    public int RentPerMonthPence { get; set; }

    // Public constructor.
    public Property(int id, string address, int rentPerMonthPence)
    {
        Id = id;
        Address = address;
        RentPerMonthPence = rentPerMonthPence;
    }
}

// Class Object for transaction
class Transaction
{
    public date Date {get; set; }
    public string Type { get; set; }
    public string Description { get; set; }
    public double MoneyOut { get; set; }
    public double MoneyIn { get; set; }
    public double Balance { get; set; }

    public Transaction(string[] transaction)
    {
        Date = transaction[0];
        Type = transaction[1];
        Description = transaction[2];
        MoneyOut = transaction[3];
        MoneyIn = transaction[4];
        Balance = transaction[5];
    }

    public isRecurringExpense(): bool
    {
        recurringExpenses = array("Direct Debit", "Standing Order");
        return inArray(Type, recurringExpenses);
    }
    
    public isSourceOfIncome(): bool
    {
        income = array("Bank Credit");
        return inArray(Type, income);
    }
}

class AffordabilityService
{
    private IncomeService IncomeService;

    //Constructor
    public AffordabilityService
    {
        IncomeService = new IncomeService();
    }

    public Check(string[][] statementData, string[][] propertiesData)
    {
        var disposableIncome = IncomeService.getMonthlyDisposableIncomeFromStatement(statementData);
        const affordableProperties = [];
        for (propertyListing in propertiesData)
        {
            const prop = new Property(propertyListing[0], propertyListing[1], propertyListing[2] * 100);
            if (disposableIncome * 100 > prop.RentPerMonthPence) {
                affordableProperties[] = prop;
            }
        }

        return affordableProperties;
    }
}

class IncomeService
{
    // returns a double or 0 as the dispsoable income after income and outings have been considered
    public getMonthlyDisposableIncomeFromStatement(string[][] statementData): double
    {
        const monthlySpendStore = [];
        for (statementEntry in statementData) {
            transaction = new Transaction(statementEntry)
            month = library.getMonthFromString(transaction.Date); // pseudocode for date parsing
    
            if (!monthlySpendStore[month]) {
                monthlySpendStore[month] = 0; // no notion of month yet, set ledger to 0
            }
    
            if (transaction.isRecurringExpense) {
                monthlySpendStore[month] -= transaction.MoneyOut; // Recurring expense substract from ledger
            } else if (transaction.isSourceOfIncome) {
                monthlySpendStore[month] -= transaction.MoneyIn // income source, append to ledger
            }
        }
    
        return count(monthlySpendStore) ? array_sum(monthlySpendStore) / count(monthlySpendStore) : 0;
    }
}
```
