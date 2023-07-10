# AWS PHP SDK Marketplace Integration

This code provides integration with the AWS Marketplace for retrieving customer entitlements based on a registration token. It utilizes the AWS SDK for PHP to interact with the AWS Marketplace Metering and Marketplace Entitlement Service APIs.

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

## Requirements
- PHP version 5.6 or higher
- The AWS SDK for PHP installed in your project. You can install it using Composer by running the command

```shell 
composer require aws/aws-sdk-php
```

## Usage

To use the code for integrating with the AWS Marketplace, follow the steps below:

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

Configure the AWS credentials and region. Replace 'us-east-1' with the appropriate AWS region, and provide your AWS access key ID and secret access key in the Credentials constructor.

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

Retrieve the registration token from the POST request. This token is sent as the x-amzn-marketplace-token header in the request.

```php
$token = $_POST['x-amzn-marketplace-token'];
```

### 5. Resolve the customer data using the registration token:

Resolve the customer data using the registration token. This step involves making a request to the AWS Marketplace Metering API to obtain the customer's product code and identifier.

```php 
$meteringClient = new MarketplaceMeteringClient($config);

$customerData = $meteringClient->resolveCustomer(['RegistrationToken' => $token]);

$ProductCode = $customerData['ProductCode'];

$CustomerIdentifier = $customerData['CustomerIdentifier'];

```

### 6. Get the customer entitlements using the Marketplace Entitlement Service:

Get the customer entitlements using the Marketplace Entitlement Service API. This involves creating a client for the Marketplace Entitlement Service and making a getEntitlements API call, passing the product code and customer identifier as parameters.

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

## License

This project is licensed under the MIT License. Feel free to modify and use the code according to your needs.

For more information and detailed usage instructions, please refer to the <a href="https://docs.aws.amazon.com/aws-sdk-php/" target="_blank">AWS SDK</a> for PHP documentation.

## Disclaimer
Please note that this script is provided as-is without any warranty. Use it at your own risk.

## Contact Me

If you have any clarifications or questions about this, please free to contact me: <a href="mailto: dinakaran.kannadhasan@gmail.com">dinakaran.kannadhasan@gmail.com</a>.
