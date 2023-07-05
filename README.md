# AWS PHP SDK Marketplace Integration

This code provides integration with the AWS Marketplace for retrieving customer entitlements based on a registration token.

## Requirements
- PHP version 5.6 or higher
- AWS SDK for PHP

### 1. Include the AWS SDK autoloader at the beginning of your PHP file:

```php 
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/aws/aws-autoloader.php'; `
```

### 2. Import the necessary classes:

``` php
use Aws\AwsClient;
use Aws\MarketplaceMetering;
use Aws\MarketplaceMetering\MarketplaceMeteringClient;
use Aws\MarketplaceEntitlementService;
use Aws\MarketplaceEntitlementService\MarketplaceEntitlementServiceClient;
use Aws\Credentials\Credentials;
use Aws\Exception\AwsException;
```

### 3. Set up the AWS credentials and configuration:

```php

$region = 'us-east-1'; // Replace with the appropriate AWS region
$credentials = new Credentials(AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY); // Replace with your AWS credentials

$config = [
  'credentials'  => $credentials,
  'region'       => $region,
  'version'      => 'latest'
];

```

### 4. Retrieve the registration token from the POST request:

```php
$token = $_POST['x-amzn-marketplace-token'];
```

### 5. Resolve the customer data using the registration token:

```php 
$meteringClient = new MarketplaceMeteringClient($config);

$customerData = $meteringClient->resolveCustomer(['RegistrationToken' => $token]);

$ProductCode = $customerData['ProductCode'];

$CustomerIdentifier = $customerData['CustomerIdentifier'];

```

### 6. Get the customer entitlements using the Marketplace Entitlement Service:

```php

$client = new MarketplaceEntitlementServiceClient([
    'version' => 'latest',
    'region' => $region,
    'credentials' => $credentials
]);

$params = [
    'ProductCode' => $ProductCode, // Replace with the product code for which you want to retrieve entitlements
    'Filter' => [
        'CUSTOMER_IDENTIFIER' => [$customerIdentifier],
    ],
];
      
$result = $client->getEntitlements($params);

$entitlements = $result['Entitlements'];
$nextToken = $result['NextToken'];

$response = [
    'Entitlements' => $entitlements,
    'NextToken' => $nextToken,
];

echo json_encode($response, JSON_PRETTY_PRINT);

```
