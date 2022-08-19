# Code Challenge

## Http Endpoints

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
