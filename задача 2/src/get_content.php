<?php

error_reporting(E_ERROR | E_PARSE);
include_once "./dbconnection.php";

$result = getUrlData('https://www.bills.ru/');

$dataToInsert = getDataToStore($result);

insertIntoTable($dataToInsert, $pdo);

/**
 * @param array $dataToInsert array containing date, title and url to insert
 * @return void
 */
function insertIntoTable($dataToInsert,$pdo)
{
    $sql = 'INSERT INTO bills_ru_events (date, title, url) VALUES (?, ?, ?)';
    $stmt = $pdo->prepare($sql);

    // Execute the statement for each data row
    foreach ($dataToInsert as $row) {
        if (!$stmt->execute($row)) {
            echo pg_last_error($conn);
        } else {
            echo "Inserted successfully \n";
        }
    }
}

/**
 * @param $result result got from site
 * @return array array contains all date,title and url
 */
function getDataToStore($result)
{
    $dom = new DOMDocument();
    $dom->loadHTML($result);
    $xpath = new DOMXPath($dom);

    $newsRows = $xpath->query('//table[@id="bizon_api_news_list"]')->item(0);
    $multi_rows = [];
    $row_a_tag = $xpath->query('.//td/a', $newsRows);

    for ($i = 0; $i < ($row_a_tag)->length; +$i++) {
        $date = $xpath->query('.//td[@class="news_date"]', $newsRows)[$i]->nodeValue;
        $link = $row_a_tag[$i]->getAttribute('href');
        $title = $row_a_tag[$i]->nodeValue;
        $formatted_date = formateDate($date);

        $multi_rows[] = ["$formatted_date", "$title", "$link"];
    }
    return $multi_rows;
}

/**
 * @param $url url of site to get data
 * @return bool|string
 */
function getUrlData($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($ch, CURLOPT_USERAGENT, 'parserbill');
    return curl_exec($ch);
}

/**
 * @param $date date format date to yyyy-mm-dd hh:mm:ss
 * @return false|string returns formatted date
 */
function formateDate($date)
{
    $month_map = array(
        "янв" => 1,
        "фев" => 2,
        "мар" => 3,
        "апр" => 4,
        "май" => 5,
        "июн" => 6,
        "июл" => 7,
        "авг" => 8,
        "сен" => 9,
        "окт" => 10,
        "ноя" => 11,
        "дек" => 12
    );
    if (preg_match("/(\d+) ([а-я]+) (\d+)/iu", $date, $matches)) {
        $day = $matches[1];
        $month = $month_map["мар"];

        $year = $matches[3];

        $timestamp = strtotime("$year-$month-$day");
        if ($timestamp !== false) {
            $formatted_date = date("Y-m-d H:i:s", $timestamp);
            return $formatted_date;
        }
    }
}
