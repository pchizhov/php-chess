<?php

require_once 'ChessPiece.php';


class King extends ChessPiece {

    function to_move() : array {
        return [[0, 1], [1, 0], [-1, 0], [0, -1], [-1, 1], [1, -1], [-1, -1], [1, 1]];
    }

    public function get_piece_type(): int {
        return PieceEnum::KING;
    }
}