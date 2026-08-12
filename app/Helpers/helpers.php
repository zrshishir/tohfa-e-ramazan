<?php

/**
 * Return success response
 */
function success_response($response)
{
    return response()->json([$response], 200);
}
