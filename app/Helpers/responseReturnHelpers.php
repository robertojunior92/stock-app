<?php

function returnWithSuccess($data = false)
{
    $res = [];

    if (is_array($data)) {
        $res = $data;
    } elseif (is_string($data)) {
        $res["message"] = $data;
    }

    $res["success"] = true;

    return $res;

}

function returnWithError($data = false)
{
    $res = [];

    if (is_array($data)) {
        $res = $data;
    } elseif (is_string($data)) {
        $res["messages"] = [$data];
    } elseif (is_object($data)) {
        $res["messages"] = $data;
    }

    $res["success"] = false;

    return $res;
}

function returnErrorPage($errorCode = 403)
{
    return response(view("errors/{$errorCode}"), $errorCode);
}

function returnAPI($success, $content, $statusCode, $page = null)
{
    $result = [
        "success" => $success,
        "content" => $content
    ];

    if (is_array($content)) {
        $result['total'] = count($content);
    }

    if (isset($page) && !is_null($page)) {
        $result["page"] = (int) $page;
    }

    return response()->json($result, $statusCode);
}

function vueComponent($componentName) {
    return file_get_contents(public_path("js/vue-components/{$componentName}.vue"));
}

function returnAndDie($content)
{
    response()->json([
        "success" => false,
        "content" => $content
    ], 500)->send();
    die();
}
