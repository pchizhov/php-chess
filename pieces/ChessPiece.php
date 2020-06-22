<?php


abstract class ChessPiece {

    protected int $color;
    protected bool $used;

    public function __construct(int $color) {
        $this->color = $color;
        $this->used = false;
    }

    abstract function to_move(): array;

    public function is_a_king(): bool {
        return false;
    }

    public function used(): bool {
        return $this->used;
    }

    public function get_color(): int {
        return $this->color;
    }

}