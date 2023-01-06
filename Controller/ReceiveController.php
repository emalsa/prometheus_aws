<?php

class ReceiveController extends BaseController {

  protected stdClass $postData;

  /**
   * Receive the request and creates the json file to process.
   */
  public function init(): void {
    $postData = file_get_contents('php://input');
    if (empty($postData)) {

    }
    $this->postData = json_decode($postData);
    if (!$this->validateRequest()) {

    }
    if (!$this->validateData()) {

    }
    $filenameJson = $this->getFilename();
    try {
      if (file_put_contents("./processing/$filenameJson", json_encode($this->postData, JSON_PRETTY_PRINT))) {
        echo 'Ok';
        return;
      }
      echo 'Could not create file.';
      return;
    }
    catch (Exception $e) {
      echo 'Something bad happened';
      return;
    }

    //    $this->sendOutput(json_encode(array('error' => 'This is bad')),
    //      array('Content-Type: application/json', 'HTTP/1.1 422 Unprocessable Entity')
    //    );
  }

  /**
   * Creates the filename string.
   *
   * @return string
   * The filename.
   */
  protected function getFilename(): string {
    return $this->postData->type . '_' . $this->postData->check_id . '_' . $this->postData->check_item_id . '.json';
  }

  /**
   * Validates the request.
   *
   * @return bool
   */
  protected function validateRequest(): bool {
    return TRUE;
  }

  /**
   * Validates the data from the request.
   *
   * @return bool
   */
  protected function validateData(): bool {
    return TRUE;
  }

}