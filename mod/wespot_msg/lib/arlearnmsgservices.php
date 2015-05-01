<?php

function __urlify($key, $val) {
  return urlencode($key) . '=' . urlencode($val);
}

/**
 * Get the Default Thread Messages for the given runid
 * @param $usertoken the ARLearn user token to append to the app key when sending the onBehalfOf token (as created with function createARLearnUserToken)
 * @param $runid the ARLearn Run id to get data from.
 * @return false, if the attempt failed, else the response data from the ARLearn service call (will be a json string).
 */
function getARLearnRunDefaultThreadMessages($usertoken, $runid)
{
    global $serviceRootARLearn;
    
    $url     = $serviceRootARLearn . 'rest/messages/runId/' . $runid . '/default';
    $results = callARLearnAPI('GET', $url, '', $usertoken);
    return $results;
}

/**
 * Get the Default Thread Id for a given runid
 * @param $userprovider The social sign on provider name ('Google/Facebook/LinkedIn/weSPOT' - not case sensitive).
 * @param $oauthid the OAuth id of the user to check.
 * @return threadId if default thread exists on ARLearn, else false
 */
function getARLearnRunDefaultThreadId($usertoken, $runId)
{
    global $serviceRootARLearn;
    
    $url = $serviceRootARLearn . 'rest/messages/thread/runId/' . $runId . '/default';
    
    $results = callARLearnAPI('GET', $url, '', $usertoken);
    if ($results != false) {
        $datareturned = json_decode($results);
        //@TODO check if deleted also
        if (!isset($datareturned->error)) {
            return $datareturned->threadId;
        }
    }
    
    return false;
}

/**
 * Create a new Message in the default thread for the runid
 * @param $usertoken the ARLearn user token to append to the app key when sending the onBehalfOf token (as created with function createARLearnUserToken)
 * @param $runId the ARLearn runId
 * @param $message_content the message content
 * @return false, if the attempt failed, else the response data from the ARLearn service call (will be a json string).
 */
function createARLearnDefaultThreadMessage($usertoken, $runId, $message_content)
{
    $threadId = getARLearnRunDefaultThreadId($usertoken, $runId);
    if ($threadId != false) {
        $response = createARLearnMessage($usertoken, $runId, $threadId, $message_content);
        return $response;
    }
    return false;
}

/**
 * Create a new Message in the given thread for the runid
 * @param $usertoken the ARLearn user token to append to the app key when sending the onBehalfOf token (as created with function createARLearnUserToken)
 * @param $runId
 * @param $threadId
 * @param $message_content the message content
 * @return false, if the attempt failed, else the response data from the ARLearn service call (will be a json string).
 */
function createARLearnMessage($usertoken, $runId, $threadId, $message_content)
{
    global $serviceRootARLearn;
    
    // register message on ARLearn
    $url     = $serviceRootARLearn . 'rest/messages/message';
    $data    = array(
                    'type' => 'org.celstec.arlearn2.beans.run.Message',
                    'subject' => '',
                    'body' => nl2br($message_content),
                    'runId' => $runId,
                    'threadId' => $threadId
                );
    $results = callARLearnAPI('POST', $url, json_encode($data), $usertoken);
    return $results;
}

/**
 * Create a new Thread for the runid
 * @param $usertoken the ARLearn user token to append to the app key when sending the onBehalfOf token (as created with function createARLearnUserToken)
 * @param $runId the run identifer within which the thread was created 
 * @param $name a character string that names the thread
 * @return false, if the attempt failed, else the response data from the ARLearn service call (will be a json string).
 */
function createARLearnThread($usertoken, $runId, $name)
{
    global $serviceRootARLearn;

    // register message on ARLearn
    $url     = $serviceRootARLearn . 'rest/messages/thread';
    $data    = array(
                    'type' => 'org.celstec.arlearn2.beans.run.Thread',
                    'name' => $name,
                    'runId' => $runId
                );
    $results = callARLearnAPI('POST', $url, json_encode($data), $usertoken);
    return $results;
}

/**
 * Get default thread given a run
 * @param $usertoken the ARLearn user token to append to the app key when sending the onBehalfOf token (as created with function createARLearnUserToken)
 * @param $runid the ARLearn Run id to get data from.
 * @return false, if the attempt failed, else the response data from the ARLearn service call (will be a json string).
 */
function getARLearnRunDefaultThread($usertoken, $runid)
{
    global $serviceRootARLearn;

    $url     = $serviceRootARLearn . 'rest/messages/thread/runId/' . $runid . '/default';
    $results = callARLearnAPI('GET', $url, '', $usertoken);
    return $results;
}

/**
 * Get all threads given a run
 * @param $usertoken the ARLearn user token to append to the app key when sending the onBehalfOf token (as created with function createARLearnUserToken)
 * @param $runid the ARLearn Run id to get data from.
 * @return false, if the attempt failed, else the response data from the ARLearn service call (will be a json string).
 */
function getARLearnRunThreads($usertoken, $runid)
{
    global $serviceRootARLearn;

    $url     = $serviceRootARLearn . 'rest/messages/thread/runId/' . $runid;
    $results = callARLearnAPI('GET', $url, '', $usertoken);
    return $results;
}

/**
 * Get Messages for the given thread
 * @param $usertoken the ARLearn user token to append to the app key when sending the onBehalfOf token (as created with function createARLearnUserToken)
 * @param $threadId the ARLearn Thread id to get data from.
 * @return false, if the attempt failed, else the response data from the ARLearn service call (will be a json string).
 */
function getARLearnThreadMessages($usertoken, $threadId, $args=array())
{ 
    global $serviceRootARLearn;

    $url     = $serviceRootARLearn . 'rest/messages/threadId/' . $threadId;
    if (is_array($args)) {
      $url    .= '?' . implode('&amp;', array_map('__urlify', array_keys($args), $args));
    }
    $results = callARLearnAPI('GET', $url, '', $usertoken);
    return $results;
}

/**
 * Get Channel API token for a given user
 * @param $usertoken the ARLearn user token to append to the app key when sending the onBehalfOf token (as created with function createARLearnUserToken)
 * @param $threadId the ARLearn Thread id to get data from.
 * @return false, if the attempt failed, else the response data from the ARLearn service call (will be a json string).
 */
function getARLearnChannelAPIToken($usertoken)
{
    global $serviceRootARLearn;

    $url     = $serviceRootARLearn . 'rest/channelAPI/token';
    $results = callARLearnAPI('GET', $url, '', $usertoken);
    return $results;
}

