<?php

class SendController {

  public const MAX_FILES = 10;

  public const URL = 'https://prometheus.nicastro.io/api/check_item/update';

  // public const URL = 'https://c348bb49-5218-4de9-ba34-732f9a0f2106.mock.pstmn.io';

  protected string $type;

  protected string $checkId;

  protected string $checkItemId;

  protected string $fileContent;

  protected string $filePath;

  /**
   * Sends the processed data to Prometheus.
   */
  public function send(): void {
    try {
      $this->filePath = '/var/www/html/processed/url';
      $filenames = array_diff(scandir($this->filePath), ['.', '..']);
      if (empty($filenames)) {
        print_r('No files');
      }
      $count = 0;
      foreach ($filenames as $filename) {
        if (!str_ends_with($filename, '.txt')) {
          continue;
        }
        if ($count >= self::MAX_FILES) {
          break;
        }

        $this->getFilenamePart($filename);
        $fileContent = file_get_contents("$this->filePath/$filename");
        if (!$fileContent || !is_string($fileContent)) {
          print_r('File content bad.');
          return;
        }
        $this->fileContent = $fileContent;
        $resp = $this->dispatch();
        if (!$this->validateResponse($resp)) {
          print_r('Response bad.');
          return;
        }
        if (!$this->moveFile($filename)) {
          print_r('File could not be moved.');
        }
        $count++;
      }
      print_r('Finished.');
      return;
    }
    catch (Exception $e) {
      echo 'Something bad happened';
      return;
    }

  }

  /**
   * Dispatch POST request.
   *
   * @return string
   *   The response.
   */
  protected function dispatch(): string {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, self::URL);
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    $headers = [
      'Accept: application/json',
      'Content-Type: application/json',
    ];
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $data = $this->buildResponseData();
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    $resp = curl_exec($curl);
    curl_close($curl);

    return $resp;
  }

  /**
   * Builds the POST data.
   *
   * @return array
   *   The POST data as array.
   */
  protected function buildResponseData(): array {
    return [
      'check_id' => $this->checkId,
      'check_item_id' => $this->checkItemId,
      'type' => $this->type,
      'cloud_url' => gethostname(),
      'response' => base64_encode($this->fileContent),
    ];
  }

  /**
   * Explodes the filename in parts.
   *
   * @param $filename
   *   The filename.
   */
  protected function getFilenamePart(string $filename): void {
    $filename_parts = explode('_', $filename);
    $this->type = $filename_parts[1];
    $this->checkId = $filename_parts[2];
    $this->checkItemId = str_replace('.txt', '', $filename_parts[3]);
  }

  /**
   * Moves the filename to the done directory.
   *
   * @param  string  $filename
   *   The filename.
   *
   * @return bool
   */
  protected function moveFile(string $filename): bool {
    return rename("$this->filePath/$filename", "$this->filePath/done/$filename");
  }

  /**
   * Validates the response after submitting.
   *
   * @return bool
   */
  protected function validateResponse(): bool {
    return TRUE;
  }

}