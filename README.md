# Code Challenge

## Http Endpoints

`{{ BaseURL }}` : deployment endpoint, for examle: https://code-challenge-ir.com

### Charge wallet using charge code

**Method**: POST
```
{{ BaseURL }}/api/v1/wallet/charge
```
#### Request Body 

| key | description |
|-----|-------------|
|mobile| user mobile number (e164 format)|
|code| charge code |

```json
{
    "mobile": "+98923456789",
    "code": "135768"
}
```

### Get Wallet Balance

**Method**: GET

``` 
{{ BaseURL }}/api/v1/wallet/balance
```

#### URL parameters 

| key | description |
|-----|-------------|
|mobile| user mobile number (e164 format)|

### Get Transactions

**Method**: GET

``` 
{{ BaseURL }}/api/v1/wallet/transactions
```

#### URL parameters 

| key | description |
|-----|-------------|
|mobile| user mobile number (e164 format)|

### Create Charge Code

**Method**: POST

```
{{ BaseURL}}/api/v1/charge-codes
```
#### Request Body
```json
{
    "code":"123456789",
    "max_usage": 1000,
    "charge_amount": 5000000
}
```

#### Request Body Schema 

| key | description |
|-----|-------------|
|code| Charge Code (Optional)|
|max_usage|maximum number of users, can charge with the code (Required) - Min: 1|
|charge_amount|amout of charge (Required), Min:1000|

### Get List of charge Codes
**Method**: GET
```
{{ BaseURL }}/api/v1/charge-codes
```

### Get Charge Code transactions

**Method**: GET

```
{{ BaseURL }}/charge-codes/{code}
```

#### Variables
| name | description |
|-----|-------------|
|code| Charge Code (Required)|
