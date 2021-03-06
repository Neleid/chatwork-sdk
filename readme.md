Chatwork SDK for PHP
==========
##### Remember that this SDK is non-official. It may not work when Chatwork update their APIs in the feature.

[![Build Status](https://travis-ci.org/neleid/chatwork-sdk.svg?branch=master)](https://travis-ci.org/neleid/chatwork-sdk)
[![Latest Stable Version](https://poser.pugx.org/neleid/chatwork-sdk/v/stable)](https://packagist.org/packages/neleid/chatwork-sdk)
[![Total Downloads](https://poser.pugx.org/neleid/chatwork-sdk/downloads)](https://packagist.org/packages/neleid/chatwork-sdk)
[![Latest Unstable Version](https://poser.pugx.org/neleid/chatwork-sdk/v/unstable)](https://packagist.org/packages/neleid/chatwork-sdk)
[![License](https://poser.pugx.org/neleid/chatwork-sdk/license)](https://packagist.org/packages/neleid/chatwork-sdk)

This project is forked from [wataridori/chatwork-sdk](https://github.com/wataridori/chatwork-sdk) and added some features. If you want to use original repository, you should check [wataridori/chatwork-sdk](https://github.com/wataridori/chatwork-sdk).

This repository is registered to Packagist. So you can use composer to install this repository with 'neleid/chatwork-sdk'.

##### Chatwork SDK supports [Chatwork API version 2](http://help.chatwork.com/hc/ja/articles/115000019401)
##### Check the Chatwork API Document [here](http://developer.chatwork.com/ja/index.html)
##### English API Document is [here](http://download.chatwork.com/ChatWork_API_Documentation.pdf)

## Differences with original repository
* Added support for Chatwork API usage limits. You can get API Usage Limits information after successfull API call.
* Added support for Chatwork 'self_unread' feature. This enables the messages you posted unread. (It means you will be notified with chime.)
* Changed namespace from wataridori to Neleid.
* PHP Requirements is up to 7.0 from 5.4.

## Requirement
* PHP >= 7.0
* PHP cURL

## Install

You can install and manage Chatwork SDK for PHP by using `Composer`

```
composer require neleid/chatwork-sdk
```

Or add `neleid/chatwork-sdk` into the require section of your `composer.json` file then run `composer update`

## Usage

##### Firstly, to use Chatwork API, you must register an API Key.
##### Pass your key to `ChatworkSDK` class.
```php
ChatworkSDK::setApiKey($apiKey);
```

If you have problems with the SSL Certificate Verification, you can turn it off by the following setting.
```php
// Not recommend. Only do this when you have problems with the request
ChatworkSDK::setSslVerificationMode(false);
```

Now you can easily use many functions to access [Chatwork API Endpoints](http://developer.chatwork.com/ja/endpoints.html).

##### ChatworkSDK's Classes

ChatworkAPI: This is the class that contains base API. You can use it to send request to Chatwork and receive the response in array.
```php
ChatworkSDK::setApiKey($apiKey);
$api = new ChatworkApi();
// Get user own information
$api->me();

// Get user own statics information
$api->getMyStatus();

// Get user rooms list
$api->getRooms();
```

##### ChatworkSDK also provides many others class that help you to work in more object oriented way.
* ChatworkRoom: Use for store Room Information, with many functions to work with Room
* ChatworkUser: Use for store User Information.
* ChatworkMessage: Use for store Message Information.

```php
ChatworkSDK::setApiKey($apiKey);
$room = new ChatworkRoom($roomId);
// The following function will return an array of ChatworkUser
$members = $room->getMembers();
foreach ($members as $member) {
    // Print out User Information
    print_r($member->toArray());
}

// Send Message to All Members in the Room
$room->sendMessageToAll('Test Message');

// Send Message to list of members in the room
$room->sendMessageToList([$member_1, $member_2], 'Another Test Message');
```

The 3 classes above are extended from the `ChatworkBase` class. `ChatworkBase` provides you some useful function to work with messages.
You can easily build a TO message, REPLY or QUOTE message.
```php
ChatworkSDK::setApiKey($apiKey);
$room = new ChatworkRoom($roomId);
$messages = $room->getMessages();
if ($messages & !empty($messages[0])) {
    $lastMessage = $messages[0];
    // Reset Message to null string
    $room->resetMessage();
    // Append the REPLY text to current message
    $room->appendReplyInRoom($lastMessage);
    // Append the QUOTE text to current message
    $room->appendQuote($lastMessage);
    // Append the Information Text to the current message
    $room->appendInfo('Test Quote, Reply, Info text', 'Test from Chatwork-SDK');
    // Send current message into the Room
    $room->sendMessage();
}
```

## Run test
* Create a file named `config.json` inside the `tests/fixtures/` folder.
* Input your API Key, and a test Room into `config.json` file. It should look like this:
```json
{
  "apiKey": "YOUR-API-KEY-HERE",
  "roomId": "YOUR-TEST-ROOM-HERE"
}
```
* Then run `phpunit` to start testing.

## Contribution
View contribution guidelines [here](./CONTRIBUTING.md)

