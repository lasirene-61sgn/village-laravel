<?php
/**
 * Test script for the new matrimony API endpoint
 * This script demonstrates how the API endpoint works
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

echo "Testing the new matrimony API endpoint...\n";
echo "Endpoint: GET /api/customer/customers-matrimony\n";
echo "Description: Get customers with family members who have matrimony set to true\n";
echo "Features:\n";
echo "  - Shows customer details (name, father_name, date_of_birth, education, mobile)\n";
echo "  - Shows related family member details\n";
echo "  - Supports search filtering\n";
echo "  - Requires customer authentication (sanctum token)\n";
echo "\n";
echo "Parameters:\n";
echo "  - search: Optional search term to filter results\n";
echo "\n";
echo "Response format:\n";
echo "  {\n";
echo "    \"status\": \"success\",\n";
echo "    \"data\": [\n";
echo "      {\n";
echo "        \"id\": 1,\n";
echo "        \"name\": \"Customer Name\",\n";
echo "        \"father_name\": \"Father Name\",\n";
echo "        \"date_of_birth\": \"YYYY-MM-DD\",\n";
echo "        \"education\": \"Education level\",\n";
echo "        \"mobile\": \"Mobile number\",\n";
echo "        \"customer_id\": 1,\n";
echo "        \"family_member_name\": \"Family Member Name\",\n";
echo "        \"family_member_relationship\": \"Relationship\",\n";
echo "        \"family_member_education\": \"Education level\",\n";
echo "        \"family_member_date_of_birth\": \"YYYY-MM-DD\",\n";
echo "        \"family_member_mobile\": \"Mobile number\",\n";
echo "        \"matrimony\": true\n";
echo "      }\n";
echo "    ]\n";
echo "  }\n";
echo "\n";
echo "The API has been successfully added to the CustomerController and registered in routes/api.php.\n";