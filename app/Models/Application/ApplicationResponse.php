<?php

namespace App\Models\Application;

use CodeIgniter\Model;

class ApplicationResponse extends Model {
    public int $code;
    public string | array | object $response;
    public ?string $description;

    public function __construct(int $code, string | array | object $response, ?string $description = null) {
        $this->code = $code;
        $this->response = $response;
        $this->description = $description;
    }

    public function __toString() : string {
        if (is_null($this->description)) {
            if (is_array($this->response)) {
                return json_encode([
                    "code" => $this->code,
                    "response" => json_encode($this->response)
                ]);
            }
            return json_encode([
                "code" => $this->code,
                "response" => $this->response
            ]);
        } else {
            if (is_array($this->response)) {
                return json_encode([
                    "code" => $this->code,
                    "response" => json_encode($this->response),
                    "description" => $this->description
                ]);
            }
            return json_encode([
                "code" => $this->code,
                "response" => $this->response,
                "description" => $this->description
            ]);
        }
    }
}



?>