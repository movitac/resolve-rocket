<?php
require "vendor/autoload.php";

use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;

$data = json_decode(file_get_contents("php://input"));

$text = $data->text;

$client = new Client(""); // Need to ask for the API Key to make the chabot function.

$response = $client->geminiPro()->generateContent(new TextPart($text));

$text = $response->text(); // Store the raw text

// Identify list items based on prefixes
$listItemPrefixes = ["- ", "* "]; // Adjust as needed for bullet and dash prefixes

$formatted_response = "";
$currentListType = ""; // Track the current list type (bullet or dash)

$lines = explode("\n", $text);
foreach ($lines as $line) {
  if (empty($line)) {
    continue; // Skip empty lines
  }

  $isListItem = false;
  foreach ($listItemPrefixes as $prefix) {
    if (strpos($line, $prefix) === 0) {
      $isListItem = true;
      $currentListType = $prefix; // Update current list type
      $line = substr($line, strlen($prefix)); // Remove the prefix
      break;
    }
  }

  if ($isListItem) {
    // Add list item based on current list type (remove asterisk)
    $formatted_response .= "<li>$line</li>";
  } else {
    // Not a list item, add a paragraph break
    $formatted_response .= "<p>$line</p>";
  }
}

// Wrap in appropriate list tags if needed
if (strpos($formatted_response, "<li>") !== false) {
  $formatted_response = (strpos($formatted_response, "<li>") === 0) ? $formatted_response : "<ul>$formatted_response</ul>"; // Wrap in UL if list items start the response
  $formatted_response = str_replace("</li></ul>", "</li>", $formatted_response); // Fix closing </li> for single list item
}

echo $formatted_response;
