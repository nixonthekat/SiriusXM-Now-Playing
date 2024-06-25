# SiriusXM Now Playing Data Scraper

This PHP script fetches and combines "now playing" data from SiriusXM's LookAround and Now-Playing services, providing a unified JSON response containing current and upcoming songs, as well as additional metadata such as album art and station logo.

## Prerequisites

- PHP 7.0 or higher
- cURL extension for PHP

## Installation

1. Clone or download this repository to your PHP server.

2. Ensure the cURL extension is enabled in your `php.ini` file:

    ```ini
    extension=curl
    ```

## Configuration

Update the `$channelMappings` array in the script to include mappings of channel hashes to channel codes:

```php
// Define the hash to channel code mappings
$channelMappings = [
    "53f1ca49-64d8-2bbb-8d01-01482ed647cd" => "altnation",
];

More mappings will be added as I progress through this project. This is just for personal learning purposes.