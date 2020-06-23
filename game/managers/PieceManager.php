<?php

require_once 'game/moves/Move.php';


abstract class PieceManager {

    protected ChessPiece $piece;
    protected Cell $cell;
    protected bool $exists;

    public function __construct(ChessPiece $piece, Cell &$cell) {
        $this->piece = $piece;
        $this->cell = $cell;
        $this->exists = true;
        $this->cell->set_piece($this);
    }

    public function get_moves(bool $to_check = true): array {
        $result = [];
        foreach ($this->all_moves() as $move) {
            if (!$to_check or $to_check and $this->check_move($move)) {
                array_push($result, $move);
            }
        }
        return $result;
    }

    protected abstract function all_moves(): array;

    private function check_move(Move &$move) {
        $move->execute();
        $res = !Game::under_check($this->get_color());
        $move->undo();
        return $res;
    }

    public function set_cell(Cell &$cell): void {
        $this->cell = $cell;
    }

    public function get_color(): int {
        return $this->piece->get_color();
    }

    public function used(): bool {
        return $this->piece->used();
    }

    public function exists(): bool {
        return $this->exists;
    }

    public function capture(): void {
        $this->exists = false;
    }

    public function put_back(): void {
        $this->exists = true;
    }

    public function get_piece_type(): int {
        return $this->piece->get_piece_type();
    }

}