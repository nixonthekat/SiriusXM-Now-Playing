<?php

// Define the hash to channel code mappings
$channelMappings = [
    "53f1ca49-64d8-2bbb-8d01-01482ed647cd" => "altnation",

];

// Function to get the now playing data from the lookAround endpoint
function getLookAroundData($channelKey) {
    global $channelMappings;

    // Get the hash for the provided channel key
    $channelHash = array_search($channelKey, $channelMappings);
    if ($channelHash === false) {
        echo json_encode(["error" => "Invalid channel key provided."]);
        return false;
    }

    // Fetch the JSON data from the lookAround endpoint
    $url = "https://lookaround-cache-prod.streaming.siriusxm.com/contentservices/v1/live/lookAround";
    $jsonData = file_get_contents($url);

    // Check if the JSON data was successfully retrieved
    if ($jsonData === false) {
        echo json_encode(["error" => "Failed to retrieve data from the URL."]);
        return false;
    }

    // Decode the JSON data
    $data = json_decode($jsonData, true);

    // Check if the JSON was successfully decoded
    if ($data === null) {
        echo json_encode(["error" => "Failed to decode JSON data."]);
        return false;
    }

    // Extract the required data
    $channelData = $data['channels'][$channelHash] ?? null;
    if ($channelData === null) {
        echo json_encode(["error" => "Channel data not found in the JSON response."]);
        return false;
    }

    return $channelData;
}

// Function to get additional data (album art and station logo) from the SiriusXM now-playing service
function getAdditionalData($channelKey) {
    $url = "https://www.siriusxm.ca/now-playing-service/?ajaxurl=%2Fnow-playing-service%2F&action=channels_now_playing&shortcode_id=&channel_keys=" . urlencode($channelKey);
    $jsonData = file_get_contents($url);

    if ($jsonData === false) {
        echo json_encode(["error" => "Failed to retrieve data from the SiriusXM now-playing service."]);
        return false;
    }

    $data = json_decode($jsonData, true);
    if ($data === null) {
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

        // Combine the data
        $responseData = [
            'channel' => $channelKey,
            'now_playing' => $nowPlaying,
            'upcoming_songs' => $upcomingSongs,
            'last_song' => $additionalData['song'],
            'last_artist' => $additionalData['artist'],
            'album_art' => $additionalData['album_art'],
            'station_logo' => $additionalData['station_logo'],
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
