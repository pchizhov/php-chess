<?php

require_once 'ChessPiece.php';


class Pawn extends ChessPiece {

    public function to_move(): array {
        $possibleMoves = [[$this->get_direction(), 0]];
        if (!$this->used) {
            array_push($possibleMoves, [$this->get_direction() * 2, 0]);
        }
        return $possibleMoves;
    }

    public function to_capture(): array {
        return [[$this->get_direction(), 1], [$this->get_direction(), -1]];
    }

    private function get_direction(): int {
        return pow(-1, $this->color);
    }

    public function get_piece_type(): int {
        return PieceEnum::PAWN;
    }

}
