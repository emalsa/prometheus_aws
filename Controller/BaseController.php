<?php

class BaseController {

  /** Send API output.
   *
   * @param  mixed  $data
   * @param  string  $httpHeader
   */
  protected function sendOutput($data, $httpHeaders = []) {
    header_remove('Set-Cookie');
    if (is_array($httpHeaders) && count($httpHeaders)) {
      foreach ($httpHeaders as $httpHeader) {
        header($httpHeader);
      }
    }
    echo $data;
    exit;
  }

}