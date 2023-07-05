<?php 

//Marketplace Integrgation
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/aws/aws-autoloader.php';

use Aws\AwsClient;
use Aws\MarketplaceMetering;
use Aws\MarketplaceMetering\MarketplaceMeteringClient;
use Aws\MarketplaceEntitlementService;
use Aws\MarketplaceEntitlementService\MarketplaceEntitlementServiceClient;
use Aws\Credentials\Credentials;
use Aws\Exception\AwsException;


if(isset($_POST['x-amzn-marketplace-token']) && !empty($_POST['x-amzn-marketplace-token'])){

  $region = 'us-east-1'; // Replace with the appropriate AWS region
  $credentials = new Credentials(AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY); // Replace with your AWS credentials

    $config = [
    'credentials'  => $credentials,
    'region'       => $region,
    'version'      => 'latest'
  ];

  $token = $_POST['x-amzn-marketplace-token'];

  $meteringClient = new MarketplaceMeteringClient($config);

  $customerData = $meteringClient->resolveCustomer([ 'RegistrationToken' => $token ]);

  $ProductCode = $customerData['ProductCode'];

  $CustomerIdentifier = $customerData['CustomerIdentifier'];

  $client = new MarketplaceEntitlementServiceClient([
      'version' => 'latest',
      'region' => $region,
      'credentials' => $credentials
  ]);

  $params = [
      'ProductCode' => $ProductCode , // Replace with the product code for which you want to retrieve entitlements
      'Filter' => [
          'CUSTOMER_IDENTIFIER' => [ $customerIdentifier ],
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

}

?>
