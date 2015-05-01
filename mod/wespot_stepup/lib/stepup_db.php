<?php
/**
 * Created by PhpStorm.
 * User: david
 * Manage stepup data in the intermmediate database
 */

// Load the httpful REST library
if (!include_once(dirname(__FILE__) . "/../httpful.phar")) {
    $msg = 'Elgg could not load the httpful library. It does not exist or there is a file permissions issue.';
    throw new InstallationException($msg);
}

global $stepup_table;
$stepup_table = 'stepup';

function table_exists($conn, $table) {
    $results = $conn->query("SHOW TABLES LIKE '$table'");
    if(!$results) {
        die(print_r($conn->errorInfo(), TRUE));
    }
    return $results->rowCount() > 0;
}

function create_table_if_not_present($conn) {
    global $stepup_table;

    if(!table_exists($conn, $stepup_table)) {
        $query = "CREATE TABLE $stepup_table (
                          id int(11) AUTO_INCREMENT,
                          target text NOT NULL,
                          body text NOT NULL,
                          success bool DEFAULT FALSE,
                          success_at TIMESTAMP NULL,
                          response_code int(11),
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          PRIMARY KEY  (id)
                          )";
        $conn->query($query);
    }
}

function get_db_connection() {
    global $CONFIG;

    try {
        $conn = new PDO("mysql:host=".$CONFIG->dbhost.";dbname=".$CONFIG->dbname, $CONFIG->dbuser, $CONFIG->dbpass);
    }
    catch(PDOException $e) {
        echo $e->getMessage();
    }

    return $conn;
}

function insert_request($target, $body) {
    global $stepup_table;
    $conn = get_db_connection();

    create_table_if_not_present($conn);

    $q = $conn->prepare("INSERT INTO $stepup_table (target, body) VALUES (?, ?)");
    $q->bindParam(1, $target, PDO::PARAM_STR);
    $q->bindParam(2, $body, PDO::PARAM_STR);

    $q->execute();

    $conn = null;
}

# http://inquiry.wespot.net/services/api/rest/json/?method=elgg.process_events
# http://localhost/elgg/services/api/rest/json/?method=elgg.process_events

# to recreate table on schema change: http://inquiry.wespot.net/services/api/rest/json/?method=elgg.process_events&option=reset
function process_requests($drop_table = false) {
    global $stepup_table;
    $conn = get_db_connection();

    if($drop_table) {
        $conn->exec("DROP TABLE $stepup_table");
    }

    create_table_if_not_present($conn);

    $MAX_PAYLOAD_ROWS = 300;

    $query = $conn->query("SELECT * FROM $stepup_table WHERE success = 0 ORDER BY created_at");
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $data = $query->fetchAll();

    $success_count = 0;

    $localhost = strtolower($_SERVER[HTTP_HOST]) == 'localhost';

    if($localhost) {
        $conn->exec("DELETE FROM $stepup_table");
        $conn = null;
        return "localhost: deleted all rows, no requests were sent";
    } else {
        foreach (array_group_by($data, function($el) { return $el['target']; }) as $target => $requests)
        {
            $authorization = null;
            if(strpos($target, 'cs.kuleuven.be') !== false) {
                $authorization = '9IywPIjfdlE7gh9T2vj523BTqu2YRkVe';
            }

            foreach (array_chunk($requests, $MAX_PAYLOAD_ROWS) as $chunk) {
                $payload = array_map(function($req) { return json_decode($req['body']); }, $chunk);
                $response = $authorization ?
                    \Httpful\Request::post($target)->sendsJson()->body($payload)->addHeader('Authorization', $authorization)->send() :
                    \Httpful\Request::post($target)->sendsJson()->body($payload)->send();

                $ids = array_map(function ($el) { return $el['id']; }, $requests);
                $list = join(',', array_fill(0, count($ids), '?'));

                if($response->code == 200) {
                    if($response->body->Success) {
                        $sql = "UPDATE $stepup_table
                        SET success = 1, response_code = $response->code, success_at = NOW()
                        WHERE id IN ($list)";
                        $conn->prepare($sql)->execute(array_values($ids));
                        $success_count += count($chunk);
                    } else {
                        break;
                    }
                } else {
                    $sql = "UPDATE $stepup_table
                        SET response_code = $response->code
                        WHERE id IN ($list)";
                    $conn->prepare($sql)->execute(array_values($ids));
                    break;
                }

            }
        }

        $conn->exec("DELETE FROM $stepup_table WHERE success=1 AND created_at < DATE_SUB(NOW(), INTERVAL 2 DAY)");

        $result = $conn->prepare("SELECT count(*) FROM $stepup_table WHERE success=0");
        $result->execute();
        $fail_count = (int)$result->fetchColumn();

        $conn = null;

        $result = Array("processed_events_from_queue" => $success_count, "failed_events_endpoint_down" => $fail_count);
        if($drop_table) {
            $result = 'table recreated and events dropped';
        }
        return $result;
    }
}

function get_db_contents() {
    global $stepup_table;
    $conn = get_db_connection();

    $query = $conn->query("SELECT * FROM $stepup_table ORDER BY created_at DESC");
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $data = $query->fetchAll();

    return $data;
}

function array_group_by(array $arr, callable $key_selector) {
    $result = array();
    foreach ($arr as $i) {
        $key = call_user_func($key_selector, $i);
        $result[$key][] = $i;
    }
    return $result;
}