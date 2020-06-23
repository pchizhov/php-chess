<?php


abstract class ChessPiece {

    protected int $color;
    protected bool $used;

    public function __construct(int $color, bool $used = false) {
        $this->color = $color;
        $this->used = $used;
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