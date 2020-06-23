<?php


abstract class ChessPiece {

    protected int $color;
    protected bool $used;

    public function __construct(int $color) {
        $this->color = $color;
        $this->used = false;
    }

    abstract function to_move(): array;

    public function used(): bool {
        return $this->used;
    }

    public function get_color(): int {
        return $this->color;
    }

    public abstract function get_piece_type(): int;

}