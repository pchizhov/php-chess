<?php

require_once 'MoveEnum.php';

abstract class Move {

    protected Cell $from;
    protected Cell $to;

    public function __construct(Cell &$from, Cell &$to) {
        $this->from = $from;
        $this->to = $to;
    }

    public abstract function execute(): void;

    public abstract function undo(): void;

    public abstract function to_json();

    public abstract function get_type(): int;

    public function get_from(): Cell {
        return $this->from;
    }

    public function get_to(): Cell {
        return $this->to;
    }

    public function threatens_the_king(): bool {
        return false;
    }

}