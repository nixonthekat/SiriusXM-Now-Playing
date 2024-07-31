<?php

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

// Custom station logos for specific channels
$customStationLogos = [
    "53f1ca49-64d8-2bbb-8d01-01482ed647cd" => "https://www.siriusxm.ca/wp-content/uploads/2020/03/Alt-Nation.png",
    "0fed9647-cc82-24d7-526d-98762e8a52cd" => "https://www.siriusxm.ca/wp-content/uploads/2020/04/Octane.png",
    "32c4747f-8739-d578-62d8-08a92d518445" => "https://www.siriusxm.ca/wp-content/uploads/2020/04/Lithium.png",
    "834383dd-9a7e-d59e-81a2-dd13e0377af2" => "https://www.siriusxm.ca/wp-content/uploads/2020/04/SiriusXM-Chill.png",
    "91be75cc-537b-0ff4-7a38-d1983c8d99d1" => "https://www.siriusxm.ca/wp-content/uploads/2021/09/The-Heat.png",
    "1c65ae75-479b-6af7-e1d9-ed789042ade5" => "https://www.siriusxm.ca/wp-content/uploads/2020/03/80s-on-8.png",
    "bda81f03-f231-d4db-56ad-40cb132c5663" => "https://www.siriusxm.ca/wp-content/uploads/2020/04/The-Blend.png",
    "2d926e91-aa32-4e53-85e5-d444870eff11" => "https://www.siriusxm.ca/wp-content/uploads/2020/02/Classic-Rewind.png",
    "f936a498-89c7-bc15-f158-19a015db0683" => "https://www.siriusxm.ca/wp-content/uploads/2020/04/SiriusXM-Turbo.png",
    "9093733f-773f-773f-773f-773f773f773f" => "https://www.siriusxm.ca/wp-content/uploads/2020/04/The-Pulse.png",
    "ef940a5b-255c-9f91-a3a5-41c6a9a24260" => "https://www.siriusxm.ca/wp-content/uploads/2020/04/90s-on-9.png",
    "44f9129f-579a-3d23-218f-3c3518036fc6" => "https://www.siriusxm.ca/wp-content/uploads/2020/04/Pop-Rocks.png",
    "2022dfab-580f-675f-6aad-00aad9c3be9b" => "https://www.siriusxm.ca/wp-content/uploads/2020/04/Shade-45.png",
    "098425c8-142d-9ee9-3d45-bc5f7b284bd4" => "https://www.siriusxm.ca/wp-content/uploads/2023/10/Flex-2K.png",
    "95179ecb-a419-71aa-f3e6-92f5c4db1f7f" => "https://www.siriusxm.ca/wp-content/uploads/2020/04/Hip-Hop-Nation.png",
    "21c2a98a-e53e-c944-8e23-12cabd6c3439" => "https://www.siriusxm.ca/wp-content/uploads/2023/09/Rock-the-Bells-Radio.png"
];

// Function to get the now playing data from the lookAround endpoint
function getLookAroundData($channelKey) {
    global $channelMappings;

    // Get the hash for the provided channel key
    $channelHash = array_search($channelKey, $channelMappings);
    if ($channelHash === false) {
        error_log("Invalid channel key provided: $channelKey");
        echo json_encode(["error" => "Invalid channel key provided."]);
        return false;
    }

    // Fetch the JSON data from the lookAround endpoint
    $url = "https://lookaround-cache-prod.streaming.siriusxm.com/contentservices/v1/live/lookAround";
    $jsonData = @file_get_contents($url);

    // Check if the JSON data was successfully retrieved
    if ($jsonData === false) {
        error_log("Failed to retrieve data from the URL: $url");
        echo json_encode(["error" => "Failed to retrieve data from the URL."]);
        return false;
    }

    // Decode the JSON data
    $data = json_decode($jsonData, true);

    // Check if the JSON was successfully decoded
    if ($data === null) {
        error_log("Failed to decode JSON data: $jsonData");
        echo json_encode(["error" => "Failed to decode JSON data."]);
        return false;
    }

    // Extract the required data
    $channelData = $data['channels'][$channelHash] ?? null;
    if ($channelData === null) {
        error_log("Channel data not found in the JSON response for hash: $channelHash");
        echo json_encode(["error" => "Channel data not found in the JSON response."]);
        return false;
    }

    return $channelData;
}

// Function to get additional data (album art and station logo) from the SiriusXM now-playing service
function getAdditionalData($channelKey) {
    $url = "https://www.siriusxm.ca/now-playing-service/?ajaxurl=%2Fnow-playing-service%2F&action=channels_now_playing&shortcode_id=&channel_keys=" . urlencode($channelKey);
    $jsonData = @file_get_contents($url);

    if ($jsonData === false) {
        error_log("Failed to retrieve data from the SiriusXM now-playing service URL: $url");
        echo json_encode(["error" => "Failed to retrieve data from the SiriusXM now-playing service."]);
        return false;
    }

    $data = json_decode($jsonData, true);
    if ($data === null) {
        error_log("Failed to decode JSON data from the SiriusXM now-playing service: $jsonData");
        echo json_encode(["error" => "Failed to decode JSON data from the SiriusXM now-playing service."]);
        return false;
    }

    $songTitle = $data['channels'][0]['song_title'] ?? null;
    $artistName = $data['channels'][0]['artist_name'] ?? null;
    $albumArt = $data['channels'][0]['album_art'] ?? null;
    $stationLogo = $data['channels'][0]['cpt_logo_url'] ?? null;

    return [
        'song' => $songTitle,
        'artist' => $artistName,
        'album_art' => $albumArt,
        'station_logo' => $stationLogo,
    ];
}

// Get the channel key from the query parameter
$channelKey = isset($_GET['channel_key']) ? $_GET['channel_key'] : null;

if ($channelKey) {
    $lookAroundData = getLookAroundData($channelKey);
    $additionalData = getAdditionalData($channelKey);

    if ($lookAroundData && $additionalData) {
        // Handle now playing and upcoming songs
        $nowPlaying = end($lookAroundData['cuts']);
        $upcomingSongs = array_slice($lookAroundData['cuts'], 0, -1);

        // Determine the custom station logo to use
        $customStationLogo = $customStationLogos[array_search($channelKey, $channelMappings)] ?? null;

        // Debugging: Log the channel key and custom logo determination
        error_log("Channel Key: $channelKey");
        error_log("Custom Station Logo: " . ($customStationLogo ?? 'Not Found'));

        // Combine the data
        $responseData = [
            'channel' => $channelKey,
            'now_playing' => $nowPlaying,
            'upcoming_songs' => $upcomingSongs,
            'last_song' => $additionalData['song'],
            'last_artist' => $additionalData['artist'],
            'album_art' => $additionalData['album_art'],
            'station_logo' => $additionalData['station_logo'],
            'custom_station_logo' => $customStationLogo
        ];

        // Set the content type to application/json
        header('Content-Type: application/json');
        echo json_encode($responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    } else {
        echo json_encode(["error" => "Failed to retrieve data."]);
    }
} else {
    echo json_encode(["error" => "No channel key provided."]);
}

?>
