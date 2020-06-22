<?php


abstract class Move {

    protected Cell $from;
    protected Cell $to;

    public function __construct(Cell $from, Cell $to) {
        $this->from = $from;
        $this->to = $to;
    }

    public abstract function execute(): void;

    public abstract function undo(): void;

    public function threatens_the_king(): bool {
        return false;
    }

}