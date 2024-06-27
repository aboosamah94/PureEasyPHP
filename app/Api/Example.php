<?php

class Example
{
    public static function get()
    {
        return [
            'status' => 'success',
            'data' => 'This is example data from a GET request.'
        ];
    }

    public static function post()
    {
        return [
            'status' => 'success',
            'data' => 'This is example data from a POST request.'
        ];
    }

    public static function put()
    {
        return [
            'status' => 'success',
            'data' => 'This is example data from a PUT request.'
        ];
    }

    public static function delete()
    {
        return [
            'status' => 'success',
            'data' => 'This is example data from a DELETE request.'
        ];
    }

    public static function patch()
    {
        return [
            'status' => 'success',
            'data' => 'This is example data from a PATCH request.'
        ];
    }
}
