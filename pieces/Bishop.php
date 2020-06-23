<?php

require_once 'ChessPiece.php';


class Bishop extends ChessPiece {

    function to_move(): array {
        return [[1, 1], [1, -1], [-1, 1], [-1, -1]];
    }

    public function get_piece_type(): int {
        return PieceEnum::BISHOP;
    }
}