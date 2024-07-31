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
    "0fed9647-cc82-24d7-526d-98762e8a52cd" => "octane",
    "32c4747f-8739-d578-62d8-08a92d518445" => "lithium",
    "834383dd-9a7e-d59e-81a2-dd13e0377af2" => "chill",
    "91be75cc-537b-0ff4-7a38-d1983c8d99d1" => "theheat",
    "2ea07147-a720-ed0c-d4ce-d7bddd1640d3" => "80s",
    "bda81f03-f231-d4db-56ad-40cb132c5663" => "theblend",
    "2d926e91-aa32-4e53-85e5-d444870eff11" => "classicrock",
    "f936a498-89c7-bc15-f158-19a015db0683" => "turbo",
    "9093733f-773f-773f-773f-773f773f773f" => "thepulse",
    "ef940a5b-255c-9f91-a3a5-41c6a9a24260" => "90son9",
    "44f9129f-579a-3d23-218f-3c3518036fc6" => "poprocks",
    "2022dfab-580f-675f-6aad-00aad9c3be9b" => "sway45",
    "098425c8-142d-9ee9-3d45-bc5f7b284bd4" => "flex2k",
    "95179ecb-a419-71aa-f3e6-92f5c4db1f7f" => "hiphopnation",
    "21c2a98a-e53e-c944-8e23-12cabd6c3439" => "rockthebell",
];

More mappings will be added as I progress through this project. This is just for personal learning purposes.
