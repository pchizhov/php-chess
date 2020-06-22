<?php

require_once 'game/moves/Move.php';


abstract class PieceManager {

    protected ChessPiece $piece;
    protected Cell $cell;
    protected bool $exists;

    public function __construct(ChessPiece $piece, Cell $cell) {
        $this->piece = $piece;
        $this->cell = $cell;
        $this->exists = true;
        $this->cell->set_piece($this);
    }

    public abstract function get_moves(): array;

    public function get_color(): int {
        return $this->piece->get_color();
    }

    public function used(): bool {
        return $this->piece->used();
    }

    public function is_a_king(): bool {
        return $this->piece->is_a_king();
    }

    public function capture(): void {
        $this->exists = false;
    }

    public function put_back(): void {
        $this->exists = true;
    }

}