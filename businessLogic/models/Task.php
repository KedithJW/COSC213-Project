<?php
class Task {
  private $name;

  public function __construct($name) {
    $this->name = $name;
  }

  public function getTask(): string {
    return $this->name;
  }
}
